<?
$orm= new Ormu();
$file=CONFIG_PATH."db.xml";
if(!is_file($file)){
	$log="Нет файла структуры базы ";
}else{
	$orm->parse_sheet($file);
	
$log=$orm->update_database();

$log.="<br><b>Обновление базы завершено. </b>";
}
?>