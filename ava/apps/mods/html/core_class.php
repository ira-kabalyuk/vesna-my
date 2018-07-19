<?php
/**
 * Mods_htm_core
 * Создание контента статических страниц
 * @author Vladimir
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Mods_html_core{


/**
 * Mods_htm_core::_get()
 * Создает контент статической страницы
 * @return
 */
static function _get(){
	global $Core;
$id=$Core->link->id;
$db=$Core->db;
$lang=$Core->link->lang;
$prefix="";

$prefix=$lang."/";

$cont=$Core->db->get_rec("static where id=$id and lang='$lang'");
if(count($cont)<2){
	$cont=$Core->db->get_rec("static where id=$id and lang='ru'");
	$cont['lang']=$lang;
	$db->insert("static","",$cont);

}
		
if(!isset($cont['params']))
	return;
$set=unserialize($cont['params']);
//$page=self::parent_crumbs($Core->link->page['parent_id']);
$Core->link->add($cont['title']);

$dizl=explode(",",$Core->db->value("select metavalue from static_metadata where metakey='dizlink'"));
$Core->htm->assign(array(
'dizlink'=>(in_array($id, $dizl) ? "1":""),
'SITE_TITLE'=>$cont['seo_t'],
'KEYWORDS'=>$cont['seo_k'],
'DESCRIPTIONS'=>$cont['seo_d'],
'TITLE'=>htmlspecialchars($cont['title']),
'MAIN'=>($cont['id']==1 ? 1:0),
'BREADCRUMBS'=>($Core->conf['pstart']==$Core->link->id ? "":$Core->link->get_crumbs()),
//'PAGE'=>$Core->link->id.($page!="" ? ','.$page:"")
//'PAGEBG'=>$Core->db->value("select img from static_photo where parent_id=".$Core->link->id)
));
$Core->htm->assign(self::get_meta($id));
// проверим есть ли свой шаблон
if($set['tpl']!=''){

	$Core->htm->external('EXT',TEMPLATES.$prefix.$set['tpl']);
	if(AJAX) $Core->htm->src(TEMPLATES.$prefix.$set['tpl']);
	if(isset($set['rcol'])){
		if(trim($set['rcol'])!="") $Core->htm->external('EXT1',TEMPLATES.$prefix.$set['rcol']);
	}
	$Core->htm->_include_($cont['descr']);
	$Core->htm->_mod($cont['descr']);
	$Core->htm->_mods($cont['descr']);
	$Core->htm->assign('HTML_CONTENT',$cont['descr']);
	
}else{
if(AJAX) $Core->ajax_get($cont['descr']);
	$Core->htm->external("EXT",TEMPLATES.$prefix."html.tpl");
	//$Core->htm->external('EXT1',TEMPLATES."leftcol1.tpl");
	$Core->htm->_include_($cont['descr']);
	$Core->htm->_mod($cont['descr']);
	$Core->htm->_mods($cont['descr']);
	$Core->htm->assign('HTML_CONTENT',$cont['descr']);
	
}

/*
$Core->htm->_include_($cont['short']);
$Core->htm->_mods($cont['short']);
$Core->htm->assign('EXT_FOOTER',$cont['short']);
$Core->htm->assign('BODYCLASS',($set['tpl']=='main.tpl' ? 'index':'inside'));
*/
//$Core->htm->assign('class',$set['class']);
//$Core->htm->assign('about',$cont['short']);


}

static function menu($pid){
	global $db;
	$pid=intval($pid);
	$res=$db->select("select a.id, a.title, a.folder, b.link from static as a left join links as b on a.id=b.parent_id where a.is_hidden=0 and a.parent_id=$pid order by sort");
	if(count($res)==0) return "";
	$ret='<ul>';
	foreach ($res as $r){
		if($r['folder']==1){
			$ret.='<li data-pid="'.$pid.'" data-id="'.$r['id'].'"><a href="/'.$r['link'].'">'.$r['title'].'</a></li>';
		}else{
			$ret.='<li data-pid="'.$pid.'" data-id="'.$r['id'].'"><a href="/'.$r['link'].'">'.$r['title'].'</a></li>';
		}
		
	} 
		return $ret.'</ul>';
	
}

static function _menu($pid,$exclude=""){
	global $db;
	$pid=intval($pid);
	$res=$db->select("select a.id, a.title, a.folder, b.link from static as a left join links as b on a.id=b.parent_id where a.is_hidden=0 and a.parent_id=$pid ".($exclude=="" ? "" :" and a.id not in ($exclude)")." order by sort");
	if(count($res)==0) return "";
	$ret='';
	foreach ($res as $r){
		if($r['folder']==1){
			$ret.='<li rel="'.$r['id'].'"><a>'.$r['title'].'</a><ul>'.self::_menu($r['id']).'</ul></li>';
		}else{
			$ret.='<li rel="'.$r['id'].'"><a href="/'.$r['link'].'">'.$r['title'].'</a></li>';
		}
	} 
		
		return $ret;
	
}

