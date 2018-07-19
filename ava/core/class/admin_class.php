<?
/**
 * Admin
 * 
 * @package   
 * @author SMART
 * @copyright Vladimir
 * @version 2012
 * @access public
 */
class Admin{
	var $debug=true;
	var $config; // конфиг сайтв
	var $user=false;   // Данные пользователя
	var $user_id=0;   // id пользователя
	var $ln='ru'; // текущий язык для контента 
	var $root="/";
	var $db;
	var $htm;
	var $log;
	var $logt;
	var $fire;	
	var $time_start;
	var $mods=array();
	var $right=array(); // права пользователя
		
		/**
		 * Admin::__construct()
		 * 
		 * @param mixed $args
		 * @return
		 */
		function __construct($args){
			$this->time_start=microtime(true);
			$this->root=$_SERVER['DOCUMENT_ROOT']."/";
			$this->ln=(_gets('lang')=="" ? $this->ln:_gets('lang'));
			foreach($args as $k=>$v)
			$this->$k=$v;
			$this->config=array_merge($this->config,load_ar(CONFIG_PATH."all.cfg"));
			}
			

function is_image(){
	global $db;
	if(!preg_match("/\.(jpg|gif|png|js|css)$/", $_SERVER['REQUEST_URI']))
		return;
	header("HTTP/1.0 404 Not Found"); 
	
	exit;

	}	
		
		/**
		 * Admin::make()
		 * 
		 * @return
		 */
		function make(){
			$this->is_image();
			if($this->debug) $this->make_debug();
			$this->make_global(); // создаем глобальные обьекты 
			$uid=$this->logins();			//логиним пользователя
			
			if($uid==0)
				$this->htm->src(ADMIN_TPL."login.tpl");
				$this->htm->assign('SITE_TITLE','Login');
			if(!AJAX){
				$this->make_iface(); // создаем front-end интерфейса
			}else{
				$this->htm->src(ADMIN_TPL."ajax_load.tpl");
				$this->load_mod(); // запускаем контроллер 
			} 
			$this->get();		// выводим страницу
			$this->db->close();  // закрываем соединение с базой
		}
		
		/**
		 * Admin::make_debug()
		 * 	
		 * @return
		 */
		function make_debug(){
			
			include_once CMS_LIBP."FirePHPCore/FirePHP.class.php";
			$this->fire = FirePHP::getInstance(true);
			$this->log=array();
			$this->logt=array();
			$this->log[]=array("QUERY","msec","error");
			$this->logt[]=array("templates","ok");
		
		}
		
		/**
		 * Admin::make_global()
		 * 
		 * @return
		 */
		function make_global(){
			global $db,$htm,$Lang;
			$db=new DB();
			$htm=new Tmpl(ADMIN_TPL."index.tpl");
			$this->db=$db;
			$this->htm=$htm;
			$this->ln=(_gets('lang')=="" ? $this->ln:_gets('lang'));
			$Lang=$this->ln; // для совместимости со старыми версиями компонент
			
			$htm->assign(array(
			"ACL"=>ADMIN_CONSOLE,
			"ART"=>ART,
			"AIN"=>AIN
	 			));
		}
		

		/**
		 * Admin::make_iface()
		 * создание фронт-енда админского интерфейса
		 * @return
		 */
		function make_iface(){
			$this->htm->external('MAIN',ADMIN_TPL."layout_admin.tpl");
			if($this->user){
				$this->htm->assign('USERID',1);
				$this->make_admin_menu();
				$this->load_mod(); // загружаем модуль компонента
			}else{
				$this->htm->external('MAIN',ADMIN_TPL."login.tpl"); // подключаем теплейт формы логина
			}
			
		}
		
		/**
		 * Admin::load_mod()
		 * загрузка контроллера компоненты
		 * @return
		 */
		function load_mod(){
			$mod=_get('mod');
			$com=_get('com');
			$htm=$this->htm;
			$db=$this->db;
			$Lang=$this->ln;
			
			if($com!=''){
				$com='Com_'.$com.'_core';
				$com=new $com;
				$com->Start();
			}elseif($mod==''){
				//include_once CMS_MYLIB."mods/htm/admin.php";
			}else{
				$mod=CMS_MYLIB."mods/".$mod."/admin.php";
				if(is_file($mod)){
					include_once($mod);
				}else{
					$this->htm->assign('CORE_MESSAGE','Компонент недоступен или не подключен');
				} 
			} 
			
		}
	
	/**
	 * Admin::make_admin_menu()
	 * 
	 * @return
	 */
	function make_admin_menu(){
	global $htm;
	$key='adminmenu'.$this->user_id;

		if(CashControl::check($key,$menu)){
			$htm->assign('TOP_MENU',$menu);
		}else{
			$menu=$this->get_parent_menu(0);
			CashControl::_save($key,$menu);
		}
		$htm->assign('TOP_MENU',$menu);	
		
	}

