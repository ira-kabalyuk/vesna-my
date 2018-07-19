<?php
class Mods_members_ajax{


	static function get_staff(){
		$mod=new Mods_members_core();
		return $mod->get_staff();
		
	}

	static function edit_staff(){
		$mod=new Mods_members_core();
		return $mod->edit_staff(_getn('id'));
		
	}
	static function add_staff(){
		$mod=new Mods_members_core();
		return $mod->add_staff();
		
	}

	static function save_staff(){
		$mod=new Mods_members_core();
		return $mod->update_staff(_getn('id'));
		
	}

}//end class