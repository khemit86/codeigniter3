<?php
$user = $this->session->userdata('user');
if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
    
	if($user_detail['current_membership_plan_id'] == '1'){
		$allowed_service_provided = $this->config->item('pa_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed');
	}else{
		$allowed_service_provided = $this->config->item('pa_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed');
	}
} else {
   
	if($user_detail['current_membership_plan_id'] == '1'){
		$allowed_service_provided = $this->config->item('ca_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed');
	}else{
		$allowed_service_provided = $this->config->item('ca_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed');
	}
}	
?>
<!-- Step 1st Start -->
<div class="pmFirstStep" id="initialViewServiceProvided" style="<?php if (!empty($user_service_provided)){ echo "display:none;"; }else{ echo "display:block;"; }?>">
	<div  class="default_hover_section_iconText emailNew mrgBtm0 closeService_provider" >
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
				<i class="fas fa-hands"></i>
				<h6><?php
				if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
				echo $this->config->item('pa_profile_management_services_provided_section_initial_view_title');
				}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
					echo $this->config->item('ca_app_profile_management_services_provided_section_initial_view_title');
				} else {
				echo $this->config->item('ca_profile_management_services_provided_section_initial_view_title');
				}
				
				?></h6>
			</div>
			<div class="col-md-12 col-sm-12 col-12">
			<p><?php
			if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
					echo $this->config->item('pa_profile_management_services_provided_section_initial_view_content');
			}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
					echo $this->config->item('ca_app_profile_management_services_provided_section_initial_view_content');
			} else {
					echo $this->config->item('ca_profile_management_services_provided_section_initial_view_content');
			}
			?></p>
			</div>
		</div>
	</div>
</div>
<!-- Step 1st End -->

<!-- Step 2nd Start -->
<div class="pmAllStep" id="addeditviewServiceprovided" style="<?php if (!empty($user_service_provided)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
	<form id="service_provided_form">
		<input type="hidden" value="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" name="uid">
		<div class="row pmtabtxt">
			<div class="col-md-12 col-sm-12 col-12 tlp0">
			
				<?php
				$service_provided_insert_option = 'display:block';
				if (count($user_service_provided) >= $allowed_service_provided) {
				$service_provided_insert_option = 'display:none';
				}
				?>
				<div id="userSpForm">
					<div class="form-group service_provided_input_option" style="<?php echo $service_provided_insert_option; ?>">
						<input type="text" name="user_service_provided" id="user_service_provided" class="form-control avoid_space default_input_field" maxlength="<?php echo $this->config->item('profile_management_user_services_provided_maximum_length_character_limit'); ?>">
						<div class="error_div_sectn clearfix default_error_div_sectn">
							<span class="content-count service_provided_length_count_message"><?php echo $this->config->item('profile_management_user_services_provided_maximum_length_character_limit') . "&nbsp;" . $this->config->item('characters_remaining_message'); ?></span>
							<span id="service_provided_error" class="error_msg"></span>
						</div>
					</div>
					<div class="tagBottom" style="<?php if (!empty($user_service_provided)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
						<ul id="service-provided-list" class="default_cross_tag pinkbg_tag">
							<?php
							if (!empty($user_service_provided)) {
								foreach ($user_service_provided as $service_provided) {
							?>
								<li class="tag_name" id="service_provided_<?php echo $service_provided['id'] ?>"><span><small><?php echo htmlspecialchars(trim($service_provided["service_provided"]), ENT_QUOTES); ?></small><input type="hidden" name="service_provided_name[]" value="<?php echo $service_provided['service_provided']; ?>" /><i class="fa fa-times delete_service_provided_row_data" data-attr="<?php echo 'service_provided_' . $service_provided['id'] ?>"></i></span></li>
							<?php
								}
							}
							?>
							
						</ul>
					</div>
					
					<div class="amBtn topSeparte service_provided_input_option" id="userSpButton" style="<?php echo $service_provided_insert_option; ?>">
						<button type="button" id="user_service_provided_save" class="btn blue_btn default_btn"><?php echo $this->config->item('save_btn_txt'); ?></button>
					</div>
				</div>

				
					
			</div>
		</div> 
	</form>
</div>
<!-- Step 2nd End -->