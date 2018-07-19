<?php
class Com_mod{
	
	/**
	 * возвращает админ-конфиг модуля
	 * @param $modname(string)
	 * @return array
	 * */ 
	static function get_config($modname,$parent=""){
			global $Core;
			$f=CONFIG_PATH."mod_".$modname.$parent.".cfg";
			$ret=array();
		if(is_file($f)){
			$ret=load_ar(CONFIG_PATH."mod_".$modname.$parent.".cfg");
			}else{
				if($Core->debug) $Core->logt=array('не удалось загрузить конфиг модуля '.$modname,'error');
			}	
		return $ret;
		
	}
	
static function save_config($modname,$config){
			$f=CONFIG_PATH."mod_".$modname.".cfg";
			file_put_contents($f, serialize($config));
}

	/**
	 * Com_mod::load_conf()
	 * 
	 * @param string $name - имя модуля
	 * @return
	 */
	static function load_conf($name,$parent=""){
		return self::get_config($name,$parent);
	}
	
/**
 * java-script Пагинатор
 * @var count (string|int) sql-запрос или кол-во записей
 * @var conf (array) limit,link,div
 * */ 	
  static function paginator($count,$conf,$div=''){
    global $db,$htm;
    $htm->addscript("js","/skin/admin/js/pg.js");
	$htm->addscript("css","/skin/admin/css/pg.css");
    $page=_getn('page');
    //print_r($conf);
    $maxrows=intval($conf['limit']);
    $link=$conf['link'];
    if(is_string($count)){
    	$count=intval($db->value($count));
    }
    $link=preg_replace("/\&page\=[(0-9)]+.*/","",$_SERVER['REQUEST_URI']);
    if($maxrows==0) return '';
    if($count<=$maxrows) return '';
    $cur=($page==0 ? 1 : $page);
    $p=1;
    $limit='';
    while($count>0){
        if($cur==$p){
                   $limit=" limit ".($p-1)*$maxrows.",".$maxrows; 
                }
        $count-=$maxrows;
        $p++;
    }
    $p--;
    $ret='
    <p class="paginator" id="PaginatorT"></p>
	<script type="text/javascript">
		PaginatorT = new Paginator(\'PaginatorT\','.$p.',10,'.$cur.', \''.$link.'&page=\',\''.$div.'\');
	</script>';
    $htm->assign("PAGINATOR",$ret);
    $htm->assvar("PAGINATOR",$ret);
   //echo $limit;
    return $limit;
  }	
	
	static function onoff($table,$id){
		global $db;
		 $db->execute("update $table set is_hidden=(is_hidden XOR 1) where id=".$id);
        return ($db->value("select is_hidden from $table where id=".$id)==1 ? 'off' :'on' );
	}	
	
}