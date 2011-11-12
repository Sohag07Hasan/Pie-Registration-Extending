<?php

if(current_user_can('create_users')) : 
	
	$uid = trim($_GET['uid']);
	$modal = trim($_GET['modal']);
	$authkey = trim($_GET['authkey']);
	$uid = (int)$uid;
		
	global $wpdb;
	$table = $wpdb->prefix . 'pie_ext';
	
	$data = $wpdb->get_row("SELECT `modal`, `type` FROM $table WHERE `id`='$uid' AND `auth_key`='$authkey'");
	
	if($data){
		
		if($modal == $data->modal && $data->type == 'd'){
			$d_array = array(
						'verified' => 'y',
						'auth_key' => ''
					);
			$d_type = array('%s','%s');
			$wpdb->update($table,$d_array,array('uid'=>$uid),$d_type,array('%d'));
			
			$modals = get_user_meta($uid,'_tern_wp_member_list',true);
			$m_array = explode(', ', $modals);
			if(!in_array($modal, $m_array)){
				$modals .= ', ' . $modal;
				update_user_meta($uid,'_tern_wp_member_list',$modals);
			}
			
			$message = 'Verification completed!';
		}
		elseif($modal == 'Other' && $data->type == 'd'){
			$modal = $data->modal;
			$values = get_option('tern_wp_members');
			
			if(!in_array($modal, $values['lists'])){	
				$values['lists'][] = $modal;
				update_option('tern_wp_members',$values);
			}
					
			
			$d_array = array(
						'verified' => 'y',
						'auth_key' => ''
					);
			$d_type = array('%s','%s');
			$wpdb->update($table,$d_array,array('uid'=>$uid),$d_type,array('%d'));
			
			$modals = get_user_meta($uid,'_tern_wp_member_list',true);
			$m_array = explode(', ', $modals);
			if(!in_array($modal, $m_array)){
				$modals .= ', ' . $modal;
				update_user_meta($uid,'_tern_wp_member_list',$modals);
			}
			
			$message = "Verification completed and a new modal \"$modal\" is added!";
		}		
		else{
			$message = 'Invalid url!';
		}
		
	}
	else{
		$message = 'Invalid url!';
	}

	
else : 
	$message = 'You are either logged out or you do not have enough previlages to manage users!';
endif;