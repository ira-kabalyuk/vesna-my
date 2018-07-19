<?php
/*11*/
function __autoload($class_name){
		$cn=strtolower($class_name);
		$m=explode("_",$cn);
		$file=CMS_LIBP."class".DIRECTORY_SEPARATOR.$cn."_class.php";
		if(is_file($file)){
    	require_once $file;
			}else{
				$file=str_replace("\\","/",implode(DIRECTORY_SEPARATOR,$m)."_class.php");
				$files=str_replace("\\","/",implode(DIRECTORY_SEPARATOR,$m).".php");
				if(realpath(CMS_LIBP.$files)){
							require_once realpath(CMS_LIBP.$files);
						}elseif(realpath(CMS_LIBP.$file)){
							require_once realpath(CMS_LIBP.$file);
					
						}elseif(realpath(CMS_MYLIB.$file)){
							require_once CMS_MYLIB.$file;
						}elseif(realpath(CMS_APP.$file)){
							require_once CMS_APP.$file;	
						}elseif(realpath(CMS_ADMIN.$file)){
							require_once CMS_ADMIN.$file;		
						}else{
							echo " not find classfile ".CMS_LIBP.$file;
						}
			}
		}
  
  function _abs_path($file){
    global $_root;
    if((bool) stripos($_root,":")){
       if(strpos($file,":")===false) $file=$_root.$file; 
       
    }else{
        if(substr($file,0,1)!=DIRECTORY_SEPARATOR) $file=$_root.$file;
    }
	return $file;  
}

	function load_ar($file){
		$file=_abs_path($file);
		$stra=array();
		if(file_exists($file)){
		   $stra= unserialize(file_get_contents($file));
		}else{
		return array();
		}
		return $stra;
}

function save_ar($ar, $file){
$file=_abs_path($file);

$fl=fopen($file,"w");
if (!$fl){
echo "невозможно открыть файл для записи ".$file;
return false;
}
if (fwrite($fl,serialize($ar))){
 fclose($fl);
 return true;
 	}else{
 	  echo "write error!";
 fclose($fl);
 return false;
 }

}

function _my_vars($varname,$type=''){
$razd=false;
if($type==''){
 $razd=$_REQUEST[$varname];
}
if($type=='p') $razd=trim(stripslashes(_post($varname)));
if($type=='g') $razd=trim(stripslashes(_get($varname)));
return $razd;
}

function _get($name){
	return isset($_GET[$name]) ? $_GET[$name]:'' ;
}
function _post($name){
	return isset($_POST[$name]) ? $_POST[$name]:'' ;
}
function _posts($name){
	return isset($_POST[$name]) ? strip($_POST[$name]): '';
}

function _strip($arg){
		return trim(stripslashes($arg));
}

function strip($arg){
	if(gettype($arg)=='array'){
		return array_map("_strip",$arg);
	}else{
		return trim(stripslashes($arg));
	}
}
function _gets($name){
	return isset($_GET[$name]) ? trim(stripslashes($_GET[$name])):'' ;
}
function _getn($name){
	return isset($_GET[$name]) ? intval($_GET[$name]):0 ;
}
function _postn($name){
	return isset($_POST[$name]) ? intval($_POST[$name]):0; 
}
function _session($name){
	return isset($_SESSION[$name]) ? $_SESSION[$name] :''; 
}

function _postn_ar($name){
	if(!is_array($_POST[$name])) return array();
	$ar=array();
	foreach($_POST[$name] as $key=>$val)
		$ar[intval($key)]=intval($val);
		return $ar;
}
function _posts_ar($name){
	if(!is_array($_POST[$name])) return array();
	$ar=array();
	foreach($_POST[$name] as $key=>$val)
		$ar[intval($key)]=trim(stripslashes($val));
		return $ar;
}
function _post_ar($name){
	if(!is_array($_POST[$name])) return array();
	$ar=array();
	foreach($_POST[$name] as $key=>$val)
		$ar[trim($key)]=trim(stripslashes($val));
		return $ar;
}
function _to_array($arg,$default=false){
	if(is_bool($arg)) return($arg==true ? $default : array());
	if(is_string($arg)) return($arg=='' ? array():explode(",",$arg));
	return $arg;
}
function data_to_int($data){
	$t=explode(".",trim($data));
	if(strlen($t[2])<4) $t[2]='20'.$t[2];
	return mktime(0,0,0,intval($t[1]),intval($t[0]),intval($t[2]));
}
function int_to_date($format,&$int){
	$int=date($format,$int);
}
function date_m($date){
	$m=array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
	$t=explode(".",trim($date));
	$t[1]=$m[intval($t[1])-1];
	return implode(" ",$t);
	
}
function array_tostr($arr,$join='=>'){
	if(is_array($join)){
		$r='';
	foreach($arr as $key=>$val)
	$r.=$key.$join[0].$val.$join[1];
	}else{
		$r=array();
		foreach($arr as $key=>$val)
		$r[]=$key.$join.$val;
		}
	return $r;
}
function check_email($email) {
        return preg_match('/[\w_\-\.]+\@[\w_\-\.]+/',$email);
}
function ajax_header(){
	 header("Content-type: text/html; charset=utf-8");
}
function array_addslashes($ar){
	$n=array();
	foreach($ar as $key=>$val){
		if(is_array($val)) $val=array_addslashes($val);
		if(is_string($val)) $val=addslashes($val);
		$n[$key]=$val;
	}
	return $n;
}

function get_json($ar){
	if(count($ar)==0) return "{}";
	$json=new stdClass;
	//$ar=array_addslashes($ar);
	foreach($ar as $key=>$val){
		 if(trim($key)!='') $json->$key=$val;
		 }
	//return json_encode($json,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	return json_encode($json,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
}

