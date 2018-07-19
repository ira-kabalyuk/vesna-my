<?php
class Mods_members_core{
	use Meta;
	var $permis;
	var $uid=array('id'=>0);
	var $id=0;
	var $mp;
	var $tb_metadata;


	function __construct(){
		global $Core;
		$this->uid=$Core->link->uid;
		$this->id=$this->uid['id'];
		$this->mp=dirname(__FILE__)."/";
		$this->tb_metadata="members_data";
	}

	function route(){
		global $Core;
		$link=explode("?",trim($Core->link->Link[2]));

		if($link[0]=="profile.html"){
			$Core->htm->src(TEMPLATES."profile.tpl");
			$this->get_profile();
			return true;

		}elseif($link[0]=="emploers.html"){
			$Core->htm->src(TEMPLATES."emploers.tpl");
			return true;
		
		}elseif($link[0]=="inbox.html"){
			$Core->htm->src(TEMPLATES."inbox.tpl");
			return true;
		
		}
		return false;
	}



	function update_user($id=0){
		global $db;
		$tb="members";
		$user=array();
		
		if($id==0){
			$new=array();
			$new['email']=_posts('email');
			$new['parent_id']=$this->id;
			$new['date_add']=time();
			$id=$db->getid($tb,'id',1);
			$new['id']=$id;	
			$db->insert($tb,"",$new);

			if(_posts('passw')=="")
				$_POST['passw']=Mods_auth_user::generate_code(6);
		}

		$pid=$db->value("select parent_id from members where id=".$id);
		if($pid!=$this->id) return "false";

		$user['name']=_posts('name');

		if(_posts('passw')!="") $user['passw']=_posts('passw');
		if(_posts('img')!="") $user['img']=_posts('img');

		$meta['phone']=_posts('phone');

		$db->update($tb,"",$user," where id=$id");
		$this->save_meta_prefix($id);
		$this->save_meta($id,'role',implode(",",_postn_ar('spec')));
		return "ok";



	}


	function get_right($key){
		if($key=='add_user'){
			return true;
		}

	}

	function get_staff(){
		global $db;
		$ret=array();
		$res=$db->select("select id, name,email,img,status,date_add from members where parent_id=".$this->id);
		if(count($res)==0) return $ret;
		$roles=$db->hash("select id, title from spec");
		foreach($res as $r){
			$meta=$this->get_meta($r['id']);
			$meta['progress']=intval($meta['progress']);
			$i=$r['id'];
			if(trim($meta['role'])==""){
				$role="роль не назначена";
			}else{
				$role="";
					$rls=explode(",",$meta['role']);
					foreach($rls as $rid)
						$role.=$roles[$rid].", ";
			}
			
			$d=array(
            "name"=> $r['name']."<br><small class='text-muted'><i>$role<i></small>",
            "est"=> "<td><div class='progress progress-xs' data-progressbar-value='{$meta['progress']}'><div class='progress-bar'></div></div></td>",
            "contacts"=> "<div class='project-members'><a class='modalbox' data-trigger='updatefrom' data-noclose='true' data-target='#emploers' href='/api/members.edit_staff/?id=$i'><img src='/uploads/members/".$r['img']."' class='offline w50'></a> </div> ",
            "status"=> "<span class='label label-success'>ACTIVE</span>",
            "target-actual"=> "<span style='margin-top:5px' class='sparkline display-inline' data-sparkline-type='compositebar' data-sparkline-height='18px' data-sparkline-barcolor='#aafaaf' data-sparkline-line-width='2.5' data-sparkline-line-val='[6,4,7,8,47,9,9,8,3,2,2,5,6,7,4,1,5,7,6]' data-sparkline-bar-val='[6,4,7,8,47,9,9,8,3,2,2,5,6,7,9,9,5,7,6]'></span>",
            "actual"=> "<span class='sparkline text-align-center' data-sparkline-type='line' data-sparkline-width='100%' data-sparkline-height='25px'>20,-35,70</span>",
            "tracker"=> "<span class='onoffswitch'><input type='checkbox' name='start_interval' class='onoffswitch-checkbox' id='st$i' checked='checked'><label class='onoffswitch-label' for='st$i'><span class='onoffswitch-inner' data-swchon-text='ON' data-swchoff-text='OFF'></span><span class='onoffswitch-switch'></span></label></span>",
            "starts"=> date("d.m.y",$r['date_add']),
            "ends"=> "<strong>02.01.2018</strong>"
        );
        $ret[]=$d;  
		}

		return $ret;


	}



	function edit_staff($id){
		global $htm,$db;
		$ret=file_get_contents($this->mp."tpl/edit_staff.tpl");
		$data=$db->get_recs("select id, name, email,img from members where id=$id and parent_id=".$this->id);
		$meta=$this->get_meta_hash($data['id']);
		$role=Input::option_multy("#sql:select id, title from spec order by title",explode(",",$meta['meta_role']));

		$arg=array("rx"=>200,"ry"=>200,"prop"=>false,'div'=>'img','img'=>$data['img'],'path'=>'uploads/members','prefix'=>'face_');
		$htm->assvar("IMAGE",Com_upload_core::get_ajax_form($arg));

		$htm->assvar("SELECT_SPEC",$role);

		$htm->assvar($data);
		$htm->assvar($meta);
		

		

		$htm->_var($ret);
		$htm->_final($ret);
		return $ret;
	}

	function add_staff(){
		global $htm,$db;
		$ret=file_get_contents($this->mp."tpl/edit_staff.tpl");
		
		$role=Input::option_multy("#sql:select id, title from spec order by title");
		
		
		$arg=array("rx"=>200,"ry"=>200,"prop"=>false,'div'=>'img','img'=>$data['img'],'path'=>'uploads/members','prefix'=>'face_');
		$htm->assvar("IMAGE",Com_upload_core::get_ajax_form($arg));
		$htm->assvar("SELECT_SPEC",$role);

		$htm->_var($ret);
		$htm->_final($ret);
		return $ret;
	}

	function get_profile(){
		global $db,$htm;

		// сотрудники
		$staff=$db->select("select id, name, img from members where parent_id=".$this->id." order by date_add");
		foreach ($staff as $s) {
			$htm->addrow("STAFF_LIST",$s);
		}

	}

}