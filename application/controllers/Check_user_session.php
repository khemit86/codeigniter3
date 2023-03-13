<?php
if ( ! defined ('BASEPATH'))
    exit ('No direct script access allowed');

class Check_user_session extends MX_Controller {
	public function __construct ()
  {	
		parent::__construct ();
  }
	//this function is used by node server /var/www/html/services_portal/node_servers/projects_management to connect node server with user details (serv_users)
	// This method is used from project detail, find professionals, user profile page to check user session validity when user click on contact me button
  public function getSession($message = "1") {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
		}
		if(!check_session_validity()) {
			if($this->input->post('user_id')) {
				$this->session->set_userdata('hire_me_user_id', $this->input->post('user_id'));
			}
			if($this->input->post('page_no')) {
				$this->session->set_userdata('page', $this->input->post('page_no'));
			}
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata ('user');
		$session_user_id = $this->input->post('session_user_id');
		if(!empty($session_user_id) && $user[0]->user_id != $session_user_id) {
			$res['popup_heading'] = $this->config->item('popup_alert_heading');
			$res['warning'] = $this->config->item('different_users_session_conflict_message');
			$res['status'] = 440;
			echo json_encode($res);
			return;
		}
		$data = [];
		if(!empty($user)) {
			$array = array ('user_id' => $user[0]->user_id);
			$array = array ('email' => $user[0]->email);
			$data['status'] = 200;
			// check unverified user entry into "users_new_registrations_pending_verification" table start here ######
			$user_data_unverified_user = $this->autoload_model->get_table_row ('users_new_registrations_pending_verification', $array);
			// check user entry into "users_new_registrations_pending_verification" table end here ########
			
			
			// check verified user entry into "users" table start here ######
			$user_data_verified_user = $this->autoload_model->get_table_row ('users', $array);
			// check verified user entry into "users" table end here ######
			if(!empty($user_data_unverified_user))
			{
				$this->user_email = $user_data_unverified_user->email;
				$user_first_name = $user_data_unverified_user->first_name;
				$user_last_name = $user_data_unverified_user->last_name;
				$data["user_id"] = $user[0]->user_id;
				$data["first_name"] = $user_first_name;
				$data["last_name"] = $user_last_name;
				$data["user_log_id"] = '0';
			
			}elseif(!empty($user_data_verified_user)){
			
				$this->user_email = $user_data_verified_user->email;
				$user_first_name = $user_data_verified_user->first_name;
				$user_last_name = $user_data_verified_user->last_name;
				$data["user_id"] = $user[0]->user_id;
				$data['user_log_id'] = $this->session->userdata('user_log_id');
				$data["first_name"] = $user_first_name;
				$data["last_name"] = $user_last_name;
			
			
			}else{
				echo json_encode(['status' => 404]);
				return;
			}
		}  
		echo json_encode($data);
  }

}