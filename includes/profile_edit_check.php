<?php
	
	/*
	 * Validates the user modality data inputed from their profile page 
	 * */
	
		
		foreach($m_names as $m_name){
			$sanitized = preg_replace('/[ ]/','',$m_name);
			
			
			if(empty($_POST[$sanitized.'_confirm']) || $_POST[$sanitized.'_confirm']=''){
				$key = 'confirmation';
				$message = "For inclusion in our public directory, healers must be adequately insured to practice their modality ($m_name) based on relevant legislation in their geographical area.";
				$this->message = $message;
				return ;
			}
			
			if(empty($_POST[$sanitized . '_document']) || $_POST[$sanitized . '_document']==''){
				$key = 'email_certificate';
				$message = "You must fill   any option for $m_name modal.";
				$this->message = $message;
				return;			
			}
			//var_dump($_POST[$sanitized . '_document']);
			if($_POST[$sanitized . '_document'] == 'yes'){
					//getting the files
					//var_dump($_FILES);
					$certificate = $_FILES[$sanitized . '_certificate'];
					
					$types = array('image/jpeg','image/png','application/pdf');
					if(!in_array($certificate['type'],$types)){
						$key = 'dcoument_format';
						$message = "Your Uploaded file type must be pdf/jpg/png for '$m_name' modal.";
						$this->message = $message;
						return;
					}
					
					$size = $certificate['size'] / 1000000;
					if((int)$size>2.1){
						$key = 'dcoument_size';
						$message = "Your Uploaded file must be within 2MB for $m_name modal.";
						$this->message = $message;
						return;
					}
			 }
				if($_POST[$sanitized . '_document'] == 'no'){
					if(!is_email(trim($_POST[$sanitized . '_email_1']))){
						$key = 'email_1';
						$message = "Your first Reference email is invalid for $m_name modal.";
						$this->message = $message;
						return;					
					}
					if(!is_email(trim($_POST[$sanitized . '_email_2']))){
						$key = 'email_2';
						$message = "Your second Reference email is invalid for $m_name modal.";
						$this->message = $message;
						return;
					}					
					if(trim($_POST[$sanitized . '_email_1']) == trim($_POST[$sanitized . '_email_2'])){
						$key = 'same_email';
						$message = "Reference emails must not be same.";
						$this->message = $message;
						return;
					}
				}
			
			
			//description
			if($sanitized == 'Other') : 
				if(strlen($_POST['Other_description'])<5){
					$key = 'description';
					$message = "No description Found For Other type.";
					$this->message = $message;
					return;
				}
			endif;
		}
	
