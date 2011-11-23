<?php
global $wpdb;
$table = $wpdb->prefix . 'pie_ext';

//$all_users = $wpdb->get_col("SELECT `ID` FROM $wpdb->users ");
//var_dump($all_users);
$pending_users = $wpdb->get_results("SELECT * FROM $table WHERE `verified`='y' ");
//var_dump($pending_users);

$pending_users_sanitized = array();

foreach($pending_users as $user){
	$pending_users_sanitized[$user->id][] = $user;
}
//var_dump($pending_users_sanitized);

echo '<div class="dashboard-pending-users">';

foreach($pending_users_sanitized as $user_id=>$san_user){
	$approve_class = $user_id . '-';
?>
	<div class="dashboard-pending-user" id="<?php echo 'pending-user-' . $user_id; ?>">	
		<b>Name: </b><?php echo get_user_meta($user_id,'first_name',true) . ' ' . get_user_meta($user_id,'last_name',true); ?><br/>
		<b>Healing Modality/Modalities: </b><br/>
	<?php 
		foreach ($san_user as $su){
			echo '<div class="pending-user-modality" id="pending-user-modality-'.$su->modal.'" >';
			echo "healing name: $su->modal <br/>";
			$m = preg_replace('/[ ]/', '^', $su->modal);
	
			$approve_class .= $m . '-';
			
			if($su->type == 'd'){
				$approve_class .= 'd';
				echo "verification type: Certificate <br/>";
				echo "view certificate: <a target='_blank' href='$su->details'> click</a><br/>";
			}
	
			if($su->type == 'e'){
				$approve_class .= 'e';
				
				echo "verification type: Reference Email<br/>";
				$emails = unserialize($su->details);
				
				echo "<b>Reference Eamils: </b> <br/>" ;
				echo '<span class="pending-ref-emails">';		
				foreach($emails as $email=>$status){
					if($status == 'n'){
						$s = 'pending';
					}
					else{
						$s = 'verified';
					}
					echo 'email: ' . $email . '<br/>';
					echo 'status: ' . $s . '<br/>';
					echo 'action: ' . "<a class='pending-mail-send' href='$email'>send ref mail</a><br/><br/>";
				}
				echo '</span>';
				
				
			}
			echo '<b>status: </b> pending &nbsp; click here to <a class="approve-the-pending" id="'.$approve_class.'" href="#">approve</a>';	
			echo '</div>'; // pending-user-modality
		}
		
	?>
	<hr noshade='noshade' />
	</div>
	
	
<?php 
}
echo '</div>';
