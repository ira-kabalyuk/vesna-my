<div style="width: 600px;">
<p> {TITLE}</p>
<form name="myform" action="{ACL}/?adr=kontent&id={ARM}&par_id={PAR}&page={PGL}&prod={ID}&act={ACTIONS}" method="post" target="send_multy">
<Table border="0" cellpadding="2" cellspacing="0" bgcolor="#ffffff" class="tlist">
{row:INPUT_FORM}
	{unless:HIDE}<tr>
	<td align="right" width="150" >{if:C}<span>*</span>{/if}{title}:</td>
	<td width="400" align="left" {ID}>{INPUT}</td>
	</tr>
	{/unless}
	{if:HIDE}{INPUT}{/if}
{/row}
<tr><td align="center" colspan="2"><input type="Submit" value="записать" class="button">&nbsp;&nbsp;<input type="button" value="Отмена" onclick="hide_box('#ajbox',1)" class="button"></td></tr>
</TABLE>
</form>
<iframe name="send_multy" width="100" height="1" border="0"></iframe>
</div>