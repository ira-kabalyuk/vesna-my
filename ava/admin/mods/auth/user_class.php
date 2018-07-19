<?php
class Mods_auth_user{
	
	function auth($id,$agent,$name){
		global $db,$Core;
		$pref="";
		switch ($agent) {
			case 'facebook':
				$pref="fb";
				break;
			
			case 'vkontakte':
				$pref="vk";
				break;
			default:
				# code...
				break;
		}
		$user=$db->get_rec("members where ".$pref."_id='".intval($id)."'");
		$uid=intval($user['id']);
		if($uid==0){
			self::add_user($id,$pref,$name);
		}else{
			self::set_token($uid);
			self::set_right($user);
		} 



}

static function set_right($user){
	global $Core;
	$r=explode(",",$user['group']);
	$Core->link->uid['right']=$r;
	foreach($r as $i)
		$Core->htm->assign('Admin_'.$i,$i);
	if($user['type']>1) $Core->htm->assign('UR_add_staff',1);
}

static function add_user($id,$agent,$name){
		global $db;

		$data=array();
		$data[$agent."_id"]=$id;
		$data['name']=$name;
		$data['type']=_postn('type');
		$db->execute($db->sql_insert("members","",$data));
		$user=$db->get_recs("select id, name,group from members where ".$agent."_id='".$id."'");
		self::set_token($user['id']);

		
}



static function find_user_by_name($name){
		global $db;

		$name=$db->clear($name);
		return $user=$db->get_recs("select id, name, passw from members where name like '".$name."'");
		
}


static function find_user_by_email($email){
		global $db;

		$name=$db->clear($email);
		return intval($db->value("select id from members where email like '".$email."'"));
		
}

static function set_token($id){
	global $db;
		$token=self::generate_code(32,true);
		$data=array();
		setcookie("ui_token",$token,time()+3600*24*30,"/");
		$data['token']=$token;
		$db->execute($db->sql_update("members","",$data," where id=".$id));
		return $token;

}

static function generate_code($len,$all=false){
	$letters="0123456789";
	if($all) $letters="0123456789qazwsxedcrfvtgbyhnujmikolp";
	$password = "";
    $lettersLength = strlen($letters) - 1;
 
    for($i = 0; $i < $len; $i++)
        $password .= $letters[rand(0, $lettersLength)];
 
    return $password;
}

static function init(){
	global $Core;
	$ok=false;
	$Core->link->uid=array('id'=>0);
	$token="";
	if(isset($_COOKIE['ui_token']))
			$token=$_COOKIE['ui_token'];
		if(isset($_GET['ui_token']))
			$token=$_GET['ui_token'];

	if(trim($token)!=""){
		$token=$Core->db->clear($token);
		$user=$Core->db->get_recs("select id, name, `type`, img, `group` from members where token='".$token."'");
		if(intval($user['id'])!=0){
			self::set_right($user);
			$Core->link->uid=$user;
			User::init();
			$Core->htm->assign('UNAME',$user['name']);
			$Core->htm->assign('UID',$user['id']);
			$Core->htm->assign('UIMG',"/uploads/members/".$user['img']);
			$ok=true;
		} 
	}
	return $ok;
}

static function logout(){
	setcookie("ui_token","0",time()-1000,"/");
}

static function sendmail($mail,$tpl,$data="",$subject="bs academy support"){
		global $htm;
	$body=file_get_contents(TEMPLATES.$tpl);
	if(is_array($data)){
		$htm->assvar($data);
		$htm->_var($body);
	}
	Fastmail::send(array(
		'to_mail'=>$mail,
		'body'=>$body,
		'subject'=>$subject
		));

}

}//