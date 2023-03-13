<?php
$user = $this->session->userdata ('user');
$feedback_message_tooltip = '';
$feedback_label_txt = '';

if($user[0]->user_id == $sp_data['user_id']){
	if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
		$fullname  = $po_data['first_name']." ".$po_data['last_name'];
		$first_name = $po_data['first_name'];
	}else{
		$fullname  = $po_data['company_name'];
		$first_name = $po_data['company_name'];
	}
	if($project_type != "fulltime"){
		$feedbacks_popup_modal_headline = $this->config->item('projects_users_ratings_feedbacks_popup_modal_headline');
		$feedbacks_popup_modal_headline = str_replace(array('{user_first_name_last_name_or_company_name}','{project_title}'),array($fullname,$project_data['project_title']),$feedbacks_popup_modal_headline);
		
		$feedback_label_txt = $this->config->item('projects_users_ratings_feedbacks_popup_feedback_sp_view');
		$feedback_message_tooltip = $this->config->item('projects_users_ratings_feedbacks_popup_feedback_message_tooltip_sp_view');
	}else{
		$feedbacks_popup_modal_headline = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_modal_headline');
		$feedbacks_popup_modal_headline = str_replace(array('{user_first_name_last_name_or_company_name}','{project_title}'),array($fullname,$project_data['project_title']),$feedbacks_popup_modal_headline);
		
		$feedback_label_txt = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_feedback_employee_view');
		$feedback_message_tooltip = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_feedback_message_tooltip_employee_view');
	}
}if($user[0]->user_id ==$po_data['user_id']){

	if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y' )){
		$fullname  = $sp_data['first_name']." ".$sp_data['last_name'];
		$first_name = $sp_data['first_name'];
	}else{
		$fullname  = $sp_data['company_name'];
		$first_name = $sp_data['company_name'];
	}

	if($project_type != "fulltime"){
		$feedbacks_popup_modal_headline = $this->config->item('projects_users_ratings_feedbacks_popup_modal_headline');
		$feedbacks_popup_modal_headline = str_replace(array('{user_first_name_last_name_or_company_name}','{project_title}'),array($fullname,$project_data['project_title']),$feedbacks_popup_modal_headline);
		
		$feedback_label_txt = $this->config->item('projects_users_ratings_feedbacks_popup_feedback_po_view');
		$feedback_message_tooltip = $this->config->item('projects_users_ratings_feedbacks_popup_feedback_message_tooltip_po_view');
	}else{
		$feedbacks_popup_modal_headline = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_modal_headline');
		$feedbacks_popup_modal_headline = str_replace(array('{user_first_name_last_name_or_company_name}','{project_title}'),array($fullname,$project_data['project_title']),$feedbacks_popup_modal_headline);
		
		$feedback_label_txt = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_feedback_employer_view');
		$feedback_message_tooltip = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_feedback_message_tooltip_employer_view');
	}
	
}

