<?
$htm->external("KONT_EXT",CMS_RUL_TPL."editcss.tpl");
if(defined('UPTOOLS')){
	$htm->src(CMS_RUL_TPL."editcss.tpl");
	ajax_header();
}
$data_file=preg_replace("/(\.\.)/","",$data_file);

$data_file=APP_VIEWS."css/".$data_file;
$action=trim($_POST['action']);
if(check_file($data_file)){
if($action=="save"){
$cont=str_replace(array("[","]"), array("{","}"), $_POST["kontent"]);

 file_put_contents($data_file,stripslashes($cont)) ;
$htm->assign("MESSAGE","страница записана");

}

$cont=file_get_contents($data_file);
}else{
echo "no access";
}
//$cont=str_replace(array("{","}"),array("[","]"),$cont);
//$cont=htmlspecialchars($cont);
//$htm->addscript('js',AIN.'/inc/ea/edit_area_full.js');
$htm->addrow("SCRIPT_ADD",array("SCRIPT"=>"/inc/ea/edit_area_full.js"));
//echo AIN.'/inc/ea/edit_area_full.js';
$descr='<textarea cols="120" rows="40" name="kontent" id="css" style="font-size:9pt;">'.$cont.'</textarea>';

$htm->assign(array(
"DESCR"=>$descr,
"DID"=>$id,
"FCK"=>(isset($_GET["htm"]) ? 1:0),
));
$descr=0;

?>