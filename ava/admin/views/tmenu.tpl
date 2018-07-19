<script>
$(function() {
			$("#mtree").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol",
				persist: "location"
			});
			
			$('#tree span').click(function () {
			 load_tpl('{ACL}/?adr=tmenu&act=edit&sid='+$(this).attr('title'),0);
			
          });
          
		});	
</script>
<div style="display:block;height:auto;;padding:10px;"><span>
<input type="Image" src="{ACL}/img/open_f.gif"   onclick="sel_cont_menu('move_pages','{ACL}/?adr=tmenu')" title="изменить привязку">&nbsp;
<input type="Image" src="{ACL}/img/add_p.gif"   onclick="load_tpl('{ACL}/?adr=tmenu&act=new',0)" title="Новая страница"/>
<input type="Image" src="{ACL}/img/del_doc.gif"   onclick="sel_cont_menu('del_page','{ACL}/?adr=tmenu')" title="Удалить отмеченные страницы"/>
</span></div>
<div style="float:left;height:100%;margin-left:6px;width:200px;">
<p>связанное меню</p>
<div id="sidetreecontrol"><a href="?#"><img src="{ACL}/img/folder_outbox.png" title="свернуть" border="0" /></a>&nbsp;<a href="?#"><img src="{ACL}/img/folder_inbox.png" title="развернуть" border="0" /></a></div>
{TREEMENU}</div>
