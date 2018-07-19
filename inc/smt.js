	var jsArray = {};

	


	function loadScript(scriptName, callback) {
	
		if (!jsArray[scriptName]) {
			jsArray[scriptName] = true;
	
			// adding the script tag to the head as suggested before
			var body = document.getElementsByTagName('body')[0],
				script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = scriptName;
	
			// then bind the event to the callback function
			// there are several events for cross browser compatibility
			script.onload = callback;
	
			// fire the loading
			body.appendChild(script);
			
			// clear DOM reference
			//body = null;
			//script = null;
	
		} else if (callback) {
			// changed else to else if(callback)
			//console.log("JS file already added!");
			//execute function
			callback();
		}
	
	}

	function getSelected() {
            if (window.getSelection) {
                return window.getSelection();
            }
            else if (document.getSelection) {
                return document.getSelection();
            }
            else {
                var selection = document.selection && document.selection.createRange();
                if (selection.text) {
                    return selection.text;
                }
                return false;
            }
            return false;
        };


// clear fuffer
function OnPaste(e) {
        	var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
	        e.preventDefault();
	        document.execCommand('insertText', false, bufferText);
    		}	

var PromptUpload=function(div){
	// Set fieldname
	var set=$.extend({rx:0,ry:0,name:"img",prop:true},$(div).data('json'));

	//$.ajaxUploadSettings.name = set.name;
	// Set promptzone
	$(div).find(".pro-gress" ).hide();
	$(div).find('.promzone').ajaxUploadPrompt({
		url : '/upload',
		fname:set.name,
		data:set,

		beforeSend : function () {
			$(div).find('.promptzone, #result').hide();
			$(div).find('.progress>div').css('width','0%');
			$(div).find(".pro-gress" ).show();
		},
		onprogress : function (e) {
			if (e.lengthComputable) {
				var p = Math.ceil((e.loaded / e.total)*100);
				$(div).find('.progress>div').css('width',p+'%');
			}
		},
		error : function () {
		},
		success : function (data) {
			var d = $.parseJSON(data);
			console.log('success',d);
			//$(Smt._img).attr("src","/"+d.path+'/'+d.fname);
			//$('#smt-modal').html("").addClass('smt-hide');
			$('#upload-smt').trigger('update',d);
			
			var html = '';

			if(d.ok=="1"){
				var img=d.fname;
				$(div).find('.promptzone').html('<img src="/'+d.path+'/'+d.fname+'" class="img-responsive">');
				//$(div).find('.hidden').val(d.fname);
			}

			
			if (d.ok!="1") {
				html += '<h2>Error</h2>';
				html += '<p>' + data.error + '</p>';
			}
			
			//$(div).find( ".pro-gress" ).hide();
			$(div).find('#result').html(html);
			$(div).find('.promptzone, #result').show();
		}
	});
	 Crop(set,"/upload");
};

