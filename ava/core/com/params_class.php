<?php
class Com_params{
	
	static function params_array($str){
		if(is_array($str)) return $str;
 		$ret=array();
 		if(!strpos($str,"_")) return $ret;
 		preg_match_all("/_([0-9]+)/",$str,$ret);
 		return $ret[1];
}

static function params_string($ar,$pref){
	$ret="";
	if(!is_array($ar)) return $ret;
	
	foreach($ar as $r)
	$ret.=$pref."_".$r." ";
	
	return $ret;	
}
	
}