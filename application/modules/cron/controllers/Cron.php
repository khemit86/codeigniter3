<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH.'third_party/users_chat/connect_cassandra_db.php';
class Cron extends MX_Controller {
	
    /**
     * Description: Constructor of controller
     * Paremete:NULL
     */
		private $conn;
		public function __construct() {
			parent::__construct();
			$this->load->model ('post_project/Post_project_model');
			$this->load->model('projects/Projects_model');
			$this->load->model('user/User_model');
			$this->load->model('bidding/Bidding_model');
			$this->load->model('projects_disputes/Projects_disputes_model');
			$this->load->library('ftp');
			$this->load->library ('email');
			$this->conn = new connect_cassandra_db();
		
		}
		
		// show 404 page if authorization key not found or not match with key defined in server custom config file
		public function check_authroization_key() {
			if(empty($_GET['authorization_key']) || (!empty($_GET['authorization_key']) && $_GET['authorization_key'] != NODE_URL_AUTHORIZATION_KEY) ) {
				show_custom_404_page();
				return true;
			}
		}

	
	/**
     * Description: this used for removed the unverified user and send email notifications
     */

	public function cron_remove_unverified_users_from_users_pending_verification_table()
	{
		if($this->check_authroization_key()) {
			return;
		}

		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		$unverified_user_records =  $this->db->select ('user_id,email,first_name,last_name,company_name,account_type,gender,is_authorized_physical_person')->where ('account_expiration_date <= ', date('Y-m-d H:i:s'))->get ('users_new_registrations_pending_verification')->result_array ();

		foreach ($unverified_user_records as $user_record)
		{ 
			
			
			$name =  (($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_record['is_authorized_physical_person'] == 'Y')) ? $user_record['first_name'] . ' ' . $user_record['last_name'] : $user_record['company_name'];
			
			
			
			
			$email =  $user_record['email'];
			$to = $email;
			$cron_removed_project_info .= "\r\n".'email : '.$email.' | account type : '.$user_record['account_type'];
			/* echo "<pre>";
			print_r($user_record); */
			if($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE)
			{
				
				// Define email variables for male user
				if($user_record['gender'] == 'M'){
					$cc = $this->config->item('email_cc_unverified_user_account_delete_personal_male');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_personal_male');
					$from = $this->config->item('email_from_unverified_user_account_delete_personal_male');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_personal_male');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_personal_male');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_personal_male');
					$message = $this->config->item('email_message_unverified_user_account_delete_personal_male');
					$message = str_replace(array('{name}'),array($name),$message);
				
				}else{
					// Define email variables for female user
					$cc = $this->config->item('email_cc_unverified_user_account_delete_personal_female');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_personal_female');
					$from = $this->config->item('email_from_unverified_user_account_delete_personal_female');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_personal_female');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_personal_female');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_personal_female');
					$message = $this->config->item('email_message_unverified_user_account_delete_personal_female');
					$message = str_replace(array('{name}'),array($name),$message);
				
				}
			
			
			}else{
				if($user_record['is_authorized_physical_person'] == 'Y'){
					// Define email variables for male app
					if($user_record['gender'] == 'M'){
						$cc = $this->config->item('email_cc_unverified_user_account_delete_company_app_male');
						$bcc = $this->config->item('email_bcc_unverified_user_account_delete_company_app_male');
						$from = $this->config->item('email_from_unverified_user_account_delete_company_app_male');
						$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_company_app_male');
						$from_name = $this->config->item('email_from_name_unverified_user_account_delete_company_app_male');
						$subject = $this->config->item('email_subject_unverified_user_account_delete_company_app_male');
						$message = $this->config->item('email_message_unverified_user_account_delete_company_app_male');
						$message = str_replace(array('{name}'),array($name),$message);
					
					}else{
						// Define email variables for female app
						$cc = $this->config->item('email_cc_unverified_user_account_delete_company_app_female');
						$bcc = $this->config->item('email_bcc_unverified_user_account_delete_company_app_female');
						$from = $this->config->item('email_from_unverified_user_account_delete_company_app_female');
						$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_company_app_female');
						$from_name = $this->config->item('email_from_name_unverified_user_account_delete_company_app_female');
						$subject = $this->config->item('email_subject_unverified_user_account_delete_company_app_female');
						$message = $this->config->item('email_message_unverified_user_account_delete_company_app_female');
						$message = str_replace(array('{name}'),array($name),$message);
					
					}
				}else{
					// Define email variables for company user
					$cc = $this->config->item('email_cc_unverified_user_account_delete_company');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_company');
					$from = $this->config->item('email_from_unverified_user_account_delete_company');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_company');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_company');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_company');
					$message = $this->config->item('email_message_unverified_user_account_delete_company');
					$message = str_replace(array('{company_name}'),array($name),$message);
				}	
			}
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
			$this->email->initialize($config);
			$this->email->from ($from,$from_name);
			$this->email->to ($to);
			if($cc){
				$this->email->cc ($cc);
			}
			if($bcc){
				$this->email->bcc ($bcc);
			}
			$this->email->subject ($subject);
			$this->email->reply_to($reply_to);
			$this->email->set_mailtype ('html');
			$this->email->set_newline("\r\n");
			$this->email->message ($message);
			$user_id = $user_record['user_id'];
			$this->db->delete('users_new_registrations_pending_verification', array ('user_id' => $user_id));
			if($this->email->send ())
			{
			}else{
				 //echo $this->email->print_debugger();
			}
		}
		echo 'cronRemoveUnverifiedUsersFromUsersPendingVerificationTable executed successfully (running every 6 hours). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Removed accounts: '.count($unverified_user_records).'. '.$cron_removed_project_info;
	}
	
	/**
     * Description: this used for send reminder to unverified user with new validation code
     */
	public function cron_send_reminder_verification_code_to_not_verified_users_accounts()
	{
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		$unverified_user_send_reminder_count = 0;
		$unverified_user_send_reminder_records =  $this->db->select ('user_id,email,first_name,last_name,company_name,account_type,gender,automatic_verification_code_generate_time, 	next_automatic_verification_code_generate_time,account_registration_date,account_expiration_date,is_authorized_physical_person')->where ('next_automatic_verification_code_generate_time <= ', date('Y-m-d H:i:s'))->get ('users_new_registrations_pending_verification')->result_array ();
		
		
		$account_expiration_time_interval = $this->config->item('signup_unverified_user_remove_set_interval');
		$account_expiration_time_interval = $account_expiration_time_interval/60;
		/* if($account_expiration_time_interval > 1){
			$account_expiration_time_interval = $account_expiration_time_interval." hodiny";
		}else{
			$account_expiration_time_interval = $account_expiration_time_interval." hodina";
		} */
		
		if($account_expiration_time_interval == 1){
			$hour = $this->config->item('1_hour');
		}else if($account_expiration_time_interval >=2 && $account_expiration_time_interval <= 4){
			$hour = $this->config->item('2_4_hours');
		}else if($account_expiration_time_interval > 4){
			$hour = $this->config->item('more_than_or_equal_5_hours');
		}
		$account_expiration_time_interval = $account_expiration_time_interval." ".$hour;
		
		
		foreach ($unverified_user_send_reminder_records as $user_record)
		{ 
			
			if(strtotime($user_record['account_expiration_date']) > time()){
				//echo $user_record['account_expiration_date'];
				$user_id = $user_record['user_id'];
				$email =  $user_record['email'];
				$code = random_string('alnum', 12);
				$current_date = date('Y-m-d H:i:s');
				############ calculation for next request reminder time for unverfied user start ########
				
				$automatic_verification_code_generate_time = time(); 
				$signup_automatic_send_reminder_to_unverified_user_set_interval_seconds = $this->config->item('signup_automatic_send_reminder_to_unverified_user_set_interval')*60;
				
				$next_automatic_verification_code_generate_time = $automatic_verification_code_generate_time + $signup_automatic_send_reminder_to_unverified_user_set_interval_seconds;
				
				############ calculation for next request reminder time for unverfied user end ########
				
				############ account verification code expire time start ############
				$current_verification_code_expiration_date = ($automatic_verification_code_generate_time + ($this->config->item('signup_verified_code_expire_set_interval')*60));
				############ account verification code expire time end ############
				
				
				
				$reminder_data = array ('verification_code' => $code, 'automatic_verification_code_generate_time' => date('Y-m-d H:i:s',$automatic_verification_code_generate_time), 'next_automatic_verification_code_generate_time' => date('Y-m-d H:i:s',$next_automatic_verification_code_generate_time), 'current_verification_code_expiration_date' => date('Y-m-d H:i:s',$current_verification_code_expiration_date));
				
				$this->db->where ('user_id', $user_id);
				$this->db->update ('users_new_registrations_pending_verification', $reminder_data);
				$signup_verification_url = HTTP_HOST_CLI . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=". Cryptor::doEncrypt($user_id)."&".$this->config->item('signup_page_code_parameter')."=".Cryptor::doEncrypt($code);
				
				
				//$name =  $user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $user_record['first_name'] . ' ' . $user_record['last_name'] : $user_record['company_name'];
				
				$name = (($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_record['is_authorized_physical_person'] =='Y')) ? $user_record['first_name'] . ' ' . $user_record['last_name'] : $user_record['company_name'];

				
				$signup_verification_code_expiration_set_interval = $this->config->item('signup_verification_code_expiration_set_interval')*60;
				$new_code_expire_time  = $signup_verification_code_expiration_set_interval + strtotime($current_date);
				//$hours_left = ($new_code_expire_time/3600);
				$account_expiration_time_left = convert_seconds_to_time($user_record['account_expiration_date']);
				$account_registration_date = date(DATE_FORMAT,strtotime($user_record['account_registration_date']));
				$account_registration_time =  date(TIME_FORMAT,strtotime($user_record['account_registration_date']));
				
				
				$to = $email;
				$cron_removed_project_info .= "\r\n".'email : '.$email.' | account type : '.$user_record['account_type'];
				if($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					
					if($user_record['gender'] == 'M')
					{
						// Define email variables for male user
						$cc = $this->config->item('email_cc_unverified_account_reminder_verification_code_personal_male');
						$bcc = $this->config->item('email_bcc_unverified_account_reminder_verification_code_personal_male');
						$from = $this->config->item('email_from_unverified_account_reminder_verification_code_personal_male');
						$reply_to = $this->config->item('email_reply_to_unverified_account_reminder_verification_code_personal_male');
						$from_name = $this->config->item('email_from_name_unverified_account_reminder_verification_code_personal_male');
						$subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_personal_male');
						$message = $this->config->item('email_message_unverified_account_reminder_verification_code_personal_male');
						$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$signup_verification_url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
					
					
					}else{
						
						// Define email variables for female user
						$cc = $this->config->item('email_cc_unverified_account_reminder_verification_code_personal_female');
						$bcc = $this->config->item('email_bcc_unverified_account_reminder_verification_code_personal_female');
						$from = $this->config->item('email_from_unverified_account_reminder_verification_code_personal_female');
						$reply_to = $this->config->item('email_reply_to_unverified_account_reminder_verification_code_personal_female');
						$from_name = $this->config->item('email_from_name_unverified_account_reminder_verification_code_personal_female');
						$subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_personal_female');
						$message = $this->config->item('email_message_unverified_account_reminder_verification_code_personal_female');
						$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$signup_verification_url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
					
					}
				}else{
				
					if($user_record['is_authorized_physical_person'] == 'Y'){
						if($user_record['gender'] == 'M')
						{
							// Define email variables for male authorized physical person
							$cc = $this->config->item('email_cc_unverified_account_reminder_verification_code_company_app_male');
							$bcc = $this->config->item('email_bcc_unverified_account_reminder_verification_code_company_app_male');
							$from = $this->config->item('email_from_unverified_account_reminder_verification_code_company_app_male');
							$reply_to = $this->config->item('email_reply_to_unverified_account_reminder_verification_code_company_app_male');
							$from_name = $this->config->item('email_from_name_unverified_account_reminder_verification_code_company_app_male');
							$subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_company_app_male');
							$message = $this->config->item('email_message_unverified_account_reminder_verification_code_company_app_male');
							$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$signup_verification_url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
						
						
						}else{
							
							// Define email variables for male authorized physical person
							$cc = $this->config->item('email_cc_unverified_account_reminder_verification_code_company_app_female');
							$bcc = $this->config->item('email_bcc_unverified_account_reminder_verification_code_company_app_female');
							$from = $this->config->item('email_from_unverified_account_reminder_verification_code_company_app_female');
							$reply_to = $this->config->item('email_reply_to_unverified_account_reminder_verification_code_company_app_female');
							$from_name = $this->config->item('email_from_name_unverified_account_reminder_verification_code_company_app_female');
							$subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_company_app_female');
							$message = $this->config->item('email_message_unverified_account_reminder_verification_code_company_app_female');
							$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$signup_verification_url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
						
						}
					
					}else{
				
						// Define email variables for company user
						$cc = $this->config->item('email_cc_unverified_account_reminder_verification_code_company');
						$bcc = $this->config->item('email_bcc_unverified_account_reminder_verification_code_company');
						$from = $this->config->item('email_from_unverified_account_reminder_verification_code_company');
						$reply_to = $this->config->item('email_reply_to_unverified_account_reminder_verification_code_company');
						$from_name = $this->config->item('email_from_name_unverified_account_reminder_verification_code_company');
						$subject = $this->config->item('email_subject_unverified_account_reminder_verification_code_company');
						$message = $this->config->item('email_message_unverified_account_reminder_verification_code_company');
						$message = str_replace(array('{company_name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$signup_verification_url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
					}
				}
				
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
				$this->email->initialize($config);
				$this->email->from ($from,$from_name);
				$this->email->to ($to);
				if($cc){
					$this->email->cc ($cc);
				}
				if($bcc){
					$this->email->bcc ($bcc);
				}
				$this->email->subject ($subject);
				$this->email->reply_to($reply_to);
				$this->email->set_mailtype ('html');
				$this->email->set_newline("\r\n");
				$this->email->message ($message);
				if($this->email->send ())
				{
				}else{
					 //echo $this->email->print_debugger();
				}
				$unverified_user_send_reminder_count++;
			}
			
		}
		echo 'cronSendReminderVerificationCodeToNotVerifiedAccountsUsers executed successfully (running every 3 mins). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s'). '.'.$unverified_user_send_reminder_count.' reminder emails sent.';
	}
	/*
	 * this function checks project expiration time comes or not and if yes then remove entry from db and also associated folder
	*/
	public function cron_to_remove_temporary_projects_expired_entries_from_disk_and_from_related_tables() {
		if($this->check_authroization_key()) {
			return;
		}

		$this->db->select('tp.*, u.profile_name');
		$this->db->from('temp_projects tp');
		$this->db->join('users u','u.user_id = tp.temp_project_owner_id', 'left');
		$this->db->where('(tp.project_expiration_date <= NOW())');
		$temp_projects = $this->db->get()->result_array();
		$cron_start_date = date('d.m.Y H:i:s');
		$removed_project_info = '';
		$removed_entry_count = 0;
		if(!empty($temp_projects)) {
			
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			
			$temp_dir 	= TEMP_DIR; 
			$logged_off_users_temporary_projects_attachments_dir = substr(LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR,1);
			
			try {
				$this->ftp->connect($config); 
				
				foreach($temp_projects as $key => $temp_project) {
					// Remove temp project attachement
					$this->db->delete('temp_projects_attachments', ['temp_project_id' => $temp_project['temp_project_id']]);
					// Remove temp project category listing
					$this->db->delete('temp_projects_categories_listing_tracking', ['temp_project_id' => $temp_project['temp_project_id']]);
					// Remove temp project tags
					$this->db->delete('temp_projects_tags', ['temp_project_id' => $temp_project['temp_project_id']]);
					// Remove temp projects
					$this->db->delete('temp_projects', ['temp_project_id' => $temp_project['temp_project_id']]);
					
					if($this->db->affected_rows() == 1) {
						$removed_entry_count++;
						$removed_project_info .= "\r\n".' project_id : '.$temp_project['temp_project_id'].' | project_owner_id : '.$temp_project['temp_project_owner_id'];
					}
					if(!empty($temp_project['profile_name'])) {
						// Remove all folders and sub-folder which are associate with temp projects
						$dir = USERS_FTP_DIR.$temp_project['profile_name'].PROJECTS_FTP_DIR.PROJECT_TEMPORARY_DIR.$temp_project['temp_project_id'].DIRECTORY_SEPARATOR;
						
						$arr = $this->ftp->list_files($dir);
						if(!empty($arr)) {
							if(!empty($dir)) {
								if(strpos($dir, USERS_FTP_DIR) !== false) {
									$dir = $dir;
								} else {
									$dir = USERS_FTP_DIR.$dir;
								}
								$flag = $this->ftp->delete_dir($dir);
							}
						}
					}
					
					if(!empty($this->ftp->check_ftp_directory_exist(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project['temp_project_id']))) {
						$this->ftp->delete_dir(DIRECTORY_SEPARATOR .$temp_dir.$logged_off_users_temporary_projects_attachments_dir.$temp_project['temp_project_id']);
					}
					
				}
				$this->ftp->close();
			} catch(Exception $e) {
				echo $e->getMessage();
			}			
		}
		echo 'cronToRemoveTemporaryProjectsExpiredEntriesFromDiskAndFromRelatedTables executed successfully (running every 8 hrs). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Entries removed: '.$removed_entry_count.'. '.$removed_project_info;		
	}


	// check and compare entries from temp categories / tags / attachments tables with temp_projects table
	public function cron_check_and_remove_orphan_DB_entries_from_related_temp_projects_tables() {
		if($this->check_authroization_key()) {
			return;
		}

		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		// remove temp project attachement which are orphan
		$this->db->select('tpa.*, pd.id as fid');
		$this->db->from('temp_projects_attachments tpa');
		$this->db->join('temp_projects pd', 'pd.temp_project_id = tpa.temp_project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$temp_attachments = $this->db->get()->result_array();
		if(!empty($temp_attachments)) {
			foreach($temp_attachments as $attachement) {
				if(empty($attachement['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$attachement['temp_project_id'].' | temp_projects_attachments';
					$this->db->delete('temp_projects_attachments', ['id' => $attachement['id']]);
				}
			}
		}
		// remove temp project tags which are orphan
		$this->db->select('tpt.*, pd.id as fid');
		$this->db->from('temp_projects_tags tpt');
		$this->db->join('temp_projects pd', 'pd.temp_project_id = tpt.temp_project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$temp_tags = $this->db->get()->result_array();
		
		if(!empty($temp_tags)) {
			
			foreach($temp_tags as $tag) {
				if(empty($tag['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$tag['temp_project_id'].' | temp_projects_tags';
					$this->db->delete('temp_projects_tags', ['id' => $tag['id']]);
				}
			}
		}
		// remove temp project categories listing tracking which are orphan
		$this->db->select('tpct.*, pd.id as fid');
		$this->db->from('temp_projects_categories_listing_tracking tpct');
		$this->db->join('temp_projects pd', 'pd.temp_project_id = tpct.temp_project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$temp_categories = $this->db->get()->result_array();
		if(!empty($temp_categories)) {
			foreach($temp_categories as $category) {
				if(empty($category['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$category['temp_project_id'].' | temp_projects_categories_listing_tracking';
					$this->db->delete('temp_projects_categories_listing_tracking', ['id' => $category['id']]);
				}
			}
		}
		echo 'cronCheckAndRemoveOrphanDBEntriesFromRelatedTempProjectsTables executed successfully (running everyday at 23.00 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. '.(!empty($cron_removed_project_info) ? 'Removed entries are as follows: '.$cron_removed_project_info : 'No entries removed.');
	}

	// check and compare entries from draft categories / tags / attachments tables with projects_draft table
	public function cron_check_and_remove_orphan_DB_entries_from_related_draft_projects_tables() {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		// remove draft project attachement which are orphan
		$this->db->select('dpa.*, pd.id as fid');
		$this->db->from('draft_projects_attachments dpa');
		$this->db->join('projects_draft pd', 'pd.project_id = dpa.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$draft_attachments = $this->db->get()->result_array();
		if(!empty($draft_attachments)) {
			foreach($draft_attachments as $attachement) {
				if(empty($attachement['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$attachement['project_id'].' | draft_projects_attachments';
					$this->db->delete('draft_projects_attachments', ['id' => $attachement['id']]);
				}
			}
		}
		// remove draft project tags which are orphan
		$this->db->select('dpt.*, pd.id as fid');
		$this->db->from('draft_projects_tags dpt');
		$this->db->join('projects_draft pd', 'pd.project_id = dpt.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$draft_tags = $this->db->get()->result_array();
		
		if(!empty($draft_tags)) {
			foreach($draft_tags as $tag) {
				if(empty($tag['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$tag['project_id'].' | draft_projects_tags';
					$this->db->delete('draft_projects_tags', ['id' => $tag['id']]);
				}
			}
		}
		// remove draft project categories listing tracking which are orphan
		$this->db->select('dpct.*, pd.id as fid');
		$this->db->from('draft_projects_categories_listing_tracking dpct');
		$this->db->join('projects_draft pd', 'pd.project_id = dpct.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$draft_categories = $this->db->get()->result_array();
		if(!empty($draft_categories)) {
			foreach($draft_categories as $category) {
				if(empty($tag['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$category['project_id'].' | draft_projects_categories_listing_tracking';
					$this->db->delete('draft_projects_categories_listing_tracking', ['id' => $category['id']]);
				}
			}
		}
		echo 'cronCheckAndRemoveOrphanDBEntriesFromRelatedDraftProjectsTables executed successfully (running everyday at 23.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. '.(!empty($cron_removed_project_info) ? 'Removed entries are as follows: '.$cron_removed_project_info : 'No entries removed.');
		return;
	}
	// check and compare entries from awaiting-moderation categories / tags / attachments tables with projects_awaiting_moderation
	public function cron_check_and_remove_orphan_DB_entries_from_related_awaiting_moderation_projects_tables () {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		// remove awaiting_moderation_projects_attachments which are orphan
		$this->db->select('ama.*, pd.id as fid');
		$this->db->from('awaiting_moderation_projects_attachments ama');
		$this->db->join('projects_awaiting_moderation pd', 'pd.project_id = ama.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$awaiting_attachments = $this->db->get()->result_array();
		if(!empty($awaiting_attachments)) {
			foreach($awaiting_attachments as $attachement) {
				if(empty($attachement['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$attachement['project_id'].' | awaiting_moderation_projects_attachments';
					$this->db->delete('awaiting_moderation_projects_attachments', ['id' => $attachement['id']]);
				}
			}
		}
		// remove awaiting_moderation_projects_tags which are orphan
		$this->db->select('amat.*, pd.id as fid');
		$this->db->from('awaiting_moderation_projects_tags amat');
		$this->db->join('projects_awaiting_moderation pd', 'pd.project_id = amat.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$awaiting_tags = $this->db->get()->result_array();
		if(!empty($awaiting_tags)) {
			foreach($awaiting_tags as $tag) {
				if(empty($tag['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$tag['project_id'].' | awaiting_moderation_projects_tags';
					$this->db->delete('awaiting_moderation_projects_tags', ['id' => $tag['id']]);
				}
			}
		}
		// remove awaiting_moderation_projects_categories_listing_tracking which are orphan
		$this->db->select('amac.*, pd.id as fid');
		$this->db->from('awaiting_moderation_projects_categories_listing_tracking amac');
		$this->db->join('projects_awaiting_moderation pd', 'pd.project_id = amac.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$awaiting_categories = $this->db->get()->result_array();
		if(!empty($awaiting_categories)) {
			foreach($awaiting_categories as $category) {
				if(empty($category['fid'])) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$category['project_id'].' | awaiting_moderation_projects_categories_listing_tracking';
					$this->db->delete('awaiting_moderation_projects_categories_listing_tracking', ['id' => $category['id']]);
				}
			}
		}
		echo 'cronCheckAndRemoveOrphanDBEntriesFromRelatedAwaitingModerationProjectsTables executed successfully (running everyday at 01.00 CET). Cron job started at '.$cron_start_date .' and ended at '.date('d.m.Y H:i:s').'. '.(!empty($cron_removed_project_info) ? 'Removed entries are as follows: '.$cron_removed_project_info : 'No entries removed.');
	}
	
	// Remove the orphan categories/tags/project attachments for all project statuses regarding the  tables projects_attachments/projects_categories_listing_tracking/projects_tags which are orphan(removed from database only)
	public function cron_check_and_remove_orphan_DB_entries_of_not_existent_projects_in_all_related_projects_tables_for_all_projects_statuses() {
		if($this->check_authroization_key()) {
			return;
		}
		
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		$this->db->select('a.*, pd.id as fid');
		$this->db->from('projects_attachments a');
		$this->db->join('projects_open_bidding pd', 'pd.project_id = a.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$attachments = $this->db->get()->result_array();
		if(!empty($attachments)) {
				$tmp = [];
				// check orphan entries availability in expired project table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_expired', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				
				$tmp = [];
				// check orphan entries availability in fulltime project expired table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('fulltime_projects_expired', ['fulltime_project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project cancelled table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_cancelled', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project cancelled by admin table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_cancelled_by_admin', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project expired table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_expired', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in fixed project progress table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_progress', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project progress table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_progress', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				
				$tmp = [];
				// check orphan entries availability in fixed project awarded table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project awarded table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				
				
				$attachments = $tmp;
				$tmp = [];
				// check orphan entries availability in fixed project completed table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_completed', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				
				$tmp = [];
				// check orphan entries availability in hourly project completed table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_completed', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;

				$tmp = [];
				// check orphan entries availability in fixed budget projects in-complete table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_incomplete', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;

				$tmp = [];
				// check orphan entries availability in hourly based projects in-complete table
				foreach($attachments as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_incomplete', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$attachments = $tmp;

				foreach($attachments as $attachement) {
					$cron_removed_project_info .= "\r\n".'project_id : '.$attachement['project_id'].' | project_attachment_name: '.$attachement['project_attachment_name'].' | projects_attachments';
					$this->db->delete('projects_attachments', ['id' => $attachement['id']]);
				}

		}
		// remove projects_tags which are orphan
		$this->db->select('t.*, pd.id as fid');
		$this->db->from('projects_tags t');
		$this->db->join('projects_open_bidding pd', 'pd.project_id = t.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$tags = $this->db->get()->result_array();
		if(!empty($tags)) {
			$tmp = [];
			// check orphan entries availability in expired project table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('fixed_budget_projects_expired', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in fulltime project expired table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('fulltime_projects_expired', ['fulltime_project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in hourly project cancelled table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_cancelled', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in hourly project cancelled by admin table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_cancelled_by_admin', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in hourly project expired table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_expired', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in fixed project awarded table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in hourly project awarded table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in fixed progress project progress table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('fixed_budget_projects_progress', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			$tmp = [];
			// check orphan entries availability in hourly progress project progress table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_progress', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			
			$tmp = [];
			// check orphan entries availability in fixed  project completed table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('fixed_budget_projects_completed', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;
			foreach($tags as $tag) {
				
				$cron_removed_project_info .= "\r\n".'project_id: '.$tag['project_id'].' | project_tag_name: '.$tag['project_tag_name'].' | projects_tags';
				$this->db->delete('projects_tags', ['id' => $tag['id']]);
			}
		
			$tmp = [];
			// check orphan entries availability in fixed  project completed table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_completed_tracking', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;


			$tmp = [];
			// check orphan entries availability in fixed budget projects in-complete table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('fixed_budget_projects_incomplete', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;

			$tmp = [];
			// check orphan entries availability in hourly based projects in-complete table
			foreach($tags as $key => $val) {
				$row = $this->db->get_where('hourly_rate_based_projects_incomplete', ['project_id' => $val['project_id']])->row_array();
				if(empty($row)) {
					array_push($tmp, $val);
				}
			}
			$tags = $tmp;

			foreach($tags as $tag) {
				
				$cron_removed_project_info .= "\r\n".'project_id: '.$tag['project_id'].' | project_tag_name: '.$tag['project_tag_name'].' | projects_tags';
				$this->db->delete('projects_tags', ['id' => $tag['id']]);
			}
		}
		// remove projects_categories_listing_tracking which are orphan
		$this->db->select('c.*, pd.id as fid');
		$this->db->from('projects_categories_listing_tracking c');
		$this->db->join('projects_open_bidding pd', 'pd.project_id = c.project_id', 'left');
		$this->db->where('pd.id IS NULL');
		$categories = $this->db->get()->result_array();
		if(!empty($categories)) {
				$tmp = [];
				// check orphan entries availability in expired project table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_expired', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in fulltime project expired table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('fulltime_projects_expired', ['fulltime_project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project cancelled table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_cancelled', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project cancelled by admin table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_cancelled_by_admin', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly project expired table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_expired', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in fixed awarded project
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly awarded project
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in fixed progress  project
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_progress', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				$tmp = [];
				// check orphan entries availability in hourly progress  project
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_progress', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				
				$tmp = [];
				// check orphan entries availability in fixed completed  project
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_completed', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;

				$tmp = [];
				// check orphan entries availability in fixed completed  project
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_completed', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;

				$tmp = [];
				// check orphan entries availability in fixed budget projects in-complete table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('fixed_budget_projects_incomplete', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;

				$tmp = [];
				// check orphan entries availability in hourly based projects in-complete table
				foreach($categories as $key => $val) {
					$row = $this->db->get_where('hourly_rate_based_projects_incomplete', ['project_id' => $val['project_id']])->row_array();
					if(empty($row)) {
						array_push($tmp, $val);
					}
				}
				$categories = $tmp;
				
				foreach($categories as $category) {
				
					$category_row = $this->db->get_where('categories_projects', ['id' => $val['project_category_id']])->row_array();
					
					$parent_category_row = $this->db->get_where('categories_projects', ['id' => $val['project_parent_category_id']])->row_array();
					
					if($category['project_parent_category_id'] == '0'){
						$cron_removed_project_info .= "\r\n".'project_category: '.$category_row['name'].' | project_id : '.$category['project_id'].' | projects_categories_listing_tracking';
					}else{
						$cron_removed_project_info .= "\r\n".'project_id : '.$category['project_id'].' | project_category: '.$category_row['name'].' | project_parent_category: '.$parent_category_row['name'].' | projects_categories_listing_tracking';
					}
					$this->db->delete('projects_categories_listing_tracking', ['id' => $category['id']]);
				}
			
			}
			
		echo 'cronCheckAndRemoveOrphanDBEntriesOfNotExistentProjectsInAllRelatedProjectsTablesForAllProjectsStatuses executed ran successfully (runnning everyday at 01.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. '.(!empty($cron_removed_project_info) ? 'Removed entries are as follows: '.$cron_removed_project_info : 'No orphan entries found.');
	}
	
	/* cron to remove orphan entries for open bidding projects related table - previous text*/
	// check and move expired entries from open bidding table to project expired table
	public function cron_check_and_move_expired_entry_from_open_bidding_projects_table() {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		$this->db->select('ob.*, u.profile_name');
		$this->db->from('projects_open_bidding ob');
		$this->db->join('users u', 'u.user_id = ob.project_owner_id');
		$this->db->where('ob.project_expiration_date <= NOW()');
		$expired_projects = $this->db->get()->result_array();
		if(!empty($expired_projects)) {
			foreach($expired_projects as $project) {
				$expired_data = [
					'project_id' => $project['project_id'],
					'project_owner_id' => $project['project_owner_id'],
					'project_posting_date' => $project['project_posting_date'],
					'project_expiration_date' => $project['project_expiration_date'],
					'project_title' => $project['project_title'],
					'project_description' => $project['project_description'],
					'locality_id' => $project['locality_id'],
					'county_id' => $project['county_id'],
					'postal_code_id' => $project['postal_code_id'],
					'min_budget' => $project['min_budget'],
					'max_budget' => $project['max_budget'],
					'confidential_dropdown_option_selected' => $project['confidential_dropdown_option_selected'],
					'not_sure_dropdown_option_selected' => $project['not_sure_dropdown_option_selected'],
					'escrow_payment_method' => $project['escrow_payment_method'],
					'offline_payment_method' => $project['offline_payment_method'],
					'views' => $project['views'],
					'sealed' => $project['sealed'],
					'hidden' => $project['hidden'],
					'revisions' => $project['revisions']
				];

				// insert data into project_expired table
				if($project['project_type'] == 'fixed'){
					$check_expired_project_count = $this->db->where(['project_id' => $project['project_id']])->from('fixed_budget_projects_expired')->count_all_results();
					if($check_expired_project_count == 0) {
						$this->db->insert('fixed_budget_projects_expired', $expired_data);
					}
				} else if($project['project_type'] == 'hourly'){
					$check_expired_project_count = $this->db->where(['project_id' => $project['project_id']])->from('hourly_rate_based_projects_expired')->count_all_results();
					if($check_expired_project_count == 0) {
						$this->db->insert('hourly_rate_based_projects_expired', $expired_data);
					}
				} else if($project['project_type'] == 'fulltime'){
				
					
					$expired_data['fulltime_project_id'] = $project['project_id'];
					$expired_data['employer_id'] = $project['project_owner_id'];
					$expired_data['fulltime_project_posting_date'] = $project['project_posting_date'];
					$expired_data['fulltime_project_expiration_date'] = $project['project_expiration_date'];
					$expired_data['fulltime_project_title'] = $project['project_title'];
					$expired_data['fulltime_project_description'] = $project['project_description'];
					$expired_data['min_salary'] = $project['min_budget'];
					$expired_data['max_salary'] = $project['max_budget'];

					unset($expired_data['project_id']);
					unset($expired_data['project_owner_id']);
					unset($expired_data['project_posting_date']);
					unset($expired_data['project_expiration_date']);
					unset($expired_data['project_title']);
					unset($expired_data['project_description']);
					unset($expired_data['min_budget']);
					unset($expired_data['max_budget']);
					
					$check_expired_project_count = $this->db->where(['fulltime_project_id' => $project['project_id']])->from('fulltime_projects_expired')->count_all_results();
					if($check_expired_project_count == 0) {
						$this->db->insert('fulltime_projects_expired', $expired_data);
					}
				}
				// remove entry from featured project upload cover picture tracking
				$this->db->delete('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project['project_id']));
				// Set next refresh time to null for cancelled project
				$this->db->limit(1);
				$this->db->order_by('id', 'desc');
				$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project['project_id']]);
				$this->db->limit(1);
				$this->db->order_by('id', 'desc');
				$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project['project_id']]);
				$this->db->limit(1);
				$this->db->order_by('id', 'desc');
				$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project['project_id']]);
				$this->db->limit(1);
				$this->db->order_by('id', 'desc');
				$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project['project_id']]);
				// remove entry from project latest refresh sequence tracking table
				$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project['project_id']));
				// remov entry from open bidding table
				$this->db->delete('projects_open_bidding', ['id' => $project['id']]);
				// trigger socket event to update expired project section on user dashboard
				$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateExpiredProjectOnUserDashboard/".$project['project_owner_id'].'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
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

				$cron_removed_project_info .= "\r\n".'project_id : '.$project['project_id'].' | project_owner_id : '.$project['project_owner_id'];
				################ genreate the log activity #################
				if($project['project_type'] != 'fulltime'){
					$project_expired_user_activity_log_displayed_message_sent_to_po = $this->config->item('project_expired_user_activity_log_displayed_message_sent_to_po');
				}else{
				
					$project_expired_user_activity_log_displayed_message_sent_to_po = $this->config->item('fulltime_project_expired_user_activity_log_displayed_message_sent_to_po');
				}
				$project_url = VPATH.($this->config->item('project_detail_page_url').'?id='.$project['project_id']);
				$project_expired_user_activity_log_displayed_message_sent_to_po = str_replace(array("{project_title}", "{project_expiration_date}", "{project_url_link}"),array(htmlspecialchars($project['project_title'], ENT_QUOTES), date('d.m.Y H:i:s', strtotime($project['project_expiration_date'])), $project_url),$project_expired_user_activity_log_displayed_message_sent_to_po);
				user_display_log($project_expired_user_activity_log_displayed_message_sent_to_po, $project['project_owner_id']);

				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				try {
					$this->ftp->connect($config); 
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					$profile_folder = $project['profile_name'];
					
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].DIRECTORY_SEPARATOR);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$project_owner_attachments_dir);
					
					
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir, 0777);// create awaiting_moderation directory in projects folder
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'] , 0777); // create the directory by using  project id
					//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project['project_id'].$project_owner_attachments_dir;
					
					$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$project_owner_attachments_dir;
					

					
					$bid_attachments = $this->db->where ('project_id', $project['project_id'])->get ('projects_active_bids_users_attachments_tracking')->result_array ();
					
					if(!empty($bid_attachments)){
						foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
							//	
							$bid_attachment_directory_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'];
							if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$users_bid_attachments_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
								
								
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
								
								//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
								
								$source_bid_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
								
								$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
								if($file_size != '-1')
								{
									$destination_bid_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
									
								}else{
								$this->db->delete('projects_active_bids_users_attachments_tracking', ['id' => $bid_attachment_value['id']]);
								}
							}
						}
					
					}
							
					
					
					$source_list = $this->ftp->list_files($source_path);
					if(!empty($source_list)) {
						foreach($source_list as $path) {
							$arr = explode('/', $path);
							$file_size = $this->ftp->get_filesize($path);
							if($file_size != '-1') {
								$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project['project_id'].$project_owner_attachments_dir.end($arr);
								$this->ftp->move($path, $destination_path);
							}
						}
					}
					
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project['project_id']))) {
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project['project_id']);
					}
				} catch(Exception $e) {

				}
				
			}
		}
		echo 'cronCheckAndMoveExpiredEntryFromOpenBiddingProjectsTable executed successfully (running every minute). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Moved projects: '.count($expired_projects).'. '.$cron_removed_project_info;
	}
	/*
	 * cron to remove orphan folder from disk for all users which are related to temp projects
	*/
	public function cron_check_and_remove_orphan_entries_from_disk_for_temporary_projects() {		
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$projects_temp_dir = PROJECT_TEMPORARY_DIR;
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_remove_project_info = '';
			$removed_folder_count = 0 ;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$user_projects = $this->ftp->list_files($user.$projects_ftp_dir.$projects_temp_dir);
				foreach($user_projects as $project) {
					if(!empty($project)) {
						$project .= DIRECTORY_SEPARATOR;
						if(!empty($this->ftp->check_ftp_directory_exist($project))) {
							$arr = explode('/', $project);
							if(ctype_digit($arr[5])) {
								$result = $this->db->get_where('temp_projects', ['temp_project_id' => $arr[5]])->row_array();
								if(empty($result)) {
									$cron_remove_project_info .= "\r\n".'project_id : '.$arr[5].' | project_owner_profile : '.end($users);
									$removed_folder_count++;
									$this->ftp->delete_dir($project);
								}
							} else {
								$this->ftp->delete_dir($project);
							}
						} else {
							if(!empty($this->ftp->check_ftp_directory_exist($project))) { 
								$this->ftp->delete_dir($project);
							}
						}
					}
				}
			}

			$logged_off_users_temporary_projects_attachments_dir = substr(LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR,1);
			$logged_off_users_temporary_projects_list = $this->ftp->list_files($temp_dir.$logged_off_users_temporary_projects_attachments_dir);
			
			foreach($logged_off_users_temporary_projects_list as $project) {
				if(!empty($this->ftp->check_ftp_directory_exist($project))) {
					$arr = explode('/', $project);
					if(ctype_digit(end($arr))) {
						$result = $this->db->get_where('temp_projects', ['temp_project_id' => end($arr)])->row_array();
						if(empty($result)) {
							$cron_remove_project_info .= "\r\n".'project_id : '.end($arr);
							$removed_folder_count++;
							$this->ftp->delete_dir(DIRECTORY_SEPARATOR.$project);
						}
					} 
				} 
			}
			echo 'cronCheckAndRemoveOrphanEntriesFromDiskForTemporaryProjects executed successfully (running everyday at 02.00 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Folders removed: '.$removed_folder_count.'. '.$cron_remove_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		
	}
	
	
	// checking disk on user folder for orphan projects(draft) and removed
	public function cron_check_and_remove_orphan_entries_from_disk_for_draft_projects() {
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_draft_dir = PROJECT_DRAFT_DIR;
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$user_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_draft_dir);
				foreach($user_projects as $project) {
					if(!empty($project)) {
						$project .= DIRECTORY_SEPARATOR;
						// $temp_projects = $this->ftp->list_files($project);
						if(!empty($this->ftp->check_ftp_directory_exist($project))) {
							$arr = explode('/', $project);
							if(ctype_digit($arr[5])) {
								$result = $this->db->get_where('projects_draft', ['project_id' => $arr[5]])->row_array();
								if(empty($result)) {
									$cron_removed_project_info .= "\r\n".'project_id : '.$arr[5].' | project_owner_profile : '.end($users);
									$removed_folder_count++;
									$this->ftp->delete_dir($project);
								}
							} else {
								$this->ftp->delete_dir($project);
							}
							
						} else {
							if(!empty($this->ftp->check_ftp_directory_exist($project))) { 
								$this->ftp->delete_dir($project);
							}
						}
					}
				}
			}
			echo 'cronCheckAndRemoveOrphanEntriesFromDiskForDraftProjects executed successfully (running every day at 02.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Folders removed: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	// checking disk on user folder for orphan projects(awaiting moderation) and removed
	public function cron_check_and_remove_orphan_entries_from_disk_for_awaiting_moderation_projects() {
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$user_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_awaiting_moderation_dir);
				foreach($user_projects as $project) {
					if(!empty($project)) {
						$project .= DIRECTORY_SEPARATOR;
						// $temp_projects = $this->ftp->list_files($project);
						if(!empty($this->ftp->check_ftp_directory_exist($project))) {
							$arr = explode('/', $project);
							if(ctype_digit($arr[5])) {
								$result = $this->db->get_where('projects_awaiting_moderation', ['project_id' => $arr[5]])->row_array();
								if(empty($result)) {
									$cron_removed_project_info .= "\r\n".'project_id : '.$arr[5].' | project_owner_profile : '.end($users);
									$removed_folder_count++;
									$this->ftp->delete_dir($project);
								}
							} else {
								$this->ftp->delete_dir($project);
							}
							
						} else {
							if(!empty($this->ftp->check_ftp_directory_exist($project))) {
								$this->ftp->delete_dir($project);
							}
						}
					}
				}
			}
			echo 'cronCheckAndRemoveOrphanEntriesFromDiskForAwaitingModerationProjects executed successfully (running every day at 03.00 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Folders removed: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	// check and remove orphan projects entries from disk from users opend bidding folder by checking it's existance with db
	public function cron_check_and_remove_orphan_entries_from_disk_on_users_open_for_bidding_folders_for_not_existent_projects_in_DB() {
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$user_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_open_for_bidding_dir);
				foreach($user_projects as $project) {
					if(!empty($project)) {
						$project .= DIRECTORY_SEPARATOR;
						if(!empty($this->ftp->check_ftp_directory_exist($project))) {
							$arr = explode('/', $project);
							if(ctype_digit($arr[5])) {
								$result = $this->db->get_where('projects_open_bidding', ['project_id' => $arr[5]])->row_array();
								
								if(empty($result)) {
									$cron_removed_project_info .= "\r\n".'project_id : '.$arr[5].' | project_owner_profile : '.end($users);
									$removed_folder_count++;
									$this->ftp->delete_dir($project);
								}
							} else {
							
								$this->ftp->delete_dir($project);
							}
							
						} else {
							if(!empty($this->ftp->check_ftp_directory_exist($project))) {
								$this->ftp->delete_dir($project);
							}
						}
					}
				}
			}
			echo 'cronCheckAndRemoveOrphanEntriesFromDiskOnUsersOpenForBiddingFoldersForNotExistentProjectsInDB executed successfully (running every day at 03.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Folders removed: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	// checking disk on user folder for orphan projects(expired) and removed
	public function cron_check_and_remove_orphan_entries_from_disk_for_expired_projects() {
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$user_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_expired_dir);
				foreach($user_projects as $project) {
					if(!empty($project)) {
						$project .= DIRECTORY_SEPARATOR;
						if(!empty($this->ftp->check_ftp_directory_exist($project))) {
							$arr = explode('/', $project);
							if(ctype_digit($arr[5])) {
							
								$check_fixed_expired_project_data = $this->db->where(['project_id' => $arr[5]])->from('fixed_budget_projects_expired')->count_all_results();
								$check_hourly_expired_project_data = $this->db->where(['project_id' => $arr[5]])->from('hourly_rate_based_projects_expired')->count_all_results();
								$check_fulltime_expired_project_data = $this->db->where(['fulltime_project_id' => $arr[5]])->from('fulltime_projects_expired')->count_all_results();
								if($check_fixed_expired_project_data == 0 && $check_hourly_expired_project_data== 0 && $check_fulltime_expired_project_data == 0) {
									$cron_removed_project_info .= "\r\n".'project_id : '.$arr[5].' | project_owner_profile : '.end($users);
									$removed_folder_count++;
									$this->ftp->delete_dir($project);
								}
							} else {
								$this->ftp->delete_dir($project);
							}
							
						} else {
							if(!empty($this->ftp->check_ftp_directory_exist($project))) {
								$this->ftp->delete_dir($project);
							}
						}
					}
				}
			}
			echo 'cronCheckAndRemoveOrphanEntriesFromDiskForExpiredProjects executed successfully (running every day at 04.00 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Folders removed: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	// This cron is used to check orphan projects attachemnts on disk for all projects statuses and remove it from disk
	public function cron_check_and_remove_orphan_project_attachments_entries_from_disk_for_all_projects_statuses() {
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;

			$project_temp_dir = PROJECT_TEMPORARY_DIR;
			$project_draft_dir = PROJECT_DRAFT_DIR;
			$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_award_dir = PROJECT_AWARDED_DIR;
			$project_inprogress_dir = PROJECT_IN_PROGRESS_DIR;
			$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;

			$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;


			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;

			$temp_dir = DIRECTORY_SEPARATOR.TEMP_DIR;
			$temp_dir = substr($temp_dir, 0 , -1);
			$logged_off_user_temp_projects_attachments_dir = LOGGED_OFF_USERS_TEMPORARY_PROJECTS_ATTACHMENTS_DIR;

			$logged_off_projects = $this->ftp->list_files($temp_dir.$logged_off_user_temp_projects_attachments_dir);
			foreach($logged_off_projects as $project) {
				if(!empty($this->ftp->check_ftp_directory_exist($project))) { 
					$project_id = end(explode('/', $project));
					$projects_attachments = $this->ftp->list_files($project);
					
					if(!empty($projects_attachments)) {
						foreach($projects_attachments as $attachment_val) {
							$name = end(explode('/', $attachment_val));
							
							if($name != 'index.php') {
								$check_attachment_cnt = $this->db->from('temp_projects_attachments')->where('temp_project_attachment_name', $name)->count_all_results();
								if($check_attachment_cnt == 0) {
									$cron_removed_project_info .= "\r\n".'project_id : '.$project_id.' | project_attachment_name : '.$name;
									$removed_folder_count++;
									$this->ftp->delete_file($attachment_val);
								}
							}
						}
					}
				}
				

			}

			foreach($user_folder_list as $user) {
				$users = explode('/', $user);

				// Temporary project status logged in
				$user_temp_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_temp_dir);
				foreach($user_temp_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('temp_projects_attachments')->where('temp_project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Draft project status
				$user_draft_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_draft_dir);
				foreach($user_draft_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('draft_projects_attachments')->where('draft_project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Awaiting moderation project status
				$user_awaiting_moderation_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_awaiting_moderation_dir);
				foreach($user_awaiting_moderation_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('awaiting_moderation_projects_attachments')->where('awaiting_moderation_project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Open bidding project status
				$user_open_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_open_for_bidding_dir);
				foreach($user_open_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('projects_attachments')->where('project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Awarded project status
				$user_awarded_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_award_dir);
				foreach($user_awarded_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('projects_attachments')->where('project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Inprogress project status
				$user_progress_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_inprogress_dir);
				foreach($user_progress_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('projects_attachments')->where('project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Incomplete project status
				$user_incomplete_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_incomplete_dir);
				foreach($user_incomplete_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('projects_attachments')->where('project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Completed project status
				$user_completed_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_completed_dir);
				foreach($user_completed_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('projects_attachments')->where('project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

				// Expired project status
				$user_expired_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_expired_dir);
				foreach($user_expired_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$projects_attachments = $this->ftp->list_files($project.$project_owner_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($projects_attachments)) {
							foreach($projects_attachments as $attachment_val) {
								$name = end(explode('/', $attachment_val));
								if($name != 'index.php') {
									$check_attachment_cnt = $this->db->from('projects_attachments')->where('project_attachment_name', $name)->count_all_results();
									if($check_attachment_cnt == 0) {
										$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | project_attachment_name : '.$name;
										$removed_folder_count++;
										$this->ftp->delete_file($attachment_val);
									}
								}
							}
						}
					}
				}

			}
			echo 'cronCheckAndRemoveOrphanProjectAttachmentsEntriesFromDiskForAllProjectsStatuses executed successfully (running every day at 03.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. removed attachments: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	// This cron is used to check orphan bid attachments of projects on disk for all projects statues and remove it from disk
	public function cron_check_and_remove_orphan_bids_attachments_entries_from_disk_for_all_projects_statuses() {
		if($this->check_authroization_key()) {
			return;
		}
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;

			$project_temp_dir = PROJECT_TEMPORARY_DIR;
			$project_draft_dir = PROJECT_DRAFT_DIR;
			$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_award_dir = PROJECT_AWARDED_DIR;
			$project_inprogress_dir = PROJECT_IN_PROGRESS_DIR;
			$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;

			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;


			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;

			foreach($user_folder_list as $user) {
				$users = explode('/', $user);

				// Open bidding project status
				$user_open_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_open_for_bidding_dir);
				foreach($user_open_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$bid_attachment_users = $this->ftp->list_files($project.$users_bid_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($bid_attachment_users)) {
							foreach($bid_attachment_users as $bid_user) {
								$bidder = end(explode('/', $bid_user));
								if(!empty($this->ftp->check_ftp_directory_exist($bid_user))) {
									$bid_attachments = $this->ftp->list_files($bid_user);
									foreach($bid_attachments as $attachment_val) {
										$name = end(explode('/', $attachment_val));
										if($name != 'index.php') {
											$check_attachment_cnt = $this->db->from('projects_active_bids_users_attachments_tracking')->where('bid_attachment_name', $name)->count_all_results();
											if($check_attachment_cnt == 0) {
												$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | bidder_id :'.$bidder.' | bid_attachment_name : '.$name;
												$removed_folder_count++;
												$this->ftp->delete_file($attachment_val);
											}
										}
									}
									
								}
							}
						}
					}
				}

				// Awarded project status
				$user_awarded_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_award_dir);
				foreach($user_awarded_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$bid_attachment_users = $this->ftp->list_files($project.$users_bid_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($bid_attachment_users)) {
							foreach($bid_attachment_users as $bid_user) {
								$bidder = end(explode('/', $bid_user));
								if(!empty($this->ftp->check_ftp_directory_exist($bid_user))) {
									$bid_attachments = $this->ftp->list_files($bid_user);
									foreach($bid_attachments as $attachment_val) {
										$name = end(explode('/', $attachment_val));
										if($name != 'index.php') {
											$check_attachment_cnt = $this->db->from('projects_active_bids_users_attachments_tracking')->where('bid_attachment_name', $name)->count_all_results();
											if($check_attachment_cnt == 0) {
												$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | bidder_id :'.$bidder.' | bid_attachment_name : '.$name;
												$removed_folder_count++;
												$this->ftp->delete_file($attachment_val);
											}
										}
									}
									
								}
							}
						}
					}
				}

				// Inprogress project status
				$user_progress_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_inprogress_dir);
				foreach($user_progress_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$bid_attachment_users = $this->ftp->list_files($project.$users_bid_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($bid_attachment_users)) {
							foreach($bid_attachment_users as $bid_user) {
								$bidder = end(explode('/', $bid_user));
								if(!empty($this->ftp->check_ftp_directory_exist($bid_user))) {
									$bid_attachments = $this->ftp->list_files($bid_user);
									foreach($bid_attachments as $attachment_val) {
										$name = end(explode('/', $attachment_val));
										if($name != 'index.php') {
											$check_attachment_cnt = $this->db->from('projects_active_bids_users_attachments_tracking')->where('bid_attachment_name', $name)->count_all_results();
											if($check_attachment_cnt == 0) {
												$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | bidder_id :'.$bidder.' | bid_attachment_name : '.$name;
												$removed_folder_count++;
												$this->ftp->delete_file($attachment_val);
											}
										}
									}
									
								}
							}
						}
					}
				}

				// Incomplete project status
				$user_incomplete_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_incomplete_dir);
				foreach($user_incomplete_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$bid_attachment_users = $this->ftp->list_files($project.$users_bid_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($bid_attachment_users)) {
							foreach($bid_attachment_users as $bid_user) {
								$bidder = end(explode('/', $bid_user));
								if(!empty($this->ftp->check_ftp_directory_exist($bid_user))) {
									$bid_attachments = $this->ftp->list_files($bid_user);
									foreach($bid_attachments as $attachment_val) {
										$name = end(explode('/', $attachment_val));
										if($name != 'index.php') {
											$check_attachment_cnt = $this->db->from('projects_active_bids_users_attachments_tracking')->where('bid_attachment_name', $name)->count_all_results();
											if($check_attachment_cnt == 0) {
												$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | bidder_id :'.$bidder.' | bid_attachment_name : '.$name;
												$removed_folder_count++;
												$this->ftp->delete_file($attachment_val);
											}
										}
									}
									
								}
							}
						}
					}
				}

				// Completed project status
				$user_completed_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_completed_dir);
				foreach($user_completed_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$bid_attachment_users = $this->ftp->list_files($project.$users_bid_attachments_dir);
						$arr = explode('/', $project);
						if(!empty($bid_attachment_users)) {
							foreach($bid_attachment_users as $bid_user) {
								$bidder = end(explode('/', $bid_user));
								if(!empty($this->ftp->check_ftp_directory_exist($bid_user))) {
									$bid_attachments = $this->ftp->list_files($bid_user);
									foreach($bid_attachments as $attachment_val) {
										$name = end(explode('/', $attachment_val));
										if($name != 'index.php') {
											$check_attachment_cnt = $this->db->from('projects_active_bids_users_attachments_tracking')->where('bid_attachment_name', $name)->count_all_results();
											if($check_attachment_cnt == 0) {
												$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | bidder_id :'.$bidder.' | bid_attachment_name : '.$name;
												$removed_folder_count++;
												$this->ftp->delete_file($attachment_val);
											}
										}
									}
									
								}
							}
						}
					}
				}

				// Expired project status
				$user_expired_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_expired_dir);
			
				foreach($user_expired_projects as $project) {
					if(!empty($this->ftp->check_ftp_directory_exist($project))) {
						$bid_attachment_users = $this->ftp->list_files($project.$users_bid_attachments_dir);
						$arr = explode('/', $project);
						
						if(!empty($bid_attachment_users)) {
							foreach($bid_attachment_users as $bid_user) {
								$bidder = end(explode('/', $bid_user));
								if(!empty($this->ftp->check_ftp_directory_exist($bid_user))) {
									$bid_attachments = $this->ftp->list_files($bid_user);
									
									foreach($bid_attachments as $attachment_val) {
										$name = end(explode('/', $attachment_val));
										if($name != 'index.php') {
											$check_attachment_cnt = $this->db->from('projects_active_bids_users_attachments_tracking')->where('bid_attachment_name', $name)->count_all_results();
											if($check_attachment_cnt == 0) {
												$cron_removed_project_info .= "\r\n".'project_owner_profile : '.end($users).' | project_id : '.$arr[5].' | bidder_id :'.$bidder.' | bid_attachment_name : '.$name;
												$removed_folder_count++;
												$this->ftp->delete_file($attachment_val);
											}
										}
									}
									
								}
							}
						}
					}
				}
			}
			echo 'cronCheckAndRemoveOrphanBidsAttachmentsEntriesFromDiskForAllProjectsStatuses executed successfully (running every day at 03.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. removed attachments: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * This method is used to check attachment exist on disk have relatation with db or not if not then remove it from disk
	 */
	public function cron_check_and_remove_orphan_entries_from_disk_for_chat_attachments() {
		// if($this->check_authroization_key()) {
		// 	return;
		// }
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$chat_attachments_dir 	= CHAT_ATTACHMENTS_DIR; 
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_removed_project_info = '';
			$removed_folder_count = 0;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$receiver_folder_list = $this->ftp->list_files($user.$chat_attachments_dir);			
				if(!empty($receiver_folder_list)) {
					foreach($receiver_folder_list as $receiver) {
						$project_list = $this->ftp->list_files($receiver);			
						$receiver_arr = explode('/', $receiver);
						$receiver_id = end($receiver_arr);
						foreach($project_list as $project_folder) {
							$project_arr = explode('/', $project_folder);
							$project = end($project_arr);
							$attachment_list = $this->ftp->list_files($project_folder);	
							foreach($attachment_list as $attachments) {
								$attachment_arr = explode('/', $attachments);
								$attachment_name = end($attachment_arr);
								$file_parts = pathinfo($attachment_name);
								if($file_parts['extension'] != 'php') {
									$result = $this->conn->get_chat_attachments_from_name("'".$attachment_name."'");
									if(empty($result)) {
										$this->ftp->delete_file($attachments);
										$cron_removed_project_info .= "\r\n".'channel : '.$project.' | sender : '.end($users).' | receiver : '.$receiver_id.' | file : '.$attachment_name;
										$removed_folder_count++;
									}
								}
								
							}
						}
						
					}
				}
				
			}
			echo 'cronCheckAndRemoveOrphanEntriesFromDiskForChatAttachments executed successfully (running everyday at 04.30 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Files removed: '.$removed_folder_count.'. '.$cron_removed_project_info;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	/**
	 * This method is used to check and remove orphan db entries of chat attachments from table
	 */
	public function cron_check_and_remove_orphan_db_entries_of_chat_attachments_from_table() {
		if($this->check_authroization_key()) {
			return;
		}
		$result = $this->conn->select('users_chats_messages');
		$attachment_arr = [];
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		$removed_folder_count = 0;
		######## connectivity of remote server start#########        
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] 	= FTP_PORT;
		$config['debug']    = TRUE;
		
		$this->ftp->connect($config); 
		######## connectivity of remote server end #######
		$users_ftp_dir 	= USERS_FTP_DIR; 
		$chat_attachments_dir = CHAT_ATTACHMENTS_DIR;
		$protocol = CDN_SERVER_LOAD_FILES_PROTOCOL;
		$domain = CDN_SERVER_DOMAIN_NAME;
		
		$file_size = $this->ftp->get_filesize($destination_path);
		foreach($result as $row) {
			if(!empty($row['chat_attachments'])) {
				array_push($attachment_arr, $row);
			}
		}
		foreach($attachment_arr as $val) {
			$val['chat_attachments'] = (array)$val['chat_attachments'];
			$val['chat_attachments'] = $val['chat_attachments']['values'];
			$val['project_id'] = (array)$val['project_id'];
			$val['project_id'] = $val['project_id']['value'];
			$user = $this->db->get_where('users', ['user_id' => $val['sender_id']])->row_array();
			$profile_folder = $user['profile_name'];
			$key_arr = [];
			foreach($val['chat_attachments'] as $key => $attachment) {
				$destination_path = $users_ftp_dir.$profile_folder.$chat_attachments_dir.$val['receiver_id'].DIRECTORY_SEPARATOR.$val['project_id'].DIRECTORY_SEPARATOR.$attachment;
				$file_size = $this->ftp->get_filesize($destination_path);
				if($file_size == '-1') { 
					array_push($key_arr, $key);
					if(isset($val['project_id']) && $val['project_id'] != '') {
						$cron_removed_project_info .= "\r\n".'channel : '.$val['project_id'].' | sender : '.$profile_folder.' | receiver : '.$val['receiver_id'].' | file : '.$attachment;
						$removed_folder_count++;
					}
				}
			}
			if(!empty($key_arr)) {
				$val['chat_attachments'] = array_diff_key($val['chat_attachments'], array_flip($key_arr));
				$files = 'null';
				if(!empty($val['chat_attachments'])) {
						if(count($val['chat_attachments']) == 1) {
								$files = "['" . implode ( "', '", $val['chat_attachments'] ) . "']";
						} else {
								$files = "['" . implode ( "', '", $val['chat_attachments']) . "']";
						}
				} else {
						$files = 'null';
				}
				$this->conn->delete_chat_attachments_by_name($files, $val);
			}
			
		}
		echo 'cronCheckAndRemoveOrphanDBEntriesOfChatAttachmentsFromTable executed successfully (running everyday at 05.00 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Files removed: '.$removed_folder_count.'. '.$cron_removed_project_info;
	}
	/*
	 * @sid
	 * cron to auto approve project and move it's entries from awaiting moderation table to open for bidding
	*/
	public function cron_to_auto_approve_projects_from_awaiting_moderation() {
		if($this->check_authroization_key()) {
			return;
		}
		$this->db->select('am.*, u.profile_name');
		$this->db->from('projects_awaiting_moderation am');
		$this->db->join('users u', 'u.user_id=am.project_owner_id');
		$this->db->where('am.auto_approval_date <= NOW()');
		$awaiting_moderation_result = $this->db->get()->result_array();

		$cron_start_date = date('d.m.Y H:i:s');
		$cron_removed_project_info = '';
		$removed_entry_count = 0;

		if(!empty($awaiting_moderation_result)) {
			
			foreach($awaiting_moderation_result as $awaiting_moderation_data) {
				
				$attachements = $this->db->get_where('awaiting_moderation_projects_attachments', ['project_id' => $awaiting_moderation_data['project_id']])->result_array();
				$tags = $this->db->get_where('awaiting_moderation_projects_tags', ['project_id' => $awaiting_moderation_data['project_id']])->result_array();
				$categories = $this->db->get_where('awaiting_moderation_projects_categories_listing_tracking', ['project_id' => $awaiting_moderation_data['project_id']])->result_array();
				// Add message to user activity about project approved
				if($awaiting_moderation_data['project_type'] == 'fulltime'){
					$msg = $this->config->item('fulltime_project_auto_approve_user_activity_log_displayed_message_sent_to_po');
				}else{
					$msg = $this->config->item('project_auto_approve_user_activity_log_displayed_message_sent_to_po');
				}
				$msg = str_replace('{project_title}', htmlspecialchars($awaiting_moderation_data['project_title'], ENT_QUOTES), $msg);
				$msg = str_replace('{project_url_link}', VPATH.$this->config->item('project_detail_page_url')."?id=".$awaiting_moderation_data['project_id'], $msg);
				user_display_log($msg, $awaiting_moderation_data['project_owner_id']);
				// Insert into fixed_budget_projects_open_bidding table
				// $time_arr = explode(':', $this->config->item('standard_project_availability'));
				// $project_expire_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
				$project_expire_date = $this->Post_project_model->get_project_correct_expiration_date($awaiting_moderation_data);
				$open_bidding_data = [
					'project_id' => $awaiting_moderation_data['project_id'],
					'project_owner_id' => $awaiting_moderation_data['project_owner_id'],
					'project_posting_date' => date('Y-m-d H:i:s'),
					'project_expiration_date' => $project_expire_date,
					'project_title' => $awaiting_moderation_data['project_title'],
					'project_description' => $awaiting_moderation_data['project_description'],
					'locality_id'=>$awaiting_moderation_data['locality_id'],
					'county_id'=>$awaiting_moderation_data['county_id'],
					'postal_code_id' => $awaiting_moderation_data['postal_code_id'],
					'project_type' => $awaiting_moderation_data['project_type'],
					'min_budget' => $awaiting_moderation_data['min_budget'],
					'max_budget' => $awaiting_moderation_data['max_budget'],
					'escrow_payment_method' => $awaiting_moderation_data['escrow_payment_method'],
					'offline_payment_method' => $awaiting_moderation_data['offline_payment_method'],
					'confidential_dropdown_option_selected' => $awaiting_moderation_data['confidential_dropdown_option_selected'],
					'not_sure_dropdown_option_selected' => $awaiting_moderation_data['not_sure_dropdown_option_selected'],
					'featured' => $awaiting_moderation_data['featured'],
					'urgent' => $awaiting_moderation_data['urgent'],
					'sealed' => $awaiting_moderation_data['sealed'],
					'hidden' => $awaiting_moderation_data['hidden'],
					'views' => 0,
					'revisions' => 0
				];
				
				$this->db->insert('projects_open_bidding', $open_bidding_data);

				$publish_project = [
					'project_id' => $awaiting_moderation_data['project_id'],
					'project_owner_id' => $awaiting_moderation_data['project_owner_id'],
					'project_posting_date' => date('Y-m-d H:i:s'),
					'project_title' => trim($awaiting_moderation_data['project_title']),
					'project_type' => $awaiting_moderation_data['project_type']
				];
				$this->db->insert('users_alltime_published_projects_tracking', $publish_project);

				// Insert data into project tags
				if(!empty($tags)) {
					foreach($tags as $tag_value) {
						$this->db->insert ('projects_tags', array('project_id'=>$tag_value['project_id'],'project_tag_name'=> $tag_value['awaiting_moderation_project_tag_name']));
					}
				}
				// Insert into project_categories_list_tracking
				$parent_categories_id = [];
				if(!empty($categories)) {
					foreach($categories as $category_key => $category_value){
						$project_category_id = 0;
						$project_parent_category_id = 0;
						if(!empty($category_value['awaiting_moderation_project_parent_category_id'])){
						
							$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['awaiting_moderation_project_parent_category_id']);
					
							$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($category_value['awaiting_moderation_project_parent_category_id'],$category_value['awaiting_moderation_project_category_id']);
							
							if($check_project_parent_category_exist){
								if($check_project_child_category_exist){
									
									$project_category_id = $category_value['awaiting_moderation_project_category_id'];
									$project_parent_category_id = $category_value['awaiting_moderation_project_parent_category_id'];
									
								}else{
								
									$project_category_id =  $category_value['awaiting_moderation_project_parent_category_id'];
									$project_parent_category_id = 0;
									
								}
							}
						}else{
							$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['awaiting_moderation_project_category_id']);
							if($check_project_parent_category_exist){
							
								$project_category_id =  $category_value['awaiting_moderation_project_category_id'];
								$project_parent_category_id = 0;
							
							}
						
						}
						
						if(!empty($project_category_id) || !empty($project_parent_category_id)){
							$this->db->insert ('projects_categories_listing_tracking', array('project_id'=>$category_value['project_id'],'project_category_id'=> $project_category_id,'project_parent_category_id'=>$project_parent_category_id)); // move the categories from table awaiting_moderation_projects_categories_listing_tracking to projects_categories_listing_tracking and apply check that category is valid or not
							if($project_parent_category_id == 0) {
								array_push($parent_categories_id, $project_category_id);
							} else {
								array_push($parent_categories_id, $project_parent_category_id);
							}
						}
					}
				}
				########## Delete the racord from awaiting moderation table 
				$this->db->delete('awaiting_moderation_projects_tags', array('project_id' => $awaiting_moderation_data['project_id']));
				$this->db->delete('awaiting_moderation_projects_categories_listing_tracking', array('project_id' => $awaiting_moderation_data['project_id']));
				$this->db->delete('projects_awaiting_moderation', array('id' => $awaiting_moderation_data['id']));
				if($this->db->affected_rows() == 1) {
					// message for cron success
					$cron_removed_project_info .= "\r\n".'project_id : '.$awaiting_moderation_data['project_id'].' | project_owner_id : '.$awaiting_moderation_data['project_owner_id'].' | awaiting_moderation_projects_attachments ('.count($attachements).') | projects_categories_listing_tracking ('.count($categories).') |  projects_tags ('.count($tags).')';
					$removed_entry_count++;
				}
				

				if($awaiting_moderation_data['featured'] == 'Y' || $awaiting_moderation_data['urgent'] == 'Y' || $awaiting_moderation_data['sealed'] == 'Y' || $awaiting_moderation_data['hidden'] == 'Y' ) {
					$this->Projects_model->update_upgrade_purchase_tracking_with_related_refresh_sequence_tracking($awaiting_moderation_data['project_id']);
				}
				
			
				
				
				// move data from awaiting_moderation_projects_attachments table to projects_attachments
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				try {
					$this->ftp->connect($config); 
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
					$profile_folder = $awaiting_moderation_data['profile_name'];
					/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
					$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create directory in projects folder
					$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$awaiting_moderation_data['project_id'] , 0777); // create the directory by using project id */
					
					
					// $this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
					
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
					
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$awaiting_moderation_data['project_id'].DIRECTORY_SEPARATOR);
					
					
					
					
					if(!empty($attachements)) {
						
						//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$awaiting_moderation_data['project_id'].$project_owner_attachments_dir , 0777); // create the owner attachment directory by using project id
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$awaiting_moderation_data['project_id'].$project_owner_attachments_dir);
						
						
						foreach($attachements as $attachment_key => $attachment_value){
						
							if(!empty($attachment_value['awaiting_moderation_project_attachment_name'])){
								$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$awaiting_moderation_data['project_id'].$project_owner_attachments_dir .$attachment_value['awaiting_moderation_project_attachment_name'];
								$file_size = $this->ftp->get_filesize($source_path);
								if($file_size != '-1'){
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$awaiting_moderation_data['project_id'].$project_owner_attachments_dir.$attachment_value['awaiting_moderation_project_attachment_name'];
									$this->ftp->move($source_path, $destination_path);
									$this->db->insert ('projects_attachments', array('project_id'=>$attachment_value['project_id'], 'project_attachment_name'=> $attachment_value['awaiting_moderation_project_attachment_name']));
								}
							}
						}
						$this->db->delete('awaiting_moderation_projects_attachments', array('project_id' => $awaiting_moderation_data['project_id']));
					}
					if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$awaiting_moderation_data['project_id']))){
						$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$awaiting_moderation_data['project_id'].DIRECTORY_SEPARATOR);// delete project directory 
					}
					$this->ftp->close();
				} catch(Exception $e) {

				}

				// trigger socket event to update awaiting moderation project list on user dashboard
				$url = PROJECT_MANAGEMENT_SOCKET_URL."/awaitingModerationProjectsApproved/".$awaiting_moderation_data['project_owner_id'].'/'.$awaiting_moderation_data['project_id'].'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
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
					echo $e->getMessage();
				}

				if(!empty($open_bidding_data['hidden']) && $open_bidding_data['hidden'] == 'N') {
					$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
					$check_valid_arr = array_map('getInt', $time_arr); 
					$valid_time_arr = array_filter($check_valid_arr);
					$next_refresh_date = null;
					if(!empty($valid_time_arr)) {
						$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
						$refresh_sequence_data = [
							'project_id' => $awaiting_moderation_data['project_id'],
							'project_last_refresh_time' => null,
							'project_next_refresh_time' => $next_refresh_date,
							'project_upgrade_refresh_sequence_standard' => $this->config->item('standard_project_refresh_sequence')
						];
						$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
					}
					
					$latest_project_refresh_tracking = [
						'project_id' => $awaiting_moderation_data['project_id'],
						'project_last_refresh_time' => date('Y-m-d H:i:s'),
						'project_next_refresh_time' => $next_refresh_date
					];
					$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);

					// save data into users newly posted projects sent notification tracking table
					$category_mapping_data = $this->Post_project_model->get_projects_professionals_categories_mapping_data_based_on_categories_id($parent_categories_id);
					if(!empty($category_mapping_data)) {
						$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_bidding_data);
					}
					// save data into users newly posted projects sent notification tracking table when project posted by favorite employer
					$users = $this->Post_project_model->get_users_id_from_favorite_employer_id($open_bidding_data['project_owner_id']);
					if(!empty($users)) {
						$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_bidding_data);
					}
				}
			}
			
		}
		echo 'cronAutoApproveProjectsFromAwaitingModeration executed successfully (running every 2 minutes). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. '.($removed_entry_count > 0 ? 'There were '.$removed_entry_count.' projects approved. Entries are as follows: '.$cron_removed_project_info : 'There were no projects to be approved.');
		return;
	}

	/*message displayed to user activity log when node (updates) counts valid user session entries in user log table*/
	public function user_activity_log_displayed_message_node_counts_valid_user_session_entries()
	{
		if($this->check_authroization_key()) {
			return;
		}
		if(!empty( $_GET['userid_arr']) && $this->config->item('user_activity_log_displayed_message_node_counts_valid_user_session_entries')){
			$users_array = explode(",",$_GET['userid_arr']);
			if(!empty($users_array)){
				foreach($users_array as $user_id){
					$user_log_message_count = $this->db->where(['user_id' => $user_id])->from('user_log')->count_all_results();
					$get_user_profile_name = $this->db->select('profile_name')->get_where('users', ['user_id' => $user_id])->row_array();
					$count_user_session_entries = $this->config->item('user_activity_log_displayed_message_node_counts_valid_user_session_entries');
					$msg = str_replace('{user_valid_connections_online_count}', $user_log_message_count, $count_user_session_entries);
					user_display_log($msg, $user_id);
					echo "\n".$user_id."-->".$msg;
				}
			}
		
		}
		//die("hello");
	}
	/**
	 * cron check expired upgrade in related table and based on that update there correct status in open bidding table
	*/
	public function cron_update_project_expired_upgrade_status_open_bidding_table() {
		if($this->check_authroization_key()) {
			return;
		}
		$this->Projects_model->update_expired_upgrade_status_open_bidding();
	}
	/**
	 * cron execute and calculate next refresh time for project and update refresh sequence related tables, also send project refresh related notification to project owner
	*/
	public function cron_calculate_and_update_project_refresh_sequence() {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_refreshed_project_info = '';
		$next_refresh_time = []; // this variable is used to manage user display activity log and next refresh time
		
		############################################ standard project refresh ###########################################################
		$standard_project_refresh = $this->Projects_model->manage_standard_project_refresh($next_refresh_time);
		$next_refresh_time = $standard_project_refresh['next_refresh_time'];
		############################################ end ################################################################################

		############################################ featured project refresh ###########################################################
		$featured_project_membership_included_refresh = $this->Projects_model->manage_featured_project_refresh_for_membership_included_payment($next_refresh_time);
		$next_refresh_time = $featured_project_membership_included_refresh['next_refresh_time'];

		$featured_project_bonus_based_refresh = $this->Projects_model->manage_featured_project_refresh_for_bonus_based_payment($next_refresh_time);
		$next_refresh_time = $featured_project_bonus_based_refresh['next_refresh_time'];

		$featured_project_refresh = $this->Projects_model->manage_featured_project_refresh_for_real_money_payment($next_refresh_time);
		$next_refresh_time = $featured_project_refresh['next_refresh_time'];
		############################################ end ################################################################################
		
		############################################ urgent project refresh ###########################################################
		$urgent_project_membership_included_refresh = $this->Projects_model->manage_urgent_project_refresh_for_membership_included_payment($next_refresh_time);
		$next_refresh_time = $urgent_project_membership_included_refresh['next_refresh_time'];

		$urgent_project_bonus_based_refresh = $this->Projects_model->manage_urgent_project_refresh_for_bonus_based_payment($next_refresh_time);
		$next_refresh_time = $urgent_project_bonus_based_refresh['next_refresh_time'];
		
		$urgent_project_refresh = $this->Projects_model->manage_urgent_project_refresh_for_real_money_payment($next_refresh_time);
		$next_refresh_time = $urgent_project_refresh['next_refresh_time'];
		############################################ end ################################################################################
		
		############################################ sealed project refresh ###########################################################
		
		$sealed_project_membership_included_refresh = $this->Projects_model->manage_sealed_project_refresh_for_membership_included_payment($next_refresh_time);
		$next_refresh_time = $sealed_project_membership_included_refresh['next_refresh_time'];
		
		$sealed_project_bonus_based_refresh = $this->Projects_model->manage_sealed_project_refresh_for_bonus_based_payment($next_refresh_time);
		$next_refresh_time = $sealed_project_bonus_based_refresh['next_refresh_time'];
		
		$sealed_project_refresh = $this->Projects_model->manage_sealed_project_refresh_for_real_money_payment($next_refresh_time);
		$next_refresh_time = $sealed_project_refresh['next_refresh_time'];

		############################################ end ##############################################################################

		########################################## Store data for user display activity log #############################################
		if(!empty($next_refresh_time)) {
			
			foreach($next_refresh_time as $key => $val) {
				$date = new DateTime();
				$date = $date->format("d.m.Y H:i:s");
				/* echo "<pre>";
				print_r($val); */
				/* if($val['next_refresh_time'] == null && ($val['type'] == 'standard' || $val['type'] == 'sealed')) {
					$msg = $this->config->item('standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po');
					if($val['project_type'] == 'fulltime') {
						$msg = $this->config->item('fulltime_standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po');
					}
					$msg = str_replace('{project_title}', htmlspecialchars(trim($val['project_title']), ENT_QUOTES), $msg);
					$project_url = base_url($this->config->item('project_detail_page_url').'?id='.$key);
					$msg = str_replace('{project_url_link}', $project_url, $msg);
					project_refresh_sequence_save_user_log($this,$val['project_owner_id'], $msg);
					continue;
				}
				else if($val['next_refresh_time'] == null){
					continue;
				} */
				
			 	// standard and sealed project user disply activity message
				if($val['type'] == 'standard' || $val['type'] == 'sealed' ) {
					$msg = $this->config->item('standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po');
					if($val['project_type'] == 'fulltime') {
						$msg = $this->config->item('fulltime_standard_or_sealed_project_refresh_user_activity_log_displayed_message_sent_to_po');
					}
					$msg = str_replace('{project_title}', htmlspecialchars(trim($val['project_title']), ENT_QUOTES), $msg);
					$project_url = base_url($this->config->item('project_detail_page_url').'?id='.$key);
					$msg = str_replace('{project_url_link}', $project_url, $msg);
					if(!empty($val['next_refresh_time'])) {
						$time_msg = $this->config->item('project_next_refresh_user_activity_log_displayed_message_sent_to_po');
						$time_msg = str_replace('{next_refresh_time}', date(DATE_TIME_FORMAT, strtotime($val['next_refresh_time'])), $time_msg);
						$msg .= ' '.$time_msg;
					}
					$cron_refreshed_project_info .= "\r\n".'project_id : '.$key.' | project_owner_id : '.$val['project_owner_id'].' | last_refresh_time : '.$val['last_refresh_time'].' | next_refresh_time : '.$val['next_refresh_time'];
					project_refresh_sequence_save_user_log($this,$val['project_owner_id'], $msg);
				} else if ($val['type'] == 'featured') { // featured project user disply activity message
					$msg = $this->config->item('featured_project_refresh_user_activity_log_displayed_message_sent_to_po');
					if($val['project_type'] == 'fulltime') {
						$msg = $this->config->item('fulltime_featured_project_refresh_user_activity_log_displayed_message_sent_to_po');
					}
					$msg = str_replace('{project_title}', htmlspecialchars(trim($val['project_title']), ENT_QUOTES), $msg);
					$project_url = base_url($this->config->item('project_detail_page_url').'?id='.$key);
					$msg = str_replace('{project_url_link}', $project_url, $msg);
					if(!empty($val['next_refresh_time'])) {
						$time_msg = $this->config->item('project_next_refresh_user_activity_log_displayed_message_sent_to_po');
						$time_msg = str_replace('{next_refresh_time}', date(DATE_TIME_FORMAT, strtotime($val['next_refresh_time'])), $time_msg);
						$msg .= ' '.$time_msg;
					}
					$cron_refreshed_project_info .= "\r\n".'project_id : '.$key.' | project_owner_id : '.$val['project_owner_id'].' | last_refresh_time : '.$val['last_refresh_time'].' | next_refresh_time : '.$val['next_refresh_time'];
					project_refresh_sequence_save_user_log($this,$val['project_owner_id'], $msg);
				} else if ($val['type'] == 'urgent') { // urgent project user disply activity message
					$msg = $this->config->item('urgent_project_refresh_user_activity_log_displayed_message_sent_to_po');
					if($val['project_type'] == 'fulltime') {
						$msg = $this->config->item('fulltime_urgent_project_refresh_user_activity_log_displayed_message_sent_to_po');
					}
					$msg = str_replace('{project_title}', htmlspecialchars(trim($val['project_title']), ENT_QUOTES), $msg);
					$project_url = base_url($this->config->item('project_detail_page_url').'?id='.$key);
					$msg = str_replace('{project_url_link}', $project_url, $msg);
					if(!empty($val['next_refresh_time'])) {
						$time_msg = $this->config->item('project_next_refresh_user_activity_log_displayed_message_sent_to_po');
						$time_msg = str_replace('{next_refresh_time}', date(DATE_TIME_FORMAT, strtotime($val['next_refresh_time'])), $time_msg);
						$msg .= ' '.$time_msg;
					}
					$cron_refreshed_project_info .= "\r\n".'project_id : '.$key.' | project_owner_id : '.$val['project_owner_id'].' | last_refresh_time : '.$val['last_refresh_time'].' | next_refresh_time : '.$val['next_refresh_time'];
					project_refresh_sequence_save_user_log($this,$val['project_owner_id'], $msg);
				}  
			}
		}
		######################################### end ##################################################################################
		echo 'cronCalculateAndUpdateProjectRefreshSequence executed successfully (running every second). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. '.$cron_refreshed_project_info;
		return;
	}
	
	/*
	This function is used to move awarded bids whose awarded expiration time is passed to active bids tables 
	*/


	public function cron_move_expired_awarded_bids_to_active_bids_table(){
		if($this->check_authroization_key()) {
			return;
		}
		
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_move_project_info = '';
		$moved_count = 0 ;

		$fixed_project_award_expiration_bids_data = $this->db // get the user detail
		->select('project_id,winner_id')
		->from('fixed_budget_projects_awarded_tracking')
		->where(['project_award_expiration_date <= '=> date('Y-m-d H:i:s')])
		->group_by('project_id')
		->get()->result_array();

		$fixed_project_award_expiration_bids_info_data = $this->db // get the user detail
		->select('project_id,winner_id')
		->from('fixed_budget_projects_awarded_tracking')
		->where(['project_award_expiration_date <= '=> date('Y-m-d H:i:s')])
		->get()->result_array();

		if(!empty($fixed_project_award_expiration_bids_info_data)) {
			foreach($fixed_project_award_expiration_bids_info_data as $fixed_project_key=>$fixed_project_value){ 
				$cron_move_project_info .= "\r\n".'project_id : '.$fixed_project_value['project_id'].' | winner_id : '.$fixed_project_value['winner_id'];
				$moved_count++;
			}
		}
		
		if(!empty($fixed_project_award_expiration_bids_data)){

			foreach($fixed_project_award_expiration_bids_data as $fixed_project_key=>$fixed_project_value){
			
				$project_status_table_array = $this->Projects_model->get_project_status_table_name($fixed_project_value['project_id']);
			
				$get_project_data_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $fixed_project_value['project_id'])
				->get()->row_array();
					
				$this->Bidding_model->move_awarded_expired_bid_to_active_bids_dedicated_project($get_project_data_data,$project_status_table_array['project_status']);	
			}
		
		}
		
		$hourly_project_award_expiration_bids_info_data = $this->db // get the user detail
		->select('project_id,winner_id')
		->from('hourly_rate_based_projects_awarded_tracking')
		->where(['project_award_expiration_date <= '=> date('Y-m-d H:i:s')])
		->get()->result_array();

		if(!empty($hourly_project_award_expiration_bids_info_data)) {
			foreach($hourly_project_award_expiration_bids_info_data as $hourly_project_key=>$hourly_project_value) {
				$cron_move_project_info .= "\r\n".'project_id : '.$hourly_project_value['project_id'].' | winner_id : '.$hourly_project_value['winner_id'];
					$moved_count++;
			}
		}	
		

		$hourly_project_award_expiration_bids_data = $this->db // get the user detail
		->select('project_id,winner_id')
		->from('hourly_rate_based_projects_awarded_tracking')
		->where(['project_award_expiration_date <= '=> date('Y-m-d H:i:s')])
		->group_by('project_id')
		->get()->result_array();

		if(!empty($hourly_project_award_expiration_bids_data)){
		
			foreach($hourly_project_award_expiration_bids_data as $hourly_project_key=>$hourly_project_value){
			
				$project_status_table_array = $this->Projects_model->get_project_status_table_name($hourly_project_value['project_id']);
			
				$get_project_data_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $hourly_project_value['project_id'])
				->get()->row_array();
					
				$this->Bidding_model->move_awarded_expired_bid_to_active_bids_dedicated_project($get_project_data_data,$project_status_table_array['project_status']);
				
			}
		
		}
		
		$fulltime_project_award_expiration_bids_data = $this->db // get the user detail
		->select('fulltime_project_id as project_id, employee_id as winner_id')
		->from('fulltime_projects_awarded_tracking')
		->where(['application_award_expiration_date <= '=> date('Y-m-d H:i:s')])
		->group_by('fulltime_project_id')
		->get()->result_array();
		
		$fulltime_project_award_expiration_bids_info_data = $this->db // get the user detail
		->select('fulltime_project_id as project_id, employee_id as winner_id')
		->from('fulltime_projects_awarded_tracking')
		->where(['application_award_expiration_date <= '=> date('Y-m-d H:i:s')])
		->get()->result_array();

		if(!empty($fulltime_project_award_expiration_bids_info_data)) {
			foreach($fulltime_project_award_expiration_bids_info_data as $fulltime_project_value) {
				$cron_move_project_info .= "\r\n".'project_id : '.$fulltime_project_value['project_id'].' | winner_id : '.$fulltime_project_value['winner_id'];
				$moved_count++;
			}
		}
		
		
		if(!empty($fulltime_project_award_expiration_bids_data)){
		
			foreach($fulltime_project_award_expiration_bids_data as $fulltime_project_key=>$fulltime_project_value){
				$project_status_table_array = $this->Projects_model->get_project_status_table_name($fulltime_project_value['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
					if(empty($project_status_table_array['table_name'])) {
						continue;
					}
					$get_project_data_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $fulltime_project_value['project_id'])
					->get()->row_array();	
				} else {
					
					if(empty($project_status_table_array['table_name'])) {
						continue;
					}
					$get_project_data_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $fulltime_project_value['project_id'])
					->get()->row_array();
				}
				
				
				$this->Bidding_model->move_awarded_expired_bid_to_active_bids_dedicated_project($get_project_data_data,$project_status_table_array['project_status']);
				
			}
		
		}

		echo 'cronMoveExpiredAwardedBidsToActiveBidsTable executed successfully (running every 30 seconds). Cron job started at '.$cron_start_date.' and ended '.date('d.m.Y H:i:s').'. Entries moved: '.$moved_count.'. '.$cron_move_project_info; 
	}
	
	// remove the orphan portfolio images regarding portfolio
	public function cron_check_and_remove_obsolete_folder_related_to_portfolio_from_user_folder() {		
		if($this->check_authroization_key()) {
			return;
		}
		
		try {
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$temp_dir 	= TEMP_DIR; 
			$user_folder_list = $this->ftp->list_files($users_ftp_dir);
			$user_portfolio_dir = USER_PORTFOLIO;
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_remove_project_info = '';
			$removed_folder_count = 0 ;
			foreach($user_folder_list as $user) {
				$users = explode('/', $user);
				$user_portfolios = $this->ftp->list_files($user.$user_portfolio_dir);
				if(!empty($user_portfolios) && is_array($user_portfolios)) {
					foreach($user_portfolios as $portfolio) {
						if(!empty($portfolio)) {
							
							$portfolio .= DIRECTORY_SEPARATOR;
							if(!empty($this->ftp->check_ftp_directory_exist($portfolio))) {
								$arr = explode('/', $portfolio);
								if(ctype_digit($arr[4])) {
									$result = $this->db->get_where('users_portfolios', ['portfolio_id' => $arr[4]])->row_array();
									if(empty($result)) {
										$cron_remove_portfolio_info .= "\r\n".'portfolio_id : '.$arr[4].' | project_owner_profile : '.end($users);
										$removed_folder_count++;
										$this->ftp->delete_dir($portfolio);
									}else{
									
										$image_list = $this->ftp->list_files($portfolio);
										foreach($image_list as $image) {
											$image_arr = explode('/', $image);
											$image_name = end($image_arr);
											
											if (strpos($image_name, '_thumb.jpg') == false) {
												$result = $this->db->get_where('users_portfolios_images', ['portfolio_id' => $arr[4],'portfolio_image_name'=>$image_name])->row_array();
												if(empty($result)){
													$this->ftp->delete_file($image);
													if (strpos($image_name, '_thumb.jpg') == false) {
														$thumb_name = explode('.',$image_name);
														$this->ftp->delete_file($user.$user_portfolio_dir.$arr[4].'/'.$thumb_name[0].'_thumb.jpg');
													}
													
												
												}
											}
											
										}
									}
								} 
								
							} 
						}
					} 
				}
				
			}
			echo 'cronCheckAndRemoveObsoleteFolderRelatedToPortfolioFromUserFolder executed successfully (running every 6 hours). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Folders removed: '.$removed_folder_count.'. '.$cron_remove_portfolio_info; 
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		
	}
		
	// remove the orphan entries from database and disk regarding portfolio	
	public function cron_check_and_remove_orphan_entries_from_db_all_portfolio_images() {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cron_remove_project_info = '';
		$removed_entries_count = 0 ;

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
		

		$this->db->select('upi.*, u.profile_name');
		$this->db->from('users_portfolios_images upi');
		$this->db->join('users u', 'u.user_id = upi.user_id', 'left');
		$portfolio_images = $this->db->get()->result_array();
		if(!empty($portfolio_images)) {
			foreach($portfolio_images as $val) {
				$profile_folder = $val['profile_name'];
				$image_name = $val['portfolio_image_name'];
				$original_image_source_path = $users_ftp_dir.$profile_folder.$user_portfolio_dir.DIRECTORY_SEPARATOR .$val['portfolio_id']. DIRECTORY_SEPARATOR .$image_name;
				$original_file_size = $this->ftp->get_filesize($original_image_source_path);
				if($original_file_size == '-1'){
					$this->db->delete('users_portfolios_images', array('id' => $val['id']));
					$cron_remove_portfolio_info .= "\r\n".'portfolio_id : '.$val['portfolio_id'].' | user_id : '.$val['user_id'].' | portfolio_image : '.$image_name;
					$removed_entries_count++;
				}
			}
		}
		$this->ftp->close();

		echo 'cronCheckAndRemoveOrphanEntriesFromDBAllPortfolioImages executed successfully. Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').' removed entries : '.$removed_entries_count.'.'.$cron_remove_portfolio_info;
	}
	
	
	//This method is used to move those active disputes to admin arbitration status
	public function cron_move_project_dispute_to_admin_arbitration() {
		if($this->check_authroization_key()) {
			return;
		}
		
		$cron_start_date = date('d.m.Y H:i:s');
		$dispute_info = '';
		$fixed_budget_negotiation_time_expired_records =  $this->db->select ('*')->where ('dispute_negotiation_end_date <= ', date('Y-m-d H:i:s'))->get ('fixed_budget_projects_active_disputes')->result_array ();
		
		$hourly_project_negotiation_time_expired_records =  $this->db->select ('*')->where ('dispute_negotiation_end_date <= ', date('Y-m-d H:i:s'))->get ('hourly_rate_based_projects_active_disputes')->result_array ();
		
		$fulltime_project_negotiation_time_expired_records =  $this->db->select ('*')->where ('dispute_negotiation_end_date <= ', date('Y-m-d H:i:s'))->get ('fulltime_projects_active_disputes')->result_array ();
		
		
		
		$counter = 0;
		if(!empty($fixed_budget_negotiation_time_expired_records)){
			
			foreach($fixed_budget_negotiation_time_expired_records as $key=>$value){
				
				
				if(!empty($value) && $value['disputed_amount'] >= $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration') && strtotime($value['dispute_negotiation_end_date']) < time()){
					
					
					$dispute_status = $this->db->from('fixed_budget_projects_active_disputes')->where(['dispute_reference_id'=>$value['dispute_reference_id'], 'dispute_status !='=>'under_admin_review'])->count_all_results();
					if($dispute_status == 1) {
						$counter ++;

						$this->db->update('projects_disputes_attachments', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$value['dispute_reference_id']]);
					
						######## update status under admin review############# start ####
						$this->db->update('fixed_budget_projects_active_disputes', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$value['dispute_reference_id']]);
						
						$data['project_type'] = 'fixed';
						$data['dispute_reference_id'] = $value['dispute_reference_id'];
						$this->Projects_disputes_model->save_projects_disputes_admin_arbitration_fees($data);

						
						######## update status under admin review############# end ####
					}

					
					
				}	
				
			}	
		}

		if(!empty($hourly_project_negotiation_time_expired_records)){
			
			foreach($hourly_project_negotiation_time_expired_records as $key=>$value){
				if(!empty($value) && $value['disputed_amount'] >= $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration') && strtotime($value['dispute_negotiation_end_date']) < time()){
					
					$dispute_status = $this->db->from('hourly_rate_based_projects_active_disputes')->where(['dispute_reference_id'=>$value['dispute_reference_id'], 'dispute_status !='=>'under_admin_review'])->count_all_results();
					if($dispute_status == 1) {
						$counter ++;
						$this->db->update('projects_disputes_attachments', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$value['dispute_reference_id']]);
						######## update status under admin review############# start ####
						$this->db->update('hourly_rate_based_projects_active_disputes', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$value['dispute_reference_id']]);
						
						$data['project_type'] = 'hourly';
						$data['dispute_reference_id'] = $value['dispute_reference_id'];
						$this->Projects_disputes_model->save_projects_disputes_admin_arbitration_fees($data);
					}
				}	
				
			}	
		}
		if(!empty($fulltime_project_negotiation_time_expired_records)){
			
			foreach($fulltime_project_negotiation_time_expired_records as $key=>$value){
				
				
				if(!empty($value) && $value['disputed_amount'] >= $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration') && strtotime($value['dispute_negotiation_end_date']) < time()){
					

					$dispute_status = $this->db->from('fulltime_projects_active_disputes')->where(['dispute_reference_id'=>$value['dispute_reference_id'], 'dispute_status !='=>'under_admin_review'])->count_all_results();
					if($dispute_status == 1) {
						$counter ++;
							$this->db->update('projects_disputes_attachments', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$value['dispute_reference_id']]);
						######## update status under admin review############# start ####
						$this->db->update('fulltime_projects_active_disputes', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$value['dispute_reference_id']]);
						
						$data['project_type'] = 'fulltime';
						$data['dispute_reference_id'] = $value['dispute_reference_id'];
						$this->Projects_disputes_model->save_projects_disputes_admin_arbitration_fees($data);
					}
				}	
				
			}	
		}
		echo 'cronMoveProjectDisputeToAdminArbitration executed successfully (running every 15 seconds). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Moved disputes entries: '.$counter.'. '.$dispute_info;
		
	}	
	
	//This method is used to move those active disputes to close disputes whose negotiation time is expired and dispute is closed by automatic decission
	public function cron_move_active_disputes_to_closed_disputes_table() {
		if($this->check_authroization_key()) {
			return;
		}
		
		$cron_start_date = date('d.m.Y H:i:s');
		$fixed_budget_negotiation_time_expired_records =  $this->db->select ('*')->where ('dispute_negotiation_end_date <= ', date('Y-m-d H:i:s'))->get ('fixed_budget_projects_active_disputes')->result_array ();
		$counter = 0;
		$dispute_info = '';
		if(!empty($fixed_budget_negotiation_time_expired_records)){
		
			foreach($fixed_budget_negotiation_time_expired_records as $key=>$value){
				
				
				if(!empty($value) && $value['disputed_amount'] < $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration') && strtotime($value['dispute_negotiation_end_date']) < time()){
					
					
					
					$data['project_type'] = 'fixed';
					$data['dispute_reference_id'] = $value['dispute_reference_id'];
					$this->Projects_disputes_model->close_automatically_arbitrated_dispute($data);
					$counter++;
					$dispute_info .= "\r\n".'dispute_id : '.$value['dispute_reference_id'];
				}	
				
			}	
		}

		$hourly_rate_based_project_negotiation_time_expired_records =  $this->db->select ('*')->where ('dispute_negotiation_end_date <= ', date('Y-m-d H:i:s'))->get ('hourly_rate_based_projects_active_disputes')->result_array ();
		
		if(!empty($hourly_rate_based_project_negotiation_time_expired_records)){
			
			foreach($hourly_rate_based_project_negotiation_time_expired_records as $key=>$value){
				
				
				if(!empty($value) && $value['disputed_amount'] < $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration') && strtotime($value['dispute_negotiation_end_date']) < time()){
					
					
					
					$data['project_type'] = 'hourly';
					$data['dispute_reference_id'] = $value['dispute_reference_id'];
					$this->Projects_disputes_model->close_automatically_arbitrated_dispute($data);
					$counter++;
					$dispute_info .= "\r\n".'dispute_id : '.$value['dispute_reference_id'];
				}	
				
			}	
		}
		
		$fulltime_project_negotiation_time_expired_records =  $this->db->select ('*')->where ('dispute_negotiation_end_date <= ', date('Y-m-d H:i:s'))->get ('fulltime_projects_active_disputes')->result_array ();
		if(!empty($fulltime_project_negotiation_time_expired_records)){
		
			foreach($fulltime_project_negotiation_time_expired_records as $key=>$value){
				
				
				if(!empty($value) && $value['disputed_amount'] < $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration') && strtotime($value['dispute_negotiation_end_date']) < time()){
					
					
					
					$data['project_type'] = 'fulltime';
					$data['dispute_reference_id'] = $value['dispute_reference_id'];
					$this->Projects_disputes_model->close_automatically_arbitrated_dispute($data);
					$counter++;
					$dispute_info .= "\r\n".'dispute_id : '.$value['dispute_reference_id'];
				}	
				
			}	
		}
		echo 'cronMoveActiveDisputesToClosedDisputesTable executed successfully (running every minute). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Automatically closed disputes: '.$counter.'. '.$dispute_info;
		
	}	

	// This method is used to save data in invoice tracking table for users
	public function cron_check_and_save_data_in_users_invoices_tracking_table() {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cnt = 0;
		$info = '';
		$users = $this->db->get('users')->result_array();
		foreach($users as $user) {
			$validation_day = date('d', strtotime($user['account_validation_date']));
			$ndate = date('Y-m-'.$validation_day, strtotime(date('Y-m-d')));
			$ctime = strtotime(date('Y-m-d'));
			if($ctime == strtotime($ndate)) {
				$start_date = date('Y-m-d', strtotime('-1 month'));
				$end_date = date('Y-m-d', strtotime('-1 day', strtotime($ndate)));

				$this->db->select('SUM(charged_service_fee_value) as total, SUM(charged_service_fee_value_excl_vat) as excluded_vat_amount, SUM(charged_service_fee_vat_amount_value) as vat_amount');
				$this->db->from('projects_charged_service_fees_tracking');
				$this->db->where('project_owner_id', $user['user_id']);
				$this->db->where('DATE(escrow_payment_release_date) >= ', $start_date);
				$this->db->where('DATE(escrow_payment_release_date) <= ', $end_date);
				$service_fees = $this->db->get()->row_array();

				$this->db->select('SUM(admin_dispute_arbitration_amount_fee) as total, SUM(admin_dispute_arbitration_amount_vat_value) as vat_amount');
				$this->db->from('projects_disputes_admin_arbitration_fees_tracking');
				$this->db->where('project_owner_id_of_disputed_project', $user['user_id']);
				$this->db->where('DATE(dispute_negotiation_end_date) >= ', $start_date);
				$this->db->where('DATE(dispute_negotiation_end_date) <= ', $end_date);
				$dispute_service_fees = $this->db->get()->row_array();

				$this->db->select('SUM(project_upgrade_purchase_value) as total, SUM(project_upgrade_purchase_value_excl_vat) as excluded_vat_amount, SUM(project_upgrade_vat_amount_value) as vat_amount');
				$this->db->from('projects_upgrades_purchases_tracking');
				$this->db->where('project_owner_id', $user['user_id']);
				$this->db->where('project_upgrade_purchase_source', 'real_money');
				$this->db->where('DATE(project_upgrade_purchase_date) >= ', $start_date);
				$this->db->where('DATE(project_upgrade_purchase_date) <= ', $end_date);
				$project_upgrade = $this->db->get()->row_array();

				$this->db->select('SUM(project_upgrade_purchase_value) as total, SUM(project_upgrade_purchase_value_excl_vat) as excluded_vat_amount, SUM(project_upgrade_vat_amount_value) as vat_amount');
				$this->db->from('projects_upgrades_purchases_tracking');
				$this->db->where('project_owner_id', $user['user_id']);
				$this->db->where('project_upgrade_purchase_source', 'bonus_based');
				$this->db->where('DATE(project_upgrade_purchase_date) >= ', $start_date);
				$this->db->where('DATE(project_upgrade_purchase_date) <= ', $end_date);
				$bonus_based_project_upgrade = $this->db->get()->row_array();
				
				$this->db->select('SUM(project_upgrade_purchase_value) as total, SUM(project_upgrade_purchase_value_excl_vat) as excluded_vat_amount, SUM(project_upgrade_vat_amount_value) as vat_amount');
				$this->db->from('projects_upgrades_purchases_tracking');
				$this->db->where('project_owner_id', $user['user_id']);
				$this->db->where('project_upgrade_purchase_source', 'membership_included');
				$this->db->where('DATE(project_upgrade_purchase_date) >= ', $start_date);
				$this->db->where('DATE(project_upgrade_purchase_date) <= ', $end_date);
				$membership_project_upgrade = $this->db->get()->row_array();
				
				$this->db->select('SUM(total_transaction_charged_fee) as total');
				$this->db->from('users_funds_paypal_deposits_transactions_history');
				$this->db->where('user_id', $user['user_id']);
				$this->db->where('DATE(transaction_date) >= ', $start_date);
				$this->db->where('DATE(transaction_date) <= ', $end_date);
				$deposit_funds_via_paypal = $this->db->get()->row_array();

				$this->db->select('SUM(business_transaction_charged_fee) as total');
				$this->db->from('users_funds_deposits_via_payment_processor_transactions');
				$this->db->where('user_id', $user['user_id']);
				$this->db->where('DATE(transaction_completion_date) >= ', $start_date);
				$this->db->where('DATE(transaction_completion_date) <= ', $end_date);
				$this->db->where_in('status_code', [2,6]);
				$deposit_funds_via_payment_processor = $this->db->get()->row_array();

				$invoice_detail = $this->db->get_where('users_invoices_tracking', ['user_id' => $user['user_id'], 'DATE(invoice_generation_date)' => date('Y-m-d')])->row_array();
				if(empty($invoice_detail)) {
					
					$total = $vat_excluded_amt = $vat_amt =  0;
					$btotal = $bvat_excluded_amt = $bvat_amt =  0;
					$mtotal = $mvat_excluded_amt = $mvat_amt =  0;
					$depvat_excluded_amt = $depvat_amt = 0;
					$deppvat_excluded_amt = $deppvat_amt = 0;


					$vat_percentage = $this->config->item('vat_percentage');

					if(!empty($deposit_funds_via_payment_processor) && is_numeric($deposit_funds_via_payment_processor['total'])) {
						$total += $deposit_funds_via_payment_processor['total'];

						$deppvat_excluded_amt = ($deposit_funds_via_payment_processor['total'] * 100) / ($vat_percentage + 100);
						$vat_excluded_amt += $deppvat_excluded_amt;

						$deppvat_amt = $deposit_funds_via_payment_processor['total'] - $deppvat_excluded_amt;
						$vat_amt += $deppvat_amt;
					}

					if(!empty($deposit_funds_via_paypal) && is_numeric($deposit_funds_via_paypal['total'])) {
						$total += $deposit_funds_via_paypal['total'];

						$depvat_excluded_amt = ($deposit_funds_via_paypal['total'] * 100) / ($vat_percentage + 100);
						$vat_excluded_amt += $depvat_excluded_amt;

						$depvat_amt = $deposit_funds_via_paypal['total'] - $depvat_excluded_amt;
						$vat_amt += $depvat_amt;
					}
					
					if(!empty($service_fees) && is_numeric($service_fees['excluded_vat_amount'])) {
						$vat_excluded_amt += $service_fees['excluded_vat_amount'];
					}
					if(!empty($service_fees) && is_numeric($service_fees['vat_amount'])) {
						$vat_amt += $service_fees['vat_amount'];
					}

					if(!empty($service_fees) && is_numeric($service_fees['total'])) {
						$total += $service_fees['total'];
					}

					
					if(!empty($dispute_service_fees)) {
						$vat_excluded_amt += ($dispute_service_fees['total'] - $dispute_service_fees['vat_amount']);
					}
					if(!empty($dispute_service_fees) && is_numeric($dispute_service_fees['vat_amount'])) {
						$vat_amt += $dispute_service_fees['vat_amount'];
					}

					if(!empty($dispute_service_fees) && is_numeric($dispute_service_fees['total'])) {
						$total += $dispute_service_fees['total'];
					}

					if(!empty($project_upgrade) && is_numeric($project_upgrade['excluded_vat_amount'])) {
						$vat_excluded_amt += $project_upgrade['excluded_vat_amount'];
					}
					if(!empty($project_upgrade) && is_numeric($project_upgrade['vat_amount'])) {
						$vat_amt += $project_upgrade['vat_amount'];
					}

					if(!empty($project_upgrade) && is_numeric($project_upgrade['total'])) {
						$total += $project_upgrade['total'];
					}
					
					if(!empty($bonus_based_project_upgrade) && is_numeric($bonus_based_project_upgrade['excluded_vat_amount'])) {
						$bvat_excluded_amt += $bonus_based_project_upgrade['excluded_vat_amount'];
					}
					if(!empty($bonus_based_project_upgrade) && is_numeric($bonus_based_project_upgrade['vat_amount'])) {
						$bvat_amt += $bonus_based_project_upgrade['vat_amount'];
					}
					if(!empty($bonus_based_project_upgrade) && is_numeric($bonus_based_project_upgrade['total'])) {
						$btotal += $bonus_based_project_upgrade['total'];
					}

					if(!empty($project_upgrade) && is_numeric($membership_project_upgrade['excluded_vat_amount'])) {
						$mvat_excluded_amt += $membership_project_upgrade['excluded_vat_amount'];
					}
					if(!empty($membership_project_upgrade) && is_numeric($membership_project_upgrade['vat_amount'])) {
						$mvat_amt += $membership_project_upgrade['vat_amount'];
					}
					if(!empty($membership_project_upgrade) && is_numeric($membership_project_upgrade['total'])) {
						$mtotal += $membership_project_upgrade['total'];
					}

					if($total != 0) {
						$reference_id = INVOICE_REFERENCE_ID_PREFIX;
					} else if(($btotal != 0 || $mtotal != 0) && $total == 0) {
						$reference_id = USER_ACTIVITY_NULL_INVOICE_REFERENCE_ID_PREFIX;
					} else if($btotal == 0 && $mtotal == 0 && $total == 0) {
						$reference_id = USER_NO_ACTIVITY_NULL_INVOICE_REFERENCE_ID_PREFIX;
					}
					$last_invoice = $this->db->from('users_invoices_tracking')->where('invoice_reference_id REGEXP', '^'.$reference_id. '[0-9]')->order_by('id','desc')->limit(1)->get()->row_array();

					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($last_invoice)) {
						$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($reference_id, '', $last_invoice['invoice_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = (int)$exclude_inital_number + 1;
						$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					
					$invoice_data = [
						'user_id' => $user['user_id'],
						'invoice_generation_date' => date('Y-m-d H:i:s'),
						'invoice_reference_id' => $reference_id,
						'invoice_total_bonuses_amount_excl_vat' => $bvat_excluded_amt,
						'invoice_total_bonuses_vat_amount' => $bvat_amt,
						'invoice_total_bonuses_amount' => $btotal,
						'invoice_total_membership_included_amount_excl_vat' => $mvat_excluded_amt,
						'invoice_total_membership_included_vat_amount' => $mvat_amt,
						'invoice_total_membership_included_amount' => $mtotal,
						'invoice_total_deposit_funds_paypal_charge_amount_excl_vat' => $depvat_excluded_amt,
						'invoice_total_deposit_funds_paypal_charge_vat_amount' => $depvat_amt,
						'invoice_total_deposit_funds_pay_processor_charge_amount_excl_vat' => $deppvat_excluded_amt,
						'invoice_total_deposit_funds_pay_processor_charge_vat_amount' => $deppvat_amt,
						'invoice_total_amount_excl_vat' => $vat_excluded_amt,
						'invoice_total_vat_amount' => $vat_amt,
						'invoice_total_amount' => $total
					];
					
					$this->db->insert('users_invoices_tracking', $invoice_data);
					$info .= "\r\n".'user_id : '.$user['user_id'].' | invoice amount : '.$total;
					$cnt++;
				}

			} 
		}
		echo 'cronCheckAndSaveDataInUsersInvoicesTrackingTable executed successfully (running every day 00.00 CET). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Generated invoices: '.$cnt.' '.$info;
	}
	
	/**
	 * This method is used to check user headline and description details and if exist then insert data from all related table which will be used in search functionality for find professional page
	 * users -> first_name, last_name, company_name, profile_name
	 * users_profile_base_information -> headline, description
	 * users_company_accounts_base_information -> company_vision, company_core_values, company_mission, company_strategy_goals
	 * users_skills_tracking -> user_skill
	 * users_services_provided_tracking -> service_provided
	 * users_personal_accounts_work_experience -> position_name, position_description
	 * users_personal_accounts_education_training -> education_diploma_degree_name, education_school_name, education_comments
	 * users_certifications -> certification_name
	 * users_portfolios -> title, description
	 * users_portfolios_tags -> portfolio_tag_name
	*/
	public function cron_check_update_and_save_data_in_find_professionals_users_information_table() {
		if($this->check_authroization_key()) {
			return;
		}
		$cron_start_date = date('d.m.Y H:i:s');
		$cnt = 0;
		$info = '';
		$this->db->select('u.user_id,u.account_type,u.first_name,u.last_name,u.company_name, ubi.description,ubi.headline, u.is_authorized_physical_person');
		$this->db->from('users u');
		$this->db->join('users_profile_base_information ubi', 'ubi.user_id = u.user_id', 'left');
		$this->db->where("IFNULL(ubi.description, '') != ''");
		$this->db->where("IFNULL(ubi.headline, '') != ''");
		$users = $this->db->get()->result_array();
		if(!empty($users)) {
			foreach($users as $user) {
				$tmp = [];
				array_push($tmp, $user['headline']);
				array_push($tmp, $user['description']);
				// users_skills_tracking
				$user_skills = $this->db->get_where('users_skills_tracking', ['user_id' => $user['user_id']])->result_array();
				if(!empty($user_skills)) {
					foreach(array_column($user_skills, 'user_skill') as $val) {
						array_push($tmp, $val);
					}
				}

				// users_services_provided_tracking
				$user_services_provided = $this->db->get_where('users_services_provided_tracking', ['user_id' => $user['user_id']])->result_array();
				if(!empty($user_services_provided)) {
					foreach(array_column($user_services_provided, 'service_provided') as $val) {
						array_push($tmp, $val);
					}
				}

				// users_personal_accounts_work_experience
				$user_work_expirience = $this->db->get_where('users_personal_accounts_work_experience', ['user_id' => $user['user_id']])->result_array();
				if(!empty($user_work_expirience)) {
					foreach(array_column($user_work_expirience, 'position_name') as $val) {
						array_push($tmp, $val);
					}
					foreach(array_column($user_work_expirience, 'position_description') as $val) {
						array_push($tmp, $val);
					}
				}

				// users_personal_accounts_education_training
				$user_education_training = $this->db->get_where('users_personal_accounts_education_training', ['user_id' => $user['user_id']])->result_array();
				if(!empty($user_education_training)) {
					foreach(array_column($user_education_training, 'education_diploma_degree_name') as $val) {
						array_push($tmp, $val);
					}

					foreach(array_column($user_education_training, 'education_school_name') as $val) {
						array_push($tmp, $val);
					}

					foreach(array_column($user_education_training, 'education_comments') as $val) {
						array_push($tmp, $val);
					}
				}

				// users_certifications
				$user_certificates = $this->db->get_where('users_certifications', ['user_id' => $user['user_id']])->result_array();
				if(!empty($user_certificates)) {
					foreach(array_column($user_certificates, 'certification_name') as $val) {
						array_push($tmp, $val);
					}
				}

				// users_company_accounts_base_information
				if($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
					$user_company_base_info = $this->db->get_where('users_company_accounts_base_information', ['user_id' => $user['user_id']])->row_array();
					if(!empty($user_company_base_info)) {
						array_push($tmp, $user_company_base_info['company_vision']);
						array_push($tmp, $user_company_base_info['company_core_values']);
						array_push($tmp, $user_company_base_info['company_mission']);
						array_push($tmp, $user_company_base_info['company_strategy_goals']);
					}
				}

				// users_portfolios
				$user_portfolios = $this->db->get_where('users_portfolios', ['user_id' => $user['user_id']])->result_array();
				if(!empty($user_portfolios)) {
					
					foreach(array_column($user_portfolios, 'title') as $val) {
						array_push($tmp, $val);
					}

					foreach(array_column($user_portfolios, 'description') as $val) {
						array_push($tmp, $val);
					}
				}

				// users_portfolios_tags
				$this->db->select('upt.portfolio_tag_name');
				$this->db->from('users_portfolios up');
				$this->db->join('users_portfolios_tags upt', 'upt.portfolio_id = up.portfolio_id');
				$this->db->where('user_id', $user['user_id']);
				$user_portfolio_tags = $this->db->get()->result_array();
				if(!empty($user_portfolio_tags)) {
					foreach(array_column($user_portfolio_tags, 'portfolio_tag_name') as $val) {
						array_push($tmp, $val);
					}
				}

				$user_information = [];
				if($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE) { 
					if($user['is_authorized_physical_person'] == 'Y') {
							$user_information = [
								'user_first_name' => $user['first_name'],
								'user_last_name' => $user['last_name']
							];
					} else {
						$user_information = [
							'company_name' => $user['company_name']
						];
					}
					
				} else {
					$user_information = [
						'user_first_name' => $user['first_name'],
						'user_last_name' => $user['last_name']
					];
				}
				$user_information['description'] = implode(' ', $tmp);
				$user_info_cnt = $this->db->from('find_professionals_users_information')->where('user_id', $user['user_id'])->count_all_results();
				if($user_info_cnt == 0) {
					$user_information['user_id'] = $user['user_id'];
					$this->db->insert('find_professionals_users_information', $user_information);
				} else {
					$this->db->update('find_professionals_users_information', $user_information, ['user_id' => $user['user_id']]);
				}

				$info .= "\r\n".'user_id : '.$user['user_id'];
				$cnt++;
			}
		}
		echo 'cronCheckUpdateAndSaveDataInFindProfessionalsUsersInformationTable executed successfully  (running every 8 hours). Cron job started at '.$cron_start_date.' and ended at '.date('d.m.Y H:i:s').'. Updated users information: '.$cnt.' '.$info;
	}
}
?>