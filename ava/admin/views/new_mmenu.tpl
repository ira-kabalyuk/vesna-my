<p align="center" class="titl"><font size="+1">{TITLE}</font></p>
<p>"линк" - любая ссылка, является приоритетом при формировании ссылки</p>
<p>"переменные"- переменные, передаваемые по ссылке, присоединяются к линку текущей страницы, на которой выводится меню</p>
<table align="center" cellpadding="4" cellspacing="1" bgcolor="#99cccc" class="t8v">
<tr bgcolor="#ccffff"><td align="center">название</td><td align="center">тип ссылки</td><td align="center">ссылка</td></tr>
<form action="{ACL}/?adr=mmenu&sid={PID}"  method="post">
<input type="Hidden" name="action" value="{MYACT}" />
{if:TOP}<input type="Hidden" name="update_top" value="{TOP}" />{/if}
<tr><td><input type="text" size="60" maxlength="60" class="t8v" name="link_name" value="{NAME}" /></td>
<td><select name="type" onchange="change_linktype(this.value)">{TYPE}</select></td>
<td><select name="page" id="pagelink" style="display:{if:!LT}none{/if}{if:LT}block{/if};">{LIST}<input type="text" size="30" id="txtlink" maxlength="60" class="t8v" name="link" value="{LINK}" style="display:{if:LT}none{/if}{if:!LT}block{/if};"/></td>

</tr>
{row:LM}<tr><td colspan="2">{ln} &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="60" maxlength="60" class="t8v" name="link_name_{ln}" value="{lname}" /></tr>{/row}
<tr bgcolor="#ccffff"><td colspan="4"><input type="Submit" class="b8v" value="сохранить" /></td></tr>
</form></table>
<script language="javascript">
function change_linktype(key){
   if(key==0){
    $('#txtlink').hide();
    $('#pagelink').show();
   }else{
    $('#pagelink').hide();
    $('#txtlink').show();
   } 
}
</script>