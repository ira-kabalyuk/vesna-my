<div id="result_upload">
<img src="/{img}" />
</div>
<script>
window.parent.$('#{div}').html(document.getElementById('result_upload').innerHTML);
window.parent.$('#progress_upload').remove();
</script>