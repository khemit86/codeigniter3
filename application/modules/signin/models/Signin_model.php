<?php

if ( ! defined ('BASEPATH'))
    exit ('No direct script access allowed');

class Signin_model extends BaseModel
{

    public function __construct ()
    {
        return parent::__construct ();
    }

   /**
     * Description: this function check the login email and password into database
     */
	public function login ()
    {	
		
		$this->load->helper ('date');
        $i = 0;
        $email = $this->input->post ('email');
        $password = $this->input->post ('password');
        $pageredirect = $this->input->post ('pageredirect');
        $last_redirect_url = $this->input->post ('last_redirect_url');
		
        if ($email == '' || !filter_var ($email, FILTER_VALIDATE_EMAIL))
        {
            $msg['status'] = 'FAIL';
            $msg['errors'][$i]['id'] = 'email_error';
            $msg['errors'][$i]['message'] = $this->config->item('enter_email_validation_signin_message');
            $i ++;
        }

        if ($password == '')
        {
            $msg['status'] = 'FAIL';
            $msg['errors'][$i]['id'] = 'password_error';
            $msg['errors'][$i]['message'] = $this->config->item('enter_password_validation_signin_message');
            $i ++;
        }
        
        if ($i == 0)
        {
			
            $password = $this->input->post ("password");
            $this->db->select ('user_id,first_name,account_expiration_date,last_name,gender,company_name, profile_name, password, password_reset_token,account_type,verification_code,account_type, email,automatic_verification_code_generate_time,verification_code_manual_request_time,verification_code,current_verification_code_expiration_date,is_authorized_physical_person');
            $this->db->where ("(email = '" . $email . "')"); 
            $this->db->where ('password', md5 ($password));
            $query = $this->db->get ('users_new_registrations_pending_verification');
			
			$this->db->select ('*');
			$this->db->from('users');
			// $this->db->where ("(email = '" . $email . "')"); // @sid comment this line for currently online count sync testing [12-10-2018]
			$this->db->where ("email", $email); 
			$this->db->where ('password', md5 ($password));
			$query_user = $this->db->get ();
            
			// @sid set flag for authorized or un-authorized user.
			$flag = true;
            if ($query->num_rows () == 1)
            {
				$result = $query->result ();
				
				if($this->session->userdata('unverfied_user_id') && $this->session->userdata('unverfied_code'))
				{
					
					$signup_verification_code_expiration_set_interval = $this->config->item('signup_verified_code_expire_set_interval')*60;
					$code = Cryptor::doDecrypt($this->session->userdata('unverfied_code'));
					$user_id = Cryptor::doDecrypt($this->session->userdata('unverfied_user_id'));
					
					/* $code_generate_time = $result[0]->automatic_verification_code_generate_time != NULL ? strtotime ($result[0]->automatic_verification_code_generate_time) : 0;
					
					$code_manual_request_time = $result[0]->verification_code_manual_request_time != NULL ? strtotime ($result[0]->verification_code_manual_request_time) : 0; */
					
					$checkVerificationCodeStatus = false;
					$code_expiration_time = strtotime($result[0]->current_verification_code_expiration_date);
					if($code_expiration_time > time()){
						$checkVerificationCodeStatus = true;
					}
					/* if($code_generate_time >= $code_manual_request_time )
					{
						$code_expiration_time = strtotime($result[0]->current_verification_code_expiration_date);
						if($code_expiration_time < time())
						{
							$checkVerificationCodeStatus = false;
						}else{
							$checkVerificationCodeStatus = true;
						}
					
					}else{
						
						$code_expiration_time = strtotime($result[0]->current_verification_code_expiration_date);
						if($code_expiration_time  < time())
						{
							$checkVerificationCodeStatus = false;
						}else{
							$checkVerificationCodeStatus = true;
						}
					
					} */
					if($checkVerificationCodeStatus)
					{
						
						if ($user_id == $result[0]->user_id && $code === $result[0]->verification_code){
							
							
							$this->session->set_userdata ('is_logged', TRUE);
							$msg['status'] = "OK";
							$this->session->set_userdata ('user', $result);
							$msg['redir_kind'] = 'Redir'; 
							$msg['location'] = VPATH . $this->config->item('signup_verified_page_url').'?'. $this->config->item('signup_page_success_parameter').'='.$this->session->userdata('unverfied_user_id').'&'.$this->config->item('signup_page_code_parameter').'='.$this->session->userdata('unverfied_code');
							$flag = false;
							
						//}else if($user_id != $result[0]->user_id  ){
						}else if(($user_id != $result[0]->user_id) || ($user_id == $result[0]->user_id && $code != $result[0]->verification_code )  ){
							
							
							$this->session->set_userdata ('is_logged', TRUE);
							$msg['status'] = "OK";
							$this->session->set_userdata ('user', $result);
							$msg['location'] = VPATH . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=".Cryptor::doEncrypt($result[0]->user_id);
							$flag = false;
						} 
					}else{
							
							$this->session->set_userdata ('is_logged', TRUE);
							$msg['status'] = "OK";
							$this->session->set_userdata ('user', $result);
							$msg['location'] = VPATH . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=".Cryptor::doEncrypt($result[0]->user_id);
							$flag = false;
					}
					
				} else {
				
					
					if(!empty($result) && strtotime($result[0]->account_expiration_date) < time()){
						$user_data['first_name'] = $result[0]->first_name;
						$user_data['email'] = $result[0]->email;
						$user_data['last_name'] = $result[0]->last_name;
						$user_data['gender'] = $result[0]->gender;
						$user_data['account_type'] = $result[0]->account_type;
						$user_data['company_name'] = $result[0]->company_name;
						$user_data['user_id'] = $result[0]->user_id;
						$user_data['is_authorized_physical_person'] = $result[0]->is_authorized_physical_person;
						$this->remove_unverified_user($user_data);
						$msg['status'] = 'FAIL';
						$msg['errors'][$i]['id'] = 'wrong_email_password_error';
						$msg['errors'][$i]['message'] = $this->config->item('login_failed_message');
						echo json_encode ($msg);die;
					} 
				
				
					$this->session->set_userdata ('is_logged', TRUE);
					$msg['status'] = "OK";
					$this->session->set_userdata ('user', $result);
					$msg['location'] = VPATH . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=".Cryptor::doEncrypt($result[0]->user_id); 
					/* if ($this->session->userdata ('redir') && $this->session->userdata ('redir') != site_url('login'))
					{
						$msg['redir_kind'] = 'Redir'; 
						$msg['location'] = $this->session->userdata ('redir');
						
					   
					} */
					$flag = false;
				}
            }
			else if($query_user->num_rows () == 1){
				
				$users = $query_user->result();
				$this->session->set_userdata ('is_logged', TRUE);
				$msg['status'] = "OK";             
				$this->session->set_userdata ('user', $users);
				$current_date = date('Y-m-d H:i:s');
				// echo 'before => '.$users[0]->currently_online;
				// $current_online = ((int)$users[0]->currently_online + 1);
				$users_data = array (
                    'latest_source_ip' => $_SERVER['REMOTE_ADDR'],
                    'latest_login_date ' => $current_date
                );
                // $this->db->where ("(email = '" . $email . "')");
                // $this->db->where ('password', md5 ($password)); // @sid - 28-09-2018 Commnet above 2 line as we don't need to check for this 2 field as we have user id we should direct check with user id
				$this->db->where('user_id', $users[0]->user_id);
                $this->db->update ('users', $users_data);
                
                //@sid set is_authorized session [17-01-2019] ==> put this line here because user log function called before is_authorized flag set
			    $this->session->set_userdata('is_authorized', $flag); 
				
				########## remove the obsolete entries of  user from user_log table ####
				$this->db->delete('user_log', array('user_id' => $users[0]->user_id,'session_expiration_time <= '=>date('Y-m-d H:i:s')));
				
				
				$user_log_str = 'user '.$users[0]->profile_name.'( '.$users[0]->email.' ) logged in. source IP '.get_client_ip().' browser '.$_SERVER['HTTP_USER_AGENT'];
				$time_arr = explode(':', USER_SESSION_AVAILABILITY_TIME);
				$data = [
					'user_id' => $users[0]->user_id,
					'source_ip' => $_SERVER['REMOTE_ADDR'],
					'timestamp' => time()*1000,
					'login_date' => $current_date,
					'user_session_id' => session_id(),
					'session_expiration_time' => !empty($time_arr) ? date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds', strtotime($current_date))): $current_date,
					'last_activity_time' => $current_date
				];
				$user_log_id = $this->insert_into_user_log($data);
				$this->session->set_userdata('user_log_id', $user_log_id);
					
				$msg['redir_kind'] = 'Default';
				
				if(!empty($last_redirect_url)) {
					$msg['location'] = $last_redirect_url;
				}else{
					$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
				}
				$this->session->unset_userdata('last_redirect_url');
				$this->db->where('user_id', $users[0]->user_id);
				$updated_user = $this->db->get('users')->row_array();
				$user_display_str = $this->config->item('login_user_activity_log_displayed_message');
				$user_display_str = str_replace('{user_connection_source_ip}', $_SERVER['REMOTE_ADDR'], $user_display_str);
				$user_display_str = str_replace('{user_valid_connections_online_count}', $updated_user['currently_online'], $user_display_str);
				user_display_log($user_display_str);
			}
            else
            {
                $msg['status'] = 'FAIL';
                $msg['errors'][$i]['id'] = 'wrong_email_password_error';
                $msg['errors'][$i]['message'] = $this->config->item('login_failed_message'); 
            }
			//@sid set is_authorized session
			$this->session->set_userdata('is_authorized', $flag); 
        }
        $msg['all_userdata'] = $this->session->all_userdata();
        unset ($_POST);
		
		// echo'<pre>';print_r($msg);die;
		
        echo json_encode ($msg);
    }

