<?php
class Com_news_rubric_admin extends Tab_elements{
  var $id;
  var $pid=0;
  var $TB;
  var $mp;
  var $base_mp;
  var $conf;
 
  function __construct($table){
    $this->TB=$table;
    $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
    //$this->initialize();
    
     }
		 
	function Start($mod){
		global $htm,$Core;
		$this->base_mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		$this->conf=Com_mod::load_conf($mod);
		$this->id=_getn('el_id');
		$this->pid=$this->conf['parent_id'];
		$this->parent_id=$this->conf['parent_id'];

		$act=_get('act');
		$action=_post('action');
		
	if($act=='list'){
    	 $this->_list();
	}elseif($act=='edit'){
    	 $this->_edit();
	}elseif ($act=='onoff'){
   		$Core->ajax_get($this->onoff());
	}elseif ($act=='place'){
   		$Core->ajax_get($this->sort());
	}elseif($act=='save'){
		$this->_save();
		$this->_list();
	}elseif ($act=='delete'){
	    $this->_del();
    	$this->_list();
	}elseif($act=='upload'){
    	$Core->json_get($this->upload());
    }elseif($act=='get_data'){
    	$this->_get_data();
	}else{
		$this->_list();
	}
}

function _list(){
	
	global $htm,$db;
	$htm->external("EXT_ADD",$this->load_tpl("list.tpl"));
	if(AJAX)	$htm->src($this->load_tpl("list.tpl"));
	$ul=new Com_ul;
	$ul->init($this->load_tpl("list.xml"),"rubric");
	$ul->add_head();
	//$ul->toolset('onof,edit,del');
	//$ul->maprow($db->select("select id, title,is_hidden, sort from news_rubric where parent_id=".$this->parent_id." order by sort"));
	$htm->assign('NEWSLIST',$ul->get_ul());	
	

}

function _get_data(){
	$sql="select id, title,is_hidden, sort,link,prefix from {$this->TB} where parent_id={$this->pid} order by sort";
	$this->get_data($sql);
}

function _edit(){
	global $db,$htm;
	$htm->src($this->load_tpl("edit.tpl"));
	$in=new Mods_setup_core();
	$in->load_set($this->load_tpl("fields.xml"));
	$in->parent_id=$this->pid;
	$in->rel=$this->pid;
	if($this->id!=0){
		$in->add_var($db->get_rec("{$this->TB} where id=".$this->id));
		$in->add_var($db->get_rec("news_seo where id=".$this->id));
		$in->add_var($this->get_meta($this->id));
		$in->add_var($this->get_meta($this->id,'meta_'));
		$in->parent_id=$this->id;
    	$in->modlink=$this->modlink."&el_id=".$this->id;

	}
	$htm->assign(array(
	'FIELDS'=>$in->get_form(),
	'EID'=>$this->id
	));
	
	
}


function _del(){
	global $db;
	$this->delete_element();
	$news=$db->select("select id,img from news where rubric_id=".$this->id);
	//foreach($news as $n) $this->del_news($n);
}



function _save(){
	global $db;
	$in=new Mods_setup_core();
    $in->load_set($this->load_tpl("fields.xml"));
    $meta_key=$in->sets['meta']['fields'];
    $data_key=$in->sets['face']['fields'];
    $is_seo=false;
    if(isset($in->sets['seo'])){
    	$seo_key=$in->sets['seo']['fields'];
    	$is_seo=true;
    	$seo=array();
    }
        
	$data=array('parent_id'=>$this->pid);
	

	$new=false;
	
	foreach($data_key as $f)
		$data[$f]=_posts($f);
	
	$meta=$_POST['meta'];
if(is_array($meta_key)){
	foreach($meta_key as $key){
		$field=$in->in[$key];
		if($field['type']!='img')
			$this->add_meta($key,$meta[$key]);
	}
}


	
	if($this->id==0){
		$this->id=$db->getid($this->TB,'id',1);
		$data['id']=$this->id;
		$new=true;
	}

	if($is_seo){
	foreach($seo_key as $f)
		$seo[$f]=_posts($f);
		$seo['id']=$this->id;
}
	
	
	if($new){
		$sql=$db->sql_insert($this->TB,"",$data);
	}else{
		$db->execute("delete from news_seo where id=".$this->id);
		$sql=$db->sql_update($this->TB,"",$data," where id=".$this->id);
	}
	if($is_seo) $db->execute($db->sql_insert("news_seo","",$seo));
	$db->execute($sql);
	$this->save_meta();
}

function save_meta(){
	foreach($_POST as $key=>$val){
		if(preg_match("/meta_([a-z|0-9|_]+)/", $key,$m)){
			$this->add_meta($m[1],_posts($key));
		}
	}
}


    function add_meta($key,$val){
        global $db;
        $db->execute("delete from rubric_metadata where parent_id=".$this->id." and metakey='$key'");
        if(trim($val)=="") return;
        $data=array('parent_id'=>$this->id);
        $data['metavalue']=$val;
        $data['metakey']=$key;
        $db->execute($db->sql_insert("rubric_metadata","",$data));
    }
function upload(){
        //создадим объект интерфейса формы настроек
        $in=new Mods_setup_core();
        $key=_gets('div');
        $in->load_set($this->load_tpl("fields.xml"));
        $in->parent_id=$this->id;
        $meta_key=$in->sets['meta']['fields'];
        if(!in_array($key,$meta_key))
            return array('ok'=>false,'error'=>'field not found');
            $field=$in->in[$key];
            $meta=array();
            $arg=parse_jar($field['json']);
            $arg['div']=$key;
            $arg['fname']=$key;
            $arg['name']=$key."-".$this->id;
            $arg['prop']=($arg['prop']=="0" ? false:true);
            
            if(isset($arg['prew_x'])){
                // создаем превью
                 $prew=array(
            'kat'=>$arg['path'],
            'rx'=>$arg['prew_x'],
            'ry'=>$arg['prew_y'], 
            'pref'=>'s_',
            'prop'=>($arg['prew_p']=="1" ? true : false));
                 $arg['prew']=$prew;
            }

            $ok=Com_upload_core::ajax_upload($arg);
            if($ok['ok']){
            $ok['path']=$arg['path']; 
              $this->add_meta($key,$ok['fname']);
          }
            return $ok;

    }

    function sort(){
        global $db;
        $ids=explode(",",_posts('ids'));
        $i=0;
        foreach ($ids as $id) {
            if(intval($id)!=0){
                $i++;
                $db->execute("update news_rubric set sort=$i where id=$id");

            }
        }
        return "ok";
    }
	
function get_meta($id,$pref=""){
    global $db;
    return $db->hash("select concat('$pref',metakey),metavalue from rubric_metadata where parent_id=$id");
 }

 function load_tpl($tpl){
 	if(is_file($this->mp.$tpl)) 
 			return $this->mp.$tpl;
 		return $this->base_mp.$tpl;
 }
    

}
 
