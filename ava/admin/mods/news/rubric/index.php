<?php
global $htm;
$mod_r= new Mods_news_rubric_admin("news_rubric");
$htm->assign('PID',$parent_id);
$mod_r->modlink=ADMIN_CONSOLE."/?mod=news&sub=rubric";
$htm->assign('MOD_LINK',$mod_r->modlink);

$mod_r->Start('news');
