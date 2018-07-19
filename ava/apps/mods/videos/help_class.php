<?php

class Mods_videos_help{

static function gallery($jar){
	global $db,$htm;
	$set=parse_jar($jar);
	$conf=Com_mod::load_conf($set['mod']);

	$w=array('parent_id='.$conf['parent_id'],'is_hidden=0');

	if(isset($set['rubric']))
		$w[]=get_against('cat','cat_',$set['rubric']);


	$where=implode(" and ",$w);

	$res=$db->select("select * from fotogal where $where order by sort");
	foreach($res as $r){
		if(preg_match("/v=([^\&]+)/", $r['link'],$m))
			$r['code']=$m[1];
		$htm->addrow($set['row'],$r);
	}

	if(isset($set['tpl'])){
		$tpl=file_get_contents(TEMPLATES.$set['tpl']);
		$htm->_row($tpl,true);
		return $tpl;
	}

	return "";

}

}