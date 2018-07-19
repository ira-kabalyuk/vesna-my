<?php

class Mods_review_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
   
    }
    


    function list_news($flag=false){
 	global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->mp."news_list.tpl");
   // $this->tags=$db->hash("select id,title from news_tag where parent_id=".$this->pid);
    if(AJAX) $htm->src($this->mp."news_list.tpl");
    //$w=array();	
    //$w[]=get_against('terms','cat_',$this->rid);
    //$w[]="parent_id=".$this->pid;
    
     $htm->assign('ULNEWS',$this->get_ul(
    'newslist', //div
    $this->mp."list.xml"
    ));
 }

 function prepend(&$r){
  global $db;
    if(!isset($this->rubric))
      $this->rubric=$db->hash("select id, title from news_rubric where parent_id=".$this->pid);
  $m=$this->get_meta($r['id']);
  $tag=get_tag($r['terms'],'cat');
  $r['cat']=$this->rubric[$tag[0]];
 // $r['tag']=($m['meta-fix']==1 ? 'отображать на главной':"");
  //$r['poz']=$m['meta-position'];
  $r['date']=date("d.m.Y",$r['date_pub']);
  $img=$this->get_meta($r['id'],'face');
  $r['img']='<img src="/uploads/news/'.$img.'" class="w100">';
 }

}