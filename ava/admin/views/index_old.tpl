<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Управление</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="{AIN}/inc/css/admin.css"  type="text/css" />
{if:USERID}

<script src="{AIN}/inc/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="{AIN}/inc/form.js" type="text/javascript"></script>
<script src="{AIN}/inc/jquery.treeview.pack.js" type="text/javascript"></script>
<script src='{AIN}/inc/mckalendar.js' type='text/javascript'></script>
<script type="text/javascript" src="{AIN}/inc/swfobject.js"></script>
<script type="text/javascript" src="{AIN}/inc/pg.js"></script>
{row:SCRIPT_ADD}<script type="text/javascript" src="{AIN}{SCRIPT}"></script> {/row}
<link rel="stylesheet" href="{AIN}/inc/css/jquery.treeview.css" />
<link href='{AIN}/inc/css/kalendar.css' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="{AIN}/inc/css/pg.css" />
{row:CSS_ADD}<link rel="stylesheet" href="{AIN}{CSS}"  type="text/css" />{/row}
<script type='text/javascript'>
var Par_id;
var DialogBox;

 function getpos(obj) { 
  var curleft = curtop = 0;
    if (obj.offsetParent) {
        curleft = obj.offsetLeft
        curtop = obj.offsetTop
        while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft
            curtop += obj.offsetTop
        }
    }
   return [curleft,curtop];

}

$(function() {
			$("#tree").treeview({collapsed: false,unique: false});
			$('#tree span').click(function () {
				load_curl('#div_content','{ACL}/?adr=get_content&par_id='+$(this).attr('title'),'drag_icon()');
          });
		});	
	
$(document).ready(function(){
   	initkattree();
		inittabset();
	if(typeof initedittree == 'function') initedittree();
	if(document.getElementById('list_var_cat')!==null){
		$('.treeview input').change(function(){
			var str='';
			$(".treeview input:checked").each(function () {
				var sub=$(this).attr('id');
                str += sub.substring(3, sub.length) + ",";
              });
              str=str.substring(0, str.length-1)
              $('#list_var_cat').val(str);
		})
		var list=$.trim($('#list_var_cat').val());
		if(list.indexOf(',')==-1){
			if(parseInt(list)==0)return;
			$('#chb'+list).attr('checked','true');	
			return;
			
		}
		list=list.split(',');
		var i;
		for(i=0;i<list.length;i++){
			$('#chb'+list[i]).attr('checked','true');
			}

	}
 if(typeof drag_icon == 'function') drag_icon();
});	


	function inittabset(){
	if(document.getElementById('wrapper')==null) return;
		
	var of=$('#wrapper .tabset').offset();
	var tops=parseInt(of.top)+26;
	$('#wrapper .tf').css('top', tops);
	var id=$('#tabset .sel').attr('id');
	$('#c'+id).css('visibility','visible');
	var top=parseInt($('#c'+id).height())+30;
	$('#wrapper').height(top);	
	$('#tabset a').click(function(){
		var id=$(this).attr('id');
		$('#wrapper .tf').css({visibility: 'hidden'});
		$('#c'+id).css('visibility','visible');
		$('#tabset a').removeClass('sel');
		$(this).addClass('sel');
		var top=$('#c'+id).height()+30;
		$('#wrapper').height(top);	
	});
	}
	
	function initkattree(){

		$("#kattree").treeview({
				collapsed: true,
				animated: "medium",
				control:"#treecontrol",
				persist: "location"
			});
			
$('#kattree span').click(function () {
				$('#edit_content').html('<div><center><img src="{ACL}/img/ajax-loader.gif"></center></div>');
				var key=$(this).attr('title').split("_");
				Par_id=key[1];
				cat_show(key[0],key[1],0);
			});
	}