    public function check_login_facebook ($fid)
    {
        $this->db->select ('user_id, username, email,ldate,membership_plan,membership_end,membership_upgrade,acc_balance,status');
        $this->db->where ('facebook_id', $fid);
        $this->db->where ('status', 'Y');
        $this->db->where ('v_stat', 'Y');
        $this->db->where ('sync_facebook', 'Y');
        $query = $this->db->get ('user');
        $result = $query->result ();    
        var_dump($result);		
        if ($query->num_rows () == 1)
        {
            $this->session->set_userdata ('is_logged', TRUE);
            if ($result[0]->status == 'S')
            {
                $this->session->set_userdata ('status', 'S');
            }
            /* Membership Auto Upgrade Code Start */
            if ($result[0]->membership_plan != 1)
            {
                if (strtotime (date ("Y-m-d")) > strtotime ($result[0]->membership_end))
                {
                    if ($result[0]->membership_upgrade == "Y")
                    {
                        $plan_charge = $this->auto_model->getFeild ("price", "membership_plan", "id", $result[0]->membership_plan);
                        $plan_day = $this->auto_model->getFeild ("days", "membership_plan", "id", $result[0]->membership_plan);
                        $plan_name = $this->auto_model->getFeild ("name", "membership_plan", "id", $result[0]->membership_plan);
                        $admin_email = $this->auto_model->getFeild ("admin_mail", "setting");
                        $first_name = $this->auto_model->getFeild ("first_name", "users", "user_id", $result[0]->user_id);
                        $last_name = $this->auto_model->getFeild ("last_name", "users", "user_id", $result[0]->user_id);

                        if ($result[0]->acc_balance >= $plan_charge)
                        {
                            $data_transaction = array (
                                "user_id" => $result[0]->user_id,
                                "amount" => $plan_charge,
                                "transction_type" => "DR",
                                "transaction_for" => "Upgrade Membership",
                                "transction_date" => date ("Y-m-d"),
                                "status" => "Y"
                            );

                            $data_user = array (
                                "acc_balance" => ($result[0]->acc_balance - $plan_charge),
                                "membership_plan" => $result[0]->membership_plan,
                                "membership_start" => date ("Y-m-d"),
                                "membership_end" => date ('Y-m-d', strtotime ("+" . $plan_day . " day", strtotime (date ("Y-m-d"))))
                            );

                        }
                        else
                        {
                            $data_user = array (
                                "membership_plan" => "1",
                                "membership_start" => date ("Y-m-d"),
                            );
                            $this->updateuser ($data_user, $result[0]->user_id);

                            $data_parse = array (
                                'username' => $first_name . " " . $last_name
                            );

                            $this->mailtemplete->send_mail ($admin_email, $result[0]->email, "degrade_membership", $data_parse);
                        }
                    }
                    else
                    {
                        $data_user = array (
                            "membership_plan" => "1",
                            "membership_start" => date ("Y-m-d"),
                        );
                        $this->updateuser ($data_user, $result[0]->user_id);
                        $data_parse = array (
                            'username' => $first_name . " " . $last_name
                        );
                        $this->mailtemplete->send_mail ($admin_email, $result[0]->email, "degrade_membership", $data_parse);
                    }
                }
            }
            $this->session->set_userdata ('user', $result);
            if (date ("Y-m-d") >= $result[0]->membership_end)
            {
                if ($result[0]->membership_upgrade == "Y")
                {

                    $this->db->select ("*");
                    $this->db->order_by ('id');
                    $res = $this->db->get_where ("membership_plan", array ("status" => "Y", "id" => $result[0]->membership_plan));
                    $plane = $res->result ();

                    $balance = $this->auto_model->getFeild ('acc_balance', 'user', 'user_id', $result[0]->user_id);

                    if ($balance >= $plane[0]->price)
                    {
                        $amount = $balance - $plane[0]->price;
                        $data_transaction = array (
                            "user_id" => $result[0]->user_id,
                            "amount" => $plane[0]->price,
                            "transction_type" => "DR",
                            "transaction_for" => "Upgrade Membership",
                            "transction_date" => date ("Y-m-d H:i:s"),
                            "status" => "Y"
                        );
                        $data_user = array (
                            "membership_plan" => $plane[0]->id,
                            "membership_start" => date ("Y-m-d"),
                            "membership_end" => date ('Y-m-d', strtotime ("+" . $plane[0]->days . " day", strtotime (date ("Y-m-d")))),
                            "membership_upgrade" => $result[0]->membership_upgrade,
                            "acc_balance" => $amount
                        );
                  
                    }
                }
                else
                {
                    $data_user = array (
                        "membership_plan" => "1",
                        "membership_start" => date ("Y-m-d"),
                        "membership_end" => date ('Y-m-d', strtotime ("+30 day", strtotime (date ("Y-m-d"))))
                    );
                    $this->Membership_model->updateUser ($data_user, $result[0]->user_id);
                }
            }
            $data = array (
                'ip' => $_SERVER['REMOTE_ADDR']
            );
            $this->db->set ('ldate', 'NOW()', FALSE);
            $this->db->where ('facebook_id', $fid);
            $this->db->update ('user', $data);
            return true;
        }
        else
        {
            return false;
        }
    }
	
