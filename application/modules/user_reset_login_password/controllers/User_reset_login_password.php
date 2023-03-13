<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class User_reset_login_password extends MX_Controller
{

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    public function __construct ()
    {
        $this->load->model ('User_reset_login_password_model');
		$this->load->model ('signin/Signin_model');
        //$this->load->model ('membership/Membership_model');
        parent::__construct ();
    }

	/**
     * Description: this is used for show the recover password page for enter the email for recover the password
     * Paremeter: null
     */
    public function reset_login_password ()
    {
        if ($this->session->userdata ('user'))
        {
            redirect(VPATH.$this->config->item('signin_page_url'));
        }
        else
        {
			
			$lay = array();

			########## set the forgot password title meta tag and meta description  start here #########
			$forgot_password_page_title_meta_tag = $this->config->item('forgot_password_page_title_meta_tag');

			$forgot_password_page_description_meta_tag = $this->config->item('forgot_password_page_description_meta_tag');
			$data['meta_tag'] = '<title>' . $forgot_password_page_title_meta_tag . '</title><meta name="description" content="' . $forgot_password_page_description_meta_tag . '"/>';
            ########## set the forgot password title meta tag and meta description  end here #########
			
			if (empty($this->input->get($this->config->item('forgot_password_page_success_parameter'))) || empty($this->input->get($this->config->item('reset_password_page_token_parameter'))))
            {	
				$data['current_page'] = 'reset_login_password';
				$this->layout->view ('reset_login_password', $lay, $data, 'normal');
			}else{
				
				$token = Cryptor::doDecrypt($this->input->get($this->config->item('reset_password_page_token_parameter')));
				$email = Cryptor::doDecrypt($this->input->get($this->config->item('forgot_password_page_success_parameter')));
				$count_verify_user_record = $this->db
				->select ('user_id')
				->from ('users')
				->where ('email', $email)
				->get ()->num_rows ();
			/* check already exist email in users_new_registrations_pending_verification table */	
				$count_unverify_user_record = $this->db
				->select ('user_id')
				->from ('users_new_registrations_pending_verification')
				->where ('email', $email)
				->get ()->num_rows ();
				
				if ($count_unverify_user_record > 0 || $count_verify_user_record > 0){
				
					if($count_unverify_user_record > 0)
					{
					
						$this->db->select ('email,password_reset_token,password_reset_token_request_time,account_type,first_name,company_name,is_authorized_physical_person');
						$this->db->where ('email', $email);
						$query = $this->db->get ('users_new_registrations_pending_verification');
						$result = $query->result ();
					}
					if($count_verify_user_record > 0)
					{
						$this->db->select ('*');
						$this->db->where ('email', $email);
						$query = $this->db->get ('users');
						$result = $query->result ();
					}
					if (empty($result[0]->password_reset_token))
					{
					   $this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
						redirect(VPATH.$this->config->item('reset_login_password_page_url'));
					}
					else if ($result[0]->password_reset_token == $token)
					{
						
						$current_time = time();
						$forgot_password_token_request_time_set_interval = $this->config->item('forgot_password_token_request_time_set_interval')*60;
						
						$password_request_date = $result[0]->password_reset_token_request_time != NULL ? strtotime (date ('Y-m-d H:i', strtotime ($result[0]->password_reset_token_request_time))) : 0;
						
						$password_token_expire_time = $password_request_date+$forgot_password_token_request_time_set_interval;
						
						if($current_time < $password_token_expire_time)
						{
							$data['current_page'] = 'reset_password';
							$data['email'] = $this->input->get($this->config->item('forgot_password_page_success_parameter'));
							

							$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name : $result[0]-> company_name;
							
							$this->layout->view ('reset_password', $lay, $data, 'normal'); 
						}else{
							 $this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
							redirect(VPATH.$this->config->item('reset_login_password_page_url'));
						}
					}else{
						
						$this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
						redirect(VPATH.$this->config->item('reset_login_password_page_url'));
					}
				}else{
					$this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
					redirect(VPATH.$this->config->item('reset_login_password_page_url'));
				}
			
			}
        }
    }

	/**
     * Description: this is used when user clicks the button(Reset Password) on recover password screen. Its executed through ajax call
     * Paremeter: null
     */
	public function recover_password_ajax ()
    {
		if(!$this->input->is_ajax_request()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		
		if ($this->session->userdata ('user'))
        {
			
			$user = $this->session->userdata ('user');
			$user_id = $user[0]->user_id;
			
			$result = $this->db->get_where('users_new_registrations_pending_verification', ['user_id' => $user_id])->row_array();
			if(!empty($result) && strtotime($result['account_expiration_date']) < time()){
				$this->Signin_model->remove_unverified_user($result);
			}
			
            $msg['status'] = 'ERROR';
			$msg['location'] = VPATH . $this->config->item('signin_page_url');
			echo json_encode ($msg);die;
        }
	
	
        //$this->load->helper ('date');
        //$i = 0;
         $email = $this->input->post ('email');
		 
		 $count_verify_user_record = $this->db
			->select ('user_id')
			->from ('users')
			->where ('email', $email)
			->get ()->num_rows ();
		/* check already exist email in users_new_registrations_pending_verification table */	
			$count_unverify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('email', $email)
			->get ()->num_rows ();
			
			if ($count_unverify_user_record > 0 || $count_verify_user_record > 0){
				
				if($count_unverify_user_record  > 0) // for those users exists in "users_new_registrations_pending_verification" table
				{
					$this->db->where ('email', $email);
					$query = $this->db->get ('users_new_registrations_pending_verification');
					$result = $query->result ();
					
					$now = time();
					$next_available_password_reset_token_request_time = $result[0]-> 	next_available_password_reset_token_request_time != NULL ? strtotime ($result[0]-> 	next_available_password_reset_token_request_time) : 0;
					
					if ($now  < $next_available_password_reset_token_request_time)
					{
						$msg['status'] = 'OK';
						$msg['location'] = VPATH . $this->config->item('forgot_password_send_reset_confirmation_page_url').'?'. $this->config->item('forgot_password_page_success_parameter').'='.Cryptor::doEncrypt($result[0]->email);
					}else{
					
						$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name : $result[0]->company_name;
						
						############ calculation for next forgot password request  time for unverfied user start ########
						$current_date = date("Y-m-d H:i:s");
						$next_available_password_reset_token_request_time = (strtotime($current_date) + ($this->config->item('forgot_password_token_request_time_set_interval')*60));
						$next_available_password_reset_token_request_time = date('Y-m-d H:i:s',$next_available_password_reset_token_request_time);
						############ calculation for next request reminder time for unverfied user end ########
						$token = random_string ('alnum', 12);
						$this->db->where ('user_id', $result[0]->user_id);
						$request = array (
							'password_reset_token' => $token,
							'password_reset_token_request_time' =>$current_date,
							'next_available_password_reset_token_request_time' =>$next_available_password_reset_token_request_time,
							'reset_password_request_source_ip'=>$_SERVER["REMOTE_ADDR"]
						);
						$this->db->update ('users_new_registrations_pending_verification', $request);
						$reset_password_request_link = SITE_URL . $this->config->item('reset_login_password_page_url').'?'.$this->config->item('forgot_password_page_success_parameter')."=". Cryptor::doEncrypt($email)."&".$this->config->item('reset_password_page_token_parameter')."=".Cryptor::doEncrypt($token);
						
						
						$forgot_password_token_expire_set_interval = $this->config->item('forgot_password_token_expire_set_interval');
						$forgot_password_token_expire_set_interval = $forgot_password_token_expire_set_interval/60;
						if($forgot_password_token_expire_set_interval > 1){
						
							 $reset_password_link_expire_time = $forgot_password_token_expire_set_interval." hodiny";
						}else{
							$reset_password_link_expire_time =  $forgot_password_token_expire_set_interval." hodina";
						}
						
						$this->load->library ('email');
						$to = $email;
						$reset_password_request_time = date(DATE_TIME_FORMAT_EXCLUDE_SECOND,time());
						$reset_password_request_source_ip = $_SERVER["REMOTE_ADDR"];
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
							if($result[0]->gender == 'M')
							{
								// Email config variables for male unverified user
								$cc = $this->config->item('email_cc_unverified_user_forgot_password_personal_male');
								$bcc = $this->config->item('email_bcc_unverified_user_forgot_password_personal_male');
								$from = $this->config->item('email_from_unverified_user_forgot_password_personal_male');
								$reply_to = $this->config->item('email_reply_to_unverified_user_forgot_password_personal_male');
								$from_name = $this->config->item('email_from_name_unverified_user_forgot_password_personal_male');
								$subject = $this->config->item('email_subject_unverified_user_forgot_password_personal_male');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_unverified_user_forgot_password_personal_male');
								$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
							}else{
								// Email config variables for female unverfied user
								$cc = $this->config->item('email_cc_unverified_user_forgot_password_personal_female');
								$bcc = $this->config->item('email_bcc_unverified_user_forgot_password_personal_female');
								$from = $this->config->item('email_from_unverified_user_forgot_password_personal_female');
								$reply_to = $this->config->item('email_reply_to_unverified_user_forgot_password_personal_female');
								$from_name = $this->config->item('email_from_name_unverified_user_forgot_password_personal_female');
								$subject = $this->config->item('email_subject_unverified_user_forgot_password_personal_female');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_unverified_user_forgot_password_personal_female');
								$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
							
							}
						
						
						}else{
						
							
								if($result[0]->is_authorized_physical_person == 'Y'){
									if($result[0]->gender == 'M')
									{
										// Email config variables for male unverified user
										$cc = $this->config->item('email_cc_unverified_user_forgot_password_company_app_male');
										$bcc = $this->config->item('email_bcc_unverified_user_forgot_password_company_app_male');
										$from = $this->config->item('email_from_unverified_user_forgot_password_company_app_male');
										$reply_to = $this->config->item('email_reply_to_unverified_user_forgot_password_company_app_male');
										$from_name = $this->config->item('email_from_name_unverified_user_forgot_password_company_app_male');
										$subject = $this->config->item('email_subject_unverified_user_forgot_password_company_app_male');
										$subject =  str_replace(array('{name}'),array($name),$subject);
										$message = $this->config->item('email_message_unverified_user_forgot_password_company_app_male');
										$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
									}else{
										// Email config variables for female unverfied user
										$cc = $this->config->item('email_cc_unverified_user_forgot_password_company_app_female');
										$bcc = $this->config->item('email_bcc_unverified_user_forgot_password_company_app_female');
										$from = $this->config->item('email_from_unverified_user_forgot_password_company_app_female');
										$reply_to = $this->config->item('email_reply_to_unverified_user_forgot_password_company_app_female');
										$from_name = $this->config->item('email_from_name_unverified_user_forgot_password_company_app_female');
										$subject = $this->config->item('email_subject_unverified_user_forgot_password_company_app_female');
										$subject =  str_replace(array('{name}'),array($name),$subject);
										$message = $this->config->item('email_message_unverified_user_forgot_password_company_app_female');
										$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
									
									}
								
								}else{
									// Email config variables for company unverfied user
									$cc = $this->config->item('email_cc_unverified_user_forgot_password_company');
									$bcc = $this->config->item('email_bcc_unverified_user_forgot_password_company');
									$from = $this->config->item('email_from_unverified_user_forgot_password_company');
									$reply_to = $this->config->item('email_reply_to_unverified_user_forgot_password_company');
									$from_name = $this->config->item('email_from_name_unverified_user_forgot_passwor_company');
									$subject = $this->config->item('email_subject_unverified_user_forgot_password_company');
									$subject =  str_replace(array('{company_name}'),array($name),$subject);
									$message = $this->config->item('email_message_unverified_user_forgot_password_company');
									$message = str_replace(array('{company_name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
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
						$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
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
						$this->email->send ();
							
						$msg['status'] = 'OK';
						$msg['location'] = VPATH . $this->config->item('forgot_password_send_reset_confirmation_page_url').'?'. $this->config->item('forgot_password_page_success_parameter').'='.Cryptor::doEncrypt($result[0]->email);
					}	
				
				}elseif( $count_verify_user_record > 0){ // for those users exists in "users" table
					
					$this->db->where ('email', $email);
					$query = $this->db->get ('users');
					$result = $query->result ();
					$now = time();
					$next_available_password_reset_token_request_time = $result[0]-> 	next_available_password_reset_token_request_time != NULL ? strtotime ($result[0]-> 	next_available_password_reset_token_request_time) : 0;
					
					if ($now  < $next_available_password_reset_token_request_time)
					{
						$msg['status'] = 'OK';
						$msg['location'] = VPATH . $this->config->item('forgot_password_send_reset_confirmation_page_url').'?'. $this->config->item('forgot_password_page_success_parameter').'='.Cryptor::doEncrypt($result[0]->email);
					}else{
					
						//$name = $result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $result[0]->first_name  : $result[0]->company_name;
						$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name : $result[0]->company_name;
						
						############ calculation for next forgot password request  time for unverfied user start ########
						$current_date = date("Y-m-d H:i:s");
						$next_available_password_reset_token_request_time = (strtotime($current_date) + ($this->config->item('forgot_password_token_request_time_set_interval')*60));
						$next_available_password_reset_token_request_time = date('Y-m-d H:i:s',$next_available_password_reset_token_request_time);
						############ calculation for next request reminder time for unverfied user end ########
						
						$this->db->where ('user_id', $result[0]->user_id);
						$token = random_string ('alnum', 12);
						$request = array (
							'password_reset_token' => $token,
							'password_reset_token_request_time' =>$current_date,
							'next_available_password_reset_token_request_time' =>$next_available_password_reset_token_request_time,
							'reset_password_request_source_ip'=>$_SERVER["REMOTE_ADDR"]
						);
						$this->db->update ('users', $request);
						
						$reset_password_request_link = SITE_URL . $this->config->item('reset_login_password_page_url').'?'.$this->config->item('forgot_password_page_success_parameter')."=". Cryptor::doEncrypt($email)."&".$this->config->item('reset_password_page_token_parameter')."=".Cryptor::doEncrypt($token);
						
						$forgot_password_token_expire_set_interval = $this->config->item('forgot_password_token_expire_set_interval');
						$forgot_password_token_expire_set_interval = $forgot_password_token_expire_set_interval/60;
						if($forgot_password_token_expire_set_interval > 1){
						
							 $reset_password_link_expire_time = $forgot_password_token_expire_set_interval." hodiny";
						}else{
							$reset_password_link_expire_time =  $forgot_password_token_expire_set_interval." hodina";
						}
						
						$this->load->library ('email');
						$to = $email;
						$reset_password_request_time = date(DATE_TIME_FORMAT_EXCLUDE_SECOND,time());
						$reset_password_request_source_ip = $_SERVER["REMOTE_ADDR"];
						if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
						{
						
							if($result[0]->gender == 'M')
							{
								// Email config variables for male unverfied user
								$cc = $this->config->item('email_cc_verified_user_forgot_password_personal_male');
								$bcc = $this->config->item('email_bcc_verified_user_forgot_password_personal_male');
								$from = $this->config->item('email_from_verified_user_forgot_password_personal_male');
								$reply_to = $this->config->item('email_reply_to_verified_user_forgot_password_personal_male');
								$from_name = $this->config->item('email_from_name_verified_user_forgot_password_personal_male');
								$subject = $this->config->item('email_subject_verified_user_forgot_password_personal_male');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_verified_user_forgot_password_personal_male');
								$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
								
							
							}else{
								// Email config variables for female unverfied user
								$cc = $this->config->item('email_cc_verified_user_forgot_password_personal_female');
								$bcc = $this->config->item('email_bcc_verified_user_forgot_password_personal_female');
								$from = $this->config->item('email_from_verified_user_forgot_password_personal_female');
								$reply_to = $this->config->item('email_reply_to_verified_user_forgot_password_personal_female');
								$from_name = $this->config->item('email_from_name_verified_user_forgot_password_personal_female');
								$subject = $this->config->item('email_subject_verified_user_forgot_password_personal_female');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_verified_user_forgot_password_personal_female');
								$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
							}
						
						}else{	
							if($result[0]->is_authorized_physical_person == 'Y'){
							
								if($result[0]->gender == 'M')
								{
									// Email config variables for male unverfied user
									$cc = $this->config->item('email_cc_verified_user_forgot_password_company_app_male');
									$bcc = $this->config->item('email_bcc_verified_user_forgot_password_company_app_male');
									$from = $this->config->item('email_from_verified_user_forgot_password_company_app_male');
									$reply_to = $this->config->item('email_reply_to_verified_user_forgot_password_company_app_male');
									$from_name = $this->config->item('email_from_name_verified_user_forgot_password_company_app_male');
									$subject = $this->config->item('email_subject_verified_user_forgot_password_company_app_male');
									$subject =  str_replace(array('{name}'),array($name),$subject);
									$message = $this->config->item('email_message_verified_user_forgot_password_company_app_male');
									$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
								
								}else{
									// Email config variables for female unverfied user
									$cc = $this->config->item('email_cc_verified_user_forgot_password_company_app_female');
									$bcc = $this->config->item('email_bcc_verified_user_forgot_password_company_app_female');
									$from = $this->config->item('email_from_verified_user_forgot_password_company_app_female');
									$reply_to = $this->config->item('email_reply_to_verified_user_forgot_password_company_app_female');
									$from_name = $this->config->item('email_from_name_verified_user_forgot_password_company_app_female');
									$subject = $this->config->item('email_subject_verified_user_forgot_password_company_app_female');
									$subject =  str_replace(array('{name}'),array($name),$subject);
									$message = $this->config->item('email_message_verified_user_forgot_password_company_app_female');
									$message = str_replace(array('{name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
								}
							
							
							}else{
								// Email config variables for company unverfied user
								$cc = $this->config->item('email_cc_verified_user_forgot_password_company');
								$bcc = $this->config->item('email_bcc_verified_user_forgot_password_company');
								$from = $this->config->item('email_from_verified_user_forgot_password_company');
								$reply_to = $this->config->item('email_reply_to_verified_user_forgot_password_company');
								$from_name = $this->config->item('email_from_name_verified_user_forgot_password_company');
								$subject = $this->config->item('email_subject_verified_user_forgot_password_company');
								$subject =  str_replace(array('{company_name}'),array($name),$subject);
								$message = $this->config->item('email_message_verified_user_forgot_password_company');
								$message = str_replace(array('{company_name}','{reset_password_request_link}','{reset_password_link_expire_time}','{reset_password_request_time}','{reset_password_request_source_ip}'),array($name,$reset_password_request_link,$reset_password_link_expire_time,$reset_password_request_time,$reset_password_request_source_ip),$message);
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
						$this->email->send ();
						$msg['status'] = 'OK';
						$msg['location'] = VPATH . $this->config->item('forgot_password_send_reset_confirmation_page_url').'?'. $this->config->item('forgot_password_page_success_parameter').'='.Cryptor::doEncrypt($result[0]->email);
					}	
				}
			}


        unset ($_POST);
        echo json_encode ($msg);
    }
	
	/**
     * Description: this is used for show the reset password page to user for enter his new password
     * Paremeter: null
     */
    public function reset_password ()
    {
		
        if ($this->session->userdata ('user'))
        {
             redirect(VPATH.$this->config->item('signin_page_url'));
        }
        else
        {
			
			if (empty($this->input->get($this->config->item('forgot_password_page_success_parameter'))) || empty($this->input->get($this->config->item('reset_password_page_token_parameter'))))
            {
              $this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
			  redirect(VPATH.$this->config->item('reset_login_password_page_url'));
			 
            }
			$token = Cryptor::doDecrypt($this->input->get($this->config->item('reset_password_page_token_parameter')));
			$email = Cryptor::doDecrypt($this->input->get($this->config->item('forgot_password_page_success_parameter')));
			
			$count_verify_user_record = $this->db
			->select ('user_id')
			->from ('users')
			->where ('email', $email)
			->get ()->num_rows ();
		/* check already exist email in users_new_registrations_pending_verification table */	
			$count_unverify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('email', $email)
			->get ()->num_rows ();
			
			if ($count_unverify_user_record > 0 || $count_verify_user_record > 0){
			
				if($count_unverify_user_record > 0)
				{
				
					$this->db->select ('email,password_reset_token,password_reset_token_request_time,account_type,first_name,company_name,is_authorized_physical_person');
					$this->db->where ('email', $email);
					$query = $this->db->get ('users_new_registrations_pending_verification');
					$result = $query->result ();
				}
				if($count_verify_user_record > 0)
				{
					$this->db->select ('*');
					$this->db->where ('email', $email);
					$query = $this->db->get ('users');
					$result = $query->result ();
				}
				if (empty($result[0]->password_reset_token))
				{
				   $this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
					redirect(VPATH.$this->config->item('reset_login_password_page_url'));
				}
				else if ($result[0]->password_reset_token == $token)
				{
					
					$current_time = time();
					$forgot_password_token_request_time_set_interval = $this->config->item('forgot_password_token_request_time_set_interval')*60;
					
					$password_request_date = $result[0]->password_reset_token_request_time != NULL ? strtotime (date ('Y-m-d H:i', strtotime ($result[0]->password_reset_token_request_time))) : 0;
					
					$password_token_expire_time = $password_request_date+$forgot_password_token_request_time_set_interval;
					
					if($current_time < $password_token_expire_time)
					{
						$data['current_page'] = 'reset_password';
						$data['email'] = $this->input->get($this->config->item('forgot_password_page_success_parameter'));
						$lay = array();

						$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name : $result[0]-> company_name;
						
						########## set the reset password page title meta tag and meta description  start here #########
						$reset_password_page_title_meta_tag = $this->config->item('reset_password_page_title_meta_tag');
						
						$reset_password_page_title_meta_tag = str_replace(array('{user_first_name_or_company_name}'),array($name),$reset_password_page_title_meta_tag);
						$reset_password_page_description_meta_tag = $this->config->item('reset_password_page_description_meta_tag');

						$data['meta_tag'] = '<title>' . $reset_password_page_title_meta_tag . '</title><meta name="description" content="' . $reset_password_page_description_meta_tag . '"/>';
						########## set the reset password page title meta tag and meta description  end here #########
						$this->layout->view ('reset_password', $lay, $data, 'normal'); 
					}else{
						 $this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
						redirect(VPATH.$this->config->item('reset_login_password_page_url'));
					}
				}else{
					
					$this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
					redirect(VPATH.$this->config->item('reset_login_password_page_url'));
				}
			}else{
				$this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
				redirect(VPATH.$this->config->item('reset_login_password_page_url'));
			}
        }
    }

	/**
     * Description: this is method is executed when user rset his password.It executed through ajax call.
     * Paremeter: null
     */	
    public function reset_password_ajax ()
    {
		if(!$this->input->is_ajax_request()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if ($this->session->userdata ('user'))
        {
			
            $msg['status'] = 'ERROR';
			$msg['location'] = VPATH . $this->config->item('signin_page_url');
			echo json_encode ($msg);die;
        }
        if ($this->input->post ())
        {
           $post_data = $this->input->post ();
		   $password = $post_data['password'];
		   $email = $post_data['email'];
		   $email = Cryptor::doDecrypt($email);
		   
		   	 $count_verify_user_record = $this->db
			->select ('user_id')
			->from ('users')
			->where ('email', $email)
			->get ()->num_rows ();
		/* check already exist email in users_new_registrations_pending_verification table */	
			$count_unverify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('email', $email)
			->get ()->num_rows ();
			
			if ($count_verify_user_record > 0 || $count_unverify_user_record > 0){
			
			
				if( $count_unverify_user_record > 0){
					$successful_reset_password_time = date(DATE_TIME_FORMAT_EXCLUDE_SECOND,time());
					$successful_reset_password_source_ip = $_SERVER["REMOTE_ADDR"];
					
					$new_password_data['password'] = md5 ($password);
					$new_password_data['password_reset_token'] = NULL;
					$new_password_data['last_password_reset_time'] = date('Y-m-d H:i:s');
					$new_password_data['successful_reset_password_source_ip'] = $successful_reset_password_source_ip;
					$this->db->where ('email', $email);
					$query = $this->db->update ('users_new_registrations_pending_verification', $new_password_data);
					########### fetch the information of user from database for sending the email start ##
					$this->db->select('first_name,company_name,account_type,gender,is_authorized_physical_person');
					$this->db->where ('email', $email);
					$query = $this->db->get ('users_new_registrations_pending_verification');
					$result = $query->result ();
					########### fetch the information of user from database for sending the email end ##
					$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name  : $result[0]->company_name;
					
					
					$this->load->library ('email');
					$to = $email;
					if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
					{
					
						if($result[0]->gender == 'M')
							{
								// Email config variables for male unverfied user
								$cc = $this->config->item('email_cc_unverified_user_successful_reset_password_confirmation_personal_male');
								$bcc = $this->config->item('email_bcc_unverified_user_successful_reset_password_confirmation_personal_male');
								$from = $this->config->item('email_from_unverified_user_successful_reset_password_confirmation_personal_male');
								$reply_to = $this->config->item('email_reply_to_unverified_user_successful_reset_password_confirmation_personal_male');
								$from_name = $this->config->item('email_from_name_unverified_user_successful_reset_password_confirmation_personal_male');
								$subject = $this->config->item('email_subject_unverified_user_successful_reset_password_confirmation_personal_male');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_unverified_user_successful_reset_password_confirmation_personal_male');
								$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
							
							}else{
								// Email config variables for female unverfied user
								$cc = $this->config->item('email_cc_unverified_user_successful_reset_password_confirmation_personal_female');
								$bcc = $this->config->item('email_bcc_unverified_user_successful_reset_password_confirmation_personal_female');
								$from = $this->config->item('email_from_unverified_user_successful_reset_password_confirmation_personal_female');
								$reply_to = $this->config->item('email_reply_to_unverified_user_successful_reset_password_confirmation_personal_female');
								$from_name = $this->config->item('email_from_name_unverified_user_successful_reset_password_confirmation_personal_female');
								$subject = $this->config->item('email_subject_unverified_user_successful_reset_password_confirmation_personal_female');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_unverified_user_successful_reset_password_confirmation_personal_female');
								$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
							}
					
					
					
					}else{
					
						if($result[0]->is_authorized_physical_person == 'Y'){
							if($result[0]->gender == 'M')
							{
								// Email config variables for male unverfied user
								$cc = $this->config->item('email_cc_unverified_user_successful_reset_password_confirmation_company_app_male');
								$bcc = $this->config->item('email_bcc_unverified_user_successful_reset_password_confirmation_company_app_male');
								$from = $this->config->item('email_from_unverified_user_successful_reset_password_confirmation_company_app_male');
								$reply_to = $this->config->item('email_reply_to_unverified_user_successful_reset_password_confirmation_company_app_male');
								$from_name = $this->config->item('email_from_name_unverified_user_successful_reset_password_confirmation_company_app_male');
								$subject = $this->config->item('email_subject_unverified_user_successful_reset_password_confirmation_company_app_male');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_unverified_user_successful_reset_password_confirmation_company_app_male');
								$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
							
							}else{
								// Email config variables for female unverfied user
								$cc = $this->config->item('email_cc_unverified_user_successful_reset_password_confirmation_company_app_female');
								$bcc = $this->config->item('email_bcc_unverified_user_successful_reset_password_confirmation_company_app_female');
								$from = $this->config->item('email_from_unverified_user_successful_reset_password_confirmation_company_app_female');
								$reply_to = $this->config->item('email_reply_to_unverified_user_successful_reset_password_confirmation_company_app_female');
								$from_name = $this->config->item('email_from_name_unverified_user_successful_reset_password_confirmation_company_app_female');
								$subject = $this->config->item('email_subject_unverified_user_successful_reset_password_confirmation_company_app_female');
								$subject =  str_replace(array('{name}'),array($name),$subject);
								$message = $this->config->item('email_message_unverified_user_successful_reset_password_confirmation_company_app_female');
								$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
							}
						}else{
							// Email config variables for company unverfied user
							$cc = $this->config->item('email_cc_unverified_user_successful_reset_password_confirmation_company');
							$bcc = $this->config->item('email_bcc_unverified_user_successful_reset_password_confirmation_company');
							$from = $this->config->item('email_from_unverified_user_successful_reset_password_confirmation_company');
							$reply_to = $this->config->item('email_reply_to_unverified_user_successful_reset_password_confirmation_company');
							$from_name = $this->config->item('email_from_name_unverified_user_successful_reset_password_confirmation_company');
							$subject = $this->config->item('email_subject_unverified_user_successful_reset_password_confirmation_company');
							$message = $this->config->item('email_message_unverified_user_successful_reset_password_confirmation_company');
							$message = str_replace(array('{company_name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
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
					$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
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
					$this->email->send ();
					
					
				}elseif($count_verify_user_record > 0){
					$successful_reset_password_time = date(DATE_TIME_FORMAT_EXCLUDE_SECOND,time());
					$successful_reset_password_source_ip = $_SERVER["REMOTE_ADDR"];
					$new_password_data['password'] = md5 ($password);
					$new_password_data['password_reset_token'] = NULL;
					$new_password_data['last_password_reset_time'] = date('Y-m-d H:i:s');
					$new_password_data['successful_reset_password_source_ip'] = $successful_reset_password_source_ip;
					$this->db->where ('email', $email);
					$query = $this->db->update ('users', $new_password_data);
					
					
					
					$user_record = $this->db // get the user detail
					->select('u.account_type,u.email,u.user_id')
					->from('users u')
					->where('u.email', $email)
					->get()->row_array();
					
					$reset_login_password_activity_log_displayed_message = $this->config->item('reset_login_password_email_user_activity_log_displayed_message');
					user_display_log($reset_login_password_activity_log_displayed_message,$user_record['user_id']); 
					
					
					########### fetch the information of user from database for sending the email start ##
					$this->db->select('first_name,company_name,account_type,gender,is_authorized_physical_person');
					$this->db->where ('email', $email);
					$query = $this->db->get ('users');
					$result = $query->result ();
					########### fetch the information of user from database for sending the email end ##
					//$name = $result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $result[0]->first_name  : $result[0]->company_name;
					
					$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name  : $result[0]->company_name;
					
					$this->load->library ('email');
					
					if($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
					{
						if($result[0]->gender == 'M')
						{
							// Email config variables for male verfied user
							$cc = $this->config->item('email_cc_verified_user_successful_reset_password_confirmation_personal_male');
							$bcc = $this->config->item('email_bcc_verified_user_successful_reset_password_confirmation_personal_male');
							$from = $this->config->item('email_from_verified_user_successful_reset_password_confirmation_personal_male');
							$reply_to = $this->config->item('email_reply_to_verified_user_successful_reset_password_confirmation_personal_male');
							$from_name = $this->config->item('email_from_name_verified_user_successful_reset_password_confirmation_personal_male');
							$to = $email;
							$subject = $this->config->item('email_subject_verified_user_successful_reset_password_confirmation_personal_male');
							$message = $this->config->item('email_message_verified_user_successful_reset_password_confirmation_personal_male');
							$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
							
						}else{
							// Email config variables for female verfied user
							$cc = $this->config->item('email_cc_verified_user_successful_reset_password_confirmation_personal_female');
							$bcc = $this->config->item('email_bcc_verified_user_successful_reset_password_confirmation_personal_female');
							$from = $this->config->item('email_from_verified_user_successful_reset_password_confirmation_personal_female');
							$reply_to = $this->config->item('email_reply_to_verified_user_successful_reset_password_confirmation_personal_female');
							$from_name = $this->config->item('email_from_name_verified_user_successful_reset_password_confirmation_personal_female');
							$to = $email;
							$subject = $this->config->item('email_subject_verified_user_successful_reset_password_confirmation_personal_female');
							$message = $this->config->item('email_message_verified_user_successful_reset_password_confirmation_personal_female');
							$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
						}
					}else{	
						if($result[0]->is_authorized_physical_person == 'Y'){
						
							if($result[0]->gender == 'M')
							{
								// Email config variables for male verfied user
								$cc = $this->config->item('email_cc_verified_user_successful_reset_password_confirmation_company_app_male');
								$bcc = $this->config->item('email_bcc_verified_user_successful_reset_password_confirmation_company_app_male');
								$from = $this->config->item('email_from_verified_user_successful_reset_password_confirmation_company_app_male');
								$reply_to = $this->config->item('email_reply_to_verified_user_successful_reset_password_confirmation_company_app_male');
								$from_name = $this->config->item('email_from_name_verified_user_successful_reset_password_confirmation_company_app_male');
								$to = $email;
								$subject = $this->config->item('email_subject_verified_user_successful_reset_password_confirmation_company_app_male');
								$message = $this->config->item('email_message_verified_user_successful_reset_password_confirmation_company_app_male');
								$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
								
							}else{
								// Email config variables for female verfied user
								$cc = $this->config->item('email_cc_verified_user_successful_reset_password_confirmation_company_app_female');
								$bcc = $this->config->item('email_bcc_verified_user_successful_reset_password_confirmation_company_app_female');
								$from = $this->config->item('email_from_verified_user_successful_reset_password_confirmation_company_app_female');
								$reply_to = $this->config->item('email_reply_to_verified_user_successful_reset_password_confirmation_company_app_female');
								$from_name = $this->config->item('email_from_name_verified_user_successful_reset_password_confirmation_company_app_female');
								$to = $email;
								$subject = $this->config->item('email_subject_verified_user_successful_reset_password_confirmation_company_app_female');
								$message = $this->config->item('email_message_verified_user_successful_reset_password_confirmation_company_app_female');
								$message = str_replace(array('{name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
							}
						
						
						
						}else{
							$cc = $this->config->item('email_cc_verified_user_successful_reset_password_confirmation_company');
							$bcc = $this->config->item('email_bcc_verified_user_successful_reset_password_confirmation_company');
							$from = $this->config->item('email_from_verified_user_successful_reset_password_confirmation_company');
							$reply_to = $this->config->item('email_reply_to_verified_user_successful_reset_password_confirmation_company');
							$from_name = $this->config->item('email_from_name_verified_user_successful_reset_password_confirmation_company');
							$to = $email;
							$subject = $this->config->item('email_subject_verified_user_successful_reset_password_confirmation_company');
							$message = $this->config->item('email_message_verified_user_successful_reset_password_confirmation_company');
							$message = str_replace(array('{company_name}','{successful_reset_password_time}','{successful_reset_password_source_ip}'),array($name,$successful_reset_password_time,$successful_reset_password_source_ip),$message);
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
					$this->email->send ();
					
				
				}
				$msg['status'] = 'OK';
                $msg['location'] = VPATH . $this->config->item('forgot_password_successful_password_reset_page_url').'?'.$this->config->item('forgot_password_page_success_parameter')."=". Cryptor::doEncrypt($email);
                $msg['message'] = 'Password Reset Successfully. Please <a href="' . VPATH . '/login"><b>Log In</b></a> To Continue.';
				$this->session->set_flashdata ('password_changed', 'succes');
			}else {
               
                $msg['errors']['message'] = 'failed! Database Error Occured. Please Try Again.';
            }
			unset ($_POST);
			echo json_encode ($msg);
        }
    }

	/**
     * Description:this method is executed after successfully reset the password
     * Paremeter: null
     */	
    public function successfull_password_reset ()
    {
			
			if ($this->session->userdata ('user'))
			{
				 redirect(VPATH.$this->config->item('signin_page_url'));
			}else if ($this->session->flashdata ('password_changed') == 'succes'){
		
				$email = Cryptor::doDecrypt($this->input->get($this->config->item('forgot_password_page_success_parameter')));
		  
				$count_verify_user_record = $this->db
				->select ('user_id')
				->from ('users')
				->where ('email', $email)
				->get ()->num_rows ();
			/* check already exist email in users_new_registrations_pending_verification table */	
				$count_unverify_user_record = $this->db
				->select ('user_id')
				->from ('users_new_registrations_pending_verification')
				->where ('email', $email)
				->get ()->num_rows ();
				if ($count_verify_user_record > 0 || $count_unverify_user_record > 0){
					if($count_unverify_user_record > 0){
						$this->db->select('account_type,first_name,company_name,is_authorized_physical_person');
						$this->db->where ('email', $email);
						$query = $this->db->get ('users_new_registrations_pending_verification');
						$result = $query->result ();
					
					
					}elseif($count_verify_user_record > 0){
						$this->db->select('account_type,first_name,company_name,is_authorized_physical_person');
						$this->db->where ('email', $email);
						$query = $this->db->get ('users');
						$result = $query->result ();
					}
					//$name = $result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $result[0]->first_name : $result[0]-> company_name;
					
					
					$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name : $result[0]-> company_name;
					
					
					$lay = array();
					$data['name'] = $name;
					$data['current_page'] = 'successfull_password_reset';
					########## reset the successfull password title meta tag and meta description  start here #########
					$successful_password_reset_page_title_meta_tag = $this->config->item('successful_password_reset_page_title_meta_tag');
					
					$successful_password_reset_page_title_meta_tag = str_replace(array('{user_first_name_or_company_name}'),array($name),$successful_password_reset_page_title_meta_tag);
					$successful_password_reset_page_description_meta_tag = $this->config->item('successful_password_reset_page_description_meta_tag');

					$data['meta_tag'] = '<title>' . $successful_password_reset_page_title_meta_tag . '</title><meta name="description" content="' . $successful_password_reset_page_description_meta_tag . '"/>';
					########## reset the successfull password title meta tag and meta description  end here #########
					$this->layout->view ('successfull_password_reset', $lay, $data, 'normal');  
				}else{
					$this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
					redirect(VPATH.$this->config->item('reset_login_password_page_url'));
				}
			}else{
				$this->session->set_flashdata('error', $this->config->item('password_reset_invalid_url_validation_message'));
				redirect(VPATH.$this->config->item('reset_login_password_page_url'));
			}
    }

   
	/**
     * Description:this method is executed after user enter the email in forgot password page.Its is the confirmation of user forgot password request
     * Paremeter: null
     */	
    public function send_password_reset_confirmation ()
    {
		
			$email = Cryptor::doDecrypt($this->input->get($this->config->item('forgot_password_page_success_parameter')));
			if(!empty($email))
			{
				$count_verify_user_record = $this->db
				->select ('user_id')
				->from ('users')
				->where ('email', $email)
				->get ()->num_rows ();
				/* check already exist email in users_new_registrations_pending_verification table */	
				$count_unverify_user_record = $this->db
				->select ('user_id')
				->from ('users_new_registrations_pending_verification')
				->where ('email', $email)
				->get ()->num_rows ();
				
				if ($count_unverify_user_record > 0 || $count_verify_user_record > 0){
					if($count_unverify_user_record > 0){
					
						$this->db->where ('email', $email);
						$query = $this->db->get ('users_new_registrations_pending_verification');
						$result = $query->result ();
					
					
					}elseif($count_verify_user_record > 0){
					
						$this->db->where ('email', $email);
						$query = $this->db->get ('users');
						$result = $query->result ();
					}
					
					######### check the request time for password reset start######
					$now = time();
					$next_available_password_reset_token_request_time = $result[0]-> 	next_available_password_reset_token_request_time != NULL ? strtotime ($result[0]-> 	next_available_password_reset_token_request_time) : 0;
					$data['expired'] = 'no';
					if ($now  < $next_available_password_reset_token_request_time)
					{
						$data['expired'] = 'yes';
					}
					######### check the request time for password reset end######
					
					
					$data['password_reset_token_request_time'] = $result[0]->password_reset_token_request_time != NULL ? strtotime ($result[0]->password_reset_token_request_time) : 0;
					 $data['next_available_password_reset_token_request_time'] = $result[0]->next_available_password_reset_token_request_time != NULL ? strtotime ($result[0]->next_available_password_reset_token_request_time) : 0;
					
					$data['uid'] = $result[0]->user_id;
					$data['user_email'] = $result[0]->email;
					//$data['expired'] = $time_password > 6 ? 'YES' : 'NO';
				   
					//$name = $result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $result[0]->first_name : $result[0]-> company_name;
					$name = (($result[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($result[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $result[0]->is_authorized_physical_person == 'Y')) ? $result[0]->first_name : $result[0]-> company_name;
					
					
					$data['name'] = $name;
					$email = Cryptor::doDecrypt($this->input->get($this->config->item('forgot_password_page_success_parameter')));
					$forgot_password_page_url = $this->config->item('reset_login_password_page_url');
					$data['forgot_password_page_url'] = VPATH.$forgot_password_page_url;
					
					########## set the password  reset confrimation title meta tag and meta description  start here #########
					$forgot_password_reset_confirmation_page_title_meta_tag = $this->config->item('forgot_password_reset_confirmation_page_title_meta_tag');
					
					$forgot_password_reset_confirmation_page_title_meta_tag = str_replace(array('{user_first_name_or_company_name}','{email}'),array($name,$email),$forgot_password_reset_confirmation_page_title_meta_tag);
					$forgot_password_reset_confirmation_page_description_meta_tag = $this->config->item('forgot_password_reset_confirmation_page_description_meta_tag');

					$data['meta_tag'] = '<title>' . $forgot_password_reset_confirmation_page_title_meta_tag . '</title><meta name="description" content="' . $forgot_password_reset_confirmation_page_description_meta_tag . '"/>';
					########## set the password  reset confrimation title meta tag and meta description  end here #########
					$data['current_page'] = 'send_password_reset_confirmation';
					$lay = array();
					$this->layout->view ('send_password_reset_confirmation', $lay, $data, 'normal');  
				
				}else{
					$this->session->set_flashdata('error', $this->config->item('password_reset_link_expired_validation_message'));
					redirect(VPATH.$this->config->item('reset_login_password_page_url'));
				}
			}else{
					$this->session->set_flashdata('error', $this->config->item('password_reset_invalid_url_validation_message'));
					redirect(VPATH.$this->config->item('reset_login_password_page_url'));
			}
		
    }
	
	// This function check the email exists or not into the database during the time of enter email in forgot password form.
	public function checkEmailExist ()
    {
		if(!$this->input->is_ajax_request()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
        $email = $_POST['email'];
        $results = $this->User_reset_login_password_model->check_email_exist ($email);
        if($results)
		{	
			$msg['check'] = '1';
			echo json_encode ($msg);die;
		}else{
			$msg['check'] = '0';
			echo json_encode ($msg);die;
		} 
    }
	
	

}
?>