<div class="metafield">
<span><select name="type" class="ftype">
<option value="text">Текст</option>
<option value="textarea">Текстовый блок</option>
<option value="editor">Текстовый блок(редактор)</option>
</select>
</span>
<span class="next"></span>
</div>
<script type="text/javascript">
$(function(){
	var TPL={
		'text':'<select name="class"><option value="w30">30px</option><option value="w50">50px</option><option value="w100">100px</option><option value="w200">200px</option><option value="w300">300px</option><option value="w400">400px</option></select>',
		'textarea':'<span>колонок: <input type="text" class="w30" value="60" name="cols"></span><span>строк: <input type="text" value="5" name="rows" class="w30"></span>',
		'editor':'<span>ширина:<input type="text" name="w" value="500" class="w30"></span><span>высота:<input type="text" name="h" value="300" class="w30"></span><span>панель:<select name="toolbar"><option value="Basic">Сокращенная</option><option value="Default">Полная</option></select></span>'
	};
	var Vars=$.parseJSON('{JSON}');
	$('.metafield select.ftype').bind('change',function(){
		console.log(this.value);
		if(this.value in TPL){
			$('.metafield .next').html(TPL[this.value.toString()]);
			if(Vars!=null){
				$('.metafield .next').find('input,select').each(function(){
				if(this.name in Vars) this.value=Vars[this.name];	
				});
			}
		
		}
	}).val('{TYPE}').trigger('change');
	
	console.log(Vars);
});
</script>