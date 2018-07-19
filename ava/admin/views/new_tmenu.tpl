<div style="text-align:left;">
<p align="center" class="titl"><font size="+1">{TITLE}</font></p>
<p>линк - любая ссылка, является приоритетом при формировании ссылки</p>

<form action="{ACL}/?adr=tmenu&sid={PID}"  method="post">
<input type="Hidden" name="action" value="{MYACT}" />

название <input type="text" size="60" maxlength="60" class="t8v" name="link_name" value="{NAME}" />
<br/>
принадлежит:
<select name="parent">{PARENT}</select><br/>
страница {LIST}<br/>
параметры <input type="text" size="60" maxlength="60" class="t8v" name="link_param" value="{PARAM}" />
<p><input type="Submit" class="b8v" value="сохранить" /></p>
</form>
<button onclick="$('#ajbox').hide()">закрыть</button>
</div>