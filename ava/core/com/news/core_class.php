<?php
class Com_news_core extends Tab_elements{
    var $id;
    var $pid;
    var $ln='ru';
    var $lang='ru';
    var $TB="news";
    var $pt="uploads/news";
    var $conf;
    var $where;
    var $imgconf;
    var $suffix=".html";
    var $rur;
    var $title;
    var $mp;
    var $mod="news";

    function __construct(){
         $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
         $this->title="НОВОСТИ";
         
   
    }

    function __get($name){
        global $db;
        if(!isset($this->$name)){

            if($name=='cats')
                $this->cats=$db->hash("select id, title from news_rubric where parent_id={$this->pid} order by title");
               
            

            if($name=='tags')
                $this->tags=$db->hash("select id, title from news_tag where parent_id={$this->pid} order by title");
 

            return $this->$name;

        }
    }
    
    function init($set){
        global $Core,$db,$htm, $Lang;
         $this->ln='ru';
        if($_COOKIE['lang']!="" && $_COOKIE['lang']!='ru')
                 $this->lang=$_COOKIE['lang'];

        if(isset($set['table'])) $this->TB=$set['table'];
        if(isset($set['img_path'])) $this->pt=$set['img_path'];
        if(isset($set['mp'])) $this->mp=$set['mp'];
        if(isset($set['title'])) $this->title=$set['title'];
        $this->mod=$set['mod'];
        $this->conf=Com_mod::get_config($this->mod);
        $mods=$db->get_rec("mods where mods='{$this->mod}'");
        $this->pid=$mods['parent_id'];

// подключим скрипты редактора
$htm->addscript("js","/inc/ajaxupload.js");
$htm->addscript("js","/skin/admin/js/pretty/pretty.js");
$htm->addscript("css","/skin/admin/js/pretty/pretty.css");
//$htm->addscript("css","/inc/wysibb/theme/default/wbbtheme.css");

$htm->external("EXT_ADD",$this->mp."admin.tpl");
if($Core->config['hide_setup']!=1) $htm->external("EXT_RAZD",$this->mp."submenu.tpl");
$htm->assign('PID',$this->pid);
$this->id=_getn('el_id');


$this->maxrows=intval($this->conf['limit']);
$this->modlink=ADMIN_CONSOLE."/?mod=".$this->mod."&lang=".$Lang."&page=".intval($_GET['page']);
$this->detect_child();

$htm->assign(array(
'MOD'=>$this->mod,
'LANG'=>$Lang,
'fa_class'=>$mods['class'],
'MOD_LINK'=>$this->modlink,
'MOD_TITLE'=>$this->title,
 "CRUMBS"=>'<a href="'.$modlink.'">'.$this->title.'</a>',
 
));

if(!AJAX){
    $htm->addscript("js",AIN."/js/pg.js");
    $htm->addscript("css",AIN."/css/pg.css");
//$htm->addscript("js",AIN."/mods/skat/skat.js");
//$htm->addscript('css',AIN.'/css/ui.css');
//$htm->addscript('js',AIN.'/js/jquery.treeview.pack.js');
}

}



    function Start(){
        global $Core;
         $this->base_mp=dirname(__FILE__).DIRECTORY_SEPARATOR;

        $this->where=" where lang='".$this->ln."' and id=".$this->id;
        $act=_get('act');

        $sub=_get('sub');
        if($sub!=''){
        	include_once $this->mp.$sub."/index.php";
        return;
		}
        
        if($act=='onoff'){
            $Core->ajax_get( $this->onoff());
        }elseif($act=='save_content'){    
            $Core->json_get($this->save_content());

          }elseif($act=='smt_init'){    
            $this->smt_init();
            return;
        }elseif($act=='save'){

            $this->save_news();
            $this->list_news();
        }elseif($act=='upload'){
           $Core->json_get($this->upload());
            
        }elseif($act=='edit'){
            $this->edit_news();
         }elseif($act=='add_new'){
            $this->add_new();
        }elseif($act=='save_new'){
            $this->save_new(); 
            $this->edit_news();      
        }elseif($act=='delete'){
            $this->del_news();
            $this->list_news();
        }elseif($act=='list'){
            $this->list_news(true);
          }elseif($act=='save_sort'){
            $this->save_sort();   
            $this->list_news(true);   
         }elseif($act=='get_pdf'){
            $this->get_pdf();  
         }elseif($act=='load_smt_content'){
            $Core->ajax_get($this->get_smt_content());

        }elseif ($act=='place'){

                $Core->ajax_get($this->_sort()); 
        }elseif ($act=='get_data'){
                $this->_get_data();               
        }else{
            $this->list_news();
        }
    }
    


