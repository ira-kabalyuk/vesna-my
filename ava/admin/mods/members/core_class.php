<?php

class Mods_members_core extends Tab_elements{
	use Meta;
	var $mp;
	var $TB;
	var $id;
	var $modlink;
	var $mod;
	var $tb_metadata="members_data";


function Start($mod){
	global $Core;
	$this->base_mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
	$act=_gets('act');
	$this->TB="members";
	$this->mod=$mod;
	$this->conf=Com_mod::load_conf($mod);
	$this->id=_getn('el_id');
	$Core->htm->external("EXT_ADD",$this->mp."admin.tpl");
	$Core->htm->external("EXT_RAZD",$this->load_tpl("submenu.tpl"));
	$Core->htm->addscript("js","/inc/ajaxupload.js");
	$Core->htm->assign(array(
		"MOD"=>$this->mod,
		'EID'=>$this->id,
		"MOD_LINK"=>$this->modlink
		));


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
		$this->_edit();
	}elseif ($act=='delete'){
	    $this->_del();
    	$this->_list();
	}elseif($act=='upload'){
    	$Core->json_get($this->upload());
	}else{
		$this->_list();
	}

}

function _list(){
	global $htm,$db;
	$htm->external("EXT_ADD",$this->load_tpl("list.tpl"));
	if(AJAX)	$htm->src($this->load_tpl("list.tpl"));
	//$type=$db->hash("select id,title from member_type");
	$ul=new Com_ul;
	$ul->init($this->load_tpl("list.xml"));
	$ul->add_head();
	$ul->toolset('onof,edit,del');
	$res=$db->select("select id, name, is_hidden, img,type, paid,email from {$this->TB} order by name");
	//$cities=$db->hash("select id,title from news where parent_id=11");
	foreach($res as $r){
		$r['img']='<img src="/uploads/members/'.$r['img'].'" width="50">';
		//$r['city']=$cities[$r['city']];
		//$r['type']=$type[$r['type']];
		$r['payd']=($r['paid']==0 ? ' ':'<span class="green-box">OK</span>');
		$ul->add_row($r);
	}
	$htm->assign('NEWSLIST',$ul->get_ul());	
	
}

function _edit(){
	global $db,$htm;
	$htm->src($this->load_tpl("edit.tpl"));
	$in=new Mods_setup_core();
	$in->load_set($this->load_tpl("fields.xml"));
	$in->parent_id=$this->id;
	$in->rel=$this->id;
	if($this->id!=0){
		$var=$db->get_rec($this->TB." where id=".$this->id);
		$var['passw']="";
		$in->add_var($var);
		$in->add_var($this->get_meta($this->id));
    	$in->modlink=$this->modlink."&el_id=".$this->id;
    	$this->payd_history($this->id);
	}

	$htm->assign(array(
	'FIELDS'=>$in->get_form(),
	'EID'=>$this->id
	));
	
	
}


function _del(){
	global $db;
	$this->delete_element();
	$this->del_meta();
}



function del_meta(){
	global $db;
	$db->execute("delete from members_data where parent_id=".$this->id);
}

function _save(){
	global $db;
	$in=new Mods_setup_core();
    $in->load_set($this->load_tpl("fields.xml"));
    $data=$in->get_post_sets('face');
    $meta=$in->get_post_sets('meta');
     
	$new=false;
	
	unset($meta['img']);
	

	if($data['passw']=="") unset($data['passw']);
	
	
	if($this->id==0){
		$this->id=$db->getid($this->TB,'id',1);
		$data['id']=$this->id;
		$new=true;
	}

	$this->save_meta($this->id,$meta);
	
	
	
	if($new){
		$sql=$db->sql_insert($this->TB,"",$data);
	}else{
		$sql=$db->sql_update($this->TB,"",$data," where id=".$this->id);
	}

	$db->execute($sql);
	
}

function upload(){
        //создадим объект интерфейса формы настроек
        $in=new Mods_setup_core();
        $key=_gets('div');
        $in->load_set($this->load_tpl("fields.xml"));
        $in->parent_id=$this->id;
        $meta_key=$in->sets['meta']['fields'];
        if(!in_array($key,$meta_key))
            return array('ok'=>false,'error'=>'field not found');
            $field=$in->in[$key];
            $meta=array();
            $arg=parse_jar($field['json']);
            $arg['div']=$key;
            $arg['fname']=$key;
            $arg['name']=$key."-".$this->id;
            $arg['prop']=($arg['prop']=="0" ? false:true);
            
            if(isset($arg['prew_x'])){
                // создаем превью
                 $prew=array(
            'kat'=>$arg['path'],
            'rx'=>$arg['prew_x'],
            'ry'=>$arg['prew_y'], 
            'pref'=>'s_',
            'prop'=>($arg['prew_p']=="1" ? true : false));
                 $arg['prew']=$prew;
            }

            $ok=Com_upload_core::ajax_upload($arg);
            if($ok['ok']){
            $ok['path']=$arg['path']; 
            $this->save_img($key,$ok['fname']);
          }
            return $ok;

    }

function load_tpl($tpl){
 	if(is_file($this->mp.$tpl)) 
 			return $this->mp.$tpl;
 		return $this->base_mp.$tpl;
 }

 function save_img($key,$img){
 	global $db;
 	$data=array("img"=>$img);
 	$db->execute($db->sql_update($this->TB,"",$data," where id=".$this->id));
 }



 function payd_history($id){
 	global $db,$htm;
 	//$res=$db->select("select date_add, trans_id from payd_log where user_id=$id order by date_add");
 	$res=$db->select("select metavalue from members_data where parent_id=$id and metakey='paypal_tid'");
 	foreach($res as $r){
 		//$r['date_add']=date("d.m.Y H:i",$r['date_add']);
 		$r['trans_id']=$r['metavalue'];
 		$htm->addrow('PAYD_ROW',$r);
 	}
 }

}