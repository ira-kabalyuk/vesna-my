<?
$file=MOD_PATH."test.xml";
//$xml=load_xml_file(MOD_PATH."test.xml");
//$xml = simplexml_load_file($file);

include_once(MOD_PATH."class_ormb.php");
include_once(MOD_PATH."class_ormu.php");
$orm=new Ormu();
$orm->debug=false;
//$orm->create_sheet();
$orm->parse_sheet($file);
//$log=$orm->create_table('uktest_info');
$sql=$orm->check_table_structure('uktest_info',true,'+');
$log=$sql."<br>";
$db->execute($sql);
$log.=mysql_error();
//$log=nl2br(htmlspecialchars( $orm->make_xml()));
/*
$orm->parse_sheet($file);
print_r($orm->db);
echo "<br>";
print_r($orm->tables);
echo "<br>";
print_r($orm->fields);
*/
?>