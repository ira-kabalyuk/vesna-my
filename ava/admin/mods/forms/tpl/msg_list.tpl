<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-send-o fa-fw "></i> 
				{MOD_TITLE}
		</h1>
	</div>

</div>

	<div class="row">
		<div class="col-md-1">Статус:</div>
		<div class="col-md-8 smart-form">
		<div class="inline-group" id="filterstatus">
			{FILTERSTATUS}
		</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-1">Форма:</div>
		<div class="col-md-8 smart-form">
			<div class="inline-group" id="filterforms">
		 {FILTERFORMS}
		</div>
		</div>
	</div>


<div class="well">
<div class="dataTables_wrapper" id="msg_list">
	
		{MSG_LIST}
</div>	
</div>

<script type="text/javascript">
$(function(){
	
	pageSetUp();

	Smart.dataTable({
		table:"#ul_messages",
		modlink:"{MOD_LINK}",
		dataurl:"{MOD_LINK}&act=get_data",
		limit:10,
		tool:["o","d"]
	});


	
		//изменение статуса сообщения
		$("#ul_messages").on('change','.status select',function(){
			var $p=$(this).parent();
			var id=$(this).parent().data('id');
			var s=$(this).val();
			console.log(id,s);
			$p.removeClassWild('status-*');
			$.get("{MOD_LINK}&act=set_status&id="+id+'&status='+s,function(d){
				console.log(d);
				$p.addClass('status-'+d);
			})
		});
	$("#ul_messages tbody").on('click','.shown',function(){
		var id=$(this).data('id');
		var self=this;
		var $p=$(this).parent();
		if(self.load){
			$p.find('.msg-form').slideToggle();
			return;
		} 
		$.get("{MOD_LINK}&act=view&id="+id,function(d){
			$p.append(d);
			self.load=true;
		});
	});

	$('#ip-ban-btn').click(function(){
			Smart.send({ div:'#msg-form',
				callback:function(d){
				$('#msg-info').html(d);
			}});
		});

	var fid=0;
	var st=0;
	$('#filterforms').on('change','input',function(){
			fid=$(this).val();
			//$(this).prop('checked',true);
		$("#ul_messages").dataTable().api().ajax.url("{MOD_LINK}&act=get_data&fid="+fid+"&status="+st).load();
		
	});

		$('#filterstatus').on('change','input',function(){
			st=$(this).val();
			//$(this).prop('checked',true);
		$("#ul_messages").dataTable().api().ajax.url("{MOD_LINK}&act=get_data&fid="+fid+"&status="+st).load();
		
	});

});		
</script>
