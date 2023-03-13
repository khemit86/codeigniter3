<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Signin extends MX_Controller
{

    /**
     * Description: this used for check the user is exists or not if exists then it redirect to this site
     * Parameter: username and password
     */
    public function __construct ()
    {
        $this->load->model ('Signin_model');
        $this->load->model ('membership/Membership_model');
        $this->load->model ('signup/Signup_model');
		$this->load->model('projects/Projects_model'); //used index, edit_job
		$this->load->helper('text');
        parent::__construct ();
    }
	

	/**
     * Description: this method load the sigin form.
     */
    public function signin ()
    {
		
        if(validate_session ()){
			
			redirect (VPATH . $this->config->item('dashboard_page_url'));
		}
        if ($this->uri->segment (3))
        {
            $uid = base64_decode ($this->uri->segment (3));
            $new_data['status'] = 'Y';
            $new_data['v_stat'] = 'Y';
            $code = $this->uri->segment (4);
        }

        $data['current_page'] = 'signin';

		$_SESSION["loggedout"] = "";
		if(isset($_SESSION["loggedout"])) {
			$data["loggedout"] = $_SESSION["loggedout"];
		}
        $lay = array();

		########## set the signin title meta tag and meta description  start here #########
		$signin_title_meta_tag = $this->config->item('signin_page_title_meta_tag');
		$signin_title_meta_tag = $signin_title_meta_tag;

		$signin_description_meta_tag = $this->config->item('signin_page_description_meta_tag');
		$signin_description_meta_tag = $signin_description_meta_tag;
		$_SESSION['share_title_short'] = $signin_title_meta_tag;
        $_SESSION['share_description'] = $signin_description_meta_tag;
		$data['meta_tag'] = '<title>' . $signin_title_meta_tag . '</title><meta name="description" content="' . $signin_description_meta_tag . '"/>';

		########## set the signin title tag start end #########
	

        $this->layout->view ('signin', $lay, $data, 'normal');   
    }


	/**
     * Description: this function is executed when user click on Log in button on sigin page
     * 
     */
    public function signin_submit ()
    {	
		if(!$this->input->is_ajax_request()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		
        if ($this->input->post ())
        {
            $post_data = $this->input->post ();
			
			$insert = $this->Signin_model->login ($post_data);
        }
    }
	/*
	 * @sid login with linked in if account is connected with it
	*/
	public function login_with_social_media() {
	
		if(!$this->input->is_ajax_request()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
	
		$row = $this->input->post();
		if(!empty($row)) {
			if($row['media_type'] == "linkedin") {
				$users = $this->db->get_where('users', ['user_linkedin_id' => $row['id'], 'sync_linkedin' => 'y'])->result();
			} else {
				$this->db->where('user_facebook_id' , $row['id']);
				$this->db->where('sync_facebook', 'y');
				$users = $this->db->get('users')->result();
			}
			if(!empty($users)) {
				$this->session->set_userdata ('is_logged', TRUE);
				$this->session->set_userdata ('is_authorized', TRUE);
				$this->session->set_userdata ('user', $users);

				$page_id = $this->input->post ('page_id');
				$page_type = $this->input->post ('page_type');

				if($this->input->post('user_id')) {
					$this->session->set_userdata('hire_me_user_id', $this->input->post('user_id'));
				}
				
				if($this->input->post('page_no')) {
					$this->session->set_userdata('page', $this->input->post('page_no'));
				}

				if($this->input->post('filter_arr')) {
					$this->session->set_userdata('filter_arr', $this->input->post('filter_arr'));
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

				$page_array  = array('post_draft_project'=>$this->config->item('dashboard_page_url'),'post_publish_project'=>$this->config->item('dashboard_page_url'),'listing'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'project_detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'hidden_project'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'user_profile'=>$page_id,'find_project'=>$this->config->item('find_projects_page_url'),'find_professionals'=>$this->config->item('find_professionals_page_url'),'portfolio_standalone_page'=>$this->config->item('portfolio_standalone_page_url')."?id=".$page_id);
				$msg['location'] = VPATH.(!empty($page_type) ?$page_array[$page_type] : $this->config->item('dashboard_page_url'));
				
				echo json_encode(['status' => 200, 'location' => $msg['location']]);
			} else {
				echo json_encode(['status' => 400, 'error' => $row['media_type'] == "linkedin" ? $this->config->item('linkedin_social_login_failed_message') : $this->config->item('facebook_social_login_failed_message')]);
			}
		} else {
			echo json_encode(['status' => 400]);
		}
		return;
	}
	
	/**
	* This function is used to load the body of login popup .
	*/
	public function load_signin_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('page_type'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			
			$page_id = $this->input->post ('page_id');
			$page_type = $this->input->post ('page_type');
			if(in_array($page_type, array('project_detail','user_profile','find_project','find_professionals','portfolio_standalone_page','hidden_project'))){
				if(check_session_validity()){
					$msg['status'] = 200;
					$msg['page_type'] = $page_type;
					$msg['login_status'] = '1';
					
					$page_array  = array('project_detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'hidden_project'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'user_profile'=>$page_id,'find_project'=>$this->config->item('find_projects_page_url'),'find_professionals'=>$this->config->item('find_professionals_page_url'),'portfolio_standalone_page'=>$this->config->item('portfolio_standalone_page_url')."?id=".$page_id);
					$msg['location'] =VPATH.$page_array[$page_type];
					echo json_encode ($msg);die;
				
				}else{
					echo json_encode(['status' => 200,'login_status'=>'0','page_type'=>$page_type,'page_id'=>$page_id,'location'=>'','data'=>$this->load->view('signin_popup',array('page_id'=>$page_id,'page_type'=>$page_type), true)]);
					die;
				}
			
			
			
			}
			if($page_type == 'listing'){
				if(check_session_validity()){
					$msg['status'] = 200;
					$msg['page_type'] = $page_type;
					$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$page_id;
					echo json_encode ($msg);die;
				}else{
				
					echo json_encode(['status' => 200,'login_status'=>'0','page_type'=>$page_type,'location'=>'','data'=>$this->load->view('signin_popup',array('page_type'=>$page_type,'page_id'=>$page_id), true)]);
					die;
				
				}
			
			}
			if($page_type == 'detail'){
				if(check_session_validity()){
					$user = $this->session->userdata ('user');
					$project_status_table_array = $this->Projects_model->get_project_status_type($page_id);
					
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_deleted_project')]);
						die;
					}
					if($project_status_table_array['project_status'] == 'expired'){
						if($project_status_table_array['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_status_table_array['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_cancelled_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
				
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $page_id)
					->get()->row_array();
					if($project_data['project_owner_id'] == $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					if(strtotime($project_data['project_expiration_date']) <= time()  && $project_status_table_array['project_status'] = 'open_for_bidding'){
						if($project_data['project_expiration_date'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					}
				
					if($project_data['project_type'] == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					}else if($project_data['project_type'] == 'hourly'){
							$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
							$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
							$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					}else if($project_data['project_type'] == 'fulltime'){
							$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
							$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
							$inprogress_bid_table_name = '';
					}
					$check_already_active_bid = $this->db->where(['project_id' => $page_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					if($check_already_active_bid > 0){
						
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_already_posted_bid_same_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_already_posted_bid_same_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					}
					if($project_data['project_type'] == 'fulltime'){
						$check_awarded_bid = $this->db->where(['fulltime_project_id' => $page_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					} else {
						$check_awarded_bid = $this->db->where(['project_id' => $page_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					}
					if($check_awarded_bid  > 0){
						if($project_status_table_array['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if($project_data['project_type'] == 'fulltime'){ 
						$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $page_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					} else {
						$check_in_progress_bid = $this->db->where(['project_id' => $page_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					}
					if($check_in_progress_bid  > 0){
						if($project_status_table_array['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					
					if($project_status_table_array['project_status'] == 'awarded'){
						if($project_status_table_array['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_different_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_different_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_status_table_array['project_status'] == 'in_progress'){
						if($project_status_table_array['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_different_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_different_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
				
					$login_user_detail = $this->db // get the user detail
					->select('u.user_id,ud.current_membership_plan_id')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $user[0]->user_id)
					->get()->row_array();
					$no_bid_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('projects_active_bids_users_attachments_tracking')
					->where ('project_id', $page_id)
					->where ('user_id', $user[0]->user_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					
					$msg['status'] = 200;
					$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$page_id;
					$msg['page_id'] = $page_id;
					$msg['page_type'] = $page_type;
					$msg['login_status'] = '1';
					
					
					//$msg['data'] = $this->load->view('bidding/place_bid_form',array('login_user_detail'=>$login_user_detail,'project_data'=>$project_data,'no_bid_attachment_uploaded_user'=>$no_bid_attachment_uploaded_user), true);
					echo json_encode ($msg);die;
				
				}else{
					echo json_encode(['status' => 200,'login_status'=>'0','page_type'=>$page_type,'location'=>'','data'=>$this->load->view('signin_popup',array('page_type'=>$page_type,'page_id'=>$page_id), true)]);
					die;
				
				}
				
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
	
	/**
	* Description: this function is executed when user will login from popup
     * 
     */
    public function signin_submit_popup ()
    {	
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
		}
        if ($this->input->post ())
        {
			$this->load->helper ('date');
            $post_data = $this->input->post ();
			$i = 0;
			$email = $this->input->post ('email');
			$password = $this->input->post ('password');
			$pageredirect = $this->input->post ('pageredirect');
			if ($email == '')
			{
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'email_error';
				$msg['errors'][$i]['message'] = $this->config->item('enter_email_validation_signin_message');
				$i ++;
			}
			if($email != '' && !filter_var ($email, FILTER_VALIDATE_EMAIL)){
				$msg['status'] = 'FAIL';
				$msg['errors'][$i]['id'] = 'email_error';
				$msg['errors'][$i]['message'] = $this->config->item('invalid_email_validation_signin_message');
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
				$this->db->select ('user_id, profile_name, password, password_reset_token,account_type,verification_code,account_type, email,automatic_verification_code_generate_time,verification_code_manual_request_time,verification_code');
				$this->db->where ("(email = '" . $email . "')"); 
				$this->db->where ('password', md5 ($password));
				$query = $this->db->get ('users_new_registrations_pending_verification');
				
				$this->db->select ('*');
				$this->db->from('users');
				$this->db->where ("email", $email); 
				$this->db->where ('password', md5 ($password));
				$query_user = $this->db->get ();
				if ($query->num_rows () == 1)
				{
					$result = $query->result ();
					$this->session->set_userdata ('is_logged',true);
					$this->session->set_userdata('is_authorized', false);
					$this->session->set_userdata ('user', $result);
					$msg['status'] = "OK";
					$msg['location'] = VPATH . $this->config->item('signup_activate_page_url').'?'.$this->config->item('signup_page_success_parameter')."=".Cryptor::doEncrypt($result[0]->user_id); 
				}
				else if($query_user->num_rows () == 1){
					
					$page_id = $this->input->post ('page_id');
					$page_type = $this->input->post ('page_type');
					$users = $query_user->result();
					
					$this->session->set_userdata ('is_logged', true);
					$this->session->set_userdata('is_authorized', true);
					//$this->session->set_userdata ('check_status_for_apply_now', 1);
					
					######### remove the obsolete entries of  user from user_log table ####
					$this->db->delete('user_log', array('user_id' => $users[0]->user_id,'session_expiration_time <= '=>date('Y-m-d H:i:s')));
					
					$msg['status'] = "OK";             
					$this->session->set_userdata ('user', $users);
					$current_date = date('Y-m-d H:i:s');
					$users_data = array (
						'latest_source_ip' => $_SERVER['REMOTE_ADDR'],
						'latest_login_date ' => $current_date
					);
					
					$this->db->where('user_id', $users[0]->user_id);
					$this->db->update ('users', $users_data);
					
					
					$user_log_str = 'user '.$users[0]->profile_name.'( '.$users[0]->email.' ) logged in. source IP '.get_client_ip().' browser '.$_SERVER['HTTP_USER_AGENT'];
					$time_arr = explode(':', USER_SESSION_AVAILABILITY_TIME);
					$data = [
						'user_id' => $users[0]->user_id,
						'source_ip' => get_client_ip(),
						'timestamp' => time()*1000,
						'login_date' => $current_date,
						'user_session_id' => session_id(),
						'session_expiration_time' => !empty($time_arr) ? date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds', strtotime($current_date))): $current_date,
						'last_activity_time' => $current_date
					];
					$user_log_id = $this->Signin_model->insert_into_user_log($data);
					$this->session->set_userdata('user_log_id', $user_log_id);
					
					if($this->input->post('user_id')) {
						$this->session->set_userdata('hire_me_user_id', $this->input->post('user_id'));
					}
					
					if($this->input->post('page_no')) {
						$this->session->set_userdata('page', $this->input->post('page_no'));
					}

					if($this->input->post('filter_arr')) {
						$this->session->set_userdata('filter_arr', $this->input->post('filter_arr'));
					}

					$page_array  = array('post_draft_project'=>$this->config->item('dashboard_page_url'),'post_publish_project'=>$this->config->item('dashboard_page_url'),'listing'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'project_detail'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'hidden_project'=>$this->config->item('project_detail_page_url')."?id=".$page_id,'user_profile'=>$page_id,'find_project'=>$this->config->item('find_projects_page_url'),'find_professionals'=>$this->config->item('find_professionals_page_url'),'portfolio_standalone_page'=>$this->config->item('portfolio_standalone_page_url')."?id=".$page_id);
					$msg['location'] =VPATH.$page_array[$page_type];
					
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
				
			}
			unset ($_POST);
			echo json_encode ($msg);
        }
    }
	
}
?>