<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-leaf fa-fw "></i> 
				Страницы
		</h1>
	</div>

</div>
<div class="row">
	<div class="col-xs-12">
	<nav>
	<a href="{MOD_LINK}&act=edit&aj=1" class="btn btn-primary">создать новую страницу</a>
	</nav>
</div>
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
									<th data-c="link"><i class="fa fa-fw fa-link txt-color-blue text-muted hidden-md hidden-sm hidden-xs"></i>Ссылка</th>
									<th data-c="date_add"><i class="fa fa-fw fa-calendar  hidden-md txt-color-blue hidden-sm hidden-xs"></i>Изменено</th>
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

	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

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

		
		/* // DOM Position key index //
		
			
		*/	
		Mod_htm.modlink="{MOD_LINK}";
		
		
			Smart.dataTable({
				modlink:"{MOD_LINK}",
				dataurl:"{MOD_LINK}&act=get_data",
				table:"#dt_static",
				sort:true,
				del_msg:"Если Вы хотите удалить страницу, нажмите <b>Да</b>."
			});

		

	};

	// load related plugins
	
	
	loadScript("/smart/js/mods/html/mod_htm.js", pagefunction);
					


</script>
