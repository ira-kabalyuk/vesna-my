<?
class Ormu extends Ormb{
	var $charset="utf8";
	var $engine="MyISAM";
	var $debug=false;
	var $log=array(); 
	/**
	 * Создание базы
	 * merge = true только добавление полей false - удаление отсутсвующих в схеме
	 * len = (+|-|0) 
	 * + только увеличивать длину полей если не соотв. макету
	 * - увеличивать и уменьшать длину полей если не соответствует макету
	 * n - не изменять длину полей
	 * */
		function 	create_database($merge=true,$len='n'){
		global $Log;
		foreach($this->tables as $key=>$table){
			if($this->check_table($key)){
				//table exists
				
				if($this->check_table_structure($key,$merge,$len)){
					$Log[]=" table ".$key." exist";
				}else{
					$ok=$this->alter_table($key);
					if(trim($ok)==''){
						$Log[]=" alter ".$key." ok!";
						}else{
							$Log[]=" alter ".$key." ERROR ".$ok;
						}
				}
			}else{
				$ok=$this->create_table($key);
			if(trim($ok)==''){
				$Log[]=" create ".$key." ok!";
			}else{
				$Log[]=" create ".$key." ERROR ".$ok;
			}
			}
		}
	
	}
	function update_database(){
		global $db;
		$chek=0;
		foreach($this->tables as $name=>$tab){
			$chek++;
			$sql="";
			$c=0;
			$log=array();
			if($this->check_table($name)){
				$sql=$this->check_table_structure($name,true,'+');
				if($sql!="") $log[]=" обновлена таблица $name ";
			}else{
				$sql=$this->create_table($name);
				$log[]=" создана таблица $name ";
			}
			if($sql!=""){
				$c++;
				$db->execute($sql);
				if($this->debug) $log[]=$sql." ".mysql_error();
			} 
			
		}	
		return implode("<br>",$log)."<br> проверено таблиц:$chek обновлено:$c "; 
	}
	
	function create_table($name){
		global $db;
		$fields=array();
		$keys="";
		$sql="";
		foreach($this->fields[$name] as $field)
			$fields[]=$this->create_field($field);
		if(isset($this->tables[$name]['index'])){
			$ks=explode(",",$this->tables[$name]['index']);
				foreach($ks as $k)
					$keys.=", KEY `".$k."` (`".$k."`)";
			}
		$sql="CREATE TABLE `".$name."` (".implode(", ",$fields).$keys.") ENGINE ".$this->engine." DEFAULT CHARSET=".$this->charset.";";
			return $sql;
	}

	private function create_field($field){
	//	$c=array("char","varchat","text");
		$ret="`".$field['name']."` ".$field['type']
		.(isset($field['def']) ? " NOT NULL default '".$field['def']."'":" NOT NULL");
		return $ret;
	}
	
	function check_table($table_name){
   return mysql_num_rows(mysql_query("SHOW TABLES LIKE '$table_name'")); 
}

function check_table_structure($tname,$merge=false,$len='n'){
	global $db;
	$sfields=$this->fields[$tname];
	$act=array();
	$fl=array(); // найденные поля
	// получим список полей таблицы	
	$fields=$db->select("show fields from ".$tname);

	foreach($fields as $r){
		$fname=$r['Field'];
		$ftype=$r['Type'];
		$fkey=trim($r['Key']);
		$fl[]=$fname;
		// удаляем поле если не стоит режим обьединения

		if(!isset($sfields[$fname])){
			if(!$merge)$act[]="DROP `".$fname."`";
			continue;
			}
			
			$field=$sfields[$fname];
			preg_match("/([^(]+)\(([0-9]+)\)|/",$field['type'],$sm);
			preg_match("/([^(]+)\(([0-9]+)\)|/",$ftype,$dm);
			$sm[2]=intval($sm[2]);
			$dm[2]=intval($dm[2]);
			// сравним типы полей
				if($sm[1]!=$dm[1]){
					$act[]="MODIFY ".$this->create_field($field);
					// не совпадает длина поля
					}elseif($sm[2]!=$dm[2]){
						if($len=='n') continue;
						if($len=='+' && $sm[2]>$dm[2]) $act[]="MODIFY ".$this->create_field($field);
						if($len=='-' && $sm[2]<$dm[2]) $act[]="MODIFY ".$this->create_field($field);
					}
				}
			
			
			foreach($sfields as $field){
				if(!in_array($field['name'],$fl))
					$act[]="ADD ".$this->create_field($field);
			}			
			
			if(count($act)>0){
					return "alter table ".$tname." ".implode(", ",$act);
					}else{
						return "";
					}
		
	
}


	
}

?>