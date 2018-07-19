
<figure class="{class}">
<div id="upload-{div}" class="ajaxwrapper" data-json='{json}'>
	<div class="promptzone mt20" title="click to upload image" >
		{ifv:img}<img src="/{path}/{img}" class="img-responsive">{/ifv}
		
	</div>
	<div class="promzone">
		<div class="btn">Прикрепить фото... <b>+</b></div>
	</div>
	<div class="pro-gress progress-sm">
	<div class="progress progress-sm"><div role="progressbar" class="progress-bar bg-color-greenLight"></div></div>
	</div>
	<div id="result"></div>
	<input type="hidden" name="{div}" class="hidden" value="{img}">
</div>
</figure>
{if:crop}<div><button class="btn btn-primary btn-xs" id="crop-{div}" type="button"><i class="fa fa-crop fa-fw"></i> обрезка</button></div>{/if}
<script>
$(function(){
	loadScript("/smart/js/jquery.ajaxupload.js", function(){
		loadScript("/smart/js/plugin/jcrop/js/jquery.Jcrop.min.js", function(){

				setTimeout(function(){PromptUpload('#upload-{div}')},400);
			});
		});
});
</script>