static function submenu($jar){
	global $db,$htm,$Core;
	$set=parse_jar($jar);
	$row='SUBMENU';
	if(isset($set['row'])) $row=$set['row'];
	$pid=(isset($set['id']) ? intval($set['id']) : $Core->link->page['id'] );
	//if($Core->link->page['parent_id']!=0) $pid=$Core->link->page['parent_id'];
	if(isset($set['tpl'])) $tpl=$htm->load_tpl($set['tpl']);
	$res=$db->select("select a.id, a.title,short, b.link from static as a left join links as b on a.id=b.parent_id where a.is_hidden=0 and a.parent_id=$pid order by sort");
	if(count($res)==0) return "";
	$ret='';
	foreach ($res as $r){ 
			if(!isset($set['clear'])) $r['title']=str_replace(" ","&nbsp;",$r['title']);
			$htm->addrow($row,$r);
		}	
		
	if(isset($set['tpl'])){
		$htm->_row($tpl,true);
		return $tpl;
	}
}

static function parent_submenu($jar=''){
		global $Core;
		if($jar=='')
			return self::menu($Core->link->page['parent_id']);
		$set=parse_jar($jar);
		if(!isset($set['tpl']))	
			return self::menu($Core->link->page['parent_id']);
		$tpl=$Core->htm->load_tpl($set['tpl']);
		$Core->htm->assvar("MENU",self::menu($Core->link->page['parent_id']));
		$Core->htm->_var($tpl);
		return $tpl;

}

static function parent_menu($jar=''){
		global $Core;
		if($jar=='')
			return self::menu($Core->link->page['parent_id']);
		$set=parse_jar($jar);
		if(!isset($set['tpl']))	
			return self::menu($Core->link->page['parent_id']);
		$tpl=$Core->htm->load_tpl($set['tpl']);
		$Core->htm->assvar("MENUL",self::_menu(4));
		$Core->htm->assvar("MENUR",self::_menu(5));
		$Core->htm->_var($tpl);
		return $tpl;

}

static function parent_crumbs($id){
	global $Core;
	if($id==0) return "";
	$r=$Core->db->get_recs("select a.id,a.parent_id, a.title, b.link from static as a left join links as b on a.id=b.parent_id where a.is_hidden=0 and a.id=$id");
	if(count($r)>0){
		if($r['id']!=1) $Core->link->tree[]='<a href="/'.$r['link'].'">'.$r['title'].'</a>';
		//self::parent_crumbs($r['parent_id']);
		return $r['id'];
	}
}

static function get_terms($terms){
	preg_match_all("/tag_([0-9]+)/", $terms,$m);
	return $m[1];
}
static function get_cats($terms){
	preg_match_all("/cat_([0-9]+)/", $terms,$m);
	return $m[1];
}

static function slider($jar){
	global $Core;
	$set=parse_jar($jar);
	$fold=$Core->link->id;
	$res=$Core->db->select("select id, title, short from static where is_hidden!=1 and parent_id=".$fold." order by sort");
	foreach($res as $r){
		$r['link']=$Core->db->value("select link from links where parent_id=".$r['id']);
		self::add_meta($r);
		$Core->htm->addrow($set['row'],$r);
	}

}

/**
*Слайдер вложенных страниц
*/
static function cslide($jar){
	global $Core;
	$set=parse_jar($jar);
	$f=intval($set['id']);
	$row=$set['row'];
	if($f==0) $f=$Core->link->id;
	$id=$Core->link->id;
	$ar=$Core->db->vector("select id from static where is_hidden=0 and parent_id=$f order by sort");
	//$ac=self::circle($ar,$id);
	foreach($ar as $i){
		$r=$Core->db->get_recs("select id, title,short from static where id=$i");
		$r['link']=$Core->db->value("select link from links where parent_id=".$i);
		self::add_meta($r);
		$Core->htm->addrow($row,$r);
	}
	if(isset($set['tpl'])){
	$ret=$Core->htm->load_tpl($set['tpl']);
	$Core->htm->_row($ret,true);
	return $ret;
}
}

static function add_meta(&$r){
	global $db;
	$h=$db->hash("select concat('meta_',metakey), metavalue from static_metadata where parent_id=".$r['id']);
	$r=array_merge($r,$h);
}

static function get_meta($id){
	global $db;
	return $db->hash("select concat('meta_',metakey), metavalue from static_metadata where parent_id=".$id);
}



// выбор соседних элементов из кольцевого массива
static function circle($ar,$el){
	// создадим псевдокольцевой массив
	$len=count($ar);
	$dif=3;
	if($len<$dif) return $ar;

	@reset($ar);
	$c=array();
	
	$start=$len-$dif;
	for($i=$start; $i<$len; $i++)
		$c[]=$ar[$i];
	for($i=0; $i<$len; $i++)
		$c[]=$ar[$i];
	for($i=0; $i<$dif; $i++)
		$c[]=$ar[$i];
	
	//сдвинем ключ
	$key=array_search($el,$ar)+$dif;
	$ret=array();

	// создадим новый массив
	$ret[]=$c[$key-1];
	$ret[]=$c[$key+1];
	$ret[]=$c[$key+2];

	return $ret;


}



}
