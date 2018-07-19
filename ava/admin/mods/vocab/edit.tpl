<div class="subcont">
	<h1>{name} </h1>
	<nav><a href="{MOD_LINK}" class="ajax-nav">Назад, к списоку слов</a></nav>
<div id="langsform" class="mt20">
<form action="{MOD_LINK}&act=save&aj=1" method="post" class="ajax" data-type="html">
<input type="hidden" name="name" value="{name}">
	{FORM_FIELDS}
	<div class="mt20"><button class="btn btn-success" type="submit">Записать</button></div>
	
</form>
</div>
</div>

<script>
$(function(){
Smart.makeAlign('#langsform label');
//Smart.makeAccord({ div:'.subcont',open:['main']});	


	
});

</script>