{if:PICKER}<div id="csset"><div class="selc"><div id="colorpickerHolder"></div></div>
{row:RCSS}<p style="background:{COLOR};"><input type="radio" onclick="appcolor(this,{{ELEM}},{{PROP}},{CURSET})" name="sel"><span>{NAME}</span></p>{/row}
<input type="button" onclick="send_var()" value="сохранить">
<div id="save_css"></div>
</div>{/if}