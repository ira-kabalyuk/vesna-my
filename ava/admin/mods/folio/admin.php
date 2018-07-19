<?php
/**
 * Модуль Ппортфолио
 */

$mod=new Mods_folio_core();

$mod->init(array(
	'title'=>'Портфолио',
	'table'=>'news',
	'img_path'=>'images/news', // каталог фотогалерей
	'mod'=>'folio',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR
	));

$mod->Start();



