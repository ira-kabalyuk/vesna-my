<?php
class Mods_review_route{

	function route(){
		$mod=new Mods_review_core();
		$mod->route('review');
	}


}