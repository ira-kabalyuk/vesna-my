<?php
/**
 * Обновлено  видео
 * */
global $db;
$id=trim($args[1]);
$act=trim($args[2]);
$out=trim($args[3]);


if($id=='') return;

$less_id=$db->value("select parent_id from lesson_data where metakey='vid' and metavalue='$id'");

if(intval($less_id)==0) return;

if($act=='delete'){
	$less=new Mods_course_lesson_sax;
	$less->id=$less_id;
	$less->add_meta('poster',"");
	$less->add_meta('time',"");
	$less->add_meta('video',"");
	$less->add_meta('vid',"");
	$db->update("update course_lesson set is_video=0 where id=$less_id");
	$db->execute("delete from members_data where metakey='video_view[".$less_id."]'");
	return;
}else{


	$r=$db->get_recs("select poster, duration from video where sid='$id'");
	$less=new Mods_course_lesson_sax;
	$less->id=$less_id;
	$less->add_meta('poster',$r['poster']);
	if(isset($args[3])){
		$sd = json_decode($out); 
		$r['duration'] = $sd->duration;
		if(intval($sd->sd_video_file_size)!=0){
			$upd=array("type"=>1,'duration'=>$sd->duration);
			$db->update("video","",$upd," where sid='$id'");
			$db->execute("update course_lesson set is_video=1 where id=$less_id");
		}

		add_log('update_video',$id."\n".$sd->id."\n".$sd->duration."\n".$sd->sd_video_file_size."\n les:".$less_id);
	}
	$less->add_meta('time',$less->get_time($r['duration']));
}
return;
