<?php
class Mods_instaler_admin{


function Start(){
  global $htm;
  $htm->assign('MOD_LINK',ADMIN_CONSOLE."/?mod=instaler");

    $this->mp=dirname(__FILE__).DIRECTORY_SEPARATOR;

    $act=_gets('act');

    switch ($act) {
      case 'install':
        $this->install();
        break;

      case 'get_data';
        $this->get_json_list();
      break;  
      
      default:
        $this->_list();
        break;
    }
  }



function _list(){
  global $htm;
    $htm->external('EXT_ADD',$this->mp."tpl/list.tpl");
  if(AJAX) $htm->src($this->mp."tpl/list.tpl");
}

function install(){
  $ins=new Mods_instaler_core();
  $ins->install("http://kafo3/upload/blog.zip");
}

function get_json_list(){
  global $Core;
  $mod=new Mods_instaler_core();
  $Core->json_get(array('ok'=>true,'data'=>$mod->get_data()));
}


}