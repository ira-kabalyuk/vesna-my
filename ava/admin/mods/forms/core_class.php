<?php
class Mods_forms_core{

	function route(){
		global $Core, $htm;
			$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		
		$modlink=ADMIN_CONSOLE."/?mod=forms&sub=form";
		$this->modlink=$modlink;

		$this->form_id=_getn('fid');
		$this->id=_getn('el_id');
		
		$htm->assign(array(
		'MOD_LINK'=>$modlink,
		'MOD'=>'forms',
		'FID'=>$this->form_id,
		"EID"=>$this->id
		));

		
		$type=_gets('type'); // тип формы или поля формы
		
		$act=_get('act');

		
		switch($act){

			case 'add':
				$this->add_form();
				$this->edit_form();
			break;

			case 'edit':
			if($type=='form'){
				$this->edit_form();
			}else{
				$this->edit_field();
			}
			break;
			
			case 'save':
			if($type=='form'){
				$this->save_form();
				$this->list_forms();
			}else{
				$this->save_field();
				$this->list_fields();
			}
			break;

			case 'list':
			if($type=='form'){
				$this->list_forms();
			}else{
				$this->list_fields();
			}
			break;

			case 'place':
				$this->sort_fields();
			break;



			case 'get_data':
				$this->get_data($type);
			break;

			case 'onoff':
				$Core->ajax_get($this->onoff(_getn('el_id')));
			break;

			case 'delete':
			if($type=='form'){
				$this->delete_form(_getn('el_id'));
				$this->list_forms();
			}else{
				$this->delete_field(_getn('el_id'));
				$this->list_fields();
			}
		
			break;

			case 'place':
				$this->place();
			break;


			default:
				$this->list_forms();
			}
	}

	/**
	 * Добавление новой формы
	 * */
	function add_form(){
		global $db;
		$tb="form_name";
		$this->id=$db->getid($tb,'id',1);
		$data=array('id'=>$id,'title'=>_posts('title'));
		$db->insert($tb,"",$data);
	}


	/**
	 * Редактирование формы
	 * */
	function edit_form(){
		global $db,$htm;
		$tb="form_name";
		$htm->src($this->mp."tpl/edit-form.tpl");
		$in=new Mods_setup_core;
		$in->load_set($this->mp."forma.xml");

		if($this->id!=0){
			$data=$db->get_rec($tb." where id=".$this->id);
			$in->add_var($data);
		}
		$htm->assign("FORM_FIELDS",$in->get_form());
	}

	function prepend(&$d){
		$d['title']='<a href="'.$this->modlink.'&act=edit&el_id='.$d['id'].'" class="ajax-nav">'.$d['title'].'</a>';
	}


	function get_data($type){
		if($type=="form"){
			$this->get_forms();
		}else{
			$this->get_form_fields();
		}
	}

	 function get_form_fields(){
        global $db,$Core;
         $fid=_getn('fid');
         $res=$db->select("select * from form_fields where parent_id=$fid order by sort ");
         $Core->json_get(array('ok'=>true,'data'=>$res));
    }

    function get_forms(){
		global $Core,$db;
		$res=$db->select("select * from form_name");
		$data=array();
		foreach($res as $r){
			$r['title']='<a class="ajax-nav" href="'.$this->modlink.'&type=fields&fid='.$r['id'].'&act=list">'.$r['title'].'</a>';
			$data[]=$r;
		}
		$Core->json_get(array('ok'=>true,'data'=>$data));
	}

	function list_forms(){
		global $htm;
		$htm->src($this->mp."tpl/list_form.tpl");
		$ul=new Com_ul;
		$ul->init($this->mp."list_forms.xml","forms");
		$ul->toolset('edit,onof,del');
		$ul->add_head();
		$htm->assign('FORM_LIST',$ul->get_ul());
	}	

	function list_fields(){
		global $htm,$db;
		$htm->assign("FORM_NAME",$db->value("select title from form_name where id="._getn('fid')));
		$htm->src($this->mp."tpl/list_fields.tpl");
		$ul=new Com_ul;
		$ul->init($this->mp."list_fields.xml","fields");
		$ul->toolset('edit,onof,del');
		$ul->add_head();
		$htm->assign('FIELDS_LIST',$ul->get_ul());
	}

	function edit_field(){
		global $db,$htm;
		$tb="form_fields";
		$htm->src($this->mp."tpl/edit-field.tpl");
		$in=new Mods_setup_core;
		$in->load_set($this->mp."field.xml");

		if($this->id!=0){
			$data=$db->get_rec($tb." where id=".$this->id);
			$in->add_var($data);
		}
		$htm->assign("FORM_FIELDS",$in->get_form());
		
	}	

	function save_field(){
		global $db;
		$tb="form_fields";
		$data=array();
		
		$data['title']=_posts('title');
		$data['error']=_posts('error');
		$data['type']=_posts('type');
		$data['name']=_posts('name');
		$data['is_hidden']=_postn('is_hidden');
		$data['check']=_postn('check');
		$data['class']=_postn('class');

		if($this->id==0){
			$data['id']=$db->getid($tb,'id',1);
			$data['parent_id']=$this->form_id;
			$data['sort']=$db->getid($tb." whre parent_id=".$this->form_id,'sort',1);
			$db->insert($tb,"",$data);
		}else{
			$db->update($tb,"",$data," where id=".$this->id);
		}
	}

	function save_form(){
		global $db;
		$tb="form_name";
		$data=array();
		
		$data['title']=_posts('title');
		$data['tpl_ok']=_posts('tpl_ok');
		$data['tpl']=_posts('tpl');
		$data['tpl_mail']=_posts('tpl_mail');
		$data['email']=_posts('email');
		$data['sms']=_posts('sms');
		$data['sms_a']=_posts('sms_a');
		$data['tel_admin']=_posts('tel_admin');
		$data['is_sms']=_postn('is_sms');
		$data['is_asms']=_postn('is_asms');
		$data['is_mail']=_postn('is_mail');
		
		if($this->id==0){
			$data['id']=$db->getid($tb,'id',1);
			$db->insert($tb,"",$data);
		}else{
			$db->update($tb,"",$data," where id=".$this->id);
		}
	}

	function sort_fields(){
		global $db,$Core;
		$ids=explode(",",_posts('ids'));
		$tb="form_fields";
		$i=1;
		foreach ($ids as $id) {
			$db->execute("update $tb set sort=$i where id=".intval($id));
		}
		$Core->ajax_get('ok');

	}

	function delete_form($id){
		global $db;
		if($id==0) return;

		$db->execute("delete from form_name where id=$id");
		//$fields=$db->vector("select id from form_fields where parent_id=".$id);
		//if(count($fields)>0)
		$ids=$db->vector("select id from form_msg where form_id=$id");
		if(is_array($ids)){
		foreach($ids as $idm){
			$db->execute("delete from form_info where parent_id=$idm");
		}
	}
		$db->execute("delete from form_msg where form_id=$id");
	}

	function onoff($id){
		global $db;
		$hid=$db->value("select is_hidden from form_fields where id=$id");
		$hid=($hid==1 ? 0:1);
		$db->execute("update form_fields set is_hidden=$hid where id=$id");
		return ($hid==0 ? 'on':'of');
	}

}