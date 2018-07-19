$(function(){



$('#lang-select').on('change',function(){
    var ln=this.value;
    var lngs=['ua','en'];

      var o=window.location.origin;
      var h=window.location.pathname.split("/");
      
      console.log(ln);
      console.log(h);
     
    if(lngs.indexOf(h[1])==-1){
        console.log('not set lng');
            if(ln=='ru')
                return;

            window.location.href=o+'/'+ln+h.join('/');
               
            
        }else{
             h.splice(1,1);
              if(ln=='ru'){
                window.location.href=o+h.join('/');
              }else{
                window.location.href=o+'/'+ln+h.join('/');
              }
            
        } 

});



})