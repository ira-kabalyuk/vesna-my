<smtselect>

<select onchange="{Select}">
    <option value="{id}" each="{items}" selected="{parent.item_id==id}">{title}</option>
</select>
<script>
    this.items=[];
    this.item_id=0;
    var html,re,target;
   
    this.on('mount',function(){
        var self=this;
        console.log(this.opts);
        console.log(this.opts.pattern.toString().replace(/~/g,'\\'));
        re = new RegExp(this.opts.pattern.toString().replace(/~/g,'\\'));
        target=$(this.root).parent().find(this.opts.target);
        html=target.html();
        console.log(html);
        var res=re.exec(html.toString());
        console.log(res);
        if(typeof(res)=='object')
                this.item_id=res[1].toString();
       
        Smt.Api.request(this.opts.src,{},function(d){
            console.log(d);
            if(d.ok){
                self.items=d.data;
                self.update();
            }
        })
    }); 
     
     this.Select=function(e){
       var id=e.target.value;
       var rpl=this.opts.replace.replace('$',id);
        var str=html.replace(re,rpl);
        console.log(id,str);
        target.html(str);
        $(this.root).remove();
     }.bind(this);   
   
</script>
</smtselect>