var Smt={
	_tpl:{}, // templates
	_edit_mode:false,
	_whait:false,
	_sm:{},
	type:0,
	_blocks:{},
	_img:{},
	opts:{},
	styles:{},
	_url:"/smart/?mod=service&aj=1&el_id=21",

setup:function(div){
	if($(div).find('._smt-setup').length==0){
		var json=$(div).data('setup');
		if(json==null)
				return;

		if(json=='youtube'){
			
				this.youtube(div);
				return;
			
		}		

		var $setup= $('<div class="_smt-setup"></div>').appendTo(div);		
		var data=this.parseJSON(json);
		riot.mount($setup,'smtselect',data);
			
	}else{
		$(div).find('._smt-setup').remove();
	}
},
youtube:function(div){
	var $y=$(div).find('iframe');
	var link=$y.attr('src');
	bootbox.prompt({
               title:"Ссылка на видео:",
                	value:link,
                		callback:function(lnk){
                			console.log(lnk);
                					if(lnk==null)
                						return;
                					var r=lnk.split("/");
                					var s=link.split("/");
                					s[s.length-1]=r[r.length-1];
                					$y.attr('src',s.join("/"));	 
                				}
                			});
},

parseJSON:function(jsn){

	var opt={};

	var t=jsn.toString().split(",");
	for(var i=0;i<t.length;i++){
		var a=t[i].split(":");
		opt[a[0]]=a[1];
	}
		return opt;
		
},

html:function(div){
		if($(div).find('textarea').length==0){
			var h=$(div).height();
		$(div).html('<textarea style="width:100%;height:100%;margin-top:32px;min-height:'+h+'px;">'+$(div).html()+'</textarea>');
	}else{
		var old=$(div).find('textarea').val();
		$(div).html(old);
	}
	

},


init:function(opt){
	var self=this;
	this._url=opt.url;
	this.opts=opt;
	var type=$('#smt-content').data('type');
	if(type!=null)
		self.type=type;

	//init styles
	if('styles' in opt)
		self.styles=opt.styles;


	this.loadTpl(); // load tool templates
	
	this.loadContent(this._url+'&act=load_smt_content&type='+self.type); // load content
	//this.edit();
	//$('[data-smt="h1"]').sortable();


// bind blocks tool-menu
$('body').on('click', '#smt-mod-tool button',function(e){
		
		e.stopPropagation();
		var role=$(this).data('role');
		
		
		var parent=$('#smt-mod-tool').parent();
		
		console.log('click '+role);

		if(role=='html'){
			$('#smt-mod-tool').appendTo('#smt-hide');
			Smt.html(parent);
			return;
		}

		if(role=='setup'){
			$('#smt-mod-tool').appendTo('#smt-hide');
			Smt.setup(parent);
			return;
		}

		if(role=='del'){
			
			

			$('#smt-mod-tool').appendTo('#smt-hide');
			if($('body #smt-content>.smt-mod').length<2){

				bootbox.alert("Нельзя удалять последний блок!");
				console.log($('body #smt-content>.smt-mod').length);
				return;
			}
			parent.remove();
		
		}else if(role=='up'){
			// move up
			var i=$('[itemtype="content"] .smt-mod').index(parent);
			console.log('move up',i);
			i--;
			if(i<0) return;
			$(parent).insertBefore('[itemtype="content"] .smt-mod:eq('+i+')');
		}else if(role=='down'){
			// move down	
			var i=$('[itemtype="content"] .smt-mod').index(parent);
			i++;
			var len=$('[itemtype="content"] .smt-mod');
			if(i==len)
				return;
			$(parent).insertAfter('[itemtype="content"] .smt-mod:eq('+i+')');

		}else if(role=='add'){
			self._current=parent;
			if($(parent).closest('#smt-content').length==0){
				self._current=$('<div data-smt="mod"></div>').appendTo('#smt-content');
			}

			$('#smt-panel').removeClass('smt-hide');

		}else if(role=='edit'){
			//Smt.activateSubmod(parent);
			
			parent.find('a').on('click',function(e){ e.preventDefault();});
			parent.find('[data-smt]').each(function(){
				var d=$(this).data('smt');
				if($(this).hasClass('smt-edit')){
					
					$(this).removeClass('panel-body smt-edit');

					switch(d){
						case 'h':
							$(this).removeAttr('contenteditable');
						break;
						
						case 'dp':
							$(this).removeAttr('contenteditable');
						break;
						case 't':
							$(this).unbind('keyup');
						break;


						case 'table':
						self.tableEdit([this],{destroy:true});
							//$(this).find('td').removeAttr('contenteditable');
						break;
					}
					
				}else{
					$(this).addClass('smt-edit');

					switch(d){
						
						case 'h':
							$(this).attr('contenteditable',true);
							
						break;

						case 'p':
							$(this).attr('contenteditable',true);
						break;

						case 'dp':
							$(this).attr('contenteditable',true);
						break;

						


						case 't':
							$(this).attr('contenteditable',true);
							$(this).on('keyup',function(e){
								e.preventDefault();
								e.stopPropagation();
								if(e.keyCode==13)
									document.execCommand("insertHTML", false,'<br>');
							});
						break;

						case 'table':
						self.tableEdit([this],{});
							//$(this).find('td').attr('contenteditable',true);
						break;

						default:
						if(d in self.styles)
							$(this).attr('contenteditable',true);
						break;
						

					}

				}
			});

			
		}else if(role=="save"){
				self.save_tpl();
				return;
		}
		

	});

	// show-hide overflow panel
	$('#smt-panel').on('click',function(e){
		e.stopPropagation();
		$(this).toggleClass('smt-hide');

	});

	//$('#smt-modal').on('click','#smt-wrap-upload',function(e){e.stopPropagation();});
	// close smt modal
	$('#smt-modal').on('click','.smt-close',function(){
		$('#smt-modal').html("");
		$('#smt-modal').addClass('smt-hide');
	});


	$('#smt-panel').on('click','.smt-row',function(e){
			e.stopPropagation();
			var id=$(this).data('id');
			console.log('add '+id,self._current);
			$('#smt-panel').addClass('smt-hide');
			if(id in self._blocks){
		
				var dt=$(self._current).data();
				console.log(dt);
				if('add' in dt){
					$(self._blocks[id]).appendTo(dt.add).addClass('smt-mod');
				}else{
					$(self._blocks[id]).insertAfter(self._current).addClass('smt-mod');
				}
				self.initStyles();
			}
			
	});




	$('body').on('mouseenter','.smt-mod',function(){
		console.log('mouseenter',$(this).data());
		$('#smt-mod-tool').appendTo(this);
	});

	$('#smt-modal').on('submit',function(e){
		e.preventDefault();
	});

	// image edit
	$('body').on('click','img[data-json]',function(e){
		e.stopPropagation();
		e.preventDefault();
		var json=$(this).data('json');
		var src=$(this).attr('src');
		self._img=this;
		console.log(json,src);	
		$('#smt-modal').html(self._tpl.wrap_tpl).removeClass('smt-hide');
		$.ajax({
			url:"/smart/?mod=smt",
			data:{json:json,src:src,act:"upload"},
			type:'post',
			success:function(d){
				$(d).appendTo('#smt-wrap-upload');
				$('#smt-wrap-upload').on('update','#upload-smt',function(e,a){
					console.log('update',a);
					$(self._img).attr("src","/"+a.path+'/'+a.fname).trigger('update',a.fname);
					
				});
			}
		});
	});



$('body').on('paste','[contenteditable]',function(e) {
    e.preventDefault();
    var text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
    		document.execCommand('insertText', false, text);
});


	//self.imageMenu();

},


destroy:function(callback){
	var self=this;
	this._whait=true;
	this.tableEdit('table',{destroy:true});
	$('.smt-edit').removeClass('smt-edit');
	$('.smt-mod').removeClass('smt-mod').find('[contenteditable="true"]').removeAttr('contenteditable');

	setTimeout(function(){callback(self);},500);
	
	
},

activateSmt:function(){
	console.log('activateSmt');
	$('[data-smt="mod"]').addClass('smt-mod');
},
makeContextMenu:function(name,m){
	var li="";
	for (var i=0;i<m.length;i++){
		li+='<li rel="'+i+'"><a href="#">'+m[i].title+'</a></li>';
	}
	var contextHTML = 
				'<div data-toggle="context" data-target="#context-menu-'+name+'" disabled>' +
				'  <div id="context-menu-'+name+'" class="smt-context-menu">' +
				'    <ul class="dropdown-menu">' +
				li +
				'   </ul>' +
				'  </div>' +
				'</div>';
	
	return contextHTML;
},
getRange:function(html){
	
    var range;
    if (window.getSelection && window.getSelection().getRangeAt) {
        range = window.getSelection().getRangeAt(0);
        range.deleteContents();
        var div = document.createElement("div");
        div.innerHTML = html;
        var frag = document.createDocumentFragment(), child;
        while ( (child = div.firstChild) ) {
            frag.appendChild(child);
        }
        range.insertNode(frag);
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.pasteHTML(html);
    }

},
replaceSelectedText:function(replacementText) {
    var sel, range;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            range.insertNode(document.createTextNode(replacementText));
        }
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        range.text = replacementText;
    }
},
wrapSelected:function(rel){
		var wrap=rel.wrap;
   		var text = getSelected().toString();
   		var self=this;
   		var range = window.getSelection().getRangeAt(0);
   			
   			
   				if('action' in rel){
   					if(rel.action=='removelink'){
   						var con=range.commonAncestorContainer.parentNode;
   						var text=con.textContent;
   						$(con).replaceWith(text);
   						//range.commonAncestorContainer.parentNode.parentNode.innerHtml=text; 
   						return;
   					}
   				}

   		
                if(text!="") {

                	if('action' in rel){
                		if(rel.action=='link'){
                			bootbox.prompt({
                				title:"Ссылка:",
                				value:"http://",
                				callback:function(lnk){
                					console.log(lnk);
                					if(lnk==null)
                						return;
                					range.deleteContents();
                					var link=$('<a href="'+lnk+'" target="_blank">'+text+'</a>').appendTo('body');
                					var element = document.createElement('a', {href:lnk});
                					element.innerHtml=text;
                					//range.insertNode(document.createTextNode('<a>'+text+'</a>'));     
                					range.insertNode(link[0]);     
									//range.insertNode($('<a href="'+lnk+'" >'+text+'</a>'));
                					//range.pasteHTML('<a href="'+lnk+'" >'+text+'</a>');
                					 
                				}
                			});
                			return;
                		}
                	}

                	if('pre' in rel){
   						var dlm='</'+rel.pre+'><'+rel.pre+'>';
   						text='<'+rel.pre+'>'+text.split("\n").join(dlm)+'</'+rel.pre+'>';
   					}
                    console.log("text",wrap[0]+text+wrap[1]);
                   // document.execCommand("formatBlock", false, 'span');
                   if(wrap[0]!=""){
                   	var link=$(wrap[0]+text+wrap[1]).appendTo('body');
                   	range.deleteContents();
                   range.insertNode(link[0]);
                   }else{
                   	range.deleteContents();
            		range.insertNode(document.createTextNode(text));
                   	//this.replaceSelectedText(text);
                  	 //window.getSelection().text=text;
                   	//document.execCommand("insertText", false,text);
                   }
                   
                    //document.execCommand("insertHTML", false, wrap[0]+text+wrap[1]);
                   // $(text).wrap('<span></span>');
                }else{
                    console.log("Nothing selected?");
                    var range = window.getSelection().getRangeAt(0);
      				//range.commonAncestorContainer.className="";
      				$(range.commonAncestorContainer.parentNode).removeAttr('class');
        			
      
                };
  },

  
