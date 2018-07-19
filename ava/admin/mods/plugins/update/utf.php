<?
/**
 * Конвертирование таблиц в utf-8
 * */
$log="Перевод баз в utf-8<br>";
 	$table=$db->vector("SHOW TABLES");
	foreach($table as $t){
		$db->execute("ALTER TABLE `".$t."` CONVERT TO CHARACTER SET 'utf8'");

	}
	$log="Конвертировано ".count($table)." таблиц <br>";
?>