?>
<form id="rating_feedback_form">
<div class="hP">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="popup_body_semibold_title"><?php echo $feedbacks_popup_modal_headline; ?></div>
		</div>
		<input type="hidden" id="project_type" name = "project_type" value="<?php echo $project_type;?>" >
		<input type="hidden" id="view_type"  name = "view_type" value="<?php echo $view_type;?>" >
		<input type="hidden" id="po_id"  name="po_id" value="<?php echo Cryptor::doEncrypt($po_data['user_id']);?>" >
		<input type="hidden" id="sp_id" name="sp_id" value="<?php echo Cryptor::doEncrypt($sp_data['user_id']);?>" >
		<input type="hidden" id="project_id" name="project_id" value="<?php echo $project_data['project_id'];?>" >
		<input type="hidden" id="section_name" name="section_name" value="<?php echo $section_name;?>" >
		<input type="hidden" id="section_id" name="section_id" value="<?php echo $section_id;?>" >
		
		<?php
		if($user[0]->user_id ==$po_data['user_id']){
			if($project_type != 'fulltime'){
		?>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_project_delivered_within_agreed_budget_po_view') ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_budget" id="project_delivered_within_agreed_budget_yes" name="project_delivered_within_agreed_budget" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="project_delivered_within_agreed_budget_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_budget" id="project_delivered_within_agreed_budget_no"  name="project_delivered_within_agreed_budget" value="N">
						<label class="custom-control-label default_black_regular_medium" for="project_delivered_within_agreed_budget_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="project_delivered_within_agreed_budget_error"></small>
					
				</div>
			</div>
			
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_work_delivered_within_agreed_time_slot_po_view') ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="work_delivered_within_agreed_time_slot_yes" name="work_delivered_within_agreed_time_slot" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="work_delivered_within_agreed_time_slot_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="work_delivered_within_agreed_time_slot_no" name="work_delivered_within_agreed_time_slot" value="N">
						<label class="custom-control-label default_black_regular_medium" for="work_delivered_within_agreed_time_slot_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="work_delivered_within_agreed_time_slot_error"></small>  
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php
					if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
						if($sp_data['is_authorized_physical_person'] == 'Y'){
							if($sp_data['gender'] =='M' ){
								echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_app_male_again_po_view'));
							}else{
								echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_app_female_again_po_view'));
							}
						}else{
							if($sp_data['gender'] =='M' ){
								echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_male_again_po_view'));
							}else{
								echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_female_again_po_view'));
							}
						}
					}else{
						echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_hire_sp_company_again_po_view')); 
					}
					?>
				</div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_hire_sp_again_yes" name="would_you_hire_sp_again" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="would_you_hire_sp_again_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_hire_sp_again_no" name="would_you_hire_sp_again" value="N">
						<label class="custom-control-label default_black_regular_medium" for="would_you_hire_sp_again_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="would_you_hire_sp_again_error"></small>  
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php
				if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
					if($sp_data['is_authorized_physical_person'] == 'Y'){
						if($sp_data['gender'] =='M' ){
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_male_po_view'));
						}else{
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_app_female_po_view'));
						}
					}else{
						if($sp_data['gender'] =='M' ){
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_male_po_view'));
						}else{
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_female_po_view'));
						}
					}
				}else{
					echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_sp_company_po_view')); 
				}
				?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_recommend_sp_yes" name="would_you_recommend_sp" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="would_you_recommend_sp_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_recommend_sp_no" name="would_you_recommend_sp" value="N">
						<label class="custom-control-label default_black_regular_medium" for="would_you_recommend_sp_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="would_you_recommend_sp_error"></small>   
				</div>
			</div>
		<?php
			}else{
		?>
		
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_shows_interest_enthusiasm_for_work_employer_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_shows_interest_enthusiasm_for_work_yes" name="employee_shows_interest_enthusiasm_for_work" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="employee_shows_interest_enthusiasm_for_work_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_shows_interest_enthusiasm_for_work_no" name="employee_shows_interest_enthusiasm_for_work" value="N">
						<label class="custom-control-label default_black_regular_medium" for="employee_shows_interest_enthusiasm_for_work_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="employee_shows_interest_enthusiasm_for_work_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_competency_in_knowledge_skills_employer_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_demonstrates_competency_in_knowledge_skills_yes" name="employee_demonstrates_competency_in_knowledge_skills" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="employee_demonstrates_competency_in_knowledge_skills_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_demonstrates_competency_in_knowledge_skills_no" name="employee_demonstrates_competency_in_knowledge_skills" value="N">
						<label class="custom-control-label default_black_regular_medium" for="employee_demonstrates_competency_in_knowledge_skills_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="employee_demonstrates_competency_in_knowledge_skills_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_levels_of_skill_knowledge_employer_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_demonstrates_levels_of_skill_knowledge_yes" name="employee_demonstrates_levels_of_skill_knowledge" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="employee_demonstrates_levels_of_skill_knowledge_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_demonstrates_levels_of_skill_knowledge_no" name="employee_demonstrates_levels_of_skill_knowledge" value="N">
						<label class="custom-control-label default_black_regular_medium" for="employee_demonstrates_levels_of_skill_knowledge_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="employee_demonstrates_levels_of_skill_knowledge_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_dependable_and_reliable_employer_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_dependable_and_relied_yes" name="employee_dependable_and_relied" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="employee_dependable_and_relied_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_dependable_and_relied_no" name="employee_dependable_and_relied" value="N">
						<label class="custom-control-label default_black_regular_medium" for="employee_dependable_and_relied_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="employee_dependable_and_relied_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_properly_organizes_prioritizes_employer_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_properly_organizes_prioritizes_yes" name="employee_properly_organizes_prioritizes" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="employee_properly_organizes_prioritizes_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="employee_properly_organizes_prioritizes_no" name="employee_properly_organizes_prioritizes" value="N">
						<label class="custom-control-label default_black_regular_medium" for="employee_properly_organizes_prioritizes_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="employee_properly_organizes_prioritizes_error"></small>   
				</div>
			</div>
		
		<?php
			}
		}
		if($user[0]->user_id ==$sp_data['user_id']){
			if($project_type != 'fulltime'){
		?>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php
				if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
					if($po_data['is_authorized_physical_person'] == 'Y'){
						if($po_data['gender'] =='M' ){
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_male_sp_view'));
						}else{
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_app_female_sp_view'));
						}
					}else{
						if($po_data['gender'] =='M' ){
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_male_sp_view'));
						}else{
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_female_sp_view'));
						}
					}
				}else{
					echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_work_again_with_po_company_sp_view')); 
				}
				?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_work_again_with_po_yes" name="would_you_work_again_with_po" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="would_you_work_again_with_po_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_work_again_with_po_no" name="would_you_work_again_with_po" value="N">
						<label class="custom-control-label default_black_regular_medium" for="would_you_work_again_with_po_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="would_you_work_again_with_po_error"></small>   
				</div>
			</div>
			
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php
				if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
					if($po_data['is_authorized_physical_person'] == 'Y'){
						if($po_data['gender'] =='M' ){
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_app_male_sp_view'));
						}else{
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_app_female_sp_view'));
						}
					}else{
						if($po_data['gender'] =='M' ){
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_male_sp_view'));
						}else{
							echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_female_sp_view'));
						}
					}
				}else{
					echo str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('projects_users_ratings_feedbacks_would_you_recommend_po_company_sp_view')); 
				}
				?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_recommend_po_yes" name="would_you_recommend_po" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="would_you_recommend_po_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="would_you_recommend_po_no" name="would_you_recommend_po" value="N">
						<label class="custom-control-label default_black_regular_medium" for="would_you_recommend_po_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="would_you_recommend_po_error"></small> 
				</div>
			</div>
		
		<?php
			}else{
		?>
		
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_appreciated_right_level_employee_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="appreciated_right_level_yes" name="appreciated_right_level" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="appreciated_right_level_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="appreciated_right_level_no" name="appreciated_right_level" value="N">
						<label class="custom-control-label default_black_regular_medium" for="appreciated_right_level_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="appreciated_right_level_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_empowered_take_extra_responsibilities_employee_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="empowered_take_extra_responsibilities_yes" name="empowered_take_extra_responsibilities" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="empowered_take_extra_responsibilities_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="empowered_take_extra_responsibilities_no" name="empowered_take_extra_responsibilities" value="N">
						<label class="custom-control-label default_black_regular_medium" for="empowered_take_extra_responsibilities_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="empowered_take_extra_responsibilities_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recognition_work_achievements_employee_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="recognition_work_achievements_yes" name="recognition_work_achievements" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="recognition_work_achievements_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="recognition_work_achievements_no" name="recognition_work_achievements" value="N">
						<label class="custom-control-label default_black_regular_medium" for="recognition_work_achievements_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="recognition_work_achievements_error"></small>   
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_receive_regular_consistent_feedback_employee_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="receive_regular_consistent_feedback_yes" name="receive_regular_consistent_feedback" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="receive_regular_consistent_feedback_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="receive_regular_consistent_feedback_no" name="receive_regular_consistent_feedback" value="N">
						<label class="custom-control-label default_black_regular_medium" for="receive_regular_consistent_feedback_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="receive_regular_consistent_feedback_error"></small>   
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_recommend_this_company_employee_view'); ?></div>
				<div class="radioYN">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="recommend_this_company_yes" name="recommend_this_company" value="Y">
						<label class="custom-control-label default_black_regular_medium" for="recommend_this_company_yes"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_yes'); ?></span></label>
					</div><div class="custom-control custom-radio custom-control-inline">
						<input type="radio" class="custom-control-input delivery_time" id="recommend_this_company_no" name="recommend_this_company" value="N">
						<label class="custom-control-label default_black_regular_medium" for="recommend_this_company_no"><span class="rangeRadio"></span><span><?php echo $this->config->item('projects_users_ratings_feedbacks_radio_button_label_no'); ?></span></label>
					</div><small class="error_msg" id="recommend_this_company_error"></small>   
				</div>
			</div>
			
			
		<?php
			
			}
		}
		?>
	</div>
