<?php
	
	$wiki_id = 0;
	
	global $wpdb;
	$table = $wpdb->prefix . 'pie_ext';
	
	if(!function_exists('wp_generate_password')) : 
		include ABSPATH . 'wp-includes/pluggable.php';
	endif;
	
	$home = get_option('siteurl');
	
	$modal_names_msg = array();
	
	$u_id = $user->ID;
	$m_names = $_REQUEST['healing_modalities'];
	$attachmentlink = '';
	$final_message = "Someone wants to register for the following healing modality/modalities \n\n";
		
	$fullname_of_new_member = trim($_POST['firstname']) . ' ' . trim($_POST['lastname']);
	$user_eamil = trim($_POST['user_email']);
	$message = "Name: $fullname_of_new_member \n";
	$message .= "Email: $user_eamil \n\n";
	
	$blogname = get_option('blogname');
	//$site_mail = get_option('mailserver_login');
	$site_mail = 'info@healerswiki.org';
	$headers = 'From : '.$blogname.' < '.$site_mail.' >' . "\r\n" .
		'Reply-To: '. $site_mail . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	foreach($m_names as $m_name){
		$activationkey = wp_generate_password(20,false);
		$sanitized = preg_replace('/[ ]/','',$m_name);
		//$key = preg_replace('[ ]','@',$m_name);
		$modal = urlencode(trim($m_name));
		
		
		//description				
		
		if($sanitized == 'Other') :	
			$modal_name = trim($_POST['Other_name']);
			$modal_description = $_POST['Other_description'];	
			$wiki_array = array(
							'post_type' => 'incsub_wiki',
							'post_content' => $modal_description,
							'post_title' => $modal_name,
							'post_author' => $u_id
						);
			$wiki_id = wp_insert_post($wiki_array);
			$action = 'edit';
			$edit_link = $home . "/wp-admin/post.php?post=$wiki_id&action=$action";				
			$message .= "Modal name: $modal_name (suggested)\n";
			$message .= "Modal description: $modal_description \n";
			$message .= "Wiki Link: $edit_link \n";
			
		else : 
			$modal_name = trim($m_name);
			$message .= "Modal Name: $modal_name \n";
			$message .= "Modal Description: Already available \n";
			
		endif;			
	
		if($_POST[$sanitized . '_document'] == 'yes') :
						
			$certificate = $_FILES[$sanitized . '_certificate'];
			
			$temp = $certificate['tmp_name'];
			$updir = wp_upload_dir();				
			$basedir = $updir['basedir'];
			if( !is_dir( $basedir.'/pieregisterextending' ) ) @ mkdir( $basedir.'/pieregisterextending' );
			$t = time();
			$name = $certificate['name'];
			
			$s = preg_replace( '/([^.]+)/', "\${1}--$t", $name, 1 );
			$s = preg_replace('/[ ]/','',$s,1);
			@ move_uploaded_file($temp,$basedir.'/pieregisterextending/'.$s);						
			$attachmentlink = $updir['baseurl'].'/pieregisterextending/'.$s;
			$okay = 'okay';
			$link = $home ."/pie-registration/?admin=$okay&uid=$u_id&modal=$modal&authkey=$activationkey";
			
			$message .= "Attachment: $attachmentlink \n";
			$message .= "Verification: $link \n\n\n";
			
			$array = array(
						'id' => $u_id,
						'modal' => $modal_name,
						'wiki_id' => $wiki_id,
						'type' => 'd',
						'details' => $attachmentlink,
						'auth_key' => $activationkey,
						'verified' => 'n'
					);			
						
		
		else:
			
			$email_1 = trim($_POST[$sanitized . '_email_1']);
			$email_2 = trim($_POST[$sanitized . '_email_2']);
			$rm_1 = urlencode($email_1);
			$rm_2 = urlencode($email_2);
			
			$message .= "Reference emails: $email_1, $email_2 \n";
			$message .= "verification links has been sent to the reference emails. \n\n";
			
			$link_to_approve_1 = $home . "/pie-registration/?uid=$u_id&modal=$modal&rm=$rm_1&authkey=$activationkey";
			$link_to_approve_2 = $home . "/pie-registration/?uid=$u_id&modal=$modal&rm=$rm_2&authkey=$activationkey";
			
			$ref_message_1 = "Dear Healer,\n$fullname_of_new_member has listed you as a reference in order for us to include him/her in our Healers Directory at http://www.HealersWiki.org If you can vouch for his/her credentials as a  \"$modal_name\" practitioner, please click the link below. If you do not know this person, or cannot vouch for their credentials, please accept our apologies for any inconvenience, and just ignore this email.\n\nTo confirm $fullname_of_new_member as a \"$modal_name\" practitioner:\n$link_to_approve_1 \n\nIf you are not already registered, you are of course also welcome to join for FREE at http://www.HealersWiki.org, and use all our interactive resources, including a community-built Wiki resource with information for healers, an international healing events calendar and of course the international healers directory.\n\n Warm wishes,\n Justin and Marcus\n Healers Wiki \n www.HealersWiki.org";
			$ref_message_2 = "Dear Healer,\n$fullname_of_new_member has listed you as a reference in order for us to include him/her in our Healers Directory at http://www.HealersWiki.org If you can vouch for his/her credentials as a  \"$modal_name\" practitioner, please click the link below. If you do not know this person, or cannot vouch for their credentials, please accept our apologies for any inconvenience, and just ignore this email.\n\n To confirm $fullname_of_new_member as a \"$modal_name\" practitioner:\n$link_to_approve_2 \n\n If you are not already registered, you are of course also welcome to join for FREE at http://www.HealersWiki.org, and use all our interactive resources, including a community-built Wiki resource with information for healers, an international healing events calendar and of course the international healers directory.\n\n Warm wishes,\n Justin and Marcus\n Healers Wiki \n www.HealersWiki.org";
						
			$subject = "Reference request for $fullname_of_new_member";
			wp_mail($email_1,$subject,$ref_message_1,$headers);
			wp_mail($email_2,$subject,$ref_message_2,$headers);
			
			$emails = array(
										$email_1 => 'n',
										$email_2 => 'n'
									);
			$array = array(
						'id' => $u_id,
						'modal' => $modal_name,
						'wiki_id' => $wiki_id,
						'type' => 'e',
						'details' => serialize($emails),
						'auth_key' => $activationkey,
						'verified' => 'n'
					);
			
		endif;
		
		
		$wpdb->insert($table,$array,array('%d','%s','%d','%s','%s','%s','%s'));
		
		$modal_names_msg[] = $modal_name;
		
	}
	//$this->user_modality = $modal_names_msg;
	$final_message .= $message;
	//$admin_mail = get_option('admin_email');
	$admin_mail = 'hyde.sohag@gmail.com';
	wp_mail($admin_mail,'New Registration',$final_message,$headers);
	$message = '';	
