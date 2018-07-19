<?php
class Mods_Paymaster_admin extends Tab_elements{
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
		$this->base_mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		$this->conf=Com_mod::load_conf($mod);
		$this->id=_getn('el_id');
		$this->pid=$this->conf['parent_id'];
		$this->parent_id=$this->conf['parent_id'];

		$act=_get('act');
		$action=_post('action');
		
	if($act=='list'){
    	 $this->_list();
	}elseif($act=='edit'){
    	 $this->_edit();
	}elseif ($act=='onoff'){
   		$Core->ajax_get($this->onoff());

   	}elseif ($act=='get_data'){
         $this->_get_data();          	
	}else{
		$this->_list();
	}
}

function _list(){
	global $htm,$db;
	$htm->external("EXT_ADD",$this->load_tpl("list.tpl"));
    if(AJAX) $htm->src($this->load_tpl("list.tpl"));
    
    $htm->assign('NEWSLIST',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml"
	));
	
	
}

function _edit(){
	global $db,$htm;
	$htm->src($this->load_tpl("edit.tpl"));
	$in=new Mods_setup_core();
	$in->load_set($this->load_tpl("fields.xml"));
	$in->parent_id=$this->pid;
	$in->rel=$this->pid;
	if($this->id!=0){
		$in->add_var($db->get_rec("news_rubric where id=".$this->id));
		$in->add_var($db->get_rec("news_seo where id=".$this->id));
		$in->add_var($this->get_meta($this->id));
		$in->parent_id=$this->id;
    	$in->modlink=$this->modlink."&el_id=".$this->id;

	}
	$htm->assign(array(
	'FIELDS'=>$in->get_form(),
	'EID'=>$this->id
	));
	
	
}




function _get_data(){
    global $db;
    $res=$db->select("select * from {$this->TB} order by date_add desc");
	foreach($res as $r){
		$r['date']=date("d.m.Y H:i",$r['date_add']);
		
	}
    

    $limit=_getn('length');
    $start=_getn('start');
    $l="";
    if($limit!=0) $l="limit $start,".$limit;

   	$res=$db->select("select * from {$this->TB} order by date_add desc");
    $this->get_data($sql,$l);
}







 function load_tpl($tpl){
 	if(is_file($this->mp.$tpl)) 
 			return $this->mp.$tpl;
 		return $this->base_mp.$tpl;
 }
    

}
 
