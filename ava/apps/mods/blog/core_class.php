<?php
class Mods_blog_core extends Mods_news_core {

function prepend_where($w){
	$rds=_postn_ar('rubric');
	if(count($rds)>0){
		$w[]=get_against('terms','cat_',$rds);
		$this->set['limit']=100;
	}

	return implode(" and ",$w);
}

}