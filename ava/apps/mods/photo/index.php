<?php

$mod=new Aphoto();
$mod->_get();


class Aphoto{
	var $set;
	var $TB;
	
	function __construct(){
		$this->set=Com_mod::load_conf('photo');
		$this->TB="fotogal";
		$this->limit=12;
	}
	
	
	function _get(){
		global $db,$htm,$Link;
		if(!AJAX){
		$htm->external("EXT",TEMPLATES."html.tpl");
		$htm->external("HTM",TEMPLATES."portfolio.tpl");
		//$htm->addscript("js","/js/pretty/pretty.js");
		//$htm->addscript("css","/js/pretty/pretty.css");
		$Link->tree[]="ПОРТФОЛИО";
		$htm->assign('BREADCRUMBS',$Link->get_crumbs());
		$set=Com_mod::get_config('foto');
		Mods_htm_menu::set($set['parent']);
		
		}else{
			$htm->src(TEMPLATES."ajax_photo.tpl");
		}
		$this->_list();
		
		
		
	}
	
	function _list(){
		global $db,$htm;
		
		$page=_getn('page');
		$pid=_getn('fold');
		$where=" where is_hidden=0 ".($pid!=0 ? " and cat like '%cat_$pid %'":'');
		$limit=get_limit($page,$this->limit);

		
		$sql_count="select count(*) from {$this->TB} ".$where;
		$sql="select * from {$this->TB} ".$where." order by sort ".$limit;
		
		
		$htm->assign('PAGE_JSON',get_page_json($page,$this->limit,$sql_count));
		$res=$db->select($sql);
		$i=0;
		foreach($res as $r){
			$r['ic']=($i==1 ? ' ic':'');
			$htm->addrow('GALLERY_ROW',$r);
			$i++;
			if($i==3) $i=0;
			
		}
		$db->maprow("RAZDEL","select id,title from foto_rubric where is_hidden=0 order by sort");
		
		
	}
	

	
}