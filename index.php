<?php
session_start();
error_reporting(E_ALL^E_NOTICE^E_STRICT);
//error_reporting(E_ALL);
$admin_mode=false;
include $_SERVER['DOCUMENT_ROOT']."/ava/conf/config.php";
include CMS_LIBP."core_func.php";
include CMS_MYLIB."class_link.php";


// инициализация роутера

$Core=new Core();
$Core->init();


// проверка , авторизован ли пользователь 
/*
$is_auth=Mods_auth_user::init();	
if(!$is_auth){
	$Core->htm->src(TEMPLATES."login.tpl");

}
*/
//print_r($Core->conf);
//$Core->htm->assign($Core->conf);
// рендеринг страницы
/*
$country=Com_geoip::get_country();
$Core->htm->assign(array(
	'CCODE'=>$country['code'],
	'CNAME'=>$country['name']
	));
*/


$Core->get();



