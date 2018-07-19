<?php
class Mods_news_ajax{

static function get_list($arg){
	global $db,$htm;
	$set=array();
	foreach($arg as $key=>$val){
		$set[]=$key.":".$val;
	}
	return Mods_news_help::get_list(implode(",",$set));


}

}