<?php
class Com_upload_core{
	
/**
 * @var arg('link'=>,'div'=>,'img'=>)
 * */
static function get_form($arg){
	global $htm;
	$ret=file_get_contents(dirname(__FILE__)."/form.tpl");
	//$arg['img'].='?v='.rand();
	$arg['div']=str_replace(array("[","]"),array("_",""),$arg['div']);
	$htm->assvar($arg);
	$htm->_var($ret);
	$htm->_if($ret);
	return $ret;
	
}

static function get_ajax_form($arg){
	global $htm;
	if(!is_array($arg)) $arg=json_decode($arg,true);

	$ret=file_get_contents(dirname(__FILE__)."/ajax-form.tpl");
	if(isset($arg['tpl'])) $ret=file_get_contents(dirname(__FILE__)."/front-form.tpl");
	//$arg['img'].='?v='.rand();
	$arg['div']=str_replace(array("[","]"),array("_",""),$arg['div']);

	if(isset($arg['json'])){
		$json=parse_jar($arg['json']);
		unset($arg['json']);
		$arg['json']=array_merge($arg,$json);
	}else{
		$arg['json']=$arg;
	}
	
	$arg['json']=get_json($arg['json']);
	
//print_r($arg);
	if(isset($json['crop']))
	$htm->assvar('crop',$json['crop']);

	if(isset($json['ext'])){
		$htm->assvar('ext',$json['ext']);
		$arg['fl']=1;
	}else{
		$arg['im']=1;
	}
	$htm->assvar($arg);
	$htm->_ifv($ret);
	$htm->_var($ret);
	
	return $ret;
	
}

static function get_video_form($vid=""){
	global $htm, $db;
	$arg=array('sid'=>"",'poster'=>"",'video'=>"");

	if($vid!=""){
		$arg=$db->get_rec("video where sid='$vid'");
	}else{
		$arg['token']=Com_sprout::get_token();
	}
	

	$ret=file_get_contents(dirname(__FILE__)."/video-form.tpl");
	//$token=Com_sprout::get_token();
	$arg['div'] = 'source_video';
	$arg['url'] = 'https://api.sproutvideo.com/v1/videos';

	//$arg['img'].='?v='.rand();
	$htm->assvar($arg);
	//$htm->_ifv($ret);
	$htm->_var($ret);
	
	return $ret;
	
}


/**
 * @var data (array)
 * @var arg (path,div,name,fname)
 * */
	static function upload(&$data,$arg){
		global $htm;
		$up=new Upload;
		$up->ext[]="pdf";
		$ret="";
	$ok=$up->my_upload(array(
	'kat'=>$arg['path'],
	'name'=>$arg['name'],
	'prop'=>true,
	'rx'=>0,
	'ry'=>0,
	'fname'=>$arg['div']
	));
	if($ok['ok']){
	$data[$arg['fname']]=$ok['fname'];
	$ret=file_get_contents(dirname(__FILE__)."/result.tpl");
	$htm->assvar(array('div'=>$arg['div'],'img'=>$arg['path']."/".$ok['fname']));
	$htm->_var($ret);
		return $ret;	
	}else{
		return $ok['error'];
	} 
	}
	
	static function ajax_upload($arg){
		$up=new Upload;
		$ret="";
		if(isset($arg['prew'])) $up->prew=$arg['prew'];
		$ok=$up->my_upload(array(
		'kat'=>$arg['path'],
		'name'=>$arg['name'],
		'prop'=>$arg['prop'],
		'rx'=>intval($arg['rx']),
		'ry'=>intval($arg['ry']),
		'fname'=>$arg['fname']
	));
		$ok['path']=$arg['path'];
		return $ok;
	}

static function jq_upload(){
	global $Core;

$ok=array('ok'=>false,'error'=>"error file name");
$fname=preg_replace("/[^a-z|0-9|_|-]/","",_posts('name'));
$path=preg_replace("/[^a-z|0-9|_|\/]/","",_posts('path'));
$prefix=preg_replace("/[^a-z|0-9|_|-]/","",_posts('prefix'));

if($fname==""){ 
	$Core->json_get($ok);
	return;
}

$up=new Upload;
$up->ext[]="pdf";
$is_auth=true;
if(!$is_auth) $path="uploads/users";

$set=array(
	'kat'=>$path,
	'name'=>$prefix.time(),
	'prop'=>((_postn('prop')==1 || _posts('prop')=='true') ? true:false),
	'rx'=>_postn('rx'),
	'ry'=>_postn('ry'),
	'fname'=>$fname,
	'crop'=>_postn('crop')
	);

if(_postn('prew_x')!=0)
	$set['prew']=array("rx"=>_postn('prew_x'),"ry"=>_postn('prew_y'),'prop'=>(_postn('prew_p')==1 || _posts('prew_p')=='true') ? true:false,'pref'=>'s_','kat'=>$path);

$ok=$up->my_upload($set);


$ok['path']=$path;
$Core->json_get($ok);
return;
}

static function ajax_crop($arg){
		$up=new Upload;
		$ok=$up->crop($arg);
		if(!$ok) return array('ok'=>false);
		return array('ok'=>true,'fname'=>$arg['dest'],'path'=>$arg['path']);
	}

}