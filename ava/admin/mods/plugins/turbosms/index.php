<?php
$htm->external("EXT_ADD",MOD_PATH."admin.tpl");
$modlink=ADMIN_CONSOLE."/?mod=plugins&plu=".$plugin;
$htm->assign(array(
'MOD_LINK'=>$modlink,
));
$log='';
$text="";
$phone="";


	if(_get('act')=='send'){
		$sms= new Com_turbosms;
		
		$log.=$sms->_connect().'<br/>';
		$text=_posts('text');
		$phone=_posts('phone');
		
		$log.=$sms->send_sms($text,$phone).'<br/>';
		
		
		
	}
	

$htm->assign(array(
		'phone'=>$phone,
		'text'=>$text,
		));
$htm->assign('LOG',$log);



