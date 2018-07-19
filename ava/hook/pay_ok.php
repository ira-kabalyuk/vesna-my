<?php
/**
 * удачная оплатат через Паймастер
 * */
global $db;
$data=$args[1];

// пометим заказ как оплаченный
$upd=['status'=>1,'date_payd'=>time()];
$db->update("booking","",$upd," where id=".$data['order_id']);
