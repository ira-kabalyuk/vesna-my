<?php
class Com_news_tags_admin extends Tab_elements{
  var $id;
  var $pid=0;
  var $TB;
  var $mp;
  var $conf;
 
  function __construct($table){
   global $mid;
    $this->TB=$table;
    $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
    //$this->initialize();
    
     }
		 
	function Start($mod){
		global $htm,$Core;
		$this->conf=Com_mod::load_conf($mod);
		$this->base_mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		$this->id=_getn('el_id');
		$act=_get('act');
		$action=_post('action');
		$this->pid=$Core->db->value("select parent_id from mods where mods='$mod'");
		$this->parent_id=$this->pid;
		
if($act=='list'){
     $this->_list();

}elseif($act=='edit'){
     $this->_edit();
}elseif ($act=='onoff'){
   $Core->ajax_get($this->onoff());
}elseif ($act=='place'){
   $Core->ajax_get($this->place(_getn('sort')));
}elseif($act=='save'){
	$this->_save();
	$this->_list();
}elseif($act=='get_data'){
    	$this->_get_data();	
}elseif ($act=='delete'){
  
    $this->_del();
    $this->_list();
}else{
	$this->_list();
}
}
function _list(){
	global $htm,$db;
	$htm->external("EXT_ADD",$this->load_tpl("list.tpl"));
	if(AJAX)	$htm->src($this->load_tpl("list.tpl"));
	$ul=new Com_ul;
	$ul->init($this->load_tpl("list.xml"),"tags");
	$ul->add_head();
	
	//$ul->toolset('onof,edit,del');
	//$ul->maprow($db->select("select * from ".$this->TB." order by sort"));
	$htm->assign('NEWSLIST',$ul->get_ul());	
	
}

function _edit(){
	global $db,$htm;
	$htm->src($this->load_tpl("edit.tpl"));
	$in=new Mods_setup_core();
	$in->load_set($this->load_tpl("fields.xml"));
	if($this->id!=0){
	$in->add_var($db->get_rec($this->TB." where id=".$this->id));
	$in->add_var($db->get_rec($this->TB." where id=".$this->id));
	}
	$htm->assign(array(
	'FIELDS'=>$in->get_form(),
	'EID'=>$this->id
	));
	
	
}


function _del(){
	global $db;
	$this->delete_element();
	//$news=$db->select("select id,img from news where rubric_id=".$this->id);
	//foreach($news as $n) $this->del_news($n);
}



function _save(){
	global $db;
	$fn=array("title","link");
	$fs=array("seo_t","seo_k","seo_d");
	$data=array();
	$seo=array();
	$new=false;
	$data=array('parent_id'=>$this->pid);
	
	
	foreach($fn as $f)
	$data[$f]=_posts($f);
	
	foreach($fs as $f)
		$seo[$f]=_posts($f);
	
	if($this->id==0){
		$this->id=$db->getid($this->TB,'id',1);
		$data['id']=$this->id;
		$new=true;
	}
	$seo['id']=$this->id;
	
	if($new){
		$sql=$db->sql_insert($this->TB,"",$data);
	}else{
	//	$db->execute("delete from news_seo where id=".$this->id);
		$sql=$db->sql_update($this->TB,"",$data," where id=".$this->id);
	}
	//$db->execute($db->sql_insert("news_seo","",$seo));
	$db->execute($sql);
	
}

 function load_tpl($tpl){
 	if(is_file($this->mp.$tpl)) 
 			return $this->mp.$tpl;
 		return $this->base_mp.$tpl;
 }

 function _get_data(){
	$sql="select id, title,is_hidden, sort,link,slug from news_tag where parent_id={$this->pid} order by sort";
	$this->get_data($sql);
}


}
 
