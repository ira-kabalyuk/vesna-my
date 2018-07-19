<?php

/* 
Апи визуального редактора

*/




class Smt_core {


function route(){


	$this->_responce($this->image_upload());

}

function _responce($ret){
	global $Core;

	$Core->ajax_get($ret);
}



function image_upload(){
	$json=_posts('json');
	$src=explode("/", _posts('src'));
	$keys="smt";
	$set=parse_jar($json);
	if(count($set)<3) return "";

	$arg=array('json'=>$json,'div'=>"smt",'img'=>end($src),'path'=>$set['path'],'prefix'=>$keys.'_','class'=>"w300");
	 	
	 $upload=Com_upload_core::get_ajax_form($arg);
	
	return $upload;




}

}

$mod=new Smt_core();
$mod->route();
