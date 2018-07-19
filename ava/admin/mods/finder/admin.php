<?php
/**
 * Менеджер файлов
 * 
 * */
$mod='finder';
$modlink=ADMIN_CONSOLE."/?mod=".$mod."&lang=".$Lang;
$dir="upload";


$htm->assign(array(
'MOD'=>$mod,
'LANG'=>$Lang,
'MOD_LINK'=>$modlink,
'CRUMBS'=>'<a href="'.$modlink.'">Файловый мнеджер</a>'
));



	
$finder=new Mods_finder_core();
$finder->path=$dir;
$finder->url=$this->config['url_site']."/".$dir;
$finder->Start();

