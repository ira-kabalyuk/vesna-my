<?php
/**
 * Модуль Блоги
 */

$mod=new Mods_news_core();

$mod->init(array(
	'title'=>'Новости',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'news',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



