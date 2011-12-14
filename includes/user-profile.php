<?php
global $wpdb;
	$table = $wpdb->prefix . 'pie_ext';
	
	$values = get_option('tern_wp_members');
	$t_ms = $values['lists'];
	
	$pendings = $wpdb->get_results("SELECT * FROM $table WHERE `id`='$profileuser->ID' AND `verified`='n' ");
	$p_ms = array();
	foreach($pendings as $pending){
		$p_ms[] = $pending->modal;
	}			
	
	$r_ms = @explode(', ',$profileuser->_tern_wp_member_list);
	$pr_ms = array_merge($p_ms,$r_ms);
	
	$u_ms = array();
	foreach($t_ms as $m){
		if(in_array($m,$pr_ms)) continue;
		$u_ms[] = $m;
	}
	
?>
	<!-- upload type multipart -->
	<script type="text/javascript">	
		jQuery('#your-profile').attr('enctype','multipart/form-data');
	</script>

	<h3>Registered Helaing Modalities</h3>
	<table class='form-table'>
		<tr>
			<th>&nbsp;</th>		
			<td><?php echo $profileuser->_tern_wp_member_list; ?></td>
		</tr>				
	</table>
	
	<h3>Pending Helaing Modalities</h3>
	<?php
		if($pendings){ 
			include dirname(__FILE__) .'/profile-pending-modailities.php' ; 
		}
		else{
			echo "You have no pending Healing modality";
		}
	?>
	
	<!-- fields while login copy and paste -->
	<h3>Unregistered Healing Modalities</h3>
	<small style="font-style:italic">(if you selected any that you are a practitioner of, these will show publicly once they have been confirmed.)</small><br/>
		
	<div>
		<?php 
					foreach($u_ms as $modal){
					$input_id = preg_replace('/[ ]/','_',$modal);
					$div_id = $input_id . 'table';
					$sanitized = preg_replace('/[ ]/','',$modal);
				?>
				
				<input type="hidden" name="healing_profile_update" value="Y" />
				
				<!-- other default table -->
				
				
				<table class='form-table'>
					<tr>
						<th>&nbsp;</th>		
						<td>
							<label for "healing_modalities[]">					
								<input id="<?php echo $input_id; ?>" class="default_listing"  type="checkbox" name="healing_modalities[]" value="<?php echo $modal; ?>" /> <?php echo $modal; ?>
							</label>
						</td>
					</tr>				
				</table>	
						
			
			<!-- pop up for regular options -->	
				
			<div class="div_for_popup" id="<?php echo $div_id; ?>" style="display:none;position:absolute;background-color:#eeeeee;width:580px;z-index:9002;">
				<h2 class="suggestion-text">Please Provide your Information For <?php echo $modal; ?></h2>
				<table class="popup_table_default">
					<tbody>
						<tr>
							<td colspan="2">Please Confirm: </td>
							<td>
								<input class="default_checkbox" type="checkbox" name="<?php echo $sanitized.'_confirm' ?>" value="confirmed" /> I am adequately insured in my state / country to practice this healing modality
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						
						<tr>
							<td colspan="2">Document: </td>
							<td>
								<input type="radio" class="radio_document_default" name="<?php echo $sanitized . '_document'; ?>" value="yes" /> I am uploading a copy of my diploma as proof of qualification <br/>
								
								<input type="radio" class="radio_document_default" name="<?php echo $sanitized . '_document'; ?>" value="no" /> I am supplying two references who can be contacted as proof of my qualification 	
								
							</td>
						</tr>
					</tbody>
				</table>	
				
				<table style="display:none;" class="popup_documents_default">
					<tbody>
						<tr>
							<td colspan="2"> only pdf/jpg/png (Max 2MB) </td>
							<td  class="popup_file_d"><input style="font-size:17px;" name="<?php echo $sanitized . '_certificate'; ?>" type="file" /> </td>
						</tr>
					</tbody>
				</table>
				
				<table style="display:none;" class="popup_emails_default">
					<tbody>
						<tr>
							<td colspan="2">Reference no.1 email address:</td>
							<td><input type="text" name="<?php echo $sanitized . '_email_1'; ?>" /></td>
						</tr>
						<tr>&nbsp;</tr>
						<tr>
							<td colspan="2">Reference no.2 email address:</td>
							<td><input type="text" name="<?php echo $sanitized . '_email_2'; ?>" /></td>
						</tr>
					</tbody>
				</table>
							
				
				<div class="popup_submit_cancel_default">
					<a href="#" id="<?php echo $div_id.'_cancel' ?>" class="popup_cancel"><input type="button" value="cancel" class="button-secondary" /></a>&nbsp;&nbsp;
					<a href="#" id="<?php echo $div_id.'_submit' ?>" class="popup_submit"><input type="button" name="popup_submit" value="submit" class="button-secondary" /></a>
				</div>
					
			</div>
				
				
				
			<?php }//end of foreach ?>	
						
				
				<table class='form-table'>
					<tr>
						<th>&nbsp;</th>		
						<td>
							<label for "healing_modalities[]">					
								<input id="Other" class="default_listing" type="checkbox" name="healing_modalities[]" value="Other" /> Healing Modality Not Listed Yet
							</label>
						</td>
					</tr>				
				</table>	
				
				
				<!-- pop up for other options -->		
				<div class="div_for_popup" id="Othertable" style="display:none;position:absolute;background-color:#eeeeee;width:580px;z-index:9002;">
					
					<h2 class="suggestion-text">Please Provide Details About Your Healing Modality</h2>
					<br/>
					<table class="popup_table_default">
						<tbody>
							<tr>
								<td colspan='2'>Healing Modality Name: </td>
								<td><input style="width:368px;background-color:#FFFFE0" type="text" name="Other_name" /></td>
							</tr>
							<tr>
								<td colspan='2'>Description (min. 200 characters): </td>
								<td>
									<textarea style="background-color:#FFFFE0;width:368px" rows="5" name="Other_description" ></textarea>
									<br/>
									<p><small>
									NB: Please note that this description will be used in our public Wiki article about this healing modality - you will be able to add more there later
									</small></p>
								</td>
								
							</tr>
							<tr>
								<td colspan='2'>Please Confirm: </td>
								<td>
									<input class="default_checkbox" type="checkbox" name="Other_confirm" value="confirmed" /> I am adequately insured in my state / country to practice this healing modality
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							
							<tr>
								<td colspan="2">Document: </td>
								<td colspan="2">
									<input type="radio" class="radio_document_default" name="Other_document" value="yes" /> I am uploading a copy of my diploma as proof of qualification <br/>
									<input type="radio" class="radio_document_default" name="Other_document" value="no" /> I am supplying two references who can be contacted as proof of my qualification 	
								</td>
							</tr>
						</tbody>
					</table>	
					
					<table style="display:none;" class="popup_documents_default">
						<tbody>
							<tr>
								<td colspan="2">  only pdf/jpg/png (Max 2MB) </td>
								<td  class="popup_file_d"><input style="font-size:17px;" name="Other_certificate" type="file" /> </td>
							</tr>
						</tbody>
					</table>
				
					<table style="display:none;" class="popup_emails_default">
						<tbody>
							<tr>
								<td colspan="2">Reference no.1 email address:</td>
								<td><input type="text" name="Other_email_1" /></td>
							</tr>
							<tr>
								<td colspan="2">Reference no.2 email address:</td>
								<td><input type="text" name="Other_email_2" /></td>
							</tr>
						</tbody>
					</table>
									
				
					<div class="popup_submit_cancel_default">
						<a href="#" id="Othertable_cancel" class="popup_cancel"><input type="button" value="cancel" class="button-secondary" /></a>&nbsp;&nbsp;
						<a href="#" id="Othertable_submit" class="popup_submit"><input type="button" name="popup_submit" value="submit" class="button-secondary" /></a>
					</div>
					
					
							
				</div> <!-- popup_div_other -->
				
				
			
			
				<!-- white balnket over the body -->
				<div id="blanket" style="display:none;"></div>
	</div> <!--  end of the div original -->
	
	