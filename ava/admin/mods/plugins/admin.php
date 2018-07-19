<?
define('PLUGIN_PATH',dirname(__FILE__)."/");

$plugin=trim($_GET['plu']);
if($plugin==''){
    $list=load_xml_file(PLUGIN_PATH."plugins.xml");
    
    $htm->external("EXT_ADD",PLUGIN_PATH."list.tpl");
  
        $htm->maprow("PLUGIN_LIST",$list);
   
    
}else{
   $plugin=trim($plugin);
   define('MOD_PATH',dirname(__FILE__)."/".$plugin."/");
if(is_file(MOD_PATH."index.php")){
    
    include_once MOD_PATH."index.php";
    
}else{
    echo "<p>Плагин не установлен</p>";
} 
}


?>