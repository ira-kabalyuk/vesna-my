<div class="subcont">
	<div>
<button class="sbut but-g" onclick="Smart.load_curl('{DIV}','{MOD_LINK}&act=add&parent_id={PID}')">добавить поле метаданных</button>
</div>

<div class="mt10" id="listmetadata">
{METALIST}
</div>
</div>
<script type="text/javascript">
$(function(){

	Smart.Tlist.makeBind({
		target:'{DIV}', 
		div:'#listmetadata .draglist',
		url:'{MOD_LINK}',
		drag:true
		});
			
});		
</script>

