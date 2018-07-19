
<h2>Edit user </h2>
<button type="button" class="sbut but-g" id="send-rubr">save</button>
<div class="subcont">
	
<div style="text-align:left;display:block;width:500px;height:auto;" id="editcat">
<form action="{MOD_LINK}&el_id={EID}&act=save"  method="post" id="menu_form">
	<input type="hidden" name="parent_id" value="{PID}"/>
<div>

</div>
{FIELDS}


</form>

</div>
<div id="payd_history" class="mt20">
	<table class="tlist" class="w500">
	<tr><th>date</th><th>transaction id</th><th>amount</th></tr>
	{row:PAYD_ROW}

	<tr><td>{date_addd}</td><td>{trans_id}</td><td>{amount}</td></tr>
	{/row}
	</table>
</div>
</div>

<script>
$(function(){
	$('#payd_history').appendTo('#set-payd');
Smart.makeAlign('.subcont label');	
Smart.makeTab('.subcont','face');
$('.ckeditor2' ).each(function(){
	$(this).ckeditor({ width:700, bodyClass: $(this).attr('data-body') });
	});

$('#send-rubr').click(function(){
	Smart.send({ div:'#div_content', fade:true, replace:true });
	return false;	
	})
});
</script>