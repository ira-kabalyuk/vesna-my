<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-gear fa-fw "></i> 
				Настройки формы
		</h1>
	</div>
</div>

<div class="well">
	<div class="row">
		<form action="{MOD_LINK}&type=form&act=save&aj=1&el_id={EID}" class="ajax" method="post">
			<div class="smart-form">
			{FORM_FIELDS}
			</div>

			<div class="mt20"><button class="btn btn-success" type="submit">Сохранить</button></div>
		</form>
	</div>
</div>

<script type="text/javascript">

pageSetUp();
var pagefunction=function(){
runAllForms();

};

$(function(){
	pagefunction();
});
</script>	
