<style>
ul.treeview li b:first-child{
    padding:1px 3px 1px 16px;
    background-image: url('{AIN}/img/tbullet.png');
       background-repeat: no-repeat;
}
ul.treeview li i{
    padding:1px 3px 1px 16px;
    margin-left:5px;
    cursor:pointer;
    background-image: url('{AIN}/img/meta.jpg');
       background-repeat: no-repeat;
}
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

#cboxBottomCenter{cursor:move;}
</style>
<script>
var Active_point=0;
var Temphtm='';
var CurMode=0;
var Lang_link='{ACL}/?mod={MOD}&mid={MID}&sub=kats&act=list';
var Lang='{LANG}';
function InitializeTree() {
			$("#mtree").treeview({
				collapsed: false,
				animated: "fast",
				control:"#sidetreecontrol",
   			unique: false,
     	  persist: "cookie",
   			cookieId: "categorytytree"
                              		
			});
			
			$('#mtree span').unbind().click(function () {
			 $('#mtree span').removeClass('active');
			$(this).addClass('active');
            Active_point=$(this).attr('title');
		   SM_edit_menu();
          });
          
       $('#mtree li i').unbind().click(function(){
       	 Active_point=$(this).attr('lang');
        	SM_seo_menu();
       });   
          
		}
        
        function camback(){
         $('#actions').html(Temphtm);
         $('#actions p').html($('#mtree span[title="'+Active_point+'"]').html());
         CurMode=0;   
        }
        function sel_phaser(){
            CurMode=1;
            $('#phaser_id').html(''+Active_point+' '+$('#mtree span[title="'+Active_point+'"]').html());
            $('#phaser').val(Active_point);
                           
            
        }
        
   $(document).ready(function(){
    Temphtm=$('#actions').html();
    InitializeTree();
  	  
        
   });
   
   function SM_edit_menu(){
      Smart.load_dialog({
      	id:'edit_menu',
				url:'{MOD_LINK}&act=new&sid='+Active_point,
				evl:function() {
					CurMode=1;
					icobut();
						Smart.makeAlign('#editcat .set label');
					Smart.makeAccord({div:'#editcat',open:['face']});
					},
				 w:'540px',title:'Редактирование раздела'});
    
   }
     
  function SM_new_menu(){
     Smart.load_dialog({
     	id:'edit_menu',
			 url:'{MOD_LINK}&act=new&sid=0',
			 evl:function(){sel_phaser();
			 icobut();
			 Smart.makeAccord({div:'#editcat',open:['face']});
			 },
			  w:'540px',
				title:'Редактирование раздела'});
    
   } 
    
      function SM_move_menu(dir){
    $('#mtree').load('{MOD_LINK}&act=move&sid='+Active_point+'&dir='+dir,{limit: 25},function(){
    
      InitializeTree();
       $('#mtree span[title="'+Active_point+'"]').addClass('active');
    });
    
   }
     function SM_del_menu(){
       $('<div id="dialog-confirm"><p><img src="/rul/img/alert.png" />Вы действительно хотите удалить <br/><b>'+$('#mtree span[title="'+Active_point+'"]').html()+'</b> ?</p></div>').dialog({
        buttons:{ "Ok": function() {
        $(this).dialog("close");
        location.href='{MOD_LINK}&act=dels&sid='+Active_point;
        },"Отмена" : function(){ $(this).dialog("close");} },
       modal: true,
       resizable: false,
       title: '<font color="#8A0303">УДАЛЕНИЕ раздела</font>',
             } );
   
   }   
   
  function ch_link(){
   var TempCor;
    var Dialog=$('<div id="dialog-confirm"><ul id="treecor" class="treeview"><li><span title="0"><b>Главный узел каталога</b></span></li>'+$('#mtree').html()+'</ul></div>').dialog({modal:true,
    title: 'Выбор привязки',
    buttons: {"Ok" : function(){
        $('#phaser_id').html('<b>'+$(TempCor).attr('title')+'</b>&nbsp;'+$(TempCor).html());
        $('#phaser input').val($(TempCor).attr('title')); 
        $(this).dialog("close");
       
        },
        "Отмена" : function(){$(this).dialog("close");}},
        close: function() {$('#dialog-confirm').remove();},
        open: function(){
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
    });
    
  } 
  function M_disable(obj,onof){
    $(obj).attr('disabled',true);
    $.ajax({
       type: "POST",
       url: '{MOD_LINK}&act=hide&sid='+Active_point+'&onof='+onof+'&aj='+Math.random(),
       success: function(data){
        if(data==0){
            $('#mtree span[title="'+Active_point+'"]').css('background-position','top left');
          
        }else if(data==1){
            $('#mtree span[title="'+Active_point+'"]').css('background-position','bottom left');
         
        }else{
            alert(data);
        }
        $(obj).attr('disabled',false);
       }
    });
 
   
  } 
  
function icobut(){

		$('<span id="phaser_id" style="margin-right:10px;">'+$('#mtree span[title="'+$('#phaser input').val()+'"]').html()+'</span>').appendTo('#phaser');
			$('<button type="button" class="sbut but-g">изменить</button>').click(function(){ch_link();}).appendTo('#phaser');
     var but=$("#toolbar button").get();
    for(i=0; i<but.length; i++){
               	$(but[i]).button({text: false, icons: {primary: "ui-icon-"+$(but[i]).attr("rel"),secondary: null} });
    }
  }

    function ch_lang(ln){
       load_curl('#catkont',Lang_link+'&lang='+ln);
}
 
 
 
</script>

<div class="contb">
<p><b>Дерево категорий</b>&nbsp;&nbsp;<button id="plus" class="sbut but-g" onclick="SM_new_menu()" ><span class="ui-icon-plus"></span>Добавить категорию</button></p>
<div id="sidetreecontrol"><a href="?#"><img src="{ACL}/img/folder_outbox.png" title="свернуть" border="0" /></a>&nbsp;<a href="?#"><img src="{ACL}/img/folder_inbox.png" title="развернуть" border="0" /></a></div>
{TREEMENU}</div>
</div>


