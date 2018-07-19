<p align="center" class="titl"><font size="+1">Профиль пользователя</font></p>
<form action="{ACL}/?adr=users&uid={UID}&act=save" method="post">
<table border="0" align="center" class="tlist" >
{row:INPUT_FORM}
	<tr>
	<td align="right">{C}{title}:</td>
	<td  align="left" >{INPUT}</td>
	</tr>

	
	
{/row}
<tr><td colspan="2" align="center"><input type="hidden" value="1" name="save"><input type="Submit" value="сохранить"></td></tr>
</table>
</form>