// расширяем возможности редактора через контекстоное меню
extendStyles:function (name){
	var self=this;
	var style=self.styles[name];
	$(this.makeContextMenu(name,style)).appendTo('body');
		console.log('init context-menu',name);
			$('[data-smt="'+name+'"]').contextmenu({
				target:'#context-menu-'+name,
				//scopes:'[data-smt="'+name+'"]',
				onItem: function(context, e) {
			e.preventDefault();
			e.stopPropagation();
			//console.log(e.currentTarget,e.relatedTarget);
			var rel=$(e.currentTarget).attr('rel');
			console.log(rel,style[rel]);
    		self.wrapSelected(style[rel]);
	  }


			});
		
},

initStyles:function(){
	for(var name in this.styles){
		this.extendStyles(name);
	}
},

bindStyles:function(){
	for(var name in this.styles){
		$('[data-smt="'+name+'"]').addClass('smt-edit');
	}
},


initToolbar:function(){
	return;
	var self=this;
	$(this._tpl.tool_tpl).appendTo('body').on('click','div[rel]',function(){
		var rel=$(this).attr('rel');
		console.log(rel);

		switch(rel){
		
		case 'onoff':
			if(self._whait) return;
			$(this).find('span').html('whait...');
		if(self._edit_mode){
			self.destroy();
			$(this).find('span').html('on');
		}else{
			self.edit();
			$(this).find('span').html('off');
		}
		break;
		
		case 'save':
				self.save_tpl();
				return;
		break;

		case 'panel':
			//$('#smt-panel').removeClass('smt-hide');
		break;
		case 'html':
			console.log('click html');
			//$('#smt-panel').removeClass('smt-hide');
		break;

		


	}
		
	});
},

