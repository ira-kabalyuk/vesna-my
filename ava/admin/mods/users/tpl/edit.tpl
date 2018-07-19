
<div class="row">

		<h1 class="page-title txt-color-blueDark">
			<nav>
			<i class="fa fa-users fa-fw "></i> 
			<a href="{MOD_LINK}&aj=1">Команда сайта</a> 
			
				<span> &raquo; Профиль пользователя</span>
			</nav>
		</h1>
	

</div>
<div class="dcont">
<form action="{MOD_LINK}&el_id={EID}&act=save&aj=1" method="post" class="ajax">
<p><button class="btn btn-success" type="submit">сохранить</button></p>
<div id="useredit" class="smart-form p10">
{FIELDS}
</div>
</form>
</div>
<br />
<br />

<script type="text/javascript">
	
	
	$(function(){
		loadScript("/smart/js/plugin/bootstraptree/bootstrap-tree.min.js",function(){
			pageSetUp();
			runAllForms();
		});
	});
</script>