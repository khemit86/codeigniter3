<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Dashboard_model extends BaseModel
{

    public function __construct ()
    {
        return parent::__construct ();
    }
	
	
	public function get_countries()
    {
		if($this->config->item('countries_drop_down_top_displayed_option_country_db_id') != 0){
			$top_country_option = $this->db->where('id',$this->config->item('countries_drop_down_top_displayed_option_country_db_id'))->order_by ('country_name', 'asc')->get ('countries')->row_array ();
			$countries =  $this->db->where('id != ',$this->config->item('countries_drop_down_top_displayed_option_country_db_id'))->order_by ('country_name', 'asc')->get ('countries')->result_array ();
			
			$countries_result_array =array_combine(range(1, count($countries)), $countries);
			array_unshift($countries_result_array,$top_country_option);
		}else{
			$countries_result_array =  $this->db->order_by ('country_name', 'asc')->get ('countries')->result_array ();
			
		
		}
		return $countries_result_array;
		
    }
    
	
	public function get_localities_selected_county($county_id)
    {
        return $this->db->order_by ('name', 'asc')->where('county_id',$county_id)->get ('localities')->result_array ();
    }
   
	public function get_counties()
    {
        return $this->db->order_by ('name', 'asc')->get ('counties')->result_array ();
    }
   
	//This function is used to update/get  the profile completion data of user
	public function get_user_profile_completion_data () {
		$user = $this->session->userdata('user');
		$user_id = $user[0]->user_id;

		$divider_value = 1;
		if($this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value')) {
			$divider_value = count($this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value'));
		}
		
		if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person  == 'Y')){ 
			$user_profile_completion_parameters_tracking_options_value =  $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
		}if($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){
			$user_profile_completion_parameters_tracking_options_value =  $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
		}
		
		$get_user_profile_completion_data = $this->db->get_where('users_accounts_profile_completion_tracking', ['user_id' => $user_id])->row_array();
		
		$user_profile_completion_percentage = 0;
		$user_profile_completion = 0;
		$user_profile_completion_data = array();
		if(!empty($get_user_profile_completion_data)){
			
			if($get_user_profile_completion_data['has_avatar'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['avatar_strength_value'];
				$user_profile_completion_data['avatar_strength_value'] = $user_profile_completion_parameters_tracking_options_value['avatar_strength_value'];
			}
			$users_address_details_data = $this->db // get the user detail
			->select('*')
			->from('users_address_details')
			->where('user_id', $user_id)
			->get()->row_array();
			if(!empty($users_address_details_data) && !empty($users_address_details_data['country_id']) && $users_address_details_data['country_id'] != 0 ){
			
				if($users_address_details_data['country_id'] == $this->config->item('reference_country_id')){
				
					if($get_user_profile_completion_data['has_country_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
						
						$user_profile_completion_data['county_address_strength_value'] = $user_profile_completion_parameters_tracking_options_value['county_address_strength_value'];
						
					}if($get_user_profile_completion_data['has_county_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['county_address_strength_value'];
						
						$user_profile_completion_data['county_address_strength_value'] = $user_profile_completion_parameters_tracking_options_value['county_address_strength_value'];
						
						
					}if($get_user_profile_completion_data['has_locality_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['locality_address_strength_value'];
						
						$user_profile_completion_data['locality_address_strength_value'] = $user_profile_completion_parameters_tracking_options_value['locality_address_strength_value'];
						
					}if($get_user_profile_completion_data['has_street_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['street_address_strength_value'];
						
						$user_profile_completion_data['street_address_strength_value'] = $user_profile_completion_parameters_tracking_options_value['street_address_strength_value'];
					}
				}else{
					if($get_user_profile_completion_data['has_country_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
						
						$user_profile_completion_data['country_address_strength_value'] = $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
					}
					if($get_user_profile_completion_data['has_street_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['street_address_when_country_not_cz_strength_value'];
						
						$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = $user_profile_completion_parameters_tracking_options_value['street_address_when_country_not_cz_strength_value'];
					}
				}
			}
			
			if($get_user_profile_completion_data['has_phone_or_mobile_number_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['phone_or_mobile_number_strength_value'];
				
				$user_profile_completion_data['phone_or_mobile_number_strength_value'] = $user_profile_completion_parameters_tracking_options_value['phone_or_mobile_number_strength_value'];
			}
			
			if($get_user_profile_completion_data['has_contact_email_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['contact_email_strength_value'];
				
				$user_profile_completion_data['contact_email_strength_value'] = $user_profile_completion_parameters_tracking_options_value['contact_email_strength_value'];
				
			}if($get_user_profile_completion_data['has_headline_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['headline_strength_value'];
				
				$user_profile_completion_data['headline_strength_value'] = $user_profile_completion_parameters_tracking_options_value['headline_strength_value'];
			}if($get_user_profile_completion_data['has_description_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['description_strength_value'];
				
				$user_profile_completion_data['description_strength_value'] = $user_profile_completion_parameters_tracking_options_value['description_strength_value'];
				
			}
			
			
			if($get_user_profile_completion_data['has_areas_of_expertise_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['areas_of_expertise_strength_value'];
				
				$user_profile_completion_data['areas_of_expertise_strength_value'] = $user_profile_completion_parameters_tracking_options_value['areas_of_expertise_strength_value'];
				
			}
			if($get_user_profile_completion_data['has_skills_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['skills_strength_value'];
				
				$user_profile_completion_data['skills_strength_value'] = $user_profile_completion_parameters_tracking_options_value['skills_strength_value'];
				
			}if($get_user_profile_completion_data['has_services_provided_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['services_provided_strength_value'];
				
				$user_profile_completion_data['services_provided_strength_value'] = $user_profile_completion_parameters_tracking_options_value['services_provided_strength_value'];
				
			}
			if($user[0]->account_type  == USER_PERSONAL_ACCOUNT_TYPE){ 
				if($get_user_profile_completion_data['has_mother_tongue_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['mother_tongue_strength_value'];
					
					$user_profile_completion_data['mother_tongue_strength_value'] = $user_profile_completion_parameters_tracking_options_value['mother_tongue_strength_value'];
					
					
				}if($get_user_profile_completion_data['has_spoken_foreign_languages_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['spoken_foreign_languages_strength_value'];
					
					$user_profile_completion_data['spoken_foreign_languages_strength_value'] = $user_profile_completion_parameters_tracking_options_value['spoken_foreign_languages_strength_value'];
					
				}if($get_user_profile_completion_data['has_work_experience_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['work_experience_strength_value'];
					
					$user_profile_completion_data['work_experience_strength_value'] = $user_profile_completion_parameters_tracking_options_value['work_experience_strength_value'];
					
				}if($get_user_profile_completion_data['has_education_training_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['education_training_strength_value'];
					
					$user_profile_completion_data['education_training_strength_value'] = $user_profile_completion_parameters_tracking_options_value['education_training_strength_value'];
				}
			}
			if($get_user_profile_completion_data['has_certifications_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['certifications_strength_value'];
				
				$user_profile_completion_data['certifications_strength_value'] = $user_profile_completion_parameters_tracking_options_value['certifications_strength_value'];
				
			}if($get_user_profile_completion_data['has_portfolio_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['portfolio_strength_value'];
				
				$user_profile_completion_data['portfolio_strength_value'] = $user_profile_completion_parameters_tracking_options_value['portfolio_strength_value'];
				
			}
			if($user[0]->account_type  == USER_COMPANY_ACCOUNT_TYPE){ 
				if($get_user_profile_completion_data['has_company_founded_year_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_founded_year_strength_value'];
					
					$user_profile_completion_data['company_founded_year_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_founded_year_strength_value'];
					
					
				}if($get_user_profile_completion_data['has_company_size_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_size_strength_value'];
					
					$user_profile_completion_data['company_size_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_size_strength_value'];
					
				}if($get_user_profile_completion_data['has_company_opening_hours_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_opening_hours_strength_value'];
					
					$user_profile_completion_data['company_opening_hours_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_opening_hours_strength_value'];
					
					
				}if($get_user_profile_completion_data['has_company_vision_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_values_strength_value'];
					
					$user_profile_completion_data['company_values_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_values_strength_value'];
					
				}if($get_user_profile_completion_data['has_company_mission_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_mission_strength_value'];
					
					$user_profile_completion_data['company_mission_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_mission_strength_value'];
					
				}if($get_user_profile_completion_data['has_company_core_values_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_core_values_strength_value'];
					
					$user_profile_completion_data['company_core_values_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_core_values_strength_value'];
					
				}if($get_user_profile_completion_data['has_company_strategy_goals_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_strategy_goals_strength_value'];
					
					$user_profile_completion_data['company_strategy_goals_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_strategy_goals_strength_value'];
					
				}if($get_user_profile_completion_data['has_company_invoicing_details_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_invoicing_details_strength_value'];
					
					$user_profile_completion_data['company_invoicing_details_strength_value'] = $user_profile_completion_parameters_tracking_options_value['company_invoicing_details_strength_value'];
					
				}
			}
			$user_profile_completion_data['user_profile_completion_percentage'] = $user_profile_completion;
			$user_profile_completion_data['user_profile_strength_value'] = ($user_profile_completion / $divider_value);
			
			$this->db->update('users_accounts_profile_completion_tracking', $user_profile_completion_data, ['user_id'=> $user_id]);
		
			$get_user_accounts_profile_completion_data = $this->db // get the user detail
			->select('user_profile_completion_percentage')
			->from('users_accounts_profile_completion_tracking')
			->where('user_id', $user_id)
			->get()->row_array();
			if(floatval($get_user_accounts_profile_completion_data['user_profile_completion_percentage']) == 0){
				$this->db->delete('users_accounts_profile_completion_tracking', array("user_id" => $user_id));
			}
			
			$get_user_profile_completion_data = $this->db->get_where('users_accounts_profile_completion_tracking', ['user_id' => $user_id])->row_array();
			$user_profile_completion_percentage = $get_user_profile_completion_data['user_profile_completion_percentage'];
			$user_profile_completion_percentage = str_replace(".00","",$user_profile_completion_percentage);
			
		}
		return $user_profile_completion_percentage;
		
       
    }
	
	//This function is used to update the profile completion data of user
	public function update_user_profile_completion_data($user_profile_completion_data,$user_data=array()){
		
		if(empty($user_data)){
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			$user_account_type = $user[0]->account_type;
		}else{
			$user_id = $user_data['user_id'];
			$user_account_type = $user_data['account_type'];
		}

		$user_details = $this->db->get_where('users', ['user_id' => $user_id])->row_array();

		$divider_value = 1;
		if($this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value')) {
			$divider_value = count($this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value'));
		}
	
		$check_users_accounts_profile_completion_tracking = $this->db->where(['user_id' => $user_id])->from('users_accounts_profile_completion_tracking')->count_all_results();
		if($check_users_accounts_profile_completion_tracking == 0){
			
			$user_profile_completion_data['user_id'] = $user_id;
			if($user_account_type  == USER_PERSONAL_ACCOUNT_TYPE){
				$user_profile_completion_data['user_account_type'] = USER_PERSONAL_ACCOUNT_TYPE;
				
			}elseif($user_account_type  == USER_COMPANY_ACCOUNT_TYPE){
				$user_profile_completion_data['user_account_type'] = USER_COMPANY_ACCOUNT_TYPE;
			}
			
			$this->db->insert ('users_accounts_profile_completion_tracking', $user_profile_completion_data);
		}else{
			$this->db->update('users_accounts_profile_completion_tracking', $user_profile_completion_data, ['user_id'=> $user_id]);
		}
		$user_profile_completion = 0;
		if($user_account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user_details['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_details['is_authorized_physical_person'] == 'Y')){ 
			$user_profile_completion_parameters_tracking_options_value =  $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
		}if($user_account_type  == USER_COMPANY_ACCOUNT_TYPE){
			$user_profile_completion_parameters_tracking_options_value =  $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
		}
		$get_user_profile_completion_data = $this->db->get_where('users_accounts_profile_completion_tracking', ['user_id' => $user_id])->row_array();
		if(!empty($get_user_profile_completion_data)){
			
			if($get_user_profile_completion_data['has_avatar'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['avatar_strength_value'];
			}
			$users_address_details_data = $this->db // get the user detail
			->select('*')
			->from('users_address_details')
			->where('user_id', $user_id)
			->get()->row_array();
			if(!empty($users_address_details_data) && !empty($users_address_details_data['country_id']) && $users_address_details_data['country_id'] != 0 ){
			
				if($users_address_details_data['country_id'] == $this->config->item('reference_country_id')){
				
					if($get_user_profile_completion_data['has_country_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
					}if($get_user_profile_completion_data['has_county_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['county_address_strength_value'];
					}if($get_user_profile_completion_data['has_locality_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['locality_address_strength_value'];
					}if($get_user_profile_completion_data['has_street_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['street_address_strength_value'];
					}
				}else{
					if($get_user_profile_completion_data['has_country_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
					}
					if($get_user_profile_completion_data['has_street_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['street_address_when_country_not_cz_strength_value'];
					}
				}
			}
			if($get_user_profile_completion_data['has_phone_or_mobile_number_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['phone_or_mobile_number_strength_value'];
			}
			if($get_user_profile_completion_data['has_contact_email_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['contact_email_strength_value'];
			}if($get_user_profile_completion_data['has_headline_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['headline_strength_value'];
			}if($get_user_profile_completion_data['has_description_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['description_strength_value'];
			}if($get_user_profile_completion_data['has_areas_of_expertise_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['areas_of_expertise_strength_value'];
			}if($get_user_profile_completion_data['has_skills_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['skills_strength_value'];
			}if($get_user_profile_completion_data['has_services_provided_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['services_provided_strength_value'];
			}
			if($user_account_type  == USER_PERSONAL_ACCOUNT_TYPE || ($user_details['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_details['is_authorized_physical_person'] == 'Y')){ 
				if($get_user_profile_completion_data['has_mother_tongue_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['mother_tongue_strength_value'];
				}if($get_user_profile_completion_data['has_spoken_foreign_languages_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['spoken_foreign_languages_strength_value'];
				}if($get_user_profile_completion_data['has_work_experience_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['work_experience_strength_value'];
				}if($get_user_profile_completion_data['has_education_training_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['education_training_strength_value'];
				}
			}
			if($get_user_profile_completion_data['has_certifications_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['certifications_strength_value'];
			}if($get_user_profile_completion_data['has_portfolio_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['portfolio_strength_value'];
			}
			if($user_account_type  == USER_COMPANY_ACCOUNT_TYPE){ 
				if($get_user_profile_completion_data['has_company_founded_year_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_founded_year_strength_value'];
				}if($get_user_profile_completion_data['has_company_size_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_size_strength_value'];
				}if($get_user_profile_completion_data['has_company_opening_hours_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_opening_hours_strength_value'];
				}if($get_user_profile_completion_data['has_company_vision_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_values_strength_value'];
				}if($get_user_profile_completion_data['has_company_mission_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_mission_strength_value'];
				}if($get_user_profile_completion_data['has_company_core_values_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_core_values_strength_value'];
				}if($get_user_profile_completion_data['has_company_strategy_goals_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_strategy_goals_strength_value'];
				}if($get_user_profile_completion_data['has_company_invoicing_details_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_invoicing_details_strength_value'];
				}
			}
			
		}
		
		
		$this->db->update('users_accounts_profile_completion_tracking', ['user_profile_completion_percentage'=>$user_profile_completion, 'profile_last_update_time' => date('Y-m-d H:i:s'), 'user_profile_strength_value' => ($user_profile_completion / $divider_value)], ['user_id'=> $user_id]);
		
		$get_user_accounts_profile_completion_data = $this->db // get the user detail
		->select('user_profile_completion_percentage')
		->from('users_accounts_profile_completion_tracking')
		->where('user_id', $user_id)
		->get()->row_array();
		if(floatval($get_user_accounts_profile_completion_data['user_profile_completion_percentage']) == 0){
			$this->db->delete('users_accounts_profile_completion_tracking', array("user_id" => $user_id));
		}	
	}

}
?>