save_tpl:function(){

	$('#smt-mod-tool').appendTo('#smt-hide');
	console.log(this._url);

	this.destroy(this.saveAll);

},



saveAll:function(self){
	
	 $('#smt-content').find('[data-smt-include]').each(function(){
		var tpl=$(this).data('smt-include');
		$(this).html('[include:'+tpl+']');
	}).promise().done(function(){

	var	div=$('#smt-content').html();
	console.log(div);
	
	

	
	var datam={};
	var meta=$('[data-smtmeta]');
	var _url=self._url;
		if(meta.length!=0){
			for(var m=0; m < meta.length; m++){
				var t=$(meta[m]).data('smtmeta').toString().split(":");

				if(t.length==1){
				//	console.log(t[0]);
					datam[t[0]]=$(meta[m]).html();
				}else{
					//console.log(t[1],$(meta[m]).prop(t[0]));
                    if(t[0]=='src'){
                        var src=$(meta[m]).prop(t[0]).split("/");
                         datam[t[1]]=src.pop();
                    }else{
                        datam[t[1]]=$(meta[m]).prop(t[0]);
                    }
					
				}
				
				
			}
		}


		$.ajax({
			url:_url+'&act=save_content&type='+self.type,
			data:{tpl:div,meta:datam},
			type:"post",
			dataType:"json",
			success:function(d){
				bootbox.alert("Контент Записан!");
				self.activateSmt();
				self.parseInclude('#smt-content');
			
		}

	});

		});
},