    function save_news(){
        global $db,$htm, $Core;
        
    $new_rec=false;
    $clone=$false;
    $data=array();
    if($this->id==0){
    	$this->id=$db->getid($this->TB,"id",1);
        $new_rec=true;
        $data['id']=$this->id;
     }
   
    if($new_rec){
        $this->ln='ru';
    	$data['id']=$this->id;
    	$data['date_add']=time();
    	$data['parent_id']=$this->pid;
        $data['lang']=$this->ln;
        }

        $data['author']=_posts('author');
        $data['date_pub']=data_to_int(_post('date_pub')." "._posts('time'));

        if($this->lang=='ru'){
            $data['title']=_posts('title');
            $data['short']=_posts('short');
            if(isset($_POST['descr']))
            $data['descr']=_posts('descr');
        }
        
        $rur=_postn_ar('rubr_id');
        $data['rubr_id']=current($rur);
        $data['terms']=$this->tag_terms($rur,_postn_ar('tag_id'));
    
        
        $meta=array();
        $meta_key=$this->get_metakey();

        foreach($meta_key as $key){
                $meta[$key]=_posts("meta-".$key);
                $this->add_meta($key,$meta[$key]);
            }
        if($this->lang!='ru'){
            $this->add_meta('title',_posts('title'));
            $this->add_meta('short',_posts('short'));
             if(isset($_POST['descr']))
                $this->add_meta('descr',_posts('descr'));
        }

        $this->meta=$meta;
        
        /*
        $meta_key=array("umtag");
        foreach($meta_key as $m){
            $this->add_meta($m);
        }
        */
   $this->before_save($data);
   
    if($new_rec){
    	$sql=$db->sql_insert($this->TB,"",$data);
        //if($clone==true) $this->clone_news($data);
    }else{
    	$sql=$db->sql_update($this->TB,"",$data,$this->where);
    //	if($this->sys['cloni']==1) $this->clone_img($data['img']);
    }
    
    	$db->execute($sql);
      //  $parser=new Mods_news_umtag;
       // $parser->parse($this->id,_posts('umtag'));
 $this->save_seo();
    _emit("save_news_".$this->mod,$this);
        
    }//

    function get_metakey(){
        $keys=array();
        foreach ($_POST as $key => $value) {
            if(preg_match("/meta-([a-z|0-9|_]+)/i", $key,$m)){
                $keys[]=$m[1];
            }
        }
        return $keys;
    }

    function save_new(){
           global $db,$htm, $Core;
        
  
        $data=array();
        $this->id=$db->getid($this->TB,"id",1);
        $data['id']=$this->id;
        $data['date_add']=time();
        $data['date_pub']=time();
        $data['parent_id']=$this->pid;
        $data['lang']='ru';
        $data['title']=_posts('title');

  
        $sql=$db->sql_insert($this->TB,"",$data);
    
        $db->execute($sql);
        $this->save_seo();
        $this->where=" where lang='".$this->ln."' and id=".$this->id;
    }


    


    function add_meta($key,$val){
        global $db;
        $db->execute("delete from news_metadata where parent_id=".$this->id." and metakey='$key' and lang='{$this->lang}'");
        if(gettype($val)=='array') $val=implode(",",$val);
        if(trim($val)=="") return;
        $data=array('parent_id'=>$this->id);
        $data['metavalue']=$val;
        $data['metakey']=$key;
        $data['lang']=$this->lang;
        $db->execute($db->sql_insert("news_metadata","",$data));
    }

