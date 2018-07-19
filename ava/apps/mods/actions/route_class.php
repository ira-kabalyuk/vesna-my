<?php
class Mods_actions_route{

	function route(){
		$app=dirname(__FILE__)."/core_class.php";


		if(is_file($app)){
			$mod=new Mods_actions_core();
		}else{
			$mod=new Mods_news_core();
		}

		$mod->route('actions');
	}
}