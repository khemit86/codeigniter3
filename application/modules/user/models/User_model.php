<?php

if ( ! defined ('BASEPATH'))
    exit ('No direct script access allowed');

class User_model extends BaseModel
{

   /*  protected $hidden = [
        'password', 'code_login'
    ]; */

    public function __construct ()
    {
		$this->load->model ('post_project/Post_project_model');
		$this->load->model('dashboard/Dashboard_model');
        return parent::__construct ();
    }

	// @sid Remove logged in user entry from user log table when he/she logged off
	public function remove_from_user_log($data) {
		$this->db->where($data);
		$this->db->delete('user_log');
	}
	
	public function get_user_detail_from_profile_name ($profile_name)
    {
	
		$result = $this->db->get_where('users', ['profile_name LIKE' => $profile_name])->row_array();
        return $result;
    }
	
	public function delete_obsolete_entry_user_profile_cover_picture($user_id){
	
		$count_user_cover_picture = $this->db // count the project featured cover picture record
				->select ('id')
				->from ('users_profile_cover_picture_tracking')
				->where('user_id',$user_id)
				->get ()->num_rows ();
		if($count_user_cover_picture == 0){
			$this->delete_user_profile_cover_picture_record($user_id);
		}else{
		
			$user_detail = $this->db // get the user detail
			->select('u.user_id,u.profile_name')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
		
			$user_cover_picture_detail = $this->db->get_where('users_profile_cover_picture_tracking', ['user_id' => $user_id])->row_array();
			if(!empty($user_cover_picture_detail['profile_cover_picture_name'])){
			
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config);
			
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$user_cover_picture = USER_COVER_PICTURE;
				$profile_folder     = $user_detail['profile_name'];
					
				$expl   = explode('.',$user_cover_picture_detail['profile_cover_picture_name']);
				$original_profile_cover_picture_name	= $expl[0].'_original.png';
					
					//for main cover picture
				$source_path = $users_ftp_dir.$profile_folder.$user_cover_picture.$user_cover_picture_detail['profile_cover_picture_name'];
				$file_size = $this->ftp->get_filesize($source_path);
                                
                //for main cover picture
				$source_path_original = $users_ftp_dir.$profile_folder.$user_cover_picture.$original_profile_cover_picture_name;
				$file_size_original = $this->ftp->get_filesize($source_path_original);
                                
                $ccheck = true;
				if($file_size == '-1' && $ccheck) {
					$this->delete_user_profile_cover_picture_record($user_id);
					$ccheck = false;
				} else if($file_size_original == '-1' && $ccheck) {
					$this->delete_user_profile_cover_picture_record($user_id);
					$ccheck = false;
				} else {
					$this->ftp->close();
				}
				
			}
		}
	
	
	}
	
	// This function is used to delete the user profile cover picture 
	public function delete_user_profile_cover_picture_record($user_id)
	{	
		
		################# remove the obsolete entry start ###########
		$user_detail = $this->db // get the user detail
			->select('u.user_id,u.profile_name')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] 	= FTP_PORT;
		$config['debug']    = TRUE;
		$this->ftp->connect($config);	
		$users_ftp_dir 	= USERS_FTP_DIR;
		$user_cover_picture = USER_COVER_PICTURE;
		$profile_folder     = $user_detail['profile_name'];
		$source_path = $users_ftp_dir.$profile_folder.$user_cover_picture;
		if(!empty($this->ftp->check_ftp_directory_exist($source_path)))
		{
			$this->ftp->delete_dir($source_path);// delete user cover picture directory 
		}
		$this->ftp->close();
		$this->db->delete('users_profile_cover_picture_tracking', array('user_id' => $user_id));
		################# remove the obsolete entry end ###########
	
	}
	
	// get language list from serv_languages table
	public function get_languages()
        {
            $result = $this->db->get('languages');
            return $result->result_array();
        }
	// get language name with respect to language id
	public function get_language_name_from_id($id)
	{
            $result = $this->db
                ->select ('language')
                ->from ('languages')
                ->where('id',$id)
                ->get()->row_array();
            return $result;		
	}
	// get category list from serv_categories_professionals table
	public function get_professional_categories($data=array())
        {
            $this->db->select('id, name');
            $this->db->from('categories_professionals');
            $this->db->where('status','Y');
            if($data['type']==1) {
                $this->db->where('parent_id',0);
            } if($data['type']==2) {
                $this->db->where('parent_id<>',0);
                $this->db->where('parent_id',$data['parent_id']);
            }
             $this->db->order_by('name','ASC');
            $query = $this->db->get();
            return $query->result_array();
        }
       
	// This function is use for validating company base information
	public function company_account_base_information_validation($post_data) {
		$user = $this->session->userdata('user');
		$user_id = $user[0]->user_id;
		$i = 0;
		if(empty($post_data['founded_in']) && array_key_exists('founded_in', $post_data)) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'error_msg_founded_in';
			$msg['errors'][$i]['error_class'] = 'required';
			if($user[0]->is_authorized_physical_person == 'Y'){
				$msg['errors'][$i]['message'] = $this->config->item('ca_app_profile_management_base_information_founded_in_required_error_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_base_information_founded_in_required_error_message');
			}
			$i ++;
		}
		if(empty($post_data['company_size']) && array_key_exists('company_size', $post_data)) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_size_error';
			$msg['errors'][$i]['error_class'] = 'required';
			if($user[0]->is_authorized_physical_person == 'Y'){
				$msg['errors'][$i]['message'] = $this->config->item('ca_app_profile_management_base_information_company_size_required_error_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_base_information_company_size_required_error_message');
			}
			$i ++;
		}

		if(empty(trim($post_data['company_vision'])) && array_key_exists('company_vision', $post_data)) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_vision_error';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_vision_required_error_message');
			$i ++;
		} else if(!empty(trim($post_data['company_vision'])) && array_key_exists('company_vision', $post_data)) {
			$user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', trim($post_data['company_vision'])));
			if($this->config->item('ca_profile_management_company_vision_minimum_length_word_limit') == 0) {
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_vision_minimum_length_character_limit')) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_vision_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_vision_minimum_length_error_message');
					$i ++;
				}
			} else {
				$string_only_single_space = preg_replace("/\s+/", " ", trim($post_data['company_vision']));
				$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_vision_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('ca_profile_management_company_vision_minimum_length_word_limit') ){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_vision_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_vision_word_minimum_length_error_message');
					$i ++;
				}
			}
		}

		if(empty(trim($post_data['company_mission'])) && array_key_exists('company_mission', $post_data)) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_mission_error';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_mission_required_error_message');
			$i ++;
		} else if(!empty(trim($post_data['company_mission'])) && array_key_exists('company_mission', $post_data)) {
			$user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', trim($post_data['company_mission'])));
			if($this->config->item('ca_profile_management_company_mission_minimum_length_word_limit') == 0) {
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_mission_minimum_length_character_limit')) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_mission_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_mission_minimum_length_error_message');
					$i ++;
				}
			} else {
				$string_only_single_space = preg_replace("/\s+/", " ", trim($post_data['company_mission']));
				$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_mission_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('ca_profile_management_company_mission_minimum_length_word_limit') ){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_mission_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_mission_word_minimum_length_error_message');
					$i ++;
				}
			}
		}

		if(empty(trim($post_data['company_core_values'])) && array_key_exists('company_core_values', $post_data)) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_core_error';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_core_values_required_error_message');
			$i ++;
		} else if(!empty(trim($post_data['company_core_values'])) && array_key_exists('company_core_values', $post_data)) {
			$user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', trim($post_data['company_core_values'])));
			if($this->config->item('ca_profile_management_company_core_values_minimum_length_word_limit') == 0) {
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_core_values_minimum_length_character_limit')) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_core_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_core_values_minimum_length_error_message');
					$i ++;
				}
			} else {
				$string_only_single_space = preg_replace("/\s+/", " ", trim($post_data['company_core_values']));
				$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_core_values_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('ca_profile_management_company_core_values_minimum_length_word_limit') ){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_core_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_core_values_word_minimum_length_error_message');
					$i ++;
				}
			}
		}

		if(empty(trim($post_data['company_strategy_goals'])) && array_key_exists('company_strategy_goals', $post_data)) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_strategy_error';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_strategy_goals_required_error_message');
			$i ++;
		} else if(!empty(trim($post_data['company_strategy_goals'])) && array_key_exists('company_strategy_goals', $post_data)) {
			$user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', trim($post_data['company_strategy_goals'])));
			if($this->config->item('ca_profile_management_company_strategy_goals_minimum_length_word_limit') == 0) {
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_strategy_goals_minimum_length_character_limit')) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_strategy_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_strategy_goals_minimum_length_error_message');
					$i ++;
				}
			} else {
				$string_only_single_space = preg_replace("/\s+/", " ", trim($post_data['company_strategy_goals']));
				$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($user_description_charcaters_length < $this->config->item('ca_profile_management_company_strategy_goals_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('ca_profile_management_company_strategy_goals_minimum_length_word_limit') ){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'company_strategy_error';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_company_strategy_goals_word_minimum_length_error_message');
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
	// This function is used for validating company opening hours
	public function company_account_opening_hours_validation($post_data) {
		$user = $this->session->userdata('user'); 
		$i = 0;
		if(empty($post_data['days'])) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'day_error';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opened_hours_select_at_least_one_day_error_message'):$this->config->item('ca_profile_management_base_information_company_opened_hours_select_at_least_one_day_error_message');
			$i ++;
		} else if(!empty($post_data['days'])) {
			foreach($post_data['days'] as $val) {
				if(empty($post_data['op'][$val])) {
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'op_'.$val.'_error';
						$msg['errors'][$i]['error_class'] = 'required';
						$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opened_hours_opening_time_required_error_message'):$this->config->item('ca_profile_management_base_information_company_opened_hours_opening_time_required_error_message');
						$i++;
				} else if(empty($post_data['cl'][$val])) {
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'cl_'.$val.'_error';
						$msg['errors'][$i]['error_class'] = 'required';
						$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('ca_app_profile_management_base_information_company_opened_hours_closing_time_required_error_message'):$this->config->item('ca_profile_management_base_information_company_opened_hours_closing_time_required_error_message');
						$i++;
				}

				// if(!empty($val) && !empty($post_data['cl'][$k])) {
					// if($val == $post_data['cl'][$k]) {
					// 	$msg['status'] = 'FAILED';
					// 	$msg['errors'][$i]['id'] = 'op_'.$post_data['days'][$k].'_error';
					// 	$msg['errors'][$i]['error_class'] = 'required';
					// 	$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_base_information_company_opened_hours_opening_time_equal_to_closing_time_error_message');
					// 	$i ++;

					// 	$msg['status'] = 'FAILED';
					// 	$msg['errors'][$i]['id'] = 'cl_'.$post_data['days'][$k].'_error';
					// 	$msg['errors'][$i]['error_class'] = 'required';
					// 	$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_base_information_company_opened_hours_closing_time_equal_to_opening_time_error_message');
					// 	$i ++;
					// }
					// $st_time = strtotime($val);
					// $end_time = strtotime($post_data['cl'][$k]);
					// if($st_time > $end_time) {
					// 	$msg['status'] = 'FAILED';
					// 	$msg['errors'][$i]['id'] = 'op_'.$post_data['days'][$k].'_error';
					// 	$msg['errors'][$i]['error_class'] = 'required';
					// 	$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_base_information_company_opened_hours_opening_time_grater_than_closing_time_error_message');
					// 	$i ++;
					// }
					// if($end_time < $st_time) { 
					// 	$msg['status'] = 'FAILED';
					// 	$msg['errors'][$i]['id'] = 'cl_'.$post_data['days'][$k].'_error';
					// 	$msg['errors'][$i]['error_class'] = 'required';
					// 	$msg['errors'][$i]['message'] = $this->config->item('ca_profile_management_base_information_company_opened_hours_closing_time_less_than_opening_time_error_message');
					// 	$i ++;
					// }
				// }
			}
		}
		if($i == 0){
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
		}
		return $msg;
	}
	//This function is using for validation of user address details
	 public function user_address_form_validation(){
	
            $i = 0;
            if(!empty($this->input->post ('address_details_street_address')) && empty($this->input->post ('address_country_id'))){
                $msg['status'] = 'FAILED';
                $msg['errors'][$i]['id'] = 'address_country_id';
                $msg['errors'][$i]['error_class'] = 'required';
                $msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_country_required_field_error_message');
                $i ++;
            }else{
				if(empty($this->input->post ('address_country_id'))){
				 $msg['status'] = 'FAILED';
                $msg['errors'][$i]['id'] = 'address_country_id';
                $msg['errors'][$i]['error_class'] = 'required';
                $msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_country_required_field_error_message');
                $i ++;
				}
			}
			if($this->input->post ('address_country_id')==  $this->config->item('countries_drop_down_top_displayed_option_country_db_id') && empty($this->input->post('address_county_id')) && !empty($this->input->post ('address_locality_id'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'address_county_id';
				//$msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_locality_required_field_error_message');
				$msg['errors'][$i]['error_class'] = 'required';
					$i ++;
			}
			/* if($this->input->post ('address_country_id')== $this->config->item('countries_drop_down_top_displayed_option_country_db_id') && !empty($this->input->post('address_county_id')) && !empty($this->input->post ('address_locality_id')) && empty($this->input->post ('address_postal_code_id'))){
				if(!empty($this->Post_project_model->get_project_post_codes($this->input->post ('address_locality_id')))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'address_postal_code_id';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_postal_code_required_field_error_message');
					$msg['errors'][$i]['error_class'] = 'required';
					$i ++;
				}
				
		  } */
           if(!empty($this->input->post ('address_details_street_address')) && mb_strlen($this->input->post ('address_details_street_address')) < $this->config->item('account_management_address_details_street_address_minimum_length_character_limit')){
		
                $msg['status'] = 'FAILED';
                $msg['errors'][$i]['id'] = 'street_address';
                $msg['errors'][$i]['error_class'] = 'min_length';
                $msg['errors'][$i]['message'] = $this->config->item('account_management_address_details_street_address_minimum_length_error_message');
                $i ++;
            }
            
            if($i == 0){
                $msg['status'] = 'SUCCESS';
                $msg['message'] = '';
            }
            return $msg;
	}
	
	//This function is using for validation of user education training details.
	public function user_education_training_form_validation(){
		$user = $this->session->userdata('user');
		$i = 0;
		if (empty($this->input->post ('diploma_name'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'diploma_name';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_required_validation_message'):$this->config->item('personal_account_education_section_diploma_name_required_validation_message');
            $i ++;
        }
		if (empty($this->input->post ('school_name'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'school_name';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_required_validation_message'):$this->config->item('personal_account_education_section_school_name_required_validation_message');
            $i ++;
        }
		if (empty($this->input->post ('school_address'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'school_address';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_required_validation_message'):$this->config->item('personal_account_education_section_school_address_required_validation_message');
            $i ++;
        }
		if (empty($this->input->post ('school_country'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'school_country';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_country_required_validation_message'):$this->config->item('personal_account_education_section_school_country_required_validation_message');
            $i ++;
        }
		if (empty($this->input->post ('graduate_in')) && empty($this->input->post ('graduate_inprogress'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'graduate_in';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_graduate_year_required_validation_message'):$this->config->item('personal_account_education_section_graduate_year_required_validation_message');
            $i ++;
        }
		
		$diploma_name_characters_minimum_length = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_diploma_name_characters_minimum_length_characters_limit');
		
		if(!empty($this->input->post ('diploma_name')) && mb_strlen($this->input->post ('diploma_name')) < $diploma_name_characters_minimum_length){
		
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'diploma_name';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_diploma_name_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_diploma_name_characters_minimum_length_validation_message');
			$i ++;
		}
		$school_name_characters_minimum_length = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_school_name_characters_minimum_length_characters_limit');
		
		if(!empty($this->input->post ('school_name')) && mb_strlen($this->input->post ('school_name')) < $school_name_characters_minimum_length){
		
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'school_name';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_name_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_school_name_characters_minimum_length_validation_message');
			$i ++;
		}
		
		$school_address_characters_minimum_length = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_school_address_characters_minimum_length_characters_limit');
		
		if(!empty($this->input->post ('school_address')) && mb_strlen($this->input->post ('school_address')) < $school_address_characters_minimum_length){
		
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'school_address';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_school_address_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_school_address_characters_minimum_length_validation_message');
			$i ++;
		}
	
		if(!empty($this->input->post ('comment'))){
			
			$comment_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('comment')));
			
			$comment_characters_minimum_length = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_minimum_length_characters_limit'):$this->config->item('personal_account_education_section_comments_characters_minimum_length_characters_limit');
			
			$comment_word_minimum_length = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_minimum_length_words_limit'):$this->config->item('personal_account_education_section_comments_characters_minimum_length_words_limit');
			
			
			
			if($comment_word_minimum_length == 0){
				
				if($comment_description_charcaters_length < $comment_characters_minimum_length){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'comment';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_comments_characters_minimum_length_validation_message'):$this->config->item('personal_account_education_section_comments_characters_minimum_length_validation_message');
					
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('comment'));
				$comment_word_count = count(explode(' ', trim($string_only_single_space)));
				if($comment_description_charcaters_length < $comment_characters_minimum_length ||$comment_word_count < $comment_word_minimum_length ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'comment';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_education_section_comments_words_minimum_length_validation_message'):$this->config->item('personal_account_work_education_section_comments_words_minimum_length_validation_message');
						
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
      
	//This function is using for validation of user certification details.  
	public function user_certifications_form_validation(){
		$user = $this->session->userdata('user');	
		if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
			$user_certifications_section_certification_name_characters_minimum_length_characters_limit = $this->config->item('pa_user_certifications_section_certification_name_characters_minimum_length_characters_limit'); 
			$user_certifications_section_certification_name_characters_minimum_length_validation_message = $this->config->item('pa_user_certifications_section_certification_name_characters_minimum_length_validation_message'); 
			$user_certifications_section_certification_name_required = $this->config->item('pa_user_certifications_section_certification_name_required'); 
			$user_certifications_section_date_acquired_select_year_required = $this->config->item('pa_user_certifications_section_date_acquired_select_year_required'); 
			$user_certifications_section_date_acquired_select_month_required = $this->config->item('pa_user_certifications_section_date_acquired_select_month_required'); 
		} else {
			$user_certifications_section_certification_name_characters_minimum_length_characters_limit = $this->config->item('ca_user_certifications_section_certification_name_characters_minimum_length_characters_limit'); 
			$user_certifications_section_certification_name_characters_minimum_length_validation_message = $this->config->item('ca_user_certifications_section_certification_name_characters_minimum_length_validation_message'); 
			$user_certifications_section_certification_name_required = $this->config->item('ca_user_certifications_section_certification_name_required'); 
			$user_certifications_section_date_acquired_select_year_required = $this->config->item('ca_user_certifications_section_date_acquired_select_year_required'); 
			$user_certifications_section_date_acquired_select_month_required = $this->config->item('ca_user_certifications_section_date_acquired_select_month_required'); 
		}
		$i = 0;
		if (empty($this->input->post ('certification_name'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'certification_name';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $user_certifications_section_certification_name_required;
			$i ++;
		}
		if(!empty($this->input->post ('certification_name')) && mb_strlen($this->input->post ('certification_name')) < $user_certifications_section_certification_name_characters_minimum_length_characters_limit){
	
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'certification_name';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = $user_certifications_section_certification_name_characters_minimum_length_validation_message;
			$i ++;
		}
		if (empty($this->input->post ('certification_month'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'certification_month';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $user_certifications_section_date_acquired_select_month_required;
			$i ++;
		}
		if (empty($this->input->post ('certification_year'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'certification_year';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $user_certifications_section_date_acquired_select_year_required;
			$i ++;
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
		}
		return $msg;
	}
	
	//This function is using for validation of user work experience details.
	public function user_work_experience_form_validation(){
		$i = 0;
		$user = $this->session->userdata ('user');
		if (empty($this->input->post ('position_title'))){
		$msg['status'] = 'FAILED';
		$msg['errors'][$i]['id'] = 'position_title';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_required_validation_message'):$this->config->item('personal_account_work_experience_section_position_title_required_validation_message');
			
			
			
			$i ++;
		}
		if(!empty($this->input->post ('position_title')) && mb_strlen($this->input->post ('position_title')) < $this->config->item('personal_account_work_experience_section_position_title_characters_minimum_length_characters_limit')){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'position_title';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_title_characters_minimum_length_validation_message'):$this->config->item('personal_account_work_experience_section_position_title_characters_minimum_length_validation_message');
			$i ++;
		}
		if (empty($this->input->post ('company_name'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_name';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_required_validation_message'):$this->config->item('personal_account_work_experience_section_company_name_required_validation_message');
			$i ++;
		}
		if (empty($this->input->post ('company_country'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'country_name';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_country_name_required_validation_message'):$this->config->item('personal_account_work_experience_section_company_country_name_required_validation_message');
			$i ++;
		}
		
		$company_name_characters_minimum_length_characters_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_characters_minimum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_name_characters_minimum_length_characters_limit');
		
		if(!empty($this->input->post ('company_name')) && mb_strlen($this->input->post ('company_name')) < $company_name_characters_minimum_length_characters_limit){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_name';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_name_characters_minimum_length_validation_message'):$this->config->item('personal_account_work_experience_section_company_name_characters_minimum_length_validation_message');
			
			$i ++;
		}
		if (empty($this->input->post ('company_address'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_address';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_required_validation_message'):$this->config->item('personal_account_work_experience_section_company_address_required_validation_message');
			$i ++;
		}
		
		$company_address_characters_minimum_length_characters_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_characters_minimum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_company_address_characters_minimum_length_characters_limit');
		
		if(!empty($this->input->post ('company_address')) && mb_strlen($this->input->post ('company_address')) < $company_address_characters_minimum_length_characters_limit){

			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'company_address';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_company_address_characters_minimum_length_validation_message'):$this->config->item('personal_account_work_experience_section_company_address_characters_minimum_length_validation_message');
			$i ++;
		}
		if (empty($this->input->post ('month_from'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'month_from';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_month_from_required_validation_message'):$this->config->item('personal_account_work_experience_section_month_from_required_validation_message');
			$i ++;
		}
		if (empty($this->input->post ('year_from'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'year_from';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_from_required_validation_message'):$this->config->item('personal_account_work_experience_section_year_from_required_validation_message');
			$i ++;
		}
		if(empty($this->input->post ('still_work'))){
			if (empty($this->input->post ('month_to'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'month_to';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_month_to_required_validation_message'):$this->config->item('personal_account_work_experience_section_month_to_required_validation_message');
				$i ++;
			}
			if (empty($this->input->post ('year_to'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'year_to';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_year_to_required_validation_message'):$this->config->item('personal_account_work_experience_section_year_to_required_validation_message');
				$i ++;
			}
		
			if(!empty($this->input->post ('position_description'))){
				$position_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('position_description')));
				
				if($user[0]->is_authorized_physical_person == 'Y'?$this->config->item('company_account_app_work_experience_section_position_description_characters_minimum_length_words_limit'):$this->config->item('personal_account_work_experience_section_position_description_characters_minimum_length_words_limit') == 0){
					
					if($position_description_charcaters_length < $this->config->item('personal_account_work_experience_section_position_description_characters_minimum_length_characters_limit')){
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'position_description';
						$msg['errors'][$i]['error_class'] = 'min_length';
						$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_description_characters_minimum_length_validation_message'):$this->config->item('personal_account_work_experience_section_position_description_characters_minimum_length_validation_message');
						$i ++;
					}
				
				}else{
					//remove unwanted space between words
					
					$position_characters_minimum_length_characters_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_description_characters_minimum_length_characters_limit'):$this->config->item('personal_account_work_experience_section_position_description_characters_minimum_length_characters_limit');
					
					$position_characters_minimum_length_words_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_description_characters_minimum_length_words_limit'):$this->config->item('personal_account_work_experience_section_position_description_characters_minimum_length_words_limit');
					
					$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('position_description'));
					$position_description_word_count = count(explode(' ', trim($string_only_single_space)));
					if($position_description_charcaters_length < $position_characters_minimum_length_characters_limit ||$position_description_word_count < $position_characters_minimum_length_words_limit ){
						
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'position_description';
						$msg['errors'][$i]['error_class'] = 'min_length';
						$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_position_description_words_minimum_length_validation_message'):$this->config->item('personal_account_work_experience_section_position_description_words_minimum_length_validation_message');	
						$i ++;
					}
				}
			}
			if(!empty($this->input->post ('year_from')) && !empty($this->input->post ('month_from')) && !empty($this->input->post ('year_to')) && !empty($this->input->post ('month_to'))){
			
				$from_date = $this->input->post ('year_from')."-".$this->input->post ('month_from')."-"."00 00:00:00";
				$to_date = $this->input->post ('year_to')."-".$this->input->post ('month_to')."-"."00 00:00:00";
				if(strtotime($to_date) < strtotime($from_date)){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'from_to';
					$msg['errors'][$i]['error_class'] = 'required';
					$msg['errors'][$i]['message'] = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_to_from_year_month_greater_validation_message'):$this->config->item('personal_account_work_experience_section_to_from_year_month_greater_validation_message');
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
	
	//This function is using for validation of user contact details.
	public function user_contact_details_validation(){
		$i = 0;
		if($this->input->post('section_name') == 'skype'){
			
			if (empty(trim($this->input->post ('data')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'skype';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_skype_id_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('data')))){
				$skype_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('data')));
				
				if($skype_charcaters_length < $this->config->item('account_management_contact_details_skype_id_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'skype';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_skype_id_characters_minimum_length_validation_message');
					$i ++;
				}
				if($skype_charcaters_length > $this->config->item('account_management_contact_details_skype_id_characters_maximum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'skype';
					$msg['errors'][$i]['error_class'] = 'max_length';
					$msg['errors'][$i]['message'] = '';
					$i ++;
				}
			}
		}
		if($this->input->post('section_name') == 'contact_email'){
			
			if (empty(trim($this->input->post ('data')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'contact_email';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_contact_email_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('data'))) && !filter_var (trim($this->input->post ('data')), FILTER_VALIDATE_EMAIL)){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'contact_email';
				$msg['errors'][$i]['error_class'] = 'invalid_email';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_contact_email_invalid_format_field_error_message');
				$i ++;
			}
		}
		if($this->input->post('section_name') == 'website_url'){
			
			if (empty(trim($this->input->post ('data')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'website_url';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_website_url_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('data')))){
				$pattern = "/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,10}(:[0-9]{1,5})?(\/.*)?$/i";
				$url = trim($this->input->post ('data'));
				if (!preg_match($pattern,$url)) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'website_url';
					$msg['errors'][$i]['error_class'] = 'invalid_url';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_invalid_website_url_field_error_message');
					$i ++;
				}
				
			}
		}
		if($this->input->post('section_name') == 'additional_phone_number'){
			
			if (empty(trim($this->input->post ('data')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'additional_phone_number';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_additional_phone_number_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('data')))){
				$additional_phone_number_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('data')));
				
				if($additional_phone_number_charcaters_length < $this->config->item('account_management_contact_details_additional_phone_number_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'additional_phone_number';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_additional_phone_number_characters_minimum_length_validation_message');
					$i ++;
				}
				if($additional_phone_number_charcaters_length > $this->config->item('account_management_contact_details_additional_phone_number_characters_maximum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'additional_phone_number';
					$msg['errors'][$i]['error_class'] = 'max_length';
					$msg['errors'][$i]['message'] = '';
					$i ++;
				}
			}
		}
		if($this->input->post('section_name') == 'mobile_phone_number'){
			
			if (empty(trim($this->input->post ('data')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'mobile_phone_number';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_mobile_phone_number_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('data')))){
				$mobile_number_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('data')));
				
				if($mobile_number_charcaters_length < $this->config->item('account_management_contact_details_mobile_phone_number_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'mobile_phone_number';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_mobile_phone_number_characters_minimum_length_validation_message');
					$i ++;
				}
				if($mobile_number_charcaters_length > $this->config->item('account_management_contact_details_mobile_phone_number_characters_maximum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'mobile_phone_number';
					$msg['errors'][$i]['error_class'] = 'max_length';
					$msg['errors'][$i]['message'] = '';
					$i ++;
				}
			}
		}
		if($this->input->post('section_name') == 'phone_number'){
			
			if (empty(trim($this->input->post ('data')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'phone_number';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_phone_number_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('data')))){
				$phone_number_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('data')));
				
				if($phone_number_charcaters_length < $this->config->item('account_management_contact_details_phone_number_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'phone_number';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_contact_details_phone_number_characters_minimum_length_validation_message');
					$i ++;
				}
				if($phone_number_charcaters_length > $this->config->item('account_management_contact_details_phone_number_characters_maximum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'phone_number';
					$msg['errors'][$i]['error_class'] = 'max_length';
					$msg['errors'][$i]['message'] = '';
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

	// This function is using for validation when user update his account email(registration)
	public function user_update_login_email_validation(){
		$i = 0;
		$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
		if($this->input->post('update_email_step') == '1'){
			if (empty(trim($this->input->post ('current_email')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'current_email';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_current_email_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('current_email'))) && !filter_var (trim($this->input->post ('current_email')), FILTER_VALIDATE_EMAIL)){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'current_email';
				$msg['errors'][$i]['error_class'] = 'invalid_email';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_current_email_invalid_format_field_error_message');
				$i ++;
			}
			
			if (empty(trim($this->input->post ('current_password')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'current_password';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_current_password_required_field_error_message');
				$i ++;
			}
			
			if(!empty(trim($this->input->post ('current_email'))) && filter_var (trim($this->input->post ('current_email')), FILTER_VALIDATE_EMAIL) && !empty($this->input->post ('current_password'))){
			
				$check_user_email_record = $this->db // get the user detail
				->select('u.email')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
				$check_user_password_record = $this->db // get the user detail
				->select('u.password')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
				if((!empty($check_user_email_record) && $check_user_email_record['email'] != trim($this->input->post ('current_email'))) || (!empty($check_user_password_record) && $check_user_password_record['password'] !=  md5 (trim($this->input->post ('current_password'))))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'current_email';
					$msg['errors'][$i]['error_class'] = 'incorrect_email_password';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_current_email_password_incorrect_combination_field_error_message');
					$i ++;
				}
			}
		}
		if($this->input->post('update_email_step') == '2'){
			$new_email_valid = '0';
			$confirm_new_email_valid = '0';
			if (empty(trim($this->input->post ('new_email')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'new_email';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_new_email_required_field_error_message');
				$i ++;
			}
			if (empty(trim($this->input->post ('confirm_new_email')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'confirm_new_email';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_confirm_new_email_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('new_email'))) && !filter_var (trim($this->input->post ('new_email')), FILTER_VALIDATE_EMAIL)){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'new_email';
				$msg['errors'][$i]['error_class'] = 'invalid_email';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_new_email_invalid_format_field_error_message');
				$i ++;
				
			}else{
				$new_email_valid = '1';
			}
			if (!empty(trim($this->input->post ('confirm_new_email'))) && !filter_var (trim($this->input->post ('confirm_new_email')), FILTER_VALIDATE_EMAIL)){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'confirm_new_email';
				$msg['errors'][$i]['error_class'] = 'invalid_email';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_confirm_new_email_invalid_format_field_error_message');
				$i ++;
			}else{
				$confirm_new_email_valid = '1';
				
			}
			if(!empty(trim($this->input->post ('new_email'))) && !empty(trim($this->input->post ('confirm_new_email'))) &&  $new_email_valid == '1' && $confirm_new_email_valid =='1' && trim($this->input->post ('new_email')) != trim($this->input->post ('confirm_new_email'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'new_email';
				$msg['errors'][$i]['error_class'] = 'new_email_not_match';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_new_email_confirmation_not_match_new_email_field_error_message');
				$i ++;
			}
			if(!empty(trim($this->input->post ('new_email'))) && !empty(trim($this->input->post ('confirm_new_email'))) &&  $new_email_valid == '1' && $confirm_new_email_valid =='1' && trim($this->input->post ('new_email')) == trim($this->input->post ('confirm_new_email'))){
				$check_user_email_record = $this->db // get the user detail
				->select('u.email')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();	
				if($check_user_email_record['email'] == $this->input->post ('new_email')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'new_email';
					$msg['errors'][$i]['error_class'] = 'new_email_old_email_match';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_new_email_match_old_email_field_error_message');
					$i ++;
				}else{
					
					$check_verified_user_new_email_record_exists = $this->db // get the user detail
					->select('u.email')
					->from('users u')
					->where('u.user_id !=', $user_id)
					->where('u.email', trim($this->input->post ('new_email')))
					->get()->row_array();
					
					$check_unverified_user_new_email_record_exists = $this->db // get the user detail
					->select('u.email')
					->from('users_new_registrations_pending_verification u')
					->where('u.user_id !=', $user_id)
					->where('u.email', trim($this->input->post ('new_email')))
					->get()->row_array();
					
					
					if(!empty($check_verified_user_new_email_record_exists) || !empty($check_unverified_user_new_email_record_exists)){
					
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'new_email';
						$msg['errors'][$i]['error_class'] = 'unique';
						$msg['errors'][$i]['message'] = $this->config->item('account_management_update_email_section_new_email_not_unique_error_message');
						$i ++;
					
					}
				
				}
			
			
			
			}
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
		}
		return $msg;
	}
	
	// This function is using for validation when usr update password from his account
	public function user_update_login_password_validation(){
		$i = 0;
		$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
		
		if($this->input->post('update_password_step') == '1'){
			if (empty(trim($this->input->post ('current_email')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'current_email_pwd_section';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_current_email_required_field_error_message');
				$i ++;
			}
			if (!empty(trim($this->input->post ('current_email'))) && !filter_var (trim($this->input->post ('current_email')), FILTER_VALIDATE_EMAIL)){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'current_email_pwd_section';
				$msg['errors'][$i]['error_class'] = 'invalid_email';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_current_email_invalid_format_field_error_message');
				$i ++;
			}
			
			if (empty(trim($this->input->post ('current_password')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'current_password_pwd_section';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_current_password_required_field_error_message');
				$i ++;
			}
			
			if(!empty(trim($this->input->post ('current_email'))) && filter_var (trim($this->input->post ('current_email')), FILTER_VALIDATE_EMAIL) && !empty($this->input->post ('current_password'))){
			
				$check_user_email_record = $this->db // get the user detail
				->select('u.email')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
				$check_user_password_record = $this->db // get the user detail
				->select('u.password')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				
			
				
				
				if((!empty($check_user_email_record) && $check_user_email_record['email'] != trim($this->input->post ('current_email'))) || (!empty($check_user_password_record) && $check_user_password_record['password'] !=  md5 (trim($this->input->post ('current_password'))))){
				
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'current_email_pwd_section';
					$msg['errors'][$i]['error_class'] = 'incorrect_email_password';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_current_email_password_incorrect_combination_field_error_message');
					$i ++;
				}
			}
		}
		
		
		/* if (empty(trim($this->input->post ('user_current_password')))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'user_current_password';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_current_password_required_field_error_message');
			$i ++;
		}
		if (empty(trim($this->input->post ('user_new_password')))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'user_new_password';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_new_password_required_field_error_message');
			$i ++;
		}
		if (empty(trim($this->input->post ('user_confirm_new_password')))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'user_confirm_new_password';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_confirm_new_password_required_field_error_message');
			$i ++;
		} 
		$check_current_password = '0';
		if (!empty(trim($this->input->post ('user_current_password')))){
		
			$check_user_password_record = $this->db // get the user detail
			->select('u.password')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
			if(!empty($check_user_password_record) && $check_user_password_record['password'] !=  md5 (trim($this->input->post ('user_current_password')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_current_password';
				$msg['errors'][$i]['error_class'] = 'incorrect_current_password';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_current_password_incorrect_field_error_message');
				$i ++;
			}else{
				$check_current_password = '1';
			}
		}*/
		if($this->input->post('update_password_step') == '2'){
		
			if (empty(trim($this->input->post ('user_new_password')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_new_password';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_new_password_required_field_error_message');
				$i ++;
			}
			if (empty(trim($this->input->post ('user_confirm_new_password')))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_confirm_new_password';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_confirm_new_password_required_field_error_message');
				$i ++;
			} 
			
			$new_password_valid = '0';
			$confirm_new_password_valid = '0';
			if (!empty(trim($this->input->post ('user_new_password')))){
				if(mb_strlen(trim($this->input->post ('user_new_password'))) < $this->config->item('account_management_update_password_section_password_min_length_character_limit')){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'user_new_password';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_new_password_minimum_length_error_message');
					$i ++;
				}else{
					$new_password_valid = '1';
				}
			}
			if (!empty(trim($this->input->post ('user_confirm_new_password')))){
				if(mb_strlen(trim($this->input->post ('user_confirm_new_password'))) < $this->config->item('account_management_update_password_section_password_min_length_character_limit')){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'user_confirm_new_password';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_confirm_new_password_minimum_length_error_message');
					$i ++;
					
				}else{
					$confirm_new_password_valid = '1';
				}
			}
			/* echo $confirm_new_password_valid;
			echo $new_password_valid;
			echo "<br>";
			echo $this->input->post ('user_new_password');
			echo "<br>";
			echo $this->input->post ('user_confirm_new_password');
			
			if($this->input->post ('user_new_password') === $this->input->post ('user_confirm_new_password')){
				echo "match";
			}else{
				echo "not match";
			}
			
			
			die; */
			
			
			
			if($new_password_valid == '1' && $confirm_new_password_valid == '1' && trim($this->input->post ('user_new_password')) !== trim($this->input->post ('user_confirm_new_password')) ){
				
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_new_password';
				$msg['errors'][$i]['error_class'] = 'password_confirm_password_not_match';
				$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_new_password_confirmation_not_match_new_password_field_error_message');
				$i ++;
			
			}
			if(!empty(trim($this->input->post ('user_new_password'))) && !empty(trim($this->input->post ('user_confirm_new_password'))) &&  $new_password_valid == '1' && $confirm_new_password_valid =='1' && trim($this->input->post ('user_new_password')) == trim($this->input->post ('user_confirm_new_password'))){
			
			
				$check_user_password_record = $this->db // get the user detail
				->select('u.password')
				->from('users u')
				->where('u.user_id', $user_id)
				->get()->row_array();
				if(!empty($check_user_password_record) && $check_user_password_record['password'] ==  md5 (trim($this->input->post ('user_new_password')))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'user_new_password';
					$msg['errors'][$i]['error_class'] = 'new_password_old_password_match';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_update_password_section_new_password_match_old_password_field_error_message');
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
	
	// This function is using for validation for close account by user
	public function account_management_close_account_form_validation($post_data){
		$i = 0;
		
		if (empty(trim($post_data['close_reason']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'close_reason';
			$msg['errors'][$i]['message'] = $this->config->item('account_management_close_account_page_close_account_reason_required_validation_message');
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		$reason_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $post_data['reason_description']));
		if (empty(trim($post_data['reason_description']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'reason_description';
			$msg['errors'][$i]['message'] = $this->config->item('account_management_close_account_page_close_account_reason_description_required_validation_message');
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		
		if(!empty(trim($post_data['reason_description']))){
			if($this->config->item('account_management_close_account_page_minimum_length_words_limit_reason_description') == 0){
				
				if($reason_description_charcaters_length < $this->config->item('account_management_close_account_page_minimum_length_character_limit_reason_description')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'reason_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_close_account_page_reason_description_characters_min_length_validation_message');
					
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $post_data['reason_description']);
				$reason_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($reason_description_charcaters_length < $this->config->item('account_management_close_account_page_minimum_length_character_limit_reason_description') ||$reason_description_word_count < $this->config->item('account_management_close_account_page_minimum_length_words_limit_reason_description') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'reason_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('account_management_close_account_page_reason_description_characters_words_min_length_validation_message');
					$i ++;
				}
			}
		}
		if(!empty($post_data['reason_description']) && mb_strlen($reason_description_charcaters_length) > $this->config->item('account_management_close_account_page_maximum_length_character_limit_reason_description')){
		
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'reason_description';
			$msg['errors'][$i]['error_class'] = 'max_length';
			$msg['errors'][$i]['message'] = $this->config->item('account_management_close_account_page_reason_description_characters_max_length_validation_message');
			
			$i ++;
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
		}
		return $msg;
	
	}
	
	
	
	//This function is using for validation of user profile definations.
	public function user_profile_definations_validation(){
		//mb_strlen()
		$i = 0;
		$user = $this->session->userdata ('user');
		if($this->input->post('section_name') == 'headline'){
			
			if (empty(trim($this->input->post ('data')))){
				
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'headline';
				$msg['errors'][$i]['error_class'] = 'required';
				
				if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
				{	
					$error_msg = $this->config->item('pa_profile_management_user_headline_required_field_error_message');
				}else{
					$error_msg = $this->config->item('ca_profile_management_user_headline_required_field_error_message');
				}
				$msg['errors'][$i]['message'] = $error_msg;
				$i ++;
			}
			if (!empty(trim($this->input->post ('data')))){
				$headline_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('data')));
				
				if($headline_charcaters_length < $this->config->item('profile_management_user_headline_minimum_length_character_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'headline';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
					{	
						$error_msg = $this->config->item('pa_profile_management_user_headline_minimum_length_error_message');
					}else{
						$error_msg = $this->config->item('ca_profile_management_user_headline_minimum_length_error_message');
					}
					$msg['errors'][$i]['message'] = $error_msg;
					$i ++;
				}
				if($headline_charcaters_length > $this->config->item('profile_management_user_headline_maximum_length_character_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'headline';
					$msg['errors'][$i]['error_class'] = 'max_length';
					if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
					{	
						$error_msg = $this->config->item('pa_profile_management_user_headline_maximum_length_error_message');
					}else{
						$error_msg = $this->config->item('ca_profile_management_user_headline_maximum_length_error_message');
					}
					$msg['errors'][$i]['message'] = $error_msg;
					$i ++;
				}
			}
		}
		if($this->input->post('section_name') == 'user_description'){
			 $description = trim($this->input->post('data'));
			 if ($description == '') {
				
				if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
				{
					$error_msg = $this->config->item('pa_profile_management_user_description_required_field_error_message');
				}else{
					$error_msg = $this->config->item('ca_profile_management_user_description_required_field_error_message');
				}
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_description';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $error_msg;
				$i ++;
			}elseif ($description != '') {
				$user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $description));
				if($this->config->item('profile_management_user_description_minimum_length_word_limit') == 0){
				
					if($user_description_charcaters_length < $this->config->item('profile_management_user_description_minimum_length_character_limit')){
					
						if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
							$error_msg = $this->config->item('pa_profile_management_user_description_minimum_length_error_message');
						}else{
							$error_msg = $this->config->item('ca_profile_management_user_description_minimum_length_error_message');
						}
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'user_description';
						$msg['errors'][$i]['error_class'] = 'min_length';
						$msg['errors'][$i]['message'] = $error_msg;
						$i ++;
					}
				}else{
				
					$string_only_single_space = preg_replace("/\s+/", " ", $description);
					$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
					
					if($user_description_charcaters_length < $this->config->item('profile_management_user_description_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('profile_management_user_description_minimum_length_word_limit') ){
						
						if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
							$error_msg = $this->config->item('pa_profile_management_user_description_word_minimum_length_error_message');
						}else{
							$error_msg = $this->config->item('ca_profile_management_user_description_word_minimum_length_error_message');
						}
						
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'user_description';
						$msg['errors'][$i]['error_class'] = 'min_length';
						$msg['errors'][$i]['message'] = $error_msg;
						$i ++;
					
					}else if($user_description_charcaters_length > $this->config->item('profile_management_user_description_maximum_length_character_limit')) {
						
						if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
							$error_msg = $this->config->item('pa_profile_management_user_description_maximum_length_error_message');
						}else{
							$error_msg = $this->config->item('ca_profile_management_user_description_maximum_length_error_message');
						}
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'user_description';
						$msg['errors'][$i]['error_class'] = 'max_length';
						$msg['errors'][$i]['message'] = $error_msg;
						$i ++;
					}	
				}
				
			}	
		}
		if($this->input->post('section_name') == 'user_hourly_rate'){
			 $user_hourly_rate = trim(str_replace(" ","",$this->input->post('data')));
			if (empty($user_hourly_rate)){
				
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_hourly_rate';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('profile_management_user_hourly_rate_required_field_error_message');
				$i ++;
			}
			if (!empty($user_hourly_rate) &&  $user_hourly_rate < $this->config->item('profile_management_user_hourly_rate_min_value')){
				
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_hourly_rate';
				$msg['errors'][$i]['error_class'] = 'min_length';
				$msg['errors'][$i]['message'] = $this->config->item('profile_management_user_hourly_rate_minimum_value_error_message');
				$i ++;
			}
		}
		if($this->input->post('section_name') == 'user_mother_tongue'){
			 $user_mother_tongue = $this->input->post('data');
			if (empty($user_mother_tongue)){
				
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'user_mother_tongue';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('pa_profile_management_user_mother_tongue_language_required_field_error_message');
				$i ++;
			}
			if(!empty($user_mother_tongue)){
				$user = $this->session->userdata ('user');
				$check_mother_tongue_language_already_selected_as_foreign_spoken_language = $this->db->where(['user_id' => $user[0]->user_id,'language_id'=>$user_mother_tongue])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
				if($check_mother_tongue_language_already_selected_as_foreign_spoken_language > 0){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'user_mother_tongue';
					$msg['errors'][$i]['error_class'] = 'invalid';
					$msg['errors'][$i]['message'] = $this->config->item('pa_profile_management_user_mother_tongue_language_already_selected_as_foreign_spoken_language_field_error_message');
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
	
	
	//This function is using for validation of user skill.
	public function user_skill_validation(){
		//mb_strlen()
		
		$i = 0;
		$user = $this->session->userdata ('user');
        $user_id = $user[0]->user_id;
		$check_skills_allowed = 1;
		if (empty(trim($this->input->post ('user_skill')))){
			
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'skill';
			$msg['errors'][$i]['error_class'] = 'required';
			
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
			{	
				$error_msg = $this->config->item('pa_profile_management_user_skills_required_field_error_message');
			}else{
				$error_msg = $this->config->item('ca_profile_management_user_skills_required_field_error_message');
			}
			$msg['errors'][$i]['message'] = $error_msg;
			$check_skills_allowed = 0;
			$i ++;
		}
		if (!empty(trim($this->input->post ('user_skill')))){
			$skill_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('user_skill')));
			
			if($skill_charcaters_length < $this->config->item('profile_management_user_skill_minimum_length_character_limit')){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'skill';
				$msg['errors'][$i]['error_class'] = 'min_length';
				if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
				{	
					$error_msg = $this->config->item('pa_profile_management_user_skill_minimum_length_error_message');
				}else{
					$error_msg = $this->config->item('ca_profile_management_user_skill_minimum_length_error_message');
				}
				$msg['errors'][$i]['message'] = $error_msg;
				$i ++;
				$check_skills_allowed = 0;
			}
			
		}
		if($check_skills_allowed  == 1){
			$user_detail = $this->db // get the user detail
			->select('u.user_id,ud.current_membership_plan_id')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array(); 
			
			if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
				
				if($user_detail['current_membership_plan_id'] == '1'){
					$user_skill_allowed = $this->config->item('pa_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed');
					$error_msg = $this->config->item('profile_management_free_membership_subscriber_user_skills_maximum_slots_reached_error_message');
					
				}else{
					$user_skill_allowed = $this->config->item('pa_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed');
					$error_msg = $this->config->item('profile_management_gold_membership_subscriber_user_skills_maximum_slots_reached_error_message');
				}
				
			} else {
			   
				if($user_detail['current_membership_plan_id'] == '1'){
					$user_skill_allowed = $this->config->item('ca_user_profile_management_competencies_page_free_membership_subscriber_number_skills_slots_allowed');
					$error_msg = $this->config->item('profile_management_free_membership_subscriber_user_skills_maximum_slots_reached_error_message');
				}else{
					$user_skill_allowed = $this->config->item('ca_user_profile_management_competencies_page_gold_membership_subscriber_number_skills_slots_allowed');
					$error_msg = $this->config->item('profile_management_gold_membership_subscriber_user_skills_maximum_slots_reached_error_message');
				}
			}
			$users_skills = $this->db
			->select('*')
			->from('users_skills_tracking')
			->where("user_id", $user_id)
			->get()->result_array();
			if (count($users_skills) >= $user_skill_allowed) {
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'skill';
				$msg['errors'][$i]['error_class'] = 'maximum_slots';
				$msg['errors'][$i]['message'] = $error_msg;
				$i ++;
			} 
			
		}	
		if($i == 0){
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
		}
		return $msg;
	}
	
	
	//This function is using for validation of user service provided.
	public function user_services_provided_validation(){
		//mb_strlen()
		
		$i = 0;
		$user = $this->session->userdata ('user');
        $user_id = $user[0]->user_id;
		$check_service_provided_allowed = 1;
		if (empty(trim($this->input->post ('user_service_provided')))){
			
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'service_provided';
			$msg['errors'][$i]['error_class'] = 'required';
			
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
			{	
				$error_msg = $this->config->item('pa_profile_management_user_services_provided_required_field_error_message');
			}else{
				$error_msg = $this->config->item('ca_profile_management_user_services_provided_required_field_error_message');
			}
			$msg['errors'][$i]['message'] = $error_msg;
			$check_service_provided_allowed = 0;
			$i ++;
		}
		if (!empty(trim($this->input->post ('user_service_provided')))){
			$service_provided_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('user_service_provided')));
			
			if($service_provided_charcaters_length < $this->config->item('profile_management_user_services_provided_minimum_length_character_limit')){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'service_provided';
				$msg['errors'][$i]['error_class'] = 'min_length';
				if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
				{	
					$error_msg = $this->config->item('pa_profile_management_user_services_provided_minimum_length_error_message');
				}else{
					$error_msg = $this->config->item('ca_profile_management_user_services_provided_minimum_length_error_message');
				}
				$msg['errors'][$i]['message'] = $error_msg;
				$i ++;
				$check_service_provided_allowed = 0;
			}
			
		}
		if($check_service_provided_allowed  == 1){
			$user_detail = $this->db // get the user detail
			->select('u.user_id,ud.current_membership_plan_id')
			->from('users u')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('u.user_id', $user_id)
			->get()->row_array(); 
			
			if ($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
    
				if($user_detail['current_membership_plan_id'] == '1'){
					$user_service_provided = $this->config->item('pa_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed');
					$error_msg = $this->config->item('profile_management_free_membership_subscriber_user_services_provided_maximum_slots_reached_error_message');
					
				}else{
					$user_service_provided = $this->config->item('pa_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed');
					$error_msg = $this->config->item('profile_management_gold_membership_subscriber_user_services_provided_maximum_slots_reached_error_message');
				}
				
			} else {
			   
				if($user_detail['current_membership_plan_id'] == '1'){
					$user_service_provided = $this->config->item('ca_user_profile_management_competencies_page_free_membership_subscriber_number_services_provided_slots_allowed');
					$error_msg = $this->config->item('profile_management_free_membership_subscriber_user_services_provided_maximum_slots_reached_error_message');
					
				}else{
					$user_service_provided = $this->config->item('ca_user_profile_management_competencies_page_gold_membership_subscriber_number_services_provided_slots_allowed');
					$error_msg = $this->config->item('profile_management_gold_membership_subscriber_user_services_provided_maximum_slots_reached_error_message');
				}
			}
			$users_service_provided = $this->db
			->select('*')
			->from('users_services_provided_tracking')
			->where("user_id", $user_id)
			->get()->result_array();
			//ECHO count($users_service_provided).$user_service_provided;DIE;
			if (count($users_service_provided) >= $user_service_provided) {
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'service_provided';
				$msg['errors'][$i]['error_class'] = 'maximum_slots';
				$msg['errors'][$i]['message'] = $error_msg;
				$i ++;
			} 
			
		}	
		if($i == 0){
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
		}
		return $msg;
	}
	
	
	
	//This function is used to get the portfolios of login user for  portfolios listing page

	public function get_user_profile_page_portfolio_listing($conditions,$start,$limit){
		$portfolio_data = array();
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS up.*', false);
		$this->db->from('users_portfolios up');
		$this->db->where($conditions);
		$this->db->order_by('up.id DESC');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$portfolio_result = $this->db->get();
		$portfolio_data = $portfolio_result->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $portfolio_data, 'total' => $total_rec];
		
		

	}
	
	
	//This function is used to get the portfolios of login user for  portfolios listing page
	public function get_user_portfolio_listing($conditions,$start,$limit){
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS up.*', false);
		$this->db->from('users_portfolios up');
		$this->db->where($conditions);
		$this->db->order_by('up.id desc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$portfolios_result = $this->db->get();
		$portfolios_data = $portfolios_result->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $portfolios_data, 'total' => $total_rec];
	}
	
	//This function is used to get the tags of portfolio
	public function get_portfolio_tags($conditions){
		$this->db->select('*');
		$this->db->from('users_portfolios_tags');
		$this->db->where($conditions);
		$this->db->order_by('users_portfolios_tags.id asc');
		$portfolio_tags_result = $this->db->get();
		$portfolio_tags = $portfolio_tags_result->result_array();
		return $portfolio_tags;
	}
	
	//This function is used to get the images of portfolio
	public function get_portfolio_images($conditions){
		$this->db->select('*');
		$this->db->from('users_portfolios_images');
		$this->db->where($conditions);
		$this->db->order_by('users_portfolios_images.id asc');
		$portfolio_images_result = $this->db->get();
		$portfolio_images = $portfolio_images_result->result_array();
		return $portfolio_images;
	}
	
	//This function is used to remove the user orphan portfolio images
	public function remove_user_orphan_portfolio_images($data){
		$user_id = $data['user_id'];
		$profile_folder = $data['profile_name'];
		$this->db->select('users_portfolios.portfolio_id');
		$this->db->from('users_portfolios');
		$this->db->where(['user_id'=>$user_id]);
		$portfolio_result = $this->db->get();
		$portfolio = $portfolio_result->result_array();
		if(!empty($portfolio)){
		
		
			######## connectivity of remote server start#########
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			######## connectivity of remote server end #######
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$user_portfolio_dir = USER_PORTFOLIO;
		
		
			foreach($portfolio as $key=>$value){
				$this->db->select('*');
				$this->db->from('users_portfolios_images');
				$this->db->where(['portfolio_id'=>$value['portfolio_id']]);
				$portfolio_images_result = $this->db->get();
				$portfolio_images = $portfolio_images_result->result_array();
				if(!empty($portfolio_images)){
					foreach($portfolio_images as $image_key=>$image_value){
						$image_name = $image_value['portfolio_image_name'];
						$image_name_array = explode('.',$image_name);
					    $thumb_image_name = $image_name_array[0].'_thumb.jpg';
					
						$original_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$value['portfolio_id']. DIRECTORY_SEPARATOR .$image_name;
						$thumb_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$value['portfolio_id']. DIRECTORY_SEPARATOR .$thumb_image_name;
						
						$original_file_size = $this->ftp->get_filesize($original_image_source_path);
						$thumb_file_size = $this->ftp->get_filesize($thumb_image_source_path);
						
						if($original_file_size == '-1' || $thumb_file_size == '-1'){
							//$this->ftp->delete_file($original_image_source_path);
							$this->db->delete('users_portfolios_images', array('id' => $image_value['id']));
							if($original_file_size != '-1'){
								$this->ftp->delete_file($original_image_source_path);
							}
							if($thumb_file_size != '-1'){
								$this->ftp->delete_file($thumb_image_source_path);
							}
							
						}
					
					}
				
				}else{
					/* if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$value['portfolio_id'])))
					{
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$value['portfolio_id']);// delete project directory 
					} */
				
				}
			}
			$this->ftp->close();
		}
	}
	
	//This function is used to remove the user orphan certificates attachments
	public function remove_user_orphan_certificates_attachments($data){
		$user_id = $data['user_id'];
		$profile_folder = $data['profile_name'];

		$this->db->select('id');
		$this->db->from('users_certifications');
		$this->db->where(['user_id'=>$user_id]);
		$certificate_result = $this->db->get();
		$certificates = $certificate_result->result_array();
		if(!empty($certificates)){
		
		
			######## connectivity of remote server start#########
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			######## connectivity of remote server end #######
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$user_certificates_dir = USER_CERTIFICATES;
		
		
			foreach($certificates as $key=>$value){
				$this->db->select('*');
				$this->db->from('users_certifications_attachments');
				$this->db->where(['certificate_id'=> $value['id']]);
				$certificates_files_result = $this->db->get();
				$certificates_files = $certificates_files_result->result_array();

				if(!empty($certificates_files)){
					foreach($certificates_files as $key => $val){
						$name = $val['attachment_name'];
						$name_array = explode('.',$name);
					
						$original_image_source_path = $users_ftp_dir.$profile_folder.$user_certificates_dir.DIRECTORY_SEPARATOR .$value['id']. DIRECTORY_SEPARATOR .$name;
						
						$original_file_size = $this->ftp->get_filesize($original_image_source_path);
						
						
						if($original_file_size == '-1') {
							$this->db->delete('users_certifications_attachments', array('id' => $val['id']));
						}
					
					}
				} 
			}
			$this->ftp->close();
		}
	}
	
	//This function is used to get the work experience of login user for  work experience listing page
	public function get_user_work_experience_listing($conditions,$start,$limit){
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS we.*,countries.country_name,countries.country_code', false);
		$this->db->from('users_personal_accounts_work_experience we');
		$this->db->join('countries', 'countries.id = we.position_country_id', 'left');
		$this->db->where($conditions);
		$this->db->order_by('we.position_still_work desc,we.position_from_year desc,we.position_from_month desc, we.position_name asc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$work_experience_result = $this->db->get();
		$work_experience_data = $work_experience_result->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $work_experience_data, 'total' => $total_rec];
	}
	
	//This function is used to get the education training of login user for education training listing page
	public function get_user_education_training_listing($conditions,$start,$limit){
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS et.*,countries.country_name,countries.country_code', false);
		$this->db->from('users_personal_accounts_education_training et');
		$this->db->join('countries', 'countries.id = et.education_country_id', 'left');
		$this->db->where($conditions);
		$this->db->order_by('et.education_progress desc,et.education_graduate_year desc, et.education_diploma_degree_name asc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$education_training_result = $this->db->get();
		$education_training_data = $education_training_result->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $education_training_data, 'total' => $total_rec];
	}
	
	//This function is used to get the certifications of login user for  certifications listing page
	public function get_user_certifications_listing($conditions,$start,$limit){
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS c.*', false);
		$this->db->from('users_certifications c');
		$this->db->where($conditions);
		$this->db->order_by('c.certification_year desc,c.certification_month desc,c.certification_name asc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$certifications_result = $this->db->get();
		$certifications_data = $certifications_result->result_array();

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		foreach($certifications_data as &$value) {
			$value['attachments'] = $this->db->get_where('users_certifications_attachments', ['certificate_id' => $value['id']])->result_array();
		}
		 return ['data' => $certifications_data, 'total' => $total_rec];
	}
	
	//This function is used for validation portfolio popup
	public function user_portfolio_form_validation(){
		$i = 0;
		if (empty($this->input->post ('portfolio_title'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'portfolio_title';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_portfolio_title_required_field_error_message');
			$i ++;
		}
		if(!empty($this->input->post ('portfolio_title')) && mb_strlen($this->input->post ('portfolio_title')) < $this->config->item('user_portfolio_section_portfolio_title_characters_minimum_length_characters_limit')){
	
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'portfolio_title';
			$msg['errors'][$i]['error_class'] = 'min_length';
			$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_portfolio_title_characters_minimum_length_validation_message');
			$i ++;
		}
		if (empty($this->input->post ('description'))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'description';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_description_required_field_error_message');
			$i ++;
		}
		
		if(!empty($this->input->post ('description'))){
			
			$description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $this->input->post ('description')));
			
			if($this->config->item('user_portfolio_section_description_minimum_length_words_limit') == 0){
				
				if($description_charcaters_length < $this->config->item('user_portfolio_section_description_characters_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_portfolio_description_characters_minimum_length_validation_message');
					
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('description'));
		 
				$description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($description_charcaters_length < $this->config->item('user_portfolio_section_description_characters_minimum_length_characters_limit') ||$description_word_count < $this->config->item('user_portfolio_section_description_minimum_length_words_limit') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_portfolio_description_characters_words_minimum_length_validation_message');
					$i ++;
				}
			}
		}
		if($this->input->post ('display_reference_url') == '0'){
			/* if (empty($this->input->post ('reference_url'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'reference_url';
				$msg['errors'][$i]['error_class'] = 'required';
				$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_reference_url_required_field_error_message');
				$i ++;
			} */
			if (!empty(trim($this->input->post ('reference_url')))){
				$url = trim($this->input->post ('reference_url'));
				$pattern = "/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,10}(:[0-9]{1,5})?(\/.*)?$/i";
				
				if (!preg_match($pattern,$url)) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'reference_url';
					$msg['errors'][$i]['error_class'] = 'invalid_reference_url';
					$msg['errors'][$i]['message'] = $this->config->item('user_portfolio_section_invalid_reference_url_field_error_message');
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
	
	
	
	//This function is used to get the education training of user for user profile page
	public function get_user_profile_page_education_training_listing($conditions){
		$education_training_data = array();
		$this->db->select('et.*,countries.country_name,countries.country_code');
		$this->db->from('users_personal_accounts_education_training et');
		$this->db->join('countries', 'countries.id = et.education_country_id', 'left');
		$this->db->where($conditions);
		$this->db->order_by('et.education_progress desc,et.education_graduate_year desc, et.education_diploma_degree_name asc');
		$education_training_result = $this->db->get();
		$education_training_data = $education_training_result->result_array();
		return $education_training_data;

	}
	
	//This function is used to get the workexperience of user for user profile page
	public function get_user_profile_page_work_experience_listing($conditions){
		$this->db->select('we.*,countries.country_name,countries.country_code');
		$this->db->from('users_personal_accounts_work_experience we');
		$this->db->join('countries', 'countries.id = we.position_country_id', 'left');
		$this->db->where($conditions);
		$this->db->order_by('we.position_still_work desc,we.position_from_year desc,we.position_from_month desc, we.position_name asc');
		$work_experience_result = $this->db->get();
		$work_experience_data = $work_experience_result->result_array();
		 return $work_experience_data;
	}
	
	//This function is used to get the certifications of user for user profile page
	public function get_user_profile_page_certifications_listing($conditions){
		$user_certifications_data = array();
		$this->db->select('c.*');
		$this->db->from('users_certifications c');
		$this->db->where($conditions);
		$this->db->order_by('c.certification_year desc,c.certification_month desc,c.certification_name asc');
		$certifications_result = $this->db->get();
		$user_certifications_data = $certifications_result->result_array();
		foreach($user_certifications_data as &$value) {
			$value['attachments'] = $this->db->get_where('users_certifications_attachments', ['certificate_id' => $value['id']])->result_array();
		}
		return $user_certifications_data;
	}
	
	//This function is used to get the user skills of user for user profile page
	public function get_user_profile_page_skills_listing($conditions){
		$user_skills_data = array();
		$this->db->select('us.*');
		$this->db->from('users_skills_tracking us');
		$this->db->where($conditions);
		$this->db->order_by('us.user_skill asc');
		$skills_result = $this->db->get();
		$user_skills_data = $skills_result->result_array();
		return $user_skills_data;
		
		
	}
	
	//This function is used to get the user services provided of user for user profile page
	public function get_user_profile_page_services_provided_listing($conditions){
		$user_services_data = array();
		$this->db->select('usp.*');
		$this->db->from('users_services_provided_tracking usp');
		$this->db->where($conditions);
		$this->db->order_by('usp.service_provided asc');
		$services_result = $this->db->get();
		$user_services_data = $services_result->result_array();
		return $user_services_data;
	}
	
	//This function is used to get the user spoken languages of user for user profile page
	public function get_user_profile_page_spoken_languages_listing($conditions){
		$user_base_information = $this->db // get the user detail
		->select('users_profile_base_information.mother_tongue_language_id')
		->from('users_profile_base_information')
		->where('user_id',$conditions['user_id'])
		->get()->row_array();
		
		$user_spoken_languages_data = array();
		$this->db->select('usp.understanding,usp.speaking,usp.writing,languages.language');
		$this->db->from('users_personal_accounts_spoken_languages_tracking usp');
		$this->db->join('languages', 'languages.id = usp.language_id', 'left');
		$this->db->where($conditions);
		if(!empty($user_base_information) && !empty($user_base_information['mother_tongue_language_id']) &&  !empty($user_base_information['mother_tongue_language_id'])){
			$this->db->where('usp.language_id !=', $user_base_information['mother_tongue_language_id']);
		}
		$this->db->order_by('languages.language asc');
		$spoken_languages_result = $this->db->get();
		$user_spoken_languages_data = $spoken_languages_result->result_array();
		return $user_spoken_languages_data;
	}
	
	//This function is used to get the spoken languages of personal account user for profile management
	public function get_user_spoken_languages_listing($user_id){
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS sp.*,languages.language', false);
		$this->db->from('users_personal_accounts_spoken_languages_tracking sp');
		$this->db->join('languages', 'languages.id = sp.language_id', 'left');
		$this->db->where('sp.user_id',$user_id);
		$this->db->order_by('languages.language asc');
		$spoken_langauge_result = $this->db->get();
		$spoken_langauge_data = $spoken_langauge_result->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $spoken_langauge_data, 'total' => $total_rec];
	}
	
	// This function is used to remove user education entries from database(if any column value are scrambled) 
	public function remove_scrambled_user_education_training_entries($user_id){
	
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type')
		->from('users u')
		->where('u.user_id', $user_id)
		->get()->row_array();
		$this->db->select('et.*');
		$this->db->from('users_personal_accounts_education_training et');
		$this->db->where(array('et.user_id'=>$user_id));
		$edu_result = $this->db->get();
		$edu_data = $edu_result->result_array();
		if(!empty($edu_data)){
			foreach($edu_data as $key=>$value){
				if(empty($value['education_diploma_degree_name']) || empty($value['education_school_name']) || empty($value['education_school_address']) || empty($value['education_country_id']) || ($value['education_progress'] == '0' && empty($value['education_graduate_year']))){
				$this->db->delete('users_personal_accounts_education_training', ['id' => $value['id']]);
				}
			}

		}
		
		// profile completion start 
		$user_profile_completion_data = array();
		$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
		$total_record = $this->db->where(['user_id'=>$user_detail['user_id']])->from('users_personal_accounts_education_training')->count_all_results();
		if($total_record == 0){
	
			$user_profile_completion_data['has_education_training_indicated'] = 'N';
			$user_profile_completion_data['education_training_strength_value'] = 0;
			$user_profile_completion_data['number_of_education_training_entries'] = $total_record;
		}else{
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
			$user_profile_completion_data['has_education_training_indicated'] = 'Y';
			$user_profile_completion_data['education_training_strength_value'] = $profile_completion_parameters['education_training_strength_value'];
		
			$user_profile_completion_data['number_of_education_training_entries'] = $total_record;
		}
		if(!empty($user_profile_completion_data)){
			 $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
		}
		// profile completion end
		
	}
	
	// This function is used to remove user work experience entries from database(if any column value are scrambled) 
	public function remove_scrambled_user_work_experience_entries($user_id){
		
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type')
		->from('users u')
		->where('u.user_id', $user_id)
		->get()->row_array();
		$this->db->select('we.*');
		$this->db->from('users_personal_accounts_work_experience we');
		$this->db->where(array('we.user_id'=>$user_id));
		$work_exp_result = $this->db->get();
		$work_exp_data = $work_exp_result->result_array();
		if(!empty($work_exp_data)){
			foreach($work_exp_data as $key=>$value){
			if(empty($value['position_name']) || empty($value['position_company_name']) || empty($value['position_company_address']) || empty($value['position_from_month']) || empty($value['position_from_year']) || empty($value['position_country_id']) || ($value['position_still_work'] == 0 && (empty($value['position_to_month']) || empty($value['position_to_year']))) ||  (!empty($value['position_to_month']) && $value['position_to_month'] > 12) ||  (!empty($value['position_from_month']) && $value['position_from_month'] > 12)){
				$this->db->delete('users_personal_accounts_work_experience', ['id' => $value['id']]);
				}
			}
		
		}
		
		//profile completion script start 
		$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
		$user_profile_completion_data = array();
		$total_record = $this->db->where(['user_id'=>$user_detail['user_id']])->from('users_personal_accounts_work_experience')->count_all_results();
		if($total_record ==0){
			$user_profile_completion_data['has_work_experience_indicated'] = 'N';
			$user_profile_completion_data['work_experience_strength_value'] = 0;
			$user_profile_completion_data['number_of_work_experiences_entries'] = 0;
		}else{
		
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
			$user_profile_completion_data['has_work_experience_indicated'] = 'Y';
			$user_profile_completion_data['work_experience_strength_value'] = $profile_completion_parameters['work_experience_strength_value'];
			$user_profile_completion_data['number_of_work_experiences_entries'] = $total_record;
		}
		if(!empty($user_profile_completion_data)){	
		 $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
		}
		//profile completion script end 
			
	}
	
	// This function is used to remove user certifications entries from database(if any column value are scrambled) 
	public function remove_scrambled_user_certifications_entries($user_id){
	
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type,u.is_authorized_physical_person')
		->from('users u')
		->where('u.user_id', $user_id)
		->get()->row_array();
	   $this->db->select('c.*');
		$this->db->from('users_certifications c');
		$this->db->where(array('c.user_id'=>$user_id));
		$certifications_result = $this->db->get();
		$certifications_data = $certifications_result->result_array();
		if(!empty($certifications_data)){
			foreach($certifications_data as $key=>$value){
				if(empty($value['certification_name']) || empty($value['certification_month']) || empty($value['certification_year']) || (!empty($value['certification_month']) && $value['certification_month'] > 12)){
				$this->db->delete('users_certifications', ['id' => $value['id']]);
				}
			}
		
		}
		
		// profile completion script start 
		$user_profile_completion_data = array();
		if($user_detail['account_type']  == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person']  == 'Y')){
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
			}elseif($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE){
			$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
		}
		$total_record = $this->db->where(['user_id'=>$user_detail['user_id']])->from('users_certifications')->count_all_results();
		if($total_record == 0){
			$user_profile_completion_data['has_certifications_indicated'] = 'N';
			$user_profile_completion_data['certifications_strength_value'] = 0;
			$user_profile_completion_data['number_of_certifications_entries'] = 0;
		}else{
		
		
			$user_profile_completion_data['has_certifications_indicated'] = 'Y';
			$user_profile_completion_data['certifications_strength_value'] = $profile_completion_parameters['certifications_strength_value'];
			$user_profile_completion_data['number_of_certifications_entries'] = $total_record;
		}
		if(!empty($user_profile_completion_data)){
			$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
		}
		// profile completion script end 
			
	}
	
	// This function is used to remove user skills entries from database(if any column value are scrambled) 
	public function remove_scrambled_user_skills_entries($user_id){
	
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type,u.is_authorized_physical_person')
		->from('users u')
		->where('u.user_id', $user_id)
		->get()->row_array();
	
	
	   $this->db->select('us.*');
		$this->db->from('users_skills_tracking us');
		$this->db->where(array('us.user_id'=>$user_id));
		$skills_result = $this->db->get();
		$skills_data = $skills_result->result_array();
		if(!empty($skills_data)){
			foreach($skills_data as $key=>$value){
				if(empty(trim($value['user_skill']))){
				$this->db->delete('users_skills_tracking', ['id' => $value['id']]);
				}
			}
		
		}
		// profile completion script start 
		if($user_detail['account_type']  == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person']  == 'Y')){
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
			}elseif($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE){
			$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
		}
		$user_profile_completion_data = array();
		$count_user_skills = $this->db->where(['user_id'=>$user_detail['user_id']])->from('users_skills_tracking')->count_all_results();
		if($count_user_skills ==0){
			$user_profile_completion_data['has_skills_indicated'] = 'N';
			$user_profile_completion_data['skills_strength_value'] = 0;
			$user_profile_completion_data['number_of_skills_entries'] = 0;
		}else{
			$user_profile_completion_data['has_skills_indicated'] = 'Y';
			$user_profile_completion_data['skills_strength_value'] = $profile_completion_parameters['skills_strength_value'];
			$user_profile_completion_data['number_of_skills_entries'] = $count_user_skills;
		}
		if(!empty($user_profile_completion_data)){
			
			$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
		}
		 // profile completion end 
			
	}
	
	// This function is used to remove user services provided entries from database(if any column value are scrambled) 
	public function remove_scrambled_user_services_provided_entries($user_id){
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type,u.is_authorized_physical_person')
		->from('users u')
		->where('u.user_id', $user_id)
		->get()->row_array();
	
	   $this->db->select('usp.*');
		$this->db->from('users_services_provided_tracking usp');
		$this->db->where(array('usp.user_id'=>$user_id));
		$services_provided_result = $this->db->get();
		$services_provided_data = $services_provided_result->result_array();
		if(!empty($services_provided_data)){
			foreach($services_provided_data as $key=>$value){
				if(empty($value['service_provided'])){
				$this->db->delete('users_services_provided_tracking', ['id' => $value['id']]);
				}
			}
		
		}
		
		// profile completion script start 
		 $user_profile_completion_data = array();
		 if($user_detail['account_type']  == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person']  == 'Y')){
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
			}elseif($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE){
			$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
		}
		 $count_user_services_provided = $this->db->where(['user_id'=>$user_id])->from('users_services_provided_tracking')->count_all_results();
		 if($count_user_services_provided == 0){
			$user_profile_completion_data['has_services_provided_indicated'] = 'N';
			$user_profile_completion_data['number_of_services_provided_entries'] = 0;
			$user_profile_completion_data['services_provided_strength_value'] = 0;
		 }else{
			$user_profile_completion_data['has_services_provided_indicated'] = 'Y';
			$user_profile_completion_data['services_provided_strength_value'] = $profile_completion_parameters['services_provided_strength_value'];
			$user_profile_completion_data['number_of_services_provided_entries'] = $count_user_services_provided;
		 }
		 if(!empty($user_profile_completion_data)){
		  $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
		 }
		 // profile completion end
			
	}
	
	// This function is used to remove spoken language entries from database(if any column value are scrambled) 
	public function remove_scrambled_user_spoken_languages_entries($user_id){
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type')
		->from('users u')
		->where('u.user_id', $user_id)
		->get()->row_array();
	   $this->db->select('usp.*');
		$this->db->from('users_personal_accounts_spoken_languages_tracking usp');
		$this->db->where(array('usp.user_id'=>$user_id));
		$spoken_languages_result = $this->db->get();
		$spoken_languages_data = $spoken_languages_result->result_array();
		
		$lan_understanding_options = $this->config->item('pa_profile_management_spoken_languages_understanding_drop_down_options');
		$lan_speaking_options = $this->config->item('pa_profile_management_spoken_languages_speaking_drop_down_options');
		$lan_writing_options = $this->config->item('pa_profile_management_spoken_languages_writing_drop_down_options');
		
		
		if(!empty($spoken_languages_data)){
			foreach($spoken_languages_data as $key=>$value){
			
				if(empty($value['language_id']) || empty($value['understanding']) || empty($value['speaking']) || empty($value['writing']) || !in_array($value['understanding'], $lan_understanding_options) || !in_array($value['speaking'], $lan_speaking_options) || !in_array($value['writing'], $lan_writing_options) ){
				$this->db->delete('users_personal_accounts_spoken_languages_tracking', ['id' => $value['id']]);
				}
			}
		
		}
		
		// profile completion script start//
		$user_profile_completion_data = array();
		$spoken_languages_count = $this->db->where(['user_id'=>$user_id])->from('users_personal_accounts_spoken_languages_tracking')->count_all_results();
		if($spoken_languages_count == 0){
		
			$user_profile_completion_data['has_spoken_foreign_languages_indicated'] = 'N';
			$user_profile_completion_data['number_of_spoken_foreign_languages_entries'] = 0;
			$user_profile_completion_data['spoken_foreign_languages_strength_value'] = 0;
			
		}else{
			$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
			
			$user_profile_completion_data['spoken_foreign_languages_strength_value'] = $profile_completion_parameters['spoken_foreign_languages_strength_value'];
			$user_profile_completion_data['has_spoken_foreign_languages_indicated'] = 'Y';
			$user_profile_completion_data['number_of_spoken_foreign_languages_entries'] = $spoken_languages_count;
		}
		if(!empty($user_profile_completion_data)){
			$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
		}
		// profile completion script end//
			
	}
	
	
	public function remove_scrambled_user_address_entries($user_id){
		
		
		
		$user_detail = $this->db // get the user detail
		->select('u.user_id, u.account_type,u.is_authorized_physical_person, u.first_name, u.last_name, u.company_name, uad.street_address, uad.locality_id, uad.county_id,uad.country_id, uad.postal_code_id')
		->from('users u')
		->join('users_address_details uad', 'uad.user_id = u.user_id', 'left')
		->where('u.user_id', $user_id)
		->get()->row_array();
			
		if(!empty($user_detail)){
			$check_valid_country = $this->db->where(['id' => $user_detail['country_id']])->from('countries')->count_all_results();
			
			if($check_valid_country == 0){
				$this->db->delete('users_address_details', ['user_id' => $user_id]);
			}
			if($user_detail['country_id'] != $this->config->item('reference_country_id')){
				$this->db->update('users_address_details', ['locality_id'=>0,'county_id'=> 0,'postal_code_id'=>0], ['user_id' => $user_id]);
			}	
			
			$check_valid_county = $this->db->where(['id' => $user_detail['county_id']])->from('counties')->count_all_results();
			
			if($check_valid_county == 0){
				$this->db->update('users_address_details', ['locality_id'=>0,'postal_code_id'=>0,'county_id'=>0], ['user_id' => $user_id]);
			}

			$check_valid_locality = $this->db->where(['id' => $user_detail['locality_id']])->from('localities')->count_all_results();
			
			if($check_valid_locality == 0){
				$this->db->update('users_address_details', ['locality_id'=>0,'postal_code_id'=>0], ['user_id' => $user_id]);
			}		
			
			
			if($user_detail['county_id'] != 0 && $user_detail['locality_id'] != 0){
				
				$check_valid_locality = $this->db->where(['id' => $user_detail['locality_id'],'county_id'=>$user_detail['county_id']])->from('localities')->count_all_results();
				if($check_valid_locality == 0){
					$this->db->update('users_address_details', ['locality_id'=>0,'postal_code_id'=>0], ['user_id' => $user_id]);
				}	
			}
			if($user_detail['county_id'] != 0 && $user_detail['locality_id'] != 0 && $user_detail['postal_code_id'] != 0){

				$check_valid_postal_code = $this->db->where(['id' => $user_detail['postal_code_id'],'locality_id'=>$user_detail['locality_id']])->from('postal_codes')->count_all_results();
				if($check_valid_postal_code == 0){
					$this->db->update('users_address_details', ['postal_code_id'=>0], ['user_id' => $user_id]);
				}
				
			}
			if($user_detail['county_id'] == 0 && $user_detail['locality_id'] == 0 && $user_detail['postal_code_id'] == 0 && $user_detail['country_id'] == 0){
				
				$this->db->delete('users_address_details', ['user_id' => $user_id]);
				$this->db->delete('users_company_accounts_opening_hours', ['user_id' => $user_id]);
			}

			
			
			$users_address_details_data = $this->db // get the user detail
			->select('*')
			->from('users_address_details')
			->where('user_id', $user_detail['user_id'])
			->get()->row_array();
			
			if(!empty($users_address_details_data) && !empty($users_address_details_data['country_id']) && $users_address_details_data['country_id'] != 0){
					//die("1");
				if($user_detail['account_type']  == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person']  == 'Y')){
					$profile_completion_parameters = $this->config->item('user_personal_account_type_profile_completion_parameters_tracking_options_value');
					
				}elseif($user_detail['account_type']  == USER_COMPANY_ACCOUNT_TYPE){
					$profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
				}
				
				$user_profile_completion_data['has_country_address_indicated'] = 'Y';	
				$user_profile_completion_data['country_address_strength_value'] = $profile_completion_parameters['country_address_strength_value'];
				if($users_address_details_data['country_id'] == $this->config->item('reference_country_id')){
					$user_profile_completion_data['country_is_cz'] = 'Y';
					$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] =0;
					if(!empty($users_address_details_data['street_address'])){
						$user_profile_completion_data['has_street_address_indicated'] = 'Y';
						$user_profile_completion_data['street_address_strength_value'] = $profile_completion_parameters['street_address_strength_value'];
					}else{
						$user_profile_completion_data['has_street_address_indicated'] = 'N';
						$user_profile_completion_data['street_address_strength_value'] = 0;
						$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = 0;
					}
					if(!empty($users_address_details_data['county_id']) && $users_address_details_data['county_id'] !=0){
						
						$user_profile_completion_data['has_county_address_indicated'] = 'Y';
						$user_profile_completion_data['county_address_strength_value'] = $profile_completion_parameters['county_address_strength_value'];
						
					}else{
					
						$user_profile_completion_data['has_county_address_indicated'] = 'N';
						$user_profile_completion_data['county_address_strength_value'] = 0;
					}
					if(!empty($users_address_details_data['locality_id']) && $users_address_details_data['locality_id'] !=0){
						
						$user_profile_completion_data['has_locality_address_indicated'] = 'Y';
						$user_profile_completion_data['locality_address_strength_value'] = $profile_completion_parameters['locality_address_strength_value'];
					}else{
						$user_profile_completion_data['has_locality_address_indicated'] = 'N';
						$user_profile_completion_data['locality_address_strength_value'] = 0;
					}
					
				}else{
					$user_profile_completion_data['country_is_cz'] = 'N';
					if(!empty($users_address_details_data['street_address'])){
						$user_profile_completion_data['has_street_address_indicated'] = 'Y';
						$user_profile_completion_data['street_address_strength_value'] = 0;
						$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = $profile_completion_parameters['street_address_when_country_not_cz_strength_value'];
					}
					$user_profile_completion_data['has_county_address_indicated'] = 'N';
					$user_profile_completion_data['county_address_strength_value'] = 0;
					$user_profile_completion_data['has_locality_address_indicated'] = 'N';
					$user_profile_completion_data['locality_address_strength_value'] = 0;
					
				}
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
			}else{
				
				// update user account profile completion address parameters start //
				$user_profile_completion_data['has_street_address_indicated'] = 'N';
				$user_profile_completion_data['street_address_strength_value'] = 0;
				$user_profile_completion_data['street_address_when_country_not_cz_strength_value'] = 0;
				
				$user_profile_completion_data['has_county_address_indicated'] = 'N';
				$user_profile_completion_data['county_address_strength_value'] = 0;
				
				$user_profile_completion_data['has_locality_address_indicated'] = 'N';
				$user_profile_completion_data['locality_address_strength_value'] = 0;
				
				$user_profile_completion_data['has_country_address_indicated'] = 'N';
				$user_profile_completion_data['country_address_strength_value'] = 0;
				
				$user_profile_completion_data['country_is_cz'] = 'N';
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
				// update user account profile completion address parameters end //
				
			}

			// Check for invalid branch address
			if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
				$check_valid_locality = $this->db->where(['user_id' => $user_id])->from('users_address_details')->count_all_results();
				if($check_valid_locality == 0) {
					$this->db->delete('users_company_accounts_additional_branches_addresses', ['user_id' => $user_id]);
					return;
				}

				$this->db->select('ba.*,con.country_code, con.country_name as country, coun.name as county, loc.name as locality, pc.postal_code as postal_code');
				$this->db->from('users_company_accounts_additional_branches_addresses ba');
				$this->db->join('countries con', 'con.id=ba.country_id', 'left');
				$this->db->join('counties coun', 'coun.id=ba.county_id', 'left');
				$this->db->join('localities loc', 'loc.id=ba.locality_id AND loc.county_id=coun.id', 'left');
				$this->db->join('postal_codes pc', 'pc.id=ba.postal_code_id AND pc.locality_id=loc.id', 'left');
				$this->db->where('ba.user_id',$user_id);
				$result = $this->db->get()->result_array();
				$update_data_arr = [];
				$delete_id_arr = [];
				foreach($result as $val) {
					$tmp = [];
					if(empty($val['county']) && $val['county_id'] != 0) {
						$tmp['county_id'] = 0 ; 
					}
					if(empty($val['locality']) && $val['locality_id'] != 0) {
						$tmp['locality_id'] = 0 ; 
					}
					if(empty($val['postal_code']) && $val['postal_code_id'] != 0) {
						$tmp['postal_code_id'] = 0 ; 
					}
					if(empty($val['country']) && $val['country_id'] != 0) {
						$tmp['country_id'] = 0 ; 
					}

					if(!empty($val['country']) && $val['country_id'] != $this->config->item('reference_country_id')) {
						$tmp['county_id'] = $tmp['locality_id'] = $tmp['postal_code_id'] = 0 ; 
					}

					if($val['county_id'] == 0 && $val['locality_id'] == 0 && $val['postal_code_id'] == 0 && $val['country_id'] == 0){
						array_push($delete_id_arr, $val['id']);
						$tmp = [];
					} else if((isset($tmp['country_id']) && $tmp['country_id'] == 0)) {
						array_push($delete_id_arr, $val['id']);
						$tmp = [];
					}


					if(!empty($tmp)) {
						$tmp['id'] = $val['id'];
						array_push($update_data_arr, $tmp);
					}
				}
				if(!empty($delete_id_arr)) {
					$this->db->where_in('id', $delete_id_arr);
					$this->db->delete('users_company_accounts_additional_branches_addresses');

					$this->db->where('user_id',$user_id);
					$this->db->where_in('company_location_id', $delete_id_arr);
					$this->db->where('is_company_headquarter', 'N');
					$this->db->delete('users_company_accounts_opening_hours');
				}
				if(!empty($update_data_arr)) {
					$this->db->update_batch('users_company_accounts_additional_branches_addresses', $update_data_arr, 'id');
				}
			}
			
		}	
		
		
			
	}
	/**
	* This method is used to get all business account additionals branches address by user id
	*/
	public function get_all_business_account_additional_branches_addresses_by_user_id($user_id) {
		$this->db->select('ba.*,con.country_code, con.country_name as country, coun.name as county, loc.name as locality, pc.postal_code as postal_code');
		$this->db->from('users_company_accounts_additional_branches_addresses ba');
		$this->db->join('countries con', 'con.id=ba.country_id', 'left');
		$this->db->join('counties coun', 'coun.id=ba.county_id', 'left');
		$this->db->join('localities loc', 'loc.id=ba.locality_id', 'left');
		$this->db->join('postal_codes pc', 'pc.id=ba.postal_code_id', 'left');
		$this->db->where('ba.user_id', $user_id);
		return $this->db->get()->result_array();
	}
	/**
	 * This method is used to save user visits on profile page and return unique count
	 */
	function save_user_profile_pages_visits_and_get_count($user_id, $access_medium = '') {
		$result = $this->db->get_where('users_profile_page_visits_tracking', ['user_id' => $user_id, 'visitor_source_ip' => get_client_ip()])->row_array();
		if(empty($result)) {
			$data = [
				'user_id' => $user_id, 
				'visitor_source_ip' => get_client_ip(),
				'number_of_visits' => 1
			];
			$this->db->insert('users_profile_page_visits_tracking', $data);
		} else {
			if(empty($access_medium)) {
				$this->db->set('number_of_visits', 'number_of_visits + 1', false);
				$this->db->where('id', $result['id']);
				$this->db->update('users_profile_page_visits_tracking');
			}
		}
		return $this->db->from('users_profile_page_visits_tracking')->where('user_id', $user_id)->count_all_results();
	}
	
	// This function is using to creates the subfolders inside the users folder as per needed
	public function check_and_create_user_subfolders_on_disk_as_per_need($destination_path){
	
		
		//$source_path = FCPATH. '404.php';
		$result = $this->ftp->mkdir($destination_path, 0755);
		return $result;
		/* if(!empty($this->ftp->check_ftp_directory_exist($destination_path)))
		{
			$destination_path = $destination_path."index.php";
			$file_size = $this->ftp->get_filesize($destination_path);
			if($file_size == '-1'){
				$this->ftp->upload($source_path,$destination_path , 'auto', 0444);
			}
		} */
		
		
		
	}
	/**
	 * This method is used to save user management page information in find professionals user information table 
	*/
	public function save_find_professionals_user_information($user_id, $action, $str, $old_str = '') {
		$find_user_info = $this->db->get_where('find_professionals_users_information', ['user_id' => $user_id])->row_array();
		$user_base_info = $this->db->get_where('users_profile_base_information', ['user_id' => $user_id])->row_array();
		if(!empty($user_base_info) && !empty($user_base_info['headline']) && !empty($user_base_info['description'])) { 
			if(empty($find_user_info)) {
				$user = $this->db->get_where('users', ['user_id' => $user_id])->row_array();
				if(!empty($user)) {
					$data = [];
					if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
						$data = [
							'user_first_name' => $user['first_name'],
							'user_last_name' => $user['last_name']
						];
					} else {
						if($user['is_authorized_physical_person'] == 'Y') {
							$data = [
								'user_first_name' => $user['first_name'],
								'user_last_name' => $user['last_name']
							];
						} else {
							$data= [
								'company_name' => $user['company_name']
							];
						}
						
					}
					$data['description'] = $user_base_info['headline'].' '.$user_base_info['description'];
					$data['user_id'] = $user_id;
					$this->db->insert('find_professionals_users_information', $data);
				}
			} else {
				if($action == 'insert') {
					$this->db->set('description', "CONCAT(description,".' '.$this->db->escape($str).")", FALSE);
					$this->db->where('user_id', $user_id);
					$this->db->update('find_professionals_users_information');
				} else if($action == 'update') {
					$this->db->set('description', "REPLACE(description,".$this->db->escape($old_str).",".$this->db->escape($str).")", FALSE);
					$this->db->where('user_id', $user_id);
					$this->db->update('find_professionals_users_information');
				} else {
					$this->db->set('description', "REPLACE(description,".$this->db->escape($old_str).",'')", FALSE);
					$this->db->where('user_id', $user_id);
					$this->db->update('find_professionals_users_information');
				}
			}
		}
		
	}
	
	// This function is using for validation for close account by user
	public function contact_us_form_validation($post_data){
		$i = 0;
		
		if (empty(trim($post_data['first_name'])) && empty(trim($post_data['company_name']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'first_name';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_firstname_or_company_required_field_error_message');
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		
		if (!empty(trim($post_data['company_name'])) && empty(trim($post_data['number_of_company_employees']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'number_of_company_employees';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_company_number_of_employees_required_field_error_message');
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		if (empty(trim($post_data['contact_reason'])) && empty(trim($post_data['contact_reason']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'contact_reason';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_contact_reason_required_field_error_message');
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		if (empty(trim($this->input->post ('contact_email')))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'contact_email';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_email_address_required_field_error_message');
			$i ++;
		}
		if (!empty(trim($this->input->post ('contact_email'))) && !filter_var (trim($this->input->post ('contact_email')), FILTER_VALIDATE_EMAIL)){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'contact_email';
			$msg['errors'][$i]['error_class'] = 'invalid_email';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_email_address_invalid_format_field_error_message');
			$i ++;
		}
		if (empty(trim($this->input->post ('phone_number')))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'phone_number';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_phone_number_required_field_error_message');
			$i ++;
		}
		$description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', $post_data['description']));
		if(empty(trim($post_data['description']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'description';
			$msg['errors'][$i]['error_class'] = 'required';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_description_required_field_error_message');
			$i ++;
		}
		if(!empty(trim($post_data['description']))){
			if($this->config->item('contact_us_page_description_minimum_length_words_limit_reason_description') == 0){
				
				if($description_charcaters_length < $this->config->item('contact_us_page_description_minimum_length_character_limit_reason_description')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_description_characters_min_length_validation_message');
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				
				
				$string_only_single_space = preg_replace("/\s+/", " ", $post_data['description']);
				
				$description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($description_charcaters_length < $this->config->item('contact_us_page_description_minimum_length_character_limit_reason_description') ||$description_word_count < $this->config->item('contact_us_page_description_minimum_length_words_limit_reason_description') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_description_characters_words_min_length_validation_message');
					$i ++;
				}
			}
		}
		if(!empty($post_data['description']) && mb_strlen($description_charcaters_length) > $this->config->item('contact_us_page_description_maximum_length_character_limit_reason_description')){
		
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'description';
			$msg['errors'][$i]['error_class'] = 'max_length';
			$msg['errors'][$i]['message'] = $this->config->item('contact_us_page_description_characters_max_length_validation_message');
			
			$i ++;
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
			$msg['message'] = '';
		}
		return $msg;
	
	}
	
}
?>