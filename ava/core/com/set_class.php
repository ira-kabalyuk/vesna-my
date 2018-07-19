<?php
class Com_set{

var $in;
var $sets;
var $ok;

	function load_set($file){
		$this->ok=false;
		if(!is_file($file)) return;
 		$xml = simplexml_load_file($file);

 		foreach ($xml->a as $sets){
 			$name=(string) $sets->attributes()->name;
 			//var_dump($sets);
			$this->sets[$name]=array();
			$this->sets[$name]['attr']=$this->get_xml_atributes($sets);
			$this->sets[$name]['fields']=array();
		
			foreach($sets->set as $set){
			    $sname=(string) $set->attributes()->name;
				$this->sets[$name]['fields'][$sname]=$this->get_xml_atributes($set);
			}
			
    		$this->ok=true;
		}
		

	}


function get_xml_atributes($set){
	$pars=array();
	foreach($set->attributes() as $a=>$b) {
		$a=(string) $a;
		$b=(string) $b;
	  $pars[$a]= $b;
}
return $pars;    
}



//извлечение групп мультиданых
function get_data($table,$where){
	global $db;
	if(!$this->ok) return;
	$this->in=array('keys'=>array(),'vals'=>array());

	foreach($this->sets as $setname=>$set){
		$keys=array();

		foreach($set['fields'] as $fname ){
			$n=$setname."_".$fname['name'];
			$hash=$db->hash("select `multy_id`, `info` from $table where $where and `key`='$n' order by multy_id asc");
			$this->in['vals'][$n]=$hash;
			$keys=array_merge($keys,array_keys($hash));

		}
		// получим все уникальные ключи набора мультиданных
		$this->in['keys'][$setname]=array_unique($keys);
	}
	//var_dump($this->in);	
}

// генерация формы для админки
function get_form(){
	$ret="";
	if(!$this->ok) return "not find multydata";

	$setkeys=array_keys($this->sets);
	foreach($setkeys as $sname){
		$mid=0;
	foreach($this->in['keys'][$sname] as $id){
		$ret.=$this->get_form_set($sname,$id);
		$mid=max($mid,$id);
	}
		$ret.=$this->get_form_set($sname,0,$mid);


		}
	return $ret;
	
	}

function get_form_set($sname,$id,$mid=0){
	$set=$this->sets[$sname];
	$wrap=false;
	if($id==0){
		$id="{%id%}";
		$wrap=true;
	}
	$ret="<fieldset class=\"accord\" lang=\"$sname\" ><legend>".$set['attr']['title']." (<b>$id</b>)</legend>";
	$ret.='<input type="hidden" name="'.$sname.'_id['.$id.']" value="'.$id.'">';	
	$ret.='<div class="del" rel="'.$id.'"><label class="c-red">Удалить</label><input type="checkbox" name="'.$sname.'_del['.$id.']" value="1"><div>';	
	foreach($set['fields'] as $name=>$fname )
		$ret.="<div><label>".$fname['title']."</label>".$this->get_inp($sname,$fname,$id)."</div>";
	
	$ret.="</fieldset>";
	if($wrap) $ret='<script type="text/template" data-mid="'.$mid.'" rel="fieldset" id="'.$sname.'-tpl">'.$ret.'</script>';
	return $ret;
}

// генерация полей ввода
function get_inp($setname,$fname,$id){
	
		return Input::get_input(
			$fname,
			$setname."_".$fname['name']."[".$id."]",
			$this->in['vals'][$setname."_".$fname['name']][$id]
			);
	
}


function save_post($data, $table="", $sname=""){
	global $db;

	if(!$this->ok) return;
	$where =" where ";
	foreach ($data as $key=>$val)
		$where.=$key."='".$val."' and ";
	
	if($sname==""){
		$sname=array_keys($this->sets);	
	}elseif(is_string($sname)){
		$sname=explode(",",$sname);	
	} 

	foreach($sname as $name){
		$ids=_postn_ar($name."_id");
		if(count($ids)==0) continue;
		$w=$where." `key` like '".$name."_%'";
		
		foreach($ids as $id){
			if($id==0) continue; 
			$db->execute("delete from $table  ".$w." and multy_id=".$id);
			if(intval($_POST[$name."_del"][$id])==1){
				continue;	
			} 

			foreach($this->sets[$name]['fields'] as $set){
				$fname=$name."_".$set['name'];
				$data['info']=trim(stripslashes($_POST[$fname][$id]));
				$data['multy_id']=$id;
				$data['key']=$fname;
			
			if($id==0)
				$data['multy_id']=$db->getid($table.$w,'multy_id',1);
			
			$sql=$db->sql_insert($table,"",$data);
			$db->execute($sql);
		}
	}
}

}
}