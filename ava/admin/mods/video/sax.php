<?php

class Mods_video_sax{


function Start(){
	global $Core;
	$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
	$this->id=_gets('vid');

	$act=_gets('act');

	if($act=='get_poster'){
		$this->get_poster();
	
	}elseif($act=="set_poster"){
		$this->set_poster();

	}elseif($act=="delete_video"){
		$Core->ajax_get($this->delete_video());
	}

}



function get_poster(){
	global $htm;
	$htm->src($this->mp."set-poster.tpl");
	$htm->assign('vid',$this->id);
	$poster=Com_sprout::get_poster($this->id);
	foreach($poster as $p){
		$htm->addrow("Poster",array('img'=>$p));
	}
}


function set_poster(){
	global $db,$htm;
	$data=array('poster'=>_posts('poster'));
	$db->update("video","",$data," where sid='{$this->id}'");
	Com_sprout::set_poster($this->id,_postn('poster_frame'));
	$htm->src($this->mp."set-ok.tpl");
}

function delete_video(){
	global $db;
	$ok= Com_sprout::delete($this->id);

	
	if($ok===true){	
		$db->execute("delete from video where sid='$id'");
		_emit("update_video",$this->id,"delete");

		return "<h1>Видео удалено</h1>";
	}else{
		return "<h4>Произошла ошибка</h4>";
	}
	

}


}

$sax=new Mods_video_sax;
$sax->Start();