<h3>Файловый менеджер</h3>

	<p>загрузить новый файл</p>
<form name="myform" class="form_upload" action="{MOD_LINK}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="upload"/>
	<div><input type="file" name="file" class="file_upload" /> <input type="submit" value="загрузить" class="ml20 sbut but-g"/></div>
	

<br> 
</form>
<div class="w960">



<table class="filelist">
{row:KONTM}<tr>
<td><a href="{link}" title="скачать" target="_blank"><span class="inline w200 name">{NAME}</span></a></td>
<td><span class="c-gr"><input type="text" class="w400" value="{link}"></span></td>
<td class="tools"><span class="ico ico_del" title="удалить"></span></td>
</tr>{/row}
</table>

</div>
<form id="fman-form" method="post" action="{MOD_LINK}">
<input type="hidden" name="action" value="delete"/>
<input type="hidden" name="fname" value=""/>
</form>

<script type="text/javascript">
$(function(){

	var delete_file=function(name){
		var dform=$('#fman-form');
		dform.find('input[name="fname"]').val(name);
		dform.submit();
	};

	$('.filelist tr').each(function(){
		var name=$(this).find('.name').text();
		$(this).find('.ico_del').click(
			function(){
		Smart.promptBox({ 
			title:"Удаление файла ",
			data:"Вы действительно хотите удалить файл <span class='red'>" + name+"</span> ? ",
			callback:function(){delete_file(name);}
			});
	});
	});
});
</script>

