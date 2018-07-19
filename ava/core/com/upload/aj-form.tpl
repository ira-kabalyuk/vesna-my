<span class="div-upload" id="upload-{div}" data-div="{div}" data-link="{link}" data-pid="{pid}">
  <span  class="uploadImage" data-json='{json}'><img src="/{img}" width="100" class="img-thumbnail" data-img="{orign}" data-path="{path}" /></span><span class="ml20"><button class="btn btn-primary btn-xs" type="button">загрузить</button></span><span class="info ml20"></span></span>

<script>
  $(function(){ 
  	loadScript("/smart/js/plugin/jcrop/js/jquery.Jcrop.min.js", function(){
  		loadScript("/smart/inc/ajaxupload.js", function(){
		
  			setTimeout(function(){	Smart.bindUpload('#upload-{div}');},400);
  			});
		});

  	 });
</script>