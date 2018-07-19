<?php
class Mods_html_static{
	//use Smt;
	var $id=0;
	var $pid=0; // parent id
	var $uid=0; // smart user id
	var $suffix='.html';
    var $lang='ru';
    var $ln='ru';

	
	
	
function __construct(){
		global $Core;
		
		$this->id=_getn('el_id');
		$this->pid=_getn('pid');
		$this->uid=$Core->user['id'];
		$this->mp=dirname(__FILE__)."/";
		
		$this->conf=Com_mod::load_conf('html');
		$Core->htm->src($this->mp."tpl/list.tpl");
		$this->set=$this->conf;
	

		
	}
	
function Start(){
		global $db, $Core;
        if($_COOKIE['lang']!="" && $_COOKIE['lang']!='ru'){
            $this->lang=$_COOKIE['lang'];
            $this->ln=$this->lang;
        }
		$Core->htm->assign("MOD_LINK",$this->modlink);

	//	if($this->smt_route())
	//		return;

		$act=_get('act');
		if($act=='list' || $act==''){
			$this->static_list();
		}elseif($act=='get_data'){
			$this->get_data_table();
			return;	
		}elseif($act=='edit'){
			$this->edit_page();
	
		}elseif($act=='get_set'){
			$this->edit_page(true);	
		}elseif($act=='save'){
			$this->save_page();
			if(_posts('actions')=='save'){
				$this->edit_page();	
			}elseif(_posts('actions')=='close'){
				$this->static_list();
			}else{
				$Core->ajax_get('ok');
			}	
		}elseif($act=='del_pages'){
			$this->delete_pages();
			$this->static_list();	
		}elseif($act=='onoff'){
			$Core->ajax_get($this->onoff());
			return;
		}elseif($act=='get_history'){
			$this->get_history();
		}elseif($act=='mark'){
			$this->mark();
		}elseif($act=='clear'){
			$this->clear_history();	
			$this->get_history();
		}elseif($act=='restore'){
			$this->restore();
			$this->edit_page();
		}elseif($act=='move_page'){
			$this->move_page();
			$this->static_list();
		 }elseif($act=='upload'){
           $Core->json_get($this->upload());
            	
		}elseif($act=='place'){
           $Core->ajax_get($this->place());
            	
		}

	}
	
/**
 * Отображение списка страниц
 * */
function static_list(){
	global $db,$htm;
		
		$this->load_tpl("list.tpl");

		$htm->assign(array(
		'PID'=>$this->pid,
		 "CRUMBS"=>$this->get_path(),
		 'TAB_MODE'=>$this->conf['tab']
		));
		
	//	if(!AJAX){
			 //$htm->external("EXT_RAZD",$this->mp."tpl/kont_menu.tpl");
			// $htm->external("EXT_TREE",CMS_RUL_TPL."tree.tpl");
 			 $htm->assign("RAZDEL", $this->razd_menu());
			
	//	}
}

