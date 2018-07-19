<div id="meta_edit">
<form action="{MOD_LINK}&act=save&el_id={EID}&parent_id={PID}" method="post">
<div>
Название  <br />
<input type="text" value="{title}" name="title" />
</div>
<br />
<div> тип поля для редактирования<br />
{KATSELECT}
</div>

</form>
<p><button class="sbut but-g" id="save_meta">записать</button></p>

</div>
<script type="text/javascript">
$(function(){
	$('#save_meta').on('click',function(){
	Smart.submit({obj:'{DIV}',form:'#meta_edit',loader:false});
	});	
});
</script>