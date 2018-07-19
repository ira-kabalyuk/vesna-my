<?
class Tmpl{
var $vals;
var $tpl;
var $error;
var $cf;
var $rows;
var $path;
var $src;
var $ext;
var $block;
var $define;
var $ex;
var $var;
var $stpl;

private $row=array("js"=>array(),"css"=>array(),"jsbody"=>array());

function __construct($file=""){
	
$this->vars=array();
$this->vals=array();
$this->ext=array();
$this->error=0;
$this->block=array();
$this->stpl=(_getn('tpl')==1 ? true:false);
if($file!=''){
	if(!file_exists($file)){
		
	echo "отсутствует файл  ".$file;
	return false;
	}

$fl=fopen($file,"r");
$this->tpl=fread($fl, filesize($file));
fclose($fl); 
$this->src = $file;
$fn = explode("/", $file);
$this->path = join("/", array_slice($fn, 0, sizeof($fn)-1));
}
}
function _wrap($content,$info){
	if(!$this->stpl) return $content;
	return '<div class="debug"><div class="debug_info">'.$info.'</div>'.$content.'</div>';
}
function load_tpl($file){
    global $Core;
	$f=TEMPLATES.$Core->link->lang."/".$file;
	if(is_file($f))
		return $this->_wrap(file_get_contents($f),'tpl:'.$file);
		return $file.' not found ';
}

function src($file){
	global $Core;
	if(!is_file($file)){
		if($Core->debug) $Core->logt[]=array($file,'src,false');
	//echo "отсутствует файл  ".$file;
	return false;
	}
if($Core->debug) $Core->logt[]=array($file,'ok');    
$this->tpl=file_get_contents($file);
$this->src = $file;
$fn = explode("/", $file);
$this->path = join("/", array_slice($fn, 0, sizeof($fn)-1));
}

function external($name, $file){
$this->ext[$name]=$file;		
}

