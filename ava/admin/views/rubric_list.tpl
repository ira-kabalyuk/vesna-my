<style>
.tlist td.t8{font-size: 8pt;color:#898989;}
</style>
<table>
<tr>
<td valign="top" id="subcont">
<table class="tlist" cellpadding="3" cellspacing="0">
<tr><th>id</th>
<th><img src="/rul/img/up.gif" onclick="movef('up')" title="переместить вверх"/>&nbsp;<img onclick="movef('dwn')" title="преместить вниз" src="{AIN}/img/dn.gif"/></th>
<th width="400">название рубрики</th>
<th width="150">действия</th></tr>
{row:RUBR_LIST}<tr><td>{id}</td>
<td valign="center" align="center" class="movef"><input type="radio" value="{id}" name="movef"/></td>
<td rel="title">{title}</td>
<td><a href="javascript:load_curl('#subcont','{MOD_LINK}&act=edit_rubr&rubr_id={id}');"><img src="{ACL}/img/image_edit.png" title="редактировать" /></a>
&nbsp;&nbsp;
<img src="{ACL}/img/{ONOFF}.png" class="pointer" title="{TITLOFF}" class="pointer" onclick="on_off_rubr(this,{id})" />
&nbsp;&nbsp;
<img src="{ACL}/img/cross.png" class="pointer" title="Удалить" class="pointer"  onclick="del_rubr(this,{id})" />
</td></tr>{/row}
</table>
<script>
<script>
var CurElement='{RUID}';
</script>
</td>
<td valign="top">
<div class="bpanel">

<a href="javascript:load_curl('#subcont','{MOD_LINK}&act=edit_rubr')">добавить рубрику</a>
<button id="help" onclick="load_help('rubric')"><span class="ui-icon-help"></span>подсказка</button>
</div>
</td>
</tr>
</table>


<script>


function on_off_rubr(obj,mid){

   $(obj).attr('src','{AIN}/img/ajax-loader.gif');
   $.ajax({url: '{MOD_LINK}&rubr_id='+mid+'&act=hide_rubr&aj='+Math.random(),
    success: function(dat){$(obj).attr('src','{AIN}/img/'+dat+'.png');}, error: function(){alert('Ошибка загрузки!');}});
}
function  del_rubr(obj,id){
   $('<div id="dialog-confirm"><p><img src="{AIN}/img/alert.png" />Вы действительно хотите удалить рубрику<br/><b>'+$(obj).parent().parent().find('td[rel="title"]').html()+'</b>?</p></div>').dialog({
        buttons:{ "Ok": function() {
        $(this).dialog("close");
        load_curl('#subcont','{MOD_LINK}&act=del_rubr&rubr_id='+id+'&aj='+Math.random());
        },"Отмена" : function(){ $(this).dialog("close");} },
       modal: true,
       resizable: false,
       title: '<font color="#8A0303">УДАЛЕНИЕ РУБРИКИ</font>',  
});
}
function movef(dir){
   load_curl('#subcont','{MOD_LINK}&rubr_id='+CurElement+'&act=move&dir='+dir,'on_load_page()'); 
}
function on_load_page(){
$('table.tlist input[type="radio"]').click(function(){
   $('table.tlist td.movef').css("background-color","#ffffff"); 
    $(this).parent().css("background-color","#00ff54");
    CurElement=parseInt($(this).val()); 
    });
    sel_cur();

}
function sel_cur(){
    if(CurElement!=0){
        var Select=$('table.tlist input[value="'+CurElement+'"]');
        $(Select).attr('checked',"true");
        $(Select).parent().css("background-color","#00ff54");
    } 
}

$(document).ready(function(){
on_load_page();
    
   });
</script>