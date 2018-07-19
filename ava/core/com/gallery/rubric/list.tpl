<h3>{MOD_NAME}</h3>
<div><button class="btn btn-success" onclick="Smart.load_curl('#div_content','{MOD_LINK}&sub=rubric&act=edit')">добавить раздел</button></div>
<div id="newslist" class="mt10">
{NEWSLIST}
</div>
<script>
$(function(){
	Smart.Tlist.makeBind({
		target:'#div_content', 
		div:'.draglist',
		url:'{MOD_LINK}&sub=rubric',
		drag:true
		});

});
</script>