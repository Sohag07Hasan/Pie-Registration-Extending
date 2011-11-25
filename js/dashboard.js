jQuery(document).ready(function($){
	$('.approve-the-pending').click(function(){
		var con = confirm("are you sure!");
		if(con){
			var image = PieRegister.plugins_url + '/image/ajax-loader.gif';
			var image_element = '<img src="' + image + '"/>';
			var en_id = $(this).attr('id');
			
			$('#' + en_id).html(image_element);
			
			$.ajax({				
				async: true,
				type:'post',
				url:PieRegister.ajaxurl,
				dataType: "json",
				cache:false,
				timeout:10000,
				data:{
					'action' : 'pie_register_dashboard_approve',
					'nonce' : PieRegister.nonce,
					'en_id' : en_id
				},
				
				success:function(result){
					
					if(result.updated == 'y'){
						$('#' + en_id).html('approved');
						alert('approved');
						$(result.id_hide).css({'display':'none'});
						$(result.id_hide).html(null);
					}
					return false;
				},
				
				error: function(jqXHR, textStatus, errorThrown){
					jQuery('#footer').html(textStatus);
					alert(textStatus);
					$('#' + en_id).html('approve');
					return false;
				}
			});
			
		}		
		return false
	});
});