var temp_inr;
var templ_cont;
function load_tpl(url, hide_cont, obj){
url=url+'&aj='+Math.random();
ColorBox=jQuery.fn.colorbox({href: url,  onComplete: function(dat){if(obj) eval(obj);},opacity: 0.4});
}
function load_dialog(arg){
    var url=arg.url+'&aj='+Math.random();
   if(arg.id) $('#'+arg.id).remove();
    var $DialogBox=$('<div syle="display:block;" '+(arg.id ? 'id="'+arg.id+'"':'')+'><center><br><br><img src="{ACL}/img/loading.gif"></center></div>').dialog({ modal: true,title: 'ЗАГРУЗКА' });
    $DialogBox.load(url,{limit: 25},function(){
        $DialogBox.dialog('destroy');
        if(arg.w){
            arg.width=arg.w;
        }else{
           var obj=$DialogBox.find('div:first-child');
          var w=obj.css('width').split('p');
          w=parseInt(w[0])+40;
          arg.width=w;    
        }
        if('ajform' in arg){
        	var $frm=$DialogBox.find('form');
        	$frm.submit(function(){
        		$.ajax({
        			url: $frm.attr('action'),
        			type:'post',
        			data:$frm.serialize(),
        			success:function(data){
        				if('close' in arg){
        					$DialogBox.remove();
        					return;
        				} 
								$frm.parent().replaceWith(data);}
        		}); 
        		
        		return false;
        	});
        }
        arg.title=(arg.title ? arg.title : (obj ? $(obj).attr('title'): ''));
        arg.close=function(){$DialogBox.remove();},
        arg.open= function(event, ui) {if(arg.evl){eval(arg.evl);}}
        $DialogBox.dialog(arg);
    });
}

function load_url(adiv,url){
url += "&aj="+Math.random();
$(adiv).html('<img src="{ACL}/img/ajax-loader.gif">');
$.ajax({url: url, success: function(dat){$(adiv).html(dat); $('#sel_img').attr({src: $('#selelement select').attr('lang')});}, error: function(){alert('Ошибка загрузки!');}});
}
function load_curl(adiv,url,evl){
url += "&aj="+Math.random();
$(adiv).html('<img src="{ACL}/img/ajax-loader.gif">');
$.ajax({url: url, success: function(dat){$(adiv).html(dat); if(evl) eval(evl);}, error: function(){alert('Ошибка загрузки!');}});
}

function hide_box(objName,hide_cont){
if (hide_cont==1) $('#content').html(templ_cont);
$(objName).animate({
opacity:0
}, 300, 'swing',function(){$(objName).hide()});
} 

function send_form(frm,murl,on_ok,obj){
var data_str=$(frm).formSerialize(); 
	$.ajax({
			'type': 'POST',
			'url': murl,
			'data': 'form_var='+encodeURIComponent(data_str),
			'success': function(msg){
			 if (typeof(ColorBox) !== "undefined") $(ColorBox).colorbox.close();
			if(msg==1){eval(on_ok);}else{if(typeof obj!=='number'){$(obj).html(msg);}else{alert('произошла ошибка');}}
				}
			});
          
}

function cliken(par, names){
nod=$('#maintd');
mdiv=$('#mydiv');
nam=('#nametd');
nam.innerHTML=names;
nod.innerHTML='<br><p align="center"><a href="JavaScript:restor()"><img src="'+par+'" alt="закрыть" ></a></p>';
mdiv.style.visibility="visible";
}
function restor(){
mdiv=$('#mydiv');
mdiv.style.visibility="hidden";
nod=$('#maintd');
nod.innerHTML="загрузка рисунка....";
}

