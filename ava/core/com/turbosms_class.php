<?php  

/** 
 * Данный пример предоставляет возможность отправлять СМС сообщения 
 * с подменой номера, просматривать остаток кредитов пользователя,  
 * просматривать статус отправленных сообщений. 
 * ----------------------------------------------------------------- 
 * Для работы данного примера необходимо подключить SOAP-расширение. 
 * 
 */ 
 
 class Com_turbosms{
	var $client;
    var $sign="";
// Все данные возвращаются в кодировке UTF-8 
//header ('Content-type: text/html; charset=utf-8'); 

// Подключаемся к серверу 
function _connect(){
    global $Core;
$this->client = new SoapClient ('http://turbosms.in.ua/api/wsdl.html'); 
// Данные авторизации 
$auth = Array ( 
        'login' => $Core->conf['sms_login'], 
        'password' => $Core->conf['sms_passw'] 
    ); 
$this->sign=$Core->conf['sms_sign']; 
// Авторизируемся на сервере 
$result = $this->client->Auth ($auth); 

// Результат авторизации 
return $result->AuthResult; 
}

function get_function(){
// Можно просмотреть список доступных функций сервера 
echo '<pre>'; 
print_r ($this->client->__getFunctions ()); 
echo '</pre>'; 
}

function get_credit(){
// Получаем количество доступных кредитов 


}

/**
 * Com_turbosms::send_sms()
 * 
 * @param string $txt
 * @param string $number 
 * @return void
 */
function send_sms($txt,$number){
// Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8 
// Номера разделены запятыми без пробелов. +380XXXXXXXXX
//$text = iconv ('windows-1251', 'utf-8', $txt); 

// Данные для отправки 
$sms = Array ( 
        'sender' => "Lash.Moda", 
        'destination' => $number, 
        'text' => $txt
		//'wappush' => 'http://rebenok.zotis.net'  
    ); 

// Отправляем сообщение на один номер.  
// Подпись отправителя может содержать английские буквы и цифры. Максимальная длина - 11 символов. 
// Номер указывается в полном формате, включая плюс и код страны 
$result= $this->client->SendSMS($sms); 
return $result->SendSMSResult->ResultArray[0];
}

 /*

// Выводим результат отправки. 
echo $result->SendSMSResult->ResultArray[0] . '<br />'; 

// ID первого сообщения 
echo $result->SendSMSResult->ResultArray[1] . '<br />'; 

// ID второго сообщения 
echo $result->SendSMSResult->ResultArray[2] . '<br />'; 

// Отправляем сообщение с WAPPush ссылкой 
// Ссылка должна включать http:// 
$sms = Array ( 
        'sender' => 'Rassilka', 
        'destination' => '+380XXXXXXXXX', 
        'text' => $text, 
        'wappush' => 'http://super-site.com' 
    ); 

$result = $client->SendSMS ($sms); 

// Запрашиваем статус конкретного сообщения по ID 
$sms = Array ('MessageId' => 'c9482a41-27d1-44f8-bd5c-d34104ca5ba9'); 
$status = $client->GetMessageStatus ($sms); 
echo $status->GetMessageStatusResult . '<br />'; 

// Запрашиваем массив ID сообщений, у которых неизвестен статус отправки 
$result = $client->GetNewMessages (); 

// Есть сообщения 
if (!empty ($result->GetNewMessagesResult->ResultArray)) { 
    echo '<pre>'; 
    print_r ($result->GetNewMessagesResult->ResultArray); 
    echo '</pre>'; 

    // Запрашиваем статус каждого сообщения по ID 
    foreach ($result->GetNewMessagesResult->ResultArray as $msg_id) { 
        $sms = Array ('MessageId' => $msg_id); 
        $status = $client->GetMessageStatus ($sms); 
        echo '<b>' . $msg_id . '</b> - ' . $status->GetMessageStatusResult . '<br />'; 
    } 
}
*/ 
}
