<p align="center" class="titl"><font size="+1">Настройки модулей</font></p>
<table width="100%" border="0"><tr><td valign="top" style="width:300px;">
<tr><td width="300" valign="top">
<table width="300" class="tlist" cellpadding="3" cellspacing="0"  border="0">
{row:LINE}
<tr><td width="200">{name}</td><td width="100"> >> <a href="{ACL}/?adr=setup&mod={fold}">настройка</a></td></tr>
{/row}
</table>
</td>
<td valign="top" width="100%">
<form action="{ACL}/?adr=setup&mod={MOD}&act=save" method="post">
<table border="0" align="center" class="t8v" width="700">
{row:INPUT_FORM}
	{unless:HIDE}<tr>
	<td align="left" width="200" >{if:C}<span>*</span>{/if}{title}:</td>
	<td width="200" align="left" {ID}>{INPUT}</td>
	</tr>
	{/unless}
	{if:HIDE}{INPUT}{/if}
	<tr><td colspan="2"><hr width="100%" size="1" noshade color="Silver"></td></tr>
{/row}
{if:MOD}<tr><td colspan="2" align="center"><input type="hidden" value="1" name="save"><input type="Submit" value="сохранить"></td></tr>{/if}
</table>
</form>
</td></tr></table>