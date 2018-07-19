<table class="tlist" cellpadding="3" cellspacing="0">
<tr><th>id</th>
<th><img src="{AIN}/img/up.gif" onclick="movef('up')" title="переместить вверх"/>&nbsp;<img onclick="movef('dwn')" title="преместить вниз" src="/rul/img/dn.gif"/></th>
<th width="400">название рубрики</th>
<th width="150">действия</th></tr>
{row:RUBR_LIST}<tr><td>{id}</td>
<td valign="center" align="center" class="movef"><input type="radio" value="{id}" name="movef"/></td>
<td rel="title">{title}</td>
<td><a href="javascript:load_curl('#subcont','{MOD_LINK}&act=edit_rubr&rubr_id={id}');"><img src="{ACL}/img/image_edit.png" title="редактировать" /></a>
&nbsp;&nbsp;
<img src="{ACL}/img/{ONOFF}.png" class="pointer" title="{TITLOFF}" class="pointer" onclick="on_off_rubr(this,{id})" />
&nbsp;&nbsp;
<img src="{ACL}/img/cross.png" class="pointer" title="Удалить" class="pointer"  onclick="del_rubr(this,{id})" />
</td></tr>{/row}
</table>
<script>
<script>
var CurElement='{RUID}';
</script>