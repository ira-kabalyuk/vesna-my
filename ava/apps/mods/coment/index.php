<?php
global $Core;
$link=$Core->link->Link[2];
switch ($link) {
	case 'add':
		Mods_coment_core::add_coment();
		break;
	
	default:
		$Core->ajax_get('false');
		break;
}