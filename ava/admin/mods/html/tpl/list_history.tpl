<div style="display:block;width:400px;height:auto;"><table cellpadding="2" cellspacing="0" class="tlist" id="history">
<tr><th id="hisid">&nbsp;</th><th>дата время</th><th>тип записи</th><th>автор</th></tr>
{row:HIS_LIST}<tr><td><input type="radio" name="sel_history" value="{hid}" onclick="Mod_htm.SelectBackup(this)" /></td><td>{data}</td><td>{type}</td><td>{autor}</td></tr>{/row}
</table>
<p id="h_restore" class="flat">выбранное сохранение:<br/>
<button disabled="true" onclick="Mod_htm.RestoreBackup()">восстановить</button>&nbsp; <button disabled="true" onclick="Mod_htm.MarkAsBackup()">отметить как бекап</button></p>
<p class="flat">Удалить историю :<br/> <button onclick="Mod_htm.ClearBackupHistory()">текущей страницы</button>&nbsp; 
<button onclick="Mod_htm.ClearAllBackupHistory()">всех страниц</button></p>
<p class="coment">Примечание: При очистке истории бекапы страниц не удаляются.</p>
</div>
