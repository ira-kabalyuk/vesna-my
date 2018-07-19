<?
class Members{
    var $TB;
    var $id;
    var $pid;
    var $set;
    var $sys;
    
    function __construct($set){
        $this->set=$set;
    
    }
    
    
    function Start(){
        global $htm;
        $htm->external("KONT_EXT",MOD_PATH."admin.tpl");
        $act=trim($_GET['act']);
        if($act=='edit'){
            $this->edit_user();
        }elseif($act=='save'){
            $this->save_user();
             $this->list_users();
        }else{
            $this->list_users();
        }
        
        
        
    }
    
    
    
    
    
    function list_users(){
	global $db, $htm;
      $htm->external("EXT_ADD",MOD_PATH."list.tpl");
          $db->maprow("ROW_TOV","select a.id, if(a.is_ban=1,'no','yes') as ban,a.email, b.title as groups, c.info as name  
          from ".$this->TB." as a left join customers_group as b on a.group_id=b.id 
          left join customers_info as c on (a.id=c.customer_id and c.field_id=7)");
    
        
    }
    
    
    function edit_user(){
        global $db,$htm;
        $htm->external("KONT_EXT",MOD_PATH."edit_user.tpl");
         $inp=new Lform();
         $inp->load_set(CONFIG_PATH."members.xml");
         $user=$db->get_rec($this->TB." where id=".$this->id);
         $user['passw']='';
         $inp->add_var($user);
         $inp->fill_form('ROW_INFO');
         $htm->assign("NAME",$user['name']);
 
    }
    
    function save_user(){
        global $db;
        $r_=$_POST['r_'];
        $set=load_xml_file(CONFIG_PATH."members.xml");
        $data=array();
        foreach($set as $s){
        
        if($s['name']!='pasw'){
        $data[$s['name']]=trim($r_[$s['name']]);
        }else{
        	if(trim($r_[$s['name']])!='') $data['passw']=md5(trim($r_[$s['name']]));
        }	
        }
        $sql=$db->sql_update($this->TB,'',$data,"where id=".$this->id);
        //echo $sql;
        $db->execute($sql);
    }
    
}

?>