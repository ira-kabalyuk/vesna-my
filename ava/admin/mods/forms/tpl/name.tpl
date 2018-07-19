<form action="{MOD_LINK}&act=save_name&el_id={id}" method="post" class="ajax">
<p>имя формы: <input type="text" name="title" class="w300" value="{title}" /></p>
<p>E-mail: <input type="text" name="email" class="w300" value="{email}" /></p>
<p>шаблон: <input type="text" name="tpl" class="w200" value="{tpl}" /></p>
<p>шаблон после отправки: <input type="text" name="tpl_ok" class="w200" value="{tpl_ok}" /></p>
<p>Отправлять письмо пользователю <input type="checkbox" value="1" name="is_mail" {is_mail}/> </p>
<p>шаблон письма: <input type="text" name="tpl_mail" class="w200" value="{tpl_mail}" /></p>
<h3>Настройки SMS - уведомлений</h3>
<p>Отправлять смс клиенту<input type="checkbox" value="1" name="is_sms" {is_sms}/> </p>
<p>Переменные шаблона: [ID]- номер заказа</p>
<p>SMS-сообщение клиенту: <br/><textarea name="sms" rows="4" cols="40">{sms}</textarea></p>
<p>Отправлять смс администратору<input type="checkbox" value="1" name="is_asms" {is_asms}/> </p>
<p>SMS-сообщение администратору: <br/><textarea name="sms_a" rows="4" cols="40">{sms_a}</textarea></p>
<p>телефон администратора(+380xxxxxxxxx): <input type="text" name="tel_admin" class="w200" value="{tel_admin}" /></p>

<p><button type="submit" class="btn btn-success">Записать</button></p>
</form>