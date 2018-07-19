<?php

class Mods_news_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
   
    }
    


    function list_news($flag=false){
 	global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->mp."news_list.tpl");
    
    if(AJAX) $htm->src($this->mp."news_list.tpl");
    $w=array();	
    //$w[]=get_against('terms','cat_',$this->rid);
    $w[]="parent_id=".$this->pid;
    
    $htm->assign('ULNEWS',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml"
		));
 }

 function prepend(&$r){
  global $db;
  if(!isset($this->rubric))
      $this->rubric=$db->hash("select id,title from news_rubric where parent_id=".$this->pid);
  preg_match_all("/cat_([0-9]+)/",$r['terms'],$m);
  $r['tag']=$this->rubric[$m[1][0]];
  $r['date_pub']=date("d.m.Y",$r['date_pub']);
 }

}