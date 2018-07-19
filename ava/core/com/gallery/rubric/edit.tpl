<div  id="editcat">
<button type="button" class="btn btn-success" rel="submit">сохранить</button>
<p>все размеры указаны в пикселах</p>
<form action="{MOD_LINK}&el_id={EID}&sub=rubric&act=save"  method="post" id="menu_form">

<div id="tabset">
{FIELDS}
</div>



</form>

</div>
<script>
$(function(){
Smart.makeAlign('#tabset label');
 Smart.makeTab('#tabset','face');	
$('button[rel="submit"]').bindSubmit('#div_content');
 Smart.aceForm('#tabset');
});
</script>

