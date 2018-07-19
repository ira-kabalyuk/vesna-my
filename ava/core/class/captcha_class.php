<?php 
class Captcha{
	
	static function _get(){
		$c=new Com_captcha_core(5);
		$captcha -> display();
		$_SESSION['captcha'] = $captcha -> getString();
		
	}
	
	static function check(){
		return (strtolower(_post('captcha'))==strtolower($_SESSION['captcha']));
	}
	
}

?>