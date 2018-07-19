<?php

global $db;
$self=$args[1];

$data=$self->meta;

if(!isset($data['start']) || !isset($data['stop'])) return;


	  $month=array("","января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
	  
	//$d1=explode(".", date("d.m.y",$start));
	$d1=explode(".", $data['start']);
	//$d2=explode(".", date("d.m.y",$stop));
	$d2=explode(".", $data['stop']);

	$diff=intval($d2[0])-intval($d1[0]);
	$difday=$d1[0];
	if($diff>0)
		$difday.="-".$d2[0];
		
	

	
	$difmonth=$month[intval($d1[1])];
 	$difyear=date("Y",$stop);
	$self->add_meta('daydiff',$difday);
	$self->add_meta('monthdiff',$difmonth);




