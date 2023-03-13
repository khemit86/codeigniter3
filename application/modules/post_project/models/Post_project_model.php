<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
	}

class Post_project_model extends BaseModel
{

    public function __construct ()
    { 
			$this->load->model('escrow/Escrow_model');
			
			parent::__construct ();
    }
	
	/**
	 * This method is used to get projects professionals categories mapping data based on category id passed as parameter when project move to open bidding status
	*/
	function get_projects_professionals_categories_mapping_data_based_on_categories_id ($categories){
		$this->db->where_in('projects_category_id', $categories);
		return $this->db->get('projects_professionals_categories_mapping_tracking')->result_array();
	}
	/**
	 * This method is used to get uesrs id from professionals category id from users areas of expertise table
	*/
	function get_users_id_from_professionals_categories_id($professionals_category) {
		$this->db->distinct();
		$this->db->select('user_id');
		$this->db->from('professionals_areas_of_expertise_listings_tracking');
		$this->db->where_in('professional_category_id', $professionals_category);
		$this->db->or_where_in('professional_parent_category_id', $professionals_category);
		return $this->db->get()->result_array();
	}
	/**
	 * This method is used to get category details by category id to store category name in send notification message 
	*/
	function get_projects_categories_by_categories_id ($categories) {
		$this->db->where_in('id', $categories);
		$this->db->order_by('name');
		return $this->db->get('categories_projects')->result_array();
	}
	/**
	 * This method is used to get user id from favorite employer id from favorite employer tracking table
	*/
	function get_users_id_from_favorite_employer_id($favorite_employer_id) {
		$this->db->where('favorite_employer_id', $favorite_employer_id);
		$result = $this->db->get('users_favorite_employer_tracking')->result_array();
		$users = [];
		if(!empty($result)) {
			$users = array_column($result, 'user_id');
		}
		return $users;
	}
	/**
	 * @sid
	 * This method is used insert data in to serv_users_newly_posted_projects_sent_notifications_tracking  whenever posted project have category mapped in projects_professionals_categories_mapping table and is status changed to open bidding status
	*/
	function add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_for_bidding_project_data) {
		$professional_categories = array_column($category_mapping_data, 'professionals_category_id');
		$users_id = $this->get_users_id_from_professionals_categories_id($professional_categories);
		$send_notification = [];
		if(!empty($users_id)) {
			$categories = $this->get_projects_categories_by_categories_id($parent_categories_id);
			$categories_name = array_column($categories, 'name');
			$project_type = '';
			if($open_for_bidding_project_data['project_type'] == 'fixed') {
				$project_type = 'fixed_budget';
			} else if ($open_for_bidding_project_data['project_type'] == 'hourly') {
				$project_type = 'hourly_rate';
			} else {
				$project_type = 'fulltime';
			}
			$favorite_employers_users = $this->get_users_id_from_favorite_employer_id($open_for_bidding_project_data['project_owner_id']);
			$insert_flag = false;
			if($open_for_bidding_project_data['project_type'] == 'fixed') {
				if(count($categories) == 1) {
					$users_send_notification_message = $this->config->item('post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
				} else {
					$users_send_notification_message = $this->config->item('post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($this->config->item('post_project_budget_range_between')){
						$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
					}
					$budget_range .= number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
				} else {
					$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY;
				}
				$users_send_notification_message = str_replace('{fixed_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{fixed_budget_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{fixed_budget_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				}
				$final_array = [];
				foreach($users_id as $user_id) {
					if($user_id['user_id'] != $open_for_bidding_project_data['project_owner_id']) {
						$favourite_employers_notification = $this->db->get_where('users_consent_receive_notifications_tracking', ['user_id' => $user_id['user_id'], 'sent_notification_type' => 'newly_posted_projects_by_favorite_employer'])->row_array();
						if(!in_array($user_id['user_id'], $favorite_employers_users) || empty($favourite_employers_notification)) {
							$tmp = [
								'user_id' => $user_id['user_id'],
								'project_owner_id' => $open_for_bidding_project_data['project_owner_id'],
								'project_id' => $open_for_bidding_project_data['project_id'],
								'notification_description' => $users_send_notification_message,
								'project_type' => $project_type
							];
							array_push($final_array, $tmp);
						}
					}
					
				}
				
				if($final_array){
					$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
					$insert_flag = true;
				}
				/* echo "<pre>";
				print_r($final_array);
				echo "<pre>";
				print_r($favorite_employers_users);
				echo "<pre>";
				print_r($users_id);
				die; */
			} else if ($open_for_bidding_project_data['project_type'] == 'hourly') {
				if(count($categories) == 1) {
					$users_send_notification_message = $this->config->item('post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
				} else {
					$users_send_notification_message = $this->config->item('post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($this->config->item('post_project_budget_range_between')){
						$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
					}
					$budget_range .= number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour');
				} else {
					$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY.$this->config->item('post_project_budget_per_hour');
				}
				$users_send_notification_message = str_replace('{hourly_rate_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{hourly_rate_budget_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{hourly_rate_budget_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				}
				$final_array = [];
				foreach($users_id as $user_id) {
					if($user_id['user_id'] != $open_for_bidding_project_data['project_owner_id']) {
						$favourite_employers_notification = $this->db->get_where('users_consent_receive_notifications_tracking', ['user_id' => $user_id['user_id'], 'sent_notification_type' => 'newly_posted_projects_by_favorite_employer'])->row_array();
						if(!in_array($user_id['user_id'], $favorite_employers_users) || empty($favourite_employers_notification)) {
							$tmp = [
								'user_id' => $user_id['user_id'],
								'project_owner_id' => $open_for_bidding_project_data['project_owner_id'],
								'project_id' => $open_for_bidding_project_data['project_id'],
								'notification_description' => $users_send_notification_message,
								'project_type' => $project_type
							];
							array_push($final_array, $tmp);
						}
					}
					
				}
				if($final_array){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			} else {
				if(count($categories) == 1) {
					$users_send_notification_message = $this->config->item('post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
				} else {
					$users_send_notification_message = $this->config->item('post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($this->config->item('post_project_budget_range_between')){
						$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
					}
					$budget_range .= number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month') .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'] 	, 0, '', ' ').'&nbsp'.CURRENCY . $this->config->item('post_project_budget_per_month');
				} else {
					$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY .$this->config->item('post_project_budget_per_month');
				}
				$users_send_notification_message = str_replace('{fulltime_salary_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural');
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{fulltime_salary_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{fulltime_salary_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				}
				$final_array = [];
				foreach($users_id as $user_id) {
					if($user_id['user_id'] != $open_for_bidding_project_data['project_owner_id']) {
						$favourite_employers_notification = $this->db->get_where('users_consent_receive_notifications_tracking', ['user_id' => $user_id['user_id'], 'sent_notification_type' => 'newly_posted_projects_by_favorite_employer'])->row_array();
						if(!in_array($user_id['user_id'], $favorite_employers_users) || empty($favourite_employers_notification)) {
							$tmp = [
								'user_id' => $user_id['user_id'],
								'project_owner_id' => $open_for_bidding_project_data['project_owner_id'],
								'project_id' => $open_for_bidding_project_data['project_id'],
								'notification_description' => $users_send_notification_message,
								'project_type' => $project_type
							];
							array_push($final_array, $tmp);
						}
					}
					
				}
				if($final_array){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			}
			
			if($insert_flag) {
				// trigger socket event to display real time notificaiton for newly posted project for mapped category
				$url = PROJECT_MANAGEMENT_SOCKET_URL."/newlyPostedProjectsRealtimeNotifications/".$open_for_bidding_project_data['project_id'].'/newly_posted?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
				$options = array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false
				);
				try {
					$ch = curl_init( $url );
					curl_setopt_array( $ch, $options );
					curl_exec( $ch );
					curl_close( $ch );
				} catch(Exception $e) {
				}	
			}
			
		}
	}
	/**
	 * @sid
	 * This method is used insert data in to serv_users_newly_posted_projects_sent_notifications_tracking  whenever favorite employer posted a new project
	*/
	function add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_for_bidding_project_data) {
		if(!empty($users)) {
			$categories = $this->get_projects_categories_by_categories_id($parent_categories_id);
			$categories_name = array_column($categories, 'name');
			$project_type = '';
			if($open_for_bidding_project_data['project_type'] == 'fixed') {
				$project_type = 'fixed_budget';
			} else if ($open_for_bidding_project_data['project_type'] == 'hourly') {
				$project_type = 'hourly_rate';
			} else {
				$project_type = 'fulltime';
			}
			$insert_flag = false;
			if($open_for_bidding_project_data['project_type'] == 'fixed') {
				if(count($categories) == 1) {
					$users_send_notification_message = $this->config->item('favorite_employers_post_fixed_budget_realtime_notification_message_singular');
				} else {
					$users_send_notification_message = $this->config->item('favorite_employers_post_fixed_budget_realtime_notification_message_plural');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($this->config->item('post_project_budget_range_between')){
						$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
					}
					$budget_range .= number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
				} else {
					$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY;
				}
				$users_send_notification_message = str_replace('{fixed_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fixed_budget_confidential_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fixed_budget_confidential_realtime_notification_message_plural');
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_plural');
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{fixed_budget_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{fixed_budget_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				}
				$final_array = [];
				// $send_notification['project_id'] = $open_for_bidding_project_data['project_id'];
				// $send_notification['user_id'] = [];
				
				foreach($users as $user_id) {
					$check_valid_newly_posted = $this->db->from('users_newly_posted_projects_sent_notifications_tracking')->where(['user_id'=> $user_id, 'project_owner_id' => $open_for_bidding_project_data['project_owner_id'], 'project_id' => $open_for_bidding_project_data['project_id']])->count_all_results();
					
					if($check_valid_newly_posted == 0) {
						$tmp = [
							'user_id' => $user_id,
							'project_owner_id' => $open_for_bidding_project_data['project_owner_id'],
							'project_id' => $open_for_bidding_project_data['project_id'],
							'notification_description' => $users_send_notification_message,
							'sent_notification_type' => 'favorite_employer',
							'project_type' => $project_type
						];
						array_push($final_array, $tmp);
					}
					
				}
				if($final_array){
					$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
					$insert_flag = true;
				}
			} else if ($open_for_bidding_project_data['project_type'] == 'hourly') {
				if(count($categories) == 1) {
					$users_send_notification_message = $this->config->item('favorite_employers_post_hourly_rate_budget_realtime_notification_message_singular');
				} else {
					$users_send_notification_message = $this->config->item('favorite_employers_post_hourly_rate_budget_realtime_notification_message_plural');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($this->config->item('post_project_budget_range_between')){
						$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
					}
					$budget_range .= number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour');
				} else {
					$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY.$this->config->item('post_project_budget_per_hour');
				}
				$users_send_notification_message = str_replace('{hourly_rate_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_plural');
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_plural');
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{hourly_rate_budget_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{hourly_rate_budget_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				}
				$final_array = [];
				
				foreach($users as $user_id) {

					$check_valid_newly_posted = $this->db->from('users_newly_posted_projects_sent_notifications_tracking')->where(['user_id'=> $user_id, 'project_owner_id' => $open_for_bidding_project_data['project_owner_id'], 'project_id' => $open_for_bidding_project_data['project_id']])->count_all_results();
					if($check_valid_newly_posted == 0) {
						$tmp = [
							'user_id' => $user_id,
							'project_owner_id' => $open_for_bidding_project_data['project_owner_id'],
							'project_id' => $open_for_bidding_project_data['project_id'],
							'notification_description' => $users_send_notification_message,
							'sent_notification_type' => 'favorite_employer',
							'project_type' => $project_type
						];
						array_push($final_array, $tmp);
					}
				}
				if($final_array){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			} else {
				if(count($categories) == 1) {
					$users_send_notification_message = $this->config->item('favorite_employers_post_fulltime_salary_realtime_notification_message_singular');
				} else {
					$users_send_notification_message = $this->config->item('favorite_employers_post_fulltime_salary_realtime_notification_message_plural');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($this->config->item('post_project_budget_range_between')){
						$budget_range .= $this->config->item('post_project_budget_range_between').'&nbsp;';
					}
					$budget_range .= number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month') .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'] 	, 0, '', ' ').'&nbsp'.CURRENCY . $this->config->item('post_project_budget_per_month');
				} else {
					$budget_range = $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY .$this->config->item('post_project_budget_per_month');
				}
				$users_send_notification_message = str_replace('{fulltime_salary_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_plural');
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_singular');
					} else {
						$users_send_notification_message = $this->config->item('favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_plural');
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{fulltime_salary_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{fulltime_salary_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				}
				$final_array = [];
				
				foreach($users as $user_id) {
					$check_valid_newly_posted = $this->db->from('users_newly_posted_projects_sent_notifications_tracking')->where(['user_id'=> $user_id, 'project_owner_id' => $open_for_bidding_project_data['project_owner_id'], 'project_id' => $open_for_bidding_project_data['project_id']])->count_all_results();
					if($check_valid_newly_posted == 0) {
						$tmp = [
							'user_id' => $user_id,
							'project_owner_id' => $open_for_bidding_project_data['project_owner_id'],
							'project_id' => $open_for_bidding_project_data['project_id'],
							'notification_description' => $users_send_notification_message,
							'sent_notification_type' => 'favorite_employer',
							'project_type' => $project_type
						];
						array_push($final_array, $tmp);
					}
					
				}
				if($final_array){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			}
			
			if($insert_flag) {
				// trigger socket event to display real time notificaiton for newly posted project for mapped category
				$url = PROJECT_MANAGEMENT_SOCKET_URL."/newlyPostedProjectsRealtimeNotifications/".$open_for_bidding_project_data['project_id'].'/favorite_employer?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
				$options = array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false
				);
				try {
					$ch = curl_init( $url );
					curl_setopt_array( $ch, $options );
					curl_exec( $ch );
					curl_close( $ch );
				} catch(Exception $e) {
				}	
			}
		}
	}


	/*
	This function fetch the parent categories of projects
	*/
	function get_project_parent_categories()
	{
		
		$count_project_parent_category = $this->db
		->select ('id')
		->from ('categories_projects')
		->where ('parent_id', 0)
		->where ('status', 'Y')
		//->where_not_in ('id', $selected_category)
		->get ()->num_rows ();
		$data = array(); 
		if($count_project_parent_category > 0)
		{
	
			$this->db->select ('id,name');
			$this->db->order_by('name', 'ASC');
			$this->db->where ('parent_id', 0);
			$this->db->where ('status', 'Y');
			$this->db->order_by ('name');
			$res = $this->db->get ('categories_projects'); 
			foreach ($res->result () as $row)
			{
				$data[] = [
					'id' => $row->id,
					'name' => $row->name,
				];
			}
		}
        return $data;
	}
	
	/**
	 * Calculate project expiration date with combination of custom config configuration and upgrade selection done through user
	 */
	public function get_project_correct_expiration_date($data) {
		$time_arr = explode(':', $this->config->item('standard_project_availability'));
		$project_expire_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
		if($data['featured'] == 'Y') {
			$featured_time_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$featured_time_arr[0].' hour +'.(int)$featured_time_arr[1].' minutes +'.(int)$featured_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		if($data['urgent'] == 'Y') {
			$urgent_time_arr = explode(":", $this->config->item('project_upgrade_availability_urgent'));
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$urgent_time_arr[0].' hour +'.(int)$urgent_time_arr[1].' minutes +'.(int)$urgent_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		if($data['sealed'] == 'Y') {
			$sealed_time_arr = explode(":", $this->config->item('project_upgrade_availability_sealed'));
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$sealed_time_arr[0].' hour +'.(int)$sealed_time_arr[1].' minutes +'.(int)$sealed_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		if($data['hidden'] == 'Y') {
			$hidden_time_arr = explode(":", $this->config->item('project_upgrade_availability_hidden'));
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$hidden_time_arr[0].' hour +'.(int)$hidden_time_arr[1].' minutes +'.(int)$hidden_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		return $project_expire_date;
	}
	/*
		This function fetch the child categories of selected parent category regarding projects
	*/
	function get_project_child_categories($category_parent_id)
	{
		$this->db->where ('id', $category_parent_id);
		$this->db->where ('status', 'Y');
        $get_parent_category_query = $this->db->get ('categories_projects');
        $parent_category_row = $get_parent_category_query->row ();
        $parent_category_row = $get_parent_category_query->result_array ();
		$data = array(); 
		if(!empty($parent_category_row))
		{
			$count_project_child_categories = $this->db
			->select ('id')
			->from ('categories_projects')
			->where ('parent_id', $category_parent_id)
			->where ('status', 'Y')
			->get ()->num_rows ();
			$this->db->select ('id,name');
			$this->db->order_by('name', 'ASC');
			$this->db->where ('parent_id', $category_parent_id);
			$this->db->where ('status', 'Y');
			$this->db->order_by ('name');
			$res = $this->db->get ('categories_projects'); 
			foreach ($res->result () as $row)
			{
				$data[] = [
					'id' => $row->id,
					'name' => $row->name,
				];
			}
		}
        return $data;
	}
	
	/*
		This function is used to check that project parent category is exists or not
	*/
	function check_project_parent_category_exist($category_parent_id){
		
		/* $parent_category_detail = $this->db->get_where('categories_projects', ['id' => $category_parent_id,'status'=>'Y'])->row_array();
		if(!empty($parent_category_detail)){
			return true;
		}
		return false; */
		$project_parent_category_count = $this->db
		->select ('id')
		->from ('categories_projects')
		->where ('id',$category_parent_id)
		->where ('status','Y')
		->get ()->num_rows ();
		if($project_parent_category_count != 0){
			return true;
		}
		return false;
	
	}
	
	/*
		This function is used to check that project child category is exists or not
	*/
	function check_project_child_category_exist($parent_category_id,$child_category_id){
	
		/* $child_category_detail = $this->db->get_where('categories_projects', ['id' => $child_category_id,'parent_id'=>$parent_category_id,'status'=>'Y'])->row_array();
		if(!empty($child_category_detail)){
			return true;
		}
		return false; */
		
		$project_child_category_count = $this->db
			->select ('id')
		->from ('categories_projects')
		->where ('id',$child_category_id)
		->where ('parent_id',$parent_category_id)
		->where ('status','Y')
		->get ()->num_rows ();
		if($project_child_category_count != 0){
			return true;
		}
		return false;
	}
	
	
	
	
	
	
	
	/*
	This function fetch the budget range of fixed type project
	*/
	
	function get_fixed_budget_projects_budget_range()
	{
		
        $count_budget_range_fixed_project = $this->db
		->select ('id')
		->from ('fixed_budget_projects_budgets_range')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_budget_range_fixed_project > 0)
		{
			/* $this->db->order_by('min_budget', 'ASC');
			$res = $this->db->get ('fixed_budget_projects_budgets_range');  */
			
			$fixed_budget_projects_budget_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'fixed_budget_projects_budgets_range ORDER BY `min_budget` *1 ');

			foreach ($fixed_budget_projects_budget_range_query->result () as $row)
			{
				
				if($row->max_budget != "All"){
				
					if($this->config->item('post_project_budget_range_between')){
						$fixed_budget_range_value = $this->config->item('post_project_budget_range_between').'&nbsp;'.number_format($row->min_budget, 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_budget, 0, '', ' ').'&nbsp'.CURRENCY;
					}else{
						$fixed_budget_range_value = number_format($row->min_budget, 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_budget, 0, '', ' ').'&nbsp'.CURRENCY;
					}
					
					$data[] = [
						'fixed_budget_range_key' => $row->min_budget."_".$row->max_budget,
						'fixed_budget_range_value' => $fixed_budget_range_value ,
					];
				} else {
				
					$data[] = [
						'fixed_budget_range_key' => $row->min_budget."_".$row->max_budget,
						'fixed_budget_range_value' => $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($row->min_budget, 0, '', ' ') ."&nbsp;".CURRENCY ,
					];
				
				}
			}
			if($this->config->item('fixed_budget_projects_confidential_dropdown_option')){
				$fixed_budget_projects_confidential_dropdown_option =  $this->config->item('fixed_budget_projects_confidential_dropdown_option');
				
				$data[] = [
						'fixed_budget_range_key' => key($fixed_budget_projects_confidential_dropdown_option),
						'fixed_budget_range_value' =>$fixed_budget_projects_confidential_dropdown_option['confidential_dropdown_option_selected'] ,
					];
			}
			if($this->config->item('fixed_budget_projects_not_sure_dropdown_option')){
				$fixed_budget_projects_not_sure_dropdown_option =  $this->config->item('fixed_budget_projects_not_sure_dropdown_option');
				
				$data[] = [
						'fixed_budget_range_key' => key($fixed_budget_projects_not_sure_dropdown_option),
						'fixed_budget_range_value' =>$fixed_budget_projects_not_sure_dropdown_option['not_sure_dropdown_option_selected'] ,
					];
			}
			
		}
        return $data;
	}
	
	
	/*
	This function fetch the budget range of hourly type based project
	*/
	
	function get_hourly_rate_based_projects_budget_range()
	{
		
        $count_budget_range_fixed_project = $this->db
		->select ('id')
		->from ('hourly_rate_based_projects_budgets_range')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_budget_range_fixed_project > 0)
		{
			
			$hourly_rate_based_projects_budget_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'hourly_rate_based_projects_budgets_range ORDER BY `min_hourly_rate` *1 ');

			foreach ($hourly_rate_based_projects_budget_range_query->result () as $row)
			{
				
				if($row->max_hourly_rate != "All"){
					if($this->config->item('post_project_budget_range_between')){
						$hourly_rate_based_budget_range_value = $this->config->item('post_project_budget_range_between').'&nbsp;'.number_format($row->min_hourly_rate, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_hourly_rate, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour');
					}else{
						$hourly_rate_based_budget_range_value = number_format($row->min_hourly_rate, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_hourly_rate, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour');
					}
					$data[] = [
						'hourly_rate_based_budget_range_key' => $row->min_hourly_rate."_".$row->max_hourly_rate,
						'hourly_rate_based_budget_range_value' => $hourly_rate_based_budget_range_value,
					];
				}else{
				
					$data[] = [
						'hourly_rate_based_budget_range_key' => $row->min_hourly_rate."_".$row->max_hourly_rate,
						'hourly_rate_based_budget_range_value' => $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($row->min_hourly_rate, 0, '', ' ') ."&nbsp;".CURRENCY.$this->config->item('post_project_budget_per_hour') ,
					];
				
				}
			}
			if($this->config->item('hourly_rate_based_budget_projects_confidential_dropdown_option')){
				$hourly_rate_based_budget_projects_confidential_dropdown_option =  $this->config->item('hourly_rate_based_budget_projects_confidential_dropdown_option');
				
				$data[] = [
						'hourly_rate_based_budget_range_key' => key($hourly_rate_based_budget_projects_confidential_dropdown_option),
						'hourly_rate_based_budget_range_value' =>$hourly_rate_based_budget_projects_confidential_dropdown_option['confidential_dropdown_option_selected'] ,
					];
			}
			if($this->config->item('hourly_rate_based_budget_projects_not_sure_dropdown_option')){
				$hourly_rate_based_budget_projects_not_sure_dropdown_option =  $this->config->item('hourly_rate_based_budget_projects_not_sure_dropdown_option');
				
				$data[] = [
						'hourly_rate_based_budget_range_key' => key($hourly_rate_based_budget_projects_not_sure_dropdown_option),
						'hourly_rate_based_budget_range_value' =>$hourly_rate_based_budget_projects_not_sure_dropdown_option['not_sure_dropdown_option_selected'] ,
					];
			}
			
		}
        return $data;
	}
	
	
	/*
	This function fetch the budget range of full time salary based project
	*/
	
	function get_fulltime_projects_salaries_range()
	{
		
        $count_salary_range_fulltime_project = $this->db
		->select ('id')
		->from ('fulltime_projects_salaries_range')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_salary_range_fulltime_project > 0)
		{
			/* $this->db->order_by('min_budget', 'ASC');
			$res = $this->db->get ('hourly_rate_based_projects_budgets_range');  */
			
			$fulltime_projects_salary_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'fulltime_projects_salaries_range ORDER BY `min_salary` *1 ');

			foreach ($fulltime_projects_salary_range_query->result () as $row)
			{
				
				if($row->max_salary != "All"){
					if($this->config->item('post_project_budget_range_between')){
						$fulltime_salary_range_value = $this->config->item('post_project_budget_range_between').'&nbsp;'.number_format($row->min_salary, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month') .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_salary 	, 0, '', ' ').'&nbsp'.CURRENCY . $this->config->item('post_project_budget_per_month');
					}else{
						$fulltime_salary_range_value = number_format($row->min_salary, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month') .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_salary 	, 0, '', ' ').'&nbsp'.CURRENCY . $this->config->item('post_project_budget_per_month');
					}
					$data[] = [
						'fulltime_salary_range_key' => $row->min_salary ."_".$row->max_salary,
						'fulltime_salary_range_value' => $fulltime_salary_range_value ,
					];
				}else{
				
					$data[] = [
						'fulltime_salary_range_key' => $row->min_salary."_".$row->max_salary,
						'fulltime_salary_range_value' => $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($row->min_salary, 0, '', ' ') ."&nbsp;".CURRENCY .$this->config->item('post_project_budget_per_month') ,
					];
				
				}
			}
			if($this->config->item('fulltime_projects_salary_confidential_dropdown_option')){
				$fulltime_projects_salary_confidential_dropdown_option =  $this->config->item('fulltime_projects_salary_confidential_dropdown_option');
				
				$data[] = [
						'fulltime_salary_range_key' => key($fulltime_projects_salary_confidential_dropdown_option),
						'fulltime_salary_range_value' =>$fulltime_projects_salary_confidential_dropdown_option['confidential_dropdown_option_selected'] ,
					];
			}
			if($this->config->item('fulltime_projects_salary_not_sure_dropdown_option')){
				$fulltime_projects_salary_not_sure_dropdown_option =  $this->config->item('fulltime_projects_salary_not_sure_dropdown_option');
				
				$data[] = [
						'fulltime_salary_range_key' => key($fulltime_projects_salary_not_sure_dropdown_option),
						'fulltime_salary_range_value' =>$fulltime_projects_salary_not_sure_dropdown_option['not_sure_dropdown_option_selected'] ,
					];
			}
			
		}
        return $data;
	}
	 
	/*
	This function fetch the postal code if exist according to locality 
	*/
	
	function get_project_post_codes($locality_id)
	{
		
		$count_project_postal_codes = $this->db
		->select ('id')
		->from ('postal_codes')
		->get ()->num_rows ();
		$data = array(); 
		if($count_project_postal_codes > 0)
		{
	
			$this->db->select ('id,postal_code');
			$this->db->order_by('id', 'ASC');
			$this->db->where ('locality_id', $locality_id);
			$res = $this->db->get ('postal_codes'); 
			foreach ($res->result () as $row)
			{	
				if(!empty( $row->id) && !empty($row->postal_code)){
					$data[] = [
					'id' => $row->id,
					'postal_code' => $row->postal_code,
					];
				}	
			}
		}
        return $data;
	}
	
	public function post_project_validation(){
		
		$i = 0;
		//$project_category = $this->input->post('project_category');
		$project_id = 0;
		//$user = $this->session->userdata('user');
		if($this->input->post('temp_project_id')){
			$project_id = $this->input->post('temp_project_id');
		}else if($this->input->post('project_id')){
			$project_id = $this->input->post('project_id');
		}
		$project_description = $this->input->post('project_description');
		if(!$this->input->post('project_category')){
		
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_parent_category_0';
            $msg['errors'][$i]['message'] = $this->config->item('parent_category_validation_post_project_message');
            $msg['errors'][$i]['error_class'] = 'required';
            $i ++;
		
		}else{
			$project_category = $this->input->post('project_category');
			if(array_key_exists(500,$project_category ) && empty($project_category[500]['project_parent_category'])){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'project_parent_category_0';
				$msg['errors'][$i]['message'] = $this->config->item('parent_category_validation_post_project_message');
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			
			}
		
		}
		if (empty($this->input->post ('project_budget'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_budget';
			$msg['errors'][$i]['error_class'] = 'required';
			if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
				$msg['errors'][$i]['message'] = $this->config->item('project_fixed_budget_validation_post_project_message');
			}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
				$msg['errors'][$i]['message'] = $this->config->item('fulltime_salary_validation_message');
			}
			
            $i ++;
        }
		if (empty($this->input->post ('project_title'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_title';
			$msg['errors'][$i]['error_class'] = 'required';
			if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
				$msg['errors'][$i]['message'] = $this->config->item('project_title_validation_post_project_message');
			}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
				$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_name_validation_message');
			}
            $i ++;
        }if(!empty($this->input->post ('project_title')) && strlen($this->input->post ('project_title')) < $this->config->item('project_title_minimum_length_characters_limit_post_project')){
		
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_title';
			$msg['errors'][$i]['error_class'] = 'min_length';
			if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
				$msg['errors'][$i]['message'] = $this->config->item('project_title_characters_min_length_validation_post_project_message');
			}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
				$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_name_characters_min_length_validation_post_project_message');
			}
			$i ++;
		}
		//if(!empty($this->input->post ('project_title')) && strlen($this->input->post ('project_title')) >= $this->config->item('project_title_minimum_length_characters_limit_post_project') && check_unique_project_title($project_id,$user[0]->user_id,trim($this->input->post ('project_title')))){
		if(!empty($this->input->post ('project_title')) && strlen($this->input->post ('project_title')) >= $this->config->item('project_title_minimum_length_characters_limit_post_project') && check_unique_project_title($project_id,trim($this->input->post ('project_title')))){
			
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_title';
			$msg['errors'][$i]['error_class'] = 'unique';
			if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
			$msg['errors'][$i]['message'] = $this->config->item('project_title_unique_validation_post_project_message');
			}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
				$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_name_unique_validation_post_project_message');
			}
			$i ++;
		}
		if (empty($this->input->post ('project_description'))){
            $msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_description';
			$msg['errors'][$i]['error_class'] = 'required';
			if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
				$msg['errors'][$i]['message'] = $this->config->item('project_description_validation_post_project_message');
			}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
				$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_description_validation_message');
			}
			
            $i ++;
        }
		if(!empty($this->input->post ('project_description'))){
			
			$project_description_charcaters_length = strlen(preg_replace('/\s+/', '', $this->input->post ('project_description')));
			
			if($this->config->item('project_description_minimum_length_words_limit_post_project') == 0){
				
				if($project_description_charcaters_length < $this->config->item('project_description_minimum_length_characters_limit_post_project')){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'project_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){

						$msg['errors'][$i]['message'] = $this->config->item('project_description_characters_minimum_length_validation_post_project_message');
					}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
						$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_description_characters_minimum_length_validation_post_project_message');
					}
					$i ++;
				}
			
			}else{
				//remove unwanted space between words
				$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('project_description'));
		 
				$project_description_word_count = count(explode(' ', trim($string_only_single_space)));
				
				
			
				if($project_description_charcaters_length < $this->config->item('project_description_minimum_length_characters_limit_post_project') ||$project_description_word_count < $this->config->item('project_description_minimum_length_words_limit_post_project') ){
					
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'project_description';
					$msg['errors'][$i]['error_class'] = 'min_length';
					if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
						
						$msg['errors'][$i]['message'] = $this->config->item('project_description_characters_words_minimum_length_validation_post_project_message');
						}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
						$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_description_characters_words_minimum_length_validation_post_project_message');
						
					}
					$i ++;
				}
			}
		}
		/* if(!empty($this->input->post ('project_description')) && strlen(str_replace(" ","",$project_description)) < $this->config->item('project_description_minimum_length_characters_limit_post_project')){
		
			$msg['status'] = 'FAILED';
            $msg['errors'][$i]['id'] = 'project_description';
			$msg['errors'][$i]['error_class'] = 'min_length';
			if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){

				$msg['errors'][$i]['message'] = $this->config->item('project_description_characters_minimum_length_validation_post_project_message');
			}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
				$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_description_characters_minimum_length_validation_post_project_message');
			}
			
			$i ++;
		} */
		
		if($this->input->post ('location_option') == '0'){
		
		
			if(empty($this->input->post ('project_county_id'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'project_county_id';
				$msg['errors'][$i]['message'] = $this->config->item('project_county_validation_post_project_message');
					$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}
			/* if(!empty($this->input->post('project_county_id')) && empty($this->input->post ('project_locality_id'))){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'project_locality_id';
				$msg['errors'][$i]['message'] = $this->config->item('project_locality_validation_post_project_message');
				$msg['errors'][$i]['error_class'] = 'required';
					$i ++;
			}
			if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id')) && empty($this->input->post ('project_postal_code_id'))){
				if(!empty($this->get_project_post_codes($this->input->post ('project_locality_id')))){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'project_postal_code_id';
					$msg['errors'][$i]['message'] = $this->config->item('project_postal_code_validation_post_project_message');
					$msg['errors'][$i]['error_class'] = 'required';
					$i ++;
				}
				
			} */
		}
		
		
		if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
	
	}
	
	/**
	* This function is used to delete the temporary project from database with attachment,folders etc.
	*/
	public function delete_temp_project($temp_project_id){
	
		$this->db->delete('temp_projects_categories_listing_tracking', array('temp_project_id' => $temp_project_id));
		$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
		$this->db->delete('temp_projects_attachments', array('temp_project_id' => $temp_project_id));
		
		$project_detail = $this->db->get_where('temp_projects', ['temp_project_id' => $temp_project_id])->row_array();
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] 	= FTP_PORT;
		$config['debug']    = TRUE;
		$this->ftp->connect($config); 
		$this->db->delete('temp_projects', array('temp_project_id' => $temp_project_id));
		
		
		$temp_dir = TEMP_DIR;
		$projects_ftp_dir = PROJECTS_FTP_DIR;
		$projects_temp_dir = PROJECT_TEMPORARY_DIR;
		$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
		
		if($this->session->userdata('user')){
			$user_detail = $this->db->get_where('users', ['user_id' => $project_detail['temp_project_owner_id']])->row_array();
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$profile_folder     = $user_detail['profile_name'];
			if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id)))
			{
				$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id);// delete project directory 
			}
		}
		if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id)))
		{
			$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);// delete project directory 
		}
		
		
	
		$this->ftp->close();
	} 
	
	/**
	This function is used to post project the project and save the project information in project temporary table.
	*/
	public function post_temp_project(){
		$temp_project_id = $this->input->post('temp_project_id');
		/* if(check_session_validity()){  */
			$user = $this->session->userdata('user');
			$this->db->where('temp_project_id', $temp_project_id);
			$temp_project_data = $this->db->get('temp_projects')->row_array();
			
			if(empty($temp_project_data)) { // if project not exists it will redirect to dashboard page
				$res = [
					'status' => 400,
					'location'=>VPATH.$this->config->item('dashboard_page_url')
				];
				echo json_encode($res);
				die;
			}
			
			if(!$this->session->userdata('user')){
			
				if($temp_project_data['temp_project_owner_id'] != 0){
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}
			/* if(!empty($temp_project_data) && $temp_project_data['temp_project_owner_id'] != $user[0]->user_id){
				$this->delete_temp_project($temp_project_id);
				$res = [
					'status' => 400,
					'location'=>VPATH.$this->config->item('dashboard_page_url')
				];
				echo json_encode($res);
				die;
			} */
			$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
			if($check_project_categories_available_or_not == 0){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
				die;
			}
				
			$project_expiration_timestamp = $temp_project_data['project_expiration_date']!= NULL ? strtotime ($temp_project_data['project_expiration_date']) : 0;
			if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
				$this->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
				$res = [
					'status' => 400,
					'location'=>VPATH . $this->config->item('dashboard_page_url')
				];
				echo json_encode($res);
				die;
			}else{
			
				// update expiration time on user action
				$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
				$upate_data = [
					'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
					'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
				];
				$this->db->where('temp_project_id', $temp_project_id);
				$this->db->update('temp_projects', $upate_data);
			}

			$validation_data_array = $this->post_project_validation();
			
			if ($validation_data_array['status'] == 'SUCCESS')
			{
			
				if($this->session->userdata('user')){
				
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					
					if($user_detail['current_membership_plan_id'] == 1){
						$po_max_draft_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$po_max_open_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$po_max_draft_fulltime_projects_number = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$po_max_open_fulltime_projects_number =  $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
						$project_error_message = $this->config->item('free_membership_subscriber_post_project_page_preview_project_project_posting_disabled_message');
						$fulltime_project_error_message = $this->config->item('free_membership_subscriber_post_project_page_preview_fulltime_project_project_posting_disabled_message');
						
						
						
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$po_max_draft_projects_number =$this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$po_max_open_projects_number = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$po_max_draft_fulltime_projects_number =$this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$po_max_open_fulltime_projects_number = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
						
						$project_error_message = $this->config->item('gold_membership_subscriber_post_project_page_preview_project_project_posting_disabled_message');
						$fulltime_project_error_message = $this->config->item('gold_membership_subscriber_post_project_page_preview_fulltime_project_project_posting_disabled_message');
					}
					
					// standard project availability
					$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
					$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
					$standard_valid_time_arr = array_filter($standard_check_valid_arr);
					
					
					
					if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
						
						if($po_max_draft_projects_number == '0' && ($po_max_open_projects_number == '0' || empty($standard_valid_time_arr))){
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$project_error_message]);die;
						}
					} else {
						
						if($po_max_draft_fulltime_projects_number == '0' && ($po_max_open_fulltime_projects_number == '0' || empty($standard_valid_time_arr) )){
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$fulltime_project_error_message]);die;
						
						}
					}
					
					
					
					
					
				}	
				
				/* $page_type = 'post_project';
				$page_id = $temp_project_id;
				if(!$this->session->userdata('user')){
					echo json_encode(['status' => 201,'login_status'=>'0','page_type'=>$page_type,'page_id'=>$page_id,'location'=>'','data'=>$this->load->view('signin/signin_popup',array('page_id'=>$page_id,'page_type'=>$page_type), true)]);
					die;
				} */
			
				
				/* $this->db->select('current_membership_plan_id');
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
				
				
				if($user_detail['current_membership_plan_id'] == 1){
					$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
					$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

					$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
					$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
				}
				if($user_detail['current_membership_plan_id'] == 4){
					$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
					$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

					$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
					$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
				} */
				
				###################### check that user selected category is valid or not(admin deactive/delete the category)
				$check_project_parent_category_status = false;
				if($this->input->post('project_category')){
					foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
						if(isset($project_category_value['project_parent_category']) && !empty($project_category_value['project_parent_category'])){
							$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
							if($check_project_parent_category_exist){
								$check_project_parent_category_status = true;
								break;
							}
						}
					}				
				}
				if(!$check_project_parent_category_status){
					$res = [
						'status' => 400,
						'error' => $this->config->item('post_project_valid_category_not_existent_popup_message'),
						'popup_heading'=>$this->config->item('popup_alert_heading'),
						'location'=>''
					];
					echo json_encode($res);
					die;
				}
				
				$project_locality_id = 0;
				$project_county_id = 0;
				$escrow_payment_method = 'N';
				$offline_payment_method = 'N';
				
				$upgrade_type_featured = 'N';
				$upgrade_type_urgent = 'N';
				$upgrade_type_sealed = 'N';
				$upgrade_type_hidden = 'N';
				$not_sure_dropdown_option_selected = 'N';
				$confidential_dropdown_option_selected = 'N';
				$min_budget = 0;$max_budget = 0;
				$postal_code_id = 0;$project_type = "fixed";
				
				if($this->input->post('escrow_payment_method') == 'Y'){
					$escrow_payment_method = $this->input->post('escrow_payment_method');
				}
				
				if($this->input->post ('offline_payment_method') == 'Y'){
					$offline_payment_method = $this->input->post ('offline_payment_method');
				}
				if(!empty($this->input->post ('project_budget'))){
					$project_budget = $this->input->post ('project_budget');
					if($project_budget == 'confidential_dropdown_option_selected'){
						$confidential_dropdown_option_selected = 'Y';
					}else if($project_budget == 'not_sure_dropdown_option_selected'){
						$not_sure_dropdown_option_selected = 'Y';
					}else{
						$project_budget_array = explode("_",$this->input->post ('project_budget'));
						$min_budget = $project_budget_array[0];
						$max_budget = $project_budget_array[1]; 
					}
				}
				
				//if($this->input->post ('location_option') == '0'){
				if(!empty($this->input->post ('project_locality_id'))){
				$project_locality_id = $this->input->post ('project_locality_id');
				}if(!empty($this->input->post ('project_county_id'))){
					$project_county_id = $this->input->post ('project_county_id');
				}
				
				//}
				if(!empty($this->input->post ('upgrade_type_featured'))){
					$upgrade_type_featured = 'Y';
				}
				if(!empty($this->input->post ('upgrade_type_hidden'))){
					$upgrade_type_hidden = 'Y';
				}
				if(!empty($this->input->post ('upgrade_type_sealed'))){
					$upgrade_type_sealed = 'Y';
				}
				if(!empty($this->input->post ('upgrade_type_urgent'))){
					$upgrade_type_urgent = 'Y';
				}
				if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id'))){
				
					$postal_code_id = $this->input->post('project_postal_code_id');
					
				}
				
				if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
					$project_type = $this->input->post('project_type');
					
				
				}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
					$project_type = 'fulltime';
				}
				
				
				
				
				
				//$temp_project_id = $this->input->post('temp_project_id');
				$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
				$temp_project_data = array (
					'project_title'=>trim($this->input->post('project_title')),
					'project_description'=>trim($this->input->post('project_description')),
					'locality_id'=>$project_locality_id,
					'county_id'=>$project_county_id,
					'postal_code_id'=>$postal_code_id,
					'project_type'=>$project_type,
					'min_budget'=>$min_budget,
					'max_budget'=>$max_budget,
					'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
					'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
					'escrow_payment_method'=>$escrow_payment_method,
					'offline_payment_method'=>$offline_payment_method,
					'featured'=>$upgrade_type_featured,
					'urgent'=>$upgrade_type_urgent,
					'sealed'=>$upgrade_type_sealed,
					'hidden'=>$upgrade_type_hidden,
					'project_owner_last_activity_date' => date('Y-m-d H:i:s'),
					'project_expiration_date' => date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'))
					   
				);			
				
				
				$this->db->where ('temp_project_id', $temp_project_id);
				$this->db->update ('temp_projects', $temp_project_data);
				
				$this->db->update('temp_projects', ['temp_project_owner_id' =>$user[0]->user_id], ['temp_project_id' => $temp_project_id]);
				####### remove project categories and add the new one ###############
				$this->db->delete('temp_projects_categories_listing_tracking', array('temp_project_id' => $temp_project_id));
				
				foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
					
					$temp_project_category_id = 0;
					$temp_project_parent_category_id = 0;
					
					if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category'])){
						
						
						$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

						$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);
						if($check_project_parent_category_exist){
							if($check_project_child_category_exist){
								
								$temp_project_category_id = $project_category_value['project_child_category'];
								$temp_project_parent_category_id = $project_category_value['project_parent_category'];
							
							}else{
								
								$temp_project_category_id =  $project_category_value['project_parent_category'];
								$temp_project_parent_category_id = 0;
								
							}
						}
						
					}else{
						
						$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
						if($check_project_parent_category_exist){
						
							$temp_project_category_id =  $project_category_value['project_parent_category'];
							$temp_project_parent_category_id = 0;
						
						}
					
					}
					if(!empty($temp_project_category_id) || !empty($temp_project_parent_category_id)){
						$this->db->insert ('temp_projects_categories_listing_tracking', array(
								'temp_project_id' => $temp_project_id,
								'temp_project_category_id' => $temp_project_category_id,
								'temp_project_parent_category_id' => $temp_project_parent_category_id
						));
					}		
					
				}
				
			   ####### remove project categories and add the new one ###############  
			   
			   ############# add and remove tags start ##########
			   
				$this->db->delete('temp_projects_tags', array('temp_project_id' => $temp_project_id));
				if(!empty($this->input->post('project_tag'))){
					foreach($this->input->post('project_tag') as $project_tag_key){
						if(!empty($project_tag_key['tag_name'])){
							$temp_project_tag_data = array('temp_project_id' => $temp_project_id,
							'temp_project_tag_name' => trim($project_tag_key['tag_name']));
							$this->db->insert ('temp_projects_tags',$temp_project_tag_data );
						}
					}	
				}
				
				// move the project attachments of logged off version to dedicated user folder start 
				if($this->session->userdata('user')){
					//$user = $this->session->userdata('user');
					$profile_folder     = $user[0]->profile_name;
					######## connectivity of remote server start#########
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					######## connectivity of remote server end #######
					$temp_dir = TEMP_DIR;
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$logged_off_users_temporary_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir);
					
					
					
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR);
					
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir, 0777);// create temporary directory in projects folder
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
					
					$this->db->select('temp_project_attachment_name');
					$this->db->from('temp_projects_attachments');
					$this->db->where('temp_project_id',$temp_project_id);
					$this->db->order_by('id',"asc");
					$temp_project_attachment_result = $this->db->get();
					$temp_project_attachment_data = $temp_project_attachment_result->result_array();
					
					if(!empty($temp_project_attachment_data)){
						
						foreach($temp_project_attachment_data as $attachment_key => $attachment_value){
								
							if(!empty($attachment_value['temp_project_attachment_name'])){
								
								$source_path = DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
								$file_size = $this->ftp->get_filesize($source_path);
								if($file_size != '-1'){
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$projects_temp_dir.$temp_project_id.DIRECTORY_SEPARATOR .$attachment_value['temp_project_attachment_name'];
									$this->ftp->move($source_path, $destination_path);
									
								}
							}
						}
						
					}
					// remov entry from open bidding table
					if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id))) {
						$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project_id);
					}
				}
				
				// move the project attachments of logged off version to dedicated user folder end 
				
			   ###########################################
			   $msg['status'] = 'SUCCESS';
			   $msg['message'] = '';
			   $msg['location'] = VPATH . $this->config->item('post_project_temporary_project_preview_page_url').'?id='.$temp_project_id ;
			   echo json_encode ($msg);
			}else{
				echo json_encode ($validation_data_array);
				die;
			}
		/* }else{
			$this->db->where('temp_project_id', $temp_project_id);
			$temp_project_data = $this->db->get('temp_projects')->row_array();
			if(!empty($temp_project_data)){
				$this->delete_temp_project($temp_project_id);// Delete the temporary project with complete files,data and redirect to dasboard
			}	
			$msg['status'] = 400;
			$msg['location'] = VPATH;
			echo json_encode($msg);
			die;
		} */
		
	}
	
	/**
	* This function is used count the number of featured upgrades included his membership of user per month.
	*/
   
	function count_user_featured_membership_included_upgrades_monthly($user_id){
	
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
		
		$membership_included_featured_upgrades_purchase_count = 0;
		
		$membership_included_featured_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('projects_awm_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','featured')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();

		$membership_included_featured_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('proj_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','featured')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();
		return $membership_included_featured_upgrades_purchase_count;
   
	}
	
	/**
	* This function is used count the number of urgent upgrades included his membership of user per month.
	*/
   
	function count_user_urgent_membership_included_upgrades_monthly($user_id){
	
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();

		$membership_included_featured_upgrades_purchase_count = 0;

		$membership_included_featured_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('projects_awm_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','urgent')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();

		$membership_included_featured_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('proj_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','urgent')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();
		return $membership_included_featured_upgrades_purchase_count;
   
	}
	
	/**
	* This function is used count the number of sealed upgrades included his membership of user per month.
	*/
	function count_user_sealed_membership_included_upgrades_monthly($user_id){
	
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();

		$membership_included_sealed_upgrades_purchase_count = 0;

		$membership_included_sealed_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('projects_awm_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','sealed')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();

		$membership_included_sealed_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('proj_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','sealed')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();
		return $membership_included_sealed_upgrades_purchase_count;
   
	}
	
	/**
	* This function is used count the number of hidden upgrades included his membership of user per month.
	*/
	function count_user_hidden_membership_included_upgrades_monthly($user_id){
	
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();

		$membership_included_hidden_upgrades_purchase_count = 0;

		$membership_included_hidden_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('projects_awm_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','hidden')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();

		$membership_included_hidden_upgrades_purchase_count += $this->db
		->select ('id')
		->from ('proj_membership_included_upgrades_purchase_tracking')
		->where ('project_owner_id',$user_id)
		->where ('project_owner_membership_plan_id',$user_detail['current_membership_plan_id'])
		->where ('project_upgrade_type','hidden')
		->where ('MONTH(project_upgrade_purchase_date)',date('m'))
		->get ()->num_rows ();
		return $membership_included_hidden_upgrades_purchase_count;
   
	}
	
	
	/**
	*	This function is used to save user upgrade purchase tracking  and refrence sequences and deduct the bonus balance and account balance when PO posted the project into open for bidding status .
	*/
	function user_project_upgrade_purchase_refresh_sequence_tracking_save($project_data,$user_id){
	
		// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
		$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user_id); // count user membership featured  upgrade

		$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user_id);// count user membership urgent upgrade
		
		$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user_id);// count user membership sealed upgrade
		
		$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user_id);// count user membership hidden upgrade
		
		
		
		
		//$upgrade_type_featured = 'N';$upgrade_type_urgent = 'N';$upgrade_type_sealed = 'N';$upgrade_type_hidden = 'N';
		
		$user_selected_upgrades = array();
		$sealed_hidden_upgrade_cnt = 0; // this counter is used to identify sealed + hidden upgrade
		if(!empty($project_data['featured']) && $project_data['featured'] == 'Y' ){
			$upgrade_type_featured = 'Y';
			$user_selected_upgrades[] = 'featured';
		}
		if(!empty($project_data['urgent']) && $project_data['urgent'] == 'Y'){
			$upgrade_type_urgent = 'Y';
			$user_selected_upgrades[] = 'urgent';
		}
		if(!empty($project_data['sealed']) && $project_data['sealed'] == 'Y'){
			$upgrade_type_sealed = 'Y';
			$user_selected_upgrades[] = 'sealed';
			$sealed_hidden_upgrade_cnt++;
		}
		if(!empty($project_data['hidden']) && $project_data['hidden'] == 'Y'){
			$upgrade_type_hidden = 'Y';
			$user_selected_upgrades[] = 'hidden';
			$sealed_hidden_upgrade_cnt++;
		}
		
		$project_id = $project_data['project_id'];
	
		if(!empty($user_selected_upgrades)){
			$refresh_sequence_tables = [
				'featured' => 'featured_projects_refresh_sequence_tracking',
				'urgent' => 'urgent_projects_refresh_sequence_tracking',
				'sealed' => 'sealed_projects_refresh_sequence_tracking'
			];
			foreach( $user_selected_upgrades as $value){
				
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
				$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
				
				$project_upgrade_type =  $value;
				$project_upgrade_price =  $this->config->item('project_upgrade_price_'.$value);
				$project_upgrade_availability =  $this->config->item('project_upgrade_availability_'.$value);
				$project_upgrade_refresh_sequence =  $this->config->item('project_upgrade_refresh_sequence_'.$value);
				$project_upgrade_end =  $this->config->item('project_upgrade_availability_'.$value);
				
				$time_arr = explode(':', $project_upgrade_end);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				
				if($project_upgrade_type == 'sealed') {
					$time_arr = explode(':', $this->config->item('project_upgrade_availability_sealed'));
					$stand_arr = explode(':', $this->config->item('standard_project_availability'));
					$featured_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
					$urgent_arr = explode(':', $this->config->item('project_upgrade_availability_urgent'));
					$hidden_arr = explode(':', $this->config->item('project_upgrade_availability_hidden'));

					$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
					$standard_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$stand_arr[0].' hour +'.(int)$stand_arr[1].' minutes +'.(int)$stand_arr[2].' seconds'));
					$featured_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$featured_arr[0].' hour +'.(int)$featured_arr[1].' minutes +'.(int)$featured_arr[2].' seconds'));
					$urgent_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$urgent_arr[0].' hour +'.(int)$urgent_arr[1].' minutes +'.(int)$urgent_arr[2].' seconds'));
					$hidden_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$hidden_arr[0].' hour +'.(int)$hidden_arr[1].' minutes +'.(int)$hidden_arr[2].' seconds'));
					if(strtotime($standard_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $standard_project_end_date;
						$project_upgrade_availability = $this->config->item('standard_project_availability');
					}
					if(strtotime($featured_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $featured_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_featured');
					}
					if(strtotime($urgent_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $urgent_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_urgent');
					}
					if(strtotime($hidden_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $hidden_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_hidden');
					}
				}
				
				if($project_upgrade_type == 'hidden') {
					$time_arr = explode(':', $this->config->item('project_upgrade_availability_hidden'));
					$stand_arr = explode(':', $this->config->item('standard_project_availability'));
					$sealed_arr = explode(':', $this->config->item('project_upgrade_availability_sealed'));

					$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
					$standard_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$stand_arr[0].' hour +'.(int)$stand_arr[1].' minutes +'.(int)$stand_arr[2].' seconds'));
					$sealed_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$sealed_arr[0].' hour +'.(int)$sealed_arr[1].' minutes +'.(int)$sealed_arr[2].' seconds'));

					if(strtotime($standard_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $standard_project_end_date;
						$project_upgrade_availability = $this->config->item('standard_project_availability');
					}
					if(strtotime($sealed_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $sealed_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_sealed');
					}
				}
				
				$upgrades_purchase_data = [
					'project_id' => $project_id,
					'project_owner_id' => $user_id,
					'project_owner_membership_plan_id' => $user_detail['current_membership_plan_id'],
					'project_upgrade_purchase_date' => date('Y-m-d H:i:s'),
					'project_upgrade_type' => $project_upgrade_type,
					'project_upgrade_availability_length' => $project_upgrade_availability,
					'project_upgrade_start_date' => date('Y-m-d H:i:s'),
					'project_upgrade_end_date' => $upgrade_end_date
				];
				
				$upgrade_purchase_tracking_data = [
					'project_id' => $project_id,
					'project_owner_id' => $user_id,
					'project_upgrade_type' => $project_upgrade_type,
					'project_upgrade_purchase_date' => date('Y-m-d H:i:s'),
				];
				// insert into projects_refresh_sequence_tracking table
				
				
				$refresh_sequence_data = [
					'project_id' => $project_id,
					'project_last_refresh_time' => null
				];
				
				
				if(!empty($project_upgrade_refresh_sequence)) {
					$time_arr = explode(':',$project_upgrade_refresh_sequence);
					$check_valid_arr = array_map('getInt', $time_arr); 
					$valid_time_arr = array_filter($check_valid_arr);
					$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
				}
				if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1'){
					$remaining_membership_point_featured = $user_membership_plan_detail['included_number_featured_upgrades'] - $count_user_featured_membership_included_upgrades_monthly;
				}else if($user_membership_plan_detail['included_number_featured_upgrades'] == '-1'){
				
					$remaining_membership_point_featured  = '-1';
				}
				if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1'){
					$remaining_membership_point_urgent = $user_membership_plan_detail['included_number_urgent_upgrades']  - $count_user_urgent_membership_included_upgrades_monthly;
				}else if($user_membership_plan_detail['included_number_urgent_upgrades'] == '-1'){
					$remaining_membership_point_urgent = '-1';
				}
				
				if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1'){
					$remaining_membership_point_sealed = $user_membership_plan_detail['included_number_sealed_upgrades']  - $count_user_sealed_membership_included_upgrades_monthly;
				} else if($user_membership_plan_detail['included_number_sealed_upgrades'] == '-1'){
					$remaining_membership_point_sealed = '-1';
				}
				
				if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1'){
					$remaining_membership_point_hidden = $user_membership_plan_detail['included_number_hidden_upgrades']  - $count_user_hidden_membership_included_upgrades_monthly;
				} else if($user_membership_plan_detail['included_number_hidden_upgrades'] == '-1'){
					$remaining_membership_point_hidden = '-1';
				}
				
				$vat_percentage = $this->config->item('vat_percentage');
				
				if( ($remaining_membership_point_featured == '-1' || ($remaining_membership_point_featured != '-1' && $remaining_membership_point_featured >=1) ) && $project_upgrade_type == 'featured'){
					
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;

					$reference_id = PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
					$latest_released_escrow_data = $this->db->from('proj_membership_included_upgrades_purchase_tracking')->where('membership_included_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($latest_released_escrow_data)) {
						$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['membership_included_project_upgrade_purchase_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
						$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					$vat_excluded_amount = ($project_upgrade_price * 100)/ ($vat_percentage+100);
					$vat_amount = $project_upgrade_price - $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'] = $vat_amount;

					$this->db->insert('proj_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);

					########################## insert data into projects_upgrades_purchases_tracking table ##########################################
					$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'membership_included';
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

					$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
					$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
					$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);
					$po_lv1_percentage_value = 0;
					$po_lv2_percentage_value = 0;

					if(!empty($po_lvl1_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
					}

					if(!empty($po_lvl2_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
					}

					$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
					$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
					############################################ End ##############################################################################
					$last_insert_id = $this->db->insert_id();
					$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
					$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] =  'membership_included';
					if(!empty($valid_time_arr)) {
						$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $this->config->item('project_upgrade_refresh_sequence_featured');
						if($project_data['hidden'] == 'N') {
							$this->db->insert($refresh_sequence_tables[$project_upgrade_type], $refresh_sequence_data);
						}
					}
				} else if(($remaining_membership_point_urgent == '-1' || ($remaining_membership_point_urgent != '-1' && $remaining_membership_point_urgent >=1) ) && $project_upgrade_type == 'urgent'){
					
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;

					$reference_id = PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
					$latest_released_escrow_data = $this->db->from('proj_membership_included_upgrades_purchase_tracking')->where('membership_included_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($latest_released_escrow_data)) {
						$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['membership_included_project_upgrade_purchase_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
						$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					$vat_excluded_amount = ($project_upgrade_price * 100)/ ($vat_percentage+100);
					$vat_amount = $project_upgrade_price - $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'] = $vat_amount;
					
					$this->db->insert('proj_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);

					########################## insert data into projects_upgrades_purchases_tracking table ##########################################
					$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'membership_included';
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

					$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
					$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
					$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);
					$po_lv1_percentage_value = 0;
					$po_lv2_percentage_value = 0;

					if(!empty($po_lvl1_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
					}

					if(!empty($po_lvl2_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
					}

					$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
					$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
					############################################ End ##############################################################################

					$last_insert_id = $this->db->insert_id();
					$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
					$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] =  'membership_included';
					if(!empty($valid_time_arr)) {
						$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $this->config->item('project_upgrade_refresh_sequence_urgent');

						if($project_data['hidden'] == 'N') {
							$this->db->insert($refresh_sequence_tables[$project_upgrade_type], $refresh_sequence_data);
						}
					}
				} else if(($remaining_membership_point_sealed == '-1' || ($remaining_membership_point_sealed != '-1' && $remaining_membership_point_sealed >=1) ) && $project_upgrade_type == 'sealed'){
					
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;

					$reference_id = PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
					$latest_released_escrow_data = $this->db->from('proj_membership_included_upgrades_purchase_tracking')->where('membership_included_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($latest_released_escrow_data)) {
						$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['membership_included_project_upgrade_purchase_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
						$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					$vat_excluded_amount = ($project_upgrade_price * 100)/ ($vat_percentage+100);
					$vat_amount = $project_upgrade_price - $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'] = $vat_amount;

					$this->db->insert('proj_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);

					########################## insert data into projects_upgrades_purchases_tracking table ##########################################
					$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'membership_included';
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

					$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
					$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
					$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);

					$po_lv1_percentage_value = 0;
					$po_lv2_percentage_value = 0;

					if(!empty($po_lvl1_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
					}

					if(!empty($po_lvl2_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
					}

					$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
					$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
					############################################ End ##############################################################################

					$last_insert_id = $this->db->insert_id();
					$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
					$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] =  'membership_included';
					if(!empty($valid_time_arr)) {
						$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $this->config->item('project_upgrade_refresh_sequence_sealed');
						if($project_data['hidden'] == 'N') {
							$this->db->insert($refresh_sequence_tables[$project_upgrade_type], $refresh_sequence_data);
						}
					}
				} else if(($remaining_membership_point_hidden == '-1' || ($remaining_membership_point_hidden != '-1' && $remaining_membership_point_hidden >=1) ) && $project_upgrade_type == 'hidden'){
					
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;

					$reference_id = PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
					$latest_released_escrow_data = $this->db->from('proj_membership_included_upgrades_purchase_tracking')->where('membership_included_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($latest_released_escrow_data)) {
						$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['membership_included_project_upgrade_purchase_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
						$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					$vat_excluded_amount = ($project_upgrade_price * 100)/ ($vat_percentage+100);
					$vat_amount = $project_upgrade_price - $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'] = $vat_amount;

					$this->db->insert('proj_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);

					########################## insert data into projects_upgrades_purchases_tracking table ##########################################
					$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'membership_included';
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
					$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
					$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;
					$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
					$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
					$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);

					$po_lv1_percentage_value = 0;
					$po_lv2_percentage_value = 0;

					if(!empty($po_lvl1_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
					}

					if(!empty($po_lvl2_referal_data)) {
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
						$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
					}

					$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
					$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
					############################################ End ##############################################################################

					$last_insert_id = $this->db->insert_id();
				} else {
					$remaining_upgrade_amount_after_deduction = 0;
					$deduction_amount = 0;
					$remaining_signup_bonus_balance =0;
					$remaining_bonus_balance =0;
					$remaining_user_account_balance =0;
					
					if( $user_detail['signup_bonus_balance'] >= $project_upgrade_price ){
						$deduction_amount = $project_upgrade_price;
						$remaining_signup_bonus_balance =  $user_detail['signup_bonus_balance'] - $deduction_amount;
					} else{
						$remaining_upgrade_amount_after_deduction = $project_upgrade_price - $user_detail['signup_bonus_balance'];
						$deduction_amount = $user_detail['signup_bonus_balance'];
						$remaining_signup_bonus_balance = 0;
					}
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;
					if(floatval($user_detail['signup_bonus_balance']) != 0){

						$reference_id = PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
						$latest_released_escrow_data = $this->db->from('proj_bonus_based_upgrades_purchase_tracking')->where('bonus_based_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['bonus_based_project_upgrade_purchase_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
						$vat_amount = $deduction_amount - $vat_excluded_amount;
	
						$upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'] = $reference_id;
						$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
						$upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
						$upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'] = $vat_amount;

						$this->db->insert('proj_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);

						########################## insert data into projects_upgrades_purchases_tracking table ##########################################
						$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
						$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'bonus_based';
						$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $deduction_amount;
						$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
						$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
						$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;
						$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
						$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
						$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);

						$po_lv1_percentage_value = 0;
						$po_lv2_percentage_value = 0;

						if(!empty($po_lvl1_referal_data)) {
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
							$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
						}

						if(!empty($po_lvl2_referal_data)) {
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
							$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
						}

						$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
						$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
						############################################ End ##############################################################################

						$last_insert_id = $this->db->insert_id();
						$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
						$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] =  'bonus_money';
						$this->db->update('users_details', ['signup_bonus_balance' => $remaining_signup_bonus_balance], ['id' => $user_detail['id']]);
					}
					if($remaining_upgrade_amount_after_deduction != 0  ){
						if( $user_detail['bonus_balance'] >= $remaining_upgrade_amount_after_deduction ){
							$deduction_amount = $remaining_upgrade_amount_after_deduction;
							$remaining_bonus_balance = $user_detail['bonus_balance'] -$remaining_upgrade_amount_after_deduction;
							$remaining_upgrade_amount_after_deduction = 0;
						} else{
							$deduction_amount =  $user_detail['bonus_balance'];
							$remaining_upgrade_amount_after_deduction = $remaining_upgrade_amount_after_deduction - $user_detail['bonus_balance'];
							$remaining_bonus_balance = 0;
						}
						if(floatval($user_detail['bonus_balance']) != 0){
							$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;

							$reference_id = PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
							$latest_released_escrow_data = $this->db->from('proj_bonus_based_upgrades_purchase_tracking')->where('bonus_based_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
							$digits = $this->config->item('reference_id_digits_limit');
							if(empty($latest_released_escrow_data)) {
								$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
							} else {
								$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['bonus_based_project_upgrade_purchase_reference_id']);
								$exclude_inital_number = ltrim($exclude_inital_number, '0');
								$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
								$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
							}
							$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
							$vat_amount = $deduction_amount - $vat_excluded_amount;
			
							$upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'] = $reference_id;
							$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
							$upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
							$upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'] = $vat_amount;
							
							$this->db->insert('proj_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);

							########################## insert data into projects_upgrades_purchases_tracking table ##########################################
							$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
							$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'bonus_based';
							$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $deduction_amount;
							$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
							$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
							$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
							$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

							$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
							$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);
							
							$po_lv1_percentage_value = 0;
							$po_lv2_percentage_value = 0;

							if(!empty($po_lvl1_referal_data)) {
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
								
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;
							}

							if(!empty($po_lvl2_referal_data)) {
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
								
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;
							}

							$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
							$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
							############################################ End ##############################################################################

							$last_insert_id = $this->db->insert_id();
							$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
							$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] =  'bonus_money';
							$this->db->update('users_details', ['bonus_balance' => $remaining_bonus_balance], ['id' => $user_detail['id']]);
						}	
						if($remaining_upgrade_amount_after_deduction != 0){
							unset($upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id']);
							unset($upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat']);
							unset($upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value']);


							if( $user_detail['user_account_balance'] >= $remaining_upgrade_amount_after_deduction ){
								$deduction_amount = $remaining_upgrade_amount_after_deduction;
								$remaining_user_account_balance = $user_detail['user_account_balance'] - $deduction_amount;
							}
							$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;

							$reference_id = PROJECT_REAL_MONEY_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
							$latest_released_escrow_data = $this->db->from('proj_real_money_upgrades_purchase_tracking')->where('real_money_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
							$digits = $this->config->item('reference_id_digits_limit');
							if(empty($latest_released_escrow_data)) {
								$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
							} else {
								$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['real_money_project_upgrade_purchase_reference_id']);
								$exclude_inital_number = ltrim($exclude_inital_number, '0');
								$exclude_inital_number = !empty($exclude_inital_number) ? (int)$exclude_inital_number + 1 : 1;
								$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
							}
							$vat_excluded_amount = ($deduction_amount * 100)/ ($vat_percentage+100);
							$vat_amount = $deduction_amount - $vat_excluded_amount;
							
							$upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'] = $reference_id;
							$upgrades_purchase_data['vat_percentage_value'] = $vat_percentage;
							$upgrades_purchase_data['real_money_project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
							$upgrades_purchase_data['real_money_project_upgrade_purchase_vat_amount_value'] = $vat_amount;

							$this->db->insert('proj_real_money_upgrades_purchase_tracking', $upgrades_purchase_data);

							########################## insert data into projects_upgrades_purchases_tracking table ##########################################
							$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $reference_id;
							$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'real_money';
							$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $deduction_amount;
							$upgrade_purchase_tracking_data['vat_percentage_value'] = $vat_percentage;
							$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $vat_excluded_amount;
							$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
							$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $vat_amount;

							$po_lvl1_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl1_user_id($user_id);
							$po_lvl2_referal_data = $this->Escrow_model->get_referral_user_details_from_lvl2_user_id($user_id);
							
							$po_lv1_percentage_value = 0;
							$po_lv2_percentage_value = 0;

							$ts = strtotime(date('Y-m-d'));
							$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
							$week_start_date = date('Y-m-d', $start);

							if(!empty($po_lvl1_referal_data)) {
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
								$po_lv1_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
								$upgrade_purchase_tracking_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;

								$referral_earnings = [
									'user_id' => $po_lvl1_referal_data['user_id'],
									'referral_earning_date' => date('Y-m-d H:i:s'),
									'referral_earning_source_reference_id' => $upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'],
									'referral_earnig_value' => $po_lv1_percentage_value,
									'referral_earning_lvl_source' => 'lvl1',
									'referee_user_id' => $user_id
								];
								$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

								$daily_earnings = [
									'user_id' => $po_lvl1_referal_data['user_id'],
									'referral_earnings_date' => date('Y-m-d H:i:s'),
									'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

								$weekly_earnings = [
									'user_id' => $po_lvl1_referal_data['user_id'],
									'referral_earnings_week_start_date' => $week_start_date,
									'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

								$monthly_earnings = [
									'user_id' => $po_lvl1_referal_data['user_id'],
									'referral_earnings_month' => date('Y-m-d'),
									'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

								$total_earnings = [
									'user_id' => $po_lvl1_referal_data['user_id'],
									'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
							}

							if(!empty($po_lvl2_referal_data)) {
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
								$po_lv2_percentage_value = ($project_upgrade_purchase_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
								$upgrade_purchase_tracking_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;

								$referral_earnings = [
									'user_id' => $po_lvl2_referal_data['user_id'],
									'referral_earning_date' => date('Y-m-d H:i:s'),
									'referral_earning_source_reference_id' => $upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'],
									'referral_earnig_value' => $po_lv2_percentage_value,
									'referral_earning_lvl_source' => 'lvl2',
									'referee_user_id' => $user_id
								];
								$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

								$daily_earnings = [
									'user_id' => $po_lvl2_referal_data['user_id'],
									'referral_earnings_date' => date('Y-m-d H:i:s'),
									'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

								$weekly_earnings = [
									'user_id' => $po_lvl2_referal_data['user_id'],
									'referral_earnings_week_start_date' => $week_start_date,
									'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

								$monthly_earnings = [
									'user_id' => $po_lvl2_referal_data['user_id'],
									'referral_earnings_month' => date('Y-m-d'),
									'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);
								
								$total_earnings = [
									'user_id' => $po_lvl2_referal_data['user_id'],
									'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
								];
								$this->Escrow_model->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
							}

							$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
							$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);
							############################################ End ##############################################################################
							
							$last_insert_id = $this->db->insert_id();
							$refresh_sequence_data['project_upgrade_purchase_reference_id'] =  $reference_id;
							$refresh_sequence_data['project_upgrade_purchase_tracking_table_source'] =  'real_money';
							
							$amount = $user_detail['signup_bonus_balance'] - $project_upgrade_price;
							$this->db->update('users_details', ['user_account_balance' => $remaining_user_account_balance], ['id' => $user_detail['id']]);
						}

						
					}
				
					if($value != 'hidden' || $sealed_hidden_upgrade_cnt < 2){
						if(!empty($valid_time_arr) && array_key_exists($project_upgrade_type, $refresh_sequence_tables)) {
							if($project_upgrade_type == 'featured'){
								$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $this->config->item('project_upgrade_refresh_sequence_featured');
							}
							if($project_upgrade_type == 'urgent'){
								$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $this->config->item('project_upgrade_refresh_sequence_urgent');
							}
							if($project_upgrade_type == 'sealed'){
								$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $this->config->item('project_upgrade_refresh_sequence_sealed');
							}
							
							if($project_data['hidden'] == 'N') {
								$this->db->insert($refresh_sequence_tables[$project_upgrade_type], $refresh_sequence_data);
							}
							$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
							// update data in projects latest project refresh sequence tracking
							$latest_project_refresh_tracking = [
								'project_id' => $project_id,
								'project_last_refresh_time' => date('Y-m-d H:i:s'),
								'project_next_refresh_time' => $next_refresh_date,
								'refresh_sequence_table_source' => $project_upgrade_type
							];
							if($project_data['hidden'] == 'N') {
								$this->db->update('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking, ['project_id' => $project_id]);
							}
						}
					}
				}
			}	
		}
	}
	
	
	/**
	*	This function is used to save user upgrade purchase tracking  and deduct the bonus balance and account balance .
	*/
	function user_project_upgrade_purchase_tracking_save($project_data,$user_id){
		// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
		$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user_id); // count user membership featured  upgrade
		$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user_id);// count user membership urgent upgrade
		$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user_id);// count user membership sealed upgrade
		$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user_id);// count user membership hidden upgrade
		
		//$upgrade_type_featured = 'N';$upgrade_type_urgent = 'N';$upgrade_type_sealed = 'N';$upgrade_type_hidden = 'N';
		
		$user_selected_upgrades = array();
		
		if(!empty($project_data['featured']) && $project_data['featured'] == 'Y' ){
			$upgrade_type_featured = 'Y';
			$user_selected_upgrades[] = 'featured';
		}
		if(!empty($project_data['urgent']) && $project_data['urgent'] == 'Y'){
			$upgrade_type_urgent = 'Y';
			$user_selected_upgrades[] = 'urgent';
		}
		if(!empty($project_data['sealed']) && $project_data['sealed'] == 'Y'){
			$upgrade_type_sealed = 'Y';
			$user_selected_upgrades[] = 'sealed';
		}
		if(!empty($project_data['hidden']) && $project_data['hidden'] == 'Y'){
			$upgrade_type_hidden = 'Y';
			$user_selected_upgrades[] = 'hidden';
		}
		
		$project_id = $project_data['project_id'];
		if(!empty($user_selected_upgrades)){
			foreach( $user_selected_upgrades as $value){
				
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
				$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
				
				$project_upgrade_type =  $value;
				$project_upgrade_price =  $this->config->item('project_upgrade_price_'.$value);
				$project_upgrade_availability =  $this->config->item('project_upgrade_availability_'.$value);
				//$project_upgrade_refresh_sequence =  $this->config->item('project_upgrade_refresh_sequence_'.$value);
				$project_upgrade_end =  $this->config->item('project_upgrade_availability_'.$value);
				
				if($project_upgrade_type == 'sealed') {
					$time_arr = explode(':', $this->config->item('project_upgrade_availability_sealed'));
					$stand_arr = explode(':', $this->config->item('standard_project_availability'));
					$featured_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
					$urgent_arr = explode(':', $this->config->item('project_upgrade_availability_urgent'));
					$hidden_arr = explode(':', $this->config->item('project_upgrade_availability_hidden'));
					
					$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
					$standard_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$stand_arr[0].' hour +'.(int)$stand_arr[1].' minutes +'.(int)$stand_arr[2].' seconds'));
					$featured_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$featured_arr[0].' hour +'.(int)$featured_arr[1].' minutes +'.(int)$featured_arr[2].' seconds'));
					$urgent_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$urgent_arr[0].' hour +'.(int)$urgent_arr[1].' minutes +'.(int)$urgent_arr[2].' seconds'));
					$hidden_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$hidden_arr[0].' hour +'.(int)$hidden_arr[1].' minutes +'.(int)$hidden_arr[2].' seconds'));

					if(strtotime($standard_project_end_date) > strtotime($upgrade_end_date)) {
						$upgrade_end_date = $standard_project_end_date;
						$project_upgrade_availability = $this->config->item('standard_project_availability');
					}
					if(strtotime($featured_project_end_date) > strtotime($upgrade_end_date) && in_array('featured', $user_selected_upgrades)) {
						$upgrade_end_date = $featured_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_featured');
					}
					if(strtotime($urgent_project_end_date) > strtotime($upgrade_end_date) && in_array('urgent', $user_selected_upgrades)) {
						$upgrade_end_date = $urgent_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_urgent');
					}
					if(strtotime($hidden_project_end_date) > strtotime($upgrade_end_date) && in_array('hidden', $user_selected_upgrades)) {
						$upgrade_end_date = $hidden_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_hidden');
					}
				}
				
				if($project_upgrade_type == 'hidden') {
					$time_arr = explode(':', $this->config->item('project_upgrade_availability_hidden'));
					$stand_arr = explode(':', $this->config->item('standard_project_availability'));
					$featured_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
					$urgent_arr = explode(':', $this->config->item('project_upgrade_availability_urgent'));
					$sealed_arr = explode(':', $this->config->item('project_upgrade_availability_sealed'));

					$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
					$standard_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$stand_arr[0].' hour +'.(int)$stand_arr[1].' minutes +'.(int)$stand_arr[2].' seconds'));
					$featured_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$featured_arr[0].' hour +'.(int)$featured_arr[1].' minutes +'.(int)$featured_arr[2].' seconds'));
					$urgent_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$urgent_arr[0].' hour +'.(int)$urgent_arr[1].' minutes +'.(int)$urgent_arr[2].' seconds'));
					$sealed_project_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$sealed_arr[0].' hour +'.(int)$sealed_arr[1].' minutes +'.(int)$sealed_arr[2].' seconds'));

					if(strtotime($standard_project_end_date) > strtotime($upgrade_end_date) ) {
						$upgrade_end_date = $standard_project_end_date;
						$project_upgrade_availability = $this->config->item('standard_project_availability');
					}
					
					if(strtotime($sealed_project_end_date) > strtotime($upgrade_end_date) && in_array('sealed', $user_selected_upgrades)) {
						$upgrade_end_date = $sealed_project_end_date;
						$project_upgrade_availability = $this->config->item('project_upgrade_availability_sealed');
					}
				}
				$upgrades_purchase_data = [
					'project_id' => $project_id,
					'project_owner_id' => $user_id,
					'project_owner_membership_plan_id' => $user_detail['current_membership_plan_id'],
					'project_upgrade_purchase_date' => date('Y-m-d H:i:s'),
					'project_upgrade_type' => $project_upgrade_type,
					'project_upgrade_availability_length' => $project_upgrade_availability
				];
				if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1'){
					$remaining_membership_point_featured = $user_membership_plan_detail['included_number_featured_upgrades'] - $count_user_featured_membership_included_upgrades_monthly;
				} else if($user_membership_plan_detail['included_number_featured_upgrades'] == '-1'){
					$remaining_membership_point_featured  = '-1';
				}
				if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1'){
					$remaining_membership_point_urgent = $user_membership_plan_detail['included_number_urgent_upgrades']  - $count_user_urgent_membership_included_upgrades_monthly;
				} else if($user_membership_plan_detail['included_number_urgent_upgrades'] == '-1'){
					$remaining_membership_point_urgent = '-1';
				}
				if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1'){
					$remaining_membership_point_sealed = $user_membership_plan_detail['included_number_sealed_upgrades']  - $count_user_sealed_membership_included_upgrades_monthly;
				} else if($user_membership_plan_detail['included_number_sealed_upgrades'] == '-1'){
					$remaining_membership_point_sealed = '-1';
				}
				if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1'){
					$remaining_membership_point_hidden = $user_membership_plan_detail['included_number_hidden_upgrades']  - $count_user_hidden_membership_included_upgrades_monthly;
				} else if($user_membership_plan_detail['included_number_hidden_upgrades'] == '-1'){
					$remaining_membership_point_hidden = '-1';
				}
				$vat_percentage = $this->config->item('vat_percentage');
				if( ($remaining_membership_point_featured == '-1' || ($remaining_membership_point_featured != '-1' && $remaining_membership_point_featured >=1) ) && $project_upgrade_type == 'featured'){
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$this->db->insert('projects_awm_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);
					$last_insert_id = $this->db->insert_id();
				} else if(($remaining_membership_point_urgent == '-1' || ($remaining_membership_point_urgent != '-1' && $remaining_membership_point_urgent >=1) ) && $project_upgrade_type == 'urgent'){
					
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$this->db->insert('projects_awm_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);
					$last_insert_id = $this->db->insert_id();
				} else if(($remaining_membership_point_sealed == '-1' || ($remaining_membership_point_sealed != '-1' && $remaining_membership_point_sealed >=1) ) && $project_upgrade_type == 'sealed'){
					
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$this->db->insert('projects_awm_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);
					$last_insert_id = $this->db->insert_id();
				} else if(($remaining_membership_point_hidden == '-1' || ($remaining_membership_point_hidden != '-1' && $remaining_membership_point_hidden >=1) ) && $project_upgrade_type == 'hidden'){
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $project_upgrade_price;
					$this->db->insert('projects_awm_membership_included_upgrades_purchase_tracking', $upgrades_purchase_data);
					$last_insert_id = $this->db->insert_id();
				} else {
				
					
					$remaining_upgrade_amount_after_deduction = 0;
					$deduction_amount = 0;
					$remaining_signup_bonus_balance =0;
					$remaining_bonus_balance =0;
					$remaining_user_account_balance =0;
					
					if( $user_detail['signup_bonus_balance'] >= $project_upgrade_price ){
						$deduction_amount = $project_upgrade_price;
						$remaining_signup_bonus_balance =  $user_detail['signup_bonus_balance'] - $deduction_amount;
					} else{
						$remaining_upgrade_amount_after_deduction = $project_upgrade_price - $user_detail['signup_bonus_balance'];
						$deduction_amount = $user_detail['signup_bonus_balance'];
						$remaining_signup_bonus_balance = 0;
					}
					$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;
					if(floatval($user_detail['signup_bonus_balance']) != 0){
						$this->db->insert('projects_awm_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);
						$last_insert_id = $this->db->insert_id();
						$this->db->update('users_details', ['signup_bonus_balance' => $remaining_signup_bonus_balance], ['id' => $user_detail['id']]);
					}
					if($remaining_upgrade_amount_after_deduction != 0  ){
						if( $user_detail['bonus_balance'] >= $remaining_upgrade_amount_after_deduction ){
							$deduction_amount = $remaining_upgrade_amount_after_deduction;
							$remaining_bonus_balance = $user_detail['bonus_balance'] -$remaining_upgrade_amount_after_deduction;
							$remaining_upgrade_amount_after_deduction = 0;
						} else{
							$deduction_amount =  $user_detail['bonus_balance'];
							$remaining_upgrade_amount_after_deduction = $remaining_upgrade_amount_after_deduction - $user_detail['bonus_balance'];
							$remaining_bonus_balance = 0;
						}
						if(floatval($user_detail['bonus_balance']) != 0){
							$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;

							$this->db->insert('projects_awm_bonus_based_upgrades_purchase_tracking', $upgrades_purchase_data);
							$last_insert_id = $this->db->insert_id();
							$this->db->update('users_details', ['bonus_balance' => $remaining_bonus_balance], ['id' => $user_detail['id']]);
						}	
						if($remaining_upgrade_amount_after_deduction != 0) {

							unset($upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id']);
							unset($upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat']);
							unset($upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value']);

							if( $user_detail['user_account_balance'] >= $remaining_upgrade_amount_after_deduction ){
								$deduction_amount = $remaining_upgrade_amount_after_deduction;
								$remaining_user_account_balance = $user_detail['user_account_balance'] - $deduction_amount;
								
							} 
							$upgrades_purchase_data['project_upgrade_purchase_value'] = $deduction_amount;

							$this->db->insert('projects_awm_real_money_upgrades_purchase_tracking', $upgrades_purchase_data);
							$last_insert_id = $this->db->insert_id();
							$amount = $user_detail['signup_bonus_balance'] - $project_upgrade_price;
							$this->db->update('users_details', ['user_account_balance' => $remaining_user_account_balance], ['id' => $user_detail['id']]);
						}
					}
				}
			}	
		}
	} 
}
?>