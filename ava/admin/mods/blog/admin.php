<?php
/**
 * Модуль Блоги
 */

$mod=new Mods_blog_core();

$mod->init(array(
	'title'=>'Блог',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'blog',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



