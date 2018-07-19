
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa {fa_class} fa-fw "></i> 
				{MOD_TITLE}
		</h1>
	</div>

</div>


<div><button class="btn btn-success" data-role="savepost">сохранить {MOD_TITLE}</button></div>
<div class="subcont">
	
<div id="news_photos" class="mt20">{NEWSIMG}</div>
<form action="{MOD_LINK}&aj=1&act=save&el_id={EID}" method="post" id="newsform" class="ajax">
	<input type="hidden" name="rubr_id[{RID}]" value="{RID}">
	{FORM_FIELDS}

</form>

{META_FIELDS}
</div>
<script>
pageSetUp();
var pagefunction=function(){
runAllForms();

Smart.makeTab('.subcont','main');
$(".datepicker").datepicker();
$('button[data-role="savepost"]').click(function(){$('#newsform').submit();});
	$( '.ckeditor2' ).ckeditor({ width:960, height:400,bodyClass:'w900 content' });
};

$(function(){
	pagefunction();
});



</script>