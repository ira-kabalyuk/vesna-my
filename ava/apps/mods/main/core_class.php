<?php
class Mods_main_core{


		// слайдер мастеров на главной
	static function master_slide(){
		global $db,$htm;

		$res=$db->select("select id,title,short,descr,guid from news where parent_id=3 and is_hidden=0 order by sort");
		$i=0;
		foreach($res as $r){
			$meta=Mods_news_help::metadata($r['id']);
			$r=array_merge($r,$meta);
			$r['short']=nl2br($r['short']);
			$r['link']="/teachers/".$r['guid'];
			$htm->addrow("MASTER_SLIDE",$r);
			$htm->addrow("MASTER_ROW",$r);
			if($i<5) $htm->addrow("MASTER_5ROW",$r);
			$i++;
		}
		
	}

	// расписание на главной
	static function shedule(){
		global $db,$htm;
		$tpl=file_get_contents(TEMPLATES."main-shedule.tpl");
		$time=time()-60*60;
		$all=$db->value("select count(*) from news where parent_id=7 and is_hidden=0 and date_pub>$time");
		$htm->assign('c_all',$all);

		$mod=new Mods_shedule_core();
		$mod->set=Com_mod::load_conf('shedule');
		$mod->pid=$mod->set['parent_id'];
		$mod->inside=true;
		$mod->where[]="date_pub>".time();
		$mod->order="date_pub asc";
		$mod->_list(0);
		$htm->_row($tpl,true);
		return $tpl;

	}

	// Ближайшие занятия
	static function shedlist($jar){
		global $db,$htm,$Core;
		$set=parse_jar($jar);
		$pid=$Core->news_id;
		$limit=" limit 0,".intval($set['limit']);
		$w=array("a.is_hidden=0","a.parent_id=7","a.date_pub>".time());
		$w[]=get_against("terms","cat_",$pid);
		$where=implode(" and ",$w);

		$sql="select a.id,a.date_pub as start,a.guid, b.metavalue as stop from news as a left join news_metadata as b on (a.id=b.parent_id and b.metakey='stop') where $where order by start asc ".$limit;
		$res=$db->select($sql);
		$i=0;
		foreach($res as $r){
			Mods_news_help::date($r,'start','');
			if(trim($r['stop'])!="") Mods_news_help::date($r,'stop','s_');
			$r['link']="/shedule/".$r['id'];

			$htm->addrow($set['row'],$r);
			if($i==0){ 
				$htm->assign(array(
					"c_day"=>$r['day'],
					"c_month"=>$r['month'],
					"c_id"=>$r['id'],
					"c_s_day"=>$r['s_day'],
					"c_s_month"=>$r['s_month']
					));
			}
			$i++;
		}
		return "";

	}





	static function item_photos(){
		global $db,$htm,$Core;
		$id=intval($Core->news_id);
		$res=$db->select("select id, img from news_photo where parent_id=$id order by sort");
		foreach($res as $r){
			$htm->addrow("PHOTOS",$r);
		}

	}

	static function upload($jar){
		$arg=parse_jar($jar);
		return Com_upload_core::get_ajax_form($arg);

	}

	static function actions($jar){
		global $db,$htm;
		$set=parse_jar($jar);
		$limit=intval($set['limit']);
		$ids=$db->select("select parent_id as id,metavalue as v from news_metadata where metakey='baner' order by v");
		if(count($ids)==0)
			return;

		$htm->assign("ACTIONS",1);
		foreach($ids as $v){
			$r=$db->get_recs("select id,title,short,guid from news where id=".$v['id']);
			$meta=Mods_news_help::metadata($v['id']);
			$r['baner']=$meta["meta_ban".$v['v']];
			$r['link']="/actions/".$r['guid'];
			$r=array_merge($r,$meta);
			$htm->addrow($set['row'],$r);
		}
	}

	

	static function top_review($jar){
		global $db,$htm;
		$set=parse_jar($jar);
		$limit=intval($set['limit']);
		$res=$db->select("select id, title,short from news where parent_id=9 order by RAND() desc limit 0,$limit");
		foreach($res as $r){
			$meta=Mods_news_help::metadata($r['id']);
			$r=array_merge($r,$meta);
			$htm->addrow($set['row'],$r);
		}
	}


	static function photos(){
		return Mods_news_help::gall('row:NEWS_PHOTOS,tpl:news_photo.tpl');
	}
}