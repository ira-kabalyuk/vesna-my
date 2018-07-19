<?php
class Mods_coment_core{
	static $set;
	
	static function get_set(){
		if(isset(self::$set)) return self::$set;
		self::$set=Com_mod::load_conf('coment');
		return self::$set;
	}

	static function show($jar){
		global $Core;
		$db=$Core->db;
		$htm=$Core->htm;
		$set=parse_jar($jar);
		$conf=self::get_set();
		$tpl='comment.tpl';
		$limit="10";
		$pid=$Core->link->news_id;

		// для каталога товаров
		$pid=$Core->link->skat['parent_id'];
		$htm->assvar('ID',$pid);
		$row="COMMENT_ROW";

		
		if(isset($set['tpl'])) $tpl=$set['tpl'];
		if(isset($set['row'])) $row=$set['row'];
		if(isset($set['limit'])) $limit=$set['limit'];
		
		$htm->assign('SOCAUTH',$set['social']);

		if($set['social']=="1"){
		$uid=$Core->link->uid;
			$htm->assign('AUTH',$uid['id']);
			$htm->assvar('UNAME',$uid['name']);
		}else{
			$htm->assign('AUTH',"A");
		}
		
		
			
		$ret=$htm->load_tpl($tpl);
		$res=$db->select("select id, user_id, user_name, date_add, date_mod, descr,city, answer from reviews where is_hidden=0 and parent_id=$pid order by date_add limit 0,".$limit);
		$htm->assvar('ccount',count($res));
		foreach($res as $r){
			$r['date_add']=self::get_date($r['date_add']);
			$r['date_mod']=self::get_date($r['date_mod']);
			$r['admin']=$conf['admin'];
			$htm->addrow($row,$r);
		}

		$htm->_var($ret);
		return $ret;

	}

	static function add_coment(){
		global $Core;
		$set=self::get_set();
		$db=$Core->db;
		$htm=$Core->htm;
		$pid=_postn('post_id');
		$data=array('parent_id'=>$pid);

		if($set['social']=="1"){
			$uid=$Core->link->uid;
			
			if($uid['id']==0){
				$Core->json_get(array('ok'=>false,"err"=>"noauthorized"));
				return;
			}else{
				$data['user_id']=$uid['id'];
				$data['user_name']=$uid['name'];
			}

		}else{
			$data['user_name']=strip_tags(_posts('name'));
		}
		$pid=_postn('post_id');
		

		
		$data['descr']=strip_tags(_posts('msg'));
		//$data['city']=strip_tags(_posts('city'));
		$data['email']=strip_tags(_posts('email'));
		

		$ok=self::check($data,"descr,email");

			if($ok){
		$data['date_add']=time();
		$data['is_hidden']=(intval($set['moder'])==1 ? 0:1);
		$db->execute($db->sql_insert("reviews","",$data));
		$data['date_add']=self::get_date($data['date_add']);
		$data['ok']=true;
		$data['answer'].="<p>Ваш комментарий будет опубликован после проверки модератором</p>";
		$Core->json_get(array('ok'=>true,'data'=>$data));
	}else{
		$Core->json_get(array('ok'=>false,'error'=>"Вы не заполнили все поля!"));
	}

	}

	static function get_date($date){
	$month=array("","января","февраля","марта","апреля","мая","июня","июля",
		"августа","сентября","октября","ноября","декабря");
	$d=explode(".",date("d.m.Y",$date));
	$now=time();
	$p="";
	
	$day_diff=floor(($now-$date)/(3600*24)); 
	if($day_diff<10){
		$p=" дней назад";
		if($day_diff<5) $p=$day_diff." дня назад";
		if($day_diff==1) $p=$day_diff." день назад";
		if($day_diff>6) $p=$day_diff." деней назад";
		if($day_diff==0){
			$min=floor(($now-$date)/60);
			$hour=floor($min/60);

			$p=$min." минут назад";

			if($hour>0){
				$p=$hour." часов назад";
				if($hour==1) $p="1 час назад";
				if($hour<5) $p=$hour." часа назад";
			}
			
		}
		
	}else{
		$p=$d[0]." ".$m[intval($d[1])]." ".$d[2];
	} 
	return $p;

	}	

	static function counts($id){
			global $db;
			return $db->value(" select count(*) from reviews where is_hidden=0 and parent_id=$id");

}

static function check($data,$keys){
	$k=explode(",",$keys);
	$ok=true;
	foreach ($k as $key) {
	if(trim($data[$key])=="") $ok=false;
	if($key=='email'){
		if(!check_email($data[$key])) $ok=false;
	} 
	}
	return $ok;
}




}
