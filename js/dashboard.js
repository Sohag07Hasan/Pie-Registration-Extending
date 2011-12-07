jQuery(document).ready(function($){
		
	$('.dashboard-resend').bind('click',function(){
		var con = confirm("are you sure!");
		if(con){
			var image = PieRegister.plugins_url + '/image/ajax-loader.gif';
			var image_element = '<img src="' + image + '"/>';
			var en_id = $(this).attr('id');
			var details = $(this).attr('href');
			
			var email_id = '#_' + en_id;			
			var email = $(email_id).val();
					
			$.ajax({				
				async: true,
				type:'post',
				url:PieRegister.ajaxurl,
				dataType: "json",
				cache:false,
				timeout:100000,
				data:{
					'action' : 'pie_register_refemail',
					'details' : details,
					'email' : email,
					'nonce' : PieRegister.nonce
				},
				
				success:function(result){
					if(result.e_s == 'n'){
						alert('Invalid Email!');
					}
					else{
						if(result.e == 'y'){
							alert('Email Sent');
						}
						else{
							alert('Email can\'t be sent! Please try again');
						}
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