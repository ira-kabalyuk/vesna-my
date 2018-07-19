<script language="JavaScript" src="/inc/menu.js" type="text/javascript"></script>
<div style="dilsplay:block;height:auto; width:200px;float:left;">
<p style="text-align:left;">
{row:LISTM}<a href="{ACL}/?adr=mmenu&sid={K}">меню {K}</a><br/>{/row}
{if:KL}<a href="{ACL}/?adr=mmenu&sid={KL}"><b>добавить  новое меню {KL}</b></a>{/if}
</p>

<form action="{ACL}/?adr=mmenu&sid={PID}" method="post" name="myform" id="myform">
<input type="hidden" name="arm" value="0" />
<input type="hidden" name="action" id="action" value="" />
</form>
</div>


<div style="dilsplay:block;height:auto; width:600px;float:left;">
<h2 align="center" class="titl"><font size="+1">MENU {PID}</font></h2>
<form action="{ACL}/?adr=mmenu&sid={PID}" method="post" name="fotoform" id="fotoform" enctype="multipart/form-data">
<input type="hidden" name="action" value="save_foto" />
<p style="text-align:center;">картинка меню 
<img src="/images/menu_{PID}.jpg" /><br/>
<input type="checkbox" name="delfoto" value="1" /> удалить фото
<br/><input type="file" name="menuimg" /> <input type="submit" value="OK" /></p>
</form>
<table width="400" class="t8v" border="0" cellpadding="4" cellspacing="1" align="center" bgcolor="#99cccc">
<tr bgcolor="White">
<td> премещение пунктов </td>
<td align="center"><img src="img/up.gif" onclick="move_up(0)" ondblclick="move_up(0)" alt="вверх" /></td>
</tr>
{row:MENU_M}
<tr id="tr{i}" bgcolor="White">
<td id="men{i}"><a href="{ACL}/?adr=mmenu&sid={PID}&top={I}" class="ver">{TITL}</a></td>
<td><input type="Radio" name="idr" onclick="selm({i})" id="rd{i}" /></td>
</tr>
{/row}
<tr bgcolor="#ccffff">
<td align="center"><input type="button" value="создать новый"  class="b8v" id="b_new" onclick="new_b()"/>&nbsp;&nbsp;<input type="button" value="удалить" class="b8v" id="b_del" onclick="del_b()"/>&nbsp;&nbsp;<input type="Button" value="сохранить"  class="b8v" id="b_save" onclick="save_b()"/></td>
<td bgcolor="White" align="center"><img src="img/dn.gif" alt="вниз" onclick="move_up(1)" ondblclick="move_up(1)"/></td>
</tr>
</table>
</div>

