<?php
#Smart menu admin

class Mods_menus{

	var $mp;


function __construct(){
	$this->mp=dirname(__FILE__)."/";
}	

function route(){

	$act=_gets('act');
	$this->id=_getn('el_id');

	switch ($act) {
		case 'get_data':
			$this->get_data();
			break;

		case 'save_structure':
			$this->save_parents();
			break;

		case 'add_new':
			$this->add_new();
			break;

		case 'update_cat':
			$this->update_cat();
			break;

		case 'delete_cat':
			$this->delete_cat();
			break;
		case 'onof_cat':
			$this->onof_cat();
			break;

		
		default:
			$this->_list();
			break;
	}

}

function _List(){
	global $htm;

	$htm->src($this->mp."cat_list.tpl");
	

}

function get_data(){

	$this->responce(true,$this->get_subcat(0));
}

function update_cat(){
	global $db;
	$title=_posts('title');
	if($title==""){
		$this->error("Menu name name is empty!");
		return;
	}
	$data=['title'=>$title];
	$data['class']=_posts('class');
	$data['extens']=_posts('extens');
	$data['mod']=_posts('mod');
	$db->update("smart_menu","",$data," where id=".$this->id);
	$res=$db->get_rec("smart_menu where id=".$this->id);
	$res['bclass']=($res['is_hidden']==0 ? 'success':'default');
	$this->responce(true,$res);
}

function get_subcat($id){
	global $db;
	$res=$db->select("select * from smart_menu where parent_id=$id order by sort");
	if(count($res)==0) return [];
	$ret=[];
	foreach($res as $r){
		$r['bclass']=($r['is_hidden']==0 ? 'success':'default');
		$r['child']=$this->get_subcat($r['id']);
		$ret[]=$r;
	}
	return $ret;
}

function save_parents(){
	global $db;
	$cats=json_decode(_posts('cat'),true);
	$s=0;
	if(count($cats)>0)
		$db->execute("update smart_menu set parent_id=0");
	
	foreach($cats as $i){
		$id=$i['id'];
		$db->execute("update smart_menu set sort=$s where id=$id");
		
		$s++;
		if(isset($i['children']))
			$this->update_children($i);
	}

	$this->responce(true,$cats);
}

function add_new(){
	global $db;
	$name=_posts('title');
	if($name==""){
		$this->error("Title is not empty!");
		return;
	} 

	$data=['title'=>$name];
	$data['extens']=_posts('extens');
	$data['mod']=_posts('mod');
	$data['class']=_posts('class');
	$db->insert("smart_menu","",$data);
	$this->responce(true,$this->get_subcat(0));


}

function delete_cat(){
	global $db;
	$db->execute("delete from smart_menu where id=".$this->id);
	$db->execute("delete from smart_menu where parent_id=".$this->id);
	$this->responce(true,'ok');
}

function onof_cat(){
	global $db;
	$h=$db->value("select is_hidden from smart_menu where id=".$this->id);
	$h=($h=="1" ? 0:1);

	$db->execute("update smart_menu set is_hidden=$h where id=".$this->id);
	$this->responce(true,($h==0 ? 'on':'off'));
}

function update_children($d){
	global $db;
	$pid=$d['id'];
	$i=0;
	foreach($d['children'] as $child){
		$db->execute("update smart_menu set parent_id=$pid, sort=$i where id=".$child['id']);
		$i++;
		if(isset($child['children']))
			$this->update_children($child);
	}
}

function responce($ok,$data){
	global $Core;
	$Core->json_get(['ok'=>true,'data'=>$data]);
}

function error($msg){
	global $Core;
	$Core->json_get(['ok'=>false,'error'=>$msg]);
}

}
$htm->assign("MOD_LINK","/smart/?mod=menus");
$mod=new Mods_menus();
$mod->route();