editModal:function(div){
	//$("[data-smt^='img']")
	$(div).find('[data-smt]').each(function(){
		var d=$(this).data('smt');
		switch(d){
						case 'h':
							$(this).attr('contenteditable',true);
						break;
			}				
	});
},

loadTpl:function(){
	var self=this;	
	$.ajax({
		url:'/inc/tpl.html?v=2.0',
		dataType:"html",
		success:function(d){
		$(d).filter('.tpl').each(function(){
			
			self._tpl[$(this).attr('id')]=$(this).html();
		}).promise().done(function(){
			self.initToolbar();
			$(self._tpl.smt_tool_tpl).appendTo('#smt-hide');

			self.loadBlocks();

		});
	}});
},
 // load constructor templates
loadBlocks:function(){
		var self=this;	
	$.ajax({
		cache:false,
		url:self.opts.url_blocks,
		dataType:"html",
		success:function(d){
			//var set=$(d).find('body').data();
			//$('#smt-block-row').addClass(set.class);
		$(d).filter("[data-smt^='mod']").each(function(id){
			var htm=$(this)[0].outerHTML;
			var dt=$.extend({id: id, title:"Blok "+id,src:"/img/smt-block.png",content:htm},$(this).data());
			//console.log(dt);
			self._blocks[id]=htm;
			$(self.tpl(self._tpl.row_item,dt)).appendTo('#smt-block-row');
		}).promise().done(function(){
			console.log('load all blocks');
			//self.parseInclude('#smt-block-row');
			$('#smt-block-row').hover(function(e){e.stopPropagation();});

		});
	}});
},

// парсинг контента на приедмет инклудов
parseInclude:function(content){
	var self=this;
	console.info('parse include');
	

	$(content).find('[data-smt-include]').each(function(){
		var tpl=$(this).data('smt-include');
		var div=this;
	
		$.get('/smart?mod=smt&act=include&tpl='+tpl,function(d){
				$(div).html(d);
			});
		
	});
},

loadContent:function(url){
	console.log('load contebt',url);
		var self=this;	
	$.ajax({
		cache:false,
		url:url,
		dataType:"html",
		success:function(d){
			$('[itemtype="content"]').html(d);
			$("[data-smt^='mod']").addClass('smt-mod');
			self.imageMenu();
			self.initStyles();
			self.editModal('#smt-modal-edit');
			//self.bindStyles();
			self.parseInclude('#smt-content');
	}});
},

tpl:function(t,d){
	for( var i in d)
		t=t.split('['+i+']').join(d[i]);
	return t;
}

};

