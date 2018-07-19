<?php


class Mods_doubleg_route{

	function route(){
		global $htm;
		$htm->src(TEMPLATES."ajax-body.tpl");
		$mod=new Mods_doubleg_core();
		$mod->route('doubleg');
	}
}