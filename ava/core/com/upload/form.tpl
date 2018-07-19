<span id="{div}"><span  class="uploadImage"><img src="/{img}" width="100" /></span><span class="ml20"><button class="sbut but-y" type="button">загрузить</button></span><span class="info ml20"></span></span>

<script>
$(function(){

var interval;
var button='#{div} button';
var upload=function(){
    $.ajax_upload(button, {
            action : '{link}&act=upload&div={div}&parent_id={pid}',
            name : '{div}',
            onSubmit : function(file, ext) {
              // показываем картинку загрузки файла
              $('<span clas="pad20" id="progress_upload"> Загрузка изображения '+file+' <span class="pad20"><img src="/skin/admin/img/ajax-loader.gif"></span></span>').appendTo('#{div} .info');
               //Выключаем кнопку на время загрузки файла
              this.disable();

            },
            onComplete : function(file, response) {
              var res=$.parseJSON(response);
                console.info(res);
              // убираем картинку загрузки файла
              $('#{div} .info').html(" ");
              $('#{div} img').attr('src','/'+res.path+'/'+res.fname+'?j='+Math.random());
              // снова включаем кнопку
              this.enable();
              
            }
          });
  };
if($.fn.ajax_upload){
  upload();
}else{
  loadScript("/smart/inc/ajaxupload.js", upload);
}
    

});
</script>




