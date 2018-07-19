<p align="center" class="titl"><font size="+1">Модули</font></p>
<div align="center">
<form action="?adr=ext" method="post">
<table>
<tr><td width="10">id</td><td>название</td><td>папка</td></tr>
{row:LINE}
<tr><td><input type="Text" name="id[{i}]" value="{id}"></td><td><input type="Text" name="name[{i}]" value="{name}" size="40"></td><td><input type="Text" name="fold[{i}]" value="{fold}" size="20"></td></tr>
{/row}
<th colspan="3">новый модуль</th>
<tr><td><input type="Text" name="id[]" value=""></td><td> <input type="Text" name="name[new]" value="" size="40"></td><td><input type="Text" name="fold[new]" value="" size="20"></td></tr>
<tr><td colspan="3" align="center"><input type="Submit" value="save" name="actions"></td></tr>
</form>
</table>
</div>