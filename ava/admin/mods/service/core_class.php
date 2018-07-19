<?php

class Mods_service_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
          $this->title="Услуги";

   
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

 function get_rubr($tag,$m){
  $ret=array();
  $tags=$this->$tag;
  foreach($m as $i)
    $ret[]=$tags[$i];

  return implode(",",$ret);
 }

 function prepend(&$r){
  global $db;
  
  $r['rubric']=$this->get_rubr('cats',get_tag($r['terms'],'cat'));
  $r['tag']=$this->get_rubr('tags',get_tag($r['terms'],'tag'));
  //$r['tag']=$r['terms'];
  $r['date_pub']=date("d.m.Y",$r['date_pub']);
  if($this->lang!='ru'){
    $title=$this->_get_meta($r['id'],'title');
    if(trim($title)!="")
      $r['title']=$title;
  }
 }

}