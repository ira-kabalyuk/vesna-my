<p><a class="ajax-nav" href="{MOD_LINK}&parent_id={PID}&aj=1"> назад, к списку полей</a></p>
<div id="meta_edit">
<form action="{MOD_LINK}&act=save&el_id={EID}&parent_id={PID}" method="post" class='ajax'>
<div  class="accord">
{row:INPUT}<div><label>{name}</label>{cont}</div>{/row}
</div>
<p><button class="btn btn-success" id="save_meta" type="submit">записать</button></p>
</form>
</div>

<script type="text/javascript">
$(function(){
	/*
	Smart.makeAlign('#meta_edit label');
	$('#save_meta').on('click',function(){
	Smart.submit({obj:'{DIV}',form:'#meta_edit',loader:false});
	});
	$('#meta_edit select[name="type"]').bind('change',function(){
		var self=this;
		if($(this).val()=='dirs'){
			console.log('dirs');
			var cont=parseInt($('#meta_edit input[name="cont_id"]').val());
			$.ajax({
				url:'{MOD_LINK}&sub=dirs&act=dirs',
				type:'post',
				dataType:'JSON',
				success:function(d){
					console.info(d);
					o={'type':'select',name:'dirs',data:d,id:cont};
					$('<span class="ml20">'+Smart.makeInput(o)+'</span>').insertAfter(self);
				}
			});
			
		}
	}).trigger('change');	
*/
});
</script>