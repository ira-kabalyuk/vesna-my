<?php

class Mods_master_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
          $this->title="Специалисты";

   
    }
    


    function list_news($flag=false){
 	  global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->mp."news_list.tpl");
    $this->rubric=$db->hash("select id,title from news_rubric where parent_id=".$this->pid);
    if(AJAX) $htm->src($this->mp."news_list.tpl");
    $w=array();	
    //$w[]=get_against('terms','cat_',$this->rid);
    $w[]="parent_id=".$this->pid;
    
    $htm->assign('ULNEWS',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml",
		" where ".implode(" and ",$w)." order by date_pub desc",
		"id,title,rubr_id, terms, date_pub,is_hidden ",
		'prepend'
	));
 }

 function prepend(&$r){
  preg_match_all("/cat_([0-9]+)/",$r['terms'],$m);
  $r['tag']=$this->rubric[$m[1][0]];
  $img=$this->get_meta($r['id'],'face');
  $r['img']='<img src="/uploads/news/'.$img.'" class="w100">';
  $r['date_pub']=date("d.m.Y",$r['date_pub']);
 }

}