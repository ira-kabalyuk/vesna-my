<?php
class Mods_instaler_core{

	var $inst_dir; // директория для подготовки пакета к инсталляции

	function install($url){
		global $htm;
			
		$this->inst_dir=UPLOAD_DIR."/install";
		$this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;
		
		 $htm->external('EXT_ADD',$this->mp."tpl/install.tpl");
  		if(AJAX) $htm->src($this->mp."tpl/install.tpl");

		$ok=array();

		if(!is_dir(UPLOAD_DIR)){
			$this->error("Отсутствует директория ".UPLOAD_DIR);
			return;
		}

		// очистим директорию для инсталляции
		if(!$this->prepend_dir()){
			$this->error("Неудачная попытка создания директории ".$this->inst_dir);
			return;
		}
		$ok[]="Подготовка к инсталляции прошла успешно";

		// скачаем файл исталляции
		if(!$this->wget($url)){
			$this->error("Не удалось скачать установочный файл по ссылке ".$url);
			return;
		}
		$ok[]="Файл инсталляции загружен";

		// распакуем файл в текущую директорию
		$file=$this->inst_dir."/mod.zip";
		$zip = new ZipArchive;
			$res = $zip->open($file);
	if ($res === TRUE) {
  				// extract it to the path we determined above
  			$zip->extractTo($this->inst_dir);
  			$zip->close();
  
			} else {
  
 				 $this->error("Не удалось распаковать файл ".$file);
			return false;
		}

	
		$ok[]="Модуль распакован";

		if(!is_file($this->inst_dir."/install.json")){
			$this->error("Отсутствует сценарий установки install.json");
			return;
		}

		// получим сценарий установки
		$json=json_decode(file_get_contents($this->inst_dir."/install.json"));

		if(!$this->copy_files($json->files)){
			$this->error("Ошибка инсталляции");
			return;
		}



		$htm->assign("LOG","<p>".implode("</p></p>",$ok)."</p>");

	}

	function copy_files($files){
		$ok=true;
		foreach($files as $fl){
			if(!$this->copy_file($fl)) 
				$ok=false;
		}
		return $ok;
	}


	function prepend_dir(){
		// очистим директорию для инсталляции
		if(is_dir($this->inst_dir))
			$this->removeDir($this->inst_dir);
		
		return mkdir($this->inst_dir, 0775);
	}

	function removeDir($dir) {
    if ($objs = glob($dir."/*")) {
		foreach($objs as $obj) {
			is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
		}
	}
	rmdir($dir);
}


function wget($url){
	return file_put_contents($this->inst_dir."/mod.zip", file_get_contents($url));
}




function copy_file($fls){
	
	$file=$fls->file;
	if($fls->type=='update'){
		if(is_file(ENGINE_PATH.$file)) 
			return true;
	}

	$dirs=explode(DIRECTORY_SEPARATOR,$fls->file);
	 //unset(end($dirs));
	@reset($dirs);
	$path=ENGINE_PATH.implode("/",$dirs);
	if(is_dir($path)) return true;
	$path=ENGINE_PATH;
	foreach($dirs as $d){
		$path.=$d;
		if(!is_dir($path))
			mkdir($path);
	}
	if(!is_dir($path)){
		$this->error("Не удалось создать папку".$path);
		return false;
	} 

	if(!copy($this->inst_dir.$file, ENGINE_PATH.$file)){
		$this->error("Не удалось скопировать файл".$file);
		return false;
	}
	return true;

}

function make_menu($menu){
	global $db;
	$tb="smart_menu";
	$pids=array();
	foreach($menu as $m){
		$is=$db->value("select count(*) from $tb where extens='{$m->extens}'");
		if($is!=0){
			$pids[]=$db->value("select id from $tb");
			}else{
			$id=$db->getid($tb,'id',1);
			$pids[]=$id;
			$data=array(
				'id'=>$id,
				'parent_id'=>($m->pid==0 ? 0: $pids[$m->pid]),
				'sort'=>$m->sort,
				'extens'=>$m->extens,
				'title'=>$m->title,
				'mod'=>$m->mod,
				'class'=>$m->class
				);
			$db->insert($tb,"",$data);
		}

	}
}

function error($msg){
	global $htm;
		$htm->assign("LOG",$msg);

	//$Core->json_get(array('ok'=>false,'error'=>$msg));
}

function get_data(){
	global $db;
	$res=$db->select("select id, title,mods,link, version from mods");
	$data=array();
	foreach($res as $r){
		
		$r['date_add']=date("d.m.Y");
		$data[]=$r;
	}
	return $data;

}



}