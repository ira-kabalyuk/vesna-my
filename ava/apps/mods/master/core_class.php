<?php
class Mods_master_core extends Mods_news_core{
	var $count="";
	var $i=0;	

	function prepend(&$r){
		$this->count="";
		//$shin=explode(" ",$r['short']);
		//array_splice($shin, 35);
		$r['descr']=$r['short'];
		$r['short']=nl2br($r['short']);
		$r['diploms']=$this->get_diploms($r['id']);
		$r['count']=$this->count;
		$r['i']=$this->i;
		$this->i++;

	}

	function get_diploms($id){
		global $db;
		$res=$db->select("select id, img, descr from news_photo where is_hidden=0 and parent_id=$id order by sort");
		$this->count=count($res);
			$ret="";
		foreach($res as $r){
			//$ret.='<li><img src="/uploads/newsphoto/s_'.$r['img'].'" alt=""></li>';
			$ret.='<li><a href="/uploads/newsphoto/'.$r['img'].'" data-toggle="lightbox"><img src="/uploads/newsphoto/s_'.$r['img'].'"></a></li>';
		}
		return $ret;
	}
	

}