<div class="subcont">
	<h2>Лог paymaster</h2>
<div class="mt10">
{NEWSLIST}
</div>
</div>
<script>
$(function(){
	Smart.dataTable({ 
			table:"#newslist",
			modlink:"{MOD_LINK}",
			dataurl:"{MOD_LINK}&act=get_data",
			set:{"pageLength":50}
			 });


});
</script>