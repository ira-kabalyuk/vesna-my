<?php
class Mods_news_route{

	function route(){
		$mod=new Mods_news_core();
		$mod->route('news');
	}
}