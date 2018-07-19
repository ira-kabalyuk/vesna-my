<div>
<p>Справочник {TITLE}</p>
<button class="sbut but-g" onclick="Smart.load_curl('{DIV}','{MOD_LINK}&act=add&pid={PID}')">добавить элемент справочника</button>
</div>

<div class="mt10" id="listmetadata">
{METALIST}
</div>

<script type="text/javascript">
$(function(){

	Smart.Tlist.makeBind({
		target:'{DIV}', 
		div:'#listmetadata .draglist',
		url:'{MOD_LINK}&pid={PID}',
		drag:true
		});
		
			
});		
</script>

