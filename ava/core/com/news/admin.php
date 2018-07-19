<?php
/**
 * Модуль новостей (базовый модуль)
 */

$news=new Com_news_core();
$news->init(array(
	'table'=>'news',
	'img_path'=>'images/news',
	'mod'=>'news',
	'pid'=>0
	));

$news->Start();


