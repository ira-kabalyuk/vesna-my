<?php
class Mods_gallery_route{

	function route(){
		global $Core;
		$mod=new Mods_gallery_core;
		$mod->init('gallery');
		$mod->_list();
	}




}


