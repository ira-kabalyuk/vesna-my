<?php
class Mods_auth_vkontakte{

    static $client_id = '4249330'; // ID приложения
    static $client_secret = '2F0sZe4WLTPfd0xusweW'; // Защищённый ключ
    static $redirect_uri = 'http://syneo.zotis.net/auth/vkontakte'; // Адрес сайта
    static $url = 'http://oauth.vk.com/authorize';

static function authorize_url(){

    $params = array(
        'client_id'     => self::$client_id,
        'redirect_uri'  => self::$redirect_uri,
        'response_type' => 'code'
    );

    return  self::$url . '?' . urldecode(http_build_query($params));
}

static function auth(){
	global $Core;
if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id' => self::$client_id,
        'client_secret' => self::$client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => self::$redirect_uri
    );

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

    if (isset($token['access_token'])) {
        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name',
            'access_token' => $token['access_token']
        );

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['uid'])) {
            $name = $userInfo['response'][0]['first_name']." ".$userInfo['response'][0]['last_name'];
            $id=$userInfo['response'][0]['uid'];
           	setcookie('vk_id',$id,time()+3600*24*30,"/");
            Mods_auth_user::auth($id,'vkontakte',$name);
            $Core->htm->assign('name',$name);
            $Core->htm->src(TEMPLATES."auth_ok.tpl");
        }
    }
$Core->htm->src(TEMPLATES."auth_ok.tpl");

}else{
	$Core->link->redirect(self::authorize_url());
}

}
}//