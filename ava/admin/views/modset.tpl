<form method="post" action="{ACL}/?adr=mod_set&mod_name={MOD}&mid={MID}&lang={LAN}&act=save">
    <input type="hidden" name="action" value="save_set" />
    {FILLS_FORM}
    <p><input type="button" value="записать" onclick="msend_form(this)" /></p>
</form>
<script>
function change_skin(skin){
    load_curl('#skinset','{ACL}/?adr=mod_set&mod_name={MOD}&mid={MID}&lang={LAN}&act=selskin&skin='+skin);
}

function sel_color(obj){
      $(obj).ColorPicker({
   livePreview:false,
 	color: '#'+$(obj).find('input').val(),
	onChange: function (hsb, hex, rgb) {
		$(obj).find('img').css('backgroundColor', '#' + hex);
        $(obj).find('input').val(hex);
	}
});
  }      

</script>