    function save_meta($meta){
         foreach($meta as $key=>$val)
                $this->add_meta($key,$val);
            
    }

    /**
     * Редактирование новости
     * @return [type] [description]
     */
    function edit_news(){
        global $htm, $db, $_root;

        //создадим объект интерфейса формы настроек
        $in=new Mods_setup_core();
        $in->load_set($this->load_tpl("fields.xml"));
        $in->rel=$this->pid;
        //print_r($in->in);
        

        if($this->id!=0){
            $in->parent_id=$this->id;
            $in->modlink=$this->modlink."&el_id=".$this->id;
            
        	$var=$db->get_rec($this->TB.$this->where);
            $this->check_lang_news($var);

            
            //$var['umtag']=$db->value("select umtag from news_umtag where id=".$this->id);

            $var=array_merge($var,$this->get_meta($this->id));
            $var['time']=date("H:i",$var['date_pub']);
        	$var['date_pub']=date("d.m.Y",$var['date_pub']);
            $var['rubr_id']=Mods_html_core::get_cats($var['terms']);
            $var['tag_id']=Mods_html_core::get_terms($var['terms']);
            $cat=Mods_html_core::get_cats($var['tags']);
            $var['smt_url']=$this->modlink."&act=smt_init&el_id=".$this->id;
        	$in->add_var($var);
        	
        }
      	// создадим обьект фотогалереи
        $conf=$this->conf['photo']; 
        if($conf['ingal']=="1"){ 
      	    
          //  print_r($conf);
            $conf['path']="uploads/newsphoto";
      	    $conf['prefix']="";
        	$conf['parent']="news";
            $conf['tab']="news_photo";
        	$conf['div']="img";
            $conf['target']="newsphoto";
      	    $conf['parent_id']=$this->id;
      	
            $img=new Com_photos_core();
            $img->init($conf);
            $img->pid=$this->id;
            $htm->assign("NEWSIMG",$img->list_foto($this->id));
        }

        if(AJAX) $htm->src($this->load_tpl("edit_news.tpl"));
        if(!AJAX) $htm->external("EXT_ADD",$this->load_tpl("edit_news.tpl"));
      	$htm->assign(array(
        "FORM_FIELDS"=>$in->get_form(),
        'EID'=>$this->id
        ));
       
        
    }
    
    function prepend(&$r){
        $r['cat']=$this->has_rubr($r['terms']);     
    	$r['tag']=$this->has_tag($r['terms']); 
		//int_to_date("d.m.y H:i",$r['date_add']);
        $r['date_pub']=date("d.m.y",$r['date_pub']);
    }

    // проверка языковой версии
    function check_lang_news(&$rec){
        global $db;
        if($this->lang=='ru')
            return;
        $meta=$db->hash("select metakey, metavalue from news_metadata where parent_id={$this->id} and lang='{$this->lang}'");
        if(count($meta)<2 || !is_array($meta)){
            $metaru=$db->hash("select metakey, metavalue from news_metadata where parent_id={$this->id} and lang='ru'");
            $this->save_meta($metaru);
        }else{
            $rec['title']=$meta['title'];
            $rec['descr']=$meta['descr'];
            $rec['short']=$meta['short'];
            $rec['seo_t']=$meta['seo_t'];
            $rec['seo_k']=$meta['seo_k'];
            $rec['seo_h']=$meta['seo_h'];
            $rec['seo_d']=$meta['seo_d'];
            
        }

    }

    function has_rubr($cat){
        $cats=Mods_html_core::get_cats($cat);
        if(count($cats)==0) return " ";
        $ret=array();
        foreach($cats as $c)
            $ret[]=$this->rur[$c];
        return implode(", ",$ret);
    }

    function has_tag($cat){
        $cats=Mods_html_core::get_terms($cat);
        if(count($cats)==0) return " ";
        $ret=array();
        foreach($cats as $c)
            $ret[]=$this->tags[$c];
        return implode(", ",$ret);
    }
    
