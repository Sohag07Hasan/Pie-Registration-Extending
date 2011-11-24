<?php
/*
 * Plugin Name: Pie Register Extending
 * author: Mahibul Hasan Sohag
 * author uri: http://sohag07hasan.elance.com
 * plugin uri: http://healerswiki.org
 * Description: The plugin extends both the pie register and the members plugin. Make sure both of them are activated. Also check the hooks are hooked in correct manner as described.
 * 
 * */

if(!class_exists('pie_register_extending')) :

	class pie_register_extending{
		
		var $user_modality = array();
		
		function __construct(){
			
			//adding extra fields in the pie registration form
			add_action('pie-registraion-form',array($this,'pie_registraion_extra_fields'),100);
			
			// css and js adding
			add_action('login_enqueue_scripts',array($this,'css_js_adding'));
			
			//saving the registration data
			add_action('pie_register_extending_save',array($this,'extending_save'),10,1);
			
			//valdating the registration data
			add_filter('registration_errors',array($this,'extending_errors'),50);
			
			//creation of db table
			register_activation_hook( __FILE__, array($this,'table_creation'));
			
			//delete users pie registration data while delte an user
			add_action('deleted_user',array($this,'delete_user_data'));
			//add_action('init',array($this,'init_checking'));
			
			//verify the users
			add_action('init',array($this,'validate_users'));

			add_filter('pie_register_email',array($this,'email_sanitizing'));
			
			//dashboard setup
			add_action('wp_dashboard_setup',array($this,'add_dashboard_widgets'));
			
			//dashboard javascript and css
			add_action('admin_enqueue_scripts',array($this,'dashboard_js_adding'),20);
			
			//ajax daata
			add_action('wp_ajax_pie_register_dashboard_approve',array($this,'dashboard_approve'));
		}
		
		/*******************************************************************************************
		 * 					DASHBOARD AJAX MANIPULATION
		 * ******************************************************************************************/
		function dashboard_approve(){
			$nonce = $_REQUEST['nonce'];
			$id = trim($_REQUEST['en_id']);
			
			include dirname(__FILE__) . '/includes/ajax_approve.php';
			
			exit;
		}
		
		//adding dashbaord js and css
		function dashboard_js_adding(){
			wp_register_style('pie_register_dashbaord_css', plugins_url('', __FILE__).'/css/dashboard.css');
			wp_enqueue_style('pie_register_dashbaord_css');
			
			wp_enqueue_script('jquery');
			wp_register_script('pie_register_dashbaord_js', plugins_url('', __FILE__).'/js/dashboard.js',array('jquery'));		
			wp_enqueue_script('pie_register_dashbaord_js');
			
			$nonce = wp_create_nonce('pie_register_extending');
			wp_localize_script( 'pie_register_dashbaord_js', 'PieRegister', array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => $nonce,
				'plugins_url' => plugins_url('',__FILE__)
			));
			
		}
		
		//adding dashbarod function
		function  add_dashboard_widgets(){
			wp_add_dashboard_widget('pie_register_widget', 'Users\' Pending Healing Modalites', array($this, 'dashboard_widget_function'));
		}
				
		// populating the dashboard
		function dashboard_widget_function(){
			include dirname(__FILE__) . '/includes/dashboard.php';
		}
		
		
		//email sanitizing
		function email_sanitizing($message){
			$message = str_replace('%user_healing_modalities%', implode(', ',$this->user_modality), $message);
			return $message;
		}
		
		//validate the register users
		function validate_users(){
			
			//validate by admin
			$message = '';
			if(isset($_GET['uid']) && isset($_GET['modal']) && isset($_GET['authkey']) && isset($_GET['admin'])){
				include dirname(__FILE__) . '/includes/admin-validate.php';
			}
			
			//validate by reference email
			if(isset($_GET['uid']) && isset($_GET['modal']) && isset($_GET['authkey']) && isset($_GET['rm'])){
				include dirname(__FILE__) . '/includes/ref-email-validate.php';
			}
			
			//confirmation message showing
			include dirname(__FILE__) . '/includes/confirmation-scrip.php';
			
		}
		
		//if an user is deleted this function will be clled
		function delete_user_data($id){
			global $wpdb;
			$table = $wpdb->prefix . 'pie_ext';
			$wpdb->query("DELETE FROM $table WHERE `id`=$id");
		}
		
		function init_checking(){
			$values = get_option('tern_wp_members');
			var_dump($values);
			global $wpdb;
			$table = $wpdb->prefix . 'pie_ext';
			$results = $wpdb->get_results("SELECT * FROM $table");
			//var_dump($results);
			if(!function_exists('wp_generate_password')) : 
				include ABSPATH . 'wp-includes/pluggable.php';
			endif;
			//wp_mail('hyde.sohag@gmail.com','gaga','gaga');
			exit;
		}
		
		//database table creation
		function table_creation(){
			global $wpdb;
			$table = $wpdb->prefix . 'pie_ext';
			$sql = "CREATE TABLE IF NOT EXISTS $table(
				`id` bigint unsigned NOT NULL,
				`modal` text NOT NULL,
				`type` varchar(10) NOT NULL,
				`details` text NOT NULL,
				`auth_key` varchar(50),
				`verified` varchar(10) NOT NULL
				)";
			if(!function_exists('dbDelta')) :
				include ABSPATH . 'wp-admin/includes/upgrade.php';
			endif;
			dbDelta($sql);	
		}
		
		//extra fileds
		function pie_registraion_extra_fields(){
			$all_options = get_option('tern_wp_members');
			$modalities = $all_options['lists'];
		?>
		<div>&nbsp;</div>
		
		<!-- upload type multipart -->
		<script type="text/javascript">	
			jQuery('#registerform').attr('enctype','multipart/form-data');
		</script>
		
		
		
		<div style="clear:both">
			<?php _e('<label>Healing Modalities:</label>', 'piereg');?>
			<br/><small style="font-style:italic">(if you selected any that you are a practitioner of, these will show publicly once they have been confirmed.)</small><br/>
			<?php 
				foreach($modalities as $modal){
				$input_id = preg_replace('/[ ]/','_',$modal);
				$div_id = $input_id . 'table';
				$sanitized = preg_replace('/[ ]/','',$modal);
			?>
			
			<!-- other default table -->
			<label for "healing_modalities[]">
				<p>
					<input id="<?php echo $input_id; ?>" class="default_listing"  type="checkbox" name="healing_modalities[]" value="<?php echo $modal; ?>" /> <?php echo $modal; ?>
				</p>
				
			</label>		
					
		
		<!-- pop up for regular options -->	
			
		<div class="div_for_popup" id="<?php echo $div_id; ?>" style="display:none;position:absolute;background-color:#eeeeee;width:580px;z-index:9002;">
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
					<input id="Other" class="default_listing" type="checkbox" name="healing_modalities[]" value="Other" /> Healing Modality Not Listed Yet
				</p>
			</label>
			
			<!-- pop up for other options -->		
			<div class="div_for_popup" id="Othertable" style="display:none;position:absolute;background-color:#eeeeee;width:580px;z-index:9002;">
				
				<h2 class="suggestion-text">Please Provide Details About Your Healing Modality</h2>
				<table class="popup_table_default">
					<tbody>
						<tr>
							<td colspan='2'>Healing Modality Name: </td>
							<td><input type="text" name="Other_name" /></td>
						</tr>
						<tr>
							<td colspan='2'>Description (min. 200 characters): </td>
							<td>
								<textarea cols="30" rows="5" name="Other_description" ></textarea>
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
		
		//form data manipulation 
		function extending_save($user){
			require dirname(__FILE__) . '/includes/extending-save.php';
			$this->user_modality = $modal_names_msg;
		}
		
		//error reporting
		function extending_errors($errors){
			require dirname(__FILE__) . '/includes/errors-checking.php';
			return $errors;
		}
		
	}
	
	$pie_extras = new pie_register_extending();

endif;

?>
