<?php
/**
 * Com_meta_core
 * Модуль редактирования набора метаданных
 * @package SMART
 * @author Vladimir
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Com_meta_core extends Tab_elements{
    var $TB;
    var $id;
    var $pid;
    var $ln='ru';
    var $pt="katimg/meta";
    var $mp;
    var $maxrows=20;
    protected $div='#div_content';
    
    /**
     * Com_meta_core::__construct()
     * 
     * @param string $tb имя таблицы описаний метаданных
     * @param string $info имя таблицы значений метаданных
     * @param int $pid парент id (для определения связи с конкр. свойством объекта)
     * 
     */
    function __construct($tb=null,$info=null,$pid=0){
     $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
   	 $this->pid=$pid ;
	   if(isset($tb)) $this->TB=$tb;
    	if(isset($info)) $this->info=$info;
    }
    
    
    /**
     * Com_meta_core::Start()
     * 
     * @return
     */
    function Start(){
    	global $Core;
    	$this->id=_getn('el_id');
    	$this->pid=_getn('parent_id');
    	$this->TB=_get('tab');
    	$this->info=_get('info');
    	$div=_session('com_meta_div');
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
            
        }elseif($act=='place'){
            $ret=$this->place(_getn('sort'));
        
        }elseif($act=='onoff'){
            $ret= $this->onoff();
              
        }else{
            //$this->resort(0,'title');
            $ret=$this->_list();
            
        }
        $Core->ajax_get($ret);
        
    }
    
    function prepend($ret){
    	global $htm;
    	$htm->assvar('MOD_LINK',ADMIN_CONSOLE.'/?com=meta&tab='.$this->TB.'&info='.$this->info);
		$htm->assvar('DIV',$this->div);
		$htm->assvar('PID',$this->pid);
		$htm->assvar('EID',$this->id);
		$_SESSION['com_meta_div']=$this->div;
    	$htm->_var($ret);
    	return $ret;
    }
    
    function div($name){
    	$this->div=$name;
    	$_SESSION['com_meta_div']=$name;
    }
    
    
    /**
     * Com_meta_core::_list()
     * 
     * @param integer $flag
     * @return
     */
    function _list($flag=0){
        global $db,$htm;
        $ret=file_get_contents($this->mp."list_meta.tpl");
        
        $ul=new Com_ul;
         
    $res=$db->select("select * from {$this->TB} where parent_id={$this->pid} order by sort ");
	$ul->init($this->mp."list.xml",'listmetadata');
	$ul->toolset('onof,edit,del');
	$ul->add_head();
	$ul->maprow($res);
	
	 $htm->assvar('METALIST',$ul->get_ul());
	 return $this->prepend($ret);
	 
        
    }
    
    /**
     * Com_meta_core::_edit()
     * 
     * @return
     */
    function _edit(){
        global $db,$htm;
         $ret=file_get_contents($this->mp."meta_edit.tpl");
        if($this->id!=0){
       	$res=$db->get_recs("select id,title,type,params  from ".$this->TB." where id=".$this->id);
        $htm->assvar('KATSELECT',$this->get_katsel($res['type'],unserialize(stripslashes($res['params']))));
        $htm->assvar($res);
        }else{
        	$htm->assvar('KATSELECT',$this->get_katsel(''));
        	$htm->assvar('title','');
        }
        
        return $this->prepend($ret);
	
    }
    
   /**
    * Com_meta_core::_delete()
    * 
    * @return
    */
   function _delete(){
   	global $db;
   	$this->delete_element();
   	$db->execute("delete from {$this->info} where meta_id=".$this->id);
   } 
   
   
    /**
     * Com_meta_core::_save()
     * 
     * @return
     */
    function _save(){
        global $db;
       
        $set=array('w','h','width','height','rows','cols','class','toolbar','dirs','path');
        $new=false;
        $dat=array();
        $dat1=array();
        if($this->id==0){
            $new=true;
  	       $dat['id']=$this->get_id();
    	    $dat['sort']=$this->get_sort();
    	    $dat['parent_id']=$this->pid;
       		}
        $params=array();
        
        foreach($set as $n)
        	if(isset($_POST[$n])) $params[$n]=_posts($n);
        
        $dat['params']=serialize($params);
        $dat['title']=_posts('title');
		$dat['type']=trim($_POST['type']);

        if($new){
            $sql=$db->sql_insert($this->TB,"",$dat);
          
        }else{
             $sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
        }
        
        $db->execute($sql);
    }
    
    
    
/**
 * meta_core::get_katsel()
 * 
 * @param mixed $type
 * @param mixed $params
 * @return
 */

function get_katsel($type,$params=array()){
	global $htm;
	$ret=file_get_contents($this->mp.'meta.tpl');
	$htm->assvar('TYPE',$type);
	$htm->assvar('JSON',get_json($params));
	$htm->assvar('DIRS',$this->get_opt("select id,title from skat_dirs"),intval($params['dirs']));
	$htm->_var($ret);
	return $ret;
	
}
   function get_opt($sql,$id=0){
   	global $db;
   	$res=$db->select($sql);
   	if(count($res)==0) return "";
   	$ret='';
   	foreach($res as $r)
   	$ret.='<option value="'.$r['id'].'" '.($id==$r['id'] ? 'selected':'').'>'.$r['title'].'</option>';
   	return $ret;
   } 
    
}

?>