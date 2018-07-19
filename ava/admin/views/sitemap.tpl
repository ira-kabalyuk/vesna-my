<div style="padding-left:30px;">
<h2>Генерация sitemap.xml </h2>
<p>выберите страницы, разделы каталога, которые нужно включить в sitemap</p>
<form method="POST" target="tagwin">
<input type="hidden" name="act" value="go">
<table cellpadding="2" cellspacing="1" bgcolor="#C3C3C3" style="font-size:9pt;">
<tr bgcolor="#FFD4AA"><td>name</td><td>tpl</td><td>key</td><td>link</td><td>sitemap</td><td>обновление</td></tr>
{row:PAGE}
<tr bgcolor="white"><td>{name}</td><td>{tpl}</td><td>{key}</td><td><a href="/{link}">{link}</a></td><td><input type="checkbox" value="{i}" name="insp[]" {if:A}checked{/if}></td><td>{SELUP}</td></tr>
{/row}
</table>
<p>Имя файла:<input type="text" value="{FNAME}" size="34" name="fname"> &nbsp;<input type="submit" value="создать sitemap"></p>
</form>
<br>
<br>
<iframe name="tagwin" width="500" height="200"></iframe>

</div>