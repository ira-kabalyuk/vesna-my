
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa {fa_class} fa-fw "></i> 
				{MOD_TITLE}
		</h1>
	</div>

</div>

<div class="subcont mt20 well">

<div><button class="btn btn-primary"  id="but-add">добавить Отзыв</button></p>
</div>
<div id="newslist">
<div class="tablebox no-margin no-padding">
{ULNEWS}
</div>
</div>

</div>

<!-- dialog template-->
<div class="modal" id="add-new-dialog">
<form action="{MOD_LINK}&aj=1&act=save_new&el_id={EID}" method="post" class="ajax">
	<input type="hidden" name="rubr_id[{RID}]" value="{RID}">
<div class="mt20"></div>
	<div class="mt20"> <input type="text" name="title" class="w400" placeholder="Название Акции" ></div>
<div class="mt20">
<button class="btn btn-success fl" type="submit">добавить</button> <!--button class="sbut but-r fr close" type="button">отмена</button-->
</div>	
</form>
</div>

<script>
pageSetUp();



	$(function() {
		console.log("cleared");
		$('#but-add').click(function(){
		$('#add-new-dialog').dialog({ 
			title:"Добавление отзыва",
			width:450,
			height:150
		});
	});
		Smart.dataTable({ table:"#ul_newslist",modlink:"{MOD_LINK}",dataurl:"{MOD_LINK}&act=get_data",sort:true });

	});



</script>
