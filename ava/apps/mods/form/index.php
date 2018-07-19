<?php
global $db,$htm;
$act=_gets('act');
$fid=_getn('fid');
if($fid==0) return;

$htm->src(TEMPLATES."ajax.tpl");
$set=$db->get_rec("form_name where id=$fid");
//print_r($set);

if($act==""){
	
	$htm->assign("HTML_CONTENT",Mods_form_help::get_form($set));

}elseif($act=='save'){
	$htm->assign("HTML_CONTENT",Mods_form_help::_save($set));
	
}