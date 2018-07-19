<?php
class Mods_upload_route{

	function route(){
		global $Core;
		$act=_posts('act');
		if($act=="crop"){

                $ok=Com_upload_core::ajax_crop($_POST);
                $Core->json_get($ok);

		}else{
			Com_upload_core::jq_upload();
		}
	}
}