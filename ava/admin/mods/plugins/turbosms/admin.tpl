<div>
<p>Отправка тестового сообщения</p>
<form method="POST" action="{MOD_LINK}&act=send">
<p>Номер(+380ХХХХХХХХХ) <input type="text" value="{phone}" name="phone" /></p>
<div><p>Текст сообщения</p>
<textarea name="text" rows="4" cols="50">{text}</textarea>
</div>
<p><input type="submit" value="Отправить" /></p>
</form>

</div>
<div class="log">{LOG}</div>