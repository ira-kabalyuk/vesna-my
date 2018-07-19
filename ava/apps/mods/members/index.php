<?php

global $Core;
$mod=new Mods_members_core();

if($mod->route()) return;

$act=_gets('act');

if($act=="add_item"){
	$ret=$mod->add_user();
	$Core->ajax_get($ret);
}elseif($act=='save_staff'){
	$Core->ajax_get($mod->update_user(_getn('id')));
}else{
	$Core->ajax_get("bad request");
}