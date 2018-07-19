<?
class Mods_foto_rubric_admin extends Tab_elements{
  var $id;
  var $pid=0;
  var $TB;
  var $mp;
  var $conf;

 
  function __construct($table){
   global $mid;
    $this->TB=$table;
    $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
    //$this->initialize();
    $this->pid=_getn('parent');
    
     }
		 
	function Start(){
		global $htm,$Core;
		$this->conf=Com_mod::load_conf('foto');
		$this->id=_getn('el_id');
		$act=_get('act');
		$action=_post('action');
		
if($act=='list'){
     $this->_list();

}elseif($act=='edit'){
     $this->_edit();
}elseif ($act=='onoff'){
   $Core->ajax_get($this->onoff());
}elseif ($act=='place'){
   $Core->ajax_get($this->place(_getn('sort')));
}elseif($act=='save'){
	$this->_save();
	$this->_list();
}elseif ($act=='delete'){
  
    $this->_del();
    $this->_list();
}else{
	$this->_list();
}
}
function _list(){
	global $htm,$db;
	$htm->external("EXT_ADD",$this->mp."list.tpl");
	if(AJAX)	$htm->src($this->mp."list.tpl");
	$ul=new Com_ul;
	$ul->init($this->mp."list.xml");
	$ul->add_head();
	$ul->toolset('onof,edit,del');
	$ul->maprow($db->select("select id, title,is_hidden, sort from {$this->TB} where parent_id={$this->pid} order by sort"));
	$htm->assign('NEWSLIST',$ul->get_ul());	
	
}

function _edit(){
	global $db,$htm;
	$htm->src($this->mp."edit.tpl");
	$in=new Mods_setup_core();
	$in->load_set($this->mp."fields.xml");
	if($this->id!=0){
		$set=$db->get_rec($this->TB." where id=".$this->id);
		$conf=unserialize($set['conf']);
		unset($set['conf']);
		
	$in->add_var($set);
	$in->add_var($conf);
	$in->add_var($db->get_rec("foto_seo where id=".$this->id));
	}
	$htm->assign(array(
	'FIELDS'=>$in->get_form(),
	'EID'=>$this->id
	));
	
	
}


function _del(){
	global $db;
	$this->delete_element();
	$news=$db->select("select id,img from fotogal where rubric_id=".$this->id);
	//foreach($news as $n) $this->del_news($n);
}



function _save(){
	global $db;
	$fn=array("title","link");
	$fs=array("seo_t","seo_k","seo_d");
	$fc=array("max_x","max_y","prop","prew_x","prew_y","prew_p","default");
	$data=array();
	$seo=array();
	$conf=array();
	$new=false;
	
	foreach($fn as $f)
	$data[$f]=_posts($f);
	
	foreach($fs as $f)
	$seo[$f]=_posts($f);

	foreach($fc as $f)
	$conf[$f]=_posts($f);

	$data['conf']=serialize($conf);	
	
	if($this->id==0){
		$this->id=$db->getid($this->TB,'id',1);
		$data['id']=$this->id;
		$data['parent_id']=$this->pid;
		$new=true;
	}
	$seo['id']=$this->id;
	
	if($new){
		$sql=$db->sql_insert($this->TB,"",$data);
	}else{
		$db->execute("delete from foto_seo where id=".$this->id);
		$sql=$db->sql_update($this->TB,"",$data," where id=".$this->id);
	}
	$db->execute($db->sql_insert("foto_seo","",$seo));
	$db->execute($sql);
	
}


}
 
