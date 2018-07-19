<div style="text-align:left;display:block;width:500px;height:auto;" id="editcat">
<form action="{MOD_LINK}&sid={SID}&lang={LANG}"  method="post" id="menu_form">
<input type="hidden" name="action" value="{MYACT}" />
{FORM_FIELDS}

<div class="toolbar ui-corner-all">
<span id="toolbar" class="my-icon ui-corner-all">
{if:SID}<button rel="move-up" onclick="SM_move_menu(1)" type="button">переместить выше</button>
<button rel="move-dwn" onclick="SM_move_menu(2)" type="button">переместить ниже</button>
<button rel="save" type="submit" style="float:right;">сохранить</button>
<button rel="del" type="button" onclick="SM_del_menu()" style="float:right;margin-right:10px;">Удаление</button>
{/if}
{if:!SID}<button rel="save" type="submit" >сохранить</button><span style="color:white;cursor:pointer;" onclick="$('#menu_form').submit();">&nbsp;&nbsp;Сохранить новый раздел</span>{/if}

</span>
</div>
</form>

</div>
