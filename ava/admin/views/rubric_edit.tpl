<p>Название рубрики {RUID}</p>
<form action="{MOD_LINK}&rubr_id={RUID}&act=save_rubr">
<table class="tlist" cellpadding="3" cellspacing="0">
{row:INP}<tr><td>{lang}</td><td><input type="text" class="w300" value="{TITLE}" name="title_{ln}" /></td></tr>{/row}
<tr><td>link</td><td><input type="text" name="link" value="{link}" /></td></tr>
</table>

<p><button type="button" onclick="msend_form(this)">Записать</button></p>
</form>
