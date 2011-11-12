<?php
	
	
	//echo $_POST['Newuser_document'];
	
	
	$m_names = $_REQUEST['healing_modalities'];
	
//	var_dump($m_names);
	if($m_names){
		foreach($m_names as $m_name){
			$sanitized = preg_replace('/[ ]/','',$m_name);
			
			
			if(empty($_POST[$sanitized.'_confirm']) || $_POST[$sanitized.'_confirm']=''){
				$errors->add('confirmation', __("<strong>ERROR</strong>: You must have to confirm that, your country supports $m_name modal practices.",'piereg'));
			}
			
			if(empty($_POST[$sanitized . '_document']) || $_POST[$sanitized . '_document']==''){
				$errors->add('email_certificate', __("<strong>ERROR</strong>: You must fill   any option for $m_name modal.", 'piereg'));
			}
			//var_dump($_POST[$sanitized . '_document']);
				if($_POST[$sanitized . '_document'] == 'yes'){
					//getting the files
					//var_dump($_FILES);
					$certificate = $_FILES[$sanitized . '_certificate'];
					
					
					$types = array('image/jpeg','image/png','application/pdf');
					if(!in_array($certificate['type'],$types)){
						$errors->add('dcoument_format', __("<strong>ERROR</strong>: Your Uploaded file type must be pdf/jpg/png for '.$m_name.' modal.", 'piereg'));
					}
					
					$size = $certificate['size'] / 1000000;
					if((int)$size>2.1){
						$errors->add('dcoument_size', __("<strong>ERROR</strong>: Your Uploaded file must be within 2MB for $m_name modal.", 'piereg'));
					}
				}
				if($_POST[$sanitized . '_document'] == 'no'){
					if(!is_email(trim($_POST[$sanitized . '_email_1']))){
						$errors->add('email_1', __("<strong>ERROR</strong>: Your first Reference email is invalid for $m_name modal.", 'piereg'));
					}
					if(!is_email(trim($_POST[$sanitized . '_email_2']))){
						$errors->add('email_2', __("<strong>ERROR</strong>: Your second Reference email is invalid for $m_name modal.", 'piereg'));
					}					
					if(trim($_POST[$sanitized . '_email_1']) == trim($_POST[$sanitized . '_email_2'])){
						$errors->add('same_email', __("<strong>ERROR</strong>: Reference emails must not be same.", 'piereg'));
					}
				}
			
			
			//description
			if($sanitized == 'Other') : 
				if(strlen($_POST['Other_description'])<5){
					$errors->add('description', __("<strong>ERROR</strong>: No description Found For Other type.", 'piereg'));
				}
			endif;
		}
	}
	else{
		$errors->add('modal_unchecked', __("<strong>ERROR</strong>: Please choose atleast one healing  modality.", 'piereg'));
	}
