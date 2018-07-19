<?php
class Com_subscribe{
	
	static $b64="bGFmbGV1ci5raWV2LnVhQGdtYWlsLmNvbTpsYWZsZXVyMjAxMw==";



static function submit($atr){
	$conf=Com_mod::load_conf('sputnik');

$url="https://esputnik.com.ua/api/v1/contact/subscribe";
$group=array("","Форма подписки","404 страница");

  $json_value = new stdClass();
  $json_value->contact=new stdClass();
  $json_value->contact->firstName = $atr['name'];
  $json_value->contact->channels = array(array('type'=>'email', 'value' => $atr['email']));
  $json_value->dedupeOn="email";
  $json_value->groups=array($group[_postn('group_id')]);

  $ch = curl_init();
  $data=json_encode($json_value);

  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_USERPWD, $conf['login'].':'.$conf['password']);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  add_log("sputnik",$output."\n".$data);
}

static function responce($atr){
		global $Core;
		$ok=true;
		$err=array();
		$resp=array();
		/*
		if(trim($atr['name'])==""){
			$ok=false;
			$err['name'] = 'Вы не указали имя';
		}
		*/
		if(!check_email($atr['email'])){
		//if(strripos("@", $atr['email'])===false){
		//if(!filter_var($atr['email'], FILTER_VALIDATE_EMAIL)){
			$ok=false;
			$err['email'] = 'Вы не указали email '.$atr['email'];
		}

		$resp['ok']=$ok;

		if(count($err)==0){
			$resp['data']="<p>Ваш адрес добавлен в базу подписки!</p>";
		}else{
			$resp['error']=$err;
		}
		if($ok) self::submit($atr);
		$Core->ajax_get(get_json($resp));
		
	}

}