<?php
global $db;
if($args[1]=='update'){
	// обновление товара
	$id=$args[2];
	$l=Mods_skat_params::get_tov_param($id,intval(LABELS));
	$b=Mods_skat_params::get_tov_param($id,intval(BRANDS));
	
	$db->execute("update skat set label=$l, brand_id=$b where id=$id");
	
	
	// обновим данные каresтегория - бренд для выбранного бренда
	$db->execute("delete from skat_tobrands where brand_id=$b");
	$res=$db->vector("select categories from skat where is_hidden=0 and brand_id=".$b);
	$cats=array();
	foreach($res as $r)
		$cats=array_merge($cats,Mods_skat_params::get_params($r));
	$cats=array_unique($cats);
	foreach($cats as $r)
		$db->execute("insert into skat_tobrands (cat_id,brand_id) VALUES($r,$b)");

	//обновим краткое описание товара
	$meta_id=2;
	
	$param_name=$db->hash("select id,title from skat_params where is_hidden=0 and is_name=1 order by sort");
	$params=$db->hash("select par_id, sub_id from skat_param where id=$id");

	$ttd=array();
	foreach ($params as $key => $value) {
		if(isset($param_name[$key])){
			//$ttd[]=$param_name[$key]." ".htmlentities($db->value("select title from skat_params_list where id=$value"),'UTF-8');
			$ttd[]=$param_name[$key]." ".str_replace(' ', '&nbsp;', trim($db->value("select title from skat_params_list where id=$value")));
		}
	}
	$db->execute("delete from skat_info where id=$id and meta_id=".$meta_id);
	$data=array('id'=>$id,'meta_id'=>$meta_id,'descr'=>implode("\n",$ttd));
	$db->execute($db->sql_insert("skat_info","",$data));
	
	
}