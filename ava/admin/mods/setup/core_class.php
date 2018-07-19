<?php
class Mods_setup_core extends Lform{
	var $sets;
	var $in;
	var $pref=array();
	var $suffix="";
	
	function __construct(){
		parent::__construct();
		$this->mp=dirname(__FILE__)."/";
	}
	
	function admin_setup($type,$parent=""){
			global $htm;

		if($type==''){
				$conf=CONFIG_PATH."all.cfg";
				$xml=CONFIG_PATH."all.xml";
		}else{
				$conf=CONFIG_PATH."mod_".$type.$parent.".cfg";
				$xml=CMS_MYLIB.'mods/'.$type."/config.xml";
			}
		
		if(AJAX) $htm->src($this->mp."all.tpl");
			$htm->external("EXT_ADD",$this->mp."all.tpl");
			

			$this->load_set($xml);
		$all_ar=load_ar($conf);
			if(!is_array($all_ar)) $all_ar=array();

 if (_get('act')=='save'){
 	  $all_ar=$this->get_post_sets();
		 save_ar($all_ar,$conf);
		}
		$this->save_modlink($all_ar);
		$this->import_var($all_ar);
		$htm->assign("FORM_CONTENT",$this->get_form());
}
	
	
	function load_set($file,$access=0){
		if(!is_file($file)) return;
 		$xml = simplexml_load_file($file);
 		
 		foreach ($xml->a as $sets){
 			$name=(string) $sets->attributes()->name;
			$this->sets[$name]=array();
			$this->sets[$name]['attr']=$this->get_xml_atributes($sets);
			$this->sets[$name]['fields']=array();
			$prefix=(isset($this->sets[$name]['attr']['prefix']) ? $this->sets[$name]['attr']['prefix']:'' );
		
			foreach($sets->set as $set){
			    $sname=(string) $set->attributes()->name;
				$this->in[$prefix.$sname]=$this->get_xml_atributes($set);
				$this->sets[$name]['fields'][]=$sname;
				$this->pref[$prefix][]=$sname;
			}
			
    
}

	}
	


function get_xml_atributes($set){
	$pars=array();
	foreach($set->attributes() as $a=>$b) {
		$a=(string) $a;
		$b=(string) $b;
	  $pars[$a]= $b;
}
return $pars;    
}

function get_form($keys=true){
	$ret="";
	$this->pr="";
	
	$keys=_to_array($keys,array_keys($this->sets));
	$tm='<ul class="nav nav-tabs bordered">';
	$ts='<div class="tab-content padding-10">';
	$a=" active";
	$at=" in active";
	foreach($this->sets as $name=>$s){
			if(!in_array($name,$keys)) continue;
	$r=$s['attr'];
	$r['class'].=$a;
	
	$tm.='<li class="'.$a.'"><a href="#set-'.$r['name'].'" data-toggle="tab"><i class="fa fa-fw fa-lg '.$r['class'].'"></i>'.$r['title'].'</a></li>';
	$ts.='<div class="tab-pane fade'.$at.(isset($r['class']) ? " ".$r['class']:"").'" id="set-'.$r['name'].'">';
	$a="";
	$at="";
	if($r['class']!='ajax'){
 // загрузка закладки производится через ajax;
	$ts.=$this->get_set($name);
		}
	$ts.='</div>';
}
$ts.='</div>';
$tm.='</ul>';
return $tm.$ts;
}

/**
 * получение POST данных формы в виде массива
 * */
function get_post_sets($keys=true){
	$post=array();
	$keys=_to_array($keys,array_keys($this->sets));
	
	foreach($keys as $set){
		$prefix=(isset($this->sets[$set]['attr']['prefix']) ? $this->sets[$set]['attr']['prefix']:'' );
		if(!is_array($this->sets[$set]['fields'])) continue;
		foreach($this->sets[$set]['fields'] as $key){
					if($prefix!=''){
						$post[$prefix][$key]=trim($_POST[$prefix][$key]);
						}else{
					$post[$key]=(is_array($_POST[$key]) ? implode(",",$_POST[$key]) : _posts($key));
						}
	}
}
	return $post;
}


/**
 * Генерация набора элементов формы
 * */
function get_set($name, $only_fields=false){
	 // загрузка закладки производится через ajax;
	 $s=$this->sets[$name];
	 $r=$s['attr'];
	$prefix=(isset($r['prefix']) ? $r['prefix']:'');
	$ret="";
	$fields=array();
		foreach($s['fields'] as $f){
		$n=$prefix.$f;
		
	//	if($this->in[$f]['type']=='hidden') continue;
			$c=$this->get_inp(($prefix=='' ? $f:$prefix.'['.$f.']'),$this->in[$n]);

		if($f=='link') $c['cont'].=$this->suffix;
		if($only_fields){
			$fields[$n]=$c['cont'];
		}else{
		$ret.='<section class="mt10"'.(isset($this->in[$n]['id']) ? ' id="'.$this->in[$n]['id'].'"':'').'>';
		//if(isset($this->in[$n]['title'])) $ret.='<label>'.$this->in[$n]['title'].'</label>';
		$ret.=($this->in[$n]['type']=='editor' ? '<br>':'').$c['cont'].'</section>';
		}
	}


	return ($only_fields ? $fields :$ret);
	
}


function import_var($vars){
	$ret=array();
	$pref=array_keys($this->pref);
	foreach($vars as $name=>$val){
		if(in_array($name,$pref)){
			foreach($val as $k=>$v)
				$ret[$name.$k]=$v;
		}else{
			$ret[$name]=$val;
		}
	}
	
	$this->add_var($ret);
	
}

function save_modlink($arg){
	global $db;
	if(!isset($arg['parent_id']))
		return;
	if(!isset($arg['prefix']))
		return;
	$data=array();
	
	$data['link']=$arg['prefix'];
	$db->update("mods","",$data," where parent_id=".intval($arg['parent_id']));
}	

}
