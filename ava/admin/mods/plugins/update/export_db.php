<?php
$orm= new Ormb();
$file=CONFIG_PATH."db.xml";

$orm->create_sheet();
$xml=$orm->make_xml();
file_put_contents($file,$xml);
$log.="экспорт завершен. <br>".$file;

