<?
class Com_images_core extends Tab_elements{
    var $TB;
    var $mp;
    var $pid=0;
    var $id=0;
    var $set;
    var $pt="images";
    var $target='photos'; // div 
    var $modlink;
    var $conf;
    
    
    /**
     * Входной аргумет - ассоциативный
     * @param $arg ассоциативный массив:
	 * 		path 	каталог изображений
     * 		tab 	имя таблицы фотогалереи
     * 		parent 	имя таблицы родит. эелемнта (для модификации поля "img")
     * 		target	префикс дива для скрипта обслуживающего данный экземпляр компонета
     * @param $set - массив наборов параметров обработки изображения
     * @var pref - префикс изображения 
     * @var x 	ширина
     * @var y 	высота
     * @var prop	(1|0) соблюдать пропорции основн. изобр
   
   
     * 
     * */
    function __construct($arg,$set){
    	$this->mp=dirname(__FILE__)."/";
    	$this->conf=array(
		'max_x'=>600,
		'max_y'=>600,
		'prop'=>1,
		'path'=>'images');
    	if($arg){
    		$this->conf=array_merge($this->conf,$arg);
    		$this->TB=$arg['tab'];
    		$this->pt=$arg['path'];
    		$this->parent=$arg['parent'];
    		
    	} 
    	$this->modlink=ADMIN_CONSOLE.'/?com=images';
    }
    
    
    function Start(){
    	global $Core;
    	$htm=$Core->htm;
    	$db=$Core->db;
    	
    	$htm->src($this->mp."list.tpl");
    	$act=_get('act');
    	$this->parse_conf();

    	$this->pid=_getn('parent_id');
    	$this->id=_getn('el_id');
  	
    	if($act=='upload'){
    		$htm->src($this->mp."result.tpl");
    		$this->upload();
    		$htm->assign('TARGET_DIV',$this->target);
    		$htm->assign('RESULT',$this->list_photo());
  
    	}elseif($act=='mark'){
    		$this->mark_as_prime();
    		$Core->ajax_get('ok');
    	}elseif($act=='edit'){
    		$this->edit_photo();
    	}elseif($act=='place'){
    		$Core->ajax_get($this->place(_getn('sort')));

   		}elseif($act=='delete'){
   			
    			$this->delete_foto();
    		
    	}elseif($act=='save'){
    		$htm->src($this->mp."result.tpl");
    		$htm->assign('TARGET_DIV',$this->target);
    			$this->save_photo();	
					$htm->assign('RESULT',$this->list_photo());
			}elseif($act=='onoff'){
				$Core->ajax_get($this->onoff());
				
    	}else{
    		$this->list_photo();
    	}
    	
    }
    
    function parse_conf(){
    	if(!isset($_POST['conf'])) return;
    	$this->conf=unserialize($_POST['com_images_conf']);
    	$this->set=unserialize($_POST['com_images_set']);
    	$this->TB=$this->conf['tab'];
   		$this->pt=$this->conf['path'];
   		$this->parent=$this->conf['parent'];
   		$this->target=$this->conf['target'];
   	
    	
    }
	function get_post_conf(){
		return '<input type="hidden" name="com_images_conf" value="'.htmlspecialchars(serialize($this->conf)).'"/>'.
		'<input type="hidden" name="com_images_set" value="'.htmlspecialchars(serialize($this->set)).'"/>';
	} 
    
	function list_photo($wrap=false){
    	global $db,$htm;
   
    		$htm->assvar(array(
				'TARGET_DIV'=>$this->conf['target'],
   				'MOD_LINK'=>$this->modlink."&parent_id=".$this->pid,
				'EID'=>$this->pid,
				'JSONDATA'=>get_json(array('conf'=>$this->conf)),
				'HIDDEN'=>$this->get_post_conf()
				 ));
    	$htm->assign('EID',$this->pid);
    	$res=$db->select("select id,top,img,is_hidden, descr from ".$this->TB." where parent_id=".$this->pid." order by sort");
    	foreach($res as $r){
    		$r['checked']=($r['top']==1 ? 'checked':'');
    		$r['onof']=($r['is_hidden'] ? 'off':'on');
    		$r['img']=$this->pt."/s_".$r['img'];
				$htm->addrow("PHOTO_ROW",$r);
    		}
    		if($wrap){
    		$ret=file_get_contents($this->mp."wrap.tpl");
    		$htm->external('EXT_PHOTOS',$this->mp."list.tpl");
    		$htm->_external($ret);
    		}else{
    		$ret=file_get_contents($this->mp."list.tpl");
    		}
    		$htm->_if($ret);
    		$htm->_row($ret);
    		$htm->_var($ret);
    		unset($htm->rows['PHOTO_ROW']);
    		
    		return $ret;
    }
    
