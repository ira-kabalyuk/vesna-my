<?php

class Mods_form_help{

static function get_form($set){
			global $db,$htm;
			if(!is_array($set)) 
				$set=$db->get_rec("form_name where id=".intval($set));
		
		if(trim($set['tpl'])!=''){
			$ret=$htm->load_tpl($set['tpl']);
		}else{
			$ret=$htm->load_tpl("form.tpl");
		}
			
	
	$res=$db->select("select * from form_fields  where is_hidden=0 and  parent_id=".$set['id']." order by sort");
	$set['9']="{9}";
	
	foreach($res as $r)
	$htm->addrow("FORM_FIELDS",array('INPUT'=>Mods_form_input::get_input($r)));
	$htm->assign('9','{9}');
	$htm->_row($ret,true);
	$htm->assvar($set);
	$htm->_var($ret);
	return $ret;
	
		
	}


static function save_form($id){
	global $db;
	$set=$db->get_rec("form_name where id=$id");
	self::_save($set);
} 	
	
static function _save($set){
	global $db,$htm, $Core;
	$res=$db->select("select * from form_fields where is_hidden=0 and  parent_id={$set['id']} order by sort");
	
	if(!self::check($res)) return;
	$ip=$_SERVER['REMOTE_ADDR'];
	$is_ban=intval($db->value("select count(*) from ipban where ip='$ip'"));
	if($is_ban!=0){
		$Core->ajax_get(get_json(array(
		'ok'=>false,
		'error'=>array('name'=>" is ban ")
		)));
		return;
	}
	$fid=$set['id'];
	$p=$_POST;
	$url=(_posts('url')=="" ? 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']:_posts('url'));
	$form=$db->get_recs("select id, title from form_name where id=$fid");
	$dbody=array();
	$id=$db->getid('form_msg','id',1);
	$msg=array('id'=>$id);
	$msg['form_id']=$form['id'];
	$msg['ip']=$ip;
	$msg['url']=$url;
	$msg['data_add']=time();
	$msg['title']=$form['title'];


	$db->insert("form_msg","",$msg);
	
	foreach($res as $r){
		$data=array('parent_id'=>$msg['id']);
		$data['meta_id']=$r['id'];
		$data['metakey']=$r['name'];
		$data['descr']=$p[$r['name']];
		
		if($r['type']=='dirs'){
			$dir=$db->hash("select id,title from skat_dirs_list where parent_id=".$r['cont_id']);
			$data['descr']=$dir[$data['descr']];   
			}


		$db->execute($db->sql_insert('form_info','',$data));
		
		$data['title']=$r['title'];
		$dbody[]=$data;
	}
	//_emit('save_form',$set['id'],$msg['id']);
	// отправим письмо если нужно
	if(trim($set['email'])!=''){
	
		$htm->assvar(array(
		'URL'=>$url,
		'FNAME'=>$form['title'],
		'DATE'=>date("d.m.Y h:i:s"),
		'IP'=>$_SERVER['REMOTE_ADDR']
		));

		$body=$htm->load_tpl('mail.tpl');
		$htm->maprow('FORM_FIELDS',$dbody);
		$htm->_row($body,true);
		$htm->_var($body);
		Fastmail::send(array(
		'body'=>$body,
		'to_mail'=>$set['email'],
		'subject'=>$form['title']." N $id ".date("d.m.Y H:i")
		));
		
	}

	// отправим письмо клиенту 
	if(trim($set['is_mail'])!=''){
		$body=$htm->load_tpl($set['tpl_mail']);
		$mail=_posts('email');
		if(check_email($mail)){
			add_log('mail',$mail."<br>".$body);
			Fastmail::send(array(
			'body'=>$body,
			'to_mail'=>$mail,
			'subject'=>$form['title']
		));
		
		}else{
			add_log(' wrong mail',$mail);
		}
	}

		//если для формы включена отправка смс клиенту
	if($set['is_sms']==1){
		if(trim($set['sms'])!=""){
			$text=str_replace(array('[ID]','[TITLE]'), array($msg['id'],$msg['title']), $set['sms']);
			_emit("send_sms",$p['phone'],$text);
		}
	}

	//если для формы включена отправка смс администратору
	if($set['is_asms']==1){
		if(trim($set['tel_admin'])!=""){
			if(trim($set['sms_a'])!=""){
			$ar=array('[ID]','[TITLE]','[PHONE]','[NAME]');
			$rep=array($msg['id'],$msg['title'],substr(_posts('phone'),0,32),substr(_posts('name'),0,32));

			$text=str_replace($ar, $rep, $set['sms_a']);

			_emit("send_sms",$set['tel_admin'],$text);
				
			}
		}
	}
	
	if(isset($set['tpl_ok'])){
		if(preg_match("/^#.+/", $set['tpl_ok'])){
			$Core->ajax_get(get_json(array(
			'ok'=>true,
			'modal'=>$set['tpl_ok']
			)));
		}elseif(strpos($set['tpl_ok'],".html")===false){
			self::responce($htm->load_tpl($set['tpl_ok']));	
		}else{
			$url=$set['tpl_ok'];
			if($Core->link->lang!='ru')
				$url="/".$Core->link->lang.$url;
			$Core->ajax_get(get_json(array(
			'ok'=>true,
			'url'=>$url
			)));
		}
		
	}else{
		self::responce('<div class="responce">Ваше сообщение принято!<br> Мы свяжемся с Вами в ближайшее время</div>');

	} 


	
}

static function check($res){
	global $Core;
	$e=array();

	foreach ($res as $r){
		if($r['check']==0) continue;
		if(_posts($r['name'])==''){
		 $e[$r['name']]=$r['error'];
		}else{
			if($r['type']=="email"){
				if(!preg_match("/[^@]+@[^\.]+\..+/", _posts($r['name'])))
					$e[$r['name']]="Incorrect Email!";
			}
		}

		
	}
	if(count($e)>0){
		$Core->ajax_get(get_json(array(
		'ok'=>false,
		'error'=>$e
		)));
		return false;
	}
	return true;
	
}
	
	static function responce($msg){
		global $Core;
		if(AJAX){
			$Core->ajax_get(get_json(array(
		'ok'=>true,
		'data'=>$msg
		)));
		}else{
			 $Core->ajax_get($msg);
		}
	}


}