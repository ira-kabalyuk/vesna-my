<form  action="{MOD_LINK}&act=save&el_id={EID}" method="post" enctype="multipart/form-data" target="upload">
{HIDDEN}
<table cellpadding="5">
<tr>
<td><img src="/{img}" /></td>
<td>
<p>Заменить фото <input type="file" name="photo"  id="file_upload" /></p>
<p>Комментарий<br />
<textarea name="descr" rows="4" cols="50">{descr}</textarea>
</p>
</td>
</tr>
</table>
<p><button class="sbut but-g" type="submit">Сохранить фото</button></p>
</form>
<script>
$(function(){
	Smart.makeAlign('#set-variants label');
});
</script>