<?php



$mod_note=new RoboEdit;

$mod_note->Start();



class RoboEdit {
	
	var $file;
	var $modlink;
	
	

	
function Start(){
	global $htm,$_root;

		$this->file=$_root."robots.txt";
		$this->modlink=ADMIN_CONSOLE."/?mod=plugins&plu=robots";
		$this->root=ADMIN_CONSOLE."/?mod=plugins";
		$this->mp=dirname(__FILE__)."/";
		$htm->assign("MOD_LINK",$this->modlink);
		$htm->assign("MOD_ROOT",$this->root);
		$htm->assign("MOD_TITLE","Утилиты");

		$act=_get('act');
		if($act=='save'){

			$this->_save();
		
		}
		$this->_list();

	}
	
/**
 * Отображение списка страниц
 * */
function _list(){
	global $htm;
		
		$this->load_tpl("edit.tpl");
		if(!is_file($this->file))
			file_put_contents($this->file, "Disallow: \n");
		$cont=file_get_contents($this->file);
		
	
 			 $htm->assign("NOTE", $cont);
			
	
}

	
/** 
 * SAVE page
 * */	
function _save(){
   file_put_contents($this->file, _posts('note'));

}


	function load_tpl($tpl){
		global $htm;
		if(AJAX){
			$htm->src($this->mp.$tpl);
		}else{
			$htm->external("EXT_ADD",$this->mp.$tpl);
		}
	}
    

}
