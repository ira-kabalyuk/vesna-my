<?php
/**
 * Модуль Специалисты
 */

$mod=new Mods_master_core();

$mod->init(array(
	'title'=>'Специалисты',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'master',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



