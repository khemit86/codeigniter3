<?php
$user = $this->session->userdata('user');	
if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
    
	if($user_detail['current_membership_plan_id'] == '1'){
		$user_skill_allowed = $this->config->item('pa_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed');
		
	}else{
		$user_skill_allowed = $this->config->item('pa_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed');
	}
	
	
} else {
   
	if($user_detail['current_membership_plan_id'] == '1'){
		$user_skill_allowed = $this->config->item('ca_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed');
	}else{
		$user_skill_allowed = $this->config->item('ca_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed');
	}
}
?>
<!-- Step 1st Start -->
<div class="pmFirstStep" id="initialViewSkills" style="<?php if (!empty($users_skills)){ echo "display:none;"; }else{ echo "display:block;"; }?>">
	<div  class="default_hover_section_iconText emailNew mrgBtm0 closeSkills">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 fontSize0 default_bottom_border">
				<i class="fas fa-universal-access"></i>
				<h6>
				<?php
				if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
						echo $this->config->item('pa_profile_management_skills_section_initial_view_title');
				}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
					echo $this->config->item('ca_app_profile_management_skills_section_initial_view_title');
				} else {
					echo $this->config->item('ca_profile_management_skills_section_initial_view_title');
				}
				?></h6>
			</div>
			<div class="col-md-12 col-sm-12 col-12">
				<p>
				<?php
				if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
					echo $this->config->item('pa_profile_management_skills_section_initial_view_content');
				}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y'){
					echo $this->config->item('ca_app_profile_management_skills_section_initial_view_content');
				} else {
					echo $this->config->item('ca_profile_management_skills_section_initial_view_content');
				}
				?></p>
			</div>
		</div>
	</div>
</div>
<!-- Step 1st End -->

<!-- Step 2nd Start -->
<div class="pmAllStep" id="addeditviewSkills" style="<?php if (!empty($users_skills)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
	<form id="user_skill_form">
		<input type="hidden" value="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" name="uid">	
		<div class="row pmtabtxt">
			<div class="col-md-12 col-sm-12 col-12 tlp0">
				<?php
				$skill_insert_option = 'display:block';
				if (count($users_skills) >= $user_skill_allowed) {
				$skill_insert_option = 'display:none';
				}
				?>
				<div id="userSkillForm">
					<div class="form-group skill_input_option" style="<?php echo $skill_insert_option; ?>">
						<input type="text" name="user_skill" id="user_skill" class="form-control avoid_space default_input_field" maxlength="<?php echo $this->config->item('profile_management_user_skill_maximum_length_character_limit'); ?>">
						<div class="error_div_sectn clearfix default_error_div_sectn">
							<span class="content-count user_skill_length_count_message"><?php echo $this->config->item('profile_management_user_skill_maximum_length_character_limit') . "&nbsp;" . $this->config->item('characters_remaining_message'); ?></span>
							<span id="skill_error" class="error_msg"></span>
						</div>
					</div>
					<div class="tagBottom" style="<?php if (!empty($users_skills)){ echo "display:block;"; }else{ echo "display:none;"; }?>">
					<ul id="tags-list" class="default_cross_tag">
						<?php
						if (!empty($users_skills)) {
							foreach ($users_skills as $user_skill) {
						?>
						<li class="tag_name" id="user_skill_<?php echo $user_skill['id'] ?>"><span><small><?php echo htmlspecialchars(trim($user_skill["user_skill"]), ENT_QUOTES); ?></small><input type="hidden" name="skill_name[]" value="<?php echo $user_skill['user_skill']; ?>" /><i class="fa fa-times delete_user_skill_row_data" data-attr="<?php echo 'user_skill_' . $user_skill['id'] ?>"></i></span></li>
						<?php
							}
						}	
						?>
						
					</ul>
				</div>
					<div class="amBtn topSeparte skill_input_option" id="userSkillButton" style="<?php echo $skill_insert_option; ?>">
						<button type="button" id="user_skill_save" class="btn blue_btn default_btn"><?php echo $this->config->item('save_btn_txt'); ?></button>
					</div>
				</div>
			</div>
		</div> 
	</form>
</div>
<!-- Step 2nd End -->