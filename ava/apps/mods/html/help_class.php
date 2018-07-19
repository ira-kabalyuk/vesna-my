<?php
class Mods_html_help{


	static function form(){
		global $htm;
		$ret=$htm->load_tpl('records.tpl');
		return $ret;
	}



static function gallery(){
  global $db,$Core;
  $set=array('row'=>"LIST_PHOTOS",'tpl'=>"gallery.tpl");

    //$set=parse_jar($jar);
    $id=intval($Core->link->id);
    $res=$db->select("select img ,descr from static_photo where is_hidden=0 and parent_id=$id order by sort");
    if(count($res)==0) return "";
    foreach($res as $r)
      $Core->htm->addrow($set['row'],$r);
    if(!isset($set['tpl'])) return;
    $ret=$Core->htm->load_tpl($set['tpl']);
    $Core->htm->_row($ret,true);
    return $ret;
}

}