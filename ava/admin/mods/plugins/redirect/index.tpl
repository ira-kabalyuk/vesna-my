<h2><a href="{MOD_LINK}">Список ссылок редиректа брендов</a></h2>

<div><button class="sbut but-g" id="crossadd">добавить новый</button></div>
<br />

<div id="crosslink">
{LISTS}
</div>


<script>
$(function(){
	Smart.Tlist.makeBind({
		target:'#div_content', 
		div:'#crosslink',
		url:'{MOD_LINK}',
		drag:false
		});

$('#crossadd').click(function(){
	Smart.load_curl({url:'{MOD_LINK}&act=edit',div:'#div_content'});	
});
});
</script>
