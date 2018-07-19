<?php
global $db;
$pid=$args[2];
	$id=$args[3];
	$p=$db->get_recs("select MIN(price) as p, count(id) as k from skat_variant where parent_id=$pid");
	$price=intval($p['p']);
	$kol=intval($p['k']);
	
if($args[1]=='save'){
	
	$db->execute("update skat set price=$price, variants=$kol where id=$pid");
	
	// свормируем название варианта из его характеристик
	$t=Mods_skat_params::get_param_title($id,'variant');
	$l=intval($_POST['params'][LABELS]);
	$db->execute("update skat_variant set ".(count($t)>0 ? "title='".implode('<br>',$t)."',":"")." label=$l where id=$id");
	
}elseif($args[1]=='delete'){
	if($kol==0){
		$db->execute("update skat variants=$kol where id=$pid");
	}else{
		$db->execute("update skat set price=$price, variants=$kol where id=$pid");	
	}
}