	/**
	 * Главное меню
	 * 
	 * */
	function get_parent_menu($id,$level=0){
		global $db,$htm;

		if($level==0) $tpl=file_get_contents(ADMIN_TPL."nav-menu.tpl");

		$res=$this->db->select(
		"select a.* from smart_menu as a right join users_right as b on (b.user_id={$this->user_id} and b.type=0 and a.id=b.id) where  a.parent_id=$id order by a.sort");
	if(count($res)==0) return '';
	
	$l=$level+1;
	$ret="<ul>";
	foreach($res as $r){
		$r['link']=ADMIN_CONSOLE.'/'.$r['extens'];
		$r['rel']=$r['mod'];
		$r['class']=$r['class'];
		$submenu=$this->get_parent_menu($r['id'],$l);
		if($submenu!="") $r['link']="#";
		$ret.='<li>';
	$ret.='<a href="'.$r['link'].'" title="'.$r['title'].'"><i class="fa '.$r['class'].'"></i><span class="menu-item-parent">'.$r['title'].'</span></a>';

	$ret.=$submenu.'</li>';
	
	}
	$ret.='</ul>';

	
	return $ret;
	}
	
	/**
	 * Admin::logins()
	 * 
	 * @return
	 */
	function logins(){
			global $Core;
			$sname='smart_adminuser';
			$is_login=false;
		// разлогинивание пользователя
		if(isset($_GET["user_exit"])){
				session_unset();
				$Core->db->close();
				setcookie($sname,"" ,time()-100);
				echo file_get_contents(ADMIN_TPL."exit.tpl");
				exit;
		}

	// если передан логин и пароль
	if(isset($_POST["logins"]) && isset($_POST["pass"])){
		$is_login=true;
		$uid=$this->get_admin_user($_POST["logins"],$_POST["pass"]);
			if(count($uid)!=0){
				$_SESSION[$sname]=serialize($uid);
				setcookie($sname,serialize($uid),time()+3600*24);
				$Core->db->close();
				echo file_get_contents(ADMIN_TPL."exit.tpl");
				exit;
			 }else{
		 		$this->user=0;
				$Core->htm->assign('ERROR_MESSAGE','неверный логин или пароль!');
			 }
	}else{ 
		// проверим есть ли инфа о пользователе в сессии
		if(isset($_SESSION[$sname])){ 
			
			$this->user=unserialize($_SESSION[$sname]);
			$this->user_id=intval($this->user['id']);
		// проверим есть ли инфа о пользователе в cookie
		}elseif(isset($_COOKIE[$sname])){
			
			$this->user=unserialize($_COOKIE[$sname]);
			$this->user_id=intval($this->user['id']);
			
		}else{
				$this->user=0;
		}
		return $this->user;
	}
	
}	
	
	/**
	 * Admin::get_admin_user()
	 * 
	 * @param mixed $login
	 * @param mixed $pass
	 * @return
	 */
	function get_admin_user($login,$pass){
	$login=$this->db->clear($login);
	$pass=md5($pass);
	$sql="select id,name,login from users where login like '$login' and passw LIKE '$pass'";
	$m=$this->db->get_recs($sql);
	if(intval($m['id'])!=0){
		// получаем наборы прав
		$m['mod']=explode(",",$this->db->value("select right from users_right where user_id='".$m['id']."' and type='mod'"));
		
	}	
	//print_r($m);
	return $m;
}
/**
 * Admin::user_right()
 * 
 * @param string $type тип обьекта (mod|mod_submod)
 * @param mixed $key
 * @return
 */
function user_right($type){
	$a=explode("_",$type);
	
	if(!is_array($this->user['right'.$key])) return false;
	return in_array(intval($r),$this->user['right'.$key]);
	
}	
/**
 * Admin::get()
 * 
 * @return
 */
function get(){
	$ret=$this->htm->get();
	if($this->debug){
		
		$this->fire->log((microtime(true)-$this->time_start),'total time');
		$this->fire->log( round(memory_get_usage()/1024)."Kb",'total memory');
		$this->fire->table('tpl trace',$this->logt);
		$this->fire->table('SQL TRACE',$this->log);
	
	}
	echo $ret;
	//if($this->debug) echo $this->print_table($this->log);
	
}
/**
 * Admin::ajax_get()
 * 
 * @param string $ret
 * @return 
 */
function ajax_get($ret){
	global $db;
	if($this->debug){
		$this->fire->log((microtime(true)-$this->time_start),'total time');
		$this->fire->log( round(memory_get_usage()/1024)."Kb",'total memory');
		$this->fire->table('tpl trace',$this->logt);
		$this->fire->table('SQL TRACE',$this->log);
	}
	$db->close();
	echo $ret;
//	echo $this->print_table($this->log);
	exit;
	
}

function json_get($ret){
	$this->ajax_get(get_json($ret));
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

?>