<?php
global $htm;
$mod_r= new Mods_service_rubric_admin("news_rubric");
$htm->assign('PID',$parent_id);
$mod_r->modlink=ADMIN_CONSOLE."/?mod=service&sub=rubric";
$htm->assign('MOD_LINK',$mod_r->modlink);

$mod_r->Start('service');