    function list_news($flag=false){
 	global $db,$htm;
    
    $htm->external("EXT_NEWS",$this->load_tpl("news_list.tpl"));
    
    if(AJAX) $htm->src($this->load_tpl("news_list.tpl"));
    
    $htm->assign('ULNEWS',$this->get_ul(
		'newslist', //div
		$this->mp."list.xml"
	));
 }

function _get_data(){
    global $db;
    
    $order="sort";
    if($this->conf['datesort']=="1")
        $order="date_pub desc";

    $limit=_getn('length');
    $start=_getn('start');
    $l="";
    if($limit!=0) $l="limit $start,".$limit;
    $w=array("lang='".$this->ln."'","parent_id=".$this->pid);

    $tags=(_gets('tags'));
    
    if($tags!=""){
       $tags=explode(",",$tags);    
        $w[]=get_against('terms','cat_',$tags);
    }
    $where=implode(" and ",$w);

    $sql="SELECT id,title,short,date_pub,terms,guid,is_hidden from {$this->TB} where $where order by $order";
    $this->get_data($sql,$l);
}

 function add_new(){
    global $htm;
     $htm->src($this->load_tpl("add_new.tpl"));

 }

  function del_news(){
    global $db;
    $img=new Com_photos_core();
    $img->del_fotos($this->id);
    $db->execute("delete from ".$this->TB." where id=".$this->id);
    $db->execute("delete from news_metadata where parent_id=".$this->id);
    
  }

  
 function clone_news($data){
    global $db,$Mod_lang;
    if($this->sys['clone']!=1) return;
    $keys=array_keys($Mod_lang);
    foreach ($keys as $ln){
        if($data['lang']==$ln) continue;
        $data['lang']=$ln;
        $db->execute($db->sql_insert($this->TB,"",$data));
    }
 }
 
 function del_file($file){
 	if(is_file($file))
 	unlink($file);
 }

 function get_meta($id,$key=""){
    global $db;
    if($key=="")
    return $db->hash("select concat('meta-',metakey),metavalue from news_metadata where parent_id=$id and lang='{$this->lang}'");
    return $db->value("select metavalue from news_metadata where parent_id=$id and metakey='$key' and lang='{$this->lang}'");
 }
  function _get_meta($id,$key=""){
    global $db;
    if($key=="")
    return $db->hash("select concat('meta_',metakey),metavalue from news_metadata where parent_id=$id and lang='{$this->lang}'");
    return $db->value("select metavalue from news_metadata where parent_id=$id and metakey='$key' and lang='{$this->lang}'");
 }
 function _get_def_meta($file){
    $data=load_xml_data($file);
   // print_r($data);
    return $data;
    
 }

  function row_metadata(&$r){
    global $db;
   $m=$db->hash("select concat('meta_',metakey),metavalue from news_metadata where parent_id=".$r['id']." and lang='{$this->lang}'");
   $r=array_merge($r,$m);
 }

 function tag_terms($cats,$tags){
    $ret="";
        if(count($cats)>0){
        foreach($cats as $c)
            if($c!=0) $ret.="cat_".$c." ";
    }
    if(count($tags)==0) return $ret;
        foreach($tags as $c)
            if($c!=0) $ret.="tag_".$c." ";
        return $ret;
              
 }

 function get_pdf(){
    global $db;
    $post=$db->get_rec("select * from news where id=".$this->id);

 }

 function prepare_link($link){
    $link=(($link=='' || $link==".html") ?  strtolower(imTranslite(_posts('title'))):$link);
    if(strlen($link)>195) $link=substr($link, 0,195);
    $link=preg_replace("/[ ]+/","_",$link);
    $link=preg_replace("/[^a-z|A-Z|0-9|_|\-|.]/","",$link);
    if($this->suffix=='') return $link;
    $link=preg_replace("/".$this->suffix."/","",$link); 
    return $link.$this->suffix;
} 

