<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Projects_disputes_model extends BaseModel
{

    public function __construct ()
    { 
		parent::__construct ();
		$this->load->model('escrow/Escrow_model');
		$this->load->model('dashboard/Dashboard_model');
		$this->load->model('user/User_model');
    }
	
	public function project_dispute_form_validation($post_data,$project_detail){
		
		$i = 0;
		
		$dispute_description_charcaters_length = strlen(preg_replace('/\s+/', '', $post_data['dispute_description']));
		if (empty(trim($post_data['dispute_description']))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'dispute_description';
			if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_validation_fulltime_project_dispute_form_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_validation_project_dispute_form_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		
		if(!empty(trim($post_data['dispute_description']))){
			if($this->config->item('project_dispute_description_minimum_length_words_limit') == 0){
				
				if($dispute_description_charcaters_length < $this->config->item('project_dispute_description_minimum_length_characters_limit')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'dispute_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if($project_detail['project_type'] == 'fulltime'){
						$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_characters_min_length_validation_fulltime_project_dispute_form_message');
					}else{
						$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_characters_min_length_validation_project_dispute_form_message');
					}
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $post_data['dispute_description']);
				$bid_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($dispute_description_charcaters_length < $this->config->item('project_dispute_description_minimum_length_characters_limit') ||$bid_description_word_count < $this->config->item('project_dispute_description_minimum_length_words_limit') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'dispute_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if($project_detail['project_type'] == 'fulltime'){
						$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_characters_words_minimum_length_validation_fulltime_project_dispute_form_message');
					}else{
						$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_characters_words_minimum_length_validation_project_dispute_form_message');
					}
					
					$i ++;
				}
			}
		}
		if(!empty($post_data['dispute_description']) && strlen($dispute_description_charcaters_length) > $this->config->item('project_dispute_description_maximum_length_characters_limit')){
		
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'dispute_description';
			$msg['errors'][$i]['error_class'] = 'max_length';
			if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_characters_maximum_length_validation_fulltime_project_dispute_form_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_dispute_description_characters_maximum_length_validation_project_dispute_form_message');
			}
			$i ++;
		}
		if($i == 0){
			$msg['status'] = 'SUCCESS';
      $msg['message'] = '';
		}
		return $msg;
	
	}
	
	// validation function for create counter offer
	public function project_dispute_create_counter_offer_validation($post_data,$param_data){
	
		
		$i = 0;
		if (empty(trim(str_replace(" ","",$post_data['counter_offer'])))){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'counter_offer';
			$msg['errors'][$i]['message'] = $this->config->item('project_dispute_details_page_counter_offer_validation_project_dispute_form_message');
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		if (!empty(trim(str_replace(" ","",$post_data['counter_offer']))) ){
			
			
			if($param_data['project_type'] == 'fixed'){
				$active_counter_offer_table = 'fixed_budget_projects_active_disputes_counter_offers';
			
			}
			if($param_data['project_type'] == 'hourly'){
				$active_counter_offer_table = 'hourly_rate_based_projects_active_disputes_counter_offers';
			
			}
			if($param_data['project_type'] == 'fulltime'){
				$active_counter_offer_table = 'fulltime_projects_active_disputes_counter_offers';
			
			}
			
			$count_counter_offers_entires = $this->db->where(['dispute_reference_id' => $param_data['dispute_ref_id']])->from($active_counter_offer_table)->count_all_results();
			
			if($count_counter_offers_entires == 0){
				if(str_replace(" ","",$post_data['counter_offer']) > $param_data['disputed_amount']-1){
					
					$counter_offer_min_amount = "1 ".CURRENCY;
					$counter_offer_max_amount = str_replace(".00","",number_format($param_data['disputed_amount']-1,  2, '.', ' '))." ". CURRENCY;
					
					$error_msg = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array($counter_offer_min_amount,$counter_offer_max_amount),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
					
					$msg['errors'][$i]['message'] = $error_msg;
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'counter_offer';
					$msg['errors'][$i]['error_class'] = 'invalid_counter_offer';
					$i ++;
				}
				
			}else if($count_counter_offers_entires == 1){
				
				$latest_counter_offer_created_by_sp_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$param_data['dispute_ref_id'],'countered_by_user_id'=>$param_data['sp_id']])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				$counter_offer_min_amount = "1 ".CURRENCY;
				$counter_offer_max_amount = str_replace(".00","",number_format($latest_counter_offer_created_by_sp_data['counter_amount_value']-1,  2, '.', ' '))." ". CURRENCY;
				
					
				$error_msg = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array($counter_offer_min_amount,$counter_offer_max_amount),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
				
				
			
				if(str_replace(" ","",$post_data['counter_offer']) > $latest_counter_offer_created_by_sp_data['counter_amount_value']-1){
					$msg['errors'][$i]['message'] = $error_msg;
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'counter_offer';
					$msg['errors'][$i]['error_class'] = 'invalid_counter_offer';
					$i ++;
				}
			}else{
				
				$latest_counter_offer_created_by_po_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$param_data['dispute_ref_id'],'countered_by_user_id'=>$param_data['po_id']])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				$latest_counter_offer_created_by_sp_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$param_data['dispute_ref_id'],'countered_by_user_id'=>$param_data['sp_id']])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				//if($param_data['po_id'] != $user_id){
					
				$counter_offer_min_amount = $latest_counter_offer_created_by_po_data['counter_amount_value'];
				$counter_offer_max_amount = $latest_counter_offer_created_by_sp_data['counter_amount_value'] ;
				$diff = floatval($counter_offer_max_amount) - floatval($counter_offer_min_amount);
				if($diff >2){
					
					$counter_offer_min_amount = $latest_counter_offer_created_by_po_data['counter_amount_value']+1;
					$counter_offer_max_amount = $latest_counter_offer_created_by_sp_data['counter_amount_value']-1 ;
					
					if( str_replace(" ","",$post_data['counter_offer']) < $counter_offer_min_amount ||   str_replace(" ","",$post_data['counter_offer']) > $counter_offer_max_amount){
					
					$msg['errors'][$i]['message'] = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array(str_replace(".00","",number_format($counter_offer_min_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($counter_offer_max_amount,  2, '.', ' '))." ". CURRENCY),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'counter_offer';
					$msg['errors'][$i]['error_class'] = 'invalid_counter_offer';
					$i ++;
					} 
				}if($diff == 2 && $param_data['po_id'] == $param_data['user_id']){
					$counter_offer_amount  = $latest_counter_offer_created_by_po_data['counter_amount_value']+1;
					if(str_replace(" ","",$post_data['counter_offer']) != $counter_offer_amount){
						$msg['errors'][$i]['message'] = str_replace(array('{dispute_counter_offer_amount_no_range_available}'),array($counter_offer_amount." ". CURRENCY),$this->config->item('project_dispute_details_page_counter_offer_no_range_available_validation_project_dispute_form_message'));
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'counter_offer';
						$msg['errors'][$i]['error_class'] = 'invalid_counter_offer';
						$i ++;
					}	
					
				}
				if($diff == 2 && $param_data['po_id'] != $param_data['user_id']){
					$counter_offer_amount  = $latest_counter_offer_created_by_sp_data['counter_amount_value']-1;
					if(str_replace(" ","",$post_data['counter_offer']) != $counter_offer_amount){
						$msg['errors'][$i]['message'] = str_replace(array('{dispute_counter_offer_amount_no_range_available}'),array($counter_offer_amount." ". CURRENCY),$this->config->item('project_dispute_details_page_counter_offer_no_range_available_validation_project_dispute_form_message'));
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'counter_offer';
						$msg['errors'][$i]['error_class'] = 'invalid_counter_offer';
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
	
	
	
	
	
	
	public function get_project_disputed_message_listing($dispute_ref_id){
		
		$dispute_messages = $this->db // get the user detail
		->select('dm.*,sender.user_id as sender_user_id,sender.first_name as sender_first_name,sender.last_name as sender_last_name,sender.company_name as sender_company_name,sender.account_type as sender_account_type,sender.profile_name as sender_profile_name,sender.gender as sender_gender,ud.user_avatar as sender_avatar')
		->from('projects_disputes_messages_activity_tracking dm')
		->join('users sender', 'dm.message_sent_by_user_id = sender.user_id', 'left')
		->join('users_details ud', 'ud.user_id = dm.message_sent_by_user_id', 'left')
		->where('dm.dispute_reference_id', $dispute_ref_id)
		->order_by("message_sent_date", "asc")
		->get()->result_array();
		if(!empty($dispute_messages)){
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] = FTP_PORT;
			$config['debug'] = TRUE;
			$this->ftp->connect($config);
		
		
			foreach($dispute_messages as $key=>$value){
				$project_dispute_attachment_list = $this->db // get the user detail
				->select('*')
				->from('projects_disputes_attachments')
				->where('dispute_reference_id', $dispute_ref_id)
				->where('dispute_message_id', $value['id'])
				->get()->result_array();
				$dispute_messages[$key] = $value;
				$dispute_messages[$key]['attachments'] = $project_dispute_attachment_list;
				
				$common_source_path = USERS_FTP_DIR . $value['sender_profile_name'];
				//start check avatar from ftp server
				$user_avatar = USER_AVATAR;
				$source_path_avatar = $common_source_path . $user_avatar;
				$avatarlist = $this->ftp->list_files($source_path_avatar);
				$avatar_pic = $source_path_avatar . $value['sender_avatar'];

				$exap = explode('.', $value['sender_avatar']);
				$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

				if (count($avatarlist) > 0) {
						$acheck = true;
						if (!in_array($avatar_pic, $avatarlist) && $acheck) {
							//die("1");
								$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['sender_user_id']));
								$this->ftp->delete_dir($source_path_avatar);
								$value['sender_avatar'] = '';
								$acheck = false;
						} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
							//die("2");
								$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['sender_user_id']));
								$this->ftp->delete_dir($source_path_avatar);
								$value['sender_avatar'] = '';
								$acheck = false;
						}
				} if (count($avatarlist) == 0 && $value['sender_avatar'] != '') {
					//die("3");
					$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['sender_user_id']));
					$value['sender_avatar'] = '';
				}
			}
			$this->ftp->close();
		}
		return $dispute_messages;
	
	}
	
	public function get_disputed_project_message_row_detail($dispute_ref_id){
	
		$dispute_messages = $this->db // get the user detail
		->select('dm.*,sender.user_id as sender_user_id,sender.first_name as sender_first_name,sender.last_name as sender_last_name,sender.company_name as sender_company_name,sender.account_type as sender_account_type,sender.profile_name as sender_profile_name,sender.gender as sender_gender,ud.user_avatar as sender_avatar')
		->from('projects_disputes_messages_activity_tracking dm')
		->join('users sender', 'dm.message_sent_by_user_id = sender.user_id', 'left')
		->join('users_details ud', 'ud.user_id = dm.message_sent_by_user_id', 'left')
		->where('dm.dispute_reference_id', $dispute_ref_id)
		->order_by('id', 'DESC')
		->limit(1)
		->get()->row_array();
		$project_dispute_attachment_list = $this->db // get the user detail
		->select('*')
		->from('projects_disputes_attachments')
		->where('dispute_reference_id', $dispute_ref_id)
		->where('dispute_message_id', $dispute_messages['id'])
		->get()->result_array();
		$dispute_messages['attachments'] = $project_dispute_attachment_list;
		
		
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] = FTP_PORT;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
		
		$common_source_path = USERS_FTP_DIR . $dispute_messages['sender_profile_name'];
		//start check avatar from ftp server
		$user_avatar = USER_AVATAR;
		$source_path_avatar = $common_source_path . $user_avatar;
		$avatarlist = $this->ftp->list_files($source_path_avatar);
		$avatar_pic = $source_path_avatar . $dispute_messages['sender_avatar'];
		
		$exap = explode('.', $dispute_messages['sender_avatar']);
		$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

		if (count($avatarlist) > 0) {
				$acheck = true;
				if (!in_array($avatar_pic, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $dispute_messages['sender_user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$dispute_messages['sender_avatar'] = '';
						$acheck = false;
				} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$dispute_messages['sender_user_id']));
						$this->ftp->delete_dir($source_path_avatar);
						$dispute_messages['sender_avatar'] = '';
						$acheck = false;
				}
		} if (count($avatarlist) == 0 && $dispute_messages['sender_avatar'] != '') {
				$this->db->update('users_details', array('sender_avatar' => ''), array("user_id" => $dispute_messages['sender_user_id']));
				$dispute_messages['sender_avatar'] = '';
		}
		$this->ftp->close();
		return $dispute_messages;
	}
	
	
	// This function is used to fetch the active dispute list on project detail page for po/logout page
	public function get_project_active_disputes_list_project_details($project_id,$project_type,$profile_name,$project_status)
	{
		
		//$bidding_table_name = '';
		if($project_type == 'fixed'){
			$incomplete_bidding_table_name = 'fixed_budget_projects_incomplete_tracking';
			$inprogress_bidding_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$completed_bidding_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				
			$completed_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_value,bd.initial_bid_value,"" as initial_project_agreed_delivery_period,bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_completion_date,bd.total_project_amount,bd.project_completion_method';
				
			$incomplete_inprogress_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_value,bd.initial_bid_value,bd.initial_project_agreed_delivery_period,bd.project_start_date,"" as project_completion_date,"" as total_project_amount,"" as project_completion_method';
			
		}
		
		if($project_type == 'hourly'){
			$incomplete_bidding_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$inprogress_bidding_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$completed_bidding_table_name = 'hourly_rate_based_projects_completed_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
			$completed_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_hourly_rate,bd.initial_project_agreed_value as initial_bid_value,initial_project_agreed_number_of_hours,bd.initial_project_agreed_value,bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_completion_date,bd.total_project_amount,bd.project_completion_method';
			
			$incomplete_inprogress_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_hourly_rate,bd.initial_project_agreed_value as initial_bid_value,bd.initial_project_agreed_number_of_hours,bd.initial_project_agreed_value,bd.project_start_date,"" as project_completion_date,"" as total_project_amount,"" as project_completion_method';
		}
		if($project_type == 'fulltime'){
			$inprogress_bidding_table_name = 'fulltime_projects_hired_employees_tracking';
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			
			
			/* 'employee_hired_on as project_start_date,bd.initial_fulltime_project_agreed_salary as initial_bid_value,"0" as initial_project_agreed_value,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.initial_application_description as initial_bid_description,"0" as initial_project_agreed_delivery_period,"0" as initial_project_agreed_hourly_rate' */
			
			$result_array = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,bd.initial_application_description,bd.initial_fulltime_project_agreed_salary,bd.employee_id as winner_id,bd.employer_id as project_owner_id,bd.employee_hired_on as project_start_date,bd.employee_id,bd.initial_fulltime_project_agreed_salary,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.fulltime_project_id as project_id')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
				
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where employee_winner_id_of_disputed_fulltime_project = u.user_id and employer_id_of_disputed_fulltime_project = bd.employer_id and bd.fulltime_project_id = disputed_fulltime_project_id ) as total_employee_active_dispute_count')
				->from($inprogress_bidding_table_name.' bd')
				->join('users u', 'u.user_id = bd.employee_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('bd.fulltime_project_id', $project_id)
				->having('total_employee_active_dispute_count > 0')
				->get()->result_array();
				/* echo "<pre>";
				print_r($result_array); */
				
		}else{
			$this->db // get the user detail
			->select('SQL_CALC_FOUND_ROWS u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,'.$incomplete_inprogress_project_bid_fields,false)
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = bd.project_owner_id and bd.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($incomplete_bidding_table_name.' bd')
			->join('users u', 'u.user_id = bd.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('bd.project_id', $project_id)
			->having('total_sp_active_dispute_count > 0');
			$active_disputes_incomplete_projects_query = $this->db->get_compiled_select();
			
			$this->db // get the user detail
			->select(' u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,'.$incomplete_inprogress_project_bid_fields)
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = bd.project_owner_id and bd.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($inprogress_bidding_table_name.' bd')
			->join('users u', 'u.user_id = bd.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('bd.project_id', $project_id)
			->having('total_sp_active_dispute_count > 0');
			$active_disputes_inprogress_projects_query = $this->db->get_compiled_select();
			
			
			$this->db // get the user detail
			->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,'.$completed_project_bid_fields)
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = bd.project_owner_id and bd.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($completed_bidding_table_name.' bd')
			->join('users u', 'u.user_id = bd.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('bd.project_id', $project_id)
			->having('total_sp_active_dispute_count > 0');
			$active_disputes_complete_projects_query = $this->db->get_compiled_select();
			
			$union_table_name = [
				 $active_disputes_incomplete_projects_query,
				 $active_disputes_inprogress_projects_query,
				 $active_disputes_complete_projects_query,
			];
			$active_disputes_query = $this->db->query(implode(' UNION ', $union_table_name));
			$result_array  = $active_disputes_query->result_array();
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
		die;  */
		return $bidder_list;
		
	}
	
	// This function is used to fetch the active dispute list on project detail page for sp of other sp detail
	/* public function get_project_active_disputes_list_sp_project_details($project_id,$project_type,$profile_name,$project_status,$sp_id)
	{
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$incomplete_bidding_table_name = 'fixed_budget_projects_incomplete_tracking';
			$inprogress_bidding_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$completed_bidding_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$bidding_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
			$inprogress_bidding_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
		}
		
		$this->db // get the user detail
		->select('SQL_CALC_FOUND_ROWS u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,incompleted_bids.project_id,incompleted_bids.project_owner_id,incompleted_bids.winner_id,incompleted_bids.initial_bid_description,incompleted_bids.bidding_dropdown_option,incompleted_bids.initial_project_agreed_value,incompleted_bids.initial_bid_value,incompleted_bids.initial_project_agreed_delivery_period,incompleted_bids.project_start_date,"" as project_completion_date,"" as total_project_amount,"" as project_completion_method',false)
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = incompleted_bids.project_owner_id and incompleted_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count')
		->from($incomplete_bidding_table_name.' incompleted_bids')
		->join('users u', 'u.user_id = incompleted_bids.winner_id', 'left')
		->join('users_details ud', 'ud.user_id = u.user_id', 'left')
		->where('incompleted_bids.project_id', $project_id)
		->where('incompleted_bids.winner_id !=', $sp_id)
		->having('total_sp_active_dispute_count > 0');
		$active_disputes_incomplete_projects_query = $this->db->get_compiled_select();
		
		$this->db // get the user detail
		->select(' u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,inprogress_bids.project_id,inprogress_bids.project_owner_id,inprogress_bids.winner_id,inprogress_bids.initial_bid_description,inprogress_bids.bidding_dropdown_option,inprogress_bids.initial_project_agreed_value,inprogress_bids.initial_bid_value,inprogress_bids.initial_project_agreed_delivery_period,inprogress_bids.project_start_date,"" as project_completion_date,"" as total_project_amount,"" as project_completion_method')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = inprogress_bids.project_owner_id and inprogress_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count')
		->from($inprogress_bidding_table_name.' inprogress_bids')
		->join('users u', 'u.user_id = inprogress_bids.winner_id', 'left')
		->join('users_details ud', 'ud.user_id = u.user_id', 'left')
		->where('inprogress_bids.project_id', $project_id)
		->where('inprogress_bids.winner_id !=', $sp_id)
		->having('total_sp_active_dispute_count > 0');
		$active_disputes_inprogress_projects_query = $this->db->get_compiled_select();
		
		
		
		$this->db // get the user detail
		->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,completed_bids.project_id,completed_bids.project_owner_id,completed_bids.winner_id,completed_bids.initial_bid_description,completed_bids.bidding_dropdown_option,completed_bids.initial_project_agreed_value,completed_bids.initial_bid_value,"" as initial_project_agreed_delivery_period,completed_bids.project_winner_work_start_date as project_start_date,completed_bids.project_winner_work_completion_date as project_completion_date,completed_bids.total_project_amount,completed_bids.project_completion_method')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
		->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = completed_bids.project_owner_id and completed_bids.project_id = disputed_project_id ) as total_sp_active_dispute_count')
		->from($completed_bidding_table_name.' completed_bids')
		->join('users u', 'u.user_id = completed_bids.winner_id', 'left')
		->join('users_details ud', 'ud.user_id = u.user_id', 'left')
		->where('completed_bids.project_id', $project_id)
		->where('completed_bids.winner_id !=', $sp_id)
		->having('total_sp_active_dispute_count > 0');
		$active_disputes_complete_projects_query = $this->db->get_compiled_select();
		
		
		
		
		$union_table_name = [
			 $active_disputes_incomplete_projects_query,
			 $active_disputes_inprogress_projects_query,
			 $active_disputes_complete_projects_query,
		];

		$active_disputes_query = $this->db->query(implode(' UNION ', $union_table_name));
		$result_array  = $active_disputes_query->result_array();
		
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
                                } if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
                                    $this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$value['user_id']));
                                    $this->ftp->delete_dir($source_path_avatar);
                                    $value['user_avatar'] = '';
                                    $acheck = false;
                                }
                            } if (count($avatarlist) == 0 && $value['user_avatar'] != '') {
                                $this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $value['user_id']));
                                $value['user_avatar'] = '';
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
						}if($project_status == 'in_complete'){
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
		return $bidder_list;
		
	} */
	
	
	// This function is used to fetch the active dispute detail of sp bid on project detail page 
	public function get_sp_project_active_dispute_bid_detail_project_details($project_id,$project_type,$profile_name,$project_status,$sp_id)
	{
		
		$bidding_table_name = '';
		if($project_type == 'fixed'){
			$incomplete_bidding_table_name = 'fixed_budget_projects_incomplete_tracking';
			$inprogress_bidding_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$completed_bidding_table_name = 'fixed_budget_projects_completed_tracking';
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
			
			$completed_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_value,bd.initial_bid_value,"" as initial_project_agreed_delivery_period,bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_completion_date,bd.total_project_amount,bd.project_completion_method';
				
			$incomplete_inprogress_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_value,bd.initial_bid_value,bd.initial_project_agreed_delivery_period,bd.project_start_date,"" as project_completion_date,"" as total_project_amount,"" as project_completion_method';
			
		}
		if($project_type == 'hourly'){
			$incomplete_bidding_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$inprogress_bidding_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$completed_bidding_table_name = 'hourly_rate_based_projects_completed_tracking';
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
			
			$completed_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_hourly_rate,bd.initial_project_agreed_value as initial_bid_value,initial_project_agreed_number_of_hours,bd.initial_project_agreed_value,bd.project_winner_work_start_date as project_start_date,bd.project_winner_work_completion_date as project_completion_date,bd.total_project_amount,bd.project_completion_method';
			
			$incomplete_inprogress_project_bid_fields = 'bd.project_id,bd.project_owner_id,bd.winner_id,bd.initial_bid_description,bd.bidding_dropdown_option,bd.initial_project_agreed_hourly_rate,bd.initial_project_agreed_value as initial_bid_value,bd.initial_project_agreed_number_of_hours,bd.initial_project_agreed_value,bd.project_start_date,"" as project_completion_date,"" as total_project_amount,"" as project_completion_method';
		}
		if($project_type == 'fulltime'){
			$inprogress_bidding_table_name = 'fulltime_projects_hired_employees_tracking';
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
		}
		
		if($project_type == 'fulltime'){
			
			
			/* 'employee_hired_on as project_start_date,bd.initial_fulltime_project_agreed_salary as initial_bid_value,"0" as initial_project_agreed_value,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.initial_application_description as initial_bid_description,"0" as initial_project_agreed_delivery_period,"0" as initial_project_agreed_hourly_rate' */
			
			$result_array = $this->db // get the user detail
				->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,bd.initial_application_description,bd.initial_fulltime_project_agreed_salary,bd.employee_id as winner_id,bd.employer_id as project_owner_id,bd.employee_hired_on as project_start_date,bd.employee_id,bd.initial_fulltime_project_agreed_salary,bd.application_bidding_dropdown_option as bidding_dropdown_option,bd.fulltime_project_id as project_id')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
				
				->select('(SELECT count(*)  FROM '.$this->db->dbprefix .$active_dispute_table_name.' where employee_winner_id_of_disputed_fulltime_project = u.user_id and employer_id_of_disputed_fulltime_project = bd.employer_id and bd.fulltime_project_id = disputed_fulltime_project_id ) as total_employee_active_dispute_count')
				->from($inprogress_bidding_table_name.' bd')
				->join('users u', 'u.user_id = bd.employee_id', 'left')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('bd.fulltime_project_id', $project_id)
				->where('bd.employee_id', $sp_id)
				->having('total_employee_active_dispute_count > 0')
				->get()->row_array();
				
				
		}else{
		
		
		
			$this->db // get the user detail
			->select('SQL_CALC_FOUND_ROWS u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,'.$incomplete_inprogress_project_bid_fields,false)
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = bd.project_owner_id and bd.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($incomplete_bidding_table_name.' bd')
			->join('users u', 'u.user_id = bd.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('bd.project_id', $project_id)
			->where('bd.winner_id', $sp_id)
			->having('total_sp_active_dispute_count > 0');
			$active_disputes_incomplete_projects_query = $this->db->get_compiled_select();
			
			$this->db // get the user detail
			->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,'.$incomplete_inprogress_project_bid_fields)
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = bd.project_owner_id and bd.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($inprogress_bidding_table_name.' bd')
			->join('users u', 'u.user_id = bd.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('bd.project_id', $project_id)
			->where('bd.winner_id', $sp_id)
			->having('total_sp_active_dispute_count > 0');
			$active_disputes_inprogress_projects_query = $this->db->get_compiled_select();
			
			
			
			
			$this->db // get the user detail
			->select('u.user_id,u.account_type,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,'.$completed_project_bid_fields)
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_fixed_budget_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix . $active_dispute_table_name.' where sp_winner_id_of_disputed_project = u.user_id and project_owner_id_of_disputed_project = bd.project_owner_id and bd.project_id = disputed_project_id ) as total_sp_active_dispute_count')
			->from($completed_bidding_table_name.' bd')
			->join('users u', 'u.user_id = bd.winner_id', 'left')
			->join('users_details ud', 'ud.user_id = u.user_id', 'left')
			->where('bd.project_id', $project_id)
			->where('bd.winner_id', $sp_id)
			->having('total_sp_active_dispute_count > 0');
			$active_disputes_complete_projects_query = $this->db->get_compiled_select();
			
			
			
			
			$union_table_name = [
				 $active_disputes_incomplete_projects_query,
				 $active_disputes_inprogress_projects_query ,
				 $active_disputes_complete_projects_query,
			];

			$active_disputes_query = $this->db->query(implode(' UNION ', $union_table_name));
			$result_array  = $active_disputes_query->row_array();
		}	
			
		
		//->where('incompleted_bids.winner_id !=', $sp_id)
		//->having('total_sp_active_dispute_count = 0')
		//->get()->result_array();
			
		if(!empty($result_array)){
		######## connectivity of remote server start #######
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] = FTP_PORT;
		$config['debug'] = TRUE;
		$this->ftp->connect($config);
//foreach($result_array as $key=>$value){
				$common_source_path = USERS_FTP_DIR . $result_array['profile_name'];
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
				/* echo "<pre>";
				print_r($result_array);
				die; */
				//$bidder_list[$key] = $result_array;
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $result_array['user_id'])
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
				$result_array['bid_attachments'] = $bid_attachment_array;
				//$bidder_list[$key]['bid_attachments'] = $bid_attachment_array;
                 $this->ftp->close();
			}
              
		return $result_array;
		
	}
	
	
	/* This function is used to check that sp have dsiputed bid or not */
	public function check_sp_dispute_bid_exists_project_details ($project_id,$bidder_id,$project_type)
	{	
		
		if($project_type == 'fixed'){
			
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
		}
		if($project_type == 'hourly'){
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
		}
		if($project_type == 'fulltime'){
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
			
		}
		
		if(!empty($active_dispute_table_name)) {
			if($project_type == 'fulltime'){ 
				$sp_active_dispute_count = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project' => $bidder_id])->from($active_dispute_table_name)->count_all_results();
			} else {
				$sp_active_dispute_count = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project' => $bidder_id])->from($active_dispute_table_name)->count_all_results();
				
				
			}
		}
		return $sp_active_dispute_count;
	}
	
	
	public function get_disputed_project_escrows_history_listing($project_type,$conditions,$params){
		if($params['dispute_initiate_status'] == 0){
			if($project_type == 'fixed'){
				$disputed_escrows_history_tracking_table = 'fixed_budget_projects_active_escrows';
			} else if($project_type == 'hourly') {
				$disputed_escrows_history_tracking_table = 'hourly_rate_based_projects_active_escrows';
			} else if($project_type == 'fulltime') {
				$disputed_escrows_history_tracking_table = 'fulltime_projects_active_escrows';
			}
		}
		if($params['dispute_initiate_status'] == 1){
			
			if($project_type == 'fixed'){
				$disputed_escrows_history_tracking_table = 'fixed_budget_projects_disputed_escrows_history_tracking';
			} else if($project_type == 'hourly') {
				$disputed_escrows_history_tracking_table = 'hourly_rate_projects_disputed_escrows_history_tracking';
			} else if($project_type == 'fulltime') {
				$disputed_escrows_history_tracking_table = 'fulltime_projects_disputed_escrows_history_tracking';
			}
		}
		
		$this->db->select('am.*');
		$this->db->from($disputed_escrows_history_tracking_table.' am');
		$this->db->where($conditions);
		$this->db->order_by('am.escrow_creation_date','desc');
		
		$disputed_escrow_result = $this->db->get();
		$disputed_escrow_data = $disputed_escrow_result->result_array();
		
		return $disputed_escrow_data;
		
	}
	
	
	/* public function get_sum_disputed_escrows_amounts_project($project_type,$conditions,$params){
		
		if($params['dispute_initiate_status'] == 0){
			if($project_type == 'fixed'){
				$disputed_escrows_history_tracking_table = 'fixed_budget_projects_active_escrows';
			} else if($project_type == 'hourly') {
				$disputed_escrows_history_tracking_table = 'hourly_rate_based_projects_active_escrows';
			} else if($project_type == 'fulltime') {
				$disputed_escrows_history_tracking_table = 'fulltime_projects_active_escrows';
			}
		}
		if($params['dispute_initiate_status'] == 1){
			if($project_type == 'fixed'){
				$disputed_escrows_history_tracking_table = 'fixed_budget_projects_disputed_escrows_history_tracking';
			} else if($project_type == 'hourly') {
				$disputed_escrows_history_tracking_table = 'hourly_rate_based_projects_active_escrows';
			} else if($project_type == 'fulltime') {
				$disputed_escrows_history_tracking_table = 'fulltime_projects_active_escrows';
			}
		}
		if($params['view_type'] == 'po'){
			$this->db->select('SUM(total_escrow_payment_value) as sum_escrow_amount_value');
		}else{
			$this->db->select('SUM(created_escrow_amount) as sum_escrow_amount_value');
		}
		$this->db->from($disputed_escrows_history_tracking_table);
		$this->db->where($conditions);
		$sum_active_escrow_result = $this->db->get();
		$sum_active_escrow_result = $sum_active_escrow_result->row_array();
		return $sum_active_escrow_result['sum_escrow_amount_value'];
	} */
	
	// This function is using for automatic decission of project dispute
	public function close_automatically_arbitrated_dispute($data){
		if($data['project_type'] == 'fixed'){
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
			$active_dispute_counter_offer_table_name = 'fixed_budget_projects_active_disputes_counter_offers';
			$closed_dispute_table_name = 'fixed_budget_projects_closed_disputes';
			$closed_dispute_counter_offer_table_name = 'fixed_budget_projects_closed_disputes_counter_offers';
			$released_escrow_table_name = 'fixed_budget_projects_released_escrows';
			$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
			$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
			$incomplete_project_table_name = 'fixed_budget_projects_incomplete';
			$completed_project_table = 'fixed_budget_projects_completed';
			
			$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
			$inprogress_project_table_name = 'fixed_budget_projects_progress';
			$po_reverted_amount_tracking_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
			
			$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		}
		if($data['project_type'] == 'hourly'){
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
			$active_dispute_counter_offer_table_name = 'hourly_rate_based_projects_active_disputes_counter_offers';
			$closed_dispute_table_name = 'hourly_rate_based_projects_closed_disputes';
			$closed_dispute_counter_offer_table_name = 'hourly_rate_based_projects_closed_disputes_counter_offers';
			$released_escrow_table_name = 'hourly_rate_based_projects_released_escrows';
			$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
			$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
			$incomplete_project_table_name = 'hourly_rate_based_projects_incomplete';
			$completed_project_table = 'hourly_rate_based_projects_completed';
			
			$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
			$inprogress_project_table_name = 'hourly_rate_based_projects_progress';
			$po_reverted_amount_tracking_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';
			$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
		}
		if($data['project_type'] == 'fulltime'){
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
			$active_dispute_counter_offer_table_name = 'fulltime_projects_active_disputes_counter_offers';
			$closed_dispute_table_name = 'fulltime_projects_closed_disputes';
			$closed_dispute_counter_offer_table_name = 'fulltime_projects_closed_disputes_counter_offers';
			$released_escrow_table_name = 'fulltime_projects_released_escrows';
			
			$po_reverted_amount_tracking_table = 'fulltime_projects_closed_disputes_employer_reverted_amounts';
			$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
		}
		$dispute_reference_id = $data['dispute_reference_id'];
		
		$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $dispute_reference_id])->row_array();
		
		
		
		$project_id = ($data['project_type'] == 'fulltime')?$projects_active_disputes_data['disputed_fulltime_project_id']:$projects_active_disputes_data['disputed_project_id'];;
		
		
		
		
		if(!empty($projects_active_disputes_data) && $projects_active_disputes_data['disputed_amount'] < $minimum_required_disputed_project_value_for_admin_arbitration && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time()){
			
			
			
			$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$project_data = $this->db // get the user detail
				->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.employer_id', 'left')
				->where('pd.fulltime_project_id', $project_id)
				->get()->row_array();
			}else{
				$project_data = $this->db // get the user detail
				->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
			}
			$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
			$project_status = $project_status_table_array['project_status'];
			$profile_name = $project_data['profile_name'];
			
			$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
			$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
			
			$project_dispute_details_page_url = VPATH.$this->config->item('project_dispute_details_page_url')."?id=".$dispute_reference_id;
			
			$po_id = ($data['project_type'] == 'fulltime')?$projects_active_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_active_disputes_data['project_owner_id_of_disputed_project'];

			$sp_id = ($data['project_type'] == 'fulltime')?$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_active_disputes_data['sp_winner_id_of_disputed_project'];
			
			
			$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$po_id)->get()->row_array();
			
			$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
			
			$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,email,profile_name,gender')->from('users')->where('user_id',$sp_id)->get()->row_array();
			
			$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
			$current_date = date('Y-m-d H:i:s');
			
			
			
			$projects_closed_disputes_data = $projects_active_disputes_data;
			unset($projects_closed_disputes_data['id']);
			
			
			$released_escrow_payment_amount = $projects_active_disputes_data['disputed_amount']/2;
			
			$service_fee_charges = $projects_active_disputes_data['disputed_service_fees']/2;
			
			unset($projects_closed_disputes_data['disputed_latest_message_sent_time']);
			$projects_closed_disputes_data['dispute_end_date'] =$current_date; 
			$projects_closed_disputes_data['disputed_final_settlement_amount'] =str_replace(',','',number_format($released_escrow_payment_amount,2)); 
			$projects_closed_disputes_data['disputed_final_settlement_service_fees'] =str_replace(',','',number_format($service_fee_charges,2)); 
			$projects_closed_disputes_data['dispute_status'] ='automatic_decision'; 
			
			if($this->db->insert ($closed_dispute_table_name, $projects_closed_disputes_data)){
			//if(1){
				
				$this->db->update('projects_disputes_attachments', ['dispute_status'=>'closed'], ['dispute_reference_id'=>$dispute_reference_id]);
				$this->db->delete($active_dispute_table_name, ['dispute_reference_id' => $dispute_reference_id]);
				
				$counter_offer_result_array = $this->db // get the user detail
				->select('*')
				->from($active_dispute_counter_offer_table_name)
				->where(['dispute_reference_id'=>$dispute_reference_id])
				->get()->result_array();

				if(!empty($counter_offer_result_array)){
					foreach($counter_offer_result_array as $counter_offer_key=>$counter_offer_value){
						unset($counter_offer_value['id']);
						$counter_offer_value['counter_status'] = 'automatic_decision';
						$this->db->insert($closed_dispute_counter_offer_table_name, $counter_offer_value);
					}
					$this->db->delete($active_dispute_counter_offer_table_name, ['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']]);
					
				}
				
				if($data['project_type'] == 'fixed'){
					$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_dispute_project_decided_automatic_user_activity_log_displayed_message'); 
					$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_automatic_user_activity_log_displayed_message');
					
					$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_disputed_amount_service_fees}','{fixed_budget_project_50%_disputed_amount}','{fixed_budget_project_50%_disputed_amount_service_fees}'),array($dispute_reference_id,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					
					$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_50%_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
				
				}
				if($data['project_type'] == 'hourly'){
					$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_automatic_user_activity_log_displayed_message'); 
					$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_automatic_user_activity_log_displayed_message');
					
					$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_disputed_amount_service_fees}','{hourly_rate_based_project_50%_disputed_amount}','{hourly_rate_based_project_50%_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_50%_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
				}
				if($data['project_type'] == 'fulltime'){
					$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_dispute_project_decided_automatic_user_activity_log_displayed_message'); 
					$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_dispute_project_decided_automatic_user_activity_log_displayed_message');
					
					$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}','{fulltime_project_50%_disputed_amount}','{fulltime_project_50%_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_50%_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
				}
				
				
				$po_activity_log_message = str_replace(array('{user_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}'),array($sp_name,VPATH.$sp_data['profile_name'],$dispute_reference_id),$po_activity_log_message);
				
					
				$sp_activity_log_message = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$sp_activity_log_message);
					
				
				user_display_log($po_activity_log_message,$po_data['user_id']); 
				user_display_log($sp_activity_log_message,$sp_data['user_id']); 
				
				
				$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();

				$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();


				if($data['project_type'] == 'fixed'){
					$release_escrow_reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
				}
				if($data['project_type'] == 'hourly'){
					$release_escrow_reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
				}
				if($data['project_type'] == 'fulltime'){
					$release_escrow_reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
				}				

				
				
				$digits = $this->config->item('reference_id_digits_limit');
				if(empty($latest_released_escrow_data)) {
					$release_escrow_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
				} else {
					$exclude_inital_number = str_replace($release_escrow_reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
					$exclude_inital_number = ltrim($exclude_inital_number, '0');
					$exclude_inital_number = (int)$exclude_inital_number + 1;
					$release_escrow_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
				}
				
				$vat_percentage = $this->config->item('vat_percentage');

				$charged_service_fees_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
				$digits = $this->config->item('reference_id_digits_limit');

				if(empty($latest_charged_service_fees)) {
					$charged_service_fees_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
				} else {
					$exclude_inital_number = str_replace($charged_service_fees_reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
					$exclude_inital_number = ltrim($exclude_inital_number, '0');
					$exclude_inital_number = (int)$exclude_inital_number + 1;
					$charged_service_fees_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
				}
					
				//}
				// move data regarding released escrows 
				$released_escrow_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
				if($data['project_type'] == 'fulltime'){
					$released_escrow_data['fulltime_project_id'] = $project_id;
					$released_escrow_data['employer_id'] = $po_id;
					$released_escrow_data['employee_id'] = $sp_id;
				}else{	
					$released_escrow_data['project_id'] = $project_id;
					$released_escrow_data['project_owner_id'] = $po_id;
					$released_escrow_data['winner_id'] = $sp_id;
				}
				
				
			
				
				$released_escrow_data['released_escrow_payment_amount'] = str_replace(',','',number_format($released_escrow_payment_amount,2));
				$released_escrow_data['service_fee_charges'] = str_replace(',','',number_format($service_fee_charges,2));
				
				$released_escrow_data['total_escrow_payment_value'] = str_replace(',','',number_format(($released_escrow_payment_amount+$service_fee_charges),2));
				
				$released_escrow_data['payment_via_dispute'] = 'Y';
				$released_escrow_data['escrow_payment_release_date'] = $current_date;
				$released_escrow_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
				
				
				
				if($this->db->insert ($released_escrow_table_name, $released_escrow_data)){
				//if(1){
				
					
					#### tracking the amount transfer to po
					
					$po_reverted_amount_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
					if($data['project_type'] == 'fulltime'){
						$po_reverted_amount_data['disputed_fulltime_project_id'] = $project_id;
						$po_reverted_amount_data['employer_id'] = $po_id;
						$po_reverted_amount_data['employee_id'] = $sp_id;						
					}else{	
						$po_reverted_amount_data['disputed_project_id'] = $project_id;
						$po_reverted_amount_data['po_id'] = $po_id;
						$po_reverted_amount_data['sp_id'] = $sp_id;
					}
					
					$po_reverted_amount_data['dispute_close_date'] = $current_date;
					
					$po_reverted_amount_data['reverted_amount'] = str_replace(',','',number_format($released_escrow_payment_amount,2));
					$po_reverted_amount_data['reverted_service_fee_amount'] =str_replace(',','',number_format($service_fee_charges,2)); 
					$po_reverted_amount_data['reverted_total_amount'] = str_replace(',','',number_format(($released_escrow_payment_amount+$service_fee_charges),2));
					
					
					$this->db->insert ($po_reverted_amount_tracking_table, $po_reverted_amount_data);
					
					// For sp
					
					$this->db->set('user_account_balance', 'user_account_balance+'.str_replace(',','',number_format($released_escrow_payment_amount,2)), false);
					$this->db->where('user_id',$sp_data['user_id']);
					$this->db->update('users_details');
					
					// For po
					
					$this->db->set('user_account_balance', 'user_account_balance+'.str_replace(',','',number_format($released_escrow_payment_amount+$service_fee_charges,2)), false);
					$this->db->where('user_id',$po_data['user_id']);
					$this->db->update('users_details');
					
					$charged_service_fees_data['charged_service_fee_reference_id'] = $charged_service_fees_reference_id;
					$charged_service_fees_data['project_id'] = $project_id;
					$charged_service_fees_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
					$charged_service_fees_data['project_owner_id'] = $po_id;
					
					$charged_service_fees_data['payment_via_dispute'] = 'Y';
					$charged_service_fees_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
					
					$charged_service_fees_data['winner_id'] = $sp_id;
					
					if($data['project_type'] == 'fixed'){
						$charged_service_fees_data['project_type'] = 'fixed_budget';
					}
					if($data['project_type'] == 'hourly'){
						$charged_service_fees_data['project_type'] = 'hourly_rate';
					}
					if($data['project_type'] == 'fulltime'){
						$charged_service_fees_data['project_type'] = 'fulltime';
					}
					
					$charged_service_fees_data['escrow_payment_release_date'] = $released_escrow_data['escrow_payment_release_date'];
					$charged_service_fees_data['released_escrow_payment_amount'] = $released_escrow_data['released_escrow_payment_amount'];
					$charged_service_fees_data['charged_service_fee_value'] = $released_escrow_data['service_fee_charges'];
					$charged_service_fees_data['vat_percentage_value'] = $vat_percentage;
					
					
					$service_fee_value_excl_vat = ($released_escrow_data['service_fee_charges'] * 100 ) / ($vat_percentage + 100);
						
						
					$vat_amount = $released_escrow_data['service_fee_charges'] - $service_fee_value_excl_vat;
					
					
					$charged_service_fees_data['charged_service_fee_value_excl_vat'] = $service_fee_value_excl_vat;
					
					$charged_service_fees_data['charged_service_fee_vat_amount_value'] = $vat_amount;
					
					
					//$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
					$this->insert_data_into_charged_service_fees_tracking_disputes($charged_service_fees_data, $released_escrow_data);
					
					
					if($data['project_type'] == 'fulltime'){
							
						$released_escrow_condition = array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id);
						
					}else{
						$released_escrow_condition = array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
					}
					
					$total_released_escrow = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($data['project_type'],$released_escrow_condition);
					
					if($data['project_type'] == 'fulltime'){
						$this->db->update($closed_dispute_table_name, ['fulltime_project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
					}else{	
						$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
					}
					
					if($data['project_type'] != 'fulltime')
					{	
						$check_complete_bid_data_exists =  $this->db->where(['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($completed_bid_table_name)->count_all_results(); // check dsipute exists or not
					
						// update when bid exists in completed bid table
						if($check_complete_bid_data_exists != 0){
							$this->db->update($completed_bid_table_name, ['total_project_amount'=>$total_released_escrow], ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
						}
						
						$incomplete_bid_data = $this->db->get_where($incomplete_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
						if(!empty($incomplete_bid_data)){
								$bid_data = $incomplete_bid_data;;
						}
						$inprogress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
						if(!empty($inprogress_bid_data)){
								$bid_data = $inprogress_bid_data;;
						}
						
						############# start ##########
						if(!empty($bid_data)){
						
							if($total_released_escrow >= $bid_data['initial_project_agreed_value'] && $bid_data['initial_project_agreed_value'] != 0){
								
								
								if($data['project_type'] == 'fixed'){
									
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message'); 
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message');
								}
								if($data['project_type'] == 'hourly'){
									
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message'); 
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_automatic_project_completed_user_activity_log_displayed_message');
								}
								$sp_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{po_profile_url_link}'),array($project_url_link,$project_title,$po_name,VPATH.$po_data['profile_name']),$sp_activity_log_message);
								
								$po_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{sp_profile_url_link}'),array($project_url_link,$project_title,$sp_name,VPATH.$sp_data['profile_name']),$po_activity_log_message);
									
								
								user_display_log($po_activity_log_message,$po_data['user_id']); 
								user_display_log($sp_activity_log_message,$sp_data['user_id']);
								
								
								
								$completed_bid_data = $bid_data;
								$completed_bid_data['project_winner_work_completion_date'] = $current_date;
								$completed_bid_data['project_winner_work_start_date'] = $bid_data['project_start_date'];
								
								$completed_bid_data['total_project_amount'] = $total_released_escrow;
								unset($completed_bid_data['id']);
								unset($completed_bid_data['project_start_date']);
								unset($completed_bid_data['initial_project_agreed_delivery_period']);
								
								
								
								if($check_complete_bid_data_exists == 0){
									
									/* $completed_bid_data['project_value_after_dispute_close'] = $total_released_escrow; */
									
									$this->db->insert ($completed_bid_table_name, $completed_bid_data);// move data to completed internally tracking
									$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
								}
								
								
															
								$this->db->delete($incomplete_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
								$this->db->delete($inprogress_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
								
								 #### project move to completed table start####
								 
								 
								 $count_incomplete_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_bid_table_name)->count_all_results();
							
								$count_inprogress_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($inprogress_bid_table_name)->count_all_results();
								
								if($count_incomplete_bids == 0 && $count_inprogress_bids ==0){
										
									$project_completed_table_data_exists = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($completed_project_table)->count_all_results();
									if($project_completed_table_data_exists == 0){
										
										
										if($project_status == 'incomplete'){
											$incomplete_project_detail = $this->db->get_where($incomplete_project_table_name, ['project_id'=>$project_data['project_id']])->row_array();
											$complete_project_detail = $incomplete_project_detail;
										}
										if($project_status == 'in_progress'){
											$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$project_data['project_id']])->row_array();
											$complete_project_detail = $inprogress_project_detail;
										}
										
										//unset($project_data['profile_name']);
										unset($complete_project_detail['id']);
										$complete_project_detail['project_completion_date'] = $current_date;
										$this->db->insert ($completed_project_table, $complete_project_detail);// 
										
										$this->db->delete($project_status_table_array['table_name'], ['project_id' =>$projects_active_disputes_data['disputed_project_id']]); 
										
										$project_id = $projects_active_disputes_data['disputed_project_id'];
										
										
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
										$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
										$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
										$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
									
										$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
										$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
										$project_completed_dir = PROJECT_COMPLETED_DIR;
										
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
										
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir);
										
										
										//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
										
										//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir, 0777);// create awaiting_moderation directory in projects folder
										//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id , 0777); // create the directory by using  project id
										//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
										$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
										if($project_status == 'incomplete'){
											$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir;
										}
										if($project_status == 'in_progress'){
											$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
										}
										
										$source_list = $this->ftp->list_files($source_path);
										if(!empty($source_list)) {
											foreach($source_list as $path) {
												$arr = explode('/', $path);
												$file_size = $this->ftp->get_filesize($path);
												if($file_size != '-1') {
													$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir.end($arr);
													$this->ftp->move($path, $destination_path);
												}
											}
										}
										$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
										
										if(!empty($bid_attachments)){
											foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
												$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
												if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
													//die("sdfsdff");
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
													
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
													
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
													//die("fsdffdf");
													
													if($project_status == 'incomplete'){
														$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
													}
													if($project_status == 'in_progress'){
														
														$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
													}
													$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
													if($file_size != '-1')
													{
														$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
														$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
														
													}
												}
											}
										}
										if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id))) {
											$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id);
										}
										if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
											$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
										}
										$this->ftp->close(); 
										
									}
									
								}
							#### project move to completed table end####
							}else{
								
								
							 // if project value is not equal to throsold value project/ bid moves to incomplete if project/ bid exists into inprogress exists	
							 
								$inprogress_bid_detail = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
								if(!empty($inprogress_bid_detail)){
									$check_already_in_complete_bid = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($incomplete_bid_table_name)->count_all_results();
									if($check_already_in_complete_bid == 0){
										unset($inprogress_bid_detail['id']);
										$this->db->insert ($incomplete_bid_table_name, $inprogress_bid_detail);
										$this->db->delete($inprogress_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id]); 
										 
										//$count_complete_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($completed_bid_table_name)->count_all_results();
										
										/* if($count_incomplete_bids == 0 && $count_complete_bids == 0){ */
										$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']])->row_array();
										
										if(!empty($inprogress_project_detail)){
											
											$check_already_in_complete_project = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_project_table_name)->count_all_results();
											
											if($check_already_in_complete_project == 0){
												
												unset($inprogress_project_detail['id']);
												$this->db->insert ($incomplete_project_table_name, $inprogress_project_detail);
												
												$this->db->delete($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']]); 
												
												
												######## connectivity of remote server start#########
												$this->load->library('ftp');
												$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
												$config['ftp_username'] = FTP_USERNAME;
												$config['ftp_password'] = FTP_PASSWORD;
												$config['ftp_port'] 	= FTP_PORT;
												$config['debug']    = TRUE;
												$this->ftp->connect($config); 
												######## connectivity of remote server end #######
												
												$project_id = $projects_active_disputes_data['disputed_project_id'];
												$users_ftp_dir 	= USERS_FTP_DIR; 
												$projects_ftp_dir = PROJECTS_FTP_DIR;
												$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
												$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
												
												$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
												$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
												
												
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
												
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir);
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR);
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir);
												
												
												//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
												
												//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir, 0777);// create awaiting_moderation directory in projects folder
												//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id , 0777); // create the directory by using  project id
												//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
												$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR;
												
												$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
												
												
												$source_list = $this->ftp->list_files($source_path);
												if(!empty($source_list)) {
													foreach($source_list as $path) {
														$arr = explode('/', $path);
														$file_size = $this->ftp->get_filesize($path);
														if($file_size != '-1') {
															$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir.end($arr);
															$this->ftp->move($path, $destination_path);
														}
													}
												}
												$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
												
												if(!empty($bid_attachments)){
													foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
														$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
														if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
															//die("sdfsdff");
															
															
															$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir);
															$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
															
															
															//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
															
															//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
															//die("fsdffdf");
															
															
																
															$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
															
															$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
															if($file_size != '-1')
															{
																$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
																
															}
														}
													}
												}
												
												if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
													$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
												}
												$this->ftp->close();
												
											}	
											
										}
											
										//}
									}
								}
							}	
						}
					}
					#################### end ########
				}
				
				###########################
				#### Email code when dispute is closed start ##
				//$project_dispute_details_page_url
				###################### SMTP variables start here ##########
				$config['protocol'] = PROTOCOL;
				$config['smtp_host']    = SMTP_HOST;
				$config['smtp_port']    = SMTP_PORT;
				$config['smtp_timeout'] = SMTP_TIMEOUT;
				$config['smtp_user']    = SMTP_USER;
				$config['smtp_pass']    = SMTP_PASS;
				$config['charset'] = CHARSET;
				$config['mailtype'] = MAILTYPE;
				$config['newline'] = NEWLINE;	
				###################### SMTP variables end here ##########
				$this->load->library ('email');
				$this->email->initialize($config);
				//$data['project_type'] != 'fulltime'
				//$data['project_type'] != 'fulltime'
				// For Po
				$po_to = $po_data['email'];
				$sp_to = $sp_data['email'];
				if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					
					if($po_data['gender'] == 'M'){
						
						if($data['project_type'] == 'fulltime'){
							$po_cc = $this->config->item('fulltime_project_dispute_email_cc_sent_to_male_employer_dispute_decided_automatic');
							$po_bcc = $this->config->item('fulltime_project_dispute_email_bcc_sent_to_male_employer_dispute_decided_automatic');
							$po_from = $this->config->item('fulltime_project_dispute_email_from_sent_to_male_employer_dispute_decided_automatic');
							$po_reply_to = $this->config->item('fulltime_project_dispute_email_reply_to_sent_to_male_employer_dispute_decided_automatic');
							$po_from_name = $this->config->item('fulltime_project_dispute_email_from_name_sent_to_male_employer_dispute_decided_automatic');
							$po_subject = $this->config->item('fulltime_project_dispute_email_subject_sent_to_male_employer_dispute_decided_automatic');
							$po_message = $this->config->item('fulltime_project_dispute_email_message_sent_to_male_employer_dispute_decided_automatic');
						}else{	
							$po_cc = $this->config->item('project_dispute_email_cc_sent_to_male_po_dispute_decided_automatic');
							$po_bcc = $this->config->item('project_dispute_email_bcc_sent_to_male_po_dispute_decided_automatic');
							$po_from = $this->config->item('project_dispute_email_from_sent_to_male_po_dispute_decided_automatic');
							$po_reply_to = $this->config->item('project_dispute_email_reply_to_sent_to_male_po_dispute_decided_automatic');
							$po_from_name = $this->config->item('project_dispute_email_from_name_sent_to_male_po_dispute_decided_automatic');
							$po_subject = $this->config->item('project_dispute_email_subject_sent_to_male_po_dispute_decided_automatic');
							$po_message = $this->config->item('project_dispute_email_message_sent_to_male_po_dispute_decided_automatic');
						}	
						
						
					}
					if($po_data['gender'] == 'F'){
						if($data['project_type'] == 'fulltime'){
							
							$po_cc = $this->config->item('fulltime_project_dispute_email_cc_sent_to_female_employer_dispute_decided_automatic');
							$po_bcc = $this->config->item('fulltime_project_dispute_email_bcc_sent_to_female_employer_dispute_decided_automatic');
							$po_from = $this->config->item('fulltime_project_dispute_email_from_sent_to_female_employer_dispute_decided_automatic');
							$po_reply_to = $this->config->item('fulltime_project_dispute_email_reply_to_sent_to_female_employer_dispute_decided_automatic');
							$po_from_name = $this->config->item('fulltime_project_dispute_email_from_name_sent_to_female_employer_dispute_decided_automatic');
							$po_subject = $this->config->item('fulltime_project_dispute_email_subject_sent_to_female_employer_dispute_decided_automatic');
							$po_message = $this->config->item('fulltime_project_dispute_email_message_sent_to_female_employer_dispute_decided_automatic');

							
						}else{	
							$po_cc = $this->config->item('project_dispute_email_cc_sent_to_female_po_dispute_decided_automatic');
							$po_bcc = $this->config->item('project_dispute_email_bcc_sent_to_female_po_dispute_decided_automatic');
							$po_from = $this->config->item('project_dispute_email_from_sent_to_female_po_dispute_decided_automatic');
							$po_reply_to = $this->config->item('project_dispute_email_reply_to_sent_to_female_po_dispute_decided_automatic');
							$po_from_name = $this->config->item('project_dispute_email_from_name_sent_to_female_po_dispute_decided_automatic');
							$po_subject = $this->config->item('project_dispute_email_subject_sent_to_female_po_dispute_decided_automatic');
							$po_message = $this->config->item('project_dispute_email_message_sent_to_female_po_dispute_decided_automatic');
						}
						
					}	
					if($data['project_type'] == 'fulltime'){
						$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}','{fulltime_project_50%_disputed_amount}','{fulltime_project_50%_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_message);
					}else{
						$po_message = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}','{project_50%_disputed_amount}','{project_50%_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_message);
					}		
					$po_message = str_replace(array('{po_first_name_last_name}','{user_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$dispute_reference_id,$project_dispute_details_page_url),$po_message);
					
				}else{
					if($data['project_type'] == 'fulltime'){
						$po_cc = $this->config->item('fulltime_project_dispute_email_cc_sent_to_company_employer_dispute_decided_automatic');
						$po_bcc = $this->config->item('fulltime_project_dispute_email_bcc_sent_to_company_employer_dispute_decided_automatic');
						$po_from = $this->config->item('fulltime_project_dispute_email_from_sent_to_company_employer_dispute_decided_automatic');
						$po_reply_to = $this->config->item('fulltime_project_dispute_email_reply_to_sent_to_company_employer_dispute_decided_automatic');
						$po_from_name = $this->config->item('fulltime_project_dispute_email_from_name_sent_to_company_employer_dispute_decided_automatic');
						$po_subject = $this->config->item('fulltime_project_dispute_email_subject_sent_to_company_employer_dispute_decided_automatic');
						$po_message = $this->config->item('fulltime_project_dispute_email_message_sent_to_company_employer_dispute_decided_automatic');

					}else{	
						$po_cc = $this->config->item('project_dispute_email_cc_sent_to_company_po_dispute_decided_automatic');
						$po_bcc = $this->config->item('project_dispute_email_bcc_sent_to_company_po_dispute_decided_automatic');
						$po_from = $this->config->item('project_dispute_email_from_sent_to_company_po_dispute_decided_automatic');
						$po_reply_to = $this->config->item('project_dispute_email_reply_to_sent_to_company_po_dispute_decided_automatic');
						$po_from_name = $this->config->item('project_dispute_email_from_name_sent_to_company_po_dispute_decided_automatic');
						$po_subject = $this->config->item('project_dispute_email_subject_sent_to_company_po_dispute_decided_automatic');
						$po_message = $this->config->item('project_dispute_email_message_sent_to_company_po_dispute_decided_automatic');
					}	
					
					if($data['project_type'] == 'fulltime'){
						
						$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}','{fulltime_project_50%_disputed_amount}','{fulltime_project_50%_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_message);
						
					}else{
						$po_message = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}','{project_50%_disputed_amount}','{project_50%_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($service_fee_charges,  2, '.', ' '))." ". CURRENCY),$po_message);

					}				
					
					$po_message = str_replace(array('{po_company_name}','{user_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$dispute_reference_id,$project_dispute_details_page_url),$po_message);
				}		
				
				$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
				$po_subject = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$po_subject);
				$this->email->from ($po_from,$po_from_name);
				$this->email->to ($po_to);
				if($po_cc){
					$this->email->cc ($po_cc);
				}
				if($po_bcc){
					$this->email->bcc ($po_bcc);
				}
				$this->email->subject ($po_subject);
				$this->email->reply_to($po_reply_to);
				$this->email->set_mailtype ('html');
				$this->email->set_newline("\r\n");
				$this->email->message ($po_message);
				$this->email->send ();
				
				## For Sp
				if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					
					if($sp_data['gender'] == 'M'){
						
						if($data['project_type'] == 'fulltime'){
							$sp_cc = $this->config->item('fulltime_project_dispute_email_cc_sent_to_male_employee_dispute_decided_automatic');
							$sp_bcc = $this->config->item('fulltime_project_dispute_email_bcc_sent_to_male_employee_dispute_decided_automatic');
							$sp_from = $this->config->item('fulltime_project_dispute_email_from_sent_to_male_employee_dispute_decided_automatic');
							$sp_reply_to = $this->config->item('fulltime_project_dispute_email_reply_to_sent_to_male_employee_dispute_decided_automatic');
							$sp_from_name = $this->config->item('fulltime_project_dispute_email_from_name_sent_to_male_employee_dispute_decided_automatic');
							$sp_subject = $this->config->item('fulltime_project_dispute_email_subject_sent_to_male_employee_dispute_decided_automatic');
							$sp_message = $this->config->item('fulltime_project_dispute_email_message_sent_to_male_employee_dispute_decided_automatic');

						}else{
							$sp_cc = $this->config->item('project_dispute_email_cc_sent_to_male_sp_dispute_decided_automatic');
							$sp_bcc = $this->config->item('project_dispute_email_bcc_sent_to_male_sp_dispute_decided_automatic');
							$sp_from = $this->config->item('project_dispute_email_from_sent_to_male_sp_dispute_decided_automatic');
							$sp_reply_to = $this->config->item('project_dispute_email_reply_to_sent_to_male_sp_dispute_decided_automatic');
							$sp_from_name = $this->config->item('project_dispute_email_from_name_sent_to_male_sp_dispute_decided_automatic');
							$sp_subject = $this->config->item('project_dispute_email_subject_sent_to_male_sp_dispute_decided_automatic');
							$sp_message = $this->config->item('project_dispute_email_message_sent_to_male_sp_dispute_decided_automatic');
						}
						
					}
					if($sp_data['gender'] == 'F'){
						if($data['project_type'] == 'fulltime'){
							$sp_cc = $this->config->item('fulltime_project_dispute_email_cc_sent_to_female_employee_dispute_decided_automatic');
							$sp_bcc = $this->config->item('fulltime_project_dispute_email_bcc_sent_to_female_employee_dispute_decided_automatic');
							$sp_from = $this->config->item('fulltime_project_dispute_email_from_sent_to_female_employee_dispute_decided_automatic');
							$sp_reply_to = $this->config->item('fulltime_project_dispute_email_reply_to_sent_to_female_employee_dispute_decided_automatic');
							$sp_from_name = $this->config->item('fulltime_project_dispute_email_from_name_sent_to_female_employee_dispute_decided_automatic');
							$sp_subject = $this->config->item('fulltime_project_dispute_email_subject_sent_to_female_employee_dispute_decided_automatic');
							$sp_message = $this->config->item('fulltime_project_dispute_email_message_sent_to_female_employee_dispute_decided_automatic');
						}else{
							$sp_cc = $this->config->item('project_dispute_email_cc_sent_to_female_sp_dispute_decided_automatic');
							$sp_bcc = $this->config->item('project_dispute_email_bcc_sent_to_female_sp_dispute_decided_automatic');
							$sp_from = $this->config->item('project_dispute_email_from_sent_to_female_sp_dispute_decided_automatic');
							$sp_reply_to = $this->config->item('project_dispute_email_reply_to_sent_to_female_sp_dispute_decided_automatic');
							$sp_from_name = $this->config->item('project_dispute_email_from_name_sent_to_female_sp_dispute_decided_automatic');
							$sp_subject = $this->config->item('project_dispute_email_subject_sent_to_female_sp_dispute_decided_automatic');
							$sp_message = $this->config->item('project_dispute_email_message_sent_to_female_sp_dispute_decided_automatic');
						}
						
					}	
					if($data['project_type'] == 'fulltime'){
						$sp_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_50%_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_message);
					}else{
						$sp_message = str_replace(array('{project_disputed_amount}','{project_50%_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_message);
					}		
					$sp_message = str_replace(array('{sp_first_name_last_name}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$dispute_reference_id,$project_dispute_details_page_url),$sp_message);
					
				}else{
					if($data['project_type'] == 'fulltime'){
						$sp_cc = $this->config->item('fulltime_project_dispute_email_cc_sent_to_company_employee_dispute_decided_automatic');
						$sp_bcc = $this->config->item('fulltime_project_dispute_email_bcc_sent_to_company_employee_dispute_decided_automatic');
						$sp_from = $this->config->item('fulltime_project_dispute_email_from_sent_to_company_employee_dispute_decided_automatic');
						$sp_reply_to = $this->config->item('fulltime_project_dispute_email_reply_to_sent_to_company_employee_dispute_decided_automatic');
						$sp_from_name = $this->config->item('fulltime_project_dispute_email_from_name_sent_to_company_employee_dispute_decided_automatic');
						$sp_subject = $this->config->item('fulltime_project_dispute_email_subject_sent_to_company_employee_dispute_decided_automatic');
						$sp_message = $this->config->item('fulltime_project_dispute_email_message_sent_to_company_employee_dispute_decided_automatic');	
						$sp_message = str_replace(array('{sp_company_name}','{dispute_reference_id}','{project_dispute_details_page_url}','{fulltime_project_disputed_amount}','{fulltime_project_50%_disputed_amount}'),array($sp_name,$dispute_reference_id,$project_dispute_details_page_url,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_message);
					}else{
						$sp_cc = $this->config->item('project_dispute_email_cc_sent_to_company_sp_dispute_decided_automatic');
						$sp_bcc = $this->config->item('project_dispute_email_bcc_sent_to_company_sp_dispute_decided_automatic');
						$sp_from = $this->config->item('project_dispute_email_from_sent_to_company_sp_dispute_decided_automatic');
						$sp_reply_to = $this->config->item('project_dispute_email_reply_to_sent_to_company_sp_dispute_decided_automatic');
						$sp_from_name = $this->config->item('project_dispute_email_from_name_sent_to_company_sp_dispute_decided_automatic');
						$sp_subject = $this->config->item('project_dispute_email_subject_sent_to_company_sp_dispute_decided_automatic');
						$sp_message = $this->config->item('project_dispute_email_message_sent_to_company_sp_dispute_decided_automatic');	
						$sp_message = str_replace(array('{sp_company_name}','{dispute_reference_id}','{project_dispute_details_page_url}','{project_disputed_amount}','{project_50%_disputed_amount}'),array($sp_name,$dispute_reference_id,$project_dispute_details_page_url,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($released_escrow_payment_amount,  2, '.', ' '))." ". CURRENCY),$sp_message);
					}
				}		
				
				$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';
				$sp_subject = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$sp_subject);
				$this->email->from ($sp_from,$sp_from_name);
				$this->email->to ($sp_to);
				if($sp_cc){
					$this->email->cc ($sp_cc);
				}
				if($sp_bcc){
					$this->email->bcc ($sp_bcc);
				}
				$this->email->subject ($sp_subject);
				$this->email->reply_to($sp_reply_to);
				$this->email->set_mailtype ('html');
				$this->email->set_newline("\r\n");
				$this->email->message ($sp_message);
				$this->email->send ();
				#### Email code when dispute is closed end ##
			}
		}
	}	
	
	public function save_projects_disputes_admin_arbitration_fees($data){
		if($data['project_type'] == 'fixed'){
			$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
			$admin_dispute_arbitration_fee = $this->config->item('fixed_budget_project_admin_dispute_arbitration_percentage_fee');
		}
		if($data['project_type'] == 'hourly'){
			$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
			$admin_dispute_arbitration_fee = $this->config->item('hourly_rate_based_project_admin_dispute_arbitration_percentage_fee');
		}
		if($data['project_type'] == 'fulltime'){
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
			$admin_dispute_arbitration_fee = $this->config->item('fulltime_project_admin_dispute_arbitration_percentage_fee');
		}
		$dispute_reference_id = $data['dispute_reference_id'];
		$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $dispute_reference_id])->row_array();
		if(!empty($projects_active_disputes_data)){
			
			$check_disputes_admin_arbitration_fees = $this->db->where(['dispute_reference_id' => $dispute_reference_id])->from('projects_disputes_admin_arbitration_fees_tracking')->count_all_results();
			
			if($check_disputes_admin_arbitration_fees == 0){
				if($data['project_type'] == 'fixed'){
					$admin_arbitration_fees_data['project_type'] =  'fixed_budget';
				}
				if($data['project_type'] == 'hourly'){
					$admin_arbitration_fees_data['project_type'] =  'hourly_rate';
				}
				if($data['project_type'] == 'fulltime'){
					$admin_arbitration_fees_data['project_type'] =  'fulltime';
				}
				if($data['project_type'] == 'fulltime'){
					$admin_arbitration_fees_data['disputed_project_id'] = $projects_active_disputes_data['disputed_fulltime_project_id'];
					$admin_arbitration_fees_data['project_owner_id_of_disputed_project'] = $projects_active_disputes_data['employer_id_of_disputed_fulltime_project'];
					$admin_arbitration_fees_data['sp_winner_id_of_disputed_project'] = $projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project'];
				}else{
					$admin_arbitration_fees_data['disputed_project_id'] = $projects_active_disputes_data['disputed_project_id'];
					$admin_arbitration_fees_data['project_owner_id_of_disputed_project'] = $projects_active_disputes_data['project_owner_id_of_disputed_project'];
					$admin_arbitration_fees_data['sp_winner_id_of_disputed_project'] = $projects_active_disputes_data['sp_winner_id_of_disputed_project'];
				}
				
				$admin_arbitration_fees_data['dispute_reference_id'] = $dispute_reference_id;
				
				$admin_arbitration_fees_data['dispute_initiated_by_user_id'] = $projects_active_disputes_data['dispute_initiated_by_user_id'];
				$admin_arbitration_fees_data['disputed_against_user_id'] = $projects_active_disputes_data['disputed_against_user_id'];
				$admin_arbitration_fees_data['disputed_amount'] = $projects_active_disputes_data['disputed_amount'];
				$admin_arbitration_fees_data['dispute_negotiation_end_date'] = $projects_active_disputes_data['dispute_negotiation_end_date'];
				$admin_arbitration_fees_data['admin_dispute_arbitration_percentage_fee'] = $admin_dispute_arbitration_fee;
				$admin_dispute_arbitration_amount_fee = ($admin_dispute_arbitration_fee/100)*$projects_active_disputes_data['disputed_amount'];
				
				$admin_dispute_arbitration_amount_fee = number_format($admin_dispute_arbitration_amount_fee,2);
				$admin_dispute_arbitration_amount_fee = str_replace(",","",$admin_dispute_arbitration_amount_fee);
				
				$admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'] = $admin_dispute_arbitration_amount_fee;
				
				$vat_percentage = $this->config->item('vat_percentage');
				
				$admin_dispute_arbitration_net_value = $admin_dispute_arbitration_amount_fee * 100 / (100+$vat_percentage);
				$admin_dispute_arbitration_net_value = number_format($admin_dispute_arbitration_net_value,2);
				$admin_dispute_arbitration_net_value = str_replace(",","",$admin_dispute_arbitration_net_value);
				
				$admin_arbitration_fees_data['vat_percentage_value'] = $vat_percentage;
				$admin_arbitration_fees_data['admin_dispute_arbitration_net_value'] = $admin_dispute_arbitration_net_value;
				
				$admin_arbitration_fees_data['admin_dispute_arbitration_amount_vat_value'] =  str_replace(",","",number_format($admin_dispute_arbitration_amount_fee-$admin_dispute_arbitration_net_value,2));
				
				$admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'] =  str_replace(",","",number_format($projects_active_disputes_data['disputed_amount'] - $admin_dispute_arbitration_amount_fee,2));
				$this->db->insert('projects_disputes_admin_arbitration_fees_tracking', $admin_arbitration_fees_data);
				
				
			}	
			
		}
		
	}		
	
	
	/**
	* This function is used to fetch the all active disputes listing of all projects for po.
	*/
	public function get_all_active_disputes_listing_projects_po($user_id,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		
		$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
		$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
		
		$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
		
		$fixed_budget_project_active_disputes_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.project_id,pd.project_owner_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status';
		
		$hourly_project_active_disputes_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.project_id,pd.project_owner_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status';
		
		$fulltime_project_disputes_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.fulltime_project_id as project_id,pd.employer_id as project_owner_id,pd.fulltime_project_title as project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status';
		

		// for fixed Budget
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_project_active_disputes_fields,false);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		/* $this->db->where('ad.project_owner_id_of_disputed_project',$user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()')->or_where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value); */
		$this->db->group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		// for hourly Budget
		$this->db->select($hourly_project_active_disputes_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project',$user_id);
		$this->db->group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$hourly_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$hourly_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->or_group_start();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$hourly_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		############ for fulltime project
		
		
		$this->db->select("u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.project_id,pd.project_owner_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status");
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_open_projects_active_disputes_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_project_disputes_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_expired_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_disputes_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_disputes_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_by_admin_projects_active_disputes_query = $this->db->get_compiled_select();
		
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_active_disputes_query,
			$fixed_budget_incomplete_projects_active_disputes_query, 
			$fixed_budget_completed_projects_active_disputes_query,
			$hourly_inprogress_projects_active_disputes_query,
			$hourly_incomplete_projects_active_disputes_query,
			$hourly_completed_projects_active_disputes_query,
			$fulltime_open_projects_active_disputes_query,
			$fulltime_expired_projects_active_disputes_query,
			$fulltime_cancelled_projects_active_disputes_query,
			$fulltime_cancelled_by_admin_projects_active_disputes_query
			
		];
		
		$active_disputes_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY dispute_start_date DESC LIMIT '.$limit_range);
		$result  = $active_disputes_projects_query->result_array();
	
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	
	/**
	* This function is used to fetch the all active disputes listing of all projects for sp.
	*/
	public function get_all_active_disputes_listing_projects_sp($user_id,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		
		$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
		$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
		
		$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
		
		$fixed_budget_project_active_disputes_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.project_id,pd.project_owner_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status';
		
		$hourly_project_active_disputes_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.project_id,pd.project_owner_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status';
		
		$fulltime_project_disputes_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.fulltime_project_id as project_id,pd.employer_id as project_owner_id,pd.fulltime_project_title as project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status';
		

		// for fixed Budget
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_project_active_disputes_fields,false);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.project_owner_id_of_disputed_project');
		$fixed_budget_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.project_owner_id_of_disputed_project');
		$fixed_budget_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		/* $this->db->where('ad.project_owner_id_of_disputed_project',$user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()')->or_where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value); */
		$this->db->group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fixed_budget_project_admin_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.project_owner_id_of_disputed_project');
		$fixed_budget_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		// for hourly Budget
		$this->db->select($hourly_project_active_disputes_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project',$user_id);
		$this->db->group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$hourly_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.project_owner_id_of_disputed_project');
		$hourly_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$hourly_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.project_owner_id_of_disputed_project');
		$hourly_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->or_group_start();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$hourly_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ad.project_owner_id_of_disputed_project');
		$hourly_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		############ for fulltime project
		
		
		$this->db->select("u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,pd.project_id,pd.project_owner_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date,ad.dispute_status");
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employer_id_of_disputed_fulltime_project');
		$fulltime_open_projects_active_disputes_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_project_disputes_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employer_id_of_disputed_fulltime_project');
		$fulltime_expired_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_disputes_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employer_id_of_disputed_fulltime_project');
		$fulltime_cancelled_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_disputes_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date >= NOW()');
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where("ad.disputed_amount >=",$fulltime_admin_project_arbitration_value);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_status', 'under_admin_review');
		$this->db->group_end();
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ad.employer_id_of_disputed_fulltime_project');
		$fulltime_cancelled_by_admin_projects_active_disputes_query = $this->db->get_compiled_select();
		
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_active_disputes_query,
			$fixed_budget_incomplete_projects_active_disputes_query, 
			$fixed_budget_completed_projects_active_disputes_query,
			$hourly_inprogress_projects_active_disputes_query,
			$hourly_incomplete_projects_active_disputes_query,
			$hourly_completed_projects_active_disputes_query,
			$fulltime_open_projects_active_disputes_query,
			$fulltime_expired_projects_active_disputes_query,
			$fulltime_cancelled_projects_active_disputes_query,
			$fulltime_cancelled_by_admin_projects_active_disputes_query
			
		];
		
		$active_disputes_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY dispute_start_date DESC LIMIT '.$limit_range);
		$result  = $active_disputes_projects_query->result_array();
	
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	// This function is using to count the all active disputes for dispute page regarding paging for po
	public function get_all_active_disputes_listing_projects_count_po($user_id){
		$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
			$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			
			$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			
			$total_record = $this->db
			->from('fixed_budget_projects_active_disputes')
			->group_start()
			->where('project_owner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date >= NOW()')
			->group_end()
			->or_group_start()
			->where('project_owner_id_of_disputed_project', $user_id)
			->where("disputed_amount >=",$fixed_budget_project_admin_arbitration_value)
			->group_end()
			->or_group_start()
			->where('project_owner_id_of_disputed_project', $user_id)
			->where('dispute_status', 'under_admin_review')
			->group_end()
			->count_all_results();
			
			$total_record += $this->db
			->from('hourly_rate_based_projects_active_disputes')
			->group_start()
			->where('project_owner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date >= NOW()')
			->group_end()
			->or_group_start()
			->where('project_owner_id_of_disputed_project', $user_id)
			->where("disputed_amount >=",$hourly_admin_project_arbitration_value)
			->group_end()
			->or_group_start()
			->where('project_owner_id_of_disputed_project', $user_id)
			->where('dispute_status', 'under_admin_review')
			->group_end()
			->count_all_results();
			
			$total_record += $this->db
			->from('fulltime_projects_active_disputes')
			->group_start()
			->where('employer_id_of_disputed_fulltime_project', $user_id)
			->where('dispute_negotiation_end_date >= NOW()')
			->group_end()
			->or_group_start()
			->where('employer_id_of_disputed_fulltime_project', $user_id)
			->where("disputed_amount >=",$fulltime_admin_project_arbitration_value)
			->group_end()
			->or_group_start()
			->where('employer_id_of_disputed_fulltime_project', $user_id)
			->where('dispute_status', 'under_admin_review')
			->group_end()
			->count_all_results();
			
			return $total_record;
	}	
	
	// This function is using to count the all active disputes for dispute page regarding paging for sp
	public function get_all_active_disputes_listing_projects_count_sp($user_id){
		$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
			$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			
			$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			
			$total_record = $this->db
			->from('fixed_budget_projects_active_disputes')
			->group_start()
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date >= NOW()')
			->group_end()
			->or_group_start()
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where("disputed_amount >=",$fixed_budget_project_admin_arbitration_value)
			->group_end()
			->or_group_start()
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where('dispute_status', 'under_admin_review')
			->group_end()
			->count_all_results();
			
			$total_record += $this->db
			->from('hourly_rate_based_projects_active_disputes')
			->group_start()
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date >= NOW()')
			->group_end()
			->or_group_start()
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where("disputed_amount >=",$hourly_admin_project_arbitration_value)
			->group_end()
			->or_group_start()
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where('dispute_status', 'under_admin_review')
			->group_end()
			->count_all_results();
			
			$total_record += $this->db
			->from('fulltime_projects_active_disputes')
			->group_start()
			->where('employee_winner_id_of_disputed_fulltime_project', $user_id)
			->where('dispute_negotiation_end_date >= NOW()')
			->group_end()
			->or_group_start()
			->where('employee_winner_id_of_disputed_fulltime_project', $user_id)
			->where("disputed_amount >=",$fulltime_admin_project_arbitration_value)
			->group_end()
			->or_group_start()
			->where('employee_winner_id_of_disputed_fulltime_project', $user_id)
			->where('dispute_status', 'under_admin_review')
			->group_end()
			->count_all_results();
			
			return $total_record;
	}


	/**
	* This function is used to fetch the all closed disputes listing of all projects for po.
	*/
	public function get_all_closed_disputes_listing_projects_po($user_id,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		
		$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
		$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
		
		$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
		
		$fixed_budget_project_active_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees';
		
		$hourly_project_active_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees';
		
		$fulltime_project_active_disputes_fields = 'pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees';
		
		#################### for closed dispute
		$fixed_budget_project_closed_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees';
		
		$hourly_project_closed_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees';
		
		$fulltime_project_closed_disputes_fields = 'pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees';
		
		
		
		$user_fields = 'po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.account_type as po_account_type,po.gender as po_gender,po.profile_name,sp.first_name as sp_first_name,sp.last_name as sp_last_name,po.company_name as sp_company_name,sp.account_type as sp_account_type,sp.gender as sp_gender,sp.profile_name';
		
		
		

		// for fixed Budget
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_project_active_disputes_fields.','.$user_fields,false);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fixed_budget_project_admin_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		
		$fixed_budget_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fixed_budget_project_admin_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fixed_budget_project_admin_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		// for hourly Budget
		$this->db->select($hourly_project_active_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$hourly_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$hourly_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$hourly_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		############ for fulltime project
		
		
		$this->db->select('pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees'.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		
		
		
		
		$fulltime_open_projects_active_disputes_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_expired_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_by_admin_projects_active_disputes_query = $this->db->get_compiled_select();
		
		########################### For closed dispute #################
		
		// for fixed Budget
		$this->db->select($fixed_budget_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_closed_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_inprogress_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_closed_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_incomplete_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_closed_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_completed_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		// for hourly Budget
		$this->db->select($hourly_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_closed_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_inprogress_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_closed_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_incomplete_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_closed_disputes as ad');
		$this->db->where('ad.project_owner_id_of_disputed_project', $user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_completed_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		############ for fulltime project
		
	
		
		
		$this->db->select('pd.project_id,pd. project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees'.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_open_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_expired_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employer_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_by_admin_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_active_disputes_query,
			$fixed_budget_incomplete_projects_active_disputes_query, 
			$fixed_budget_completed_projects_active_disputes_query,
			$hourly_inprogress_projects_active_disputes_query,
			$hourly_incomplete_projects_active_disputes_query,
			$hourly_completed_projects_active_disputes_query,
			$fulltime_open_projects_active_disputes_query,
			$fulltime_expired_projects_active_disputes_query,
			$fulltime_cancelled_projects_active_disputes_query,
			$fulltime_cancelled_by_admin_projects_active_disputes_query,
			$fixed_budget_inprogress_projects_closed_disputes_query,
			$fixed_budget_incomplete_projects_closed_disputes_query, 
			$fixed_budget_completed_projects_closed_disputes_query,
			 $hourly_inprogress_projects_closed_disputes_query,
			$hourly_incomplete_projects_closed_disputes_query,
			$hourly_completed_projects_closed_disputes_query,
			$fulltime_open_projects_closed_disputes_query,
			 $fulltime_expired_projects_closed_disputes_query,
			$fulltime_cancelled_projects_closed_disputes_query,
			$fulltime_cancelled_by_admin_projects_closed_disputes_query
			
		];
		
		$closed_disputes_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY dispute_end_date DESC LIMIT '.$limit_range);
		$result  = $closed_disputes_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		/* echo "<pre>";
		print_r($result);
		die;
		
		 */
		
		return ['data' => $result, 'total' => $total_rec];
		
	}
	
	/**
	* This function is used to fetch the all closed disputes listing of all projects for sp.
	*/
	public function get_all_closed_disputes_listing_projects_sp($user_id,$start,$limit){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		
		
		$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
		$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
		
		$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
		
		$fixed_budget_project_active_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees';
		
		$hourly_project_active_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees';
		
		$fulltime_project_active_disputes_fields = 'pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees';
		
		$user_fields = 'po.first_name as po_first_name,po.last_name as po_last_name,po.company_name as po_company_name,po.account_type as po_account_type,po.gender as po_gender,po.profile_name,sp.first_name as sp_first_name,sp.last_name as sp_last_name,po.company_name as sp_company_name,sp.account_type as sp_account_type,sp.gender as sp_gender,sp.profile_name';
		
		#################### for closed dispute
		$fixed_budget_project_closed_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees';
		
		$hourly_project_closed_disputes_fields = 'pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.project_owner_id_of_disputed_project as project_owner_id,ad.sp_winner_id_of_disputed_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees';
		
		$fulltime_project_closed_disputes_fields = 'pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees';
		
		
		

		// for fixed Budget
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_project_active_disputes_fields.','.$user_fields,false);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fixed_budget_project_admin_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fixed_budget_project_admin_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fixed_budget_project_admin_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		// for hourly Budget
		$this->db->select($hourly_project_active_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$hourly_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_inprogress_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$hourly_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_incomplete_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_active_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_active_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$hourly_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_completed_projects_active_disputes_query = $this->db->get_compiled_select();
		
		############ for fulltime project
		
		
		$this->db->select('pd.project_id,pd.project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_negotiation_end_date as dispute_end_date,ad.dispute_status,"0" as disputed_winner_id,"0" as disputed_final_settlement_amount ,"0" as disputed_final_settlement_service_fees'.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_open_projects_active_disputes_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_expired_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_projects_active_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_active_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_active_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->where('ad.dispute_negotiation_end_date < NOW()');
		$this->db->where("ad.disputed_amount <",$fulltime_admin_project_arbitration_value);
		$this->db->where('ad.dispute_status', 'active');
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_by_admin_projects_active_disputes_query = $this->db->get_compiled_select();
		
		########################### For closed dispute #################
		
		// for fixed Budget
		$this->db->select($fixed_budget_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_closed_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_inprogress_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_closed_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_incomplete_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fixed_budget_projects_closed_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$fixed_budget_completed_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		// for hourly Budget
		$this->db->select($hourly_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_closed_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_inprogress_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_closed_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_incomplete_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('hourly_rate_based_projects_closed_disputes as ad');
		$this->db->where('ad.sp_winner_id_of_disputed_project', $user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ad.disputed_project_id');
		$this->db->join('users as po', 'po.user_id = ad.project_owner_id_of_disputed_project');
		$this->db->join('users as sp', 'sp.user_id = ad.sp_winner_id_of_disputed_project');
		$hourly_completed_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		############ for fulltime project
		
	
		
		
		$this->db->select('pd.project_id ,pd. project_title,pd.project_type,ad.dispute_reference_id,ad.dispute_initiated_by_user_id,ad.employer_id_of_disputed_fulltime_project as project_owner_id,ad.employee_winner_id_of_disputed_fulltime_project as winner_id,ad.dispute_start_date,ad.disputed_amount,ad.disputed_service_fees,ad.dispute_end_date,ad.dispute_status,ad.disputed_winner_id,ad.disputed_final_settlement_amount,ad.disputed_final_settlement_service_fees'.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_open_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_expired_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_closed_disputes_fields.','.$user_fields);
		$this->db->from('fulltime_projects_closed_disputes as ad');
		$this->db->where('ad.employee_winner_id_of_disputed_fulltime_project', $user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ad.disputed_fulltime_project_id');
		$this->db->join('users as po', 'po.user_id = ad.employer_id_of_disputed_fulltime_project');
		$this->db->join('users as sp', 'sp.user_id = ad.employee_winner_id_of_disputed_fulltime_project');
		$fulltime_cancelled_by_admin_projects_closed_disputes_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_active_disputes_query,
			$fixed_budget_incomplete_projects_active_disputes_query, 
			$fixed_budget_completed_projects_active_disputes_query,
			$hourly_inprogress_projects_active_disputes_query,
			$hourly_incomplete_projects_active_disputes_query,
			$hourly_completed_projects_active_disputes_query,
			$fulltime_open_projects_active_disputes_query,
			$fulltime_expired_projects_active_disputes_query,
			$fulltime_cancelled_projects_active_disputes_query,
			$fulltime_cancelled_by_admin_projects_active_disputes_query,
			$fixed_budget_inprogress_projects_closed_disputes_query,
			$fixed_budget_incomplete_projects_closed_disputes_query, 
			$fixed_budget_completed_projects_closed_disputes_query,
			$hourly_inprogress_projects_closed_disputes_query,
			$hourly_incomplete_projects_closed_disputes_query,
			$hourly_completed_projects_closed_disputes_query,
			$fulltime_open_projects_closed_disputes_query,
			 $fulltime_expired_projects_closed_disputes_query,
			$fulltime_cancelled_projects_closed_disputes_query,
			$fulltime_cancelled_by_admin_projects_closed_disputes_query
			
		];
		
		$closed_disputes_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY dispute_end_date DESC LIMIT '.$limit_range);
		$result  = $closed_disputes_projects_query->result_array();
	
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		
		
		return ['data' => $result, 'total' => $total_rec];
	}


	// This function is using to count the all closed disputes for dispute page regarding paging for po
	public function get_all_closed_disputes_listing_projects_count_po($user_id){
			$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
			$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			
			$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			
			$total_record = $this->db
			->from('fixed_budget_projects_active_disputes')
			->where('project_owner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date < NOW()')
			->where("disputed_amount <",$fixed_budget_project_admin_arbitration_value)
			->where('dispute_status', 'active')
			->count_all_results();
			
			$total_record += $this->db
			->from('hourly_rate_based_projects_active_disputes')
			->where('project_owner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date < NOW()')
			->where("disputed_amount <",$hourly_admin_project_arbitration_value)
			->where('dispute_status', 'active')
			->count_all_results();
			
			$total_record += $this->db
			->from('fulltime_projects_active_disputes')
			->where('employer_id_of_disputed_fulltime_project', $user_id)
			->where('dispute_negotiation_end_date < NOW()')
			->where("disputed_amount <",$fulltime_admin_project_arbitration_value)
			->where('dispute_status', 'active')
			->count_all_results();
			
			$total_record += $this->db
			->from('fixed_budget_projects_closed_disputes')
			->where('project_owner_id_of_disputed_project', $user_id)
			->count_all_results();
			
			$total_record += $this->db
			->from('hourly_rate_based_projects_closed_disputes')
			->where('project_owner_id_of_disputed_project', $user_id)
			->count_all_results();
			
			$total_record += $this->db
			->from('fulltime_projects_closed_disputes')
			->where('employer_id_of_disputed_fulltime_project', $user_id)
			->count_all_results();
			
			return $total_record;
	}

	// This function is using to count the all closed disputes for dispute page regarding paging for sp
	public function get_all_closed_disputes_listing_projects_count_sp($user_id){
			$fixed_budget_project_admin_arbitration_value = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
		
			$hourly_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			
			$fulltime_admin_project_arbitration_value = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			
			$total_record = $this->db
			->from('fixed_budget_projects_active_disputes')
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date < NOW()')
			->where("disputed_amount <",$fixed_budget_project_admin_arbitration_value)
			->where('dispute_status', 'active')
			->count_all_results();
			
			$total_record += $this->db
			->from('hourly_rate_based_projects_active_disputes')
			->where('sp_winner_id_of_disputed_project', $user_id)
			->where('dispute_negotiation_end_date < NOW()')
			->where("disputed_amount <",$hourly_admin_project_arbitration_value)
			->where('dispute_status', 'active')
			->count_all_results();
			
			$total_record += $this->db
			->from('fulltime_projects_active_disputes')
			->where('employee_winner_id_of_disputed_fulltime_project', $user_id)
			->where('dispute_negotiation_end_date < NOW()')
			->where("disputed_amount <",$fulltime_admin_project_arbitration_value)
			->where('dispute_status', 'active')
			->count_all_results();
			
			$total_record += $this->db
			->from('fixed_budget_projects_closed_disputes')
			->where('sp_winner_id_of_disputed_project', $user_id)
			->count_all_results();
			
			$total_record += $this->db
			->from('hourly_rate_based_projects_closed_disputes')
			->where('sp_winner_id_of_disputed_project', $user_id)
			->count_all_results();
			
			$total_record += $this->db
			->from('fulltime_projects_closed_disputes')
			->where('employee_winner_id_of_disputed_fulltime_project', $user_id)
			->count_all_results();
			
			return $total_record;
	}	
	
	// Function is using to get the list of project whose active escrow exists and po want to open dispute
	public function get_all_new_disputes_projects_listing_po($user_id){

		$fixed_projects_fields = 'pd.project_id,pd.project_title,bd.project_owner_id,bd.winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"fixed" as project_type';
		
		$hourly_projects_fields = 'pd.project_id,pd.project_title,bd.project_owner_id,bd.winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"hourly" as project_type';
		
		$fulltime_projects_fields = 'pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,bd.employer_id as project_owner_id,bd.employee_id as winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"fulltime" as project_type';
	
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_projects_fields,false);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_completed_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_incomplete_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_inprogress_project_completed_bid_query = $this->db->get_compiled_select();
		
		#########################################################################
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_completed_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_incomplete_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_inprogress_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		##################################################################################
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_completed_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_incomplete_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_inprogress_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		
		////////////// hourly budget /////////////////
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_completed_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_incomplete_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_inprogress_project_completed_bid_query = $this->db->get_compiled_select();
		
		#########################################################################
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_completed_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_incomplete_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_inprogress_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		##################################################################################
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_completed_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_incomplete_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.project_owner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.winner_id');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_inprogress_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		////////////////// For fulltime //////////////////
		
		
		$this->db->select($fulltime_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employer_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employee_id');
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_expired_project_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employer_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employee_id');
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_cancelled_project_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employer_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employee_id');
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_cancelled_by_admin_project_bid_query = $this->db->get_compiled_select();
		
		
		
		$this->db->select('pd.project_id,pd.project_title,bd.employer_id as project_owner_id,bd.employee_id as winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"fulltime" as project_type');
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employer_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_open_project_bid_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_completed_project_completed_bid_query,
			$fixed_budget_incomplete_project_completed_bid_query,
			$fixed_budget_inprogress_project_completed_bid_query,
			$fixed_budget_completed_project_incomplete_bid_query,
			$fixed_budget_incomplete_project_incomplete_bid_query,
			$fixed_budget_incomplete_project_incomplete_bid_query,
			$fixed_budget_inprogress_project_incomplete_bid_query,
			$fixed_budget_completed_project_inprogress_bid_query,
			$fixed_budget_incomplete_project_inprogress_bid_query,
			$fixed_budget_inprogress_project_inprogress_bid_query,
			$hourly_completed_project_completed_bid_query,
			$hourly_incomplete_project_completed_bid_query,
			$hourly_inprogress_project_completed_bid_query,
			$hourly_completed_project_incomplete_bid_query,
			$hourly_incomplete_project_incomplete_bid_query,
			$hourly_incomplete_project_incomplete_bid_query,
			$hourly_inprogress_project_incomplete_bid_query,
			$hourly_completed_project_inprogress_bid_query,
			$hourly_incomplete_project_inprogress_bid_query,
			$hourly_inprogress_project_inprogress_bid_query,
			$fulltime_expired_project_bid_query,
			$fulltime_cancelled_project_bid_query,
			$fulltime_cancelled_by_admin_project_bid_query,
			$fulltime_open_project_bid_query
			
		];
	
		$new_disputes_projects_listing_query = $this->db->query(implode(' UNION ', $union_table_name));
		$result  = $new_disputes_projects_listing_query->result_array(); 
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}

	// Function is using to get the list of project whose active escrow exists and sp want to open dispute
	public function get_all_new_disputes_projects_listing_sp($user_id){

		$fixed_projects_fields = 'pd.project_id,pd.project_title,bd.project_owner_id,bd.winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"fixed" as project_type';
		
		$hourly_projects_fields = 'pd.project_id,pd.project_title,bd.project_owner_id,bd.winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"hourly" as project_type';
		
		$fulltime_projects_fields = 'pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,bd.employer_id as project_owner_id,bd.employee_id as winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"fulltime" as project_type';
	
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_projects_fields,false);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_completed_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_incomplete_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_inprogress_project_completed_bid_query = $this->db->get_compiled_select();
		
		#########################################################################
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_completed_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_incomplete_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_inprogress_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		##################################################################################
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_completed_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_incomplete_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fixed_budget_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('fixed_budget_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$fixed_budget_inprogress_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		
		////////////// hourly budget /////////////////
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_completed_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_incomplete_project_completed_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_completed_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_inprogress_project_completed_bid_query = $this->db->get_compiled_select();
		
		#########################################################################
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_completed_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_incomplete_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_incomplete_tracking as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_inprogress_project_incomplete_bid_query = $this->db->get_compiled_select();
		
		##################################################################################
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_completed_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_incomplete_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_active_escrows WHERE project_id = bd.project_id AND project_owner_id = bd.project_owner_id and winner_id = bd.winner_id) as total_escrow_amount');
		$this->db->from('hourly_rate_based_projects_progress_sp_bid_reference as bd');
		$this->db->where('bd.winner_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.project_owner_id');
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = bd.project_id');
		$this->db->having('total_escrow_amount > 0');
		$hourly_inprogress_project_inprogress_bid_query = $this->db->get_compiled_select();
		
		////////////////// For fulltime //////////////////
		
		
		$this->db->select($fulltime_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employer_id');
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_expired_project_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employer_id');
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_cancelled_project_bid_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_fields);
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employer_id');
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_cancelled_by_admin_project_bid_query = $this->db->get_compiled_select();
		
		
		
		$this->db->select('pd.project_id,pd.project_title,bd.employer_id as project_owner_id,bd.employee_id as winner_id,u.account_type,u.first_name,u.last_name,u.company_name,"fulltime" as project_type');
		$this->db->select('(select SUM(created_escrow_amount)  FROM '.$this->db->dbprefix.'fulltime_projects_active_escrows WHERE fulltime_project_id = bd.fulltime_project_id AND employer_id = bd.employer_id and employee_id = bd.employee_id) as total_escrow_amount');
		$this->db->from('fulltime_projects_hired_employees_tracking as bd');
		$this->db->where('bd.employee_id', $user_id);
		$this->db->join('users as u', 'u.user_id = bd.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = bd.fulltime_project_id');
		$this->db->having('total_escrow_amount > 0');
		$fulltime_open_project_bid_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_completed_project_completed_bid_query,
			$fixed_budget_incomplete_project_completed_bid_query,
			$fixed_budget_inprogress_project_completed_bid_query,
			$fixed_budget_completed_project_incomplete_bid_query,
			$fixed_budget_incomplete_project_incomplete_bid_query,
			$fixed_budget_incomplete_project_incomplete_bid_query,
			$fixed_budget_inprogress_project_incomplete_bid_query,
			$fixed_budget_completed_project_inprogress_bid_query,
			$fixed_budget_incomplete_project_inprogress_bid_query,
			$fixed_budget_inprogress_project_inprogress_bid_query,
			$hourly_completed_project_completed_bid_query,
			$hourly_incomplete_project_completed_bid_query,
			$hourly_inprogress_project_completed_bid_query,
			$hourly_completed_project_incomplete_bid_query,
			$hourly_incomplete_project_incomplete_bid_query,
			$hourly_incomplete_project_incomplete_bid_query,
			$hourly_inprogress_project_incomplete_bid_query,
			$hourly_completed_project_inprogress_bid_query,
			$hourly_incomplete_project_inprogress_bid_query,
			$hourly_inprogress_project_inprogress_bid_query,
			$fulltime_expired_project_bid_query,
			$fulltime_cancelled_project_bid_query,
			$fulltime_cancelled_by_admin_project_bid_query,
			$fulltime_open_project_bid_query
			
		];
	
		$new_disputes_projects_listing_query = $this->db->query(implode(' UNION ', $union_table_name));
		$result  = $new_disputes_projects_listing_query->result_array(); 
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to count the total active escrows count of project regarding po .
	*/
	public function get_po_all_projects_active_escrows_count($user_id){
	
		// For fixed
		$projects_escrows_tables_array['fixed_budget_projects_active_escrows']= array('conditions'=>array('project_owner_id'=>$user_id));
		
		// For hourly
		$projects_escrows_tables_array['hourly_rate_based_projects_active_escrows']= array('conditions'=>array('project_owner_id'=>$user_id));
		
		// For fulltime
		$projects_escrows_tables_array['fulltime_projects_active_escrows']= array('conditions'=>array('employer_id'=>$user_id));
		$po_projects_escrows_count = 0;
		foreach($projects_escrows_tables_array as $table_name=>$conditions){
			$po_projects_escrows_count += $this->db->where($conditions['conditions'])->from($table_name)->count_all_results();
		}
		return $po_projects_escrows_count;
	}
	

	/**
	* This function is used to count the total active escrows count of project regarding sp .
	*/
	public function get_sp_all_projects_active_escrows_count($user_id){
	
		// For fixed
		$projects_escrows_tables_array['fixed_budget_projects_active_escrows']= array('conditions'=>array('winner_id'=>$user_id));
		
		// For hourly
		$projects_escrows_tables_array['hourly_rate_based_projects_active_escrows']= array('conditions'=>array('winner_id'=>$user_id));
		
		// For fulltime
		$projects_escrows_tables_array['fulltime_projects_active_escrows']= array('conditions'=>array('employee_id'=>$user_id));
		
		$sp_projects_escrows_count = 0;
		foreach($projects_escrows_tables_array as $table_name=>$conditions){
			$sp_projects_escrows_count += $this->db->where($conditions['conditions'])->from($table_name)->count_all_results();
		}
		return $sp_projects_escrows_count;
	}	
	
	
	// This method is used to store data into charged_service_fees table but not considered the affiliate income
	function insert_data_into_charged_service_fees_tracking_disputes($charged_service_fees_data, $released_escrow_data) {

		if($charged_service_fees_data['project_type'] != 'fulltime') {
			$po = $released_escrow_data['project_owner_id'];
			$sp = $released_escrow_data['winner_id'];
		} else {
			$po = $released_escrow_data['employer_id'];
			$sp = $released_escrow_data['employee_id'];
		}

		$service_fee_value_excl_vat = $charged_service_fees_data['charged_service_fee_value_excl_vat'];
		$charged_service_fees_data['charged_service_fee_net_value'] = $service_fee_value_excl_vat;

		if(!empty($charged_service_fees_data)) {
			$this->db->insert('projects_charged_service_fees_tracking', $charged_service_fees_data);
		}
	}
    
}
?>