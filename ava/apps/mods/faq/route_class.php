<?php
class Mods_faq_route{
	function route(){
		$mod=new Mods_faq_core();
		$mod->route('faq');
	}
}