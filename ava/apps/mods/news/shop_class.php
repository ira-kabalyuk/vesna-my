<?php
class Mods_news_shop{
	static $icons;

	static function get_list($jar){
   	global $db,$htm,$Core;

   	$set=parse_jar($jar);

    if(isset($set['tpl']))
      $ret=$htm->load_tpl($set['tpl']);
    
    $w=array('is_hidden=0');
    $rid=1;
    
    $tag=intval($set['tag']);
    $row=(isset($set['row']) ? $set['row']:"NEWS_LIST");
    $limit=intval($set['limit']);
    $offset=intval($set['offset']);
    $limit=($limit==0 ? 10 : $limit);
    $link="shop";
    $sort=" sort ";
    



    if($rid!=0){
        $w[]=get_against('terms','cat_',$rid);
        $rlink=$db->value("select prefix from news_rubric where id=$rid");
        $link=($rlink!="" ? $rlink:$link);

     } 

    if($tag!=0)  $w[]=get_against('terms','tag_',$tag);

   	$res=$db->select("select id, title,date_pub,img, short from news  where ".implode(" and ",$w)." order by $sort limit $offset,".$limit);
    $c=1;

   	foreach($res as $r){

      //if(!isset($set['nodate']))
       //  self::date($r);
   		   
      
      $r['fotos']=$db->value("select count(*) from news_photo where is_hidden=0 and parent_id=".$r['id']);
     
      $r=array_merge($r,Mods_news_help::metadata($r['id']));
      self::get_icons($r);
        //if($r['meta-nolink']!='1')
         //  $r['link']="/".$link."-".$r['id'].".html";
      
      if($Core->link->news_id==$r['id']){
        $r['class']="active link-off";
        $r['link']="#";
      }else{
         $r['link']="/".$link."-".$r['id'].".html";
      }
     
      $c++;
      if($c==4){
         $r['c']=1;
         $c=1;
      } 
      
   		$htm->addrow($row,$r);
   	}

    if(isset($set['tpl'])){
      $htm->assvar($set);
      $htm->_var($ret);
      $htm->_row($ret,true);
   	  return $ret;
   }else{
      if(isset($set['json'])) return $htm->rows[$row];
   }
   
   }


static function get_icons(&$r){
	global $db;
	if(!isset(self::$icons)) self::$icons=$db->hash("select id,title,img from skat_dirs_list where parent_id=1",4);
	$icon=explode(",",$r['meta_icons']);
	$r['icons']='';
	foreach($icon as $i)
	$r['icons'].='<img class="poshy" src="/images/dirs/'.self::$icons[$i]['img'].'" alt="" title="'.htmlspecialchars(self::$icons[$i]['title']).'">';
}

static function photos(){
	global $Core;
	$id=$Core->link->news_id;
	$tpl=$Core->htm->load_tpl("shop_photos.tpl");
	$res=$Core->db->select("select img, descr from news_photo where parent_id=$id and is_hidden=0 order by sort");
	if(count($res)==0) return "";
	foreach($res as $r){
		$r['descr']=htmlspecialchars($r['descr']);
		$Core->htm->addrow("SHOP_PHOTOS",$r);
	}
	$Core->htm->_row($tpl,true);
	return $tpl;

}

static function shop_select(){
  return Input::option('#sql:select id,title from news where parent_id=4 and is_hidden=0 order by sort',0);

}

  	

}