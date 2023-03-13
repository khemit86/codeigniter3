<?php
if ( ! defined ('BASEPATH')) {
	exit ('No direct script access allowed');
}
require_once APPPATH.'third_party/users_chat/connect_cassandra_db.php';
class Bidding extends MX_Controller
{
    public function __construct ()
    {	
		parent::__construct ();
        $this->load->model ('Bidding_model');
        $this->load->model('projects/Projects_model'); //used index, edit_job
		$this->load->model('escrow/Escrow_model'); //used index, edit_job
		$this->load->model('user/User_model');
		$this->conn = new connect_cassandra_db();
    }
	
	/**
	* This function is used to load the edit bid form .
	*/
	public function load_place_bid_form(){
	
		if($this->input->is_ajax_request ()){
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				
				if($po_id == $user[0]->user_id){
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_deleted_project')]);
					die;
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name,pd.fulltime_project_expiration_date as project_expiration_date')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				
				
				if($project_status_table_array['project_status'] == 'expired'){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				
				if(strtotime($project_data['project_expiration_date']) <= time()  && $project_status_table_array['project_status'] = 'open_for_bidding'){
					if($project_data['project_type'] == 'fulltime'){
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
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					
				} else if($project_data['project_type'] == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_data['project_type'] == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				if($project_data['project_type'] == 'fulltime') {
					$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
				} else {
					$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
				}
				if($check_already_active_bid > 0){
					
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_already_posted_bid_same_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_already_posted_bid_same_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
				}
				if($project_data['project_type'] == 'fulltime') { 
					$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				} else {
					$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				}
				if($check_awarded_bid  > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
				
				}
				if($project_data['project_type'] == 'fulltime') { 
					$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
					$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				if($check_in_progress_bid  > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
				
				}
				
				if($project_data['project_type'] != 'fulltime'){
				
					if(!empty($completed_bid_table_name)) {
						$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
					}
				
					if(!empty($check_completed_bid) && $check_completed_bid  > 0){
						
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_on_completed_project');
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
				}	
				if($project_status_table_array['project_status'] == 'completed'){
					$error_message = $this->config->item('project_details_page_sp_view_different_sp_try_place_bid_on_completed_project');
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
					
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_different_sp_project');
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($project_status_table_array['project_status'] == 'in_progress'){
					
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_different_sp_project');
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
				->where ('project_id', $project_id)
				->where ('user_id', $user[0]->user_id)
				->get ()->num_rows ();// check the number of attachment of user into database
				echo json_encode(['status' => 200,'location'=>'','data'=>$this->load->view('place_bid_form',array('login_user_detail'=>$login_user_detail,'project_data'=>$project_data,'no_bid_attachment_uploaded_user'=>$no_bid_attachment_uploaded_user), true)]);
				die;
				
				
			}else{
				echo json_encode(['status' => 'FAILED','location'=>'','data'=>$this->load->view('login/login_popup',array('project_id'=>$project_id), true)]);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	} 
	 
	 
	## This function is used to save the bid data into the database
	public function post_bid(){
	
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		$project_id = $this->input->post ('project_id');
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
			die;
		}
		
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			$po_id =  Cryptor::doDecrypt($this->input->post ('po_id'));
			
			if($po_id == $user[0]->user_id){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			
			
			$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
			
			if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_deleted_project')]);
				die;
			}
				
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$project_data = $this->db // get the user detail
				->select('pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name,pd.fulltime_project_expiration_date as project_expiration_date')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.employer_id', 'left')
				->where('pd.fulltime_project_id', $project_id)
				->get()->row_array();
			}else{
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
			}	
			$profile_name = $project_data['profile_name'];
			if($project_status_table_array['project_status'] == 'expired'){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_expired_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			}
			
			
			
			if($project_status_table_array['project_status'] == 'cancelled'){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_cancelled_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_cancelled_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			}
			if(strtotime($project_data['project_expiration_date']) <= time()  && $project_status_table_array['project_status'] = 'open_for_bidding'){
				if($project_data['project_type'] == 'fulltime'){
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
				$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
			} else if($project_data['project_type'] == 'hourly'){
				$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
				$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
				$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
				$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
			} else if($project_data['project_type'] == 'fulltime'){
				$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
				$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
				$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
			}
			if($project_data['project_type'] == 'fulltime'){ 
				$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
			} else {
				$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
			}
			if($check_already_active_bid > 0){
				
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_already_posted_bid_same_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_already_posted_bid_same_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
			}
			if($project_data['project_type'] == 'fulltime') { 
				$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
			} else {
				$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
			}
			if($check_awarded_bid  > 0){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_same_sp_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_same_sp_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
			
			}
			if($project_data['project_type'] == 'fulltime') { 
				$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
			} else {
				$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
			}
			if($check_in_progress_bid  > 0){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_same_sp_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_same_sp_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
			}
			if(!empty($completed_bid_table_name)) {
				$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
			}
			if(!empty($check_completed_bid) && $check_completed_bid  > 0){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_on_completed_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_on_completed_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
			
			}
			if($project_status_table_array['project_status'] == 'completed'){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_different_sp_try_place_bid_on_completed_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_different_sp_try_place_bid_on_completed_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			}
			if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
				
				$error_message = $this->config->item('project_details_page_sp_view_place_bid_awarded_different_sp_project');
				
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			}
			if($project_status_table_array['project_status'] == 'in_progress'){
				if($project_data['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_different_sp_fulltime_project');
				}else{
					$error_message = $this->config->item('project_details_page_sp_view_place_bid_in_progress_different_sp_project');
				}
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
				die;
			}
			
			
			$post_data = $this->input->post ();
			$validation_data_array = $this->Bidding_model->post_update_bid_validation($post_data,$project_data);
			if($validation_data_array['status'] == 'SUCCESS'){ 
				
				if(!empty($_FILES)){
				
				
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				
				
					if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR)){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir)){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir)){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR)){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir)){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR)){
						echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
						die;
					}
					
				}
				
				$bid_data['project_id'] = $project_id;
				$bid_data['bidder_id'] = $user[0]->user_id;
				$bid_data['project_owner_id'] = $project_data['project_owner_id'];
				if($project_data['project_type'] == 'fixed'){
					if(!isset($post_data['biding_form_checkbox'])){
						$bid_data['initial_bidded_amount']= trim(str_replace(" ","",$post_data['bid_amount']));
						$bid_data['project_delivery_period']= trim(str_replace(" ","",$post_data['delivery_period']));
						$bid_data['bidding_dropdown_option']= 'NA';
					}else{
						$bid_data['initial_bidded_amount']= 0;
						$bid_data['project_delivery_period']= 0;
						$bid_data['bidding_dropdown_option']= trim($post_data['biding_form_drop_down_options']);
					}
					$bid_data['bid_description']= trim($post_data['bid_description']);
					$bid_data['bid_date']= date('Y-m-d H:i:s');
					
				} else if($project_data['project_type'] == 'hourly'){
					if(!isset($post_data['biding_form_checkbox'])){
						$bid_data['initial_bidded_hourly_rate']= str_replace(" ","",$post_data['bid_amount']);
						$bid_data['bidding_dropdown_option']= 'NA';
						$bid_data['project_delivery_hours']= str_replace(" ","",$post_data['delivery_period']);
					}else{
						$bid_data['initial_bidded_hourly_rate']= 0;
						$bid_data['project_delivery_hours']= 0;
						$bid_data['bidding_dropdown_option']= $post_data['biding_form_drop_down_options'];
					}
					$bid_data['bid_description']= trim($post_data['bid_description']);
					$bid_data['bid_date']= date('Y-m-d H:i:s');
					
				} else if($project_data['project_type'] == 'fulltime'){
					if(!isset($post_data['biding_form_checkbox'])){
						$bid_data['initial_requested_salary']= str_replace(" ","",$post_data['bid_amount']);
						$bid_data['application_bidding_dropdown_option']= 'NA';
					} else {
						$bid_data['initial_requested_salary']= 0;
						$bid_data['application_bidding_dropdown_option']= $post_data['biding_form_drop_down_options'];
					}
					$bid_data['application_description']= trim($post_data['bid_description']);
					$bid_data['application_date']= date('Y-m-d H:i:s');
					$bid_data['fulltime_project_id']= $project_id;
					$bid_data['employee_id']= $user[0]->user_id;
					$bid_data['employer_id']= $project_data['project_owner_id'];

					unset($bid_data['project_id']);
					unset($bid_data['bidder_id']);
					unset($bid_data['project_owner_id']);
				}
				
				$this->db->insert ($bid_table_name, $bid_data);
				$last_insert_id = $this->db->insert_id();
				
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
				
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				//$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
				
				
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir);
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir);
				$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
				
				if(!empty($_FILES)){
				
					$file_array = $_FILES;
					foreach($file_array['files']['name'] as $key => $val) {
						$temp 		= 	explode(".", $val);
						$extension 	= 	end($temp);
						$bid_attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$project_id.'.'.$extension;// name of attachment
						
						if(move_uploaded_file($file_array['files']['tmp_name'][$key], TEMP_DIR.$bid_attachment_name)){ 
						
							$source_path = FCPATH .TEMP_DIR. $bid_attachment_name;
							
							$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
							
							$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
							unlink(FCPATH .TEMP_DIR. $bid_attachment_name);
							$bid_attachments_data = array('user_id'=>$user[0]->user_id,'project_id'=>$project_id,'bid_attachment_name'=>$bid_attachment_name);
							$this->db->insert ('projects_active_bids_users_attachments_tracking', $bid_attachments_data);
						}
					}
				
				}
				####### generate log #########
				
