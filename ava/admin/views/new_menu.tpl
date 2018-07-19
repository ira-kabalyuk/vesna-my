<div >
<p align="center" class="titl"><font size="+1">Новый пункт меню</font></p>
<table align="center" cellpadding="4" cellspacing="1" bgcolor="#99cccc" class="t8v">
<tr bgcolor="#ccffff"><td align="center">название</td><td align="center">страница</td></tr>
<form action="{ACL}/?adr=menu"  method="post">
<input type="Hidden" name="action" value="{MYACT}">
{if:TOP}<input type="Hidden" name="update_top" value="{TOP}">{/if}
<tr><td><input type="text" size="60" maxlength="60" class="t8v" name="link_name" value="{NAME}"></td><td>{LIST}</td></tr>
{row:LM}<tr><td colspan="2">{ln} &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="60" maxlength="60" class="t8v" name="link_name_{ln}" value="{lname}"></tr>{/row}
<tr bgcolor="#ccffff"><td colspan="2"><input type="Submit" class="b8v" value="сохранить"></td></tr>
</form></table>
</div>