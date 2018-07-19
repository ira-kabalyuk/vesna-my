<p align="center" class="titl"><font size="+1">Общие настройки</font></p>
<form action="{ACL}/?adr=all&act=save" method="post">
<table border="0" align="center" class="t8v" width="700">
{row:INPUT_FORM}
	{unless:HIDE}<tr>
	<td align="right" width="150" >{if:C}<span>*</span>{/if}{title}:</td>
	<td width="400" align="left" {ID}>{INPUT}</td>
	</tr>
	{/unless}
	{if:HIDE}{INPUT}{/if}
	<tr><td colspan="2"><hr width="100%" size="1" noshade color="Silver"></td></tr>
{/row}
<tr><td colspan="2" align="center"><input type="hidden" value="1" name="save"><input type="Submit" value="сохранить"></td></tr>
</table>
</form>
