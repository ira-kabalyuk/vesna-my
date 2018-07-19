<?php
class Ajaxstatic{
	
	public static function _callStatic($name,$args){
		global $Core;
		$Core->get_json(array('ok'=>false,'err'=>' method '.$name.' not supported'));
	}
}