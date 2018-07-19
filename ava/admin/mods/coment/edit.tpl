<h1> &lang; <a href="{MOD_LINK}">{MOD_TITLE}</a></h1>
<div>
<p>Коментарий к посту: {title}</p>
<p>Пользователь: {user_name}</p>
<p>Город: {city}</p>
<p>Email: {email}</p>
<form action="{MOD_LINK}&page={PGL}&el_id={id}&act=save" method="post">
<input type="hidden" name="actions" value="update">
<div>текст комментария</div>
<textarea name="descr" rows="5" cols="60">{descr}</textarea>
<div class="mt20">ответ</div>
<textarea name="answer" rows="5" cols="60">{answer}</textarea>
<p class="butpanle"><input type="submit" name="submit" class="sbut but-g" value="сохранить">&nbsp;&nbsp;<input type="button" value="отмена" class="sbut but-r fr" onclick="Smart.hide_box('#ajbox')"></p>
</form>

</div>
