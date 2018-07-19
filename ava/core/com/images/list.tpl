<ul id="ul_{TARGET_DIV}">
{row:PHOTO_ROW}
<li lang="{id}">
<span class="w50"><img src="/{img}" width="50" /></span>
<span class="w300">{descr}</span>
<span class="tool" lang="{id}">
<span><input type="radio" name="top" class="_mark" {checked}/></span>
<img src="/rul/img/{onof}.png" title="вкл/выкл" class="_onof" />
<img src="/rul/img/image_edit.png" title="редактировать" class="_edit"/>
<img src="/rul/img/cross.png" title="удалить" class="_del"/>
</span>
</li>
{/row}
</ul>
<script type="text/javascript">
$(function(){
	Smart.Tlist.makeBind({
		target:'#{TARGET_DIV}', 
		div:'#ul_{TARGET_DIV}',
		url:'{MOD_LINK}',
		drag:true,
		data:$.parseJSON('{JSONDATA}'),
		});
});
</script>

