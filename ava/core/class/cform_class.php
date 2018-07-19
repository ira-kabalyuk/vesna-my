<?php
class Cform{
	var $in;
	var $error=array();
	var $data;
	static $tpl=array( 
 'text' =>'<input type="text" name="{name}" value="{value}" title="{title}" class="{class}">',
 'hidden' => '<input type="hidden" name="{name}" class="{class}" value="{value}" >',
 'textarea' => '<textarea class="{class}" name="{name}" title="{title}">{value}</textarea>',
 'checkbox' =>'<input type="checkbox" title="{title}" name="{name}" value="1" class="{class}" {checked}>',
 'captcha'=>'<img src="{img}" alt="" />&nbsp;<input type="text" value="" name="{name}" class="{class}">');


 function get_inp($keys,$r){
 $ret=array();	
 $ret["title"]=$r["titl"];
 $ret["error"]=$r["err"];
 $r["checked"]=($r['cont']==$r['value'] ? 'checked' :'');
 $tpl=self::$tpl[$r["type"]];
 if($r["type"]=="text") $r["value"]=htmlspecialchars($r["value"]);
 $ret["input"]=str_replace(array("{name}","{value}","{class}","{id}","{checked}","{title}"), array($keys, $r["value"],$r["class"],$r["id"],$r['checked'],$r['titl']) ,$tpl);
	return $ret;
 }
	
	function load_set($file,$access=0){
        $set=load_xml_file($file);
        foreach($set as $s)
        	$this->add_ar($s);	
	}
    
 function add_ar($ar){
	if(!$ar["check"]) $ar["check"]=0;
	$this->in[$ar["name"]]=$ar;
 	}
function assvar($ar){
	foreach($ar as $key=>$val)
	if(isset($this->in[$key])) $this->in[$key]['value']=$val;
} 	
function fill_form($row_name){
	global $htm;
	foreach($this->in as $key=>$r)
	$htm->addrow($row_name,$this->get_inp($key,$r));
} 	
 	
function check_field($post){
  $ret=true;
  $this->error=array();
foreach($this->in as $key=>$r){
	$var=stripslashes($post[$key]);
	$this->data[$key]=$var;
  if($r["check"]==0) continue;
  
    if($r["check"]==1){
    if(trim($var)==''){
  		 $this->error[$key]=$r["err"];
		  $ret=false;
		 }	  
     }elseif($r["check"]==2){
   if(!check_email($var)) {
     $this->error[$key]=$r["err"];
	   $ret=false;
	  }
   }elseif($r["check"]==3){
   if(intval($var)==0) {
     $this->error[$key]=$r["err"];
	   $ret=false;
	  }
   }elseif($r["check"]==4){
    if(strtolower($var)!==strtolower($_SESSION['captcha'])){
        $this->error[$key]=$r["err"];
	   $ret=false;
    }
   }
   }
  
return $ret;
}

	
}