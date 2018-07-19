<?php 
class Com_clogin{
	
	static function _get(){
		global $Core,$members;
		
$Core->msg="";
$Core->user=array();	
if(isset($_GET["user_exit"]))
{

	unset($_SESSION["mb_userlogin"]);
	unset($_SESSION["mb_userpasw"]);
	unset($members);
	
	$Core->user=array();	
	
		//session_destroy();

	setcookie ("mb_users", "~", time(),"/");
	header("Location:/");
}else{

	//if (isset($_POST["logins"]) && isset($_POST["passw"]))
	if(isset($_REQUEST['logins']) or _postn('new')==2){
	
		$uid=self::get_member_user(trim($_REQUEST["logins"]),trim($_REQUEST["passw"]));
		
			if(isset($uid['id'])){
				if($uid['is_ban']==1){
				$Core->msg='<span>учетная запись заблокирована!</span>';	
			
				}else{
				$_SESSION["mb_userlogin"]=trim($_REQUEST["logins"]);
				$_SESSION["mb_userpasw"]=trim($_REQUEST["passw"]);
				if(intval($_POST['member'])==1){
				setcookie("mb_users", trim($_REQUEST["logins"])."~".trim($_REQUEST["passw"]), time()+2592000,"/");
								
				}
				$members=$uid;
				$Core->user=$uid;
			
			}
			 }else{
			$Core->msg='<span>Неверный e-mail или пароль!!!</span>';
		
			 }
	}else{
		if(isset($_SESSION["mb_userlogin"]))
		{ 
			$uid=self::get_member_user($_SESSION["mb_userlogin"],$_SESSION["mb_userpasw"]);
			if (intval($uid['id'])!=0)
			{
				if($uid['is_ban']!=0){
					unset($members);
				}else{
				$members=$uid;
				if($uid['is_mail']==1){
					$htm->assign('NOMAIL',1);
				}elseif($uid['popup_id']!=0){ 
					$htm->assign('USER_POPUP'.$uid['popup_id'],$uid['popup_id']);
					$Core->db->execute("update customers set popup_id=0 where id=".$uid['id']);
					}
				
				$Core->db->execute("update customers set last_login=".time()." where id='".$uid['id']."'");
				$Core->user=$uid;	
				}
				
			}else{
		unset($members);
	//	unset($_SESSION["mb_userlogin"]);
			}
		}elseif(isset($_COOKIE["mb_users"])){
			$cokie=explode("~",$_COOKIE["mb_users"]);
			$uid=self::get_member_user($cokie[0],$cokie[1]);
			if (intval($uid['id'])!=0)
			{
				if($uid['is_ban']!=0){
					unset($members);
				}else{
				$members=$uid;
				$Core->user=$uid;	
				}
				
				
			}else{
		unset($members);
	//	unset($_SESSION["mb_userlogin"]);
			}
			
		}
	}
}	
if(isset($members)){
	$Core->htm->assign(array(
"LOGIN"=>1,
"NICK"=>$members['nickname']

));

//$Core->Hook->emit('members');
}
}
static function get_member_user($login, $passw){
	global $Core;
	$db=$Core->db;
$login=$db->clear($login);
$passw=$db->clear($passw);
$sql="customers where email LIKE '$login' and passw LIKE '$passw'";
return $db->get_rec($sql);
}
}