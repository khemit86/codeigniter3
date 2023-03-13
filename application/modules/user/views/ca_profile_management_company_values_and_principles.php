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
					<h4><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_title'):$this->config->item('ca_profile_management_company_values_and_principles_title'); ?></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
                <!-- Checkbox Start -->
				<div class="default_checkbox_button company_values_and_principles_chkbox four_checkbox">
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="vision_tab" name="contacts_management_checkbox" value="vision_tab" class="chk-btn company_values_principles" data-target="vision_container">
						<label class="singleLine_radioBtn" for="vision_tab">
							<span><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_vision_tab_label'):$this->config->item('ca_profile_management_company_values_and_principles_vision_tab_label'); ?></span>
						</label>
					</span>
					<span class="singleLine_chkBtn">
						<input type="checkbox" id="mission_tab" name="contacts_management_checkbox" value="mission_tab" class="chk-btn company_values_principles" data-target="mission_container">
						<label class="singleLine_radioBtn" for="mission_tab">
							<span><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_mission_tab_label'):$this->config->item('ca_profile_management_company_values_and_principles_mission_tab_label'); ?></span>
						</label>
					</span>
                    <span class="singleLine_chkBtn">
						<input type="checkbox" id="core_values_tab" name="contacts_management_checkbox" value="core_values_tab" class="chk-btn company_values_principles" data-target="core_values_container">
						<label class="singleLine_radioBtn" for="core_values_tab">
							<span><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_core_values_tab_label'):$this->config->item('ca_profile_management_company_values_and_principles_core_values_tab_label'); ?></span>
						</label>
					</span>
                    <span class="singleLine_chkBtn">
						<input type="checkbox" id="strategy_goals_tab" name="contacts_management_checkbox" value="strategy_goals_tab" class="chk-btn company_values_principles" data-target="strategy_goals_container">
						<label class="singleLine_radioBtn" for="strategy_goals_tab">
							<span><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_company_values_and_principles_strategy_goals_tab_label'):$this->config->item('ca_profile_management_company_values_and_principles_strategy_goals_tab_label'); ?></span>
						</label>
					</span>
				</div>
				<!-- Checkbox End -->
				<!-- Content Start -->			
				<div class="cmFieldText">
					<div id="vision_container" class="cmField d-none">
						
					</div>                                    
					<div id="mission_container" class="cmField d-none">
									
					</div>						
					<div id="core_values_container" class="cmField d-none">
						
					</div>						
					<div id="strategy_goals_container" class="cmField d-none">
						
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

// Company vision description
var ca_profile_management_company_vision_minimum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_vision_minimum_length_character_limit'); ?>";
var ca_profile_management_company_vision_maximum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_vision_maximum_length_character_limit'); ?>";

// company mission
var ca_profile_management_company_mission_minimum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_mission_minimum_length_character_limit'); ?>";
var ca_profile_management_company_mission_maximum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_mission_maximum_length_character_limit'); ?>";

// company core values
var ca_profile_management_company_core_values_minimum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_core_values_minimum_length_character_limit'); ?>";
var ca_profile_management_company_core_values_maximum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_core_values_maximum_length_character_limit'); ?>";

// company strategy and goals
var ca_profile_management_company_strategy_goals_minimum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_strategy_goals_minimum_length_character_limit'); ?>";
var ca_profile_management_company_strategy_goals_maximum_length_character_limit = "<?php echo $this->config->item('ca_profile_management_company_strategy_goals_maximum_length_character_limit'); ?>";

var characters_remaining_txt = "<?php echo $this->config->item('characters_remaining_message'); ?>";
</script>
<script src="<?= ASSETS ?>js/modules/ca_profile_management_company_values_and_principles.js"></script>