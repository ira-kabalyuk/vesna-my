<?
class Com_tree_core {
  var $sid;
  var $arm;
  var $file;
  var $t;
  var $conf;
  var $mp;
 
  function __construct($arg=false){
   if($arg){
    $this->t=arg['table'];
    $this->cong=$arg;
    $this->initialize();
    }
    $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
     }
     
		 
	function Start(){
		global $htm;
		$act=_get('act');
		$action=_post('action');
		$arm=_post('arm');
		$show=1;
		$top=_getn('sid');
		$this->sid=$top;
		$this->parse_conf();
		$this->initialize();
		
		
		if($act=='new'){
    $this->new_menu();
   
    $show=0;
}elseif($act=='list'){
     $htm->src($this->mp."kategory.tpl");
     $this->listm(1);

}elseif($act=='edit'){
     $this->new_menu();
}elseif($act=='seo'){
     $this->seo();     
}elseif ($act=='hide'){
   
   $this->hide_men(); 
}elseif ($act=='move'){
   $this->move_menu(_getn('dir'));
      $show=0; 
     
       $this->initialize();
       $htm->src($this->mp."kategory.tpl");
       $this->listm(1);  
}elseif ($act=='resort'){   
    $show=0; 
   $sort=0;
   //$this->resort_menu(0,"tmenu");  
    
}elseif ($act=='dels'){
  
    $this->del();
    $this->initialize();
}




if ($action=='save_new'){
    $this->add_men();
    
}elseif ($action=='update'){
   $this->add_men();

}elseif ($action=='save'){
$this->save($arm);

}

if($show==1) $this->listm();
	}	 
		   
   
   function new_id($parent){
    global $db;
    $this->sid=$db->getid($this->t,"id",1);
    return $this->new_sort($parent);
   }
   function new_sort($parent){
     global $db;
     $ret=intval($db->value("select max(sort) from ".$this->t." where parent_id=".$parent))+1;
    return $ret;
   }
 
 function initialize(){
     global $db;
    $this->arm=$db->hash("select id, parent_id as p, if(is_hidden=1, 'hidden', '') as tt, link as link, title as name from ".$this->t."  order by parent_id, sort",4); 
   }
   
function hide_men(){
    global $db;
    $on=intval($_GET['onof']);
    $db->execute("update ".$this->t." set is_hidden=$on where id=".$this->sid);
    echo $on;
    exit;
}
   
