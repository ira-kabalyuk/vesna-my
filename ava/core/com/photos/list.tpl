

<div class="well">
<div class="row">
	<div class="col-md-2"><button class="btn btn-primary _add">добавить фото</button></div>
</div>


<ul class="row ul-gallery"></ul>

</div>



<script type="text/javascript">

$(function(){
	
var pageFunctions=function(){
	Mods_photo.init({
		url:"{MOD_LINK}",
		div:"#news_photos .ul-gallery",
		btn_add:"#news_photos ._add",
		path:"/uploads/newsphoto/",
		set:{set_json},
		popup:true
	});
};

loadScript("/smart/js/mods/photo/photo.js",pageFunctions);

});

</script>