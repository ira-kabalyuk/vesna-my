var AjForm={
	showError:function (form,error){
	$(form).find('.error').remove();
		for(name in error){
			$(form).find('input[name="'+name+'"]').parent().append('<p class="error">'+error[name]+'</p>');
		}
	},
	submit:function(form){
		console.log("submit");
		this.send(form);
		return false;
	},

	send:function(form){
		var url="/api/form/"+$(form).data('fid');
		var self=this;
	$.ajax({
		url:url,
		data:$(form).serialize(),
		type:"post",
		dataType:"json",
		success:function(d){
			console.log(d);
			if(d.ok){
				if('url' in d){
					window.location.href=d.url;
					return;
				}
				
				if('html' in d){
					$(form).parent().html(d.html);
					return;
				}

				if('data' in d){
					$(form).html(d.data);
					return;
				}

				if('modal' in d){
					$(d.modal).trigger('ok');
					return;
				}


			}else{
				if('error' in d)
					self.showError(form,d.error);
			}
		}
	});
	}

};


$(function(){


$('form.ajax').on('submit',function(event){
	console.log('submit');
	event.preventDefault();
	AjForm.submit(this);
	return false;
	
});

$('form.ajax').each(function(){
	$('<input type="hidden" name="url" value="'+window.location.href.toString()+'">').appendTo(this);
	
});

	console.log("AjaxForm");
});