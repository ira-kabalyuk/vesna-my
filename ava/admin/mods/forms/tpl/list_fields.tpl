<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-send-o fa-fw "></i> 
				Поля формы <span>{FORM_NAME}</span>
		</h1>
	</div>
</div>

<div class="well">
	<div><a href="{MOD_LINK}&type=fields&act=edit&el_id=0&fid={FID}" class="ajax-nav btn btn-primary">добавить поле</a></div>
{FIELDS_LIST}
</div>


<script type="text/javascript">


pageSetUp();



Smart.dataTable({
		table:"#ul_fields",
		modlink:"{MOD_LINK}&type=fields&fid={FID}",
		dataurl:"{MOD_LINK}&act=get_data&type=fields&fid={FID}",
		sort:true,
		set:{ paging:false }
	});

			
	
</script>