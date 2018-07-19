<?php
class Lform{
var $in;
var $def_cl;
var $def_val;
var $tpl;
var $name;
var $xml;
var $pr;
var $tmpl="";
var $picker=false;
var $child=array();
var $rel="";
var $parent_id=0;
var $error=array();
var $modlink="";
    

function __construct(){
$this->xml=array();
$this->tpl=array( 
 'text' =>'<label class="input">{title}</label><input type="text" name="{name}" value="{value}" class="{class} form-control"/>',
 'hidden' =>'<input type="hidden" name="{name}" value="{value}" />',
 'url' =>'<input type="text" name="{name}" value="{value}" class="{class}"/>',
 'password' =>'<input type="password" name="{name}" value="{value}" class="{class}"/>',
 'hidden' => '<input type="hidden" name="{name}" class="{class}" value="{value}" />',
 'textarea' => '<div><label class="input">{title}</label></div><textarea class="{class} p10" name="{name}" rows="{rows}" cols="{cols}">{value}</textarea>',
 'checkbox' =>'<label class="checkbox"><input type="checkbox" name="{name}" value="1" class="{class}" {checked}/><i></i>{title}</label>',
 'file'=>'<span class="{class}"><input type="file" name="{name}" /></span>',
 'date'=>'<label class="input">{title}</label><div class="input-group {class}"><input type="text" name="{name}" class="form-control datepicker input-pl" data-dateformat="dd.mm.yy"  value="{value}" size="10"/><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>',
'mce'=>'<textarea name="{name}" style="width: 98%;" rows="{rows}" wysiwyg="true" id="{id}">{value}</textarea>',
'color'=>'<span onclick="sel_color(this)"><img width="16" height="16" src="/rul/img/pix.gif" style="background-color:#{value};cursor:pointer;border:solid 1px;"  alt="" /><input type="hidden" name="'.$this->_name('{name}').'" value="{value}"/></span>',
'icons'=>'<img src="{img}" class="{class}" alt="" onclick="{script}" />',
'ico'=>'<span id="{id}"><span class="{class}"></span><input type="hidden" name="{name}" value="{value}"></span>',
'captcha'=>'<img src="{img}" alt="" />&nbsp;<input type="text" value="" name="{name}" class="{class}">',
'img'=>'<span class="upimg"><div>{title}</div>{upload}</span>',
'prew'=>'<a href="{script}" class="gall {class}" title=""><img class="prew" alt="" src="{value}" alt=""/></a>',
'content'=>'<div id="widget-grid">
<div class="jarviswidget jarviswidget-color-greenLight" id="wid-id-3" >
				
				<header>
					<h2>{title}</h2>				
				</header>
				
				<div>
					
					<div class="widget-body no-padding well" style="height:100%;min-height:600px;">
						<iframe style="min-height:600px;" id="smt-iframe" width="100%" height="100%"  frameBorder="0" src="{value}"></iframe>
					</div>
					
				</div>
				
				
			</div></div>'
			

  );
}

function add($name,  $is_check=0, $titl="", $razd, $err="",  $type, $value="",   $content="", $class="")
{
if(!$class) $class=$this->def_cl;
if(!$value) $value=$this->def_val;
		$this->in[$name]=array(
		"type"=>$type, 
		"value"=>$value, 
		"titl"=>$titl, 
		"err"=>$err, 
		"check"=>$is_check,
		"cont"=>$content,
		"class"=>$class,
		);
}

function add_ar($ar){
//$this->xml[]=$ar;
if(!$ar["class"]) $ar["class"]=$this->def_cl;
if(!$ar["value"])$ar["value"]=$this->def_val;
if(!$ar["check"]) $ar["check"]=0;
if($ar['ajax']) $this->child[$ar['ajax']]=$ar['name'];
//if(!$ar["div"]){ $ar["div"]=''; $ar["ediv"]='';}
$this->in[$ar["name"]]=$ar;
 }


function get_ar($str){
    global $db;
    if(is_array($str)) return $str;
    $ret=array();
if(substr($str,0,1)=="\$") return $this->xml[substr($str,1)];
$st=explode(":",$str);
if($st[0]=="#sql"){
   $st[1]=str_replace('$rel',$this->rel,$st[1]);
  $ret=$db->hash($st[1],0);
  if(isset($st[2])) $ret[0]=$st[2];
  return $ret;  
}elseif($st[0]=="#tree"){
	
	$t=$db->hash($st[1],4);
	foreach($t as $r)
		$ret[$r['parent_id']][]=$r;
		return $this->get_tree($ret,0);
	
} 

$r=explode(",",$str);
if(strrpos($r[0],":")===false) return $r;

foreach($r as $s){
$a=explode(":",$s);
$ret[$a[0]]=$a[1];
}
return $ret;
}


function parse_value($str){
	global $db;
	if(strpos($str,'#sql:')===false) return $str;
	$s=explode(":",$str);
	return $db->value($s[1]);
}

function get_tree($ar,$id){
	static $ret=array();
	static $i=-1;
	$i++;
	if(!is_array($ar[$id])) return $ret;
	foreach($ar[$id] as $r){
		$ret[$r['id']]=array('title'=>$r['title'],'i'=>$i);
		if(isset($ar[$r['id']])) $this->get_tree($ar,$r['id']);
	}
	$i--;
	return $ret;
}

function create_editor($name,$r){
	global $htm;
	
	
	 //$htm->addscript("js","/smart/ckeditor2/ckeditor.js");
	//$htm->addscript("css","/inc/wysibb/theme/default/wbbtheme.css");
	return str_replace(array("{name}","{value}","{class}"), array($name,$r['value'],$r["class"]), '<textarea class="ckeditor2" name="{name}" rows="10" cols="40" data-body="{class}">{value}</textarea>');

	//return str_replace(array("{name}","{value}","{class}"), array($name,$r['value'],$r["class"]), '<div class="summernote mt20 {class}" data-name="{name}">{value}</div>');
/*
  include_once($_SERVER['DOCUMENT_ROOT'].ADMIN_CONSOLE.'/fck6/fckeditor.php'); 
  $editor = new FCKeditor($name);
	$editor->Width=($r['w'] ? $r['w'] : 300)."px";
	$editor->Height=($r['h'] ? $r['h'] : 300)."px";
	$editor ->BasePath	= ADMIN_CONSOLE."/fck6/";
	$editor->ToolbarSet = ($r['toolbar'] ? $r['toolbar'] : 'Basic');
	$editor->Value = stripslashes($r['value']);
	$descr = $editor->Create() ;
    return $descr;
    */
}

function create_MCE(){
    	$content='<textarea name="{name}" style="width: 98%;" rows="30" wysiwyg="true" id="des1">{value}</textarea>';
}
 
