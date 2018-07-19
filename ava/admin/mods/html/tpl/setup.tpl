<div style="display:block;width:500px;height:auto;min-height:250px;" id="my_form">
<form  action="{MOD_LINK}&act=save_one&pid={PID}&el_id={PAGE_ID}" method="post">
<div id="accord">
{FORM_CONTENT}
</div>
<button type="submit">сохранить</button>
</form>
<script>
$(function(){
	Mod_htm.PAGE_ID='{PAGE_ID}';
	$.fn.extend({_SetParentRoot:function(){Mod_htm.SetParentRoot(this.get(0));}});
	if('{PAGE_ID}'=='0') Mod_htm.SetAutocompleet(); 
	$('#pid_root')._SetParentRoot();
	Smart.makeAccord('#my_form',true);
	});

</script>
