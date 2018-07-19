<?php
class Mods_blog_route{



	function route(){
		global $htm;
		$conf=Com_mod::load_conf('blog');
		$htm->assign($conf['seo']);
		/*
		if(preg_match("/page-[0-9]+/",$_SERVER['REQUEST_URI']))
			$htm->assign("canonical",$conf['canonical']);
		*/
		$app=dirname(__FILE__)."/core_class.php";
		
		if(is_file($app)){
			$mod=new Mods_blog_core();
		}else{
			$mod=new Mods_news_core();
		}
			

		$mod->route('blog');
	}
}