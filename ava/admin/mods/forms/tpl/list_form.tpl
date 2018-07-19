<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-send-o fa-fw "></i> 
				Список форм
		</h1>
	</div>
</div>

<div class="well">

<form action="{MOD_LINK}&type=form&act=add&el_id={id}&aj=1" method="post" class="ajax">
<div class="row">


	<div class="col-md-1">имя формы:</div>
	<div class="col-md-3"><input type="text" name="title" class="w300 input-md" value="{title}" /></div>


 <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm" >добавить форму</button></div>

</div>
</form>

<div class="row mt20" id="flist">
{FORM_LIST}

</div>
</div>

<script>

pageSetUp();

$(function(){
	Smart.dataTable({
			table:"#ul_forms",
			modlink:"{MOD_LINK}&type=form",
			dataurl:"{MOD_LINK}&type=form&act=get_data"
		});
});

</script>