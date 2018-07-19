<?

define('MOD_PATH',dirname(__FILE__)."/");
$mod='design';
$modlink=ADMIN_CONSOLE."/?mod=".$mod."&lang=".$Lang;
define('SKIN',$Core->config['skin']."/");



$htm->assign(array(
'MOD'=>$mod,
'LANG'=>$Lang,
'MOD_LINK'=>$modlink,
'CRUMBS'=>'<a href="'.$modlink.'">Страницы</a>'
));



$htm->addscript("js","/inc/cm/lib/codemirror.js");
	//$htm->addscript("js","/inc/cm/mode/htmlembedded/htmlembedded.js");
	//$htm->addscript("js","/inc/cm/mode/htmlmixed/htmlmixed.js");
	$htm->addscript("js","/inc/cm/mode/xml/xml.js");
	//$htm->addscript("js","/inc/cm/mode/javascript/javascript.js");
	$htm->addscript("js","/inc/cm/lib/util/overlay.js");
	$htm->addscript("js","/inc/cm/lib/util/foldcode.js");
	$htm->addscript("js","/inc/cm/mode/css/css.js");
	$htm->addscript("css","/inc/cm/lib/codemirror.css");

$design=new Mods_design_core();
$design->Start();




?>