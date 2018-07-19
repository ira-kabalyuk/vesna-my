{if:EID}<div class="butp" id="{TARGET_DIV}_photopanel"><button class="sbut but-y add_photo">добавить изображение</button></div>{/if}
{if:!EID}Загрузка изображений доступна после сохранения {/if}
<br />
<div id="{TARGET_DIV}">
{external:EXT_PHOTOS}
</div>
<div id="{TARGET_DIV}_uploads">
<form name="myform" class="form_upload" action="{MOD_LINK}&act=upload" method="post" enctype="multipart/form-data" target="upload"><input type="file" name="photo" class="file_upload none" />
{HIDDEN}
</form>

</div>

<iframe name="upload" id="upload" width="200" height="10" frameborder="0" scrolling="no"></iframe>
<script>


$('#{TARGET_DIV}_photopanel .add_photo').click(function(){
	$('#{TARGET_DIV}_uploads .file_upload').trigger('click');
});
$('#{TARGET_DIV}_uploads .file_upload').bind('change',function(){
	$('#progress_upload').remove();
	$('<span clas="pad20" id="progress_upload"> Загрузка изображения : '+$(this).val()+'<span class="pad20"><img src="/skin/admin/img/ajax-loader.gif"></span></span>').appendTo('#{TARGET_DIV}_photopanel');
	$('#{TARGET_DIV}_uploads .form_upload').submit();
});


</script>