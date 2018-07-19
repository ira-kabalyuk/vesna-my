<?php
class Mods_doubleg_core extends Mods_news_core{
	var $count="";
	var $i=0;	

	function prepend(&$r){
		$this->count="";
		$meta=get_meta("news_metadata",$r['id']);
		//$shin=explode(" ",$r['short']);
		//array_splice($shin, 35);
		$r['foto1']=($meta['one'] == "" ? "":"/uploads/news/".$meta['one']);
		$r['foto2']=($meta['two'] == "" ? "":"/uploads/news/".$meta['two']);
		$r['i']=$this->i;
		$this->i++;

	

	
	}

	function gallery($jar){
		global $htm,$db;
		$set=parse_jar($jar);
		$tpl=file_get_contents(TEMPLATES.$set['tpl']);
		$pid=$db->value("select parent_id from mods where mods='doubleg'");
		$w=['is_hidden=0','parent_id'=>$pid];
		if(isset($set['rubric']))
		$w[]=get_against('terms','cat_',$set['rubric']);
		$where=implode(" and ",$w);
		$sql="select id, title, date_pub,short,img,descr,guid,terms from news where $where order by sort";
		$res=$db->select($sql);
		foreach($res as $r){
			$this->prepend($r);
			$htm->addrow($set['row'],$r);
		}
		$htm->_row($tpl,true);
		return $tpl;



	}


	

}