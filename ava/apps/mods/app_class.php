<?php
class Mods_app{



static function portfolio($id){
	return Mods_photo_help::gallery('tpl:portfolio.tpl,row:PORTFOLIO,rubric:'.$id);
} 
static function slider($id){
	return Mods_photo_help::gallery('tpl:slider.tpl,row:SLIDER,rubric:'.$id);
} 
static function gallery($id){
	return Mods_photo_help::gallery('tpl:slider.tpl,row:SLIDER,rubric:'.$id);
}

static function gallery2($id){
		$mod = new Mods_doubleg_core();

	return $mod->gallery('tpl:gallery-block.tpl,row:GALLERY2,rubric:'.$id);
}

static function inside_gallery(){
	return Mods_news_help::gall('tpl:blog-gallery.tpl,row:GAL_ROW');
} 	
static function posts($jar){
	return Mods_news_help::get_list($jar);
}

static function top_service($jar){
	$jar.=",mod:service,meta:1,tag:1,order:sort";
	return Mods_news_help::get_list($jar);

}

static function reviews($jar){
	global $Core;
	$id=$Core->news_id;
	$jar.=",mod:review,meta:1,cat:$id,order:date_pub";
	return Mods_news_help::get_list($jar);
}

static function now($arg){
	$r=array('date'=>time());
	Mods_news_help::date($r,'date');
	return $r[$arg];
}

static function service_menu($row){
	global $db,$htm,$Core;
	$lang=$Core->link->lang;
	$pid=6;
	$res=$db->select("select id, title, guid from news where parent_id=$pid and is_hidden=0 order by sort");
	foreach ($res as $r) {
		

			$r['link']="/service/".$r['guid'];
			if($lang!='ru'){
				Mods_news_help::langData($r,$lang);
				$r['link']="/".$lang.$r['link'];
			}
			$htm->addrow($row,$r);
	}
}

}