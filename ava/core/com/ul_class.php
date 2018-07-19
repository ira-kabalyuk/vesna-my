<?php

class Com_ul{
	var $xml;
	var $ul='';
	var $head='';
	var $tool=false;
	var $toolset='';
	var $map;
	
	
	/**
	 * Com_ul::init()
	 * 
	 * @param mixed $xml
	 * @return
	 */
	function init($xml,$div){
		$this->xml=load_xml_file($xml);
		$this->ul='<table class="table table-striped table-bordered table-hover" id="ul_'.$div.'">';
	}
	
	/**
	 * Com_ul::add_head()
	 * 
	 * @return
	 */
	function add_head(){
		$this->ul.='<thead><tr>';
		foreach($this->xml as $t)
			$this->ul.='<th class="'.$t['class'].'" data-c="'.$t['name'].'">'.htmlspecialchars($t['title']).'</th>';

			if($this->tool) $this->ul.='<th class="tool" data-c="tool"> действия </th>';

			$this->ul.='</tr></thead><tbody></tbody>';
	}
	
	function get_json($a){
		if(!isset($a['type'])) return "";
			$ret=array();
		if(isset($a['cont'])) $a['cont']=Input::get_ar_js($a['cont']);
		foreach($a as $key=>$val)
			$ret[]='"'.$key.'":"'.$val.'"';

		if(count($ret)==0) return "";
		return "{".implode(",",$ret)."}";
	}

	
	
	/**
	 * Com_ul::get_ul()
	 * 
	 * @return
	 */
	function get_ul(){
		return $this->ul.'</table>';
	}
	


	
	/**
	 * Com_ul::toolset()
	 * Создает набор инструментов в строке
	 * @param mixed $set ('onof,edit,del')
	 * @return
	 */
	function toolset($set){
		$tool=array(
		'edit'=>'<img src="'.AIN.'/img/image_edit.png" class="_edit" title="редактировать"/>',
		'onof'=>'<img src="'.AIN.'/img/{onof}.png" class="_onof"  title="Вкл./Выкл"  />',
		'del'=>'<img src="'.AIN.'/img/cross.png" title="удалить" class="_del" />'
		);
		$set=explode(",",$set);
		foreach($set as $s){
			$this->toolset.=$tool[$s];
		}
		$this->tool=true;
		
	}
	
}