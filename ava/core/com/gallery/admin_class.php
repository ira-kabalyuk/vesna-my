<?php
class Com_gallery_admin extends Tab_elements{
    var $pt;
    var $set;
    var $sys;
    var $pid;
    var $id;
    var $params;
    var $TB;
    var $te;
    var $bp;
    var $link_id=0;
    var $tag=0;


    
    
  function __construct(){
        $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
         $this->title="Фотогалерея";
      
       
    }

    function init($set){
         global $Core,$db,$htm, $Lang;
         $this->ln=$Lang;

        if(isset($set['table'])) $this->TB=$set['table'];
        if(isset($set['mp'])) $this->mp=$set['mp'];
        if(isset($set['title'])) $this->title=$set['title'];
        if(isset($set['modlink'])) $this->modlink=$set['modlink'];

        $this->mod=$set['mod'];
        $this->conf=Com_mod::get_config($this->mod);
        $mods=$db->get_recs("select class, parent_id from mods where mods='{$this->mod}'");
        $this->pid=$mods['parent_id'];
        $htm->assign('fa_class',$mods['class']);
    }


function Start(){
    global $htm, $Core;
    $this->bp=dirname(__FILE__)."/";


   
 $act=_get('act');
 $this->tag=_getn('parent');
 $this->id=_getn('el_id');

 $htm->external("EXT_ADD",$this->get_file("list.tpl"));
 if(AJAX)  $htm->src($this->get_file("list.tpl"));
 $htm->assign(array(
    'MOD_TITLE'=>$this->title,
    'MOD_LINK'=>$this->modlink,
    'CAT_SELECT'=>Input::option("#sql:select id,title from news_rubric where parent_id=".$this->pid)
    ));
    
    
    if($act=='add'){
        $this->edit_foto();
     
     }elseif($act=='edit'){
        $this->edit_foto();

     }elseif($act=='save_foto'){
        $this->save_foto();    
          $this->list_foto();
  
     }elseif($act=='list'){
        $this->list_foto(); 

    }elseif($act=='get_data'){
        $this->_get_data(); 

     }elseif($act=='save_sort'){
        $Core->ajax_get($this->save_sort()); 
       

        
    }elseif($act=='onoff' || $act=='onof'){
       $Core->ajax_get( $this->onoff());

    }elseif($act=='delete'){
         //$Core->ajax_get($this->del_foto());
         $this->del_foto();
           $this->list_foto(); 
        
    }elseif($act=='save_sorts'){
       $Core->ajax_get( $this->save_sorts());

    }else{
        $this->list_foto(); 
    }
    
    
}


    function edit_foto(){
        global $db, $htm;
        $file=$this->get_file("edit_foto.tpl");
        $htm->external("EXT_ADD",$file);
	    if(AJAX) $htm->src($file);  

      $r=array();

      //создадим объект интерфейса формы настроек
        $in=new Mods_setup_core();
        $in->load_set($this->get_file("fields.xml"));
        $in->rel=$this->pid;
        $r=array('cat'=>array());

        if($this->id!=0){
            $r=$db->get_rec($this->TB." where id=".$this->id);
            $r['cat']=get_tag($r['cat'],'cat');
            $r['_prew']=$this->get_prew($r);
            $meta=$db->hash("select concat('meta-',metakey), metavalue from foto_metadata where parent_id=".$this->id);
            $in->add_var($meta);
            //$in->set_attr('_prew','script',$this->get_prew($r,true));
        }
            $in->add_var($r);
            $htm->assign($r);
            $htm->assign(array(
            "FID"=>$this->id,
            "FORM_FIELDS"=>$in->get_form()
            ));
        

        $htm->assign(array(
        'ACTIONS'=>'save_foto',
        'MAXX'=>$this->set['max_x'],
        'MAXY'=>$this->set['max_y']        
         ));
        
        
        
    }
    
    function del_foto(){
        global $_root,$db;
        
         $r=$db->get_rec($this->TB." where id=".$this->id);
         $this->del_file($_root.$this->pt."/".trim($r['img']));
         $this->del_file($_root.$this->pt."/s_".trim($r['img']));
         $this->delete_element();
         
    }
    
