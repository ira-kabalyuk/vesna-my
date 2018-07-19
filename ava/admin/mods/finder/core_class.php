<?
/**
 * Mods_finder_core
 * 
 * @package   
 * @author SMART
 * @copyright Vladimir
 * @version 2014
 * @access public
 */
class Mods_finder_core{
	
	var $fname;
	var $path;
	var $mp;
	var $url;
	
	
	function __construct(){

	}
	




function Start(){
	global $Core,$_root;
	$this->mp=dirname(__FILE__)."/";
	$act=_get('act');
	$action=_post('action');
	$this->fname=str_replace(array("..","/","\\"),"",_get('fname'));
	
	


	if($action=='upload'){
		$this->upload_file();
		
	}elseif($action=='delete'){
		$this->delete_file();

	}
	

	$this->list_dir();
	

	
}

function upload_file(){
		$up=new Upload();
		$up->ext=array("jpg","gif","bmp","png","swf","jpeg","pdf","txt","doc","docx","xls","xlsx","html","mp3","avi","mp4","ogv","ogg");
		$res=$up->my_upload(array(
        'kat'   =>$this->path,     //каталог загрузки 
        'fname' =>'file',   //OST-имя файла
        'name'  => $this->fname,    //новое имя файла (если пусто, имя не меняется)
        'rnd'   =>0,    //    если 1 то генерится новое имя файла
        'rx'    =>0,
        'ry'    =>0, //      ресайзинг по y
        'prop'=>true,
        ));
		// /print_r($res);
	
}




/**
 * Выводит список файлов в директори 
 * */
function list_dir(){
global $htm, $Core,$_root;
$htm->external("EXT_ADD",$this->mp."list.tpl");
if(AJAX) $htm->src($this->mp."list.tpl");

$dir=$_root.$this->path."/";

$htm->assign('PATH',$dir);
$dir=$this->walk_dir($dir);
foreach ($dir as $file) {
	
	$name = preg_replace("#//+#", '/', $file);
	$name=substr($name,strrpos($file,"/"));
		$htm->addrow("KONTM",array(
		"NAME"=>$name,
		"link"=>$this->url."/".$name,
		"ACL"=>ADMIN_CONSOLE,
		 ));
	}

}



/**
 * Получаем список файлов в директории
 * 
 * */
function walk_dir($path) {
	$retval=array();
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

function delete_file(){
	global $_root;
	$name=_posts('fname');
	$file=$_root.$this->path."/"._posts('fname');
	if(is_file($file)) unlink($file);
}



	
}
	
