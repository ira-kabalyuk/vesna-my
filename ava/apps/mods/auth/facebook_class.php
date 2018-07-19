<?php
class Mods_auth_facebook{

    static $client_id  = '217211558474085';
    static $client_secret  = '5da8e7d6c3c6179036bd49b2f7877953';
    static $redirect_uri  = 'http://syneo.zotis.net/auth/facebook';
    static $response_type = 'code';
    static $auth_url = 'https://www.facebook.com/dialog/oauth';


static function authorize_url(){
	$url='https://www.facebook.com/dialog/oauth';
	$params = array(
    'client_id'     => self::$client_id,
    'redirect_uri'  => self::$redirect_uri,
    'response_type' => self::$response_type,
    'scope'         => ''
	);
	return $url . '?' . urldecode(http_build_query($params));
}

static function auth(){
	global $Core;
	if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id'     => self::$client_id,
        'redirect_uri'  => self::$redirect_uri,
        'client_secret' => self::$client_secret,
        'code'          => $_GET['code']
    );

    $url = 'https://graph.facebook.com/oauth/access_token';

    $tokenInfo = null;
    parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

    if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
        $params = array('access_token' => $tokenInfo['access_token']);

        $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

        if (isset($userInfo['id'])) {
            setcookie('fb_id',$userInfo['id'],time()+3600*24*30,"/");
            Mods_auth_user::auth($userInfo['id'],'facebook',$userInfo['name']);
            $Core->htm->assign('name',$userInfo['name']);
            $Core->htm->src(TEMPLATES."auth_ok.tpl");

        }
    }
}else{
   // $Core->link->redirect(self::authorize_url());
	$Core->ajax_get(self::authorize_url());
}
}



}