<?php

global $wpdb;
$table = $wpdb->prefix . 'pie_ext';

$ajax_return = array(
	'updated' => 'n',
	'id_hide' => ''
);

$variables = explode('-', $id);
$author_id = (int)$variables[0];
$modal = preg_replace('/\^/',' ',$variables[1]);
$type = $variables[2];

if($type = 'e') :

	$data = $wpdb->get_var("SELECT `details` FROM $table WHERE `id`='$author_id' AND `modal`='$modal' AND `type`='$type' ");
	$data_array = unserialize($data);
	$d = array();
	foreach ($data_array as $email=>$status){
		$d[$email] = 'y';
	}
	$u_d = serialize($d);
	
	if($wpdb->update($table,array('details'=>$u_d,'auth_key'=>'','verified'=>'y'),array('id'=>$author_id,'modal'=>$modal,'type'=>$type),array('%s','%s','%s'),array('%d','%s','%s'))){
		$ajax_return['updated'] = 'y';
	}

endif;

if($type = 'd'){
	if($wpdb->update($table,array('auth_key'=>'','verified'=>'y'),array('id'=>$author_id,'modal'=>$modal,'type'=>$type),array('%s','%s'),array('%d','%s','%s'))){
		$ajax_return['updated'] = 'y';
	}
}

$ajax_return['id_hide'] = '#pending-user-modality-' . $modal;

//inserting the data into default tables
$values = get_option('tern_wp_members');			
if(!in_array($modal, $values['lists'])){	
	$values['lists'][] = $modal;
	update_option('tern_wp_members',$values);
}
$user_modals = get_user_meta($author_id,'_tern_wp_member_list',true);
$modal_array = explode(', ', $user_modals);
if(!$user_modals){
	$modal_array = array();	
}

if(!in_array($modal, $modal_array)){
	$modal_array[] = $modal;
	update_user_meta($author_id,'_tern_wp_member_list',implode(', ',$modal_array));
}

$result = $wpdb->get_results("SELECT `details` FROM $table WHERE `id`='$author_id' AND `verified`='n' ");

//check for another modality of this user. If not then null the whole user dashbaord profile
if(!$result){	
	$ajax_return['id_hide'] = '#pending-user-' . $author_id;
}

echo json_encode($ajax_return);
