<h3>{MOD_NAME}</h3>
<p>
	<a class="btn btn-success" href="{MOD_LINK}&act=edit">Добавить фото</a>
	<button class="btn btn-yellow ml20" id="save-sort">Сохранить сортировку</button>
	<span class="ml20"> отборать по разделу:</span>
	<span><select class="w300" onchange="Smart.load_curl({url:'{MOD_LINK}&act=list&parent_id='+this.value,div:'#div_content'})"><option value="0">-- все --</option>{RUBRIC}</select></span>
</p>

<div id="foto_list">
{LISTS}
</div>
<div>{PAGINATOR}</div>
<script>
$(function(){
	/*
Smart.Tlist.makeBind({
				div:'#foto_list .draglist',
				target:'#div_content',
				url:'{MOD_LINK}',
				drag:false,
				parent:'li'
				});

			$('a.gall').prettyPhoto();
			*/
			$('#save-sort').click(function(){
			var sort=$('.draglist li .sort input').serialize();
			Smart.load_curl({ div:'#div_content', url:'{MOD_LINK}&act=save_sort',data:sort});
	});
		
				});
				
</script>
