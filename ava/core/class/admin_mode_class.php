<?php
class Admin_mode{
	static $conf=false; // конфиг компонента
	var $mod=''; // mod name as folder name
	public $modlink='';
	var $mp='';  // mod path
	var $title='';
	var $ln='ru'; // default language
	
	function __construct($mod){
		$this->mod=$mod;
		$this->mp=CMS_MYLIB."mods/".$mod."/";
		$this->ln=(_gets('lang')=="" ? $this->ln:_gets('lang'));
		$this->modlink=ADMIN_CONSOLE."/?mod=".$mod."&lang=".$this->ln;
		
	}
	
	function __get($name){
		if($name=='conf'){
			if(!self::$conf) $this->load_mod_config();
		}
		return $this->$name;
	}
	function &ref($name){
		return $this->$name;
	}

	
	function load_mod_config(){
		global $Core;
			$f=CONFIG_PATH."mod_".$this->mod.".cfg";
		if(is_file($f)){
			self::$conf=load_ar(CONFIG_PATH."mod_".$this->mod.".cfg");
			}else{
				if($Core->debug) $Core->logt=array('не удалось загрузить конфиг модуля '.$this->mod,'error');
				
				self::$conf=array('not '.$f);
			}
		return self::$conf;
	}
	
	function prepend(){
		global $Core;
		$Core->htm->assign(array(
			'MOD'=>$this->mod,
			'LANG'=>$this->ln,
			'MOD_LINK'=>$this->modlink,
			'TITLE_MOD'=>$this->title
		));

	}
	
	function _setup(){
		$set=new Mods_setup_core();
		$set->admin_setup($this->mod);
	}

}
