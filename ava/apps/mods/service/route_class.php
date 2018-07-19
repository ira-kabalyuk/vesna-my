<?php
class Mods_service_route{

	function route(){
		$mod=new Mods_news_core();
		$mod->route('service');
	}
}