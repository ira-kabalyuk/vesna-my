<?php
global $Core;
$mmem=new Mods_members_core();

$act=_gets('act');

if($act=="add_item"){
	$ret=$mod->add_user();
	$Core->ajax_get($ret);
}else{
	$Core->ajax_get("bad request");
}