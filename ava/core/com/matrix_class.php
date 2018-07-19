<?php
class Com_matrix{
	var $cell=array();
	var $i=0;
	var $row=array();
	
	function cell($arg){
	$this->cell[$this->i][]=$arg;	
	}
	function row(){
		$this->row[$this->i]='draglist';
		$this->i++;
	}
		function head(){
		$this->row[$this->i]='ulhead';
		$this->i++;
	}
	
	function _get(){
		$ret="";
		$b='';
		$i=0;
		foreach($this->cell as $r){
			
			$li='<li>';
			foreach($r as $c)
				$li.='<span class="'.$c['class'].'">'.$c['content'].'</span>';
			$li.='</li>';
			if($this->row[$i]!=$b){
					$li=($b!='' ? '</ul>':'').'<ul class="'.$this->row[$i].'">'.$li;
					$b=$this->row[$i];
							}		
				$ret.=$li;
	$i++;	
	}
	
	return $ret.'</ul>';
	}
}