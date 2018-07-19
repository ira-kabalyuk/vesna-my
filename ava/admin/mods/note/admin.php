<?php



$mod_note=new Mods_note();

$mod_note->Start();



class Mods_note {
	
	var $file;
	var $modlink;
	
	

	
function Start(){
	global $htm;

		$this->file=ENGINE_PATH."conf/note.html";
		$this->modlink=ADMIN_CONSOLE."/?mod=note";
		$this->mp=dirname(__FILE__)."/";
		$htm->assign("MOD_LINK",$this->modlink);

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
