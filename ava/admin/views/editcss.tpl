	<script type="text/javascript">
	editAreaLoader.init({
			id: "css"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "ru"
			,syntax: "css"
			,toolbar:"save,|, search, go_to_line, |, undo, redo, |, select_font, |, highlight, reset_highlight, |, help"
			,save_callback: "ea_save"	
		});
		
		function ea_save(id,content){
		$('#'+id).html(content);
		$('#cssform').submit();
		
		}
</script>		
<div style="width:710px;height:auto;display:block;" id="ajmodal">
<form id="cssform" action="{ACL}/?adr=css&tpl={data_file}" method="post" {if:IFRAME}target="myframe"{/if}>
<input type="hidden" name="action" value="save"/>
{DESCR}
<p align="center"><input type="Submit" value="сохранить" class="b8t"/></p>
</form>
{if:IFRAME}<iframe name="myframe" width="1" height="1" frameborder="0" scrolling="no"></iframe>{/if}
</div>
