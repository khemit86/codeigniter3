<?php 
$user = $this->session->userdata('user');

if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
    $profile_management_user_select_areas_of_expertise_category_initial_selection = $this->config->item('pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection');$profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection = $this->config->item('pa_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
	
	$area_of_expertise_heading = $this->config->item('pa_profile_management_competencies_page_areas_of_expertise_tab_txt');
	$skills_heading = $this->config->item('pa_profile_management_competencies_page_skills_tab_txt');
	$services_provided_heading = $this->config->item('pa_profile_management_competencies_page_services_provided_tab_txt');
	
	if($user_detail['current_membership_plan_id'] == '1'){
		$user_skill_allowed = $this->config->item('pa_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed');
		$allowed_service_provided = $this->config->item('pa_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed');
		
		
	}else{
		$user_skill_allowed = $this->config->item('pa_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed');
		$allowed_service_provided = $this->config->item('pa_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed');
	}
	
	
	
} else {
    $profile_management_user_select_areas_of_expertise_category_initial_selection = $this->config->item('ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_category_initial_selection');
    $profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection = $this->config->item('ca_profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection');
	if($user[0]->is_authorized_physical_person == 'Y'){
		$area_of_expertise_heading = $this->config->item('ca_app_profile_management_competencies_page_areas_of_expertise_tab_txt');
		$skills_heading = $this->config->item('ca_app_profile_management_competencies_page_skills_tab_txt');
		$services_provided_heading = $this->config->item('ca_app_profile_management_competencies_page_services_provided_tab_txt');
	}else{
		$area_of_expertise_heading = $this->config->item('ca_profile_management_competencies_page_areas_of_expertise_tab_txt');
		$skills_heading = $this->config->item('ca_profile_management_competencies_page_skills_tab_txt');
		$services_provided_heading = $this->config->item('ca_profile_management_competencies_page_services_provided_tab_txt');
	}
	
	if($user_detail['current_membership_plan_id'] == '1'){
		$user_skill_allowed = $this->config->item('ca_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed');
		$allowed_service_provided = $this->config->item('ca_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed');
	}else{
		$user_skill_allowed = $this->config->item('ca_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed');
		$allowed_service_provided = $this->config->item('ca_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed');
	}
	
	
}
if ($user_detail['current_membership_plan_id'] == 1) { 
	 $areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed');
      $areas_of_expertise_subcategory_limit = $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_subcategory_slots_allowed');
}else{
	 $areas_of_expertise_category_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed');
     $areas_of_expertise_subcategory_limit = $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_subcategory_slots_allowed');
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
        <div id="content" class="profile_competencies_page body_distance_adjust">
			<div class="etSecond_step" id="work_experience_listing_data" style="display:block;">
				<!-- Profile Management Text Start -->
				<div class="default_page_heading">
					<h4><div><?php echo $this->config->item('profile_management_headline_title_competencies'); ?></div></h4>
					<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
				</div>
				<!-- Profile Management Text End -->
                <!-- Checkbox Start -->
				<div class="default_checkbox_button profManagement_competencies_chkbox three_checkbox"><span class="singleLine_chkBtn"><input type="checkbox" id="areas_of_expertise_tab" name="contacts_management_checkbox" value="areas_of_expertise_tab" class="chk-btn competencies_tab" data-target="areas_of_expertise_container"><label class="singleLine_radioBtn" for="areas_of_expertise_tab"><span><?php echo $area_of_expertise_heading; ?></span></label></span><span class="singleLine_chkBtn"><input type="checkbox" id="skills_tab" name="contacts_management_checkbox" value="skills_tab" class="chk-btn competencies_tab" data-target="skills_container"><label class="singleLine_radioBtn" for="skills_tab"><span><?php echo $skills_heading; ?></span></label></span> <span class="singleLine_chkBtn"><input type="checkbox" id="services_provided_tab" name="contacts_management_checkbox" value="services_provided_tab" class="chk-btn competencies_tab" data-target="services_provided_container"><label class="singleLine_radioBtn" for="services_provided_tab"><span><?php echo $services_provided_heading; ?></span></label></span></div>
				<!-- Checkbox End -->
				
				<!-- Content Start -->
				<div class="cmFieldText">
					<div id="areas_of_expertise_container" class="cmField areas_expertise_section d-none">
						
					</div>
                                    
					<div id="skills_container" class="cmField skills_section d-none"></div>
                                    
					<div id="services_provided_container" class="cmField services_provided_section d-none"></div>	
				</div>			
				<!-- Checkbox Content End -->			
			</div>
        <!-- Right Section End -->
		</div>
    <!-- Middle Section End -->	
	</div>
<script type="text/javascript">
/************ Areas Of Expertise *****************/
<?php if ($user_detail['current_membership_plan_id'] == 1) { ?>
        var profile_management_user_areas_of_expertise_category = "<?php echo $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_category_slots_allowed'); ?>";
        //var profile_management_user_areas_of_expertise_subcategory = "<?php echo $this->config->item('user_profile_management_competencies_page_free_membership_subscriber_number_subcategory_slots_allowed'); ?>";
<?php } if ($user_detail['current_membership_plan_id'] == 4) { ?>
        var profile_management_user_areas_of_expertise_category = "<?php echo $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_category_slots_allowed'); ?>";
        //var profile_management_user_areas_of_expertise_subcategory = "<?php echo $this->config->item('user_profile_management_competencies_page_gold_membership_subscriber_number_subcategory_slots_allowed'); ?>";
<?php } ?>

    var profile_management_user_select_areas_of_expertise_category_initial_selection = "<?php echo $profile_management_user_select_areas_of_expertise_category_initial_selection; ?>";
    var profile_management_user_select_areas_of_expertise_subcategory_initial_selection = "<?php echo $profile_management_areas_of_expertise_section_select_areas_of_expertise_subcategory_initial_selection; ?>";
	var membership_page_url = "<?php echo $this->config->item('membership_page_url'); ?>";
	

    var areas_of_expertise_record = '';
<?php if ($record != '') { ?>
        var areas_of_expertise_record = '1';
<?php } ?>

    var areas_of_expertise_category_limit = '<?php echo $areas_of_expertise_category_limit ?>';
   // var areas_of_expertise_added_limit = '1';
<?php if ($areas_of_expertise_category_limit > $areas_of_expertise_category_added) { ?>
        //var areas_of_expertise_added_limit = '';
<?php } ?>

/********skills******/
var profile_management_user_skill_minimum_length_character_limit = "<?php echo $this->config->item('profile_management_user_skill_minimum_length_character_limit'); ?>";
var profile_management_user_skill_maximum_length_character_limit = "<?php echo $this->config->item('profile_management_user_skill_maximum_length_character_limit'); ?>";
var profile_management_user_skill_minimum_length_error_message = "<?php echo $profile_management_user_skill_minimum_length_error_message ; ?>";
var number_user_skill_allowed = "<?php echo $user_skill_allowed; ?>";
var characters_remaining_message = "<?php echo $this->config->item('characters_remaining_message'); ?>";

/********service provided******/
var profile_management_user_service_provided_minimum_length_character_limit = "<?php echo $this->config->item('profile_management_user_services_provided_minimum_length_character_limit'); ?>";
var profile_management_user_service_provided_maximum_length_character_limit = "<?php echo $this->config->item('profile_management_user_services_provided_maximum_length_character_limit'); ?>";
var profile_management_user_service_provided_minimum_length_error_message = "<?php echo $profile_management_user_service_provided_minimum_length_error_message; ?>";
var number_service_provided_allowed = "<?php echo $allowed_service_provided; ?>";


var uid = '<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>';
</script>
<script src="<?= ASSETS ?>js/modules/user_profile_management_competencies.js"></script>