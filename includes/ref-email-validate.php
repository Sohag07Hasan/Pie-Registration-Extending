<?php
$uid = trim($_GET['uid']);
$uid = (int)$uid;
$email = trim($_GET['rm']);
$modal = trim($_GET['modal']);
$authkey = trim($_GET['authkey']);
$message = '';

global $wpdb;
$table = $wpdb->prefix . 'pie_ext';
$data = $wpdb->get_row("SELECT `modal`, `type`, `details` FROM $table WHERE `id`='$uid' AND `auth_key`='$authkey'");

if($data) : 
	$e_array = array();	
	$total_array = array();
	$total_format = array();
	
	$details = unserialize($data->details);
	if($details[$email] == 'n') :
		
		if($data->type == 'e' && $data->modal == $modal && is_array($details)){
			foreach($details as $em=>$status){			
				if($em == $email){
					$e_array[$em] = 'y';
				}
				elseif($status == 'y'){
					$authkey_remove = 1;
					$e_array[$em] = $status;
				}
				else{
					$e_array[$em] = $status;
				}
			}
			
			$details_data = serialize($e_array);
			$total_array['details'] = $details_data;
			$total_format[] = '%s';
			if($authkey_remove == 1){
				$total_array['authkey'] = '';
				$total_format[] = '%s';
				$total_array['verified'] = 'y';
				$total_format[] = '%s';
				$modals = get_user_meta($uid,'_tern_wp_member_list',true);
				$m_array = explode(', ', $modals);
				if(!in_array($modal, $m_array)){
					$modals .= ', ' . $modal;
					update_user_meta($uid,'_tern_wp_member_list',$modals);
				}
			}			
			$wpdb->update($table,$total_array,array('id'=>$uid),$total_format,array('%d'));			
			
			$username = get_user_meta($uid,'first_name',true) . ' ' . get_user_meta($uid,'last_name',true);
			$message = "Thank you for confirming $username as $modal practioner. <br/> Please feel free to stay and have a look around HealersWiki!";
		}
		elseif($data->type == 'e' && 'Other' == $modal && is_array($details)){
			$modal = $data->modal;
			foreach($details as $em=>$status){			
				if($em == $email){
					$e_array[$em] = 'y';
				}
				elseif($status == 'y'){
					$authkey_remove = 1;
					$e_array[$em] = $status;
				}
				else{
					$e_array[$em] = $status;
				}
			}
			
			$details_data = serialize($e_array);
			$total_array['details'] = $details_data;
			$total_format[] = '%s';
			if($authkey_remove == 1){
				$total_array['authkey'] = '';
				$total_format[] = '%s';
				$total_array['verified'] = 'y';
				$total_format[] = '%s';
				
				$modals = get_user_meta($uid,'_tern_wp_member_list',true);
				$m_array = explode(', ', $modals);
				if(!in_array($modal, $m_array)){
					$modals .= ', ' . $modal;
					update_user_meta($uid,'_tern_wp_member_list',$modals);
				}
				
				//updating options
				$values = get_option('tern_wp_members');			
				if(!in_array($modal, $values['lists'])){	
					$values['lists'][] = $modal;
					update_option('tern_wp_members',$values);
				}
			}			
			$wpdb->update($table,$total_array,array('id'=>$uid),$total_format,array('%d'));
			$username = get_user_meta($uid,'first_name',true) . ' ' . get_user_meta($uid,'last_name',true);
			$message = "Thank you for confirming $username as $modal practioner. <br/> Please feel free to stay and have a look around HealersWiki!";
		}
		else{
			$message = 'Invalid Url!';
		}
		
	else : 
		$message = 'Invalid URL!';
	endif;
else:
	$message = 'Invalid URL!';
endif;