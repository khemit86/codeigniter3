<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Project_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
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
	 * This method is used insert data in to serv_users_new_posted_projects_sent_notifications_tracking  whenever posted project have category mapped in projects_professionals_categories_mapping table and is status changed to open bidding status
	*/
	function add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_for_bidding_project_data, $config) {
		
		$professional_categories = array_column($category_mapping_data, 'professionals_category_id');
		$users_id = $this->get_users_id_from_professionals_categories_id($professional_categories);
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
					$users_send_notification_message = $config['post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
				} else {
					$users_send_notification_message = $config['post_fixed_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
				}
				$project_url_link = HTTP_WEBSITE_HOST.'/'.$config['project_detail_page_url']."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
				
					$budget_range = '';
					if($config['post_project_budget_range_and']){
						$budget_range .= $config['post_project_budget_range_and'].'&nbsp;';
					}
				
					$budget_range = number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $config['post_project_budget_range_and'].'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
				} else {
					$budget_range = $config['post_project_budget_range_more_then'].'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY;
				}
				$users_send_notification_message = str_replace('{fixed_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) { 
						$users_send_notification_message = $config['post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['post_fixed_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) { 
						$users_send_notification_message = $config['post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['post_fixed_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
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
						if(!in_array($user_id['user_id'] ,$favorite_employers_users) || empty($favourite_employers_notification)) {
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
				if(!empty($final_array)){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			} else if ($open_for_bidding_project_data['project_type'] == 'hourly') {
				if(count($categories) == 1) { 
					$users_send_notification_message = $config['post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
				} else {
					$users_send_notification_message = $config['post_hourly_rate_budget_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
				}
				$project_url_link = HTTP_WEBSITE_HOST.'/'.$config['project_detail_page_url']."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
				
					$budget_range = '';
					if($config['post_project_budget_range_between']){
						$budget_range .= $config['post_project_budget_range_between'].'&nbsp;';
					}
					$budget_range = number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$config['post_project_budget_per_hour'].'&nbsp;'. $config['post_project_budget_range_and'].'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$config['post_project_budget_per_hour'];
				} else {
					$budget_range = $config['post_project_budget_range_more_then'].'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY.$config['post_project_budget_per_hour'];
				}
				$users_send_notification_message = str_replace('{hourly_rate_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) { 
						$users_send_notification_message = $config['post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['post_hourly_rate_budget_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) { 
						$users_send_notification_message = $config['post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['post_hourly_rate_budget_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
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
						if(!in_array($user_id['user_id'] ,$favorite_employers_users) || empty($favourite_employers_notification)) {
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
				if(!empty($final_array)){
					$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
					$insert_flag = true;
				}
			} else {
				if(count($categories) == 1) { 
					$users_send_notification_message = $config['post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
				} else {
					$users_send_notification_message = $config['post_fulltime_salary_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
				}
				$project_url_link = HTTP_WEBSITE_HOST.'/'.$config['project_detail_page_url']."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($config['post_project_budget_range_between']){
						$budget_range .= $config['post_project_budget_range_between'].'&nbsp;';
					}
					$budget_range = number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$config['post_project_budget_per_month'] .'&nbsp;'. $config['post_project_budget_range_and'].'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'] 	, 0, '', ' ').'&nbsp'.CURRENCY . $config['post_project_budget_per_month'];
				} else {
					$budget_range = $config['post_project_budget_range_more_then'].'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY .$config['post_project_budget_per_month'];
				}
				$users_send_notification_message = str_replace('{fulltime_salary_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) { 
						$users_send_notification_message = $config['post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['post_fulltime_salary_confidential_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) { 
						$users_send_notification_message = $config['post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['post_fulltime_salary_not_sure_project_mapped_projects_professionals_categories_realtime_notification_message_plural'];
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
						if(!in_array($user_id['user_id'] ,$favorite_employers_users) || empty($favourite_employers_notification)) {
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
				if(!empty($final_array)){
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
	 * This method is used insert data in to serv_users_new_posted_projects_sent_notifications_tracking  whenever favorite employer posted a new project
	*/
	function add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_for_bidding_project_data, $config) {
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
					$users_send_notification_message = $config['favorite_employers_post_fixed_budget_realtime_notification_message_singular'];
				} else {
					$users_send_notification_message = $config['favorite_employers_post_fixed_budget_realtime_notification_message_plural'];
				}
				$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($config['post_project_budget_range_and']){
						$budget_range .= $config['post_project_budget_range_and'].'&nbsp;';
					}
					$budget_range = number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $config['post_project_budget_range_and'].'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY;
				} else {
					$budget_range = $config['post_project_budget_range_more_then'].'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY;
				}
				$users_send_notification_message = str_replace('{fixed_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $config['favorite_employers_post_fixed_budget_confidential_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['favorite_employers_post_fixed_budget_confidential_realtime_notification_message_plural'];
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $config['favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['favorite_employers_post_fixed_budget_not_sure_realtime_notification_message_plural'];
					}
				}
				if(count($categories) == 1) { 
					$users_send_notification_message = str_replace(array('{project_url_link}','{fixed_budget_project_title}', '{project_posting_date}', '{category_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
				} else {
					$users_send_notification_message = str_replace(array('{project_url_link}','{fixed_budget_project_title}', '{project_posting_date}', '{categories_list}'),array($project_url_link,htmlspecialchars($project_title), date(DATE_TIME_FORMAT, strtotime($open_for_bidding_project_data['project_posting_date'])), implode(' / ', $categories_name)),$users_send_notification_message);
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
				if(!empty($final_array)){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			} else if ($open_for_bidding_project_data['project_type'] == 'hourly') {
				if(count($categories) == 1) {
					$users_send_notification_message = $config['favorite_employers_post_hourly_rate_budget_realtime_notification_message_singular'];
				} else {
					$users_send_notification_message = $config['favorite_employers_post_hourly_rate_budget_realtime_notification_message_plural'];
				}
				$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($config['post_project_budget_range_between']){
						$budget_range .= $config['post_project_budget_range_between'].'&nbsp;';
					}
					$budget_range = number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$config['post_project_budget_per_hour'].'&nbsp;'. $config['post_project_budget_range_and'].'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'], 0, '', ' ').'&nbsp'.CURRENCY.$config['post_project_budget_per_hour'];
				} else {
					$budget_range = $config['post_project_budget_range_more_then'].'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY.$config['post_project_budget_per_hour'];
				}
				$users_send_notification_message = str_replace('{hourly_rate_budget_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $config['favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['favorite_employers_post_hourly_rate_budget_confidential_realtime_notification_message_plural'];
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $config['favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['favorite_employers_post_hourly_rate_budget_not_sure_realtime_notification_message_plural'];
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
				if(!empty($final_array)){
				$this->db->insert_batch('users_newly_posted_projects_sent_notifications_tracking', $final_array);
				$insert_flag = true;
				}
			} else {
				if(count($categories) == 1) {
					$users_send_notification_message = $config['favorite_employers_post_fulltime_salary_realtime_notification_message_singular'];
				} else {
					$users_send_notification_message = $config['favorite_employers_post_fulltime_salary_realtime_notification_message_plural'];
				}
				$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$open_for_bidding_project_data['project_id'];
				$project_title = trim($open_for_bidding_project_data['project_title']);
				$budget_range = '';
				if($open_for_bidding_project_data['max_budget'] != "All"){
					$budget_range = '';
					if($config['post_project_budget_range_between']){
						$budget_range .= $config['post_project_budget_range_between'].'&nbsp;';
					}
					$budget_range = number_format($open_for_bidding_project_data['min_budget'], 0, '', ' '). '&nbsp;'.CURRENCY .$config['post_project_budget_per_month'] .'&nbsp;'. $config['post_project_budget_range_and'].'&nbsp;'.number_format($open_for_bidding_project_data['max_budget'] 	, 0, '', ' ').'&nbsp'.CURRENCY . $config['post_project_budget_per_month'];
				} else {
					$budget_range = $config['post_project_budget_range_more_then'].'&nbsp;'. number_format($open_for_bidding_project_data['min_budget'], 0, '', ' ') ."&nbsp;".CURRENCY .$config['post_project_budget_per_month'];
				}
				$users_send_notification_message = str_replace('{fulltime_salary_range}', $budget_range, $users_send_notification_message);
				if($open_for_bidding_project_data['confidential_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $config['favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['favorite_employers_post_fulltime_salary_confidential_realtime_notification_message_plural'];
					}
				} else if($open_for_bidding_project_data['not_sure_dropdown_option_selected'] == 'Y') {
					if(count($categories) == 1) {
						$users_send_notification_message = $config['favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_singular'];
					} else {
						$users_send_notification_message = $config['favorite_employers_post_fulltime_salary_not_sure_realtime_notification_message_plural'];
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
				if(!empty($final_array)){
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
	This is used to fetch the project list from "projects_open_bidding" table	
	*/
	public function get_open_for_bidding_project_list($lim_to = '',$lim_from = '',$filter_arr = [])
	{ 
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS projects_open_bidding.*,u.account_type,u.first_name,u.last_name,u.company_name,u.is_authorized_physical_person', false);
		$this->db->from('projects_open_bidding');
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$this->db->where('(project_id LIKE'.'"%'.$val.'%")', null, false); 
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$this->db->where('(project_id LIKE'.'"%'.$val.'%"', null, false); 
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$this->db->or_where('project_id LIKE'.'"%'.$val.'%")', null, false); 
							} else {
								$this->db->or_like('project_id',$val); 
							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$this->db->or_where("(MATCH(project_title) AGAINST('".$schr."' IN BOOLEAN MODE) )");
					} else if($filter_arr['search_type'] == 'project_owner') {
						$this->db->or_where("(MATCH(u.first_name, u.last_name, u.company_name, u.profile_name) AGAINST('".$schr."' IN BOOLEAN MODE) )");
					}
				// $this->db->group_end();
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');;
				$this->db->where('(project_type IN ('.$str.'))', null, false);
			}
			 
		}
		$this->db->join('users u', 'u.user_id = projects_open_bidding.project_owner_id', 'left');
		$this->db->where('projects_open_bidding.project_expiration_date >= NOW()');
		$this->db->limit($lim_to, $lim_from);  
		$this->db->order_by('project_posting_date','desc'); 
		$project_result = $this->db->get();
		$project_data = $project_result->result_array();

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;

		return ['data' => $project_data, 'total' => $total_rec];
	}

	/**
	 * This is used to fetch the project list from "fixed_budget_projects_expired" table
	*/
	public function get_expired_project_list($lim_to = '', $lim_from = '', $filter_arr = []) {
		$project_data = [];
		$fixed_budget_projects_expired_tbl = $this->db->dbprefix."fixed_budget_projects_expired";
		$fulltime_projects_expired_tbl = $this->db->dbprefix."fulltime_projects_expired";
		$hourly_rate_based_projects_expired_tbl = $this->db->dbprefix."hourly_rate_based_projects_expired";
		$open_for_bidding_tbl = $this->db->dbprefix."projects_open_bidding";
		
			
		$user_tbl = $this->db->dbprefix."users";
		$query = "(SELECT SQL_CALC_FOUND_ROWS fbpe.project_id,fbpe.project_title,fbpe.project_description,fbpe.project_type,fbpe.min_budget,fbpe.max_budget,fbpe.confidential_dropdown_option_selected,fbpe.not_sure_dropdown_option_selected,fbpe.project_posting_date,fbpe.project_expiration_date,fbpe.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fixed_budget_projects_expired_tbl ." as fbpe";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fbpe.project_owner_id";

		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fbpe.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fbpe.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fbpe.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fbpe.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fbpe.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fbpe.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fbpe.project_type IN (".$str.") )";
				}
			}			 
		}	

		$query .= ") UNION (";
		$query .= "SELECT fpe.fulltime_project_id as project_id,fpe.fulltime_project_title as project_title,fpe.fulltime_project_description as project_description,fpe.project_type,fpe.min_salary as min_budget,fpe.max_salary as max_budget,fpe.confidential_dropdown_option_selected,fpe.not_sure_dropdown_option_selected,fpe.fulltime_project_posting_date as project_posting_date,fpe.fulltime_project_expiration_date as project_expiration_date,fpe.escrow_payment_method, ".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fulltime_projects_expired_tbl ." as fpe";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fpe.employer_id";
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fpe.fulltime_project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fpe.fulltime_project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fpe.fulltime_project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fpe.fulltime_project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fpe.fulltime_project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fpe.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fpe.project_type IN (".$str.") )";
				}
			}			 
		}	
	
		$query .= ") UNION (";
		$query .= "SELECT hrpe.project_id,hrpe.project_title,hrpe.project_description,hrpe.project_type,hrpe.min_budget,hrpe.max_budget,hrpe.confidential_dropdown_option_selected,hrpe.not_sure_dropdown_option_selected,hrpe.project_posting_date,hrpe.project_expiration_date,hrpe.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$hourly_rate_based_projects_expired_tbl ." as hrpe";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = hrpe.project_owner_id";
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(hrpe.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(hrpe.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR hrpe.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR hrpe.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(hrpe.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (hrpe.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (hrpe.project_type IN (".$str.") )";
				}
			}			 
		}	
		
		$query .= ") UNION (";
		$query .= "SELECT op.project_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$open_for_bidding_tbl ." as op";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = op.project_owner_id";
		$query .= " WHERE op.project_expiration_date < NOW() ";

	
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " AND(op.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " AND(op.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR op.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR op.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " AND (MATCH(op.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " AND (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				$query .= " AND (op.project_type IN (".$str.") )";
			}	
		} 
		
		$query .= ")";
		$query .= " ORDER BY project_expiration_date DESC ";
		if(!empty($lim_to))
		{
			$query .= "LIMIT ".$lim_from.",".$lim_to."";
		}
		$project_data = $this->db->query($query)->result_array();
		
		// pre($project_data);
		return $project_data;
	}
	
	/**
	 * This is used to fetch the awarded projects
	*/
	public function get_awarded_project_list($lim_to = '', $lim_from = '', $filter_arr = []) {
		$project_data = [];
		$fixed_budget_projects_awarded_tbl = $this->db->dbprefix."fixed_budget_projects_awarded";
		$hourly_rate_based_projects_awarded_tbl = $this->db->dbprefix."hourly_rate_based_projects_awarded";
		
		$user_tbl = $this->db->dbprefix."users";
		$query = "(SELECT SQL_CALC_FOUND_ROWS fbpa.project_id,fbpa.project_title,fbpa.project_description,fbpa.project_type,fbpa.min_budget,fbpa.max_budget,fbpa.confidential_dropdown_option_selected,fbpa.not_sure_dropdown_option_selected,fbpa.project_posting_date,fbpa.project_expiration_date,fbpa.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fixed_budget_projects_awarded_tbl ." as fbpa";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fbpa.project_owner_id";

		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fbpa.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fbpa.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fbpa.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fbpa.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fbpa.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fbpa.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fbpa.project_type IN (".$str.") )";
				}
			}
			 
		}

		$query .= ") UNION (";
		$query .= "SELECT hrpa.project_id,hrpa.project_title,hrpa.project_description,hrpa.project_type,hrpa.min_budget,hrpa.max_budget,hrpa.confidential_dropdown_option_selected,hrpa.not_sure_dropdown_option_selected,hrpa.project_posting_date,hrpa.project_expiration_date,hrpa.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$hourly_rate_based_projects_awarded_tbl ." as hrpa";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = hrpa.project_owner_id";
	
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(hrpa.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(hrpa.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR hrpa.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR hrpa.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(hrpa.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (hrpa.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (hrpa.project_type IN (".$str.") )";
				}
			}
			 
		}

		$query .= ")";

		$query .= " ORDER BY project_expiration_date DESC ";
		if(!empty($lim_to)){
			$query .= " LIMIT ".$lim_from.",".$lim_to."";
		}
		$project_data = $this->db->query($query)->result_array();
		
		return $project_data;
	}
	
	/**
	 * This is used to fetch the inprogress projects
	*/
	public function get_in_progress_project_list($lim_to = '', $lim_from = '', $filter_arr = []) {
		$project_data = [];
		$fixed_budget_projects_in_progress_tbl = $this->db->dbprefix."fixed_budget_projects_progress";
		
		$hourly_rate_based_projects_in_progress_tbl = $this->db->dbprefix."hourly_rate_based_projects_progress";
		
		
		$user_tbl = $this->db->dbprefix."users";
		$query = "(SELECT SQL_CALC_FOUND_ROWS fbpip.project_id,fbpip.project_title,fbpip.project_description,fbpip.project_type,fbpip.min_budget,fbpip.max_budget,fbpip.confidential_dropdown_option_selected,fbpip.not_sure_dropdown_option_selected,fbpip.project_posting_date,fbpip.project_expiration_date,fbpip.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fixed_budget_projects_in_progress_tbl ." as fbpip";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fbpip.project_owner_id";
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fbpip.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fbpip.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fbpip.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fbpip.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fbpip.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fbpip.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fbpip.project_type IN (".$str.") )";
				}
			}
			 
		}
			
		$query .= ") UNION (";
		$query .= "SELECT hrpip.project_id,hrpip.project_title,hrpip.project_description,hrpip.project_type,hrpip.min_budget,hrpip.max_budget,hrpip.confidential_dropdown_option_selected,hrpip.not_sure_dropdown_option_selected,hrpip.project_posting_date,hrpip.project_expiration_date,hrpip.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$hourly_rate_based_projects_in_progress_tbl ." as hrpip";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = hrpip.project_owner_id";
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(hrpip.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(hrpip.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR hrpip.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR hrpip.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(hrpip.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (hrpip.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (hrpip.project_type IN (".$str.") )";
				}
			}
			 
		}
		
		$query .= ")";
		$query .= " ORDER BY project_expiration_date DESC ";
		if(!empty($lim_to)){
			$query .= " LIMIT ".$lim_from.",".$lim_to."";
		}
		$project_data = $this->db->query($query)->result_array();
		// pre($project_data);
		return $project_data;
	}
	
	/**
	 * This is used to fetch the project list from "serv_fixed_budget_projects_cancelled and serv_fixed_budget_projects_cancelled_by_admin" table
	*/
	public function get_cancelled_project_list($lim_to = '', $lim_from = '', $filter_arr = []) {
		$project_data = [];
		$fixed_budget_canceled_tbl = $this->db->dbprefix."fixed_budget_projects_cancelled";
		$fixed_budget_canceled_by_admin_tbl = $this->db->dbprefix."fixed_budget_projects_cancelled_by_admin";
		$fulltime_projects_cancelled_tbl = $this->db->dbprefix."fulltime_projects_cancelled";
		$fulltime_projects_cancelled_by_admin_tbl = $this->db->dbprefix."fulltime_projects_cancelled_by_admin";
		$hourly_rate_based_projects_cancelled_tbl = $this->db->dbprefix."hourly_rate_based_projects_cancelled";
		$hourly_rate_based_projects_cancelled_by_admin_tbl = $this->db->dbprefix."hourly_rate_based_projects_cancelled_by_admin";
		
		
		$user_tbl = $this->db->dbprefix."users";
		$query = "(SELECT SQL_CALC_FOUND_ROWS fbpc.project_id,fbpc.project_title,fbpc.project_description,fbpc.project_type,fbpc.min_budget,fbpc.max_budget,fbpc.confidential_dropdown_option_selected,fbpc.not_sure_dropdown_option_selected,fbpc.sealed,fbpc.hidden,fbpc.project_posting_date,fbpc.project_expiration_date,fbpc.escrow_payment_method,fbpc.project_cancellation_date,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fixed_budget_canceled_tbl ." as fbpc";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fbpc.project_owner_id";

		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fbpc.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fbpc.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fbpc.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fbpc.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fbpc.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fbpc.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fbpc.project_type IN (".$str.") )";
				}
			}
			 
		}
				
		$query .= ") UNION (";
		$query .= "SELECT fbpca.project_id,fbpca.project_title,fbpca.project_description,fbpca.project_type,fbpca.min_budget,fbpca.max_budget,fbpca.confidential_dropdown_option_selected,fbpca.not_sure_dropdown_option_selected,fbpca.sealed,fbpca.hidden,fbpca.project_posting_date,fbpca.project_expiration_date,fbpca.escrow_payment_method,fbpca.project_cancellation_date, ".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fixed_budget_canceled_by_admin_tbl ." as fbpca";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fbpca.project_owner_id";	
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fbpca.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fbpca.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fbpca.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fbpca.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fbpca.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fbpca.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fbpca.project_type IN (".$str.") )";
				}
			}			 
		}
				
		$query .= ") UNION (";
		$query .= "SELECT fpc.fulltime_project_id as project_id,fpc.fulltime_project_title as project_title,fpc.fulltime_project_description as project_description,fpc.project_type,fpc.min_salary as min_budget,fpc.max_salary as max_budget,fpc.confidential_dropdown_option_selected,fpc.not_sure_dropdown_option_selected,fpc.sealed,fpc.hidden,fpc.fulltime_project_posting_date as project_posting_date,fpc.fulltime_project_expiration_date as project_expiration_date,fpc.escrow_payment_method,fpc.fulltime_project_cancellation_date as project_cancellation_date,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fulltime_projects_cancelled_tbl ." as fpc";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fpc.employer_id";
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fpc.fulltime_project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fpc.fulltime_project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fpc.fulltime_project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fpc.fulltime_project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fpc.fulltime_project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fpc.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fpc.project_type IN (".$str.") )";
				}
			}			 
		}
						
		$query .= ") UNION (";
		$query .= "SELECT fpca.fulltime_project_id as project_id,fpca.fulltime_project_title as project_title,fpca.fulltime_project_description as project_description,fpca.project_type,fpca.min_salary as min_budget,fpca.max_salary as max_budget,fpca.confidential_dropdown_option_selected,fpca.not_sure_dropdown_option_selected,fpca.sealed,fpca.hidden,fpca.fulltime_project_posting_date as project_posting_date,fpca.fulltime_project_expiration_date as project_expiration_date,fpca.escrow_payment_method,fpca.fulltime_project_cancellation_date as project_cancellation_date, ".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fulltime_projects_cancelled_by_admin_tbl." as fpca";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fpca.employer_id";

		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fpca.fulltime_project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fpca.fulltime_project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fpca.fulltime_project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fpca.fulltime_project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fpca.fulltime_project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fpca.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fpca.project_type IN (".$str.") )";
				}
			}			 
		}
				
		$query .= ") UNION (";
		$query .= "SELECT hrpc.project_id,hrpc.project_title,hrpc.project_description,hrpc.project_type,hrpc.min_budget,hrpc.max_budget,hrpc.confidential_dropdown_option_selected,hrpc.not_sure_dropdown_option_selected,hrpc.sealed,hrpc.hidden,hrpc.project_posting_date,hrpc.project_expiration_date,hrpc.escrow_payment_method,hrpc.project_cancellation_date, ".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$hourly_rate_based_projects_cancelled_tbl." as hrpc";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = hrpc.project_owner_id";

		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(hrpc.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(hrpc.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR hrpc.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR hrpc.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(hrpc.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (hrpc.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (hrpc.project_type IN (".$str.") )";
				}
			}			 
		}
		
		$query .= ") UNION (";
		$query .= "SELECT hrpca.project_id,hrpca.project_title,hrpca.project_description,hrpca.project_type,hrpca.min_budget,hrpca.max_budget,hrpca.confidential_dropdown_option_selected,hrpca.not_sure_dropdown_option_selected,hrpca.sealed,hrpca.hidden,hrpca.project_posting_date,hrpca.project_expiration_date,hrpca.escrow_payment_method,hrpca.project_cancellation_date, ".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$hourly_rate_based_projects_cancelled_by_admin_tbl." as hrpca";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = hrpca.project_owner_id";
		
		
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(hrpca.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(hrpca.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR hrpca.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR hrpca.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(hrpca.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (hrpca.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (hrpca.project_type IN (".$str.") )";
				}
			}
			 
		}
			
		$query .= " ) ";
		$query .= " ORDER BY project_cancellation_date DESC ";
		if(!empty($lim_to)){
			$query .= " LIMIT ".$lim_from.",".$lim_to."";
		}

		$project_data = $this->db->query($query)->result_array();
		
		return $project_data;
	}

	/* 
	This is used to fetch the project list from "projects_awaiting_moderation" table	
	*/
	public function get_awaiting_moderation_project_list($lim_to = '',$lim_from = '',$filter_arr = [])
	{ 
		$this->db->select('projects_awaiting_moderation.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name');
		$this->db->from('projects_awaiting_moderation');
	
		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$this->db->where('(project_id LIKE'.'"%'.$val.'%")', null, false); 
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$this->db->where('(project_id LIKE'.'"%'.$val.'%"', null, false); 
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$this->db->or_where('project_id LIKE'.'"%'.$val.'%")', null, false); 
							} else {
								$this->db->or_like('project_id',$val); 
							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$this->db->or_where("(MATCH(project_title) AGAINST('".$schr."' IN BOOLEAN MODE) )");
					} else if($filter_arr['search_type'] == 'project_owner') {
						$this->db->or_where("(MATCH(u.first_name, u.last_name, u.company_name, u.profile_name) AGAINST('".$schr."' IN BOOLEAN MODE) )");
					}
				// $this->db->group_end();
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');;
				$this->db->where('(project_type IN ('.$str.'))', null, false);
			}
			 
		}
		
		$this->db->join('users u', 'u.user_id = projects_awaiting_moderation.project_owner_id', 'left');
		if(!empty($lim_from)){
			$this->db->limit($lim_to, $lim_from);  
		}
		$this->db->order_by('project_submission_to_moderation_date','desc'); 
		$project_result = $this->db->get();
		$project_data = $project_result->result_array();
		
		// pre($project_data);
		return $project_data;
	}
	
	/**
	 * This is used to fetch the awarded projects
	*/
	public function get_completed_project_list($lim_to = '', $lim_from = '', $filter_arr = []) {
		$project_data = [];
		$fixed_budget_projects_completed_tbl = $this->db->dbprefix."fixed_budget_projects_completed";
		//$fulltime_projects_awarded_tbl = $this->db->dbprefix."fulltime_projects_awarded";
		$hourly_rate_based_projects_completed_tbl = $this->db->dbprefix."hourly_rate_based_projects_completed";
		
		$user_tbl = $this->db->dbprefix."users";
		$query = "(SELECT SQL_CALC_FOUND_ROWS fbpa.project_id,fbpa.project_title,fbpa.project_description,fbpa.project_type,fbpa.min_budget,fbpa.max_budget,fbpa.confidential_dropdown_option_selected,fbpa.not_sure_dropdown_option_selected,fbpa.project_posting_date,fbpa.project_completion_date,fbpa.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fixed_budget_projects_completed_tbl ." as fbpa";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fbpa.project_owner_id";

		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(fbpa.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(fbpa.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR fbpa.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR fbpa.project_id LIKE '%".$val."%' ";

							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(fbpa.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (fbpa.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (fbpa.project_type IN (".$str.") )";
				}
			}
			 
		}

		/* $query .= ") UNION (";
		$query .= "SELECT fpa.project_id,fpa.project_title,fpa.project_description,fpa.project_type,fpa.min_budget,fpa.max_budget,fpa.confidential_dropdown_option_selected,fpa.not_sure_dropdown_option_selected,fpa.project_posting_date,fpa.project_completion_date,fpa.escrow_payment_method, ".$user_tbl.".account_type,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$fulltime_projects_awarded_tbl ." as fpa";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = fpa.project_owner_id";
		if(!empty($search)) {
			$query .= " WHERE fpa.project_title LIKE '%".$search."%' ";
		} */
		
		$query .= ") UNION (";
		$query .= "SELECT hrpa.project_id,hrpa.project_title,hrpa.project_description,hrpa.project_type,hrpa.min_budget,hrpa.max_budget,hrpa.confidential_dropdown_option_selected,hrpa.not_sure_dropdown_option_selected,hrpa.project_posting_date,hrpa.project_completion_date,hrpa.escrow_payment_method,".$user_tbl.".account_type,".$user_tbl.".is_authorized_physical_person,".$user_tbl.".first_name,".$user_tbl.".last_name,".$user_tbl.".company_name";
		$query .= " FROM ".$hourly_rate_based_projects_completed_tbl ." as hrpa";
		$query .= " LEFT JOIN ".$user_tbl." ON ".$user_tbl.".user_id = hrpa.project_owner_id";

		if(!empty($filter_arr)) {

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if(!empty($filter_arr['searchtxt_arr'])) {
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);

					if($filter_arr['search_type'] == 'project_id') {
					
						foreach($filter_arr['searchtxt_arr'] as $k => $val) {
							if($k == 0 && count($filter_arr['searchtxt_arr']) == 1) {
								$query .= " where(hrpa.project_id LIKE '%".$val."%')";
							} else if($k == 0 && count($filter_arr['searchtxt_arr']) > 1) {
								$query .= " where(hrpa.project_id LIKE '%".$val."%'";
							} else if($k == count($filter_arr['searchtxt_arr']) - 1 ) {
								$query .= " OR hrpa.project_id LIKE '%".$val."%')";
							} else {
							    $query .= " OR hrpa.project_id LIKE '%".$val."%' ";
							}
						}
						
					} else if($filter_arr['search_type'] == 'project_title') {
							$query .= " WHERE (MATCH(hrpa.project_title) AGAINST('".$schr."' IN BOOLEAN MODE)) ";

					} else if($filter_arr['search_type'] == 'project_owner') {
						$query .= " WHERE (MATCH(".$user_tbl.".first_name, ".$user_tbl.".last_name, ".$user_tbl.".company_name, ".$user_tbl.".profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))";
					}
			}
			if(!empty($filter_arr['project_type'])) { 
				$str ='';
				foreach($filter_arr['project_type'] as $val) {
					$str .= "'".$val."'," ;
				}
				$str = rtrim($str, ',');
				if(!empty($filter_arr['searchtxt_arr'])) {
			    $query .= " AND (hrpa.project_type IN (".$str.") )";
				} else {
					$query .= " WHERE (hrpa.project_type IN (".$str.") )";
				}
			}
			 
		}
		
		$query .= ")";
		$query .= " ORDER BY project_completion_date DESC ";
		if(!empty($lim_to)){
			$query .= "LIMIT ".$lim_from.",".$lim_to."";
		}
		$project_data = $this->db->query($query)->result_array();
		
		return $project_data;
	}
	
	
	
	/**
	* This function is used to return the list of valid project attachments if any attachment is not exist in disk then this will remove the entry from table also.
	*/
	public function get_project_attachments($project_id,$user_profile_name,$project_status){
		
		$project_attachment_data = [];
		########## fetch the draft project attachments ###
		if($project_status == 'awaiting_moderation')
		{
			echo $project_id;
			
			$this->db->select('*');
			$this->db->from('awaiting_moderation_projects_attachments');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_attachment_result = $this->db->get();
			$project_attachment_data = $project_attachment_result->result_array();			
		
		} elseif($project_status == 'open_for_bidding' || $project_status == 'expired' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'completed'){
			$this->db->select('*');
			$this->db->from('projects_attachments');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_attachment_result = $this->db->get();
			$project_attachment_data = $project_attachment_result->result_array();
		} 
		
		
		
		$project_attachment_array = array();
		if(!empty($project_attachment_data)){
			include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
			
			$profile_folder     = $user_profile_name;
			$CI = & get_instance();
			$CI->load->library('ftp');
			$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$conf['ftp_username'] = FTP_USERNAME;
			$conf['ftp_password'] = FTP_PASSWORD;
			$conf['ftp_port'] = FTP_PORT;
			$conf['debug'] = TRUE;
			$CI->ftp->connect($conf);
			
			foreach($project_attachment_data as $attachment_key){
				
				if($project_status == 'awaiting_moderation'){
					$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
					$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir .$attachment_key['awaiting_moderation_project_attachment_name'];
				}else if($project_status == 'open_for_bidding'){
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
				}else if($project_status == 'awarded'){
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
				}else if($project_status == 'in_progress'){
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
				}
				else if($project_status == 'completed'){
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
				}
				else if($project_status == 'expired') {
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir .$attachment_key['project_attachment_name'];
				}
				
				$file_size = $CI->ftp->get_filesize($source_path);
				if($file_size != '-1'){
					$project_attachment['id'] = $attachment_key['id'];
					$project_attachment['project_id'] = $attachment_key['project_id'];
					if($project_status == 'awaiting_moderation'){
						$project_attachment['project_attachment_name'] = $attachment_key['awaiting_moderation_project_attachment_name'];
					}else if($project_status == 'open_for_bidding' || $project_status == 'expired' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'completed'){
						$project_attachment['project_attachment_name'] = $attachment_key['project_attachment_name'];
					}
					$project_attachment['size']  = number_format($file_size/1024). 'KB';
					$project_attachment_array[] = $project_attachment;
				}else{
					/* if($project_status == 'awaiting_moderation'){
						$this->db->delete('awaiting_moderation_projects_attachments', array('id' => $attachment_key['id'])); 
					}else if($project_status == 'open_for_bidding'){
						$this->db->delete('projects_attachments', array('id' => $attachment_key['id'])); 
						} */
				}
			}
			$CI->ftp->close();
		}
		
		return $project_attachment_array;
	}
	
	
	/**
	*	This function is used to download project attachment.
	*/
	public function download_project_attachment ($attachment_id,$profile_folder,$project_status)
    {
		$this->load->helper('download');
		include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		$user = $this->session->userdata ('user');
		######## connectivity of remote server start#########
		$CI = & get_instance();
		$CI->load->library('ftp');
		$conf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$conf['ftp_username'] = FTP_USERNAME;
		$conf['ftp_password'] = FTP_PASSWORD;
		$conf['ftp_port'] = FTP_PORT;
		$conf['debug'] = TRUE;
		$CI->ftp->connect($conf);
		######## connectivity of remote server end #######
		
		$users_ftp_dir 	= USERS_FTP_DIR; 
		$projects_ftp_dir = PROJECTS_FTP_DIR;
		$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
		$project_attachment_name = '';
		if($project_status == 'awaiting_moderation'){
			$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
			$project_attachment_detail = $this->db->get_where('awaiting_moderation_projects_attachments', array('id' => $attachment_id))->row();
			$project_attachment_name = $project_attachment_detail->awaiting_moderation_project_attachment_name;
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
			
		}else if($project_status == 'open_for_bidding' ){
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_attachment_detail = $this->db->get_where('projects_attachments', array('id' => $attachment_id))->row();
			$project_attachment_name = $project_attachment_detail->project_attachment_name;
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
		}else if($project_status == 'awarded' ){
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_attachment_detail = $this->db->get_where('projects_attachments', array('id' => $attachment_id))->row();
			$project_attachment_name = $project_attachment_detail->project_attachment_name;
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
		}else if($project_status == 'in_progress' ){
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$project_attachment_detail = $this->db->get_where('projects_attachments', array('id' => $attachment_id))->row();
			$project_attachment_name = $project_attachment_detail->project_attachment_name;
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
		}else if($project_status == 'completed' ){
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$project_attachment_detail = $this->db->get_where('projects_attachments', array('id' => $attachment_id))->row();
			$project_attachment_name = $project_attachment_detail->project_attachment_name;
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
		}else if($project_status == 'expired') {
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_attachment_detail = $this->db->get_where('projects_attachments', array('id' => $attachment_id))->row();
			$project_attachment_name = $project_attachment_detail->project_attachment_name;
			$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_attachment_detail->project_id .DIRECTORY_SEPARATOR .$project_attachment_name;
		}
		
		$file_size = $CI->ftp->get_filesize($source_path);
		if($file_size != '-1')
		{
			$destination_path =  FCPATH.TEMP_DIR.$project_attachment_name;			
			$CI->ftp->download($source_path,$destination_path, 'auto', 0777);
			$CI->ftp->close();
			
			$data = file_get_contents (FCPATH.TEMP_DIR.$project_attachment_name);// read the content of file
			unlink(TEMP_DIR.$project_attachment_name);
			force_download ($project_attachment_name,$data);
		}else{
			show_404();
		}
    }
	
	/**
	*	This function is used to return the list of tag of projects from tables .
	*/
	public function get_project_tags($project_id,$project_status){
		
		########## fetch the draft project attachments ###
		if($project_status == 'awaiting_moderation')
		{
			$this->db->select('awaiting_moderation_project_tag_name');
			$this->db->from('awaiting_moderation_projects_tags');
			$this->db->where('project_id',$project_id);
            $this->db->order_by('id',"asc");
            $project_tag_result = $this->db->get();
			$project_tag_data = $project_tag_result->result_array();
		
		} else if($project_status == 'open_for_bidding' || $project_status == 'expired' || $project_status == 'cancelled' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'completed'){
			$this->db->select('project_tag_name');
			$this->db->from('projects_tags');
			$this->db->where('project_id',$project_id);
            $this->db->order_by('id',"asc");
            $project_tag_result = $this->db->get();
			$project_tag_data = $project_tag_result->result_array();
		}
		
		return $project_tag_data;
	}
	
	/**
	*	This function is used to return the list of categories of projects from tables .
	*/
	public function get_project_categories($project_id,$project_status){
	
		########## fetch the draft project attachments ###
		if($project_status == 'awaiting_moderation')
		{			
			$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
			$this->db->from('awaiting_moderation_projects_categories_listing_tracking as category_tracking');
			$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.awaiting_moderation_project_category_id', 'left');
			$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.awaiting_moderation_project_parent_category_id', 'left');
			$this->db->where('category_tracking.project_id',$project_id);
            $this->db->order_by('category_tracking.id',"asc");
            $category_result = $this->db->get();
			$category_data = $category_result->result_array();
		
		}elseif($project_status == 'open_for_bidding' || $project_status == 'expired' || $project_status == 'cancelled' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'completed'){
			$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
			$this->db->from('projects_categories_listing_tracking as category_tracking');
			$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.project_category_id', 'left');
			$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.project_parent_category_id', 'left');
			$this->db->where('category_tracking.project_id',$project_id);
            $this->db->order_by('category_tracking.id',"asc");
            $category_result = $this->db->get();
			$category_data = $category_result->result_array();
		}
		return $category_data;
	}
	
	/**
	* This function give the information table name of project in which project is exists  .
	*/
	public function get_project_status_table_name($project_id){
		
		$project_status_table_name_array = array('project_status'=>'','table_name'=>'');
		
		$tables_name_array = array('projects_draft'=>'draft',
			'projects_awaiting_moderation'=>'awaiting_moderation',
			'projects_open_bidding'=>'open_for_bidding', 
			'fixed_budget_projects_cancelled'=>'cancelled',
			'fixed_budget_projects_cancelled_by_admin'=>'cancelled',
			'hourly_rate_based_projects_cancelled'=>'cancelled',
			'hourly_rate_based_projects_cancelled_by_admin'=>'cancelled',
			'fulltime_projects_cancelled'=>'cancelled',
			'fulltime_projects_cancelled_by_admin'=>'cancelled',
			'fulltime_projects_expired'=>'expired',
			'hourly_rate_based_projects_expired'=>'expired',
			'fixed_budget_projects_expired'=>'expired',
			'fixed_budget_projects_awarded'=> 'awarded',
			'hourly_rate_based_projects_awarded'=>'awarded',
			'fixed_budget_projects_progress'=>'in_progress',
			'hourly_rate_based_projects_progress'=>'in_progress',
			'fixed_budget_projects_completed'=>'completed',
			'hourly_rate_based_projects_completed'=>'completed'
			);
		foreach($tables_name_array as $table_name=>$project_status){
			if(substr($table_name, 0, strlen('fulltime')) === 'fulltime') {
				$count_project_exists = $this->db 
				->select ('id')
				->from ($table_name)
				->where('fulltime_project_id',$project_id)
				->get ()->num_rows ();
			} else {
				$count_project_exists = $this->db 
				->select ('id')
				->from ($table_name)
				->where('project_id',$project_id)
				->get ()->num_rows ();
			}
			
			if($count_project_exists > 0){
				$project_status_table_name_array = array('project_status'=>$project_status,'table_name'=>$table_name);
				break;
			}
		}
		return $project_status_table_name_array;	
	}
	
	/*
	This method is used to update memebership included purchase tracking, bonus included purchase tracking, upgrade purchase tracking tables project_upgrade_start_date and project_upgrade_end_date column
    also make entry into there respected refresh sequence table when admin approved the project for open for bidding from awaiting moderation status
	*/
	public function update_upgrade_purchase_tracking_with_related_refresh_sequence_tracking($project_id) {
		$refresh_sequence_tables = [
			'featured' => 'featured_projects_refresh_sequence_tracking',
			'urgent' => 'urgent_projects_refresh_sequence_tracking',
			'sealed' => 'sealed_projects_refresh_sequence_tracking'
		];
		$refresh_sequence_duration = [
			'featured' => 'project_upgrade_refresh_sequence_featured',
			'urgent' => 'project_upgrade_refresh_sequence_urgent',
			'sealed' => 'project_upgrade_refresh_sequence_sealed'
		];
		$refresh_sequence_tracking_arr = [];

		include '../application/config/'.SITE_LANGUAGE.'_post_project_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_post_project_settings_custom_config.php';

		$vat_percentage = $config['vat_percentage'];
		// Get membership included upgrade purchase tracking data
		// $membership_included_upgrade_data = $this->db->get_where('projects_awm_membership_included_upgrades_purchase_tracking', ['project_id' => $project_id ])->result_array();

		$this->db->select('mupt.*, op.hidden');
		$this->db->from('projects_awm_membership_included_upgrades_purchase_tracking mupt');
		$this->db->join('projects_open_bidding op', 'op.project_id = mupt.project_id');
		$this->db->where('mupt.project_id' , $project_id);
		$membership_included_upgrade_data = $this->db->get()->result_array();

		if(!empty($membership_included_upgrade_data)) {
			foreach($membership_included_upgrade_data as $val) {
				$time_arr = explode(':', $val['project_upgrade_availability_length']);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				$upgrades_purchase_data = $val;
				$upgrades_purchase_data['project_upgrade_start_date'] = date('Y-m-d H:i:s');
				$upgrades_purchase_data['project_upgrade_end_date'] = $upgrade_end_date;
				unset($upgrades_purchase_data['id']);
				unset($upgrades_purchase_data['hidden']);
				$project_upgrade_price = $upgrades_purchase_data['project_upgrade_purchase_value'];

				$reference_id = PROJECT_MEMBERSHIP_INCLUDED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
				$latest_released_escrow_data = $this->db->from('proj_membership_included_upgrades_purchase_tracking')->where('membership_included_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
				$digits = $config['reference_id_digits_limit'];
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
				$upgrade_purchase_tracking_data = [
					'project_id' => $upgrades_purchase_data['project_id'],
					'project_owner_id' => $upgrades_purchase_data['project_owner_id'],
					'project_upgrade_type' => $upgrades_purchase_data['project_upgrade_type'],
					'project_upgrade_purchase_date' => $upgrades_purchase_data['project_upgrade_purchase_date']
				];
				
				$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'membership_included';
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $upgrades_purchase_data['project_upgrade_purchase_value'];
				$upgrade_purchase_tracking_data['vat_percentage_value'] = $upgrades_purchase_data['vat_percentage_value'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $upgrades_purchase_data['membership_included_project_upgrade_purchase_value_excl_vat'];
				$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
				$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $upgrades_purchase_data['membership_included_project_upgrade_purchase_vat_amount_value'];


				$po_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($upgrades_purchase_data['project_owner_id']);
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

				$this->db->delete('projects_awm_membership_included_upgrades_purchase_tracking', ['id' => $val['id']]);
				############################################ End ##############################################################################

				$check_valid_arr = array_map('getInt', $time_arr); 
				$valid_time_arr = array_filter($check_valid_arr);
				if(!empty($valid_time_arr)) {
					// insert into refresh sequence tracking membership included upgrades
					$refresh_sequence_data = [
						'project_id' => $project_id,
						'project_upgrade_purchase_reference_id' => $upgrades_purchase_data['membership_included_project_upgrade_purchase_reference_id'],
						'project_upgrade_purchase_tracking_table_source' => 'membership_included',
						'project_last_refresh_time' => null
					];
					
					if($val['project_upgrade_type'] == 'featured'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $config['project_upgrade_refresh_sequence_featured'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_featured']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['project_upgrade_type'] == 'urgent'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $config['project_upgrade_refresh_sequence_urgent'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_urgent']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['project_upgrade_type'] == 'sealed'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $config['project_upgrade_refresh_sequence_sealed'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_sealed']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['hidden'] == 'N') {
						$this->db->insert($refresh_sequence_tables[$val['project_upgrade_type']], $refresh_sequence_data);
					}
				}
				
			}
		}
		// get bonus based upgrade purchased tracking data
		$this->db->select('bupt.*, op.hidden');
		$this->db->from('projects_awm_bonus_based_upgrades_purchase_tracking bupt');
		$this->db->join('projects_open_bidding op', 'op.project_id = bupt.project_id');
		$this->db->where('bupt.project_id' , $project_id);
		$bonus_based_upgrade_data = $this->db->get()->result_array();
		
		if(!empty($bonus_based_upgrade_data)) {
			foreach($bonus_based_upgrade_data as $val) {
				$time_arr = explode(':', $val['project_upgrade_availability_length']);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				$upgrades_purchase_data = $val;
				unset($upgrades_purchase_data['id']);
				unset($upgrades_purchase_data['hidden']);

				$deduction_amount = $val['project_upgrade_purchase_value'];

				$upgrades_purchase_data['project_upgrade_start_date'] = date('Y-m-d H:i:s');
				$upgrades_purchase_data['project_upgrade_end_date'] = $upgrade_end_date;

				$reference_id = PROJECT_BONUS_BASED_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
				$latest_released_escrow_data = $this->db->from('proj_bonus_based_upgrades_purchase_tracking')->where('bonus_based_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
				$digits = $config['reference_id_digits_limit'];
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
				$upgrade_purchase_tracking_data = [
					'project_id' => $upgrades_purchase_data['project_id'],
					'project_owner_id' => $upgrades_purchase_data['project_owner_id'],
					'project_upgrade_type' => $upgrades_purchase_data['project_upgrade_type'],
					'project_upgrade_purchase_date' => $upgrades_purchase_data['project_upgrade_purchase_date']
				];
				
				$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'bonus_based';
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $upgrades_purchase_data['project_upgrade_purchase_value'];
				$upgrade_purchase_tracking_data['vat_percentage_value'] = $upgrades_purchase_data['vat_percentage_value'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $upgrades_purchase_data['bonus_based_project_upgrade_purchase_value_excl_vat'];
				$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
				$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $upgrades_purchase_data['bonus_based_project_upgrade_vat_amount_value'];


				$po_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($upgrades_purchase_data['project_owner_id']);
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

				$this->db->delete('projects_awm_bonus_based_upgrades_purchase_tracking', ['id' => $val['id']]);
				############################################ End ##############################################################################
				
				if($val['hidden'] == 'N') {
					// insert into refresh sequence tracking membership included upgrades
					$refresh_sequence_data = [
						'project_id' => $project_id,
						'project_upgrade_purchase_reference_id' => $upgrades_purchase_data['bonus_based_project_upgrade_purchase_reference_id'],
						'project_upgrade_purchase_tracking_table_source' => 'bonus_money',
						'project_last_refresh_time' => null
						//'project_next_refresh_time' => date('Y-m-d H:i:s')
					];
					if($val['project_upgrade_type'] == 'featured'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $config['project_upgrade_refresh_sequence_featured'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_featured']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['project_upgrade_type'] == 'urgent'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $config['project_upgrade_refresh_sequence_urgent'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_urgent']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['project_upgrade_type'] == 'sealed'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $config['project_upgrade_refresh_sequence_sealed'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_sealed']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					$refresh_sequence_tracking_arr[$val['project_upgrade_type']] = [$project_id => $refresh_sequence_data];
				}
				
			}
		}
		// get real payment upgrade data
		$this->db->select('rupt.*, op.hidden');
		$this->db->from('projects_awm_real_money_upgrades_purchase_tracking rupt');
		$this->db->join('projects_open_bidding op', 'op.project_id = rupt.project_id');
		$this->db->where('rupt.project_id' , $project_id);
		$upgrade_purchase_data = $this->db->get()->result_array();
		if(!empty($upgrade_purchase_data)) {
			foreach($upgrade_purchase_data as $val) {
				$time_arr = explode(':', $val['project_upgrade_availability_length']);
				$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));

				$upgrades_purchase_data = $val;
				unset($upgrades_purchase_data['id']);
				unset($upgrades_purchase_data['hidden']);
				
				$upgrades_purchase_data['project_upgrade_start_date'] = date('Y-m-d H:i:s');
				$upgrades_purchase_data['project_upgrade_end_date'] = $upgrade_end_date;

				$deduction_amount = $val['project_upgrade_purchase_value'];

				$reference_id = PROJECT_REAL_MONEY_UPGRADE_PURCHASE_REFERENCE_ID_PREFIX;
				$latest_released_escrow_data = $this->db->from('proj_real_money_upgrades_purchase_tracking')->where('real_money_project_upgrade_purchase_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
				$digits = $config['reference_id_digits_limit'];
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
				$upgrade_purchase_tracking_data = [
					'project_id' => $upgrades_purchase_data['project_id'],
					'project_owner_id' => $upgrades_purchase_data['project_owner_id'],
					'project_upgrade_type' => $upgrades_purchase_data['project_upgrade_type'],
					'project_upgrade_purchase_date' => $upgrades_purchase_data['project_upgrade_purchase_date']
				];
				
				$upgrade_purchase_tracking_data['project_upgrade_purchase_reference_id'] = $upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_source'] = 'real_money';
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value'] = $upgrades_purchase_data['project_upgrade_purchase_value'];
				$upgrade_purchase_tracking_data['vat_percentage_value'] = $upgrades_purchase_data['vat_percentage_value'];
				$upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'] = $upgrades_purchase_data['real_money_project_upgrade_purchase_value_excl_vat'];
				$project_upgrade_purchase_value_excl_vat = $upgrade_purchase_tracking_data['project_upgrade_purchase_value_excl_vat'];
				$upgrade_purchase_tracking_data['project_upgrade_vat_amount_value'] = $upgrades_purchase_data['real_money_project_upgrade_purchase_vat_amount_value'];


				$po_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($upgrades_purchase_data['project_owner_id']);
				$po_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($upgrades_purchase_data['project_owner_id']);
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
						'referee_user_id' => $upgrades_purchase_data['project_owner_id']
					];
					$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

					$daily_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earnings_date' => date('Y-m-d H:i:s'),
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

					$weekly_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earnings_week_start_date' => $week_start_date,
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

					$monthly_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'referral_earnings_month' => date('Y-m-d'),
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

					$total_earnings = [
						'user_id' => $po_lvl1_referal_data['user_id'],
						'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
					];
					$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
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
						'referee_user_id' => $upgrades_purchase_data['project_owner_id']
					];
					$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

					$daily_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earnings_date' => date('Y-m-d H:i:s'),
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

					$weekly_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earnings_week_start_date' => $week_start_date,
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

					$monthly_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'referral_earnings_month' => date('Y-m-d'),
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);
					
					$total_earnings = [
						'user_id' => $po_lvl2_referal_data['user_id'],
						'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
					];
					$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
				}

				$upgrade_purchase_tracking_data['project_upgrade_purchase_net_value'] = $project_upgrade_purchase_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value);
				$this->db->insert('projects_upgrades_purchases_tracking', $upgrade_purchase_tracking_data);

				$this->db->delete('projects_awm_real_money_upgrades_purchase_tracking', ['id' => $val['id']]);

				############################################ End ##############################################################################
				
				if($val['hidden'] == 'N') { 
					// insert into refresh sequence tracking membership included upgrades
					$refresh_sequence_data = [
						'project_id' => $project_id,
						'project_upgrade_purchase_reference_id' => $upgrades_purchase_data['real_money_project_upgrade_purchase_reference_id'],
						'project_upgrade_purchase_tracking_table_source' => 'real_money',
						'project_last_refresh_time' => null
					];
					if($val['project_upgrade_type'] == 'featured'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_featured'] = $config['project_upgrade_refresh_sequence_featured'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_featured']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['project_upgrade_type'] == 'urgent'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_urgent'] = $config['project_upgrade_refresh_sequence_urgent'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_urgent']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					if($val['project_upgrade_type'] == 'sealed'){
						$refresh_sequence_data['project_upgrade_refresh_sequence_sealed'] = $config['project_upgrade_refresh_sequence_sealed'];
						$time_arr = explode(':', $config['project_upgrade_refresh_sequence_sealed']);
						$refresh_sequence_data['project_next_refresh_time'] = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
					}
					$refresh_sequence_tracking_arr[$val['project_upgrade_type']] = [$project_id => $refresh_sequence_data];
				}
				
			}
		}
		
		foreach($refresh_sequence_tracking_arr as $key => $val) {
			foreach($val as $k => $v) {
				if(!empty($config[$refresh_sequence_duration[$key]])) {
					$time_arr = explode(':', $config[$refresh_sequence_duration[$key]]);
					$check_valid_arr = array_map('getInt', $time_arr); 
					$valid_time_arr = array_filter($check_valid_arr);
					if(!empty($valid_time_arr) && array_key_exists($key, $refresh_sequence_tables)) {
						$this->db->insert($refresh_sequence_tables[$key], $v);
					}
				}
			}	
		}
	}

	// This method is used to get referral user information like his current membership information and based on membership which how much percentage he will get from lvl1 referral  
	function get_referral_user_details_from_lvl1_user_id($user_id) {
		$this->db->select('ud.user_id, mp.id, mp.lvl1_percentage');
		$this->db->from('users_referrals_tracking lvl1');
		$this->db->join('users_details ud', 'ud.user_id = lvl1.lvl1_referrer_id');
		$this->db->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id');
		$this->db->where('lvl1.user_id', $user_id);
		return $this->db->get()->row_array();
	}
	
	// This method is used to get referral user information like his current membership information and based on membership which how much percentage he will get from lvl2 referal
	function get_referral_user_details_from_lvl2_user_id($user_id) {
		$this->db->select('ud.user_id, mp.id, mp.lvl2_percentage');
		$this->db->from('users_referrals_tracking lvl2');
		$this->db->join('users_details ud', 'ud.user_id = lvl2.lvl2_referrer_id');
		$this->db->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id');
		$this->db->where('lvl2.user_id', $user_id);
		return $this->db->get()->row_array();
	}

	// This method is used to save data ino users referral aggregated daily earning history table
	function save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($data) {
		$daily_earnings = $this->db->select('id')->from('users_referrals_daily_earnings_history_tracking')->where('user_id', $data['user_id'])->where('DATE(referral_earnings_date) = DATE(NOW())')->get()->row_array();
		if(empty($daily_earnings)) {
			$this->db->insert('users_referrals_daily_earnings_history_tracking', $data);
		} else {
			$this->db->where('id', $daily_earnings['id']);
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->update('users_referrals_daily_earnings_history_tracking');
		}		
	}

	// This method is used to save data ino users referral aggregated weekly earning history table
	function save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($data) {
		$weekly_earnings = $this->db->select('id')->from('users_referrals_weekly_earnings_history_tracking')->where('user_id', $data['user_id'])->where('referral_earnings_week_start_date', $data['referral_earnings_week_start_date'])->get()->row_array();
		if(empty($weekly_earnings)) {
			$this->db->insert('users_referrals_weekly_earnings_history_tracking', $data);
		} else {
			$this->db->where('id', $weekly_earnings['id']);
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->update('users_referrals_weekly_earnings_history_tracking');
		}		
	}

	// This method is used to save data ino users referral aggregated monthly earning history table
	function save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($data) {
		$monthly_earnings = $this->db->select('id')->from('users_referrals_monthly_earnings_history_tracking')->where('user_id', $data['user_id'])->where('YEAR(referral_earnings_month) = YEAR(NOW()) AND MONTH(referral_earnings_month)=MONTH(NOW())')->get()->row_array();
		if(empty($monthly_earnings)) {
			$this->db->insert('users_referrals_monthly_earnings_history_tracking', $data);
		} else {
			$this->db->where('id', $monthly_earnings['id']);
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->update('users_referrals_monthly_earnings_history_tracking');
		}		
	}

	// This method is used to save data ino users referral lifetime total earnings tracking table
	function save_data_into_users_referrals_lifetime_total_earnings_tracking($data) {
		$total_earnings = $this->db->select('id')->from('users_referrals_lifetime_total_earnings_tracking')->where('user_id', $data['user_id'])->get()->row_array();
		if(empty($total_earnings)) {
			$this->db->insert('users_referrals_lifetime_total_earnings_tracking', $data);
		} else {
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->where('id', $total_earnings['id']);
			$this->db->update('users_referrals_lifetime_total_earnings_tracking');
		}		
	}

	/**
	 * Calculate project expiration date with combination of custom config configuration and upgrade selection done through user
	 */
	public function get_project_correct_expiration_date($data, $config) {
				
		$time_arr = explode(':', $config['standard_project_availability']);
		$project_expire_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
		if($data['featured'] == 'Y') {
			$featured_time_arr = explode(':', $config['project_upgrade_availability_featured']);
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$featured_time_arr[0].' hour +'.(int)$featured_time_arr[1].' minutes +'.(int)$featured_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		if($data['urgent'] == 'Y') {
			$urgent_time_arr = explode(":", $config['project_upgrade_availability_urgent']);
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$urgent_time_arr[0].' hour +'.(int)$urgent_time_arr[1].' minutes +'.(int)$urgent_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		if($data['sealed'] == 'Y') {
			$sealed_time_arr = explode(":", $config['project_upgrade_availability_sealed']);
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$sealed_time_arr[0].' hour +'.(int)$sealed_time_arr[1].' minutes +'.(int)$sealed_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		if($data['hidden'] == 'Y') {
			$hidden_time_arr = explode(":", $config['project_upgrade_availability_hidden']);
			$compare_date = date('Y-m-d H:i:s', strtotime('+'.(int)$hidden_time_arr[0].' hour +'.(int)$hidden_time_arr[1].' minutes +'.(int)$hidden_time_arr[2].' seconds'));
			if(strtotime($compare_date) > strtotime($project_expire_date)) {
				$project_expire_date = $compare_date; 
			}
		}
		return $project_expire_date;
	}
	
	
	/* 
	This is used to count the number of hires in for fulltime project
	*/
	public function get_hires_count_fulltime_project($project_id)
	{ 
		$count_hires = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_hired_employees_tracking')->count_all_results();
		return $count_hires;
	}
	
	public function get_project_status_type($project_id){
		$project_status_table_name_array = array('project_status'=>'','table_name'=>'');
		if(!empty($project_id)){
			$tables_name_array['projects_open_bidding'] = array('project_status'=>'open_for_bidding','project_type'=>'fixed_hourly_fulltime');
			
			$tables_name_array['fixed_budget_projects_awarded'] = array('project_status'=>'awarded','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_awarded'] = array('project_status'=>'awarded','project_type'=>'hourly');
			
			$tables_name_array['fixed_budget_projects_progress'] = array('project_status'=>'in_progress','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_progress'] = array('project_status'=>'in_progress','project_type'=>'hourly');
			
			
			
			
			$tables_name_array['fixed_budget_projects_cancelled'] = array('project_status'=>'cancelled','project_type'=>'fixed');
			$tables_name_array['fixed_budget_projects_cancelled_by_admin'] = array('project_status'=>'cancelled','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_cancelled'] = array('project_status'=>'cancelled','project_type'=>'hourly');
			$tables_name_array['hourly_rate_based_projects_cancelled_by_admin'] = array('project_status'=>'cancelled','project_type'=>'hourly');
			$tables_name_array['fulltime_projects_cancelled'] = array('project_status'=>'cancelled','project_type'=>'fulltime');
			$tables_name_array['fulltime_projects_cancelled_by_admin'] = array('project_status'=>'cancelled','project_type'=>'fulltime');
			
			$tables_name_array['fulltime_projects_expired'] = array('project_status'=>'expired','project_type'=>'fulltime');
			$tables_name_array['hourly_rate_based_projects_expired'] = array('project_status'=>'expired','project_type'=>'hourly');
			$tables_name_array['fixed_budget_projects_expired'] = array('project_status'=>'expired','project_type'=>'fixed');
			$tables_name_array['fixed_budget_projects_completed'] = array('project_status'=>'completed','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_completed'] = array('project_status'=>'completed','project_type'=>'hourly');
			
			$tables_name_array['fixed_budget_projects_incomplete'] = array('project_status'=>'incomplete','project_type'=>'fixed');
			$tables_name_array['hourly_rate_based_projects_incomplete'] = array('project_status'=>'incomplete','project_type'=>'hourly');
			
			foreach($tables_name_array as $table_name=>$project_status){
				if(substr($table_name, 0, strlen('fulltime')) === 'fulltime') {
					$count_project_exists = $this->db->where(['fulltime_project_id LIKE' => $project_id])->from($table_name)->count_all_results();
				} else {
					$count_project_exists = $this->db->where(['project_id LIKE' => $project_id])->from($table_name)->count_all_results();
				}
				
				if($count_project_exists > 0){
					$project_status_table_name_array = array('project_status'=>$project_status['project_status'],'project_type'=>$project_status['project_type'],'table_name'=>$table_name);
					break;
				}
			}
		}
		return $project_status_table_name_array;
	}
	
	
	// This method is used to store data into charged_service_fees table
	function insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data) {
		
		if($charged_service_fees_data['project_type'] != 'fulltime') {
			$po = $released_escrow_data['project_owner_id'];
			$sp = $released_escrow_data['winner_id'];
		} else {
			$po = $released_escrow_data['employer_id'];
			$sp = $released_escrow_data['employee_id'];
		}

		$service_fee_value_excl_vat = $charged_service_fees_data['charged_service_fee_value_excl_vat'];
		
		$po_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($po);
		$po_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($po);
		$sp_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($sp);
		$sp_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($sp);
		$po_lv1_percentage_value = 0;
		$po_lv2_percentage_value = 0;
		$sp_lv1_percentage_value = 0;
		$sp_lv2_percentage_value = 0;
		
		$ts = strtotime(date('Y-m-d'));
		$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
		$week_start_date = date('Y-m-d', $start);

		if(!empty($po_lvl1_referal_data)) {
			$charged_service_fees_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
			$charged_service_fees_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
			$charged_service_fees_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
			$po_lv1_percentage_value = ($service_fee_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
			$charged_service_fees_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;

			$referral_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $po_lv1_percentage_value,
				'referral_earning_lvl_source' => 'lvl1',
				'referee_user_id' => $po
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];
			
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];

			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

			$total_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}
		
		if(!empty($po_lvl2_referal_data)) {
			$charged_service_fees_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
			$charged_service_fees_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
			$charged_service_fees_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
			$po_lv2_percentage_value = ($service_fee_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
			$charged_service_fees_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;

			$referral_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $po_lv2_percentage_value,
				'referral_earning_lvl_source' => 'lvl2',
				'referee_user_id' => $po
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			//pre($daily_earnings);
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);
			
			$total_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}

		if(!empty($sp_lvl1_referal_data)) {
			$charged_service_fees_data['winner_lvl1_referrer_id'] = $sp_lvl1_referal_data['user_id'];
			$charged_service_fees_data['winner_lvl1_referrer_membership_id'] = $sp_lvl1_referal_data['id'];
			$charged_service_fees_data['winner_lvl1_referrer_considered_percentage'] = $sp_lvl1_referal_data['lvl1_percentage'];
			$sp_lv1_percentage_value = ($service_fee_value_excl_vat * $sp_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
			$charged_service_fees_data['winner_lvl1_referrer_affiliate_generated_income'] = $sp_lv1_percentage_value;

			$referral_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $sp_lv1_percentage_value,
				'referral_earning_lvl_source' => 'lvl1',
				'referee_user_id' => $sp
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			
			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

			$total_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}

		if(!empty($sp_lvl2_referal_data)) {
			$charged_service_fees_data['winner_lvl2_referrer_id'] = $sp_lvl2_referal_data['user_id'];
			$charged_service_fees_data['winner_lvl2_referrer_membership_id'] = $sp_lvl2_referal_data['id'];
			$charged_service_fees_data['winner_lvl2_referrer_considered_percentage'] = $sp_lvl2_referal_data['lvl2_percentage'];
			$sp_lv2_percentage_value = ($service_fee_value_excl_vat * $sp_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
			$charged_service_fees_data['winner_lvl2_referrer_affiliate_generated_income'] = $sp_lv2_percentage_value;

			$referral_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $sp_lv2_percentage_value,
				'referral_earning_lvl_source' => 'lvl2',
				'referee_user_id' => $sp
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

			$total_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}

		$charged_service_fees_data['charged_service_fee_net_value'] = $service_fee_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value + $sp_lv1_percentage_value + $sp_lv2_percentage_value);

		if(!empty($charged_service_fees_data)) {
			$this->db->insert('projects_charged_service_fees_tracking', $charged_service_fees_data);
		}
	}
	
	
	
	
	/*
	This functions is used to sum the paid escrow for service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_released_escrow_amounts_project_sp($project_type,$conditions){

		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		} else if($project_type == 'fulltime') {
			$released_escrow_table = 'fulltime_projects_released_escrows';
		}
		$check_released_escrow_exists = $this->db->where($conditions)->from($released_escrow_table)->count_all_results();
		$sum_released_escrow_result['sum_escrow_amount_value'] = 0;
		if($check_released_escrow_exists > 0){
			$this->db->select('SUM(released_escrow_payment_amount) as sum_escrow_amount_value');
			$this->db->from($released_escrow_table);
			$this->db->where($conditions);
			$sum_released_escrow_result = $this->db->get();
			$sum_released_escrow_result = $sum_released_escrow_result->row_array();
		}
		return $sum_released_escrow_result['sum_escrow_amount_value'];
		
	}
}
