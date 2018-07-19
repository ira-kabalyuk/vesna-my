	<script type="text/javascript">
	
		function ea_save(id,content){
		$('#'+id).html(content);
		$('#tplform').submit();
		
		}
</script>

<div style="width:710px;height:650px;display:block;" id="ajmodal">
<form action="{ACL}/?adr=tml&tpl={data_file}" method="post" id="tplform" {if:IFRAME}target="myframe"{/if}>
<input type="hidden" name="action" value="save"/>
{DESCR}
<p align="center"><input type="Submit" value="сохранить" class="b8t"/></p>
</form>
{if:IFRAME}<iframe name="myframe" width="1" height="1" frameborder="0" scrolling="no"></iframe>{/if}
</div>
<script>
$(function(){
	var t=$('#editcont').html().replace(/\{~/g,'{').replace(/~\}/g,'}');
	$('#editcont').html(t);
	
	editAreaLoader.init({
			id: "editcont"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "ru"
			,syntax: "html"
			,toolbar:"save,|, search, go_to_line, |, undo, redo, |, select_font, |, highlight, reset_highlight, |, help"
			,save_callback: "ea_save"	
		});
		
	
});
</script>