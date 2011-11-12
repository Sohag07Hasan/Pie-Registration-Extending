
/*
 * This simply toggles the inserted div name from being displayed (display:block) to being hidden
 *	(display:none).
 */

function toggle(div_id){
	var el=document.getElementById(div_id);
	if(el.style.display=='none'){
		el.style.display='block';
	}
	else{
		el.style.display='none';
	}
}

/*
 * This resizes the blanket to fit the height of the page because there is not height=100%
 * attribute. This also centers the popUp vertically.
 */

function blanket_size(popUpDivVar){
	if(typeof window.innerWidth!='undefined'){
		viewportheight=window.innerHeight;
	}
	else{
		viewportheight=document.documentElement.clientHeight;
	}
	if((viewportheight>document.body.parentNode.scrollHeight)&&(viewportheight>document.body.parentNode.clientHeight)){
		blanket_height=viewportheight;
	}
	else{
		if(document.body.parentNode.clientHeight>document.body.parentNode.scrollHeight){
			blanket_height=document.body.parentNode.clientHeight;
		}
		else{
			blanket_height=document.body.parentNode.scrollHeight;
		}
	}
	var blanket=document.getElementById('blanket');blanket.style.height=blanket_height+'px';
	var popUpDiv=document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-150;
	popUpDiv.style.top=popUpDiv_height+'px';
}

/*
 * This centers the popUp vertically.
 * */

function window_pos(popUpDivVar){
	if(typeof window.innerWidth!='undefined'){
		viewportwidth=window.innerHeight;
	}
	else{
		viewportwidth=document.documentElement.clientHeight;
	}
	if((viewportwidth>document.body.parentNode.scrollWidth)&&(viewportwidth>document.body.parentNode.clientWidth)){
		window_width=viewportwidth;
	}
	else{
		if(document.body.parentNode.clientWidth>document.body.parentNode.scrollWidth){
			window_width=document.body.parentNode.clientWidth;
		}
		else{
			window_width=document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv=document.getElementById(popUpDivVar);
	window_width=window_width/2-260;
	popUpDiv.style.left=window_width+'px';
}

/*
 * This function contains the other three to make life simple in the HTML file.
 */

function popup(windowname){
	blanket_size(windowname);
	window_pos(windowname);
	toggle('blanket');
	toggle(windowname);
}


//starting the custom jquery and it controlls others
jQuery(document).ready(function($){
	
	
	
	
	//default popup
	$('.default_listing').bind('click',function(){
		
		if($(this).attr('checked') == 'checked'){
			var pop_div = $(this).attr('id') + 'table';			
			popup(pop_div);
			return false;
		}
					
	});
	
	//if cancel is pressed default
	$('.popup_cancel').bind('click',function(){
		var popup_id = $(this).attr('id').replace('_cancel','');
		var modal_list = '#' + popup_id.replace('table','');
		popup(popup_id);
		return false;
	});
	
	//submit the popup default
	$('.popup_submit').bind('click',function(){
		var popup_id = $(this).attr('id').replace('_submit','');		
		var modal_list = '#' + popup_id.replace('table','');
		$(modal_list).attr('checked','checked');			
		popup(popup_id);
		
	});
	
	
	//radion button controller
	$('.radio_document_default').bind('click',function(){
		var val = $(this).val();
		if(val == 'yes'){
			other_radio = 'yes'
			
			$('.popup_emails_default').css({'display':'none'});
			$('.popup_documents_default').css({'display':'inline'});		
			
									
		}
		if(val == 'no'){			
			other_radio = 'no'
			
			$('.popup_documents_default').css({'display':'none'});
			$('.popup_emails_default').css({'display':'inline'});
					
		}
	});
	
	//if other option is checked
	$('.other_listing').bind('click',function(){
		var div_id = $(this).attr('id').replace('table','div');
		popup(div_id);
	});
	
	//make the checkbox alwasy checked
	$('.default_checkbox').bind('click',function(){
		return false;
	});	
	
});
