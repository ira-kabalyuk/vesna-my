<?php

class Mods_actions_core extends Com_news_core{
	
   var $rid;

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
   
    }
    


    function list_news($flag=false){
 	global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->mp."news_list.tpl");
   // $this->tags=$db->hash("select id,title from news_tag where parent_id=".$this->pid);
    if(AJAX) $htm->src($this->mp."news_list.tpl");
    $w=array();	
    //$w[]=get_against('terms','cat_',$this->rid);
    $w[]="parent_id=".$this->pid;
    
    $htm->assign('ULNEWS',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml",
		" where ".implode(" and ",$w)." order by date_pub desc",
		"id,title,rubr_id, terms, date_pub,guid,is_hidden ",
		'prepend'
	));
 }

 function prepend(&$r){
  $m=$this->_get_meta($r['id']);
  $tag=get_tag($r['terms'],'tag');
  $r['tag']=($m['meta-fix']==1 ? 'отображать на главной':"");
  $r['poz']=$m['meta-position'];
  $r['link']='<a href="/actions/'.$r['guid'].'" target="_blank">'.'/actions/'.$r['guid'].'</a>';
  $r['date_pub']=date("d.m.Y",$r['date_pub']);
  if($this->lang!='ru')
    $r['title']=$m['meta_title'];
 }

}