<?php
class Com_post{
		
static	function get_post($file){
		if(!is_file($file)) return array();
 		$xml = simplexml_load_file($file);
 		$fields=array();
 		foreach ($xml->a as $sets){
 			foreach($sets->set as $set){
			    $name=(string) $set->attributes()->name;
			    $fields[$name]=_posts($name);
			}
			return $fields;
    
}

	}
static function get_post_field(&$data,$fields){
	foreach($fields as $name=>$type){
		switch($type){
			case 'text':
			$data[$name]=_posts($name);
			break;
			
			case 'int':
			$data[$name]=_postn($name);
			break;
			
			case 'date':
			$data[$name]=data_to_int(_posts($name));
			break;
			
			case 'array':
			$data[$name]=array();
			foreach ($_POST[$name] as $key=>$val)
			$data[$name][$key]=$val;
			break;
			
			default:
			$data[$name]=_posts($name);
			
		}
	}
}

/**
 * преобразование POST в строковуый тег
 * 
 * */
static function get_post_tags($keys){
	

	foreach($keys as $key=>$post){
		$tags=$_POST[$post];
		if(is_array($tags)){
			foreach($tags as $t){
				$t=intval($t);
				if($t!=0) $ret.=$key."_".$t." ";
			}
		}else{
			$t=intval($tags);
				if($t!=0) $ret.=$key."_".$t." ";
		}
	}
	return $ret;
}
	
}