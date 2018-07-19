<?php
class Mods_gallery_core{

	function init($mod){
		$this->set=Com_mod::load_conf($mod);
	}

function _list(){
	global $db,$htm;
	
	$htm->external("EXT",TEMPLATES.$this->set['main_tpl']);
	$w=array("parent_id=".$this->set['parent_id']);

	$exclude=$db->vector("select id from news_rubric where parent_id=".$this->set['parent_id']." and is_hidden=0");
	if(count($exclude)!=0) $w[]=get_against("cat","cat_",$exclude);

 	$res=$db->select("select * from fotogal where ".implode(" and ",$w)." order by sort");
	$htm->assign("count_all",count($res));
	foreach($res as $r){
		$r['cat']=trim($r['cat']);
		$r['prew']="/uploads/photo/".$r['img'];
		$r['img']="/uploads/photo/".str_replace("c_", "o_",$r['img']);
		$htm->addrow("LIST_IMAGE",$r);
	}
	
	$this->rubric_menu($this->set['parent_id']);
	$htm->assign(array("SITE_TITLE"=>$this->set['seo']['seo_t']));

}

function rubric_menu($pid){
	global $db,$htm;
	$res=$db->select("select id,title from news_rubric where parent_id=".$pid." and is_hidden=0 order by sort");
	foreach($res as $r){
		$r['count']=$db->value("select count(*) from fotogal where is_hidden=0 and parent_id=$pid and ".get_against("cat","cat_",$r['id']));
		if($r['count']!=0) $htm->addrow("RUBR_LIST",$r);
	}
}





function get_video($jar){
	global $htm, $db;
		
	$set=parse_jar($jar);
	$w=array("is_hidden=0");
	$pid=intval($set['rubric']);
	$par=$this->set['parent_id'];
	$clear=intval($set['clear']);
	$limit="";

		$htm->external("EXT",TEMPLATES.$this->set['one_tpl']);


	if(isset($set['limit']))
			$limit="limit 0,".intval($set['limit']);
	
	if ($pid!=0) $w[]="cat LIKE '%cat_$pid %' ";
	if ($par!=0) $w[]="parent_id=$par";

	if(isset($set['tpl'])) $ret=file_get_contents(TEMPLATES.$set['tpl']);
	
	$row=(isset($set['row']) ? $set['row']:'VIDEO_ROW');

	$res=$db->select("select * from fotogal where ".implode(" and ",$w)." order by sort ".$limit);
	$i=1;
	foreach ($res as $r) {
		preg_match("/\?v=([^ ]+)/", $r['link'], $m);
		//print_r($m);
		$r['cat']=trim($r['cat']);
		$r['video']=$m[1];
		$r['img']="http://img.youtube.com/vi/".$m[1]."/0.jpg";
		
		if($i==$clear){
			$r['clear']=1;
			$i=0;
		}
		$i++;
		$this->add_meta($r);
		$htm->addrow($row,$r);
	}


}

function last_video($par=0,$pid=0){
	global $db;
	$w=array("is_hidden=0");
	if ($pid!=0) $w[]="cat LIKE '%cat_$pid %' ";
	if ($par!=0) $w[]="parent_id=$par";

	$r=$db->value("select link from fotogal where ".implode(" and ",$w)." order by sort  limit 0,1");
	if(trim($r)=="") return "";
	preg_match("/\?v=([^ ]+)/", $r, $m);
	return $m[1];
}

 function add_meta(&$r){
	global $db;
	$meta=$db->hash("select concat('meta_',metakey), metavalue from foto_metadata where parent_id=".$r['id']);
	if(count($meta)==0) return;
	$r=array_merge($r,$meta);
}
	

}