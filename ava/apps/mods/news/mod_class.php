<?
class Mods_news_mod{
    
  function _get(){
    
    $mod="news";
    $att=intval($_GET['att']);
    if($att!=0){
        $this->get_attach($att);
    }else{
		return $this->get_list()
		
      }  
            
        }
    
    
    public static function mpaginator($count,$max,$cpage,$link,$id=1){
    global $htm;
    //echo $cpage;
    if($count<=$max) return '';
    $htm->addscript("js",AIN."/inc/pg.js");
    $htm->addscript("css",AIN."/inc/css/pgs.css");
     $cur=($cpage==0 ? 1 : $cpage);
       $p=1;
   $limit='';
    while($count>0){
        if($cur==$p)$limit=" limit ".($p-1)*$max.",".$max; 
        $count-=$max;
        $p++;
    }
    $p--;
    $ret='<div id="paginator'.$id.'" class="paginator"></div>
    <script type="text/javascript">
		pag'.$id.' = new Paginator(\'paginator'.$id.'\', '.$p.', 20, '.$cur.', \''.$link.'\');
</script>
    ';
    $htm->assvar("PAGINATOR".$id,$ret);
   
    return $limit;
  }
   
   function get_attach($att){
        global $db, $Lang,$_root;
   $f=$db->get_rec("news_attached where id=$att and lang='$Lang'");     
if($f['is_hidden']==1){
$file=$_root."/img/empty.gif";
$attach="no_file.gif";
}else{
$file = CMS_MYLIB."uploads/".$att."_".$Lang;
}
header('Content-Type: application/octet-stream');
//header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="'.$f['fname'].'"');
//header('Content-Description: MY FILE');
header("Accept-Ranges: bytes");
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Length: '.filesize($file));
readfile($file);
    exit;
    
   } 
   
   function list_attached($id,$link){
     global $db, $Lang;
   $res=$db->select("select id, title,ext from news_attached where parent_id=$id and lang='$Lang' and is_hidden=0"); 
    $ret="";
    foreach ($res as $r){
        $ret.='<a href="'.$link.$r['id'].'" class="'.$r['ext'].'">'.$r['title'].'</a><br/>';
    }
    return $ret;
   }
   function get_list(){
   	global $db;$htm;
   	$ret=$htm->load_tpl("news_list.tpl");
   	$res=$db->select("select id, title,data_add,img, descr from news order by data_add desc");
   	foreach($res as $r){
   		$r['data_add']=date("d.m.Y",$r['data_add']);
   		$htm->addrow("NEWS_LIST");
   		
   	}
   	$htm->_row($ret);
   	return $ret;
   }
   
}
?>