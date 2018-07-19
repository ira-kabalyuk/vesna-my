<script language="JavaScript" src="/inc/menu.js" type="text/javascript"></script>
<h2 align="center" class="titl"><font size="+1">Главное меню</font></h2>
<table width="400" class="t8v" border="0" cellpadding="4" cellspacing="1" align="center" bgcolor="#99cccc"><form action="{ACL}/?adr=menu" method="post" name="myform" id="myform"><input type="Hidden" name="arm" value="0"><input type="Hidden" name="action" value=""></form>
<tr bgcolor="White"><td> премещение пунктов =>></td><td align="center"><img src="img/up.gif" onclick="move_up(0)" ondblclick="move_up(0)" alt="вверх"></td></tr>
{row:MENU_M}
<tr id="tr{i}" bgcolor="White"><td id="men{i}"><a href="{ACL}/?adr=menu&top={I}" class="ver">{TITL}</a></td><td><input type="Radio" name="idr" onclick="selm({i})" id="rd{i}"></td></tr>
{/row}
<tr bgcolor="#ccffff"><td align="center"><input type="button" value="создать новый"  class="b8v" id="b_new" onclick="new_b()">&nbsp;&nbsp;<input type="button" value="удалить" class="b8v" id="b_del" onclick="del_b()">&nbsp;&nbsp;<input type="Button" value="сохранить"  class="b8v" id="b_save" onclick="save_b()"></td><td bgcolor="White" align="center"><img src="img/dn.gif" alt="вниз" onclick="move_up(1)" ondblclick="move_up(1)"></td></tr>
</table>
