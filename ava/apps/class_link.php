<?php
/**
 * Link
 * 
 * @package Smart
 * @author Vladimir
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Link{
	var $params; // параметры, переданные методом GET
	var $links;
	var $Link; // массив REQUEST_URI
  	var $id;  // айди статической страницы
  	var $mod=""; // имя компоненты
  	var $page; // контент статической страницы
  	var $skat_seo; // метатеги и данные роутинга компоненты skat
  	var $rubric_id=0; // рубрика компоненты новостей
  	var $news_id=0;   // айди нововсти
  	var $search=false;
  	var $delimiter="  —  ";
	var $tree=array();
	var $langs=['ua','ru','en'];
    var $lang="ru";
		
	/**
	 * Link::__construct()
	 * 
	 * @return
	 */
	function __construct(){
	// убьем знак вопроса и все после него
		//$link=preg_replace("/\?.*/","",$_SERVER['REQUEST_URI']); 
		$link=$_SERVER['REQUEST_URI']; 
		$this->Link=explode("/",$link);
	
	}
	
	


/**
 * Link::parse_link()
 * основной роутинг (разбор ссылки)
 * @return
 */
function route(){
	global $config,$db,$htm;
	$this->is_image();
	$this->add('Главная','/');
    // мультиязычность основной сайт - русск
    $langs=['ua','en'];
   
    if(!in_array($this->Link[1],$langs)){
          $this->lang="ru";
    }else{
        $this->lang=$this->Link[1];
        array_shift($this->Link);
        $this->Link[0]="";
        $_SERVER['REQUEST_URI']=str_replace('/'.$this->lang, "", $_SERVER['REQUEST_URI']);
        print_r($this->Link[0]);
    }
      
       // define("TEMPLATES",APP_VIEWS.$this->lang."/");
    
    $htm->src(TEMPLATES.$this->lang."/index.tpl");
    

    
if(trim($this->Link[1])=='' && count($this->Link)==2){ //если нет урла, то главная 
    $this->id=$config['pstart'];
    $this->mod="html";
    return;
    } 

 if($this->find_modlink()){
 		return;
 	}elseif($this->find()){
 		return;	
	}else{ 
		$this->e_404(); 
		return;
	}
}

function is_image(){
	global $db;
	if(!preg_match("/\.(jpg|gif|png|js|css)$/", $_SERVER['REQUEST_URI']))
		return;
	header("HTTP/1.0 404 Not Found"); 
	$db->close();
	exit;

	}


/**
 * Link::find()
 * роутинг статических страниц
 * @param mixed $id
 * @return
 */
function find(){
    global $db,$config;
    if(count($this->Link)>2) return false;
    $qlink=explode("?",$this->Link[1]);
    $link=$qlink[0];
   
    $this->id=intval($db->value("select id from static where BINARY guid = '".mysql_real_escape_string($link)."' and is_hidden=0 and lang='{$this->lang}'"));
    if($this->id!=0){
    	if($link=='index.html') $this->redirect($config['url_site']."/");
    	$this->mod="html";
		return true;
	} 
		  return false;
  }	
  
 
	function find_modlink(){
		global $db;
      
        $uri=explode("?",$_SERVER['REQUEST_URI']);
        $path=explode("/",$uri[0]);
        if(!isset($path[1]))
            return false;       
        
		$link1=$db->clear($path[1]);
		if($link1=="") return false;
		$mod=$db->get_recs("select `mods` as 'mod', `submod` from `mods` where `link`='$link1'");
		if(!isset($mod['mod'])) return false;
		$this->mod=$mod['mod'];
		$this->submod=$mod['submod'];
		return true;

	}


 
/**
 * Link::e_404()
 * заголовки+страница ошибки
 * @return
 */
function e_404($txt=""){
  	global $db,$htm;
    //header("HTTP/1.0 404 Not Found"); 
   //header("HTTP/1.1 404 Not Found"); 
    
    $htm->src(TEMPLATES.$this->lang."/404.tpl");
    echo $htm->get();
    $db->close();
    exit;
   //include $_SERVER['DOCUMENT_ROOT']."/404.html";
    //exit();
  }


    
/**
 * Link::redirect()
 * 301 редирект на $url
 * @param mixed $url
 * @return
 */
function redirect($url){
	global $members;
	if(isset($members['id']) && $_SESSION['checkout']==1)
			$url="/order/?act=checkout";
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $url");
    exit();
    
  }  	
 

	function is_slash(){
		$l=trim($_SERVER['REQUEST_URI']);
		if(preg_match("/[^\.]+\.html/", $l)!=0) return;

		if(stripos($l,'//')==false){
			if(strripos($l,'?')!=0) return; 
			if(strripos($l,'/')!=(strlen($l)-1)) $this->redirect("https://".$_SERVER['HTTP_HOST'].$l."/");
			return;
		}else{
			$this->e_404();
		}
	} 



function add($title,$link=""){
	if($link==""){
		$this->tree[]=$title;
		return;
	} 
	$this->tree[]='<a itemprop="url" href="'.$link.'"><span itemprop="title">'.$title.'</span></a>';
}

	/**
	 * Link::get_crumbs()
	 * возвращает хлебные крошки для вставки в тело документа
	 * @return string
	 */
	function get_crumbs(){
		return  '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">'.implode($this->delimiter,$this->tree).'</div>';
		
	}
}
