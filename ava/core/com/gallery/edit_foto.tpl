<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa {fa_class} fa-fw "></i> 
				{MOD_TITLE} <span>- &raquo;</span><span><a href="{MOD_LINK}" class="ajax-nav">Все фото</a></span>
		</h1>
	</div>
</div>
<div class="well">	


<p>Рекомендуемый размер фото {MAXX} х {MAXY} </p>

<form name="myform" action="{MOD_LINK}&el_id={FID}&act={ACTIONS}&aj=1" id="fotoforms" method="post" class="ajax">

<div class="row">
{FORM_FIELDS}
</div>
<div class="mt20"><button class="btn btn-success" >Сохранить</button></div>
</form>


</div>
<script type="text/javascript">
pageSetUp();
</script>
