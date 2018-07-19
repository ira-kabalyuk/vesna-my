<?php
/**
 * Генератор гугль сайтмап
 */
$htm->external("EXT_ADD",MOD_PATH."smap_admin.tpl");

$conf=load_ar("ava/conf/all.cfg");
//print_r($conf)
Sitemap::$count=0;
Sitemap::$url=$conf['url_site'];

Sitemap::category();
//Sitemap::cross();
Sitemap::tov();
Sitemap::_static();
Sitemap::save_map(dirname(__FILE__)."/sitemap.xml",$_SERVER['DOCUMENT_ROOT']."/sitemap.xml");
$log= '<a href="'.$conf['url_site'].'/sitemap.xml" target="_blank">'.$conf['url_site'].'/sitemap.xml</a>';
$log.="<p> Сгенерировано ".Sitemap::$count." записей </p>";
$htm->assign('LOG',$log);


class Sitemap{
 static $url="";
static $count=0;
	static function category(){
		global $db;

		$res=$db->vector("select b.link from skat_categories as a left join skat_links as b on a.id=b.parent_id and a.is_hidden=0 and b.type=1");
		if(!is_array($res)) return;

		foreach ($res as $r) 
			self::url($r,0.8);
		
	}

	static function tov(){
		global $db;
		$res=$db->vector("select b.link from skat as a left join skat_links as b on (a.id=b.parent_id and b.type=0) where a.is_hidden=0");
		if(!is_array($res)) return;

		foreach ($res as $r) 
			self::url($r,0.7,'monthly');

	}


static function _static(){
		global $db;
		$res=$db->vector("select b.link from static as a left join links as b on a.id=b.parent_id where a.is_hidden=0");
		if(!is_array($res)) return;

		foreach ($res as $r) 
			self::url($r,0.7,'monthly');

	}

	static function cross(){
		global $db;
		$res=$db->vector("select link from crosslink");
		if(!is_array($res)) return;

		foreach ($res as $r) 
			self::url($r,0.8);

	}

	static function url($url,$prior="0.8",$freq="weekly"){
		global $htm;
		if(trim($url)=="") return;
		if(trim($url)=="/") return;
		$r=array();
		$r['prior']=$prior;
		$r['freq']=$freq;
		$r['url']=self::$url."/".$url;
		$htm->addrow("LIST_URL",$r);
		self::$count++;

	}

	static function save_map($tpl, $filename){
		global $htm;
		$ret=file_get_contents($tpl);
			$htm->_row($ret,false);
			file_put_contents($filename, $ret);
			//echo $filename;
	}

}