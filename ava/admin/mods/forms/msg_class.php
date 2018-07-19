<?php
class Mods_forms_msg{
	var $pid;
	var $conf=array();
	var $tb='form_info';
	
	function route(){
		global $Core;
		$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		$modlink=ADMIN_CONSOLE."/?mod=forms&sub=msg";
		$this->modlink=$modlink;
		$this->pid=_getn('parent_id');
		$this->conf['link']=$modlink;
		$this->conf['limit']='20';
		$this->status=array("новый", "обработан", "не  отвечает", "ошибка");
		$Core->htm->assign(array(
			"MOD_TITLE"=>"Сообщения",
			"MOD_LINK"=>$modlink
			));
		$act=_gets('act');

		switch($act){
			case 'view':
			$this->_view();
			break;
			
			case 'onoff':
			$Core->ajax_get(Com_mod::onoff('form_msg',_getn('el_id')));
			break;

			case 'set_status':
			$Core->ajax_get($this->save_status(_getn('id'),_getn('status')));
			break;

			case 'ban':
			$Core->ajax_get($this->ban_ip());
			break;

			case 'get_data':
			$this->get_data();
			break;
			
			case 'delete':
			$this->_del();
			$this->_list();
			break;
			
			default:
			$this->_list();
		}
		
	}
	function prepend(&$r){
		$r['data_add']=date('d.m.y H:i',$r['data_add']);
		$r['status']='<span class="status status-'.$r['status'].'" data-id="'.$r['id'].'">'.Input::select('status',$r['status'],$this->status).'</span>';
		$r['title']='<span class="shown" data-id="'.$r['id'].'">'.$r['title'].' <i class="fa fa-lg fa-arrow-circle-o-down fr"></i></span>';
		
	}
	
	function _list(){
		global $db,$htm;
		$htm->external('EXT_ADD',$this->mp.'tpl/msg_list.tpl');
		$st=false;
		if(AJAX) $htm->src($this->mp.'tpl/msg_list.tpl');
	
		//$res=$db->select("select * from form_msg $w order by data_add desc ".$limit);
		$ul=new Com_ul;
		$ul->init($this->mp."list_msg.xml","messages");
		$ul->toolset('onof');
		$ul->add_head();
		
		//$ul->map_prepend($res,$this);

		$htm->assign('MSG_LIST',$ul->get_ul());
		
		//$htm->assign('PAGINATOR',$pg['paginator']);
		
		$htm->assign('FILTERSTATUS',Input::selectradio('status', $this->status,''));
		$htm->assign('FILTERFORMS',Input::selectradio('form', $db->hash('select id, title from form_name'),''));
		/*foreach($this->status as $id=>$title)
				$htm->addrow("FILTER",array('id'=>$id,'title'=>$title));
				*/		
	}

	
	function _view(){
		global $db,$htm;
		$id=_getn('id');
		$fid=$db->get_rec("form_msg where id=$id");
		$fid['data_add']=date('d.m.Y H:i:s',$fid['data_add']);
		$fid['is_ban']=intval($db->value("select count(*) from ipban where ip='{$fid['ip']}'"));
		$htm->src($this->mp."tpl/msg.tpl");
		$title=$db->select ("select id, title,name  from form_fields where parent_id={$fid['form_id']} order by sort");
		$info=$db->hash("select meta_id,descr from {$this->tb} where parent_id=$id");
		
		foreach($title as $r){
			$r['descr']=$info[$r['id']];
			if($r['name']=='photo')
				$r['descr']='<a href="/uploads/users/o_'.$info[$r['id']].'" target="_blank"><img src="/uploads/users/'.$info[$r['id']].'"></a>';
			$htm->addrow("INFO",$r);
		}
		$htm->assign($fid);
		
		
	}

	function _del(){
		global $db;
		$id=_getn('el_id');
		$db->execute("delete from form_msg where id=$id");
		$db->execute("delete from form_info where parent_id=$id");
	}

		function save_status($id,$status){
		global $db;
	
		$db->execute("update form_msg set status=$status where id=$id");
		return $status;
	}

	function ban_ip(){
		global $db;
		$ip=$db->clear(_posts('ip'));

		if($ip=='127.0.0.1') return " нельзя забанить localhost! (127.0.0.1) ";
		
		$ban=_postn('ban');
		if($ban==0){
			$sql="delete from ipban where ip='$ip'";
			$ret="<div class=\"alert alert-success fade in\"> $ip удален из блеклиста </div>";
		}else{
			$sql="insert into ipban (ip) values ('$ip')";
			$ret="<div class=\"alert alert-danger fade in\"> $ip добавлен в  блеклист </div>";
		}
		$db->execute($sql);
		return $ret;

	}

	function get_data(){
		global $Core,$db;
		$limit=_getn('length');
		$start=_getn('start');
		$fid=_getn('fid');
		$status=_getn('status');
		$l="";
		
		if($limit!=0) $l="limit $start,".$limit;
		$search=$_GET['search'];
		
		$where=array();
		$w="";
		if($this->pid !=0 ) $where[]="parent_id=".$this->pid;

		if($status!=0){
				$where[]="status=".$status;
				$st=true;
				
		}

		if($fid!=0){
				$where[]="form_id=".$fid;
				$st=true;
				
		}

		if(trim($search['value'])!="") $where[]="title like '%".$db->clear($search['value'])."%'";
			
		if(count($where)>0 ) $w="where ".implode(" and ",$where);

		$total=$db->value("select count(*) from form_msg  $w");

		$res=$db->select("select * from form_msg $w order by data_add desc $l");
		$data=array();
		foreach($res as $r){
			$this->prepend($r);
			$data[]=$r;
		}
		
		$Core->json_get(array('ok'=>true,'data'=>$data,'recordsTotal'=>$total,'recordsFiltered'=>$total,'draw'=>_getn('draw'),'search'=>$search));
	}	
	
}