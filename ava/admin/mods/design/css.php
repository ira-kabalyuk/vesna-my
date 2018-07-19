<?
$htm->external("EXT_ADD",CMS_RUL_TPL."css.tpl");
$htm->assign('TITLE','Include '.SKIN);
$men=array();

$act=my_vars('action');
$cont=my_vars('cont');
$page_id=my_vars('id');
$edit=intval(my_vars('edit'));
$id=my_vars('id');
$subf=my_vars('subf');
$subid=intval(my_vars('subid'));
$tpl=my_vars('tpl');
//$arm=load_ar("conf/page.cfg");



function walk_dir($path) {

	if ($dir = opendir($path)) {
		while (false !== ($file = readdir($dir)))
		{
			if ($file[0]==".") continue;
			//if (is_dir($path."/".$file))				$retval = array_merge($retval,walk_dir($path."/".$file));
			else if (is_file($path."/".$file))
				$retval[]=$path."/".$file;
			}
		closedir($dir);
		}
		
		asort($retval);
		@reset($retval);
	return $retval;
}

function kont_menu(){
global $htm, $arm;
$dir=$_SERVER["DOCUMENT_ROOT"]."/skin/".SKIN;
foreach (walk_dir($dir) as $file) {
	$file = preg_replace("#//+#", '/', $file);
	$name=substr($file,strrpos($file,"/")+1);
	if(preg_match("/\.(js|css)/",$name))
		$htm->addrow("KONTM",array(
		"TITL"=>$name,
		"ACL"=>ADMIN_CONSOLE,
		 ));
	}

}


kont_menu();


if($_GET['tpl']){
$data_file=$_GET['tpl'];
$htm->assign("data_file",$tpl);
include CMS_LIBP."csstpl.php";
}




?>
