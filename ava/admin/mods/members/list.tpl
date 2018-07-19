<div class="subcont">
	<h2>Members</h2>
<div><button class="sbut but-g" onclick="Smart.load_curl('#div_content','{MOD_LINK}&act=edit')">add members</button></div>
<div id="newslist" class="mt10">
{NEWSLIST}
</div>
</div>
<script>
$(function(){
	Smart.Tlist.makeBind({
		target:'#div_content', 
		div:'#newslist ul.draglist',
		url:'{MOD_LINK}&sub=rubric',
		drag:false
		});

});
</script>