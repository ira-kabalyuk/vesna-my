<?
$db_xml=CMS_LIBP."mods/skat/db.xml";

if(is_file($db_xml)){
   $orm=new Ormu();
   $orm->parse_sheet($db_xml);
   $htm->assign("MESSAGE",$orm->update_database());
   }
if(!is_dir($_root."katimg")) mkdir($_root."katimg");

?>