	//@sid Insert into user log
	public function insert_into_user_log($data) {
		if($this->db->insert('user_log', $data)) {
			return $this->db->insert_id();
		}
		return 0;
	}
	
	// delete the unverified user from table "users_new_registrations_pending_verification" and send email.
	public function remove_unverified_user($user_data){
		
	
		$name =  (($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y' )) ? $user_data['first_name'] . ' ' . $user_data['last_name'] : $user_data['company_name'];
		$email =  $user_data['email'];
		$to = $email;
		/* echo "<pre>";
		print_r($user_record); */
		if(($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_data['is_authorized_physical_person'] == 'Y' ))
		{
			
			// Define email variables for male user
			if($user_data['gender'] == 'M'){
			
				if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
			
					$cc = $this->config->item('email_cc_unverified_user_account_delete_personal_male');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_personal_male');
					$from = $this->config->item('email_from_unverified_user_account_delete_personal_male');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_personal_male');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_personal_male');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_personal_male');
					$message = $this->config->item('email_message_unverified_user_account_delete_personal_male');
					$message = str_replace(array('{name}'),array($name),$message);
				}else{
					$cc = $this->config->item('email_cc_unverified_user_account_delete_company_app_male');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_company_app_male');
					$from = $this->config->item('email_from_unverified_user_account_delete_company_app_male');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_company_app_male');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_company_app_male');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_company_app_male');
					$message = $this->config->item('email_message_unverified_user_account_delete_company_app_male');
					$message = str_replace(array('{name}'),array($name),$message);
				}
			
			}else{
				// Define email variables for female user
				if($user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
					$cc = $this->config->item('email_cc_unverified_user_account_delete_personal_female');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_personal_female');
					$from = $this->config->item('email_from_unverified_user_account_delete_personal_female');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_personal_female');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_personal_female');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_personal_female');
					$message = $this->config->item('email_message_unverified_user_account_delete_personal_female');
					$message = str_replace(array('{name}'),array($name),$message);
				}else{
					$cc = $this->config->item('email_cc_unverified_user_account_delete_company_app_female');
					$bcc = $this->config->item('email_bcc_unverified_user_account_delete_company_app_female');
					$from = $this->config->item('email_from_unverified_user_account_delete_company_app_female');
					$reply_to = $this->config->item('email_reply_to_unverified_user_account_delete_company_app_female');
					$from_name = $this->config->item('email_from_name_unverified_user_account_delete_company_app_female');
					$subject = $this->config->item('email_subject_unverified_user_account_delete_company_app_female');
					$message = $this->config->item('email_message_unverified_user_account_delete_company_app_female');
					$message = str_replace(array('{name}'),array($name),$message);
				}
			
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
		$user_id = $user_data['user_id'];
		$this->db->delete('users_new_registrations_pending_verification', array ('user_id' => $user_id));
		$this->email->send ();
	}
}

?>