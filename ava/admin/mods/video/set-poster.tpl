<div class="row" id="poster-row" data-vid="{vid}">
{row:Poster}
<span class="sprout-poster" data-src="{img}"><img src="{img}" class="img-responsive"></span>
{/row}

</div>
<form action="/sax/video/?act=set_poster&vid={vid}" method="post" class="ajax" data-target="#poster-row" id="poster-form">
	<input type="hidden" value="" name="poster">
	<input type="hidden" value="" name="poster_frame">
</form>
<script type="text/javascript">
	$(function(){
		$('#poster-row span').click(function(){
			var i=$('#poster-row span').index(this);
			$('#poster-row span').removeClass('active');
			$('#poster-form  input[name="poster"]').val($(this).data('src'));
			$('#poster-form  input[name="poster_frame"]').val(i);
			$(this).addClass('active');
		});


	});
</script>