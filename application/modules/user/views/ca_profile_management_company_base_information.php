<?php 
	$user = $this->session->userdata('user');
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
        <div id="content">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_headline_title_company_base_information'):$this->config->item('ca_profile_management_headline_title_company_base_information'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
                <!-- Checkbox Start -->
				<div class="default_checkbox_button company_base_info_chkbox three_checkbox">
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="founded_in_tab" name="contacts_management_checkbox" value="founded_in_tab" class="chk-btn company_base_information" data-target="founded_in_container">
						<label class="singleLine_radioBtn" for="founded_in_tab">
							<span><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_founded_in_tab_label'):$this->config->item('ca_profile_management_base_information_founded_in_tab_label'); ?></span>
						</label>
					</span>
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="company_size_tab" name="contacts_management_checkbox" value="company_size_tab" class="chk-btn company_base_information" data-target="company_size_container">
						<label class="singleLine_radioBtn" for="company_size_tab">
							<span><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_size_tab_label'):$this->config->item('ca_profile_management_base_information_company_size_tab_label'); ?></span>
						</label>
					</span>
          <span class="singleLine_chkBtn">
						<input type="checkbox" id="opening_hours_tab" name="contacts_management_checkbox" value="opening_hours_tab" class="chk-btn company_base_information" data-target="opening_hours_container">
						<label class="singleLine_radioBtn" for="opening_hours_tab">
							<span><?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_opening_hours_tab_label'):$this->config->item('ca_profile_management_base_information_opening_hours_tab_label'); ?></span>
						</label>
					</span>
				</div>
				<!-- Checkbox End -->
				<!-- Content Start -->			
				<div class="cmFieldText">
					<div id="founded_in_container" class="cmField d-none">
						
					</div>                                    
					<div id="company_size_container" class="cmField d-none">
						
					</div>						
					<div id="opening_hours_container" class="cmField d-none">
						
						
					
					</div>
				</div>			
				<!-- Checkbox Content End -->			
			</div>
        <!-- Right Section End -->
		</div>
    <!-- Middle Section End -->	
	</div>
<script type="text/javascript">
var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
var ca_profile_management_base_information_company_opening_hours_no_location_available_warning_message = '<?php echo ($user_detail['is_authorized_physical_person'] == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'):$this->config->item('ca_profile_management_base_information_company_opening_hours_conflict_no_location_available_warning_message'); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/ca_profile_management_company_base_information.js"></script>