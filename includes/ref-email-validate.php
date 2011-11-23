<?php
$uid = trim($_GET['uid']);
$uid = (int)$uid;
$email = trim($_GET['rm']);
$modal = trim($_GET['modal']);
$other = $modal;
$authkey = trim($_GET['authkey']);
$message = '';

global $wpdb;
$table = $wpdb->prefix . 'pie_ext';
$data = $wpdb->get_row("SELECT `modal`, `type`, `details` FROM $table WHERE `id`='$uid' AND `auth_key`='$authkey'");


if($data) :
	if($modal = 'Other'){
		$modal = $data->modal;		
	}

	$e_array = array();	
	$total_array = array();
	$total_format = array();
	
	$details = unserialize($data->details);
	//var_dump($details);
	if($details[$email] == 'n') :
		
		$details[$email] = 'y';
								
		$details_data = serialize($details);
		$wpdb->update($table,array('details'=>$details_data),array('id'=>$uid),array('%s'),array('%d'));
		
		$d = array();
		foreach($details as $d){
			$dy [] = $d;
		}
		
		if(!in_array('n', $dy)){
			
			$values = get_option('tern_wp_members');			
			if(!in_array($modal, $values['lists'])){	
				$values['lists'][] = $modal;
				update_option('tern_wp_members',$values);
			}
				
			$user_modals = get_user_meta($uid,'_tern_wp_member_list',true);
			$modal_array = explode(', ', $user_modals);
			if(!$user_modals){
				$modal_array = array();	
			}
			
			if(!in_array($modal, $modal_array)){
				$modal_array[] = $modal;
				update_user_meta($uid,'_tern_wp_member_list',implode(', ',$modal_array));
			}
			
			$wpdb->update($table,array('auth_key'=>'','verified'=>'y'),array('id'=>$uid),array('%s','%s'),array('%d'));
		}	
		
		$username = get_user_meta($uid,'first_name',true) . ' ' . get_user_meta($uid,'last_name',true);
		$message = "Thank you for confirming $username as $modal practioner. <br/> Please feel free to stay and have a look around HealersWiki!";	
	
	else:
		$message = 'Invalid URL!';
	endif;

else : 
	$message = 'Invalid URL!';
endif;	
