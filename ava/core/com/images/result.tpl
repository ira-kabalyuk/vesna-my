<div id="result_upload">
{RESULT}
</div>
<script>
window.parent.jQuery('#{TARGET_DIV}').html(document.getElementById('result_upload').innerHTML);
window.parent.$('#progress_upload').remove();

</script>