<?php


if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Signup extends MX_Controller
{

	/**
	 * Description: this used for check the user is exsts or not if exists then it redirect to this site
	 * Paremete: username and password
	 */
	public function __construct ()
	{
		$this->load->model ('Signup_model');
		$this->load->model ('signin/Signin_model');
		 $this->load->model('user/User_model');
		$this->load->library ('form_validation');
		$this->load->library('email');
		$this->load->helper('ci_helper');

		parent::__construct ();
	
	}

	public function user_signup ()
	{	
		if(validate_session()) {
			redirect (VPATH . $this->config->item('dashboard_page_url'));
		}
		if(!empty($_GET["rfrd"])) {
			$rfrd = json_decode(base64_decode($_GET["rfrd"]), true);
			$array = array ('referral_code' => $rfrd['code']);
			$this->user_data = $this->autoload_model->get_table_row ('users', $array);
			if(empty($this->user_data)) {
				redirect($this->config->item('signup_page_url'));
			}
			$this->user_first_name = $this->user_data->first_name;
			$this->user_last_name = $this->user_data->last_name;	
			if($rfrd['source'] == 'user_self_url_share_fb') {
				$share_description = $this->config->item('user_referral_url_facebook_share_message');
				$share_short_title = $this->config->item('user_referral_url_facebook_share_title_short');
			} else if($rfrd['source'] == 'user_self_url_share_ln') {
				$share_description = $this->config->item('user_referral_url_linkedin_share_message');
				$share_short_title = $this->config->item('user_referral_url_linkedin_share_title_short');
			}
		
			$_SESSION['share_description'] = $share_description;
			$this->user_email = $this->user_data->email;		
			$_SESSION['share_title_short'] = $share_short_title;
			$_SESSION['share_url'] = URL.$this->config->item('referrer_page_url').'?rfrd='.$_GET["rfrd"];
			if($rfrd['source'] == 'user_self_url_share_fb') {
				$_SESSION['share_image'] = URL.$this->config->item('facebook_share_image_path').'?'.time();
			} else if ($rfrd['source'] == 'user_self_url_share_ln' && $this->config->item('linkedin_share_image_preview_status')) {
				$_SESSION['share_image'] = URL.$this->config->item('linkedin_share_image_path').'?'.time();
			}
			$_SESSION['share_image_height'] = $this->config->item('facebook_share_image_height');
			$_SESSION['share_image_width'] = $this->config->item('facebook_share_image_width');
			$cookie= array(
			'name'   => 'referral_code',
			'value'  => $_GET["rfrd"],
			'expire' => '3600',
			'path' => '/',
			'httponly' => false
			);
			$this->input->set_cookie($cookie);
			$signup_title_meta_tag = $this->config->item('signup_page_title_meta_tag');
			$signup_description_meta_tag = $this->config->item('signup_page_description_meta_tag');
		} else {
			$signup_title_meta_tag = $this->config->item('signup_page_title_meta_tag');
			$signup_description_meta_tag = $this->config->item('signup_page_description_meta_tag');

			$_SESSION['share_title_short'] = $signup_title_meta_tag;
			$_SESSION['share_description'] = $signup_description_meta_tag;
		}
		########## set the signup title meta tag and meta description  start here #########
		$data['meta_tag'] = '<title>' . $signup_title_meta_tag . '</title><meta name="description" content="' . $signup_description_meta_tag . '"/>';
		$data['current_page'] = 'signup';
		
		########## set the dashboard title tag start end #########
		$this->layout->view ('signup', '', $data, 'normal');
	}
	
	 //checking and save when user register his account
	public function save_user_signup_data ()
    {
		if(!$this->input->is_ajax_request()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
        if ($this->input->post ())
        {
            $post_data = $this->input->post ();
            $insert_data = $this->Signup_model->save_user_signup_data ($post_data);
			
			
			if($insert_data['status'] == "SUCCESS"){
				//die("dgddgdg");
				$user_id = $insert_data['user_id'];
				$name = $insert_data['name'];
				$code = $insert_data['code'];
				$email = $insert_data['email']; 
				$signup_verification_url = SITE_URL . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=". Cryptor::doEncrypt($user_id)."&".$this->config->item('signup_page_code_parameter')."=".Cryptor::doEncrypt($code);
				$account_expiration_time = $this->config->item('signup_unverified_user_remove_set_interval');
				$account_expiration_time = $account_expiration_time/60;
				/* if($account_expiration_time > 1){
					$account_expiration_time = $account_expiration_time." hodiny";
				}else{
					$account_expiration_time = $account_expiration_time." hodina";
				} */
				
				if($account_expiration_time == 1){
					$hour = $this->config->item('1_hour');
				}else if($account_expiration_time >=2 && $account_expiration_time <= 4){
					$hour = $this->config->item('2_4_hours');
				}else if($account_expiration_time > 4){
					$hour = $this->config->item('more_than_or_equal_5_hours');
				}
				$account_expiration_time = $account_expiration_time." ".$hour;
                $this->load->library ('email');
				$to = $email;
				if($insert_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE)
				{
					if($insert_data['gender'] == 'M')
					{
						// Email config variables for male user
						$cc = $this->config->item('welcome_email_cc_signup_personal_male');
						$bcc = $this->config->item('welcome_email_bcc_signup_personal_male');
						$from = $this->config->item('welcome_email_from_signup_personal_male');
						$reply_to = $this->config->item('welcome_email_reply_to_signup_personal_male');
						$from_name = $this->config->item('welcome_email_from_name_signup_personal_male');
						$subject = $this->config->item('welcome_email_subject_signup_personal_male');
						$message = $this->config->item('welcome_email_message_signup_personal_male');
						$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}'),array($name,$code,$signup_verification_url,$account_expiration_time),$message);
					
					
					}else{
						// Email config variables for female user
						$cc = $this->config->item('welcome_email_cc_signup_personal_female');
						$bcc = $this->config->item('welcome_email_bcc_signup_personal_female');
						$from = $this->config->item('welcome_email_from_signup_personal_female');
						$reply_to = $this->config->item('welcome_email_reply_to_signup_personal_female');
						$from_name = $this->config->item('welcome_email_from_name_signup_personal_female');
						$subject = $this->config->item('welcome_email_subject_signup_personal_female');
						$message = $this->config->item('welcome_email_message_signup_personal_female');
						$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}'),array($name,$code,$signup_verification_url,$account_expiration_time),$message);
					
					}
				
				}else{
				
					if($insert_data['company_sub_account_type'] == 'Y'){
					
						if($insert_data['gender'] == 'M')
						{
							// Email config variables for company app male user
							$cc = $this->config->item('welcome_email_cc_signup_company_app_male');
							$bcc = $this->config->item('welcome_email_bcc_signup_company_app_male');
							$from = $this->config->item('welcome_email_from_signup_company_app_male');
							$reply_to = $this->config->item('welcome_email_reply_to_signup_company_app_male');
							$from_name = $this->config->item('welcome_email_from_name_signup_company_app_male');
							$subject = $this->config->item('welcome_email_subject_signup_company_app_male');
							$message = $this->config->item('welcome_email_message_signup_company_app_male');
							$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}'),array($name,$code,$signup_verification_url,$account_expiration_time),$message);
						
						
						}else{
							// Email config variables for company app male user
							$cc = $this->config->item('welcome_email_cc_signup_company_app_female');
							$bcc = $this->config->item('welcome_email_bcc_signup_company_app_female');
							$from = $this->config->item('welcome_email_from_signup_company_app_female');
							$reply_to = $this->config->item('welcome_email_reply_to_signup_company_app_female');
							$from_name = $this->config->item('welcome_email_from_name_signup_company_app_female');
							$subject = $this->config->item('welcome_email_subject_signup_company_app_female');
							$message = $this->config->item('welcome_email_message_signup_company_app_female');
							$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}'),array($name,$code,$signup_verification_url,$account_expiration_time),$message);
						}
					
					}else{
				
						// Email config variables for company user
						$cc = $this->config->item('welcome_email_cc_signup_company');
						$bcc = $this->config->item('welcome_email_bcc_signup_company');
						$from = $this->config->item('welcome_email_from_signup_company');
						$reply_to = $this->config->item('welcome_email_reply_to_signup_company');
						$from_name = $this->config->item('welcome_email_from_name_signup_company');
						$subject = $this->config->item('welcome_email_subject_signup_company');
						$message = $this->config->item('welcome_email_message_signup_company');
						$message = str_replace(array('{company_name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}'),array($name,$code,$signup_verification_url,$account_expiration_time),$message);
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
                //below comments for check email debug.
				if(!$this->email->send ()) {
				//if(true) {
                    $msg['uid'] = $user_id;
                    $msg['status'] = 'OK';	
                    $msg['location'] = VPATH . $this->config->item('signup_confirmation_page_url').'?'.$this->config->item('signup_page_success_parameter')."=". Cryptor::doEncrypt($user_id);  
                    $this->session->set_flashdata('registered', TRUE);
                } else {
					$msg['uid'] = $user_id;
					$msg['status'] = 'OK';	
					$msg['location'] = VPATH . $this->config->item('signup_confirmation_page_url').'?'.$this->config->item('signup_page_success_parameter')."=". Cryptor::doEncrypt($user_id);  
					$this->session->set_flashdata('registered', TRUE);
               }
			}else{
				$msg['status'] = $insert_data;
			}
			echo json_encode ($msg);
        }
      
    }
	
	/**
     * Description: this method manage the redirection from signup successful verifcation process to login process
     * 
     */
	public function verify_redirection(){		
	
		
		
		$uid = $this->input->get($this->config->item('signup_page_success_parameter'));
		$user_id = Cryptor::doDecrypt($uid);
		$this->db->where ('user_id', $user_id);
		$query = $this->db->get ('users_new_registrations_pending_verification');
		if($query->num_rows () == 0)
		{
			$this->session->unset_userdata('is_logged');
			$this->session->unset_userdata('is_authorized');
			$this->session->unset_userdata('user');
			$this->session->set_userdata ('user', $results);
			redirect (VPATH . $this->config->item('signin_page_url'));
		}
		
		
		$code = Cryptor::doEncrypt($this->input->get($this->config->item('signup_page_code_parameter')));
		$url = VPATH . $this->config->item('signup_activate_page_url').'?'. $this->config->item('signup_page_success_parameter').'='.$uid.'&'.$this->config->item('signup_page_code_parameter').'='.$code;
		 redirect ($url);
	}
	
	/**
     * Description: this method send manualy the new verification code to user email address.
     * 
     */
	public function send_verification_code_manually_generated_by_user()
	{
		
	
		if ($this->input->is_ajax_request()) {
			
			if (!$this->session->userdata ('user'))
			{
				$response['status'] = 'ERROR';
				$response['location'] = VPATH . $this->config->item('signin_page_url');
				echo json_encode ($response);die;
			}
		
			$user_id = $this->input->post ('uid');
			if(!empty($user_id))
			{
				
				$uid = Cryptor::doDecrypt($user_id);
				$this->db->where ('user_id', $uid);
				$query = $this->db->get ('users_new_registrations_pending_verification');
				
				if($query->num_rows () == 1)
				{
					$this->Signup_model->update_automatic_verification_code_generate_time($uid);
				
					$this->db->where ('user_id', $uid);
					$query = $this->db->get ('users_new_registrations_pending_verification');
				
					$res = $query->result ();
					$account_expiration_date = $res[0]->account_expiration_date != NULL ? strtotime ($res[0]->account_expiration_date) : 0;
					############# check expiration account date of user start ##############
					if(!empty($account_expiration_date) && time() > $account_expiration_date){
						$this->remove_unverified_users_account_expiration($res[0]->user_id); // remove the unverified user account
						$this->session->unset_userdata('is_logged');
						$this->session->unset_userdata('is_authorized');
						$this->session->unset_userdata('user');
						$response['status'] = 'ERROR';
						$response['location'] = VPATH . $this->config->item('signin_page_url');
						echo json_encode ($response);die;
					}
					############# check expiration account date of user e ##############
					//$name = $res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $res[0]->first_name . ' ' . $res[0]->last_name : $res[0]->company_name;
					$name = (($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($res[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $res[0]->is_authorized_physical_person =='Y')) ? $res[0]->first_name . ' ' . $res[0]->last_name : $res[0]->company_name;
					
					
					$verification_code_manual_request_time_set_interval = $this->config->item('signup_verification_code_manual_request_time_set_interval');
					$verification_code_manual_request_time_set_interval = $verification_code_manual_request_time_set_interval*60;
					$code = $this->Signup_model->generate_verification_code();
					$new_mannual_request_time = date ("Y-m-d H:i:s");
					
					############ calculation for next mannual request time for unverfied user start ########
					$next_available_verification_code_manual_request_time = (strtotime($new_mannual_request_time) + ($this->config->item('signup_verification_code_manual_request_time_set_interval')*60));
					$next_available_verification_code_manual_request_time = date('Y-m-d H:i:s',$next_available_verification_code_manual_request_time);
					############ calculation for next mannual request time for unverfied user end ########
					
					
					############ account verification code expire time start ############
					$current_verification_code_expiration_date = (strtotime($new_mannual_request_time) + ($this->config->item('signup_verified_code_expire_set_interval')*60));
					############ account verification code expire time end ############
					
					
					
					$manual_request_code_data = array ('verification_code' => $code, 'verification_code_manual_request_time' => $new_mannual_request_time, 'next_available_verification_code_manual_request_time' => $next_available_verification_code_manual_request_time, 'current_verification_code_expiration_date' => date('Y-m-d H:i:s',$current_verification_code_expiration_date) );
					$this->db->where ('user_id', $uid);
					$this->db->update ('users_new_registrations_pending_verification', $manual_request_code_data);
					
					$url = SITE_URL . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=". $user_id."&".$this->config->item('signup_page_code_parameter')."=".Cryptor::doEncrypt($code);
					$link = $url;
					
					################### send email with new code start ##########
					
					$account_expiration_time_interval = $this->config->item('signup_unverified_user_remove_set_interval');
					$account_expiration_time_interval = $account_expiration_time_interval/60;
					if($account_expiration_time_interval == 1){
						$account_expiration_time_interval = $account_expiration_time_interval." ".$this->config->item('1_hour');
					}else if($account_expiration_time_interval  >=2  && $account_expiration_time_interval <= 4){
						$account_expiration_time_interval = $account_expiration_time_interval." ".$this->config->item('2_4_hours');
					}else if($account_expiration_time_interval  > 4){
						$account_expiration_time_interval = $account_expiration_time_interval." ".$this->config->item('more_than_or_equal_5_hours');
					}
					$account_expiration_time_left = convert_seconds_to_time($res[0]->account_expiration_date);
					$account_registration_date = date(DATE_FORMAT,strtotime($res[0]->account_registration_date));
					$account_registration_time =  date(TIME_FORMAT,strtotime($res[0]->account_registration_date));
						
					$this->load->library ('email');
					$to = $res[0]->email;
					if($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
					{
						// Define email variables for male user
						if($res[0]->gender == 'M')
						{
							$cc = $this->config->item('email_cc_unverified_account_manual_request_verification_code_personal_male');
							$bcc = $this->config->item('email_bcc_unverified_account_manual_request_verification_code_personal_male');
							$from = $this->config->item('email_from_unverified_account_manual_request_verification_code_personal_male');
							$reply_to = $this->config->item('email_reply_to_unverified_account_manual_request_verification_code_personal_male');
							$from_name = $this->config->item('email_from_name_unverified_account_manual_request_verification_code_personal_male');
							$subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_male');
							$message = $this->config->item('email_message_unverified_account_manual_request_verification_code_personal_male');
							$message = str_replace(array('{name}','{activation_code}','{url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
						
						}else{
							// Define email variables for female user
							$cc = $this->config->item('email_cc_unverified_account_manual_request_verification_code_personal_female');
							$bcc = $this->config->item('email_bcc_unverified_account_manual_request_verification_code_personal_female');
							$from = $this->config->item('email_from_unverified_account_manual_request_verification_code_personal_female');
							$reply_to = $this->config->item('email_reply_to_unverified_account_manual_request_verification_code_personal_female');
							$from_name = $this->config->item('email_from_name_unverified_account_manual_request_verification_code_personal_female');
							$subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_personal_female');
							$message = $this->config->item('email_message_unverified_account_manual_request_verification_code_personal_female');
							$message = str_replace(array('{name}','{activation_code}','{url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
						
						}
					
					
					}else{
						if($res[0]->is_authorized_physical_person =='Y'){
						
							// Define email variables for company app male
							if($res[0]->gender == 'M')
							{
								$cc = $this->config->item('email_cc_unverified_account_manual_request_verification_code_company_app_male');
								$bcc = $this->config->item('email_bcc_unverified_account_manual_request_verification_code_company_app_male');
								$from = $this->config->item('email_from_unverified_account_manual_request_verification_code_company_app_male');
								$reply_to = $this->config->item('email_reply_to_unverified_account_manual_request_verification_code_company_app_male');
								$from_name = $this->config->item('email_from_name_unverified_account_manual_request_verification_code_company_app_male');
								$subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_company_app_male');
								$message = $this->config->item('email_message_unverified_account_manual_request_verification_code_company_app_male');
								$message = str_replace(array('{name}','{activation_code}','{url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
							
							}else{
								// Define email variables for company app female
								$cc = $this->config->item('email_cc_unverified_account_manual_request_verification_code_company_app_female');
								$bcc = $this->config->item('email_bcc_unverified_account_manual_request_verification_code_company_app_female');
								$from = $this->config->item('email_from_unverified_account_manual_request_verification_code_company_app_female');
								$reply_to = $this->config->item('email_reply_to_unverified_account_manual_request_verification_code_company_app_female');
								$from_name = $this->config->item('email_from_name_unverified_account_manual_request_verification_code_company_app_female');
								$subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_company_app_female');
								$message = $this->config->item('email_message_unverified_account_manual_request_verification_code_company_app_female');
								$message = str_replace(array('{name}','{activation_code}','{url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
							
							}
						
						
						
						}else{
							// Define email variables for company user
							$cc = $this->config->item('email_cc_unverified_account_manual_request_verification_code_company');
							$bcc = $this->config->item('email_bcc_unverified_account_manual_request_verification_code_company');
							$from = $this->config->item('email_from_unverified_account_manual_request_verification_code_company');
							$reply_to = $this->config->item('email_reply_to_unverified_account_manual_request_verification_code_company');
							$from_name = $this->config->item('email_from_name_unverified_account_manual_request_verification_code_company');
							$subject = $this->config->item('email_subject_unverified_account_manual_request_verification_code_company');
							$message = $this->config->item('email_message_unverified_account_manual_request_verification_code_compnay');
							$message = str_replace(array('{company_name}','{activation_code}','{url}','{account_expiration_interval}','{account_expiration_time_left}','{account_registration_date}','{account_registration_time}'),array($name,$code,$url,$account_expiration_time_interval,$account_expiration_time_left,$account_registration_date,$account_registration_time),$message);
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
					$response['status'] = 'OK';
					$response['success_message'] = $this->config->item('signup_verified_activation_code_new_message');
				}else{
					$this->session->unset_userdata('user_id');
					$this->session->unset_userdata('user');
					$this->session->unset_userdata('is_logged');
					$this->session->unset_userdata('is_authorized');
					$this->session->sess_destroy();
					$response['status'] = 'ERROR';
					$response['location'] = VPATH . $this->config->item('signin_page_url');;
				}
				echo json_encode ($response); die;
			}
		
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
	
	
	/**
     * Description: this method check varification code expiration.
     * 
     */
	public function check_verification_code()
	{
		if ($this->input->is_ajax_request()) {
			if (!$this->session->userdata ('user'))
			{
				$response['status'] = 'ERROR';
				$response['location'] = VPATH . $this->config->item('signin_page_url');;
				echo json_encode ($response);die;
			}
			$user_id = $this->input->post ('uid');
			$verification_code = $this->input->post ('verification_code');
			
			if(!empty($user_id) && !empty($verification_code))
			{
				$code = Cryptor::doEncrypt($verification_code);
			
				$signup_verification_code_expiration_set_interval = $this->config->item('signup_verified_code_expire_set_interval')*60;
				$uid = Cryptor::doDecrypt($user_id);
				$this->db->where ('user_id', $uid);
				$query = $this->db->get ('users_new_registrations_pending_verification');
				if($query->num_rows () == 1)
				{
				
					$this->Signup_model->update_automatic_verification_code_generate_time($uid);
				
					$this->db->where ('user_id', $uid);
					$query = $this->db->get ('users_new_registrations_pending_verification');
					
					$res = $query->result ();
					$account_expiration_date = $res[0]->account_expiration_date != NULL ? strtotime ($res[0]->account_expiration_date) : 0;
					############# check expiration account date of user start ##############
					if(!empty($account_expiration_date) && time() > $account_expiration_date){
						$this->remove_unverified_users_account_expiration($res[0]->user_id); // remove the unverified user account
						$this->session->unset_userdata('is_logged');
						$this->session->unset_userdata('is_authorized');
						$this->session->unset_userdata('user');
						$response['status'] = 'ERROR';
						$response['location'] = VPATH . $this->config->item('signin_page_url');
						echo json_encode ($response);die;
					}
					if ($verification_code !== $res[0]->verification_code){
						$response['status'] = 'ERROR';
						$response['error_message'] = $this->config->item('signup_verified_activation_code_wrong_message');
						$response['location'] = '';
						echo json_encode ($response);die;
					}
					############# check expiration account date of user e ##############
					$code_generate_time = $res[0]->automatic_verification_code_generate_time != NULL ? strtotime ($res[0]->automatic_verification_code_generate_time) : 0;
					$code_manual_request_time = $res[0]->verification_code_manual_request_time != NULL ? strtotime ($res[0]->verification_code_manual_request_time) : 0;
					$code_expiration_time = $res[0]->current_verification_code_expiration_date != NULL ? strtotime ($res[0]->current_verification_code_expiration_date) : 0;
					
					if($code_expiration_time < time()){
						$response['status'] = 'ERROR';
						$response['error_message'] = $this->config->item('signup_verified_activation_code_wrong_message');
						$response['location'] = '';
					
					}else{
					
						if ($verification_code === $res[0]->verification_code){
							$response['status'] = 'OK';
							$response['location'] = VPATH . $this->config->item('signup_verified_page_url').'?'. $this->config->item('signup_page_success_parameter').'='.$user_id.'&'.$this->config->item('signup_page_code_parameter').'='.$code;
							
						}else{
							
							$response['status'] = 'ERROR';
							$response['error_message'] = $this->config->item('signup_verified_activation_code_wrong_message');
							$response['location'] = '';
						}
					}
				
				}else{
					$this->session->unset_userdata('user_id');
					$this->session->unset_userdata('user');
					$this->session->unset_userdata('is_logged');
					$this->session->unset_userdata('is_authorized');
					$this->session->sess_destroy();
					$response['status'] = 'ERROR';
					$response['location'] = VPATH . $this->config->item('signin_page_url');;
				}
				echo json_encode ($response); die;
			}
		}else{
		show_custom_404_page(); //show custom 404 page
		}	
		
	}
	
	
	/**
     * Description: this method redirect the unverfied user to verfied page screen until user not verified his account.
     * 
     */
    public function signup_verification ()
    {
		$data = array ();
		$lay = array ();
		
		$data['current_page'] = 'signup_verify_page';
		$data['message'] = NULL;
		$data['message_type'] = NULL;
		$data['requested'] = NULL;
		
		
		
        if ( ! $this->session->userdata ('user'))
        {	
			if($this->input->get($this->config->item('signup_page_success_parameter')) && $this->input->get($this->config->item('signup_page_code_parameter'))){
				if($this->session->userdata('unverfied_user_id'))
				{
					$this->session->unset_userdata('unverfied_user_id');
				}
				if($this->session->userdata('unverfied_code'))
				{
					$this->session->unset_userdata('unverfied_code');
				}
				$this->session->set_userdata('unverfied_user_id', $this->input->get($this->config->item('signup_page_success_parameter'))); 
				$this->session->set_userdata('unverfied_code', $this->input->get($this->config->item('signup_page_code_parameter'))); 
				$this->session->set_userdata ('redir', current_url ());
			} 
			
			redirect (VPATH . $this->config->item('signin_page_url'));
		} else {
			
			$uid = Cryptor::doDecrypt($this->input->get($this->config->item('signup_page_success_parameter')));
			$user = $this->session->userdata ('user');
			$user_id = $user[0]->user_id;
			
			$this->db->where ('user_id', $uid);
			$query = $this->db->get ('users_new_registrations_pending_verification');
			
			if($user_id != $uid && $query->num_rows () != 0){
			
				
			
				if($this->session->userdata('is_authorized')){
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
				
				$this->session->unset_userdata('is_logged');
				$this->session->unset_userdata('is_authorized');
				$this->session->unset_userdata('user');
				$this->session->set_userdata('unverfied_user_id', $this->input->get($this->config->item('signup_page_success_parameter'))); 
				$this->session->set_userdata('unverfied_code', $this->input->get($this->config->item('signup_page_code_parameter'))); 
				redirect (VPATH . $this->config->item('signin_page_url'));
			}
			
			if($query->num_rows () == 0)
			{
				
				$this->db->where ('user_id', $user_id);
				$users_query = $this->db->get ('users');
				// redirect to dashboard if verified user 
				if($users_query->num_rows () == 1){
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
				
				$this->session->unset_userdata('is_logged');
				$this->session->unset_userdata('is_authorized');
				$this->session->unset_userdata('user');
				redirect (VPATH . $this->config->item('signin_page_url'));
			}
			$user_id_from_url = $this->input->get($this->config->item('signup_page_success_parameter'));
			$data['user_id_from_url'] = $user_id_from_url;
			$latest_code = $this->input->get($this->config->item('signup_page_code_parameter'));
			$signup_verification_code_expiration_set_interval = $this->config->item('signup_verified_code_expire_set_interval')*60;
			
			$this->Signup_model->update_automatic_verification_code_generate_time($uid);
			
			
			
			$this->db->where ('user_id', $uid);
			$query = $this->db->get ('users_new_registrations_pending_verification');
			if ($query->num_rows () > 0)
			{
				$res = $query->result ();
				$account_expiration_date = $res[0]->account_expiration_date != NULL ? strtotime ($res[0]->account_expiration_date) : 0;
				############# check expiration account date of user start ##############
				if(!empty($account_expiration_date) && time() > $account_expiration_date){
					$this->remove_unverified_users_account_expiration($res[0]->user_id); // remove the unverified user account
					$this->session->unset_userdata('is_logged');
					$this->session->unset_userdata('is_authorized');
					$this->session->unset_userdata('user');
					redirect (VPATH . $this->config->item('signin_page_url'));
				} 
				############# check expiration account date of user end ##############
				
				// check code valid or not if valid then redirect to verfication page start
				$code_expiration_time = $res[0]->current_verification_code_expiration_date != NULL ? strtotime ($res[0]->current_verification_code_expiration_date) : 0;
				
				if(Cryptor::doDecrypt($latest_code) == $res[0]->verification_code && $code_expiration_time > time()){
					redirect(VPATH . $this->config->item('signup_verified_page_url').'?'. $this->config->item('signup_page_success_parameter').'='.$user_id_from_url.'&'.$this->config->item('signup_page_code_parameter').'='.$latest_code);
				}
				
				// check code valid or not if valid then redirect to verfication page end
				
				
				$data['result'] = $res;
				$name = (($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($res[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $res[0]->is_authorized_physical_person =='Y')) ? $res[0]->first_name . ' ' . $res[0]->last_name : $res[0]->company_name;
				$data['name'] = $name;
				$first_name= $res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $res[0]->first_name : $res[0]->company_name;
				$email = $res[0]->email;
				$code = $res[0]->verification_code;
				
				$automatic_code_generate_time = $res[0]->automatic_verification_code_generate_time != NULL ? strtotime ($res[0]->automatic_verification_code_generate_time) : 0;
				
				$code_manual_request_time = $res[0]->verification_code_manual_request_time != NULL ? strtotime ($res[0]->verification_code_manual_request_time) : 0;
				
				$code_next_manual_request_time = $res[0]->next_available_verification_code_manual_request_time != NULL ? strtotime ($res[0]-> 	next_available_verification_code_manual_request_time) : 0;
				
				$data['is_manual_requested'] = 'yes';	 //
				if(!empty($code_next_manual_request_time) && time() < $code_next_manual_request_time ){
					$data['is_manual_requested'] = 'no';
				}
				$data['expired'] = 'no';
				if(strtotime($res[0]->current_verification_code_expiration_date) <= time())
				{
					$data['expired'] = 'yes';
				}
				########## set the signup verified page title meta tag and meta description  start here #########
				
				$signup_verify_page_title_meta_tag = str_replace(array('{user_first_name_or_company_name}'),array($first_name),$this->config->item('signup_verify_page_title_meta_tag')); ;

				$signup_verify_page_description_meta_tag = $this->config->item('signup_verify_page_description_meta_tag');
				$data['meta_tag'] = '<title>' . $signup_verify_page_title_meta_tag . '</title><meta name="description" content="' . $signup_verify_page_description_meta_tag . '"/>';
				########## set the signup verified page title meta tag and meta description  end here #########
				
				$this->layout->view ('signup_verify_page', $lay, $data, 'normal');  
			}else{						
				redirect(VPATH);
			}
		}
	}
	
	/**
     * Description: this method update the verification page content through ajax.
     */
	 public function update_signup_verification()
	 {
		if ($this->input->is_ajax_request()) {
			$user_id = $_POST['uid'];
			$uid = Cryptor::doDecrypt($user_id);
			$this->db->where ('user_id', $uid);
			$query = $this->db->get ('users_new_registrations_pending_verification');
			if ( ! $this->session->userdata ('user') || $query->num_rows ()== 0 ){
				echo '<script>window.location.href = "'.VPATH . $this->config->item('logout_page_url').'"</script>';
				die;
			}else{
			
				if ($query->num_rows () > 0)
				{
					$this->Signup_model->update_automatic_verification_code_generate_time($uid);
					$this->db->where ('user_id', $uid);
					$query = $this->db->get ('users_new_registrations_pending_verification');
					
					
					$signup_verification_code_expiration_set_interval = $this->config->item('signup_verified_code_expire_set_interval')*60;
					 $res = $query->result ();
					 
					 
					 $account_expiration_date = $res[0]->account_expiration_date != NULL ? strtotime ($res[0]->account_expiration_date) : 0;
					############# check expiration account date of user start ##############
					if(!empty($account_expiration_date) && time() > $account_expiration_date){
						$this->remove_unverified_users_account_expiration($res[0]->user_id); // remove the unverified user account
						$this->session->unset_userdata('is_logged');
						$this->session->unset_userdata('is_authorized');
						$this->session->unset_userdata('user');
						echo '<script>window.location.href = "'.VPATH . $this->config->item('logout_page_url').'"</script>';
						die;
					}
					############# check expiration account date of user  ##############
					 
					 $data['result'] = $res;
					 //$name = $res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $res[0]->first_name . ' ' . $res[0]->last_name : $res[0]-> company_name;
					 
					 $name = (($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($res[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $res[0]->is_authorized_physical_person =='Y')) ? $res[0]->first_name . ' ' . $res[0]->last_name : $res[0]->company_name;
					 
					$email = $res[0]->email;
					$code = $res[0]->verification_code;

					$automatic_code_generate_time = $res[0]->automatic_verification_code_generate_time != NULL ? strtotime ($res[0]->automatic_verification_code_generate_time) : 0;
					$code_manual_request_time = $res[0]->verification_code_manual_request_time != NULL ? strtotime ($res[0]->verification_code_manual_request_time) : 0;
					$code_next_manual_request_time = $res[0]->next_available_verification_code_manual_request_time != NULL ? strtotime ($res[0]-> 	next_available_verification_code_manual_request_time) : 0;

					 $data['is_manual_requested'] = 'yes';	 //
					if(!empty($code_next_manual_request_time) && time() < $code_next_manual_request_time ){
						$data['is_manual_requested'] = 'no';
					}

					$data['expired'] = 'no';
					if(strtotime($res[0]->current_verification_code_expiration_date) <= time())
					{
						$data['expired'] = 'yes';
					}
					
					
					$url = $this->config->item('signup_activate_page_url').'?'. $this->config->item('signup_page_success_parameter').'='.$user_id;
					//$data['uri'] = $url; 
					$data['user_id'] = $user_id; 
					/* $data['message'] = ''; 
					$data['message_type'] = '';  */
					$data['msg'] = '1';
					
					if($_POST['type'] == '2'){
						$data['msg'] = $this->config->item('signup_verified_activation_code_new_message');
					}
					
					//$msg['data'] = $this->load->view ('signup_verify_page_ajax',$data);
					$msg['data'] = $this->load->view('signup_verify_page_ajax',$data, true);
					$msg['type'] = $_POST['type'];
					echo json_encode ($msg);die;
					
				}
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
	 }
	
	
	

	/**
     * Description: this method redirect the user to confirmation page after successful registered his account.
     */
    public function signup_confirmation_successful ()
    {
		$uid = Cryptor::doDecrypt($this->input->get($this->config->item('signup_page_success_parameter')));	
		$this->db->select('user_id,account_type,first_name,company_name,email,account_expiration_date');
        $this->db->where ('user_id', $uid);
        $query = $this->db->get ('users_new_registrations_pending_verification');
		$once = $this->session->flashdata ('registered');
        if ($query->num_rows () > 0 && $once)
       // if ($query->num_rows () > 0 )
        {
            $the_template = 'successful_signup_confirmation';
            $res = $query->result ();
            $data['email'] = $res[0]->email;
			
			 if ($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
                $name = $res[0]->first_name;
            }else{
               $name = $res[0]->company_name;
            }
			
			
            $data['account_expiration_date'] = $res[0]->account_expiration_date;
            $data['name'] = $name;

            $this->db->where ('user_id', $res[0]->user_id);
            $data['current_page'] = 'successful_signup_confirmation';
			//registration success confirmation page 
			########## set the dashboard title meta tag and meta description  start here #########
            $registration_success_confirmation_title_meta_tag = $this->config->item('signup_success_confirmation_page_title_meta_tag');
            $registration_success_confirmation_description_meta_tag = $this->config->item('signup_success_confirmation_page_description_meta_tag');
            $data['meta_tag'] = '<title>' . $registration_success_confirmation_title_meta_tag . '</title><meta name="description" content="' . $registration_success_confirmation_description_meta_tag . '"/>';
            ########## set the dashboard title tag start end #########
			
			
            $this->layout->view ($the_template, '', $data, 'normal');
        }
        else
        {			
           redirect (VPATH . $this->config->item('signin_page_url'));
        }
    }
	
	/**
     * Description: this method is execute when user verified his successfully account.It will call when user enter the correct verification code on verified page.It will move the entry of user record from "users_new_registrations_pending_verification" to "users" table.
     */
    public function user_signup_successful_verification ()
    {
		$this->session->unset_userdata('unverfied_user_id');
		$this->session->unset_userdata('unverfied_code');
       if ( ! $this->session->userdata ('user'))
        {
            $this->session->set_userdata ('redir', current_url ());
			redirect (VPATH . $this->config->item('signin_page_url'));
			/*$signup_confirmation_page_title_meta_tag = str_replace(array('{user_first_name_or_company_name}'),array($name),$this->config->item('signup_confirmation_page_title_meta_tag'));
			$signup_confirmation_page_description_meta_tag = $this->config->item('signup_confirmation_page_description_meta_tag');
			$data['meta_tag'] = '<title>' . $signup_confirmation_page_title_meta_tag . '</title><meta name="description" content="' . $signup_confirmation_page_description_meta_tag . '"/>';
			########## set the forgot password title meta tag and meta description  end here #########
			$lay = array();
			$data['current_page'] = 'successful_signup_verification';
			$this->layout->view ('successful_signup_verification', $lay, $data, 'normal'); */
        }
        else
        { 
		
			$uid = Cryptor::doDecrypt($this->input->get($this->config->item('signup_page_success_parameter')));
			$code = Cryptor::doDecrypt($this->input->get($this->config->item('signup_page_code_parameter')));
			
            $this->db->where ('user_id', $uid);
			//$this->db->where ('verification_code', $code);
            $query = $this->db->get ('users_new_registrations_pending_verification');
            //if ($query->num_rows () > 0 && $this->session->userdata ('user')[0]->user_id == $uid)
            if ($query->num_rows () > 0)
            {
			
                $res = $query->result ();
				
				 $count_verify_user_record = $this->db
				->select ('user_id')
				->from ('users')
				->where ('email', $res[0]->email)
				->get ()->num_rows ();
				
				if($count_verify_user_record > 0)
				{
					 redirect(VPATH.$this->config->item('signin_page_url'));
				}
				
				
				$current_date = date('Y-m-d H:i:s');
                if ($code == $res[0]->verification_code)
                {	
						$insertArr = array (
							'account_type' => $res[0]->account_type,
							'is_authorized_physical_person' => $res[0]->is_authorized_physical_person,
							'first_name' => $res[0]->first_name,
							'last_name' => $res[0]->last_name,
							'email' => $res[0]->email,
							'gender' => $res[0]->gender,
							'company_name' => $res[0]->company_name,
							'profile_name' => $res[0]->profile_name,
							'password' => $res[0]->password,
							'latest_source_ip' => $res[0]->registration_source_ip,
							'account_validation_date' => $current_date,
							'referral_code' => generate_referral_code(),
							'lvl1_referrer_id' => $res[0]->referrer_id != 0 ? $res[0]->referrer_id : null,
							'lvl1_referrer_code' => $res[0]->referrer_code,
							'referee_source' => $res[0]->referee_source
						);
						$this->db->insert ('users', $insertArr);
						$last_insert_id = $this->db->insert_id();
						// @sid Code to insert record into level 1 referrals tracking
						if($res[0]->referrer_id != 0) {

							$referrer_data = $this->db->get_where('users', ['user_id' => $res[0]->referrer_id])->row_array();
							$referral_data = [];
							if(!empty($referrer_data)) {
								$referral_data = [
									'user_id' => $last_insert_id,
									'user_account_validation_date' => date('Y-m-d H:i:s'),
									'lvl1_referrer_id' => $res[0]->referrer_id,
									'lvl1_referrer_account_validation_date' => date('Y-m-d H:i:s', strtotime($referrer_data['account_validation_date'])),
									'lvl2_referrer_id' => !empty($referrer_data['lvl1_referrer_id']) ? $referrer_data['lvl1_referrer_id'] : 0
								];

								if(!empty($referrer_data['lvl1_referrer_id'])) {
									$lvl1_referrer_data = $this->db->get_where('users', ['user_id' => $referrer_data['lvl1_referrer_id']])->row_array();
									if(!empty($lvl1_referrer_data)) {
										$referral_data['lvl2_referrer_account_validation_date'] = date('Y-m-d H:i:s', strtotime($lvl1_referrer_data['account_validation_date']));
									}
								}
							}
							if(!empty($referral_data)) {
								$this->db->insert('users_referrals_tracking', $referral_data);
							}

							if($res[0]->referee_source == 'user_self_url_share_email') {
								$pend_invi = $this->Signup_model->get_pending_invitation_detail_by_email($res[0]->email);
								if(!empty($pend_invi)) {
									$invi_register = [
										'invitation_id' => $pend_invi['invitation_id'],
										'invitee_user_id' => $last_insert_id,
										'invitee_email_address' => $pend_invi['invitee_email_address'],
										'invitee_account_validation_date' => date('Y-m-d H:i:s'),
										'referrer_id' => $pend_invi['referrer_id'],
										'referrer_code' => $res[0]->referrer_code,
										'initial_invitation_sent_date' => $pend_invi['initial_invitation_sent_date'],
										'no_of_resend_done' => ($this->config->item('resends_available') - $pend_invi['resends_available'])
									];
									$this->Signup_model->invited_friends_registered($invi_register);
									$this->Signup_model->remove_pending_invitation_by_id($pend_invi['id']);
								}
							}
						}
						$data['last_insert_id'] = Cryptor::doEncrypt($last_insert_id);
						$name = (($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($res[0]->account_type == USER_COMPANY_ACCOUNT_TYPE  && $res[0]->is_authorized_physical_person == 'Y')) ? $res[0]->first_name : $res[0]->company_name;
                        $this->load->library ('email');
						$to = $res[0]->email;
						if ($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
						
							if($res[0]->gender == 'M')
							{
								// Define email variables for male user
								$cc = $this->config->item('email_cc_account_verified_personal_male');
								$bcc = $this->config->item('email_bcc_account_verified_personal_male');
								$from = $this->config->item('email_from_account_verified_personal_male');
								$reply_to = $this->config->item('email_reply_to_account_verified_personal_male');
								$from_name = $this->config->item('email_from_name_account_verified_personal_male');
								$subject = $this->config->item('email_subject_account_verified_personal_male');
								$message = $this->config->item('email_message_account_verified_personal_male');
								$message = str_replace(array('{name}','{faq_page_url}','{site_url}'),array($name,site_url($this->config->item('faq_page_url')),site_url()),$message);
							}else{
								// Define email variables for female user
								$cc = $this->config->item('email_cc_account_verified_personal_female');
								$bcc = $this->config->item('email_bcc_account_verified_personal_female');
								$from = $this->config->item('email_from_account_verified_personal_female');
								$reply_to = $this->config->item('email_reply_to_account_verified_personal_female');
								$from_name = $this->config->item('email_from_name_account_verified_personal_female');
								$subject = $this->config->item('email_subject_account_verified_personal_female');
								$message = $this->config->item('email_message_account_verified_personal_female');
								$message = str_replace(array('{name}','{faq_page_url}','{site_url}'),array($name,site_url($this->config->item('faq_page_url')),site_url()),$message);
							
							}
						}else if ($res[0]->account_type == USER_COMPANY_ACCOUNT_TYPE){
							if($res[0]->is_authorized_physical_person == 'Y'){
								if($res[0]->gender == 'M')
								{
									// Define email variables for male user
									$cc = $this->config->item('email_cc_account_verified_company_app_male');
									$bcc = $this->config->item('email_bcc_account_verified_company_app_male');
									$from = $this->config->item('email_from_account_verified_company_app_male');
									$reply_to = $this->config->item('email_reply_to_account_verified_company_app_male');
									$from_name = $this->config->item('email_from_name_account_verified_company_app_male');
									$subject = $this->config->item('email_subject_account_verified_company_app_male');
									$message = $this->config->item('email_message_account_verified_company_app_male');
									$message = str_replace(array('{name}','{faq_page_url}','{site_url}'),array($name,site_url($this->config->item('faq_page_url')),site_url()),$message);
								}else{
									// Define email variables for female user
									$cc = $this->config->item('email_cc_account_verified_company_app_female');
									$bcc = $this->config->item('email_bcc_account_verified_company_app_female');
									$from = $this->config->item('email_from_account_verified_company_app_female');
									$reply_to = $this->config->item('email_reply_to_account_verified_company_app_female');
									$from_name = $this->config->item('email_from_name_account_verified_company_app_female');
									$subject = $this->config->item('email_subject_account_verified_company_app_female');
									$message = $this->config->item('email_message_account_verified_company_app_female');
									$message = str_replace(array('{name}','{faq_page_url}','{site_url}'),array($name,site_url($this->config->item('faq_page_url')),site_url()),$message);
								}
							}else{
						
						
								// Define email variables for company user
								$cc = $this->config->item('email_cc_account_verified_company');
								$bcc = $this->config->item('email_bcc_account_verified_company');
								$from = $this->config->item('email_from_account_verified_company');
								$reply_to = $this->config->item('email_reply_to_account_verified_company');
								$from_name = $this->config->item('email_from_name_account_verified_company');
								$subject = $this->config->item('email_subject_account_verified_company');
								$message = $this->config->item('email_message_account_verified_company');
								$message = str_replace(array('{company_name}','{faq_page_url}','{site_url}'),array($name,site_url($this->config->item('faq_page_url')),site_url()),$message);
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
						//below comments for check email debug.
						$this->email->send ();
					
						//start creating folder by diib
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config);
						$users_ftp_dir 	= USERS_FTP_DIR;
						
						$profile_folder = $res[0]->profile_name;
						
						
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir)))
						{
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder. DIRECTORY_SEPARATOR );
						
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.	$profile_folder. USER_AVATAR );
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder. USER_PORTFOLIO );
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder. PROJECTS_FTP_DIR );
						}
						
						
						$this->ftp->close();
						
						$this->db->where('user_id', $uid);
						$this->db->delete('users_new_registrations_pending_verification');
						$this->db->select('user_id,account_type,email,first_name,company_name,is_authorized_physical_person');
						$this->db->where ('user_id', $last_insert_id);
						$users_query = $this->db->get ('users');
						$users_table_data = $users_query->result ();
						
						
						
						 if ($users_table_data[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
							$name = $users_table_data[0]->first_name;
						}else{
							if($users_table_data[0]->is_authorized_physical_person == 'Y'){
								$name = $users_table_data[0]->first_name;
							}else{
								$name = $users_table_data[0]->company_name;
						   }
						}
						//@sid account verification message will store in user log
						$user_log_str = $this->config->item('user_account_verification_success_message_for_log');
						$user_log_str = str_replace('{user_first_name_or_company_name}', $name, $user_log_str);
						
						//user_display_log($user_log_str, $last_insert_id);
						//@sid account verification message will store in user activity log
						
						
						$email = $users_table_data[0]->email;
						$data['name'] = $name;
						$data['email'] = $email;
						############### save the user details  into database start here###########
						$this->db->where ('default_plan', 'Yes');
						$query = $this->db->get ('membership_plans');
						$default_membership_plan_record = $query->result ();
						$default_membership_plan_record = array_shift($default_membership_plan_record);
						
						if(!empty($default_membership_plan_record) && floatval($default_membership_plan_record->signup_bonus_value) != 0){
							$user_activity_str = $user_log_str." ".$this->config->item('user_account_verification_success_message_for_display_activity');
							$user_activity_str = str_replace('{user_first_name_or_company_name}', $name, $user_activity_str);
							user_display_log($user_activity_str, $last_insert_id);
						
						}else{
							user_display_log($user_log_str, $last_insert_id);
						}
						
						################################### Deposit funds user bank variable symbol generation start #########################################
						$variabil_symbol = '';
						if($this->config->item('deposit_funds_user_bank_variable_symbol_prefix_value')) {
							$variabil_symbol = $this->config->item('deposit_funds_user_bank_variable_symbol_prefix_value');
						}
						$variabil_symbol .= $last_insert_id;

						$random_num = '';
						for($i = 0; $i < $this->config->item('deposit_funds_user_bank_variable_symbol_suffix_number_of_digits'); $i++) {
							$random_num .= mt_rand(0, 9);
						}
						$variabil_symbol .= $random_num;
						################################# end ################################################################################

						################################### withdraw funds user bank variable symbol generation start #########################################
						$wvariabil_symbol = '';
						if($this->config->item('withdraw_funds_user_bank_variable_symbol_prefix_value')) {
							$wvariabil_symbol = $this->config->item('withdraw_funds_user_bank_variable_symbol_prefix_value');
						}
						$wvariabil_symbol .= $last_insert_id;

						$random_num = '';
						for($i = 0; $i < $this->config->item('withdraw_funds_user_bank_variable_symbol_suffix_number_of_digits'); $i++) {
							$random_num .= mt_rand(0, 9);
						}
						$wvariabil_symbol .= $random_num;
						################################# end ################################################################################

						$user_detail_array = array (
								'user_id' => $last_insert_id,
								'current_membership_plan_id' => $default_membership_plan_record->id,
								'current_membership_start_date' =>$current_date,
								'signup_bonus_balance' => $default_membership_plan_record->signup_bonus_value,
								'user_bank_deposit_variable_symbol' => $variabil_symbol,
								'user_bank_withdrawal_variable_symbol' => $wvariabil_symbol
							);
						$this->db->insert ('users_details', $user_detail_array);
						################### update the parameter of email confirmation when user verify his account
						//$this->db->insert ('users_profile_completion_parameters_tracking', array('user_id' => $last_insert_id,'email_confirmation' => 'Y'));
						
						
						
						$default_plan = $this->Signup_model->get_default_membership_plan();
						$membership_tracking_data = [
							'user_id' => $last_insert_id,
							'current_membership_plan_id' => $default_membership_plan_record->id,
							'current_membership_plan_start_date' =>$current_date,
							'membership_price' => $default_plan['monthly_price']
						];
						$this->db->insert('users_membership_tracking', $membership_tracking_data);
						############### save the user details  into database end here###########
						
						$this->session->unset_userdata('is_logged');
						$this->session->unset_userdata('is_authorized');
						$this->session->unset_userdata('user');
						
						
						//end creating folder by dib
						//$this->load->view ('successful_signup_verification', $data);
						########## set the forgot password title meta tag and meta description  start here #########
						$signup_confirmation_page_title_meta_tag = str_replace(array('{user_first_name_or_company_name}'),array($name),$this->config->item('signup_confirmation_page_title_meta_tag'));
						$signup_confirmation_page_description_meta_tag = $this->config->item('signup_confirmation_page_description_meta_tag');
						$data['meta_tag'] = '<title>' . $signup_confirmation_page_title_meta_tag . '</title><meta name="description" content="' . $signup_confirmation_page_description_meta_tag . '"/>';
						########## set the forgot password title meta tag and meta description  end here #########
						$lay = array();
						$data['current_page'] = 'successful_signup_verification';
						$this->layout->view ('successful_signup_verification', $lay, $data, 'normal'); 
                }
                else
                {
                     redirect(VPATH);
                }
            }
            else
            {
                 redirect(VPATH);
            }
       }
    }
	

	/**
     * Need to review this function
     */
   /*  public function switch_account ()
    {
		 if(!$this->session->userdata ('switch_account_email')){
			$this->session->unset_userdata ('switch_account_email');
		 }
		$uid = Cryptor::doDecrypt($this->input->get($this->config->item('signup_page_success_parameter')));
		$user_id_from_url = $this->input->get($this->config->item('signup_page_success_parameter'));
        $data['current_page'] = 'switch_account';
        $lay = array();

		########## set the signin title meta tag and meta description  start here #########
		$switch_account_title_meta_tag = $this->config->item('signup_switch_account_page_title_meta_tag');

		$switch_account_description_meta_tag = $this->config->item('signup_switch_account_page_description_meta_tag');
		$data['meta_tag'] = '<title>' . $switch_account_title_meta_tag . '</title><meta name="description" content="' . $switch_account_description_meta_tag . '"/>';
		
		$this->db->where ('user_id', $uid);
		$query = $this->db->get ('users_new_registrations_pending_verification');
		
		if($query->num_rows () == 0){
			redirect (VPATH . $this->config->item('signin_page_url'));
		}
		if(!empty($this->input->post ())){
			
			$this->session->unset_userdata('is_logged');
			$this->session->unset_userdata('is_authorized');
			$this->session->unset_userdata('user');
			$this->session->set_userdata ('switch_account_email', $this->input->post ('email'));
			redirect (VPATH . $this->config->item('signin_page_url')); 
		}
		$res = $query->result ();
		$data['result'] = $res;
		$url = $this->config->item('switch_account_page_url').'?'. $this->config->item('signup_page_success_parameter').'='.$user_id_from_url;
		$data['uri'] = $url; 
        $this->layout->view ('switch_account', $lay, $data, 'normal');  
    } */
	
    
	public function set_session_after_verification(){		
	//user_log
		$encrypty_uid = $this->input->get($this->config->item('signup_page_success_parameter'));
		$user_id = Cryptor::doDecrypt($encrypty_uid);
		$this->db->where ('user_id', $user_id);
		$query = $this->db->get ('users');
		if($query->num_rows () == 0)
		{
			$this->session->unset_userdata('is_logged');
			$this->session->unset_userdata('is_authorized');
			$this->session->unset_userdata('user');
			 redirect (VPATH . $this->config->item('signin_page_url'));
		}
		
		
		$results = $query->result ();
		$this->session->unset_userdata('is_logged');
		$this->session->unset_userdata('is_authorized');
		$this->session->unset_userdata('user');
		$this->session->set_userdata ('is_logged', TRUE);
		$this->session->set_userdata('is_authorized', true); 
		$this->session->set_userdata('is_login_first', true); 
		$this->session->set_userdata ('user', $results);
		$user_log_str = 'user '.$results[0]->profile_name.'( '.$results[0]->email.' ) logged in. source IP '.get_client_ip().' browser '.$_SERVER['HTTP_USER_AGENT'];
		
		$current_date = date('Y-m-d H:i:s');
		$time_arr = explode(':', USER_SESSION_AVAILABILITY_TIME);
		$data = [
			'user_id' => $results[0]->user_id,
			'source_ip' => get_client_ip(),
			'timestamp' => time()*1000,
			'login_date' => $current_date,
			'user_session_id' => session_id(),
			'session_expiration_time' => !empty($time_arr) ? date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds', strtotime(date('Y-m-d H:i:s')))): date('Y-m-d H:i:s'),
			'last_activity_time' => date('Y-m-d H:i:s')
		];
		$user_log_id = $this->Signin_model->insert_into_user_log($data);
		$this->session->set_userdata('user_log_id', $user_log_id); 
		$data = array (
			'latest_source_ip' => $_SERVER['REMOTE_ADDR'],
			'latest_login_date' => $current_date,
			'online' => '1',
			'currently_online' => '1'
		);
		$this->db->where ('user_id', $user_id);
		$this->db->update ('users', $data);
		redirect ($this->config->item('dashboard_page_url')); 
	}
	
	
	public function checkUniqueEmail ()
    {
		if($this->input->is_ajax_request ()){
			$email = $_POST['email'];
			$results = $this->Signup_model->check_unique_email ($email);
			if($results)
			{	
				$msg['check'] = '0';
				echo json_encode ($msg);die;
			}else{
				$msg['check'] = '1';
				echo json_encode ($msg);die;
			} 
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}	
    }
	
	public function checkValidProfileName ()
    {	 
		if($this->input->is_ajax_request ()){
			$profilename = $this->input->post ('profile_name');
			$results = $this->Signup_model->check_valid_unique_profile_name ($profilename);
			echo json_encode($results);
		}else{
			show_custom_404_page(); //show custom 404 page
		}	
    }
	
	/*
	This function remove the unverified user from database whose account is expire.
	*/
	public function remove_unverified_users_account_expiration($user_id)
	{
		
		$this->db->where ('user_id', $user_id);
		$query = $this->db->get ('users_new_registrations_pending_verification');
		if ($query->num_rows () > 0)
		{
			
		   $res = $query->result ();
			$name =  (($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($res[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $res[0]->is_authorized_physical_person == 'Y')) ? $res[0]->first_name . ' ' . $res[0]->last_name : $res[0]->company_name;
			$to = $res[0]->email;
			if($res[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE)
			{
				// Define email variables for male user
				if($res[0]->gender == 'M'){
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
				if($res[0]->is_authorized_physical_person == 'Y'){
					// Define email variables for male user
					if($res[0]->gender == 'M'){
						$cc = $this->config->item('email_cc_unverified_user_account_delete_company_app_male');
						$bcc = $this->config->item('email_bcc_unverified_user_account_delete_company_app_male');
						$from = $this->config->item('email_from_unverified_user_account_delete_company_app_male');
						$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_company_app_male');
						$from_name = $this->config->item('email_from_name_unverified_user_account_delete_company_app_male');
						$subject = $this->config->item('email_subject_unverified_user_account_delete_company_app_male');
						$message = $this->config->item('email_message_unverified_user_account_delete_company_app_male');
						$message = str_replace(array('{name}'),array($name),$message);
					
					}else{
						// Define email variables for female user
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
			$this->db->delete('users_new_registrations_pending_verification', array ('user_id' => $user_id));
			$this->email->send ();
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/* public function test_email(){
	
		$name="manish";
		$code="420";
		$signup_verification_url="https://manish.devserver1.info/registrace/aktivace?overeni=TWRMU3c1QXNwN3RkWmRxaUhzbmNldz09&kod=M0hWR0FobjQ3QWhqVTFhbnU5VEdmZz09";
		$account_expiration_time = '120 hodiny';
		//$to = "khemit.verma25@outlook.com";
		//$to = "khemit.verma25@gmail.com";
		//$to = "khemit@mailinator.com";
		$to = "globalitapp@yahoo.com";
		//$cc = $this->config->item('welcome_email_cc_signup_personal_male');
		$from = $this->config->item('welcome_email_from_signup_personal_male');
		$reply_to = $this->config->item('welcome_email_reply_to_signup_personal_male');
		$from_name = $this->config->item('welcome_email_from_name_signup_personal_male');
		//$subject = $this->config->item('welcome_email_subject_signup_personal_male');
		$subject = 'Vítejte na travai.cz - ověřte Vaši emailovou adresu(male)';
		$message = $this->config->item('welcome_email_message_signup_personal_male');
		$message = str_replace(array('{name}','{activation_code}','{signup_verification_url}','{account_expiration_interval}'),array($name,$code,$signup_verification_url,$account_expiration_time),$message);
		
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
		//$this->email->set_header("Content-Type","text/html; charset=utf-8' . '\r\n'");
		//$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
		//$subject = '=?utf-8?B?'.base64_encode($subject).'?=';
		
		$this->email->from ($from,$from_name);
		$this->email->to ($to);
		//$this->email->cc ($cc);
		
		//$subject = mb_encode_mimeheader($subject, 'UTF-8');
		
		$this->email->subject ($subject);
		$this->email->reply_to($reply_to);
		$this->email->set_mailtype ('html');
		$this->email->set_newline("\r\n");
		$this->email->message ($message);
		//mb_internal_encoding('UTF-8');
		$this->email->send ();
		die("cxvcx");
		
		
	
	} */

}
?>