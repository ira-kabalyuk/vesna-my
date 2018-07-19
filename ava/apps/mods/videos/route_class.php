<?php
class Mods_videos_route{

	function route(){
		global $Core;
		$mod=new Mods_gallery_core;
		$mod->init('videos');
		$mod->rubric_menu($mod->set['parent_id']);

		$mod->get_video('row:VIDEO_ROW');
		
	}




}


