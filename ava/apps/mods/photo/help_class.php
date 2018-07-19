<?php
class Mods_photo_help{

static function gallery($jar){
	global $db, $htm;
	//$htm->addscript('js','/js/pretty/pretty.js');
	//$htm->addscript('css','/js/pretty/pretty.css');
	$set=parse_jar($jar);
	$ret="";
	$limit=intval($set['limit']);
	$limit=($limit==0 ? "":"limit 0,".$limit);
	$pid=intval($set['rubric']);
	if(isset($set['tpl'])) $ret=file_get_contents(TEMPLATES.$set['tpl']);
	$row=(isset($set['row']) ? $set['row']:'GALLERY_ROW');

	$res=$db->select("select id, img, descr,link from fotogal where is_hidden=0 and cat LIKE '%cat_$pid %' order by sort ".$limit);
	foreach ($res as $r) {
		self::add_meta($r);
		$r['simg']="/uploads/photo/s_".$r['img'];
		$r['fimg']="/uploads/photo/".$r['img'];
		$htm->addrow($row,$r);
	}
	
	if(isset($set['menu'])){
	if(isset($set['tpl'])){
		$htm->assvar("RUBRICS",self::get_rubric_menu());
		$htm->_var($ret);	
	}else{
			$htm->assign("RUBRICS",self::get_rubric_menu());
		}
	} 
	
	if(isset($set['tpl'])){
			$htm->assvar('rid',$pid);
			$htm->_var($ret);
			$htm->_row($ret,true);
	}
	return $ret;



}

static function rmenu($jar){
	global $db,$htm;
	$set=parse_jar($jar);
	$tag=intval($set['tag']);
	$res=$db->select("select id,title,link from foto_rubric where is_hidden=0 and parent_id=$tag order by sort");
	$ret="";
	$row=(isset($set['row']) ? $set['row'] :"GALL_MENU");
	if(isset($set['tpl'])) $ret=$htm->load_tpl($set['tpl']);

	foreach($res as $r){
		$id=$r['id'];
		if(isset($set['image'])) 
			$r['img']="/uploads/photo/".$db->value("select img from fotogal where cat LIKE '%cat_$id %' order by sort limit 0,1");
			if(isset($set['kol'])) $r['kol']=$db->value("select count(*) from fotogal where cat LIKE '%cat_$id %'");
		$htm->addrow($row,$r);
	}

	if(isset($set['tpl'])){
		$htm->_row($row);
		return $ret;
	}
		

}


static function add_meta(&$r){
	global $db;
	$meta=$db->hash("select concat('meta_',metakey), metavalue from foto_metadata where parent_id=".$r['id']);
	if(count($meta)==0) return;
	$r=array_merge($r,$meta);
}

static function sgal($id){
	return self::gallery('tpl:slider.tpl,row:SERVICE_SLIDER,rubric:'.$id);
} 
static function cgal($id){
	return self::gallery('tpl:cgal.tpl,row:CGAL,limit:3,rubric:'.$id);
} 

static function get_video($jar){
	global $htm, $db;
	$set=parse_jar($jar);
	$w=array("is_hidden=0");
	$pid=intval($set['rubric']);
	$clear=intval($set['clear']);
	$limit="";
	$par=intval($set['parent']);
	if ($pid!=0) $w[]="cat LIKE '%cat_$pid %' ";
	if ($par!=0) $w[]="parent_id=$par";

	if(isset($set['tpl'])) $ret=file_get_contents(TEMPLATES.$set['tpl']);
	
	$row=(isset($set['row']) ? $set['row']:'VIDEO_ROW');

	$res=$db->select("select id, img, descr,link from fotogal where ".implode(" and ",$w)." order by sort ".$limit);
	$i=1;
	foreach ($res as $r) {
		preg_match("/\?v=([^ ]+)/", $r['link'], $m);
		//print_r($m);
		$r['video']=$m[1];
		$r['img']="http://img.youtube.com/vi/".$m[1]."/0.jpg";
		
		if($i==$clear){
			$r['clear']=1;
			$i=0;
		}
		$i++;
		self::add_meta($r);
		$htm->addrow($row,$r);
	}


}

static function last_video($par=0,$pid=0){
	global $db;
	$w=array("is_hidden=0");
	if ($pid!=0) $w[]="cat LIKE '%cat_$pid %' ";
	if ($par!=0) $w[]="parent_id=$par";

	$r=$db->value("select link from fotogal where ".implode(" and ",$w)." order by sort  limit 0,1");
	if(trim($r)=="") return "";
	preg_match("/\?v=([^ ]+)/", $r, $m);
	return $m[1];
}


}