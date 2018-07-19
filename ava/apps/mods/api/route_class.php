<?php

class Mods_api_route{


	function route(){
		global $Core;
$uri=explode("/",$_SERVER['REQUEST_URI']);

switch ($uri[2]) {
	case 'form':
		$id=intval($uri[3]);
		Mods_form_help::save_form($id);
	break;
	
	case 'subscribe':
		$data=array(
		'name'=>_posts('name'),
		'email'=>_posts('email')
		);
		
		Com_subscribe::responce($data);


	break;

	case 'booking':
	$mod=new Mods_booking_route();
		$name=$uri[3];
		$Core->json_get($mod->$name());
	break;




	default:
		$Core->json_get(array('ok'=>false,'error'=>'unknow request','data'=>$uri));
		break;
}
	}
}