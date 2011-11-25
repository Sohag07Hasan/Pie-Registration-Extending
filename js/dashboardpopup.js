// popup js file to send email

function toggle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
	else {el.style.display = 'none';}
}

function blanket_size(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} 
	else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} 
	else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket = document.getElementById('blanket');
	blanket.style.height = blanket_height + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-150;//150 is half popup's height
	//popUpDiv.style.top = popUpDiv_height + 'px';
}

function window_pos(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	}
	else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	}
	else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	/*
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=window_width/2-150;//150 is half popup's width
	popUpDiv.style.left = window_width + 'px';
	*/
}

function popup(windowname) {
	blanket_size(windowname);
	window_pos(windowname);
	toggle('blanket');
	toggle(windowname);		
}

jQuery(document).ready(function($){
	
	//make the popup apper
	$('.pending-mail-send').bind('click',function(){
		var details = $(this).attr('id');
		var email = $(this).attr('href');
		$('#popuprefmail').val(email);
		$('#popuprefmail_details').val(details);
		popup('popUpDiv');
		return false;
	});
	
	//make the popup diapper
	$('#cross_image_button').bind('click',function(){
		popup('popUpDiv');
		return false;
	});
	
	//ajax calling if the send button is clicked
	$('#alreay-ref-mailsend').bind('click',function(){
		var email = $('#popuprefmail').val();
		var details = $('#popuprefmail_details').val();
				
		//calling ajax
		$.ajax({
			async: true,
			type:'post',
			url:PieRegister.ajaxurl,
			dataType: "json",
			cache:false,
			timeout:10000,
			data:{
				'action' : 'pie_register_refemail',
				'email' : email,
				'details' : details
			},
			
			success:function(result){
				var message = '';
				if(result.e_s == 'n'){
					alert('ERROR: email address invalid!');
					
					
				}
				else{
					if(result.e == 'y'){
						message += 'Reference email sent\n';
					}else{
						message += 'ERROR: Mail can\'t be sent. Please try again!\n';
					}
					if(result.u == 'y'){
						message += 'Email has been changed\n';
						$('#'+details).html(email);
					}
					else{
						message += 'ERROR: Email can\'t be updated! please try again! \n';
					}
					alert(message);
					popup('popUpDiv');
				}
				
				return false;
			},
			
			error: function(jqXHR, textStatus, errorThrown){
				jQuery('#footer').html(textStatus);
				alert(textStatus);
				//$('#' + en_id).html('approve');
				return false;
			}
		});
		
		
	});
});


