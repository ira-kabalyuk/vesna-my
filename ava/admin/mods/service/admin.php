<?php
/**
 * Модуль Услуги
 */

$mod=new Mods_service_core();

$mod->init(array(
	'title'=>'Услуги',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'service',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



