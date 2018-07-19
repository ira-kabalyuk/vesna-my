<div>
<nav><a href="{MOD_LINK}&type=tpl" >шаблоны</a><a href="{MOD_LINK}&type=css" class="ml20">CSS</a> <a href="{MOD_LINK}&type=js" class="ml20">JS</a> <span class="sbut but-g ml20" onclick="makeFile();" id="mkfile">создать файл</span></div>
<div id="newfile"></nav>
<p align="center" class="titl"><font size="+1">{PATH}</font></p>
<div class='well'>
<ul class="filelist">
{row:KONTM}<li><a href="{MOD_LINK}&type={TYPE}&tpl={NAME}&aj=1" class="ajax-nav"><span class="inline w200">{NAME}</span><span class="c-gr">{DESCR}</span></a></li>{/row}
</ul>
</div>
</div>
<script type="javascript/template" id="new_file-tpl">
	<p>имя файла: <input type="text" class="w200" value=""> <button class="sbut but-y">сохранить</button></p>
	<p id="error" class="mark"></p>	
</script>
<script type="text/javascript">
function makeFile(){
	$('.mkfile').hide();
	$($('#new_file-tpl').html()).appendTo('#newfile');
	$('#newfile button').click(function(){
		$.ajax({
			url:'{MOD_LINK}&act=new&aj=1',
			type:'post',
			dataType:'json',
			data:{'name':$('#newfile input').val()},
			success:function(d){
				if(d.ok){
					//console.log(d);
					window.location.hash='{MOD_LINK}&type='+d.type;

			}else{
				$('#error').html(d.msg);
				}
			}
		})
	});
}
</script>

