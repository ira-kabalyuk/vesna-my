<?php
class Tab_elements{
    
    var $maxrows=40;
    var $curl;
    var $sort='asc';
   	public $records;
     
    function hide_element($act){
        global $db;
        $db->execute("update ".$this->TB." set is_hidden='".($act=='on' ? 1:0)."' where id=".$this->id);
    }
    function hide_toggle(){
        global $db; 
        $db->execute("update ".$this->TB." set is_hidden=(is_hidden XOR 1) where id=".$this->id);
        return ($db->value("select is_hidden from ".$this->TB." where id=".$this->id)==1 ? 'off' :'on' );
    }
    
    function get_id(){
        global $db;
         if($this->id==0) $this->id=$db->getid($this->TB,"id",1);
        return $this->id;
    }
     function get_sort(){
        global $db;
        return $db->getid($this->TB." where parent_id=".$this->pid.($this->lng ? " and lang='".$this->lng."'" : ""),"sort",1);
    }   
    
   function move_element(){
   
        $dir=trim($_GET['dir']);
        $this->resort();
        if($dir=='up') $this->move_sort($this->id,"");
        if($dir=='dwn') $this->move_sort($this->id,"desc");
   }

 

   function del_elements(){
    global $db;
    $pages=explode(",",$_GET['pages']);
    foreach($pages as $p){
       $db->execute("delete from ".$this->TB." where id=".intval($p));
        $db->execute("delete from links where parent_id=".intval($p));
         
    }
    $this->resort();
   }
   
  function delete_element(){
        global $db;
        $db->execute("delete from ".$this->TB." where id=".$this->id);
        $this->resort();
   }
   
    private function move_sort($fid, $dir){
    global $db;
    $ar=$db->select("select `id`, `sort` from ".$this->TB." where `parent_id`=".$this->pid.($this->lng ? " and lang='".$this->lng."'" : "")." order by `sort` ".$dir);
    //echo "movesort";
    $old=array();
    $sql=array();
        foreach ($ar as $r){
        if($fid==$r['id']){
            if(intval($old['id'])!=0){
                $sql[]="update ".$this->TB." set `sort`=".$r['sort']." where id=".$old['id'].($this->lng ? " and lang='".$this->lng."'" : "");
                $sql[]="update ".$this->TB." set `sort`=".$old['sort']." where id=".$r['id'].($this->lng ? " and lang='".$this->lng."'" : "");
                break;
            } 
        }
        $old=$r;
    }
    $db->execute_all($sql);
    //print_r($sql);
    
   }
   
   // пересортировать эелементв
   function resort($parent=-1){
    global $db;
    if($parent==-1) $parent=$this->pid;
    $ar=$db->vector("select id from ".$this->TB." where parent_id=$parent ".($this->lng ? " and lang='".$this->lng."'":"")." order by sort ");
    if(count($ar)==0) return;
    $i=1;
    
    foreach ($ar as $r){
        $db->execute("update ".$this->TB." set `sort`=".$i." where id=".$r.($this->lng ? " and lang='".$this->lng."'":""));
        $i++;
    }
   }
   
   // перемещение элемента
    function place($place){
    global $db, $Core;
    $limit="";
    $place--;
    $sid=0;
    $old=_getn('sort_old')-1;
    $page=_getn('page');
    $dx=$this->maxrows*($page==0 ? 0:$page-1);
    $limit=" limit ".$dx.",99999";
    $place+=$dx;
   	$i=$dx;
		$res=$db->vector("select id from ".$this->TB." where parent_id=".$this->pid.($this->lng ? " and lang='".$this->lng."'":"")." order by sort ".$this->sort.$limit);
	 foreach ($res as $id){
	 	if($i==$place){
	 		$sid=$id;
	 		$id=$this->id;	
	 	}elseif($i==$old){
	 		$id=$sid;
	 	}
	 		$db->execute("update ".$this->TB." set sort=$i where id=".$id);
	  $i++;
	 }
		
   }

   function _sort(){
    global $db;
    $ids=explode(",",_posts('ids'));
    $i=1;
      foreach($ids as $id){
        $id=intval($id);
        $db->execute("update {$this->TB} set sort=$i where id=$id");
        $i++;
      }
      return "ok";
   }
   
