<?
/**
 * Mods_design_core
 * 
 * @package   
 * @author SMART
 * @copyright Vladimir
 * @version 2012
 * @access public
 */
class Mods_design_core{
	
	var $file;
	var $tpl;
	var $tref="";
	
	function __construct(){

	}
	




function Start(){
	global $Core,$_root;
	$act=_get('act');
	$ln=$_COOKIE['lang'];
	
	
		$this->pref=$ln."/";
	
	$action=_post('action');
	$this->tpl=str_replace(array("..","/","\\"),"",_get('tpl'));
	
	$this->type=_get('type');
	$this->file=APP_VIEWS.($this->type=='css' ? 'css/':($this->type=='js' ? 'js/':'tpl/'.$this->pref)).$this->tpl;
	
	if($act=='new'){
		$this->new_file();
	}


	if($action=='save'){
		$this->save_tpl();
		$this->get_tpl();
	}elseif($action=='close'){
		$this->save_tpl();
		$this->list_dir();
	}elseif($this->tpl!=''){
		$this->get_tpl();
	}else{
		$this->list_dir();
	}

	
}

function save_tpl(){
		global $htm;
		file_put_contents($this->file,$_POST['kontent']);
		$htm->assign("MESSAGE","страница записана");
	
}

function get_tpl(){
	global $htm;
	
	$htm->external("EXT_ADD",MOD_PATH."templ.tpl");
	if(AJAX) $htm->src(MOD_PATH."templ.tpl");
	
	if($this->tpl!=''){
		if(is_file($this->file)){
		$cont=file_get_contents($this->file);
		$cont=str_replace(array("{","}"),array("{~","~}"),$cont);
		//$descr='<textarea cols="140" rows="50" name="kontent" id="editcont">'.$cont.'</textarea>';
		$htm->assign(array(
		"PATH"=>$this->file,
		"TPL"=>$this->tpl,
		"TYPE"=>$this->type,
		"DESCR"=>htmlspecialchars($cont),
		'CMMODE'=>'htmlembedded'
		));
		
		}else{
				$htm->assign('PATH','не могу прочитать файл:'.$this->file);
		}
	}	
		
	}


/**
 * Выводит список файлов в директори с темплейтами скина
 * */
function list_dir(){
global $htm, $Core,$_root;
$htm->external("EXT_ADD",MOD_PATH."tpl.tpl");
if(AJAX) $htm->src(MOD_PATH."tpl.tpl");
if($this->type=='css'){
	$dir=APP_VIEWS."css/";
}elseif($this->type=="js"){
	$dir=APP_VIEWS."js/";
}else{
	$dir=APP_VIEWS."tpl/".$this->pref;
}
$htm->assign('PATH',$dir);
$dir=$this->walk_dir($dir);
foreach ($dir as $file) {
	
	$name = preg_replace("#//+#", '/', $file);
	$name=substr($name,strrpos($file,"/"));
		if(preg_match("/\.(js|css|tpl)/",$name))
		$htm->addrow("KONTM",array(
		"NAME"=>$name,
		"TYPE"=>$this->type,
		"DESCR"=>$this->get_title($file),
		"ACL"=>ADMIN_CONSOLE,
		 ));
	}

}

function get_title($file){
	$t=file_get_contents($file,false,NULL,0,200);
	preg_match("/<!--([^>]+)(-->)/",$t,$m);
	
	return str_replace("mode","",$m[1]);
}

/**
 * Получаем список файлов в директории
 * 
 * */
function walk_dir($path) {
	if ($dir = opendir($path)) {
		while (false !== ($file = readdir($dir))){
			if ($file[0]==".") continue;
			else if (is_file($path."/".$file))
				$retval[]=$path."/".$file;
			}
		closedir($dir);
		}
		
		asort($retval);
		@reset($retval);
	return $retval;
}

function new_file(){
		global $Core;
	$ret=array('ok'=>false);
	$types=array("css","tpl","js");
	$name=_posts('name');

		if ($name==""){ 
	$ret['msg']="не указано имя файла";
		$Core->ajax_jsont($ret);
	}
	$t=explode(".",$name);
	$name=preg_replace("/[^a-z|0-9|\-|_]/", "", $t[0]);
		if ($name==""){ 
		$ret['msg']="недопустимые символы в имени файла! разрешены только прописные буквы, _ и -";
		$Core->ajax_json($ret);
	}
	$type=$t[1];
	if (!in_array($type, $types)){
		$ret['msg']=$type." неверное расширение файла! ( используйте .tpl, .css, .js)";
		$Core->ajax_json($ret);
	} 
	if($type=='tpl')
	$type.=$this->pref;		
	$file=APP_VIEWS.$type."/".$name.".".$type;

	if(is_file($file)){
		$ret['msg'] ="Файл <b>$name.$type</b> уже существует !";
		$Core->ajax_json($ret);
	}

	file_put_contents($file, '<!-- new file -->');
	$ret['ok']=true;
	$ret['type']=$type;
	$ret['file']=$file;
			$Core->ajax_json($ret);

}
	
}
	
