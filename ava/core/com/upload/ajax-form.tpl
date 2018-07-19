
<figure class="{class}">
<div id="upload-{div}" class="ajaxwrapper" data-json='{json}'>
	<div class="promptzone mt20" title="click to upload image" >
		{ifv:im}<img src="/{path}/{img}" class="img-responsive">{/ifv}
		{ifv:fl}<a href="{path}/{img}" target="_blank" class="ico ico-{ext}">{img}</a>{/ifv}
		
	</div>
	<div class="promzone">
		<p style="padding:10px">
		Загрузка изображения или файла
		</p>
	</div>
	<div class="pro-gress progress-sm">
	<div class="progress progress-sm"><div role="progressbar" class="progress-bar bg-color-greenLight"></div></div>
	</div>
	<div id="result"></div>
	<input type="hidden" name="{div}" class="hidden" value="{img}">
</div>
</figure>
{ifv:crop}<div><button class="btn btn-primary btn-xs" id="crop-{div}" type="button"><i class="fa fa-crop fa-fw"></i> обрезка</button></div>{/ifv}
<script>
$(function(){
	loadScript("/smart/js/jquery.ajaxupload.js", function(){
		loadScript("/smart/js/plugin/jcrop/js/jquery.Jcrop.min.js?v=1.3", function(){

				setTimeout(function(){PromptUpload('#upload-{div}')},400);
			});
		});
});
</script>