Smt.tableEdit=function(table,opt){
		var self=this;
		var o=$.extend({destroy:false},opt);
		var tool='<div class="smt-table-tool"><button class="btn btn-xs btn-success _add-row"><i class="fa fa-plus"></i> добавить строку</button>&nbsp;&nbsp;<button class="btn btn-xs btn-primary _add-head ml20">добавить заголовок</button> <button class="btn btn-xs _sort">сортировка</button></div>';
		var rowTool='<div class="smt-table-row"><a class="btn btn-xs btn-danger _del-row"><i class="fa fa-times"></i></a></div>';
	$(table).each(function(){
		var $ct=$(this).find('tbody');

		if(o.destroy){
			try{
				$ct.sortable( "disable" );
			}catch(e){
				
			}
			
			$ct.removeAttr('contenteditable');
			$ct.removeClass('smt-edit');
			$(this).unbind('click');
			$ct.find('.smt-table-tool').remove();
			$ct.find('.smt-table-row').remove();

			return;
		}

		$ct.addClass('smt-edit').attr('contenteditable',true);

		$ct.find('tr').each(function(){
			$(this).find('td:eq(0)').append(rowTool);

			//$(this).append(rowTool);
		});

		$(this).on('click','._del-row',function(){
			var tr=$(this).parentsUntil('tr').parent().remove();
		});

		$(tool).appendTo($ct).on('click','button',function(){
			if($(this).hasClass('_add-row')){
				var newRow=$ct.find('tr:eq(0)').clone().appendTo($ct);
				newRow.find('td').html(" - ");
				newRow.find('td:eq(0)').append(rowTool);
			}

		if($(this).hasClass('_add-head')){
			var newRow=$('<tr></tr>').appendTo($ct);
			var td=$('<th class="thead"> - </th>').appendTo(newRow);
			col=$ct.find('tr:eq(0) td').length;
			td.attr('colspan',col).append(rowTool);
		}
		if($(this).hasClass('_sort')){
			if($(this).hasClass('btn-warning')){
				$(this).removeClass('btn-warning');
				$ct.sortable( "disable" );
			}else{
				$(this).addClass('btn-warning');
				$ct.sortable({items:"tr"});
			}
			$ct.sortable({items:"tr"});
		}

		});
		
		
	});
};

Smt.activateSubmod=function(obj){
	var jsn=$(obj).data('smt').toString();
	var opt={};
	console.log(jsn);
	var t=jsn.split(",");
	for(var i=0;i<t.length;i++){
		var a=t[i].split(":");
		opt[a[0]]=a[1];
	}
		console.log(opt);
		
};



Smt.stringJson=function(jsn){

	var str=[];
	for(i in jsn)
		str.push(i+':'+jsn[i].toString());
	return str.join(",");
		
};

Smt.imageUpload=function(opt){
		var self=this;	
		
		console.log(opt);

		$('#smt-modal').html(self._tpl.wrap_tpl).removeClass('smt-hide');
		var jsn=$.extend({ prew:"0", target:false, path:"uploads/static" } ,self.parseJson(opt.json));
		console.log('jsn',jsn);

		$.ajax({
			url:"/smart/?mod=smt",
			data:{json:self.stringJson(jsn),src:opt.src,act:"upload"},
			type:'post',
			success:function(d){
				$(d).appendTo('#smt-wrap-upload');
				$('#smt-wrap-upload').on('update','#upload-smt',function(e,a){
					var ar=$.extend({'action':false},a);
					console.log('update',a);
					// update preview
					

					if(jsn.prew=="1"){
						console.log('set prew to ',opt.img);
						$(opt.img).attr("src","/"+a.path+'/'+(a.prew ? a.prew:a.fname));
					}else{
						$(opt.img).attr("src","/"+a.path+'/'+a.fname);
					}
					if(jsn.target!=false && a.prew){
						$(opt.href).attr("href","/"+a.path+'/'+a.fname);
					}
					
					
				});
			}
		});
};

Smt.imageMenu=function(){
	var self=this;
	console.log('Init image context menu...');
	$("[data-smt^='img']").contextmenu({ 
		target: '#smt-image-context-menu',
		//scopes:"[data-smt^='img']",
		onItem: function(context, e) {
			e.preventDefault();
			e.stopPropagation();
			var rel=$(e.currentTarget).attr('rel');
			var json=$(context).data('smt');
    		console.log(context,rel);
    		this.closemenu();
    		switch(rel){
    			case 'upload':
    			self.imageUpload({
    				json:json,
    				img:$(context).find('img'),
    				src:$(context).attr('href'),
    				href:context});
    			break;
    		}
	  }
});
};
Smt.Api={
    request:function(url,data,callback){
        $('body').addClass('loader');
        $.ajax({
            url:url,
            type:"post",
            dataType:"JSON",
            data:data,
            success:function(d){
                $('body').removeClass('loader');
                callback(d);
            },
            error:function(x,e){
                console.log(e);
                callback({ok:false,error:e});
            }
        });
    },

    json:function(url,data,callback){
    $.ajax({
        url:url,
        type:"POST",
        contentType:"application/json",
       // headers:{token:this.set.token},
        dataType:"json",
        data:JSON.stringify(data),
        success:function(d){
            callback(d);
        },
        error:function(x,e){
            console.log(e);
            callback({ok:false,error:e});
        }
    });
},
};