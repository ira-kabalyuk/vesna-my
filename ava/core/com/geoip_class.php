<?php
class Com_geoip{


	static function get_country(){
		global $db;

		$ip=$_SERVER['REMOUTE_ADDR'];
		if(isset($_SERVER['HTTP_X_REAL_IP'])) 
			$ip=$_SERVER['HTTP_X_REAL_IP'];
// Преобразуем IP в число
$int = sprintf("%u", ip2long($ip));
$code=array("code"=>"UA","name"=>"Украина");
if(isset($_COOKIE['selcountry'])){
	$cc=$db->clear($_COOKIE['selcountry']);
	$code =$db->get_recs("select code,name_ru as name from net_country where code='$cc'");
}else{
// Ищем страну
$country_id = 0;
// Европа?
$sql = "select * from (select * from net_euro where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
$result = $db->get_recs($sql);
if (count($result) == 0) {
    $sql = "select * from (select * from net_country_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int";
    $result = $db->get_recs($sql);
}
$country_id = intval($result['country_id']);

if ($country_id!=0) 
    $code =$db->get_recs("select code,name_ru as name from net_country where id='$country_id'");
}

   return $code;


	}
	
}