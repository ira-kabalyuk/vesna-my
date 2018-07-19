<?php
/**
 * Com_meta_core
 * Модуль редактирования справочников
 * @package SMART
 * @author Vladimir
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Mods_skat_dirs_items extends Tab_elements {
    var $TB='skat_dirs_list';
    var $id;
    var $pid;
    var $mp;
    var $maxrows=20;
    var $modlink;
    protected $div='#div_content';
    
    /**
     * Mods_skat_dirs_admin::__construct()
     * 
     * @param string $tb имя таблицы описаний метаданных
     * @param string $info имя таблицы значений метаданных
     * @param int $pid парент id (для определения связи с конкр. свойством объекта)
     * 
     */
    function __construct($div){
     $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
     $this->div=$div;
    }
    
    
    /**
     * Mods_skat_dirs_admin::Start()
     * 
     * @return
     */
    function Start(){
    	global $Core;
    	$this->id=_getn('el_id');
    	$this->pid=_getn('pid');
    	if($div!='') $this->div=$div;
    	
        $act=trim($_GET['act']);
        
        
        if($act=='edit'){
            $ret=$this->_edit();
        
        }elseif($act=='add'){
            $ret=$this->_edit();  
              
        }elseif($act=='save'){
            $this->_save();
            $ret=$this->_list();
            
        }elseif($act=='delete'){
            $this->_delete();
            $ret=$this->_list(1);
        }elseif($act=='upload'){
        	$this->upload();
        }else{
            //$this->resort(0,'title');
            $ret=$this->_list();
            
        }
        $Core->ajax_get($ret);
        
    }
    
    function prepend($ret){
    	global $htm,$db;
    
		$htm->assvar('DIV',$this->div);
		$htm->assvar('EID',$this->id);
		$htm->assvar('PID',$this->pid);
		$htm->assvar('MOD_LINK',$this->modlink);
		$htm->assvar('TITLE',$db->value("select title from skat_dirs where id=".$this->pid));
		
		$_SESSION['skat_items_div']=$this->div;
    	$htm->_var($ret);
    	return $ret;
    }
    
    function div($name){
    	$this->div=$name;
    	$_SESSION['skat_items_div']=$name;
    }
    
    
    /**
     * Mods_skat_dirs_admin::_list()
     * 
     * @param integer $flag
     * @return
     */
   
    function _list(){
        global $db,$htm;
        $ret=file_get_contents($this->mp."list_items.tpl");
        $pid=_getn('pid');
        $ul=new Com_ul;
         
    $res=$db->select("select id,title from {$this->TB} where parent_id=".$this->pid);
	$ul->init($this->mp."list.xml",'listmetadata');
	$ul->toolset('edit,onof,del');
	$ul->add_head();
	$ul->maprow($res);
	
	 $htm->assvar('METALIST',$ul->get_ul());
	 return $this->prepend($ret);
	 
        
    }
   
   
    
    /**
     * CMods_skat_dirs_admin::_edit()
     * 
     * @return
     */
    function _edit(){
        global $db,$htm;
         $ret=file_get_contents($this->mp."item_edit.tpl");
        // получим метаданные
        $in = new Mods_skat_set();
        $in->import_metaset('skat_dirs_meta','meta',' and parent_id='.$this->pid);
     	$res=array('title'=>'');  
	    if($this->id!=0){
       	$res=$db->get_rec($this->TB." where id=".$this->id);
        $in->add_var($db->hash(
		"select concat('meta[',meta_id,']') as id, descr from skat_dirs_info where id=".$this->id)
		);	
		$modlink=$this->modlink.'&pid='.$this->pid.'&el_id='.$this->id;
         $res['UPLOADS']=Com_upload_core::get_form(
		 array(
		 'div'=>'pupload',
		 'link'=>$modlink,
		 'img'=>'/katimg/dirs/'.$res['img'])
		 );
       	
        }
         $res['COM_META']=$in->get_set('meta');
		 $htm->assvar($res);
		
        return $this->prepend($ret);
	
    }
    
   /**
    * Mods_skat_dirs_admin::_delete()
    * 
    * @return
    */
   function _delete(){
   	global $db;
   	$this->delete_element();
   	$db->execute("delete from {$this->info} where id=".$this->id);
   	$db->execute("delete from skat_dirs_list where parent_id=".$this->id);
   	$v=$db->vector("select id from skat_dirs_meta where parent_id=".$this->id);
   	if(count($v)!=0) $db->execute("delete from skat_dirs_info where meta_id in(".implode(",",$v).")");
   } 
   
   
    /**
     * Com_meta_core::_save()
     * 
     * @return
     */
    function _save(){
        global $db;
       
        $new=false;
        $dat=array();
        
        if($this->id==0){
            $new=true;
            $this->id=$this->get_id();
  	       $dat['id']=$this->id;
    	   $dat['parent_id']=$this->pid;
       		}
        $dat['title']=_posts('title');
			

        if($new){
            $sql=$db->sql_insert($this->TB,"",$dat);
          
        }else{
             $sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
        }
        
        $db->execute($sql);
        $this->save_meta();
    }
    
function save_meta(){
	global $db;
	
	$meta=$_POST['meta'];
	if(!is_array($meta)) return;
	if(count($meta)==0) return;
	$data=array('id'=>$this->id);
	$db->execute("delete from skat_dirs_info where id=".$this->id);
	foreach($meta as $key=>$val){
		$key=intval($key);
		$data['descr']=trim(stripslashes($meta[$key]));
		$data['meta_id']=$key;
		$db->execute($db->sql_insert("skat_dirs_info","",$data));
	}
	
}

function upload(){
	global $Core;
	$data['img']='';
	$ret=Com_upload_core::upload($data,array(
	'path'=>'katimg/dirs',
	'name'=>"s_".$this->id,
	'fname'=>'img',
	'div'=>'pupload'
	));
	if($data['img']!='') 
	$Core->db->execute($Core->db->sql_update($this->TB,'',$data," where id=".$this->id));
	$Core->ajax_get($ret);
}
    
}

?>