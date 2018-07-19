<?php
class Com_photos_core extends Tab_elements{
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
         $this->TB="news_photo";
         $this->modlink="/smart/?com=photos&aj=1";
         $this->pt="uploads/newsphoto";
       
       
    }

    function init($set){
        $this->set=$set;
    }


function Start(){
    global $htm, $Core;
    $this->bp=dirname(__FILE__)."/";


   
 $act=_get('act');
 $this->tag=_getn('parent');
 $this->id=_getn('el_id');
 $this->parent_id=_getn('parent_id');
    
$this->get_set();
 
    
    
    if($act=='add'){
        $this->edit_foto();
     
     }elseif($act=='edit'){
        $this->edit_foto();

     }elseif($act=='save'){
        $this->save_foto();    
          $Core->ajax_get($this->list_foto(_getn('parent_id')));
  
     }elseif($act=='list'){
        $this->list_foto(0); 

    }elseif($act=='get_data'){
        $this->_get_data(); 

     }elseif($act=='save_sort'){
        $Core->ajax_get($this->save_sort()); 
        
    }elseif($act=='onoff'){
       $Core->ajax_get( $this->onoff());

    }elseif($act=='delete'){
        $this->del_foto();
        $this->set=json_decode(_posts('hiddens'),true);
        $Core->ajax_get($this->list_foto(_getn('parent_id')));
  

    }else{
        $this->list_foto(0); 
    }
    
    
}


    function edit_foto(){
        global $db, $htm;
        $file=$this->get_file("edit.tpl");
        
       $htm->src($file);  
       $descr="";
        $set=$_POST;
        if($this->id!=0){
            $rec=$db->get_recs("select img, descr from {$this->TB} where id=".$this->id);
            $set['img']=$rec['img'];
            $descr=$rec['descr'];

        }


        $htm->assign(array(
        'MOD_LINK'=>$this->modlink."&parent_id="._postn('parent_id'),
        'MAXX'=>$this->set['max_x'],
        'FID'=>$this->id,
        'DESCR'=>$descr,
        'MAXY'=>$this->set['max_y'],
        "UPLOAD"=>Com_upload_core::get_ajax_form($set),
        "HIDDENS"=>get_json($_POST)        
         ));
        
        
        
    }
    
    function del_foto(){
        global $_root,$db;
         
         $r=$db->get_rec($this->TB." where id=".$this->id);
         $this->del_file($_root.$this->pt."/".trim($r['img']));
         $this->del_file($_root.$this->pt."/s_".trim($r['img']));
         $this->del_file($_root.$this->pt."/o_".trim($r['img']));
         $db->execute("delete from {$this->TB} where id='".$this->id."'");
         
    }

function del_fotos($pid){
    global $db;
    $ids=$db->vector("select id from {$this->TB} where parent_id=$pid");
    foreach($ids as $id){
        $this->id=$id;
        $this->del_foto();
    }

}

    
    function del_file($file){
        if(is_file($file)) unlink($file);
    }
    
    
    function list_foto($pid=0){

        global $db, $htm,$Lang;
        $json=get_json($this->set);
        if($pid!=0){
            $tpl=file_get_contents($this->get_file("list.tpl"));
            $htm->assvar(array(
                "MOD_LINK"=>$this->modlink."&parent_id=".$pid,
                "set_json"=>$json
                ));
            $htm->_var($tpl);
            return $tpl;
        }

        $htm->assign(array(
                "MOD_LINK"=>$this->modlink."&parent_id=".$pid,
                "json"=>$json
                ));
           
    $htm->src($this->get_file("ext_list.tpl"));
    }
   
   function prepend(&$r){
            $r['video']=$r['img'];
            $r['color']=($r['is_hidden']==0 ? 'green' :'gray');
             $r['open']=($r['is_hidden']==1 ? 'hide' :'');
            $r['full']=$this->get_prew($r,true);
            $r['prew']=$this->get_prew($r);
            //$r['img']='<a href="'.$r['full'].'" title="" class="gall"><img src="'.$r['prew'].'" width="100" /></a>';
          
            $r['sort']='<input type="text" class="w50" value="'.$r['sort'].'" name="sort['.$r['id'].']"/>'; 
    
   }

   function _get_data(){
    $sql="select * from {$this->TB} where parent_id={$this->parent_id} order by sort";
    $this->get_data($sql);
   }

    function save_foto(){
        global $db;
        $dat=array();
        $its_new=false;
        
        $this->set=json_decode(_posts('hiddens'),true);
        
        if($this->id==0){
            $this->id=$db->getid($this->TB,'id',1);
            $its_new=true;
            $dat['parent_id']=_getn('parent_id');
            $dat['id']=$this->id;
            $dat['sort']=$this->get_sort();
            
        } 
         $dat['descr']=_posts('descr');
       
       
        //$dat['parent_id']=_postn('parent');
       
        $dat['img']=_posts('img');
        
         

         if($its_new){
            $sql=$db->sql_insert($this->TB,"",$dat);
         }else{
            $sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
         }

         $db->execute($sql);
         //$this->save_meta();
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

    function save_sort(){
        global $db;
        $sort=_postn_ar('sort');
        foreach($sort as $id=>$s){
            $db->execute("update {$this->TB} set sort=$s where id=$id");
        }
    }


    
    function save_meta(){
        global $db;
        if(!isset($_POST['meta'])) return;
        $meta=_post_ar('meta');
        $db->execute("delete from foto_metadata where parent_id=".$this->id);
        foreach($meta as $key=>$val){
            $sql=$db->sql_insert("foto_metadata","",array('metakey'=>$key,'metavalue'=>$val,'parent_id'=>$this->id));
            $db->execute($sql);
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

    function get_set(){
        global $db;
        if($this->parent_id==0) return;
        $pid=intval($db->value("select parent_id from news where id=".$this->parent_id));
        if($pid==0) return;
        $mod=$db->value("select mods from mods where parent_id=$pid");
         if($mod=="") return;

         $set=Com_mod::get_config($mod);
         $conf=$set['photo'];

            $conf['path']="uploads/newsphoto";
            $conf['prefix']="";
            $conf['parent']="news";
            $conf['tab']="news_photo";
            $conf['div']="img";
            $conf['target']="newsphoto";
            $conf['parent_id']=$this->parent_id;
            $this->set=$conf;
    }
   
}
