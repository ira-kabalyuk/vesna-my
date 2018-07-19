<?php
/**
 * CashControl
 * type (string) ключ кеша (обычно имя компоненты)
 * id (int) id виджета, для которого создан кеш
 * linked string через запятую, ключи кешей которые связаны с основным (type) и тоже должны быть очищены
 * 
 * @package   
 * @author SMART
 * @copyright Vladimir
 * @version 2012
 * @access public
 */
class CashControl{
    static $stack=array();
    static $memcache;
    static $jsdata=array();
    
    /**
     * CashControl::clear()
     * очищает кеш модуля и связанных с ним обьектов(виджетов)
     * @param string ключи кеша через запятую (ключ кеша - обычно имя модуля)
     * @return
     */
    static function clear($str=""){
        global $Core;
          $mods=explode(",",$str);  
       
        foreach($mods as $key){
            // очистим основной кеш
            self::_clear($key);
            $m=$Core->db->vector("select id from cash_control where type='$key' and id!=0");
            // очистим  кеш модулей
            if(is_array($m)){
            foreach($m as $r)
                self::_clear($r);
        }
                        
        }
        $m=$Core->db->vector("select linked from cash_control where type='$key' and id=0");
            // очистим  связанный с ключом кеш
        if(is_array($m)){
            foreach($m as $r)
                self::_clear($r);
        }
                        
        }
    
    static function clear_all($mask){
        if(trim($mask)=="") return;
        array_map("unlink", glob(CMS_CASH.$mask));


    }
    
    /**
     * CashControl::create_set($id,$type,[$linked])
     * создает связи для виджета или модуля (вызывается при инсталляции виджета, модуля)
     * @var int $id id виджета
     * @var string $type ключ кеша видджета
     * */
    function create_set($id,$type,$linked=""){
        global $Core;
       $Core->db->execute("delete from cash_control where id=".$id);
       $data=array(
       'id'=>$id,
       'type'=>$type,
       'linked'=>$linked);
        $sql=$Core->db->sql_insert("cash_control","",$data);
       // echo $sql;
        $Core->db->execute($sql);
       
    }
    
    static function key_control($name,$key){
        global $db;
        if($db->value("select count(*) from cash_control where linked='$name'")==0)
            $db->execute("insert into cash_control (id,type,linked) VALUES(0,'$key','$name')");
    }
    
    
    /**
     * CashControl::is_cash($type,[$id])
     * проверяет, есть ли кеш
     * @var string $type ключ кеша
     */
    static function is_cash($key){
         return is_file(self::cash_name($type));
        
    }
    
    
    /**
     * CashControl::cash_name($key)
     * Полученние полного имени файла кеша
     * @param (string|int) ключ кеша или id виджета
     * @return string full path to cach file
     */
    static function cash_name($key){
        global $Core;
        return CMS_CASH.$key."_".$Core->ln;
    }
 
     
    /**
     * CashControl::_save($key,$data)
     * Запись кеша в файл 
     * @param (string|int) $key ключ кеша или id виджета
     * @param mixed $data
     * @return string full path to cach file
     */
    static function _save($key,$data,$cachkey=''){
        if($cachkey!='') self::key_control($key,$cachkey);
        file_put_contents(self::cash_name($key),$data);
    }
    
 
    
    
    /**
     * CashControl::_get($key)
     * получить содержимое кеша
     * @param (int|string)  ключ кеша или id виджета
     * @param integer $id  id виджета
     * @return data or ""
     */
    static function _get($key,$type='string'){
        $file=self::cash_name($key);
    
        if(isset(self::$stack[$key])) return self::$stack[$key];

        if(is_file($file)){
            $ret=file_get_contents($file);
        self::$stack[$key]=($type=='array'? unserialize($ret):$ret);
        return self::$stack[$key];
        }
        return "";
    }

