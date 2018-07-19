<?
$htm->external("EXT_ADD",MOD_PATH."index.tpl");
$modlink=ADMIN_CONSOLE."/?mod=plugins&plu=".$plugin;
$htm->assign(array(
'MOD_LINK'=>$modlink,
));
$log='';

if(isset($_GET['act'])){
	$act=preg_replace("/[^(a-z|_)]/","",$_GET['act']);
if(is_file(MOD_PATH.$act.".php"))
include_once(MOD_PATH.$act.".php");	
	
}
$htm->assign('LOG',$log);



?>