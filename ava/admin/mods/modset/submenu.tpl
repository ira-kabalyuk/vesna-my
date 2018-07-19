<div id="dropmenu" class="dropmenu">
{row:SETLIST}
<a href="#" rel="{mods}" onclick="Smart.load_curl('#div_content','{ACL}/?mod=setup&type={mods}&target=modset')">{title}</a>
{if:rubr}<a href="#" onclick="Smart.load_curl('#div_content','{ACL}/?mod={mods}&sub=rubric&target=modset')">{title} &rang; pубрики </a>{/if}
{/row}
<a href="#" onclick="Smart.load_curl('#div_content','{ACL}/?mod=forms')">Формы</a>
<a href="#" onclick="Smart.load_curl('#div_content','{ACL}/?mod=setup&type=foto&parent=4')">Банеры</a>
<a href="#" onclick="Smart.load_curl('#div_content','/smart/?mod=foto&parent=4&sub=rubric&act=list')">Банеры &rang; разделы </a>
<a href="#" onclick="Smart.load_curl('#div_content','{ACL}/?mod=setup&type=foto&parent=5')">Видео (youtube)</a>
</div>
<script type="text/javascript">
$(function(){
	$('.dropmenu a').click(function(){
		$('.dropmenu a').removeClass('active');
		$(this).addClass('active');
	})
})
</script>