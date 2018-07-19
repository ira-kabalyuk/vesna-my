<div>
<button class="sbut but-g" onclick="Smart.load_curl('{DIV}','{MOD_LINK}&act=add')">добавить Справочник</button>
</div>

<div class="mt10" id="listmetadata">
{METALIST}
</div>

<script type="text/javascript">
$(function(){

	Smart.Tlist.makeBind({
		target:'{DIV}', 
		div:'#listmetadata .draglist',
		url:'{MOD_LINK}',
		drag:true
		});
		
$('#listmetadata a').click(function(){
	Smart.load_curl('#div_content','{MOD_LINK}&act=list_items&pid='+$(this).attr('rel'));
});		
			
});		
</script>

