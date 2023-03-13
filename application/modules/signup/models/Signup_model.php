<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Signup_model extends BaseModel
{

    public function __construct ()
    {	
        return parent::__construct ();
    }

    public function save_user_signup_data ()
    {
			$this->load->helper ('date');
			$i = 0;
			$first_name = $this->input->post ('first_name');
			$last_name = $this->input->post ('last_name');
			$profile_name = $this->input->post ('profile_name');
			$email = $this->input->post ('email');
			$gender = $this->input->post ('current_user');
			$password = $this->input->post ('password');

			$account_type = $this->input->post ('account_type');
			$company_name = $this->input->post ('company_name');
			// @sid code to check referral_code is valid or not and if it is valid then get associate user id to store in pending verification
			$referral_code = '';
			$referrer_id = '';
			$referee_source = '';
			if(!empty($this->input->post('referral_part_1')) && !empty($this->input->post('referral_part_2')) && !empty($this->input->post('referral_part_3'))) {
				$referral_code = $this->input->post('referral_part_1').'-'.$this->input->post('referral_part_2').'-'.$this->input->post('referral_part_3');
				$result = $this->get_user_by_referral_code($referral_code);
				if(!empty($result)) {
					$referrer_id = $result['user_id'];
					$referee_source = 'user_referral_code_direct_usage';
				}
			} else if($this->input->cookie('referral_code',true)) {
				$code = json_decode(base64_decode(urldecode($this->input->cookie('referral_code'))), true);
				$result = [];
				
				if(isset($code['source']) && $code['source'] != 'user_self_url_share_email') {
					if(isset($code['code'])) {
						$referral_code = $code['code'];
						$result = $this->get_user_by_referral_code($referral_code);
					}
					if(!empty($result)) {
						$referrer_id = $result['user_id'];
						if(isset($code['source'])) {
							$referee_source = $code['source'];
						}
					}
				} else {
					$pending_invitations = $this->db->get_where('invited_friends_invitations_pending_acceptance', ['id' => $code['invite_frnd_id']])->row_array();
					if(!empty($pending_invitations)) {
						if(isset($code['code'])) {
							$referral_code = $code['code'];
							$result = $this->get_user_by_referral_code($referral_code);
						}
						if(!empty($result)) {
							$referrer_id = $result['user_id'];
							if(isset($code['source'])) {
								$referee_source = $code['source'];
							}
						}
					}
				}
				if($_COOKIE['referral_code']) {
					delete_cookie('referral_code');
				}
			}
			$code = $this->generate_verification_code ();
			
			$name = (($account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($account_type == USER_COMPANY_ACCOUNT_TYPE && !empty($this->input->post ('company_sub_account_type')) && $this->input->post ('company_sub_account_type') == 1)) ? $this->input->post ('first_name') . ' ' . $this->input->post ('last_name') : $company_name;
			
			
			$current_date = date('Y-m-d H:i:s');
			
			
			############ calculation for next request reminder time for unverfied user start ########
			$next_automatic_verification_code_generate_time = (strtotime($current_date) + ($this->config->item('signup_automatic_send_reminder_to_unverified_user_set_interval')*60));
			$next_automatic_verification_code_generate_time = date('Y-m-d H:i:s',$next_automatic_verification_code_generate_time);
			############ calculation for next request reminder time for unverfied user end ########
			
			############ calculation for unverified user expiration date start here ########
			$account_expiration_date = (strtotime($current_date) + ($this->config->item('signup_unverified_user_remove_set_interval')*60));
			$account_expiration_date = date('Y-m-d H:i:s',$account_expiration_date);
			############ calculation for unverified user expiration date end here ########
			
			############ account verification code expire time start ############
			$current_verification_code_expiration_date = (strtotime($current_date) + ($this->config->item('signup_verified_code_expire_set_interval')*60));
			############ account verification code expire time end ############
			$company_sub_account_type = 'N';
			if ($account_type == USER_PERSONAL_ACCOUNT_TYPE)
			{
					$data = [
						'registration_source_ip' => $_SERVER['REMOTE_ADDR'],
						'account_type' => $account_type,
						'first_name' => $this->input->post ('first_name'),
						'last_name' => $this->input->post ('last_name'),
						'gender' => $gender,
						'profile_name' => strtolower($this->input->post ('profile_name')),
						'email' => $this->input->post ('email'),
						'password' => md5 ($password),
						'verification_code' => $code,
						'account_registration_date' => $current_date,
						'automatic_verification_code_generate_time' => $current_date,
						'next_automatic_verification_code_generate_time' => $next_automatic_verification_code_generate_time,
						'account_expiration_date' => $account_expiration_date,
						'current_verification_code_expiration_date'=>date('Y-m-d H:i:s',$current_verification_code_expiration_date)
					];
			}
			else
			{
					$data = [
						'registration_source_ip' => $_SERVER['REMOTE_ADDR'],
						'account_type' => $account_type,	
						'profile_name' => strtolower($this->input->post ('profile_name')),
						'email' => $this->input->post ('email'),
						'password' => md5 ($password),
						'verification_code' => $code,
						'account_registration_date' => $current_date,
						'automatic_verification_code_generate_time' => $current_date,
						'next_automatic_verification_code_generate_time' => $next_automatic_verification_code_generate_time,
						'account_expiration_date' => $account_expiration_date,
						'current_verification_code_expiration_date'=>date('Y-m-d H:i:s',$current_verification_code_expiration_date)
					];
					if(!empty($this->input->post ('company_sub_account_type')) && $this->input->post ('company_sub_account_type') == 1){
						$data['is_authorized_physical_person'] = 'Y';
						$data['first_name'] = trim($this->input->post ('first_name'));
						$data['last_name'] = trim($this->input->post ('last_name'));
						$data['gender'] = $gender;
						$company_sub_account_type = 'Y';
					}else{		
						$data['company_name'] = trim($company_name);	
					}
	
			}
			
			if(!empty($referral_code)) {
				$data['referrer_code'] = $referral_code;
			}
			if(!empty($referrer_id)) {
				$data['referrer_id'] = $referrer_id;
			}
			if(!empty($referee_source)) {
				$data['referee_source'] = $referee_source;
			}
			
			// $this->db->insert ("users_new_registrations_pending_verification", $data);
			
			$count_unverify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('email', $email)
			->get ()->num_rows ();
			if($count_unverify_user_record == 0)
			{
			
				if ($this->db->insert ('users_new_registrations_pending_verification', $data))
				{	
					$user_id = $this->db->insert_id ();
		
					$msg['email'] = $email;
					$msg['user_id'] = $user_id;
					$msg['name'] = $name;
					$msg['code'] = $code;
					$msg['account_type'] = $account_type;
					$msg['gender'] = $gender;
					$msg['company_sub_account_type'] = $company_sub_account_type;
					$msg['status'] = 'SUCCESS';
					
				}
			}	
			return $msg;
    }
   
    public function generate_verification_code ()
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < 8; $i ++)
        {
            $str .= substr ($pool, mt_rand (0, strlen ($pool) - 1), 1);
        }
        
        $str = random_string('alnum', 12);
        
        return $str;
    }

	public function check_valid_unique_profile_name ($profilename)
	{	
		$count_user_record = $this->db
			->select ('user_id')
			->from ('users')
			->where ('profile_name', $profilename)
			->get ()->num_rows ();
		/* check already exist profile_name in users_new_registrations_pending_verification table */
		$count_verify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('profile_name', $profilename)
			->get ()->num_rows ();
		if ($profilename == '' || strlen ($profilename) < 4 || ! preg_match ('/^[0-9a-zA-Z\-\_\.]+$/', $profilename))
		{
			return ['status' => false, 'profile_name' => htmlspecialchars($profilename, ENT_QUOTES)];
		}
		else if ($count_user_record > 0 || $count_verify_user_record > 0)
		{
			return ['status' => false, 'profile_name' => htmlspecialchars($profilename, ENT_QUOTES)];
		}
		else
		{
			return ['status' => true, 'profile_name' => htmlspecialchars($profilename, ENT_QUOTES)];
		}
	}
	

	public function check_unique_email ($email)
    {
        $this->db->where ("email", $email);
        $en = $this->db->count_all_results ('users');
		
		$count_user_record = $this->db
			->select ('user_id')
			->from ('users')
			->where ('email', $email)
			->get ()->num_rows ();
		/* check already exist email in users_new_registrations_pending_verification table */	
		$count_verify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('email', $email)
			->get ()->num_rows ();
		
        if ($count_user_record > 0 || $count_verify_user_record > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
	/*
	 * @sid
	 * Get user id from referral code
	 * Used in signup.php controller post register method.
	*/
	public function get_user_by_referral_code($code) {
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('referral_code', $code);
		return $this->db->get()->row_array();
	}
	/*
	 * @sid
	 * Get pending invitation detail by email id
	 * Used in signup.php controller post verfied method.
	*/
	public function get_pending_invitation_detail_by_email($email) {
		$this->db->where('invitee_email_address', $email);
		return $this->db->get('invited_friends_invitations_pending_acceptance')->row_array();
	}
	/*
	 * @sid
	 * Insert record into invited friend register table
	 * Used in signup.php controller post verfied method.
	*/
	public function invited_friends_registered($data) {
		if($this->db->insert('invited_friends_registered', $data)) {
			return $this->db->insert_id();
		}
		return 0;
	}
	/*
	 * @sid
	 * Remove pending invitation entry by id
	 * Used in signup.php controller post verfied method.
	*/
	public function remove_pending_invitation_by_id($id) {
		$this->db->where('id', $id);
		$this->db->delete('invited_friends_invitations_pending_acceptance');
	}
	/*
	 * @sid Get default membership plan details
	 * Called in signup controller verified method
	*/
	public function get_default_membership_plan() {
		$this->db->where('default_plan', 'Yes');
		return $this->db->get('membership_plans')->row_array();
	}
	
	
	public function update_automatic_verification_code_generate_time($user_id){
		$user_record =  $this->db->select ('user_id,email,first_name,last_name,company_name,account_type,gender,automatic_verification_code_generate_time, 	next_automatic_verification_code_generate_time,account_registration_date,account_expiration_date,is_authorized_physical_person')->where ('user_id', $user_id)->get ('users_new_registrations_pending_verification')->row_array ();
		if(!empty($user_record)){
			
			
			if(strtotime($user_record['automatic_verification_code_generate_time']) < time() && strtotime($user_record['next_automatic_verification_code_generate_time']) < time()){
				if(strtotime($user_record['account_expiration_date']) > time()){
				
				
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
					
					$name = (($user_record['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_record['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_record['is_authorized_physical_person'] =='Y')) ? $user_record['first_name'] . ' ' . $user_record['last_name'] : $user_record['company_name'];

				
					$signup_verification_code_expiration_set_interval = $this->config->item('signup_verification_code_expiration_set_interval')*60;
					$new_code_expire_time  = $signup_verification_code_expiration_set_interval + strtotime($current_date);
					//$hours_left = ($new_code_expire_time/3600);
					$account_expiration_time_left = convert_seconds_to_time($user_record['account_expiration_date']);
					$account_registration_date = date(DATE_FORMAT,strtotime($user_record['account_registration_date']));
					$account_registration_time =  date(TIME_FORMAT,strtotime($user_record['account_registration_date']));
					$to = $email;
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
				}
			}
		}
	}
	
	
	
}
?>