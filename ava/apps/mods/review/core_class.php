<?php
class Mods_review_core extends Mods_news_core{

function pre_route(){
	$this->set['limit']=_postn('limit');
}

}