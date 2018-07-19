<?php
class Fastmail{


static function php_mailer($arg){
		global $Core;

$oMailer = new PHPMailer;
		$oMailer->IsSendmail();
		$oMailer->Priority = 3;
//		$oMailer->SMTPDebug  = 2;
		//	$oMailer->SMTPSecure = "tls";
		$oMailer->CharSet = "utf-8";
		$oMailer->From = $Core->conf['mail_from'];
		$oMailer->FromName = $Core->conf['smtp_subject'];
		$oMailer->Sender = $oMailer->From;
		$oMailer->Host = 'localhost';
		$oMailer->Port = 25;
		$oMailer->SMTPAuth  = true;
		$oMailer->Username  = $Core->conf['mail_from'];
		$oMailer->Password  = $Core->conf['smtp_passw'];
		$oMailer->Subject = $arg['subject'];
		$oMailer->Body = $arg['body'];
		$oMailer->isHTML(true);
		$oMailer->AddAddress($arg['to_mail']);
		$ret=$oMailer->Send();
		add_log('phpmailer',$arg['to_mail']." ".(string) $ret);
		

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
