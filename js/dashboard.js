jQuery(document).ready(function($){
	$('.approve-the-pending').click(function(){
		var con = confirm("are you sure!");
		if(con){
			var en_id = $(this).attr('id');
						
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
					alert(result.id_hide);
					//reloading page
					//window.location = AffAjax.pageurl;
					return false;
				},
				
				error: function(jqXHR, textStatus, errorThrown){
					jQuery('#footer').html(textStatus);
					alert(textStatus);
					return false;
				}
			});
			
		}		
		return false
	});
});