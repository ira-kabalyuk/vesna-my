<?php

 $sub=_get('sub');
        if($sub!=''){
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR.$sub."/index.php";
        return;
        }
        
$mod=new Com_gallery_admin();

$mod->init(array(
	'title'=>'Фотогалерея',
	'table'=>'fotogal',
	'img_path'=>'uploads/photo', // каталог фотогалерей
	'mod'=>'gallery',
	'mp'=>dirname(__FILE__).DIRECTORY_SEPARATOR,
	'modlink'=>ADMIN_CONSOLE."/?mod=gallery"
	));

$mod->Start();