 function save_seo(){
        global $db;
        $t=_posts("seo_t");
        $data=array(
            'seo_t'=>($t=="" ?  _posts("title"):$t),
            'seo_k'=>_posts('seo_k'),
            'seo_h'=>(_posts('seo_h')=="" ? _post('title'):_posts('seo_h')),
            'seo_d'=>_posts('seo_d'),
            'guid'=>$this->prepare_link(_posts('guid'))
            );
        if($this->lang=='ru'){
            $db->execute($db->sql_update("news","",$data," where id=".$this->id));
    
        }else{
            $this->save_meta($data);
        }
        
    }


    function save_sort(){
        global $db;
        $sort=_postn_ar('sort');
        foreach($sort as $id=>$s){
            $db->execute("update news set sort=$s where id=$id");
        }
    }


 function load_tpl($tpl){
    if(is_file($this->mp.$tpl)) 
            return $this->mp.$tpl;
        return $this->base_mp.$tpl;
 }

function get_filter(){
        $f=array();
        $cat=_postn('cat');
        if($cat!=0) $f[]=get_against('terms','cat_',$cat);

            if(count($f)==0) return "";
            return "and ".implode(" and ",$f);
    }

     function detect_child(){
        global $htm;
        $param=_gets('child_param');
        if($param!=''){
            $this->modlink.="&child_param=".$param;
            $params=explode(";",$param);
             foreach($params as $pt){
                $p=explode("-",$pt);
                if($p[0]=="c"){
                   $this->params['cat']=$p[1];
                   $htm->assign('RID',$p[1]); 
                }  
                if($p[0]=="t")  $this->params['tag']=$p[1];
            }
        }
    }

function before_save($data){

}

function save_content(){
    global $db;
    $dev=_posts('tpl');
    $descr=preg_replace("/data-json=\"[^\"]+\"/", "", $dev);
    $descr=preg_replace("/data-smt=\"[^\"]+\"/", "", $descr);
    $descr=preg_replace("/data-smtmeta=\"[^\"]+\"/", "", $descr);
    $db->execute("delete from smt_content where mod_id=".$this->pid." and lang='{$this->lang}' and parent_id=".$this->id);
    $data=array('parent_id'=>$this->id,'mod_id'=>$this->pid,'content'=>$dev);
    $data['lang']=$this->lang;
    $update=array('descr'=>$descr);
    $db->insert("smt_content","",$data);
    $db->update("news","",$update," where id='".$this->id."'");
    
    if(isset($_POST['meta'])){
        foreach($_POST['meta'] as $key=>$val){
            $this->add_meta($key,$val);
        }
    }


    return array('ok'=>true);

}

function smt_init(){
    global $htm;
    
    $htm->src(TEMPLATES."smt-index.html");
    $htm->assign('MOD_LINK',$this->modlink."&el_id=".$this->id);
    $htm->assign('URL_BLOCKS',$this->conf['smt_bloсks']);
    $htm->assign('el_id',$this->id);
    $htm->external('BODY',TEMPLATES.$this->conf['smt_tpl']);
    $htm->assign($this->_get_def_meta(TEMPLATES."def.xml"));
    $htm->assign($this->_get_meta($this->id));

}

function get_smt_content(){
    global $db;

    $r=$db->get_recs("select id, content from smt_content where parent_id=".$this->id." and lang='{$this->lang}' and mod_id=".$this->pid);
    if (isset($r['id']))
        return $r['content'];
    return file_get_contents(TEMPLATES.$this->set['content_tpl']);
}

    // codemirror init
    function cm_init(){
        $htm->addscript("js","/inc/cm/lib/codemirror.js");
    $htm->addscript("js","/inc/cm/mode/htmlembedded/htmlembedded.js");
    $htm->addscript("js","/inc/cm/mode/htmlmixed/htmlmixed.js");
    $htm->addscript("js","/inc/cm/mode/xml/xml.js");
    $htm->addscript("js","/inc/cm/mode/javascript/javascript.js");
    $htm->addscript("js","/inc/cm/lib/util/overlay.js");
    $htm->addscript("js","/inc/cm/lib/util/foldcode.js");
    $htm->addscript("js","/inc/cm/mode/css/css.js");
    $htm->addscript("css","/inc/cm/lib/codemirror.css");
    }
}