    function del_file($file){
        if(is_file($file)) unlink($file);
    }
    
    
    function list_foto(){

        global $db, $htm,$Lang;

        
            if(AJAX){
                 $htm->src($this->get_file("list.tpl"));
            }else{
                $htm->external('EXT_ADD',$this->get_file("list.tpl"));
            }

    }
   
   function prepend(&$r){
            $r['video']=$r['img'];
            $r['color']=($r['is_hidden']==0 ? 'green' :'gray');
             $r['open']=($r['is_hidden']==1 ? 'hide' :'');
   			$r['full']=$this->get_prew($r,true);
            $r['prew']=$this->get_prew($r);
            $r['orign']=str_replace(array("c_","s_"), array("o_","o_"), $r['img']);
            //$r['img']='<a href="'.$r['full'].'" title="" class="gall"><img src="'.$r['prew'].'" width="100" /></a>';
          
            $r['sort']='<input type="text" class="w50" value="'.$r['sort'].'" name="sort['.$r['id'].']"/>'; 
   	
   }

   function _get_data(){
    $cat_id=_postn('cat_id');
    $w=array("parent_id=".$this->pid);

    if($cat_id!=0) $w[]=get_against('cat',"cat_",$cat_id);

    $sql="select * from fotogal where ".implode(" and ",$w)." order by sort";
    $this->get_data($sql);

   }

    function save_foto(){
        global $db;
        $dat=array();
        $its_new=false;
        
        
        
        if($this->id==0){
            $this->id=$this->get_id();
            $its_new=true;
            $dat['parent_id']=$this->pid;
            $dat['id']=$this->id;
            $dat['sort']=$this->get_sort();
            
        } 
       
       
        //$dat['parent_id']=_postn('parent');
        $cat=_postn_ar('cat');
        $dat['cat']=Com_params::params_string($cat,"cat");
        $dat['descr']=_posts('descr');
        $dat['img']=_posts('img');
        $dat['link']=_posts('link');
        if(_postn('youtube')==1)
            $dat['img']="http://img.youtube.com/vi/".$this->parse_youtube($dat['link'])."/0.jpg";
        
         

         if($its_new){
            $sql=$db->sql_insert($this->TB,"",$dat);
         }else{
            $sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
         }

         $db->execute($sql);
         $this->save_meta();
         //_emit('save_photo',$this->tag,$this->id,$dat);
        
        
    }
    
    function get_razdel($cat=''){
    	global $db;
        return Input::option_multy(get_tag($cat,'cat'),"select id,title from news_rubric where parent_id=".$this->pid);
      
    }
	
    function get_conf($pid){
        global $db;
        $conf=unserialize($db->value("select conf from foto_rubric where id=".$pid));
        if($conf['default']!="1") return;
        $this->set=$conf;

    }

    function parse_youtube($link){
   
         if(preg_match("/v=([^\&]+)/", $link,$m))
            return $m[1];
    }

    function save_sort(){
        global $db;
        $sort=_postn_ar('sort');
        foreach($sort as $id=>$s){
            $db->execute("update {$this->TB} set sort=$s where id=$id");
        }
    }


    
    function save_meta(){
        global $db;
        
        $db->execute("delete from foto_metadata where parent_id=".$this->id);
        foreach($_POST as $key=>$val){
            if(preg_match("/meta-([a-z|0-9|_|-]+)/", $key,$m)){
            $sql=$db->sql_insert("foto_metadata","",array('metakey'=>$m[1],'metavalue'=>$val,'parent_id'=>$this->id));
            $db->execute($sql);
         }
        }

    }
    function get_prew($r,$full=false){
        if(strpos($r['img'],"://")===false){
            return "/".$this->pt."/".($full ? '':'s_').$r['img'];
        }else{
            if($full) return $r['link'];
            return $r['img'];
        }
            
        
    }

    function get_file($file){
        if(is_file($this->mp.$file)) return $this->mp.$file;
            return  $this->bp.$file;
    }

    function save_sorts(){
        global $db;
        $sort=explode(",",_posts('sort'));
        $i=0;
        foreach($sort as $id){
            $db->execute("update ".$this->TB." set sort=$i where id='$id'");
            $i++;
        }
        return "ok";

    }
   
}
