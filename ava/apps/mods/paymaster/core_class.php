<?php
class Mods_Paymaster_core{
 
 var $skey="";
 var $hash_type="sha256";
 var $currency_url="https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=3";
 var $currency="UAH";
 var $currency_default=1;
 var $mp="";
 var $amount=1;
 var $mer_id="";
 var $uid=0;
 var $data=array();
 var $set;

 var $paid_confirm=array(
 	'LMI_MERCHANT_ID',
 	'LMI_PAYMENT_AMOUNT',
 	'LMI_PAID_AMOUNT',
 	'LMI_PAYMENT_NO',
 	'LMI_MODE',
 	'LMI_SYS_PAYMENT_ID',
 	'LMI_PAYMENT_SYSTEM',
 	'LMI_SYS_PAYMENT_DATE',
 	'LMI_PAYER_IDENTIFIER',
 	'LMI_PAYMENT_DESC',
 	'LMI_HASH',
 	'PM_MAIL'
 	);



function __construct(){
	global $Core;
	$set=Com_mod::load_conf('paymaster');
	$this->mer_id=$set['mer_id'];
	$this->skey=$set['key'];
	$this->currency_url=$set['xml_url'];
	$this->currency=$set['valute'];
	$this->currency_default=$set['currency'];
	$this->amount=$set['amount'];
	$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
	$this->uid=$Core->link->uid['id'];
	$this->set=$set;
}




function im_post($name){
	if(isset($_POST[$name])){
		$this->data[$name]=$_POST[$name];
	}else{
		$this->data[$name]="";
	}
	
}

function im_get($name){
	if(isset($_GET[$name])){
		$this->data[$name]=$_GET[$name];
	}else{
		$this->data[$name]="";
	}
	
}

function receive_post(){
	foreach($this->paid_confirm as $name)
			$this->im_post($name);
}

function receive_get(){
	foreach($this->paid_confirm as $name)
			$this->im_get($name);
}

/**
 * получение курсов валют(продажа) с сайта приватбанка
 * */
function get_currency(){

if($this->set['valute']=="UAH")
	return 1;

$response_xml_data = file_get_contents($this->currency_url);
 if($response_xml_data){

 $data = simplexml_load_string($response_xml_data);
	foreach($data->row as $r){
		$a=$r->exchangerate->attributes();
		if($this->currency== $a->ccy) return floatval($a->buy);
			//echo $a->ccy." = ".$a->buy."<br>";
	} 
}
	return $this->currency_default;
}

/**
 * Получение хеша ответа
 **/
function _hash(){
	$d=$this->data;
	$res=$d['LMI_MERCHANT_ID'];
	$res.=$d['LMI_PAYMENT_NO'];
	$res.=$d['LMI_SYS_PAYMENT_ID'];
	$res.=$d['LMI_SYS_PAYMENT_DATE'];
	$res.=$d['LMI_PAYMENT_AMOUNT'];
	$res.=$d['LMI_PAID_AMOUNT'];
	$res.=$d['LMI_PAYMENT_SYSTEM'];
	$res.=$d['LMI_MODE']; 
	$res.=$this->skey;

	return hash($this->hash_type, $res);
}

function add_log($file,$msg){
	$log=fopen(LOG_PATH.$file.".log",'a+');
	@fwrite($log,date('d.m.Y H:i:s')."\n".$msg."\n\n");
	fclose($log);
}




//обработка подтверждения платежа
function receive_paid(){
		$log="";
		$hash=strtoupper($this->_hash());
		$this->data['R_HASH']=$hash;
		$ok=$hash==$this->data['LMI_HASH'];
		$this->data['HASH_OK']=($ok ? "YES":"NO");

		foreach ($this->data as $key => $value) {
			$log.=$key." => ".$value."\r\n";
		}
		$this->add_log("pmaster",$log);
		$this->save_order();
		return ($hash==$this->data['LMI_HASH'] ? true:false);
	}
	
// запись в базе 
	
function save_order(){
	global $db;
	$data=[];
	
	$data['order_id']=$this->data['LMI_PAYMENT_NO'];
	$data['system_id']=$this->data['LMI_SYS_PAYMENT_ID'];
	$data['amount']=$this->data['LMI_PAID_AMOUNT'];
	$data['date_add']=$this->parse_date($this->data['LMI_SYS_PAYMENT_DATE']);
	$db->insert("pay_log","",$data);
	_emit('pay_ok',$data);
	
}

function parse_date($data){
	$s=explode(" ",trim($data));
	$d=explode("-",$s[0]);
	$t=explode(":",$s[1]);
	
	return mktime(intval($t[0]),intval($t[1]),intval($t[2]),intval($d[1]),intval($d[2]),intval($d[0]));
}
// генерация ссылки на скачивание файла
function gen_link(){
	global $db,$htm;
	$link=md5($this->data['LMI_HASH']);

	$data=array(
		"date_add"=>time(),
		"link"=>$link,
		'order_id'=>$this->data['LMI_SYS_PAYMENT_ID'],
		'email'=>$this->data['PM_MAIL'],
		'amount'=>$this->data['LMI_PAYMENT_AMOUNT']
		);
	$mail_data=$data;
	$is=trim($db->value("select link from downloads where order_id=".intval($data['order_id'])));

	if($is==""){
			$db->execute($db->sql_insert("downloads","",$data));
			$this->sendmail($mail_data);
	}else{
			$link=$is;
	}
	

	$htm->assign(array(
		'PAYD_OK'=>1,
		'link'=>$link
		));
	


	}

