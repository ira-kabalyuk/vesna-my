<?php
class Mods_subscribe_route{


	function route(){
		$data=array(
		//'name'=>_posts('name'),
		'email'=>_posts('email')
		);

		Com_subscribe::responce($data);
	}
}