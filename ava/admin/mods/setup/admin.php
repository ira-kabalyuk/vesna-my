<?php

$mod='setup';
$type=_get('type');
$parent=_gets('parent');
$modlink=ADMIN_CONSOLE."/?mod=".$mod."&lang=".$Lang.'&type='.$type."&parent=".$parent;

$htm->assign(array(
'MOD'=>$mod,
'LANG'=>$Lang,
'MOD_LINK'=>$modlink,
'TITLE_CONTENT'=>'Настройки сайта'
));
$setup=new Mods_setup_core();
$setup->admin_setup(_get('type'),$parent);

	 