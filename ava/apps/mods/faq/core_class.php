<?php
class Mods_faq_core extends Mods_news_core{


function route($mod){
	global $Core;
	$this->set=Com_mod::load_conf($mod);
	$pid=$this->set['parent_id'];
	$this->pid=$pid;

	$links=$Core->link->Link;
	$a=count($links);

	if($a==3 && $links[2]==""){
		// ссылка на корень, все вопросы
		$this->_list();
	}elseif($a==3 && $links[2]!=""){
		//возможно вопрос
	$l=$Core->db->clear($links[2]);
	if($l=='add'){
		$this->add_questions();
	}else{
		$Core->link->e_404();
	}


	}else{
		$Core->link->e_404();

	}


}


	function _list($rid=0){
		global $db,$htm;
		$tpl=$this->set['main_tpl'];
		$htm->external("EXT",TEMPLATES.$tpl);
		if(AJAX) $htm->src(TEMPLATES.$tpl);

		$pid=$this->set['parent_id'];

		$rds=_postn_ar('rubric');
		$wr="";
	if(count($rds)>0)
		$wr=" and id in(".implode(",",$rds).")";
	

	
		$rubr=$db->select("select id,title from news_rubric where is_hidden=0 and parent_id=$pid $wr order by sort");

		foreach($rubr as $r){
			$w="parent_id=$pid and is_hidden=0 and ";
			$w.=get_against("terms","cat_",$r['id']);

			$res=$db->select("select id, title, short, descr from news where $w order by date_pub desc");
			if(count($res)>0){
				$r['FAQ_ROW']=array();
				$i=0;
				foreach ($res as $s) {
					if($i==1)
						$s['clear']='<div class="clearfix hidden-sm hidden-xs"></div>';
					$r['FAQ_ROW'][]=$s;
					$i++;
					if($i==2) $i=0;

				}
				
				
			}
			$htm->addrow("NEWS_ROW",$r);
		}
		$htm->assign(array(
	"SITE_TITLE"=>$this->set['seo']['seo_t'],
	"KEYWORDS"=>$this->set['seo']['seo_k'],
	"DESCRIPTIONS"=>$this->set['seo']['seo_d']
	));


	}

	function add_questions(){
		global $Core;
		$db=$Core->db;
		$fields=array("mail"=>"mail","my_name"=>"title","descr"=>"short");
		$e=array("mail"=>"Вы не указали свой Email !","name"=>"Вы не указали свое имя !","descr"=>"Вы не написали текст вопроса !" );
		$error=array();
		$ok=true;
		$data=array();
		$meta=array();

		//проверим переменные
		foreach ($fields as $key => $val) {
			if(_posts($key)==""){
				$error[$key]=$e[$key];
			}else{
				if($val=="mail"){
					$meta[$key]=_posts($key);
				}else{
					$data[$val]=_posts($key);
				}
				
			}
			
		}
		// в случае ошибки вернем сообщение об ошибке
		if(count($error)>0){
			$Core->json_get(array('ok'=>false,'error'=>$error));
			return;
		}

		$data['is_hidden']=1;
		$data['parent_id']=$this->pid;
		$data['date_add']=time();
		$data['id']=$db->getid('news','id',1);

		$db->insert("news", "", $data);

		foreach($meta as $key=>$val){
			$db->insert("news_metadata","",array('parent_id'=>$data['id'],"metakey"=>$key,"metavalue"=>$val));
		}
		$Core->json_get(array('ok'=>true,'data'=>file_get_contents(TEMPLATES."faq_add-ok.tpl")));




	}

}