    static function is($key,$type='string'){
        $file=self::cash_name($key);
        if(isset(self::$stack[$key])) return true;
        if(is_file($file)){
            $ret=file_get_contents($file);
            self::$stack[$key]=($type=='array'? unserialize($ret):$ret);
            return true;
        }
        return false;
        
    }

    /**
     * Проверка и загрузка кеша в синглтон кеша
     * @param  [type] $key  имя кеша
     * @param  mix    $ret  ссылка на переменную в которую будет помещен кеш
     * @param  string $type тип кеша string|array
     * @return bool       true - кеш есть
     */
    static function check($key,&$ret="",$type='string'){
        $file=self::cash_name($key);
        if(isset(self::$stack[$key])){
            $ret=self::$stack[$key];
            return true;  
        } 
        if(is_file($file)){
            $ret=file_get_contents($file);
            self::$stack[$key]=($type=='array'? unserialize($ret):$ret);
            $ret=self::$stack[$key];
            return true;
        }
        return false;
    }

    static function is_stak($key){
        return isset(self::$stack[$key]);
    }

    static function stak($key){
        return self::$stack[$key];
    }



    /**
     * CashControl::clear_cash($key)
     * удаление кеша конкретного модуля или виджета
     * @param string $type ключ кеша
     * @param integer $parent_id  id виджета
     * @return
     */
    static function _clear($key){
        if(trim($key)=="") return;
         $file=self::cash_name($key);
         if(is_file($file)) unlink($file);
    }

    /**
     *  кеширование результатов функций и методов 
     *  @param array data кушируемые данные в виде массива
     *  @param string $key ключ кеша
     */
     static function save_data($data, $key){
            file_put_contents(self::cash_name($key), serialize($data));
    }

    /**
     * проверка и получение кеша массива
     * @param  string $key  ключ кеша
     * @param  mixed &$data переменная,в которую помещается результат кеширования
     */
    static function get_data($key,&$data){
            $file=self::cash_name($key);
        if(!is_file($file)) return false;
        $data=unserialize(file_get_contents($file));
        return true;
    }

    static function array_hash($ar){
        if(!is_array($ar)) return $ar;
        $ret="";
        foreach ($ar as $key=>$r)
            $ret.=$key.":".self::array_hash($r).",";
         return $ret;

    }

static function is_memcache(){
    if(!class_exists('Memcache')) return false;
    if(isset(self::$memcache)) return true;
        self::$memcache = new Memcache;
        self::$memcache->pconnect('127.0.0.1', 11211);
    return true;
}

static function check_mem($key,&$ret,$type='string'){
    if(!self::is_memcache()) return false;

    $ret=self::$memcache->get($key);
    if($ret===false) return false;
    if($type!='string') $ret=unserialize($ret);
    return true; 
}

static function save_mem($key,$data){
    if(!self::is_memcache()) return false;
    self::$memcache->set($key,$data,MEMCACHE_COMPRESSED,0);
}

static function memclear($key=''){
    if(!self::is_memcache()) return false;
    if($key==''){
      self::$memcache->flush();  
  }else{
    self::$memcache->delete($key);

  } 

}

static function set_json_cach($data,$key){
    self::save_mem($key,$data);
    return $key;
}

static function put_json_cach($key){
    $hash=md5($key);
    $ret='{';
    foreach(self::$jsdata as $key=>$data){
        if(is_array($data)) $data=self::get_json($data);
        $ret.="\"$key\":$data,";
    }
    $ret.="}";
    self::save_mem($hash,$ret);
    return $hash;
}

static function get_json($ar){
    $ret="{";
    foreach($ar as $key=>$val){
        $ret.="\"$key\":$val,";
    }
    return $ret."}";
}

static function add_json_cach($key,$data,$childkey=""){
   
    if($childkey!=""){
    if(!isset(self::$jsdata[$key])) self::$jsdata[$key]=array();
        self::$jsdata[$key][$childkey]=$data;  
    }else{
      self::$jsdata[$key]=$data;  
    } 

}
    
}

