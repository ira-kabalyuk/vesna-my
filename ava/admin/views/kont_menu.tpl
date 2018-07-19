<style>

ul.treeview li span.active{
    background-color: #FF3A3A;
    color:white;
   }
ul.treeview li span.hidden{
   background-position: bottom left;
  
    
}
#actions{background:#A0D0F9;padding:5px;}

#actions div.titl{color:#023653;}
#actions p{
    font-weight:bold;
    margin:0 0 8px 0;
    padding:2px 3px;
    background:white;
    border:solid 1px #d8d8d8;
    
}

div.bord{background:#D4FDFF;
border:solid 1px #8DA9AC;
padding:4px;margin:5px 0;
}
.toolbar{margin-top:10px;padding: 4px; background: #4ACCFF; border:solid 1px #17669E;}
tr.fold td{background:#D6EEFF;}
#cboxBottomCenter{cursor:move;}
</style>
<div id="div_content" >
{external:CONTENT_LIST}
</div>


<script type="text/javascript">
var Active_point=0;

var TempCor;
var CurMode=0;
var ParentId='{PID}';
var DragPage='0';
function on_off_page(obj){
   var pid=$(obj).attr('lang');
   $(obj).attr('src','{AIN}/img/ajax-loader.gif');
   $.ajax({url: '{AIN}/?adr=pages&page_id='+pid+'&act=onoff&aj='+Math.random(),
    success: function(dat){$(obj).attr('src','{AIN}/img/'+dat+'.png');}, error: function(){alert('Ошибка загрузки!');}});
}
function move_element(id,dir){
    load_curl('#div_content','{ACL}/?adr=get_content&par_id='+ParentId+'&act=move&id='+id+'&dir='+dir);
}


function ch_parent(id){
$('<div><ul id="treecor" class="treeview"><li><span title="0"><b>Корень сайта</b></span></li>'+$('#tree').html()+'</ul></div>').dialog({
   modal: true,
   buttons:{
    "OK": function(){
        cancel_ok();
        $("#treecor").remove();
        $(this).dialog('destroy');
        },
    "Отмена": function(){$("#treecor").remove();$(this).dialog('destroy');}
   }
    
});
    
        $("#treecor").treeview({
				collapsed: false,
				animated: "fast"});
                 $('#treecor span').removeClass('active');
                 $('#treecor span[title="'+$('#phaser').val()+'"]').addClass('active');
                 
             $('#treecor span').click(function () {
			     $('#treecor span').removeClass('active');
			     $(this).addClass('active');
                TempCor=this;
            
          });
            
    }
    

function cancel_ok(){
    var id=$(TempCor).attr('title');
 
    $('#input_parent_id').val(id);
    $('#mfol').html($(TempCor).html());
       
}
function reload_content(){
    load_curl('#div_content','{ACL}/?adr=get_content&par_id='+ParentId,'drag_icon()');
}
function move_pages(page,fold){
    var moves=$(fold).attr('title');
   $('<div style="min-width:300px;height:auto;"><p><span>переместить страницу </span><b>'+page+'</b> в папку <b>'+$(fold).html()+'</b> <span> ?</span></p></div>').dialog({
   title: 'Перемещение страниц сайта',
   buttons: {
   "Ok": function(){
   load_curl('#div_content','{ACL}/?adr=get_content&act=move_page&par_id='+ParentId+'&page_id='+page+'&fold_id='+moves,'drag_icon()');
    $(this).dialog("close");},
   "Oтмена":function(){$(this).dialog("close");}}
});
}
function sel_cont_menu(url){
var	data=new Array();
$('#k_menu input').each(function(i){
if(this.checked) data.push(this.name);
});
if(data.length==0){ alert("Вы не отметили ни одной  страницы!"); return;}
$('<div style="min-width:300px;height:auto;"><p><span>Удалить страницы </span><b>'+data.join(',')+'</b><span> ?</span></p></div>').dialog({
   title: 'Удаление страниц сайта',
   buttons: {
   "Ok": function(){
    del_pages(data);
    $(this).dialog("close");},
   "Oтмена":function(){$(this).dialog("close");}}
});
}
function del_pages(data){
load_curl('#div_content','{ACL}/?adr=get_content&act=del_pages&par_id='+ParentId+'&page={PGL}&pages='+data.join(','),'document.location=\'{ACL}/?adr=kontent&par_id='+ParentId+'\'');
}
function drag_icon(){
    $('#div_content span.myico').draggable({ opacity: 0.7,
    cursor: "pointer", 
    start: function(event, ui){
        DragPage=$(this).attr('title');
    $("#tree li span").bind("mouseup",function (e) {
         $("#tree li span").unbind('mouseup');
          move_pages(DragPage,this);
    });     
    },
   cursorAt: { top: -10, left: -10 },
   stop: function(){$("#tree li span").unbind('mouseup');},
    helper: function( event ){
				return $( "<div class='ui-widget-content t8v'>Перетащи  указатель курсора<br> на название раздела,<br/> в который нужно скопировать<br/>страницу "+$(this).attr('title')+"</div>");
                }
                });
                
       
}
</script>

