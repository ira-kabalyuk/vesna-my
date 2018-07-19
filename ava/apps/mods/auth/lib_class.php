<?php
class Mods_auth_lib{


static function uid(&$uid=0){
	global $Core;

	if($Core->link->uid['id']==0) 
		return false ;
	$uid=$Core->link->uid['id'];
		return true;
}	

static function get_user_meta($key=''){
	global $Core, $db;
	$uid=$Core->link->uid;
	if($uid['id']==0) return ($key=='' ? "":array());
	$id=$uid['id'];
	
	if($key=='') 
		return $db->hash("select metakey, metavalue from members_data where parent_id=$id");

	if(preg_match("/([^\[]+)\[\]/",$key,$m)){
		
		$w="metakey LIKE '".$m[1]."[%'";
		$res=$db->select("select metakey, metavalue from members_data where parent_id=$id and $w");
			$ret=array();
		foreach ($res as $r){
			if(preg_match("/[^\[]+\[([0-9|a-z]+)\]/",$r['metakey'],$m))
				$ret[$m[1]]=$r['metavalue'];
		}
		return $ret;
	}else{
		return $db->value("select  metavalue from members_data where parent_id=$id and metakey='$key'");
	}
	
	
	
}

static function set_user_meta($key,$val){
	global $Core,$db;
	$uid=$Core->link->uid;
	if($uid['id']==0) return ;
	$id=$uid['id'];
	$db->execute("delete from members_data where metakey='$key' and parent_id=$id");
	if($val=="") return;
	$data=array('parent_id'=>$id,'metakey'=>$key,'metavalue'=>$val);
	$db->insert("members_data","",$data);

}







}