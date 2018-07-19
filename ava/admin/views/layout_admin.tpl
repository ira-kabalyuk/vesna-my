<div class="topmenu">{TOP_MENU}</div>

<div id="exttop" class="aleft">
{external:EXT_RAZD}{external:EXT_TREE}
<div style="clear:both;"></div>
</div>
<div id="div_content" class="content aleft">{external:EXT_ADD}</div>
<div style="clear:both;"></div>

{CORE_MESSAGE}

<script type="text/javascript">
$(function(){
	var w=$(window).width()-30;
	if($('#exttop>div').length<2){
		$('#div_content').css({ transition:'left 0.4s', left:'0px',width:w });
	}
})
</script>