    function edit_photo(){
    	global $db,$htm;
    	$htm->src($this->mp."edit.tpl");
    	
		$htm->assign(array(
		'MOD_LINK'=>$this->modlink."&parent_id=".$this->pid,
		"EID"=>$this->id,
		'HIDDEN'=>$this->get_post_conf()
		));
		
    	$res=$db->get_rec($this->TB." where id=".$this->id);
    	$res['img']=$this->pt."/s_".$res['img'];
    	
    	$htm->assign($res);
    }
    function save_photo(){
    	global $db;
    	$data['descr']=_posts('descr');
    	$this->pid=$db->value("select parent_id from ".$this->TB." where id=".$this->id);
    	$db->execute($db->sql_update($this->TB,"",$data," where id=".$this->id));
    	if(isset($_FILES['photo'])) $this->upload();
    	
    }
    
    function upload($conf=false){
    	global $db;
    	$def=$this->conf;
		if($conf) $def=array_merge($this->conf,$conf);
		
    	$new=false;
    	$dat=array();
    		if($this->id==0){
    			$new=true;
    			$this->id=$db->getid($this->TB,'id',1);
    			$dat['id']=$this->id;
    			$dat['top']=0;
    		} 
				$dat['parent_id']=$this->pid;
    		
    		$up=new Upload();
        

        $name=$this->id."_".rand(1,100);
        foreach($this->set as $set){
        
        	// Основное изображение!!
        	if(trim($set['preg'])=='')){
        $res=$up->my_upload(array(
        'kat'   =>$def['path'],     //каталог загрузки изображения
        'fname' =>'photo',   //OST-имя файла
        'name'  => $name,    //новое имя файла (если пусто, имя не меняется)
        'rnd'   =>0,    //    если 1 то генерится новое имя файла
        'rx'    =>$set['x'],
        'ry'    =>$set['y'], //      ресайзинг по y
        'prop'=>($set['prop']==1 ? true : false),
        ));
        
        if($res['ok']==1){
        		// если префикса нет, это основное изобр, запишем его
            $dat['img']=$res['fname'];
            if($db->value("select count(*) from ".$this->TB." where parent_id=".$this->pid)==0) 
									$this->mark_as_prime($dat['img']);
           if($new){
           	$sql=$db->sql_insert($this->TB,'',$dat);
           }else{
           	$this->delete_old_files();
           	$sql=$db->sql_update($this->TB,"",$dat," where id=".$this->id);
           }
           $db->execute($sql);
    			}else{
    				// если попытка не удалась, выходим
					return;
					}
    			}else{
    				// создание доп. изображений из основного
    				$up->resizeimg(
					$_SERVER['DOCUMENT_ROOT']."/".$def['path']."/".$dat['img'],
					$_SERVER['DOCUMENT_ROOT']."/".$def['path']."/".$def['pref'].$dat['img'],
					$set['x'],
					$set['y'],
					($set['prop']==1 ? true : false)
					);
    			}
}
function delete_foto(){
	global $db, $Core;

	$this->delete_old_files();
	$db->execute("delete from ".$this->TB." where id=".$this->id);
	$Core->ajax_get($this->list_photo());
	
}
	function delete_old_files(){
		global $db,$_root;
		$img=$db->value("select img from ".$this->TB." where id=".$this->id);
		$fname=$_root.$this->pt."/".$name;
		if(is_file($fname)) unlink($fname);
		$fname=$_root.$this->pt."/s_".$name;
		if(is_file($fname)) unlink($fname);
		
	}
	function mark_as_prime($name=''){
    	global $db;
    	if($name==''){
    		$rec=$db->get_recs("select parent_id, img from ".$this->TB." where id=".$this->id);
    		$this->pid=$rec['parent_id'];
    		$name=$rec['img'];
    	} 
    	$db->execute("update ".$this->parent." set img='".$name."' where id=".$this->pid);
    	$db->execute("update ".$this->TB." set top=0 where parent_id=".$this->pid);
    	$db->execute("update ".$this->TB." set top=1 where id=".$this->id);
    }
  
}
?>