<?php
$htm->addscript("js",AIN."/js/mods/htm/mod_htm.js");
$htm->addscript("js","/skin/admin/js/pretty/pretty.js");
$htm->addscript("js","/inc/ajaxupload.js");
$htm->addscript("css","/skin/admin/js/pretty/pretty.css");

$mod_static=new Mods_html_static();
$mod_static->modlink=ADMIN_CONSOLE."/?mod=html&lang=".$Lang;
$mod_static->Start();



