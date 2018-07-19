<?php
class DB {
   var $dbh;
   var $cach=array();
 	 function __construct()
	 {
		global $config;
		$this->dbh = mysql_connect($config["db_server"], $config["db_login"], $config["db_passw"]);
		if (!$this->dbh) {
			print "Can't connect to DB server";
			print $config["db_server"];
			exit;
		}
		mysql_select_db($config["database"],$this->dbh);
                mysql_query('set names utf8', $this->dbh);
	}

	function changedb($db){
		mysql_select_db($db,$this->dbh);
	}

	function reconnect()
	{
		global $config;
		mysql_close($this->dbh);
		$this->dbh = mysql_pconnect($config["db_server"], $config["db_login"], $config["db_passw"]);
		if (!$this->dbh)
		{
			print "Can't connect to DB";
			print $config["db_server"].$config["database"] ;
			exit;
		}
		mysql_select_db($config["database"],$this->dbh);
        //mysql_query('set names utf-8', $this->dbh);
	}

	function insert_id()
	{
		return	mysql_insert_id($this->dbh);
	}


	function last_id(){
		return	mysql_insert_id($this->dbh);
	}
	
	function get_next_id($table,$field="id"){
		$sth = mysql_query("select MAX(`".$field."`) from `".$table."`",$this->dbh);
		$res=mysql_fetch_row($sth);
		return intval($res[0])+1;
	}

	function execute($sql){
		global $Core;
		$debug=$Core->debug;
		
		if ($debug)	$start_time = microtime(true);
		$sth = mysql_query($sql, $this->dbh);
		if ($debug) {
			$fin_time = microtime(true);
			$Core->log[]= array($sql,round(($fin_time-$start_time)*1000,2),mysql_error());
		}
	
		if (!$sth) return false;
	
		return $sth;
	}
	function value()
	{
		$result = array();
		$args = func_get_args();
		$sql = $args[0];

		$sth = $this->execute($sql, array_slice($args,1));
		if (!$sth)
			return "";
		
		$r = mysql_fetch_row($sth);
		return $r[0];
	}

	function select($sql)
	{
		$result = array();
		
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_array($sth,MYSQL_ASSOC))
			$result[] = $row;
		
		return $result;
	}
    
function option($sql,$id){
		$result = '';
		
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_array($sth))
			$result.= '<option value="'.$row[0].'" '.($row[0]==$id ? 'selected' : '').'>'.htmlspecialchars($row[1]).'</option>';
		
		return $result;
	}
    
    
    function maprow($rname,$sql){
        global $htm;
       $result = array();
		
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_array($sth,MYSQL_ASSOC))
			$htm->addrow($rname,$row);
		 
        
    }
    
    

	function getid($table, $field, $next=0)
	{
		$rs = $this->select("select max($field) as mid from $table");
		return ($rs[0]["mid"] ? ($next ? intval($rs[0]["mid"])+1 : $rs[0]["mid"]) : ($next ? 1 : 0));
	}
	
	function hash($sql, $mode=0)
	{
	  
		$sth = $this->execute($sql);
		if (!$sth) return array();
		$res = array();
		$key = mysql_field_name($sth,0);
		$skey = mysql_field_name($sth,1);
		if($mode!==5) $val = mysql_field_name($sth,1);
		while ($r=mysql_fetch_array($sth,MYSQL_ASSOC)) {
			if ($mode==0) $res[$r[$key]] = $r[$val];
			elseif ($mode==1) $res[$r[$val]] = $r[$key];
			elseif ($mode==2) $res[] = array($r[$key], $r[$val]);
			elseif ($mode==4) $res[$r[$key]] = $r;
			elseif ($mode==5) $res[$r[0]] = $r[0];
			elseif ($mode==6) $res[] =array($key=> $r[0]);
			elseif ($mode==7) $res[$r[$key]][$r[$skey]] =$r;
			elseif($mode==3){
				if(!isset($res[$r[$key]])){
					$res[$r[$key]][] = $r[$val];
					}else{
					if(!in_array($r[$val],$res[$r[$key]]))
								$res[$r[$key]][] = $r[$val];
					}
			}
		}
		return $res;
	}
	
	function get_hash($sql, $key, $val)
	{
		$result = array();
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_array($sth))
			$result[trim($row[$key])] = trim($row[$val]);
		
		return $result;
	}
	
	function get_vector($sql, $key)
	{
		$result = array();
		
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_array($sth))
			$result[trim($row[$key])] = $row;
		
			return $result;
	}

