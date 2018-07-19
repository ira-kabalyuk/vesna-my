
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-th-list fa-fw "></i> 
				Рубрики
		</h1>
	</div>

</div>


<div class="subcont well">
	
<div><nav><a class="btn btn-primary" href="{MOD_LINK}&sub=rubric&act=edit&aj=1'"><i class="fa fa-plus fa-fw"></i> добавить рубрику</a></nav></div>
<div id="newslist" class="mt10">
{NEWSLIST}
</div>
</div>
<script>
pageSetUp();

$(function(){
	Smart.dataTable({
		modlink:"{MOD_LINK}&sub=rubric",
		dataurl:"{MOD_LINK}&sub=rubric&act=get_data",
		table:"#ul_rubric",
		sort:true
	});
});
</script>