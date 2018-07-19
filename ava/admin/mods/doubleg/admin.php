<?php
/**
 * Модуль Doble gallery
 */

$mod=new Mods_doubleg_core();

$mod->init(array(
	'title'=>'Галерея2',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'doubleg',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



