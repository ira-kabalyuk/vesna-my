<?php
class Mods_blog_rubric_admin extends Com_news_rubric_admin{
  var $id;
  var $pid=0;
  var $TB;
  var $mp;
  var $conf;
 
  function __construct($table){
   global $mid;
    $this->TB=$table;
    $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
    //$this->initialize();
    
     }
		 
	



}
 
