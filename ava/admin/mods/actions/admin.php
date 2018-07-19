<?php
/**
 * Модуль Акции
 */

$mod=new Mods_actions_core();

$mod->init(array(
	'title'=>'Акции',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'actions',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



