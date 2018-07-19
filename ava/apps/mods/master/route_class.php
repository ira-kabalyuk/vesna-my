<?php


class Mods_master_route{

	function route(){
		global $htm;
		$htm->src(TEMPLATES."ajax-body.tpl");
		$mod=new Mods_master_core();
		$mod->route('master');
	}
}