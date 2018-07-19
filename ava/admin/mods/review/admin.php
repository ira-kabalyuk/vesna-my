<?php
/**
 * Модуль Review
 */

$mod=new Mods_review_core();

$mod->init(array(
	'title'=>'Отзывы',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'review',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



