<?php
class Mods_form_input{
	
static function get_input($set){

	switch ($set['type']) {
		case 'text':
			return self::text($set);
			break;

		case 'phone':
			return self::phone($set);
			break;	
		case 'dirs':
			return self::dirs($set);
			break;	
			
		case 'textarea':
			return self::textarea($set);
			break;	
		
		default:
			return "";
			break;
	}

}

static function text($set){
	return '<input type="text" name="'.$set['name'].'" class="'.$set['class'].'" value="" title="'.htmlspecialchars($set['title']).'" '.($set['check']==1 ? 'required="true"':'').' name="'.$set['name'].'">';
}

static function textarea($set){
	return '<textarea name="'.$set['name'].'" title="'.htmlspecialchars($set['title']).'" '.($set['check']==1 ? 'required="true"':'').' class="'.$set['class'].'" rows="5" cols="40"></textarea>';
}

static function phone($set){
	return '<input type="text" name="'.$set['name'].'" title="'.htmlspecialchars($set['title']).'" alt="+380999999999"  value="+380" autocomplete="true">';
}

static function dirs($set){
	return '<select name="'.$set['name'].'" class="'.$set['class'].'"><option value="0">'.$set['title'].'</option>'.self::option($set['cont_id']).'</select>';
} 

static function option($dirs){
	global $db;
	$res=$db->hash("select id,title from skat_dirs_list where parent_id=$dirs and is_hidden=0 order by sort");
	$ret="";
	foreach($res as $id=>$t)
		$ret.='<option value="'.$id.'">'.$t.'</option>';
	return $ret;
}
}