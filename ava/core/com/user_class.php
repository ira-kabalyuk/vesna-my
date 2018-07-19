<?php
class Com_user{
	var $user;
	var $id;

	function __construct(){
		global $Core;
		$this->user=$Core->db->get_recs("select id,`type`,`group` from members where id=".$Core->link->uid['id']);
		$this->id=$Core->uid['id'];
	}

	function get_role($id=0){
		global $db;
		if($id==0) $id=$this->id;
		$role=$db->value("select metavalue from members_data where metakey='role' and parent_id=$id");
		if(trim($role)=="") return array();
			return explode(",",$role);

	}

}