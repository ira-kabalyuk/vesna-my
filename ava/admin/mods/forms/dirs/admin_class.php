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
class Mods_skat_dirs_admin {
    var $TB='skat_dirs';
    var $id;
    var $mp;
    var $maxrows=20;
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
        }else{
            //$this->resort(0,'title');
            $ret=$this->_list();
            
        }
        $Core->ajax_get($ret);
        
    }
    
    function prepend($ret){
    	global $htm;
    
		$htm->assvar('DIV',$this->div);
		$htm->assvar('EID',$this->id);
		$_SESSION['skat_dirs_div']=$this->div;
    	$htm->_var($ret);
    	return $ret;
    }
    
    function div($name){
    	$this->div=$name;
    	$_SESSION['skat_dirs_div']=$name;
    }
    
    
    /**
     * Mods_skat_dirs_admin::_list()
     * 
     * @param integer $flag
     * @return
     */
    function _list($flag=0){
        global $db,$htm;
        $ret=file_get_contents($this->mp."list_meta.tpl");
        
        $ul=new Com_ul;
         
    $res=$db->select("select * from {$this->TB} ");
	$ul->init($this->mp."list.xml",'listmetadata');
	$ul->toolset('edit,del');
	$ul->add_head();
	foreach($res as $r){
		$r['title']='<a class="pointer" rel="'.$r['id'].'">'.$r['title'].'</a>';
		$ul->add_row($r);
	}
	
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
         $ret=file_get_contents($this->mp."meta_edit.tpl");
        if($this->id!=0){
       	$res=$db->get_rec($this->TB." where id=".$this->id);
        $meta=new Com_meta_core('skat_dirs_meta','skat_dirs_info',$this->id);
        $meta->div('#metadirs');
        
        $htm->assvar('COM_META',$meta->_list());
        $htm->assvar($res);
		}else{
        	
        	$htm->assvar('title','');
        }
        
        return $this->prepend($ret);
	
    }
    
   /**
    * Mods_skat_dirs_admin::_delete()
    * 
    * @return
    */
   function _delete(){
   	global $db;
   	$db->execute("delete from skat_dirs where id=".$this->id);
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
  	       $dat['id']=$db->getid('skat_dirs','id',1);
    	   
       		}
        $dat['title']=_posts('title');
	

        if($new){
            $sql=$db->sql_insert($this->TB,"",$dat);
          
        }else{
             $sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
        }
        
        $db->execute($sql);
    }
    

    
}

?>