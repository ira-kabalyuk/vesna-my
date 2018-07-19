<?
class Form{
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
    

function __construct($def_cl="input",$def_val="",$pr="r_"){
$this->xml=array();
$this->def_cl=$def_cl;
$this->def_val=$def_val;
$this->pr=$pr;
$this->tpl=array( 
 'text' =>'<input type="text" name="'.$pr.'[{name}]" value="{value}" class="{class}">',
 'div' =>'{value}',
  'url' =>'<input type="Text" name="'.$pr.'[{name}]" value="{value}" class="{class}">',
 'password' =>'<input type="password" name="'.$pr.'[{name}]" value="{value}" class="{class}">',
 'hidden' => '<input type="hidden" name="'.$pr.'[{name}]" class="{class}" value="{value}" >',
 'textarea' => '<textarea class="{class}" name="'.$pr.'[{name}]" rows="{rows}" cols="{cols}">{value}</textarea>',
 'checkbox' =>'<input type="checkbox" name="'.$pr.'[{name}]" value="1" class="{class}" {checked}>',
 'file'=>'{img}<input type="file" name="{name}" class="{class}">',
 'date'=>'<input type="text" name="'.$pr.'[{name}]" class="{class}" id="day_{id}" value="{value}" size="10">&nbsp;<input type="button" value="..." onclick="create_table({id})"><div id="kalendar_{id}"  style="position:absolute;">',
 'video'=>'<img alt="{id}" src="/rul/img/film_add.png" onclick="fancy(this)" title="Загрузить видео" />',
 'mce'=>'<textarea name="'.$pr.'[{name}]" style="width: 98%;" rows="{rows}" wysiwyg="true" id="{id}">{value}</textarea>',
  'color'=>'<span onclick="sel_color(this)"><img width="16" height="16" src="/rul/img/pix.gif" style="background-color:#{value};cursor:pointer;border:solid 1px;"  alt="" /><input type="hidden" name="'.$this->_name('{name}').'" value="{value}"/></span>',
  'icons'=>'<img src="{img}" class="{class}" alt="" onclick="{script}" />',
  'ico'=>'<span class="{class}" title="{name}" onclick="{script}" />{value}</span>',
  'captcha'=>'<img src="{img}" alt="" />&nbsp;<input type="text" value="" name="'.$pr.'[{name}]" class="{class}">',
   );
}
function get_rel($a1,$a2){
    return "";
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
if(!$ar["chek"]) $ar["chek"]=0;
if($ar['ajax']) $this->child[$ar['ajax']]=$ar['name'];
//if(!$ar["div"]){ $ar["div"]=''; $ar["ediv"]='';}
$this->in[$ar["name"]]=$ar;
 }

function add_div($div){
   $this->in['div'.count($this->in)]=array('type'=>'div','value'=>$div,'div'=>'','ediv'=>''); 
}

function get_ar($str){
    global $db;
if(substr($str,0,1)=="\$") return $this->xml[substr($str,1)];
if(substr($str,0,5)=="#sql:"){
    $str=explode(":",$str);
    $str[1]=str_replace('$rel',$this->rel,$str[1]);
  $ret=$db->hash($str[1],0);
  if(isset($str[2])) $ret[0]=$str[2];
  return $ret;  
} 

$r=explode(",",$str);
if(strrpos($r[0],":")===false) return $r;
$ret=array();
foreach($r as $s){
$a=explode(":",$s);
$ret[$a[0]]=$a[1];
}
return $ret;
}

function create_editor($r,$pr="r_"){
	global $_root;
  include_once($_root.ADMIN_CONSOLE.'/fck6/fckeditor.php'); 
  $editor = new FCKeditor($pr."[".$r['name']."]");
	$editor->Width=($r['w'] ? $r['w'] : 300)."px";
	$editor->Height=($r['h'] ? $r['h'] : 300)."px";
	$editor ->BasePath	= ADMIN_CONSOLE."/fck6/";
	$editor->ToolbarSet = ($r['toolbar'] ? $r['toolbar'] : 'Basic');
	$editor->Value = stripslashes($r['value']);
	$descr = $editor->Create() ;
    return $descr;
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
 if ($r["type"]=="hidden") $ret["hidden"]=1;
  if ($r["type"]=="textarea")  $tpl=str_replace(array("{rows}","{cols}"), (isset($r["rows"]) ? array($r["rows"],$r["cols"]) : array(5,80)),$tpl);
 
 if ($r["type"]=="editor")  $tpl=$this->create_editor($r);

 
 if ($r["type"]=="select"){
	 $ret["cont"]='<select class="'.$r['class'].'" name="'.$pr.'['.$r['name'].']" '.$r['script'].'>';
	   if(isset($r["cont"])){
	     if(!is_array($r["cont"])) $r["cont"]=$this->get_ar($r["cont"]);
		  @reset($r["cont"]);
		  while(list($key, $val)=each($r["cont"])){
			$ret["cont"].='<option value="'.$key.'" '.(($key==$r["value"])? 'selected' : '').'>'.$val.'</option>';
			
			}
		}
	 $ret["cont"].='</select>';

	 }elseif($r['type']=='radio'){
	 		 $ret["cont"]='';
	   if(isset($r["cont"])){
	     if(!is_array($r["cont"])) $r["cont"]=$this->get_ar($r["cont"]);
		  @reset($r["cont"]);
		  while(list($key, $val)=each($r["cont"])){
			$ret["cont"].='<input type="radio" name="'.$pr.'['.$r['name'].']" value="'.$key.'" '.(($key==$r["value"])? 'checked' : '').'>'.$val.'<br/>';
			
			}
		}
	 	 	
	 }elseif($r['type']=='icon'){
	   $ret["cont"]=$r['html'];
     }else{  
	 $inp=$tpl;
	 $inp=str_replace(array("{name}","{value}","{class}","{id}","{checked}","{img}","{script}","{rows}"), array($keys, $r["value"],$r["class"],$r["id"],$r['checked'],$r['img'],$r['script'],$r['rows']) ,$inp);
	 $ret["cont"].=$inp;
	 	 }
	 
  if ($r["type"]=="list_checkbox")
    $this->get_listbox($r,$ret);
 
    $ret['div']=$r['div'];
    $ret['ediv']=$r['ediv'];
 	return $ret;
 }
 
