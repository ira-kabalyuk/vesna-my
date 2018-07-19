<?php
class User{
	public static  $mod;
	static $user;
	static $id;

	static function init(){
		global $Core;
		if(!isset(self::$id)){
			self::$id=$Core->link->uid['id'];
			self::$user=$Core->link->uid;
		} 
		//self::$mod=new Com_user();
	} 

	static function get_role($id=0){
		global $db;
		if($id==0) $id=self::$id;
		$role=$db->value("select metavalue from members_data where metakey='role' and parent_id=$id");
		if(trim($role)=="") return array();
			return explode(",",$role);

	}

	static function get_type(){
		return intval(self::$user['type']);
	}
	

}