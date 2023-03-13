<?php 
	$user = $this->session->userdata('user');
	if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
		$profile_management_profile_definations_hourly_rate_tab = $this->config->item('pa_profile_management_profile_definitions_page_hourly_rate_tab_txt');
		
		$profile_management_profile_definations_description_tab = $this->config->item('pa_profile_management_profile_definitions_page_description_tab_txt');
		
		$profile_management_profile_definations_headline_tab = $this->config->item('pa_profile_management_profile_definitions_page_headline_tab_txt');
	}else if ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
		$profile_management_profile_definations_hourly_rate_tab = $this->config->item('ca_app_profile_management_profile_definitions_page_hourly_rate_tab_txt');
		
		$profile_management_profile_definations_description_tab = $this->config->item('ca_app_profile_management_profile_definitions_page_description_tab_txt');
		
		$profile_management_profile_definations_headline_tab = $this->config->item('ca_app_profile_management_profile_definitions_page_headline_tab_txt');
	} else {
		$profile_management_profile_definations_hourly_rate_tab = $this->config->item('ca_profile_management_profile_definitions_page_hourly_rate_tab_txt');
		
		$profile_management_profile_definations_description_tab = $this->config->item('ca_profile_management_profile_definitions_page_description_tab_txt');
		
		$profile_management_profile_definations_headline_tab = $this->config->item('ca_profile_management_profile_definitions_page_headline_tab_txt');
	}
	
?>
<div class="dashTop">		
    <!-- Menu Icon on Responsive View Start -->		
    <?php echo $this->load->view('user_left_menu_mobile.php'); ?>
    <!-- Menu Icon on Responsive View End -->		
    <!-- Middle Section Start -->
    <div class="wrapper wrapper1">
        <!-- Left Menu Start -->
        <?php echo $this->load->view('user_left_nav.php'); ?>
        <!-- Left Menu End -->
        <!-- Right Section Start -->
        <div id="content" class="profile_definitions_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><div><?php echo $this->config->item('profile_management_headline_title_profile_definitions'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
				<!-- Checkbox Start -->
				<div class="default_checkbox_button profManagement_profDefinitions_chkbox three_checkbox"><span class="singleLine_chkBtn"><input type="checkbox" id="headline_tab" name="contacts_management_checkbox" value="headline_tab" class="chk-btn profile_definitions_tab" data-target="headline_container"><label class="singleLine_radioBtn" for="headline_tab"><span><?php echo $profile_management_profile_definations_headline_tab; ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="description_tab" name="contacts_management_checkbox" value="description_tab" class="chk-btn profile_definitions_tab" data-target="description_container"><label class="singleLine_radioBtn" for="description_tab"><span><?php echo $profile_management_profile_definations_description_tab; ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="hourly_rate_tab" name="contacts_management_checkbox" value="hourly_rate_tab" class="chk-btn profile_definitions_tab" data-target="hourly_rate_container"><label class="singleLine_radioBtn" for="hourly_rate_tab"><span><?php echo $profile_management_profile_definations_hourly_rate_tab; ?></span></label></span></div>
				<!-- Checkbox End -->
				
				<!-- Content Start -->
				<div class="cmFieldText">
					<div id="headline_container" class="cmField d-none"></div>
					<div id="description_container" class="cmField d-none"></div>
					<div id="hourly_rate_container" class="cmField d-none"></div>
				</div>
				<!-- Content End -->			
			</div>
        <!-- Right Section End -->
		</div>
    <!-- Middle Section End -->
	</div>
<script>	
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
var profile_management_user_headline_minimum_length_character_limit = "<?php echo $this->config->item('profile_management_user_headline_minimum_length_character_limit'); ?>";
var profile_management_user_headline_maximum_length_character_limit = "<?php echo $this->config->item('profile_management_user_headline_maximum_length_character_limit'); ?>";



/*description*/
var profile_management_user_description_minimum_length_word_limit = "<?php echo $this->config->item('profile_management_user_description_minimum_length_word_limit'); ?>";
var profile_management_user_description_minimum_length_character_limit = "<?php echo $this->config->item('profile_management_user_description_minimum_length_character_limit'); ?>";
var profile_management_user_description_maximum_length_character_limit = "<?php echo $this->config->item('profile_management_user_description_maximum_length_character_limit'); ?>";
var characters_remaining_txt = "<?php echo $this->config->item('characters_remaining_message'); ?>";

</script>	
<script type="text/javascript"></script>
<script src="<?= ASSETS ?>js/modules/user_profile_management_profile_definitions.js"></script>