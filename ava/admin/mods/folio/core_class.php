<?php

class Mods_folio_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
          $this->title="Блог";

   
    }
    


    function list_news($flag=false){
 	global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->mp."news_list.tpl");
    
    if(AJAX) $htm->src($this->mp."news_list.tpl");
    $w=array();	
    //$w[]=get_against('terms','cat_',$this->rid);
    $w[]="parent_id=".$this->pid;
    $ul=new Com_ul;
    $ul->init($this->mp."list.xml","newslist");
    $ul->toolset('onof');
    $ul->add_head();
    
    
    $htm->assign('ULNEWS',$ul->get_ul());
		
 }

 function prepend(&$r){
  global $db;
  if(!isset($this->rubric))
      $this->rubric=$db->hash("select id,title from news_rubric where parent_id=".$this->pid);
  preg_match_all("/cat_([0-9]+)/",$r['terms'],$m);
  $tag=[];
  foreach ($m[1] as $key) {
    $tag[]=$this->rubric[$key];
  }
  $r['tag']=implode("<br>",$tag);
  $r['date_pub']=date("d.m.Y",$r['date_pub']);
 }

}