function sel_razd(selr){
load_url('#selelement','{ACL}/?adr=selrazd&id='+selr);
}
function sel_element(selel,razd){
$('#sel_img').attr({src: "{ACL}/img/ajax-loader.gif"});
 $.get('{ACL}/?adr=selimg&katid='+razd+'&img='+selel, function(data){
   $('#sel_img').attr({src: data});
 });
}
function spoiler(obj){
	var cont=$(obj).parent().find('div.cont');
		$(cont).slideToggle(500);
}
function helper_(obj){
    var ln=$(obj).attr('lang');
    if(ln!=''){
    $('body').append('<div id="helper"></div>');
    $(obj).append('<img src="{ACL}/img/ajax-loader.gif">');
     
    $('#helper').load('{ACL}/helper.php?help='+ln,{limit: 25}, function(){
      $(obj).attr('lang','');
       $(obj).find('img').remove();  
       var offset=$(obj).offset();
       $('#helper').css({left:''+(offset.left-$('#helper').width()/2)+'px',top:''+(offset.top+25)+'px'});
        $('#helper').slideToggle('fast'); 
    });
    
    }else{
       $('#helper').slideToggle('fast'); 
    }
}
function load_help(help_id){
        $.fn.colorbox({overlayClose: false,opacity: 0, iframe: true, width: 400, height:400, 
        href: '{HELPERS}'+help_id+'/?aj='+Math.random(),
        onComplete: function(){
            $('#cboxOverlay').remove();
           $('#colorbox').draggable({ handle: "#cboxBottomCenter" });
            
        }
        });
        
    }
    function load_cbox(arg){
        $.fn.colorbox({overlayClose: false,opacity: 0, iframe: true, width: arg.w, height: arg.h, 
        href: arg.url+'&aj='+Math.random(),
        onComplete: function(){
            $('#cboxOverlay').remove();
           $('#colorbox').draggable({ handle: "#cboxBottomCenter" });
           if('ajform' in arg){
        	var $frm=$('#colorbox').find('form');
        	$frm.submit(function(){
        		$.ajax({
        			url: $frm.attr('action'),
        			type:'post',
        			data:$frm.serialize(),
        			success:function(data){$frm.parent().replaceWith(data);}
        		}); 
        		
        		return false;
        	});
        }
            
        }
        });
        
    }
function msend_form(obj){
    var form=$(obj).parent().parent();
    var div=$(form).parent();
    var url=$(form).attr('action');
   $(div).html('<center><img src="{ACL}/img/ajax-loader.gif" /></center>');
    send_form(form,url,'',div);
    
}    
</script>
{/if}
</head>

{unless:USERID}
<body  bgcolor="#006699">
<form method="post" action="{ACL}/">
<div class="login">
<div align="center" ><font color="Yellow" size="+1"> Управление </font></div>
<div><span>логин</span><input type="text" size="10" class="t8v" name="logins"/></div>
<div><span>пароль</span><input type="Password" size="10" class="t8v" name="pass"/></div>
<div align="right"><input type="submit" value="войти"/></div>
</div>
</form>
</body></html>
{/unless}
{unless:!USERID}
<body>
{DROPMENU}
<table width="100%"  cellspacing="0"  cellpadding="0" class="t8v" border="0" style="height:95%;"><tbody>
{if:rows:MENU}<tr><td  valign="center" id="ver" colspan="2">&nbsp;
<a class="spm">Быстрый доступ</a>&nbsp;|
{row:MENU}
&nbsp;&nbsp;<a href="{ACL}/?adr={HREF}" {CLASS}>{TITL}</a>&nbsp;|
{/row}&nbsp;&nbsp;{ONLINE}</td></tr>{/if}
<tr><td valign="top" width="180"  style="border-right:solid 1px gray;">{external:EXT_RAZD}{external:EXT_TREE}&nbsp;</td>
<td height="100%" valign="top" id="content" align="left" style="display:blocka;">{external:EXT_ADD}</td></tr>
</tbody>
</table>
<script>
$(document).ready(function(){
   $('a.spm').hover(function(){$('.dropmenu').css('visibility','visible');});
   $('ul.dropmenu').hover(
      function () {
        $(this).css('visibility','visible');
      }, 
      function () {
        $(this).css('visibility','hidden');
      }
    );
});
</script>
</body>
</html>
{/unless}

