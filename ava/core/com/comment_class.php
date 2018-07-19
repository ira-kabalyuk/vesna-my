<?php
class Com_comment{
	
static function count_comment($pid,$type){
		global $db;
		return $db->value(" select count(*) from com_comment where parent_type=$type and parent_id=$pid");
	}
	
}