 function add_men(){
    global $db;
 $parent_id=_postn('parent_id');   

$new=false;
$dat=array();
$dat['title']=_posts('title');
if($this->sid==0){
    $new=true;
    $dat['sort']=$this->new_id($parent_id);
    $dat['id']=$this->sid;
    $dat['parent_id']=$parent_id;
   
    if($this->conf['translit'])
    		$dat['link']=imTranslite($dat['title'])."-r-".$dat['id'].".html";
 
    $sql[]=$db->sql_insert($this->t,"",$dat);
    
}else{
    if($this->chk_parents($this->sid,$parent_id)){
      $dat['parent_id']=$this->arm['parent_id'];  
    }else{
      $dat['parent_id']=$parent_id; 
    }
    $dat['link']=_posts('link');
    if($this->conf['translit'] && $dat['link']=='')
    		$dat['link']=imTranslite($dat['title'])."-r-".$this->sid.".html";
    		if($this->conf['upper']) $dat['link']=strtolower($dat['link']);
    if($this->arm[$this->sid]['p']!=$dat['parent_id']) $dat['sort']=$this->new_sort($dat['parent_id']);
    //foreach($Keys as $key){
//        $dat['title']=trim(stripslashes($_POST['link_name_'.$key]));    
//        $sql[]=$db->sql_update($this->t,"",$dat," where id=".$this->sid." and lang='$key'"); 
//    }
    
		$sql[]=$db->sql_update($this->t,"",$dat," where id=".$this->sid." and lang='ru'"); 
    
}
$meta=array('id'=>$this->sid,'meta_id'=>1,'descr'=>_posts('meta_1'));
$sql[]="delete from skat_categories_info where id=".$this->sid;
$sql[]=$db->sql_insert('skat_categories_info','',$meta);
//print_r($sql);
$db->execute_all($sql);
Mods_skat_seo::save_post($this->sid,1);
if($new==true) resort_menu(0,$this->t);
$this->initialize();
}
 
 

  
function list_templ($cur){
global $db;
$res=$db->select("select b.title, b.id from links as a left join static as b on a.parent_id=b.id where  b.lang='ru'");

$ret='<select name="link_page" size="1" class="t8v">';
$ret.='<option value="0"> - без привязки - </option>';

foreach($res as $r){
$sel="";
if ($cur==$r['id']) $sel="SELECTED";
$ret.='<option value="'.$r['id'].'" '.$sel.'>'.$r["title"].'</option>';
	}
$ret.='</select>';
return $ret;
} 

 
 function new_menu(){
    global $htm, $db;
     
    $htm->src($this->mp."edit.tpl");
    $my_act='save_new';
    $pid=0;
    $name="";
    $men=array();
    $in=new Mods_setup_core();
  $in->load_set($this->mp."fields.xml");
  

if ($this->sid!=0){
 $my_act='update';
 
  
 $dat=$db->get_rec($this->t." where id=".$this->sid);
 $seo=$db->get_rec("skat_seo where parent_id=".$this->sid." and type=1");
 $meta=$db->hash("select concat('meta_',meta_id) as id, descr from skat_categories_info where id=".$this->sid);
 $in->add_var($seo);
 $in->add_var($dat);
 $in->add_var($meta);
 
  }
  

  $htm->assign(array(
  "MYACT"=>$my_act,
  "SID"=>$this->sid,
  "PARENT"=>($pid==0 ? 'Главный узел' : $pid." ".$this->arm[$pid]['name']),
	"FORM_FIELDS"=>$in->get_form()
  ));
  

  
  $htm->assign("LIST",$this->list_templ(intval($men['page_id'])));
    
 }  
 

function del(){
    global $db, $sort;
    $db->execute("delete from ".$this->t." where id='".$this->sid."'");
    $sort=0;
    resort_menu(0,$this->t);
    }
 function seo(){
 	echo Mods_skat_seo::edit_seo($this->sid,1);
 	exit;
 }



    
function listm($flag=0){
global $htm;
	$htm->addscript('css',AIN.'/css/jquery.treeview.css');
	$htm->addscript('js',AIN.'/js/jquery.treeview.pack.js');
$tpl='<li><span title="\'.$ms["id"].\'">\'.$ms["name"].\'</span>';
$arg=array(
'ar'=>$this->arm,
'id'=>'mtree',
'count'=>count($this->arm),
'tpl'=>$tpl,
'title'=>array('title'=>'<b>Корневой раздел</b>','key'=>0),
);
if($flag==1){
  $arg['reload']=true;
  $Core->ajax_get(get_tree($arg));
}else{ 
	$ret=$htm->loa_tpl($this->mp."kategory.tpl");
	$htm->assvar("TREEMENU", get_tree($arg));
	$htm->_var($ret);
	return $ret;
	
}
}    


   
   
   function move_menu($dir){
    global $db;
  
    $new=$db->get_recs("select `parent_id`, `sort` from `".$this->t."` where id=".$this->sid);
     $sort=$new['sort']+($dir==1 ? -1 : 1);
    $old=intval($db->value("select `id` from `".$this->t."` where parent_id=".$new['parent_id']."  and sort=".$sort));
    
    if($old==0) return;

       $sql=array();
        $sql[]="update `".$this->t."` set `sort`=$sort where id=".$this->sid;
        $sql[]="update `".$this->t."` set `sort`=".$new['sort']." where id=".$old;
        
    $db->execute_all($sql);
 

}
function chk_parents($id,$pid){
    global $db;
    if($id==$pid){
       echo "Замкнутое вложение запрещено!!!";
        return true; 
    }
    $ar=array();
    
    while($pid!=0){
        $pid=intval($db->value("select parent_id from ".$this->t." where id=".$pid));
        if($pid!=0) $ar[]=$pid;
    }
    if(in_array($id,$ar)){
        echo "Рекурсивное вложение запрещено!!!";
        return true;
    }
    return false;
 }
 
 function parse_conf(){
    	if(!isset($_POST['conf'])) return;
    	$conf=$_POST['conf'];
    	foreach($conf as $key=>$c)
    	$this->conf[$key]=trim($c);
    	$this->t=$this->conf['table'];
    }

}
function resort_menu($id,$table){
    global $db,$sort,$mid;
    $res=$db->vector("select id from $table where parent_id=$id  order by sort");
    @reset($res);
    
    while(list($key,$r)=each($res)){
      $db->execute("update $table set sort=$sort where id=".$r);
      $sort++;  
    }
    @reset($res);
    while(list($key,$r)=each($res)){
        $parent=$db->vector("select id from $table where parent_id=$r  order by sort");
      if(count($parent)>0) resort_menu($r,$table); 
    }
    return;
}
 

?>