 function get_inp($keys,$r){
 	$pr=$this->pr;
 $ret=array();	
 $ret["varname"]=$keys;  
 $ret["name"]=$r["titl"];
 $ret["check"]=$r["check"];
 $r["checked"]=($r['cont']==$r['value'] ? 'checked' :'');
 $tpl=$this->tpl[$r["type"]];

 if($r["type"]=="text") $r["value"]=htmlspecialchars($r["value"]);
 if($r["type"]=="color"){
    $r["value"]=htmlspecialchars($r["value"]);
     $this->picker=true;
 } 
 if ($r["type"]=="hidden"){
 	if(isset($r['cont'])) $r['value']=$this->parse_value($r['cont']); 
 	$ret["hidden"]=1;
	} 
  if ($r["type"]=="textarea")  $tpl=str_replace(array("{rows}","{cols}"), (isset($r["rows"]) ? array($r["rows"],$r["cols"]) : array(5,80)),$tpl);
 
 if ($r["type"]=="editor")  $tpl=$this->create_editor($keys,$r);
 
 if ($r["type"]=="select"){
			$this->get_select($keys,$r,$ret);
	 }elseif($r['type']=='radio'){
	 		 $ret["cont"]='';
	   if(isset($r["cont"])){
	     if(!is_array($r["cont"])) $r["cont"]=$this->get_ar($r["cont"]);
		  @reset($r["cont"]);
		  while(list($key, $val)=each($r["cont"])){
			$ret["cont"].='<input type="radio" name="'.$pr.'['.$r['name'].']" value="'.$key.'" '.(($key==$r["value"])? 'checked' : '').'>'.$val.'<br/>';
			
			}
		}
	 	 	
	 }elseif($r['type']=='img'){
	 	$arg=array('json'=>$r['json'],'div'=>$keys,'img'=>$r['value'],'path'=>$r['path'],'prefix'=>$keys.'_','class'=>$r['class']);
	 	//$upload=Com_upload_core::get_ajax_form(array('div'=>$keys,'link'=>$this->modlink,'img'=>$r['path']."/".$r['value'], 'orign'=>$r['value'],'path'=>$r['path']."/",'json'=>$r['json']));
	 	$upload=Com_upload_core::get_ajax_form($arg);
	 	$ret['cont']=str_replace(array("{upload}","{title}"),array($upload,$r['title']) ,$tpl);
	 	return $ret;

	 }elseif($r['type']=='icon'){
	   $ret["cont"]=$r['html'];
	   return $ret;

	 }elseif($r['type']=='content'){
	   $inp=$tpl;
	 	$inp=str_replace(array("{name}","{value}","{class}","{id}","{tpl}","{title}"), array($keys, $r["value"],$r["class"],$r["id"],$r['tpl'],$r['title']) ,$inp);
	 $ret["cont"].=$inp;
   
	     
     }else{  
	 $inp=$tpl;
	 $inp=str_replace(array("{name}","{value}","{class}","{id}","{checked}","{img}","{script}","{rows}","{title}"), array($keys, $r["value"],$r["class"],$r["id"],$r['checked'],$r['img'],$r['script'],$r['rows'],$r['title']) ,$inp);
	 $ret["cont"].=$inp;
	 	 }
	 
  if ($r["type"]=="list_checkbox")
    $this->get_listbox($r,$ret);

  if ($r["type"]=="tree_checkbox")
    	$ret['cont']=$this->treeview($r);
    	
   
  if ($r["type"]=="list_text")
    $this->get_listtext($r,$ret);  
    
  	return $ret;
 }

