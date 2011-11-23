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

$result = $wpdb->get_results("SELECT `details` FROM $table WHERE `id`='$author_id' AND `verified`='n' ");
if(!$result){
	
	$ajax_return['id_hide'] = '#pending-user-' . $author_id;
}

echo json_encode($ajax_return);


