<?php
class Fastmail{


static function php_mailer($arg){
		global $Core;

$oMailer = new PHPMailer;
		$oMailer->Priority = 3;
			$oMailer->IsMail();
		//$oMailer->IsSendmail();
		//$oMailer->isSMTP(); 
//		$oMailer->SMTPDebug  = 2;
		//	$oMailer->SMTPSecure = "tls";
/*
$oMailer->CharSet = "utf-8";
$oMailer->From = "info@zotis.net";
$oMailer->FromName = $Core->conf['smtp_subject'];
$oMailer->Sender = $oMailer->From;
$oMailer->Host = 'smtp.yandex.ru';
$oMailer->Port = 465;
$oMailer->SMTPSecure = 'ssl';
$oMailer->SMTPAuth  = true;
$oMailer->Username  = "info@zotis.net";
$oMailer->Password  = "BerhZ00m";
*/

		$oMailer->CharSet = "utf-8";
		$oMailer->From = $Core->conf['mail_from'];
		$oMailer->FromName = $Core->conf['smtp_subject'];
		$oMailer->Sender = $oMailer->From;
		/*
		$oMailer->Host = $Core->conf['smtp_host'];
		//$oMailer->Port = 25;
		//$oMailer->Port = 465;
		$oMailer->Port = 587;
		//$oMailer->SMTPSecure = 'ssl';
		$oMailer->SMTPSecure = 'tls';
		$oMailer->SMTPAuth  = true;
		$oMailer->Username  = $Core->conf['smtp_login'];
		
		$oMailer->Password  = $Core->conf['smtp_passw'];
		*/
		$oMailer->Subject = trim($arg['subject'])!="" ? $arg['subject'] : $Core->conf['smtp_subject'];
		$oMailer->Body = $arg['body'];
		$oMailer->isHTML(true);
		if(is_array($arg['to_mail'])){
			foreach ($arg['to_mail'] as $mail) {
				$oMailer->AddAddress($mail);
			}
		}else{
			$oMailer->AddAddress($arg['to_mail']);
		}
		
		$ret=$oMailer->Send();
		add_log('phpmailer',print_r($arg['to_mail'],true)." ".(string) $ret);
		

}

static function send($arg){
	global $Core;
		if($Core->debug){
		file_put_contents(LOG_PATH."mail_".date("d-m-Y_H-i-s").".html",array_tostr($arg,array('=>',"<br>\n")).".html");
		}
		if($Core->conf['nosend']) return;
		//return;
		
	self::php_mailer(array(
	'to_mail'=>$arg['to_mail'],
	'body'=>$arg['body'],
	'subject'=>$arg['subject']
	));


	
}

	
}
