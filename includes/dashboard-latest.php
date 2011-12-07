<?php

$pending_users = $wpdb->get_results("SELECT * FROM $table WHERE `verified`='n' ");


$pending_users_sanitized = array();

foreach($pending_users as $user){
	$pending_users_sanitized[$user->id][] = $user;
}
//var_dump($pending_users_sanitized);
$user_profile = get_option('siteurl') . "/wp-admin/user-edit.php?user_id=";
//var_dump($pending_users_sanitized);
	
?>
<div class="wrap">
	<?php 
			foreach($pending_users_sanitized as $user_id=>$san_user){
				
		?>

	<div style="padding:10px;">
		<span style="float:left;"> <strong>Name: </strong> <?php echo get_user_meta($user_id,'first_name',true) . ' ' . get_user_meta($user_id,'last_name',true); ?> </span> <span style="float:right;"> <strong>Registration Date: </strong> Dec 12,2011</span>
	</div>
	<div style="clear:both;"></div>
	<table class="widefat" style="padding:5px;">
		<thead>
			<tr>
				<th>Healing Modalities</th> <th>Verification</th> <th>Status</th> <th>Action</th>		
			</tr>
		</thead>
		
		<tbody>
			<?php 
				foreach($san_user as $su){
				$del_link = $home . '/wp-admin/index.php?p_ext=yes&uid=' . $user_id . '&modal=' . urlencode($su->modal) . '&action=delete';
				$app_link = $home . '/wp-admin/index.php?p_ext=yes&uid=' . $user_id . '&modal=' . urlencode($su->modal) . '&action=approved';
				$m = preg_replace('/[ ]/', '', $su->modal);				
				$email_class = $su->id . '_' . $m;
				
				$n = preg_replace('/[ ]/', '^', $su->modal);
				$href = $su->id . '|' . $n;
				
				
			?>	
		
		<tr>			
				<td><?php echo $su->modal; ?></td>
				
				<?php 
					if($su->type == 'd') :
											
						echo "<td><a href='$su->details'>Certificate</a></td>";
						echo "<td>pending</td>";						
						echo "<td> <a href='$del_link'>Delete</a> / <a href='$app_link'>Approve</a> </td>";
		echo "</tr>";
						if($su->wiki_id > 0) : 
							
							$action = 'edit';
							$edit_link = $home . "/wp-admin/post.php?post=$su->wiki_id&action=$action";
							$post = get_post($su->wiki_id);
							$content = $post->post_content;
		echo "<tr>";
							echo "<td></td>";
							echo "<td colspan=2>$content</td>";
							echo "<td> <a href='$edit_link'>edit</a> </td>";
		echo "</tr>";
						endif;			
		
					
					else :
						echo "<td>Reference Emails</td>";
						echo "<td>pending</td>";
						echo "<td> <a href='$del_link'>Delete</a> / <a href='$app_link'>Approve</a> </td>";
						
						// getting new table rows
						$ref_mails = unserialize($su->details);
		echo "</tr>";
		echo "<tr>";
					$email_string = array();
					$pre_email = array();
					$status_string = array();
					$action_string = array();
					$ky = array();
						foreach($ref_mails as $mail=>$status){
							$email_string[] = $mail;							
							$status_string[] = ($status == 'n') ? 'pending' : 'approved';
							
						}
						foreach($email_string as $ey=>$sy){
							$a = $email_class;
							$a .= '_' . $ey;
							$b = '_' . $a;
							$h = $href;
							$h .= '|' . $sy;
							
							$ky[] = "<input type='text' class='stupid' id='$b' value='$sy' />";
							$action_string[] = "<a class='dashboard-resend' id='$a' href='$h'>Re-send</a>";
						}
						
							?>						
								
								<td></td>
								<td><?php echo implode('<br/>',$ky); ?></td>								
								<td><?php  echo implode('<br/><br/>',$status_string) ?></td>
								<td><?php  echo implode('<br/><br/>',$action_string) ?></td>							
							
							<?php 					
		echo "</tr>";
						if($su->wiki_id > 0):
							
							$action = 'edit';
							$edit_link = $home . "/wp-admin/post.php?post=$su->wiki_id&action=$action";
							$post = get_post($su->wiki_id);
							$content = $post->post_content;
		echo "<tr>";
							echo "<td></td>";
							echo "<td colspan=2>$content</td>";
							echo "<td> <a href='$edit_link'>edit</a> </td>";
		echo "</tr>";
						endif;
		
					endif;					
				?>
						
	</form>	<!-- every form -->
			<?php 
			} //second foreach			
			
			?>
	</tbody>			
	</table>
<?php } ?>

</div> <!-- wrap -->
