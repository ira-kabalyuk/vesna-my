
<h2>Редактирование рубрики </h2>
<div>
<button type="button" class="btn btn-success" id="send-rubr">сохранить изменения</button>
</div>
<div class="row subcont mt20">
	
<div class="col-lg-9" id="editcat">
<form action="{MOD_LINK}&el_id={EID}&sub=rubric&act=save&aj=1"  method="post" id="menu_form" class="ajax">
	<input type="hidden" name="parent_id" value="{PID}"/>
<div>

</div>
{FIELDS}


</form>

</div>
</div>

<script>
pageSetUp();
$(function(){

$('.ckeditor2' ).each(function(){
	$(this).ckeditor({ width:700, bodyClass: $(this).attr('data-body') });
	});

$('#send-rubr').click(function(){
	$('#menu_form').submit();
	});
});

</script>