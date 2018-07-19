<div style="padding:5px;margin:0;width:300px;height:auto;min-height:250px;"  title="{TITLE}">
<form id="my_form" style="padding:0;margin:0;" action="{MOD_LINK}">
<input type="hidden" name="action" value="save_one" />
<input type="hidden" name="rec_id" value="{ID}" />
<input type="hidden" name="parent_id" id="input_parent_id" value="{PID}" />
<div class="bord">
<b>Заголовок страницы</b> <br/>
{row:LANG}
<div>{l}<br/><input type="text" name="name_{ln}" style="width:90%" value="{PGNAME}" class="t8v"/></div>
{/row}
</div>
<div class="bord"><input type="checkbox" name="folder" value="1" {FOLDER}/> страница является разделом </div>
<div class="bord">Линк <input type="text" name="link" value="{PGLINK}" /></div>
<div class="bord"><b>раздел:&nbsp;&nbsp;</b><span id="mfol">{MFOL}</span> <img src="{AIN}/img/image_edit.png" title="Выбрать раздел" onclick="ch_parent('{ID}')"/></div>
{row:INPUT_FORM}
<div class="bord">
<span>{title}</span>{INPUT}
</div>{/row}
<div class="butp"><input type="button"  value="записать" onclick="saveOnePage()"/></div>
</form>
</div>
<script>
function saveOnePage(){
	Smart.submit({form:'#my_form',loader:true,on_ok:function(){
		Smart.load_url('{MOD_LINK}','#div_content');
		}});
}
</script>


