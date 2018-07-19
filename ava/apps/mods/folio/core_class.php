<?php
class Mods_folio_core extends Mods_news_core{


function prepend_where($w){
	global $htm;
	
	$rds=_postn_ar('rubric');
	if(count($rds)>0){
		$w[]=get_against('terms','cat_',$rds);
		$this->set['limit']=100;
	}

	

	return implode(" and ",$w);
}
	

	function prepend(&$r){
		global $db;
		$count=0;
		//$shin=explode(" ",$r['short']);
		//array_splice($shin, 35);
		//$r['descr']=$r['short'];
		//$r['short']=nl2br($r['short']);
		//$r['diploms']=$this->get_diploms($r['id']);
		$r['count']=$db->value("select count(*)from news_photo where is_hidden=0 and parent_id={$r['id']}");
		
	}

	function get_photo($id){
		global $db;
		$res=$db->select("select id, img, descr from news_photo where is_hidden=0 and parent_id=$id order by sort");
		$this->count=count($res);
			$ret="";
		foreach($res as $r){
			$htm->addrow("PHOTOS",$r);
			//$ret.='<li><img src="/uploads/newsphoto/s_'.$r['img'].'" alt=""></li>';
			//$ret.='<li><a href="/uploads/newsphoto/'.$r['img'].'" data-toggle="lightbox"><img src="/uploads/newsphoto/s_'.$r['img'].'"></a></li>';
		}
		//return $ret;
	}
	

}