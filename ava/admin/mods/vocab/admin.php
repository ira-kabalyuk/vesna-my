<?php

class Langs_admin extends Tab_elements{
	var $TB="vocab";
	var $lng="";
	var $pid=0;
	var $mp;

	function Start(){
		global $htm,$Core,$db;
		$mod="vocab";
		$this->ln=_gets('ln');
		if($this->ln=='')
			$this->ln='ru';
		$title="Словарь";
		$this->name=_gets('name');
		$act=_gets('act');
		$id=_getn('el_id');
		if($id!=0)
			$this->name=$db->value("select name from vocab where id='$id'");
		

		$this->mp=dirname(__FILE__)."/";
		$modlink=ADMIN_CONSOLE."/?mod=".$mod."&ln=".$this->ln."&page=".intval($_GET['page']);
		$this->modlink=$modlink;

		$htm->external("EXT_ADD",$this->mp."admin.tpl");
		if(AJAX)
			$htm->src($this->mp."admin.tpl");

		$htm->assign(array(
			'fa_class'=>"fa-globe",
			'MOD'=>$mod,
			'MOD_LINK'=>$modlink,
			'MOD_TITLE'=>$title
 
		));
		
		switch ($act) {
			case 'save':
				# code...
				$this->_save();
				$this->_list();
				break;

			case 'save_new':
				# code...
				$this->add();
				$this->_edit();
				break;	

			case 'edit':
				$this->_edit();
				break;	

			/* 
			case 'upload':
           		$Core->json_get($this->upload());
           	break;
			*/
			case 'delete':
				$this->delete_element();
				$this->_list();
				break;

			case 'get_data':
				$Core->json_get(['ok'=>true,'data'=>$this->get_data("")]);	
			default:
				$this->_list();
				break;
		}
	}

function _edit(){
	global $htm,$db;
	$htm->external("EXT_ADD",$this->mp."edit.tpl");
	if(AJAX) $htm->src($this->mp."edit.tpl");
	$in=new Mods_setup_core();
	$in->load_set($this->mp."fields.xml");

        if($this->name!=""){
            $in->modlink=$this->modlink."&name=".$this->name;
        	$var=$db->hash("select ln,title from vocab where name='".$this->name."'");
        	$in->add_var($var);
        }

        $htm->assign(array(
        "FORM_FIELDS"=>$in->get_form(),
        "name"=>$this->name
        ));

}

function add(){
	global $db;
	$this->name=_posts('name');
	$data=['ln'=>$this->ln,'name'=>_posts('name'),'title'=>_posts('title')];
	$id=$db->value("select id from vocab where name='".$data['name']."' and ln='{$this->ln}'");
	$lng=$db->vector("select ln from langs");

	if(intval($id)==0){
		
		foreach($lng as $l){
			$data['ln']=$l;
			$db->insert("vocab","",$data);
			
		}
	}

}

function _list(){
	global $htm,$db;

    $htm->external("EXT_ADD",$this->mp."list.tpl");
    
    if(AJAX) 
    	$htm->src($this->mp."list.tpl");
    
    $htm->assign('ULNEWS',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml"
	));
	
}

function _save(){
	global $db;
	$name=_posts('name');
	$lng=$db->vector("select ln from langs");

	foreach($lng as $l){
		$data=[];
		$data['title'] =_posts($l);

		$is=$db->value("select count(*)  from vocab where name='$name' and ln='$l'");
		
		if(intval($is)==0){
			$data['ln']=$l;
			$data['name']=$name;
			$db->insert("vocab","",$data);
		}else{
			$db->update("vocab","",$data," where name='$name' and ln='$l'");
		}
	}
	
}

function prepend(&$r){
	global $db;

	$r['ru']=substr($r['ru'], 0,100);
	$r['trans']="";
	foreach($this->langs as $l)
		$r['trans'].=(trim($r[$l])=="" ? "":$l." "); 

}

function unic(&$data,$field){
	global $db;
	$like=$db->clear($data[$field]);
	$is=$db->value("select count(*) from ".$this->TB." where title like '$like'");
	if($is>0) $data[$field].="-".$data['id'];
}

    
function get_data($sql){
	global $db;
	$ln=$this->ln;
	return $db->select("select * from vocab where ln='$ln' order by name");
}




}


$ladmin=new Langs_admin();
$ladmin->Start();
