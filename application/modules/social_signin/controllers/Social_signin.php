<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Social_signin extends MX_Controller
{
	public function __construct ()
    {	
			$this->load->model ('signin/Signin_model');
        parent::__construct ();
	}
	
	/**
	 * This method is used to redirect to linkedin page for authorization 
	*/
	public function signin_with_linkedin($param) {
		if(empty($param)) {
			show_custom_404_page(); //show custom 404 page
			return;
		} else {
			if(!in_array($param, ['signin', 'find_project', 'find_professionals', 'project_detail', 'user_profile', 'portfolio_standalone_page', 'post_project', 'edit_temporary_project_preview', 'temporary_project_preview']) && !check_session_validity()) {
				show_custom_404_page(); //show custom 404 page
				return;
			}
		}
			$user = $this->session->userdata('user');
			
			$linkedin_authorization_url = 'https://www.linkedin.com/oauth/v2/authorization';

			$data['response_type'] = 'code';
			$data['client_id'] = $this->config->item('LINKEDIN_CLIENT_ID');
			$data['redirect_uri'] = site_url('social_signin/linkedin_auth_callback');
			$data['state'] = uniqid();
			$data['scope'] = 'r_liteprofile r_emailaddress';

			// Build the query string from the data.
			$queryString = http_build_query($data);

			$cookie= array(
			'name'   => 'linkedin_authentication',
			'value'  => base64_encode(json_encode(['user_id' => $user[0]->user_id, 'page' => $param])),
			'expire' => time() + 3600,
			'path' => '/',
			'httponly' => false
			);
			$this->input->set_cookie($cookie);

			header('location:' . $linkedin_authorization_url . '?' . $queryString);
      exit();
		
	}

	/*
	 * @sid this method  is used to update data related to linkedin social login for user who connect his/her account
	*/
	public function linkedin_auth_callback() {
		$uri_segment = $this->uri->segment(3);
		if(isset($uri_segment)) {
			show_custom_404_page(); //show custom 404 page
		}

		$linkedin_auth_cookie = $this->input->cookie('linkedin_authentication', TRUE);
		if(!empty($linkedin_auth_cookie)) {
			$linkedin_auth_cookie = json_decode(base64_decode($linkedin_auth_cookie), true);
			if(!empty($linkedin_auth_cookie)) {
				delete_cookie("linkedin_authentication");
				$param = $linkedin_auth_cookie['page'];
			}
		}

		$linkedin_success_param = $this->input->cookie('linkedin_success_param', true);
		if(!empty($linkedin_success_param)) {
			$linkedin_success_param = json_decode(($linkedin_success_param), true);
			if(!empty($linkedin_success_param)) {
				delete_cookie("linkedin_success_param");
			}
		}

		$page_id = !empty($linkedin_success_param['page_id']) ? $linkedin_success_param['page_id'] : '';
		$tmp_project_id = !empty($linkedin_success_param['temp_project_id']) ? $linkedin_success_param['temp_project_id'] : '';
		if(!empty($linkedin_success_param['user_id'])) {
			$this->session->set_userdata('hire_me_user_id', $linkedin_success_param['user_id']);
		}
		$page_array  = array(
			'post_draft_project'=>$this->config->item('dashboard_page_url'),
			'post_publish_project'=>$this->config->item('dashboard_page_url'),
			'listing'=>$this->config->item('project_detail_page_url')."?id=".$page_id,
			'detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,
			'project_detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,
			'hidden_project'=>$this->config->item('project_detail_page_url')."?id=".$page_id,
			'user_profile'=>$page_id,'find_project'=>$this->config->item('find_projects_page_url'),
			'find_professionals'=>$this->config->item('find_professionals_page_url'),
			'portfolio_standalone_page'=>$this->config->item('portfolio_standalone_page_url')."?id=".$page_id,
			'post_project' => $this->config->item('post_project_page_url')."?id=".$tmp_project_id,
			'temporary_project_preview' => $this->config->item('post_project_page_url')."?id=".$tmp_project_id,
			'edit_temporary_project_preview' => $this->config->item('post_project_page_url')."?id=".$tmp_project_id
		);

		if(!empty($param) && in_array($param, ['signin', 'find_project', 'find_professionals', 'project_detail', 'user_profile', 'portfolio_standalone_page', 'post_project', 'edit_temporary_project_preview', 'temporary_project_preview'])) {
			if(!empty($_GET['error'])) {
				if(array_key_exists($param, $page_array)) {
					$this->session->set_userdata('login_popup_op', 1);
					redirect(base_url($page_array[$param]));
				} else {
					redirect(base_url($this->config->item('signin_page_url')));
				}
			} else {
				$res = $this->get_access_token_from_authorization_code_receive_from_linkedin($_GET['code'], $param);
				$res = json_decode($res, true);
				
				if(array_key_exists('access_token', $res)) {
					$row = $this->get_linkedin_login_member_info($res['access_token']);
					if(!empty($row)) {
						$users = $this->db->get_where('users', ['user_linkedin_associated_email' => $row['email'], 'sync_linkedin' => 'y'])->result();
						
						if(!empty($users)) {
							$this->session->set_userdata ('is_logged', TRUE);
							$this->session->set_userdata ('is_authorized', TRUE);
							$this->session->set_userdata ('user', $users);

							if(!empty($linkedin_success_param['user_id'])) {
								$this->session->set_userdata('hire_me_user_id', $linkedin_success_param['user_id']);
							}
							
							if(!empty($linkedin_success_param['page_no'])) {
								$this->session->set_userdata('page', $linkedin_success_param['page_no']);
							}

							if(!empty($linkedin_success_param['filter_arr'])) {
								$this->session->set_userdata('filter_arr', $linkedin_success_param['filter_arr']);
							}

							########## remove the obsolete entries of  user from user_log table ####
							$this->db->delete('user_log', array('user_id' => $users[0]->user_id,'session_expiration_time <= '=>date('Y-m-d H:i:s')));
							
							$current_date = date('Y-m-d H:i:s');
							$users_data = array (
								'latest_source_ip' => $_SERVER['REMOTE_ADDR'],
								'latest_login_date ' => $current_date
							);
							$this->db->where('user_id', $users[0]->user_id);
							$this->db->update ('users', $users_data);
							
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
							$user_log_id = $this->Signin_model->insert_into_user_log($data);
							
							$this->session->set_userdata('user_log_id', $user_log_id);
								
							$this->db->where('user_id', $users[0]->user_id);
							$updated_user = $this->db->get('users')->row_array();
							$user_display_str = $this->config->item('login_user_activity_log_displayed_message');
							$user_display_str = str_replace('{user_connection_source_ip}', $_SERVER['REMOTE_ADDR'], $user_display_str);
							$user_display_str = str_replace('{user_valid_connections_online_count}', $updated_user['currently_online'], $user_display_str);
							user_display_log($user_display_str);
							
							if(array_key_exists($param, $page_array)) {	
								redirect(base_url($page_array[$param]));
							} else {
								redirect(base_url($this->config->item('dashboard_page_url')));
							}
						} else {
							
							$this->session->set_userdata('linkedin_signin_error', $this->config->item('linkedin_social_login_failed_message'));
							
							if(array_key_exists($param, $page_array)) {
								$this->session->set_userdata('login_popup_op', 1);
								redirect(base_url($page_array[$param]));
							} else {
								redirect(base_url($this->config->item('signin_page_url')));
							}
						}
					} else {
							if(array_key_exists($param, $page_array)) {
								redirect(base_url($page_array[$param]));
							} else {
								redirect(base_url($this->config->item('signin_page_url')));
							}
					}
				} else {
					if(array_key_exists($param, $page_array)) {
						$this->session->set_userdata('login_popup_op', 1);
						redirect(base_url($page_array[$param]));
					} else {
						redirect(base_url($this->config->item('signin_page_url')));
					}
				}
			}
		} else if(!empty($param) && $param == 'connect_linkedin') {
			$this->session->set_userdata('open_verfication_tab', 1);
			if(!empty($_GET['error'])) {
				redirect(base_url($this->config->item('account_management_account_login_details_page_url')));
			} else {
				$res = $this->get_access_token_from_authorization_code_receive_from_linkedin($_GET['code'], $param);
				$res = json_decode($res, true);
				
				if(array_key_exists('access_token', $res)) {
					$row = $this->get_linkedin_login_member_info($res['access_token']);
					if(!empty($row)) {
						if (check_session_validity()) {
							$user = $this->session->userdata('user')[0];
							if(!empty($linkedin_auth_cookie['user_id']) && $user->user_id == $linkedin_auth_cookie['user_id']) {
								
								if(!empty($row['id']) && !empty($row['email']) || !empty($row['profile_url']) ) {
										$update_data = [
											'sync_linkedin' => 'y',
											'user_linkedin_id' => $row['id'],
											'user_linkedin_profile_url' => !empty($row['profile_url']) ? $row['profile_url'] : null,
											'user_linkedin_associated_email' => $row['email']
										];
										// Check user already linked his account with system or not
										$linked_user = $this->db->get_where('users', ['user_linkedin_associated_email' => $row['email']])->row_array();
										if(empty($linked_user)) {
											$this->db->update('users', $update_data, ['user_id' => $user->user_id]);
										} else {
											$this->session->set_userdata('linkedin_error', $this->config->item('account_management_account_login_details_page_social_media_verification_section_user_attempts_connect_already_used_linkedin_account_error_msg'));
										}
								} else {
									$this->session->set_userdata('linkedin_error', $this->config->item('account_management_account_login_details_page_social_media_verification_section_connect_linkedin_account_error_msg'));
								}
							}
						}
					}
				}
				redirect(base_url($this->config->item('account_management_account_login_details_page_url')));
			}
			
		}
	}

	// This method is used to get access tocken of logged in user
	private function get_access_token_from_authorization_code_receive_from_linkedin($code, $param) {
		$linkedin_url = 'https://www.linkedin.com/oauth/v2/accessToken';

		$data['grant_type'] = 'authorization_code';
		$data['code'] = $code;
		$data['redirect_uri'] = site_url('social_signin/linkedin_auth_callback');
		$data['client_id'] = $this->config->item('LINKEDIN_CLIENT_ID');
		$data['client_secret'] = $this->config->item('LINKEDIN_CLIENT_SECRETE');

		// Build the query string from the data.
		$req = http_build_query($data);
		
		$ch = curl_init($linkedin_url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		
		$res = curl_exec($ch);

		curl_close($ch);
		return $res;
	}


	private function get_linkedin_login_member_info($access_token) {

		$tmp = [];
		$linkedin_url = 'https://api.linkedin.com/v2/me?projection=(id,profilePicture(displayImage~:playableStreams))';
	
		$ch = curl_init($linkedin_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$access_token]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$res = curl_exec($ch);
		$res = json_decode($res, true);
		if(array_key_exists('id', $res)) {
			$tmp['id'] = $res['id'];
		}
		if(array_key_exists('profilePicture', $res)) {
			$tmp['profile_url'] = $res['profilePicture']['displayImage~']['elements'][0]['identifiers'][0]['identifier'];
		}

		$linkedin_url = 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(id,elements*(handle~))';

		$ch = curl_init($linkedin_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$access_token]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		$res = json_decode($res, true);
		if(array_key_exists('elements', $res)) {
			$tmp['email'] = $res['elements'][0]['handle~']['emailAddress'];
		}
		curl_close($ch);
		return $tmp;
	}

	/*
	 * @sid this method  is used to update data related to facebook social login for user who connect his/her account
	*/
	public function save_facebook_data() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if (check_session_validity()) {
			$user = $this->session->userdata('user')[0];
			$row = $this->input->post();
			if($row['user_id'] != $user->user_id){
				echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			if(!empty($row)) {
				if(!empty($row['id']) && !empty($row['email'])) {
					$update_data = [
						'sync_facebook' => 'Y',
						'user_facebook_id' => $row['id'],
						'user_facebook_associated_email' => $row['email']
					];
					// Check user already linked his account with system or not
					$fb_user = $this->db->get_where('users', ['user_facebook_id' => $row['id']])->row_array();
					if(empty($fb_user)) {
						$this->db->update('users', $update_data, ['user_id' => $user->user_id]);
						// $this->db->update('users_profile_completion_parameters_tracking', ['facebook_connection'=>'Y'], ['user_id' => $user->user_id]);
						$data['user'] = $this->db->get_where('users', ['user_id' => $user->user_id])->row_array();
						$msg = $this->config->item('account_management_account_login_details_page_social_media_verification_section_user_connected_facebook_account_confirmation_label_txt');
						$msg = str_replace('{user_facebook_email_id}', $row['email'], $msg);
						$res = [
							'status' => 200,
							'message' => $msg
						];
					} else {
						$res = ['status' => 422, 'error' => $this->config->item('account_management_account_login_details_page_social_media_verification_section_user_attempts_connect_already_used_facebook_account_error_msg')];
					}	
				} else {
					$res = ['status' => 422, 'error' => $this->config->item('account_management_account_login_details_page_social_media_verification_section_connect_facebook_account_error_msg')];
				}
				
				echo json_encode($res);
			} else {
				echo json_encode(['status', 400]);
			}
			
		} else {
			echo json_encode(['status' => 400]);
		}
		return;
	}
	/*
	 * @sid this method is used to update data related to linkedin social login for user who decided to disconnect from his account
	*/
	public function disconnect_from_linkedin() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if (check_session_validity()) {
			$user = $this->session->userdata('user')[0];
			$row = $this->input->post();
			if($row['user_id'] != $user->user_id){
				echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			$update_data = [
				'sync_linkedin' => 'n',
				'user_linkedin_id' => null,
				'user_linkedin_profile_url' => null,
				'user_linkedin_associated_email' => null
			];
			$this->db->update('users', $update_data, ['user_id' => $user->user_id]);
			
			$data['user'] = $this->db->get_where('users', ['user_id' => $user->user_id])->row_array();
			$res = [
				'status' => 200
			];
			echo json_encode($res);
		} else {
			echo json_encode(['status' => 400]);
		}
		return;
	}
	/*
	 * @sid this method is used to update data related to facebook social login for user who decided to disconnect from his account
	*/
	public function disconnect_from_facebook() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if (check_session_validity()) {
			$user = $this->session->userdata('user')[0];
			$row = $this->input->post();
			if($row['user_id'] != $user->user_id){
				echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			$update_data = [
				'sync_facebook' => 'n',
				'user_facebook_id' => null,
				'user_facebook_associated_email' => null
				
			];
			$this->db->update('users', $update_data, ['user_id' => $user->user_id]);
			
			// $this->db->update('users_profile_completion_parameters_tracking', ['facebook_connection'=>'N'], ['user_id' => $user->user_id]);
			
			$data['user'] = $this->db->get_where('users', ['user_id' => $user->user_id])->row_array();
			
			$res = [
				'status' => 200
			];
			echo json_encode($res);
		} else {
			echo json_encode(['status' => 400]);
		}
		return;
	}
}
?>