<?
class Core{
	var $db=null;
	var $htm=null;
	var $link=null;
	var $ln='ru';
	var $conf=array();
	var $log;
	var $logt;
	var $debug=false;
	var $fire;
	var $user=array();
	var $start_time;
	

	
	function __construct(){
				global $config;
				
				$this->start_time=microtime(1);
				$config=array_merge($config,load_ar(CONFIG_PATH."all.cfg"));
				$this->conf=$config;
				$this->debug=$config['debug'];
				define ('AJAX', isset($_GET['aj']) || isset($_GET['_']) || isset($_POST['aj']));
				//define ('AJAX',true);
		}	

	function init($parse=true){
			global $db,$htm,$Link;
				if($this->debug)	$this->make_debug();
				$this->db=new DB();
				$db=$this->db;
				if($parse) $this->link=new Link();
				$this->htm=new Tmpl();
				$htm=$this->htm;
				//define('TEMPLATES',APP_VIEWS);
		
			
				
	}

	function get(){
		
			
			
		$this->htm->assign('NOINDEX',(stripos($_SERVER['REQUEST_URI'],'?')===false ? 0 :1));

		$this->link->route();

		if(is_file(CMS_APP."mods/".$this->link->mod."/route_class.php")){
			$mod="Mods_".$this->link->mod."_route";
			$route=new $mod;
			$route->route();
		}else{
			$this->link->e_404("Mods ".$this->link->mod." not installed!");
 			
		}
		
		
		
		$ret=$this->htm->get();
		$this->db->close();
		if($this->debug) $this->_trace();
			echo $ret;
	if($this->debug){
		//	echo $this->print_table($this->logt);
		//	echo $this->print_table($this->log);
		}
	}
	
	function ajax_get($ret){
	global $db;
	if($this->debug) $this->_trace();

		$db->close();
	
		echo $ret;

	exit;
	
}

function json_get($ret){
	global $db;
	if($this->debug) $this->_trace();
	$db->close();
	
	echo get_json($ret);

	exit;
}

function _trace(){
		$this->fire->log((microtime(1)-$this->start_time),'total time');
		$this->fire->log( round(memory_get_usage()/1024)."Kb",'total memory');
		$this->fire->table('tpl trace',$this->logt);
		$this->fire->table('SQL TRACE',$this->log);
}

function save_log($file,$msg){
	$fo=fopen(LOG_PATH.$file,'a+');
	fwrite($fo,date("d.m.Y H:i:s")."\n".$msg."\n");
	fclose($fo);
}	
	
 function make_debug(){
			include_once CMS_LIBP."FirePHPCore/FirePHP.class.php";
			$this->fire = FirePHP::getInstance(true);
			$this->log=array();
			$this->logt=array();
			$this->log[]=array("QUERY","msec","error");
			$this->logt[]=array("templates","ok");
		
		}
		
function print_table($tab){
	$r='<table class="tlist" cellspacing="0" border="0">';
	foreach($tab as $tr){
		$r.='<tr>';
		foreach($tr as $td)
			$r.='<td>'.$td.'</td>';
			$r.='</tr>';
	}
	return $r.='</table>';
}		
	
}
