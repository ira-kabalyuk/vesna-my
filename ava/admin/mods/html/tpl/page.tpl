
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<nav>
			<a href="/smart/?mod=html&aj=1">
		<h1 class="page-title txt-color-blue under">
			<i class="fa fa-leaf fa-fw "></i> 
				Страницы
		</h1></a>
		</nav>
	</div>

</div>



<form  action="{MOD_LINK}&act=save&pid={PID}&el_id={PAGE_ID}" class="ajax"  method="post" id="my_form">
<input type="hidden" name="actions" value="">
<div>
	<button type="button" name="actions" data-action="close" class="btn btn-primary _save">Сохранить и закрыть</button>
	<button type="button" name="actions" data-action="save" class="btn btn-warning _save ml20" >Сохранить и продолжить редактирование</button> 
	<span class="fr btn btn-warning" lang="{PAGE_ID}-{PID}" onclick="Mod_htm.GetHistory(this)" ><i class="pointer fa fa-lg fa-gear"  src="{AIN}/img/arch.png" title="История" ></i></span>
</div>

<div  class="mt20">
{FORM_CONTENT}
</div>

</form>

</div>
<script type="text/javascript">
pageSetUp();

var pagefunction = function() {
	runAllForms();
	console.log('run PAgeFunction');
	Mod_htm.PAGE_ID='{PAGE_ID}';
	Mod_htm.modlink='{MOD_LINK}';
	Mod_htm.PID='{PID}';
	//Smart.makeAlign('#my_form label');

	
		$( '.ckeditor2' ).ckeditor({ width:980, height:400,bodyClass:'service' });
		$('#my_form ._save').click(function(){
			$('#my_form input[name="actions"]').val($(this).data('action'));
			$('#my_form').submit();
		})

};



	loadScript("/smart/js/jquery.ajaxupload.js",function(){
	//loadScript("/smart/inc/ajaxupload.js",function(){
		loadScript("/smart/js/mods/html/mod_htm.js", pagefunction);
	});

					

</script>