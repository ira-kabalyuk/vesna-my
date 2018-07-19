
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-list fa-fw "></i> 
				Smart menu
		</h1>
	</div>

</div>

<div class="row">
<div class="col-md-12">
	<button class="btn btn-success" id="save_struct">save structure</button>

<button class="btn btn-primary ml20" id="add_new">add new</button></div>
</div>
<div class="dd mt20" id="nestable"></div>
	
<script type="text/template" id="tpl-nestable-li">
	<li class="dd-item dd3-item" data-id="[id]">
		<div class="dd-handle dd3-handle">&nbsp;</div>
		<div class="dd3-content" data-id="[id]" data-title="[title]" data-extens="[extens]" data-class="[class]" data-mod="[mod]">
			<span>[title]</span>

				<button class="_delete btn btn-xs pull-right btn-danger ml10" ><i class="fa fa-times"></i></button>
				<button class="_edit btn btn-xs pull-right btn-primary ml10" ><i class="fa fa-edit"></i></button>
				<button class="_onof btn btn-xs pull-right btn-[bclass] ml10" ><i class="fa fa-power-off"></i></button>
				
		</div>
	</li>
</script>
<script type="text/template" id="form-tpl">
<div class="smart-form">
	<fieldset id="add-fieldset">
	<section>
		<label class="input">Title</label>
		<input type="text" name="title" class="input-sm w400" value="[title]">	
	</section>
	<section>
		<label class="input">Link( as ?mods=mod_name)</label>
		<input type="text" name="extens" class="input-sm w200" value="[extens]">	
	</section>
	<section>
		<label class="input">Class (css icon)</label>
		<input type="text" name="class" class="input-sm" value="[class]">	
	</section>	
	<section>
		<label class="input">Mod name</label>
		<input type="text" name="mod" class="input-sm" value="[mod]">	
	</section>	
		
	</fieldset>
</div>
</script>
<script type="text/javascript">
	
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();

	// PAGE RELATED SCRIPTS
	
	// pagefunction
	
	var pagefunction = function() {

		function tpl(t,d){
			for(var i in d)	
				t=t.split('['+i+']').join(d[i]);
			return t;
		}

		function fieldset(div){
			var inp=$(div).find('input');
			var data={ };
			for(var i=0; i<inp.length; i++){
				var $i=$(inp[i]);
				data[$i.attr('name')]=$i.val();
			}
			return data;
		}
		
		$('#save_struct').on('click',function() {
			var json=JSON.stringify($('#nestable').nestable('serialize'));
			$.ajax({
				url:"{MOD_LINK}&act=save_structure",
				type:"post",
				data:{"cat":json},
				dataType:"json",
				success:function(d){
					Main.alert({title:"The order of the menu is updated"});
				}
			});
		});

		

		function makeList(data){
			$('#nestable').html("");
			var tpli=$('#tpl-nestable-li').html();
		Smart.makeNestable(
			'#nestable',data,
			{"li":tpli},
				function(){
					$('#nestable').nestable({group : 1});
				});
		};

		function dialog(data,callback){
			bootbox.dialog({
				html:true,						
  				message: tpl($('#form-tpl').html(),data),
  				title: data.titl,
  				buttons: {
    				success: {
      					label: "Save",
      					className: "btn-success w100",
      					callback: callback
    					},
    				danger: {
      					label: "Cancel",
      					className: "btn-danger w100"
    				}
  				}
		});
		}


		// With Login
		$("#add_new").click(function(e) {

			dialog({
				titl:"New menu item",
				title:"",
				mod:"",
				extens:"?mod=",
				class:"fa-icon"
			},function(){
				var data=fieldset('#add-fieldset');
				$.ajax({
					url:"{MOD_LINK}&act=add_new",
					type:"post",
					data:data,
					dataType:"json",
					success:function(d){
						console.log(d);
							makeList(d.data);
					}
				});
			});
	
		});
		

		$.ajax({
			url:"{MOD_LINK}&act=get_data",
			dataType:"json",
			success:function(d){
				if(d.ok){
					makeList(d.data);

			}
		}
		});

		
		$('#nestable').on('click','button._edit',function(e){
			console.log('click');
			e.preventDefault();
			e.stopPropagation();

			
			var dt=$(this).parent().data();
			var self=this;
			dt.titl="Change menu item name";

			dialog(dt,function(){
				var data=fieldset('#add-fieldset');
				$.ajax({
					url:'{MOD_LINK}&act=update_cat&el_id='+dt.id,
					type:"post",
					data:data,
					dataType:"json",
					success:function(d){
						if(d.ok){
							var tpl=$('#tpl-nestable-li').html();
							$('#nestable li[data-id="'+dt.id+'"]>').tpl(tpl,d.data,'replace');
							
						}
					}
				})
			});
			

		});
		

		$('#nestable').on('click','button._delete',function(e){
			var dt=$(this).parent().data();
			var li=$(this).closest('li');
			bootbox.confirm("Menu item <b>"+dt.title+'</b> will be delete, continue?',function(res){
				console.log(res,dt);
				if(res){
					$.ajax({
					url:'{MOD_LINK}&act=delete_cat&el_id='+dt.id,
					dataType:"json",
					success:function(d){
						console.log(d);
						li.remove();
					}
					})
				}
			})
		});


		$('#nestable').on('click','button._onof',function(e){
			var dt=$(this).parent().data();
			var li=$(this).closest('li');
			var self=this;
					$.ajax({
					url:'{MOD_LINK}&act=onof_cat&el_id='+dt.id,
					dataType:"json",
					success:function(d){
						console.log(d);
						if(d.data=='on'){
							var on="btn-default";
							var off="btn-success";
						}else{
							var off="btn-default";
							var on="btn-success";
						}

						$(self).removeClass(on);
						$(self).addClass(off);
					}
					})
			
		});



		
		
	};
	
	// end pagefunction
	
	// load nestable.min.js then run pagefunction
	loadScript("/smart/js/plugin/jquery-nestable/jquery.nestable.min.js", pagefunction);
	

</script>
