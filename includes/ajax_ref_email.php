<?php
/*
 * this script is to send reference email if admin wants to send a mail
 * 
 * */

global $wpdb;
$table = $wpdb->prefix . 'pie_ext';

$details = explode('|', $detail);
$id = (int)$details[0];

$modal = preg_replace('/\^/',' ',$details[1]);
$m_url = urlencode($modal);
$rm = urlencode($l_mail);
$activationkey = $wpdb->get_var("SELECT `auth_key` FROM $table WHERE `id`='$id' AND `modal`='$modal'");

$p_mail = $details[2];

$data = $wpdb->get_var("SELECT `details` FROM $table WHERE `id`='$id' AND `modal`='$modal'");
$datas = unserialize($data);

$updated_data = array();
foreach($datas as $email=>$status){
	if($email == $p_mail){
		$email = $l_mail;
	}
	$updated_data[$email] = $status;
}

if($wpdb->update($table,array('details'=>serialize($updated_data)),array('id'=>$id,'modal'=>$modal),array('%s'),array('%d','%s'))){
	$message['u'] = 'y';
}

//sending mail
$name = get_user_meta($id,'first_name',true) . ' ' . get_user_meta($id,'last_name',true);
$home = get_option('siteurl');
$link = $home . "/pie-registration/?uid=$id&modal=$m_url&rm=$rm&authkey=$activationkey";
$ref_message = "Dear Healer,\n$name has listed you as a reference in order for us to include him/her in our Healers Directory at http://www.HealersWiki.org If you can vouch for his/her credentials as a  \"$modal\" practitioner, please click the link below. If you do not know this person, or cannot vouch for their credentials, please accept our apologies for any inconvenience, and just ignore this email.\n\nTo confirm $name as a \"$modal\" practitioner:\n$link \n\nIf you are not already registered, you are of course also welcome to join for FREE at http://www.HealersWiki.org, and use all our interactive resources, including a community-built Wiki resource with information for healers, an international healing events calendar and of course the international healers directory.\n\n Warm wishes,\n Justin and Marcus\n Healers Wiki \n www.HealersWiki.org";

//setting up headers
$blogname = get_option('blogname');	
$site_mail = 'info@healerswiki.org';
$headers = 'From : '.$blogname.' < '.$site_mail.' >' . "\r\n" .
	'Reply-To: '. $site_mail . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
$subject = "Reference request for $name";

if(!function_exists('wp_mail')) : 
	include ABSPATH . 'wp-includes/pluggable.php';
endif;

if(wp_mail($l_mail,$subject,$ref_message,$headers)){
	$message['e'] = 'y';
}
