
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-users fa-fw "></i> 
				Команда сайта :)
		</h1>
	</div>

</div>

<div><nav><a class="btn btn-primary" href="{MOD_LINK}&act=edit&aj=1">новый пользователь</a></nav></div>
<div class="mt10" id="userlist">
			<div class="tablebox no-margin no-padding">

						<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
							<thead>			                
								<tr>
									<th data-c="id">ID</th>
									<th data-c="name">Имя</th>
									<th data-c="login"><i class="fa fa-fw fa-user txt-color-blue text-muted hidden-md hidden-sm hidden-xs"></i>логин</th>
									<th data-c="last_login"><i class="fa fa-fw fa-calendar  hidden-md txt-color-blue hidden-sm hidden-xs"></i>last login</th>
									<th data-c="tool">Действия</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
	</div>
</div>

<script type="text/javascript">
	pageSetUp();

	$(function(){
		Smart.dataTable({ table:"#dt_basic",modlink:"{MOD_LINK}",dataurl:"{MOD_LINK}&act=get_data"});

	});
</script>
