<?php
/**
 * Модуль комментариев к постам
 * 
 * 
 * */

class Mods_coment extends Tab_elements{
	var $mod="coment";

function Start(){
	global $htm,$Core;
		$this->TB="reviews";
		$this->mp=dirname(__FILE__)."/";

		$htm->external("EXT_ADD",$this->mp."admin.tpl");
		$htm->external("EXT_RAZD",$this->mp."submenu.tpl");
	 	$this->conf=Com_mod::get_config($this->mod);

	 	$this->id=_getn('el_id');


		$this->maxrows=intval($this->conf['limit']);
		$this->modlink=ADMIN_CONSOLE."/?mod=".$this->mod."&page=".intval($_GET['page']);

	
	if(!AJAX){
    	$htm->addscript("js",AIN."/js/pg.js");
    	$htm->addscript("css",AIN."/css/pg.css");
	
	}
	
	$box=_getn('box');

	$htm->assign(array(
		'MOD'=>$this->mod,
		'MOD_LINK'=>$this->modlink.($box==1 ? "&box=1":"")
	));



	$act=_get('act');
	
	switch ($act) {
	case 'onoff':
		$Core->ajax_get( $this->onoff());
		break;

	case 'edit':
		$this->edit();
		break;

	case 'delete':
		$this->delete();
		$this->_list();
		break;	

	case 'save':
		$this->save();
		if($box==0){
			$this->_list();
		}else{
			$this->edit();
		}
		
		break;
	
	
	default:
		$this->_list();
		break;
	}
}


function prepend(&$r){
	global $db;
	$r['date_add']=date("d.m.y H:i",$r['date_add']);
	$r['link']='<a href="/blog-'.$r['id'].'.html" target="_blank">'.$db->value("select title from news where id=".$r['parent_id']).'</a>';
}

function _list(){
	global $db,$htm;

    $htm->external("EXT_LIST",$this->mp."ext_list.tpl");
    if(AJAX) $htm->src($this->mp."ext_list.tpl");
    
    $htm->assign('ULLIST',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml",
		" order by  date_add desc",
		"id,user_name,city,email,date_add,is_hidden,descr,parent_id ",
		'prepend'
	));
}

function edit(){
	global $htm, $db;
		$htm->src($this->mp."edit.tpl");
		$rec=$db->get_recs("select id, user_name, parent_id, date_add, descr,city,email, answer from reviews where id=".$this->id);

		$r['title']=$db->value("select title from news where id=".$rec['parent_id']);
		$htm->assign($rec);


}

function delete(){
	global $db;
	$db->execute("delete from reviews where id=".$this->id);
}

function save(){
		global $db;
		$data=array(
		'descr'=>_post('descr'),
		'answer'=>_post('answer'),
		'date_mod'=>time()
		);
		
		$db->execute($db->sql_update("reviews","",$data," where id=".$this->id));
	//	echo $sql;
}


}

$mod=new Mods_coment();
$mod->Start();