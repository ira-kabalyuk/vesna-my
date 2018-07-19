<?php
global $Core;
$modlink=ADMIN_CONSOLE."/?mod=skat&sub=dirs";

$Core->htm->assvar("MOD_LINK",$modlink);
if(_getn('pid')==0){
	$mod=new Mods_skat_dirs_admin('#div_content');
}else{
	$mod=new Mods_skat_dirs_items('#div_content');
	$mod->modlink=$modlink;
}
$Core->ajax_get($mod->Start());

