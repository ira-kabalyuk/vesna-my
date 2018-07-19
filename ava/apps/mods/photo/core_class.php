<?php
class Mods_photo_core{

static function gallery($rid,$limit){
	global $db,$htm;

	$ret=$htm->load_tpl("photo.tpl");
	$sql="select id, img, descr as title from fotogal where is_hidden=0 and cat like '%cat_$rid %' order by sort limit 0,$limit";

	$db->maprow("GALLERY_ROW",$sql);

	$htm->assvar(array(
		'RID'=>$rid
		));
$htm->_row($ret,true);
$htm->_var($ret);
return $ret;


}

}