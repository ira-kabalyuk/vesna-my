<?
/** класс для импорта-экспорта структуры базы в xml
 * parse_sheet($file) - читает файл структуры
 * create_sheet() - читает базу и создает массивы таблиц и полей
 * make_xml - преобразует структуру в xml
 * */
 
class Ormb{
	var $db=array(); // свойства базы
	var $tables=array(); // свойства таблиц
	var $fields=array(); // свойства полей таблиц
	
	// разбор xml-файла описания структуры базы
	function parse_sheet($file){
		$xml = simplexml_load_file($file);
		if(!is_object($xml)) return;
			foreach($xml->attributes() as $k=>$v)
			$this->db[$k]=(string) $v;
			
			foreach($xml->children() as $t){
				$a=$this->get_attr($t);
				$this->tables[$a['name']]=$a;
				$this->fields[$a['name']]=array();		
			foreach($t->children() as $f){
				$p=$this->get_attr($f);
				$this->fields[$a['name']][$p['name']]=$p;
	}
}
			
			
			
		}	
	
	private function get_attr($xml){
		$ret=array();
		foreach($xml->attributes() as $k=>$a)
		$ret[$k]=(string)$a;
		return $ret;
	}
	
	function create_sheet(){
		global $db;
		$table=$db->vector("SHOW TABLES");
		foreach($table as $t){
			$this->tables[$t]=array();
			$this->fields[$t]=array();
			$f=$db->select("show fields from ".$t);
			$key=array();
			
			foreach($f as $l){
					$m=explode("(",$l['Field']);
					$fa=array('name'=>$l['Field'],'type'=>$l['Type']);
					if($l['Key']!='') $key[]=$m[0];
					if($l['Default']!='') $fa['def']=$l['Default'];
					$this->fields[$t][$m[0]]=$fa;
			}
			$this->tables[$t]['name']=$t;
			if(count($key)) $this->tables[$t]['index']=implode(",",$key);
			
			
		}
		
	}
	
	function make_xml(){
		$xml="<db> \n";
		foreach ($this->tables as $t=>$ta){
			$xml.='<table name="'.$t.'"'.(isset($ta['index']) ? ' index="'.$ta['index'].'"':'').'>'." \n";
			foreach($this->fields[$t] as $fl)
				$xml.='  <field name="'.$fl['name'].'" type="'.$fl['type'].'"'.(isset($fl['def']) ? ' def="'.$fl['def'].'"':'').' />'." \n";
			
			$xml.="</table> \n";
		}
		$xml.="</db>";
		return $xml;
	}
	
	
}

?>