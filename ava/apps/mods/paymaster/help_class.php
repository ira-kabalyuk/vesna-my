<?php
class Mods_paymaster_help{

	static function get_order($jar){
		global $Core;
		$set=parse_jar($jar);
		$pay=new Mods_Paymaster_core();
		return $pay->get_form($set);
	}

	


	public static function __callStatic($name, $arguments) {
        	return __CLASS__.":$name not defined!";
    
}

}