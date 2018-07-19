<?
class Form_Builder extends Tab_elements{
 
    var $types=array("text","checkbox","textarea","email","password","captcha");
    var $chk=array("не проверять","проверять","проверять как e-mail");
    var $id;
    var $pid;
    var $TB;
    var $lng;
      
     function __construct($mid){
        global $Lang;
        $this->pid=$mid;
        $this->id=intval($_GET['fid']);
        $this->lng=$Lang;
        
     }
    
    
    function save_field(){
        global $db;
        $f=$db->get_rec($this->TB." where id=".$this->id." and lang='".$this->lng."'");
        $dat=array();
        parse_str($_POST['form_var'],$v);
        
        if(intval($f['id'])==0){
            $this->get_id();
            $dat['id']=$this->id;
            $dat['parent_id']=$this->pid;
            $dat['lang']=$this->lng;
            $dat['sort']=$this->get_sort();
            }
        $dat['titl']=trim($v['f_title']);
        $dat['type']=trim($v['f_type']);
        $dat['chk']=intval($v['f_chk']);
        $dat['err']=trim($v['f_err']);
        $dat['minw']=intval($v['f_minw']);
        
        
            if($dat['id']){
            $sql=$db->sql_insert($this->TB,"",$dat);
        }else{
            
            $sql=$db->sql_update($this->TB,"",$dat, " where id=".$this->id." and lang='".$this->lng."'");    
        }
        
        //echo $sql;
        $db->execute($sql);
    }
   function set_tb($table){
    $this->TB=$table;
   } 
    
    function edit_field(){
        global $db, $htm,$Lang;
        $f=$db->get_rec($this->TB." where parent_id=".$this->pid." and id=".$this->id." and lang='".$Lang."'");
       
        $types="";
        foreach($this->types as $t){
            $types.='<option value="'.$t.'" '.($t==$f['type'] ? 'selected':'' ).'>'.$t.'</option>';
            
        }
        $f['type']=$types;
           $types="";
           $i=0;
        foreach($this->chk as $t){
            $types.='<option value="'.$i.'" '.($i==$f['chk'] ? 'selected':'' ).'>'.$t.'</option>';
            $i++;
        }
        $f['chk']=$types;
        $htm->assign($f);
        
    }
    
    
        
        
   function list_fields(){
        global $db,$htm, $Lang;
        $res=$db->select("select * from ".$this->TB." where parent_id=".$this->pid." and lang='".$Lang."' order by sort");
                 foreach($res as $r){
            $htm->addrow("ROW_FIELDS",$r);
        }
        }
  function del_field(){
    global $db;
        $db->execute("delete from ".$this->TB." where lang='".$Lang."' and id=".$this->id);
        $this->resort($this->pid);
  }      
        
    function compile(){
       global $db, $htm;
        $res=$db->hash("select concat('f_',id) as name,titl,chk,err,type,minw from ".$this->TB." where parent_id=".$this->pid." and lang='".$this->lng."' order by sort",4);
          
          $tpl=file_get_contents(MOD_PATH."mail_my.tpl");
          $set=load_mod_config($this->mod,$this->pid);
          $htm->assvar("BUTSEND",$set['butsend']);
          $htm->assvar("ACTION",$set['action']);
          $inp=new Form("input","","f_");
          foreach($res as $key=>$r){
            if($r['type']=='textarea'){
              $r['cols']=40;
              $r['rows']=5;  
            }elseif($r['type']=='email'){
              $r['name']='email';
              $r['chk'] =2;
              $r['type']='text';
              $res[$key]=$r;
            }elseif($r['type']=='password'){
              $r['name']='passwd';
              $res[$key]=$r;  
            }elseif($r['type']=='captcha'){
               $r['name']='captcha';
               $r['chk']=4;
               $res[$key]=$r;  
               $r['img']="/example.php";
               
            } 
            $r['value']='~'.$r['name'].'~';
            $inp->add_ar($r);
          }
         $inp->fill_form('SEND_FORMS',0);
         $htm->_row($tpl);
          $htm->_var($tpl);
         unset($htm->rows['SEND_FORMS']);
         file_put_contents(CMS_CASH."smod".$this->pid."_".$this->lng,serialize($res));
         file_put_contents(CMS_CASH."mod".$this->pid."_".$this->lng,$tpl);
          
        
    }    

}
?>