{if:EID}
<div class="none" id="add-dialog-tpl">
<form name="myform" class="form_upload" action="{MOD_LINK}&act=upload" method="post" enctype="multipart/form-data" target="upload">
	<p>загрузка нового изображения</p>
	<div><input type="file" name="photo" class="file_upload" /></div>
	<div class="mt10"><textarea name="descr" rows="4" cols="50" placeholder="комментарий к изображению" class="w300">{descr}</textarea></div>
{HIDDEN}
<div class="mt10"><input type="submit" value="загрузить" class="sbut but-g"/> <button type="button" class="sbut but-r fright">отмена(Esc)</button> </div>	

</form>
</div>
{/if}
{if:!EID}Загрузка изображений доступна после сохранения {/if}
<div id="{TARGET_DIV}">
{external:EXT_PHOTOS}
</div>


<iframe name="upload" id="upload" width="200" height="10" frameborder="0" scrolling="no"></iframe>
<script>
$(function(){
	var showDialog=false;
var createDialog=function(){
if(showDialog) return;
		showDialog=true;
	$(addf).css('opacity',0.3);
	var dialog=$('#add-dialog-tpl').dialog({
		title:"Добавление изображения в галерею",
		width:400,
		open: function(){var self=this;$(this).find('.but-r').click(function(){$(self).dialog('close');});}
	});
	dialog.on('dialogclose',function(){
		console.log('close');
		$(addf).css('opacity',1);
		showDialog=false;
	});
	};

var addf=$('#{TARGET_DIV} #add_li').click(function(){
	createDialog();
});
$('ul.gallery').on('change',function(){
	console.log('ul modify');
});
$('#{TARGET_DIV}_photopanel .add_photo').click(function(){
	//$('#{TARGET_DIV}_uploads .file_upload').trigger('click');
	//Smart.load_dialog({ url:{MOD_LINK}&act=upload})
});
$('#{TARGET_DIV}_uploads .file_upload').bind('change',function(){
	$('#progress_upload').remove();
	$('<span clas="pad20" id="progress_upload"> Загрузка изображения : '+$(this).val()+'<span class="pad20"><img src="/skin/admin/img/ajax-loader.gif"></span></span>').appendTo('#{TARGET_DIV}_photopanel');
	$('#{TARGET_DIV}_uploads .form_upload').submit();
});

});
</script>