 function treeview($r){
 	global $db;

 	$t=$db->select($r['cont']);
 	$hash=array();
	foreach($t as $l){
		$c=Input::checkbox($r['name']."[]",$l['id'],in_array($l['id'], $r['value']),$l['title']);
		$hash[$l['parent_id']][]=array('id'=>$l['id'],'cont'=>'<label class="checkbox">'.$c.'<i></i></label>');
	}
	return '<div class="tree '.$r['class'].'">'.$this->get_ul(0,$hash).'</div>';
	
	
		
 }

function get_ul($id,$hash){
	if(!isset($hash[$id])) return "";
	$ret="<ul> \n";
	foreach($hash[$id] as $r){
		$ret.='<li>'.$r['cont'].$this->get_ul($r['id'],$hash)."</li>\n";
	}
	return $ret."</ul>\n";
}
 
 function get_listbox($r,&$ret){
   if(!is_array($r["cont"])) $r["cont"]=$this->get_ar($r["cont"]);
		 $ret["cont"]='<div class="p10"><div class="row">';
		 if(!is_array($r["value"])) $r["value"]=explode(",",$r["value"]);
		 		  @reset($r["cont"]);
		  while(list($key, $val)=each($r['cont'])){
			$ret["cont"].='<span class="fl ml20 smart-form"><label class="checkbox"><input  type="checkbox" '.$r['script'].' name="'.$this->_name($r['name']).'['.$key.']" value="'.$key.'" '.(in_array($key,$r["value"]) ? 'checked' : '').'/><i></i> '.$val.'</label></span>';
		    // $ret["cont"].=$this->get_rel($key,$r);
			}	
			$ret["cont"].='</div></div>';
 }
  function get_listtext($r,&$ret){
   if(!is_array($r["cont"])) $r["cont"]=$this->get_ar($r["cont"]);
		 $ret["cont"]="";
		 if(!is_array($r["value"])) $r["value"]=explode(",",$r["value"]);
		 		  @reset($r["cont"]);
		  while(list($key, $val)=each($r['cont']))
		    {
			$ret["cont"].='<section><label>'.$val.'</label><label class="input">'.str_replace(array("{name}","{value}","{class}","{id}","{script}"), array($r['name'].'['.$keys.']', $r["value"][$key],$r["class"],$r["id"],$r['script']) ,$this->tpl['text']).'</label></section>';
		    // $ret["cont"].=$this->get_rel($key,$r);
			}	
 }
 function get_select($name,$r,&$ret=''){

 		$r['m']="";
 		$opt="";
 		$sl="";
 		$cs="";
 		
 		$ar=$this->get_ar($r["cont"]);
 		if($r['multy']==1){
 			$opt=Input::option_multy($ar,$r["value"]);
 			$r['m']='multiple';
 			$sl="select2";
 			$r['script'].=' data-placeholder=" - - - "';
 		} else{
 			$cs="select";
 			$opt.=Input::option($ar,$r["value"]);
 		}
 			$inp='<div class="'.$r['class'].'"><label>'.$r['title'].'</label><div class="'.$cs.'"><select '.$r['m'].' class="'.$sl.'" name="'.$name.($r['multy']==1 ? '[]':'').'" '.$r['script'].' id="select-'.$name.'">';
 	
	     $inp.=$opt;
		
		$inp.='</select>'.($r['multy']==1 ? '':'<i></i>').'</div></div>';
	 $ret["cont"].=$inp;
	 return $inp;
 }
 
