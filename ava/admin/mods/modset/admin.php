<?php
global $Core;



class Modset{

function Start(){
	global $db,$htm;
	$this->mp=dirname(__FILE__)."/";

$htm->external("EXT_ADD",$this->mp."admin.tpl");
$htm->external("EXT_RAZD",$this->mp."submenu.tpl");
$res=$db->select("select mods,title,rubr from mods where is_hidden=0 order by title");
$htm->addscript("js","/inc/ajaxupload.js");
$htm->addscript("js","/skin/admin/js/pretty/pretty.js");
$htm->addscript("css","/skin/admin/js/pretty/pretty.css");

foreach($res as $r){
	$htm->addrow('SETLIST',$r);


}


}

}

$mod=new Modset;
$mod->Start();