<div style="float:left;width:200px;">
<div id="sidetreecontrol"><p>Страницы</p>
<a href="?#"><img src="/skin/admin/img/folder_outbox.png" title="свернуть" border="0"/></a>&nbsp;<a href="?#"><img src="/skin/admin/img/folder_inbox.png" title="развернуть" border="0"/></a>
</div>
{RAZDEL}
</div>

<div style="width:800px;float:left;padding-left:20px;">
<div class="butp" id="pages">
<button class="sbut but-g" id="addpage">добавить страницу</button>
<button class="sbut but-r" id="del_page">удалить отмеченные</button>
<button class="sbut but-y" id="modsetup">Настройки</button>
<br />

</div>
<p class="path">{CRUMBS}</p>

<div id="pagelist">

<ul class="ulhead">
<li>
<span class="w50">m</span>
<span class="w50">id</span>
<span class="w300">Заголовок страницы</span>
<span class="w200">ссылка</span>
<span class="w80">действия</span>
</li>
</ul>

<ul class="draglist">
{row:STATIC_LIST}
<li lang="{id}">
<span class="w80"><span class="myico ui-icon-{ICO}" lang="{id}"></span><input type="checkbox" class="idpage" value="{id}" /> <b>{id}</b></span>
<span class="w300"><a href="{ACL}/?mod=htm&act=edit&el_id={id}&pid={parent_id}">{title}</a></span>
<span class="w200">&nbsp;<a href="/{link}" target="_blank" class="t8">{link}</a></span>
<span class="tool" lang="{id}-{parent_id}" >
<i class="_edit fa fa-edit" title="редактировать!!"></i>
	<i class="_param fa fa-edit" title="свойства страницы"></i>
	<i class="_onoff fa fa-edit" title="свойства страницы"></i>
	<i class="_history fa fa-edit" title="свойства страницы"></i>
</span>
</li>
{/row}
</ul>

</div>

</div>
<div style="clear:both;"></div>
<script>
$(function(){
		$('#extleft').hide();
	Mod_htm.modlink='{MOD_LINK}';
	Mod_htm.PID='{PID}';
	var self=this;
	$('#del_page').click(function(){Mod_htm.DelSelectPage(this); return false;});

	$('#pagelist ._edit').click(function(){
		var t=$(this).parent().attr('lang').split("-")
		window.location.href='{ACL}/?mod=htm&act=edit&el_id='+t[0]+'&pid='+t[1]
		});
	$('#pagelist ._param').click(function(){Mod_htm.EditParam(this);return false;});
	$('#addpage').click(function(){Mod_htm.AddPage(this); return false;});
	$('#pagelist ._onoff').click(function(){Mod_htm.OnoffPage(this);return false;});
	$('#pagelist ._history').click(function(){Mod_htm.GetHistory(this);return false;});
	$('.ui-icon-folder_close').addClass('pointer').click(function(){Mod_htm.OpenFolder(this);});
	$('.path a').click(function(){Smart.load_curl('#div_content',$(this).attr('href'));return false;});
	Smart.modSetup({obj:'#modsetup',mod:'htm',w:400});
	Mod_htm.drag_icon();
	$("#tree").treeview({
				collapsed: false,
				animated: "fast"});
				
	$('#tree span').click(function(){
	Smart.load_curl({
			div:'#div_content',
			url:'{MOD_LINK}&pid='+$(this).attr('title')});
	});
	
	Smart.Tlist.makeBind({
		target:'#div_content', 
		div:'#pagelist .draglist',
		url:'{MOD_LINK}',
		parent:'-t-',
		drag:true
		});
				
                
});
</script>