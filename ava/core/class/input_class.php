<?php
class Input{
	static function option($ar,$id=0){
		$ar=self::get_ar($ar);
		$ret="";
		foreach ($ar as $key=>$t)
		$ret.='<option value="'.$key.'"'.($id==$key ? 'selected':'').'>'.htmlspecialchars($t).'</option>';
		return $ret;
	}

	static function option_multy($ar,$opt="-"){
		if(!is_array($ar)) $ar=self::get_ar($ar);
		if(!is_array($opt)) $opt=self::get_val_ar($opt);
		$ret="";
		foreach ($ar as $key=>$t)
		$ret.='<option value="'.$key.'"'.(in_array($key, $opt) ? ' selected':'').'>'.htmlspecialchars($t).'</option>';
		return $ret;
	}
	static function _option($ar,$id=0){
		if(!is_array($ar)) $ar=self::get_ar($ar);
		$ret="";
		foreach ($ar as $key=>$t)
		$ret.='<option value="'.$key.'"'.($id==$key ? ' selected':'').' class="'.$t['class'].'">'.htmlspecialchars($t['title']).'</option>';
		return $ret;
	}
	
	static function select($name,$id,$cont,$null=false){
		return '<select name="'.$name.'">'.($null ? '<option value="">---</option>':'').self::option($cont,$id).'</select>';
		
	}
	static function selectbox($name,$ar,$val=array()){
		if(!is_array($ar)) $ar=self::get_ar($ar);
		//if(!is_array($val)) $val=self::get_ar($val);
		$ret="";
		$i=0;
		foreach ($ar as $key=>$t){
		$ret.='<span>'.self::checkbox($name."[$i]",$key,in_array($key, $val),$t).'</span>';
		$i++;
		}
		return $ret;
		
	}

	static function selectradio($name,$ar,$val){
		if(!is_array($ar)) $ar=self::get_ar($ar);
		$ret="";
		$i=0;
		foreach ($ar as $key=>$t){
		$ret.='<label class="radio">'.self::radio($name,$key,($key==$val),'<i></i>'.$t).'</label>';
		$i++;
		}
		return $ret;
		
	}
	

	static function text($name,$val=''){
		return '<input name="'.$name.'" type="text" value="'.htmlspecialchars($val).'" />';
	}

	static function date($name,$val=""){
		return '<input type="text" name="'.$name.'" class="datepicker"  value="'.$val.'" size="10">';
		}

	static function textarea($name,$val='',$rows=4, $cols=40){
		return '<textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$val.'</textarea>';
	}

	static function checkbox($name,$val='',$checked=false,$title=''){
		return '<input type="checkbox" name="'.$name.'" value="'.$val.'" '.($checked ? 'checked':'').'>'.$title;
	}
	static function radio($name,$val='',$checked=false,$title=''){
		return '<input type="radio" name="'.$name.'" value="'.$val.'" '.($checked==$val ? 'checked':'').'>'.$title;
	}

	static function get_ar($s){
			global $db;

		if(gettype($s)=='array') return $s;

		if(gettype($s)=='string'){
			if(strpos($s,":")===false)
				return array($s);

			$t=explode(":",$s);
			if($t[0]=='#sql')
				return $db->hash($t[1]);

			$t=explode(",",$s);
			$r=array();
			foreach($t as $k){
				$td=explode(":",$k);
				$r[$td[0]]=$td[1];
			}
			return $r;
		}
	}
	static function get_val_ar($s){
			global $db;

		if(is_array($s)) return $s;
		if(trim($s)=="") return array("0");

		if(is_string($s)){

			if(strpos(":", $s)>0){

			$t=explode(":",$s);
			if($t[0]=='#sql')
				return $db->vector($t[1]);
		}
			$r=explode(",",$s);
			return $r;
		}
	}


	static function get_ar_js($s){
		global $db;
		$ret=array();
		if(is_array($s)){
			if(count($s)==0) return "";
		foreach ($s as $key => $value) {
			$ret[]=$key.":".$value;
		}
		return implode(",",$ret);
	}
		$t=explode(":",$s);
			if($t[0]=='#sql')
				return self::get_ar_js($db->hash($t[1]));
			return $s;
	}

	static function get_input($in,$name,$val){
		$type=$in['type'];
		if($type=='text'){
			return self::text($name,$val);
		}elseif($type=='textarea'){
			return self::textarea($name,$val);
		}elseif($type=='select'){
			
			return self::select($name,$val,self::get_ar($in['cont']));
		}elseif($type=='option'){
			return self::option($name,$val);
		}elseif($type=='date'){
			return self::date($name,$val);
		}
		return "";
	}
}
