<?php

/*
global $htm;
$htm->addscript("js",AIN."/js/poshy/min.js");
$htm->addscript("js",AIN."/mods/order/order.js");
$htm->addscript("css",AIN."/js/poshy/yellow/tip-yellow.css");
$htm->addscript("css",AIN."/css/forms.css");
$mod= new Form_admin;
*/
$mod= new Form_admin;
$mod->route();

class Form_admin{
	
	function route(){
	
		$sub=_get('sub');
		if($sub=='form'){
			$form=new Mods_forms_core;
			$form->route();
		}else{
			$msg=new Mods_forms_msg;
			$msg->route();
		}
		
		
	}
	
}