 function get_listbox($r,&$ret){
   if(!is_array($r["cont"])) $r["cont"]=$this->get_ar($r["cont"]);
		 $ret["cont"]="";
		 if(!is_array($r["value"])) $r["value"]=explode(",",$r["value"]);
		 		  @reset($r["cont"]);
		  while(list($key, $val)=each($r['cont']))
		    {
			$ret["cont"].='<div><input type="checkbox" '.$r['script'].' class="check" name="'.$this->_name($r['name']).'['.$key.']" value="'.$key.'" '.(in_array($key,$r["value"]) ? 'checked' : '').'>&nbsp;'.$val.'</div>';
		     $ret["cont"].=$this->get_rel($key,$r);
			}	
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

function check_field(){
  global $error, $$this->pr;
    //$r_=check_array($r_);
  $ret=false;
  $error=array();
@reset($this->in);
while (list($key,$r)=@each($this->in)){
$var=stripslashes(${$this->pr}[$key]);
${$this->pr}[$key]=$var;
  if($r["check"]==0) continue;
  
    if($r["check"]==1){
    if(strlen($var)==0){
  		 $error[]=$r["err"];
		  $ret++;
		 }	  
     }elseif($r["check"]==2){
   if(!check_email($var)) {
     $error[]=$r["err"];
	   $ret++;
	  }
   }elseif($r["check"]==3){
   if($var==0) {
     $error[]=$r["err"];
	   $ret++;
	  }
   }elseif($r["check"]==4){
    if(strtolower($var)!==strtolower($_SESSION['captcha'])){
        $error[]=$r["err"];
	   $ret++;
    }
   }
   }
   $this->error=$error;
return $ret;
}


function get_fields(){
$r=array();
@reset($this->in);
while (list($key,$value)=@each($this->in)){
$r[]=$key;
  }
return $r;
}

function get_vars(){
$r=array();
@reset($this->in);
while (list($key,$value)=@each($this->in)){
    $r[$key]=trim($value["value"]);
	}
return $r;
}

		function add_var($ar, $noc=array()){

		@reset($this->in);
while (list($key,$value)=@each($this->in)){
			 if(isset($ar[$key]))  
			 if(!in_array($this->in[$key]["type"],$noc)) $this->in[$key]["value"]=$ar[$key];
			}
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
	
function fill_form($row='INPUT_FORM',$access=0){
	 global $htm;
	 $ar=$this->get();
 
	 foreach($ar as $a){
	 
	 $htm->addrow($row, array(
	   "INPUT" 		=>$a["cont"],
	   "HIDE" 		=>$a["hidden"],
	   "title"		  =>$a["name"],
	   "C" 			 =>$a["check"],
       "DIV"=>isset($a['div']) ? $a['div'] :'',
       "EDIV"=>isset($a['ediv']) ? $a['ediv'] :''
	    	   ));
	 } 
	if($this->picker){
	   $htm->addscript('js',AIN.'/inc/picker/colorpicker.js');
        $htm->addscript('css',AIN.'/inc/picker/picker.css');
	}
}

function fills_form(){
    global $htm;
         $ret='';
         $ar=$this->get();
     foreach($ar as $a){
      $ret.=str_replace(array("{INPUT}","{HIDE}","{title}","{C}","{DIV}","{EDIV}"),array($a['cont'],$a["hidden"],$a["name"],$a["check"],$a["div"],$a["ediv"]),$this->tmpl);  
     }
     	if($this->picker){
	   $htm->addscript('js',AIN.'/inc/picker/colorpicker.js');
        $htm->addscript('css',AIN.'/inc/picker/picker.css');
	}
     return $ret;
}
	

function fill_xml_form($name){
    foreach($this->set as $s){
           
        $name="";
           foreach($s['row'] as $set){
            $name.="<".$set['row']['atr']['tag'].">";
            foreach ($set['set'] as $sets){
             $name.='<'.$sets['atr']['tag'].">".$sets['atr']['titl'].'</'.$sets['atr']['tag'].'>';
            } 
             $name.="</".$set['row']['atr']['tag'].">";
           } 
    
}
return $name;
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

function helper($arg){
    $form=new Form("","","");
    $form->add_ar($arg);
    $r=$form->get_inp($arg['name'],$form->in[$arg['name']]);
    return $r['cont'];
    
}



}

?>