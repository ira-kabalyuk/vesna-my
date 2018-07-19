{if:!ACT_ADD}
<div>
<p>Новый раздел</p>
<form id="add_form">
<input type="Hidden" name="action"  value="add_new_razd">
<table border=0 >
<tr><td>название раздела</td><td><input type="text" value="" size="30" name="add_name"></td></tr>
<tr><td>принадлежит разделу:</td><td><select  name="parent_id">{row:ROW_FL}<option value="{id}">{name}</option>{/row}</select></td></tr>
<tr><td colspan="2" align="center"><input type="button" value="отправить" class="button" onclick="send_form('#add_form','{ACL}/?adr=add_razd','document.location=\'{ACL}/?adr=kontent\'')">&nbsp;&nbsp;<input type="button" value="отмена" class="button" onclick="hide_box('#ajbox')"></td></tr>
</table>
</form>
</div>
{/if}
{if:ACT_ADD}{ACT_ADD}{/if}