	function sendmail($data){
		global $htm;
		$tpl=file_get_contents(TEMPLATES."mail_paymaster.tpl");
		$htm->assvar($data);
		$htm->_var($tpl);
		$to=$data['email']; // Адрес получателя
		$subject="BeaitySalonBoss";
		$body=$tpl; // можно и HTML
		$headers  = "MIME-Version: 1.0 \r\n";
		$headers .= "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: info@zotis.net \r\n";

		$ok=mail($to,$subject,$body,$headers);
		//$this->add_log("pmaster_mail","mailto:".$to."\r\n ok:".$ok."\r\n".$body."\r\n");
		$this->add_log("pmaster_mail","mailto:".$to."\r\n ok:".$ok."\r\n");
	}


// проверка id заказа в системе paymaster
function check_id($id){
	global $db,$htm;
$link=trim($db->value("select link from pay_orders where order_id=".intval($id)));
if($link!=""){
		$htm->assign(array(
			'PAYD_OK'=>1,
			'link'=>$link
		));
	}

}	

function get_form($set){
		global $htm;
		if(isset($set['price'])) 
			$this->amount=floatval($set['price']);

		$cur=$this->get_currency();
		if(isset($set['order_id'])){
			$order_id=$set['order_id'];
		}else{
			$order_id=$this->get_order_id();
		}
		
		//print_r($this->set);
		$data=array(
				'AMOUNT'=>round($this->amount*$cur,2),
				'PRICE'=>$this->amount,
				"LMI_PAYMENT_DESC"=>(isset($set['title']) ? $set['title']:"Оплата счета"),
				'CURRENCY'=>$cur,
				'ORDER'=>$order_id,
			//	'LMI_MODE'=>$this->set['mode'],
				'MERCH_ID'=>$this->mer_id,
				'LMI_FORMID'=>(isset($set['formid']) ? $set['formid']:"") 
			);
		if($this->set['mode']=="1"){
			$data['LMI_PAYMENT_SYSTEM']="18";
		}else{
		//	$data['LMI_PAYMENT_SYSTEM']="21";
		}

		if(isset($set['tpl'])){
			$tpl=file_get_contents(TEMPLATES.$set['tpl']);
			$htm->assvar($data);
			$htm->_var($tpl);
			$htm->_if($tpl);
			//$htm->_clear($tpl);
			return $tpl;
		}else{
			$htm->assign($data);
		}
	}

function get_order_id(){
	global $db;
	$id=$db->getid("pay_order","order_id",1);
	$data=array("order_id"=>$id,"date_add"=>time(),'user_id'=>$this->uid);
	$db->insert("pay_order","",$data);
	return $id;
}	

// проверка данных от платежной системы
function checkout(){
	global $htm;

// страница приема результата платежа
if(isset($_POST['LMI_HASH'])){
	$this->receive_post();
	$ok=$this->receive_paid();

	return $ok;
}

// страница успешного платежа
if(_getn('ok')==1){
	$this->receive_post();
	
	$id=$this->data['LMI_SYS_PAYMENT_ID'];
	if($id!=0)
		$this->check_id($id);
}

// страница неуспешного платежа
if(_getn('ok')==2){
	$htm->assign("PAYD_ERROR",1);
}


}	


}
