<?php
class Com_form_core extends Tab_elements{
	var $TB;
    var $id;
    var $pid;
    var $ln='ru';
    var $pt="images/form";
    var $mp;
    var $maxrows=20;
    protected $div='#div_content';
    
    
    
    function __construct($tb=null,$pid=0){
     $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
   	 $this->pid=$pid ;
 	if(isset($tb)) $this->TB=$tb;
    
    }
	
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
        }elseif($act=='dirs'){
            $ret=$this->get_dirs(); 

        }elseif($act=='save'){
            $this->_save();
            $ret=$this->_list();
         }elseif($act=='get_data'){
            $this->_get_data();

         }elseif($act=='get_field'){
            $this->_get_field();

    
        }elseif($act=='delete'){
            $this->_delete();
            $ret=$this->_list(1);
            
        }elseif($act=='place'){
            $this->pid=$Core->db->value("select parent_id from ".$this->TB." where id=".$this->id);
            $ret=$this->place(_getn('sort'));
            $ret="ok";
        
        }elseif($act=='onoff'){
            $ret= $this->onoff();
              
        }else{
            //$this->resort(0,'title');
            $ret=$this->_list();
            
        }
        $Core->ajax_get($ret);
        
    }
    
     function prepend(&$ret){
    	global $htm;
    	$htm->assvar('MOD_LINK',ADMIN_CONSOLE.'/?com=form&tab='.$this->TB.'&info='.$this->info);
		$htm->assvar('DIV',$this->div);
		$htm->assvar('PID',$this->pid);
		$htm->assvar('EID',$this->id);
		$_SESSION['com_form_div']=$this->div;
    	$htm->_var($ret);
    	return $ret;
    }
    
    function div($name){
    	$this->div=$name;
    	$_SESSION['com_form_div']=$name;
    }
    
    function get_dirs(){
        global $Core;
        $Core->ajax_get(get_json($Core->db->hash("select id, title from skat_dirs")));
    }
    
    /**
     * Com_form_core::_list()
     * 
     * @param integer $flag
     * @return
     */
    function _list($flag=0){
        global $db,$htm;
        $ret=file_get_contents($this->mp."list_fields.tpl");
        
        $ul=new Com_ul;
         
   
	$ul->init($this->mp."list.xml",'listfrom');
	$ul->toolset('onof,edit,del');
	$ul->add_head();
	//$ul->maprow($res);
	
	 $htm->assvar('FORMLIST',$ul->get_ul());
	 return $this->prepend($ret);
	 
        
    }
    function _get_data(){
        global $db,$Core;
         $res=$db->select("select * from {$this->TB} where parent_id={$this->pid} order by sort ");
         $Core->json_get(array('ok'=>true,'data'=>$res));
    }

    function _get_field(){
        global $db,$Core;
        $fid=_getn('form');
         $res=$db->select("select * from form_fileds where parent_id=$fid order by sort ");
         $Core->json_get(array('ok'=>true,'data'=>$res));
    }
    
    /**
     * Com_form_core::_edit()
     * 
     * @return
     */
    function _edit(){
        global $db,$htm;
         $ret=file_get_contents($this->mp."form_edit.tpl");
         $in=new Lform;
         $in->load_set($this->mp."fields.xml");
        if($this->id!=0){
        	$var=$db->get_recs("select * from ".$this->TB." where id=".$this->id);
      	$in->add_var($var);
      	$this->pid=$var['parent_id'];
		  }
        $in->fill_form('INPUT');
        $htm->_row($ret,true);
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
      $set=load_xml_file($this->mp."fields.xml");
      
      $dat=array();
      $new=false;
	  if($this->id==0){
            $new=true;
  	       $dat['id']=$this->get_id();
   			$dat['sort']=$this->get_sort();
    	    $dat['parent_id']=$this->pid;
  		}

      foreach($set as $s){
      	$dat[$s['name']]=_posts($s['name']);
      }
      $dat['cont_id']=_postn('dirs');
    
        if($new){
            $sql=$db->sql_insert($this->TB,"",$dat);
          
        }else{
             $sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
        }
        
        $db->execute($sql);
    }
   

    
	
	
}