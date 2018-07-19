<?php
class Mods_actions_core extends Mods_news_core{
	var $edd=1;	

	function prepend(&$r){

		$r['class'.$this->edd] = 1;
		$this->edd = ($this->edd==1 ? 2:1);

	}

	

}