 function ext($name, $cont){
$this->ex[$name]=$cont;		
}
function _crow($arg){
	if(is_array($arg)){
		foreach($arg as $k)
		unset($this->rows[$k]);
	}else{
		unset($this->rows[$arg]);
	}
}

function addblock($name,$vars,$file){
	if(!is_file($file)) return;
	$block=file_get_contents($file);
	$block=str_replace(array_keys($vars),$vars,$block);
	if(isset($this->block[$name])){
			$this->block[$name].=$block;
	}else{
			$this->block[$name]=$block;
	}
	
}


function assign($name, $var=""){
if(is_array($name)){
@reset($name);
while(list($key,$val)=each($name)){
   $this->vals[$key]=$val;
   }
}else{
$this->vals[$name]=$var;
}
 }
 
function assvar($name, $var=""){
if(is_array($name)){
@reset($name);
while(list($key,$val)=each($name)){
   $this->var["{".$key."}"]=$val;
   }
}else{
$this->var["{".$name."}"]=$var;
}
 } 
 
function def($name, $var=""){
if(is_array($name)){
@reset($name);
while(list($key,$val)=each($name)){
   $this->define[$key]=$val;
   }
}else{
$this->define[$name]=$var;
}
 }
 
 
function assar( $array){
$this->cf=$array;
 }
	function addrow($name,$arr) {
		if (!is_array($arr)) return;
		if (is_array($name))
			for ($i=0; $i<sizeof($name); $i++)
				$this->rows[$name[$i]][] = $arr;
		else
			$this->rows[$name][] = $arr;
	}
	
   
	
function maprow($name,$ar){$this->rows[$name] = $ar;}

function check_Srow($_rcont,$srow){

	//$_rcont,$this->rows[$rname][$j]
	$fc=$_rcont;
		if (strpos(" ".$fc,"{srow:")>0) {
			$items = explode("{srow:", $fc);
			$str = $items[0];
			for ($i=1;$i<sizeof($items);$i++) {
				$its = explode("}", $items[$i]);
				$rname = $its[0];
				$rcont = join("}", array_slice($its, 1));
				$rcont = str_replace("\r","", $rcont);
				if (substr($rcont,0,1)=="\n") $rcont = substr($rcont,1);
				list($rcont,$fc1) = explode("{/srow}", $rcont, 2);
				for ($j=0;$j<sizeof($srow[$rname]);$j++) {
					if (!is_array($srow[$rname][$j])) continue;
					$row = $rcont;
				
					@reset($srow[$rname][$j]);
					while (list($k,$v)=@each($srow[$rname][$j])) {
						$row = str_replace('{'.$k.'}', $v, $row);
					}
					$row = preg_replace("/\{[\w\d]+\}/", "", $row);
					$str .= $row;
	
	}
	$fc1 = str_replace("\r","", $fc1);
	if (substr($fc1,0,1)=="\n") $fc1 = substr($fc1,1);
				$str .= trim($fc1);
	}
	$fc=$str;
	
}
return $fc;
}

function get(){
    $this->_include($this->tpl);
    $this->_external($this->tpl);
    $this->_include($this->tpl);
    $this->_ext($this->tpl);
    $this->_external($this->tpl);
    //$this->_block($this->tpl);
    $this->_mods($this->tpl);
    $this->_row($this->tpl);
    $this->_if($this->tpl);
    $this->_def($this->tpl);
    $this->_mod($this->tpl);
    $this->_mods($this->tpl);
    $this->get_js();
    $this->get_jsbody();
    $this->_val($this->tpl);
    $this->_final($this->tpl);
    
return $this->tpl;
}
function fast_get(&$content){
    $this->_mod($content);
    $this->get_js();
    $this->get_jsbody();
    $this->_val($content);
}

function _include(&$fc){
    while (strpos(" ".$fc,"{include:")>0) {
			$items = explode("{include:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("}", $items[$i]);
				if (is_file($this->path."/".$its[0]))
					$str .= $this->_wrap(file_get_contents($this->path."/".$its[0]),'tpl:'.$its[0]);
				else
					$str .= "tmplate error: can't include ".$this->path."/".$its[0]." in ".$this->src.", file not found";
				$str .= join("}", array_slice($its, 1));
			}
			$fc = $str;
		}
    
}
function _include_(&$fc){

    while (strpos(" ".$fc,"[include:")>0) {
			$items = explode("[include:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("]", $items[$i]);
				if (is_file($this->path."/".$its[0]))
					$str .= join("\n", file($this->path."/".$its[0]));
				else
					$str .= "tmplate error: can't include ".$this->path."/".$its[0]." in ".$this->src.", file not found";
				$str .= join("]", array_slice($its, 1));
			}
			$fc = $str;
		}
    
}
function _external(&$fc){
	global $Core;
    while (strpos(" ".$fc,"{external:")>0) {
			$items = explode("{external:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("}", $items[$i]);
				if(isset($this->ext[$its[0]])){
				$file=$this->ext[$its[0]];
				$f=explode("/",$file);
				if (is_file($file)){
					if($Core->debug) $Core->logt[]=array($its[0].":".$file,'external, ok');
					$str .= $this->_wrap(file_get_contents($file)," ext:".end($f));
				}else{
					if($Core->debug) $Core->logt[]=array($its[0].":".$file,'external, false');
				}	
				}	
				$str .= join("}", array_slice($its, 1));
			}
			$fc = $str;
		}		
    
}	

function _ext(&$fc){	
		
while (strpos(" ".$fc,"{ext:")>0) {
			$items = explode("{ext:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("}", $items[$i]);
				$str .= $this->ex[$its[0]];
				$str .= join("}", array_slice($its, 1));
			}
			$fc = $str;
		}	
}			

function _block(&$fc){
    while (strpos(" ".$fc,"{block:")>0){
			$items = explode("{block:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("}", $items[$i]);
				$str .= $this->block[$its[0]];
				$str .= join("}", array_slice($its, 1));
			}
			$fc = $str;
		}
    
}

/**
 * Tmpl::_row()
 * 
 * @param mixed $fc
 * @param bool $clear (true - удалить массив после рендеринга)
 * @return void
 */
