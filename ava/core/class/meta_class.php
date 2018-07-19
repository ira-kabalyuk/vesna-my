<?php
Trait meta{

	public function save_meta($id,$key,$val=""){
        global $db;
        if(!isset($this->tb_metadata)) return;

        if(is_array($key)){
        	foreach($key as $k=>$v)
        		$this->save_meta($id,$k,$v);
        		return;
        }

        $db->execute("delete from {$this->tb_metadata} where parent_id=".$id." and metakey='$key'");
        if(gettype($val)=='array') $val=implode(",",$val);
        if(trim($val)=="") return;
        $data=array('parent_id'=>$id);
        $data['metavalue']=$val;
        $data['metakey']=$key;
        $db->execute($db->sql_insert($this->tb_metadata,"",$data));
    }


    function save_meta_prefix($id){
    	foreach($_POST as $key=>$val){
    		if(preg_match("/^meta_([a-z|0-9|-|_]+)$/",$key,$m)){
    			$this->save_meta($id,$m[1],$val);
    		}
    	}
    }


	function add_meta($key,$val){
		$this->save_meta($this->id,$key,$val);
	}
   


    public function get_meta_hash($id,$prefix="meta_"){
		global $db;
		if(!isset($this->tb_metadata)) return;
		return $db->hash("select concat('$prefix',metakey), metavalue from {$this->tb_metadata} where parent_id=".$id);

	}

	 public function get_meta($id,$key=""){
		global $db;
		if(!isset($this->tb_metadata)) return ;
		if($key=="")
		return $db->hash("select metakey, metavalue from {$this->tb_metadata} where parent_id=".$id);
		return $db->value("select  metavalue from {$this->tb_metadata} where metakey='$key' and parent_id=".$id);

	}

    

    
}