<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Users_ratings_feedbacks_model extends BaseModel
{

    public function __construct ()
    { 
        return parent::__construct ();
    }
    
	
	//This function is using for validation of feedback excahnge.
	public function user_give_rating_feedback_validation(){
		
		$i = 0;
		if($this->input->post ('project_type') != 'fulltime'){
			if($this->input->post ('view_type') == 'po'){
				if(empty($this->input->post ('project_delivered_within_agreed_budget'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'project_delivered_within_agreed_budget';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_project_delivered_within_agreed_budget_required_validation_message_po_view');
					$i ++;
				}
				if(empty($this->input->post ('work_delivered_within_agreed_time_slot'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'work_delivered_within_agreed_time_slot';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_work_delivered_within_agreed_time_slot_required_validation_message_po_view');
					$i ++;
				}
				if(empty($this->input->post ('would_you_hire_sp_again'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'would_you_hire_sp_again';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_would_you_hire_sp_again_required_validation_message_po_view');
					$i ++;
				}
				if(empty($this->input->post ('would_you_recommend_sp'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'would_you_recommend_sp';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_would_you_recommend_sp_required_validation_message_po_view');
					$i ++;
				}
				if(!empty($this->input->post ('quality_of_work')) && $this->input->post ('quality_of_work') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'quality_of_work';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_project_quality_of_work_required_validation_message_po_view');
					$i ++;
				}
				if(!empty($this->input->post ('communication')) && $this->input->post ('communication') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'communication';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_project_communication_required_validation_message_po_view');
					$i ++;
				}
				if(!empty($this->input->post ('expertise')) && $this->input->post ('expertise') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'expertise';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_project_expertise_required_validation_message_po_view');
					$i ++;
				}
				if(!empty($this->input->post ('professionalism')) && $this->input->post ('professionalism') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'professionalism';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_project_professionalism_required_validation_message_po_view');
					$i ++;
				}
				if(!empty($this->input->post ('value_for_money')) && $this->input->post ('value_for_money') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'value_for_money';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_project_value_for_money_required_validation_message_po_view');
					$i ++;
				}
			}
			if($this->input->post ('view_type') == 'sp'){
			
				if(empty($this->input->post ('would_you_work_again_with_po'))){
				$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'would_you_work_again_with_po';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_would_you_work_again_with_po_required_validation_message_sp_view');
					$i ++;
				}
				if(empty($this->input->post ('would_you_recommend_po'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'would_you_recommend_po';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_would_you_recommend_po_required_validation_message_sp_view');
					$i ++;
				}
				if(!empty($this->input->post ('clarity_requirements')) && $this->input->post ('clarity_requirements') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'clarity_requirements';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_clarity_in_requirements_required_validation_message_sp_view');
					$i ++;
				}
				if(!empty($this->input->post ('communication')) && $this->input->post ('communication') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'communication';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_communication_required_validation_message_sp_view');
					$i ++;
				}
				if(!empty($this->input->post ('payment_promptness')) && $this->input->post ('payment_promptness') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'payment_promptness';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_project_payment_promptness_required_validation_message_sp_view');
					$i ++;
				}
			}	
			
		}
		if($this->input->post ('project_type') == 'fulltime'){
			if($this->input->post ('view_type') == 'po'){
				if(empty($this->input->post ('employee_shows_interest_enthusiasm_for_work'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'employee_shows_interest_enthusiasm_for_work';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_shows_interest_enthusiasm_for_work_required_validation_message_employer_view');
					$i ++;
				}
				if(empty($this->input->post ('employee_demonstrates_competency_in_knowledge_skills'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'employee_demonstrates_competency_in_knowledge_skills';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_competency_in_knowledge_skills_required_validation_message_employer_view');
					$i ++;
				}
				if(empty($this->input->post ('employee_demonstrates_levels_of_skill_knowledge'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'employee_demonstrates_levels_of_skill_knowledge';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_levels_of_skill_knowledge_required_validation_message_employer_view');
					$i ++;
				}
				if(empty($this->input->post ('employee_dependable_and_relied'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'employee_dependable_and_relied';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_dependable_and_reliable_required_validation_message_employer_view');
					$i ++;
				}
				if(empty($this->input->post ('employee_properly_organizes_prioritizes'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'employee_properly_organizes_prioritizes';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_properly_organizes_prioritizes_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('verbal_communication_skills')) && $this->input->post ('verbal_communication_skills') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'verbal_communication_skills';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_effective_oral_verbal_communication_skills_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('work_quality')) && $this->input->post ('work_quality') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'work_quality';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_work_quality_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('self_motivated')) && $this->input->post ('self_motivated') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'self_motivated';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_self_motivated_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('working_relations')) && $this->input->post ('working_relations') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'working_relations';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_working_relations_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('demonstrates_flexibility_adaptability')) && $this->input->post ('demonstrates_flexibility_adaptability') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'demonstrates_flexibility_adaptability';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_demonstrates_flexibility_adaptability_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('solves_problems')) && $this->input->post ('solves_problems') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'solves_problems';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_solves_problems_required_validation_message_employer_view');
					$i ++;
				}
				if(!empty($this->input->post ('work_ethic')) && $this->input->post ('work_ethic') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'work_ethic';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_employee_work_ethic_required_validation_message_employer_view');
					$i ++;
				}
			
			}
			if($this->input->post ('view_type') == 'sp'){
			
				if(empty($this->input->post ('appreciated_right_level'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'appreciated_right_level';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_appreciated_right_level_required_validation_message_employee_view');
					$i ++;
				}
				if(empty($this->input->post ('empowered_take_extra_responsibilities'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'empowered_take_extra_responsibilities';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_empowered_take_extra_responsibilities_required_validation_message_employee_view');
					$i ++;
				}
				if(empty($this->input->post ('recognition_work_achievements'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'recognition_work_achievements';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_recognition_work_achievements_required_validation_message_employee_view');
					$i ++;
				}
				if(empty($this->input->post ('receive_regular_consistent_feedback'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'receive_regular_consistent_feedback';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_receive_regular_consistent_feedback_required_validation_message_employee_view');
					$i ++;
				}
				if(empty($this->input->post ('recommend_this_company'))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'recommend_this_company';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_recommend_this_company_required_validation_message_employee_view');
					$i ++;
				}
				if(!empty($this->input->post ('work_life_balance')) && $this->input->post ('work_life_balance') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'work_life_balance';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_work_life_balance_feedback_required_validation_message_employee_view');
					$i ++;
				}
				/* if(!empty($this->input->post ('recommend_this_company')) && $this->input->post ('recommend_this_company') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'recommend_this_company';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_recommend_this_company_required_validation_message_employee_view');
					$i ++;
				} */
				if(!empty($this->input->post ('career_opportunities')) && $this->input->post ('career_opportunities') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'career_opportunities';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_career_opportunities_required_validation_message_employee_view');
					$i ++;
				}
				if(!empty($this->input->post ('compensation_benefits')) && $this->input->post ('compensation_benefits') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'compensation_benefits';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_compensation_benefits_required_validation_message_employee_view');
					$i ++;
				}
				if(!empty($this->input->post ('proper_training_support_mentorship_leadership')) && $this->input->post ('proper_training_support_mentorship_leadership') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'proper_training_support_mentorship_leadership';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_proper_training_support_mentorship_leadership_required_validation_message_employee_view');
					$i ++;
				}
				if(!empty($this->input->post ('job_responsibilities_expectation')) && $this->input->post ('job_responsibilities_expectation') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'job_responsibilities_expectation';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_explained_job_responsibilities_expectation_required_validation_message_employee_view');
					$i ++;
				}
				if(!empty($this->input->post ('environment_encourages_expressing')) && $this->input->post ('environment_encourages_expressing') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'environment_encourages_expressing';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_environment_encourages_expressing_sharing_ideas_innovation_required_validation_message_employee_view');
					$i ++;
				}
				if(!empty($this->input->post ('safe_healthy_environment')) && $this->input->post ('safe_healthy_environment') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'safe_healthy_environment';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_safe_healthy_environment_required_validation_message_employee_view');
					$i ++;
				}
				/* if(!empty($this->input->post ('recommend_this_company')) && $this->input->post ('recommend_this_company') == '0.0'){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'recommend_this_company';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('fulltime_projects_users_ratings_feedbacks_popup_recommend_this_company_required_validation_message_employee_view');
					$i ++;
				} */
			}
		}	
		if(empty($this->input->post ('feedback'))){
			
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'feedback';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_popup_feedback_required_validation_message');
			$i ++;
		}
		if(!empty($this->input->post ('feedback'))){
			
			$feedback_charcaters_length = strlen(preg_replace('/\s+/', '', $this->input->post ('feedback')));
			
			if($this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_words_limit') == 0){
				
				if($feedback_charcaters_length < $this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'feedback';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_validation_message');
					
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('feedback'));
				$comment_word_count = count(explode(' ', trim($string_only_single_space)));
				if($feedback_charcaters_length < $this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_characters_limit') ||$comment_word_count < $this->config->item('users_ratings_feedbacks_popup_feedback_characters_minimum_length_words_limit') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'feedback';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_popup_feedback_words_minimum_length_validation_message');
						
					$i ++;
				}
			}
		}
		
		
		if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
	
	}
	
	// function used to fetch the feedback data for received feedback tab on project detail page
	public function get_received_feedback_tab_data_project_detail($params){
		
		$po_id = $params['po_id'];
		$sp_id = $params['sp_id'];
		$project_id = $params['project_id'];
		$view_type = $params['view_type'];
		$user_id = $params['login_user_id'];
		$project_type = $params['project_type'];
		
		$sp_data = $this->db // get the user detail
		->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
		->from('users u')
		->where('u.user_id', $sp_id)
		->get()->row_array();
		
		$po_data = $this->db // get the user detail
		->select('u.user_id,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
		->from('users u')
		->where('u.user_id', $po_id)
		->get()->row_array();
		
		$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
		$po_name = (($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')) ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
	
		$check_receiver_view_his_rating = 0;
		$check_receiver_received_rating = 0;
		$feedback_data = array();
		$feedback_given_msg = '';// message when other part given the feedabck for feedback received tab
		$other_party_given_feedback_msg = '';// message when other party given the feedback for feedback received tab
		$other_party_not_given_feedback_msg = ''; // message when other party not given the message for feedback received tab
		
		if($project_type != 'fulltime'){
			if($user_id == $po_id && $view_type == 'po'){
			  $check_receiver_view_his_rating = $this->db->where(['feedback_given_by_po_id' => $po_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
			
				$check_receiver_received_rating = $this->db->where(['feedback_given_by_sp_id' => $sp_id,'feedback_recived_by_po_id'=>$po_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
				
				$feedback_data = $this->db // get the user detail
				->select('*')
				->from('projects_users_received_ratings_feedbacks_as_po')
				->where(['feedback_provided_on_project_id' => $project_id,'feedback_recived_by_po_id'=>$po_id,'feedback_given_by_sp_id'=>$sp_id])
				->get()->row_array();
				if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
					if($sp_data['gender'] == 'M')
					{
						if($sp_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
							
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');
							
							
						}else{
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
							
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
						}
						
						if(empty($feedback_data)){
							if($sp_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
							}else{
							
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
							}
						}
					}else{
						if($sp_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
							
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
							
						}else{	
					
					
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
						}
						if(empty($feedback_data)){
							if($sp_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
												
							}else{	
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
							}
						}
					}
					$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					
					if(empty($feedback_data)){
					$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_not_given_feedback_msg);
					}
				
				}else{
					$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
					$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
					$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					
					if(empty($feedback_data)){
					$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
					
					$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($sp_name),$other_party_not_given_feedback_msg);
					}
				}
			}else if($user_id == $sp_id && $view_type == 'sp'){
				
				$check_receiver_view_his_rating = $this->db->where(['feedback_recived_by_po_id' => $po_id,'feedback_given_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_po')->count_all_results();
				
				
				
				$check_receiver_received_rating = $this->db->where(['feedback_given_by_po_id' => $po_id,'feedback_recived_by_sp_id'=>$sp_id,'feedback_provided_on_project_id'=>$project_id])->from('projects_users_received_ratings_feedbacks_as_sp')->count_all_results();
			
				$feedback_data = $this->db // get the user detail
				->select('*')
				->from('projects_users_received_ratings_feedbacks_as_sp')
				->where(['feedback_provided_on_project_id' => $project_id,'feedback_given_by_po_id'=>$po_id,'feedback_recived_by_sp_id'=>$sp_id])
				->get()->row_array();
				
				if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
					if($po_data['gender'] == 'M')
					{
						if($po_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');
						}else{	
					
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
						
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
						}
						if(empty($feedback_data)){
							if($po_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
							}else{
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
							}
						}
					}else{
						if($po_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
						}else{
							$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
						}
						if(empty($feedback_data)){
							if($po_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
							}else{
								$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
							}
						}
					}
					$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					if(empty($feedback_data)){
						$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($po_name),$other_party_not_given_feedback_msg);
					}
				
				}else{
				
					
				
				
					$feedback_given_msg = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
					$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
									
					$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					
					
					if(empty($feedback_data)){
						$other_party_not_given_feedback_msg  = $this->config->item('projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
						$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($po_name),$other_party_not_given_feedback_msg);
					}
				}
			}
			
		}
		else if($project_type == 'fulltime'){
			
			if($user_id == $po_id && $view_type == 'po'){
			  $check_receiver_view_his_rating = $this->db->where(['feedback_given_by_employer_id' => $po_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
			
				$check_receiver_received_rating = $this->db->where(['feedback_given_by_employee_id' => $sp_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
				
				$feedback_data = $this->db // get the user detail
				->select('*')
				->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')
				->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_recived_by_employer_id'=>$po_id,'feedback_given_by_employee_id'=>$sp_id])
				->get()->row_array();
				if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
					if($sp_data['gender'] == 'M')
					{
						if($sp_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
						
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');
						}else{	
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
						
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
						}
						
						if(empty($feedback_data)){
							if($sp_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
							}else{
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
							}
						}
					}else{
						if($sp_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
							
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
						}else{
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
						}
						if(empty($feedback_data)){
							if($sp_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
							}else{
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
							}
						}
					}
					$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					
					if(empty($feedback_data)){
					$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_not_given_feedback_msg);
					}
				
				}else{
					$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
					$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
					$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($sp_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					
					if(empty($feedback_data)){
					$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
					
					$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($sp_name),$other_party_not_given_feedback_msg);
					}
				}
			}else if($user_id == $sp_id && $view_type == 'sp'){
			
				$check_receiver_view_his_rating = $this->db->where(['feedback_recived_by_employer_id' => $po_id,'feedback_given_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employer')->count_all_results();
				
				$check_receiver_received_rating = $this->db->where(['feedback_given_by_employer_id' => $po_id,'feedback_recived_by_employee_id'=>$sp_id,'feedback_provided_on_fulltime_project_id'=>$project_id])->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')->count_all_results();
			
				$feedback_data = $this->db // get the user detail
				->select('*')
				->from('fulltime_prj_users_received_ratings_feedbacks_as_employee')
				->where(['feedback_provided_on_fulltime_project_id' => $project_id,'feedback_given_by_employer_id'=>$po_id,'feedback_recived_by_employee_id'=>$sp_id])
				->get()->row_array();
				
				if(($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')){
					if($po_data['gender'] == 'M')
					{
					
						if($po_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_male_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_male_party_given_feedback');	
						}else{	
					
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_male_feedback_provider');
						
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_male_party_given_feedback');
						}
						if(empty($feedback_data)){
							if($po_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_male_party_not_given_feedback');
							}else{	
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_male_party_not_given_feedback');
							}
						}
					}else{
					
						if($po_data['is_authorized_physical_person'] == 'Y'){
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_app_female_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_app_female_party_given_feedback');
						}else{
					
							$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_female_feedback_provider');
							$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_female_party_given_feedback');
						}
						if(empty($feedback_data)){
							if($po_data['is_authorized_physical_person'] == 'Y'){
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_app_female_party_not_given_feedback');
							}else{	
								$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_female_party_not_given_feedback');
							}
						}
					}
					$feedback_given_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg = str_replace(array('{user_first_name_last_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					if(empty($feedback_data)){
						$other_party_not_given_feedback_msg = str_replace(array('{user_first_name_last_name}'),array($po_name),$other_party_not_given_feedback_msg);
					}
				
				}else{
				
				
					$feedback_given_msg = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_message_provided_by_company_feedback_provider');
					$feedback_given_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$feedback_given_msg);
					
					$other_party_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_feedback_not_other_company_party_given_feedback');
									
					$other_party_given_feedback_msg = str_replace(array('{user_company_name}','{feedback_provided_on_date}'),array($po_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($feedback_data['feedback_provided_on_date']))),$other_party_given_feedback_msg);
					
					if(empty($feedback_data)){
						$other_party_not_given_feedback_msg  = $this->config->item('fulltime_projects_users_ratings_feedbacks_received_tabs_project_details_page_other_company_party_not_given_feedback');
						$other_party_not_given_feedback_msg = str_replace(array('{user_company_name}'),array($po_name),$other_party_not_given_feedback_msg);
					}
				}
			}
			
		}
		$data['check_receiver_view_his_rating'] = $check_receiver_view_his_rating;
		$data['check_receiver_received_rating'] = $check_receiver_received_rating;
		$data['feedback_data'] = $feedback_data;
		$data['feedback_given_msg'] = $feedback_given_msg;
		$data['other_party_given_feedback_msg'] = $other_party_given_feedback_msg;
		$data['sp_name'] = $sp_name;
		$data['po_name'] = $po_name;
		$data['sp_is_authorized_physical_person'] = $sp_data['is_authorized_physical_person'];
		$data['sp_gender'] = $sp_data['gender'];
		$data['sp_account_type'] = $sp_data['account_type'];
		
		$data['po_is_authorized_physical_person'] = $po_data['is_authorized_physical_person'];
		$data['po_gender'] = $po_data['gender'];
		$data['po_account_type'] = $po_data['account_type'];
		
		
		
		$data['other_party_not_given_feedback_msg'] = $other_party_not_given_feedback_msg;
		return	$data;
	}
	
	//This function is used to get the ratings and feedback recieved by sp of fixed/hourly project
	public function get_user_profile_page_ratings_feedbacks_on_projects_as_sp_tab_listing($conditions,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender ,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.gender as sp_gender,sp.company_name as sp_company_name,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person', false);
		$this->db->from('projects_users_received_ratings_feedbacks_as_sp feedbacks');
		$this->db->where($conditions);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_given_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_recived_by_sp_id');
		$fixed_budget_in_progress_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.gender as sp_gender,sp.company_name as sp_company_name,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person');
		$this->db->from('projects_users_received_ratings_feedbacks_as_sp feedbacks');
		$this->db->where($conditions);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_given_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_recived_by_sp_id');
		$hourly_rate_based_in_progress_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.gender as sp_gender,sp.company_name as sp_company_name,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person');
		$this->db->from('projects_users_received_ratings_feedbacks_as_sp feedbacks');
		$this->db->where($conditions);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_given_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_recived_by_sp_id');
		$fixed_budget_completed_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.gender as sp_gender,sp.company_name as sp_company_name,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person');
		$this->db->from('projects_users_received_ratings_feedbacks_as_sp feedbacks');
		$this->db->where($conditions);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_given_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_recived_by_sp_id');
		$hourly_rate_based_completed_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		$union_table_name = [
			$fixed_budget_in_progress_projects_ratings_feedbacks_query,
			$hourly_rate_based_in_progress_projects_ratings_feedbacks_query,
			$fixed_budget_completed_projects_ratings_feedbacks_query,
			$hourly_rate_based_completed_projects_ratings_feedbacks_query
		];
		$feedback_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY feedback_provided_on_date DESC LIMIT '.$limit_range);
		$feedback_result  = $feedback_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $feedback_result, 'total' => $total_rec];
	}
	
	//This function is used to get the ratings and feedback recieved by po of fixed/hourly project
	public function get_user_profile_page_ratings_feedbacks_on_projects_as_po_tab_listing($conditions,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.company_name as sp_company_name,sp.profile_name as sp_profile_name,sp.gender as sp_gender,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person', false);
		$this->db->from('projects_users_received_ratings_feedbacks_as_po feedbacks');
		$this->db->where($conditions);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_recived_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_given_by_sp_id');
		$fixed_budget_in_progress_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.company_name as sp_company_name,sp.profile_name as sp_profile_name,sp.gender as sp_gender,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person');
		$this->db->from('projects_users_received_ratings_feedbacks_as_po feedbacks');
		$this->db->where($conditions);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_recived_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_given_by_sp_id');
		$hourly_rate_based_in_progress_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.company_name as sp_company_name,sp.profile_name as sp_profile_name,sp.gender as sp_gender,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person');
		$this->db->from('projects_users_received_ratings_feedbacks_as_po feedbacks');
		$this->db->where($conditions);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_recived_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_given_by_sp_id');
		$fixed_budget_completed_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.project_title,po.user_id as po_id,po.account_type po_account_type,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.profile_name as po_profile_name,po.gender as po_gender,sp.account_type as sp_account_type,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.company_name as sp_company_name,sp.profile_name as sp_profile_name,sp.gender as sp_gender,po.is_authorized_physical_person po_is_authorized_physical_person,sp.is_authorized_physical_person as sp_is_authorized_physical_person');
		$this->db->from('projects_users_received_ratings_feedbacks_as_po feedbacks');
		$this->db->where($conditions);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = feedbacks.feedback_provided_on_project_id');
		$this->db->join('users as po', 'po.user_id = feedbacks.feedback_recived_by_po_id');
		$this->db->join('users as sp', 'sp.user_id = feedbacks.feedback_given_by_sp_id');
		$hourly_rate_based_completed_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		$union_table_name = [
			$fixed_budget_in_progress_projects_ratings_feedbacks_query,
			$hourly_rate_based_in_progress_projects_ratings_feedbacks_query,
			$fixed_budget_completed_projects_ratings_feedbacks_query,
			$hourly_rate_based_completed_projects_ratings_feedbacks_query
		];
		$feedback_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY feedback_provided_on_date DESC LIMIT '.$limit_range);
		$feedback_result  = $feedback_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $feedback_result, 'total' => $total_rec];
	}
	
	//This function is used to get the ratings and feedback recieved by employee of fulltime project
	public function get_user_profile_page_ratings_feedbacks_on_projects_as_employee_tab_listing($conditions,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS feedbacks.*,pd.project_title,employer.user_id as employer_id,employer.account_type employer_account_type,employer.first_name as employer_first_name,employer.last_name as employer_last_name,employer.company_name as employer_company_name,employer.profile_name as employer_profile_name,employee.account_type as employee_account_type,employee.first_name as employee_first_name,employee.last_name as employee_last_name,employee.company_name as employee_company_name,employer.is_authorized_physical_person employer_is_authorized_physical_person,employee.is_authorized_physical_person as employee_is_authorized_physical_person', false);
		$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee feedbacks');
		$this->db->where($conditions);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = feedbacks.feedback_provided_on_fulltime_project_id');
		$this->db->join('users as employer', 'employer.user_id = feedbacks.feedback_given_by_employer_id');
		$this->db->join('users as employee', 'employee.user_id = feedbacks.feedback_recived_by_employee_id');
		$fulltime_open_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.fulltime_project_title as project_title,employer.user_id as employer_id,employer.account_type employer_account_type,employer.first_name as employer_first_name,employer.last_name as employer_last_name,employer.company_name as employer_company_name,employer.profile_name as employer_profile_name,employee.account_type as employee_account_type,employee.first_name as employee_first_name,employee.last_name as employee_last_name,employee.company_name as employee_company_name,employer.is_authorized_physical_person employer_is_authorized_physical_person,employee.is_authorized_physical_person as employee_is_authorized_physical_person');
		$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee feedbacks');
		$this->db->where($conditions);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = feedbacks.feedback_provided_on_fulltime_project_id');
		$this->db->join('users as employer', 'employer.user_id = feedbacks.feedback_given_by_employer_id');
		$this->db->join('users as employee', 'employee.user_id = feedbacks.feedback_recived_by_employee_id');
		$fulltime_expired_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.fulltime_project_title as project_title,employer.user_id as employer_id,employer.account_type employer_account_type,employer.first_name as employer_first_name,employer.last_name as employer_last_name,employer.company_name as employer_company_name,employer.profile_name as employer_profile_name,employee.account_type as employee_account_type,employee.first_name as employee_first_name,employee.last_name as employee_last_name,employee.company_name as employee_company_name,employer.is_authorized_physical_person employer_is_authorized_physical_person,employee.is_authorized_physical_person as employee_is_authorized_physical_person');
		$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employee feedbacks');
		$this->db->where($conditions);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = feedbacks.feedback_provided_on_fulltime_project_id');
		$this->db->join('users as employer', 'employer.user_id = feedbacks.feedback_given_by_employer_id');
		$this->db->join('users as employee', 'employee.user_id = feedbacks.feedback_recived_by_employee_id');
		$fulltime_cancelled_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		
		$union_table_name = [
			$fulltime_open_projects_ratings_feedbacks_query,
			$fulltime_expired_projects_ratings_feedbacks_query,
			$fulltime_cancelled_projects_ratings_feedbacks_query
		];
		$feedback_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY feedback_provided_on_date DESC LIMIT '.$limit_range);
		$feedback_result  = $feedback_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $feedback_result, 'total' => $total_rec];
	}
	
	
	//This function is used to get the ratings and feedback recieved by employer of fulltime
	public function get_user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab_listing($conditions,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS feedbacks.*,pd.project_title,employer.user_id as employer_id,employer.account_type employer_account_type,employer.first_name as employer_first_name,employer.last_name as employer_last_name,employer.company_name as employer_company_name,employer.profile_name as employer_profile_name,employee.account_type as employee_account_type,employee.first_name as employee_first_name,employee.last_name as employee_last_name,employee.company_name as employee_company_name,employee.profile_name as employee_profile_name,employer.is_authorized_physical_person employer_is_authorized_physical_person,employee.is_authorized_physical_person as employee_is_authorized_physical_person', false);
		$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer feedbacks');
		$this->db->where($conditions);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = feedbacks.feedback_provided_on_fulltime_project_id');
		$this->db->join('users as employer', 'employer.user_id = feedbacks.feedback_recived_by_employer_id');
		$this->db->join('users as employee', 'employee.user_id = feedbacks.feedback_given_by_employee_id');
		$fulltime_open_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.fulltime_project_title as project_title,employer.user_id as employer_id,employer.account_type employer_account_type,employer.first_name as employer_first_name,employer.last_name as employer_last_name,employer.company_name as employer_company_name,employer.profile_name as employer_profile_name,employee.account_type as employee_account_type,employee.first_name as employee_first_name,employee.last_name as employee_last_name,employee.company_name as employee_company_name,employee.profile_name as employee_profile_name,employer.is_authorized_physical_person employer_is_authorized_physical_person,employee.is_authorized_physical_person as employee_is_authorized_physical_person');
		$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer feedbacks');
		$this->db->where($conditions);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = feedbacks.feedback_provided_on_fulltime_project_id');
		$this->db->join('users as employer', 'employer.user_id = feedbacks.feedback_recived_by_employer_id');
		$this->db->join('users as employee', 'employee.user_id = feedbacks.feedback_given_by_employee_id');
		$fulltime_expired_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		$this->db->select('feedbacks.*,pd.fulltime_project_title as project_title,employer.user_id as employer_id,employer.account_type employer_account_type,employer.first_name as employer_first_name,employer.last_name as employer_last_name,employer.company_name as employer_company_name,employer.profile_name as employer_profile_name,employee.account_type as employee_account_type,employee.first_name as employee_first_name,employee.last_name as employee_last_name,employee.company_name as employee_company_name,employee.profile_name as employee_profile_name,employer.is_authorized_physical_person employer_is_authorized_physical_person,employee.is_authorized_physical_person as employee_is_authorized_physical_person');
		$this->db->from('fulltime_prj_users_received_ratings_feedbacks_as_employer feedbacks');
		$this->db->where($conditions);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = feedbacks.feedback_provided_on_fulltime_project_id');
		$this->db->join('users as employer', 'employer.user_id = feedbacks.feedback_recived_by_employer_id');
		$this->db->join('users as employee', 'employee.user_id = feedbacks.feedback_given_by_employee_id');
		$fulltime_cancelled_projects_ratings_feedbacks_query = $this->db->get_compiled_select();
		
		
		$union_table_name = [
			$fulltime_open_projects_ratings_feedbacks_query,
			$fulltime_expired_projects_ratings_feedbacks_query,
			$fulltime_cancelled_projects_ratings_feedbacks_query
		];
		$feedback_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY feedback_provided_on_date DESC LIMIT '.$limit_range);
		$feedback_result  = $feedback_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $feedback_result, 'total' => $total_rec];
	}
	
	//This function is using for validation of reply form on ratings and feedback on user profile page.
	public function rating_feeback_reply_form_validation(){
		
		$i = 0;	
		$section_id = $this->input->post ('section_id');
		if(empty($this->input->post ('rating_feedback_reply'))){
			
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'feedback_reply_'.$section_id;
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('projects_users_ratings_feedbacks_reply_required_validation_message');
			$i ++;
		}
		if(!empty($this->input->post ('rating_feedback_reply'))){
			
			$feedback_charcaters_length = strlen(preg_replace('/\s+/', '', $this->input->post ('rating_feedback_reply')));
			
			if($this->config->item('users_ratings_feedbacks_reply_characters_minimum_length_words_limit') == 0){
				
				if($feedback_charcaters_length < $this->config->item('users_ratings_feedbacks_reply_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'feedback_reply_'.$section_id;
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_reply_characters_minimum_length_validation_message');
					
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('rating_feedback_reply'));
				$comment_word_count = count(explode(' ', trim($string_only_single_space)));
				if($feedback_charcaters_length < $this->config->item('users_ratings_feedbacks_reply_characters_minimum_length_characters_limit') ||$comment_word_count < $this->config->item('users_ratings_feedbacks_reply_characters_minimum_length_words_limit') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'feedback_reply_'.$section_id;
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('users_ratings_feedbacks_reply_words_minimum_length_validation_message');
					$i ++;
				}
			}
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
	
	}
	
	public function get_user_projects_pending_ratings_feedbacks_listing($user_id,$start = '', $limit = ''){
		
		
		
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS f.*,po.user_id as po_user_id,po.account_type as po_account_type,po.is_authorized_physical_person as po_is_authorized_physical_person,po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,sp.user_id as sp_user_id,sp.account_type as sp_account_type,sp.is_authorized_physical_person as sp_is_authorized_physical_person,sp.first_name as sp_first_name,sp.last_name as sp_last_name,sp.company_name as sp_company_name', false);
		$this->db->from('projects_candidates_for_users_ratings_feedbacks_exchange f');
		$this->db->group_start();
		$this->db->where('f.po_id', $user_id);
		$this->db->where('f.po_rating_to_sp_date IS NULL');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('f.sp_id', $user_id);
		$this->db->where('f.sp_rating_to_po_date IS NULL');
		$this->db->group_end();
		
		
		
		/* $this->db->where('f.po_id',$user_id);
		$this->db->or_where('f.sp_id',$user_id); */
		$this->db->join('users as po', 'po.user_id = f.po_id', 'left');
		$this->db->join('users as sp', 'sp.user_id = f.sp_id', 'left');
		$this->db->order_by('f.project_completion_date','desc');
		
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$result = $this->db->get();
		$feedback_data = $result->result_array();
		/* echo "<pre>";
		print_r($feedback_data);
		die;
		 */
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		/* echo "<pre>";
		print_r(['data' => $feedback_data, 'total' => $total_rec]);
		die; */
		return ['data' => $feedback_data, 'total' => $total_rec];
		
		
	}	
	
	public function get_user_projects_pending_ratings_feedbacks_count($user_id){
	
	
		 $total_records = $this->db
		->from('projects_candidates_for_users_ratings_feedbacks_exchange')
		->group_start()
		->where('po_id', $user_id)
		->where('po_rating_to_sp_date IS NULL')
		->group_end()
		->or_group_start()
		->where('sp_id', $user_id)
		->where('sp_rating_to_po_date IS NULL')
		->group_end()
		//->join('users as u', 'u.user_id = fbcpo.project_owner_id')
		->get ()->num_rows ();
		return $total_records;
	
	
	
	}
	
	public function old_get_user_projects_pending_ratings_feedbacks_listing($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		// query for fixed budget project and project is completed status(po will give feedback) 
		$this->db->select('SQL_CALC_FOUND_ROWS fbcpo.id,fbcpo.project_id,pd.project_title,fbcpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type',false);
		$this->db->from('fixed_budget_projects_completed_tracking as fbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = fbcpo.project_id AND feedback_given_by_po_id = fbcpo.project_owner_id and feedback_recived_by_sp_id = fbcpo.winner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('fbcpo.project_owner_id',$user_id);
		$this->db->where('fbcpo.project_completion_method','via_portal');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = fbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = fbcpo.winner_id');
		$this->db->having('feedback_count = 0');
		$fixed_buget_completed_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for fixed budget project and project is in progress status(po will give feedback) 
		$this->db->select('fbcpo.id,fbcpo.project_id,pd.project_title,fbcpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('fixed_budget_projects_completed_tracking as fbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = fbcpo.project_id AND feedback_given_by_po_id = fbcpo.project_owner_id and feedback_recived_by_sp_id = fbcpo.winner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('fbcpo.project_owner_id',$user_id);
		$this->db->where('fbcpo.project_completion_method','via_portal');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = fbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = fbcpo.winner_id');
		$this->db->having('feedback_count = 0');
		$fixed_buget_inprogress_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for fixed budget project and project is  completed status(sp will give feedback) 
		$this->db->select('fbcpo.id,fbcpo.project_id,pd.project_title,fbcpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('fixed_budget_projects_completed_tracking as fbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = fbcpo.project_id AND feedback_given_by_sp_id = fbcpo.winner_id and feedback_recived_by_po_id = fbcpo.project_owner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('fbcpo.winner_id',$user_id);
		$this->db->where('fbcpo.project_completion_method','via_portal');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = fbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = fbcpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$fixed_buget_completed_projects_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for fixed budget project and project is  in progress status(sp will give feedback) 
		$this->db->select('fbcpo.id,fbcpo.project_id,pd.project_title,fbcpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('fixed_budget_projects_completed_tracking as fbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = fbcpo.project_id AND feedback_given_by_sp_id = fbcpo.winner_id and feedback_recived_by_po_id = fbcpo.project_owner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('fbcpo.winner_id',$user_id);
		$this->db->where('fbcpo.project_completion_method','via_portal');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = fbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = fbcpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$fixed_buget_inprogress_projects_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		
		############## HOURLY RATE BASED PROJECT ##############
		// query for hourly budget project and project is in completed status(po will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('hourly_rate_based_projects_completed_tracking as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_po_id = hrbcpo.project_owner_id and feedback_recived_by_sp_id = hrbcpo.winner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbcpo.project_owner_id',$user_id);
		$this->db->where('hrbcpo.project_completion_method','via_portal');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.winner_id');
		$this->db->having('feedback_count = 0');
		$hourly_rate_based_completed_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for hourly budget project and project is in inpogress status(po will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('hourly_rate_based_projects_completed_tracking as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_po_id = hrbcpo.project_owner_id and feedback_recived_by_sp_id = hrbcpo.winner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbcpo.project_owner_id',$user_id);
		$this->db->where('hrbcpo.project_completion_method','via_portal');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.winner_id');
		$this->db->having('feedback_count = 0');
		$hourly_rate_based_inprogress_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		
		// query for hourly rate based project and project is  completed status(sp will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('hourly_rate_based_projects_completed_tracking as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_sp_id = hrbcpo.winner_id and feedback_recived_by_po_id = hrbcpo.project_owner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbcpo.winner_id',$user_id);
		$this->db->where('hrbcpo.project_completion_method','via_portal');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$hourly_rate_based_completed_projects_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for hourly rate based project and project is  inprogress status(sp will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('hourly_rate_based_projects_completed_tracking as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_sp_id = hrbcpo.winner_id and feedback_recived_by_po_id = hrbcpo.project_owner_id) as feedback_count,"" as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbcpo.winner_id',$user_id);
		$this->db->where('hrbcpo.project_completion_method','via_portal');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$hourly_rate_based_inprogress_projects_pending_feedbacks_sp_query = $this->db->get_compiled_select(); 
		
		// query for hourly budget project and project is in inpogress status(po/sp not mention any budget)(po will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_po_id = hrbcpo.project_owner_id and feedback_recived_by_sp_id = hrbcpo.winner_id) as feedback_count');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbcpo.project_id AND winner_id = hrbcpo.winner_id and is_partial_released ="N") as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		
		$this->db->where('hrbcpo.project_owner_id',$user_id);
		$this->db->where('hrbcpo.initial_project_agreed_value',0);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.winner_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count != 0');
		$hourly_rate_based_inprogress_projects_no_budget_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		
		// query for hourly budget project and project is in incomplete status(po/sp not mention any budget)(po will give feedback) 
		$this->db->select('hrbicpo.id,hrbicpo.project_id,pd.project_title,hrbicpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as hrbicpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbicpo.project_id AND feedback_given_by_po_id = hrbicpo.project_owner_id and feedback_recived_by_sp_id = hrbicpo.winner_id) as feedback_count');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbicpo.project_id AND winner_id = hrbicpo.winner_id and is_partial_released ="N") as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		
		$this->db->where('hrbicpo.project_owner_id',$user_id);
		$this->db->where('hrbicpo.initial_project_agreed_value',0);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = hrbicpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbicpo.winner_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count != 0');
		$hourly_rate_based_incomplete_projects_no_budget_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		
		
		
		
		// query for hourly budget project and project is in completed status(po/sp not mention any budget)(po will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.project_owner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_po_id = hrbcpo.project_owner_id and feedback_recived_by_sp_id = hrbcpo.winner_id) as feedback_count');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbcpo.project_id AND winner_id = hrbcpo.winner_id and is_partial_released ="N") as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		
		$this->db->where('hrbcpo.project_owner_id',$user_id);
		$this->db->where('hrbcpo.initial_project_agreed_value',0);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.winner_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count != 0');
		$hourly_rate_based_completed_projects_no_budget_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		
		
		// query for hourly budget project and project is in inpogress status(po/sp not mention any budget)(SP will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_sp_id = hrbcpo.winner_id and feedback_recived_by_po_id = hrbcpo.project_owner_id) as feedback_count');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbcpo.project_id AND winner_id = hrbcpo.winner_id and is_partial_released ="N") as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbcpo.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count != 0');
		$hourly_rate_based_inprogress_projects_no_budget_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for hourly budget project and project is in incomplete status(po/sp not mention any budget)(SP will give feedback) 
		$this->db->select('hrbicpo.id,hrbicpo.project_id,pd.project_title,hrbicpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as hrbicpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbicpo.project_id AND feedback_given_by_sp_id = hrbicpo.winner_id and feedback_recived_by_po_id = hrbicpo.project_owner_id) as feedback_count');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbicpo.project_id AND winner_id = hrbicpo.winner_id and is_partial_released ="N") as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbicpo.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = hrbicpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbicpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count != 0');
		$hourly_rate_based_incomplete_projects_no_budget_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for hourly budget project and project is in completed status(po/sp not mention any budget)(SP will give feedback) 
		$this->db->select('hrbcpo.id,hrbcpo.project_id,pd.project_title,hrbcpo.winner_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as hrbcpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_sp_id = hrbcpo.winner_id and feedback_recived_by_po_id = hrbcpo.project_owner_id  ) as feedback_count');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbcpo.project_id AND winner_id = hrbcpo.winner_id and is_partial_released ="N") as paid_milestone_count,"" as initial_fulltime_project_threshold_value');
		$this->db->where('hrbcpo.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = hrbcpo.project_id');
		$this->db->join('users as u', 'u.user_id = hrbcpo.project_owner_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count != 0');
		$hourly_rate_based_completed_projects_no_budget_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		
		// query for fulltime project and project is in open for bidding status(po will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.project_title,fpo.employer_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employer_id = fpo.employer_id AND feedback_recived_by_employee_id = fpo.employee_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		$this->db->where('fpo.employer_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employee_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_open_bidding_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in expired status(po will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,fpo.employer_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employer_id = fpo.employer_id AND feedback_recived_by_employee_id = fpo.employee_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		$this->db->where('fpo.employer_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employee_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_expired_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in cancelled status(po will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,fpo.employer_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employer_id = fpo.employer_id AND feedback_recived_by_employee_id = fpo.employee_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		$this->db->where('fpo.employer_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employee_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_cancelled_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in cancelled by admin status(po will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,fpo.employer_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"po" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employer_id = fpo.employer_id AND feedback_recived_by_employee_id = fpo.employee_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		$this->db->where('fpo.employer_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employee_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_cancelled_by_admin_projects_pending_feedbacks_po_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in open bidding status(sp will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.project_title,fpo.employee_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employee_id = fpo.employee_id AND feedback_recived_by_employer_id = fpo.employer_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		
		$this->db->where('fpo.employee_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employer_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_open_projects_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in expired status(sp will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,fpo.employee_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employee_id = fpo.employee_id AND feedback_recived_by_employer_id = fpo.employer_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		
		$this->db->where('fpo.employee_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employer_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_expired_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in cancelled status(sp will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,fpo.employee_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employee_id = fpo.employee_id AND feedback_recived_by_employer_id = fpo.employer_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		
		$this->db->where('fpo.employee_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employer_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_cancelled_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		// query for fulltime project and project is in cancelled by admin status(sp will give feedback) 
		$this->db->select('fpo.id,fpo.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,fpo.employee_id as given_by,u.user_id,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,pd.project_type,"sp" as view_type');
		$this->db->from('fulltime_projects_hired_employees_tracking as fpo');
		$this->db->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employee_id = fpo.employee_id AND feedback_recived_by_employer_id = fpo.employer_id) as feedback_count');
		$this->db->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value');
		
		$this->db->where('fpo.employee_id',$user_id);
		$this->db->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'));
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = fpo.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = fpo.employer_id');
		$this->db->having('feedback_count = 0');
		$this->db->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value');
		$fulltime_cancelled_by_admin_pending_feedbacks_sp_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			 $fixed_buget_completed_projects_pending_feedbacks_po_query,
			$fixed_buget_inprogress_projects_pending_feedbacks_po_query,
			$fixed_buget_completed_projects_pending_feedbacks_sp_query,
			$fixed_buget_inprogress_projects_pending_feedbacks_sp_query,
			$hourly_rate_based_completed_projects_pending_feedbacks_po_query,
			$hourly_rate_based_inprogress_projects_pending_feedbacks_po_query,
			$hourly_rate_based_completed_projects_pending_feedbacks_sp_query,
			$hourly_rate_based_inprogress_projects_pending_feedbacks_sp_query,
			$hourly_rate_based_inprogress_projects_no_budget_pending_feedbacks_po_query, 
			$hourly_rate_based_incomplete_projects_no_budget_pending_feedbacks_po_query,
			$hourly_rate_based_completed_projects_no_budget_pending_feedbacks_po_query,
			$hourly_rate_based_inprogress_projects_no_budget_pending_feedbacks_sp_query,
			$hourly_rate_based_incomplete_projects_no_budget_pending_feedbacks_sp_query,
			$hourly_rate_based_completed_projects_no_budget_pending_feedbacks_sp_query,
			$fulltime_open_bidding_projects_pending_feedbacks_po_query,
			$fulltime_expired_projects_pending_feedbacks_po_query,
			$fulltime_cancelled_projects_pending_feedbacks_po_query,
			$fulltime_cancelled_by_admin_projects_pending_feedbacks_po_query,
			$fulltime_open_projects_pending_feedbacks_sp_query,
			$fulltime_expired_pending_feedbacks_sp_query,
			$fulltime_cancelled_pending_feedbacks_sp_query,
			$fulltime_cancelled_by_admin_pending_feedbacks_sp_query
		];

		$pending_feedbacks_query = $this->db->query(implode(' UNION ', $union_table_name).'  LIMIT '.$limit_range);
		$result  = $pending_feedbacks_query->result_array();
		//pre($result);
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
	
		return ['data' => $result, 'total' => $total_rec];
	
	}
	
	public function old_get_user_projects_pending_ratings_feedbacks_count($user_id){
		// for fixed budget project as po 
		
		 $total_records = $this->db
		->from('fixed_budget_projects_completed_tracking as fbcpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = fbcpo.project_id AND feedback_given_by_po_id = fbcpo.project_owner_id and feedback_recived_by_sp_id = fbcpo.winner_id) as feedback_count')
		->where('fbcpo.project_owner_id',$user_id)
		->where('fbcpo.project_completion_method','via_portal')
		//->join('users as u', 'u.user_id = fbcpo.project_owner_id')
		->having('feedback_count = 0')
		->get ()->num_rows ();
		
		// for fixed budget project as sp 
		$total_records += $this->db
		->from('fixed_budget_projects_completed_tracking as fbcpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = fbcpo.project_id AND feedback_given_by_sp_id = fbcpo.winner_id and feedback_recived_by_po_id = fbcpo.project_owner_id ) as feedback_count')
		->where('fbcpo.winner_id',$user_id)
		->where('fbcpo.project_completion_method','via_portal')
		//->join('users as u', 'u.user_id = fbcpo.winner_id')
		->having('feedback_count = 0')
		->get ()->num_rows ();
		
		############## HOURLY RATE BASED PROJECT ##############
		// for hourly rate based  project as po 
		$total_records += $this->db
		->from('hourly_rate_based_projects_completed_tracking as hrbcpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_po_id = hrbcpo.project_owner_id and feedback_recived_by_sp_id = hrbcpo.winner_id) as feedback_count')
		->where('hrbcpo.project_owner_id',$user_id)
		->where('hrbcpo.project_completion_method','via_portal')
		//->join('users as u', 'u.user_id = hrbcpo.project_owner_id')
		->having('feedback_count = 0')
		->get ()->num_rows ();
		
		// for hourly rate based project as sp 
		$total_records += $this->db
		->from('hourly_rate_based_projects_completed_tracking as hrbcpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_sp_id = hrbcpo.winner_id and feedback_recived_by_po_id = hrbcpo.project_owner_id) as feedback_count')
		->where('hrbcpo.winner_id',$user_id)
		->where('hrbcpo.project_completion_method','via_portal')
		//->join('users as u', 'u.user_id = hrbcpo.winner_id')
		->having('feedback_count = 0')
		->get ()->num_rows ();
		
		////////////////////////////////////////////////////////////////////
		
		
		// for hourly rate based  project as po when both po/sp not budget specified 
		$total_records += $this->db
		->from('hourly_rate_based_projects_progress_sp_bid_reference as hrbcpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_given_by_po_id = hrbcpo.project_owner_id and feedback_recived_by_sp_id = hrbcpo.winner_id) as feedback_count')
		->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbcpo.project_id AND winner_id = hrbcpo.winner_id and is_partial_released ="N") as paid_milestone_count')
		->where('hrbcpo.project_owner_id',$user_id)
		//->join('users as u', 'u.user_id = hrbcpo.project_owner_id')
		->having('feedback_count = 0')
		->having('paid_milestone_count != 0')
		->get ()->num_rows ();
		
		// for hourly rate based  project as sp when both po/sp not budget specified 
		$total_records += $this->db
		->from('hourly_rate_based_projects_progress_sp_bid_reference as hrbcpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbcpo.project_id AND feedback_recived_by_po_id = hrbcpo.project_owner_id and feedback_given_by_sp_id = hrbcpo.winner_id) as feedback_count')
		->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbcpo.project_id AND winner_id = hrbcpo.winner_id and is_partial_released ="N") as paid_milestone_count')
		->where('hrbcpo.winner_id',$user_id)
		//->join('users as u', 'u.user_id = hrbcpo.project_owner_id')
		->having('feedback_count = 0')
		->having('paid_milestone_count != 0')
		->get ()->num_rows ();
		
		////////////////////////////////////////////////////////////////////
		
		
		// for hourly rate based  project as po when both po/sp not budget specified 
		$total_records += $this->db
		->from('hourly_rate_based_projects_incomplete_tracking as hrbicpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp WHERE feedback_provided_on_project_id = hrbicpo.project_id AND feedback_given_by_po_id = hrbicpo.project_owner_id and feedback_recived_by_sp_id = hrbicpo.winner_id) as feedback_count')
		->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbicpo.project_id AND winner_id = hrbicpo.winner_id and is_partial_released ="N") as paid_milestone_count')
		->where('hrbicpo.project_owner_id',$user_id)
		//->join('users as u', 'u.user_id = hrbicpo.project_owner_id')
		->having('feedback_count = 0')
		->having('paid_milestone_count != 0')
		->get ()->num_rows ();
		
		// for hourly rate based  project as sp when both po/sp not budget specified 
		$total_records += $this->db
		->from('hourly_rate_based_projects_incomplete_tracking as hrbicpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po WHERE feedback_provided_on_project_id = hrbicpo.project_id AND feedback_recived_by_po_id = hrbicpo.project_owner_id and feedback_given_by_sp_id = hrbicpo.winner_id) as feedback_count')
		->select('(select count(id) FROM '.$this->db->dbprefix.'hourly_rate_based_projects_released_escrows WHERE project_id = hrbicpo.project_id AND winner_id = hrbicpo.winner_id and is_partial_released ="N") as paid_milestone_count')
		->where('hrbicpo.winner_id',$user_id)
		//->join('users as u', 'u.user_id = hrbicpo.project_owner_id')
		->having('feedback_count = 0')
		->having('paid_milestone_count != 0')
		->get ()->num_rows ();
		
		
		
		
		
		
		// for fulltime project for po
		$total_records += $this->db
		->from('fulltime_projects_hired_employees_tracking as fpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employer_id = fpo.employer_id and feedback_recived_by_employee_id = fpo.employee_id) as feedback_count')
		
		->select('(select sum(released_escrow_payment_amount) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employee_id = fpo.employee_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value')
		->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'))
		->where('fpo.employer_id',$user_id)
		->having('feedback_count = 0')
		->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value')
		->get ()->num_rows ();
	
		// for fulltime project for sp
		$total_records += $this->db
		->from('fulltime_projects_hired_employees_tracking as fpo')
		->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer WHERE feedback_provided_on_fulltime_project_id = fpo.fulltime_project_id AND feedback_given_by_employee_id = fpo.employee_id and feedback_recived_by_employer_id = fpo.employer_id) as feedback_count')
		->select('(select count(id) FROM '.$this->db->dbprefix.'fulltime_projects_released_escrows WHERE fulltime_project_id = fpo.fulltime_project_id AND employer_id = fpo.employer_id) as paid_milestone_count,fpo.initial_fulltime_project_threshold_value')
		->where('fpo.employee_id',$user_id)
		->where('fpo.feedback_exchange_availability_date <',date('Y-m-d H:i:s'))
		->having('feedback_count = 0')
		->having('paid_milestone_count >= fpo.initial_fulltime_project_threshold_value')
		->get ()->num_rows ();
		
		
		return $total_records;
	
	}
	
}
?>