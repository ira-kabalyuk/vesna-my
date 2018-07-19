<div class="msg-form">
<p>Создано:{data_add}</p>
<form action="{MOD_LINK}&act=ban&aj=1" class="ajax" data-target="#msg-info-{id}">
	<input type="hidden" name="ip" value="{ip}"/>
<p>ip:{ip} <span>забанить ip <input type="checkbox" name="ban" value="1" {if:is_ban}checked{/if}/> <button class="btn btn-danger btn-xs ip-ban">применить</button></span></p>
<div id="msg-info-{id}"></div>
</form>
<p>страница:<a href="{url}" target="_blank">{url}</a></p>

<div>Содержание:</div>
<table class="tlist">
{row:INFO}
<tr><td>{title}:</td><td>{descr} </td></tr>
{/row}
</table>
</div>
