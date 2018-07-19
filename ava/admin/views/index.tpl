<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Vladimir" />
	<title>Smart cms</title>
	<link rel="stylesheet" href="{AIN}/admin.css" />
{if:USERID}	
	<link rel="stylesheet" href="{AIN}/icons.css" />
	<link rel="stylesheet" href="{AIN}/css/icon.css" />
	<link rel="stylesheet" href="{AIN}/css/ui-lightness/jquery-ui-1.8.17.custom.css" />
	<link rel="stylesheet" href="{AIN}/ui-lightness/jquery-ui-1.10.4.custom.css" />
	<script src="{AIN}/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="{AIN}/js/browser.js" type="text/javascript"></script>
	<script src="{AIN}/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
	<script src="/smart/ckeditor2/ckeditor.js"></script>
	<script src="/smart/ckeditor2/adapters/jquery.js"></script>
	
	<script src="{AIN}/js/core.js?ver=2.0" type="text/javascript"></script>
	{HEAD_SCRIPTS}
	<script>
	$(function(){
		Smart.ACL='{AIN}';
		$('.topmenu a[rel="{MOD}"]').addClass('active');
	});
	</script>
{/if}
</head>
<body>
{MAIN_CONTENT}
{external:MAIN}
<script>
/*	
$(function(){
var topfix=true;
	var tbind=function(){$('body').bind('mouseleave',function(e){
		if(e.originalEvent.clientY<0) $('.topmenu').slideDown(300);
	});};
	var blink=function(){$('.topmenu').animate({'opacity':0},100,function(){$('.topmenu').css({'opacity':1})});};
	$('.topmenu').bind('mouseleave',function(){$(this).slideUp(200);}).dblclick(function(){
		if(topfix){
			topfix=false;
			$(this).unbind('mouseleave');
			$('body').unbind('mouseleave');
			blink();
		}else{
			topfix=true;
			tbind();
			$(this).bind('mouseleave',function(){$(this).slideUp(200);});
			blink();
		}
		
	});
	tbind();
});
*/
</script>
</body>
</html>