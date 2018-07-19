<?php


class Mods_folio_route{

	function route(){
		global $htm;
		
		$mod=new Mods_folio_core();
		$mod->route('folio');
	}
}