</div>
<?php
if($user[0]->user_id ==$po_data['user_id']){
	if($project_type != 'fulltime'){
?>	
    <div class="sliderAdjust poview">
	<!-- Quality Range Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_quality_po_view'); ?><p class="error_msg" id="quality_of_work_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="quality">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="quality_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="quality_range" name="quality_range" value="" />
				
			</div>
		</div>
	</div>
	<!-- Quality Range Slider End -->
	<!-- Communication Range Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_communication_po_view'); ?><p class="error_msg" id="communication_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="communication">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="communication_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='communication'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='communication_value'>(<small>2</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="communication_range" name="communication_range" value="" />
			</div>
		</div>
	</div>
	<!-- Communication Range Slider End -->
	<!-- Expertise Range Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_expertise_po_view'); ?><p class="error_msg" id="expertise_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="expertise">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="expertise_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='expertise'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='expertise_value'>(<small>3</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="expertise_range" name="expertise_range" value="" />
			</div>
		</div>
	</div>
	<!-- Expertise Range Slider End -->
	<!-- Professionalism Range Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_professionalism_po_view'); ?><p class="error_msg" id="professionalism_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="professionalism">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="professionalism_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="professionalism_range" name="professionalism_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for Money Range Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_value_for_money_po_view'); ?><p class="error_msg" id="value_for_money_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="value_for_money">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="value_for_money_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="value_for_money_range" name="value_for_money_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for Money Range Slider End -->
    </div>
<?php
	}else{
?>
<div class="sliderAdjust spview">	
	<!-- Value for verbal communication skills Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_effective_oral_verbal_communication_skills_employer_view'); ?><p class="error_msg" id="verbal_communication_skills_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="verbal_communication_skills">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="verbal_communication_skills_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="verbal_communication_skills_range" name="verbal_communication_skills_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for verbal communication skills Slider End -->
	
	<!-- Value for work quality Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_work_quality_employer_view'); ?><p class="error_msg" id="work_quality_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="work_quality">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="work_quality_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="work_quality_range" name="work_quality_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for work quality Slider End -->
	
	<!-- Value for self motivated Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_self_motivated_employer_view'); ?><p class="error_msg" id="self_motivated_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="self_motivated">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="self_motivated_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="self_motivated_range" name="self_motivated_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for self motivated Slider End -->
	
	<!-- Value for working relations Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_working_relations_employer_view'); ?><p class="error_msg" id="working_relations_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="working_relations">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="working_relations_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="working_relations_range" name="working_relations_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for working relations Slider End -->
	
	<!-- Value for demonstrates flexibility adaptability  Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_demonstrates_flexibility_adaptability_employer_view'); ?><p class="error_msg" id="demonstrates_flexibility_adaptability_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="demonstrates_flexibility_adaptability">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="demonstrates_flexibility_adaptability_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="demonstrates_flexibility_adaptability_range" name="demonstrates_flexibility_adaptability_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for demonstrates flexibility adaptability Slider End -->

	<!-- Value for solves problems Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_solves_problems_employer_view'); ?><p class="error_msg" id="solves_problems_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="solves_problems">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="solves_problems_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="solves_problems_range" name="solves_problems_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for solves problems Slider End -->
	
	
	
	
	<!-- Value for work ethic Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_employee_work_ethic_employer_view'); ?><p class="error_msg" id="work_ethic_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="work_ethic">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="work_ethic_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="work_ethic_range" name="work_ethic_range" value="" />
			</div>
		</div>
	</div>
	<!-- Value for work ethic Slider End -->
</div>
<?php
	}
}	
if($user[0]->user_id ==$sp_data['user_id']){
	
	if($project_type != 'fulltime'){	
?>
<div class="sliderAdjust spview">
	<!-- Clarity requirement Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_clarity_in_requirements_sp_view'); ?><p class="error_msg" id="clarity_requirements_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="clarity_requirements">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
				</div><span id="clarity_requirements_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<div class="clearfix"></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12 rangeSlider">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="clarity_requirements_range" name="clarity_requirements_range" value="" />
			</div>
		</div>
	</div>
	<!-- Clarity requirement Range Slider End -->
	<!-- Communication Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_communication_sp_view'); ?><p class="error_msg" id="communication_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="communication">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
				</div><span id="communication_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<div class="clearfix"></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="communication_range" name="communication_range" value="" />
			</div>
		</div>
	</div>
	<!-- Communication Range Slider End -->
	<!-- Payment promptness Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('projects_users_ratings_feedbacks_payment_promptness_sp_view'); ?><p class="error_msg" id="payment_promptness_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="payment_promptness">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
				</div><span id="payment_promptness_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<div class="clearfix"></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="payment_promptness_range" name="payment_promptness_range" value="" />
			</div>
		</div>
	</div>
	<!-- Payment promptness Range Slider End -->
</div>

<?php
	}else{
?>
    <div class="empview">
	<!-- work life balance Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_work_life_balance_feedback_employee_view'); ?><p class="error_msg" id="work_life_balance_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="work_life_balance">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="work_life_balance_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="work_life_balance_range" name="work_life_balance_range" value="" />
			</div>
		</div>
	</div>
	
	<!-- career opportunities Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_career_opportunities_employee_view'); ?><p class="error_msg" id="career_opportunities_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="career_opportunities">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="career_opportunities_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="career_opportunities_range" name="career_opportunities_range" value="" />
			</div>
		</div>
	</div>
	<!-- compensation benefits Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_compensation_benefits_employee_view'); ?><p class="error_msg" id="compensation_benefits_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="compensation_benefits">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="compensation_benefits_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="compensation_benefits_range" name="compensation_benefits_range" value="" />
			</div>
		</div>
	</div>
	<!-- proper training support mentorship leadership Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_proper_training_support_mentorship_leadership_employee_view'); ?><p class="error_msg" id="proper_training_support_mentorship_leadership_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="proper_training_support_mentorship_leadership">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="proper_training_support_mentorship_leadership_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="proper_training_support_mentorship_leadership_range" name="compensation_benefits_range" value="" />
			</div>
		</div>
	</div>
	
	<!-- job responsibilities expectation Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_explained_job_responsibilities_expectation_employee_view'); ?><p class="error_msg" id="job_responsibilities_expectation_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="job_responsibilities_expectation">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="job_responsibilities_expectation_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="job_responsibilities_expectation_range" name="job_responsibilities_expectation_range" value="" />
			</div>
		</div>
	</div>
	
	<!-- environment encourages expressing sharing ideas innovation  Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_environment_encourages_expressing_sharing_ideas_innovation_employee_view'); ?><p class="error_msg" id="environment_encourages_expressing_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="environment_encourages_expressing">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="environment_encourages_expressing_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="environment_encourages_expressing_range" name="environment_encourages_expressing_range" value="" />
			</div>
		</div>
	</div>
	
	<!-- safe healthy environment  Slider Start -->
	<div class="sldRng">
		<div class="row">						
			<div class="col-md-12 col-sm-12 col-12 starRatting">
				<h5 class="default_black_bold_medium"><?php echo $this->config->item('fulltime_projects_users_ratings_feedbacks_safe_healthy_environment_employee_view'); ?><p class="error_msg" id="safe_healthy_environment_error"></p></h5>
				<div class="claritySection"><div class="sRate" id="safe_healthy_environment">
					<span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span><span><i class="fa fa-star-o default_avatar_review_star"></i></span>
                                    </div><span id="safe_healthy_environment_value"><small class="default_avatar_review avatar_review_project_owner">0.00</small></span></div>
				<!-- <div class='starrr' id='professionalism'>
					<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>
				</div>
				<span class='choice' id='professionalism_value'>(<small>4</small>)</span> -->
			</div>
			
			<div class="col-md-12 col-sm-12 col-12">
				<div class="row rngSld">
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_low_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-6">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_modarate_quality'); ?></h6>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-3">
						<div class="text-center">
							<h6 class="default_black_bold_medium"><?php echo $this->config->item('users_ratings_feedbacks_popup_rating_level_high_quality'); ?></h6>
						</div>
					</div>
				</div>
				<input type="text" class="js-range-slider" id="safe_healthy_environment_range" name="safe_healthy_environment_range" value="" />
			</div>
		</div>
	</div>
        
    </div>
	
<?php
	}
}	
?>	
<div class="fBack">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			 <div class="form-group">
				<label class="default_black_bold_medium" for="feedback"><?php echo $feedback_label_txt; ?><i class="fa fa-question-circle default_icon_help tooltipAuto" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="<?php echo $feedback_message_tooltip; ?>"></i></label>
				<textarea class="avoid_space_textarea default_textarea_field" name="feedback" id="feedback" maxlength="<?php echo $this->config->item('users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit'); ?>"></textarea>
				<div class="error_div_sectn clearfix default_error_div_sectn">
					<span class="content-count feedback_length_count_message"><?php echo $this->config->item('users_ratings_feedbacks_popup_feedback_characters_maximum_length_characters_limit').' '.$this->config->item('characters_remaining_message'); ?>
					</span> 
					<span class="error_msg" id="feedback_error"></span>
				</div>
			</div> 
		</div>
	</div>
</div>
</form>