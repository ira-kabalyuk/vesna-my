<?php
/**
 * Mods_news_core
 * Клиентская компонента новостей
 * @package   
 * @copyright Vladimir
 * @version 2012
 * @access public
 */
class Mods_news_core{
	var $page=1;
	var $inside=false;
	var $lang;

function route($mod){
	global $Core;
	$this->set=Com_mod::load_conf($mod);
	$this->lang=$Core->link->lang;
	$pid=$this->set['parent_id'];
	$this->pid=$pid;
	


	if($mod=='_service'){
		$uri=explode("?",$_SERVER['REQUEST_URI']);
		$url=$uri[0];
	}else{
		$url=$_SERVER['REQUEST_URI'];
	}
	

	$links=explode("/",$url);
	
	
	$a=count($links);

	if(preg_match("/^\/([^\/]+)\/page-([0-9]+)\/$/", $url,$m)){
		$this->page=intval($m[2]);
		$a=3;
		$links[2]="";
		

	}

	if($this->pre_route($a)) 
		return;
	if($a==3 && $links[2]==""){
		// ссылка на корень, все посты блога
		
		$this->_list();

	}elseif($a==3 && $links[2]!=""){
		//рубрика или пост

	$l=$Core->db->clear($links[2]);
	$rid=intval($Core->db->value("select id from news_rubric where link='$l' and parent_id=$pid"));
	if($rid!=0){

		$this->_list($rid);
		return;	
	}
		$id=intval($Core->db->value("select id from news where guid='$l' and parent_id=$pid"));

	if($id!=0){
		$this->get_post($id);
		return;	
		}
		$Core->link->e_404();
		//$mod=new Mods_html_core;
		//$mod->_get();
		return;
	}else{

		$Core->link->e_404();
		//$mod=new Mods_html_core;
		//$mod->_get();

	}


}


public function pre_route($a){
	return false;
}

		/**
	 * Вывод одиночной новости
	 * */
	function get_post($id){
		global $db,$htm,$Core;
	$Core->news_id=$id;
	$lang=$Core->link->lang;

	$htm->assign('BODYCLASS','inside');
	
	$Core->link->add($this->set['title'],"/".$this->set['prefix']);

	$news=$db->get_rec("news where id=".$id);
	if($lang!='ru')
		Mods_news_help::langData($news,$lang);

	$Core->link->news=array('id'=>$id,"date_pub"=>$news['date_pub'],"parent_id"=>$news['parent_id']);
	
	$tpl=$this->set['one_tpl'];
	$link="/".$this->set['prefix']."/";

		
	$cat=Mods_news_help::get_rubr($news['terms']);

	if(count($cat>0)){
		$rid=intval($cat[0]);
		if($rid!=0){
			$rubr=$db->get_rec("news_rubric where id=".$rid);
			$news['rlink']=$link.$rubr['link'];
			// добавим в хлебные крошки путь к рубрике
			$Core->link->add($rubr['title'],$link.$rubr['link']);
			$htm->assign('RUBR_TITLE',$rubr['title']);
		}
	}
	
	
	$Core->link->add($news['title']);

		$htm->external("EXT",TEMPLATES.$this->lang."/".$tpl);
		if(AJAX) $htm->src(TEMPLATES.$this->lang."/".$tpl);
		//преобразуем дату 
		
		$htm->_mods($news['descr']);
		$news=array_merge($news,Mods_news_help::metadata($id,$lang));
		$htm->_include_($news['descr']);
		$this->prepend($news);
		$htm->assign($news);
		
		
		//$Core->link->tree[]=$news['title'];
$htm->assign(array(
	"IMG"=>$news['img'],
	"PAGE"=>"",
	"NTITLE"=>$news['title'],
	"SITE_TITLE"=>$news['seo_t'],
	"KEYWORDS"=>$news['seo_k'],
	"SEO_H"=>$news['seo_h'],
	"DESCRIPTIONS"=>$news['seo_d'],
	"BREADCRUMBS"=>$Core->link->get_crumbs()
	));
}

/**
 * Листинг списка новостей
 * */
function _list($rid=0){
	global $db,$htm,$Core;
	$tpl=$this->set['main_tpl'];
	$lang=$Core->link->lang;
	
	$seo=$this->set['seo'];


	$w=array('is_hidden=0',"parent_id=".$this->set['parent_id']);

	$order=($this->set['datesort']=='1' ? 'date_pub desc':'sort');
	if(isset($this->order))
			$order=$this->order; 
	
	if($rid!=0){
		$Core->link->add($this->set['title'],"/".$this->set['prefix']);
		$rubr=$db->get_rec("news_rubric where id=".$rid);
		if($rubr['main_tpl']!="") $tpl=$rubr['main_tpl'];
		$seo=$db->get_rec("news_seo where id=$rid");
		$w[]=get_against('terms','cat_',$rid);
		$link="/".$this->set['prefix'];
	//	$link="/".$this->set['prefix']."/".$rubr['link'];
		$Core->link->add($rubr['title']);
		$rubr['h']=$seo['seo_h'];
		if(trim($seo['seo_h'])=="") 
			$rubr['h']=$rubr['title'];
		$htm->assign('RID',$rid);

	}else{
		$link="/".$this->set['prefix'];
		$Core->link->add($this->set['title']);
	}

	if(!$this->inside)
		$htm->external("EXT",TEMPLATES.$this->lang."/".$tpl);

	if(AJAX) $htm->src(TEMPLATES.$this->lang."/".$tpl);
	
	$where=$this->prepend_where($w);
	
	
	
	$count=$db->value("select count(*) from news where ".$where);
	// получим пагинацию
	$pg=Com_paginator::_get(intval($this->set['limit']),intval($count),$this->page,$link);
	//if($this->page!=1) echo $pg['limit'];

	$sql="select id, title, date_pub,short,img,descr,guid,terms from news where $where order by $order ".$pg['limit'];
	$res=$db->select($sql);
	
	$odd=1;
	$even=0;
	foreach($res as $r){	
		if($lang!='ru')
			Mods_news_help::langData($r,$lang);
		$r=array_merge($r,Mods_news_help::metadata($r['id']));
		//$r['link']="";
		//if($r['meta-nolink']!="1")
		$r['link']=$link."/".$r['guid'];
		$r['odd']=$odd;
		$r['even']=$even;
		$odd=($odd==1 ? 0:1);
		$even=($even==1 ? 0:1);

		$this->prepend($r);
		$htm->addrow("NEWS_ROW",$r);
		
	}
	//$htm->_row($ret);
	if(!$this->inside){
	$htm->assign(array(
	//"HTM_CONTENT"=>$ret,
	"RTITLE"=>$rubr['title'],
	"RH1"=>$rubr['h'],
	"TITLE"=>$this->set['title'],
	"PAGINATOR"=>implode(" ",$pg['paginator']),
	"BREADCRUMBS"=>$Core->link->get_crumbs(),
	"SITE_TITLE"=>$seo['seo_t'].($this->page>1 ? "| Страница ".$this->page:""),
	"KEYWORDS"=>$seo['seo_k'].($this->page>1 ? "| Страница ".$this->page:""),
	"PAGE"=>"",
	"DESCRIPTIONS"=>$seo['seo_d'].($this->page>1 ? "| Страница ".$this->page:"")
	));
}
	$htm->assign(Mods_news_help::rubric_meta($rid));
}

function prepend(&$r){
	Mods_news_help::date($r);
}

function prepend_where($w){
	return implode(" and ",$w);
}





}
