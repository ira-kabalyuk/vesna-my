<?php
Trait Smt{


	public function smt_route(){
        global $Core;
		$act=_gets('act');
        
switch ($act) {

	case 'smt_init':
		$this->smt_init();
		return true;
		break;

	case 'save_content':
		  $Core->json_get($this->save_content());
		return true;
		break;

	case 'get_smt_content':
		$this->get_smt_content();
		return true;
		break;


	
	default:
		return false;
		break;
}

	}

function save_content(){
    global $db;
    $dev=_posts('tpl');
    $descr=preg_replace("/data-json=\"[^\"]+\"/", "", $dev);
    $descr=preg_replace("/data-smt=\"[^\"]+\"/", "", $descr);
    $descr=preg_replace("/data-smtmeta=\"[^\"]+\"/", "", $descr);
    $db->execute("delete from smt_content where mod_id=".$this->pid." and parent_id=".$this->id);
    $data=array('parent_id'=>$this->id,'mod_id'=>$this->pid,'content'=>$dev);
    $db->insert("smt_content","",$data);

    if(isset($this->TB)){
    	$update=array('descr'=>$descr);
 	   	$db->update($this->TB,"",$update," where id='".$this->id."'");
	}
    
    if(isset($_POST['meta'])){
    	//if(isset($this->add_meta)){
     	   foreach($_POST['meta'] as $key=>$val)
        	    $this->add_meta($key,$val);
        
    	//}
    }


    return array('ok'=>true);

}

function smt_init(){
    global $htm;
    $htm->src(TEMPLATES."smt-index.html");
    $htm->external('BODY',TEMPLATES.$this->conf['smt_tpl']);
    $htm->assign('MOD_LINK',$this->modlink."&el_id=".$this->id);
    $htm->assign('URL_BLOCKS',$this->conf['smt_bloсks']);
    $htm->assign('el_id',$this->id);
    
    $htm->assign($this->_get_meta($this->id));

}

function get_smt_content(){
    global $db;

    $r=$db->get_recs("select id, content from smt_content where parent_id=".$this->id." and mod_id=".$this->pid);
    if (isset($r['id']))
        return $r['content'];
    return file_get_contents(TEMPLATES.$this->set['content_tpl']);
}


}