<?php
class Mods_users_core extends Admin_mode{
	var $id;
	var $mp;
	
	function __construct(){
		parent::__construct('users');
		$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		
	}
	
	function Start(){
		global $htm;
		$this->prepend();
	$this->id=_getn('el_id');
	$act=_get('act');
	$htm->assign('EID',$this->id);
	$htm->assign('MOD_LINK',"/smart/?mod=users");
	
	switch($act){
		
		case 'edit':
		$this->_edit();
		break;
		
		case 'save':
		$this->_save();
		break;

		case 'get_data':
		$this->get_data();
		break;
		
		case 'delete':
		$this->_del();
		$this->_list();
		break;
		
		default:
		$this->_list();
		
	}
	
		
	}
	
	function _save(){
		global $db;
		
		$new=false;
		if($this->id==0){
			$new=true;
			$this->id=$db->getid("users","id",1);
		}
		
		$in=new Mods_setup_core;
		$in->load_set($this->mp."fields.xml");
		$prof=$in->get_post_sets('profile');
		$com=$in->get_post_sets('com');
		
		if(trim($prof['passw'])==''){
		 unset($prof['passw']);
		 }else{
		 	$prof['passw']=md5(trim($prof['passw']));
		 }
		 $prof['login']==preg_replace("/[^(A-z)]/","",$prof['login']);
		 
		 if($new){
		 	$prof['id']=$this->id;
		 	$sql=$db->sql_insert("users","",$prof);
		 }else{
		 	$sql=$db->sql_update("users","",$prof," where id=".$this->id);
		 }
		 //echo $sql."<br>";
		$db->execute($sql);
		 
	 	

	    $this->add_user_right($com['rights'],0);
		//$this->add_user_right($com['rightr'],1);
		
		CashControl::clear('adminmenu'.$this->id);
		$this->_edit($in);
		
		
		
	}
	
	function _edit($in=false){
		global $db,$htm;
		
		$htm->external("EXT_ADD",$this->mp."tpl/edit.tpl");
		if(AJAX) $htm->src($this->mp."tpl/edit.tpl");
		if(!$in){
			$in=new Mods_setup_core;
			$in->load_set($this->mp."fields.xml");
		}
		$user=$db->get_rec("users where id=".$this->id);
		unset($user['passw']);
		$user['rights']=$db->vector("select id from users_right where user_id={$this->id} and type=0");
		
		$in->add_var($user);
		$htm->assign("FIELDS",$in->get_form());
		
		
	}
	
	function _list(){
		global $db,$htm;
		$htm->external('EXT_ADD',$this->mp."tpl/list.tpl");
		if(AJAX) $htm->src($this->mp."tpl/list.tpl");
		/*
		$ul=new Com_ul;
		$ul->init($this->mp."list.xml",'userlist');
		$ul->add_head();
		$ul->tool=true;
		//$ul->maprow($db->select("select id,login, name from users"));
		$htm->assign("USERLIST",$ul->get_ul());
		*/
	}

	function get_data(){
		global $Core;
		$res=$Core->db->select("select id,name,login,is_hidden,last_login from users");
	$data=array();
		foreach($res as $r){
			$r['last_login']=date("dm.m.Y H:i",$r['last_login']);
			$data[]=$r;
		}
		$Core->json_get(array('ok'=>true,'data'=>$data));
	}
	
	function add_user_right($vals, $key){
			global $db;
			$db->execute("delete from users_right where user_id={$this->id} and type=".$key);
//			$db->execute("delete from users_right where user_id={$this->id}");
			if($vals=='') return;
			$ar=explode(",",$vals);
			foreach($ar as $r){
			$r=intval($r);
			if($r!=0) 
			$db->execute("insert into users_right (user_id, id, type) values('".$this->id."','$r','$key')");
			//echo 	"insert into users_right (user_id, id, type) values('".$this->id."','$r','$key') <br>";
		
		}
	}
	function _del(){
		global $db;
			$db->execute("delete from users_right where user_id={$this->id} ");
			$db->execute("delete from users where id={$this->id} ");
	}
	
}