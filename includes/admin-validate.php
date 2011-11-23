<?php

if(current_user_can('create_users')) : 
	
	$uid = trim($_GET['uid']);
	$modal = trim($_GET['modal']);
	$authkey = trim($_GET['authkey']);
	$uid = (int)$uid;
	$m_array = array();
		
	global $wpdb;
	$table = $wpdb->prefix . 'pie_ext';
	
	$data = $wpdb->get_row("SELECT `modal`, `type` FROM $table WHERE `id`='$uid' AND `auth_key`='$authkey'");
	//var_dump($data);
	
	if($data) {
	
		if($modal = 'Other'){
			$modal = $data->modal;
			$values = get_option('tern_wp_members');			
			if(!in_array($modal, $values['lists'])){	
				$values['lists'][] = $modal;
				update_option('tern_wp_members',$values);
			}		
		}
						
		$wpdb->update($table,array('verified'=>'y','auth_key'=>''),array('id'=>$uid),array('%s','%s'),array('%d'));
		
		$user_modals = get_user_meta($uid,'_tern_wp_member_list',true);
		$modal_array = explode(', ', $user_modals);
		if(!$user_modals){
				$modal_array = array();	
		}
		
		if(!in_array($modal, $modal_array)){
			$modal_array[] = $modal;
			update_user_meta($uid,'_tern_wp_member_list',implode(', ',$modal_array));
		}
		$message = 'Completed';	
	}	
	else{
		$message = 'Invalid URL!';	
	}	
		
	
else :
	$login_url = get_option('siteurl') . '/wp-login.php';
	$message = 'You need to be logged in with an administrator account in order to manage users. <a href="'.$login_url.'"> Please log in </a>';
endif;