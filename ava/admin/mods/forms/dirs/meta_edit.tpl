<div id="dirs_edit">
<form action="{MOD_LINK}&act=save&el_id={EID}" method="post">
<ul class="draglist">
<li><span class="w100">Название</span><span class="w200"><input type="text" value="{title}" name="title" /></span></li>
</ul>
</form>
<p>Метаданные справочника {title}</p>
<div id="metadirs" style="padding:10px; background:white;margin:10px;">

{COM_META}
</div>

</div>
<p><button class="sbut but-g" id="dirs_meta">записать</button></p>
<script type="text/javascript">
$(function(){
	$('#dirs_meta').on('click',function(){
	console.log('submit');
	Smart.submit({obj:'{DIV}',form:'#dirs_edit',loader:false});
	});	
});
</script>