function get_tree($arg){
global $Tree;

$Tree=array();
if(isset($arg['tpl'])) {
$tpl=$arg['tpl'];
	}else{
$key='\'.$ms["id"].\'';
$left='';
$right='';
if(isset($arg['key']))	$key=str_replace(array('{','}'), array('\'.$ms[','].\''),$arg['key']);	
if(isset($arg['left'])) $left=str_replace(array('{','}'), array('\'.$ms[','].\''),$arg['left']);
if(isset($arg['right'])) $right=str_replace(array('{','}'), array('\'.$ms[','].\''),$arg['right']);
$tpl='<li>'.$left.'<span title="'.$key.'">\'.$ms["name"].\'</span>'.$right;
}

$act=intval($arg['act']);
$fill=array();

foreach ($arg['ar'] as $fl){
	
	$gr[$fl["p"]][]=array("id"=>$fl["id"],"tt"=>$fl['tt'],"name"=>$fl["name"].($act==$fl["id"] ? ' &gt;' : ''));
	$fill[$fl["id"]]=array('p'=>$fl["p"],'name'=>$fl["name"]);
		}
        
$Act=array($act);
$Tree[$act]=$fill[$act]['name'];
$id=$act;
while($fill[$id]['p']!=0){
	$id=$fill[$id]['p'];
	$Act[]=$id;
	$Tree[$id]=$fill[$id]['name'];
}	
	
	
$stack = array();
$broken = 0;
$parent = (!isset($arg['parent']) ? 0 : $arg['parent']);
//print_r($gr);
$xml="";
if(!isset($arg['reload'])) $xml= '<ul id="'.$arg['id'].'">';
$xml.="\r\n";
if(isset($arg['title'])){
$xml.='<li><span title="'.$arg['title']['key'].'">'.$arg['title']['title'].'</span></li>';
$xml.="\r\n";
	
	}
//print_r($gr);

while (1){
	while (list($i,$ms)=@each($gr[$parent]))	{
	   
		if(in_array($ms['id'],$Act)){
		$ms['class']=' class="active"';	
		} 
		eval("\$str = '$tpl';");
		$xml.=$str;
		//$xml.="\r\n";
			if (sizeof($gr[$ms["id"]])){
			$stack[] = $parent;
			$parent = $ms["id"];
			$broken = 1;
			
			break;
		}else{
			$xml .= "</li>";
		}
	}
	if ($parent==0)
		break;
	
	if (!$broken){
		$parent = array_pop($stack);
		$broken = 0;

		$xml .= "</ul>";
	}else{
		$xml .= "<ul>";
	}
	$broken = 0;
}

if(isset($arg['reload'])) $xml .= "</ul>";
return $xml;
}

function add_log($file,$msg){
	$log=fopen(LOG_PATH.$file.".log",'a+');
	@fwrite($log,date('d.m.Y H:i:s')."\n".$msg."\n\n");
	fclose($log);
}

//устаревшая
function load_xml_file($file){

$pars=array();
$file=_abs_path($file);

if(is_file($file)){
 $xml = simplexml_load_file($file);

foreach ($xml->set as $set){
$pars[$i]=array();	
foreach($set->attributes() as $a=>$b) {
	$c=each($b);
    $pars[$i][$a]= $c[1];
}
$i++;
}
}
return $pars;	
}


function load_xml_data($file){

$pars=array();
$file=_abs_path($file);

if(is_file($file)){
 $xml = simplexml_load_file($file);
$data=[];

foreach ($xml->children() as $set){
	$name=$set->getName();
	$data[$name]=(string)$set;
	}
	
}
return $data;	
}

function get_limit($page,$max){
	$page=($page==0 ? 1: $page);
	return "limit ".($max*($page-1)).",".$max;
}
function get_page_json($page,$max,$count){
	global $db;
	if(is_string($count)) $count=$db->value($count);
	return '{"page":'.intval($page==0 ? 1: $page).',"max":'.intval($max).',"count":'.intval($count).'}';
} 
 
 function _emit(){
	 $numargs = func_num_args();
	 if($numargs==0) return;
	 $args=func_get_args();
	 $script=$args[0];
	$file=HOOKS_PATH.$script.".php";
	if(is_file($file))
		include($file);	

} 
 
function fire($arg,$titl){
	global $Core;
	$Core->fire->log($arg,$titl);
} 

function parse_jar($str){
	$t=explode(",",$str);
	$ret=array();
	foreach($t as $arg){
		$s=explode(":",$arg);
		$ret[$s[0]]=$s[1];
	}
	return $ret;
} 

function get_against($fname,$pref,$args){
	if(!is_array($args)) $args=explode(",",$args);
		return " MATCH ($fname) AGAINST('".$pref.implode(' '.$pref,$args)."' IN BOOLEAN MODE)";
	}

function get_tag($str,$tag){
 		$ret=array();
 		if(!strpos($str,"_")) return $ret;
 		if(preg_match_all("/".$tag."_([0-9]+)/",$str,$m)){
 			return $m[1];
 		}else{
 			return $ret;
 	}
}		

function get_meta($tb,$id,$key='',$prefix=''){
	global $db;
	if($key!=''){
		return $db->value("select metavalue from $tb where parent_id=$id and metakey='$key'");
	}else{
		if($prefix=='')
			return $db->hash("select  metakey,metavalue from $tb where parent_id=$id");
		return $db->hash("select concat('$prefix',metakey), metavalue from $tb where parent_id=$id");
	}
	

}     
    