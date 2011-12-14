<?php
$uid = (int)$_REQUEST['uid'];
$modal = $_REQUEST['modal'];
$action = $_REQUEST['action'];

if($action == 'delete') :
	$wpdb->query("DELETE FROM $table WHERE `id`='$uid' AND `modal`='$modal'");
	echo "<div class='updated'><p>Deleted</p></div>";
endif;

if($action == 'approved'):
	$row = $wpdb->get_row("SELECT * FROM $table WHERE `id`='$uid' AND `modal`='$modal'");
	
	$where =array(
		'id' => $uid,
		'modal' => $modal,
	);
	$where_format = array('%d','%s');
	
	if($row->type == 'd'){
		$data = array(
			'auth_key' => '',
			'verified' => 'y'
		);		
		$data_format('%s','%s');		
	}
	else{
		$emails = unserialize($row->details);
		$new_emails = array();
		foreach($emails as $e=>$s){
			$new_emails[$e] = 'y';
		}
		$data = array(
			'details' => serialize($new_emails),
			'auth_key' => '',
			'verified' => 'y'
		);
		
		$data_format = array('%s','%d','%s');
		
		//adding modality into user meta table
		$user_modals = get_user_meta($uid,'_tern_wp_member_list',true);
		$modal_array = explode(', ', $user_modals);
		if(!$user_modals){
			$modal_array = array();	
		}
		
		if(!in_array($modal, $modal_array)){
			$modal_array[] = $modal;
			update_user_meta($uid,'_tern_wp_member_list',implode(', ',$modal_array));
		}
		
		//adding it to the options table
		$values = get_option('tern_wp_members');			
		if(!in_array($modal, $values['lists'])){	
			$values['lists'][] = $modal;
			update_option('tern_wp_members',$values);
		}
	
	}
	
	$wpdb->update($table,$data,$where,$data_format,$where_format);
	echo "<div class='updated'><p>Approved</p></div>";	
	
endif;