<?php
class Com_comment_core{
	var $type=1;
	var $mp;
	var $tb="com_comment";
	var $pid=0;
	var $u;
	
	function __construct(){
		global $db;
		$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		$this->u=$db->hash("select id, name from users");
		
	}
	function Start(){
		global $Core;
		$act=_get('act');
		$this->type=_getn('type');
		$this->pid=_getn('pid');
		if($act=='save'){
			$Core->ajax_get($this->_save());
			
		}
		
	}
	
	
	function _list($pid){
		global $db, $htm;
		//$this->u=$db->hash("select id, name from users");
		$ret=file_get_contents($this->mp."list.tpl");
		$com="";
		$res=$db->select("select * from {$this->tb} where parent_type={$this->type} and parent_id=$pid order by data_add desc");
		foreach($res as $r)
		$com.=$this->get_comment($r);
		
		$htm->assvar(array(
		'COMLIST'=>$com,
		'MOD_LINK'=>ADMIN_CONSOLE."/?com=comment&act=save&type=".$this->type."&pid=".$pid
		));
		$htm->_var($ret);	
		return $ret;
	
		
	}
	
	function get_comment($r){
			return '<div class="item"><span>'.$this->u[$r['author']].'</span><span class="data">'.
			date("d.m.y H:i",$r['data_add']).'</span><div>'.nl2br($r['descr']).'</div></div>';
		
	}
	
	
	function _save(){
		global $db, $Core;
	
		$data=array();
		$data['parent_id']=$this->pid;
		$data['parent_type']=$this->type;
		$data['author']=$Core->user_id;
		$data['data_add']=time();
		$data['descr']=_posts('comment');
		$db->execute($db->sql_insert($this->tb,"",$data));
		return $this->get_comment($data);
		
	}
	static function del_parent($pid,$type){
		global $db;
		$db->execute("delete from com_comment where parent_id=".$pid." and parent_type=".$type);
	}
}