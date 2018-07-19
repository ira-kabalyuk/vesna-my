<?
$htm->external("EXT_RAZD",CMS_RUL_TPL."tpl.tpl");
$htm->external("EXT_ADD",CMS_RUL_TPL."templ.tpl");
if(defined('UPTOOLS')){
	$htm->src(CMS_RUL_TPL."templ.tpl");
	ajax_header();
}

//echo $data_file;


    $data_file=preg_replace("/(\.\.)/","",$data_file);
    $data_file=$_root."templ/".SKIN.$data_file;
if($_POST['action']=="save"){
//$cont=str_replace(array("[","]"), array("{","}"), $_POST["kontent"]);
//$cont=str_replace(array("{{","}}"), array("[[","]]"), $cont);
$cont=$_POST['kontent'];
file_put_contents($data_file,stripslashes($cont));
$htm->assign("MESSAGE","страница записана");

}
//echo $_root.$data_file;
if(is_file($data_file)){
$cont=file_get_contents($data_file);
}
$cont=str_replace(array("{","}"),array("{~","~}"),$cont);

//$cont=htmlspecialchars($cont);
//if(isset($_GET["htm"])){
//
//$fckeditor_dir = "rul/fck6/";
//include $_root.$fckeditor_dir."fckeditor.php";
//$sBasePath=$_SERVR['DOCUMENT_ROOT']."{AIN}/fck6/";
//$editor = new FCKeditor('kontent');
//	$editor->Width="1000px";
//	$editor->Height="800px";
//	$editor ->BasePath	= $sBasePath ;
//	$editor->Value = stripslashes($cont);
//	$descr = $editor->Create() ;
//}else{
$descr='<textarea cols="130" rows="50" name="kontent" id="editcont">'.$cont.'</textarea>';
//}
$htm->addrow("SCRIPT_ADD",array("SCRIPT"=>"/inc/ea/edit_area_full.js"));
$htm->assign(array(
"DESCR"=>$descr,
"DID"=>$id,
"FCK"=>(isset($_GET["htm"]) ? 1:0),
));
$descr=0;

?>