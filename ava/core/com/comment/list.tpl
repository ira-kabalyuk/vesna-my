<div class="comlist">
<p>Комментарии: <span class="href pl20" onclick="Com_show()">добавить комментарий</span></p>
<div style="display:none;" id="add_comment">
<div>

<textarea name="comment" rows="4" cols="40"></textarea>

</div>
<div><br />
<button class="sbut but-y none" type="button" onclick="Com_add(this)">сохранить комментарий</button>
<br />
</div>
</div>
<div class="comcontent">
{COMLIST}
</div>
</div>
<script>
function Com_show(){
	$('#add_comment').slideDown(300,function(){$('#add_comment button').show();});
}
function Com_add(obj){
	$(obj).hide();
	var loader=$('<img src="'+Smart.loader_src+'">').appendTo($(obj).parent());
	$.ajax({
	url:'{MOD_LINK}',
	data:$('#add_comment textarea').serialize(),
	type:'POST',
	success:function(d){
		loader.remove();
		$('#add_comment texarea').val('');
		$('#add_comment').slideUp(200,function(){$(d).prependTo('.comcontent');});
		
	}	
	});
}
</script>