   // включить / выключить елемент
   function onoff(){
    global $db;
    $status=$db->value("select is_hidden from ".$this->TB." where id=".$this->id.($this->lng ? " and lang='".$this->lng."'":""));
    $status=($status==0 ? 1 : 0);
    $db->execute("update ".$this->TB." set is_hidden=$status where id=".$this->id.($this->lng ? " and lang='".$this->lng."'":""));
    return ($status==1 ? 'off':'on');
    
 }
 	
 	// скрыть/показать элементы
  function hide_list($i){
 	global $db;
	 $items=_get('items');
 	$db->execute("update ".$this->TB." set is_hidden=$i where id in (".$items.")");
 	$this->_list();
 }
   
  function paginator($where=''){
    global $db,$htm;
    $page=_getn('page');
    $count=intval($db->value("select count(*) from ".$this->TB." ".$where));
    $this->records=$count;
    $link=preg_replace("/\&page\=[(0-9)]+.*/","",$_SERVER['REQUEST_URI']);
    if($this->maxrows==0) return '';
    if($count<=$this->maxrows) return '';
    $cur=($page==0 ? 1 : $page);
    $p=1;
    $limit='';
    while($count>0){
        if($cur==$p)
             $limit=" limit ".($p-1)*$this->maxrows.",".$this->maxrows; 
        $count-=$this->maxrows;
        $p++;
    }
    $p--;
    $ret='
    <p class="paginator" id="PaginatorT"></p>
	<script type="text/javascript">
		PaginatorT = new Paginator(\'PaginatorT\','.$p.',10,'.$cur.', \''.$link.'&page=\',\''.$this->curl.'\');
	</script>';
    $htm->assign("PAGINATOR",$ret);
   //echo $limit;
    return $limit;
  }
  
  // получение ссылки В виде пути по вложенным элементам
  function get_path($link){
    global $db;
    $ret='';
    $pid=$this->pid;
    while($pid!=0){
    $rec=$db->get_recs("select title, id, parent_id from ".$this->TB." where id=".$pid.($this->lng ? " and lang='".$this->lng."'":""));
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
        $pid=intval($db->value("select parent_id from ".$this->TB." where id=".$pid));
        if($pid!=0) $ar[]=$pid;
    }
    if(in_array($id,$ar)){
        echo "Рекурсивное вложение запрещено!!!";
        return true;
    }
    return false;
 }
 
 /**
  *   получение списка с предварительной обрабткой результата
  * @var $dest - обьект у которого есть метод addrow
  * @var $fields - перечень поолей через запятую
  * @var $where  - условие sql- запроса
  * @var $pre - пре-обработчик полученной строки из выборки (метод вызывающего обьекта)
  * */
 function get_list(&$dest,$fields="id,title",$where="",$pre=false){
 	global $db,$htm;
 	$limit=$this->paginator($where);
 	$res=$db->select("select ".$fields." from ".$this->TB.$where.$limit);
 	foreach($res as $r){
 		if($pre) $this->$pre($r);
 		$dest->add_row($r);
 	}
 }
 
 /**
  * авторендеринг списка элементов
  * @var $div = имя дива списка
  * @var $xml def:'$this->mp.list.xml'
  * @var $where def:""  условие
  * @var $fields def:"*" поля для выборки
  * @var $pre def:false имя пре-обработчика вызывающего обьекта
  * @var $toolset набор инструментов
  * */
 function get_ul($div,$xml='',$where='',$fields='*',$pre=false,$toolset='edit,onof,del'){
 	global $db;
 	if($xml=='') $xml=ENGINE_PATH.'default/ul_list.xml';
 	$ul=new Com_ul;
 	$ul->init($xml,$div);
  $ul->tool=true;
 	$ul->add_head();
 	//$ul->toolset($toolset);
 	
 	
 	return $ul->get_ul();
 }

 function get_data($sql){
  global $Core;
   
    $res=$Core->db->select($sql);
    $data=array();
    foreach($res as $r){
      $this->prepend($r);
      $data[]=$r;
    }
    $Core->json_get(array('ok'=>true,'data'=>$data));
 }

 function prepend(&$r){

 }
 
 function moves($page,$fold){
    global $db; 
    if($this->chk_parents($page,$fold)) return;
    $db->execute("update static set parent_id=$fold where id=$page");
    $this->resort($fold);
    
  } 
  
    
}

