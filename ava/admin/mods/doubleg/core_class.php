<?php

class Mods_doubleg_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
          $this->title="Галерея2";

   
    }
    


    function list_news($flag=false){
 	  global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->mp."news_list.tpl");
    $rubric=$db->hash("select id,title from news_rubric where parent_id=".$this->pid);
    if(AJAX) $htm->src($this->mp."news_list.tpl");
  
    $htm->assign('ULNEWS',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml"
	));
    $htm->assign("OPTION_RUBRIC",Input::option($rubric,_getn('tags')));
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
  $m=get_meta("news_metadata",$r['id']);
  $r['foto1']='<img src="/uploads/news/'.$m['one'].'" class="w200" >';
  $r['foto2']='<img src="/uploads/news/'.$m['two'].'" class="w200" >';
  $r['rubric']=$this->get_rubr('cats',get_tag($r['terms'],'cat'));
 // $r['tag']=$this->get_rubr('tags',get_tag($r['terms'],'tag'));
  //$r['tag']=$r['terms'];
  $r['date_pub']=date("d.m.Y",$r['date_pub']);
 }

}