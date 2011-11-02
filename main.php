<?php
/*
 * Plugin Name: Pie Register Extending
 * author: Mahibul Hasan Sohag
 * author uri: http://sohag07hasan.elance.com
 * plugin uri: http://healerswiki.org
 * 
 * */

if(!class_exists('pie_register_extending')) :

	class pie_register_extending{
		
		function __construct(){
			add_action('pie-registraion-form',array($this,'pie_registraion_extra_fields'),100);
			add_action('login_enqueue_scripts',array($this,'css_js_adding'));
		}
		
		//extra fileds
		function pie_registraion_extra_fields(){
			$all_options = get_option('tern_wp_members');
			$modalities = $all_options['lists'];
		?>
		<div>&nbsp;</div>
		
		<div style="clear:both">
			<?php _e('<label>Healing Modalities:</label>', 'piereg');?>
			
			<?php 
				foreach($modalities as $modal){
				$input_id = preg_replace('/[ ]/','_',$modal);
				$div_id = $input_id . 'table';
				$sanitized = preg_replace('/[ ]/','',$modal);
			?>
			
			<!-- other default table -->
			<label for "healing_modalities[]">
				<p>
					<input id="<?php echo $input_id; ?>" class="default_listing" type="checkbox" name="healing_modalities[]" value="<?php echo $modal; ?>" /> <?php echo $modal; ?>
				</p>
				
			</label>		
					
		
		<!-- pop up for regular options -->		
		<div id="<?php echo $div_id; ?>" style="display:none;position:absolute;background-color:#eeeeee;width:580px;z-index:9002;">
			<h2 class="suggestion-text">Please Provide your Information</h2>
			<table class="popup_table_default">
				<tbody>
					<tr>
						<td colspan='2'>Please Confirm: </td>
						<td>
							<input class="default_checkbox" type="checkbox" name="<?php echo $sanitized.'_confirm' ?>" value="confirmed" /> I am adequately insured in my state / country to practice this healing modality
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					
					<tr>
						<td colspan="2">Document: </td>
						<td colspan="2">
							<input type="radio" class="radio_document_default" name="<?php echo $sanitized . '_document'; ?>" value="yes" /> I am uploading a copy of my diploma as proof of qualification <br/>
							
							<input type="radio" class="radio_document_default" name="<?php echo $sanitized . '_document'; ?>" value="no" /> I am supplying two references who can be contacted as proof of my qualification 	
							
						</td>
					</tr>
				</tbody>
			</table>	
			
			<table style="display:none;" class="popup_documents_default">
				<tbody>
					<tr>
						<td colspan="2">  only pdf (Max 2MB) </td>
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
			
			<label for "healing_modalities[]">
				<p>
					<input id="other_listing_table" class="other_listing" type="checkbox" name="healing_modalities[]" value="Other" /> Other
				</p>
			</label>
		
		
			<!-- white balnket over the body -->
			<div id="blanket" style="display:none;"></div>
			</div> <!--  end of the default table -->
		
		<?php 	
		}
		
		//css and js
		function css_js_adding(){
			if($_GET['action'] == 'register') : 
				wp_enqueue_script('jquery');
				wp_register_script('popup_js', plugins_url('', __FILE__).'/js/popup.js');
				wp_enqueue_script('popup_js');
				$path = plugins_url('', __FILE__).'/css/popup.css';
				
				/*
				if(!function_exists('wp_register_style')){
					include ABSPATH . 'wp-includes/functions.wp-styles.php';
				}
				wp_register_style('wp_popup_style', plugins_url('', __FILE__).'/css/popup.css');
				wp_enqueue_style('wp_popup_style',plugins_url('', __FILE__).'/css/popup.css');
				*/ 
				
				echo "<link  rel='stylesheet' type='text/css' href='$path'></link>"; 
			endif;
		}
		
	}
	
	$pie_extras = new pie_register_extending();

endif;

?>
