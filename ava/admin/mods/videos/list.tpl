
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa {fa_class} fa-fw "></i> 
				{MOD_TITLE}
		</h1>
	</div>
</div>



<div class="well">
<div class="row">
	<div class="col-md-2"><nav><a class="btn btn-primary"  href="{MOD_LINK}&act=add&aj=1">добавить видео</a></nav></div>
	<span class="col-md-2">Отобрать по разделу</span>
	<div class="col-md-4"> <select class="form-control" id="cat_select"></select></div>
</div>


<ul id="gallery" class="row ul-gallery"></ul>
<div class="row">
<div id="sboxgallery" class="superbox col-sm-12"></div>
</div>
<div class="superbox-show" style="height:300px; display: none"></div>

</div>



<script type="text/javascript">
pageSetUp();

var pageFunctions=function(){
	Mods_photo.init({
		url:"{MOD_LINK}",
		div:"#gallery",
		//item:'<div class="superbox-list"><img src="[link]" alt="2" class="superbox-img" title="1"></div>',
		cat_select:'#cat_select'
	});

	setTimeout(function(){
		$( "#gallery" ).sortable({
			update: function( event, ui ) {
			var ids = $( "#gallery" ).sortable( "toArray", {attribute:'rel'} );
			console.log(ids);
			$.ajax({
				url:"{MOD_LINK}&act=save_sorts",
				type:"post",
				data:{sort:ids.join(",")}
			});
		}});
		
	},1000);
};
//loadScript("/smart/js/plugin/superbox/superbox.min.js",function(){
	loadScript("/smart/js/mods/photo/photo.js?v=1.2",pageFunctions);
//});

</script>