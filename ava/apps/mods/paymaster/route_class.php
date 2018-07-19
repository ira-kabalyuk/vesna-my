<?php
class Mods_paymaster_route{

	function route(){
		global $Core;
		$link=trim($Core->link->Link[2]);
		$mod = new Mods_paymaster_core();
		$conf=$mod->set;
		
		
	
		$k=explode("/",$conf['ok_url']);
		$ok_url=end($k);
		$k=explode("/",$conf['no_url']);
		$no_url=end($k);
		$k=explode("/",$conf['server_url']);
		$server_url=end($k);
		

		switch ($link) {
			case 'order':

				$this->get_order($mod);
				
			break;
			case $ok_url:
				# страница успешной оплаты
				$Core->htm->external("EXT",TEMPLATES."predoplata-step-3.tpl");
				$Core->htm->assign($_POST);
				if($conf['log']==1)
					add_log("paymaster",print_r($_POST,true));
			break;

			case $no_url:
				$Core->htm->external("EXT",TEMPLATES."predoplata-error.tpl");
			break;

			case $server_url:
				# страница приема данных от платежной системы
			add_log('payserver',"recive");
				if($mod->checkout()){
					// платеж проведен успешно
					_emit('payd_ok',$mod->data);
				}
				$Core->ajax_get('ok');
			break;

			
			default:
				# code...
				break;
		}



	}

function get_order($mod){
		global $db,$htm;
		$htm->src(TEMPLATES."pay-form.tpl");

		$title=_posts('title');
		$price=_postn('price');	
		//$order_id=$db->getid('pay_order','order_id',1);	
		$data=[
		//	'order_id'=>$order_id,
			'name'=>_posts('name'),
			'phone'=>_posts('phone'),
			'email'=>_posts('email'),
			'amount'=>$price,
			'title'=>$title,
			'date_add'=>time()
			];
		
		$db->insert("pay_order","",$data);
		$order_id=$db->last_id();
		
		$mod->get_form(['mode'=>$mod->set['mode'],'title'=>htmlspecialchars($title),'price'=>$price,'order_id'=>$order_id]);

		
}




}