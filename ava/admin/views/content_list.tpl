<div class="bpanel">
<button class="ui-icon-plus" onclick="Smart.load_dialog({url:'{ACL}/?adr=pages&act=new&par_id={PID}'})">добавить страницу</button>
<button class="ui-icon-del" onclick="sel_cont_menu('get_content')">удалить отмеченные</button><br />

</div>
<p class="path">{CRUMBS}</p>

<table cellpadding="0" cellspacing="0" border="0" class="tlist" id="k_menu" width="800">
<tr><th>&nbsp;</th><th width="40">id</th><th>Заголовок страницы</th><th>ссылка</th><th colspan="2">действия</th></tr>
{row:KONTM}
<tr class="{CLASS}">
<td><input type="Checkbox" name="{ARM}" /></td>
<td><span class="myico ui-icon-{ICO}" title="{ARM}"></span>{ARM}</td>
<td><a href="{ACL}/?adr=kontent&id={ARM}&par_id={PID}" {if:CLASS}onclick="open_fold({ARM});return false;"{/if}>{TITL}</a></td>
<td> &nbsp;{LINKS} </td>
<td ><img src="{ACL}/img/up.gif" title="вверх" onclick="move_element('{ARM}','up')"/>&nbsp;<img src="{AIN}/img/dn.gif" title="вниз" onclick="move_element('{ARM}','dwn')"/></td>
<td lang="{ARM}-{PID}">&nbsp;<a href="#" class="edit" rel="{ARM}-{PID}"><img src="{AIN}/img/image_edit.png" title="редактировать!!" /></a>&nbsp;<img class="param"  src="/rul/img/config.png" title="свойства страницы" />&nbsp;<img src="{AIN}/img/{ONOFF}.png" title="{TITLOFF}" class="pointer" lang="{ARM}" onclick="on_off_page(this)" /></td>
</tr>
{/row}
</table>

<script>
ParentId='{PID}';
function open_fold(id){
 	Smart.load_curl('#div_content','{ACL}/?adr=get_content&par_id='+id+'&aj='+Math.random(),'drag_icon()');
    return false;
}
$(function(){
	$('.edit').click(function(){
		var rel=$(this).attr('rel').split("-");
		Smart.load_curl('#div_content','{ACL}/?adr=kontent&id='+rel[0]+'&par_id='+rel[1]);
		return false;
	});
	$('.param').click(function(){
		var rel=$(this).parent().attr('lang').split("-");
		Smart.setCash('#div_content','content');
		
		Smart.load_curl('#cach_internal','{ACL}/?mod=html&par_id={PID}&page_id={ARM}&act=get_set');
	//	Smart.load_dialog({url:'{ACL}/?adr=pages&par_id={PID}&page_id={ARM}',title:'пареметры страницы'});
		return false;
	});
	
});
</script>