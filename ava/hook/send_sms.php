<?php
/**
 * Отправка СМС через шлюз TurboSMS
 * */
global $Core;
$number=trim($args[1]);
$number=str_replace(array("(",")","-"," "), "", $number);
$text=trim($args[2]);
if(trim($text)=='') return;
if(!preg_match("/\+380[0-9]{9}/",$number)) return;
$log="";
$sms=new Com_turbosms;
$log.=$sms->_connect()."\n";
$log.=trim($sms->send_sms($text,$number))."\n";
$log.="номер $number \n текст $text \n\n";
add_log("sms",$log);
