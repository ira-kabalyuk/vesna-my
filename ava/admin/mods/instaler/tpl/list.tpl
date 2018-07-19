<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-leaf fa-fw "></i> 
				Инсталлятор модулей
		</h1>
	</div>

</div>

<div class="row">
	<div class="col-xs-12">
	<nav>
	<a href="{MOD_LINK}&act=install&aj=1" class="btn btn-primary">Добавить модуль</a>
	</nav>
</div>
</div>


<div class="row">
<div class="col-xs-12"><div class="h3">Установленные модули</div></div>
</div>

	<!-- row -->
	<div class="row mt20">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="tablebox no-margin no-padding">
			
				<div class="table-responsive">
						<table id="dt_static" class="table table-striped table-bordered table-hover" width="100%">
							<thead>			                
								<tr>
									<th data-c="id">ID</th>
									<th data-c="title">Название</th>
									<th data-c="version">версия</th>
									<th data-c="link">ссылка</th>
									<th data-c="date_add"><i class="fa fa-fw fa-calendar  hidden-md txt-color-blue hidden-sm hidden-xs"></i>обновление</th>
									<th data-c="tool">Действия</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						</div>
			</div>	



	


		</article>
		<!-- WIDGET END -->

	</div>




	<div class="row">
<div class="col-xs-12"><div class="h3">Доступные модули</div></div>
</div>

	<!-- row -->
	<div class="row mt20">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="tablebox no-margin no-padding">
			
				<div class="table-responsive">
						<table id="dt_source" class="table table-striped table-bordered table-hover" width="100%">
							<thead>			                
								<tr>
									<th data-c="title">Название</th>
									<th data-c="version">версия</th>
									<th data-c="descr">Описание модуля</th>
									<th data-c="tool">Действия</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						</div>
			</div>	



	


		</article>
		<!-- WIDGET END -->

	</div>

	<!-- end row -->

	<!-- end row -->


<script type="text/javascript">

	
	pageSetUp();
	
	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 * 
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 * 
	 */
	
	// PAGE RELATED SCRIPTS
	
	// pagefunction	
pagefunction=function() {
		console.log("cleared");

	
			Smart.dataTable({
				modlink:"{MOD_LINK}",
				dataurl:"{MOD_LINK}&act=get_data",
				table:"#dt_static",
				sort:false,
				del_msg:"Если Вы хотите удалить модуль, нажмите <b>Да</b>.",
				tool:['o','s']
			});

		

	};

	// load related plugins
	pagefunction();
	
	
					


</script>
