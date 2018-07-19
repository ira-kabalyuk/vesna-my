<?
class Rubric extends Tab_elements{
    var $TB;
    var $id;
    var $pid;
    var $lng;
    var $where;
    function __construct($tab){
        global $Lang;
        $this->TB=$tab;
        $this->lng=$Lang;
       
    }
    
    function list_rubr($ajax=0){
        global $db,$htm;
        $htm->src(CMS_RUL_TPL."rubric_list.tpl");
        if($ajax==1)  $htm->src(CMS_RUL_TPL."rubr_list.tpl");
        $res=$db->select("select * from ".$this->TB." where parent_id=".$this->pid." and lang='".$this->lng."' order by sort");
        foreach ($res as $r){
             $r['ONOFF']=($r['is_hidden']==1 ? 'off' :'on');
             $r['TITLOFF']=($r['is_hide']==1 ? 'Включить' :'Выключить');
            $htm->addrow("RUBR_LIST",$r);
        }
        
    }
    
    function edit_rubr(){
        global $htm, $db, $Mod_lang;
        
        $htm->src(CMS_RUL_TPL."rubric_edit.tpl");
        $r=$db->hash("select lang,title from ".$this->TB." where  id=".$this->id);
        foreach ($Mod_lang as $key=>$lang){
            $htm->addrow("INP",array(
            'ln'=>$key,
            'lang'=>$lang,
            'TITLE'=>$r[$key],
            ));
        }
        $htm->assign('link',$db->value("select link from ".$this->TB." where  id=".$this->id));
    }
    
        function save_rubr(){
        global $db, $Mod_lang;
        if($this->id==0){
            $this->id=$db->getid($this->TB,'id',1);
        }else{
            $db->execute("delete from ".$this->TB." where id=".$this->id);    
        }
        
        parse_str($_POST['form_var'],$val);
       
        foreach ($Mod_lang as $key=>$lang){
            $dat=array('id'=>$this->id,'link'=>$val['link'],'parent_id'=>$this->pid,'lang'=>$key,'title'=>stripslashes($val['title_'.$key]));
            $db->execute($db->sql_insert($this->TB,"",$dat));
           
            }
        
        }
        
         function check_db(){
        global $db;
        
        if($db->table_find($this->TB)) return;
        
        $sql="CREATE TABLE `".$this->TB."` (
  `id` int(10) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `title` varchar(200) character set utf8 NOT NULL,
  `lang` varchar(2) character set utf8 NOT NULL default 'ru',
  `is_hidden` tinyint(1) NOT NULL default '0',
  `sort` int(5) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `parent_id` (`parent_id`)
);";
    $db->execute($sql);
    echo mysql_error();
   }

        
    }
    

?>