function _row(&$fc,$clear=false){
    	//row
		
		if (strpos(" ".$fc,"{row:")>0) {
			$items = explode("{row:", $fc);
			$str = $items[0];
			for ($i=1;$i<sizeof($items);$i++) {
				$its = explode("}", $items[$i]);
				$rname = $its[0];
				$rcont = join("}", array_slice($its, 1));
				$rcont = str_replace("\r","", $rcont);
				if (substr($rcont,0,1)=="\n") $rcont = substr($rcont,1);
				list($rcont,$fc1) = explode("{/row}", $rcont, 2);
				for ($j=0;$j<sizeof($this->rows[$rname]);$j++) {
					if (!is_array($this->rows[$rname][$j])) continue;
					$row = $rcont;
					$row=$this->check_Srow($row,$this->rows[$rname][$j]);

					if (strpos(" ".$row,"{iif:")>0) {
						$_items = explode("{iif:", $row);
						$_str = $_items[0];
						for ($_i=1;$_i<sizeof($_items);$_i++) {
							$_its = explode("}", $_items[$_i]);
							$_rname = $_its[0];
							$_rcont = join("}", array_slice($_its, 1));
							list($_rcont,$_fc1) = explode("{/iif}", $_rcont, 2);
						if (substr($_rname,0,1)=="!") {
							 if (!$this->vals[substr($_rname,1)])
									$_str .= $_rcont;
							}else{
							if ($this->vals[$_rname])
								$_str .= $_rcont; // возможно проветь на вхождение srow
       	                }
								if (substr($_fc1,0,1)=="\n") $_fc1 = substr($_fc1,1);
                              
							$_str .= $_fc1;
                            
						}
						$row = $_str;
					}//end iif


					if (strpos(" ".$row,"{if:")>0) {
						$_items = explode("{if:", $row);
						$_str = $_items[0];
						for ($_i=1;$_i<sizeof($_items);$_i++) {
							$_its = explode("}", $_items[$_i]);
							$_rname = $_its[0];
							$_rcont = join("}", array_slice($_its, 1));
							list($_rcont,$_fc1) = explode("{/if}", $_rcont, 2);
						if (substr($_rname,0,1)=="!") {
							 if (!$this->rows[$rname][$j][substr($_rname,1)])
									$_str .= $_rcont;
							}else{
							if ($this->rows[$rname][$j][$_rname])
								$_str .= $_rcont; // возможно проветь на вхождение srow
       	                }
								if (substr($_fc1,0,1)=="\n") $_fc1 = substr($_fc1,1);
                              
							$_str .= $_fc1;
                            
						}
						$row = $_str;
					}//end if
					 

					@reset($this->rows[$rname][$j]);
					while (list($k,$v)=@each($this->rows[$rname][$j])) {
						$row = str_replace('{'.$k.'}', $v, $row);
					}
				//	$row = preg_replace("/\{[\w\d]+\}/", "", $row);

					$str .= $row;
				}
				$fc1 = str_replace("\r","", $fc1);
				if (substr($fc1,0,1)=="\n") $fc1 = substr($fc1,1);
				$str .= trim($fc1);
			if($clear) unset($this->rows[$rname]);
			}
		
			$fc = $str;
				
		}		
	
// end row
}

function _if(&$fc){
  		if (strpos(" ".$fc, "{if:")>0) {
			$items = explode("{if:", $fc);
			$str = $items[0];
			for ($i=1;$i<sizeof($items);$i++) {
				$its = explode("}", $items[$i]);
				$rname = $its[0];
				$rcont = join("}", array_slice($its, 1));
				list($rcont,$fc1) = explode("{/if}", $rcont,2);
				if (substr($rname,0,1)=="!") {
					if (!$this->vals[substr($rname,1)])
						$str .= $rcont;
				} else if ($this->vals[$rname])
					$str .= $rcont;
				else 
					if (substr($fc1,0,1)=="\n") $fc1 = substr($fc1,1);
				$str .= $fc1;
			}
		
			$fc = $str;
				
		}  
  
}	

function _ifv(&$fc){
  		if (strpos(" ".$fc, "{ifv:")>0) {
			$items = explode("{ifv:", $fc);
			$str = $items[0];
			for ($i=1;$i<sizeof($items);$i++) {
				$its = explode("}", $items[$i]);
				$rname = $its[0];
				$rcont = join("}", array_slice($its, 1));
				list($rcont,$fc1) = explode("{/ifv}", $rcont,2);
				if (substr($rname,0,1)=="!") {
					if (!$this->var["{".substr($rname,1)]."}")
						$str .= $rcont;
				} else if ($this->var["{".$rname."}"])
					$str .= $rcont;
				else 
					if (substr($fc1,0,1)=="\n") $fc1 = substr($fc1,1);
				$str .= $fc1;
			}
		
			$fc = $str;
				
		}  
  
}	
		
