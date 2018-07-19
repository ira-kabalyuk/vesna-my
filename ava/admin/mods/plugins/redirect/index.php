<?php
define('MOD_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
$htm->external("EXT_ADD",MOD_PATH."index.tpl");
$modlink=ADMIN_CONSOLE."/?mod=plugins&plu=".$plugin;
$htm->assign(array(
'MOD_LINK'=>$modlink,
));

$import=new LinkTab;
$import->Start();

class LinkTab{
	var $id;
	var $mp;
	var $TB='brand_redir';
	
	function Start(){
		$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		$act=_get('act');
		$this->id=_getn('el_id');
		
		switch($act){
			case 'list':
			$this->_list();
			break;
			
			case 'edit':
			$this->_edit();
			break;
			
			case 'onoff':
			$this->onof();
			break;
			
			case 'delete':
			$this->_del();
			$this->_list();
			break;
			
			case 'save':
			$this->_save();
			$this->_list();
			break;
			
			default:
			$this->_list();
		}
	}
	

	
	function _list(){
		global $db,$htm;
		$ul=new Com_ul;
		$ul->init($this->mp."list.xml");
    	$ul->toolset('onof,edit,del');
    	$ul->add_head();
    	$ul->maprow("select * from {$this->TB} order by id");
    	
		$htm->assign('LISTS',$ul->get_ul());
		
	}
	
	function _edit(){
		global $db,$htm;
		$htm->external("EXT_RAZD",MOD_PATH."edit.tpl");
		if(AJAX) $htm->src(MOD_PATH."edit.tpl");
 		$in=new Mods_skat_set();
 		$in->load_set($this->mp."fields.xml");
 		if($this->id!=0){
 			$var=$db->get_rec("{$this->TB} where id=".$this->id);
 			$in->add_var($var);
 		}
		
		$htm->assign(array(
        "PID"=>$this->id,
        'FORM_FIELDS'=>$in->get_form(),
        ));
		
	}
	
	function _save(){
		global $db;
		$new=false;
		$data=array();
		
		if($this->id==0){
			$new=true;
			$this->id=$db->getid($this->TB,'id',1);
		}
	$in=new Mods_skat_set();
	$in->load_set($this->mp."fields.xml");
	$in->get_post(true,$data);
	if($data['link']==''){
	$data['link']=$db->value("select link from skat_links where type=1 and parent_id=".$data['cat_id']).'/';
	$data['link'].=$db->value("select link from skat_links where type=3 and parent_id=".$data['brand_id']);
		}
		if($new){
			$data['id']=$this->id;
			$sql=$db->sql_insert($this->TB,"",$data);
		}else{
			$sql=$db->sql_update($this->TB,"",$data, "where id=".$this->id);	
		}	
	$db->execute($sql);
		
	}
	
	function _del(){
		global $db;
		$db->execute("delete from {$this->TB} where id=".$this->id);
	}
	
	function onof(){
		    global $db,$Core;
    $status=$db->value("select is_hidden from {$this->TB} where id=".$this->id);
    $status=($status==0 ? 1 : 0);
    $db->execute("update {$this->TB} set is_hidden=$status where id=".$this->id);
    $Core->ajax_get($status==1 ? 'off':'on');
		
	}
	
}