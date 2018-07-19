<?
class Tab_element  {
    var $page;  // page 
    var $maxrows=5;
    
    public static $TB;
    public static $pid;
    public static $id;
    public static $link;
    public static $curl;
    static $plink='';
    
    
    function __construct(){
          global $htm, $mod;
       $this->page=intval($_GET['page']);
        self::$link=ADMIN_CONSOLE."/?mod=".$mod."&mid=".self::$pid;
        $htm->assign(array(
        "MODLINK"=>self::$link.'&page='.$this->page,
        "PGL"=>$this->page,
        "PID"=>self::$pid));
        
        
    }
    
   
         
     
    function hide_element($act){
        global $db;
        $db->execute("update ".self::$TB." set is_hidden='".($act=='on' ? 1 :0)."' where id=".self::$id);
    }
    
    function get_id(){
        global $db;
         if(self::$id==0) self::$id=$db->getid(self::$TB,"id",1);
        return self::$id;
    }
     function get_sort(){
        global $db;
        return $db->getid(self::$TB." where parent_id=".self::$pid,"sort",1);
    }   
    
   function move_element(){
   
        $dir=trim($_GET['dir']);
        $this->resort();
        if($dir=='up') $this->move_sort(self::$id,"");
        if($dir=='dwn') $this->move_sort(self::$id,"desc");
        
    
   }
   function delete_element(){
        global $db;
        $db->execute("delete from ".self::$TB." where id=".self::$id);
        $this->resort();
   }
   function del_elements(){
    global $db;
    $pages=explode(",",$_GET['pages']);
    foreach($pages as $p){
       $db->execute("delete from ".self::$TB." where id=".intval($p));
        $db->execute("delete from links where parent_id=".intval($p));
         
    }
    $this->resort();
   }
   
    private function move_sort($fid, $dir){
    global $db;
   
    $ar=$db->select("select `id`, `sort` from ".self::$TB." where `parent_id`=".self::$pid.self::$plink." order by `sort` ".$dir);
    
    $old=array();
    $sql=array();
        foreach ($ar as $r){
        if($fid==$r['id']){
            if(intval($old['id'])!=0){
                $sql[]="update ".self::$TB." set `sort`=".$r['sort']." where id=".$old['id'];
                $sql[]="update ".self::$TB." set `sort`=".$old['sort']." where id=".$r['id'];
                break;
            } 
        }
        $old=$r;
    }
    $db->execute_all($sql);
    //print_r($sql);
    
   }
   
   function resort($parent=-1){
    global $db;
    if($parent==-1) $parent=self::$pid;
    $ar=$db->vector("select id from ".self::$TB." where parent_id=$parent".self::$plink." order by sort ");
    $i=1;
    
    foreach ($ar as $r){
        $db->execute("update ".self::$TB." set `sort`=".$i." where id=".$r);
        $i++;
    }
   }
   
   function onoff(){
    global $db;
    $status=$db->value("select is_hidden from ".self::$TB." where id=".self::$id);
    $status=($status==0 ? 1 : 0);
    $db->execute("update ".self::$TB." set is_hidden=$status where id=".self::$id);
    return ($status==1 ? 'off':'on');
    
 }
   
  function paginator($where=''){
    global $db,$htm;
   
    $count=intval($db->value("select count(*) from ".self::$TB." ".$where));

    if($this->maxrows==0) return '';
    if($count<=$this->maxrows) return '';
     $cur=($this->page==0 ? 1 : $this->page);
    $p=1;
   $limit='';
    while($count>0){
        if($cur==$p){
                   $limit=" limit ".($p-1)*$this->maxrows.",".$this->maxrows; 
                }
        $count-=$this->maxrows;
        $p++;
    }
    $p--;
    $ret='
    <p class="paginator" id="PaginatorT"></p>
	<script type="text/javascript">
		PaginatorT = new Paginator(\'PaginatorT\','.$p.',10,'.$cur.', \''.self::$link.'&page=\',\''.self::$curl.'\');
	</script>';
    $htm->assign("PAGINATOR",$ret);
   //echo $limit;
    return $limit;
  }
  
  function get_path($link){
    global $db;
    $ret='';
    $pid=self::$pid;
   
    while($pid!=0){
    $rec=$db->get_recs("select title, id, parent_id from ".self::$TB." where lang='ru' and id=".$pid);
    $ret='&nbsp;-&gt; <a href="'.$link.$rec['id'].'">'.$rec['title'].'</a>'.$ret;
    $pid=intval($rec['parent_id']);
        
    }
    return '<a href="'.$link.'0">Корень сайта</a>'.$ret;
}
 function chk_parents($id,$pid){
    global $db;
    if($id==$pid){
       echo "Замкнутое вложение запрещено!!!";
        return true; 
    }
    $ar=array();
    
    while($pid!=0){
        $pid=intval($db->value("select parent_id from ".self::$TB." where id=".$pid));
        if($pid!=0) $ar[]=$pid;
    }
    if(in_array($id,$ar)){
        echo "Рекурсивное вложение запрещено!!!";
        return true;
    }
    return false;
 }
 
 function moves($page,$fold){
    global $db;
    if($this->chk_parents($page,$fold)) return;
    $db->execute("update static set parent_id=$fold where id=$page");
    $this->resort($fold);
    
} 
  
    
}

?>