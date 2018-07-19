
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-star fa-fw "></i> 
				Теги
		</h1>
	</div>

</div>


<div class="subcont well">
	
<div><nav><a class="btn btn-primary" href="{MOD_LINK}&sub=tags&act=edit&aj=1'"><i class="fa fa-plus fa-fw"></i> добавить тег</a></nav></div>
<div id="tagslist" class="mt10">
{NEWSLIST}
</div>
</div>
<script>
pageSetUp();

$(function(){
	Smart.dataTable({
		modlink:"{MOD_LINK}",
		dataurl:"{MOD_LINK}&sub=tags&act=get_data",
		table:"#ul_tags"
	});
});
</script>