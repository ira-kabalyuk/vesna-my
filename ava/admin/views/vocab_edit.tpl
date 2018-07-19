<div style="width:500px;height:200px;display:block;" id="ajmodal">
<p class="mark">{ERROR}</p>
<form action="{ACL}/?adr=vocab&key={id}&mod={mod}&act={ACT}{if:IFRAME}&ajm=yes{/if}" method="post" {if:IFRAME}target="myframe"{/if}>
{if:NEW}
<p> MOD <input type="text" value="{mod}" name="mod" style="width:100px;"></p>
<p> ID <input type="text" value="{id}" name="new_id" style="width:200px;"></p>

{/if}
<p>{title}</p>
{id}
{row:VOLANG}
<p>{ln}<input type="text" value="{TITLE_LEN}" name="title_{ln}" style="width:400px;"></p>
{/row}
 <input type="submit" value="save">
</form>
{if:IFRAME}<iframe name="myframe" width="1" height="1" frameborder="0" scrolling="no"></iframe>{/if}
</div>