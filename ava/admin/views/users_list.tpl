<p align="center" class="titl"><font size="+1">Пользователи</font></p>
<p class="butp"> <a href="{ACL}/?adr=users&act=edit">новый пользователь</a></p>
<table border="0" align="center" class="tlist" cellpadding="3" cellspacing="0" >
	<tr>
	<th >id</th>
	<th >login</th>
	<th >Имя</th>
	<th >коментарий</th>
	<th >действие</th>
	</tr>

{row:LIST_USERS}
	<tr>
	<td >{id}</td>
	<td >{login}</td>
	<td >&nbsp;{name}</td>
	<td >&nbsp;{email}</td>
	<td ><a href="{ACL}/?adr=users&uid={id}&act=edit">изменить</a><br>
	<a href="{ACL}/?adr=users&uid={id}&act=del">удалить</a>
	</td>
	</tr>
	
	
	
{/row}

</table>