				if($project_data['project_type'] == 'fixed'){
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M')
						{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_post_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_male_user_activity_log_displayed_message');
							}else{		
								$po_post_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_male_user_activity_log_displayed_message');
							}
						
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_post_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_female_user_activity_log_displayed_message');
							}else{	
								$po_post_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_female_user_activity_log_displayed_message');
							}
						}
					
					}else{
						$po_post_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_new_bid_received_from_sp_company_user_activity_log_displayed_message');
					}
					
					
					$sp_post_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_new_bid_placed_user_activity_log_displayed_message');
				
				} else if($project_data['project_type'] == 'hourly'){
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M') {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_post_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_male_user_activity_log_displayed_message');
							}else{	
							
								$po_post_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_new_bid_received_from_sp_male_user_activity_log_displayed_message');
							}
						
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_post_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_new_bid_received_from_sp_company_app_female_user_activity_log_displayed_message');
							}else{	
								$po_post_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_new_bid_received_from_sp_female_user_activity_log_displayed_message');
							}
						}
					
					} else {
						$po_post_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_new_bid_received_from_sp_company_user_activity_log_displayed_message');
					}
					$sp_post_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_new_bid_placed_user_activity_log_displayed_message');
				} else if($project_data['project_type'] == 'fulltime'){

					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M') {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_post_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_app_male_user_activity_log_displayed_message');
							}else{	
								$po_post_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_male_user_activity_log_displayed_message');
							}
						
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_post_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_app_female_user_activity_log_displayed_message');
							}else{	
								$po_post_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_female_user_activity_log_displayed_message');
							}
						}
					
					} else {
						$po_post_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_new_application_received_from_employee_company_user_activity_log_displayed_message');
					}
					$sp_post_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_new_application_placed_from_employee_user_activity_log_displayed_message');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
				
				$sp_profile_url_link = VPATH.$user[0]->profile_name;
				
				$sp_post_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$sp_post_bid_activity_log_message);
				user_display_log($sp_post_bid_activity_log_message); // activity log message for service provider
				
				$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )) ?  $user[0]->first_name." ".$user[0]->last_name : $user[0]->company_name;
				
				if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
					$po_post_bid_activity_log_message = str_replace(array('{user_first_name_last_name}','{sp_profile_url_link}','{project_url_link}','{project_title}'),array($name,$sp_profile_url_link,$project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$po_post_bid_activity_log_message);
				}else{
					$po_post_bid_activity_log_message = str_replace(array('{user_company_name}','{sp_profile_url_link}','{project_url_link}','{project_title}'),array($name,$sp_profile_url_link,$project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$po_post_bid_activity_log_message);	
				}
				user_display_log($po_post_bid_activity_log_message,$project_data['project_owner_id']); // activity log message for project owner
				
				if($project_data['project_type'] == 'fulltime'){
					$bid_confirmation_message = $this->config->item('project_details_page_bid_posted_confirmation_fulltime_project_bid_form_message');
				} else {
					$bid_confirmation_message = $this->config->item('project_details_page_bid_posted_confirmation_project_bid_form_message');
				}
				$project_bid_count = $this->Bidding_model->get_project_bid_count($project_data['project_id'],$project_data['project_type']); // get bid count of project
				
				$project_bid_count_history = $project_bid_count;
				######################### get the bid detail of user ####################
				$this->db->select('projects_open_bidding.project_id,projects_open_bidding.project_owner_id,projects_open_bidding.project_type');
				$this->db->from('projects_open_bidding');
				$this->db->where('projects_open_bidding.project_id',$project_id);
				$project_result = $this->db->get();
				$project_data = $project_result->row_array();
				$data['project_data'] = $project_data;
				$data['bidder_data'] = $this->Bidding_model->get_user_bid_detail($project_data['project_id'],$user[0]->user_id,$project_data['project_type'],$project_status_table_array['project_status'],$profile_name);
				####################################################################
				
				$response['data'] = $this->load->view('project_bidders_listing', $data, true);
				$response['status'] = 200;
				$response['project_bid_count_history'] = $project_bid_count_history;
				$response['location'] = '';
				$response['message'] = $bid_confirmation_message;
				$response['project_id'] = $project_id;
				$response['bid_id'] = $last_insert_id;
				$response['po_id'] = $project_data['project_owner_id'];
				$response['message'] = $bid_confirmation_message;
				
				echo json_encode($response);
			}else{
				//echo json_encode($msg);
				echo json_encode ($validation_data_array);
				die;
			}
		}
	}
	
	/**
	* This function is used to load the edit bid form .
	*/
	public function load_edit_bid_form(){
	
		if($this->input->is_ajax_request ()){
			$project_id = $this->input->post ('project_id');
			$section_id = $this->input->post ('section_id');
			$project_type = $this->input->post ('project_type');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				if($project_type == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
				} else if($project_type == 'hourly') {
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
				} else if($project_type == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
				}
				$bid_data = $this->db->get_where($bid_table_name, ['id' => $section_id])->row_array();
				if($project_type == 'fulltime' && !empty($bid_data)) { 
					$bid_data['project_id'] = $bid_data['fulltime_project_id'];
					$bid_data['project_owner_id'] = $bid_data['employer_id'];
					$bid_data['bidder_id'] = $bid_data['employee_id'];
					$bid_data['bidding_dropdown_option'] = $bid_data['application_bidding_dropdown_option'];
				}
				
				if(!empty($bid_data) && $bid_data['bidder_id'] !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_update_bid_deleted_project')]);
					die;
				}
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				/* $project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array(); */
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				if($project_type == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_type == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_type == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				if($project_type == 'fulltime') { 
					$awarded_bid_data = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				} else {
					$awarded_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				}
				
				if($awarded_bid_data > 0 ){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_awarded_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_awarded_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
			
				}
				if($project_type == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				/* if(!empty($inprogress_bid_table_name)) {
					$inprogress_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				} */
				
				if($check_already_in_progress_bid > 0 ){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_in_progress_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_in_progress_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if(!empty($completed_bid_table_name)) {
					$completed_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_type != 'fulltime' && !empty($completed_bid_data) && $completed_bid_data > 0 ){
					/* if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_update_bid_on_completed_fulltime_project');
					}else{ */
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_update_bid_on_completed_project');
					//}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if(!empty($bid_data) && $project_type == 'fulltime') { 
					$bid_data = $this->db->get_where($bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->row_array();
					$bid_data['project_id'] = $bid_data['fulltime_project_id'];
					$bid_data['project_owner_id'] = $bid_data['employer_id'];
					$bid_data['bidder_id'] = $bid_data['employee_id'];
					$bid_data['bidding_dropdown_option'] = $bid_data['application_bidding_dropdown_option'];
				} else if($project_type != 'fulltime') {
					$bid_data = $this->db->get_where($bid_table_name, ['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->row_array();
				}
				if(empty($bid_data)){

					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				$login_user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id, u.first_name, u.last_name, u.account_type, u.company_name, u.profile_name')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
				
				$bidder_attachment_list = $this->db // get the user detail
				->select('id,bid_attachment_name,user_id')
				->from('projects_active_bids_users_attachments_tracking')
				->where('project_id', $project_id)
				->where('user_id', $user[0]->user_id)
				->order_by('id DESC')
				->get()->result_array();
				
				
				
				$bid_attachment_data = array();
				if(!empty($bidder_attachment_list)){
				
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config);
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$profile_folder     = $project_data['profile_name'];
					
					
					
					$project_cancelled_dir = PROJECT_CANCELLED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					
					
					
					foreach($bidder_attachment_list as $attachment_key=>$attachment_value){
					
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$attachment_value['user_id'] .DIRECTORY_SEPARATOR .$attachment_value['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$attachment_value['user_id'] .DIRECTORY_SEPARATOR .$attachment_value['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$attachment_value['user_id'] .DIRECTORY_SEPARATOR .$attachment_value['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$attachment_value['user_id'] .DIRECTORY_SEPARATOR .$attachment_value['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$attachment_value['user_id'] .DIRECTORY_SEPARATOR .$attachment_value['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'cancelled'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id.$users_bid_attachments_dir.$attachment_value['user_id'] .DIRECTORY_SEPARATOR .$attachment_value['bid_attachment_name'];
						}
						
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$attachment_data['id'] = $attachment_value['id'];
							$attachment_data['bid_attachment_name'] = $attachment_value['bid_attachment_name'];
							$attachment_data['user_id'] = $attachment_value['user_id'];
							$attachment_data['bid_attachment_encrypt_id'] = Cryptor::doEncrypt($attachment_value['id']);
							$attachment_data['size'] = number_format($file_size/1024). 'KB';
							$bid_attachment_data[] = $attachment_data;
						}
					}
					$this->ftp->close();
				}
				$login_user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
				
				if($login_user_detail['current_membership_plan_id'] == 4){
					$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
					
				}else if($login_user_detail['current_membership_plan_id'] == 1){

					$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
				}
				
				
				$no_bid_attachment_uploaded_user = $this->db
				->select ('id')
				->from ('projects_active_bids_users_attachments_tracking')
				->where ('project_id', $project_id)
				->where ('user_id', $user[0]->user_id)
				->get ()->num_rows ();// check the number of attachment of user into database
				
				if(!empty($bid_data)){
					//echo json_encode(['status' => 200,'location'=>'','remove_id'=>$user[0]->user_id,'b_id'=>$bid_data['id'],'data'=>$this->load->view('edit_bid_form',array('bid_data'=>$bid_data,'project_data'=>$project_data,'bid_attachment_data'=>$bid_attachment_data,'login_user_detail'=>$login_user_detail,'no_bid_attachment_uploaded_user'=>$no_bid_attachment_uploaded_user), true)]);
					echo json_encode(['status' => 200,'maximum_allowed_number_of_bid_attachments'=>$maximum_allowed_number_of_bid_attachments,'location'=>'','no_bid_attachment_uploaded_user'=>$no_bid_attachment_uploaded_user,'remove_id'=>$user[0]->user_id,'data'=>$this->load->view('edit_bid_form',array('bid_data'=>$bid_data,'project_data'=>$project_data,'bid_attachment_data'=>$bid_attachment_data,'login_user_detail'=>$login_user_detail,'no_bid_attachment_uploaded_user'=>$no_bid_attachment_uploaded_user), true)]);
					die;
					
				}/* else{
					
					echo json_encode(['status' => 400,'location'=>'','message'=>$this->config->item('project_details_page_place_bid_validation_project_bid_form_message')]);
					die;
				} */
				
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	## This function is used to update the bid data into the database
	public function update_bid(){
	
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		$project_id = $this->input->post ('project_id');
		$section_id = $this->input->post ('section_id');
		$project_type = $this->input->post ('project_type');
		if(!check_session_validity()) {
			
			echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
			die;
		}
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			if($project_type == 'fixed'){
				$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
			} else if($project_type == 'hourly') {
				$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
			} else if($project_type == 'fulltime') {
				$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
			}
			$bid_data = $this->db->get_where($bid_table_name, ['id' => $section_id])->row_array();
			if($project_type == 'fulltime' && !empty($bid_data) ) {
				$bid_data['bidder_id'] = $bid_data['employee_id'];
			}
			if(!empty($bid_data) && $bid_data['bidder_id'] !=  $user[0]->user_id){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				die;
			}
			
			$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
			/* if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
				$error_message = ''; */
				
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_update_bid_deleted_project')]);
					die;
				}
				
				
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_cancelled_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$this->db->select('pd.*,users.profile_name');
					$this->db->from($project_status_table_array['table_name'].' pd');
					$this->db->join('users', 'users.user_id = pd.employer_id', 'left');
					$this->db->where('pd.fulltime_project_id',$project_id);
					$project_result = $this->db->get();
					$project_detail = $project_result->row_array();
					$project_detail['project_owner_id'] = $project_detail['employer_id'];
					$project_detail['project_id'] = $project_detail['fulltime_project_id'];
					$project_detail['min_budget'] = $project_detail['min_salary'];
					$project_detail['project_title'] = $project_detail['fulltime_project_title'];
				} else {
					$this->db->select('pd.*,users.profile_name');
					$this->db->from($project_status_table_array['table_name'].' pd');
					$this->db->join('users', 'users.user_id = pd.project_owner_id', 'left');
					$this->db->where('pd.project_id',$project_id);
					$project_result = $this->db->get();
					$project_detail = $project_result->row_array();
				}
				
				/* if($project_detail['project_owner_id'] == $user[0]->user_id){
					echo json_encode(['status' => 400,'location'=>'','error'=>$this->config->item('project_details_page_place_bid_validation_project_bid_form_message')]);
					die;
				}
			
				 */
				if($project_detail['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$update_bid_table_name = 'fixed_budget_projects_open_bidding_updated_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_detail['project_type'] == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$update_bid_table_name = 'hourly_rate_based_projects_open_bidding_updated_active_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_detail['project_type'] == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';	
					$update_bid_table_name = 'fulltime_projects_open_bidding_updated_active_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}	
				if($project_detail['project_type'] == 'fulltime'){ 
					$awarded_bid_data = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				} else {
					$awarded_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				}
				if($awarded_bid_data > 0 ){
					if($project_detail['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_awarded_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_awarded_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if(!empty($inprogress_bid_table_name)) {
					if($project_detail['project_type'] == 'fulltime'){
						
						$inprogress_bid_data = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
						
					}else{
						$inprogress_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					}
				}
				if($inprogress_bid_data > 0){
					if($project_detail['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_in_progress_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_in_progress_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if(!empty($completed_bid_table_name)) {
					$completed_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_detail['project_type'] != 'fulltime' && !empty($completed_bid_data) && $completed_bid_data > 0 ){
					/* if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_update_bid_on_completed_fulltime_project');
					}else{ */
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_update_bid_on_completed_project');
					//}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($project_detail['project_type'] == 'fulltime') { 
					$bid_detail = $this->db->get_where($bid_table_name, ['fulltime_project_id'=>$project_id,'employee_id'=>$user[0]->user_id,'employer_id'=>$project_detail['project_owner_id']])->row_array();
				} else {
					$bid_detail = $this->db->get_where($bid_table_name, ['project_id'=>$project_id,'bidder_id'=>$user[0]->user_id,'project_owner_id'=>$project_detail['project_owner_id']])->row_array();
				}
				if(empty($bid_detail)){
					
					if($project_detail['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				/* if($bid_detail['bidder_id'] != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('	popup_error_heading'),'location'=>'','error'=>$this->config->item('project_details_page_place_bid_validation_project_bid_form_message')]);
					die;
				} */
				
				$post_data = $this->input->post ();
				$validation_data_array = $this->Bidding_model->post_update_bid_validation($post_data,$project_detail);
				if($validation_data_array['status'] == 'SUCCESS'){ 
				
				
					if(!empty($_FILES)){
				
					
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					
					
						if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
						
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR)){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir)){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir)){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR)){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir)){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
						if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR)){
							echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$this->config->item('users_folder_not_exist_error_message')]);
							die;
						}
					}
				
					//$user = $this->session->userdata('user');
					$bid_data['project_id']= $project_id;
					$bid_data['bidder_id']= $user[0]->user_id;
					$bid_data['project_owner_id']= $project_detail['project_owner_id'];
					$update_bid_history = array();
					if($project_detail['project_type'] == 'fixed') {
						//$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
						if(!isset($post_data['biding_form_checkbox'])){
							$bid_data['initial_bidded_amount']= trim(str_replace(" ","",$post_data['bid_amount']));
							$bid_data['project_delivery_period']= trim(str_replace(" ","",$post_data['delivery_period']));
							$bid_data['bidding_dropdown_option']= 'NA';
							
							if(intval($bid_detail['initial_bidded_amount']) != str_replace(" ","",$post_data['bid_amount'])){
								$update_bid_history['new_bidded_amount'] = trim(str_replace(" ","",$post_data['bid_amount']));
								$update_bid_history['previous_bidded_amount'] = trim($bid_detail['initial_bidded_amount']);
							}
							if(intval($bid_detail['project_delivery_period']) != str_replace(" ","",$post_data['delivery_period'])){
								$update_bid_history['project_delivery_update_period'] = trim(str_replace(" ","",$post_data['delivery_period']));
							}
						
							
						} else {
							$bid_data['initial_bidded_amount']= 0;
							$bid_data['project_delivery_period']= 0;
							$bid_data['bidding_dropdown_option']= $post_data['biding_form_drop_down_options'];
							if($bid_detail['bidding_dropdown_option'] != $post_data['biding_form_drop_down_options']){
								$update_bid_history['bidding_dropdown_option'] = trim($bid_detail['bidding_dropdown_option']);
								$update_bid_history['previous_bidded_amount'] = trim($bid_detail['initial_bidded_amount']);
								$update_bid_history['project_delivery_update_period'] = trim($bid_detail['project_delivery_period']);
							}
						
						}
						$bid_data['bid_description']= $post_data['bid_description'];
						if(strlen($bid_detail['bid_description']) != strlen($post_data['bid_description'])){
							$update_bid_history['bid_update_description'] = trim($bid_detail['bid_description']);
						}
						if(!empty($update_bid_history)){
							$update_bid_history['bid_update_date'] = date('Y-m-d H:i:s');
						}
						
					} else if($project_detail['project_type'] == 'hourly'){
						if(!isset($post_data['biding_form_checkbox'])){
							$bid_data['initial_bidded_hourly_rate']= trim(str_replace(" ","",$post_data['bid_amount']));
							$bid_data['bidding_dropdown_option']= 'NA';
							$bid_data['project_delivery_hours']= trim(str_replace(" ","",$post_data['delivery_period']));
							
							
							if(intval($bid_detail['initial_bidded_hourly_rate']) != intval(str_replace(" ","",$post_data['bid_amount']))){
								$update_bid_history['newly_bidded_hourly_rate'] = str_replace(" ","",$post_data['bid_amount']);
								$update_bid_history['previous_bidded_hourly_rate'] = trim($bid_detail['initial_bidded_hourly_rate']);
							}
							if(intval($bid_detail['project_delivery_hours']) != intval(str_replace(" ","",$post_data['delivery_period']))){
								$update_bid_history['previously_project_delivery_bidded_hours'] = $bid_detail['project_delivery_hours'];
								$update_bid_history['newly_project_delivery_bidded_hours'] = str_replace(" ","",$post_data['delivery_period']);
							}
							
							
						} else {
							$bid_data['initial_bidded_hourly_rate']= 0;
							$bid_data['project_delivery_hours']= 0;
							$bid_data['bidding_dropdown_option']= trim($post_data['biding_form_drop_down_options']);
							if($bid_detail['bidding_dropdown_option'] != $post_data['biding_form_drop_down_options']){
								$update_bid_history['bidding_dropdown_option'] = trim($post_data['biding_form_drop_down_options']);
								$update_bid_history['previous_bidded_hourly_rate'] = trim($bid_detail['initial_bidded_hourly_rate']);	
								// $update_bid_history['project_delivery_update_hours'] = trim($bid_detail['project_delivery_hours']);
								$update_bid_history['previously_project_delivery_bidded_hours'] = $bid_detail['project_delivery_hours'];
								$update_bid_history['newly_project_delivery_bidded_hours'] = $bid_detail['project_delivery_hours'];
							}
						}
						$bid_data['bid_description']= trim($post_data['bid_description']);
						if(strlen($bid_detail['bid_description']) != strlen($post_data['bid_description'])){
							$update_bid_history['bid_update_description'] = $bid_detail['bid_description'];
						}
						if(!empty($update_bid_history)){
							$update_bid_history['bid_update_date'] = date('Y-m-d H:i:s');
						}
						
					} else if($project_detail['project_type'] == 'fulltime'){

						$bid_data['fulltime_project_id']= $project_id;
						$bid_data['employee_id']= $user[0]->user_id;
						$bid_data['employer_id']= $project_detail['project_owner_id'];
						unset($bid_data['project_id']);
						unset($bid_data['bidder_id']);
						unset($bid_data['project_owner_id']);

						if(!isset($post_data['biding_form_checkbox'])){
							$bid_data['initial_requested_salary']= trim(str_replace(" ","",$post_data['bid_amount']));
							$bid_data['application_bidding_dropdown_option']= 'NA';
							
							if(intval($bid_detail['initial_requested_salary']) != str_replace(" ","",$post_data['bid_amount'])){
								$update_bid_history['new_requested_salary'] = trim(str_replace(" ","",$post_data['bid_amount']));
								$update_bid_history['previous_requested_salary'] = trim(str_replace(" ","",$bid_detail['initial_requested_salary']));
							}
							
						} else {
							$bid_data['initial_requested_salary']= 0;
							$bid_data['application_bidding_dropdown_option']= $post_data['biding_form_drop_down_options'];
							if($bid_detail['application_bidding_dropdown_option'] != $post_data['biding_form_drop_down_options']){
								$update_bid_history['application_bidding_dropdown_option'] = trim($bid_detail['application_bidding_dropdown_option']);
								$update_bid_history['previous_requested_salary'] = trim($bid_detail['initial_requested_salary']);
							}
						}
						$bid_data['application_description']= trim($post_data['bid_description']);
						if(strlen($bid_detail['application_description']) != strlen($post_data['bid_description'])){
							$update_bid_history['application_update_description'] = trim($bid_detail['application_description']);
						}
						if(!empty($update_bid_history)){
							$update_bid_history['application_update_date'] = date('Y-m-d H:i:s');
						}
						
						
					}
					if($project_detail['project_type'] == 'fulltime'){ 
						$this->db->update($bid_table_name, $bid_data, ['fulltime_project_id' => $project_id,'employee_id'=> $user[0]->user_id]);
					} else {
						$this->db->update($bid_table_name, $bid_data, ['project_id' => $project_id,'bidder_id'=> $user[0]->user_id]);
					}
					
					
					$profile_name = $project_detail['profile_name'];
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					//$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
					
					
					/* $this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir);
					$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR); */
					
					
					if($project_status_table_array['project_status'] == 'open_for_bidding'){
								
								
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR;
					
						
					}
					if($project_status_table_array['project_status'] == 'awarded'){
					
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR;
					}
					if($project_status_table_array['project_status'] == 'in_progress'){
					
					
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR;
						
					}
					if($project_status_table_array['project_status'] == 'incomplete'){
					
					
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR;
						
					}
					if($project_status_table_array['project_status'] == 'completed'){
					
					
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR;
						
					}
					if($project_status_table_array['project_status'] == 'expired'){
					
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir);
						
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR;
						
					}
					
					
					/* echo "<pre>";
					print_r($_FILES); */
					/* die; */
					
					if(!empty($_FILES)){
					
						$file_array = $_FILES;
						foreach($file_array['files']['name'] as $key => $val) {
							
							$temp 		= 	explode(".", $val);
							$extension 	= 	end($temp);
							$bid_attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$project_id.'.'.$extension;// name of attachment
							
							if(move_uploaded_file($file_array['files']['tmp_name'][$key], TEMP_DIR.$bid_attachment_name)){ 
							
								$source_path = FCPATH .TEMP_DIR. $bid_attachment_name;
								$attachment_destination_path = $destination_path.$bid_attachment_name;
								
								$this->ftp->upload($source_path,$attachment_destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
								unlink(FCPATH .TEMP_DIR. $bid_attachment_name);
								$bid_attachments_data = array('user_id'=>$user[0]->user_id,'project_id'=>$project_id,'bid_attachment_name'=>$bid_attachment_name);
								$this->db->insert ('projects_active_bids_users_attachments_tracking', $bid_attachments_data);
							/* 	$bid_attachment_name = '';
								$attachment_destination_path = ''; */
							}
						}
					
					}
					if(!empty($update_bid_history)){
						if($project_detail['project_type'] == 'fulltime') {
							$update_bid_history['fulltime_project_id'] = $bid_detail['fulltime_project_id'];
							$update_bid_history['employer_id'] = $bid_detail['employer_id'];
							$update_bid_history['employee_id'] = $bid_detail['employee_id'];
						} else {
							$update_bid_history['project_id'] = $bid_detail['project_id'];
							$update_bid_history['project_owner_id'] = $bid_detail['project_owner_id'];
							$update_bid_history['bidder_id'] = $bid_detail['bidder_id'];
						}
						
						$this->db->insert ($update_bid_table_name, $update_bid_history);
					}
					// maintain history of bid update
					
					####### generate log #########
					if($project_detail['project_type'] == 'fixed'){
						
						if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
							
							
							
							if($user[0]->gender == 'M'){
								
								if($user[0]->is_authorized_physical_person == 'Y'){
									$po_update_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_edit_bid_user_activity_log_displayed_message');
									
								}else{	
								
								
									$po_update_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_edit_bid_user_activity_log_displayed_message');
								}
							} else {
								if($user[0]->is_authorized_physical_person == 'Y'){
									
									$po_update_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_edit_bid_user_activity_log_displayed_message');
									
								}else{	
									$po_update_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_edit_bid_user_activity_log_displayed_message');
								}
								
							}
						} else {
							$po_update_bid_activity_log_message = $this->config->item('fixed_project_message_sent_to_po_when_sp_company_edit_bid_user_activity_log_displayed_message');
						}
						
						$sp_update_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_edit_bid_user_activity_log_displayed_message');
					} else if($project_detail['project_type'] == 'hourly'){
						if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
							if($user[0]->gender == 'M') {
								
								if($user[0]->is_authorized_physical_person == 'Y'){
									$po_update_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_male_edit_bid_user_activity_log_displayed_message');
								}else{	
									$po_update_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_male_edit_bid_user_activity_log_displayed_message');
								}
							} else {
								if($user[0]->is_authorized_physical_person == 'Y'){
									$po_update_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_female_edit_bid_user_activity_log_displayed_message');
								}else{	
								
									$po_update_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_female_edit_bid_user_activity_log_displayed_message');
								}
							}
						} else {
							$po_update_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_edit_bid_user_activity_log_displayed_message');
						}
						$sp_update_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_edit_bid_user_activity_log_displayed_message');
					
					
					} else if($project_detail['project_type'] == 'fulltime'){
						if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
							if($user[0]->gender == 'M') {
								
								if($user[0]->is_authorized_physical_person == 'Y'){
									$po_update_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_edit_application_user_activity_log_displayed_message');
								}else{	
								
									$po_update_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_edit_application_user_activity_log_displayed_message');
								}
							} else {
								if($user[0]->is_authorized_physical_person == 'Y'){
									$po_update_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_edit_application_user_activity_log_displayed_message');
								}else{	
									$po_update_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_edit_application_user_activity_log_displayed_message');
								}
							}
						} else {
							$po_update_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_edit_application_user_activity_log_displayed_message');
						}
						
						$sp_update_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_edit_application_user_activity_log_displayed_message');
					}
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_detail['project_id'];
					
					$sp_profile_url_link = VPATH.$user[0]->profile_name;
					
					$sp_update_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_detail['project_title'], ENT_QUOTES)),$sp_update_bid_activity_log_message);
					user_display_log($sp_update_bid_activity_log_message); // activity log message for service provider
					
					
					
					
					
					
					$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )) ?  $user[0]->first_name ." ".$user[0]->last_name : $user[0]->company_name;
					
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						
						
						
						$po_update_bid_activity_log_message = str_replace(array('{user_first_name_last_name}','{sp_profile_url_link}','{project_url_link}','{project_title}'),array($name,$sp_profile_url_link,$project_url_link,htmlspecialchars($project_detail['project_title'], ENT_QUOTES)),$po_update_bid_activity_log_message);
						
						
					}else{
						$po_update_bid_activity_log_message = str_replace(array('{user_company_name}','{sp_profile_url_link}','{project_url_link}','{project_title}'),array($name,$sp_profile_url_link,$project_url_link,htmlspecialchars($project_detail['project_title'], ENT_QUOTES)),$po_update_bid_activity_log_message);
					
					}
					
					user_display_log($po_update_bid_activity_log_message,$project_detail['project_owner_id']); // activity log message for project owner
					
					
					
					if($project_detail['project_type'] == 'fulltime'){
						$bid_updated_confirmation_message = $this->config->item('project_details_page_bid_updated_confirmation_fulltime_project_bid_form_message');
					}else{
						$bid_updated_confirmation_message = $this->config->item('project_details_page_bid_updated_confirmation_project_bid_form_message');
					}
					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_detail['project_id'],$project_detail['project_type']); // get bid count of project
					
					$project_bid_count_history = $project_bid_count;
					######################### get the bid detail of user ####################
					$data['project_data'] = $project_detail;
					
				
					
					$data['bidder_data'] = $this->Bidding_model->get_user_bid_detail($project_detail['project_id'],$user[0]->user_id,$project_detail['project_type'],$project_status_table_array['project_status'],$project_detail['profile_name']);
					####################################################################
					$response['data'] = $this->load->view('bidder_bid_information', $data, true);
					$response['row_id'] = $user[0]->user_id;
					$response['project_id'] = $project_id;
					$response['po_id'] = $project_detail['project_owner_id'];
					$response['status'] = 200;
					$response['project_bid_count_history'] = $project_bid_count_history;
					$response['location'] = '';
					$response['message'] = $bid_updated_confirmation_message;
					
					echo json_encode($response);
				}else{
					//echo json_encode($msg);
					echo json_encode ($validation_data_array);
					die;
				}
		}
	}
	
	## This function is used to cancel the bid data into the database
	public function cancel_bid ()
    {
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				
				if($po_id == $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
					die;
				}
				
				/* $project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array(); */
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$project_data = $this->db // get the user detail
				->select('pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name,pd.fulltime_project_expiration_date as project_expiration_date')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.employer_id', 'left')
				->where('pd.fulltime_project_id', $project_id)
				->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}	
				
				
				
				
				/* if( $project_data['project_owner_id'] ==  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
					 */
				$this->db->delete('projects_active_bids_users_attachments_tracking', array('project_id' => $project_id,'user_id'=>$user[0]->user_id));	
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
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
				
				$profile_folder     = $project_data['profile_name'];
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id;
				if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.	$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir))){
					$this->ftp->delete_dir($users_ftp_dir.$profile_folder.	$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir);
				
				}
				$this->ftp->close();
				
				
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
				
					$project_expiration_timestamp = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
					
					if(empty($project_expiration_timestamp) || $project_expiration_timestamp < time()){
						$msg['status'] = 'FAILED';
						$msg['message'] = '';
						echo json_encode ($msg);die;
						die;
					}
				
				
					$no_bid_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('projects_active_bids_users_attachments_tracking')
					->where ('project_id', $project_id)
					->where ('user_id', $user[0]->user_id)
					->get ()->num_rows ();
					
					if($login_user_detail['current_membership_plan_id'] == 4){
						$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
						
					}else if($login_user_detail['current_membership_plan_id'] == 1){

						$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
					}
					$upload_button_status = '0';
					if($no_bid_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
						$upload_button_status = '1';
					} 
					$msg['upload_button_status'] = $upload_button_status;
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				}else{
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	## This function is used to cancel the bid data into the database
	public function cancel_update_bid ()
    {
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
			
				
				$row_id = $this->input->post ('row_id');
				$project_type = $this->input->post ('project_type');
				$user = $this->session->userdata ('user');
				
				if($project_type == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
				} else if($project_type == 'hourly') {
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
				} else if($project_type == 'fulltime') {
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
				}
				
				$bid_data = $this->db->get_where($bid_table_name, ['id' => $row_id])->row_array();
				if(!empty($bid_data) && $project_type == 'fulltime') {
					$bid_data['bidder_id'] = $bid_data['employee_id'];
				}
				 
				if(!empty($bid_data) && $bid_data['bidder_id'] !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				
				}
				if( $project_status_table_array['project_status'] == 'cancelled'){
				
						$msg['status'] = 'FAILED';
						$msg['message'] = '';
						echo json_encode ($msg);die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_id'] = $project_data['fulltime_project_id'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
			
			
				if($project_data['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_data['project_type'] == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_data['project_type'] == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = '';
				}	
				
				if($project_data['project_type'] == 'fulltime') { 
					$awarded_bid_data = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				} else {
					$awarded_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				}

				if($awarded_bid_data > 0){
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				}
				if(!empty($inprogress_bid_table_name)) {
					$inprogress_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				if($inprogress_bid_data > 0){
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				
				}
				if(!empty($completed_bid_table_name)) {
					$completed_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
				}
				if(!empty($completed_bid_data) && $completed_bid_data > 0){
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				
				}
				if($project_data['project_type'] == 'fulltime') { 
					$bid_detail = $this->db->get_where($bid_table_name, ['fulltime_project_id'=>$project_id,'employee_id'=>$user[0]->user_id,'employer_id'=>$project_data['project_owner_id']])->row_array();
				} else {
					$bid_detail = $this->db->get_where($bid_table_name, ['project_id'=>$project_id,'bidder_id'=>$user[0]->user_id,'project_owner_id'=>$project_data['project_owner_id']])->row_array();
				}
				if(empty($bid_detail)){
					$msg['status'] = 'FAILED';
					$msg['message'] = '';
					echo json_encode ($msg);die;
				}
				
				$data['project_data'] = $project_data;
				$data['bidder_data'] = $this->Bidding_model->get_user_bid_detail($project_id,$user[0]->user_id,$project_data['project_type'],$project_status_table_array['project_status'],$project_data['profile_name']);
				
				####################################################################
				$msg['data'] = $this->load->view('bidder_bid_information', $data, true);
				$msg['row_id'] = $user[0]->user_id;
				$msg['status'] = 200;
				$msg['message'] = '';
				echo json_encode ($msg);die;

			} else {
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		} else {
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to make the popup of retract bid confirmation .
	*/
	public function retract_bid_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
			//echo $project_type;die;
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				
				if($bidder_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_retract_bid_deleted_project')]);
					die;
					
				}
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($project_type == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_type == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_type == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				if($project_type == 'fulltime') { 
					$awarded_bid_data = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				} else {
					$awarded_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				}
				if($awarded_bid_data > 0){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_awarded_same_sp_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				/* if(!empty($inprogress_bid_table_name)) {
					$inprogress_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				 */
				if($project_type == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				
				
				if($check_already_in_progress_bid > 0){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_in_progress_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_in_progress_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($project_type != 'fulltime' && !empty($completed_bid_table_name)) {
					$completed_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_type != 'fulltime' && !empty($completed_bid_data)){
					
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_retract_bid_on_completed_project');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if($project_type == 'fulltime') { 
					$bid_data = $this->db->get_where($bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->row_array();
				} else {
					$bid_data = $this->db->get_where($bid_table_name, ['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->row_array();
				}
				if(empty($bid_data)){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if(!empty($bid_data)){
				
					if($project_type == 'fulltime'){
            $confirmation_modal_title = $this->config->item('retract_application_confirmation_fulltime_project_modal_title');
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('retract_application_confirmation_fulltime_project_modal_body').'</div>';
						$confirmation_modal_body .= '<input type="hidden" id="section_id" value="'.$section_id.'" /><input type="hidden" id="project_type" value="'.$project_type.'" /><div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="retract_bid_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'.</span></label></div></div></div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" class="retract_bid_sp btn blue_btn default_btn retract_bid project_cancel_button width-auto" disabled style="opacity:0.65" data-attr="'.Cryptor::doEncrypt($bid_data['employee_id']).'">'.$this->config->item('retract_bid_confirmation_fulltime_project_modal_retract_btn_txt').'</button>';
					} else {
						if($project_type == 'fixed'){
							$confirmation_modal_title = $this->config->item('retract_bid_confirmation_fixed_budget_project_modal_title');
						}else{
							$confirmation_modal_title = $this->config->item('retract_bid_confirmation_hourly_project_modal_title');
						}
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('retract_bid_confirmation_project_modal_body').'</div>';
						
						$confirmation_modal_body.= '<input type="hidden" id="section_id" value="'.$section_id.'" /><input type="hidden" id="project_type" value="'.$project_type.'" /><div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="retract_bid_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></label></div></div></div>';
						
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="retract_bid_sp btn blue_btn default_btn retract_bid project_cancel_button width-auto" disabled style="opacity:0.65" data-attr="'.Cryptor::doEncrypt($bid_data['bidder_id']).'">'.$this->config->item('retract_bid_confirmation_project_modal_retract_btn_txt').'</button>';
					}
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	
	}
	
	/**
	* This function is used to retract the bid .
	*/
	public function retract_bid(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				if($bidder_id  !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}

				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_retract_bid_deleted_project')]);
					die;
				
				}
				//echo $project_type;die;
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				$login_user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
				
				$bid_data = array();
				$retract_bid_confirm_message = $this->config->item('project_details_page_retract_bid_confirmation_project_bid_form_message');
				if($project_data['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$retract_bid_history_table_name = 'fixed_budget_projects_open_bidding_retracted_bids';
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
					
						if($user[0]->gender == 'M'){	
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_retract_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_retract_bid_user_activity_log_displayed_message');
							}else{
								$po_retract_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_retract_bid_user_activity_log_displayed_message');
							}
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_retract_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_retract_bid_user_activity_log_displayed_message');
							}else{
								$po_retract_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_retract_bid_user_activity_log_displayed_message');
							}
							
						}
					}else{
						$po_retract_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_retract_bid_user_activity_log_displayed_message');
					}
					
					$sp_retract_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_retract_bid_user_activity_log_displayed_message');
					
				} else if($project_data['project_type'] == 'hourly'){
					
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$retract_bid_history_table_name = 'hourly_rate_based_projects_open_bidding_retracted_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M'){ 
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_retract_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_male_retract_bid_user_activity_log_displayed_message');
							
							}else{
						
								$po_retract_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_male_retract_bid_user_activity_log_displayed_message');
							}
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_retract_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_female_retract_bid_user_activity_log_displayed_message');
							}else{
								$po_retract_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_female_retract_bid_user_activity_log_displayed_message');
							}
						}
					} else {
						$po_retract_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_retract_bid_user_activity_log_displayed_message');
					}
					$sp_retract_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_retract_bid_user_activity_log_displayed_message');
					
						
				} else if($project_data['project_type'] == 'fulltime'){
					
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$retract_bid_history_table_name = 'fulltime_projects_open_bidding_retracted_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M'){ 
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_retracted_application_user_activity_log_displayed_message');
							}else{
								$po_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_retracted_application_user_activity_log_displayed_message');
							}
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_retracted_application_user_activity_log_displayed_message');
							}else{
							
								$po_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_retracted_application_user_activity_log_displayed_message');
							}
						}
					} else {
						$po_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_retracted_application_user_activity_log_displayed_message');
					}

					// $po_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_po_when_bidder_retract_bid_user_activity_log_displayed_message');
					$sp_retract_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_retracted_application_user_activity_log_displayed_message');
					
					$retract_bid_confirm_message = $this->config->item('project_details_page_retract_application_confirmation_fulltime_project_application_form_message');

				}
				if($project_data['project_type'] == 'fulltime') {
					$awarded_bid_data = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				} else {
					$awarded_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
				}
				if($awarded_bid_data > 0){
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_awarded_same_sp_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				/* if(!empty($inprogress_bid_table_name)) {
					$inprogress_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				} */
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				if($check_already_in_progress_bid > 0){
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_in_progress_same_sp_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_in_progress_same_sp_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if(!empty($completed_bid_table_name)) {
					$completed_bid_data = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
				}
				if(!empty($completed_bid_data)){
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_retract_bid_on_completed_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_retract_bid_on_completed_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($project_data['project_type'] == 'fulltime') { 
					$bid_data = $this->db->get_where($bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->row_array();
				} else {
					$bid_data = $this->db->get_where($bid_table_name, ['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->row_array();
				}
				
				if(empty($bid_data)){
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_retract_bid_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
			
				
				if($project_data['project_type'] == 'fixed'){
					$bid_data['bid_retract_date'] = date('Y-m-d H:i:s');
				} else if($project_data['project_type'] == 'hourly'){
					$bid_data['bid_retract_date'] = date('Y-m-d H:i:s');
				} else if($project_data['project_type'] == 'fulltime'){
					$bid_data['application_retract_date'] = date('Y-m-d H:i:s');
				}
				
				if(!empty($bid_data)){
				
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
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$profile_folder     = $project_data['profile_name'];
					$project_expired_dir = PROJECT_EXPIRED_DIR;
				
					if($project_data['project_type'] == 'fulltime') { 
						$this->db->delete($bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id]); 
					} else {
						$this->db->delete($bid_table_name, ['project_id' => $project_id,'bidder_id'=>$user[0]->user_id]); 
					}
				
					
					$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id,'user_id'=>$user[0]->user_id]);
					
					// retract bid
					$remove_id = $user[0]->user_id;
					unset($bid_data['id']);
					$this->db->insert ($retract_bid_history_table_name, $bid_data);// maintain history of bid
					
					if($project_status_table_array['project_status'] == 'open_for_bidding'){
					
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir.$user[0]->user_id))){
						
							$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir.$user[0]->user_id);
						
						}
					}
					if($project_status_table_array['project_status'] == 'expired'){
					
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir.$user[0]->user_id))){
						
							$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir.$user[0]->user_id);
						
						}
					}
					$this->ftp->close();
					
					####### generate log #########
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$sp_profile_url_link = VPATH.$user[0]->profile_name;
					
					$sp_retract_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$sp_retract_bid_activity_log_message);
					user_display_log($sp_retract_bid_activity_log_message); // activity log message for service provider
					
					$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )) ?  $user[0]->first_name." ".$user[0]->last_name : $user[0]->company_name;
					
					
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						$po_retract_bid_activity_log_message = str_replace(array('{user_first_name_last_name}','{sp_profile_url_link}','{project_url_link}','{project_title}'),array($name,$sp_profile_url_link,$project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$po_retract_bid_activity_log_message);
					} else {
						$po_retract_bid_activity_log_message = str_replace(array('{user_company_name}','{sp_profile_url_link}','{project_url_link}','{project_title}'),array($name,$sp_profile_url_link,$project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$po_retract_bid_activity_log_message);
					}

					user_display_log($po_retract_bid_activity_log_message,$project_data['project_owner_id']); // activity log message for project owner
					
					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_data['project_id'],$project_data['project_type']); // get bid count of project
					
					$project_bid_count_history = $project_bid_count;
					$project_status = $project_status_table_array['project_status'];
					if($project_status_table_array['project_status'] == 'open_for_bidding'){
						if(strtotime($project_data['project_expiration_date']) <= time()){
							$project_status = 'expired';
						}
					}

					echo json_encode(['status' => 200,'location'=>'','project_id'=>$project_id,'po_id'=>$project_data['project_owner_id'],'sp_id'=>$user[0]->user_id,'message'=>$retract_bid_confirm_message,'remove_id'=>$remove_id,'project_status'=>$project_status,'project_bid_count_history'=>$project_bid_count_history,'data'=>$this->load->view('place_bid_form',array('project_data'=>$project_data,'login_user_detail'=>$login_user_detail), true)]);
					die;
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to make the popup of award bid confirmation .
	*/
	public function award_bid_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			if(check_session_validity()){ 
				$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				$user = $this->session->userdata ('user');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);

				if($po_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_award_bid_deleted_project')]);
					die;
				}
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_po_view_award_bid_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_award_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_title'] = $project_data['fulltime_project_title'];
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				if($project_data['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_data['project_type'] == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_data['project_type'] == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				
				
				if($check_already_in_progress_bid >0 ){
					$sp_data = $this->db // get the user detail
					->select('u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from('users u')
					->where('u.user_id', $bidder_id)
					->get()->row_array();
				
					if($project_data['project_type'] == 'fulltime'){
					
						$error_message = $this->config->item('project_details_page_po_view_award_bid_in_progress_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_award_bid_in_progress_same_sp_project');
					}
					
					$name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y'))  ?  $sp_data['first_name']." ".$sp_data['last_name'] : $sp_data['company_name'];
					
					
					
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($project_data['project_type'] != 'fulltime'){
					if(!empty($completed_bid_table_name)) {
						$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id,'winner_id'=>$bidder_id])->from($completed_bid_table_name)->count_all_results();
					}
					
					if($check_already_completed_bid >0){
						
						
						$sp_data = $this->db // get the user detail
						->select('u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
						->from('users u')
						->where('u.user_id', $bidder_id)
						->get()->row_array();
						
						$name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y'))  ?  $sp_data['first_name']." ".$sp_data['last_name'] : $sp_data['company_name'];
						
						
						$error_message = $this->config->item('project_details_page_po_view_same_sp_try_award_bid_on_completed_project');
						$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$error_message);
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
				}
				if($project_data['project_type'] == 'fulltime'){ 
					$awarded_bid_data = $this->db // get the user detail
					->select('at.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from($awarded_bid_table_name.' at')
					->join('users u', 'u.user_id = at.employee_id', 'left')
					->where('at.fulltime_project_id', $project_id)
					->where('at.employee_id', $bidder_id)
					->get()->row_array();
				} else {
					$awarded_bid_data = $this->db // get the user detail
					->select('at.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from($awarded_bid_table_name.' at')
					->join('users u', 'u.user_id = at.winner_id', 'left')
					->where('at.project_id', $project_id)
					->where('at.winner_id', $bidder_id)
					->get()->row_array();
				}
				
				if(!empty($awarded_bid_data)){
					if($project_data['project_type'] == 'fulltime'){
						
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_awarded_same_sp_fulltime_project');
					}else{
						
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_awarded_same_sp_project');
					}
					//$name = $awarded_bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?  $awarded_bid_data['first_name']." ".$awarded_bid_data['last_name'] : $awarded_bid_data['company_name'];
					
					
					$name = ($awarded_bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($awarded_bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $awarded_bid_data['is_authorized_physical_person'] == 'Y')  ?  $awarded_bid_data['first_name']." ".$awarded_bid_data['last_name'] : $awarded_bid_data['company_name'];
					
					
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				
				if($project_data['project_type'] == 'fulltime'){
					$bid_data = $this->db // get the user detail
					->select('bd.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.profile_name')
					->from($bid_table_name.' bd')
					->join('users u', 'u.user_id = bd.employee_id', 'left')
					->where('bd.fulltime_project_id', $project_id)
					->where('bd.employee_id', $bidder_id)
					->get()->row_array();
					if(!empty($bid_data)){
					 $bid_data['bidder_id'] = $bidder_id;
					 $bid_data['bidding_dropdown_option'] = $bid_data['application_bidding_dropdown_option'];
					}
				} else {
					$bid_data = $this->db // get the user detail
					->select('bd.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.profile_name')
					->from($bid_table_name.' bd')
					->join('users u', 'u.user_id = bd.bidder_id', 'left')
					->where('bd.project_id', $project_id)
					->where('bd.bidder_id', $bidder_id)
					->get()->row_array();
				}
				if(empty($bid_data)){
				
					if($project_data['project_type'] == 'fulltime') {
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_retracted_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				$confirmation_modal_title = '';
				if(!empty($bid_data)){
					
					$project_title = $project_data['project_title'];
					$name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) ?  $bid_data['first_name']." ".$bid_data['last_name'] : $bid_data['company_name'];
					

					$first_name_company_name = $bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?  $bid_data['first_name'] : $bid_data['company_name'];
					
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_title = $this->config->item('award_application_confirmation_fulltime_project_modal_title_employer_view');
						
						if($bid_data['bidding_dropdown_option'] == 'NA'){
							$confirmationModalBody = $this->config->item('award_application_confirmation_fulltime_project_modal_body_employer_view');
							$confirmationModalBody = str_replace(array('{requested_salary_value}'),array(number_format($bid_data['initial_requested_salary'], 0, '', ' ')),$confirmationModalBody);
						}
						if($bid_data['bidding_dropdown_option'] == 'confidential'){
							$confirmationModalBody = $this->config->item('award_application_confirmation_fulltime_project_modal_body_employer_view_confidential');
						}
						if($bid_data['bidding_dropdown_option'] == 'to_be_agreed'){
							$confirmationModalBody = $this->config->item('award_application_confirmation_fulltime_project_modal_body_employer_view_to_be_agreed');
						
						}
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmationModalBody.'</div>';
						$completion_threshold_disclaimer_msg_po_view = $this->config->item('user_confirmation_check_box_txt');
						$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="award_bid_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$completion_threshold_disclaimer_msg_po_view.'</span></label></div></div></div>';
						
						$confirmation_modal_body = str_replace(array('{fulltime_project_title}','{user_first_name_last_name_or_company_name}'),array('<a href="#" class="default_popup_blue_text">'.$project_title.'</a>','<a href="#" class="default_popup_blue_text">'.$name.'</a>'),$confirmation_modal_body);
						
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_btn_padding" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn award_bid_po width-auto default_btn_padding" data-attr="'.Cryptor::doEncrypt($bid_data['bidder_id']).'" data-po-id="'.Cryptor::doEncrypt($po_id).'">'.$this->config->item('award_application_confirmation_fulltime_project_modal_award_btn_txt_employer_view').'</button>';
					} else {
						
						if($project_data['project_type'] == 'fixed'){
							if($bid_data['bidding_dropdown_option'] == 'NA'){
								$confirmationModalBody = $this->config->item('award_bid_confirmation_fixed_budget_project_modal_body_po_view');
								$confirmationModalBody = str_replace(array('{fixed_budget_project_title}','{user_first_name_last_name_or_company_name}'),array('<a href="#" class="default_popup_blue_text">'.$project_title.'</a>','<a href="#" class="default_popup_blue_text">'.$name.'</a>'),$confirmationModalBody);
							} else {
								if($bid_data['bidding_dropdown_option'] == 'confidential'){
									$confirmationModalBody = $this->config->item('award_bid_confirmation_fixed_budget_project_modal_body_po_view_confidential');
								}
								if($bid_data['bidding_dropdown_option'] == 'to_be_agreed'){
									$confirmationModalBody = $this->config->item('award_bid_confirmation_fixed_budget_project_modal_body_po_view_to_be_agreed');
								}
								$confirmationModalBody = str_replace(array('{fixed_budget_project_title}','{user_first_name_last_name_or_company_name}'),array('<a href="#" class="default_popup_blue_text">'.$project_title.'</a>','<a href="#" class="default_popup_blue_text">'.$name.'</a>'),$confirmationModalBody);
							}
							
							
							$completion_threshold_disclaimer_msg_po_view = $this->config->item('user_confirmation_check_box_txt');
							
							$completion_threshold_fixed_project_value = $this->Bidding_model->get_minimum_required_completion_threshold_fixed_project($project_id,$bid_data['bidder_id'],$bid_data['project_owner_id'],$project_status_table_array['project_status']);
							
							$completion_threshold_fixed_project_value = number_format($completion_threshold_fixed_project_value, 0, '', ' ')." ". CURRENCY;
							
							
							
							//$completion_threshold_disclaimer_msg_po_view = str_replace(array('{fixed_budget_project_completion_threshold}'),array($completion_threshold_fixed_project_value),$completion_threshold_disclaimer_msg_po_view);


							$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmationModalBody.'</div>';
							$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><div class="chkText">';						
							if($bid_data['bidding_dropdown_option'] == 'NA'){
								$confirmation_modal_body.= '<label><i class="fas fa-gavel default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt').'</span><span class="touch_line_break">'.number_format($bid_data['initial_bidded_amount'], 0, '', ' '). ' '.CURRENCY.'</span></b></label>';
								
								/* if($bid_data['project_delivery_period'] > 1){
									$day = $this->config->item('day_plural_txt');
								}else{
									$day = $this->config->item('day_singular_txt');
								}
								 */
								 
								if(floatval($bid_data['project_delivery_period']) == 1){
									$day = $this->config->item('1_day');
								}else if(floatval($bid_data['project_delivery_period']) >=2 && floatval(	$bid_data['project_delivery_period']) <= 4){
									$day = $this->config->item('2_4_days');
								}else if(floatval($bid_data['project_delivery_period']) >4){
									$day = $this->config->item('more_than_or_equal_5_days');
								}
								
								
								$confirmation_modal_body.= '<label><i class="far fa-clock default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_delivery_in')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_delivery_in_txt').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($bid_data['project_delivery_period'],  2, '.', ' ')). ' '.$day.'</span></b></label>';
							}else{
								if($bid_data['bidding_dropdown_option'] == 'confidential'){
								$confirmation_modal_body.= '<label><i class="fas fa-gavel default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt').'</span><span class="touch_line_break">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_confidential_txt').'</span></b></label>';
								}
								if($bid_data['bidding_dropdown_option'] == 'to_be_agreed'){
									$confirmation_modal_body.= '<label><i class="fas fa-gavel default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt').'</span><span class="touch_line_break">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_to_be_agreed_txt').'</span></b></label>';
								}
							}
							
							$confirmation_modal_body.= '<label><i class="fas fa-coins default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_project_value')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_project_value_txt').'</span><span class="touch_line_break">'.$completion_threshold_fixed_project_value.'</span></b>';
							
							$confirmation_modal_body.= '</label></div></div></div></div>';
						
						}
						
						if($project_data['project_type'] == 'hourly') {

							if($bid_data['bidding_dropdown_option'] == 'NA'){
								$confirmationModalBody = $this->config->item('award_bid_confirmation_hourly_project_modal_body_po_view');
								$confirmationModalBody = str_replace(array('{hourly_rate_based_budget_project_title}','{user_first_name_last_name_or_company_name}','{hourly_rate}','{number_hours}','{total_bid_value}'),array('<a href="#" class="default_popup_blue_text">'.$project_title.'</a>','<a href="#" class="default_popup_blue_text">'.$name.'</a>',$bid_data['initial_bidded_hourly_rate'],$bid_data['project_delivery_hours'],number_format(($bid_data['initial_bidded_hourly_rate'] * $bid_data['project_delivery_hours']), 0, '', ' ')),$confirmationModalBody);
							}else{
								if($bid_data['bidding_dropdown_option'] == 'confidential'){
									$confirmationModalBody = $this->config->item('award_bid_confirmation_hourly_project_modal_body_po_view_confidential');
								}
								if($bid_data['bidding_dropdown_option'] == 'to_be_agreed'){
									$confirmationModalBody = $this->config->item('award_bid_confirmation_hourly_project_modal_body_po_view_to_be_agreed');
								}
								$confirmationModalBody = str_replace(array('{hourly_rate_based_budget_project_title}','{user_first_name_last_name_or_company_name}'),array('<a href="#" class="default_popup_blue_text">'.$project_title.'</a>','<a href="#" class="default_popup_blue_text">'.$name.'</a>'),$confirmationModalBody);
							}
							$completion_threshold_disclaimer_msg_po_view = $this->config->item('user_confirmation_check_box_txt');


							$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmationModalBody.'</div>';
							if(!in_array($bid_data['bidding_dropdown_option'], ['confidential', 'to_be_agreed'])) {
								$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><div class="chkText">';						
								if($bid_data['bidding_dropdown_option'] == 'NA'){
									$confirmation_modal_body.= '<label><i class="fas fa-gavel default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('hourly_rate_based_project_award_bid_modal_body_po_view_tooltip_message_sp_bidded_hourly_rate')).'">'.$this->config->item('project_details_page_bidder_listing_bidded_hourly_rate_txt').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($bid_data['initial_bidded_hourly_rate'],  2, '.', ' ')). ' '.CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour').'</span></b></label>';
									
									/* if($bid_data['project_delivery_hours'] > 1){
										$hour = $this->config->item('hourly_rate_based_project_award_bid_modal_body_po_view_hour_plural');
									} else {
										$hour = $this->config->item('hourly_rate_based_project_award_bid_modal_body_po_view_hour_singular');
									}	 */	
									if(floatval($bid_data['project_delivery_hours']) == 1){
										$hour = $this->config->item('1_hour');
									}else if(floatval($bid_data['project_delivery_hours']) >=2 && floatval($bid_data['project_delivery_hours']) <= 4){
										$hour = $this->config->item('2_4_hours');
									}else if(floatval($bid_data['project_delivery_hours']) >4){
										$hour = $this->config->item('more_than_or_equal_5_hours');
									}
									
									
									$confirmation_modal_body.= '<label><i class="fas fa-calendar default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('hourly_rate_based_project_award_bid_modal_body_po_view_tooltip_message_estimated_hours')).'">'.$this->config->item('project_details_page_bidder_listing_details_estimated_hours').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($bid_data['project_delivery_hours'],  2, '.', ' ')). ' '.$hour.'</span></b></label>';
								}else{
									// if($bid_data['bidding_dropdown_option'] == 'confidential'){
									// $confirmation_modal_body.= '<label><i class="fas fa-gavel default_icon_help"></i><b class="default_black_bold"><span data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt').'</span>'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_confidential_txt').'</b></label>';
									// }
									// if($bid_data['bidding_dropdown_option'] == 'to_be_agreed'){
									// 	$confirmation_modal_body.= '<label><i class="fas fa-gavel default_icon_help"></i><b class="default_black_bold"><span data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_tooltip_message_bid_amount')).'">'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_bid_amount_txt').'</span>'.$this->config->item('fixed_budget_project_award_bid_modal_body_po_view_to_be_agreed_txt').'</b></label>';
									// }
								}
								
								$confirmation_modal_body.= '<label><i class="fas fa-coins default_icon_help"></i><b class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$this->config->item('hourly_rate_based_project_award_bid_modal_body_po_view_tooltip_message_project_value')).'">'.$this->config->item('hourly_rate_based_project_award_bid_modal_body_po_view_project_value_txt').'</span><span class="touch_line_break">'.number_format(($bid_data['initial_bidded_hourly_rate'] * $bid_data['project_delivery_hours']), 0, '', ' ').' '.CURRENCY.'</span></b>';
								
								$confirmation_modal_body.= '</label></div></div></div></div>';
							}
						}

						$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="award_bid_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$completion_threshold_disclaimer_msg_po_view.'</span></label></div></div></div>';
						
						
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_btn_padding" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn award_bid_po width-auto default_btn_padding" data-attr="'.Cryptor::doEncrypt($bid_data['bidder_id']).'" data-po-id="'.Cryptor::doEncrypt($po_id).'">'.$this->config->item('award_btn_txt').'</button>';
					}
					
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				}
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to award the bid .
	*/
	public function award_bid(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			
			if(check_session_validity()){ 

				$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
				$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
				$user = $this->session->userdata ('user');
				if($po_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_award_bid_deleted_project')]);
					die;
					
				}
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_type == 'fulltime'){
						$error_message = $this->config->item('project_details_page_po_view_award_bid_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_award_bid_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				$featured_upgrade_expired_status = 0;
				$urgent_upgrade_expired_status = 0;
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
					$project_data['project_expiration_date'] = $project_data['fulltime_project_expiration_date'];

				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				
				
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$award_project_table_name = 'fixed_budget_projects_awarded';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$progress_project_table_name = 'fixed_budget_projects_progress';
					$expired_project_table_name = 'fixed_budget_projects_expired';
					
					$po_award_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_award_bid_user_activity_log_displayed_message');
					
					
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
					
						if($user[0]->gender == 'M'){
							if($user[0]->is_authorized_physical_person == 'Y'){
								$sp_award_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_male_user_activity_log_displayed_message');
							}else{
								$sp_award_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_male_user_activity_log_displayed_message');
							}
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$sp_award_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_female_user_activity_log_displayed_message');
							}else{
								$sp_award_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_female_user_activity_log_displayed_message');
							}
						}
						
					}else{
						$sp_award_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_user_activity_log_displayed_message');
					}
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$award_project_table_name = 'hourly_rate_based_projects_awarded';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$progress_project_table_name = 'hourly_rate_based_projects_progress';
					$expired_project_table_name = 'hourly_rate_based_projects_expired';
					
					$po_award_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_award_bid_user_activity_log_displayed_message');
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M'){ 
							if($user[0]->is_authorized_physical_person == 'Y'){
								$sp_award_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_male_user_activity_log_displayed_message');
							
							}else{
								$sp_award_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_male_user_activity_log_displayed_message');
							}
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$sp_award_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_app_female_user_activity_log_displayed_message');
							}else{
								$sp_award_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_female_user_activity_log_displayed_message');
							}
						}
					} else {
						$sp_award_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_is_awarded_bid_by_po_company_user_activity_log_displayed_message');
					}
					
						
				} else if($project_data['project_type'] == 'fulltime'){
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$award_bid_table_name = 'fulltime_projects_awarded_tracking';
					$award_project_table_name = '';
					$expired_project_table_name = 'fulltime_projects_expired';
					
					$po_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_awarded_application_user_activity_log_displayed_message');
					// $sp_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_sp_when_is_awarded_bid_user_activity_log_displayed_message');
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
						if($user[0]->gender == 'M'){ 
						
							if($user[0]->is_authorized_physical_person == 'Y'){
								$sp_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_awarded_application_user_activity_log_displayed_message');
							}else{
						
								$sp_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_awarded_application_user_activity_log_displayed_message');
							}	
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
								$sp_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_female_awarded_application_user_activity_log_displayed_message');
							}else{
								$sp_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_awarded_application_user_activity_log_displayed_message');
							}
						}
					} else {
						$sp_award_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_awarded_application_user_activity_log_displayed_message');
					}
				}
				if(!empty($inprogress_bid_table_name)) {
				
					if($project_data['project_type'] == 'fulltime'){
						$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employer_id'=>$user[0]->user_id,'employee_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
					}else{
						$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
					}
				
				
					
				}
				if($check_already_in_progress_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_po_view_award_bid_in_progress_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_award_bid_in_progress_same_sp_project');
					}
					
					$sp_data = $this->db // get the user detail
					->select('u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from('users u')
					->where('u.user_id', $bidder_id)
					->get()->row_array();
					
					
					$name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?  $sp_data['first_name']." ".$sp_data['last_name'] : $sp_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$error_message);
					
					
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if($project_data['project_type'] != 'fulltime' ){
				
					$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id,'winner_id'=>$bidder_id])->from($completed_bid_table_name)->count_all_results();
					
					if($check_already_completed_bid > 0){
						$sp_data = $this->db // get the user detail
						->select('u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
						->from('users u')
						->where('u.user_id', $bidder_id)
						->get()->row_array();
						
						$name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?  $sp_data['first_name']." ".$sp_data['last_name'] : $sp_data['company_name'];
						
						
						
						$error_message = $this->config->item('project_details_page_po_view_same_sp_try_award_bid_on_completed_project');
						$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$error_message);
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
				
				}

				if($project_data['project_type'] == 'fulltime'){ 
					$awarded_bid_data = $this->db // get the user detail
					->select('at.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from($award_bid_table_name.' at')
					->join('users u', 'u.user_id = at.employee_id', 'left')
					->where('at.fulltime_project_id', $project_id)
					->where('at.employee_id', $bidder_id)
					->get()->row_array();
				} else {
					$awarded_bid_data = $this->db // get the user detail
					->select('at.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from($award_bid_table_name.' at')
					->join('users u', 'u.user_id = at.winner_id', 'left')
					->where('at.project_id', $project_id)
					->where('at.winner_id', $bidder_id)
					->get()->row_array();
				}
				
				if(!empty($awarded_bid_data)){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_awarded_same_sp_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_awarded_same_sp_project');
					}
					$name = (($awarded_bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($awarded_bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $awarded_bid_data['is_authorized_physical_person'] == 'Y')) ?  $awarded_bid_data['first_name']." ".$awarded_bid_data['last_name'] : $awarded_bid_data['company_name'];
					
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				
				//$bid_data = $this->db->get_where($bid_table_name, ['project_id' => $project_id,'bidder_id'=>$bidder_id,'project_owner_id'=>$user[0]->user_id])->row_array();
				
				if($project_data['project_type'] == 'fulltime') { 
					$bid_data = $this->db // get the user detail
					->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name')
					->from($bid_table_name.' bd')
					->join('users u', 'u.user_id = bd.employee_id', 'left')
					->where('bd.fulltime_project_id', $project_id)
					->where('bd.employee_id', $bidder_id)
					->get()->row_array();
					
				} else {
					$bid_data = $this->db // get the user detail
					->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name')
					->from($bid_table_name.' bd')
					->join('users u', 'u.user_id = bd.bidder_id', 'left')
					->where('bd.project_id', $project_id)
					->where('bd.bidder_id', $bidder_id)
					->get()->row_array();
				}
				
				if(empty($bid_data)) {
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_award_bid_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($project_data['project_type'] == 'fulltime'){
					$bid_data['project_id'] = $bid_data['fulltime_project_id'];
					$bid_data['project_owner_id'] = $bid_data['employer_id'];
					$bid_data['bidder_id'] = $bid_data['employee_id'];
					$bid_data['bidding_dropdown_option'] = $bid_data['application_bidding_dropdown_option'];
				
				}
				$bidder_data = $this->db // get the user detail
				->select('u.profile_name')
				->from('users u')
				->where('u.user_id', $bidder_id)
				->get()->row_array();
				
				
				//$remove_id = $bid_data['id'];
				
				$award_expiration_time_arr = explode(':', $this->config->item('award_expiration_time'));
			
				if($project_data['project_type'] == 'fulltime'){ 
					$award_bid_data['fulltime_project_id'] = $bid_data['project_id'];
					$award_bid_data['employer_id'] = $bid_data['project_owner_id'];
					$award_bid_data['employee_id'] = $bid_data['bidder_id'];
					$award_bid_data['application_bidding_dropdown_option'] = $bid_data['bidding_dropdown_option'];
				} else {
					$award_bid_data['project_id'] = $bid_data['project_id'];
					$award_bid_data['project_owner_id'] = $bid_data['project_owner_id'];
					$award_bid_data['winner_id'] = $bid_data['bidder_id'];
					$award_bid_data['bidding_dropdown_option'] = $bid_data['bidding_dropdown_option'];
				}
				
				
				if($project_data['project_type'] == 'fixed'){
				
					$completion_threshold_fixed_project_value = $this->Bidding_model->get_minimum_required_completion_threshold_fixed_project($bid_data['project_id'],$bid_data['bidder_id'],$bid_data['project_owner_id'],$project_status_table_array['project_status']);
				
				
					$award_expiration_date = date('Y-m-d H:i:s', strtotime('+'.(int)$award_expiration_time_arr[0].' hour +'.(int)$award_expiration_time_arr[1].' minutes +'.(int)$award_expiration_time_arr[2].' seconds'));
					
					$award_bid_data['bid_description'] = $bid_data['bid_description'];
					$award_bid_data['bid_date'] = $bid_data['bid_date'];
					$award_bid_data['project_awarded_date'] = date('Y-m-d H:i:s');
					$award_bid_data['project_award_expiration_date']  = $award_expiration_date;
					
					$award_bid_data['initial_project_agreed_value'] = $completion_threshold_fixed_project_value;
					$award_bid_data['awarded_amount'] = $bid_data['initial_bidded_amount'];
					$award_bid_data['project_delivery_period'] = $bid_data['project_delivery_period'];
				} else if($project_data['project_type'] == 'hourly'){
					$award_expiration_date = date('Y-m-d H:i:s', strtotime('+'.(int)$award_expiration_time_arr[0].' hour +'.(int)$award_expiration_time_arr[1].' minutes +'.(int)$award_expiration_time_arr[2].' seconds'));
				
					$award_bid_data['bid_description'] = $bid_data['bid_description'];
					$award_bid_data['bid_date'] = $bid_data['bid_date'];
					$award_bid_data['awarded_hours'] = $bid_data['project_delivery_hours'];
					$award_bid_data['awarded_hourly_rate'] = $bid_data['initial_bidded_hourly_rate'];
					$award_bid_data['project_awarded_date'] = date('Y-m-d H:i:s');
					$award_bid_data['project_award_expiration_date']  = $award_expiration_date;
					
					
				} else if($project_data['project_type'] == 'fulltime'){
					
					$award_expiration_date = date('Y-m-d H:i:s', strtotime('+'.(int)$award_expiration_time_arr[0].' hour +'.(int)$award_expiration_time_arr[1].' minutes +'.(int)$award_expiration_time_arr[2].' seconds'));
								
					$award_bid_data['application_description'] = $bid_data['application_description'];
					$award_bid_data['application_date'] = $bid_data['application_date'];
					$award_bid_data['awarded_salary'] = $bid_data['initial_requested_salary'];
					$award_bid_data['application_awarded_date'] = date('Y-m-d H:i:s');
					$award_bid_data['application_award_expiration_date']  = $award_expiration_date;

				}
				$project_status = '';
				$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
				$urgent_upgrade_expired_status = 0;
				$featured_upgrade_expired_status = 0;
				if(!empty($bid_data)){
					if($project_data['project_type'] == 'fulltime'){ 
						$check_award_bid_data_exists = $this->db->where(['fulltime_project_id' => $bid_data['project_id'],'employer_id'=>$bid_data['project_owner_id'],'employee_id'=>$bid_data['bidder_id']])->from($award_bid_table_name)->count_all_results();
					} else {
						$check_award_bid_data_exists = $this->db->where(['project_id' => $bid_data['project_id'],'project_owner_id'=>$bid_data['project_owner_id'],'winner_id'=>$bid_data['bidder_id']])->from($award_bid_table_name)->count_all_results();
					}
					
					if($check_award_bid_data_exists == 0){
						$this->db->insert ($award_bid_table_name, $award_bid_data);// maintain history of bid
					}
					if($project_data['project_type'] == 'fulltime'){ 
						$this->db->delete($bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id,'employer_id'=>$user[0]->user_id]); 
					} else {
						$this->db->delete($bid_table_name, ['project_id' => $project_id,'bidder_id'=>$bidder_id,'project_owner_id'=>$user[0]->user_id]); 
					}
					
					if($project_data['project_type'] != 'fulltime') {
						$check_award_project_data_exists = $this->db->where(['project_id' => $bid_data['project_id']])->from($award_project_table_name)->count_all_results();
					}
					######## connectivity of remote server start #######
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);
					//$is_project_saved_in_awarded_table = 0;
					
					if(($check_award_project_data_exists == 0 && $project_data['project_type'] != 'fulltime' && ($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired')) || ($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding' && $project_expiration_date <= time())){
					
					
						unset($project_data['profile_name']);
						unset($project_data['id']);
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							
							unset($project_data['featured']);
							unset($project_data['urgent']);
						}
						if($project_data['project_type'] == 'fulltime'){
						
							$expired_data = [
									'fulltime_project_id' => $project_data['project_id'],
									'employer_id' => $project_data['project_owner_id'],
									'fulltime_project_posting_date' => $project_data['project_posting_date'],
									'fulltime_project_expiration_date' => $project_data['project_expiration_date'],
									'fulltime_project_title' => $project_data['project_title'],
									'fulltime_project_description' => $project_data['project_description'],
									'locality_id' => $project_data['locality_id'],
									'county_id' => $project_data['county_id'],
									'postal_code_id' => $project_data['postal_code_id'],
									'min_salary' => $project_data['min_budget'],
									'max_salary' => $project_data['max_budget'],
									'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
									'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
									'escrow_payment_method' => $project_data['escrow_payment_method'],
									'offline_payment_method' => $project_data['offline_payment_method'],
									'sealed' => $project_data['sealed'],
									'hidden' => $project_data['hidden'],
									'views' => $project_data['views'],
									'revisions' => $project_data['revisions']
								];
						
							$this->db->insert ('fulltime_projects_expired', $expired_data);// insert into expired fulltime project table name
							$project_status = $this->config->item('project_status_expired');
						}
						
						if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired'){
							$this->db->insert ($award_project_table_name, $project_data);// insert into awarded project table name
							//$is_project_saved_in_awarded_table = 1;
							$project_status = $this->config->item('project_status_awarded');
						}
						
						############### update the refresh sequence table start ########
						
						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);



						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						// remove entry from project latest refresh sequence tracking table
						$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project_data['project_id']));
						
						############### update the refresh sequence table end ########
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
						
							$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
						}
						if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'expired'){
							$this->db->delete($expired_project_table_name, ['project_id' => $project_id]);
						}
						
						
						############move project attachment/bid attachment folder from open for bidding to awarded folder###
					
						
						
						
						
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							
						$project_expired_dir = PROJECT_EXPIRED_DIR;
						$project_awarded_dir = PROJECT_AWARDED_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name . DIRECTORY_SEPARATOR);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir);
						
						
						
						
						
						
						
						if($project_data['project_type'] != 'fulltime' && ($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired')) {
						
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_owner_attachments_dir);
							
							
							
							if($project_status_table_array['project_status'] == 'open_for_bidding'){
								$source_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir;
							}
							if($project_status_table_array['project_status'] == 'expired'){
								$source_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir;
							}
							$destination_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.DIRECTORY_SEPARATOR;
						}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir);
							
							$source_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir;
							
							$destination_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR;
						}
							
						// project cover picture code for fixed/hourly project 
						
						if($project_data['project_type'] != 'fulltime'  && $project_status_table_array['project_status'] == 'open_for_bidding'){

							$project_cover_picture_data = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_id))->row_array();
							
							
							if(!empty($project_cover_picture_data) && !empty($project_cover_picture_data['project_cover_picture_name'])){
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture);
							
							
								  $source_cover_picture_attachment_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
								
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture))){
									
									$file_size = $this->ftp->get_filesize($source_cover_picture_attachment_path);
									
									if($file_size != '-1')
									{
										
										$destination_cover_picture_attachment_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
										
										$this->ftp->move($source_cover_picture_attachment_path, $destination_cover_picture_attachment_path);
										
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
								
									if($project_data['project_type'] != 'fulltime'){
								
										$destination_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_owner_attachments_dir.end($arr);
										$this->ftp->move($path, $destination_path);
									}
									if($project_data['project_type'] == 'fulltime'){
								
										$destination_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir.end($arr);
										$this->ftp->move($path, $destination_path);
									}
								}
							}
						}
						
						$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
						if(!empty($bid_attachments)){
							
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir);
							
							foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
								//	
								
								if($project_status_table_array['project_status'] == 'open_for_bidding'){
									$bid_attachment_directory_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
								}
								
								if($project_data['project_type'] != 'fulltime'){
									
									if($project_status_table_array['project_status'] == 'expired'){
										$bid_attachment_directory_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
									}
								}
								
								if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
									
									if($project_data['project_type'] != 'fulltime'){
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
									}
									if($project_data['project_type'] == 'fulltime'){
									
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
									}
									
									if($project_status_table_array['project_status'] == 'open_for_bidding'){
										$source_bid_attachment_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									}
									if($project_data['project_type'] != 'fulltime'){
										if($project_status_table_array['project_status'] == 'expired'){
											$source_bid_attachment_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
									}
									
									$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
									if($file_size != '-1')
									{
										if($project_data['project_type'] != 'fulltime'){
											$destination_bid_attachment_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										if($project_data['project_type'] == 'fulltime'){
											$destination_bid_attachment_path = $users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
									}
								}
							}
						
						}
						
						// remov entry from open bidding table
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
							$this->ftp->delete_dir($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
						}
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id))) {
							$this->ftp->delete_dir($users_ftp_dir.$user[0]->profile_name.$projects_ftp_dir.$project_expired_dir.$project_id);
						}
					}
					
					####### generate log #########
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					
					$po_profile_url_link = VPATH.$user[0]->profile_name;
					$sp_profile_url_link = VPATH.$bid_data['profile_name'];
					
					$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )) ?  $user[0]->first_name." ".$user[0]->last_name : $user[0]->company_name;
					
					$sp_name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y'))?$bid_data['first_name'] . ' ' . $bid_data['last_name'] :$bid_data['company_name'];
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE || $user[0]->is_authorized_physical_person == 'Y' )){
					
						$sp_award_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name}','{po_profile_url_link}','{award_expiration_date}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name,$po_profile_url_link,date(DATE_TIME_FORMAT,strtotime($award_expiration_date))),$sp_award_bid_activity_log_message);
					} else {
					
						$sp_award_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_company_name}','{po_profile_url_link}','{award_expiration_date}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name,$po_profile_url_link,date(DATE_TIME_FORMAT,strtotime($award_expiration_date))),$sp_award_bid_activity_log_message);
					}

					$po_award_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{sp_profile_url_link}','{user_first_name_last_name_or_company_name}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$sp_profile_url_link,$sp_name),$po_award_bid_activity_log_message);
					
					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_data['project_id'],$project_data['project_type']); // get bid count of project
					
					$project_bid_count_history = $project_bid_count;
					
					if($project_data['project_type'] == 'fulltime') {
						$awarded_bidder_data = $this->db // get the user detail
						->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,ab.*')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
						->from($award_bid_table_name. ' ab')
						->join('users u', 'u.user_id = ab.employee_id', 'left')
						->join('users_details ud', 'ud.user_id = u.user_id', 'left')
						->where('fulltime_project_id', $award_bid_data['fulltime_project_id'])
						->where('employee_id', $award_bid_data['employee_id'])
						->where('ab.employer_id', $award_bid_data['employer_id'])
						->get()->row_array();
						$awarded_bidder_data['project_id'] = $awarded_bidder_data['fulltime_project_id'];
						$awarded_bidder_data['project_owner_id'] = $awarded_bidder_data['employer_id'];
						$awarded_bidder_data['winner_id'] = $awarded_bidder_data['employee_id'];
						$awarded_bidder_data['bidding_dropdown_option'] = $awarded_bidder_data['application_bidding_dropdown_option'];
					} else {
						$awarded_bidder_data = $this->db // get the user detail
						->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,ab.*')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal") as sp_total_completed_hourly_based_projects')
						->from($award_bid_table_name. ' ab')
						->join('users u', 'u.user_id = ab.winner_id', 'left')
						->join('users_details ud', 'ud.user_id = u.user_id', 'left')
						->where('project_id', $award_bid_data['project_id'])
						->where('winner_id', $award_bid_data['winner_id'])
						->where('ab.project_owner_id', $award_bid_data['project_owner_id'])
						->get()->row_array();
					}

					$common_source_path = USERS_FTP_DIR . $awarded_bidder_data['profile_name'];

					//avatar picture
					//start check avatar from ftp server
					$user_avatar = USER_AVATAR;
					$source_path_avatar = $common_source_path . $user_avatar;
					$avatarlist = $this->ftp->list_files($source_path_avatar);
					$avatar_pic = $source_path_avatar . $awarded_bidder_data['user_avatar'];

					$exap = explode('.', $awarded_bidder_data['user_avatar']);
					$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

					if (count($avatarlist) > 0) {
						$acheck = true;
						if (!in_array($avatar_pic, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $awarded_bidder_data['user_id']));
							$this->ftp->delete_dir($source_path_avatar);
							$awarded_bidder_data['user_avatar'] = '';
							$acheck = false;
							// profile completeion script start //
							$user_profile_completion_data = array();
							$user_profile_completion_data['has_avatar'] = 'N';
							$user_profile_completion_data['avatar_strength_value'] =0;
							if($user_profile_completion_data){
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$awarded_bidder_data['user_id'],'account_type'=>$awarded_bidder_data['account_type']));
							}
							// profile completeion script end //
							
						} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$awarded_bidder_data['user_id']));
							$this->ftp->delete_dir($source_path_avatar);
							$awarded_bidder_data['user_avatar'] = '';
							$acheck = false;
							
							// profile completeion script start //
							$user_profile_completion_data = array();
							$user_profile_completion_data['has_avatar'] = 'N';
							$user_profile_completion_data['avatar_strength_value'] =0;
							if($user_profile_completion_data){
								$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$awarded_bidder_data['user_id'],'account_type'=>$awarded_bidder_data['account_type']));
							}
							// profile completeion script end //
							
							
						}
					} if (count($avatarlist) == 0 && $awarded_bidder_data['user_avatar'] != '') {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $awarded_bidder_data['user_id']));
						$awarded_bidder_data['user_avatar'] = '';
						
						// profile completeion script start //
						$user_profile_completion_data = array();
						$user_profile_completion_data['has_avatar'] = 'N';
						$user_profile_completion_data['avatar_strength_value'] =0;
						if($user_profile_completion_data){
							$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$awarded_bidder_data['user_id'],'account_type'=>$awarded_bidder_data['account_type']));
						}
						// profile completeion script end //
					}

					$this->ftp->close();
					######## connectivity of remote server end ####### 
					
					user_display_log($sp_award_bid_activity_log_message,$bidder_id); // activity log message for service provider
					user_display_log($po_award_bid_activity_log_message); // activity log message for project owner
					
					$awarded_bidder_attachment_list = $this->db // get the user detail
					->select('id,bid_attachment_name,user_id')
					->from('projects_active_bids_users_attachments_tracking')
					->where('project_id', $awarded_bidder_data['project_id'])
					->where('user_id', $awarded_bidder_data['winner_id'])
					->order_by('id DESC')
					->get()->result_array();
					$awarded_bidder_data['bid_attachments'] = $awarded_bidder_attachment_list;
					
					$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
					if($project_expiration_date > time() && $project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'awarded'){
					
						
					
					
						$this->db->select('pd.project_id,pd.project_expiration_date,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
						
						$this->db->from($project_status_table_array['table_name']." as pd");
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->where('pd.project_id',$project_id);
						
						$project_result = $this->db->get();
						$project_info = $project_result->row_array();
						
						
						
						if(!empty($project_info)){
							$featured_max = 0;
							$urgent_max = 0;
							$project_expiration_date = $project_info['project_expiration_date']!= NULL ? strtotime ($project_info['project_expiration_date']) : 0;
							if(!empty($project_info['featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['featured_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['bonus_featured_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['membership_include_featured_upgrade_end_date'];
							}
							if(!empty($expiration_featured_upgrade_date_array)){
								$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $featured_max >= time()){
								$featured_upgrade_expired_status = 1;
							}else{
							
								$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($user[0]->profile_name,$project_id);
							}
							
							if(!empty($project_info['urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['urgent_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['bonus_urgent_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['membership_include_urgent_upgrade_end_date'];
							}
							if(!empty($expiration_urgent_upgrade_date_array)){
								$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $urgent_max >= time()){
								$urgent_upgrade_expired_status = 1;
							}
						}
					}else{
						$urgent_upgrade_expired_status = 0;
						$featured_upgrade_expired_status = 0;
						$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($user[0]->profile_name,$project_id);
						
					}
					$is_project_expired = '0';
					if($project_expiration_date < time()){
						$is_project_expired = '1';
					
					}
					
					echo json_encode(['status' => 200,'is_project_expired'=>$is_project_expired,'featured_upgrade_expired_status'=>$featured_upgrade_expired_status,'urgent_upgrade_expired_status'=>$urgent_upgrade_expired_status,'section_id' => $awarded_bidder_data['id'],'location'=>'','message'=>'','remove_id'=>$bid_data['bidder_id'],'project_id'=>$project_data['project_id'],'sp_id'=>$bid_data['bidder_id'],'project_status'=>$project_status,'project_bid_count_history'=>$project_bid_count_history,'data'=>$this->load->view('project_awarded_bidders_listing',array('project_data'=>$project_data,'awarded_bidder_data'=>$awarded_bidder_data), true)]);
					die;
					
				}
				
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to make the popup of decline award confirmation .
	*/
	public function decline_award_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))) {
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			if(check_session_validity()){ 
				$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
				$user = $this->session->userdata ('user');
				if($bidder_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_decline_award_deleted_project')]);
					die;
				}
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_status_table_array['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_decline_award_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_cancelled_project');
					}
					
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_id as project_id,pd.project_type')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.project_id,pd.project_type')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				if($project_data['project_type'] == 'fixed'){
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$active_bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_data['project_type'] == 'hourly'){
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$active_bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_data['project_type'] == 'fulltime'){
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$active_bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($active_bid_table_name)->count_all_results();
				} else {
					$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$bidder_id])->from($active_bid_table_name)->count_all_results();
				}
				if($check_already_active_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_award_already_declined_or_expired_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_award_already_declined_or_expired_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				/* if(!empty($inprogress_bid_table_name)) {
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				} */
				if($check_already_in_progress_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_try_decline_award_in_progress_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_in_progress_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if(!empty($completed_bid_table_name)) {
					$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_data['project_type'] != 'fulltime' && !empty($check_already_completed_bid) && $check_already_completed_bid > 0){
					
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_decline_award_on_completed_project');
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if($project_data['project_type'] == 'fulltime') { 
					$awarded_bid_data = $this->db->get_where($awarded_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->row_array();
				} else {
					$awarded_bid_data = $this->db->get_where($awarded_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id])->row_array();
				}
				if($active_bid_data == 0 && $inprogress_bid_data == 0 && empty($awarded_bid_data)){
				
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_already_retracted_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				/* if(!empty($awarded_bid_data)){ */
					$confirmation_modal_title = '';
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_title = $this->config->item('decline_award_confirmation_fulltime_project_modal_title_employee_view');
						$confirmation_modal_body = $this->config->item('decline_award_confirmation_fulltime_project_modal_body_employee_view');
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body.'</div>';
						$confirmation_modal_body .= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="decline_bid_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></label></div></div></div>';
						$confirmation_modal_footer = '<button type="button" class="btn decline_bid red_btn decline_bid_sp default_btn project_cancel_button width-auto" disabled style="opacity:0.65" data-attr="'.Cryptor::doEncrypt($bidder_id).'">'.$this->config->item('decline_btn_txt').'</button><button type="button" class="btn blue_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>';
					} else {
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('decline_award_confirmation_project_modal_body_sp_view').'</div>';
						
						$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="decline_bid_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></label></div></div></div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn decline_bid_sp project_cancel_button width-auto" data-attr="'.Cryptor::doEncrypt($bidder_id).'" disabled style="opacity:0.65">'.$this->config->item('decline_btn_txt').'</button>';
					}
					
				
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
				
				
			} else {
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to decline the award.
	*/
	public function decline_award(){
		
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			if(check_session_validity()){ 
				$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
				$user = $this->session->userdata ('user');
				if($bidder_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_decline_award_deleted_project')]);
					die;
				}
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_status_table_array['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_decline_award_cancelled_fulltime_project');
					 }else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				$featured_upgrade_expired_status = 0;
				$urgent_upgrade_expired_status = 0;
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
					$project_data['project_expiration_date'] = $project_data['fulltime_project_expiration_date'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				
				$login_user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
				
				$bid_data = array();
				$decline_bid_history_data = array();
				if($project_data['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$award_project_table_name = 'fixed_budget_projects_awarded';
					$awards_not_completed_table_name = 'fixed_budget_projects_awards_not_completed_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';

					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						if($user[0]->gender == 'M'){
						
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_decline_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_declined_awarded_bid_user_activity_log_displayed_message');
							}else{
						
								$po_decline_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_declined_awarded_bid_user_activity_log_displayed_message');
							}
						
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_decline_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_declined_awarded_bid_user_activity_log_displayed_message');
							}else{
								$po_decline_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_declined_awarded_bid_user_activity_log_displayed_message');
							}
							
						}
					}else{
						$po_decline_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_declined_awarded_bid_user_activity_log_displayed_message');
					
					}
					$sp_decline_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_declined_awarded_bid_user_activity_log_displayed_message');
					
				} else if($project_data['project_type'] == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$award_project_table_name = 'hourly_rate_based_projects_awarded';
					$awards_not_completed_table_name = 'hourly_rate_based_projects_awards_not_completed_tracking';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						if($user[0]->gender == 'M'){
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_decline_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_male_declined_awarded_bid_user_activity_log_displayed_message');
							
							}else{
						
								$po_decline_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_male_declined_awarded_bid_user_activity_log_displayed_message');
							}
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
							
								$po_decline_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_female_declined_awarded_bid_user_activity_log_displayed_message');
							}else{
								$po_decline_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_female_declined_awarded_bid_user_activity_log_displayed_message');
							}
						}
					} else {
						$po_decline_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_declined_awarded_bid_user_activity_log_displayed_message');
					}
					$sp_decline_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_declined_awarded_bid_user_activity_log_displayed_message'); 
						
				} else if($project_data['project_type'] == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$award_bid_table_name = 'fulltime_projects_awarded_tracking';
					$award_project_table_name = '';
					$awards_not_completed_table_name = 'fulltime_projects_awards_not_completed_tracking';
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';

					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						if($user[0]->gender == 'M' ){
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_decline_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_declined_award_user_activity_log_displayed_message');
							}else{
								$po_decline_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_declined_award_user_activity_log_displayed_message');
							}
						} else {
							if($user[0]->is_authorized_physical_person == 'Y'){
							
								$po_decline_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_declined_award_user_activity_log_displayed_message');
							}else{
							
								$po_decline_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_declined_award_user_activity_log_displayed_message');
							}
						}
					} else {
						$po_decline_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_declined_award_user_activity_log_displayed_message');
					}
					$sp_decline_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_declined_award_user_activity_log_displayed_message');
				}
				if($project_data['project_type'] == 'fulltime') {
					$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($bid_table_name)->count_all_results();
				} else {
					$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$bidder_id])->from($bid_table_name)->count_all_results();
				}
				if($check_already_active_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_award_already_declined_or_expired_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_award_already_declined_or_expired_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				/* if(!empty($inprogress_bid_table_name)) {
					$check_already_in_progress_bid = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id])->row_array();
				} */
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				if($check_already_in_progress_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_try_decline_award_in_progress_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_in_progress_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if($project_data['project_type'] != 'fulltime' && !empty($completed_bid_table_name)) {
					$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_data['project_type'] != 'fulltime' && !empty($check_already_completed_bid) && $check_already_completed_bid > 0){
				
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_decline_award_on_completed_project');
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}

				if($project_data['project_type'] == 'fulltime') { 
					$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->row_array();
				} else {
					$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id])->row_array();
				}

				if($active_bid_data == 0 && $inprogress_bid_data == 0 && empty($awarded_bid_data)){
					
				
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_already_retracted_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_decline_award_already_retracted_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				$project_status = '';
				//$remove_id = $awarded_bid_data['id'];
				if($project_data['project_type'] == 'fulltime') {
					$bid_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
					$bid_data['employer_id'] = $awarded_bid_data['employer_id'];
					$bid_data['employee_id'] = $awarded_bid_data['employee_id'];
					$bid_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];

					$decline_bid_history_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
					$decline_bid_history_data['employer_id'] = $awarded_bid_data['employer_id'];
					$decline_bid_history_data['employee_id'] = $awarded_bid_data['employee_id'];
					$decline_bid_history_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];
				} else {
					$bid_data['project_id'] = $awarded_bid_data['project_id'];
					$bid_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
					$bid_data['bidder_id'] = $awarded_bid_data['winner_id'];
					$bid_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];

					$decline_bid_history_data['project_id'] = $awarded_bid_data['project_id'];
					$decline_bid_history_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
					$decline_bid_history_data['winner_id'] = $awarded_bid_data['winner_id'];
					$decline_bid_history_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];
				}

				
				
				if($project_data['project_type'] == 'fixed'){
				
					$project_award_expiration_date = $awarded_bid_data['project_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['project_award_expiration_date']) : 0;
					
					$bid_data['bid_description'] = $awarded_bid_data['bid_description'];
					$bid_data['bid_date'] = $awarded_bid_data['bid_date'];
					$bid_data['initial_bidded_amount'] = $awarded_bid_data['awarded_amount'];
					$bid_data['project_delivery_period'] = $awarded_bid_data['project_delivery_period'];
					
					$decline_bid_history_data['bid_description'] = $awarded_bid_data['bid_description'];
					$decline_bid_history_data['bid_date'] = $awarded_bid_data['bid_date'];
					$decline_bid_history_data['project_awarded_date'] = $awarded_bid_data['project_awarded_date'];
					$decline_bid_history_data['project_award_expiration_date'] = $awarded_bid_data['project_award_expiration_date'];
					$decline_bid_history_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
					$decline_bid_history_data['awarded_amount'] = $awarded_bid_data['awarded_amount'];
					$decline_bid_history_data['project_delivery_period'] = $awarded_bid_data['project_delivery_period'];
					if(time() > $project_award_expiration_date){
						$decline_bid_history_data['awarded_salary'] = 'award_period_expired';
					}
					
					
				} else if($project_data['project_type'] == 'hourly'){
					$project_award_expiration_date = $awarded_bid_data['project_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['project_award_expiration_date']) : 0;
					
					$bid_data['bid_description'] = $awarded_bid_data['bid_description'];
					$bid_data['bid_date'] = $awarded_bid_data['bid_date'];
					$bid_data['project_delivery_hours'] = $awarded_bid_data['awarded_hours'];
					$bid_data['initial_bidded_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
					
					$decline_bid_history_data['bid_description'] = $awarded_bid_data['bid_description'];
					$decline_bid_history_data['bid_date'] = $awarded_bid_data['bid_date'];
					$decline_bid_history_data['project_awarded_date'] = $awarded_bid_data['project_awarded_date'];
					$decline_bid_history_data['project_award_expiration_date'] = $awarded_bid_data['project_award_expiration_date'];
					$decline_bid_history_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
					$decline_bid_history_data['awarded_hours'] = $awarded_bid_data['awarded_hours'];
					$decline_bid_history_data['awarded_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
					if(time() > $project_award_expiration_date){
						$decline_bid_history_data['awarded_salary'] = 'award_period_expired';
					}
					
					
					
				} else if($project_data['project_type'] == 'fulltime'){
					$project_award_expiration_date = $awarded_bid_data['application_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['application_award_expiration_date']) : 0;
					
					$bid_data['application_description'] = $awarded_bid_data['application_description'];
					$bid_data['application_date'] = $awarded_bid_data['application_date'];
					$bid_data['initial_requested_salary'] = $awarded_bid_data['awarded_salary'];
					
					
					$decline_bid_history_data['application_description'] = $awarded_bid_data['application_description'];
					$decline_bid_history_data['application_date'] = $awarded_bid_data['application_date'];
					$decline_bid_history_data['application_awarded_date'] = $awarded_bid_data['application_awarded_date'];
					$decline_bid_history_data['application_award_expiration_date'] = $awarded_bid_data['application_award_expiration_date'];
					$decline_bid_history_data['application_award_decline_date'] = date('Y-m-d H:i:s');
					$decline_bid_history_data['awarded_salary'] = $awarded_bid_data['awarded_salary'];
					if(time() > $project_award_expiration_date){
						$decline_bid_history_data['awarded_salary'] = 'award_period_expired';
					}
					
				}
				
				//if(!empty($awarded_bid_data)){
					if($project_data['project_type'] == 'fulltime'){ 
						$check_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_data['fulltime_project_id'],'employer_id'=>$awarded_bid_data['employer_id'],'employee_id'=>$awarded_bid_data['employee_id']])->from($bid_table_name)->count_all_results();
					} else {
						$check_bid_data_exists = $this->db->where(['project_id' => $awarded_bid_data['project_id'],'project_owner_id'=>$awarded_bid_data['project_owner_id'],'bidder_id'=>$awarded_bid_data['winner_id']])->from($bid_table_name)->count_all_results();
					}
					
					if($check_bid_data_exists == 0){
						$this->db->insert ($bid_table_name, $bid_data);// maintain history of bid
					}
					$profile_name = $project_data['profile_name'];
					unset($project_data['profile_name']);
					unset($project_data['id']);
					
					$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
					//$project_status = $this->config->item('project_status_awarded');
					if($project_expiration_date > time()){
						$project_bidding_expired_table = 'projects_open_bidding';
						//$project_status = $this->config->item('project_status_open_for_bidding');
					} else {
						//$project_status = $this->config->item('project_status_expired');
						if($project_data['project_type'] == 'fixed'){
							$project_bidding_expired_table = 'fixed_budget_projects_expired';
						}
						if($project_data['project_type'] == 'hourly'){
							$project_bidding_expired_table = 'hourly_rate_based_projects_expired';
						}
						if($project_data['project_type'] == 'fulltime'){
							$project_bidding_expired_table = 'fulltime_projects_expired';
						}
					}
					
					if($project_data['project_type'] == 'fulltime') { 
						$this->db->delete($award_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id]); // remove entry from award tracking table
					} else {
						$this->db->delete($award_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id]); // remove entry from award tracking table
					}
					
					###### tracking the decline bids start ######
					$this->db->insert ($awards_not_completed_table_name, $decline_bid_history_data);// insert into table from save the history of decline bid
					###### tracking the decline bids end ########

					################# check the award bid tracking table have last entry or not if this is last entry move project otherwise not  ####
					if($project_data['project_type'] == 'fulltime') { 
						$check_award_project_bid_counts =  $this->db->where(['fulltime_project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
					} else {
						$check_award_project_bid_counts =  $this->db->where(['project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
					}
					
					if(($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $check_award_project_bid_counts == 0 && !empty($awarded_bid_data)) || ($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding' && $project_expiration_date <= time() && !empty($awarded_bid_data))) {
						
						if($project_expiration_date > time()){
							$project_status = $this->config->item('project_status_open_for_bidding');
						}else{
							$project_status = $this->config->item('project_status_expired');
						}
						$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]);
						
						if($project_data['project_type'] == 'fulltime'){ 
							$project_bidding_expired_table_data_exists = $this->db->where(['fulltime_project_id' => $bid_data['project_id']])->from($project_bidding_expired_table)->count_all_results();
						} else {
							$project_bidding_expired_table_data_exists = $this->db->where(['project_id' => $bid_data['project_id']])->from($project_bidding_expired_table)->count_all_results();
						}
						if($project_bidding_expired_table_data_exists == 0){
							
							
							$expired_data = [
								'project_id' => $project_data['project_id'],
								'project_owner_id' => $project_data['project_owner_id'],
								'project_type' => $project_data['project_type'],
								'project_posting_date' => $project_data['project_posting_date'],
								'project_expiration_date' => $project_data['project_expiration_date'],
								'project_title' => $project_data['project_title'],
								'project_description' => $project_data['project_description'],
								'locality_id' => $project_data['locality_id'],
								'county_id' => $project_data['county_id'],
								'postal_code_id' => $project_data['postal_code_id'],
								'min_budget' => $project_data['min_budget'],
								'max_budget' => $project_data['max_budget'],
								'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
								'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
								'escrow_payment_method' => $project_data['escrow_payment_method'],
								'offline_payment_method' => $project_data['offline_payment_method'],
								'sealed' => $project_data['sealed'],
								'hidden' => $project_data['hidden'],
								'views' => $project_data['views'],
								'revisions' => $project_data['revisions']
							];
							
							
							if($project_data['project_type'] == 'fulltime'){
								$expired_data['fulltime_project_id'] = $project_data['project_id'];
								$expired_data['employer_id'] = $project_data['project_owner_id'];
								$expired_data['fulltime_project_posting_date'] = $project_data['project_posting_date'];
								$expired_data['fulltime_project_expiration_date'] = $project_data['project_expiration_date'];
								$expired_data['fulltime_project_title'] = $project_data['project_title'];
								$expired_data['fulltime_project_description'] = $project_data['project_description'];
								$expired_data['min_salary'] = $project_data['min_budget'];
								$expired_data['max_salary'] = $project_data['max_budget'];

								unset($expired_data['project_id']);
								unset($expired_data['project_owner_id']);
								unset($expired_data['project_posting_date']);
								unset($expired_data['project_expiration_date']);
								unset($expired_data['project_title']);
								unset($expired_data['project_description']);
								unset($expired_data['min_budget']);
								unset($expired_data['max_budget']);
							
							}
							
							$this->db->insert ($project_bidding_expired_table, $expired_data);// insert into main project(expired/open for bidding) table name
							
							
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->load->library('ftp');
							$this->ftp->connect($config); 
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
							$project_expired_dir = PROJECT_EXPIRED_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
							$project_awarded_dir = PROJECT_AWARDED_DIR;
							$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
								
							if($project_expiration_date <= time() ){	
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$project_owner_attachments_dir);
							}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
								
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture);
									
								$project_cover_picture_data = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_id))->row_array();
					
								if(!empty($project_cover_picture_data) && !empty($project_cover_picture_data['project_cover_picture_name']) ){
									
									
									 $source_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
									
									
									
									if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture))){
										
										$file_size = $this->ftp->get_filesize($source_cover_picture_attachment_path);
										if($file_size != '-1')
										{
											$destination_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
											$this->ftp->move($source_cover_picture_attachment_path, $destination_cover_picture_attachment_path);
										}
									}
								}
							}
							if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
							
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_data['project_id'].$project_owner_attachments_dir;
								if($project_expiration_date > time()){
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
								}else{
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
								}
								
							}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir;
								
								$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
							}
							$source_list = $this->ftp->list_files($source_path);
							if(!empty($source_list)) {
								foreach($source_list as $path) {
									$arr = explode('/', $path);
									$file_size = $this->ftp->get_filesize($path);
									if($file_size != '-1') {
									
										if($project_expiration_date <= time() ){
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir.end($arr);
											$this->ftp->move($path, $destination_path);
											//$this->ftp->move($path, $destination_path);
										}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.end($arr);
											$this->ftp->move($path, $destination_path);
										}
									}
								}
							}
		
							$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
							if(!empty($bid_attachments)){
								foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
								
									
									if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
										$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
									}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
									
										$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
									}
									
									if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
										
										
										if($project_expiration_date <= time()){	
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
											
										}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
										
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir);
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
										}
										if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
											$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
											$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										
										$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
										if($file_size != '-1')
										{
											if($project_expiration_date <= time()){		
												$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
												$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											
											}
											$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
											
										}
									}
								}
							
							}
		
							if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
								// remov entry from open bidding table
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id))) {
									$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id);
								}
							}
							if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
								// remov entry from open bidding table
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
									$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
								}
							}

							if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
								if(!empty($project_data['hidden']) && $project_data['hidden'] == 'N') {
									$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
									$check_valid_arr = array_map('getInt', $time_arr); 
									$valid_time_arr = array_filter($check_valid_arr);
									$next_refresh_date = null;
									if(!empty($valid_time_arr)) {
										$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
										$refresh_sequence_data = [
											'project_id' => $project_data['project_id'],
											'project_last_refresh_time' => null,
											'project_next_refresh_time' => date('Y-m-d H:i:s')
										];
										$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
									}
									
									$latest_project_refresh_tracking = [
										'project_id' => $project_data['project_id'],
										'project_last_refresh_time' => date('Y-m-d H:i:s'),
										'project_next_refresh_time' => $next_refresh_date
									];
									$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);
								}
								if(!empty($project_data['sealed']) && $project_data['sealed'] == 'N') {
								
									$bonus_based_purchased_upgrades_tracking_data = $this->db // get the user detail
										->select('id,bonus_based_project_upgrade_purchase_reference_id')
										->from('proj_bonus_based_upgrades_purchase_tracking')
										->where('project_id', $project_data['project_id'])
										->where('project_upgrade_type', 'urgent')
										->get()->row_array();
										
									$real_money_upgrades_purchase_tracking_data = $this->db // get the user detail
									->select('id,real_money_project_upgrade_purchase_reference_id')
									->from('proj_real_money_upgrades_purchase_tracking')
									->where('project_id', $project_data['project_id'])
									->where('project_upgrade_type', 'sealed')
									->get()->row_array();	
									$purchase_tracking_id = '';
									$purchase_tracking_table_source = '';			
									if(!empty($bonus_based_purchased_upgrades_tracking_data) && !empty($real_money_upgrades_purchase_tracking_data)){
										$purchase_tracking_id = $real_money_upgrades_purchase_tracking_data['real_money_project_upgrade_purchase_reference_id'];
										$purchase_tracking_table_source = 'real_money';
									
									}else if(!empty($bonus_based_purchased_upgrades_tracking_data) && empty(	$real_money_upgrades_purchase_tracking_data)){
										$purchase_tracking_id = $bonus_based_purchased_upgrades_tracking_data['bonus_based_project_upgrade_purchase_reference_id'];
										$purchase_tracking_table_source = 'real_money';
									
									}else if(empty($bonus_based_purchased_upgrades_tracking_data) && !empty(	$real_money_upgrades_purchase_tracking_data)){
										$purchase_tracking_id = $real_money_upgrades_purchase_tracking_data['real_money_project_upgrade_purchase_reference_id'];
										$purchase_tracking_table_source = 'bonus_money';
									
									}
									if(!empty($purchase_tracking_id) && !empty($purchase_tracking_table_source)){			
										$refresh_sequence_data = [
											'project_id' => $project_data['project_id'],
											'project_upgrade_purchase_reference_id' => $purchase_tracking_id,
											'project_upgrade_purchase_tracking_table_source' => $purchase_tracking_table_source,
											'project_last_refresh_time' => null,
											'project_next_refresh_time' => date('Y-m-d H:i:s')
										];
										$this->db->insert ('sealed_projects_refresh_sequence_tracking', $refresh_sequence_data);
									}
								}
							}
							
						}
					}
					$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
					if($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'awarded'){
						
						$this->db->select('pd.project_id,pd.project_expiration_date,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
							
						$this->db->from($project_status_table_array['table_name']." as pd");
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->where('pd.project_id',$project_id);
						
						$project_result = $this->db->get();
						$project_info = $project_result->row_array();
						
						$update_upgrade = array();
						if(!empty($project_info)){
							$featured_max = 0;
							$urgent_max = 0;
							$project_expiration_date = $project_info['project_expiration_date']!= NULL ? strtotime ($project_info['project_expiration_date']) : 0;
							if(!empty($project_info['featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['featured_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['bonus_featured_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['membership_include_featured_upgrade_end_date'];
							}
							if(!empty($expiration_featured_upgrade_date_array)){
								$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $featured_max >= time()){
								$update_upgrade['featured'] = 'Y';
								$featured_upgrade_expired_status = 1;
					
							}else{
								$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($profile_name,$project_id);
							}
							
							if(!empty($project_info['urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['urgent_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['bonus_urgent_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['membership_include_urgent_upgrade_end_date'];
							}
							if(!empty($expiration_urgent_upgrade_date_array)){
								$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $urgent_max >= time()){
								$update_upgrade['urgent'] = 'Y';
								
								$urgent_upgrade_expired_status = 1;
							}
						}
						if(!empty($update_upgrade)){
							$this->db->update('projects_open_bidding',$update_upgrade, array("project_id" => $project_id));
						}
						
					}
					####### generate log #########
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					
					$sp_profile_url_link = VPATH.$user[0]->profile_name;
					
					$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ?  $user[0]->first_name." ". $user[0]->last_name : $user[0]->company_name;
					
					
					$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,is_authorized_physical_person,gender')->from('users')->where('user_id',$project_data['project_owner_id'])->get()->row_array();
					$po_name = (($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y')) ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					
					
					$sp_decline_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{po_profile_url_link}','{user_first_name_last_name_or_company_name}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$po_profile_url_link,$po_name),$sp_decline_bid_activity_log_message);
					
					
					

					user_display_log($sp_decline_bid_activity_log_message);  // activity log message for service provider
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						
						$po_decline_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name,$sp_profile_url_link),$po_decline_bid_activity_log_message);
					}else{
					
						$po_decline_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_company_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name,$sp_profile_url_link),$po_decline_bid_activity_log_message);
					}
					
					user_display_log($po_decline_bid_activity_log_message,$project_data['project_owner_id']); // activity log message for project owner 
					
					
					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_data['project_id'],$project_data['project_type']); // get bid count of project
					
					$project_bid_count_history = $project_bid_count;
					
					
					if($project_status_table_array['table_name'] == 'awarded'){
						if($check_award_project_bid_counts == 0){
							if($project_data['project_type']== 'fulltime') {
								$project_data = $this->db // get the user detail
								->select('pd.*')
								->from($project_bidding_expired_table.' pd')
								->where('pd.fulltime_project_id', $project_id)
								->get()->row_array();
								$project_data['project_id'] = $project_data['fulltime_project_id'];
								$project_data['project_owner_id'] = $project_data['employer_id'];
								$project_data['project_title'] = $project_data['fulltime_project_title'];
								$project_data['project_expiration_date'] = $project_data['fulltime_project_expiration_date'];
							} else {
								$project_data = $this->db // get the user detail
								->select('pd.*')
								->from($project_bidding_expired_table.' pd')
								->where('pd.project_id', $project_id)
								->get()->row_array();
							}
							
							
						} else {
							if($project_data['project_type']== 'fulltime') { 
								$project_data = $this->db // get the user detail
								->select('pd.*')
								->from($award_project_table_name.' pd')
								->where('pd.fulltime_project_id', $project_id)
								->get()->row_array();
							} else {
								$project_data = $this->db // get the user detail
								->select('pd.*')
								->from($award_project_table_name.' pd')
								->where('pd.project_id', $project_id)
								->get()->row_array();
							}
							
						}
					}
					
					
								
					$bidder_data = $this->Bidding_model->get_user_bid_detail($project_id,$bidder_id,$project_data['project_type'],$project_status_table_array['project_status'],$profile_name);
					
					$is_project_expired = '0';
					if($project_expiration_date < time()){
						$is_project_expired = '1';
					
					}
					
					echo json_encode(['status' => 200,'is_project_expired'=>$is_project_expired,'location'=>'','featured_upgrade_expired_status'=>$featured_upgrade_expired_status,'urgent_upgrade_expired_status'=>$urgent_upgrade_expired_status,'po_id'=>$project_data['project_owner_id'],'sp_id'=>$bidder_id,'project_id'=>$project_id,'message'=>'','remove_id'=>$bidder_id,'project_status'=>$project_status,'project_bid_count_history'=>$project_bid_count_history,'data'=>$this->load->view('project_bidders_listing',array('project_data'=>$project_data,'bidder_data'=>$bidder_data), true)]);
					die;
					
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to make the popup of accept award confirmation .
	*/
	public function accept_award_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			if(check_session_validity()){ 

				$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
				$user = $this->session->userdata ('user');
				if($bidder_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])) {
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_accept_award_deleted_project')]);
					die;
				}
				
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_status_table_array['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_accept_award_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
					
				}
				if($project_data['project_type'] == 'fixed'){
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$active_bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$in_progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					
				} else if($project_data['project_type'] == 'hourly'){
					$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$active_bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$in_progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				} else if($project_data['project_type'] == 'fulltime'){
					$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
					$active_bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$in_progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				/* if(!empty($in_progress_bid_table_name)) { 
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($in_progress_bid_table_name)->count_all_results();
				} */
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($in_progress_bid_table_name)->count_all_results();
				} else {
				
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($in_progress_bid_table_name)->count_all_results();
				}
				
				if($check_already_in_progress_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_in_progress_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_in_progress_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if(!empty($completed_bid_table_name)) {
					$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_data['project_type'] != 'fulltime' && !empty($check_already_completed_bid) && $check_already_completed_bid > 0){
				
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_accept_award_on_completed_project');
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if($project_data['project_type'] == 'fulltime') {
					$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($active_bid_table_name)->count_all_results();
				} else {
					$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$bidder_id])->from($active_bid_table_name)->count_all_results();
				}
				if($check_already_active_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_award_already_declined_or_expired_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_award_already_declined_or_expired_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if($project_data['project_type'] == 'fulltime') { 
					$awarded_bid_data = $this->db->get_where($awarded_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->row_array();
				} else {
					$awarded_bid_data = $this->db->get_where($awarded_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id])->row_array();
				}
				if($check_already_in_progress_bid == 0 && $check_already_active_bid == 0 && empty($awarded_bid_data)){
				
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_application_already_retracted_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				if(!empty($awarded_bid_data)){
					$featured_upgrade_expired_status = 0;
					$urgent_upgrade_expired_status = 0;
					$project_status = '';
					$profile_name = $project_data['profile_name'];
					unset($project_data['profile_name']);
					unset($project_data['id']);
				
					if($project_data['project_type'] == 'fulltime'){
						$project_award_expiration_date = $awarded_bid_data['application_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['application_award_expiration_date']) : 0;
					}else{
						$project_award_expiration_date = $awarded_bid_data['project_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['project_award_expiration_date']) : 0;
					}
					if($project_award_expiration_date < time()){
					
						if($project_data['project_type'] == 'fulltime'){
						
							$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
							$award_bid_table_name = 'fulltime_projects_awarded_tracking';
							$award_project_table_name = '';
							$awards_not_completed_table_name = 'fulltime_projects_awards_not_completed_tracking';
							
							$bid_data['application_description'] = $awarded_bid_data['application_description'];
							$bid_data['application_date'] = $awarded_bid_data['application_date'];
							$bid_data['initial_requested_salary'] = $awarded_bid_data['awarded_salary'];
							$award_expiration_bid_data['application_description'] = $awarded_bid_data['application_description'];
							$award_expiration_bid_data['application_date'] = $awarded_bid_data['application_date'];
							$award_expiration_bid_data['application_awarded_date'] = $awarded_bid_data[' application_awarded_date'];
							$award_expiration_bid_data['application_award_expiration_date'] = $awarded_bid_data['application_award_expiration_date'];
							//$award_expiration_bid_data['application_award_decline_date'] = date('Y-m-d H:i:s');
							$award_expiration_bid_data['awarded_salary'] = $awarded_bid_data['awarded_salary'];
							
						}else if($project_data['project_type'] == 'fixed'){
							$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
							$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
							$award_project_table_name = 'fixed_budget_projects_awarded';
							$awards_not_completed_table_name = 'fixed_budget_projects_awards_not_completed_tracking';
							$bid_data['bid_description'] = $awarded_bid_data['bid_description'];
							$bid_data['bid_date'] = $awarded_bid_data['bid_date'];
							$bid_data['initial_bidded_amount'] = $awarded_bid_data['awarded_amount'];
							$bid_data['project_delivery_period'] = $awarded_bid_data['project_delivery_period'];
							$award_expiration_bid_data['bid_description'] = $awarded_bid_data['bid_description'];
							$award_expiration_bid_data['bid_date'] = $awarded_bid_data['bid_date'];
							$award_expiration_bid_data['project_awarded_date'] = $awarded_bid_data['project_awarded_date'];
							$award_expiration_bid_data['project_award_expiration_date'] = $awarded_bid_data['project_award_expiration_date'];
							//$award_expiration_bid_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
							$award_expiration_bid_data['awarded_amount'] = $awarded_bid_data['awarded_amount'];
							$award_expiration_bid_data['project_delivery_period'] = $awarded_bid_data['project_delivery_period'];
							
						}else if($project_data['project_type'] == 'hourly'){
							$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
							$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
							$award_project_table_name = 'hourly_rate_based_projects_awarded';
							$awards_not_completed_table_name = 'hourly_rate_based_projects_awards_not_completed_tracking';
							$bid_data['bid_description'] = $awarded_bid_data['bid_description'];
							$bid_data['bid_date'] = $awarded_bid_data['bid_date'];
							$bid_data['project_delivery_hours'] = $awarded_bid_data['awarded_hours'];
							$bid_data['initial_bidded_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
							$award_expiration_bid_data['bid_description'] = $awarded_bid_data['bid_description'];
							$award_expiration_bid_data['bid_date'] = $awarded_bid_data['bid_date'];
							$award_expiration_bid_data['project_awarded_date'] = $awarded_bid_data['project_awarded_date'];
							$award_expiration_bid_data['project_award_expiration_date'] = $awarded_bid_data['project_award_expiration_date'];
							//$award_expiration_bid_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
							$award_expiration_bid_data['awarded_hours'] = $awarded_bid_data['awarded_hours'];
							$award_expiration_bid_data['awarded_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
						}

						if($project_data['project_type'] == 'fulltime'){
							$bid_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
							$bid_data['employer_id'] = $awarded_bid_data['employer_id'];
							$bid_data['employee_id'] = $awarded_bid_data['employee_id'];
							$bid_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];

							$award_expiration_bid_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
							$award_expiration_bid_data['employer_id'] = $awarded_bid_data['employer_id'];
							$award_expiration_bid_data['employee_id'] = $awarded_bid_data['employee_id'];
							$award_expiration_bid_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];
							$award_expiration_bid_data['award_not_completed_reason'] = 'award_period_expired';
						} else {
							$bid_data['project_id'] = $awarded_bid_data['project_id'];
							$bid_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
							$bid_data['bidder_id'] = $awarded_bid_data['winner_id'];
							$bid_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];
							$award_expiration_bid_data['project_id'] = $awarded_bid_data['project_id'];
							$award_expiration_bid_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
							$award_expiration_bid_data['winner_id'] = $awarded_bid_data['winner_id'];
							$award_expiration_bid_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];
							$award_expiration_bid_data['award_not_completed_reason'] = 'award_period_expired';
						}

						if($project_data['project_type'] == 'fulltime'){
							$check_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_data['fulltime_project_id'],'employer_id'=>$awarded_bid_data['employer_id'],'employee_id'=>$awarded_bid_data['employee_id']])->from($bid_table_name)->count_all_results();
						} else {
							$check_bid_data_exists = $this->db->where(['project_id' => $awarded_bid_data['project_id'],'project_owner_id'=>$awarded_bid_data['project_owner_id'],'bidder_id'=>$awarded_bid_data['winner_id']])->from($bid_table_name)->count_all_results();
						}
					
						if($check_bid_data_exists == 0){
							$this->db->insert ($bid_table_name, $bid_data);// maintain history of bid
						}
						$this->db->insert ($awards_not_completed_table_name, $award_expiration_bid_data);// track the bid if award time is expiration
						if($project_data['project_type'] == 'fulltime'){
							$this->db->delete($award_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id]); // remove entry from award tracking table
						} else {
							$this->db->delete($award_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id]); // remove entry from award tracking table
						}
						
						$project_bid_count = $this->Bidding_model->get_project_bid_count($project_id,$project_data['project_type']); // get bid count of project
					
						$project_bid_count_history = $project_bid_count;
					
						$bidder_data = $this->Bidding_model->get_user_bid_detail($project_id,$bidder_id,$project_data['project_type'],$project_status_table_array['project_status'],$project_data['profile_name']);
						
						$remove_id = $awarded_bid_data['id'];
						
						if($project_data['project_type'] == 'fulltime'){
							
							
							$error_message = $this->config->item('project_details_page_sp_view_validation_fulltime_project_accept_award_award_acceptance_deadline_already_expired_message');
						} else {
							$error_message = $this->config->item('project_details_page_sp_view_validation_project_accept_award_award_acceptance_deadline_already_expired_message');
						}
						############## Add code for move project from award to open for bidding/expired table start#########
						if($project_data['project_type'] == 'fulltime'){
							$check_award_project_bid_counts =  $this->db->where(['fulltime_project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
						} else {
							$check_award_project_bid_counts =  $this->db->where(['project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
						}
						$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
						//$project_status = $this->config->item('project_status_awarded');
						if($project_expiration_date >= time()){
							//$project_status = $this->config->item('project_status_open_for_bidding');
							$project_bidding_expired_table = 'projects_open_bidding';
						} else {
							//$project_status = $this->config->item('project_status_expired');
							if($project_data['project_type'] == 'fixed'){
								$project_bidding_expired_table = 'fixed_budget_projects_expired';
							}
							if($project_data['project_type'] == 'hourly'){
								$project_bidding_expired_table = 'hourly_rate_based_projects_expired';
							}
							if($project_data['project_type'] == 'fulltime'){
								$project_bidding_expired_table = 'fulltime_projects_expired';
							}
						}
						
						
						if(($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $check_award_project_bid_counts == 0 && !empty($awarded_bid_data)) || ($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding' && $project_expiration_date <= time() && !empty($awarded_bid_data))) {
						//if($check_award_project_bid_counts == 0 && ($project_status_table_array['project_status'] == 'awarded' || ( $project_data['project_type'] == 'fulltime' && $project_expiration_date <= time() && $project_status_table_array['project_status'] == 'open_for_bidding' ))){
							
							if($project_expiration_date > time()){
								$project_status = $this->config->item('project_status_open_for_bidding');
							}else{
								$project_status = $this->config->item('project_status_expired');
							}
							
							
							
							$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]); 
							if($project_data['project_type'] == 'fulltime'){ 
								$project_bidding_expired_table_data_exists = $this->db->where(['fulltime_project_id' => $bid_data['project_id']])->from($project_bidding_expired_table)->count_all_results();
							} else {
								$project_bidding_expired_table_data_exists = $this->db->where(['project_id' => $bid_data['project_id']])->from($project_bidding_expired_table)->count_all_results();
							}
							if($project_bidding_expired_table_data_exists == 0){
							
							
								$expired_data = [
									'project_id' => $project_data['project_id'],
									'project_owner_id' => $project_data['project_owner_id'],
									'project_posting_date' => $project_data['project_posting_date'],
									'project_expiration_date' => $project_data['project_expiration_date'],
									'project_title' => $project_data['project_title'],
									'project_description' => $project_data['project_description'],
									'locality_id' => $project_data['locality_id'],
									'county_id' => $project_data['county_id'],
									'postal_code_id' => $project_data['postal_code_id'],
									'min_budget' => $project_data['min_budget'],
									'max_budget' => $project_data['max_budget'],
									'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
									'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
									'escrow_payment_method' => $project_data['escrow_payment_method'],
									'offline_payment_method' => $project_data['offline_payment_method'],
									'sealed' => $project_data['sealed'],
									'hidden' => $project_data['hidden'],
									'views' => $project_data['views'],
									'revisions' => $project_data['revisions']
								];
								if($project_bidding_expired_table == 'fulltime_projects_expired'){
									$expired_data['fulltime_project_id'] = $project_data['project_id'];
									$expired_data['employer_id'] = $project_data['project_owner_id'];
									$expired_data['fulltime_project_posting_date'] = $project_data['project_posting_date'];
									$expired_data['fulltime_project_expiration_date'] = $project_data['project_expiration_date'];
									$expired_data['fulltime_project_title'] = $project_data['project_title'];
									$expired_data['fulltime_project_description'] = $project_data['project_description'];
									$expired_data['min_salary'] = $project_data['min_budget'];
									$expired_data['max_salary'] = $project_data['max_budget'];

									unset($expired_data['project_id']);
									unset($expired_data['project_owner_id']);
									unset($expired_data['project_posting_date']);
									unset($expired_data['project_expiration_date']);
									unset($expired_data['project_title']);
									unset($expired_data['project_description']);
									unset($expired_data['min_budget']);
									unset($expired_data['max_budget']);
								
								}
								
								$this->db->insert ($project_bidding_expired_table, $expired_data);// insert into main project(expired/open for bidding) table name
								
								$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								$config['ftp_username'] = FTP_USERNAME;
								$config['ftp_password'] = FTP_PASSWORD;
								$config['ftp_port'] 	= FTP_PORT;
								$config['debug']    = TRUE;
								$this->load->library('ftp');
								$this->ftp->connect($config); 
								$users_ftp_dir 	= USERS_FTP_DIR; 
								$projects_ftp_dir = PROJECTS_FTP_DIR;
								$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
								$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
								$project_expired_dir = PROJECT_EXPIRED_DIR;
								$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
								$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
								$project_awarded_dir = PROJECT_AWARDED_DIR;
								$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
								$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
									
								if($project_expiration_date <= time()){	
									
									$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir, 0777);// create awaiting_moderation directory in projects folder
									$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'] , 0777); // create the directory by using  project id
									$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
								}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
									
									$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create awaiting_moderation directory in projects folder
									$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'] , 0777); // create the directory by using  project id
									$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
									
									
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture);
									
									$project_cover_picture_data = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_id))->row_array();
						
									if(!empty($project_cover_picture_data) && !empty($project_cover_picture_data['project_cover_picture_name']) ){
									
									
										 $source_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
										
										
										
										if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture))){
											
											$file_size = $this->ftp->get_filesize($source_cover_picture_attachment_path);
											if($file_size != '-1')
											{
												$destination_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
												$this->ftp->move($source_cover_picture_attachment_path, $destination_cover_picture_attachment_path);
											}
										}
									}
								}
								
								
								if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
							
									$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_data['project_id'].$project_owner_attachments_dir;
									if($project_expiration_date > time()){
										$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
									}else{
										$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
									}
								
								}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
									$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir;
									
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
								}
								
								$source_list = $this->ftp->list_files($source_path);
								if(!empty($source_list)) {
									foreach($source_list as $path) {
										$arr = explode('/', $path);
										$file_size = $this->ftp->get_filesize($path);
										if($file_size != '-1') {
										
											if($project_expiration_date <= time() ){
												$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir.end($arr);
												$this->ftp->move($path, $destination_path);
												//$this->ftp->move($path, $destination_path);
											}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
												$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.end($arr);
												$this->ftp->move($path, $destination_path);
											}
										}
									}
								}
			
								$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
								if(!empty($bid_attachments)){
									foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
									
										
										if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
											$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
										}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
										
											$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
										}
										
										if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
											
											
											if($project_expiration_date <= time()){	
												
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir);
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
												
											}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
											
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir);
												
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
											}
											if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
												$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											}
											else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
												$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											}
											
											$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
											if($file_size != '-1')
											{
												if($project_expiration_date <= time()){		
													$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
												}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
													$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
												
												}
												$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
												
											}
										}
									}
								
								}
			
								
								if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
									// remov entry from open bidding table
									if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id))) {
										$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id);
									}
								}
								if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
									// remov entry from open bidding table
									if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
										$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
									}
								}

								
								
								if($project_bidding_expired_table == 'projects_open_bidding'){
									if(!empty($project_data['hidden']) && $project_data['hidden'] == 'N') {
										$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
										$check_valid_arr = array_map('getInt', $time_arr); 
										$valid_time_arr = array_filter($check_valid_arr);
										$next_refresh_date = null;
										if(!empty($valid_time_arr)) {
											$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
											$refresh_sequence_data = [
												'project_id' => $project_data['project_id'],
												'project_last_refresh_time' => null,
												'project_next_refresh_time' => date('Y-m-d H:i:s')
											];
											$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
										}
										
										$latest_project_refresh_tracking = [
											'project_id' => $project_data['project_id'],
											'project_last_refresh_time' => date('Y-m-d H:i:s'),
											'project_next_refresh_time' => $next_refresh_date
										];
										$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);
									}
									if(!empty($project_data['sealed']) && $project_data['sealed'] == 'N') {

										$bonus_based_purchased_upgrades_tracking_data = $this->db // get the user detail
											->select('id,bonus_based_project_upgrade_purchase_reference_id')
											->from('proj_bonus_based_upgrades_purchase_tracking')
											->where('project_id', $project_data['project_id'])
											->where('project_upgrade_type', 'urgent')
											->get()->row_array();
											
										$real_money_upgrades_purchase_tracking_data = $this->db // get the user detail
										->select('id,real_money_project_upgrade_purchase_reference_id')
										->from('proj_real_money_upgrades_purchase_tracking')
										->where('project_id', $project_data['project_id'])
										->where('project_upgrade_type', 'sealed')
										->get()->row_array();	
										$purchase_tracking_id = '';
										$purchase_tracking_table_source = '';			
										if(!empty($bonus_based_purchased_upgrades_tracking_data) && !empty($real_money_upgrades_purchase_tracking_data)){
											$purchase_tracking_id = $real_money_upgrades_purchase_tracking_data['real_money_project_upgrade_purchase_reference_id'];
											$purchase_tracking_table_source = 'real_money';
										
										} else if(!empty($bonus_based_purchased_upgrades_tracking_data) && empty(	$real_money_upgrades_purchase_tracking_data)){
											$purchase_tracking_id = $bonus_based_purchased_upgrades_tracking_data['bonus_based_project_upgrade_purchase_reference_id'];
											$purchase_tracking_table_source = 'real_money';
										
										} else if(empty($bonus_based_purchased_upgrades_tracking_data) && !empty(	$real_money_upgrades_purchase_tracking_data)){
											$purchase_tracking_id = $real_money_upgrades_purchase_tracking_data['real_money_project_upgrade_purchase_reference_id'];
											$purchase_tracking_table_source = 'bonus_money';
										
										}

										if(!empty($purchase_tracking_id) && !empty($purchase_tracking_table_source)){			
											$refresh_sequence_data = [
												'project_id' => $project_data['project_id'],
												'project_upgrade_purchase_reference_id' => $purchase_tracking_id,
												'project_upgrade_purchase_tracking_table_source' => $purchase_tracking_table_source,
												'project_last_refresh_time' => null,
												'project_next_refresh_time' => date('Y-m-d H:i:s')
											];
											$this->db->insert ('sealed_projects_refresh_sequence_tracking', $refresh_sequence_data);
										}
									}
								}
								
							}
						}
						
						$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
						if($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'awarded'){
							
							$this->db->select('pd.project_id,pd.project_expiration_date,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
								
							$this->db->from($project_status_table_array['table_name']." as pd");
							$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
							
							$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->where('pd.project_id',$project_id);
							
							$project_result = $this->db->get();
							$project_info = $project_result->row_array();
							
							$update_upgrade = array();
							if(!empty($project_info)){
								$featured_max = 0;
								$urgent_max = 0;
								$project_expiration_date = $project_info['project_expiration_date']!= NULL ? strtotime ($project_info['project_expiration_date']) : 0;
								if(!empty($project_info['featured_upgrade_end_date'])){
									$expiration_featured_upgrade_date_array[] = $project_info['featured_upgrade_end_date'];
								}
								if(!empty($project_info['bonus_featured_upgrade_end_date'])){
									$expiration_featured_upgrade_date_array[] = $project_info['bonus_featured_upgrade_end_date'];
								}
								if(!empty($project_info['membership_include_featured_upgrade_end_date'])){
									$expiration_featured_upgrade_date_array[] = $project_info['membership_include_featured_upgrade_end_date'];
								}
								if(!empty($expiration_featured_upgrade_date_array)){
									$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
								}
								if($project_expiration_date >= time()  && $featured_max >= time()){
									$update_upgrade['featured'] = 'Y';
									$featured_upgrade_expired_status = 1;
						
								}else{
									$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($profile_name,$project_id);
								}
								
								if(!empty($project_info['urgent_upgrade_end_date'])){
									$expiration_urgent_upgrade_date_array[] = $project_info['urgent_upgrade_end_date'];
								}
								if(!empty($project_info['bonus_urgent_upgrade_end_date'])){
									$expiration_urgent_upgrade_date_array[] = $project_info['bonus_urgent_upgrade_end_date'];
								}
								if(!empty($project_info['membership_include_urgent_upgrade_end_date'])){
									$expiration_urgent_upgrade_date_array[] = $project_info['membership_include_urgent_upgrade_end_date'];
								}
								if(!empty($expiration_urgent_upgrade_date_array)){
									$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
								}
								if($project_expiration_date >= time()  && $urgent_max >= time()){
									$update_upgrade['urgent'] = 'Y';
									
									$urgent_upgrade_expired_status = 1;
								}
							}
							if(!empty($update_upgrade)){
								$this->db->update('projects_open_bidding',$update_upgrade, array("project_id" => $project_id));
							}
							
						}
						$is_project_expired = '0';
						if($project_expiration_date < time()){
							$is_project_expired = '1';
						
						}
						################################## End ###################
						
						echo json_encode(['status' => 'award_expired','is_project_expired'=>$is_project_expired,'featured_upgrade_expired_status'=>$featured_upgrade_expired_status,'urgent_upgrade_expired_status'=>$urgent_upgrade_expired_status,'project_status'=>$project_status,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','popup_heading'=>$this->config->item('popup_alert_heading'),'error'=>$error_message,'remove_id'=>$remove_id,'project_bid_count_history'=>$project_bid_count_history,'data'=>$this->load->view('project_bidders_listing',array('project_data'=>$project_data,'bidder_data'=>$bidder_data), true)]);
						die;
					}
					if($project_data['project_type'] == 'fixed') {
					
						
						$completion_threshold_disclaimer_msg_sp_view = $this->config->item('user_confirmation_check_box_txt');
						$completion_threshold_fixed_project_value = number_format($awarded_bid_data['initial_project_agreed_value'], 0, '', ' ')." ". CURRENCY;
					
						$completion_threshold_disclaimer_msg_sp_view = str_replace(array('{fixed_budget_project_completion_threshold}'),array($completion_threshold_fixed_project_value),$completion_threshold_disclaimer_msg_sp_view);
					
					} else if($project_data['project_type'] == 'hourly') {
						$completion_threshold_disclaimer_msg_sp_view = $this->config->item('user_confirmation_check_box_txt');
					} 
					$confirmation_modal_title = '';
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_title = $this->config->item('accept_award_confirmation_fulltime_project_modal_title_employee_view');
						$confirmation_modal_body = $this->config->item('accept_award_confirmation_fulltime_project_modal_body_employee_view');
						$completion_threshold_disclaimer_msg_sp_view = $this->config->item('user_confirmation_check_box_txt');
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body.'</div>';
						$confirmation_modal_body .= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="accept_bid_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$completion_threshold_disclaimer_msg_sp_view.'</span></label></div></div></div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" class="btn accept_bid_sp width-auto blue_btn default_btn" disabled style="opacity:0.65" data-attr="'.Cryptor::doEncrypt($awarded_bid_data['employee_id']).'">'.$this->config->item('accept_btn_txt').'</button>';
					} else {
						$confirmation_modal_body = $this->config->item('accept_award_confirmation_project_modal_body_sp_view');
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body.'</div>';
						$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="accept_bid_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$completion_threshold_disclaimer_msg_sp_view.'</span></label></div></div></div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn accept_bid_sp width-auto" disabled style="opacity:0.65" data-attr="'.Cryptor::doEncrypt($awarded_bid_data['winner_id']).'">'.$this->config->item('accept_btn_txt').'</button>';
					}
					
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				}
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to accept the award by service provider .
	*/
	public function accept_award(){
	
		if($this->input->is_ajax_request ()){
		
		
			if(empty($this->input->post ('project_id'))) {
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			
			if(check_session_validity()){ 
				$bidder_id = Cryptor::doDecrypt($this->input->post ('id'));
				$user = $this->session->userdata ('user');
				if($bidder_id !=  $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])) {
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_accept_award_deleted_project')]);
					die;
				}
				if($project_status_table_array['project_status'] == 'cancelled'){
					if($project_status_table_array['project_type'] == 'fulltime'){
					$error_message = $this->config->item('project_details_page_sp_view_accept_award_cancelled_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_cancelled_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
					$project_data['project_expiration_date'] = $project_data['fulltime_project_expiration_date'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				
				$project_owner_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y'))? $project_data['first_name'] . ' ' . $project_data['last_name'] : $project_data['company_name'];
				
				/* $po_detail['account_type'] = $project_data['account_type'];
				$po_detail['is_authorized_physical_person'] = $project_data['is_authorized_physical_person'];
				$po_detail['first_name'] = $project_data['first_name'];
				$po_detail['last_name'] = $project_data['last_name'];
				$po_detail['company_name'] = $project_data['company_name'];
				$po_detail['profile_name'] = $project_data['profile_name']; */
				
				
				
				$bidder_data = $this->db // get the user detail
				->select('u.profile_name')
				->from('users u')
				->where('u.user_id', $bidder_id)
				->get()->row_array();
				$progress_bid_data = array();
				
				if($project_data['project_type'] == 'fixed'){
					
					$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$award_project_table_name = 'fixed_budget_projects_awarded';
					$active_bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$progress_project_table_name = 'fixed_budget_projects_progress';
					$expired_project_table_name = 'fixed_budget_projects_expired';
					$awards_not_completed_table_name = 'fixed_budget_projects_awards_not_completed_tracking';
					
					$completed_project_table_name = 'fixed_budget_projects_completed';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						
						if($user[0]->gender == 'M'){
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_accept_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_sp_company_app_male_accepted_awarded_bid_user_activity_log_displayed_message');
							}else{
								$po_accept_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_sp_male_accepted_awarded_bid_user_activity_log_displayed_message');
							}
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_accept_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_sp_company_app_female_accepted_awarded_bid_user_activity_log_displayed_message');
							}else{
								$po_accept_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_sp_female_accepted_awarded_bid_user_activity_log_displayed_message');
							}
						}
					}else{
					
						$po_accept_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_awarded_sp_company_accepted_awarded_bid_user_activity_log_displayed_message');
					}
					$sp_accept_bid_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_accepted_awarded_bid_user_activity_log_displayed_message'); 
					
				} else if($project_data['project_type'] == 'hourly'){
					
					$active_bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$award_project_table_name = 'hourly_rate_based_projects_awarded';
					
					$progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$progress_project_table_name = 'hourly_rate_based_projects_progress';
					$expired_project_table_name = 'hourly_rate_based_projects_expired';
					$awards_not_completed_table_name = 'hourly_rate_based_projects_awards_not_completed_tracking';

					$completed_project_table_name = 'hourly_rate_based_projects_completed';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						if($user[0]->gender == 'M'){
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_accept_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_sp_company_app_male_accepted_awarded_bid_user_activity_log_displayed_message');
							}else{
								$po_accept_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_sp_male_accepted_awarded_bid_user_activity_log_displayed_message');
							}
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_accept_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_sp_company_app_female_accepted_awarded_bid_user_activity_log_displayed_message');
							}else{
								$po_accept_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_sp_female_accepted_awarded_bid_user_activity_log_displayed_message');
							}
						}
					} else {
						$po_accept_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_awarded_sp_company_accepted_awarded_bid_user_activity_log_displayed_message');
					}
					
					$sp_accept_bid_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_accepted_awarded_bid_user_activity_log_displayed_message');
					 
						
				} else if($project_data['project_type'] == 'fulltime'){
					
					$active_bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$award_bid_table_name = 'fulltime_projects_awarded_tracking';
					$award_project_table_name = '';
					$expired_project_table_name = 'fulltime_projects_expired';
					$progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					
					$awards_not_completed_table_name = 'fulltime_projects_awards_not_completed_tracking';

					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						if($user[0]->gender == 'M'){
						
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_accept_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_employee_company_app_male_accepted_awarded_application_user_activity_log_displayed_message');
							}else{
						
								$po_accept_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_employee_male_accepted_awarded_application_user_activity_log_displayed_message');
							}
						}else{
							if($user[0]->is_authorized_physical_person == 'Y'){
								$po_accept_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_employee_company_app_female_accepted_awarded_application_user_activity_log_displayed_message');
							}else{
								$po_accept_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_employee_female_accepted_awarded_application_user_activity_log_displayed_message');
							}
						}
					} else {
						$po_accept_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_awarded_employee_company_accepted_awarded_application_user_activity_log_displayed_message');
					}
					
					$sp_accept_bid_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_accepted_awarded_application_user_activity_log_displayed_message'); 

				}
				
				if($project_data['project_type'] == 'fulltime') { 
					$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($progress_bid_table_name)->count_all_results();
				} else {
					$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($progress_bid_table_name)->count_all_results();
				}
				if($check_already_in_progress_bid > 0){
					
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_in_progress_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_in_progress_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if(!empty($completed_bid_table_name)) {
					$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$bidder_id])->from($completed_bid_table_name)->count_all_results();
				}
				if($project_data['project_type'] != 'fulltime' && !empty($check_already_completed_bid) && $check_already_completed_bid > 0){
					
					$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_accept_award_on_completed_project');
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}

				if($project_data['project_type'] == 'fulltime') { 
					$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->from($active_bid_table_name)->count_all_results();
				} else {
					$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$bidder_id])->from($active_bid_table_name)->count_all_results();
				}
				if($check_already_active_bid > 0){
					
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_award_already_declined_or_expired_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_award_already_declined_or_expired_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				if($project_data['project_type'] == 'fulltime') { 
					$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id])->row_array();
				} else {
					$awarded_bid_data = $this->db->get_where($award_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id])->row_array();
				}
				
				if($check_already_in_progress_bid == 0 && $check_already_active_bid == 0 && empty($awarded_bid_data)){
						
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_application_already_retracted_fulltime_project');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_accept_award_already_retracted_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				
				}
				$remove_id = $awarded_bid_data['id'];
				$featured_upgrade_expired_status = 0;
				$urgent_upgrade_expired_status = 0;
				$project_status = '';
				$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;

				if($project_data['project_type'] == 'fulltime'){
					$project_award_expiration_date = $awarded_bid_data['application_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['application_award_expiration_date']) : 0;
					}else{
					$project_award_expiration_date = $awarded_bid_data['project_award_expiration_date']!= NULL ? strtotime ($awarded_bid_data['project_award_expiration_date']) : 0;
				}
				$profile_name = $project_data['profile_name'];
				unset($project_data['profile_name']);
				unset($project_data['id']);
				unset($project_data['account_type']);
				unset($project_data['is_authorized_physical_person']);
				unset($project_data['first_name']);
				unset($project_data['last_name']);
				unset($project_data['company_name']);
				
				if($project_award_expiration_date < time()){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_validation_fulltime_project_accept_award_award_acceptance_deadline_already_expired_message');
					} else {
						$error_message = $this->config->item('project_details_page_sp_view_validation_project_accept_award_award_acceptance_deadline_already_expired_message');
					
					}
					
					if($project_data['project_type'] == 'fulltime') { 
						$bid_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
						$bid_data['employer_id'] = $awarded_bid_data['employer_id'];
						$bid_data['employee_id'] = $awarded_bid_data['employee_id'];
						$bid_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];
						
						$award_expiration_bid_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
						$award_expiration_bid_data['employer_id'] = $awarded_bid_data['employer_id'];
						$award_expiration_bid_data['employee_id'] = $awarded_bid_data['employee_id'];
						$award_expiration_bid_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];
						$award_expiration_bid_data['award_not_completed_reason'] = 'award_period_expired';
					} else {
						$bid_data['project_id'] = $awarded_bid_data['project_id'];
						$bid_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
						$bid_data['bidder_id'] = $awarded_bid_data['winner_id'];
						$bid_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];
						
						$award_expiration_bid_data['project_id'] = $awarded_bid_data['project_id'];
						$award_expiration_bid_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
						$award_expiration_bid_data['winner_id'] = $awarded_bid_data['winner_id'];
						$award_expiration_bid_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];
						$award_expiration_bid_data['award_not_completed_reason'] = 'award_period_expired';
					}

					
					
					if($project_data['project_type'] == 'fixed'){
						$bid_data['bid_description'] = $awarded_bid_data['bid_description'];
						$bid_data['bid_date'] = $awarded_bid_data['bid_date'];
						$bid_data['initial_bidded_amount'] = $awarded_bid_data['awarded_amount'];
						$bid_data['project_delivery_period'] = $awarded_bid_data['project_delivery_period'];
						
						$award_expiration_bid_data['bid_description'] = $awarded_bid_data['bid_description'];
						$award_expiration_bid_data['bid_date'] = $awarded_bid_data['bid_date'];
						$award_expiration_bid_data['project_awarded_date'] = $awarded_bid_data['project_awarded_date'];
						$award_expiration_bid_data['project_award_expiration_date'] = $awarded_bid_data['project_award_expiration_date'];
						//$award_expiration_bid_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
						$award_expiration_bid_data['awarded_amount'] = $awarded_bid_data['awarded_amount'];
						$award_expiration_bid_data['project_delivery_period'] = $awarded_bid_data['project_delivery_period'];
					
					
					} else if($project_data['project_type'] == 'hourly'){
						$bid_data['bid_description'] = $awarded_bid_data['bid_description'];
						$bid_data['bid_date'] = $awarded_bid_data['bid_date'];
						$bid_data['project_delivery_hours'] = $awarded_bid_data['awarded_hours'];
						$bid_data['initial_bidded_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
						
						$award_expiration_bid_data['bid_description'] = $awarded_bid_data['bid_description'];
						$award_expiration_bid_data['bid_date'] = $awarded_bid_data['bid_date'];
						$award_expiration_bid_data['project_awarded_date'] = $awarded_bid_data['project_awarded_date'];
						$award_expiration_bid_data['project_award_expiration_date'] = $awarded_bid_data['project_award_expiration_date'];
						//$award_expiration_bid_data['bid_award_decline_date'] = date('Y-m-d H:i:s');
						$award_expiration_bid_data['awarded_hours'] = $awarded_bid_data['awarded_hours'];
						$award_expiration_bid_data['awarded_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
						
					} else if($project_data['project_type'] == 'fulltime'){
						$bid_data['application_description'] = $awarded_bid_data['application_description'];
						$bid_data['application_date'] = $awarded_bid_data['application_date'];
						$bid_data['initial_requested_salary'] = $awarded_bid_data['awarded_salary'];
						$award_expiration_bid_data['application_description'] = $awarded_bid_data['application_description'];
						$award_expiration_bid_data['application_date'] = $awarded_bid_data['application_date'];
						$award_expiration_bid_data['application_awarded_date'] = $awarded_bid_data[' application_awarded_date'];
						$award_expiration_bid_data['application_award_expiration_date'] = $awarded_bid_data['application_award_expiration_date'];
						//$award_expiration_bid_data['application_award_decline_date'] = date('Y-m-d H:i:s');
						$award_expiration_bid_data['awarded_salary'] = $awarded_bid_data['awarded_salary'];
					}
					if($project_data['project_type'] == 'fulltime') { 
						$check_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_data['fulltime_project_id'],'employer_id'=>$awarded_bid_data['employer_id'],'employee_id'=>$awarded_bid_data['employee_id']])->from($active_bid_table_name)->count_all_results();
					} else {
						$check_bid_data_exists = $this->db->where(['project_id' => $awarded_bid_data['project_id'],'project_owner_id'=>$awarded_bid_data['project_owner_id'],'bidder_id'=>$awarded_bid_data['winner_id']])->from($active_bid_table_name)->count_all_results();
					}
					
					if($check_bid_data_exists == 0){
						$this->db->insert ($active_bid_table_name, $bid_data);// maintain history of bid
					}
					$this->db->insert ($awards_not_completed_table_name, $award_expiration_bid_data);// track the bid if award time is expiration
					if($project_data['project_type'] == 'fulltime') { 
						$this->db->delete($award_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$bidder_id]); // remove entry from award tracking table
					} else {
						$this->db->delete($award_bid_table_name, ['project_id' => $project_id,'winner_id'=>$bidder_id]); // remove entry from award tracking table
					}
					
					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_id,$project_data['project_type']); // get bid count of project
						
					$project_bid_count_history = $project_bid_count;
					
					$bidder_data = $this->Bidding_model->get_user_bid_detail($project_id,$bidder_id,$project_data['project_type'],$project_status_table_array['project_status'],$profile_name);
					
					############## Add code for move project from award to open for bidding/expired table start#########
					if($project_data['project_type'] == 'fulltime') { 
						$check_award_project_bid_counts =  $this->db->where(['fulltime_project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
					} else {
						$check_award_project_bid_counts =  $this->db->where(['project_id' => $project_id])->from($award_bid_table_name)->count_all_results();
					}

					
					//$project_status = $this->config->item('project_status_awarded');
					if($project_expiration_date >= time()){
						//$project_status = $this->config->item('project_status_open_for_bidding');
						$project_bidding_expired_table = 'projects_open_bidding';
					}else{
						//$project_status = $this->config->item('project_status_expired');
						if($project_data['project_type'] == 'fixed'){
							$project_bidding_expired_table = 'fixed_budget_projects_expired';
						}
						if($project_data['project_type'] == 'hourly'){
							$project_bidding_expired_table = 'hourly_rate_based_projects_expired';
						}
						if($project_data['project_type'] == 'fulltime'){
							$project_bidding_expired_table = 'fulltime_projects_expired';
						}
					}
					//if($check_award_project_bid_counts == 0 && ($project_status_table_array['project_status'] == 'awarded' || ( $project_data['project_type'] == 'fulltime' && $project_expiration_date <= time() && $project_status_table_array['project_status'] == 'open_for_bidding' ))){
					if(($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $check_award_project_bid_counts == 0 && !empty($awarded_bid_data)) || ($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding' && $project_expiration_date <= time() && !empty($awarded_bid_data))) {
						
						
						if($project_expiration_date > time()){
							$project_status = $this->config->item('project_status_open_for_bidding');
						}else{
							$project_status = $this->config->item('project_status_expired');
						}
						
						$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]); 
						if($project_data['project_type'] == 'fulltime'){ 
							$project_bidding_expired_table_data_exists = $this->db->where(['fulltime_project_id' => $bid_data['project_id']])->from($project_bidding_expired_table)->count_all_results();
						} else {
							$project_bidding_expired_table_data_exists = $this->db->where(['project_id' => $bid_data['project_id']])->from($project_bidding_expired_table)->count_all_results();
						}
						if($project_bidding_expired_table_data_exists == 0){
							
							
							$expired_data = [
								'project_id' => $project_data['project_id'],
								'project_owner_id' => $project_data['project_owner_id'],
								'project_posting_date' => $project_data['project_posting_date'],
								'project_expiration_date' => $project_data['project_expiration_date'],
								'project_title' => $project_data['project_title'],
								'project_description' => $project_data['project_description'],
								'locality_id' => $project_data['locality_id'],
								'county_id' => $project_data['county_id'],
								'postal_code_id' => $project_data['postal_code_id'],
								'min_budget' => $project_data['min_budget'],
								'max_budget' => $project_data['max_budget'],
								'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
								'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
								'escrow_payment_method' => $project_data['escrow_payment_method'],
								'offline_payment_method' => $project_data['offline_payment_method'],
								'sealed' => $project_data['sealed'],
								'hidden' => $project_data['hidden'],
								'views' => $project_data['views'],
								'revisions' => $project_data['revisions']
							];
							if($project_bidding_expired_table == 'fulltime_projects_expired'){
								$expired_data['fulltime_project_id'] = $project_data['project_id'];
								$expired_data['employer_id'] = $project_data['project_owner_id'];
								$expired_data['fulltime_project_posting_date'] = $project_data['project_posting_date'];
								$expired_data['fulltime_project_expiration_date'] = $project_data['project_expiration_date'];
								$expired_data['fulltime_project_title'] = $project_data['project_title'];
								$expired_data['fulltime_project_description'] = $project_data['project_description'];
								$expired_data['min_salary'] = $project_data['min_budget'];
								$expired_data['max_salary'] = $project_data['max_budget'];

								unset($expired_data['project_id']);
								unset($expired_data['project_owner_id']);
								unset($expired_data['project_posting_date']);
								unset($expired_data['project_expiration_date']);
								unset($expired_data['project_title']);
								unset($expired_data['project_description']);
								unset($expired_data['min_budget']);
								unset($expired_data['max_budget']);
							
							}
							
							
							$this->db->insert ($project_bidding_expired_table, $expired_data);// insert into main project(expired/open for bidding) table name
							
							
							
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->load->library('ftp');
							$this->ftp->connect($config); 
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
							$project_expired_dir = PROJECT_EXPIRED_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
							$project_awarded_dir = PROJECT_AWARDED_DIR;
							$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
							
							
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
							
							
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
							
							//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
								
							if($project_expiration_date <= time() ){	
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$project_owner_attachments_dir);
							}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir);
								
								if(!empty($project_cover_picture_data) && !empty($project_cover_picture_data['project_cover_picture_name']) ){
									
									
									 $source_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
									
									
									
									if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture))){
										
										$file_size = $this->ftp->get_filesize($source_cover_picture_attachment_path);
										if($file_size != '-1')
										{
											$destination_cover_picture_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture.$project_cover_picture_data['project_cover_picture_name'];
											$this->ftp->move($source_cover_picture_attachment_path, $destination_cover_picture_attachment_path);
										}
									}
								}
								
								
							}
							if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
							
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_data['project_id'].$project_owner_attachments_dir;
								if($project_expiration_date > time()){
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
								}else{
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
								}
								
							}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$project_owner_attachments_dir;
								
								$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].DIRECTORY_SEPARATOR;
							}
							
							$source_list = $this->ftp->list_files($source_path);
							if(!empty($source_list)) {
								foreach($source_list as $path) {
									$arr = explode('/', $path);
									$file_size = $this->ftp->get_filesize($path);
									if($file_size != '-1') {
									
										if($project_expiration_date <= time() ){
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir.end($arr);
											$this->ftp->move($path, $destination_path);
											//$this->ftp->move($path, $destination_path);
										}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded' && $project_expiration_date > time()){
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.end($arr);
											$this->ftp->move($path, $destination_path);
										}
									}
								}
							}
		
							$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
							if(!empty($bid_attachments)){
								foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
								
									
									if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
										$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
									}else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
									
										$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
									}
									
									if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
										
										
										if($project_expiration_date <= time()){	
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
											
										}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
										
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir);
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_data['project_id'].$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
										}
										if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
											$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										else if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
											$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										
										$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
										if($file_size != '-1')
										{
											if($project_expiration_date <= time()){		
												$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											}else if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
												$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											
											}
											$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
											
										}
									}
								}
							
							}
		
							
							if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
								// remov entry from open bidding table
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id))) {
									$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id);
								}
							}
							if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding'){
								// remov entry from open bidding table
								if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
									$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
								}
							}

							if($project_bidding_expired_table == 'projects_open_bidding'){
								if(!empty($project_data['hidden']) && $project_data['hidden'] == 'N') {
									$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
									$check_valid_arr = array_map('getInt', $time_arr); 
									$valid_time_arr = array_filter($check_valid_arr);
									$next_refresh_date = null;
									if(!empty($valid_time_arr)) {
										$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
										$refresh_sequence_data = [
											'project_id' => $project_data['project_id'],
											'project_last_refresh_time' => null,
											'project_next_refresh_time' => date('Y-m-d H:i:s')
										];
										$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
									}
									
									$latest_project_refresh_tracking = [
										'project_id' => $project_data['project_id'],
										'project_last_refresh_time' => date('Y-m-d H:i:s'),
										'project_next_refresh_time' => $next_refresh_date
									];
									$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);
								}
								if(!empty($project_data['sealed']) && $project_data['sealed'] == 'N') {

									$bonus_based_purchased_upgrades_tracking_data = $this->db // get the user detail
										->select('id,bonus_based_project_upgrade_purchase_reference_id')
										->from('proj_bonus_based_upgrades_purchase_tracking')
										->where('project_id', $project_data['project_id'])
										->where('project_upgrade_type', 'urgent')
										->get()->row_array();
										
									$real_money_upgrades_purchase_tracking_data = $this->db // get the user detail
									->select('id,real_money_project_upgrade_purchase_reference_id')
									->from('proj_real_money_upgrades_purchase_tracking')
									->where('project_id', $project_data['project_id'])
									->where('project_upgrade_type', 'sealed')
									->get()->row_array();	
									$purchase_tracking_id = '';
									$purchase_tracking_table_source = '';			
									if(!empty($bonus_based_purchased_upgrades_tracking_data) && !empty($real_money_upgrades_purchase_tracking_data)){
										$purchase_tracking_id = $real_money_upgrades_purchase_tracking_data['real_money_project_upgrade_purchase_reference_id'];
										$purchase_tracking_table_source = 'real_money';
									
									} else if(!empty($bonus_based_purchased_upgrades_tracking_data) && empty(	$real_money_upgrades_purchase_tracking_data)){
										$purchase_tracking_id = $bonus_based_purchased_upgrades_tracking_data['bonus_based_project_upgrade_purchase_reference_id'];
										$purchase_tracking_table_source = 'real_money';
									
									} else if(empty($bonus_based_purchased_upgrades_tracking_data) && !empty(	$real_money_upgrades_purchase_tracking_data)){
										$purchase_tracking_id = $real_money_upgrades_purchase_tracking_data['real_money_project_upgrade_purchase_reference_id'];
										$purchase_tracking_table_source = 'bonus_money';
									
									}

									if(!empty($purchase_tracking_id) && !empty($purchase_tracking_table_source)){			
										$refresh_sequence_data = [
											'project_id' => $project_data['project_id'],
											'project_upgrade_purchase_reference_id' => $purchase_tracking_id,
											'project_upgrade_purchase_tracking_table_source' => $purchase_tracking_table_source,
											'project_last_refresh_time' => null,
											'project_next_refresh_time' => date('Y-m-d H:i:s')
										];
										$this->db->insert ('sealed_projects_refresh_sequence_tracking', $refresh_sequence_data);
									}
								}
							}
						}
					}
					
					$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
					if($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'awarded'){
						
						$this->db->select('pd.project_id,pd.project_expiration_date,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
							
						$this->db->from($project_status_table_array['table_name']." as pd");
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->where('pd.project_id',$project_id);
						
						$project_result = $this->db->get();
						$project_info = $project_result->row_array();
						
						$update_upgrade = array();
						if(!empty($project_info)){
							$featured_max = 0;
							$urgent_max = 0;
							$project_expiration_date = $project_info['project_expiration_date']!= NULL ? strtotime ($project_info['project_expiration_date']) : 0;
							if(!empty($project_info['featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['featured_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['bonus_featured_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['membership_include_featured_upgrade_end_date'];
							}
							if(!empty($expiration_featured_upgrade_date_array)){
								$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $featured_max >= time()){
								$update_upgrade['featured'] = 'Y';
								$featured_upgrade_expired_status = 1;
					
							}else{
								$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($profile_name,$project_id);
							}
							
							if(!empty($project_info['urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['urgent_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['bonus_urgent_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['membership_include_urgent_upgrade_end_date'];
							}
							if(!empty($expiration_urgent_upgrade_date_array)){
								$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $urgent_max >= time()){
								$update_upgrade['urgent'] = 'Y';
								
								$urgent_upgrade_expired_status = 1;
							}
						}
						if(!empty($update_upgrade)){
							$this->db->update('projects_open_bidding',$update_upgrade, array("project_id" => $project_id));
						}
						
					}
					
					$is_project_expired = '0';
					if($project_expiration_date < time()){
						$is_project_expired = '1';
					
					}
					################################## End ###################
					
					echo json_encode(['status' => 'award_expired','is_project_expired'=>$is_project_expired,'featured_upgrade_expired_status'=>$featured_upgrade_expired_status,'urgent_upgrade_expired_status'=>$urgent_upgrade_expired_status,'project_status'=>$project_status,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message,'remove_id'=>$bidder_id,'project_bid_count_history'=>$project_bid_count_history,'data'=>$this->load->view('project_bidders_listing',array('project_data'=>$project_data,'bidder_data'=>$bidder_data), true)]);
					die;
				}
				$project_start_date = date('Y-m-d H:i:s');
				$po_user_detail = $this->db->get_where('users_details', ['user_id' => $project_data['project_owner_id']])->row_array();
				if($project_data['project_type'] == 'fulltime'){ 
					$progress_bid_data['fulltime_project_id'] = $awarded_bid_data['fulltime_project_id'];
					$progress_bid_data['employer_id'] = $awarded_bid_data['employer_id'];
					$progress_bid_data['employee_id'] = $awarded_bid_data['employee_id'];
					$progress_bid_data['application_bidding_dropdown_option'] = $awarded_bid_data['application_bidding_dropdown_option'];
					$progress_bid_data['employee_hired_on'] = $project_start_date;
					$progress_bid_data['employer_membership_plan_id'] = $po_user_detail['current_membership_plan_id'];
					// $progress_bid_data['fulltime_project_start_date'] = $project_start_date;
				} else {
					$progress_bid_data['project_id'] = $awarded_bid_data['project_id'];
					$progress_bid_data['project_owner_id'] = $awarded_bid_data['project_owner_id'];
					$progress_bid_data['winner_id'] = $awarded_bid_data['winner_id'];
					$progress_bid_data['bidding_dropdown_option'] = $awarded_bid_data['bidding_dropdown_option'];
					$progress_bid_data['project_start_date'] = $project_start_date;
				}
				
				
				if($project_data['project_type'] == 'fixed'){
					$progress_bid_data['initial_bid_value'] = $awarded_bid_data['awarded_amount'];
					$progress_bid_data['initial_project_agreed_delivery_period'] = $awarded_bid_data['project_delivery_period'];
					$progress_bid_data['initial_bid_description'] = $awarded_bid_data['bid_description'];
					$progress_bid_data['initial_project_agreed_value'] = $awarded_bid_data['initial_project_agreed_value'];
					
				} else if($project_data['project_type'] == 'hourly'){
					
					$progress_bid_data['initial_project_agreed_value'] = ($awarded_bid_data['awarded_hours']*$awarded_bid_data['awarded_hourly_rate']);
					$progress_bid_data['initial_project_agreed_number_of_hours'] = $awarded_bid_data['awarded_hours'];
					$progress_bid_data['initial_project_agreed_hourly_rate'] = $awarded_bid_data['awarded_hourly_rate'];
					$progress_bid_data['initial_bid_description'] = $awarded_bid_data['bid_description'];
					
				} else if($project_data['project_type'] == 'fulltime'){
					
					$feedback_exchange_months = 1;
					if($po_user_detail['current_membership_plan_id'] == 1) {
						$feedback_exchange_months = $this->config->item('free_membership_subscriber_fulltime_project_minimum_months_to_exchange_feedback');
					} else if ($po_user_detail['current_membership_plan_id'] == 4)  {
						$feedback_exchange_months = $this->config->item('gold_membership_subscriber_fulltime_project_minimum_months_to_exchange_feedback');
					}

					$awarded_salary = 0;
					/* if($awarded_bid_data['awarded_salary'] != 0) {
						$awarded_salary = $awarded_bid_data['awarded_salary'] * $feedback_exchange_months;
					} else { */
						$awarded_salary = $this->Bidding_model->get_initial_minimum_required_threshold_to_exchange_feedback_fulltime_project($project_id, $bidder_id, $project_data['project_owner_id'], $project_status_table_array['project_status']);/* 
					} */
					
					$progress_bid_data['initial_fulltime_project_agreed_salary'] = $awarded_bid_data['awarded_salary'];
					$progress_bid_data['initial_application_description'] = $awarded_bid_data['application_description'];
					$progress_bid_data['initial_fulltime_project_threshold_value'] = $awarded_salary;
					$progress_bid_data['salary_multiplication_value_months'] = $feedback_exchange_months;
					$progress_bid_data['feedback_exchange_availability_date'] = date('Y-m-d H:i:s', strtotime("+".$feedback_exchange_months." months", strtotime($project_start_date)));;
					 	
				}
				
				
				if(!empty($awarded_bid_data)){
				
					 ######## connectivity of remote server start #######
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);
					
					if($project_data['project_type'] == 'fulltime'){ 
						$check_progress_bid_data_exists = $this->db->where(['fulltime_project_id' => $awarded_bid_data['fulltime_project_id'],'employer_id'=>$awarded_bid_data['employer_id'],'employee_id'=>$bidder_id])->from($progress_bid_table_name)->count_all_results();
					} else {
						$check_progress_bid_data_exists = $this->db->where(['project_id' => $awarded_bid_data['project_id'],'project_owner_id'=>$awarded_bid_data['project_owner_id'],'winner_id'=>$bidder_id])->from($progress_bid_table_name)->count_all_results();
					}

					
					if($check_progress_bid_data_exists == 0){
						$this->db->insert ($progress_bid_table_name, $progress_bid_data);// maintain history of bid
						if($project_data['project_type'] == 'fulltime'){
							$this->db->delete($award_bid_table_name, ['fulltime_project_id' => $project_data['project_id'],'employee_id'=>$bidder_id]); 
						} else {
							$this->db->delete($award_bid_table_name, ['project_id' => $project_data['project_id'],'winner_id'=>$bidder_id]); 
						}
					}
					if(!empty($progress_project_table_name)) {
						$check_progress_project_data_exists = $this->db->where(['project_id' => $awarded_bid_data['project_id']])->from($progress_project_table_name)->count_all_results();
					}
					if($check_progress_project_data_exists == 0 && !empty($progress_project_table_name)){
						
					
						//unset($project_data['profile_name']);
						//unset($project_data['id']);
						
						
						if($project_status_table_array['project_status'] == 'completed') {
							unset($project_data['project_completion_date']);
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$project_data['project_start_date'] = $project_start_date;
						}
						$this->db->insert ($progress_project_table_name, $project_data);// insert into progress project table name
						
						if($project_status_table_array['project_status'] == 'awarded'){
						
							$this->db->delete($award_project_table_name, ['project_id' => $project_id]);
						}
						
						if($project_status_table_array['project_status'] == 'expired'){
							if($project_data['project_type'] == 'fulltime'){ 
								$this->db->delete($expired_project_table_name, ['fulltime_project_id' => $project_id]);
							} else {
								$this->db->delete($expired_project_table_name, ['project_id' => $project_id]);
							}
						}

						if($project_status_table_array['project_status'] == 'completed'){
							$this->db->delete($completed_project_table_name, ['project_id' => $project_id]);
						}
					
						############move project attachment/bid attachment folder from awarded to in progress folder###
					
					
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_awarded_dir = PROJECT_AWARDED_DIR;
						$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
						$project_completed_dir = PROJECT_COMPLETED_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.DIRECTORY_SEPARATOR);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir);
						
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_owner_attachments_dir;
						}
						
						if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir;
						}
						$source_list = $this->ftp->list_files($source_path);
						if(!empty($source_list)) {
							foreach($source_list as $path) {
								$arr = explode('/', $path);
								$file_size = $this->ftp->get_filesize($path);
								if($file_size != '-1') {
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir.end($arr);
									$this->ftp->move($path, $destination_path);
								}
							}
						}
						
						$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
						if(!empty($bid_attachments)){
							
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir);
							foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
							
								if($project_status_table_array['project_status'] == 'awarded'){
							
									$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
								}
								if($project_status_table_array['project_status'] == 'completed'){
							
									$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
								}
								
								
								if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
								
									//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); 
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
								
									if($project_status_table_array['project_status'] == 'awarded'){
										$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									}
									if($project_status_table_array['project_status'] == 'completed'){
										$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									}
									
									
									$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
									if($file_size != '-1')
									{
										$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
										
									}
								}
							}
						
						}
						
						// remove folder project folder from awarded folder
						if($project_status_table_array['project_status'] == 'awarded'){
						
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_awarded_dir.$project_id);
							}
						}
						// remove folder project folder from awarded folder
						if($project_status_table_array['project_status'] == 'completed'){
						
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id);
							}
						}	
						############move project attachment/bid attachment folder from awarded to in progress folder###
						############### update the refresh sequence table start ########
						
						 $this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);



						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						// remove entry from project latest refresh sequence tracking table
						$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project_data['project_id'])); 
						
						############### update the refresh sequence table end ########
						//$project_status = $this->config->item('project_status_in_progress');
					}
					
					/* if($project_data['project_type'] == 'fulltime' && $project_expiration_date > time() ){
						$project_status =$this->config->item('project_status_open_for_bidding');
					} */
					// move project from open for bidding to expired table
					if($project_data['project_type'] == 'fulltime' && $project_status_table_array['project_status'] == 'open_for_bidding' && $project_expiration_date <= time()){
					//project_expiration_date
					
						//$project_status =$this->config->item('project_status_expired');
					
						############### update the refresh sequence table start ########
						
						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);


						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);



						$this->db->limit(1);
						$this->db->order_by('id', 'desc');
						$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_data['project_id']]);
					
					
					
						$expired_data = [
							'fulltime_project_id' => $project_data['project_id'],
							'employer_id' => $project_data['project_owner_id'],
							'fulltime_project_posting_date' => $project_data['project_posting_date'],
							'fulltime_project_expiration_date' => $project_data['project_expiration_date'],
							'fulltime_project_title' => $project_data['project_title'],
							'fulltime_project_description' => $project_data['project_description'],
							'locality_id' => $project_data['locality_id'],
							'county_id' => $project_data['county_id'],
							'postal_code_id' => $project_data['postal_code_id'],
							'min_salary' => $project_data['min_budget'],
							'max_salary' => $project_data['max_budget'],
							'confidential_dropdown_option_selected' => $project_data['confidential_dropdown_option_selected'],
							'not_sure_dropdown_option_selected' => $project_data['not_sure_dropdown_option_selected'],
							'escrow_payment_method' => $project_data['escrow_payment_method'],
							'offline_payment_method' => $project_data['offline_payment_method'],
							'sealed' => $project_data['sealed'],
							'hidden' => $project_data['hidden'],
							'views' => $project_data['views'],
							'revisions' => $project_data['revisions']
						];
					
						$check_fulltime_projects_expired_exists = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_expired')->count_all_results();
					
						if($check_fulltime_projects_expired_exists == 0){
							$this->db->insert ('fulltime_projects_expired', $expired_data);// insert into main project(expired/open for bidding) table name
						}
						
						$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
						
						
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							
						$project_expired_dir = PROJECT_EXPIRED_DIR;
						$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name . DIRECTORY_SEPARATOR);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
						
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir);
						
						$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir;
						
						$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR;
						
						$source_list = $this->ftp->list_files($source_path);
						if(!empty($source_list)) {
							foreach($source_list as $path) {
								$arr = explode('/', $path);
								$file_size = $this->ftp->get_filesize($path);
								if($file_size != '-1') {
									
									$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$project_owner_attachments_dir.end($arr);
									$this->ftp->move($path, $destination_path);
								}
							}
						}
						
						$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
						if(!empty($bid_attachments)){
							
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir);
							
							foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
								//	
								
								$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
								
								if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
									
									
									$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
									$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
									if($file_size != '-1')
									{
										$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
									}
								}
							}
						}
						if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
							$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
						}
					}
				
					####### generate log #########
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					
					$sp_profile_url_link = VPATH .$user[0]->profile_name;
					
					$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ?  $user[0]->first_name ." ".$user[0]->last_name : $user[0]->company_name;
					
					$sp_accept_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name),$sp_accept_bid_activity_log_message);
					
					
					if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')){
						$po_accept_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name,$sp_profile_url_link),$po_accept_bid_activity_log_message);
					}else{
						$po_accept_bid_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_company_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$name,$sp_profile_url_link),$po_accept_bid_activity_log_message);
					}

					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_data['project_id'],$project_data['project_type']); // get bid count of project
					
					$project_bid_count_history = $project_bid_count;
					
					
					
					
					if(!empty($progress_project_table_name)) {
						$project_data = $this->db // get the user detail
						->select('pd.*')
						->from($progress_project_table_name.' pd')
						->where('pd.project_id', $progress_bid_data['project_id'])
						->get()->row_array();
					} 

					if($project_data['project_type'] == 'fulltime'){ 
						$inprogress_bidder_data = $this->db // get the user detail
						->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.fulltime_project_user_total_avg_rating_as_employee,ipb.*')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects')
						->from($progress_bid_table_name. ' ipb')
						->join('users u', 'u.user_id = ipb.employee_id', 'left')
						->join('users_details ud', 'ud.user_id = u.user_id', 'left')
						->where('fulltime_project_id', $progress_bid_data['fulltime_project_id'])
						->where('employee_id', $progress_bid_data['employee_id'])
						->where('ipb.employer_id', $progress_bid_data['employer_id'])
						->get()->row_array();
						$inprogress_bidder_data['bidding_dropdown_option'] = $inprogress_bidder_data['application_bidding_dropdown_option'];
						$inprogress_bidder_data['winner_id'] = $inprogress_bidder_data['employee_id'];
						$inprogress_bidder_data['project_owner_id'] = $inprogress_bidder_data['employer_id'];
						$inprogress_bidder_data['project_start_date'] = $inprogress_bidder_data['employee_hired_on'];
						$inprogress_bidder_data['project_id'] = $inprogress_bidder_data['fulltime_project_id'];
					} else {
						$inprogress_bidder_data = $this->db // get the user detail
						->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,ipb.*')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_fixed_budget_projects')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_hourly_based_projects')
						->from($progress_bid_table_name. ' ipb')
						->join('users u', 'u.user_id = ipb.winner_id', 'left')
						->join('users_details ud', 'ud.user_id = u.user_id', 'left')
						->where('project_id', $progress_bid_data['project_id'])
						->where('winner_id', $progress_bid_data['winner_id'])
						->where('ipb.project_owner_id', $progress_bid_data['project_owner_id'])
						->get()->row_array();
					}

					$common_source_path = USERS_FTP_DIR . $inprogress_bidder_data['profile_name'];

					//avatar picture
					//start check avatar from ftp server
					$user_avatar = USER_AVATAR;
					$source_path_avatar = $common_source_path . $user_avatar;
					$avatarlist = $this->ftp->list_files($source_path_avatar);
					$avatar_pic = $source_path_avatar . $inprogress_bidder_data['user_avatar'];

					$exap = explode('.', $inprogress_bidder_data['user_avatar']);
					$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

					if (count($avatarlist) > 0) {
						$acheck = true;
						if (!in_array($avatar_pic, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $inprogress_bidder_data['user_id']));
							$this->ftp->delete_dir($source_path_avatar);
							$inprogress_bidder_data['user_avatar'] = '';
							$acheck = false;
						} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$inprogress_bidder_data['user_id']));
							$this->ftp->delete_dir($source_path_avatar);
							$inprogress_bidder_data['user_avatar'] = '';
							$acheck = false;
						}
					} if (count($avatarlist) == 0 && $inprogress_bidder_data['user_avatar'] != '') {
						$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $inprogress_bidder_data['user_id']));
						$inprogress_bidder_data['user_avatar'] = '';
					}

					$this->ftp->close();
					######## connectivity of remote server end ####### 
					
					user_display_log($sp_accept_bid_activity_log_message, $bidder_id); // activity log message for service provider
					user_display_log($po_accept_bid_activity_log_message, $project_data['project_owner_id']); // activity log message for project owner
					
					
					
					$in_progress_bidder_attachment_list = $this->db // get the user detail
					->select('id,bid_attachment_name,user_id')
					->from('projects_active_bids_users_attachments_tracking')
					->where('project_id', $project_id)
					->where('user_id', $bidder_id)
					->order_by('id DESC')
					->get()->result_array();
					$sender = $this->db->get_where('users', ['user_id' => $project_data['project_owner_id']])->row_array();
					$sender_profile = $this->db->get_where('users_details', ['user_id' => $project_data['project_owner_id']])->row_array();
						
					$profile_name = [];

					$receiver = $this->db->get_where('users', ['user_id' => $bidder_id])->row_array();
					$receiver_profile = $this->db->get_where('users_details', ['user_id' => $bidder_id])->row_array();

					if(!empty($sender)) {
					   $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
					   $common_source_path = USERS_FTP_DIR . $sender['profile_name'];
					   $user_avatar = USER_AVATAR;
					   $source_path_avatar = $common_source_path . $user_avatar;
					   ######## connectivity of remote server start #######
					   $this->load->library('ftp');
					   $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					   $config['ftp_username'] = FTP_USERNAME;
					   $config['ftp_password'] = FTP_PASSWORD;
					   $config['ftp_port'] = FTP_PORT;
					   $config['debug'] = TRUE;
					   $this->ftp->connect($config);
					   $avatarlist = $this->ftp->list_files($source_path_avatar);
					   $avatar_pic = $source_path_avatar . $sender_profile['user_avatar'];
										   
					   if (!empty($sender_profile['user_avatar'])) {
						   $file_size = $this->ftp->get_filesize($avatar_pic);
						   if ($file_size != '-1') {
							$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
							}
					   }else {   
						//if(!empty($sender_profile['user_avatar']) && is_url_exist($sender_profile_picture)) {
							//$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
						//} else {
							 if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
								if($sender['gender'] == 'M'){
										$sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($sender['gender'] == 'F'){
									 $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
								}
							} else {
								$sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
							}
						}
						 //end check avatar
						$this->ftp->close();
						if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')) {
							$profile_name[$project_data['project_owner_id']] = ['profile' => $sender['first_name'].' '.$sender['last_name']];
						} else {
							$profile_name[$project_data['project_owner_id']] = ['profile' => $sender['company_name']];
						}	
						$profile_name[$project_data['project_owner_id']]['avatar'] = $sender_profile_picture;
					}
					if(!empty($receiver)) {
					   $receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
					   $common_source_path_receiver = USERS_FTP_DIR . $receiver['profile_name'];
					   $user_avatar_receiver = USER_AVATAR;
					   $source_path_avatar_receiver = $common_source_path_receiver . $user_avatar_receiver;
					   ######## connectivity of remote server start #######
					   $this->load->library('ftp');
					   $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					   $config['ftp_username'] = FTP_USERNAME;
					   $config['ftp_password'] = FTP_PASSWORD;
					   $config['ftp_port'] = FTP_PORT;
					   $config['debug'] = TRUE;
					   $this->ftp->connect($config);
								 
					   $avatarlist_receiver = $this->ftp->list_files($source_path_avatar_receiver);
					   $avatar_pic_receiver = $source_path_avatar_receiver . $receiver_profile['user_avatar'];
										   
					   if (!empty($receiver_profile['user_avatar'])) {
						   $file_size = $this->ftp->get_filesize($avatar_pic_receiver);
						   if ($file_size != '-1') {
							$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
						}
					   }else { 
						//if(!empty($receiver_profile['user_avatar']) && is_url_exist($receiver_profile_picture)) {
						//	$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
						//} else {
							if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
								if($receiver['gender'] == 'M'){
										$receiver_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($receiver['gender'] == 'F'){
									 $receiver_profile_picture = URL . 'assets/images/avatar_default_female.png';
								}
							} else {
									$receiver_profile_picture = URL . 'assets/images/avatar_default_company.png';
							}
						}
						//end check avatar
						$this->ftp->close();
						
						if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')) {
							$profile_name[$inprogress_bidder_data['winner_id']] = ['profile' => $receiver['first_name'].' '.$receiver['last_name']];
						} else {
							$profile_name[$inprogress_bidder_data['winner_id']] = ['profile' => $receiver['company_name']];
						}
						$profile_name[$inprogress_bidder_data['winner_id']]['avatar'] = $receiver_profile_picture;
					} 



					$inprogress_bidder_data['bid_attachments'] = $in_progress_bidder_attachment_list;
					$tmp[$bidder_id] = $this->conn->get_users_conversations_on_project($project_data['project_owner_id'],$bidder_id,$project_data['project_id'], $this->config->item('project_details_page_messages_tab_users_conversation_listing_limit'));
					$chat_msg = [];
					foreach($tmp[$bidder_id] as $val) {
						$id = (array)$val['id'];
						$timepstamp = (array)$val['message_sent_time'];
						$msg = nl2br($val['chat_message_text']);
						$tmp = [
							'id' => $id['uuid'],
							'sender_id' => $val['sender_id'],
							'receiver_id' => $val['receiver_id'],
							'chat_message_text' => $msg,
							'chat_attachments' => $val['chat_attachments'],
							'is_general_chat' => $val['is_general_chat'],
							'message_sent_time' => date(DATE_TIME_FORMAT, $timepstamp['seconds']),
							'sender' => $profile_name[$val['sender_id']]['profile'],
							'sender_profile_pic' => $profile_name[$val['sender_id']]['avatar'],
							'timestamp' => "'".date('Y-m-d H:i:s', $timepstamp['seconds'])."'",
							'seconds' => $timepstamp['seconds'],
							'is_read' => $val['is_read'] ? 1 : 0
						] ;
						array_push($chat_msg, $tmp);
					}
					$chat_msg = array_reverse($chat_msg);
					$tmp[$bidder_id] = $chat_msg;
					$first[$bidder_id] = current($chat_msg);

					$first_conversation_row = $this->conn->get_users_conversations_on_project($project_data['project_owner_id'],$bidder_id,$project_data['project_id'], 1, '', 'ASC');
					$first_conversation_uuid = '';
					foreach($first_conversation_row as $val) {
							$id = (array)$val['id'];
							$first_conversation_uuid = $id['uuid'];
					}
					$first_conversation_uuid[$bidder_id] = $first_conversation_uuid;

					$datewise_group_chat_data = [];
					foreach($tmp[$bidder_id] as $val) {
						$datewise_group_chat_data[date(DATE_FORMAT, $val['seconds'])][] = $val;
					}
					$tmp[$bidder_id] = $datewise_group_chat_data;
					$project_chat_unread_messages_count[$bidder_id] = $this->conn->get_unread_chat_messages_count($user[0]->user_id, $project_data['project_id'], ($user[0]->user_id != $bidder_id) ? $bidder_id : $project_data['project_owner_id'] );
					
					$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
					if($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'awarded'){
						$this->db->select('pd.project_id,pd.project_expiration_date,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
											
						$this->db->from($project_status_table_array['table_name']." as pd");
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
						$this->db->where('pd.project_id',$project_id);
						
						$project_result = $this->db->get();
						$project_info = $project_result->row_array();
						
						$update_upgrade = array();
						if(!empty($project_info)){
							$featured_max = 0;
							$urgent_max = 0;
							$project_expiration_date = $project_info['project_expiration_date']!= NULL ? strtotime ($project_info['project_expiration_date']) : 0;
							if(!empty($project_info['featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['featured_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['bonus_featured_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_info['membership_include_featured_upgrade_end_date'];
							}
							if(!empty($expiration_featured_upgrade_date_array)){
								$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $featured_max >= time()){
								$update_upgrade['featured'] = 'Y';
								$featured_upgrade_expired_status = 1;
					
							}
							
							if(!empty($project_info['urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['urgent_upgrade_end_date'];
							}
							if(!empty($project_info['bonus_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['bonus_urgent_upgrade_end_date'];
							}
							if(!empty($project_info['membership_include_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_info['membership_include_urgent_upgrade_end_date'];
							}
							if(!empty($expiration_urgent_upgrade_date_array)){
								$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
							}
							if($project_expiration_date >= time()  && $urgent_max >= time()){
								$update_upgrade['urgent'] = 'Y';
								
								$urgent_upgrade_expired_status = 1;
							}
						}
						if(!empty($update_upgrade)){
							$this->db->update('projects_open_bidding',$update_upgrade, array("project_id" => $project_id));
						}
					}	
					$is_project_expired = '0';
					if($project_expiration_date < time()){
						$is_project_expired = '1';
					
					}
					if($project_status_table_array['project_status'] == 'open_for_bidding'){
						$project_status =$this->config->item('project_status_open_for_bidding');
					}else if($project_status_table_array['project_status'] == 'expired'){
						$project_status =$this->config->item('project_status_expired');
					}else if($project_status_table_array['project_status'] == 'in_progress'){
						$project_status =$this->config->item('project_status_in_progress');
					}
					
					/* $project_data['account_type'] = $po_detail['account_type'];
					$project_data['is_authorized_physical_person'] = $po_detail['is_authorized_physical_person'];
					$project_data['first_name'] = $po_detail['first_name'];
					$project_data['last_name'] = $po_detail['last_name'];
					$project_data['company_name'] = $po_detail['company_name'];
					$project_data['profile_name'] = $po_detail['profile_name']; */ 
					
					echo json_encode(['status' => 200,'is_project_expired'=>$is_project_expired,
					'location'=>'','featured_upgrade_expired_status'=>$featured_upgrade_expired_status,'urgent_upgrade_expired_status'=>$urgent_upgrade_expired_status,
					'po_id'=>$project_data['project_owner_id'],
					'sp_id'=>$bidder_id,
					'project_id'=>$project_id,'message'=>'','remove_id'=>$bidder_id,
					'project_status'=>$project_status,
					'bid_status'=>($project_data['project_type'] != 'fulltime') ? $this->config->item('project_details_page_inprogress_project_tab_sp_view_txt') : $this->config->item('fulltime_project_details_page_hired_fulltime_project_tab_employee_view_txt'),
					'project_bid_count_history'=>$project_bid_count_history,
					'data'=>$this->load->view('project_inprogress_bidders_listing',array(
																																							'project_data'=>$project_data,
																																							'inprogress_bidder_data'=>$inprogress_bidder_data, 
																																							'first' => $first,
																																							'first_conversation_uuid' => $first_conversation_uuid,
																																							'project_chat' => $tmp,
																																							'profile_pic' => $profile_name,
																																							'project_chat_unread_messages_count' => $project_chat_unread_messages_count,
																																							'project_owner_name' =>$project_owner_name
																																						), true)]);
					die;
					
				}
				
			} else {
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
		} else {
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to upload bid attachment.
	*/
	public function upload_bid_attachment ()
    {
		if($this->input->is_ajax_request ()){
			if(!$this->uri->segment('3')){ // if project temp id not coming it will show 404 page
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->uri->segment('3');
			$action_type = $this->uri->segment('4');
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				$login_user_detail = $this->db // get the user detail
				->select('u.user_id,ud.current_membership_plan_id')
				->from('users u')
				->join('users_details ud', 'ud.user_id = u.user_id', 'left')
				->where('u.user_id', $user[0]->user_id)
				->get()->row_array();
				
				if($login_user_detail['current_membership_plan_id'] == 4){
					$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
					
				}else if($login_user_detail['current_membership_plan_id'] == 1){
				
					$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
				}
					
				$bid_attachment_maximum_size_limit	 = $this->config->item('project_details_page_bid_attachment_maximum_size_limit');
				
				$bid_attachment_maximum_size_limit = ($bid_attachment_maximum_size_limit * 1048576);
				
				if($action_type == 'place_bid'){
					$po_id = Cryptor::doDecrypt($this->uri->segment('5'));
					if($po_id == $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
				
					if(empty($project_status_table_array['project_status']) && empty(	$project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_deleted_project')]);
						die;
					}
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.*,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
					} else {
						$project_data = $this->db // get the user detail
						->select('pd.*,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_cancelled_fulltime_project');
							}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_status_table_array['project_status'] == 'expired'){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_expired_fulltime_project');
							}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_expired_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
				
					
					
					
					if(strtotime($project_data['project_expiration_date']) <= time() && $project_status_table_array['project_status'] == 'open_for_bidding' ){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_expired_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_expired_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					}
					
					if($project_data['project_type'] == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					} else if($project_data['project_type'] == 'hourly'){
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
						$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					} else if($project_data['project_type'] == 'fulltime'){
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
						$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					}
					if($project_data['project_type'] == 'fulltime') { 
						$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					} else {
						$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					}
					if($check_already_active_bid > 0){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_already_posted_bid_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_already_posted_bid_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					}
					if($project_data['project_type'] == 'fulltime') { 
						$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					} else {
						$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					}
					if($check_awarded_bid  > 0){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					/* if(!empty($inprogress_bid_table_name)) {
						$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					} */
					
					if($project_data['project_type'] == 'fulltime') { 
						$check_already_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					} else {
						$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					}
					
					
					if($check_already_in_progress_bid  > 0){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if(!empty($completed_bid_table_name)) {
						$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
					}
					if($project_data['project_type'] != 'fulltime' && !empty($check_completed_bid) && $check_completed_bid  > 0){
						
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_upload_bid_attachment_on_completed_project');
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					
					if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'awarded'){
						
						
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_awarded_different_sp_project');
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'completed'){
						
						$error_message = $this->config->item('project_details_page_sp_view_different_sp_try_place_bid_upload_bid_attachment_on_completed_project');
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_data['project_type'] != 'fulltime' && $project_status_table_array['project_status'] == 'in_progress'){
						
						$error_message = $this->config->item('project_details_page_sp_view_place_bid_upload_bid_attachment_in_progress_different_sp_project');
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					
					$no_bid_attachment_uploaded_user = $this->db->where(['project_id' => $project_id,'user_id'=>$user[0]->user_id,'user_id'=>$user[0]->user_id])->from('projects_active_bids_users_attachments_tracking')->count_all_results();// check the number of attachment of user into database // check the number of attachment of user into database
					if(!empty($_FILES['file']['tmp_name'])){
						$file_array = $_FILES['file'];
						if($file_array['size'] > $bid_attachment_maximum_size_limit){

							$msg['status'] = 'FAILED';
							$msg['location'] = '';
							$msg['error'] = $this->config->item('project_attachment_maximum_size_validation_post_project_message');
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							$msg['upload_button_status'] = 1;
							echo json_encode ($msg);die;
						}elseif($no_bid_attachment_uploaded_user >= $maximum_allowed_number_of_bid_attachments){
							$msg['status'] = 'FAILED';
							$msg['upload_button_status'] = 0;
							$msg['location'] = '';
							$msg['error'] = $this->config->item('project_details_page_maximum_allowed_number_of_bid_attachments_validation_project_bid_form_message');
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						
						}else{
					
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
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
							$profile_folder     = $project_data['profile_name'];
							$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$project_expired_dir = PROJECT_EXPIRED_DIR;
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
							
							/* $this->ftp->mkdir($users_ftp_dir.$profile_folder, 0777);// create project owner directory if not exists
							$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists */
							if($project_status_table_array['project_status'] == 'open_for_bidding'){
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
								
								/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777); // create the project directory if it is not exists
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id, 0777); // create the project directory if it is not exists
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir , 0777);
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
								
							}
							if($project_status_table_array['project_status'] == 'expired'){
							
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir);
								
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
							
								/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir, 0777);//
							
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir , 0777);
								
								
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
								
							}
							if(!empty($project_id )){
								$temp 		= 	explode(".", $file_array["name"]);
								$extension 	= 	end($temp);
								$bid_attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$project_id.'.'.$extension;// name of attachment
							
								if(move_uploaded_file($file_array['tmp_name'],TEMP_DIR.$bid_attachment_name)){
								
									$source_path = FCPATH .TEMP_DIR. $bid_attachment_name;
									if($project_status_table_array['project_status'] == 'open_for_bidding'){
										$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
									}
									if($project_status_table_array['project_status'] == 'expired'){
										$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
									}
									$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
									unlink(FCPATH .TEMP_DIR. $bid_attachment_name);
									$bid_attachments_data = array('user_id'=>$user[0]->user_id,'project_id'=>$project_id,'bid_attachment_name'=>$bid_attachment_name);
									$this->db->insert ('projects_active_bids_users_attachments_tracking', $bid_attachments_data);
									$last_insert_id = $this->db->insert_id();
								
								
									$no_bid_attachment_uploaded_user = $this->db->where(['project_id' => $project_id,'user_id'=>$user[0]->user_id,'user_id'=>$user[0]->user_id])->from('projects_active_bids_users_attachments_tracking')->count_all_results();// check the number of attachment of user into database // check the number of attachment of user into database
								
									$msg['status'] = 'OK';
									$msg['message'] = 'uploded';
									$msg['filename'] = $bid_attachment_name;
									$msg['size'] = number_format($file_array['size']/1024). 'KB';
									$msg['id'] = $last_insert_id;
									$msg['attachment_id'] = Cryptor::doEncrypt($last_insert_id);
									$msg['action_type'] = 'place_bid';
									
									$upload_button_status = '0';
									if($no_bid_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
										$upload_button_status = '1';
									}
									$msg['upload_button_status'] = $upload_button_status;
									echo json_encode ($msg);die;
								}
							}
							$this->ftp->close();
						}	
					
					}else{
						$msg['status'] = 400;
						$msg['error'] = 'file is empty';
						$msg['location'] = '';
						echo json_encode($msg);
						die;
					}
					
				} else if($action_type == 'update_bid'){
				
					$section_id = $this->uri->segment('5');
					$project_type = $this->uri->segment('6');
					
					if($project_type == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					} else if($project_type == 'hourly') {
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					} else if($project_type == 'fulltime') {
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					}
					$bid_data = $this->db->get_where($bid_table_name, ['id' => $section_id])->row_array();
					if(!empty($bid_data) && $project_type == 'fulltime') { 
						$bid_data['bidder_id'] = $bid_data['employee_id'];
					}
					if(!empty($bid_data) && $bid_data['bidder_id'] !=  $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
		
					if(empty($project_status_table_array['project_status']) && empty(	$project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_deleted_project')]);
						die;
					}
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.*,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
					} else {
						$project_data = $this->db // get the user detail
						->select('pd.*,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					if($project_data['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					
					} else if($project_data['project_type'] == 'hourly'){
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
						$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					} else if($project_data['project_type'] == 'fulltime'){
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
						$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					}
					
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_cancelled_fulltime_project');
							}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_data['project_type'] == 'fulltime') {
						$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					} else {
						$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					}
					if($check_awarded_bid  > 0){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_awarded_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if(!empty($inprogress_bid_table_name)) {
						if($project_data['project_type'] == 'fulltime'){
							$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
						}else{
							$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
						}		
						
						
					}
					if($check_in_progress_bid  > 0){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_in_progress_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_upload_bid_attachment_in_progress_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if($project_data['project_type'] != 'fulltime' &&  !empty($completed_bid_table_name)) {
						$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
					}
					if($project_data['project_type'] != 'fulltime' && $check_completed_bid  > 0){
						
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_update_bid_upload_bid_attachment_on_completed_project');
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if($project_data['project_type'] == 'fulltime') {
						$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					} else {
						$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					}
					if($check_already_active_bid == 0){
						
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_upload_bid_attachment_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_upload_bid_attachment_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
						
					
					} else {
						$no_bid_attachment_uploaded_user = $this->db
						->select ('id')
						->from ('projects_active_bids_users_attachments_tracking')
						->where ('project_id', $project_id)
						->where ('user_id', $user[0]->user_id)
						->get ()->num_rows ();// check the number of attachment of user into database // check the number of attachment of user into database
						
						if(!empty($_FILES['file']['tmp_name'])){
						$file_array = $_FILES['file'];
						if($file_array['size'] > $bid_attachment_maximum_size_limit){
							$msg['status'] = 'FAILED';
							$msg['location'] = '';
							$msg['error'] = $this->config->item('project_attachment_maximum_size_validation_post_project_message');
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							$msg['upload_button_status'] = 1;
							echo json_encode ($msg);die;
						} else if($no_bid_attachment_uploaded_user >= $maximum_allowed_number_of_bid_attachments){
							$msg['status'] = 'FAILED';
							$msg['location'] = '';
							$msg['error'] = $this->config->item('project_details_page_maximum_allowed_number_of_bid_attachments_validation_project_bid_form_message');
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							$msg['upload_button_status'] = 0;
							echo json_encode ($msg);die;
						
						} else {
					
								#### connectivity of remote server start#########
								$this->load->library('ftp');
								$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								$config['ftp_username'] = FTP_USERNAME;
								$config['ftp_password'] = FTP_PASSWORD;
								$config['ftp_port'] 	= FTP_PORT;
								$config['debug']    = TRUE;
								$this->ftp->connect($config); 
								######## connectivity of remote server end #######
								
								$users_ftp_dir 	= USERS_FTP_DIR; 
								$projects_ftp_dir = PROJECTS_FTP_DIR;
								$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
								$profile_folder     = $project_data['profile_name'];
								$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
								$project_expired_dir = PROJECT_EXPIRED_DIR;
								$project_awarded_dir = PROJECT_AWARDED_DIR;
								$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
								$project_completed_dir = PROJECT_COMPLETED_DIR;
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								
								
								/* $this->ftp->mkdir($users_ftp_dir.$profile_folder, 0777);// create project owner directory if not exists
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists */
								
								
								if($project_status_table_array['project_status'] == 'open_for_bidding'){
								
								
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir);
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
								
								
									/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777); // create the project directory if it is not exists
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id, 0777); // create the project directory if it is not exists
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir , 0777);
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
									
								}
								if($project_status_table_array['project_status'] == 'awarded'){
								
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
								
								
									/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir, 0777);//
								
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir , 0777);
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
									
								}
								if($project_status_table_array['project_status'] == 'in_progress'){
								
								
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
								
									/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir, 0777);//
								
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir , 0777);
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
									
								}
								if($project_status_table_array['project_status'] == 'completed'){
								
								
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
								
									/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir, 0777);//
								
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777);
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
									
								}
								if($project_status_table_array['project_status'] == 'expired'){
								
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir);
									
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.DIRECTORY_SEPARATOR);
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir);
									
									$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR);
								
								
									/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir, 0777);//
								
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir , 0777);
									$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id . DIRECTORY_SEPARATOR , 0777); */
									
								}
								if(!empty($project_id )){
									$temp 		= 	explode(".", $file_array["name"]);
									$extension 	= 	end($temp);
									$bid_attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$project_id.'.'.$extension;// name of attachment
								
									if(move_uploaded_file($file_array['tmp_name'],TEMP_DIR.$bid_attachment_name)){
									
										$source_path = FCPATH .TEMP_DIR. $bid_attachment_name;
										if($project_status_table_array['project_status'] == 'open_for_bidding'){
											$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
										}
										if($project_status_table_array['project_status'] == 'awarded'){
											$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
										}
										if($project_status_table_array['project_status'] == 'in_progress'){
											$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
										}
										if($project_status_table_array['project_status'] == 'completed'){
											$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
										}
										
										if($project_status_table_array['project_status'] == 'expired'){
											$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
										}
										
										$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
										unlink(FCPATH .TEMP_DIR. $bid_attachment_name);
										$bid_attachments_data = array('user_id'=>$user[0]->user_id,'project_id'=>$project_id,'bid_attachment_name'=>$bid_attachment_name);
										$this->db->insert ('projects_active_bids_users_attachments_tracking', $bid_attachments_data);
										$last_insert_id = $this->db->insert_id();
									
									
										$no_bid_attachment_uploaded_user = $this->db
										->select ('id')
										->from ('projects_active_bids_users_attachments_tracking')
										->where ('project_id', $project_id)
										->where ('user_id', $user[0]->user_id)
										->get ()->num_rows ();// check the number of attachment of user into database // check the number of attachment of user into database
									
										$msg['status'] = 'OK';
										$msg['message'] = 'uploded';
										$msg['filename'] = $bid_attachment_name;
										$msg['size'] = number_format($file_array['size']/1024). 'KB';
										$msg['id'] = $last_insert_id;
										$msg['attachment_id'] = Cryptor::doEncrypt($last_insert_id);
										$msg['action_type'] = 'update_bid';
										$msg['section_id'] = $section_id;
										
										$upload_button_status = '0';
										if($no_bid_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
											$upload_button_status = '1';
										}
										$msg['upload_button_status'] = $upload_button_status;
										echo json_encode ($msg);die;
									}
								}
								$this->ftp->close();
							}	
						
						}else{
							$msg['status'] = 400;
							$msg['location'] = '';
							$msg['error'] = 'file is empty';
							echo json_encode($msg);
							die;
						}
					
					}
				
				
				}
				
				
			}else{
			
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	* This function is used to remove the bid attachment.
	*/
	public function delete_bid_attachment ()
    {
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('bid_attachment_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$action_type = $this->input->post ('action_type');
				$project_type = $this->input->post ('project_type');
				$po_id =  Cryptor::doDecrypt($this->input->post ('po_id'));
				$user = $this->session->userdata ('user');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if($action_type == 'place_bid'){
					
					
					if($po_id ==  $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_delete_attachment_deleted_project')]);
						die;
					}
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
						
						$project_detail = $this->db 
						->select('pd.fulltime_project_id as project_id,pd.fulltime_project_expiration_date as project_expiration_date,pd.project_type,pd.employer_id as project_owner_id,users.profile_name')
						->from($project_status_table_array['table_name'].' as pd')
						->join('users', 'users.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id',$project_id)
						->get()->row_array();
					}else{	
					
						$project_detail = $this->db // get the user detail
						->select('pd.project_owner_id,pd.project_type,pd.project_id,pd.project_expiration_date,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					
					/* if($project_detail['project_owner_id'] == $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					} */
					
					$bid_attachment_name = $this->input->post ('bid_attachment_name');
					$bid_attachment_id = $this->input->post ('bid_attachment_id');
					
					$bid_attachment_data = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $bid_attachment_id])->row_array();
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_cancelled_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_status_table_array['project_status'] == 'expired'){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_expired_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_expired_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_detail['project_type'] == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					} else if($project_detail['project_type'] == 'hourly'){
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
						$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					} else if($project_detail['project_type'] == 'fulltime'){
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
						$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					}
					if($project_detail['project_type'] == 'fulltime') {
						$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					} else {
						$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					}
					if($check_already_active_bid > 0) {
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_already_posted_same_sp_fulltime_project');
							}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_already_posted_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					}
					if($project_detail['project_type'] == 'fulltime') { 
						$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					} else {
						$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					}
					if($check_awarded_bid  > 0){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_awarded_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if($project_detail['project_type'] == 'fulltime'){
						$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					}else{
						$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					}
					if($check_in_progress_bid  > 0){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_in_progress_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_in_progress_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if($project_detail['project_type'] != 'fulltime') {
						$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
					}
					if($project_detail['project_type'] != 'fulltime' && $check_completed_bid >0  && $check_in_progress_bid  == 0){
						/* if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_delete_bid_attachment_on_completed_fulltime_project');
						}else{ */
							$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_delete_bid_attachment_on_completed_project');
						/* } */
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					
					if(strtotime($project_detail['project_expiration_date']) <= time()  && $project_status_table_array['project_status'] == 'open_for_bidding'){
					
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_expired_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_delete_bid_attachment_expired_project');
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
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$profile_folder     = $project_detail['profile_name'];
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					
					
					if(!empty($bid_attachment_data)){
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						
						$this->db->delete('projects_active_bids_users_attachments_tracking', array('id' => $bid_attachment_id));
						$no_bid_attachment_uploaded_user = $this->db->where(['project_id' => $project_id,'user_id'=>$user[0]->user_id,'user_id'=>$user[0]->user_id])->from('projects_active_bids_users_attachments_tracking')->count_all_results();
						$msg['status'] = 'SUCCESS';
						$msg['message'] = '';
						if($login_user_detail['current_membership_plan_id'] == 4){
							$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
							
						}else if($login_user_detail['current_membership_plan_id'] == 1){

							$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
						}
						$upload_button_status = '0';
						if($no_bid_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
							$upload_button_status = '1';
						}
						$msg['upload_button_status'] = $upload_button_status;
					
					}else{
					
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						$no_bid_attachment_uploaded_user = $this->db->where(['project_id' => $project_id,'user_id'=>$user[0]->user_id,'user_id'=>$user[0]->user_id])->from('projects_active_bids_users_attachments_tracking')->count_all_results();
						$msg['status'] = 'SUCCESS';
						$msg['message'] = '';
						if($login_user_detail['current_membership_plan_id'] == 4){
							$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
							
						}else if($login_user_detail['current_membership_plan_id'] == 1){

							$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
						}
						$upload_button_status = '0';
						if($no_project_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
							$upload_button_status = '1';
						}
						$msg['upload_button_status'] = $upload_button_status;
					}
					
					$this->ftp->close();
					echo json_encode ($msg);die;
				}
				
				if($action_type == 'update_bid'){
					$bid_section_id = $this->input->post ('bid_section_id');
					if($project_type == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					} else if($project_type == 'hourly') {
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					} else if($project_type == 'fulltime') {
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					}
					$bid_data = $this->db->get_where($bid_table_name, ['id' => $bid_section_id])->row_array();
					if(!empty($bid_data) && $project_type == 'fulltime') {
						$bid_data['bidder_id'] = $bid_data['employee_id'];
					}
					
					if(!empty($bid_data) && $bid_data['bidder_id'] !=  $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_update_bid_delete_attachment_deleted_project')]);
						die;
					}
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_detail = $this->db // get the user detail
						->select('pd.employer_id as project_owner_id,pd.project_type,pd.fulltime_project_id as project_id,pd.fulltime_project_expiration_date as project_expiration_date,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
					} else {
						$project_detail = $this->db // get the user detail
						->select('pd.project_owner_id,pd.project_type,pd.project_id,pd.project_expiration_date,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					if($project_detail['project_type'] == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					} else if($project_detail['project_type'] == 'hourly'){
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
						$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					} else if($project_detail['project_type'] == 'fulltime'){
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
						$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					}
					
					
					
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_delete_bid_attachment_cancelled_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_delete_bid_attachment_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_detail['project_type'] == 'fulltime') { 
						$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					} else {
						$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
					}
					if($check_awarded_bid  > 0){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_delete_bid_attachment_awarded_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_delete_bid_attachment_awarded_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					/* if(!empty($inprogress_bid_table_name)) {
						$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					} */
					
					if($project_detail['project_type'] == 'fulltime'){
						$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					}else{
						$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					}
					if($check_in_progress_bid  > 0){
						if($project_detail['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_delete_bid_attachment_in_progress_same_sp_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_delete_bid_attachment_in_progress_same_sp_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					if(!empty($completed_bid_table_name)) {
						$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
					}
					if($project_detail['project_type'] != 'fulltime' && $check_completed_bid  > 0){
						
						$error_message = $this->config->item('project_details_page_sp_view_same_sp_try_place_bid_delete_bid_attachment_on_completed_project');
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					
					}
					
					$login_user_detail = $this->db // get the user detail
					->select('u.user_id,ud.current_membership_plan_id')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $user[0]->user_id)
					->get()->row_array();
					
					
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
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					$profile_folder     = $project_detail['profile_name'];
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$project_expired_dir = PROJECT_EXPIRED_DIR;
					
					
					$bid_attachment_name = $this->input->post ('bid_attachment_name');
					$bid_attachment_id = $this->input->post ('bid_attachment_id');
					
					$bid_attachment_data = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $bid_attachment_id])->row_array();
					if(!empty($bid_attachment_data)){
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_data['bid_attachment_name'];
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						
						$this->db->delete('projects_active_bids_users_attachments_tracking', array('id' => $bid_attachment_id));
						$no_bid_attachment_uploaded_user = $this->db->where(['project_id' => $project_id,'user_id'=>$user[0]->user_id,'user_id'=>$user[0]->user_id])->from('projects_active_bids_users_attachments_tracking')->count_all_results();
						$msg['status'] = 'SUCCESS';
						$msg['message'] = '';
						if($login_user_detail['current_membership_plan_id'] == 4){
							$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
							
						}else if($login_user_detail['current_membership_plan_id'] == 1){$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
							
						}
						$upload_button_status = '0';
						if($no_bid_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
							$upload_button_status = '1';
						}
						$msg['upload_button_status'] = $upload_button_status;
					
					}else{
					
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'in_prgress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$user[0]->user_id .DIRECTORY_SEPARATOR .$bid_attachment_name;
						}
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						$no_bid_attachment_uploaded_user = $this->db->where(['project_id' => $project_id,'user_id'=>$user[0]->user_id,'user_id'=>$user[0]->user_id])->from('projects_active_bids_users_attachments_tracking')->count_all_results();
						$msg['status'] = 'SUCCESS';
						$msg['message'] = '';
						
						if($login_user_detail['current_membership_plan_id'] == 4){
							$maximum_allowed_number_of_bid_attachments = $this->config->item('gold_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
							
						}else if($login_user_detail['current_membership_plan_id'] == 1){$maximum_allowed_number_of_bid_attachments = $this->config->item('free_subscribers_project_details_page_maximum_allowed_number_of_bid_attachments_on_project_bid');
							
						}
						
						$upload_button_status = '0';
						if($no_project_attachment_uploaded_user < $maximum_allowed_number_of_bid_attachments){
							$upload_button_status = '1';
						}
						$msg['upload_button_status'] = $upload_button_status;
					}
					$msg['remaining_bid_attachments'] = $maximum_allowed_number_of_bid_attachments - $no_bid_attachment_uploaded_user; 
					$this->ftp->close();
					echo json_encode ($msg);die;
				}
				
				
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	/**
	* This function is used to check that bid attachment exists or not in either in folder or into database
	*/
	public function check_bid_attachment_exists ()
    {
		if( $this->input->is_ajax_request ()){
		
			if(empty($this->input->post ('attachment_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			$project_type = $this->input->post ('project_type');
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				
				$user = $this->session->userdata('user');
				$action_type = $this->input->post ('action_type');
				$po_id =  Cryptor::doDecrypt($this->input->post ('po_id'));
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				
				$open_bidding_project_detail = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id])->row_array();
				if(!empty($open_bidding_project_detail)){
					$project_status_table_array['project_type'] = $open_bidding_project_detail['project_type'];
				}
				
				if($action_type == 'place_bid'){
				
					if($po_id ==  $user[0]->user_id ){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
				
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_open_bid_attachment_deleted_project')]);
						die;
					}
					$encrypt_attachment_id = $this->input->post ('attachment_id');
					$decrypt_attachment_id = Cryptor::doDecrypt($this->input->post ('attachment_id'));
					
					$bid_attachment_detail = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $decrypt_attachment_id])->row_array();
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.employer_id as project_owner_id,pd.project_type,pd.fulltime_project_id as project_id,pd.fulltime_project_expiration_date as project_expiration_date,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
						
					} else {
						$project_data = $this->db // get the user detail
						->select('pd.*,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $project_id)
						->get()->row_array();
						
					}
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_data['project_type'] == 'fulltime'){
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_open_bid_attachment_cancelled_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_place_bid_open_bid_attachment_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
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
					
					if(!empty($bid_attachment_detail)){
						
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_expired_dir = PROJECT_EXPIRED_DIR;
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
						$project_awarded_dir = PROJECT_AWARDED_DIR;
						$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
						$project_completed_dir = PROJECT_COMPLETED_DIR;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						$profile_folder     = $project_data['profile_name'];
						
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}if($project_status_table_array['project_status'] == 'incomplete'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
						}
						
						
						
						$file_size = $this->ftp->get_filesize($source_path);
						
						if($file_size != '-1')
						{
							
							$msg['status'] = 'SUCCESS';
							$msg['error'] = '';
							$msg['location'] = VPATH . 'bidding/download_bid_attachment/'.$encrypt_attachment_id;
							
						}else{
							$msg['status'] = 'FAILED';
							if($project_status_table_array['project_type'] == 'fulltime'){
								$attachment_not_exists_error_message = 
								$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project');
							}else{
								$attachment_not_exists_error_message = 
								$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_project');
							}
							$msg['error'] = $attachment_not_exists_error_message;
							$msg['location'] = '';
						}
						$this->ftp->close();
					}else{
						$msg['status'] = 'FAILED';
						if($project_status_table_array['project_type'] == 'fulltime'){
							$attachment_not_exists_error_message = 
							$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project');
						}else{
							$attachment_not_exists_error_message = 
							$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_project');
						}
						$msg['error'] = $attachment_not_exists_error_message;	
						$msg['location'] = '';
						
					}
					echo json_encode ($msg);die;
				}
				if($action_type == 'update_bid'){
				
					$section_id = $this->input->post ('section_id');
					if($project_type == 'fixed'){
						$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'fixed_budget_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					}else if($project_type == 'hourly'){
						$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
						$awarded_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
						$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
						$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					}else if($project_type == 'fulltime'){
						$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
						$awarded_bid_table_name = 'fulltime_projects_awarded_tracking';
						$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					}
					$bid_data = $this->db->get_where($bid_table_name, ['id' => $section_id])->row_array();
					if($project_type == 'fulltime' && !empty($bid_data) ){
					
						$bid_data['bidder_id'] = $bid_data['employee_id'];
					
					}
					
					if(!empty($bid_data) && $bid_data['bidder_id'] !=  $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_update_bid_open_bid_attachment_deleted_project')]);
						die;
					}
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.employer_id as project_owner_id,pd.project_type,pd.fulltime_project_id as project_id,pd.fulltime_project_expiration_date as project_expiration_date,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $project_id)
						->get()->row_array();
					} else {
					
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
					}
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						if($project_data['project_type'] == 'fulltime'){
							
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_open_bid_attachment_cancelled_fulltime_project');
						}else{
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_open_bid_attachment_cancelled_project');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
						die;
					}
					if($project_data['project_type'] != 'fulltime'){
						$check_awarded_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
						$check_already_active_bid = $this->db->where(['project_id' => $project_id,'bidder_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					}else{
						$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($awarded_bid_table_name)->count_all_results();
						$check_already_active_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($bid_table_name)->count_all_results();
					}
					
					if($project_data['project_type'] != 'fulltime'){
						$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
					
						$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$user[0]->user_id])->from($completed_bid_table_name)->count_all_results();
						if($check_awarded_bid == 0 && $check_in_progress_bid == 0 && $check_already_active_bid == 0 && $check_in_progress_bid == 0){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_open_bid_attachment_same_sp_project');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
						}
							
					}else{
						
						$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
						
						if($check_awarded_bid == 0 && $check_already_active_bid == 0 && $check_in_progress_bid == 0 ){
							$error_message = $this->config->item('project_details_page_sp_view_update_bid_already_retracted_open_bid_attachment_same_sp_fulltime_project');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
						}
					
					}
					$encrypt_attachment_id = $this->input->post ('attachment_id');
					$decrypt_attachment_id = Cryptor::doDecrypt($this->input->post ('attachment_id'));
					
					$bid_attachment_detail = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $decrypt_attachment_id])->row_array();
					
					$project_id = $bid_attachment_detail['project_id'];
					
					$bid_attachment_detail = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $decrypt_attachment_id])->row_array();
					if(!empty($bid_attachment_detail)){
						
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_expired_dir = PROJECT_EXPIRED_DIR;
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
						$project_awarded_dir = PROJECT_AWARDED_DIR;
						$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						$profile_folder     = $project_data['profile_name'];
						
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}if($project_status_table_array['project_status'] == 'incomplete'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
						}
						
						
						
						$file_size = $this->ftp->get_filesize($source_path);
						
						if($file_size != '-1')
						{
							
							$msg['status'] = 'SUCCESS';
							$msg['error'] = '';
							$msg['location'] = VPATH . 'bidding/download_bid_attachment/'.$encrypt_attachment_id;
							
						}else{
							$msg['status'] = 'FAILED';
							if($project_status_table_array['project_type'] == 'fulltime'){
							$attachment_not_exists_error_message = 
							$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project');
							}else{
								$attachment_not_exists_error_message = 
								$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_project');
							}
							$msg['error'] = $attachment_not_exists_error_message;
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							$msg['location'] = '';
						}
						$this->ftp->close();
					}else{
						$msg['status'] = 'FAILED';
						if($project_status_table_array['project_type'] == 'fulltime'){
						$attachment_not_exists_error_message = 
						$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project');
						}else{
							$attachment_not_exists_error_message = 
							$this->config->item('project_details_page_bid_form_bid_attachment_not_exist_validation_message_bidder_view_project');
						}
						$msg['error'] = $attachment_not_exists_error_message;
						$msg['location'] = '';
						$msg['popup_heading'] = $this->config->item('popup_alert_heading');
						
					}
					echo json_encode ($msg);die;
				}
				if($action_type == 'download'){
					
					$sp_id = Cryptor::doDecrypt($this->input->post ('sp_id'));
					$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
					
					if($po_id ==  $user[0]->user_id && $sp_id ==  $user[0]->user_id ){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						$msg['popup_heading'] = $this->config->item('popup_alert_heading');
						$msg['status'] = 'FAILED';
						$msg['error'] = $this->config->item('project_details_page_deleted_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view');
						$msg['location'] = '';
						echo json_encode ($msg);die;
					}
					
					$encrypt_attachment_id = $this->input->post ('attachment_id');
					$decrypt_attachment_id = Cryptor::doDecrypt($this->input->post ('attachment_id'));
					
					$bid_attachment_detail = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $decrypt_attachment_id])->row_array();
					
					//$project_id = $bid_attachment_detail['project_id'];
					
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
						$project_data = $this->db 
						->select('pd.fulltime_project_id as project_id,pd.fulltime_project_expiration_date as project_expiration_date,pd.project_type,pd.employer_id as project_owner_id,users.profile_name')
						->from($project_status_table_array['table_name'].' as pd')
						->join('users', 'users.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id',$project_id)
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.*,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					
					if(!empty($bid_attachment_detail) && ($bid_attachment_detail['user_id'] !=  $user[0]->user_id && $project_data['project_owner_id'] !=  $user[0]->user_id )){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					
					if($project_status_table_array['project_status'] == 'cancelled'){
						$msg['status'] = 'FAILED';
						
						if($project_data['project_type'] == 'fulltime'){
							$msg['error'] = $this->config->item('project_details_page_cancelled_fulltime_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view');
						}else{
							$msg['error'] = $this->config->item('project_details_page_cancelled_project_bidder_list_open_bid_attachment_not_exist_validation_message_sp_po_view');
						}
						$msg['popup_heading'] = $this->config->item('popup_alert_heading');
						$msg['location'] = '';
						echo json_encode ($msg);die;
							
					}
					
					if($project_data['project_type'] == 'fixed'){
						$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					}else if($project_data['project_type'] == 'hourly'){
						$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					}else if($project_data['project_type'] == 'fulltime'){
					}
					
					$bid_attachment_detail = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $decrypt_attachment_id])->row_array();
					$file_size = 0;
					if(!empty($bid_attachment_detail)){
						
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_expired_dir = PROJECT_EXPIRED_DIR;
						$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
						$project_awarded_dir = PROJECT_AWARDED_DIR;
						$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
						$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
						$project_completed_dir = PROJECT_COMPLETED_DIR;
						$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						$profile_folder     = $project_data['profile_name'];
						
						
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'awarded'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'in_progress'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}if($project_status_table_array['project_status'] == 'incomplete'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}if($project_status_table_array['project_status'] == 'completed'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
							
						}
						if($project_status_table_array['project_status'] == 'expired'){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR .$bid_attachment_detail['bid_attachment_name'];
						}
						
						$file_size = $this->ftp->get_filesize($source_path);
						$this->ftp->close();
					}	
					
				
					
					if( ((empty($bid_attachment_detail)) || (!empty($bid_attachment_detail) && $file_size == '-1'))  && $user[0]->user_id == $sp_id){
						
						if($project_data['project_type'] == 'fulltime'){
							$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$user[0]->user_id])->from('fulltime_projects_awarded_tracking')->count_all_results();
							if($check_awarded_bid > 0){
								$msg['status'] = 'FAILED';
								$msg['error'] = $this->config->item('fulltime_project_details_page_sp_awarded_status_sp_try_open_bid_attachment_not_exist_validation_message_bidder_view');
								$msg['location'] = '';
								$msg['popup_heading'] = $this->config->item('popup_alert_heading');
								echo json_encode ($msg);die;
								
							}	
						}
						
						
						
						if($project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) > time()){
							
							if($project_data['project_type'] == 'fulltime'){
								$error_msg = $this->config->item('project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'); 
								}else{
								$error_msg = $this->config->item('project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_project'); 
							}
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						
						if(($project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) <= time()) || $project_status_table_array['project_status'] == 'expired'){
							
							if($project_data['project_type'] == 'fulltime'){
								
							
								$error_msg = $this->config->item('project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_fulltime_project'); 
								}else{
								$error_msg = $this->config->item('project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_bidder_view_project'); 
							}
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}

						if($project_status_table_array['project_status'] == 'awarded' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_awarded_project_awarded_open_bid_attachment_not_exist_validation_message_bidder_list_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'in_progress' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_in_progress_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'incomplete' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_incomplete_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'completed' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_completed_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}						

						
						
					}else if( ((empty($bid_attachment_detail)) || (!empty($bid_attachment_detail) && $file_size == '-1'))  && $user[0]->user_id == $po_id){
						
						
						
						if($project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) > time()){
							
							if($project_data['project_type'] == 'fulltime'){
								$error_msg = $this->config->item('project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_fulltime_project'); 
								}else{
								$error_msg = $this->config->item('project_details_page_open_for_bidding_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_project'); 
							}
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if(($project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) <= time()) || $project_status_table_array['project_status'] == 'expired'){
							
							if($project_data['project_type'] == 'fulltime'){
								$error_msg = $this->config->item('project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_fulltime_project'); 
								}else{
								$error_msg = $this->config->item('project_details_page_expired_project_bidder_list_open_bid_attachment_not_exist_validation_message_po_view_project'); 
							}
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'awarded' && $project_data['project_type'] != 'fulltime'){
							//die("dfgdgd");
							$error_msg = $this->config->item('project_details_page_awarded_project_awarded_open_bid_attachment_not_exist_validation_message_bidder_list_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'in_progress' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_in_progress_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'incomplete' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_incomplete_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}
						if($project_status_table_array['project_status'] == 'completed' && $project_data['project_type'] != 'fulltime'){
							$error_msg = $this->config->item('project_details_page_completed_project_open_bid_attachment_not_exist_validation_message_bidder_list_awarded_sp_po_view_project');
							$msg['status'] = 'FAILED';
							$msg['error'] = $error_msg;
							$msg['location'] = '';
							$msg['popup_heading'] = $this->config->item('popup_alert_heading');
							echo json_encode ($msg);die;
						}							
					}
					
					$msg['status'] = 'SUCCESS';
					$msg['error'] = '';
					$msg['location'] = VPATH . 'bidding/download_bid_attachment/'.$encrypt_attachment_id;
					echo json_encode ($msg);die;
				}
			}else{
				
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die; 
			
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	
	/**
	This function is used to download bid attachment.
	*/
	public function download_bid_attachment () {
		if($this->session->userdata ('user')){
			$this->load->helper('download');
			$attachment_id = Cryptor::doDecrypt($this->uri->segment(3));
			$user = $this->session->userdata ('user');
			
			######## connectivity of remote server start#########
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			######## connectivity of remote server end #######
			
		
			$bid_attachment_detail = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['id' => $attachment_id])->row_array();
			
			$project_id = $bid_attachment_detail['project_id'];
			$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
			
			if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						
				$msg['status'] = 'FAILED';
				$msg['error'] = $this->config->item('project_details_page_deleted_project_bidder_list_open_bid_attachment_not_exist_validation_message_visitor_view');
				$msg['location'] = '';
				echo json_encode ($msg);die;
			}else if($project_status_table_array['project_status'] == 'cancelled'){
				$msg['status'] = 'FAILED';
				
				if($project_status_table_array['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('project_details_page_bidder_list_open_bid_attachment_not_exist_validation_message_cancelled_visitor_view_fulltime_project');
				}else{
					$msg['error'] = $this->config->item('project_details_page_bidder_list_open_bid_attachment_not_exist_validation_message_cancelled_visitor_view_project');
				}
				$msg['location'] = '';
				echo json_encode ($msg);die;
					
			}
			/* $project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array(); */
				
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
				$project_data = $this->db 
				->select('pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,users.profile_name')
				->from($project_status_table_array['table_name'].' as pd')
				->join('users', 'users.user_id = pd.employer_id', 'left')
				->where('pd.fulltime_project_id',$project_id)
				->get()->row_array();
			}else{
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
			}	
				
				
				
			
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
			$project_expired_dir = PROJECT_EXPIRED_DIR;
			$project_awarded_dir = PROJECT_AWARDED_DIR;
			$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
			$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
			$project_completed_dir = PROJECT_COMPLETED_DIR;
			$profile_folder     = $project_data['profile_name'];
			if($project_status_table_array['project_status'] == 'open_for_bidding'){
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR  .$bid_attachment_detail['bid_attachment_name'];
			}
			if($project_status_table_array['project_status'] == 'awarded'){
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR  .$bid_attachment_detail['bid_attachment_name'];
			}
			if($project_status_table_array['project_status'] == 'in_progress'){
				
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR  .$bid_attachment_detail['bid_attachment_name'];
				
			}
			if($project_status_table_array['project_status'] == 'incomplete'){
				
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR  .$bid_attachment_detail['bid_attachment_name'];
				
			}
			if($project_status_table_array['project_status'] == 'completed'){
				
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR  .$bid_attachment_detail['bid_attachment_name'];
				
			}
			
			if($project_status_table_array['project_status'] == 'expired'){
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_detail['user_id'].DIRECTORY_SEPARATOR  .$bid_attachment_detail['bid_attachment_name'];
			}
		
			 $file_size = $this->ftp->get_filesize($source_path);
			
			
			if($file_size != '-1')
			{
				$destination_path =  FCPATH .TEMP_DIR.$bid_attachment_detail['bid_attachment_name'];
				$this->ftp->download($source_path,$destination_path, 'auto', 0777);
				$this->ftp->close();
				$data = file_get_contents (TEMP_DIR.$bid_attachment_detail['bid_attachment_name'] );// read the content of file
				unlink(TEMP_DIR.$bid_attachment_detail['bid_attachment_name'] );
				force_download ($bid_attachment_detail['bid_attachment_name'],$data);
			}
			else if($file_size == 0){
				
				show_custom_404_page();
			
			}
			else{
			
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
  }
	
	/**
	* This function is used to make the popup of mark project complete request .
	*/
	public function create_mark_complete_request_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				
				$mark_project_complete_request_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
				} 
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id,'project_completion_method'=>'outside_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					$error_message = $this->config->item('project_details_page_po_view_po_try_create_mark_project_complete_request_already_accepted_by_sp_message');
					$error_message = str_replace(array('{next_request_sent_time}'),array(secondsToWords(strtotime($next_request_send_date) -time())),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id,'project_completion_method'=>'via_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					$error_message = $this->config->item('project_details_page_po_view_po_try_create_mark_project_complete_request_project_already_completed_via_portal_message');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				
				
				$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				
				
				$check_already_incomplete_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id])->from($incomplete_bid_table_name)->count_all_results();
					
				if($check_already_in_progress_bid == 0 && $check_already_incomplete_bid ==0){
					$error_message = $this->config->item('project_details_page_po_view_create_mark_project_complete_invalid_request');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($check_already_in_progress_bid != 0){
				 $bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name')
				->from($inprogress_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				
				if($check_already_incomplete_bid != 0){
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name')
				->from($incomplete_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				
				if(!empty($bid_data)){
				
					$project_complete_request_detail = $this->Bidding_model->get_latest_project_complete_request_detail(array('winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>Cryptor::doDecrypt($po_id),'project_id'=>$project_id),$project_data['project_type']);
					
					if(!empty($project_complete_request_detail)){
						
						if($project_complete_request_detail['request_status'] == 'expired' || $project_complete_request_detail['request_status'] == 'declined' || $project_complete_request_detail['request_status'] == 'active'){
						
							if($project_complete_request_detail['request_status'] == 'declined'){
								$response_time = $project_complete_request_detail['request_declined_on'];
							}
							if($project_complete_request_detail['request_status'] == 'expired'){
								$response_time = $project_complete_request_detail['request_expires_on'];
							}
							if($project_complete_request_detail['request_status'] == 'active'){
								$response_time = $project_complete_request_detail['request_expires_on'];
							
							}
							
							$time_arr = explode(':', $this->config->item('po_send_mark_project_complete_request_time_left_till_next_resent'));
							$next_request_send_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($response_time)));
							
							
							if(strtotime($next_request_send_date) > time()){
								$error_message = $this->config->item('project_details_page_po_view_create_mark_project_complete_request_resent_time_expired');
								$error_message = str_replace(array('{next_mark_project_as_complete_request_send_available_time}'),array(secondsToWords(strtotime($next_request_send_date) -time())),$error_message);
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
								die;
							}
						
						}
					
					
					}
					if($project_data['project_type'] == 'hourly'){
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bid_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone

						if($bid_data['initial_project_agreed_hourly_rate'] == 0 && $paid_milestones_count_project > 0) {
							$error_message = $this->config->item('project_details_page_po_view_create_mark_project_complete_invalid_request');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}
					
					$confirmation_modal_title = $this->config->item('create_mark_project_complete_request_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('create_mark_project_complete_request_confirmation_project_modal_body').'</div>';
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn create_mark_complete_request_po blue_btn default_btn width-auto" disabled style="opacity:0.65">'.$this->config->item('send_btn_txt').'</button>';
					
					$sp_name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) ?$bid_data['first_name'] . ' ' . $bid_data['last_name'] :$bid_data['company_name'];
					
					$confirmation_modal_body = str_replace(array('{user_first_name_last_name_or_company_name}'),array($sp_name),$confirmation_modal_body);
					
					
					$confirmation_modal_body.= '<form class="create_mark_project_compelete_request_form" name="create_mark_project_compelete_request_form"><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="create_mark_complete_request_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$mark_project_complete_request_disclaimer_message.'</span></label></div></div></div>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to make save the created request by po form mark complete the project .
	*/
	public function create_mark_complete_request(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.gender')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$mark_complete_request_tracking_table_name = 'fixed_budget_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$mark_complete_request_tracking_table_name = 'hourly_rate_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
				}
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id,'project_completion_method'=>'outside_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					$error_message = $this->config->item('project_details_page_po_view_po_try_create_mark_project_complete_request_already_accepted_by_sp_message');
					$error_message = str_replace(array('{next_request_sent_time}'),array(secondsToWords(strtotime($next_request_send_date) -time())),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id,'project_completion_method'=>'via_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					$error_message = $this->config->item('project_details_page_po_view_po_try_create_mark_project_complete_request_project_already_completed_via_portal_message');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id])->from($inprogress_bid_table_name)->count_all_results();
				
				$check_already_incomplete_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>$user[0]->user_id])->from($incomplete_bid_table_name)->count_all_results();
				
				if($check_already_in_progress_bid == 0 && $check_already_incomplete_bid ==0){
					$error_message = $this->config->item('project_details_page_po_view_create_mark_project_complete_invalid_request');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($check_already_in_progress_bid != 0){
				 $bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name')
				->from($inprogress_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				if($check_already_incomplete_bid !=0){
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name')
				->from($incomplete_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				
				/* if($in_progress_bid_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('	popup_error_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_create_mark_project_complete_invalid_request')]);
					die;
				} */
				
				if(!empty($bid_data)){
				
					$project_complete_request_detail = $this->Bidding_model->get_latest_project_complete_request_detail(array('winner_id'=>Cryptor::doDecrypt($sp_id),'project_owner_id'=>Cryptor::doDecrypt($po_id),'project_id'=>$project_id),$project_data['project_type']);
					
					if(!empty($project_complete_request_detail)){
						
						if($project_complete_request_detail['request_status'] == 'expired' || $project_complete_request_detail['request_status'] == 'declined' || $project_complete_request_detail['request_status'] == 'active'){
						
							if($project_complete_request_detail['request_status'] == 'declined'){
								$response_time = $project_complete_request_detail['request_declined_on'];
							}
							if($project_complete_request_detail['request_status'] == 'expired'){
								$response_time = $project_complete_request_detail['request_expires_on'];
							}
							if($project_complete_request_detail['request_status'] == 'active'){
								$response_time = $project_complete_request_detail['request_expires_on'];
							
							}
							$time_arr = explode(':', $this->config->item('po_send_mark_project_complete_request_time_left_till_next_resent'));
							$next_request_send_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($response_time)));
							if(strtotime($next_request_send_date) > time()){
								$error_message = $this->config->item('project_details_page_po_view_create_mark_project_complete_request_resent_time_expired');
								$error_message = str_replace(array('{next_mark_project_as_complete_request_send_available_time}'),array(secondsToWords(strtotime($next_request_send_date) -time())),$error_message);
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
								die;
							}
						}
					}

					if($project_data['project_type'] == 'hourly'){
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bid_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone

						if($bid_data['initial_project_agreed_hourly_rate'] == 0 && $paid_milestones_count_project > 0) {
							$error_message = $this->config->item('project_details_page_po_view_create_mark_project_complete_invalid_request');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}

					if($project_data['project_type'] == 'fixed'){
						$mark_project_complete_request_expiration_time_arr = explode(':', $this->config->item('mark_project_complete_request_expiration_time'));
						$mark_complete_request_data['project_id'] = $project_id;
						$mark_complete_request_data['project_owner_id'] = Cryptor::doDecrypt($po_id);
						$mark_complete_request_data['winner_id']= Cryptor::doDecrypt($sp_id) ;
						$mark_complete_request_data['request_sent_on']= date('Y-m-d H:i:s') ;
						$mark_complete_request_data['request_expires_on']= date('Y-m-d H:i:s', strtotime('+'.(int)$mark_project_complete_request_expiration_time_arr[0].' hour +'.(int)$mark_project_complete_request_expiration_time_arr[1].' minutes +'.(int)$mark_project_complete_request_expiration_time_arr[2].' seconds')) ;
						
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) {
							if($project_data['gender'] == 'M') {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_mark_project_complete_request_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_created_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							} else {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_mark_project_complete_request_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_created_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							}
						} else {
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_created_mark_project_complete_request_user_activity_log_displayed_message');
							
						}
						
						$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_created_mark_project_complete_request_user_activity_log_displayed_message');
						
					} else if($project_data['project_type'] == 'hourly') {
						$mark_project_complete_request_expiration_time_arr = explode(':', $this->config->item('mark_project_complete_request_expiration_time'));
						$mark_complete_request_data['project_id'] = $project_id;
						$mark_complete_request_data['project_owner_id'] = Cryptor::doDecrypt($po_id);
						$mark_complete_request_data['winner_id']= Cryptor::doDecrypt($sp_id) ;
						$mark_complete_request_data['request_sent_on']= date('Y-m-d H:i:s') ;
						$mark_complete_request_data['request_expires_on']= date('Y-m-d H:i:s', strtotime('+'.(int)$mark_project_complete_request_expiration_time_arr[0].' hour +'.(int)$mark_project_complete_request_expiration_time_arr[1].' minutes +'.(int)$mark_project_complete_request_expiration_time_arr[2].' seconds')) ;
						
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) {
							if($project_data['gender'] == 'M') {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_po_company_app_male_created_mark_project_complete_request_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_po_male_created_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							} else {
								$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_po_female_created_mark_project_complete_request_user_activity_log_displayed_message');
								
							}
						} else {
							if($project_data['is_authorized_physical_person'] == 'Y'){
								$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_po_company_app_female_created_mark_project_complete_request_user_activity_log_displayed_message');
							}else{
								$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_po_company_created_mark_project_complete_request_user_activity_log_displayed_message');
							}
							
						}
						$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_po_created_mark_project_complete_request_user_activity_log_displayed_message');
					}
					$this->db->insert ($mark_complete_request_tracking_table_name, $mark_complete_request_data);
					$last_insert_id = $this->db->insert_id();
					############# send activity log message to SP/PO
					
					
					 
					 $sp_name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) ?$bid_data['first_name'] . ' ' . $bid_data['last_name'] :$bid_data['company_name'];
					 
					 
					 $sp_profile_url_link = VPATH.$bid_data['profile_name'];
					 $project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					 
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					
					
					
					$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
					
					if($project_data['project_type'] == 'fixed') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) {
						
							$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name}","{project_url_link}","{project_title}"),array($po_profile_url_link,$po_name,$project_url_link,$project_title),$sp_activity_log_message);
							
						} else {
							$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_company_name}","{project_url_link}","{project_title}"),array($po_profile_url_link,$po_name,$project_url_link,$project_title),$sp_activity_log_message);
							
						}

					} else if($project_data['project_type'] == 'hourly') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) { 
							$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name}","{project_url_link}","{project_title}"),array($po_profile_url_link,$po_name,$project_url_link,$project_title),$sp_activity_log_message);
							
						} else {
							$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_company_name}","{project_url_link}","{project_title}"),array($po_profile_url_link,$po_name,$project_url_link,$project_title),$sp_activity_log_message);
							
						}
					}

					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					$mark_complete_project_request_data = $this->db // count the number of record in projects_draft table
					->select ('cr.*')
					->from ($mark_complete_request_tracking_table_name. ' as cr')
					->where('id',$last_insert_id)
					->get ()->row_array ();
					$data['mark_complete_project_request_value'] = $mark_complete_project_request_data;
					$data['sp_id'] = $mark_complete_project_request_data['winner_id'];
					$data['po_id'] = $mark_complete_project_request_data['project_owner_id'];
					
					if($mark_complete_project_request_data['request_status'] == 'declined'){
					$response_time = $mark_complete_project_request_data['request_declined_on'];
					}
					if($mark_complete_project_request_data['request_status'] == 'expired'){
						$response_time = $mark_complete_project_request_data['request_expires_on'];
					}
					if($mark_complete_project_request_data['request_status'] == 'active'){
						$response_time = $mark_complete_project_request_data['request_expires_on'];
					}
					if($project_data['project_type'] == 'fixed') {
						$time_arr = explode(':', $this->config->item('po_send_mark_project_complete_request_time_left_till_next_resent'));
					} else if($project_data['project_type'] == 'hourly') {
						$time_arr = explode(':', $this->config->item('po_send_mark_project_complete_request_time_left_till_next_resent'));
					}
					$next_request_send_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($response_time)));
					
					$next_project_complete_request_sent_msg =  '<div>'.$this->config->item('project_details_page_mark_complete_project_request_listing_time_left_send_next_request_txt_po_view')." ".secondsToWordsResponsive(strtotime($next_request_send_date) -time()).'</div>';
					
					echo json_encode(['status' => 200,'sp_id'=>$mark_complete_project_request_data['winner_id'],'next_project_complete_request_sent_msg'=>$next_project_complete_request_sent_msg,'data'=>$this->load->view('bidding/mark_project_complete_request_row_detail_project_detail',$data, true),'location'=>'']);
					die;
					
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to make the popup of decline project complete request by sp .
	*/
	public function decline_mark_complete_request_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				$row_id = $this->input->post ('row_id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				
				$mark_project_complete_request_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$mark_complete_request_tracking_table_name = 'fixed_budget_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$mark_complete_request_tracking_table_name = 'hourly_rate_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
				} 
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'outside_portal'])->from($completed_bid_table_name)->count_all_results();
				
				
				if($check_already_completed_bid > 0) {
					$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_accepted_by_sp_message');
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'via_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_project_complete_request_project_already_completed_via_portal_message');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
			
				
				$mark_complete_project_request_data = $this->db // count the number of record in projects_draft table
				->select ('cr.*')
				->from ($mark_complete_request_tracking_table_name. ' as cr')
				->where('id',$row_id)
				->get ()->row_array ();
				if($mark_complete_project_request_data['request_status'] == 'declined'){
					$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_declined_by_sp_message');
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				
				$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($inprogress_bid_table_name)->count_all_results();
				
				$check_already_incomplete_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($incomplete_bid_table_name)->count_all_results();
				
				if($check_already_in_progress_bid == 0 &&  $check_already_incomplete_bid == 0){
					$error_message = $this->config->item('project_details_page_sp_view_decline_mark_project_complete_invalid_request');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($check_already_in_progress_bid != 0){
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.company_name')
				->from($inprogress_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				if($check_already_incomplete_bid != 0){
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.company_name')
				->from($incomplete_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				
				if(!empty($bid_data)){

					if($project_data['project_type'] == 'hourly'){
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bid_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone

						if($bid_data['initial_project_agreed_hourly_rate'] == 0 && $paid_milestones_count_project > 0) {
							$error_message = $this->config->item('project_details_page_sp_view_decline_mark_project_complete_invalid_request');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}
					
					$confirmation_modal_title = $this->config->item('decline_mark_project_complete_request_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('decline_mark_project_complete_request_confirmation_project_modal_body').'</div>';
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn decline_mark_complete_request_sp blue_btn default_btn width-auto" disabled style="opacity:0.65">'.$this->config->item('decline_btn_txt').'</button>';
					
					$po_name = $project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$confirmation_modal_body = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$confirmation_modal_body);
					
					$confirmation_modal_body.= '<form class="decline_mark_project_compelete_request_form" name="decline_mark_project_compelete_request_form"><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$row_id.'" id="row_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="decline_mark_complete_request_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$mark_project_complete_request_disclaimer_message.'</span></label></div></div></div>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}

	/**
	* This function is used to decline the mark complete request by sp .
	*/
	public function decline_mark_complete_request(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				$row_id = $this->input->post ('row_id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				
				
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$mark_complete_request_tracking_table_name = 'fixed_budget_projects_manual_completed_requests_tracking';
					$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_declined_mark_project_complete_request_user_activity_log_displayed_message');
					/* $po_activity_log_message = $this->config->item('fixed_project_message_sent_to_po_when_decline_mark_project_complete_request_user_activity_log_displayed_message');
					$po_message = $this->config->item('fixed_project_notification_message_sent_to_po_when_decline_mark_project_complete_request'); */
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
					$mark_complete_request_tracking_table_name = 'hourly_rate_projects_manual_completed_requests_tracking';
					$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_sp_declined_mark_project_complete_request_user_activity_log_displayed_message');
				} else if($project_data['project_type'] == 'fulltime'){
					$inprogress_bid_table_name = '';
				}
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'outside_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						//$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_fulltime_project_complete_request_already_accepted_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_project_complete_request_project_already_completed_via_portal_message');
					}
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
			
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'via_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_fulltime_project_complete_request_already_accepted_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_accepted_by_sp_message');
					}
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;

				}
			
				
				$mark_complete_project_request_data = $this->db // count the number of record in projects_draft table
				->select ('cr.*')
				->from ($mark_complete_request_tracking_table_name. ' as cr')
				->where('id',$row_id)
				->get ()->row_array ();
				if($mark_complete_project_request_data['request_status'] == 'declined'){
				
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_fulltime_project_complete_request_already_declined_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_decline_mark_project_complete_request_already_declined_by_sp_message');
					}
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($inprogress_bid_table_name)->count_all_results();
				$check_already_incomplete_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($incomplete_bid_table_name)->count_all_results();
				
				if($check_already_in_progress_bid == 0 && $check_already_incomplete_bid==0 ){
					$error_message = $this->config->item('project_details_page_sp_view_decline_mark_project_complete_invalid_request');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($check_already_in_progress_bid != 0){ 
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name, u.gender')
				->from($inprogress_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				if($check_already_incomplete_bid != 0){ 
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name, u.gender')
				->from($incomplete_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				/* if($in_progress_bid_data['winner_id'] != $user[0]->user_id){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('	popup_error_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_decline_mark_project_complete_invalid_request')]);
					die;
				} */
				
				if(!empty($bid_data)){

					if($project_data['project_type'] == 'hourly'){
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bid_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone

						if($bid_data['initial_project_agreed_hourly_rate'] == 0 && $paid_milestones_count_project > 0) {
							$error_message = $this->config->item('project_details_page_sp_view_decline_mark_project_complete_invalid_request');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}
				
					$this->db->where ('id', $row_id);
					$this->db->update($mark_complete_request_tracking_table_name,array('request_declined_on'=>date('Y-m-d H:i:s'),'request_status'=>'declined')); 
					############# send activity log message to SP/PO

					$sp_name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) ?$bid_data['first_name'] . ' ' . $bid_data['last_name'] :$bid_data['company_name'];
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];

					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					$sp_profile_url_link = VPATH.$bid_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);

					if($project_data['project_type'] == 'fixed') {
					
							if(($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) {
								if($bid_data['gender'] == 'M') {
									if($bid_data['is_authorized_physical_person'] == 'Y'){
										$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_declined_mark_project_complete_request_user_activity_log_displayed_message');
									}else{
										$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_declined_mark_project_complete_request_user_activity_log_displayed_message');
									}
									
									
								} else {
									if($bid_data['is_authorized_physical_person'] == 'Y'){
										$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_declined_mark_project_complete_request_user_activity_log_displayed_message');
									}else{
										$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_declined_mark_project_complete_request_user_activity_log_displayed_message');
									}
									
								}
								
								$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
								
								
								
							}else{
							
								$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_declined_mark_project_complete_request_user_activity_log_displayed_message');
								$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_company_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
								
							}
						
					} else if($project_data['project_type'] == 'hourly') {
						if(($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) {
							if($bid_data['gender'] == 'M') {
							
								if($bid_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_male_declined_mark_project_complete_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_male_declined_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							} else {
								if($bid_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_female_declined_mark_project_complete_request_user_activity_log_displayed_message');
								}else{
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_female_declined_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							}
							$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
							
						} else {
							$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_declined_mark_project_complete_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_company_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
						}

					} 

					$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_url_link}","{project_title}"),array($po_profile_url_link,$po_name,$project_url_link,$project_title),$sp_activity_log_message);
					
					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner
					
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider

					$mark_complete_project_request_data = $this->db // count the number of record in projects_draft table
					->select ('cr.*')
					->from ($mark_complete_request_tracking_table_name. ' as cr')
					->where('id',$row_id)
					->get ()->row_array ();
					$data['mark_complete_project_request_value'] = $mark_complete_project_request_data;
					$data['sp_id'] = $mark_complete_project_request_data['winner_id'];
					$data['po_id'] = $mark_complete_project_request_data['project_owner_id'];
					$data['mode'] = 'decline';
					echo json_encode(['status' => 200,'po_id'=>$mark_complete_project_request_data['project_owner_id'],'row_id'=>$row_id,'data'=>$this->load->view('bidding/mark_project_complete_request_row_detail_project_detail',$data, true),'location'=>'']);
					die;
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page 
		}
	}
	
	
	/**
	* This function is used to make the popup of accept project complete request by sp .
	*/
	public function accept_mark_complete_request_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 

				$row_id = $this->input->post ('row_id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				
				
				$mark_project_complete_request_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$mark_complete_request_tracking_table_name = 'fixed_budget_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$mark_complete_request_tracking_table_name = 'hourly_rate_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
				} 
				
				 $check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'via_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						//$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_accepted_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_project_already_completed_via_portal_message');
					}
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'outside_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						//$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_accepted_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_accepted_by_sp_message');
					}
					
					
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				$mark_complete_project_request_data = $this->db 
				->select ('cr.*')
				->from ($mark_complete_request_tracking_table_name. ' as cr')
				->where('id',$row_id)
				->get ()->row_array ();
				
				if($mark_complete_project_request_data['request_status'] == 'declined'){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_declined_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_declined_by_sp_message');
					}
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if(!empty($mark_complete_project_request_data) && strtotime($mark_complete_project_request_data['request_expires_on']) < time()){
				
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_expired_message');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_expired_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($inprogress_bid_table_name)->count_all_results();
				
				$check_already_incomplete_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($incomplete_bid_table_name)->count_all_results();
				
				
				
				if($check_already_in_progress_bid == 0 && $check_already_incomplete_bid ==0){
					$error_message = $this->config->item('project_details_page_sp_view_accept_mark_project_complete_invalid_request');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				if($check_already_in_progress_bid != 0){
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.company_name')
				->from($inprogress_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				if($check_already_incomplete_bid !=0){
				$bid_data = $this->db // get the user detail
				->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.company_name')
				->from($incomplete_bid_table_name.' bd')
				->join('users u', 'u.user_id = bd.winner_id', 'left')
				->where('bd.project_id', $project_id)
				->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				}
				/* if($in_progress_bid_data['winner_id'] != $user[0]->user_id){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('	popup_error_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_accept_mark_project_complete_invalid_request')]);
					die;
				} */
				
				
				if(!empty($bid_data)){
				
					if($project_data['project_type'] == 'hourly'){
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bid_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone

						if($bid_data['initial_project_agreed_hourly_rate'] == 0 && $paid_milestones_count_project > 0) {
							$error_message = $this->config->item('project_details_page_sp_view_accept_mark_project_complete_invalid_request');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
						
					}


					
					$confirmation_modal_title = $this->config->item('accept_mark_project_complete_request_confirmation_project_modal_title');
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('accept_mark_project_complete_request_confirmation_project_modal_body').'</div>';
					
					$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn accept_mark_complete_request_sp blue_btn default_btn width-auto" disabled style="opacity:0.65">'.$this->config->item('accept_btn_txt').'</button>';
					
					$po_name = $project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$confirmation_modal_body = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$confirmation_modal_body);
					
					
					$confirmation_modal_body.= '<form class="accept_mark_project_compelete_request_form" name="accept_mark_project_compelete_request_form"><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$row_id.'" id="row_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="accept_mark_complete_request_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$mark_project_complete_request_disclaimer_message.'</span></label></div></div></div>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
					
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to accept the mark complete request by sp when accept the request poject is completed manually(if only single sp is working otherwise only dedicated sp bid is completed manually not entire project)  .
	*/
	public function accept_mark_complete_request(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 

				$row_id = $this->input->post ('row_id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				$project_data = $this->db // get the user detail
				->select('pd.*,u.profile_name,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				
				
				
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$mark_complete_request_tracking_table_name = 'fixed_budget_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$completed_project_table = 'fixed_budget_projects_completed';
					
					
					$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_accepted_mark_project_complete_request_user_activity_log_displayed_message');
					
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$mark_complete_request_tracking_table_name = 'hourly_rate_projects_manual_completed_requests_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$completed_project_table = 'hourly_rate_based_projects_completed';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';

					$sp_activity_log_message = $this->config->item('hourly_project_message_sent_to_sp_when_sp_accepted_mark_project_complete_request_user_activity_log_displayed_message');
					
				} else if($project_data['project_type'] == 'fulltime'){
					$inprogress_bid_table_name = '';
					
				}
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'via_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						//$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_accepted_by_sp_message');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_project_already_completed_via_portal_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				$check_already_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id),'project_completion_method'=>'outside_portal'])->from($completed_bid_table_name)->count_all_results();
				if($check_already_completed_bid > 0){
					if($project_data['project_type'] == 'fulltime'){
						//$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_accepted_by_sp_message');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_accepted_by_sp_message');
					}
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				$mark_complete_project_request_data = $this->db 
				->select ('cr.*')
				->from ($mark_complete_request_tracking_table_name. ' as cr')
				->where('id',$row_id)
				->get ()->row_array ();
				
				if($mark_complete_project_request_data['request_status'] == 'declined'){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_declined_by_sp_message');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_declined_by_sp_message');
					}
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$error_message = str_replace(array('{user_first_name_last_name_or_company_name}'),array($po_name),$error_message);
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				
				if(!empty($mark_complete_project_request_data) && strtotime($mark_complete_project_request_data['request_expires_on']) < time()){
				
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_fulltime_project_complete_request_already_expired_message');
						}else{
						$error_message = $this->config->item('project_details_page_sp_view_sp_try_accept_mark_project_complete_request_already_expired_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				$check_already_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($inprogress_bid_table_name)->count_all_results();
				
				$check_already_incomplete_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($incomplete_bid_table_name)->count_all_results();
				
				if($check_already_in_progress_bid == 0 && $check_already_incomplete_bid ==0){
					$error_message = $this->config->item('project_details_page_sp_view_accept_mark_project_complete_invalid_request');
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($check_already_in_progress_bid != 0){
					//in_progress_bid_data
					$bid_data = $this->db // get the user detail
					->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name,u.gender')
					->from($inprogress_bid_table_name.' bd')
					->join('users u', 'u.user_id = bd.winner_id', 'left')
					->where('bd.project_id', $project_id)
					->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
				}
				if($check_already_incomplete_bid != 0){
					$bid_data = $this->db // get the user detail
					->select('bd.*,u.profile_name,u.first_name,u.last_name,u.account_type,u.is_authorized_physical_person,u.company_name,u.gender')
					->from($incomplete_bid_table_name.' bd')
					->join('users u', 'u.user_id = bd.winner_id', 'left')
					->where('bd.project_id', $project_id)
					->where('bd.winner_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
				}
				
				
				$profile_name = $project_data['profile_name'];
				
				$get_completed_bid_data = $this->db->get_where($completed_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->row_array();
				
				/* if($in_progress_bid_data['winner_id'] != $user[0]->user_id){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('	popup_error_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_decline_mark_project_complete_invalid_request')]);
					die;
				} */
				$is_project_status_change = '0';
				if(!empty($bid_data)){

					if($project_data['project_type'] == 'hourly'){
						$paid_milestones_count_project = get_released_escrows_count_project($project_data['project_type'],array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bid_data['winner_id'], 'is_partial_released' => 'N')); // count the active milestone

						if($bid_data['initial_project_agreed_hourly_rate'] == 0 && $paid_milestones_count_project > 0) {
							$error_message = $this->config->item('project_details_page_sp_view_accept_mark_project_complete_invalid_request');
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}
					
					
					
					if($project_data['project_type'] == 'fixed'){ 
						if(($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) {
							if($bid_data['gender'] == 'M') {
								
								if($bid_data['is_authorized_physical_person'] == 'Y'){
									
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_accepted_mark_project_complete_request_user_activity_log_displayed_message');
									
								}else{
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_accepted_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							} else {
								if($bid_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_accepted_mark_project_complete_request_user_activity_log_displayed_message');	
								}else{
								
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_accepted_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							}
						} else {
								
								$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_accepted_mark_project_complete_request_user_activity_log_displayed_message');
							
							
						}
					}
					
					if($project_data['project_type'] == 'hourly'){ 
						if(($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) {
							if($bid_data['gender'] == 'M') {
								if($bid_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_male_accepted_mark_project_complete_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_male_accepted_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							} else {
								if($bid_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_app_female_accepted_mark_project_complete_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_female_accepted_mark_project_complete_request_user_activity_log_displayed_message');
								}
								
							}
						} else {
							
							$po_activity_log_message = $this->config->item('hourly_project_message_sent_to_po_when_sp_company_accepted_mark_project_complete_request_user_activity_log_displayed_message');
							
						}
					}
					
					
					$project_status = $this->config->item('project_status_in_progress');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->load->library('ftp');
					$this->ftp->connect($config); 
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
					$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
					$project_completed_dir = PROJECT_COMPLETED_DIR;
					$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
					
					$project_completion_date = date('Y-m-d H:i:s');
					$this->db->where ('id', $row_id);
					$this->db->update($mark_complete_request_tracking_table_name,array('request_accepted_on'=>date('Y-m-d H:i:s'),'request_status'=>'accepted')); 
					############# send activity log message to SP/PO
					
					
					$sp_name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y'))?$bid_data['first_name'] . ' ' . $bid_data['last_name'] :$bid_data['company_name'];
					
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y'))?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					
				
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					$sp_profile_url_link = VPATH.$bid_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{user_first_name_last_name_or_company_name}","{project_url_link}","{project_title}"),array($po_profile_url_link,$po_name,$project_url_link,$project_title),$sp_activity_log_message);
					


					if($project_data['project_type'] == 'fixed') {
					
						if(($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) {
							
							$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
						}else{
						
							$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_company_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
						}
					} else if($project_data['project_type'] == 'hourly') {
						if(($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) {
							$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_first_name_last_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
							
						} else {
							$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{user_company_name}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$sp_name,$project_url_link,$project_title),$po_activity_log_message);
							
						}
						
					}

					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner
					
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					
					################# start code for complete the bid here#####
					
					//$is_bid_completed = '1';
					$remove_id = $bid_data['id'];
					$total_paid_milestones = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					if($project_data['project_type'] == 'fixed'){
						$completed_bid_data['project_id'] = $bid_data['project_id'];
						$completed_bid_data['winner_id'] = $bid_data['winner_id'];
						$completed_bid_data['project_owner_id'] = $bid_data['project_owner_id'];
						$completed_bid_data['initial_bid_description'] = $bid_data['initial_bid_description'];
						$completed_bid_data['bidding_dropdown_option'] = $bid_data['bidding_dropdown_option'];
						$completed_bid_data['initial_project_agreed_value'] = $bid_data['initial_project_agreed_value'];
						$completed_bid_data['initial_bid_value'] = $bid_data['initial_bid_value'];
						
						$completed_bid_data['project_winner_work_start_date'] = $bid_data['project_start_date'];
						$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
						$completed_bid_data['total_project_amount'] = $total_paid_milestones;
						$completed_bid_data['project_completion_method'] = 'outside_portal';
					} else if($project_data['project_type'] == 'hourly') {
						$completed_bid_data['project_id'] = $bid_data['project_id'];
						$completed_bid_data['winner_id'] = $bid_data['winner_id'];
						$completed_bid_data['project_owner_id'] = $bid_data['project_owner_id'];
						$completed_bid_data['initial_bid_description'] = $bid_data['initial_bid_description'];
						$completed_bid_data['bidding_dropdown_option'] = $bid_data['bidding_dropdown_option'];
						$completed_bid_data['initial_project_agreed_value'] = $bid_data['initial_project_agreed_value'];
						$completed_bid_data['initial_project_agreed_number_of_hours'] = $bid_data['initial_project_agreed_number_of_hours'];
						$completed_bid_data['initial_project_agreed_hourly_rate'] = $bid_data['initial_project_agreed_hourly_rate'];
						
						$completed_bid_data['project_winner_work_start_date'] = $bid_data['project_start_date'];
						$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
						$completed_bid_data['total_project_amount'] = $total_paid_milestones;
						$completed_bid_data['project_completion_method'] = 'outside_portal';
					}
					if(empty($get_completed_bid_data)){
						$this->db->insert ($completed_bid_table_name, $completed_bid_data);// move data to completed internally tracking
						$this->db->delete($inprogress_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
						$this->db->delete($incomplete_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
						
						if($check_already_in_progress_bid != 0){
						
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_data['winner_id'];
						}
						if($check_already_incomplete_bid != 0){
						
							$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_data['winner_id'];
						}
						
						$this->db->delete('projects_active_bids_users_attachments_tracking', ['user_id' => $bid_data['winner_id']]);
						
						
						/* if(!empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))) {
							$this->ftp->delete_dir($bid_attachment_directory_path);
						} */
						
						$completed_bidder_data = $this->db // get the user detail
						->select('u.user_id,u.account_type,u.is_authorized_physical_person,u.gender,u.first_name,u.last_name,u.company_name,u.profile_name,ud.user_avatar,ud.project_user_total_avg_rating_as_sp,cb.*')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_fixed_budget_projects')
						->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id ) as sp_total_completed_hourly_based_projects')
						->from($completed_bid_table_name. ' cb')
						->join('users u', 'u.user_id = cb.winner_id', 'left')
						->join('users_details ud', 'ud.user_id = u.user_id', 'left')
						->where('project_id', $completed_bid_data['project_id'])
						->where('winner_id', $completed_bid_data['winner_id'])
						->where('cb.project_owner_id', $completed_bid_data['project_owner_id'])
						->get()->row_array();
						$common_source_path = USERS_FTP_DIR . $completed_bidder_data['profile_name'];

						//avatar picture
						//start check avatar from ftp server
						$user_avatar = USER_AVATAR;
						$source_path_avatar = $common_source_path . $user_avatar;
						$avatarlist = $this->ftp->list_files($source_path_avatar);
						$avatar_pic = $source_path_avatar . $completed_bidder_data['user_avatar'];

						$exap = explode('.', $completed_bidder_data['user_avatar']);
						$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';

						if (count($avatarlist) > 0) {
							$acheck = true;
							if (!in_array($avatar_pic, $avatarlist) && $acheck) {
								$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $completed_bidder_data['user_id']));
								$this->ftp->delete_dir($source_path_avatar);
								$completed_bidder_data['user_avatar'] = '';
								$acheck = false;
							} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
								$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$completed_bidder_data['user_id']));
								$this->ftp->delete_dir($source_path_avatar);
								$completed_bidder_data['user_avatar'] = '';
								$acheck = false;
							}
						} if (count($avatarlist) == 0 && $completed_bidder_data['user_avatar'] != '') {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $completed_bidder_data['user_id']));
							$completed_bidder_data['user_avatar'] = '';
						}
					}
					$count_in_progress_bids = $this->db->where(['project_id' => $project_id])->from($inprogress_bid_table_name)->count_all_results();
					
					$count_incomplete_bids = $this->db->where(['project_id' => $project_id])->from($incomplete_bid_table_name)->count_all_results();
					
					
					
					if($count_in_progress_bids ==0 && $count_incomplete_bids == 0){
						$is_project_status_change = '1';
						$project_status = $this->config->item('project_status_completed');
						$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]); 
						$project_completed_table_data_exists = $this->db->where(['project_id' => $project_id])->from($completed_project_table)->count_all_results();
						if($project_completed_table_data_exists == 0){
							
							unset($project_data['profile_name']);
							unset($project_data['id']);
							unset($project_data['gender']);
							unset($project_data['first_name']);
							unset($project_data['last_name']);
							unset($project_data['company_name']);
							unset($project_data['account_type']);
							unset($project_data['is_authorized_physical_person']);
							$project_data['project_completion_date'] = $project_completion_date;
							$this->db->insert ($completed_project_table, $project_data);// 
							
							###Start######## Project is manually completed so project attachment and bid attachment deleted from disk and database ####
							$this->db->delete('projects_attachments', ['project_id' => $project_id]);
							$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);
							
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
							}
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id);
							}
							############ End ###############
							
							
							
							//$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
						
							//$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							//$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
							//$project_completed_dir = PROJECT_COMPLETED_DIR;
							//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
							
							//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir, 0777);// create awaiting_moderation directory in projects folder
							//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id , 0777); // create the directory by using  project id
							//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
							
							/* $source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
							
							$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR; */
							
							/* $source_list = $this->ftp->list_files($source_path);
							if(!empty($source_list)) {
								foreach($source_list as $path) {
									$arr = explode('/', $path);
									$file_size = $this->ftp->get_filesize($path);
									if($file_size != '-1') {
										$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir.end($arr);
										$this->ftp->move($path, $destination_path);
									}
								}
							}
							
							$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
							
							
							if(!empty($bid_attachments)){
								foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
									$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
									if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
										$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
										
										$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
										$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										
										$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
										if($file_size != '-1')
										{
											
											$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
											$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
											
										}
									}
								}
							}
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
							} */
							
							
						}
					}
					############### End code for complete the bid/project here #####
					$this->ftp->close();
					
					$project_completion_date_container_html = '<span class="default_black_bold"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->config->item('project_details_page_completed_on').'</span>'.$project_completion_date;
					
					//if($is_bid_completed == '1' ){
					
						$completed_bidder_attachment_list = $this->db // get the user detail
						->select('id,bid_attachment_name,user_id')
						->from('projects_active_bids_users_attachments_tracking')
						->where('project_id', $in_progress_bid_data['project_id'])
						->where('user_id', $in_progress_bid_data['winner_id'])
						->order_by('id DESC')
						->get()->result_array();
							
						$completed_bidder_data['bid_attachments'] = $completed_bidder_attachment_list;
						echo json_encode(['status' => 200,'po_id'=>$completed_bid_data['project_owner_id'],'sp_project_status'=>$this->config->item('project_details_page_marked_completed_project_tab_sp_view_txt'),'remove_id'=>$remove_id,'is_project_status_change'=>$is_project_status_change,
						'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status'=>$project_status,'data'=>$this->load->view('bidding/project_completed_bidders_listing',array('project_data'=>$project_data,'completed_bidder_data'=>$completed_bidder_data), true)]);
						die;
					
					
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page 
		}
	}
	
}
?>