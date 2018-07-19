<?php
$mod_name="members";
$mod=new Mods_members_core();
$mod->modlink=ADMIN_CONSOLE."/?mod=".$mod_name."&page="._getn('page');
$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;

$mod->Start($mod_name);