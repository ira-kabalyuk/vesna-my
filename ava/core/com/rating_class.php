<?php
class Com_rating{
	
	static function add_rating($rating,$parent_id,$type){
		global $db;
		$data=array(
		'parent_id'=>$parent_id,
		'rating'=>$rating,
		'type'=>$type,
		'data_add'=>time()
		);
		$db->execute($db->sql_insert('ratings','',$data));
	}
	
	
}
