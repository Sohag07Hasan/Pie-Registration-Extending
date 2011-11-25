<?php
global $wpdb;
$table = $wpdb->prefix . 'pie_ext';

//$all_users = $wpdb->get_col("SELECT `ID` FROM $wpdb->users ");
//var_dump($all_users);
$pending_users = $wpdb->get_results("SELECT * FROM $table WHERE `verified`='n' ");
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
			echo '<div class="content-pending-user">';
			echo "healing name: $su->modal <br/>";
			$m = preg_replace('/[ ]/', '^', $su->modal);
	
			$approve_class .= $m . '-';
			$ref_email_id = $user_id . '-' . $m;
			
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
					$aw = $ref_email_id;
					$ref_email_id .= '-' . $email;
					if($status == 'n'){
						$s = 'pending';
					}
					else{
						$s = 'verified';
					}
					echo 'email: ' . $email . '<br/>';
					echo 'status: ' . $s . '<br/>';
					echo 'action: ' . "<a class='pending-mail-send' id='$ref_email_id' href='$email'>send ref mail</a><br/>";
					$ref_email_id = $aw;
				}
				echo '</span>';
				
				
			}
			echo '<b>status: </b> pending &nbsp; click here to <a class="approve-the-pending" id="'.$approve_class.'" href="#">approve</a>';	
			echo '</div>';
			echo '</div>'; // pending-user-modality
		}
		
	?>
	<hr noshade='noshade' />
	<br/>
	</div>
	
	
<?php 
}
echo '</div>';

// pop up for reference mail
echo '<div id="blanket" style="display:none;"></div>';
echo '<div id="popUpDiv" style="display:none;">
		<div class="popupdiv_class">
			<input style="width:275px;margin-top:15px;" type="text" id="popuprefmail" /><br/><br/>
			<input type="hidden" id="popuprefmail_details" value="" />
			<small style="color:#A52A2A">N.B: You can change the email address, this one will replace the previous one!</small>
			<br/><br/>
			<div class="cross_image">
				<a id="cross_image_button" href="#"><img width=30px; src="'.$image.'" alt="cancel" /></a>
			</div>
			<div class="send_email">
				<input id="alreay-ref-mailsend" type="button" value="send" class="button-secondary" />
			</div>
		</div>
	</div>';


