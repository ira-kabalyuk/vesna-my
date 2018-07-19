<div class="subcont">
<h1>Коментарии</h1>
</div>
<div id="newslist">
{ULLIST}
</div>
<div class="w700">{PAGINATOR}</div>
</div>

<script type="text/javascript">
$(function(){
	Smart.Tlist.makeBind({
		target:'#div_content', 
		div:'#newslist',
		url:'{MOD_LINK}',
		drag:false,
		callback:function(){

		}
		});

	$('.draglist li').on('click','.edit',function(){
		var id=$(this).parent().attr('lang');
		console.log(id);
		Smart.load_dialog({
			url:'{MOD_LINK}&act=edit&box=1&el_id='+id,
			w:500,
			h:300,
			ajform:true,
			cloase:true,
			title:"Редактирование комментария "+id
		});
	});
	
});
</script>
