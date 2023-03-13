<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}
class Bidding_model extends BaseModel
{
    public function __construct ()
    {
        parent::__construct ();
		$this->load->model('dashboard/Dashboard_model');
    }
	
	public function post_update_bid_validation($post_data,$project_detail){
		
		$i = 0;
		$bid_description_charcaters_length = strlen(preg_replace('/\s+/', '', $post_data['bid_description']));
		if (!isset($post_data['biding_form_checkbox']) && empty($post_data['bid_amount']) ){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'bid_amount';
			if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_amount_validation_fulltime_project_bid_form_message');
			}else if($project_detail['project_type'] == 'fixed'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_amount_validation_fixed_budget_project_bid_form_message');
			}else if($project_detail['project_type'] == 'hourly'){
			$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_bid_amount_validation_hourly_project_bid_form_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		} else if(!isset($post_data['biding_form_checkbox']) && !empty($post_data['bid_amount'])){
			
		
			if($project_detail['confidential_dropdown_option_selected'] == 'N' && $project_detail['not_sure_dropdown_option_selected'] == 'N'){
				$min_rate = $this->config->item('project_details_page_min_hourly_rate_value');
				$min_salary_amt = $this->config->item('fulltime_project_min_salary_amount');
				if($project_detail['project_type'] == 'hourly' && $project_detail['min_budget'] < $min_rate && str_replace(" ","",$post_data['bid_amount']) < $min_rate) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_amount';
					$error_message = $this->config->item('project_details_page_minimum_required_bid_amount_validation_hourly_project_bid_form_message');
					$error_message = str_replace("{project_minimum_hourly_bid_amount}", str_replace(".00","",number_format($min_rate,  2, '.', ' ')), $error_message);
					$msg['errors'][$i]['message'] = $error_message;
					$msg['errors'][$i]['error_class'] = 'minimum_bid_amount';
					$i ++;
				} /* else if($project_detail['project_type'] == 'fulltime' && $project_detail['min_budget'] < $min_salary_amt && str_replace(" ","",$post_data['bid_amount']) < $min_salary_amt) {
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_amount';
					$error_message = $this->config->item('project_details_page_minimum_required_salary_amount_validation_fulltime_project_application_form_message');
					$error_message = str_replace("{project_minimum_salary_amount}", str_replace(".00","",number_format($min_salary_amt,  2, '.', ' ')), $error_message);
					$msg['errors'][$i]['message'] = $error_message;
					$msg['errors'][$i]['error_class'] = 'minimum_bid_amount';
					$i ++;
				} */ else if(str_replace(" ","",$post_data['bid_amount']) < $project_detail['min_budget']){
					
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_amount';
					if($project_detail['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_minimum_required_salary_amount_validation_fulltime_project_application_form_message');
						$error_message = str_replace("{project_minimum_salary_amount}",str_replace(".00","",number_format($project_detail['min_budget'],  2, '.', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month')),$error_message);
					} else if($project_detail['project_type'] == 'fixed'){
						$error_message = $this->config->item('project_details_page_minimum_required_bid_amount_validation_fixed_budget_project_bid_form_message');
						$error_message = str_replace("{project_minimum_fixed_bid_amount}",str_replace(".00","",number_format($project_detail['min_budget'],  2, '.', ' ')),$error_message);
					} else if($project_detail['project_type'] == 'hourly'){
						$error_message = $this->config->item('project_details_page_minimum_required_bid_amount_validation_hourly_project_bid_form_message');
						$error_message = str_replace("{project_minimum_hourly_bid_amount}",str_replace(".00","",number_format($project_detail['min_budget'],  2, '.', ' ')),$error_message);
					}
					
					$msg['errors'][$i]['message'] = $error_message;
					$msg['errors'][$i]['error_class'] = 'minimum_bid_amount';
					$i ++;
				}
			} else if($project_detail['confidential_dropdown_option_selected'] == 'Y' || $project_detail['not_sure_dropdown_option_selected'] == 'Y') {
				
				$min_salary_amt = $this->config->item('fulltime_project_min_salary_amount');
				
				if($project_detail['project_type'] == 'hourly' && str_replace(" ","",$post_data['bid_amount']) < $this->config->item('project_details_page_min_hourly_rate_value')) {
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_amount';
					if($project_detail['project_type'] == 'hourly') {
						$error_message = $this->config->item('project_details_page_minimum_required_bid_amount_validation_hourly_project_bid_form_message');
						$error_message = str_replace("{project_minimum_hourly_bid_amount}",str_replace(".00","",number_format($this->config->item('project_details_page_min_hourly_rate_value'), 0, '', ' ')),$error_message);
					}
					$msg['errors'][$i]['message'] = $error_message;
					$msg['errors'][$i]['error_class'] = 'minimum_bid_amount';
					$i ++;
				}else if($project_detail['project_type'] == 'fulltime' &&  str_replace(" ","",$post_data['bid_amount']) < $min_salary_amt) {
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_amount';
					$error_message = $this->config->item('project_details_page_minimum_required_salary_amount_validation_fulltime_project_application_form_message');
					$error_message = str_replace("{project_minimum_salary_amount}", str_replace(".00","",number_format($min_salary_amt,  2, '.', ' ')." ".CURRENCY.$this->config->item('post_project_budget_per_month')), $error_message);
					$msg['errors'][$i]['message'] = $error_message;
					$msg['errors'][$i]['error_class'] = 'minimum_bid_amount';
					$i ++;
				}
				
			}
		}
		if(!isset($post_data['biding_form_checkbox']) && ($project_detail['project_type'] == 'fixed' || $project_detail['project_type'] == 'hourly')){
			
			if (empty($post_data['delivery_period'])){
			
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'delivery_period';
				$msg['errors'][$i]['error_class'] = 'required';
				if($project_detail['project_type'] == 'fixed'){
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_number_days_validation_fixed_budget_project_bid_form_message');
				}else{
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_number_hours_validation_hourly_project_bid_form_message');
				}
				$i ++;
			}
		}
		
		if (empty(trim($post_data['bid_description']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'bid_description';
			if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_validation_fulltime_project_bid_form_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_validation_project_bid_form_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		
		if(!empty(trim($post_data['bid_description']))){
			if($this->config->item('project_details_page_bid_description_minimum_length_words_limit') == 0){
				
				if($bid_description_charcaters_length < $this->config->item('project_details_page_bid_description_minimum_length_character_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if($project_detail['project_type'] == 'fulltime'){
						$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_characters_min_length_validation_fulltime_project_bid_form_message');
					}else{
						$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_characters_min_length_validation_project_bid_form_message');
					}
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $post_data['bid_description']);
				$bid_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($bid_description_charcaters_length < $this->config->item('project_details_page_bid_description_minimum_length_character_limit') ||$bid_description_word_count < $this->config->item('project_details_page_bid_description_minimum_length_words_limit') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'bid_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if($project_detail['project_type'] == 'fulltime'){
						$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_characters_words_minimum_length_validation_fulltime_project_bid_form_message');
					}else{
						$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_characters_words_minimum_length_validation_project_bid_form_message');
					}
					
					$i ++;
				}
			}
		}
		if(!empty($post_data['bid_description']) && strlen($bid_description_charcaters_length) > $this->config->item('project_details_page_bid_description_maximum_length_character_limit')){
		
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'bid_description';
			$msg['errors'][$i]['error_class'] = 'max_length';
			if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_characters_maximum_length_validation_fulltime_project_bid_form_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_bid_description_characters_maximum_length_validation_project_bid_form_message');
			}
			$i ++;
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
      $msg['message'] = '';
		}
		return $msg;
	
	}
	
	
	// This function is using to fetch the bidding list but not include the login bidder
	public function get_project_bidder_list_exclude_bidder($project_id,$project_type,$profile_name,$project_status,$bidder_id)
	{
		
		$bidding_table_name = '';
		$order_by = 'bid_date';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_open_bidding_active_bids';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_open_bidding_active_applications';
			$order_by = 'application_date';
		}
		
		if($project_type == 'fulltime') { 
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,active_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
			->from($bidding_table_name.' active_bids')
			->join('users u', 'u.user_id = active_bids.employee_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('active_bids.employee_id != ', $bidder_id)
			->where('active_bids.fulltime_project_id', $project_id)
			->order_by('active_bids.'.$order_by.' DESC')
			->get()->result_array();
			$bidder_list = array();
		} else {
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,active_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->from($bidding_table_name.' active_bids')
			->join('users u', 'u.user_id = active_bids.bidder_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('active_bids.bidder_id != ', $bidder_id)
			->where('active_bids.project_id', $project_id)
			->order_by('active_bids.'.$order_by.' DESC')
			->get()->result_array();
			$bidder_list = array();
		}

		
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key => $value) {
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
							
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
							$this->ftp->delete_dir($source_path_avatar);
							$value['user_avatar'] = '';
							$acheck = false;
							// profile completeion script start //
							$user_profile_completion_data = array();
							$user_profile_completion_data['has_avatar'] = 'N';
							$user_profile_completion_data['avatar_strength_value'] =0;
							if($user_profile_completion_data){
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

							}
							// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$value['user_avatar'] = '';
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime') { 
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
					$bidder_list[$key]['bidder_id'] = $value['employee_id'];
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
				}
				
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;

			}
      $this->ftp->close();
      ######## connectivity of remote server end ####### 
		}
		
		return $bidder_list;
		
	}
	
	// This function is used to fetch the bidder list of project
	public function get_project_bidder_list($project_id,$project_type,$profile_name,$project_status)
	{
			
		$bidding_table_name = '';
		$order_by = 'bid_date';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_open_bidding_active_bids';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_open_bidding_active_applications';
			$order_by = 'application_date';
		}
		
		if($project_type == 'fulltime'){ 
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,active_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
			->from($bidding_table_name.' active_bids')
			->join('users u', 'u.user_id = active_bids.employee_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('active_bids.fulltime_project_id', $project_id)
			->order_by('active_bids.'.$order_by.' DESC')
			->get()->result_array();
		} else {
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,active_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->from($bidding_table_name.' active_bids')
			->join('users u', 'u.user_id = active_bids.bidder_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('active_bids.project_id', $project_id)
			->order_by('active_bids.'.$order_by.' DESC')
			->get()->result_array();
		}
		
		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
							$this->ftp->delete_dir($source_path_avatar);
							$value['user_avatar'] = '';
							$acheck = false;
							// profile completeion script start //
							$user_profile_completion_data = array();
							$user_profile_completion_data['has_avatar'] = 'N';
							$user_profile_completion_data['avatar_strength_value'] =0;
							if($user_profile_completion_data){
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

							}
							// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime'){  
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['bidder_id'] = $value['employee_id'];
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
				}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'incomplete'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
            $this->ftp->close();
                    ######## connectivity of remote server end ####### 
		}
		
		return $bidder_list;
		
	}
	
	
	// This function is used to fetch the bidder list of project
	public function get_project_awarded_bidder_list($project_id,$project_type,$profile_name,$project_status)
	{
		
		$bidding_table_name = '';
		$order_by = 'bid_date';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_awarded_tracking';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_awarded_tracking';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_awarded_tracking';
			$order_by = 'application_date';
		}
		if($project_type == 'fulltime'){ 
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,awarded_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
			->from($bidding_table_name.' awarded_bids')
			->join('users u', 'u.user_id = awarded_bids.employee_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('awarded_bids.fulltime_project_id', $project_id)
			->order_by('awarded_bids.'.$order_by.' DESC')
			->get()->result_array();
			
		} else {
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,awarded_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id  and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->from($bidding_table_name.' awarded_bids')
			->join('users u', 'u.user_id = awarded_bids.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('awarded_bids.project_id', $project_id)
			->order_by('awarded_bids.'.$order_by.' DESC')
			->get()->result_array();
		}
		/* echo "<pre>";
		print_r($result_array);
		die; */
		
		
		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$value['user_avatar'] = '';
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime'){
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['winner_id'] = $value['employee_id'];
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
				}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'incomplete'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						
						
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
                    $this->ftp->close();
                    ######## connectivity of remote server end ####### 
		}
		return $bidder_list;
		
	}
	
	
	// This function is used to fetch the bidder list of project
	public function get_project_awarded_bidder_list_sp($project_id,$project_type,$profile_name,$project_status,$sp_id)
	{
		
		$bidding_table_name = '';
		$order_by = 'bid_date';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_awarded_tracking';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_awarded_tracking';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_awarded_tracking';
			$order_by = 'application_date';
		}
		
		if($project_type == 'fulltime') { 
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,awarded_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
			->from($bidding_table_name.' awarded_bids')
			->join('users u', 'u.user_id = awarded_bids.employee_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('awarded_bids.fulltime_project_id', $project_id)
			->where('awarded_bids.employee_id != ', $sp_id)
			->order_by('awarded_bids.'.$order_by.' DESC')
			->get()->result_array();
		} else {
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,awarded_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->from($bidding_table_name.' awarded_bids')
			->join('users u', 'u.user_id = awarded_bids.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('awarded_bids.project_id', $project_id)
			->where('awarded_bids.winner_id != ', $sp_id)
			->order_by('awarded_bids.'.$order_by.' DESC')
			->get()->result_array();
		}

		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime'){
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['winner_id'] = $value['employee_id'];
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
				}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'incomplete'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
                    $this->ftp->close();
                    ######## connectivity of remote server end ####### 
		}
		
		return $bidder_list;
		
	}
	
	// This function is used to fetch the bidder list of project whose status is in progress
	public function get_project_inprogress_bidder_list($project_id,$project_type,$profile_name,$project_status,$project_owner_id)
	{
		
		$bidding_table_name = '';
		$user = $this->session->userdata('user');
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_hired_employees_tracking';
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
		}
		if(!empty($bidding_table_name)) {
			if($project_type == 'fulltime'){
				//$result_array = $this->db // get the user detail
				$this->db->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,inprogress_bids.*');
				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews');
				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects');
				
				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where employee_winner_id_of_disputed_fulltime_project = u.user_id and employer_id_of_disputed_fulltime_project = inprogress_bids.employer_id and inprogress_bids.fulltime_project_id = disputed_fulltime_project_id ) as total_sp_active_dispute_count');
				$this->db->from($bidding_table_name.' inprogress_bids');
				$this->db->join('users u', 'u.user_id = inprogress_bids.employee_id', 'left');
				$this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
				$this->db->where('inprogress_bids.fulltime_project_id', $project_id);
				//$this->db->having('total_employee_active_dispute_count = 0')

				if($this->session->userdata ('user') && $user[0]->user_id  == $project_owner_id) {
					$this->db->having('total_sp_active_dispute_count = 0');
				}
				$bid_result = $this->db->get();
				$result_array = $bid_result->result_array();


				//->get()->result_array();
				
			} else {
				$this->db->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,inprogress_bids.*');
				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews');

				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects');

				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects');

				$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = inprogress_bids.project_owner_id and inprogress_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count');

				$this->db->from($bidding_table_name.' inprogress_bids');
				$this->db->join('users u', 'u.user_id = inprogress_bids.winner_id', 'left');
				$this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
				$this->db->where('inprogress_bids.project_id', $project_id);
				if($this->session->userdata ('user') && $user[0]->user_id  == $project_owner_id) {
					$this->db->having('total_sp_active_dispute_count = 0');
				}
				$bid_result = $this->db->get();
				$result_array = $bid_result->result_array();
			}
		}
		
		/* echo "<pre>";
		print_r($result_array );
		die; */
		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));
						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime') {
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['winner_id'] = $value['employee_id'];
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
					$bidder_list[$key]['project_start_date'] = $value['employee_hired_on'];
				}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'incomplete'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
			$this->ftp->close();
			######## connectivity of remote server end ####### 
		
		}/* 
		echo "<pre>";
		print_r($bidder_list);
		die; */
		return $bidder_list;
		
	}
	
	// This function is used to fetch the bidder list of project whose status is completed
	public function get_project_completed_bidder_list($project_id,$project_type,$profile_name,$project_status,$project_owner_id)
	{
		$user = $this->session->userdata('user');
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_completed_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		/* if($project_type == 'fulltime'){
			$bidding_table_name = 'fixed_budget_projects_completed_tracking';
		}
		 */
		//$result_array = $this->db // get the user detail
		$this->db->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,completed_bids.*');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_hourly_based_projects');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = completed_bids.project_owner_id and completed_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count');
		$this->db->from($bidding_table_name.' completed_bids');
		$this->db->join('users u', 'u.user_id = completed_bids.winner_id', 'left');
		$this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
		$this->db->where('completed_bids.project_id', $project_id);
		//$this->db->having('total_sp_active_dispute_count = 0')
		//$this->db->get()->result_array();
		
		if($this->session->userdata ('user') && $user[0]->user_id  == $project_owner_id) {
			$this->db->having('total_sp_active_dispute_count = 0');
		}
		$bid_result = $this->db->get();
		$result_array = $bid_result->result_array();
		/* echo "<pre>";
		print_r($result_array);
		die; */
		
		
		$bidder_list = array();
		if(!empty($result_array)){
		######## connectivity of remote server start #######
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] = FTP_PORT;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						
						
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
             $this->ftp->close();
                    ######## connectivity of remote server end ####### 
		
		}
		/* echo "<pre>";
		print_r($bidder_list);
		die; */
		return $bidder_list;
		
	}
	
	// This function is used to fetch the bidder list of project whose status is in progress for SP
	public function get_project_inprogress_bidder_list_sp($project_id,$project_type,$profile_name,$project_status,$sp_id)
	{
		
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_hired_employees_tracking';
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
		}
		
		if(!empty($bidding_table_name)) {
			if($project_type == 'fulltime'){ 
				$result_array = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,inprogress_bids.*')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
				
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where employee_winner_id_of_disputed_fulltime_project = u.user_id and employer_id_of_disputed_fulltime_project = inprogress_bids.employer_id and inprogress_bids.fulltime_project_id = disputed_fulltime_project_id ) as total_sp_active_dispute_count')
				
				->from($bidding_table_name.' inprogress_bids')
				->join('users u', 'u.user_id = inprogress_bids.employee_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('inprogress_bids.fulltime_project_id', $project_id)
				->where('inprogress_bids.employee_id !=', $sp_id)
				//->having('total_employee_active_dispute_count = 0')
				->get()->result_array();
			} else {
				$result_array = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,inprogress_bids.*')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_hourly_based_projects')
				
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = inprogress_bids.project_owner_id and inprogress_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count')
				
				->from($bidding_table_name.' inprogress_bids')
				
				->join('users u', 'u.user_id = inprogress_bids.winner_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('inprogress_bids.project_id', $project_id)
				->where('inprogress_bids.winner_id !=', $sp_id)
				//->having('total_sp_active_dispute_count = 0')
				->get()->result_array();
			}
			
		}
		/* echo "<pre>";
		print_r($result_array); */
		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime') {
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
					$bidder_list[$key]['winner_id'] = $value['employee_id'];
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['project_start_date'] = $value['employee_hired_on'];
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
				}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
			$this->ftp->close();
			######## connectivity of remote server end ####### 
		
		}
		
		return $bidder_list;
		
	}
	
	
	// This function is used to fetch the bidder list of project whose status is incomplete for SP
	public function get_project_incomplete_bidder_list_sp($project_id,$project_type,$profile_name,$project_status,$sp_id)
	{
	
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_incomplete_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
			
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		if(!empty($bidding_table_name)) {
			
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,incomplete_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_hourly_based_projects')
			
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.$active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = incomplete_bids.project_owner_id and incomplete_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($bidding_table_name.' incomplete_bids')
			
			->join('users u', 'u.user_id = incomplete_bids.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('incomplete_bids.project_id', $project_id)
			->where('incomplete_bids.winner_id !=', $sp_id)
			//->having('total_sp_active_dispute_count = 0')
			->get()->result_array();
		}
		/* echo "<pre>";
		print_r($result_array); */
		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				if($project_type == 'fulltime') {
					$bidder_list[$key]['bidding_dropdown_option'] = $value['application_bidding_dropdown_option'];
					$bidder_list[$key]['winner_id'] = $value['employee_id'];
					$bidder_list[$key]['project_owner_id'] = $value['employer_id'];
					$bidder_list[$key]['project_start_date'] = $value['employee_hired_on'];
					$bidder_list[$key]['project_id'] = $value['fulltime_project_id'];
				}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}if($project_status == 'incomplete'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
			$this->ftp->close();
			######## connectivity of remote server end ####### 
		
		}
		
		return $bidder_list;
		
	}
	
	// This function is used to fetch the bidder list of project whose status is completed for SP
	public function get_project_completed_bidder_list_sp($project_id,$project_type,$profile_name,$project_status,$sp_id)
	{
		
			
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_completed_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		/* if($project_type == 'fulltime'){
			$bidding_table_name = 'fixed_budget_projects_completed_tracking';
		} */
		 	
		$result_array = $this->db // get the user detail
		->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,completed_bids.*')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = completed_bids.project_owner_id and completed_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count')
		->from($bidding_table_name.' completed_bids')
		->join('users u', 'u.user_id = completed_bids.winner_id', 'left')
		->join('users_details ud', 'ud.user_id = u.user_id', 'left')
		->where('completed_bids.project_id', $project_id)
		->where('completed_bids.winner_id !=', $sp_id)
		//->having('total_sp_active_dispute_count = 0')
		->get()->result_array();
		$bidder_list = array();
		if(!empty($result_array)){
				######## connectivity of remote server start #######
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] = FTP_PORT;
				$config['debug'] = TRUE;
				$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
							
								
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							
							
							}
						}
					
					
					}
				
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
                    $this->ftp->close();
                    ######## connectivity of remote server end ####### 
		
		}
		
		return $bidder_list;
		
	}
	
	// This function is used to fetch the bidder list of project whose status is incomplete for SP
	public function get_project_incomplete_bidder_list($project_id,$project_type,$profile_name,$project_status,$project_owner_id)
	{
		$user = $this->session->userdata('user');	
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_incomplete_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		/* if($project_type == 'fulltime'){
			$bidding_table_name = 'fixed_budget_projects_completed_tracking`';
		} */
		
		//$result_array = $this->db // get the user detail
		$this->db->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,incompleted_bids.*');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = incompleted_bids.project_owner_id and incompleted_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count');
		
		$this->db->from($bidding_table_name.' incompleted_bids');
		$this->db->join('users u', 'u.user_id = incompleted_bids.winner_id', 'left');
		$this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
		
		$this->db->where('incompleted_bids.project_id', $project_id);
		//->where('incompleted_bids.winner_id !=', $sp_id)
		if($this->session->userdata ('user') && $user[0]->user_id  == $project_owner_id) {
			$this->db->having('total_sp_active_dispute_count = 0');
		}
		
		$bid_result = $this->db->get();
		$result_array = $bid_result->result_array();
		
		//->get()->result_array();
		
		/* echo "<pre>";
		print_r($result_array);
		die; */
		
		$bidder_list = array();
		if(!empty($result_array)){
			######## connectivity of remote server start #######
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $value['profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['user_avatar'];

				$exap = explode('.', $value['user_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
					$acheck = true;
					if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$value['user_avatar'] = '';
						$acheck = false;
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

						}
						// profile completeion script end //
					}
				} if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
					$value['user_avatar'] = '';
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$value['user_id'],'account_type'=>$value['account_type']));

					}
					// profile completeion script end //
				}
				$bidder_list[$key] = $value;
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $value['user_id'])
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
				if(!empty($bidder_attachment_list)){
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
						if($project_status == 'open_for_bidding'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
							
						}
						if($project_status == 'awarded'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'in_progress'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}if($project_status == 'incomplete'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'completed'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'expired'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if($project_status == 'cancelled'){
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						}
						if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
							if($file_size != '-1'){
								$bid_attachment_data['id'] = $bid_attachment_value['id'];
								$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
								$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
								$bid_attachment_array[] = $bid_attachment_data;
							}
						}
					}
				}
				$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
			}
                    $this->ftp->close();
                    ######## connectivity of remote server end ####### 
		
		}
		/* echo "<pre>";
		print_r($bidder_list);
		die; */
		return $bidder_list;
		
	}
	
	
	
	/* This function is used to fetch the bidder list of specific project */
	public function get_user_bid_detail($project_id,$bidder_id,$project_type,$project_status,$profile_name ){
		
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_open_bidding_active_bids';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_open_bidding_active_applications';
			$order_by = 'application_date';
		}
		$result_array = array();

		if($project_type == 'fulltime') { 
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,active_bids.*')
			
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
			
			->from($bidding_table_name.' active_bids')
			->join('users u', 'u.user_id = active_bids.employee_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('active_bids.employee_id', $bidder_id)
			->where('active_bids.fulltime_project_id', $project_id)
			->get()->row_array();

			$result_array['project_owner_id'] = $result_array['employer_id'];
			$result_array['project_id'] = $result_array['fulltime_project_id'];
			$result_array['bidder_id'] = $result_array['employee_id'];
			$result_array['bidding_dropdown_option'] = $result_array['application_bidding_dropdown_option'];

		} else {
			$result_array = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,active_bids.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id  and project_completion_method = "via_portal") as sp_total_completed_hourly_based_projects')
			->from($bidding_table_name.' active_bids')
			->join('users u', 'u.user_id = active_bids.bidder_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('active_bids.bidder_id', $bidder_id)
			->where('active_bids.project_id', $project_id)
			->get()->row_array();
		}

		
		######## connectivity of remote server start #######
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] = FTP_PORT;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
		$common_source_path = USERS_FTP_DIR . $result_array['profile_name'];
		//$common_source_path = USERS_FTP_DIR . $profile_name;

		//avatar picture
		//start check avatar from ftp server
		$user_avatar = USER_AVATAR;
		$source_path_avatar = $common_source_path . $user_avatar;
		$avatarlist = $this->ftp->list_files($source_path_avatar);
		$avatar_pic = $source_path_avatar . $result_array['user_avatar'];

		$exap = explode('.', $result_array['user_avatar']);
		$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

		if (count($avatarlist) > 0) {
			$acheck = true;
			if (!in_array($avatar_pic, $avatarlist) && $acheck) {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $result_array['user_id']));
					$this->ftp->delete_dir($source_path_avatar);
					$result_array['user_avatar'] = '';
					$acheck = false;
					
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$result_array['user_id'],'account_type'=>$result_array['account_type']));
					
					}
					// profile completeion script end //
					
			} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$result_array['user_id']));
					$this->ftp->delete_dir($source_path_avatar);
					$result_array['user_avatar'] = '';
					$acheck = false;
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$result_array['user_id'],'account_type'=>$result_array['account_type']));
					
					}
					// profile completeion script end //
			}
		} if (count($avatarlist) == 0 && $result_array['user_avatar'] != '') {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $result_array['user_id']));
				$result_array['user_avatar'] = '';
				// profile completeion script start //
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$result_array['user_id'],'account_type'=>$result_array['account_type']));
				
				}
				// profile completeion script end //
		}
		######## connectivity of remote server end ####### 
            
		$bidder_attachment_list = $this->db // get the user detail
		->select('id,bid_attachment_name,user_id')
		->from('projects_active_bids_users_attachments_tracking')
		->where('project_id', $project_id)
		->where('user_id', $bidder_id)
		->order_by('id DESC')
		->get()->result_array();
		
		
		
		
		if(!empty($bidder_attachment_list)){
			
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_cancelled_dir = PROJECT_CANCELLED_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			//die($project_status);
			foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
				if($project_status == 'open_for_bidding'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					
				}
				if($project_status == 'awarded'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'in_progress'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'expired'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'cancelled'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				
				if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
					
					$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
					if($file_size != '-1'){
					
						
						$bid_attachment_data['id'] = $bid_attachment_value['id'];
						$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
						$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
						$bid_attachment_array[] = $bid_attachment_data;
					}
				}
			}
		
		}
		
		$this->ftp->close();
		$result_array['bid_attachments'] = $bid_attachment_array;
		return $result_array;
	}
	
	/* This function is used to fetch the awarded bid detail of sp */
	public function get_sp_awarded_bid_detail($project_id,$project_type,$profile_name,$project_status,$bidder_id){
		
		if($project_type == 'fixed'){
			$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
		}
		if($project_type == 'hourly'){
			$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
		}
		if($project_type == 'fulltime'){
			$award_bid_table_name = 'fulltime_projects_awarded_tracking';
			
		}
		$user_avatar = USER_AVATAR;
		$users_ftp_dir 	= USERS_FTP_DIR; 
		$projects_ftp_dir = PROJECTS_FTP_DIR;
		$project_cancelled_dir = PROJECT_CANCELLED_DIR;
		$project_expired_dir = PROJECT_EXPIRED_DIR;
		$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
		$project_awarded_dir = PROJECT_AWARDED_DIR;
		$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
		$project_completed_dir = PROJECT_COMPLETED_DIR;
		$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
		$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
		

		
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] = FTP_PORT;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
		if($project_type == 'fulltime') { 
			$awarded_bidder_data = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,ab.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
			->from($award_bid_table_name. ' ab')
			->join('users u', 'u.user_id = ab.employee_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('fulltime_project_id', $project_id)
			->where('employee_id', $bidder_id)
			->get()->row_array();
			$awarded_bidder_data['project_id'] = $awarded_bidder_data['fulltime_project_id'];
			$awarded_bidder_data['project_owner_id'] = $awarded_bidder_data['employer_id'];
			$awarded_bidder_data['winner_id'] = $awarded_bidder_data['employee_id'];
			$awarded_bidder_data['bidding_dropdown_option'] = $awarded_bidder_data['application_bidding_dropdown_option'];
		} else {
			$awarded_bidder_data = $this->db // get the user detail
			->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,ab.*')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->from($award_bid_table_name. ' ab')
			->join('users u', 'u.user_id = ab.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('project_id', $project_id)
			->where('winner_id', $bidder_id)
			->get()->row_array();
		}
		
		$source_path_avatar = USERS_FTP_DIR . $awarded_bidder_data['profile_name'] . $user_avatar;
		
		
		$bidder_attachment_list = $this->db // get the user detail
		->select('id,bid_attachment_name,user_id')
		->from('projects_active_bids_users_attachments_tracking')
		->where('project_id', $project_id)
		->where('user_id', $bidder_id)
		->order_by('id DESC')
		->get()->result_array();
		$bid_attachment_array = array();
		
		if(!empty($bidder_attachment_list)){
			
			foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
				if($project_status == 'open_for_bidding'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					
				}
				if($project_status == 'awarded'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'in_progress'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'expired'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'cancelled'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'completed'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				
				if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
				
					$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
					if($file_size != '-1'){
					
						$bid_attachment_data['id'] = $bid_attachment_value['id'];
						$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
						$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
						$bid_attachment_array[] = $bid_attachment_data;
					}
				}
			
			}
		
		}
		$awarded_bidder_data['bid_attachments'] = $bid_attachment_array;
		
		//avatar picture
		//start check avatar from ftp server
		
		$avatarlist = $this->ftp->list_files($source_path_avatar);
		$avatar_pic = $source_path_avatar . $awarded_bidder_data['user_avatar'];

		$exap = explode('.', $awarded_bidder_data['user_avatar']);
		$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

		if (count($avatarlist) > 0) {
			$acheck = true;
			if (!in_array($avatar_pic, $avatarlist) && $acheck) {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $awarded_bidder_data['user_id']));
				$this->ftp->delete_dir($source_path_avatar);
				$awarded_bidder_data['user_avatar'] = '';
				$acheck = false;
				// profile completeion script start //
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$awarded_bidder_data['user_id'],'account_type'=>$awarded_bidder_data['account_type']));

				}
				// profile completeion script end //
			} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$awarded_bidder_data['user_id']));
				$this->ftp->delete_dir($source_path_avatar);
				$awarded_bidder_data['user_avatar'] = '';
				$acheck = false;
				// profile completeion script start //
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$awarded_bidder_data['user_id'],'account_type'=>$awarded_bidder_data['account_type']));

				}
				// profile completeion script end //
			}
		} if (count($avatarlist) == 0 && $awarded_bidder_data['user_avatar'] != '') {
			$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $awarded_bidder_data['user_id']));
			$awarded_bidder_data['user_avatar'] = '';
			// profile completeion script start //
			$user_profile_completion_data = array();
			$user_profile_completion_data['has_avatar'] = 'N';
			$user_profile_completion_data['avatar_strength_value'] =0;
			if($user_profile_completion_data){
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$awarded_bidder_data['user_id'],'account_type'=>$awarded_bidder_data['account_type']));

			}
			// profile completeion script end //
		}
		$this->ftp->close();
		return $awarded_bidder_data;
	}
	
	/* This function is used to fetch the in progress bid detail of sp */
	public function get_sp_inprogress_bid_detail($project_id,$project_type,$profile_name,$project_status,$bidder_id){
		if($project_type == 'fixed'){
			$progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			$progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
			
		}
		if(!empty($progress_bid_table_name)) {
			if($project_type == 'fulltime'){ 
			
				
				$inprogress_bidder_data = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,ipb.*')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where employee_winner_id_of_disputed_fulltime_project = u.user_id and employer_id_of_disputed_fulltime_project = ipb.employer_id and ipb.fulltime_project_id = disputed_fulltime_project_id ) as total_employee_active_dispute_count')
				
				
				->from($progress_bid_table_name. ' ipb')
				->join('users u', 'u.user_id = ipb.employee_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('fulltime_project_id', $project_id)
				->where('employee_id', $bidder_id)
				->having('total_employee_active_dispute_count = 0')
				->get()->row_array();
				$inprogress_bidder_data['bidding_dropdown_option'] = $inprogress_bidder_data['application_bidding_dropdown_option'];
				$inprogress_bidder_data['winner_id'] = $inprogress_bidder_data['employee_id'];
				$inprogress_bidder_data['project_owner_id'] = $inprogress_bidder_data['employer_id'];
				$inprogress_bidder_data['project_start_date'] = $inprogress_bidder_data['employee_hired_on'];
				$inprogress_bidder_data['project_id'] = $inprogress_bidder_data['fulltime_project_id'];
			} else {
				$inprogress_bidder_data = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,ipb.*')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = ipb.project_owner_id and ipb.project_id = disputed_project_id ) as total_sp_active_dispute_count')
				
				->from($progress_bid_table_name. ' ipb')
				->join('users u', 'u.user_id = ipb.winner_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('project_id', $project_id)
				->where('winner_id', $bidder_id)
				->having('total_sp_active_dispute_count = 0')
				->get()->row_array();
			}
			
		}
		$users_ftp_dir 	= USERS_FTP_DIR; 
		$projects_ftp_dir = PROJECTS_FTP_DIR;
		$project_cancelled_dir = PROJECT_CANCELLED_DIR;
		$project_expired_dir = PROJECT_EXPIRED_DIR;
		$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
		$project_awarded_dir = PROJECT_AWARDED_DIR;
		$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
		$project_completed_dir = PROJECT_COMPLETED_DIR;
		$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
		$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] = FTP_PORT;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
		//avatar picture
		//start check avatar from ftp server
		$user_avatar = USER_AVATAR;
		$source_path_avatar = USERS_FTP_DIR . $inprogress_bidder_data['profile_name'] . $user_avatar;
		$avatarlist = $this->ftp->list_files($source_path_avatar);
		$avatar_pic = $source_path_avatar . $inprogress_bidder_data['user_avatar'];

		$exap = explode('.', $inprogress_bidder_data['user_avatar']);
		$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

		if (count($avatarlist) > 0) {
			$acheck = true;
			if (!in_array($avatar_pic, $avatarlist) && $acheck) {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $inprogress_bidder_data['user_id']));
				$this->ftp->delete_dir($source_path_avatar);
				$inprogress_bidder_data['user_avatar'] = '';
				$acheck = false;
				
				// profile completeion script start //	
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$inprogress_bidder_data['user_id'],'account_type'=>$inprogress_bidder_data['account_type']));

				}
				// profile completeion script end //

				
			} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$inprogress_bidder_data['user_id']));
				$this->ftp->delete_dir($source_path_avatar);
				$inprogress_bidder_data['user_avatar'] = '';
				$acheck = false;
				// profile completeion script start //	
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$inprogress_bidder_data['user_id'],'account_type'=>$inprogress_bidder_data['account_type']));

				}
				// profile completeion script end //
			}
		} if (count($avatarlist) == 0 && $inprogress_bidder_data['user_avatar'] != '') {
			$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $inprogress_bidder_data['user_id']));
			$inprogress_bidder_data['user_avatar'] = '';
			// profile completeion script start //	
			$user_profile_completion_data = array();
			$user_profile_completion_data['has_avatar'] = 'N';
			$user_profile_completion_data['avatar_strength_value'] =0;
			if($user_profile_completion_data){
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$inprogress_bidder_data['user_id'],'account_type'=>$inprogress_bidder_data['account_type']));

			}
			// profile completeion script end //
		}
		$bidder_attachment_list = $this->db // get the user detail
			->select('id,bid_attachment_name,user_id')
			->from('projects_active_bids_users_attachments_tracking')
			->where('project_id', $project_id)
			->where('user_id', $bidder_id)
			->order_by('id DESC')
			->get()->result_array();
			$bid_attachment_array = array();
		if(!empty($bidder_attachment_list)){
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_cancelled_dir = PROJECT_CANCELLED_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
				if($project_status == 'open_for_bidding'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					
				}
				if($project_status == 'awarded'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'in_progress'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'expired'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'cancelled'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if($project_status == 'completed'){
					$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
					$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
				}
				if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
				
					$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
					if($file_size != '-1'){
					
						
						$bid_attachment_data['id'] = $bid_attachment_value['id'];
						$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
						$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
						$bid_attachment_array[] = $bid_attachment_data;
					
					
					}
				}
			
			
			}
		
		}
		$inprogress_bidder_data['bid_attachments']= $bid_attachment_array;
		$this->ftp->close();
		return $inprogress_bidder_data;
	
	
	}	
	
	/* This function is used to fetch the incomplete bid detail of sp */
	public function get_sp_incomplete_bid_detail($project_id,$project_type,$profile_name,$project_status,$bidder_id){
		$incomplete_bid_table_name = '';
		if($project_type == 'fixed'){
			$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		/* if($project_type == 'fulltime'){
			$incomplete_bid_table_name = 'fulltime_projects_hired_employees_tracking';
			
		} */
		if(!empty($incomplete_bid_table_name)) {
			if($project_type == 'fulltime'){ 
				$incomplete_bidder_data = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,ipb.*')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
				->from($incomplete_bid_table_name. ' ipb')
				->join('users u', 'u.user_id = ipb.employee_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('fulltime_project_id', $project_id)
				->where('employee_id', $bidder_id)
				->get()->row_array();
				$incomplete_bidder_data['bidding_dropdown_option'] = $incomplete_bidder_data['application_bidding_dropdown_option'];
				$incomplete_bidder_data['winner_id'] = $incomplete_bidder_data['employee_id'];
				$incomplete_bidder_data['project_owner_id'] = $incomplete_bidder_data['employer_id'];
				$incomplete_bidder_data['project_start_date'] = $incomplete_bidder_data['employee_hired_on'];
				$incomplete_bidder_data['project_id'] = $incomplete_bidder_data['fulltime_project_id'];
			} else {
				$incomplete_bidder_data = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,ipb.*')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = ipb.project_owner_id and ipb.project_id = disputed_project_id ) as total_sp_active_dispute_count')
				->from($incomplete_bid_table_name. ' ipb')
				->join('users u', 'u.user_id = ipb.winner_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('project_id', $project_id)
				->where('winner_id', $bidder_id)
				->having('total_sp_active_dispute_count = 0')
				->get()->row_array();
			}
			
		}
		if(!empty($incomplete_bidder_data)){
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_cancelled_dir = PROJECT_CANCELLED_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			//avatar picture
			//start check avatar from ftp server
			$user_avatar = USER_AVATAR;
			$source_path_avatar = USERS_FTP_DIR . $incomplete_bidder_data['profile_name'] . $user_avatar;
			$avatarlist = $this->ftp->list_files($source_path_avatar);
			$avatar_pic = $source_path_avatar . $incomplete_bidder_data['user_avatar'];

			$exap = explode('.', $incomplete_bidder_data['user_avatar']);
			$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

			if (count($avatarlist) > 0) {
				$acheck = true;
				if (!in_array($avatar_pic, $avatarlist) && $acheck) {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $incomplete_bidder_data['user_id']));
					$this->ftp->delete_dir($source_path_avatar);
					$incomplete_bidder_data['user_avatar'] = '';
					$acheck = false;
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$incomplete_bidder_data['user_id'],'account_type'=>$incomplete_bidder_data['account_type']));

					}
					// profile completeion script end //
				} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$incomplete_bidder_data['user_id']));
					$this->ftp->delete_dir($source_path_avatar);
					$incomplete_bidder_data['user_avatar'] = '';
					$acheck = false;
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$incomplete_bidder_data['user_id'],'account_type'=>$incomplete_bidder_data['account_type']));

					}
					// profile completeion script end //
				}
			} if (count($avatarlist) == 0 && $incomplete_bidder_data['user_avatar'] != '') {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $incomplete_bidder_data['user_id']));
				$incomplete_bidder_data['user_avatar'] = '';
				// profile completeion script start //	
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$incomplete_bidder_data['user_id'],'account_type'=>$incomplete_bidder_data['account_type']));

				}
				// profile completeion script end //
			}
			$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $bidder_id)
				->order_by('id DESC')
				->get()->result_array();
				$bid_attachment_array = array();
			if(!empty($bidder_attachment_list)){
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_cancelled_dir = PROJECT_CANCELLED_DIR;
				$project_expired_dir = PROJECT_EXPIRED_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_awarded_dir = PROJECT_AWARDED_DIR;
				$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
				$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
				$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
					if($project_status == 'open_for_bidding'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						
					}
					if($project_status == 'awarded'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'in_progress'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'incomplete'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'expired'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'cancelled'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'completed'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
					
						$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
						if($file_size != '-1'){
						
							
							$bid_attachment_data['id'] = $bid_attachment_value['id'];
							$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
							$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
							$bid_attachment_array[] = $bid_attachment_data;
						
						
						}
					}
				
				
				}
			
			}
			$incomplete_bidder_data['bid_attachments']= $bid_attachment_array;
			$this->ftp->close();
		}else{
			$incomplete_bidder_data = array();
		}
		/* echo "<pre>";
		print_r($incomplete_bidder_data); */
		
		return $incomplete_bidder_data;
	
	
	}
	
	/* This function is used to fetch the completed bid detail of sp */
	public function get_sp_completed_bid_detail($project_id,$project_type,$profile_name,$project_status,$bidder_id){
		
		if($project_type == 'fixed'){
			$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		/* if($project_type == 'fulltime'){
			$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
			
		} */
		
		$complete_bidder_data = $this->db // get the user detail
		->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,cb.*')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = cb.project_owner_id and cb.project_id = disputed_project_id ) as total_sp_active_dispute_count')
		->from($completed_bid_table_name. ' cb')
		->join('users u', 'u.user_id = cb.winner_id', 'left')
		->join('users_details ud', 'ud.user_id = u.user_id', 'left')
		->where('project_id', $project_id)
		->where('winner_id', $bidder_id)
		->having('total_sp_active_dispute_count = 0')
		->get()->row_array();
		if(!empty($complete_bidder_data)){
		
			//$common_source_path = USERS_FTP_DIR . $profile_name;
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_cancelled_dir = PROJECT_CANCELLED_DIR;
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			
			
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
			//avatar picture
			//start check avatar from ftp server
			$user_avatar = USER_AVATAR;
			$source_path_avatar = USERS_FTP_DIR . $complete_bidder_data['profile_name'] . $user_avatar;
			$avatarlist = $this->ftp->list_files($source_path_avatar);
			$avatar_pic = $source_path_avatar . $complete_bidder_data['user_avatar'];

			$exap = explode('.', $complete_bidder_data['user_avatar']);
			$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';
			
			if (count($avatarlist) > 0) {
				$acheck = true;
				if (!in_array($avatar_pic, $avatarlist) && $acheck) {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $complete_bidder_data['user_id']));
					$this->ftp->delete_dir($source_path_avatar);
					$complete_bidder_data['user_avatar'] = '';
					$acheck = false;
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$complete_bidder_data['user_id'],'account_type'=>$complete_bidder_data['account_type']));

					}
					// profile completeion script end //
				} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$complete_bidder_data['user_id']));
					$this->ftp->delete_dir($source_path_avatar);
					$complete_bidder_data['user_avatar'] = '';
					$acheck = false;
					// profile completeion script start //	
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					if($user_profile_completion_data){
						$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$complete_bidder_data['user_id'],'account_type'=>$complete_bidder_data['account_type']));

					}
					// profile completeion script end //
				}
			} if (count($avatarlist) == 0 && $complete_bidder_data['user_avatar'] != '') {
				$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $complete_bidder_data['user_id']));
				 $complete_bidder_data['user_avatar'] = '';
				 // profile completeion script start //	
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				if($user_profile_completion_data){
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$complete_bidder_data['user_id'],'account_type'=>$complete_bidder_data['account_type']));

				}
				// profile completeion script end //
			}
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $bidder_id)
				->order_by('id DESC')
				->get()->result_array();
				
				$bid_attachment_array = array();
				
				if(!empty($bidder_attachment_list)){
				foreach($bidder_attachment_list as $bid_attachment_key=>$bid_attachment_value){
					if($project_status == 'open_for_bidding'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
						
					}
					if($project_status == 'awarded'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'in_progress'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'expired'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'cancelled'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if($project_status == 'completed'){
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
					}
					if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
					
						$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
						if($file_size != '-1'){
						
							
							$bid_attachment_data['id'] = $bid_attachment_value['id'];
							$bid_attachment_data['bid_attachment_name'] = $bid_attachment_value['bid_attachment_name'];
							$bid_attachment_data['user_id'] = $bid_attachment_value['user_id'];
							$bid_attachment_array[] = $bid_attachment_data;
						
						
						}
					}
				
				
				}
			
			}
			$complete_bidder_data['bid_attachments']= $bid_attachment_array;
			$this->ftp->close();
		}	
		
		return $complete_bidder_data;
	
	
	}	
	
	// This function is used to sum the number of active,awarded,in progress bids/applications
	public function get_project_bid_count($project_id,$project_type)
	{
		if(empty($project_id) || empty($project_type)){
		
			return 0;
		}
	
	
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_array = array('fixed_budget_projects_open_bidding_active_bids','fixed_budget_projects_awarded_tracking','fixed_budget_projects_progress_sp_bid_reference','fixed_budget_projects_completed_tracking','fixed_budget_projects_incomplete_tracking');
		}
		if($project_type == 'hourly'){
			
			$bidding_table_array = array('hourly_rate_based_projects_open_bidding_active_bids','hourly_rate_based_projects_awarded_tracking','hourly_rate_based_projects_progress_sp_bid_reference', 'hourly_rate_based_projects_completed_tracking','hourly_rate_based_projects_incomplete_tracking');
		}
		if($project_type == 'fulltime'){
			
			$bidding_table_array = array('fulltime_projects_open_bidding_active_applications','fulltime_projects_awarded_tracking', 'fulltime_projects_hired_employees_tracking');
			
		}
		
		
		$project_bid_count = 0;
		foreach($bidding_table_array as $bidding_table_name){
			if(substr($bidding_table_name, 0, strlen('fulltime')) === 'fulltime') {
				$project_bid_count +=  $this->db->where(['fulltime_project_id' => $project_id])->from($bidding_table_name)->count_all_results();
			} else {
				$project_bid_count +=  $this->db->where(['project_id' => $project_id])->from($bidding_table_name)->count_all_results();
			}
		
		}
		return $project_bid_count; 
		
	}
	
	// This function is used to count the number of in progress bidder+completed bidder working on the project or hires for the project
	public function get_project_hires_count($project_id,$project_type)
	{
		if(empty($project_id) || empty($project_type)){
		
			return 0;
		}
	
	
		$in_progress_bid_count = '';
		$in_complete_bid_count = 0;
		if($project_type == 'fixed'){
			$in_progress_bidding_table = 'fixed_budget_projects_progress_sp_bid_reference';
			$in_complete_bidding_table = 'fixed_budget_projects_incomplete_tracking';
			$completed_bidding_table = 'fixed_budget_projects_completed_tracking';
		}
		if($project_type == 'hourly') {
			$in_progress_bidding_table = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$in_complete_bidding_table = 'hourly_rate_based_projects_incomplete_tracking';
			$completed_bidding_table = 'hourly_rate_based_projects_completed_tracking';
		}
		if($project_type == 'fulltime') {
			$in_progress_bidding_table = 'fulltime_projects_hired_employees_tracking';	
		}
		if($project_type == 'fulltime') {
			$in_progress_bid_count =  $this->db->where(['fulltime_project_id' => $project_id])->from($in_progress_bidding_table)->count_all_results();
		} else {
			$in_progress_bid_count =  $this->db->where(['project_id' => $project_id])->from($in_progress_bidding_table)->count_all_results();
			if($project_type == 'fixed' || $project_type == 'hourly') {
			 $in_complete_bid_count =  $this->db->where(['project_id' => $project_id])->from($in_complete_bidding_table)->count_all_results();
			}
		}
		if(!empty($completed_bidding_table)) {
			$completed_bid_count =  $this->db->where(['project_id' => $project_id])->from($completed_bidding_table)->count_all_results();
		} else {
			$completed_bid_count = 0;
		}
		$total_hires = $in_progress_bid_count+$completed_bid_count+$in_complete_bid_count;
		return $total_hires; 
		
	}
	
	/* This function is used to check that user already apply the bid on project or not*/
	public function check_sp_active_bid_exists_project ($project_id,$bidder_id,$project_type)
	{	
		
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$bidding_table_name = 'fixed_budget_projects_open_bidding_active_bids';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
		}
		if($project_type == 'fulltime'){
			$bidding_table_name = 'fulltime_projects_open_bidding_active_applications';
			
		}
		if($project_type == 'fulltime'){ 
			$sp_project_bid_count = $this->db->where(['fulltime_project_id' => $project_id,'employee_id' => $bidder_id])->from($bidding_table_name)->count_all_results();
		} else {
			$sp_project_bid_count = $this->db->where(['project_id' => $project_id,'bidder_id' => $bidder_id])->from($bidding_table_name)->count_all_results();
		}
		return $sp_project_bid_count;
	}
	
	/* This function is used to check that sp have awarded bid */
	public function check_sp_awarded_bid_exists ($project_id,$bidder_id,$project_type)
	{	
		
		$awarded_bid_table_name = '';
		if($project_type == 'fixed'){
			$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
		}
		if($project_type == 'hourly'){
			$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
		}
		if($project_type == 'fulltime'){
			$awarded_bid_table_name = 'fulltime_projects_awarded_tracking`';
			
		}
		if($project_type == 'fulltime'){
			$sp_awarded_bid_count = $this->db->where(['fulltime_project_id' => $project_id,'employee_id' => $bidder_id])->from($awarded_bid_table_name)->count_all_results();
		} else {
			$sp_awarded_bid_count = $this->db->where(['project_id' => $project_id,'winner_id' => $bidder_id])->from($awarded_bid_table_name)->count_all_results();
		}
		return $sp_awarded_bid_count;
	}
	
	/* This function is used to check that sp have in progress bid */
	public function check_sp_in_progress_bid_exists ($project_id,$bidder_id,$project_type)
	{	
		
		$in_progress_bid_table_name = '';
		if($project_type == 'fixed'){
			$in_progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$in_progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			$in_progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
			
		}
		if(!empty($in_progress_bid_table_name)) {
			if($project_type == 'fulltime'){ 
			
				$inprogress_bidder_data = $this->db // get the user detail
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where employee_winner_id_of_disputed_fulltime_project = ipb.employee_id and employer_id_of_disputed_fulltime_project = ipb.employer_id and ipb.fulltime_project_id = disputed_fulltime_project_id ) as total_sp_active_dispute_count')
				->from($in_progress_bid_table_name. ' ipb')
				->where('fulltime_project_id', $project_id)
				->where('employee_id', $bidder_id)
				//->having('total_sp_active_dispute_count = 0')
				->get()->row_array();
			} else {
				/* $sp_in_progress_bid_count = $this->db->where(['project_id' => $project_id,'winner_id' => $bidder_id])->from($in_progress_bid_table_name)->count_all_results(); */
				
				$inprogress_bidder_data = $this->db // get the user detail
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = ipb.winner_id and project_owner_id_of_disputed_project = ipb.project_owner_id and ipb.project_id = disputed_project_id ) as total_sp_active_dispute_count')
				->from($in_progress_bid_table_name. ' ipb')
				->where('project_id', $project_id)
				->where('winner_id', $bidder_id)
				//->having('total_sp_active_dispute_count = 0')
				->get()->row_array();
			}
			if(empty($inprogress_bidder_data) || $inprogress_bidder_data['total_sp_active_dispute_count'] >= 1){
				$sp_in_progress_bid_count = 0;
			}else{
				$sp_in_progress_bid_count = 1;
			}
				
		}
		
		return $sp_in_progress_bid_count;
	}
	
	/* This function is used to check that sp have in complete bid */
	public function check_sp_incomplete_bid_exists ($project_id,$bidder_id,$project_type)
	{	
		
		$in_complete_bid_table_name = '';
		
		if($project_type == 'fixed'){
			$in_complete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$in_complete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		
		if(!empty($in_complete_bid_table_name)) {
			$incomplete_bidder_data = $this->db // get the user detail
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = ipb.winner_id and project_owner_id_of_disputed_project = ipb.project_owner_id and ipb.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($in_complete_bid_table_name. ' ipb')
			->where('project_id', $project_id)
			->where('winner_id', $bidder_id)
			//->having('total_sp_active_dispute_count = 0')
			->get()->row_array();
			if(empty($incomplete_bidder_data) || $incomplete_bidder_data['total_sp_active_dispute_count'] >= 1){
				$sp_in_complete_bid_count = 0;
			}else{
				$sp_in_complete_bid_count = 1;
			}
		}
		return $sp_in_complete_bid_count;
	}
	
	
	/* This function is used to check that sp have completed bid */
	public function check_sp_completed_bid_exists ($project_id,$bidder_id,$project_type)
	{	
		
		$completed_bid_table_name = '';
		if($project_type == 'fixed'){
			$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}			
		
		/* if($project_type == 'fixed'){ */
		
			
			$complete_bidder_data = $this->db // get the user detail
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where sp_winner_id_of_disputed_project = cpb.winner_id and project_owner_id_of_disputed_project = cpb.project_owner_id and cpb.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($completed_bid_table_name. ' cpb')
			->where('project_id', $project_id)
			->where('winner_id', $bidder_id)
			//->having('total_sp_active_dispute_count = 0')
			->get()->row_array();
			
			if(empty($complete_bidder_data) || $complete_bidder_data['total_sp_active_dispute_count'] >= 1){
				$sp_completed_bid_count = 0;
			}else{
				$sp_completed_bid_count = 1;
			}
			
			
		/* } */
		/* if($project_type == 'hourly'){
			$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
			$sp_completed_bid_count = $this->db->where(['project_id' => $project_id,'winner_id' => $bidder_id])->from($completed_bid_table_name)->count_all_results();
		} */
		if($project_type == 'fulltime'){
			$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
			$sp_completed_bid_count = $this->db->where(['project_id' => $project_id,'winner_id' => $bidder_id])->from($completed_bid_table_name)->count_all_results();
			
		}
		
		
		return $sp_completed_bid_count;
	}
	
	
	/* This function is removed the orphans entries of bid attachment from disk and database*/
	public function check_and_remove_orphan_entries_from_disk_database_project_bid_attachments($project_id,$project_status,$profile_name) {
		
		if($project_status == 'open_for_bidding' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'expired' || $project_status == 'completed' || $project_status == 'incomplete' ){
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
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$profile_folder     = $profile_name;
			if($project_status == 'open_for_bidding'){
				$bid_attachment_dir_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir;
			}if($project_status == 'expired'){
				$bid_attachment_dir_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir;
			}
			if($project_status == 'awarded'){
				$bid_attachment_dir_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir;
			}if($project_status == 'in_progress'){
				$bid_attachment_dir_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir;
			}
			if($project_status == 'completed'){
				$bid_attachment_dir_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir;
			}
			if($project_status == 'incomplete'){
				$bid_attachment_dir_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir;
			}
			
			
			
			
			if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_dir_path))){
				$user_folder_list = $this->ftp->list_files($bid_attachment_dir_path);
				foreach($user_folder_list as $user) {
					$users = explode('/', $user);
					if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_dir_path.end($users)))){
						
						if($project_status == 'open_for_bidding'){
							$user_bid_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.end($users). DIRECTORY_SEPARATOR );
						}if($project_status == 'expired'){
							$user_bid_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.end($users). DIRECTORY_SEPARATOR );
						}if($project_status == 'awarded'){
							$user_bid_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.end($users). DIRECTORY_SEPARATOR );
						}if($project_status == 'in_progress'){
							$user_bid_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.end($users). DIRECTORY_SEPARATOR );
						}
						if($project_status == 'incomplete'){
							$user_bid_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.end($users). DIRECTORY_SEPARATOR );
						}
						
						if($project_status == 'completed'){
							$user_bid_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.end($users). DIRECTORY_SEPARATOR );
						}
						
						foreach($user_bid_attachment_list as $attachments) {
							$attachment_array = explode('/', $attachments);
							//echo end($attachment_array);
							
							$project_bid_attachment_data = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id,'user_id'=>end($users),'bid_attachment_name'=>end($attachment_array)])->result_array();
							if(empty($project_bid_attachment_data)){
								if($project_status == 'open_for_bidding'){
									$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.end($users) .DIRECTORY_SEPARATOR .end($attachment_array);
								}if($project_status == 'expired'){
									$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.end($users) .DIRECTORY_SEPARATOR .end($attachment_array);
								}if($project_status == 'awarded'){
									$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.end($users) .DIRECTORY_SEPARATOR .end($attachment_array);
								}if($project_status == 'in_progress'){
									$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.end($users) .DIRECTORY_SEPARATOR .end($attachment_array);
								}if($project_status == 'incomplete'){
									$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.end($users) .DIRECTORY_SEPARATOR .end($attachment_array);
								}if($project_status == 'completed'){
									$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.end($users) .DIRECTORY_SEPARATOR .end($attachment_array);
								}
								
								
								$file_size = $this->ftp->get_filesize($source_path);
								if($file_size != '-1')
								{
									$this->ftp->delete_file($source_path);
								}
							
							}
							
						}
					}
				}
			}
			$project_attachment_data = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id])->result_array();
			if(!empty($project_attachment_data)){
			
				foreach($project_attachment_data as $attachment_key=>$attachment_vale){
					
					if($project_status == 'open_for_bidding'){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$attachment_vale['user_id'] .DIRECTORY_SEPARATOR .$attachment_vale['bid_attachment_name'];
					}if($project_status == 'expired'){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$attachment_vale['user_id'] .DIRECTORY_SEPARATOR .$attachment_vale['bid_attachment_name'];
					}if($project_status == 'awarded'){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$attachment_vale['user_id'] .DIRECTORY_SEPARATOR .$attachment_vale['bid_attachment_name'];
					}if($project_status == 'in_progress'){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$attachment_vale['user_id'] .DIRECTORY_SEPARATOR .$attachment_vale['bid_attachment_name'];
					}if($project_status == 'incomplete'){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$attachment_vale['user_id'] .DIRECTORY_SEPARATOR .$attachment_vale['bid_attachment_name'];
					}if($project_status == 'completed'){
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$attachment_vale['user_id'] .DIRECTORY_SEPARATOR .$attachment_vale['bid_attachment_name'];
						}
					
					
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size == '-1')
					{
						$this->db->delete('projects_active_bids_users_attachments_tracking', array('id' => $attachment_vale['id']));
					}
				}
			
			}else{
				if($project_status == 'open_for_bidding'){
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir;
				}if($project_status == 'expired'){
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir;
				}if($project_status == 'awarded'){
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir;
				}if($project_status == 'in_progress'){
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir;
				}if($project_status == 'completed'){
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir;
				}
				if($project_status == 'incomplete'){
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir;
				}
				
				if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
					$this->ftp->delete_dir($source_path);// delete project directory 
				}
			}
			$this->ftp->close();
		}
	}
	
	/*
	This function is used to move awarded bids whose awarded expiration time is passed to active bids tables 
	*/
	public function move_awarded_expired_bid_to_active_bids_dedicated_project($awarded_project_detail,$project_status){
	
		
		
		$project_type = $awarded_project_detail['project_type'];
		if(isset($awarded_project_detail['project_id'])){
			$project_id = $awarded_project_detail['project_id'];
		}
		if(isset($awarded_project_detail['fulltime_project_id'])){
			$project_id = $awarded_project_detail['fulltime_project_id'];
		}
		/* $featured_upgrade_expired_status = 0;
		$urgent_upgrade_expired_status = 0;
		 */
		
		
		if($project_type == 'fulltime'){
			$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
			$award_bid_table_name = 'fulltime_projects_awarded_tracking';
			$awards_not_completed_table_name = 'fulltime_projects_awards_not_completed_tracking';
			$award_project_table = 'projects_open_bidding';
			$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['application_award_expiration_date <= ' => date('Y-m-d H:i:s'),'fulltime_project_id'=>$project_id])->result_array();
		}if($project_type == 'fixed'){
			$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
			$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
			$award_project_table = 'fixed_budget_projects_awarded';
			$awards_not_completed_table_name = 'fixed_budget_projects_awards_not_completed_tracking'; 
			$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['project_award_expiration_date <= ' => date('Y-m-d H:i:s'),'project_id'=>$project_id])->result_array();
		}if($project_type == 'hourly'){
			$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
			$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
			$award_project_table = 'hourly_rate_based_projects_awarded';
			$awards_not_completed_table_name = 'hourly_rate_based_projects_awards_not_completed_tracking';
			$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['project_award_expiration_date <= ' => date('Y-m-d H:i:s'),'project_id'=>$project_id])->result_array();
		}
		
		
		if(!empty($awarded_bid_data)){
			foreach($awarded_bid_data as $awarded_bid_key=>$awarded_bid_value) {
	
				if($project_type == 'fulltime'){
					
					$bid_data['application_description'] = $awarded_bid_value['application_description'];
					$bid_data['application_date'] = $awarded_bid_value['application_date'];
					$bid_data['initial_requested_salary'] = $awarded_bid_value['awarded_salary'];
					
					$award_expiration_bid_data['application_description'] = $awarded_bid_value['application_description'];
					$award_expiration_bid_data['application_date'] = $awarded_bid_value['application_date'];
					$award_expiration_bid_data['application_awarded_date'] = $awarded_bid_value[' application_awarded_date'];
					$award_expiration_bid_data['application_award_expiration_date'] = $awarded_bid_value['application_award_expiration_date'];
					//$award_expiration_bid_data['application_award_decline_date'] = date('Y-m-d H:i:s');
					$award_expiration_bid_data['awarded_salary'] = $awarded_bid_value['awarded_salary'];
					
					
				}
				if($project_type == 'fixed'){
					
					$bid_data['bid_description'] = $awarded_bid_value['bid_description'];
					$bid_data['bid_date'] = $awarded_bid_value['bid_date'];
					$bid_data['initial_bidded_amount'] = $awarded_bid_value['awarded_amount'];
					$bid_data['project_delivery_period'] = $awarded_bid_value['project_delivery_period'];
					
					$award_expiration_bid_data['bid_description'] = $awarded_bid_value['bid_description'];
					$award_expiration_bid_data['bid_date'] = $awarded_bid_value['bid_date'];
					$award_expiration_bid_data['project_awarded_date'] = $awarded_bid_value['project_awarded_date'];
					$award_expiration_bid_data['project_award_expiration_date'] = $awarded_bid_value['project_award_expiration_date'];
					//$award_expiration_bid_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
					$award_expiration_bid_data['awarded_amount'] = $awarded_bid_value['awarded_amount'];
					$award_expiration_bid_data['project_delivery_period'] = $awarded_bid_value['project_delivery_period'];
					
					
					
				}if($project_type== 'hourly'){
					$bid_data['bid_description'] = $awarded_bid_value['bid_description'];
					$bid_data['bid_date'] = $awarded_bid_value['bid_date'];
					$bid_data['project_delivery_hours'] = $awarded_bid_value['awarded_hours'];
					$bid_data['initial_bidded_hourly_rate'] = $awarded_bid_value['awarded_hourly_rate'];
					
					$award_expiration_bid_data['bid_description'] = $awarded_bid_value['bid_description'];
					$award_expiration_bid_data['bid_date'] = $awarded_bid_value['bid_date'];
					$award_expiration_bid_data['project_awarded_date'] = $awarded_bid_value['project_awarded_date'];
					$award_expiration_bid_data['project_award_expiration_date'] = $awarded_bid_value['project_award_expiration_date'];
					//$award_expiration_bid_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
					$award_expiration_bid_data['awarded_hours'] = $awarded_bid_value['awarded_hours'];
					$award_expiration_bid_data['awarded_hourly_rate'] = $awarded_bid_value['awarded_hourly_rate'];
					
					
				}
				if($project_type == 'fulltime') {
					$bid_data['fulltime_project_id'] = $awarded_bid_value['fulltime_project_id'];
					$bid_data['employer_id'] = $awarded_bid_value['employer_id'];
					$bid_data['employee_id'] = $awarded_bid_value['employee_id'];
					$bid_data['application_bidding_dropdown_option'] = $awarded_bid_value['application_bidding_dropdown_option'];
					
					$award_expiration_bid_data['fulltime_project_id'] = $awarded_bid_value['fulltime_project_id'];
					$award_expiration_bid_data['employer_id'] = $awarded_bid_value['employer_id'];
					$award_expiration_bid_data['employee_id'] = $awarded_bid_value['employee_id'];
					$award_expiration_bid_data['application_bidding_dropdown_option'] = $awarded_bid_value['application_bidding_dropdown_option'];
					$award_expiration_bid_data['award_not_completed_reason'] = 'award_period_expired';
				} else {
					$bid_data['project_id'] = $awarded_bid_value['project_id'];
					$bid_data['project_owner_id'] = $awarded_bid_value['project_owner_id'];
					$bid_data['bidder_id'] = $awarded_bid_value['winner_id'];
					$bid_data['bidding_dropdown_option'] = $awarded_bid_value['bidding_dropdown_option'];
					
					$award_expiration_bid_data['project_id'] = $awarded_bid_value['project_id'];
					$award_expiration_bid_data['project_owner_id'] = $awarded_bid_value['project_owner_id'];
					$award_expiration_bid_data['winner_id'] = $awarded_bid_value['winner_id'];
					$award_expiration_bid_data['bidding_dropdown_option'] = $awarded_bid_value['bidding_dropdown_option'];
					$award_expiration_bid_data['award_not_completed_reason'] = 'award_period_expired';
				}
				
				
				if($project_type == 'fulltime'){ 
					$check_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_value['fulltime_project_id'],'employer_id'=>$awarded_bid_value['employer_id'],'employee_id'=>$awarded_bid_value['employee_id']])->from($bid_table_name)->count_all_results();
				} else {
					$check_bid_data_exists = $this->db->where(['project_id' => $awarded_bid_value['project_id'],'project_owner_id'=>$awarded_bid_value['project_owner_id'],'bidder_id'=>$awarded_bid_value['winner_id']])->from($bid_table_name)->count_all_results();
				}
				
				$this->db->insert ($awards_not_completed_table_name, $award_expiration_bid_data);// track the bid if award time is expiration
				
				if($check_bid_data_exists == 0){
					$this->db->insert ($bid_table_name, $bid_data);
					
					if($project_type == 'fulltime') {
						$bidder_data = $this->db->get_where('users', ['user_id' =>$awarded_bid_value['employee_id']])->row_array();
					} else {
						$bidder_data = $this->db->get_where('users', ['user_id' =>$awarded_bid_value['winner_id']])->row_array();
					}
					$sp_profile_name = $bidder_data['profile_name'];
					$sp_profile_url_link = VPATH.$sp_profile_name;
					
					if(isset($awarded_project_detail['employer_id'])){
						$po_id = $awarded_project_detail['employer_id'];
						}else{
						$po_id = $awarded_project_detail['project_owner_id'];
					}
					
					
					$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$po_id)->get()->row_array();
					$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
					$po_profile_url_link = VPATH.$awarded_project_detail['profile_name'];
					
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
					if($project_type == 'fixed') {
						$sp_award_bid_expiration_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message');
					} else if($project_type == 'hourly') {
						$sp_award_bid_expiration_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message');
					}else if($project_type == 'fulltime') {
						$sp_award_bid_expiration_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_awarded_bid_expiration_time_passed_user_activity_log_displayed_message');
					}
					
					$sp_award_bid_expiration_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{po_profile_url_link}','{user_first_name_last_name_or_company_name}'),array($project_url_link,htmlspecialchars($awarded_project_detail['project_title'], ENT_QUOTES),$po_profile_url_link,$po_name),$sp_award_bid_expiration_activity_log_message);
					
					if($project_type == 'fulltime'){
						user_display_log($sp_award_bid_expiration_activity_log_message,$awarded_bid_value['employee_id']); // activity log message for service provider
					}else{	
						user_display_log($sp_award_bid_expiration_activity_log_message,$awarded_bid_value['winner_id']); // activity log message for service provider
					}
					
					if($project_type == 'fixed') {
						if(($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y')) { 
							if($bidder_data['gender'] == 'M') { 
							
								if($bidder_data['is_authorized_physical_person'] == 'Y'){
									$po_award_bid_expiration_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_app_male_expiration_time_passed_user_activity_log_displayed_message');
								}else{
							
									$po_award_bid_expiration_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_male_expiration_time_passed_user_activity_log_displayed_message');
								}
							} else {
								if($bidder_data['is_authorized_physical_person'] == 'Y'){
									$po_award_bid_expiration_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_app_female_expiration_time_passed_user_activity_log_displayed_message');
								}else{
									$po_award_bid_expiration_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_female_expiration_time_passed_user_activity_log_displayed_message');
								}
							}
						} else {
							$po_award_bid_expiration_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_bid_sp_company_expiration_time_passed_user_activity_log_displayed_message');
						}
					}else if($project_type == 'fulltime') {
						if(($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y')) { 
							if($bidder_data['gender'] == 'M') {
							
								if($bidder_data['is_authorized_physical_person'] == 'Y'){
									$po_award_bid_expiration_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_app_male_expiration_time_passed_user_activity_log_displayed_message');
								}else{
									$po_award_bid_expiration_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_bid_employee_male_expiration_time_passed_user_activity_log_displayed_message');
								}
							} else {
								if($bidder_data['is_authorized_physical_person'] == 'Y'){
									$po_award_bid_expiration_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_app_female_expiration_time_passed_user_activity_log_displayed_message');
								}else{
									$po_award_bid_expiration_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_bid_employee_female_expiration_time_passed_user_activity_log_displayed_message');
								
								}
							}
						} else {
							$po_award_bid_expiration_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_bid_employee_company_expiration_time_passed_user_activity_log_displayed_message');
						}
					} else if($project_type == 'hourly') {
						if(($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y')) { 
							if($bidder_data['gender'] == 'M') {
							
								if($bidder_data['is_authorized_physical_person'] == 'Y'){
									$po_award_bid_expiration_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_bid_sp_company_app_male_expiration_time_passed_user_activity_log_displayed_message');
								}else{
									$po_award_bid_expiration_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_bid_sp_male_expiration_time_passed_user_activity_log_displayed_message');
								}
							} else {
								if($bidder_data['is_authorized_physical_person'] == 'Y'){
									$po_award_bid_expiration_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_bid_sp_company_app_female_expiration_time_passed_user_activity_log_displayed_message');
								}else{
									$po_award_bid_expiration_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_bid_sp_female_expiration_time_passed_user_activity_log_displayed_message');
								}
							}
						} else {
							$po_award_bid_expiration_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_bid_sp_company_expiration_time_passed_user_activity_log_displayed_message');
						}
					}
					
					$name = (($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y')) ?  $bidder_data['first_name']." ".$bidder_data['last_name'] : $bidder_data['company_name'];
					
					if(($bidder_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bidder_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bidder_data['is_authorized_physical_person'] == 'Y')) {
						$po_award_bid_expiration_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($awarded_project_detail['project_title'], ENT_QUOTES),$name,$sp_profile_url_link),$po_award_bid_expiration_activity_log_message);
					} else {
						$po_award_bid_expiration_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_company_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($awarded_project_detail['project_title'], ENT_QUOTES),$name,$sp_profile_url_link),$po_award_bid_expiration_activity_log_message);
					}
					if($project_type == 'fulltime'){ 
						if(isset($awarded_project_detail['employer_id'])){
							$po_id = $awarded_project_detail['employer_id'];
						}else{
							$po_id = $awarded_project_detail['project_owner_id'];
						}
						
						user_display_log($po_award_bid_expiration_activity_log_message,$po_id); // activity log message for service provider
					}else{
					//die("ddsfd");
						user_display_log($po_award_bid_expiration_activity_log_message,$awarded_project_detail['project_owner_id']); // activity log message for service provider
					}
					
				}
				if($project_type == 'fulltime'){ 
					$this->db->delete($award_bid_table_name, ['fulltime_project_id' => $awarded_bid_value['fulltime_project_id'],'employee_id'=>$awarded_bid_value['employee_id']]); // remove entry from award tracking table
				} else {
					$this->db->delete($award_bid_table_name, ['project_id' => $awarded_bid_value['project_id'],'winner_id'=>$awarded_bid_value['winner_id']]); // remove entry from award tracking table
				}
			}
		
			if($project_type == 'fulltime'){ 
				$count_awarded_bid_project_exists = $this->db->where(['fulltime_project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
			} else {
				$count_awarded_bid_project_exists = $this->db->where(['project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
			}
			
			
			
				if((!empty($awarded_project_detail) && $count_awarded_bid_project_exists == 0 && ($project_status == 'awaiting_moderation' || $project_status == 'awarded') && $project_type != 'fulltime') || (!empty($awarded_project_detail) &&  $project_type == 'fulltime' && $project_status == 'open_for_bidding' )){
					
					
					$profile_name = $awarded_project_detail['profile_name'];
					unset($awarded_project_detail['id']);
					unset($awarded_project_detail['profile_name']);
					
					$project_expiration_date = $awarded_project_detail['project_expiration_date']!= NULL ? strtotime ($awarded_project_detail['project_expiration_date']) : 0;
					//$project_status = $this->config->item('project_status_awarded');
					if($project_expiration_date > time()){
						$project_bidding_expired_table = 'projects_open_bidding';
					} else {
						
						if($project_type == 'fixed'){
							$project_bidding_expired_table = 'fixed_budget_projects_expired';
						}
						if($project_type == 'hourly'){
							$project_bidding_expired_table = 'hourly_rate_based_projects_expired';
						}
						if($project_type == 'fulltime'){
							$project_bidding_expired_table = 'fulltime_projects_expired';
						}
					
					}
					
					if($project_type == 'fulltime' && $project_expiration_date <= time()){
						
						$expired_data['fulltime_project_id'] = $awarded_project_detail['project_id'];
						$expired_data['employer_id'] = $awarded_project_detail['project_owner_id'];
						$expired_data['fulltime_project_posting_date'] = $awarded_project_detail['project_posting_date'];
						$expired_data['fulltime_project_expiration_date'] = $awarded_project_detail['project_expiration_date'];
						$expired_data['fulltime_project_title'] = $awarded_project_detail['project_title'];
						$expired_data['fulltime_project_description'] = $awarded_project_detail['project_description'];
						$expired_data['min_salary'] = $awarded_project_detail['min_budget'];
						$expired_data['max_salary'] = $awarded_project_detail['max_budget'];
						$expired_data['locality_id'] = $awarded_project_detail['locality_id'];
						$expired_data['county_id'] = $awarded_project_detail['county_id'];
						$expired_data['postal_code_id'] = $awarded_project_detail['postal_code_id'];
						$expired_data['confidential_dropdown_option_selected'] = $awarded_project_detail['confidential_dropdown_option_selected'];
						$expired_data['not_sure_dropdown_option_selected'] = $awarded_project_detail['not_sure_dropdown_option_selected'];
						$expired_data['escrow_payment_method'] = $awarded_project_detail['escrow_payment_method'];
						$expired_data['offline_payment_method'] = $awarded_project_detail['offline_payment_method'];
						$expired_data['sealed'] = $awarded_project_detail['sealed'];
						$expired_data['hidden'] = $awarded_project_detail['hidden'];
						$expired_data['views'] = $awarded_project_detail['views'];
						$expired_data['revisions'] = $awarded_project_detail['revisions'];
						$this->db->insert ($project_bidding_expired_table, $expired_data);
						$this->db->delete($award_project_table, ['project_id' => $project_id]);
					}else if($project_type != 'fulltime'){
						$awarded_project_data['project_id'] = $awarded_project_detail['project_id'];
						$awarded_project_data['project_owner_id'] = $awarded_project_detail['project_owner_id'];
						$awarded_project_data['project_posting_date'] = $awarded_project_detail['project_posting_date'];
						$awarded_project_data['project_expiration_date'] = $awarded_project_detail['project_expiration_date'];
						$awarded_project_data['project_title'] = $awarded_project_detail['project_title'];
						$awarded_project_data['project_description'] = $awarded_project_detail['project_description'];
						$awarded_project_data['min_budget'] = $awarded_project_detail['min_budget'];
						$awarded_project_data['max_budget'] = $awarded_project_detail['max_budget'];
						$awarded_project_data['locality_id'] = $awarded_project_detail['locality_id'];
						$awarded_project_data['county_id'] = $awarded_project_detail['county_id'];
						$awarded_project_data['postal_code_id'] = $awarded_project_detail['postal_code_id'];
						$awarded_project_data['confidential_dropdown_option_selected'] = $awarded_project_detail['confidential_dropdown_option_selected'];
						$awarded_project_data['not_sure_dropdown_option_selected'] = $awarded_project_detail['not_sure_dropdown_option_selected'];
						$awarded_project_data['escrow_payment_method'] = $awarded_project_detail['escrow_payment_method'];
						$awarded_project_data['offline_payment_method'] = $awarded_project_detail['offline_payment_method'];
						$awarded_project_data['sealed'] = $awarded_project_detail['sealed'];
						$awarded_project_data['hidden'] = $awarded_project_detail['hidden'];
						$awarded_project_data['views'] = $awarded_project_detail['views'];
						$awarded_project_data['revisions'] = $awarded_project_detail['revisions'];
						$this->db->insert ($project_bidding_expired_table, $awarded_project_data);
						$this->db->delete($award_project_table, ['project_id' => $project_id]);
					}

					
					
					$this->db->select('pd.project_id,pd.project_expiration_date,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
					
					$this->db->from("projects_open_bidding as pd");
					$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
					$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
					$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
					$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
					$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
					
					$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
					$this->db->where('pd.project_id',$project_id);
					
					$project_result = $this->db->get();
					$project_info = $project_result->row_array();
					
					$update_upgrade = array();
					if(!empty($project_info)){
						$featured_max = 0;
						$urgent_max = 0;
						$project_expiration_date = $project_info['project_expiration_date']!= NULL ? strtotime ($project_info['project_expiration_date']) : 0;
						if(!empty($project_info['featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_info['featured_upgrade_end_date'];
						}
						if(!empty($project_info['bonus_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_info['bonus_featured_upgrade_end_date'];
						}
						if(!empty($project_info['membership_include_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_info['membership_include_featured_upgrade_end_date'];
						}
						if(!empty($expiration_featured_upgrade_date_array)){
							$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
						}
						if($project_expiration_date > time()  && $featured_max > time()){
							$update_upgrade['featured'] = 'Y';
							$featured_upgrade_expired_status = 1;
				
						}
						
						if(!empty($project_info['urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_info['urgent_upgrade_end_date'];
						}
						if(!empty($project_info['bonus_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_info['bonus_urgent_upgrade_end_date'];
						}
						if(!empty($project_info['membership_include_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_info['membership_include_urgent_upgrade_end_date'];
						}
						if(!empty($expiration_urgent_upgrade_date_array)){
							$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
						}
						if($project_expiration_date > time()  && $urgent_max > time()){
							$update_upgrade['urgent'] = 'Y';
							
							$urgent_upgrade_expired_status = 1;
						}
					}
					if(!empty($update_upgrade)){
						$this->db->update('projects_open_bidding',$update_upgrade, array("project_id" => $project_id));
					}
					
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					
					
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
					
					
					
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
					if($project_bidding_expired_table != 'projects_open_bidding'){	
					
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].DIRECTORY_SEPARATOR);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$project_owner_attachments_dir);
						
					}else{
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].DIRECTORY_SEPARATOR);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$project_owner_attachments_dir);
						
						
						if($project_status == 'awarded' && $featured_upgrade_expired_status == 1){
								
							$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;	
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$project_featured_upgrade_cover_picture);
							
							
							
							$project_cover_picture_data = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $awarded_project_detail['project_id']))->row_array();
				
							if(!empty($project_cover_picture_data) && !empty($project_cover_picture_data['project_cover_picture_name']) ){
								
								
								 $source_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$awarded_project_detail['project_id'].$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
								
								
								
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$project_featured_upgrade_cover_picture))){
									
									$file_size = $this->ftp->get_filesize($source_cover_picture_attachment_path);
									if($file_size != '-1')
									{
										$destination_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
										$this->ftp->move($source_cover_picture_attachment_path, $destination_cover_picture_attachment_path);
									}
								}
							}
						}
					}
					if($project_type != 'fulltime'){
						$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$awarded_project_detail['project_id'].$project_owner_attachments_dir;
					}else{
						$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$project_owner_attachments_dir;
					}
					
					$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].DIRECTORY_SEPARATOR;
					
					$source_list = $this->ftp->list_files($source_path);
					
					
					
					if(!empty($source_list)) {
						foreach($source_list as $path) {
							$arr = explode('/', $path);
							$file_size = $this->ftp->get_filesize($path);
							if($file_size != '-1') {
								
								if($project_bidding_expired_table != 'projects_open_bidding'  && end($arr) != 'index.php'){	
									
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$project_owner_attachments_dir.end($arr);
									$this->ftp->move($path, $destination_path);
									
								}else{
									if(end($arr) != 'index.php'){
										$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$project_owner_attachments_dir.end($arr);
										$this->ftp->move($path, $destination_path);
									}
								}
							}
						}
					}
					
		
					$bid_attachments = $this->db->where('project_id', $awarded_project_detail['project_id'])->get('projects_active_bids_users_attachments_tracking')->result_array ();
				
					if(!empty($bid_attachments)){
					foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
						/* if($project_type != 'fulltime'){	
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						
						}else{
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						} */
						
						$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'];
						
						if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
						
							
							if($project_bidding_expired_table != 'projects_open_bidding'){	
								
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
							}else{
							
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir);
								
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
								}
								if($project_type != 'fulltime'){	
									$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
								}else{
									$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
								}
								
								
								$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
								if($file_size != '-1')
								{
									if($project_bidding_expired_table != 'projects_open_bidding'){	
										$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									}else{
										$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									
									}
									$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
									
								}
							}
						} 
					
					}
					
				if($project_type != 'fulltime'){	
					// remov entry from open bidding table
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$awarded_project_detail['project_id']))) {
						$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$awarded_project_detail['project_id']);
					}
				}else{
					// remov entry from open bidding table
					
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id']))) {
						$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$awarded_project_detail['project_id']);
					}
				}
			} 
		}
		
	}
	
	/**
		* This function is used to fetch the active bids of service provider my projects section.
	*/
	public function get_sp_active_bids_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$open_project_upgrade_fields = 'featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date';
		$not_open_project_upgrade_fields = '"0" as featured_upgrade_end_date,"0" as bonus_featured_upgrade_end_date,"0" as urgent_upgrade_end_date,"0" as bonus_urgent_upgrade_end_date,"0" as membership_include_featured_upgrade_end_date,"0" as membership_include_urgent_upgrade_end_date';

		$project_fields = "pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date, '' as project_cancellation_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		$fulltime_project_fields = "pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,'' as project_cancellation_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		$fulltime_cancelled_project_fields = "pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,pd.fulltime_project_cancellation_date as project_cancellation_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fixed_project_bid_fields = "bd.initial_bidded_amount,bd.bidding_dropdown_option,bd.bid_description,bd.bid_date,bd.project_delivery_period";
		$hourly_project_bid_fields = "bd.initial_bidded_hourly_rate as initial_bidded_amount,bd.bidding_dropdown_option,bd.bid_description,bd.bid_date,bd.project_delivery_hours as project_delivery_period";
		$fulltime_project_bid_fields = "bd.initial_requested_salary as initial_bidded_amount,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.application_description as bid_description,bd.application_date as bid_date,'0' as project_delivery_period";
		
		
		### fetch fields from awarded bid table whose award expiration time is passed
		
		$fixed_project_awarded_bid_fields = "bd.awarded_amount as initial_bidded_amount,bd.bidding_dropdown_option,bd.bid_description,bd.bid_date,bd.project_delivery_period";
		$hourly_project_awarded_bid_fields = "bd.awarded_hourly_rate as initial_bidded_amount,bd.bidding_dropdown_option,bd.bid_description,bd.bid_date,bd.awarded_hours as project_delivery_period";
		$fulltime_project_awarded_bid_fields = "bd.awarded_salary as initial_bidded_amount,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.application_description as bid_description,bd.application_date as bid_date,'0' as project_delivery_period";
		
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_project_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_for_bidding" as project_status,featured,urgent',false);
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->where('pd.project_type','fixed');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$fixed_budget_open_bidding_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_for_bidding" as project_status,featured,urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$fixed_budget_open_bidding_projects_awarded_bids_query = $this->db->get_compiled_select();
		

		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_for_bidding" as project_status,featured,urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->where('pd.project_type','hourly');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$hourly_budget_open_bidding_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_for_bidding" as project_status,featured,urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$hourly_open_bidding_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_for_bidding" as project_status,featured,urgent');
		$this->db->from('fulltime_projects_open_bidding_active_applications as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->where('pd.project_type','fulltime');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$fulltime_open_bidding_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_for_bidding" as project_status,featured,urgent');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->where('pd.project_type','fulltime');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$fulltime_open_bidding_projects_awarded_bids_query = $this->db->get_compiled_select();

		
		############################# EXPIRED PROJECT######################
		$this->db->select($fixed_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('fixed_budget_projects_expired as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_expired_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_expired as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_expired_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('hourly_rate_based_projects_expired as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_expired_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_expired as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_expired_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_bid_fields.','.$fulltime_project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fulltime_projects_open_bidding_active_applications as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_expired_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$fulltime_project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_expired_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		############################# AWARDED PROJECT######################
		$this->db->select($fixed_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('fixed_budget_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_awarded_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_awarded_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('hourly_rate_based_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_awarded_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_awarded_projects_awarded_bids_query = $this->db->get_compiled_select();

		############################# IN PROGRESS PROJECT######################
		$this->db->select($fixed_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"in_progress" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_in_progress_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_in_progress_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"in_progress" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_in_progress_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"in_progress" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_in_progress_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		############################# CANCELLED BY PO PROJECT######################
		$this->db->select($fixed_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('fixed_budget_projects_cancelled as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_cancelled_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_cancelled as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_cancelled_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('hourly_rate_based_projects_cancelled as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_cancelled_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_cancelled as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_cancelled_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_bid_fields.','.$fulltime_cancelled_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fulltime_projects_open_bidding_active_applications as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_cancelled_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$fulltime_cancelled_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_cancelled_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		
		############################# CANCELLED BY ADMIN PROJECT######################
		$this->db->select($fixed_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('fixed_budget_projects_cancelled_by_admin as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_cancelled_by_admin_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_cancelled_by_admin as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_cancelled_by_admin_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('hourly_rate_based_projects_cancelled_by_admin as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_cancelled_by_admin_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_cancelled_by_admin as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_cancelled_by_admin_projects_awarded_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_bid_fields.','.$fulltime_cancelled_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fulltime_projects_open_bidding_active_applications as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_cancelled_by_admin_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$fulltime_cancelled_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_cancelled_by_admin_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		############################# COMPLETED PROJECT ######################
		$this->db->select($fixed_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"completed" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_completed_projects_active_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_completed_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"completed" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_open_bidding_active_bids as bd');
		$this->db->where('bd.bidder_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_completed_projects_active_bids_query = $this->db->get_compiled_select();

		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"completed" as project_status,"N" as featured,"N" as urgent');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date <',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_completed_projects_awarded_bids_query = $this->db->get_compiled_select();

		$union_table_name = [
			$fixed_budget_open_bidding_projects_active_bids_query,
			$fixed_budget_open_bidding_projects_awarded_bids_query,
			$hourly_budget_open_bidding_projects_active_bids_query,
			$hourly_open_bidding_projects_awarded_bids_query,
			$fulltime_open_bidding_projects_active_bids_query,
			$fulltime_open_bidding_projects_awarded_bids_query,
			$fixed_budget_expired_projects_active_bids_query,
			$fixed_budget_expired_projects_awarded_bids_query,
			$hourly_budget_expired_projects_active_bids_query,
			$hourly_expired_projects_awarded_bids_query,
			$fulltime_expired_projects_active_bids_query,
			$fulltime_expired_projects_awarded_bids_query,
			$fixed_budget_awarded_projects_active_bids_query,
			$fixed_budget_awarded_projects_awarded_bids_query,
			$hourly_budget_awarded_projects_active_bids_query,
			$hourly_awarded_projects_awarded_bids_query,
			$fixed_budget_in_progress_projects_active_bids_query,
			$fixed_budget_in_progress_projects_awarded_bids_query,
			$hourly_budget_in_progress_projects_active_bids_query,
			$hourly_in_progress_projects_awarded_bids_query,
			$fixed_budget_cancelled_projects_active_bids_query,
			$fixed_budget_cancelled_projects_awarded_bids_query,
			$hourly_budget_cancelled_projects_active_bids_query,
			$hourly_cancelled_projects_awarded_bids_query,
			$fulltime_cancelled_projects_active_bids_query,
			$fulltime_cancelled_projects_awarded_bids_query,
			$fixed_budget_cancelled_by_admin_projects_active_bids_query,
			$fixed_budget_cancelled_by_admin_projects_awarded_bids_query,
			$hourly_budget_cancelled_by_admin_projects_active_bids_query,
			$hourly_cancelled_by_admin_projects_awarded_bids_query,
			$fulltime_cancelled_by_admin_projects_active_bids_query,
			$fulltime_cancelled_by_admin_projects_awarded_bids_query,
			$fixed_budget_completed_projects_active_bids_query,
			$fixed_budget_completed_projects_awarded_bids_query,
			$hourly_budget_completed_projects_active_bids_query,
			$hourly_completed_projects_awarded_bids_query
		];

		$active_bid_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY bid_date DESC LIMIT '.$limit_range);
		$result  = $active_bid_query->result_array();
		// pre($result);
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
	
		return ['data' => $result, 'total' => $total_rec];
	
	}
	/**
		* This function is used to fetch the hired list of service provider my projects section.
		* used to display projects listing in employee hired tab on dashboard my projects section and my projects page
	*/
	public function get_employee_hired_listing_my_projects($user_id,$start = '', $limit = '') {
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}

		$open_project_upgrade_fields = 'pd.featured,pd.urgent,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date';
		$not_open_project_upgrade_fields = '"N" as featured, "N" as urgent,"0" as featured_upgrade_end_date,"0" as bonus_featured_upgrade_end_date,"0" as urgent_upgrade_end_date,"0" as bonus_urgent_upgrade_end_date,"0" as membership_include_featured_upgrade_end_date,"0" as membership_include_urgent_upgrade_end_date';

		$project_fields = "pd.project_owner_id,pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		$expired_project_fields = $cancelled_project_fields = "pd.employer_id as project_owner_id,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		$fulltime_project_in_progress_bid_fields = "employee_hired_on as project_start_date,bd.initial_fulltime_project_agreed_salary as initial_bid_value,'0' as initial_project_agreed_value,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.initial_application_description as initial_bid_description,'0' as initial_project_agreed_delivery_period,'0' as initial_project_agreed_hourly_rate";
		############################# HIRED PROJECT ######################
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fulltime_project_in_progress_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_bidding" as project_status',false);

		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.project_id and employer_id_of_disputed_fulltime_project =  pd.project_owner_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');



		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$fulltime_projects_hired_list_query = $this->db->get_compiled_select();

		###### Expired
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$expired_project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.fulltime_project_id and employer_id_of_disputed_fulltime_project =  pd.employer_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_projects_expired_hired_list_query = $this->db->get_compiled_select();

		###### Cancelled
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$cancelled_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.fulltime_project_id and employer_id_of_disputed_fulltime_project =  pd.employer_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_projects_cancelled_hired_list_query = $this->db->get_compiled_select();

		###### Cancelled by admin
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$cancelled_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.fulltime_project_id and employer_id_of_disputed_fulltime_project =  pd.employer_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_projects_cancelled_by_admin_hired_list_query = $this->db->get_compiled_select();

		$hired_list_query = $this->db->query($fulltime_projects_hired_list_query. ' UNION ' . $fulltime_projects_expired_hired_list_query.' UNION '.$fulltime_projects_cancelled_hired_list_query.' UNION '.$fulltime_projects_cancelled_by_admin_hired_list_query.' ORDER BY project_start_date DESC LIMIT '.$limit_range);
		
		$result  = $hired_list_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;

		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
		* This function is used to fetch the awarded bids of service provider my projects section.
	*/
	public function get_sp_awarded_bids_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$open_project_upgrade_fields = 'pd.featured,pd.urgent,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date';
		$not_open_project_upgrade_fields = '"N" as featured, "N" as urgent,"0" as featured_upgrade_end_date,"0" as bonus_featured_upgrade_end_date,"0" as urgent_upgrade_end_date,"0" as bonus_urgent_upgrade_end_date,"0" as membership_include_featured_upgrade_end_date,"0" as membership_include_urgent_upgrade_end_date';

		$project_fields = "pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date,'' as project_cancellation_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		$expired_fulltime_project_fields = "pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,'' as project_cancellation_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		$cancelled_fulltime_project_fields = "pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,pd.fulltime_project_cancellation_date as project_cancellation_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fixed_project_awarded_bid_fields = "bd.awarded_amount,bd.bidding_dropdown_option,bd.bid_description,bd.bid_date,bd.project_delivery_period,bd.project_awarded_date,bd.project_award_expiration_date";
		
		$hourly_project_awarded_bid_fields = "bd.awarded_hourly_rate as awarded_amount,bd.bidding_dropdown_option,bd.bid_description,bd.bid_date,bd.awarded_hours as project_delivery_period,bd.project_awarded_date,bd.project_award_expiration_date";
		
		$fulltime_project_awarded_bid_fields = "bd.awarded_salary as awarded_amount,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.application_description as bid_description,bd.application_date as bid_date,'0' as project_delivery_period,bd.application_awarded_date as project_awarded_date,bd.application_award_expiration_date as project_award_expiration_date";
		
		############################# AWARDED PROJECT######################
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status',false);
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_awarded_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"awarded" as project_status');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_awarded as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_awarded_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_awarded_bid_fields.','.$project_fields.','.$open_project_upgrade_fields.',"open_bidding" as project_status');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');

		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
		$fulltime_awarded_projects_awarded_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$expired_fulltime_project_fields.','.$not_open_project_upgrade_fields.',"expired" as project_status');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_awarded_expired_projects_awarded_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$cancelled_fulltime_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_awarded_cancelled_projects_awarded_bids_query = $this->db->get_compiled_select();

		$this->db->select($fulltime_project_awarded_bid_fields.','.$cancelled_fulltime_project_fields.','.$not_open_project_upgrade_fields.',"cancelled" as project_status');
		$this->db->from('fulltime_projects_awarded_tracking as bd');
		$this->db->where('bd.application_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_awarded_cancelled_by_admin_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		############################# IN PROGRESS PROJECT######################
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"in_progress" as project_status');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_in_progress_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"in_progress" as project_status');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_in_progress_projects_awarded_bids_query = $this->db->get_compiled_select();

		############################# COMPLETED PROJECT ######################
		$this->db->select($fixed_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"completed" as project_status');
		$this->db->from('fixed_budget_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_completed_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_awarded_bid_fields.','.$project_fields.','.$not_open_project_upgrade_fields.',"completed" as project_status');
		$this->db->from('hourly_rate_based_projects_awarded_tracking as bd');
		$this->db->where('bd.project_award_expiration_date >=',date('Y-m-d H:i:s'));
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_completed_projects_awarded_bids_query = $this->db->get_compiled_select();
		
		$awarded_bid_query = $this->db->query($fixed_budget_awarded_projects_awarded_bids_query. ' UNION ' . $hourly_budget_awarded_projects_awarded_bids_query. ' UNION ' . $fixed_budget_in_progress_projects_awarded_bids_query. ' UNION ' . $hourly_budget_in_progress_projects_awarded_bids_query. ' UNION ' .$fixed_budget_completed_projects_awarded_bids_query.' UNION '.$hourly_completed_projects_awarded_bids_query.' UNION '.$fulltime_awarded_projects_awarded_bids_query.' UNION '.$fulltime_awarded_expired_projects_awarded_bids_query.' UNION '.$fulltime_awarded_cancelled_projects_awarded_bids_query.' UNION '.$fulltime_awarded_cancelled_by_admin_projects_awarded_bids_query.' ORDER BY project_awarded_date DESC LIMIT '.$limit_range);
		$result  = $awarded_bid_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
	
		return ['data' => $result, 'total' => $total_rec];
	
	}
	
	
	/**
		* This function is used to fetch the in_progress bids of service provider my projects section.
	*/
	public function get_sp_in_progress_bids_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$project_fields = "pd.project_owner_id,pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fixed_project_in_progress_bid_fields = "bd.winner_id,bd.project_start_date,bd.initial_bid_value,bd.initial_project_agreed_value,bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_delivery_period,'0' as initial_project_agreed_hourly_rate";
		
		$hourly_project_in_progress_bid_fields = "bd.winner_id,bd.project_start_date,initial_project_agreed_value as initial_bid_value,initial_project_agreed_value, bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_number_of_hours as initial_project_agreed_delivery_period,bd.initial_project_agreed_hourly_rate";
		
		/* $fulltime_project_in_progress_bid_fields = "bd.project_start_date,bd.initial_fulltime_project_agreed_salary as initial_bid_value,'0' as initial_project_agreed_value,bd.bidding_dropdown_option,bd.initial_application_description as initial_bid_description,'0' as initial_project_agreed_delivery_period,'0' as initial_project_agreed_hourly_rate"; */
		
		
		
		############################# IN PROGRESS PROJECT######################
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_project_in_progress_bid_fields.','.$project_fields.',"in_progress" as project_status',false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id',$user_id);
		
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left');
		 */
		
		$fixed_budget_in_progress_projects_in_progress_bids_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_in_progress_bid_fields.','.$project_fields.',"in_progress" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		
		$hourly_budget_in_progress_projects_in_progress_bids_query = $this->db->get_compiled_select();
		
		$in_progress_bid_query = $this->db->query($fixed_budget_in_progress_projects_in_progress_bids_query. ' UNION ' . $hourly_budget_in_progress_projects_in_progress_bids_query. ' ORDER BY project_start_date DESC LIMIT '.$limit_range);
		$result  = $in_progress_bid_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
	
		
		return ['data' => $result, 'total' => $total_rec];
	
	}
	
	
	/**
		* This function is used to fetch the in_complete bids of service provider my projects section.
	*/
	public function get_sp_incomplete_bids_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$project_fields = "pd.project_owner_id,pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fixed_project_in_complete_bid_fields = "bd.winner_id,bd.project_start_date,bd.initial_bid_value,bd.initial_project_agreed_value,bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_delivery_period,'0' as initial_project_agreed_hourly_rate";
		
		$hourly_project_in_complete_bid_fields = "bd.winner_id,bd.project_start_date,initial_project_agreed_value as initial_bid_value,initial_project_agreed_value, bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_number_of_hours as initial_project_agreed_delivery_period,bd.initial_project_agreed_hourly_rate";
		
		
		
		
		############################# IN PROGRESS PROJECT######################
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_project_in_complete_bid_fields.','.$project_fields.',"in_progress" as project_status',false);
		
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date');
		 */
		
		
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
	/* 	$this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		$fixed_budget_in_progress_projects_in_complete_bids_query = $this->db->get_compiled_select();
		
		##########################################################################################
		
		############################# IN COMPLETE PROJECT######################
		$this->db->select($fixed_project_in_complete_bid_fields.','.$project_fields.',"in_complete" as project_status');
		
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date');
		 */
		
		
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		/* 
		$this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		$fixed_budget_in_complete_projects_in_complete_bids_query = $this->db->get_compiled_select();
		
		
		############################# COMPLETED PROJECT######################
		$this->db->select($fixed_project_in_complete_bid_fields.','.$project_fields.',"in_complete" as project_status');
		
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		
		
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		$fixed_budget_completed_projects_in_complete_bids_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_project_in_complete_bid_fields.','.$project_fields.',"in_complete" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		
		$hourly_rate_in_complete_projects_in_complete_bids_query = $this->db->get_compiled_select();
		
		
		
		$union_table_name = [
			$fixed_budget_in_progress_projects_in_complete_bids_query,
			$fixed_budget_in_complete_projects_in_complete_bids_query,
			$fixed_budget_completed_projects_in_complete_bids_query,
			$hourly_rate_in_complete_projects_in_complete_bids_query
		];
		
		$in_complete_bid_query = $this->db->query(implode(' UNION ', $union_table_name) .  ' ORDER BY project_start_date DESC LIMIT '.$limit_range);
		$result  = $in_complete_bid_query->result_array();
		
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		/*  echo "<pre>";
		print_r($result);
		die;
		 */
		
		return ['data' => $result, 'total' => $total_rec];
	
	}
	
	/**
		* This function is used to fetch the completed bids of service provider my projects section.
	*/
	public function get_sp_completed_bids_listing_my_projects($user_id,$start = '', $limit = ''){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}	
		
		$project_fields = "pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		
		$fixed_project_completed_bid_fields = "bd.winner_id,bd.project_id,bd.project_winner_work_start_date,bd.project_winner_work_completion_date,bd.initial_bid_value,bd.initial_project_agreed_value,bd.total_project_amount,bd.bidding_dropdown_option,bd.initial_bid_description,'0' as initial_project_agreed_hourly_rate, '0' as initial_project_agreed_number_of_hours,'fixed' as project_type";
		############################# IN PROGRESS PROJECT######################
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_project_completed_bid_fields.','.$project_fields,false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
	/* 	$this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		$fixed_budget_completed_projects_completed_bids_query = $this->db->get_compiled_select();
		
		##############################################################################
		
		$this->db->select($fixed_project_completed_bid_fields.','.$project_fields,false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		$fixed_budget_in_progress_projects_completed_bids_query = $this->db->get_compiled_select();
		
		
		###################################################################################
		
		$hourly_project_completed_bid_fields = "bd.winner_id,bd.project_id,bd.project_winner_work_start_date,bd.project_winner_work_completion_date,'0' as initial_bid_value,bd.initial_project_agreed_value,bd.total_project_amount,bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_hourly_rate, bd.initial_project_agreed_number_of_hours, 'hourly' as project_type";
		$this->db->select($hourly_project_completed_bid_fields.','.$project_fields,false);
		
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date');
		 */
		
		
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		
		
		$hourly_rate_based_completed_projects_completed_bids_query = $this->db->get_compiled_select();
		
		
		########################################################################################
		
		// $hourly_project_completed_bid_fields = "bd.project_id,bd.project_winner_work_start_date,bd.project_winner_work_completion_date,'0' as initial_bid_value,bd.initial_project_agreed_value,bd.total_project_amount,bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_hourly_rate, bd.initial_project_agreed_number_of_hours, 'hourly' as project_type";
		$this->db->select($hourly_project_completed_bid_fields.','.$project_fields,false);
		$this->db->select('"0" as total_active_disputes');
		
		/* $this->db->select('closed_dispute.dispute_reference_id,closed_dispute.disputed_project_id,closed_dispute.sp_winner_id_of_disputed_project,closed_dispute.disputed_winner_id,closed_dispute.dispute_status,closed_dispute.dispute_end_date'); */
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		/* $this->db->join('(select dispute_reference_id,disputed_project_id,sp_winner_id_of_disputed_project,disputed_winner_id,dispute_end_date,dispute_status from '.$this->db->dbprefix .'fixed_budget_projects_closed_disputes where  sp_winner_id_of_disputed_project = "'.$user_id.'" order by dispute_end_date desc limit 0,1 ) as closed_dispute', 'closed_dispute.disputed_project_id = pd.project_id', 'left'); */
		$hourly_rate_based_in_progress_projects_completed_bids_query = $this->db->get_compiled_select();
		
		
		########################################################################################
		
		
		
		$completed_bid_query = $this->db->query($fixed_budget_completed_projects_completed_bids_query.' UNION '.$fixed_budget_in_progress_projects_completed_bids_query.' UNION '.$hourly_rate_based_completed_projects_completed_bids_query.' UNION '.$hourly_rate_based_in_progress_projects_completed_bids_query.' ORDER BY project_winner_work_completion_date DESC LIMIT '.$limit_range);
		$result  = $completed_bid_query->result_array();
		
		/* echo "<pre>";
		print_r($result);
		die; */
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	
	}
	

	/**
		* This function is used to fetch the all won projects of service provider .
	*/
	
	public function get_sp_won_project_listing_profile_page($user_id,$start = '', $limit = ''){
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		$project_fields = "pd.project_owner_id,pd.project_id,pd.project_title,pd.project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_budget,pd.max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.project_posting_date,pd.project_expiration_date,'' as project_cancellation_date,'' as cancelled_by_admin, pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fulltime_expired_project_fields = "pd.employer_id as project_owner_id,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,'' as project_cancellation_date,'' as cancelled_by_admin,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fulltime_cancelled_project_fields = "pd.employer_id as project_owner_id,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,pd.fulltime_project_cancellation_date as project_cancellation_date,'' as cancelled_by_admin,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fulltime_cancelled_by_admin_project_fields = "pd.employer_id as project_owner_id,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.fulltime_project_description as project_description,pd.sealed,pd.hidden,pd.project_type,pd.min_salary as min_budget,pd.max_salary as max_budget,pd.confidential_dropdown_option_selected,pd.not_sure_dropdown_option_selected,pd.fulltime_project_posting_date as project_posting_date,pd.fulltime_project_expiration_date as project_expiration_date,pd.fulltime_project_cancellation_date as project_cancellation_date,pd.cancelled_by_admin,pd.escrow_payment_method,pd.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code";
		
		$fixed_project_in_progress_bid_fields = "bd.winner_id,bd.project_start_date,'' as project_winner_work_completion_date,'' as  project_completion_method,bd.initial_bid_value,bd. 	bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_delivery_period,initial_project_agreed_value";
		
		$hourly_project_in_progress_bid_fields = "bd.winner_id,bd.project_start_date,'' as project_winner_work_completion_date,'' as  project_completion_method, bd.initial_project_agreed_value as initial_bid_value,bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_number_of_hours as initial_project_agreed_delivery_period,initial_project_agreed_hourly_rate";
		
		$hourly_project_in_complete_bid_fields = "bd.winner_id,bd.project_start_date,'' as project_winner_work_completion_date,'' as  project_completion_method, bd.initial_project_agreed_value as initial_bid_value,bd.bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_number_of_hours as initial_project_agreed_delivery_period,initial_project_agreed_hourly_rate";
		
		$fulltime_project_in_progress_bid_fields = "bd.employee_id as winner_id,bd.employee_hired_on as project_start_date,'' as project_winner_work_completion_date,'' as  project_completion_method,bd.initial_fulltime_project_agreed_salary as initial_bid_value,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.initial_application_description as initial_bid_description,,'0' as initial_project_agreed_delivery_period,'0' as initial_project_agreed_hourly_rate";
		
		$fixed_project_in_complete_bid_fields = "bd.winner_id,bd.project_start_date,'' as project_winner_work_completion_date,'' as  project_completion_method,bd.initial_bid_value,bd. 	bidding_dropdown_option,bd.initial_bid_description,bd.initial_project_agreed_delivery_period,initial_project_agreed_value";
		
		
		$fixed_project_completed_bid_fields = "bd.winner_id,bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_winner_work_completion_date,bd.project_completion_method,bd.initial_bid_value,bd.bidding_dropdown_option,bd.initial_bid_description,,'0' as initial_project_agreed_delivery_period,'0' as initial_project_agreed_hourly_rate";
		
		$hourly_project_completed_bid_fields = "bd.winner_id,bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_winner_work_completion_date,bd.project_completion_method,bd.initial_project_agreed_value as initial_bid_value,bd.bidding_dropdown_option,bd.initial_bid_description,,initial_project_agreed_number_of_hours as initial_project_agreed_delivery_period,initial_project_agreed_hourly_rate";
		
		// $hourly_project_completed_bid_fields = "bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_winner_work_completion_date,bd.project_completion_method,bd.initial_bid_value,bd.bidding_dropdown_option,bd.initial_bid_description,,initial_project_agreed_number_of_hours as initial_project_agreed_delivery_period,initial_project_agreed_hourly_rate";
		
		
		
		############################# IN PROGRESS PROJECT######################
		###### fixed budget progress - in-progress bid
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_project_in_progress_bid_fields.','.$project_fields.',"in_progress" as project_status',false);
		
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_in_progress_projects_in_progress_bids_query = $this->db->get_compiled_select();
		####### hourly rate based porject progress - in-progress bid
		$this->db->select($hourly_project_in_progress_bid_fields.','.$project_fields.',"in_progress" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_in_progress_projects_in_progress_bids_query = $this->db->get_compiled_select();
		
		############################# INCOMPLTE PROJECT######################
		###### fixed budget incomplete - in-complete bid
		$this->db->select($fixed_project_in_complete_bid_fields.','.$project_fields.',"incomplete" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_budget_in_complete_projects_in_complete_bids_query = $this->db->get_compiled_select();
		
		####### hourly rate based porject incomplete - in-complete bid
		$this->db->select($hourly_project_in_complete_bid_fields.','.$project_fields.',"incomplete" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_budget_in_complete_projects_in_complete_bids_query = $this->db->get_compiled_select();
		
		###### Fulltime project expired
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$fulltime_expired_project_fields.',"expired" as project_status');
		//$this->db->select('"0" as total_active_disputes');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.fulltime_project_id and employer_id_of_disputed_fulltime_project =  pd.employer_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');		

		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_hired_expired_projects_hired_query = $this->db->get_compiled_select();
		###### Fulltime project cancelled
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$fulltime_cancelled_project_fields.',"cancelled" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.fulltime_project_id and employer_id_of_disputed_fulltime_project =  pd.employer_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');	
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_hired_cancelled_projects_hired_query = $this->db->get_compiled_select();
		###### Fulltime project cancelled by admin
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$fulltime_cancelled_by_admin_project_fields.',"cancelled" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.fulltime_project_id and employer_id_of_disputed_fulltime_project =  pd.employer_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');	
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_hired_cancelled_by_admin_projects_hired_query = $this->db->get_compiled_select();
		##### open bidding
		$this->db->select($fulltime_project_in_progress_bid_fields.','.$project_fields.',"open_bidding" as project_status');
		//$this->db->select('"0" as total_active_disputes');

		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_active_disputes where disputed_fulltime_project_id = pd.project_id and employer_id_of_disputed_fulltime_project =  pd.project_owner_id and employee_winner_id_of_disputed_fulltime_project = "'.$user_id.'") as total_active_disputes');		


		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id',$user_id);
		$this->db->where('pd.hidden','N');
		//$this->db->where('pd.sealed','N');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fulltime_hired_projects_hired_query = $this->db->get_compiled_select();
		##### fixed budget completed
		$this->db->select($fixed_project_completed_bid_fields.','.$project_fields.',"completed" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_completed_projects_completed_bids_query = $this->db->get_compiled_select();
		##### fixed budget progress - completed bid
		$this->db->select($fixed_project_completed_bid_fields.','.$project_fields.',"completed" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$fixed_in_progress_projects_completed_bids_query = $this->db->get_compiled_select();
		##### hourly rate based porject completed - completed bid
		$this->db->select($hourly_project_completed_bid_fields.','.$project_fields.',"completed" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_completed_projects_completed_bids_query = $this->db->get_compiled_select();
		##### hourly rate based project progress - in-progress
		$this->db->select($hourly_project_completed_bid_fields.','.$project_fields.',"completed" as project_status');
		$this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_disputes where disputed_project_id = pd.project_id and project_owner_id_of_disputed_project =  pd.project_owner_id and sp_winner_id_of_disputed_project = "'.$user_id.'") as total_active_disputes');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id',$user_id);
		$this->db->where('pd.hidden','N');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->join('counties', 'counties.id = pd.county_id', 'left');
		$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
		$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
		$hourly_in_progress_projects_completed_bids_query = $this->db->get_compiled_select();
		
		
		
		
		$tables_name_array = [
			$fixed_budget_in_progress_projects_in_progress_bids_query,
			$fixed_budget_in_complete_projects_in_complete_bids_query,
			$hourly_budget_in_progress_projects_in_progress_bids_query,
			$fulltime_hired_projects_hired_query,
			$fulltime_hired_expired_projects_hired_query,
			$fulltime_hired_cancelled_projects_hired_query,
			$fulltime_hired_cancelled_by_admin_projects_hired_query,
			$fixed_completed_projects_completed_bids_query,
			$fixed_in_progress_projects_completed_bids_query,
			$hourly_completed_projects_completed_bids_query,
			$hourly_in_progress_projects_completed_bids_query,
			$hourly_budget_in_complete_projects_in_complete_bids_query
			
			
		];
		
		
		
		$sp_bid_query = $this->db->query(implode(' UNION ', $tables_name_array).' ORDER BY project_start_date DESC LIMIT '.$limit_range);
		
		/* $sp_bid_query = $this->db->query($fixed_budget_in_progress_projects_in_progress_bids_query. ' UNION '. $fixed_budget_in_complete_projects_in_complete_bids_query. 'UNION '. $hourly_budget_in_progress_projects_in_progress_bids_query.' UNION '.$fulltime_hired_projects_hired_query.' UNION '.$fulltime_hired_expired_projects_hired_query.' UNION '.$fulltime_hired_cancelled_projects_hired_query.' UNION '.$fulltime_hired_cancelled_by_admin_projects_hired_query.' UNION '.$fixed_completed_projects_completed_bids_query.' UNION '.$fixed_in_progress_projects_completed_bids_query.' UNION '.$hourly_completed_projects_completed_bids_query.' UNION '.$hourly_in_progress_projects_completed_bids_query.' ORDER BY project_start_date DESC LIMIT '.$limit_range); */
		$result  = $sp_bid_query->result_array();
	
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	// This function count the total won projects of service provider
	public function get_sp_won_projects_count($user_id){
	
		$projects_won_tables_array = array('fixed_budget_projects_progress_sp_bid_reference','hourly_rate_based_projects_progress_sp_bid_reference');
		$sp_total_won_projects_count = 0;
		foreach($projects_won_tables_array as $table_name){
			$sp_total_won_projects_count += $this->db->where(['winner_id' => $user_id])->from($table_name)->count_all_results();
		
		}
		return $sp_total_won_projects_count;
		
	}
	
	// This function count the total in progress  projects of service provider
	public function get_sp_in_progress_projects_count($user_id){
	
		$projects_in_progress_tables_array = array('fixed_budget_projects_progress_sp_bid_reference','hourly_rate_based_projects_progress_sp_bid_reference');
		$sp_total_in_progress_projects_count = 0;
		foreach($projects_in_progress_tables_array as $table_name){
			$sp_total_in_progress_projects_count += $this->db->where(['winner_id' => $user_id])->from($table_name)->count_all_results();
		
		}
		return $sp_total_in_progress_projects_count;
		
	}
	
	// This function count the total completed  projects of service provider
	public function get_sp_completed_projects_count($conditions){
	
		$projects_completed_tables_array = array('fixed_budget_projects_completed_tracking', 'hourly_rate_based_projects_completed_tracking');
		$sp_total_completed_projects_count = 0;
		foreach($projects_completed_tables_array as $table_name){
			$sp_total_completed_projects_count += $this->db->where($conditions)->from($table_name)->count_all_results();
		
		}
		return $sp_total_completed_projects_count;
		
	}
	
	// This function count the hires of sp on fulltime projects
	public function get_sp_hires_fulltime_projects_count($user_id){
	
		$sp_hires_fulltime_projects_count = $this->db->where(['employee_id' => $user_id])->from('fulltime_projects_hired_employees_tracking')->count_all_results();
		return $sp_hires_fulltime_projects_count;
		
	}
	
	
	// This function is used to calculate the minimum required completion threshold of fixed type project 
	public function get_minimum_required_completion_threshold_fixed_project($project_id,$winner_id,$project_owner_id,$project_status){
		
		
		if($project_status == 'open_for_bidding'){
			$project_table_name = 'projects_open_bidding';
		}
		if($project_status == 'awarded'){
			$project_table_name = 'fixed_budget_projects_awarded';
			
		}
		if($project_status == 'expired'){
			$project_table_name = 'fixed_budget_projects_expired';
			
		}
		if($project_status == 'in_progress'){
			$project_table_name = 'fixed_budget_projects_progress';
		}
		if($project_status == 'completed'){
			$project_table_name = 'fixed_budget_projects_completed';
		}
		
			$login_user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
		
		
		$project_data = $this->db // get the user detail
		->select('pd.*,ud.current_membership_plan_id')
		->from($project_table_name. ' as pd')
		->join('users_details ud', 'pd.project_owner_id = ud.user_id', 'left')
		->where('pd.project_id', $project_id)
		->where('pd.project_owner_id', $project_owner_id)
		->get()->row_array();
		
	
		
		$bid_data = $this->db // get the user detail
		->select('*')
		->from('fixed_budget_projects_open_bidding_active_bids')
		->where('project_id', $project_id)
		->where('bidder_id', $winner_id)
		->get()->row_array();
		$minimum_required_value_completion_threshold = 1;
		if(!empty($project_data) && !empty($bid_data)){
		
			if(($project_data['confidential_dropdown_option_selected'] == 'Y' || $project_data['not_sure_dropdown_option_selected'] == 'Y') && ($bid_data['bidding_dropdown_option'] == 'to_be_agreed' || $bid_data['bidding_dropdown_option'] == 'confidential')){
			
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fixed_budget_projects_minimum_required_completion_threshold')
				->where('budget_not_specified', 'Y')
				->where('po_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				if(!empty($minimum_required_completion_threshold_data)){
					$minimum_required_value_completion_threshold = $minimum_required_completion_threshold_data['project_minimum_required_value_completion_threshold'];
				}
			
			
			}
			else if(($project_data['confidential_dropdown_option_selected'] == 'Y' || $project_data['not_sure_dropdown_option_selected'] == 'Y') && $bid_data['initial_bidded_amount'] != 0){
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fixed_budget_projects_minimum_required_completion_threshold')
				->where('budget_not_specified', 'Y')
				->where('po_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['project_minimum_required_value_completion_threshold'];
				
				if( $bid_data['initial_bidded_amount'] >= $minimum_required_completion_value){
					$minimum_required_value_completion_threshold = $bid_data['initial_bidded_amount'];
				}else{
					$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
				}
			
			}
			else if(!empty($project_data['min_budget']) && !empty($project_data['max_budget']) && $project_data['max_budget'] != 'All'){
				
				
				$project_max_budget = (int)$project_data['max_budget'];
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fixed_budget_projects_minimum_required_completion_threshold')
				->where('budget_not_specified', 'N')
				->where('FLOOR(project_minimum_budget) <=', $project_max_budget)
				->where('FLOOR(project_maximum_budget) >=', $project_max_budget)
				->where('project_maximum_budget !=', 'All')
				->where('po_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				
				
				
				if(!empty($minimum_required_completion_threshold_data)){
					
					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['project_minimum_required_value_completion_threshold'];
					if($bid_data['bidding_dropdown_option'] == 'NA'){
						if( $bid_data['initial_bidded_amount'] >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['initial_bidded_amount'];
						}else{
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					
					}else{
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					}
					
				}else{
					
					$minimum_required_completion_threshold_data =$this->db // get the user detail
					->select('*')
					->from('fixed_budget_projects_minimum_required_completion_threshold')
					->where('budget_not_specified', 'N')
					//->where('FLOOR(project_minimum_budget) >=', $project_max_budget)
					->where('project_maximum_budget', 'All')
					->where('po_membership_plan_id', $project_data['current_membership_plan_id'])
					->get()->row_array();
					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['project_minimum_required_value_completion_threshold'];
					if($bid_data['bidding_dropdown_option'] == 'NA'){
					
						if( $bid_data['initial_bidded_amount'] >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['initial_bidded_amount'];
						}else{
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					}else{
					
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					
					}
				}
			
			}elseif(!empty($project_data['min_budget']) && $project_data['max_budget'] == 'All'){
			
				$project_max_budget = (int)$project_data['min_budget'];
				
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fixed_budget_projects_minimum_required_completion_threshold')
				->where('budget_not_specified', 'N')
				->where('FLOOR(project_minimum_budget) <=', $project_max_budget)
				->where('FLOOR(project_maximum_budget) >=', $project_max_budget)
				->where('project_maximum_budget !=', 'All')
				->where('po_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				if(!empty($minimum_required_completion_threshold_data)){
					
					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['project_minimum_required_value_completion_threshold'];
					
					if($bid_data['bidding_dropdown_option'] == 'NA'){
						
						if( $bid_data['initial_bidded_amount'] >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['initial_bidded_amount'];
						}else{
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					
					}else{
					
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					
					}
					
				}else{
					
					$minimum_required_completion_threshold_data =$this->db // get the user detail
						->select('*')
						->from('fixed_budget_projects_minimum_required_completion_threshold')
						->where('budget_not_specified', 'N')
						//->where('FLOOR(project_minimum_budget) >=', $project_max_budget)
						->where('project_maximum_budget', 'All')
						->where('po_membership_plan_id', $project_data['current_membership_plan_id'])
						->get()->row_array();
						

					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['project_minimum_required_value_completion_threshold'];

					if($bid_data['bidding_dropdown_option'] == 'NA'){
						
						if( $bid_data['initial_bidded_amount'] >= $minimum_required_completion_value){
						
							$minimum_required_value_completion_threshold = $bid_data['initial_bidded_amount'];
						}else{
							
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}

					}else{
							
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;

					}
				}
			
			}
		
		}
		return $minimum_required_value_completion_threshold;
		
	}
	

	// This function is used to calculate the minimum required completion threshold for fulltime type project 
	public function get_initial_minimum_required_threshold_to_exchange_feedback_fulltime_project($project_id,$winner_id,$project_owner_id,$project_status){

		if($project_status == 'open_for_bidding'){
			$project_table_name = 'projects_open_bidding';
		}
		
		if($project_status == 'expired'){
			$project_table_name = 'fulltime_projects_expired';
		}
		
		if($project_status == 'cancelled'){
			$project_table_name = 'fulltime_projects_cancelled';
			$cancelled_by_admin_project_table_name = 'fulltime_projects_cancelled_by_admin';
		}
				
		if($project_status == 'open_for_bidding'){
			$project_data = $this->db // get the user detail
			->select('pd.*,ud.current_membership_plan_id')
			->from($project_table_name. ' as pd')
			->join('users_details ud', 'pd.project_owner_id = ud.user_id', 'left')
			->where('pd.project_id', $project_id)
			->where('pd.project_owner_id', $project_owner_id)
			->get()->row_array();
		} else {
			$project_data = $this->db // get the user detail
			->select('pd.*,ud.current_membership_plan_id')
			->from($project_table_name. ' as pd')
			->join('users_details ud', 'pd.employer_id = ud.user_id', 'left')
			->where('pd.fulltime_project_id', $project_id)
			->where('pd.employer_id', $project_owner_id)
			->get()->row_array();

			if(empty($project_data) && $project_status == 'cancelled') {
				$project_data = $this->db // get the user detail
				->select('pd.*,ud.current_membership_plan_id')
				->from($cancelled_by_admin_project_table_name. ' as pd')
				->join('users_details ud', 'pd.employer_id = ud.user_id', 'left')
				->where('pd.fulltime_project_id', $project_id)
				->where('pd.employer_id', $project_owner_id)
				->get()->row_array();
			}

			$project_data['project_id'] = $project_data['fulltime_project_id'];
			$project_data['project_owner_id'] = $project_data['employer_id'];
			$project_data['min_budget'] = $project_data['min_salary'];
			$project_data['max_budget'] = $project_data['max_salary'];
		}
		
		$membership_subscriber_minimum_months_parameter = 1;
		if($project_data['current_membership_plan_id'] == 1 ){
			$membership_subscriber_minimum_months_parameter = $this->config->item('free_membership_subscriber_fulltime_project_minimum_months_to_exchange_feedback'); 
		}if($project_data['current_membership_plan_id'] == 4 ){
			$membership_subscriber_minimum_months_parameter = $this->config->item('gold_membership_subscriber_fulltime_project_minimum_months_to_exchange_feedback'); 
		}
		
		$bid_data = $this->db // get the user detail
		->select('*')
		->from('fulltime_projects_awarded_tracking')
		->where('fulltime_project_id', $project_id)
		->where('employee_id', $winner_id)
		->get()->row_array();
		$minimum_required_value_completion_threshold = 1;
		if(!empty($project_data) && !empty($bid_data)){
		
			if(($project_data['confidential_dropdown_option_selected'] == 'Y' || $project_data['not_sure_dropdown_option_selected'] == 'Y') && ($bid_data['application_bidding_dropdown_option'] == 'to_be_agreed' || $bid_data['application_bidding_dropdown_option'] == 'confidential')){
			
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fulltime_projects_minimum_required_completion_threshold')
				->where('fulltime_project_salary_not_specified', 'Y')
				->where('employer_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				if(!empty($minimum_required_completion_threshold_data)){
					$minimum_required_value_completion_threshold = $minimum_required_completion_threshold_data['fulltime_project_minimum_required_completion_threshold'];
				}
			
			
			} else if(($project_data['confidential_dropdown_option_selected'] == 'Y' || $project_data['not_sure_dropdown_option_selected'] == 'Y') && $bid_data['awarded_salary'] != 0){
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fulltime_projects_minimum_required_completion_threshold')
				->where('fulltime_project_salary_not_specified', 'Y')
				->where('employer_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['fulltime_project_minimum_required_completion_threshold'];
				
				if( ($bid_data['awarded_salary'] * $membership_subscriber_minimum_months_parameter) >= $minimum_required_completion_value){
					$minimum_required_value_completion_threshold = $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter;
				}else{
					$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
				}
			
			} else if(!empty($project_data['min_budget']) && !empty($project_data['max_budget']) && $project_data['max_budget'] != 'All'){

				$project_max_budget = (int)$project_data['max_budget'];
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fulltime_projects_minimum_required_completion_threshold')
				->where('fulltime_project_salary_not_specified', 'N')
				->where('FLOOR(fulltime_project_minimum_salary) <=', $project_max_budget)
				->where('FLOOR(fulltime_project_maximum_salary) >=', $project_max_budget)
				->where('fulltime_project_maximum_salary !=', 'All')
				->where('employer_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				
				if(!empty($minimum_required_completion_threshold_data)){
					
					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['fulltime_project_minimum_required_completion_threshold'];
					if($bid_data['application_bidding_dropdown_option'] == 'NA'){
						if( $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter;
						}else{
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					
					}else{
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					}
					
				} else {
					
					$minimum_required_completion_threshold_data =$this->db // get the user detail
					->select('*')
					->from('fulltime_projects_minimum_required_completion_threshold')
					->where('fulltime_project_salary_not_specified', 'N')
					//->where('FLOOR(project_minimum_budget) >=', $project_max_budget)
					->where('fulltime_project_maximum_salary', 'All')
					->where('employer_membership_plan_id', $project_data['current_membership_plan_id'])
					->get()->row_array();
					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['fulltime_project_minimum_required_completion_threshold'];
					if($bid_data['application_bidding_dropdown_option'] == 'NA'){
					
						if( $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter;
						} else {
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					} else {
					
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					
					}
				}
			
			} else if(!empty($project_data['min_budget']) && $project_data['max_budget'] == 'All'){
			
				$project_max_budget = (int)$project_data['min_budget'];
				
				$minimum_required_completion_threshold_data =$this->db // get the user detail
				->select('*')
				->from('fulltime_projects_minimum_required_completion_threshold')
				->where('fulltime_project_salary_not_specified', 'N')
				->where('FLOOR(fulltime_project_minimum_salary) <=', $project_max_budget)
				->where('FLOOR(fulltime_project_maximum_salary) >=', $project_max_budget)
				->where('fulltime_project_maximum_salary !=', 'All')
				->where('employer_membership_plan_id', $project_data['current_membership_plan_id'])
				->get()->row_array();
				if(!empty($minimum_required_completion_threshold_data)){
					
					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['fulltime_project_minimum_required_completion_threshold'];
					
					if($bid_data['application_bidding_dropdown_option'] == 'NA'){
						
						if( $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter;
						}else{
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					
					} else {
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					}
					
				} else {
					
					$minimum_required_completion_threshold_data =$this->db // get the user detail
						->select('*')
						->from('fulltime_projects_minimum_required_completion_threshold')
						->where('fulltime_project_salary_not_specified', 'N')
						//->where('FLOOR(project_minimum_budget) >=', $project_max_budget)
						->where('fulltime_project_maximum_salary', 'All')
						->where('employer_membership_plan_id', $project_data['current_membership_plan_id'])
						->get()->row_array();
						

					$minimum_required_completion_value = (int)$minimum_required_completion_threshold_data['fulltime_project_minimum_required_completion_threshold'];

					if($bid_data['application_bidding_dropdown_option'] == 'NA') {
						if($bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter >= $minimum_required_completion_value){
							$minimum_required_value_completion_threshold = $bid_data['awarded_salary']*$membership_subscriber_minimum_months_parameter;
						} else {
							$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
						}
					} else { 
						$minimum_required_value_completion_threshold  = $minimum_required_completion_value;
					}
				}
			
			}
		
		}
		return $minimum_required_value_completion_threshold;
	}

	/*
	This functions is used to fetch the listing of project completed request created by po
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the project completed request.
	*/
	public function get_mark_complete_project_request_listing($project_type,$conditions){
		
		if($project_type == 'fixed'){
			$mark_complete_request_tracking_table = 'fixed_budget_projects_manual_completed_requests_tracking';
		} else if($project_type == 'hourly') {
			$mark_complete_request_tracking_table = 'hourly_rate_projects_manual_completed_requests_tracking';
		}
		
		
		
		$this->db->where($conditions);
		$this->db->where('request_expires_on <',date('Y-m-d H:i:s'));
		$this->db->where('request_status','active');
		$this->db->update($mark_complete_request_tracking_table,['request_status'=>'expired']);
		
		
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS cr.*', false);
		$this->db->from($mark_complete_request_tracking_table.' cr');
		$this->db->where($conditions);
		$this->db->order_by('cr.request_sent_on','desc');
		
		$mark_complete_request_result = $this->db->get();
		$mark_complete_request_data = $mark_complete_request_result->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $mark_complete_request_data, 'total' => $total_rec];
		
	}
	
	
	// This function is used to fetch the complete request detail
	public function get_latest_project_complete_request_detail($conditions,$project_type) {
		if($project_type == 'fixed'){
			$mark_complete_request_tracking_table = 'fixed_budget_projects_manual_completed_requests_tracking';
		} else if($project_type == 'hourly') {
			$mark_complete_request_tracking_table = 'hourly_rate_projects_manual_completed_requests_tracking';
		}
		$row = [];
		if(!empty($mark_complete_request_tracking_table)) {
			$this->db->select('*');
			$this->db->from($mark_complete_request_tracking_table);
			$this->db->where($conditions);
			$this->db->order_by('id', 'desc');	
			$this->db->limit(1);
			$row = $this->db->get()->row_array();
		} 
		return $row;		
	}
	
	
	/**
	* This function is used to sum of the project value for po.
	* ex:sp1 project value = 1000 sp2 project value = 0 sp3 project value = 1000 total project value for po->2000
	*/
	public function get_total_project_value_po($project_id,$project_type){
		
		$project_value = 0;
		if($project_type == 'fixed'){
			$table_array = array('fixed_budget_projects_progress_sp_bid_reference','fixed_budget_projects_completed_tracking');
			foreach($table_array  as $table_name){
				$check_records_exists_count = $this->db->where(['project_id' => $project_id])->from($table_name)->count_all_results();
				if($check_records_exists_count > 0){
				
					if($table_name == 'fixed_budget_projects_progress_sp_bid_reference'){
						$this->db->select('SUM(initial_project_agreed_value) as sum_project_value');
						$this->db->from($table_name);
						$this->db->where(['project_id'=>$project_id]);
						$sum_project_value_result = $this->db->get();
						$sum_project_value_row = $sum_project_value_result->row_array();
						$project_value += $sum_project_value_row['sum_project_value'];
					}
					else{
						
						/* $this->db->select('SUM(initial_project_agreed_value) as sum_project_value');
						$this->db->from($table_name);
						$this->db->where(['project_id'=>$project_id,'project_completion_method'=>'via_portal']);
						$sum_project_value_result = $this->db->get();
						$sum_project_value_row = $sum_project_value_result->row_array();
						$project_value += $sum_project_value_row['sum_project_value']; */
						$this->db->select('SUM(total_project_amount) as sum_project_value');
						$this->db->from($table_name);
						$this->db->where(['project_id'=>$project_id]);
						$sum_project_value_result = $this->db->get();
						$sum_project_value_row = $sum_project_value_result->row_array();
						$project_value += $sum_project_value_row['sum_project_value'];
					}
				}
			}
		} else if($project_type == 'hourly') {
			$table_array = array('hourly_rate_based_projects_progress_sp_bid_reference', 'hourly_rate_based_projects_completed_tracking');
			foreach($table_array  as $table_name){
				$check_records_exists_count = $this->db->where(['project_id' => $project_id])->from($table_name)->count_all_results();
				if($check_records_exists_count > 0){
					$this->db->select('*');
					$this->db->from($table_name);
					$this->db->where(['project_id'=>$project_id]);
					$result = $this->db->get()->result_array();
					if(!empty($result)) {
						foreach($result as $val) {
							$this->db->select('SUM(released_escrow_payment_amount) as sum_project_value');
							$this->db->from('hourly_rate_based_projects_released_escrows');
							$this->db->where(['project_id'=>$project_id, 'project_owner_id' => $val['project_owner_id'], 'winner_id' => $val['winner_id']]);
							$sum_project_value_result = $this->db->get();
							$sum_project_value_row = $sum_project_value_result->row_array();

							if($sum_project_value_row['sum_project_value'] > $val['initial_project_agreed_value']) {
								$project_value += $sum_project_value_row['sum_project_value'];
							} else {
								$project_value += $val['initial_project_agreed_value'];	
							}
						}
					}
				}
			}
		} else if($project_type == 'fulltime') {
			$table_array = array('fulltime_projects_hired_employees_tracking');
			foreach($table_array  as $table_name){
				$check_records_exists_count = $this->db->where(['fulltime_project_id' => $project_id])->from($table_name)->count_all_results();
				if($check_records_exists_count > 0){
					$this->db->select('*');
					$this->db->from($table_name);
					$this->db->where(['fulltime_project_id'=>$project_id]);
					$result = $this->db->get()->result_array();
					if(!empty($result)) {
						foreach($result as $val) {
							$this->db->select('SUM(released_escrow_payment_amount) as sum_project_value');
							$this->db->from('fulltime_projects_released_escrows');
							$this->db->where(['fulltime_project_id'=>$project_id, 'employer_id' => $val['employer_id'], 'employee_id' => $val['employee_id']]);
							$sum_project_value_result = $this->db->get();
							$sum_project_value_row = $sum_project_value_result->row_array();
							$project_value += $sum_project_value_row['sum_project_value'];
							/* if($sum_project_value_row['sum_project_value'] > $val['initial_fulltime_project_agreed_salary']) {
								$project_value += $sum_project_value_row['sum_project_value'];
							} else {
								$project_value += $val['initial_fulltime_project_agreed_salary'];	
							} */
						}
					}
				}
			}
		}
		return $project_value;
	}
	
}
?>