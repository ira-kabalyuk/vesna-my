<?php
global $Core;
$link=$Core->link->Link[2];
switch ($link) {
	case 'facebook':
		Mods_auth_facebook::auth();
		break;
	case 'vkontakte':
		Mods_auth_vkontakte::auth();
		break;
	case 'local':
		Mods_auth_local::auth();
	break;

	case 'registration':
		Mods_auth_local::register();
	break;

	case 'forgot':
		Mods_auth_local::forgot_password();
	break;

	case 'logout':
		Mods_auth_local::logout();
	break;

	default:
		$Core->link->redirect("/");
		break;
}