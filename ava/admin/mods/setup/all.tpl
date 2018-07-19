<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-gear fa-fw "></i> 
				Настройки
		</h1>
	</div>

</div>


<div id="modsetup_form" class="well">

	<div role="content">
<form action="{MOD_LINK}&act=save&mod_setup=1&aj=1" method="post" class="ajax">
<input type="hidden" value="1" name="save" />
<div id="ajform" class="smart-form">
{FORM_CONTENT}
</div>
<p align="center" class="mt20"><button class="btn btn-success" type="submit">Сохранить настройки</button></p>
</form>
</div>
<script>
pageSetUp();

</script>
</div>
