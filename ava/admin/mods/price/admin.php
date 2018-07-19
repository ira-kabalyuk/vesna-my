<?php
/**
 * Модуль Цены
 */


class PriceMod{

function Start(){
	global $htm,$Core;
	$this->mp=dirname(__FILE__)."/";
	$htm->src($this->mp."list.tpl");

	$htm->assign("MOD_LINK","/smart/?mod=price");
	$htm->assign("PRICE",file_get_contents(APP_VIEWS."/tpl/price-table.tpl"));
	$htm->assign("PRICE_TPL",file_get_contents($this->mp."tpl.htm"));

	$act=_gets('act');

	if($act=="save_price")
		$Core->ajax_get($this->save_price());


}

function save_price(){
	$data=_posts('table');
	
	if(trim($data)==""){
		return "no";
	}

	file_put_contents(APP_VIEWS."/tpl/price-table.tpl", $data);
	
	return 'ok';

}


}



$mod=new PriceMod();

$mod->Start();



