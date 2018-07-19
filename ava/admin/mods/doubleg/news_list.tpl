
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa {fa_class} fa-fw "></i> 
				{MOD_TITLE}
		</h1>
	</div>

</div>

<div class="subcont mt20 well">

<div class="alert alert-warning">Перетаскивать посты только при выбранной рубрике! иначе собьется сортировка</div>

<div class="row">
	<div class="col-md-1">
		<button class="btn btn-primary"  id="but-add">добавить фото</button>
	</div>
	
	<div class="col-md-10">
		<div class="smart-form">
			<div class="col col-3">
				<label class="label">Фильтр по рубрике</label>
			</div>
			<div class="col col-4">
				<label class="select">
					<select id="rubric"><option value="0">Все</option>{OPTION_RUBRIC}</select><i></i>	
				</label>
			</div>
		</div>
	</div>
</div>

<div id="newslist" class="mt20">
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
	<div class="mt20"> <input type="text" name="title" class="w400" placeholder="Название" ></div>
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
			title:"Добавление",
			width:450,
			height:150
		});
	});
		Smart.dataTable({ table:"#ul_newslist",modlink:"{MOD_LINK}",dataurl:"{MOD_LINK}&act=get_data",sort:true });

		
		$('#rubric').on('change',function(){
			var tag="&tags="+$(this).val();
			$('#ul_newslist').trigger('ajaxload',{ url:"{MOD_LINK}&act=get_data"+tag});
		});

	});



</script>
