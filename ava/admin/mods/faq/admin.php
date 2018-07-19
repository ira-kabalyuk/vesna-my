<?php
/**
 * Модуль Вопросы
 */

$mod=new Mods_faq_core();

$mod->init(array(
	'title'=>'Вопросы',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'faq',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



