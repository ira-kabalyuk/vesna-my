<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa {fa_class} fa-fw "></i> 
				{MOD_TITLE} <span>- &raquo;</span><span><a href="{MOD_LINK}" class="ajax-nav">Все видео</a></span>
		</h1>
	</div>
</div>
<div class="well">	


<div class="alert alert-warning"><i class="fa-fw fa fa-warning"></i> Для корректного размещения видео просто скопируйте ссылку из адресной строки браузера со страницы нужного вам видео на сайте youtube.com</div>

<div class="youtube">
<a href="{link}"><img src="{img}" width="200" alt=""></a>
</div>

<form name="myform" action="{MOD_LINK}&el_id={FID}&act={ACTIONS}&aj=1" id="fotoforms" method="post" class="ajax">
<input type="hidden" name="youtube" value="1">
<div class="row">
{FORM_FIELDS}
</div>
<div class="mt20"><button class="btn btn-success">Сохранить</button></div>
</form>


</div>
<script type="text/javascript">
pageSetUp();
</script>