function _val(&$fc){
    if (count($this->vals)) {
			foreach($this->vals as $key=>$value)
				$fc = str_replace("{".$key."}", $value, $fc);
       }
    
}
function _var(&$fc,$clear=true){
    if (is_array($this->var)) {
			reset($this->var);
				$fc = str_replace(array_keys($this->var), $this->var, $fc);
         }
   if($clear) $this->var=array(); 
}  
      

        
		
function _def(&$fc){
    if (is_array($this->define) && !$error) {
			reset($this->define);
			$i=0;
			$mas=preg_match_all("/{def:[\w\d]*?}/",$fc,$matches);
			if(is_array($matches)){
			foreach($matches[0] as $r) {
				$key=substr($r,5,strlen($r)-6);
				//echo $r."=".$key."<br>";
				$fc = str_replace("{def:".$key."}", $this->define[$key], $fc);
			
			
			}
			}
	}
    
}			
function _mods(&$fc){
     while (strpos(" ".$fc,"[mods:")>0){
			$items = explode("[mods:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("]", $items[$i]);
				$str .= $this->_compile($its[0]);
				$str .= join("]", array_slice($its, 1));
			}
			$fc = $str;
		}
}
function _compile($mod){
	$ret='';
	$eval="\$ret=Mods_$mod;";
	eval($eval);
	return $ret;
}
function _mod(&$fc){
     while (strpos(" ".$fc,"[[mode:")>0){
			$items = explode("[[mode:",$fc);
			$str = $items[0];
			for ($i=1; $i<sizeof($items); $i++) {
				$its = explode("]]", $items[$i]);
				$str .= $this->_CMOD('mode:'.$its[0]);
				$str .= join("]]", array_slice($its, 1));
			}
			$fc = $str;
		}
}	


function _CMOD($arg,$var=null){
    global $Core;
  
    $arg=explode(":",$arg);
    $mod_id=intval($arg[2]);
    if($mod_id==0){
    	$class_name="Vijets_".$arg[1]."_core";
	  	$class= new $class_name;
	  	return $class->_get();
    };
    $cach=CashControl::_get($mod_id);
    if($cach) return $cach;
    $mods=$Core->db->get_rec("vijets where id=".$mod_id." and lang='".$Core->ln."'");   
	  $ret="";
    if($mods['mod_id']=='') return "Модуль ".$mod_id." отсутствует";
    if($mods['is_hidden']==1) return "<!--hide $mod_id-->";
	  $set=unserialize($mods['params']);
	  $set['mod_name']=$arg[1];
  		$mod=$mods['mod_id'];
    $class_name="Vijets_".$mod."_core";
	  $class= new $class_name;
   if(isset($arg[3])){
        $this->ex[$arg[3]]=$class->_get($mod_id,$set);
        }else{
        return $class->_get($mod_id,$set);
        }
  }	
   
       function addscript($type,$var){
         if(in_array($var,$this->row[$type])) return false;
            $this->row[$type][]=$var;
             
           return true;
        }
     function get_js(){
        $ret="";
       
    if(count($this->row['js'])) $ret.='<script language="JavaScript" type="text/javascript" src="'.implode('"></script><script language="JavaScript" type="text/javascript" src="',$this->row['js']).'"></script>';
    if(count($this->row['css'])) $ret.='<link href="'.(implode('" rel="stylesheet" type="text/css"/><link href="',$this->row['css'])).'" rel="stylesheet" type="text/css"/>';
    $this->vals['HEAD_SCRIPTS']=$ret;
    }
  function get_jsbody(){
  
    if(count($this->row['jsbody'])==0) return '';
    $ret='<script type="text/javascript">
    $(document).ready(function(){
       '.implode("\r\n",$this->row['jsbody']).' 
    });
    </script>'; 
    $this->vals['BODY_SCRIPTS']=$ret;;
 } 
    
function clear_comment(&$fc){
	$fc = preg_replace("/<![^>]*?>/", "", $fc);
}
    
function _final(&$fc){
    $uri=$_SERVER['REQUEST_URI'];

    $host="http://".$_SERVER['HTTP_HOST'];
    $fc = preg_replace("/{[\.\:\w\d]*?}/", "", $fc);
    // уберем ссылки сами на себя
    //$fc=str_replace('href="'.$uri.'"', "", $fc);
    //$fc=str_replace(array('http="/','src="/'), $host, $fc);


  // $fc = preg_replace("/\n+/", "\n", str_replace("\r","",$fc));
}	
		
		


}

?>