 function get_tag($keys,$r){
 		$ret=$this->get_inp($keys,$r);
 		
 		return $ret['cont'];
 }
 function _name($name){
 if($this->pr==""){
   return $name; 
 } else{
    return $this->pr."[".$name."]";
 }  
 } 
 
function get(){
$ret=array();
@reset($this->in);
while (list($keys,$r)=@each($this->in)){
    $ret[]=$this->get_inp($keys,$r);

  }
return $ret;
}

function value(){
global $$this->pr;
@reset($this->in);
while (list($key,$value)=@each($this->in)){
    $this->in[$key]["value"]=${$this->pr}[$key];
	}
}

function assign($name,$val){
    $this->in[$name]["value"]=$val;
}

function set_attr($name,$attr,$value){
	$this->in[$name][$attr]=$value;
}

/**
 * Lform::check_field()
 * Проверка данных 
 * @param mixed $post
 * @return int 0- все ок иначе - кол-во ошибок
 */
function check_field($post){
 
    //$r_=check_array($r_);
  $ret=0;
  $this->error=array();
@reset($this->in);
while (list($key,$r)=@each($this->in)){
$var=stripslashes($post[$key]);

  if($r["check"]==0) continue;
  
    if($r["check"]==1){
    if(trim($var)==''){
  		 $this->error[$key]=$r["err"];
		  $ret++;
		 }	  
     }elseif($r["check"]==2){
   if(!check_email($var)) {
     $this->error[$key]=$r["err"];
	   $ret++;
	  }
   }elseif($r["check"]==3){
   if($var==0) {
     $this->error[$key]=$r["err"];
	   $ret++;
	  }
   }elseif($r["check"]==4){
    if(strtolower($var)!==strtolower($_SESSION['captcha'])){
        $this->error[$key]=$r["err"];
	   $ret++;
    }
   }
   }
  
return $ret;
}


/**
 * Lform::get_fields()
 * Возвращает массив имен элементов формы
 * @return
 */
function get_fields(){
$r=array();
@reset($this->in);
while (list($key,$value)=@each($this->in)){
$r[]=$key;
  }
return $r;
}

/**
 * Lform::get_vars()
 * возвращает массив данных формы
 * @return
 */
function get_vars(){
$r=array();
@reset($this->in);
while (list($key,$value)=@each($this->in)){
    $r[$key]=trim($value["value"]);
	}
return $r;
}

function add_var($ar,$prefix=""){
	if(!is_array($ar)) return;
	if(!is_array($this->in)) return;
if($prefix!=''){
	$n=array();
	foreach($ar as $k=>$v)
	$n[$prefix.$k]=$v;
	$ar=$n;
}
	$keys=array_keys($this->in);
	//print_r($keys);
	
	foreach ($keys as $key)
	if(isset($ar[$key])) $this->in[$key]["value"]=$ar[$key];
}

	
function update_var($name,$ar){
	   if(isset($this->in[$name])){
	             @reset($ar);
				 while (list($key,$value)=@each($ar)){
				   $this->in[$name][$key]=$value;
				   }
				 }	
			}
function update_field($name,$field,$val){
              $this->in[$name][$field]=$val;
	 
			}
	


function load_set($file,$access=0){
        $set=load_xml_file($file);
        foreach($set as $s){
        if($access!=0){
            if($s['access']!=$access) continue;
            }
            $this->add_ar($s);	
            
        }
    }

static function helper($arg){
    $r=$form->get_inp($arg['name'],$arg);
    return $r['cont'];
}

function fill_form($row_name){
	global $htm;
	foreach($this->in as $key=>$r)
		$htm->addrow($row_name,$this->get_inp($key,$r));	
	
}

function get_posts(){
$ret=array();
@reset($this->in);
while (list($key,$r)=@each($this->in)){

    $ret[$key]=($r['type']=="int" ? _postn($key):_posts($key));

  }
return $ret;
}


}

