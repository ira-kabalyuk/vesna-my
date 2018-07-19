<?php
class Mods_auth_local{



	static function auth(){
		global $Core;
		$db=$Core->db;
		$login=$db->clear(_posts('email'));
		$passw=$db->clear(_posts('password'));
		$failed=" Login is failed !";

		$id=intval($db->value("SELECT id FROM members WHERE email='$login' AND passw='$passw'"));
		if($id==0){
			$Core->json_get(array('ok'=>false, 'error'=>array('email'=>$failed)));
			return;
		}
		$token=Mods_auth_user::set_token($id);
		$Core->json_get(array('ok'=>true, 'url'=>'/','token'=>$token));
	}


static function register(){
	global $Core;
	$ret=array('ok'=>false);
	$error=array();
	$login=_posts('email');
	$passw=_posts('password');
	$name=_posts('name');

	if(trim($name)=="") $error['name']='Input your name!';
	if(trim($login)=="") $error['email']='wrong email!';
	if(trim($passw)=="") $error['password']='wrong password!';
	if(!Captcha::check()) $error['captcha']='wrong code!';


	if(count($error)>0){
		$ret['error']=$error;
	}else{
		$id=Mods_auth_user::find_user_by_email($login);
		if($id>0){
			$error['email']='This email is already registered!';
			$ret['ok']=false;
			$ret['error']=$error;
		}else{
			self::add_local_user($login,$passw,$name);

			$ret['url']="/";
			$ret['ok']=true;
		} 
	} 


	$Core->json_get($ret);
}


static function add_local_user($email,$passw,$name=""){
	global $db,$config;
		$id=$db->getid("members","id",1);
		$data=array();
		$data['id']=$id;
		$data['email']=$email;
		$data['passw']=$passw;
		$data['name']=$name;
		$data['type']=_postn('type');
		$db->execute($db->sql_insert("members","",$data));
		Mods_auth_user::sendmail($data['email'],"mail_registration.tpl",$data);
		Mods_auth_user::sendmail($config['admin_email'],"mail_admin_registration.tpl",$data);
		Mods_auth_user::set_token($id);
		return $id;
}

static function forgot_password(){
	global $Core;
	$mail=_posts('email');
	$id=Mods_auth_user::find_user_by_email($mail);
	if($id!=0){
	self::remember($id);
		$Core->json_get(array('ok'=>true,'data'=>'Password has been sent to your email!'));
	}else{
		$Core->json_get(array('ok'=>false,'error'=>array('email'=>'This Email is not registered!')));
	}

}

static function remember($id){
	global $db;
	$data=$db->get_recs("select `passw`, `email` from `members` where id=$id");
	Mods_auth_user::sendmail($data['email'],"mail_repass.tpl",$data);
}
static function logout(){
	global $Core;
	setcookie("ui_token","0",time()-1000,"/");
	$Core->link->redirect("/");
}

}