
<div id="upload-{div}" class="sprout">
	
<div  class="w300 ajaxwrapper" >
	<div class="promptzone mt20" title="click to upload image" >
		
	</div>
	<div class="promzone">
		<p class="pt10 ac">
		Перетащите видео в это поле для загрузки изображений 

		<br/>
		или кликните для выбора файла на вашем копьютере 
		</p>
	</div>
	<div class="pro-gress progress-sm">
	<div class="progress progress-sm"><div role="progressbar" class="progress-bar bg-color-greenLight"></div></div>
	</div>
	<div id="result"></div>
	<div class="video-tool">
		
		<span><a rel="setposter" href="/sax/video/?act=get_poster&vid={sid}" class="btn btn-xs btn-success ml20" data-title="Выбор постера"> Выбрать постер </a> </span>
	<!--
		<span rel="uploadposter"><a href="/sax/video/?act=upload_poster&vid={sid}" class="btn btn-xs btn-primary ml20" data-toggle="modal" data-target="#remoteModal">Загрузить постер</a> </span>
	-->
	<span rel="delvideo"><a href="/sax/video/?act=delete_video&vid={sid}" class="btn btn-danger ml20 btn-xs" data-title="Удаление видео" data-target="#upload-{div}">Удалить видео</a></span>
	</div>
	<input type="hidden" name="up{div}" class="hidden" value="{img}">
</div>
</div>
<script>
$(function(){
	
	loadScript("/js/jquery.ajaxupload.js", function(){
		VideoUpload({ 
			name:"{div}",poster:"{poster}", 
			video:"{video}", sid:"{sid}", 
			token:"{token}", type:"{type}"
		});
	});
});
</script>