function vector($sql)
	{
		$res = array();
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_row($sth))
			$res[] = $row[0];
		
			return $res;
	}

	function count($sql, $count="0")
	{
		if ($sth = mysql_query($sql))
		{
			if ($count)
			{
				$row=mysql_fetch_array($sth);
				return (isset($row[0]) ? $row[0] : 0);
			}
			else
				return mysql_num_rows($sth);
		}
		else
			return 0;
	}

	function execute_all($sqls)
	{
		for ($i=0; $i<count($sqls); $i++)
			$this->execute($sqls[$i]);
	}

	function get_fields($table)
	{
		$sql="show fields from $table";
		$result = array();
		$sth = $this->execute($sql);
		if (!$sth)
			return $result;
		while ($row=mysql_fetch_array($sth))
			$result[]= $row["Field"];
		return $result;
	}

	function get_row($sth)
	{
		return mysql_fetch_array($sth);
	}

	function get_rec($table="", $where="")
	{
		$sql="select * from $table $where";
		$sth=$this->execute($sql);
		return ($sth ? mysql_fetch_array($sth,MYSQL_ASSOC) : "");
	}
    function assign($sql){
        global $htm;
		$sth=$this->execute($sql);
		if($sth) $htm->assign(mysql_fetch_array($sth,MYSQL_ASSOC));
    }
    
	function get_recs($sql)
	{
		$sth=$this->execute($sql);
		return ($sth ? mysql_fetch_array($sth,MYSQL_ASSOC) : "");
	}
	function is_val($table, $where)
	{
		global $db;
		$sql="select count(*) from $table $where";
		return $this->count($sql, true);
	}

	function get_val($table="", $field="id", $where="")
	{
		$sql="select $field from $table $where";
		$sth=$this->execute($sql);
		if ($sth)
		{
			$r=mysql_fetch_array($sth);
			return $r[$field];
		}
		else
			return "";
	}

	function sql_insert($table, $fields, $val){
		$fields=$fields ? (is_array($fields) ? $fields : explode(",",$fields)) : (is_array($val) ? array_keys($val):array());
		$val=(is_array($val) ? $val : ($val ? explode(",", $val) : array()));
		$array=array();
		for ($i=0; $i<count($fields); $i++) $array[]="'".mysql_real_escape_string($val[$fields[$i]])."'";
		return (count($fields) ? "insert into $table (`".implode("`, `", $fields)."`) values (".implode(", ", $array).")" : "");
	}


	function sql_update($table, $fields, $val, $where) {
	
		$fields=$fields ? (is_array($fields) ? $fields : explode(",",$fields)) : (is_array($val) ? array_keys($val):array());
		$val=(is_array($val) ? $val : ($val ? explode(",", $val) : array()));
		if (count($fields)) {
			$sql="update $table set ";
			for ($i=0; $i<count($fields); $i++) $sql.=($i ? ", " : "")."`".$fields[$i]."`='".mysql_real_escape_string($val[$fields[$i]])."'";
			return $sql.=" $where";
		}
		return "";
	}

	function update($table, $fields, $val, $where){
		$this->execute($this->sql_update($table, $fields, $val, $where));
	}
	
	function insert($table, $fields, $val){
		$this->execute($this->sql_insert($table, $fields, $val));
	}

	function sql_create($table, $fields) {
		return "create table $table ($fields)";
	}

	function sql_drop($table) {
		return "drop table if exists $table";
	}

	function get_row_from_table($name, $table, $id=0, $key="id", $val="title", $page=NULL) {
		$sql="select $key as id, $val as title from $table order by title";
		return $this->get_row_from_sql($name, $sql, $id, $page);
	}



	

	function from_sql_to_array($sql)
	{
		$result=array();
		$sth = $this->execute($sql);
		if (!$sth) return $result;
		while ($row=mysql_fetch_row($sth)) $result[] = $row[0];
		return $result;
	}


	
	function close() {
		mysql_close($this->dbh);
	}

function define ($mod,$lang,$ret=0){
	global $htm;
	$sth = $this->execute("select id,title from vocab where `mod`='$mod' and `lang`='$lang'");
	if($ret==0){
	while ($row=mysql_fetch_row($sth)) $htm->define[trim($row[0])]=trim($row[1]);	
	}else{
		$ret=array();
		while ($row=mysql_fetch_row($sth)) $ret[trim($row[0])]=trim($row[1]);
		return $ret;
	}	
	
	
	
}

function clear($arg){
	$arg=str_replace(array("%","'"),array("","\'"),stripslashes($arg));
	return mysql_real_escape_string($arg);
}


function check_table($table_name){
   return mysql_num_rows(mysql_query("SHOW TABLES LIKE '$table_name'")); 
  

}

function create_table($dump){
    $file=file_get_contents($dump);
    $sql=explode(";",$file);
    foreach($sql as $r){
        if(trim($r)!="") $this->execute($r);
      echo mysql_error();
    }
}
function autocheck($tab,$dump){
    if($this->check_table($tab)==0) $this->create_table($dump);
    
}
}

