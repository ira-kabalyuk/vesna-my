

<p><span ><a href="{MOD_LINK}&aj=1" class="ajax-nav">назад, к списку файлов</a> </span><span class="ml20"> {MESSAGE}</span><span class="ml20"> открыт файл: <b>{PATH}</b> </span></p>

<div style="width:100%;height:auto;display:block;" id="ajmodal">
<p align="center"><button class="btn btn-success submit" rel="save" >сохранить</button> <button rel="close" class="ml20 btn btn-warning submit">сохранить и закрыть</button></p>
<form action="{MOD_LINK}&type={TYPE}&tpl={TPL}&aj=1" method="post" id="tplform" class="ajax">
<input type="hidden" name="action" value="save"/>
<input type="hidden" name="kontent" value=""/>
</form>
<div id="editplace" style="width:100%;">
<textarea id="editcont">{DESCR}</textarea>
</div>
<div class="row ac"><button class="btn btn-success submit" rel="save">сохранить</button> <button rel="close" class="ml20 btn btn-warning submit">сохранить и закрыть</button></div>
</div>


<script>
var pageFunction=function(){			

CodeMirror.defineMode("smarty", function(config, parserConfig) {

	var smartyOverlay = {
		token: function(stream, state){

			if (stream.match("{*"))
				return null;
			if (stream.match("{") && (stream.next()!=' ') && stream.next()!= null) {
				while ((ch = stream.next()) != null)
					if (ch == "}") break;
				return "smarty";
			}
			while (stream.next() != null && !stream.match('{', false)) {}
			return null;
		}
	};
	return CodeMirror.overlayParser(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), smartyOverlay);
});

	
	
	
	var t=$('#editcont').html().replace(/\{~/g,'{').replace(/~\}/g,'}');
	$('#editcont').html(t);
	var editor = CodeMirror(function(elt) {
  $('#editcont').replaceWith(elt);
}, {value: $('#editcont').val(),
	mode: {name: "smarty", htmlMode: true},
	lineNumbers: true,
	matchBrackets: false,
	enterMode: 'keep',
	indentWithTabs: false,
	indentUnit: 1,
	lineWrapping:true,
	tabMode: 'classic',
	onCursorActivity: function() {
			editor.setLineClass(hlLine, null);
			hlLine = editor.setLineClass(editor.getCursor().line, "activeline");
	}

});
	var hlLine = editor.setLineClass(0, "activeline");
$('#ajmodal button').bind('click',function(){
		$('#tplform input[name="kontent"]').val(editor.getValue());
		$('#tplform input[name="action"]').val($(this).attr('rel'));
		$('#tplform').submit();
		
	});	

};

$.multiload(["/inc/cm/lib/codemirror.css"],function(){
	loadScript("/inc/cm/mode/css/css.js",function(){
		loadScript("/inc/cm/lib/codemirror.js",function(){
			loadScript("/inc/cm/lib/util/overlay.js",function(){
			loadScript("/inc/cm/mode/xml/xml.js",function(){
			loadScript("/inc/cm/lib/util/foldcode.js",function(){
				pageFunction();
	});
	});
	});
	});
	});
	
});

/*
$.multiload(["/inc/cm/lib/codemirror.css","/inc/cm/mode/css/css.js","/inc/cm/lib/codemirror.js","/inc/cm/lib/util/overlay.js","/inc/cm/mode/xml/xml.js","/inc/cm/lib/util/foldcode.js"],pageFunction);
*/


</script>