	function get_data_table(){
		global $Core;
		$res=$Core->db->select(
		"select id, title, folder, guid as link, is_hidden, parent_id, date_add from static
		where lang='".$this->lang."' 
		and parent_id=".$this->pid." order by sort");
		$data=array();
		foreach($res as $r){
            $r['date_add']=date("d.m.Y",$r['date_add']);
			
			$data[]=$r;
		}

		$Core->json_get(array('ok'=>true, 'data'=>$data));

	}



	function load_tpl($tpl){
		global $htm;
		if(AJAX){
			$htm->src($this->mp."tpl/".$tpl);
		}else{
			$htm->external("EXT_ADD",$this->mp."tpl/".$tpl);
		}
	}
	
	function prepend(&$r){

	}
	

	
function razd_menu(){
	global $db,$htm;
	$htm->addscript('css',AIN.'/css/jquery.treeview.css');
	$htm->addscript('js',AIN.'/js/jquery.treeview.pack.js');
	$folder=$db->select(
	"select id, parent_id as p, title as name from static 
	where lang='".$this->lang."' and folder=1 order by sort");
	//print_r($Folder);
	$tpl='<li><span title="\'.$ms["id"].\'">\'.$ms["name"].\'</span> &nbsp;&nbsp;<b>\'.$arg["count"][$ms["id"]].\'</b>';
	$arg=array(
	'ar'=>$folder,
	'id'=>'tree',
	'class'=>'treeview',
	'count'=>count($folder),
	'tpl'=>$tpl,
	'title'=>array('title'=>'Корень сайта','key'=>0),
	);
	return get_tree($arg);
}

/**
 * форма редактирования страницы
 * */
function edit_page($flag=false){
  global $db,$htm;
 	$in=new Mods_setup_core();
 	$in->suffix=$this->suffix;
 	if(is_file($this->mp."static_".$this->id.".xml")){
  	 	$in->load_set($this->mp."static_".$this->id.".xml");
   }else{
   		$in->load_set($this->mp."static.xml");
   }
 	
 	 $in->modlink=$this->modlink."&el_id=".$this->id;
 	//$htm->external('EXT_ADD',$this->mp.'tpl/page.tpl');
 	
 $htm->src($this->mp.'tpl/page.tpl');
 	//print_r($this->conf);
 	
 	// применим нааши настройки
 	if(isset($this->conf['w'])) $in->in['descr']['w']=$this->conf['w'];
 	if(isset($this->conf['h'])) $in->in['descr']['h']=$this->conf['h'];
 	if(isset($this->conf['toolbar'])) $in->in['descr']['toolbar']=$this->conf['toolbar'];

	 $params=array();
 	if($this->id!=0){
 		$page=$db->get_rec("static where id=".$this->id." and lang='".$this->lang."'");
        if(count($page)<2){
            $page=$db->get_rec("static where id=".$this->id." and lang='ru'");
            $page['date_add']=time();
            $page['lang']=$this->lang;
            $db->insert("static","",$page);
        }
                
 		$meta=$this->get_meta($this->id);
 		$page['link']=$page['guid'];
 		$params=unserialize($page['params']);
 		$meta['smt_url']=$this->modlink."&act=smt_init&el_id=".$this->id;
 		$in->add_var($meta);
 		$in->add_var($params,$in->sets['params']['attr']['prefix']);
 	}
 	$in->add_var($page);
 	
 	
 	// создадим обьект фотогалереи
      	$conf=array();
      	$conf['path']="/images/static";
      	$conf['parent']="static";
      	$conf['tab']="static_photo";
      	$conf['target']="sphoto";
      	$conf['max_x']=800;
      	$conf['max_y']=600;
      	$conf['prew_x']=155;
      	$conf['prew_y']=118;
      	$conf['prew_P']=0;
      	$conf['prop']=0;
      	
        //$img=new Com_photos_core($conf);
       // $img->pid=$this->id;

 	$htm->assign(array(
	 'FORM_CONTENT'=>$in->get_form(),
	 'PAGE_ID'=>$this->id,
	 'PID'=>$this->pid,
	  "CRUMBS"=>$this->get_path(),
	 'TAB_MODE'=>$this->conf['tab'],
	 //'PHOTO'=>$img->list_photo(true)
	 ));
	}
	
/** 
 * SAVE page
 * */	
function save_page(){
    global $db, $htm;
    $in=new Mods_setup_core();
    $fields=['title','descr','short','seo_d','seo_k','seo_t','guid'];
       $new=false;
   if($this->id==0){
   	$new=true;
   	$this->id=$db->getid('static','id',1);
   	}else{
        if($db->value("select count(*) from static where id=".$this->id." and lang='".$this->ln."'")==0) $new=true;
   }

   if(is_file($this->mp."static_".$this->id.".xml")){
  	 	$in->load_set($this->mp."static_".$this->id.".xml");
   }else{
   		$in->load_set($this->mp."static.xml");
   }
   	 
	$post=$_POST;
	$data=array();

   

	foreach($post as $key=>$val){

	 if(preg_match("/meta_([a-z|_]+)/", $key,$m)){
        $this->add_meta($m[1],_posts($key));
    }elseif(in_array($key, $fields)){
        $data[$key]=_posts($key);
    }
	
	// получаем доп параметры страницы
	

}

$prefix=$in->sets['params']['attr']['prefix'];
if(isset($post[$prefix])){
    $params=array();
            foreach($in->pref[$prefix] as $key)
                $params[$key]=trim(stripslashes($post[$prefix][$key]));
            
        $data['params']=serialize($params);
    }

$data['guid']=$this->prepare_link(_posts('link'));
	
	$sql=array();
	
    $data['date_add']=time();
	if($new){
		$data['id']=$this->id;
		$data['lang']=$this->ln;
		$sql[]=$db->sql_insert("static","",$data);
		
	}else{
		$sql[]=$db->sql_update("static","",$data," where id=".$this->id." and lang='".$this->ln."'");
		
	} 
	
	$db->execute_all($sql);
	$this->save_history();
   
   
   
 } 
 function delete_pages(){
 	$pages=explode(",",trim($_GET['pages']));
 	foreach($pages as $p){
 		$p=intval($p);
 		if($p!=0) $this->del_page($p);
 	}
 }
 function del_page($id){
 	global $db;
 	$sql=array();
 	$ids=implode(",",$this->get_parents($id));
    $sql[]="delete from static_metadata where parent_id in($ids)";
 	$sql[]="delete from static where id in($ids)";
 	$sql[]="delete from links where parent_id in($ids) ";
 	$sql[]="delete from history where id in ($ids) ";
 	$db->execute_all($sql);
 }
 
 function get_parents($id){
 	global $db;
 	static $ids;
 	if(!is_array($ids)) $ids=array();
 	$ids[]=$id;
 	$res=$db->vector("select id from static where parent_id=".$id);
 	if(count($res)==0) return $ids;
 	foreach($res as $r){
 		if(!in_array($r,$ids)){
			 $ids[]=$r;
 			 $this->get_parents($r);
 			 }
		 }
 		return $ids;
 }
function get_history(){
        global $db,$htm,$Core;
        $htm->src($this->mp.'tpl/ajax.tpl');
        $tpl=file_get_contents($this->mp."tpl/list_history.tpl");
        $sql="select a.hid, IF(a.is_backup=0,'autosave','<b>backup</b>') as type,
				 b.name as autor, DATE_FORMAT(a.data_m,'%d.%m.%y %H:%i:%s') as data 
				 from history as a left join users as b on a.user_id=b.id 
				 where a.lang='".$this->lang."' and  a.id=".$this->id." and a.user_id=".$this->uid;
       	$db->maprow("HIS_LIST",$sql); 
        $htm->_row($tpl);
        //print_r($htm->rows['HIS_LIST']);
        unset($htm->rows['HIS_LIST']);
       $Core->ajax_get($tpl);
        
    }
    
    function save_history(){
        global $db, $Lang;
        
        $data=$db->get_rec("static where lang='".$this->lang."' and  id=".$this->id);
        $data['data_m']=date('Y-m-d H:i:s');
        $data['user_id']=$this->uid;
        $fields=array("id","lang","title","descr","seo_t","seo_k","seo_d","params","data_m","user_id");
        $sql=$db->sql_insert("history",$fields,$data);
        $db->execute($sql);
    }
        
  function restore(){
    global $db, $htm;
    $hid=intval($_GET['back_id']);
    $res=$db->get_rec("history where hid=".$hid);
    $this->id=$res['id'];
    $fields=array("title","descr","seo_t","seo_k","seo_d","params");
    $sql=$db->sql_update("static",$fields,$res, "where lang='".$this->lang."' and  id=".$this->id);
    $db->execute($sql);
    
   
    
} 

function mark(){
    global $db,$htm;
    $htm->src($this->mp.'ajax.tpl');
    $hid=intval($_GET['back_id']);
    $db->execute("update history set is_backup=1 where hid=$hid");
    $this->get_history();
   
}     
function clear_history(){
    global $db;
    $hid=trim($_GET['hid']);
    $sql="delete from history where is_backup=0 and id=".$this->id." and user_id=".$this->uid;
    if($hid=='all') $sql="delete from history where is_backup=0 and user_id=".$this->uid;
    $db->execute($sql);
}
 function check_link($link){
		global $db;
		if($db->value("select count(*) from static where guid like '".$db->clear($link)."'")){
			return false;
		}else{
			return true;
		}
	}     
function prepare_link($link){
	$link=($link=='' ?  strtolower(imTranslite(_posts('title'))):$link);
	$link=preg_replace("/[ ]+/","_",$link);
	$link=preg_replace("/[^a-z|A-Z|0-9|_|\-|.]/","",$link);
	if($this->suffix=='') return $link;
	$link=preg_replace("/".$this->suffix."/","",$link); 
    return $link.$this->suffix;
} 	
function onoff(){
	global $db;
	$on=$db->value("select is_hidden from static where id=".$this->id." and lang='".$this->lang."'");
	$on=($on==0 ? 1 : 0);
	$db->execute("update static set is_hidden=$on  where id=".$this->id." and lang='".$this->lang."'");
	return ($on==1 ? 'off':'on');
}
function get_path(){
    global $db;
    $ret='';
    $pid=$this->pid;
    while($pid!=0){
    $rec=$db->get_recs("select title, id, parent_id, folder from static where lang='".$this->lang."' and id=".$pid);
    $ret='&nbsp;-&gt; <a  href="'.$this->modlink.($rec['folder']==1 ? '&pid=' :'&el_id=').$rec['id'].'">'.$rec['title'].'</a>'.$ret;
    $pid=intval($rec['parent_id']);
        
    }
    return '<a href="'.$this->modlink.'&pid=0" >Страницы</a>'.$ret;
}
function move_page(){
	global $db;
	$db->execute("update static set parent_id="._getn('fold_id')." where id="._getn('page_id'));
}



 function place(){
 	global $db;
 	$ids=explode(",",_posts('ids'));
 	$page=_postn('page');
 	$limit=_postn('limit');
 	$sort=$limit*$page;
 	$mid=_getn('el_id');
  	$pid=intval($db->value("select parent_id from static where id=$mid"));

 	foreach ($ids as $key) {
 		$id=intval($key);
 		if($id!=0) $db->execute("update static set sort=$sort where id=$id and parent_id=".$pid);
 		$sort++;
 	}
 	return "ok";
 }

     function add_meta($key,$val){
        global $db;
        $db->execute("delete from static_metadata where parent_id={$this->id} and metakey='$key' and lang='{$this->lang}'");
        if(trim($val)=="") return;
        $data=array('parent_id'=>$this->id);
        $data['metavalue']=$val;
        $data['metakey']=$key;
        $data['lang']=$this->lang;
        $db->execute($db->sql_insert("static_metadata","",$data));
    }
    
    function _get_meta($id){
    	return $this->get_meta($id);
    }
    
    function get_meta($id){
    global $db;
    return $db->hash("select concat('meta_',metakey),metavalue from static_metadata where lang='{$this->lang}' and parent_id=".$id);
 }

}
