<p><button class="sbut but-g dirs_meta">записать</button></p>
{UPLOADS}
<div id="dirs_edit">
<form action="{MOD_LINK}&act=save&el_id={EID}&pid={PID}" method="post">
<ul class="draglist">
<li><span class="w100">Название</span><span class="w200"><input type="text" value="{title}" name="title" /></span></li>
</ul>
<p>Метаданные </p>
<div class="accord">
{COM_META}
</div>
</form>

</div>
<p><button class="sbut but-g dirs_meta">записать</button></p>
<script type="text/javascript">
$(function(){
	$('.dirs_meta').on('click',function(){
	console.log('submit');
	Smart.submit({obj:'{DIV}',form:'#dirs_edit',loader:false});
	
	});
	Smart.makeAlign('.accord label','w');	
});
</script>