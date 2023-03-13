<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Projects_disputes extends MX_Controller
{
    public function __construct ()
    {
        /* $this->load->helper ('recaptcha'); */
       $this->load->library ('pagination');
       $this->load->model ('Projects_disputes_model');
       $this->load->model('projects/Projects_model');
	   $this->load->model ('user_projects_payments_overview/User_projects_payments_overview_model');
	   $this->load->model('escrow/Escrow_model');
	   $this->load->model('user/User_model');
        parent::__construct ();
        
    }

	/**
	* This function is used to make to check generate project dispute reference id .
	*/
	public function generate_project_dispute_reference_id(){
	
		if($this->input->is_ajax_request ()){
			
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				if($post_data['project_type'] == 'fixed'){
					$active_disputes_table_name ='fixed_budget_projects_active_disputes';
					$closed_disputes_table_name ='fixed_budget_projects_closed_disputes';
					$reference_id = FIXED_BUDGET_PROJECT_DISPUTE_REFERENCE_ID_PREFIX;
				}
				if($post_data['project_type'] == 'hourly'){
					$active_disputes_table_name ='hourly_rate_based_projects_active_disputes';
					$closed_disputes_table_name ='hourly_rate_based_projects_closed_disputes';
					$reference_id = HOURLY_RATE_BASED_PROJECT_DISPUTE_REFERENCE_ID_PREFIX;
				}
				if($post_data['project_type'] == 'fulltime'){
					$active_disputes_table_name ='fulltime_projects_active_disputes';
					$closed_disputes_table_name ='fulltime_projects_closed_disputes';
					$reference_id = FULLTIME_PROJECT_DISPUTE_REFERENCE_ID_PREFIX;
				}

				$latest_projects_temp_disputes_data = $this->db->from('temp_projects_active_disputes')->where('temp_dispute_reference_id REGEXP', $reference_id. '[0-9]')->where('project_type',$post_data['project_type'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				$latest_projects_active_disputes_data = $this->db->from($active_disputes_table_name)->where('dispute_reference_id REGEXP', $reference_id. '[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
				$latest_projects_closed_disputes_data = $this->db->from($closed_disputes_table_name)->where('dispute_reference_id REGEXP', $reference_id. '[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
				
				$digits = $this->config->item('reference_id_digits_limit');
				if(empty($latest_projects_temp_disputes_data) && empty($latest_projects_active_disputes_data) && empty($latest_projects_closed_disputes_data)){
					$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					
				}else{
					if(!empty($latest_projects_temp_disputes_data)){
						$exclude_inital_number = str_replace($reference_id, '', $latest_projects_temp_disputes_data['temp_dispute_reference_id']);
						$x = ltrim($exclude_inital_number, '0');
					}else{
						$x = 0;
					}
					if(!empty($latest_projects_active_disputes_data)){
						$exclude_inital_number = str_replace($reference_id, '', $latest_projects_active_disputes_data['dispute_reference_id']);
						$y = ltrim($exclude_inital_number, '0');
					}else{
						$y = 0;
					}
					if(!empty($latest_projects_closed_disputes_data)){
						$exclude_inital_number = str_replace($reference_id, '', $latest_projects_closed_disputes_data['dispute_reference_id']);
						$z = ltrim($exclude_inital_number, '0');
					}else{
						$z = 0;
					}
					
					/* echo "<pre>";
					print_r($latest_projects_active_disputes_data); */
					/* 
					echo "<pre>";
					print_r($latest_projects_closed_disputes_data);
					
					"X:".$x."Y:".$y."Z:".$z;
					
					die("hiii"); */
					
					$exclude_inital_number = max($x, $y,$z);
					$exclude_inital_number = (int)$exclude_inital_number + 1;
					$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
				}
				if($post_data['initiator_id'] == $post_data['po_id']){
					$dispute_initiated_by = $post_data['po_id'];
					$disputed_against_user_id = $post_data['sp_id'];
				
				}if($post_data['initiator_id'] == $post_data['sp_id']){
					$dispute_initiated_by = $post_data['sp_id'];
					$disputed_against_user_id = $post_data['po_id'];
				}
				
				$project_active_dispute_data['temp_dispute_reference_id'] = $reference_id;
				$project_active_dispute_data['temp_disputed_project_id'] = $post_data['project_id'];
				$project_active_dispute_data['project_type'] = $post_data['project_type'];
				$project_active_dispute_data['temp_dispute_initiated_by_user_id'] = $dispute_initiated_by;
				$project_active_dispute_data['temp_disputed_against_user_id'] = $disputed_against_user_id;
				
				if($this->db->insert ('temp_projects_active_disputes', $project_active_dispute_data)){
					$this->session->set_userdata ('check_project_dispute_redirection', 1);
					echo json_encode(['status' => 200,'location'=>VPATH . $this->config->item('project_dispute_details_page_url').'?id='.$reference_id]);
					die;
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	public function project_dispute_details() 
    { 
		if(check_session_validity()) {
			$user = $this->session->userdata ('user');
			$data['current_page'] = 'project_dispute_details';
			$dispute_ref_id = $this->input->get('id');
			$latest_projects_temp_disputes_data = $this->db->get_where('temp_projects_active_disputes', ['temp_dispute_reference_id' => $dispute_ref_id])->row_array();
			
			
			if(!empty($latest_projects_temp_disputes_data )){
				//die("gdfgfgfdfffffffffffffffffg");
				$disputed_initiated_status = 0;
				if(! $this->session->userdata('check_project_dispute_redirection')){
				//if(0){
					$this->load->library('user_agent');
					$url = $project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$latest_projects_temp_disputes_data['temp_disputed_project_id'];
					$url =  VPATH.$this->config->item('dashboard_page_url');
					
					$result_array = $this->db // get the user detail
					->select('*')
					->from('temp_disputes_projects_attachments')
					->where('temp_dispute_reference_id', $dispute_ref_id)
					->get()->result_array();
					if(!empty($result_array)){
						foreach($result_array as $key=>$value){
							if(file_exists(TEMP_DIR.$value['temp_dispute_attachment_name'])){
								unlink(TEMP_DIR.$value['temp_dispute_attachment_name']);
							}
						}
						$this->db->delete('temp_disputes_projects_attachments', array('temp_dispute_reference_id' => $dispute_ref_id));
					}
					$this->db->delete('temp_projects_active_disputes', array('temp_dispute_reference_id' => $dispute_ref_id));
					redirect ($url);
				}
				$data['project_id'] = $latest_projects_temp_disputes_data['temp_disputed_project_id'];
				$data['dispute_ref_id'] = $latest_projects_temp_disputes_data['temp_dispute_reference_id'];
				$data['project_type'] = $latest_projects_temp_disputes_data['project_type'];
				$data['dispute_initiated_by'] = $latest_projects_temp_disputes_data['temp_dispute_initiated_by_user_id'];
				$data['disputed_against_user_id'] = $latest_projects_temp_disputes_data['temp_disputed_against_user_id'];
				$data['dispute_messages']	= array();
				$data['projects_disputes_data'] = array();
				$data['latest_projects_dispute_message_data'] = array();
				$project_status_table_array = $this->Projects_model->get_project_status_type($latest_projects_temp_disputes_data['temp_disputed_project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $latest_projects_temp_disputes_data['temp_disputed_project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $latest_projects_temp_disputes_data['temp_disputed_project_id'])
					->get()->row_array();
				}
				
				
				if($project_data['project_owner_id'] == $latest_projects_temp_disputes_data['temp_dispute_initiated_by_user_id']){
					$po_id = $latest_projects_temp_disputes_data['temp_dispute_initiated_by_user_id'];
					$sp_id = $latest_projects_temp_disputes_data['temp_disputed_against_user_id'];
					
				}else if($project_data['project_owner_id'] == $latest_projects_temp_disputes_data['temp_disputed_against_user_id']){
					$po_id = $latest_projects_temp_disputes_data['temp_disputed_against_user_id'];
					$sp_id = $latest_projects_temp_disputes_data['temp_dispute_initiated_by_user_id'];
				}
				
				
				if($latest_projects_temp_disputes_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$active_escrow_table = 'fixed_budget_projects_active_escrows';
					$conditions = array('project_id'=>$latest_projects_temp_disputes_data['temp_disputed_project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
				}
				if($latest_projects_temp_disputes_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
					$conditions = array('project_id'=>$latest_projects_temp_disputes_data['temp_disputed_project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
				}
				if($latest_projects_temp_disputes_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$active_escrow_table = 'fulltime_projects_active_escrows';
					$conditions = array('fulltime_project_id'=>$latest_projects_temp_disputes_data['temp_disputed_project_id'],'employer_id'=>$po_id,'employee_id'=>$sp_id);
				}
				
				$this->db->select('SUM(created_escrow_amount) as sum_escrow_amount_value');
				$this->db->from($active_escrow_table);
				$this->db->where($conditions);
				$sum_active_escrow_result = $this->db->get();
				$sum_active_escrow_result = $sum_active_escrow_result->row_array();
			
				
				$dispute_initiated_by_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$latest_projects_temp_disputes_data['temp_dispute_initiated_by_user_id'])->get()->row_array();
				
				$disputed_against_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$latest_projects_temp_disputes_data['temp_disputed_against_user_id'])->get()->row_array();
				
				$dispute_initiated_by_user_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] : $dispute_initiated_by_user_data['company_name'];
				
				$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
				
				$data['dispute_initiated_by_user_name'] = $dispute_initiated_by_user_name;
				$data['disputed_against_user_name'] = $disputed_against_user_name;
				
				$data['project_dispute_stage'] = $this->config->item('project_dispute_details_page_dispute_initiation_phase');
				$data['disputed_amount'] = $sum_active_escrow_result['sum_escrow_amount_value'];
				$projects_disputed_escrows_history_listing = $this->Projects_disputes_model->get_disputed_project_escrows_history_listing($latest_projects_temp_disputes_data['project_type'],$conditions,array('dispute_initiate_status'=>0));
				
				//$data['sum_disputed_escrows_amount_project'] = $sum_disputed_escrows_amount_project;
				$data['projects_disputed_escrows_history_listing'] = $projects_disputed_escrows_history_listing;
				$data['total_counter_offers']  = 0;
				
			}else{
				
				$disputed_initiated_status = 1;
				$latest_projects_dispute_message_data = $this->db->select('*')->from('projects_disputes_messages_activity_tracking')->where('dispute_reference_id',$dispute_ref_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				if($latest_projects_dispute_message_data['message_sent_by_user_id'] != $user[0]->user_id && $latest_projects_dispute_message_data['message_sent_to_user_id'] != $user[0]->user_id){
					
					$url =  VPATH.$this->config->item('dashboard_page_url');
					redirect($url);
				}	
				
				
				if($latest_projects_dispute_message_data['project_type'] == 'fixed'){
					$dispute_active_table = 'fixed_budget_projects_active_disputes';
					$dispute_closed_table = 'fixed_budget_projects_closed_disputes';
					$active_counter_offer_table = 'fixed_budget_projects_active_disputes_counter_offers';
					$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				}
				if($latest_projects_dispute_message_data['project_type'] == 'hourly'){
					$dispute_active_table = 'hourly_rate_based_projects_active_disputes';
					$dispute_closed_table = 'hourly_rate_based_projects_closed_disputes';
					$active_counter_offer_table = 'hourly_rate_based_projects_active_disputes_counter_offers';
					
					$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				}
				if($latest_projects_dispute_message_data['project_type'] == 'fulltime'){
					$dispute_active_table = 'fulltime_projects_active_disputes';
					$dispute_closed_table = 'fulltime_projects_closed_disputes';
					$active_counter_offer_table = 'fulltime_projects_active_disputes_counter_offers';
					$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				}
				$data['project_type'] = $latest_projects_dispute_message_data['project_type'];
				
				$projects_active_disputes_data = $this->db->get_where($dispute_active_table, ['dispute_reference_id' => $dispute_ref_id])->row_array();
				
				if(!empty($projects_active_disputes_data) && $projects_active_disputes_data['disputed_amount'] < $minimum_required_disputed_project_value_for_admin_arbitration && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time()){
					
					######## Automatic decission ############# start ####
					$params['dispute_reference_id'] = $dispute_ref_id;
					$params['project_type'] = $latest_projects_dispute_message_data['project_type'];
					$this->Projects_disputes_model->close_automatically_arbitrated_dispute($params);
					######## Automatic decission ############# end ####
				}
				if(!empty($projects_active_disputes_data) && $projects_active_disputes_data['disputed_amount'] >= $minimum_required_disputed_project_value_for_admin_arbitration && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time()){
					
					######## update status under admin review############# start ####
					$this->db->update($dispute_active_table, ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$dispute_ref_id]);
					
					$this->db->update('projects_disputes_attachments', ['dispute_status'=>'under_admin_review'], ['dispute_reference_id'=>$dispute_ref_id]);
					
					###### calculate the  projects disputes admin arbitration fees tracking
					$params['dispute_reference_id'] = $dispute_ref_id;
					$params['project_type'] = $latest_projects_dispute_message_data['project_type'];
					$this->Projects_disputes_model->save_projects_disputes_admin_arbitration_fees($params);
					
					
					######## update status under admin review############# end ####
				}
				
				
				
				$projects_active_disputes_data = $this->db->get_where($dispute_active_table, ['dispute_reference_id' => $dispute_ref_id])->row_array();
				$projects_closed_disputes_data = $this->db->get_where($dispute_closed_table, ['dispute_reference_id' => $dispute_ref_id])->row_array();
				$project_dispute_status = 0;
				if(!empty($projects_active_disputes_data)){
					if($latest_projects_dispute_message_data['project_type'] == 'fulltime'){
						$data['project_id'] = $projects_active_disputes_data['disputed_fulltime_project_id'];	
					}else{	
						$data['project_id'] = $projects_active_disputes_data['disputed_project_id'];
					}
					if($projects_active_disputes_data['dispute_status'] == 'active' && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) > time()){
						$data['project_dispute_stage'] = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase');
						$project_dispute_status = 1;
					}
					if($projects_active_disputes_data['dispute_status'] == 'under_admin_review' && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) <= time()){
						$data['project_dispute_stage'] = $this->config->item('project_dispute_details_page_dispute_under_admin_review_phase');
						$project_dispute_status = 1;
						
						
					}
					if($projects_active_disputes_data['dispute_status'] == 'under_admin_review'){
						$data['project_dispute_stage'] = $this->config->item('project_dispute_details_page_dispute_under_admin_review_phase');
					}
				
				}
				else if(!empty($projects_closed_disputes_data)){
					if($latest_projects_dispute_message_data['project_type'] == 'fulltime'){
						 $data['project_id'] = $projects_closed_disputes_data['disputed_fulltime_project_id'];
					}else{	
						$data['project_id'] = $projects_closed_disputes_data['disputed_project_id'];
					}
					
					
					if($projects_closed_disputes_data['dispute_status'] == 'admin_decision' ||$projects_closed_disputes_data['dispute_status'] == 'automatic_decision'){
						$data['project_dispute_stage'] = $this->config->item('project_dispute_details_page_dispute_closed_phase');
					}
					if($projects_closed_disputes_data['dispute_status'] == 'parties_agreement' || $projects_closed_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_po' || $projects_closed_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_sp' || $projects_closed_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employee'|| $projects_closed_disputes_data['dispute_status'] == 'dispute_cancelled_by_initiator_employer'){
						$data['project_dispute_stage'] = $this->config->item('project_dispute_details_page_dispute_resolved_phase');

					}			
					
					
					$project_dispute_status = 2;
					
				}
				
				//$project_dispute_status = (!empty($projects_active_disputes_data)) ? "1" : "0";
				$data['project_dispute_status'] = $project_dispute_status;
				
				
				if(!empty($projects_active_disputes_data)){
					$dispute_initiated_by = $projects_active_disputes_data['dispute_initiated_by_user_id'];
					$disputed_against_user_id = $projects_active_disputes_data['disputed_against_user_id'];
					
					$data['projects_disputes_data'] = $projects_active_disputes_data;
					
					$project_dispute_start_date = (!empty($projects_active_disputes_data)) ? $projects_active_disputes_data['dispute_start_date'] : "";
					
				
					$dispute_started_heading = 'Dispute Started on '. date(DATE_FORMAT,strtotime($project_dispute_start_date)).'  at '.date(TIME_FORMAT,strtotime($project_dispute_start_date));
					$data['dispute_started_heading'] = $dispute_started_heading;
					$data['disputed_amount'] = $projects_active_disputes_data['disputed_amount'];
					
					if($latest_projects_dispute_message_data['project_type'] == 'fixed'){
						$counter_offer_table = 'fixed_budget_projects_active_disputes_counter_offers';
					}
					if($latest_projects_dispute_message_data['project_type'] == 'hourly'){
						$counter_offer_table = 'hourly_rate_based_projects_active_disputes_counter_offers';
					}
					if($latest_projects_dispute_message_data['project_type'] == 'fulltime'){
						$counter_offer_table = 'fulltime_projects_active_disputes_counter_offers';
					}
				}else{
					
					
					$data['projects_disputes_data'] = $projects_closed_disputes_data;
					$dispute_initiated_by = $projects_closed_disputes_data['dispute_initiated_by_user_id'];
					$disputed_against_user_id = $projects_closed_disputes_data['disputed_against_user_id']; 
					
					$project_dispute_end_date = (!empty($projects_closed_disputes_data)) ? $projects_closed_disputes_data['dispute_end_date'] : "";
					
					
					
					
					$po_id = ($latest_projects_dispute_message_data['project_type'] == 'fulltime')?$projects_closed_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_closed_disputes_data['project_owner_id_of_disputed_project'];

					$sp_id = ($latest_projects_dispute_message_data['project_type'] == 'fulltime')?$projects_closed_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_closed_disputes_data['sp_winner_id_of_disputed_project'];	
				

					
					$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$po_id)->get()->row_array();
					$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
					
					$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$sp_id)->get()->row_array();
					$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
					
					
					
					$data['dispute_closed_reason'] = $dispute_closed_reason;
					$data['dispute_closed_heading'] = $dispute_closed_heading;
					$data['disputed_amount'] = $projects_closed_disputes_data['disputed_amount'];
					
					
					
					if($latest_projects_dispute_message_data['project_type'] == 'fixed'){
						$counter_offer_table = 'fixed_budget_projects_closed_disputes_counter_offers';
					}
					if($latest_projects_dispute_message_data['project_type'] == 'hourly'){
						$counter_offer_table = 'hourly_rate_based_projects_closed_disputes_counter_offers';
					}
					if($latest_projects_dispute_message_data['project_type'] == 'fulltime'){
						$counter_offer_table = 'fulltime_projects_closed_disputes_counter_offers';
					}
					$data['po_data'] =$po_data;
					$data['sp_data'] =$sp_data;
				}
				
				
				$latest_counter_offer_data = $this->db->select('*')->from($counter_offer_table)->where('dispute_reference_id',$dispute_ref_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				
				
				$total_counter_offers_other_party = $this->db->where(['dispute_reference_id' => $dispute_ref_id,'countered_by_user_id !='=> $user[0]->user_id])->from($counter_offer_table)->count_all_results();
				
				
				if($total_counter_offers_other_party >= 1){
				
					if($disputed_initiated_status == 1 && $project_dispute_status == 1){
						if(isset($projects_active_disputes_data['dispute_status']) && $projects_active_disputes_data['dispute_status'] == 'under_admin_review'){
							$limit = $total_counter_offers_other_party;
						}else{	
								
							if($latest_counter_offer_data['countered_by_user_id'] == $user[0]->user_id){
								$limit = $total_counter_offers_other_party;
							}else{	
							  $limit = $total_counter_offers_other_party -1;
							
							}
						}	
					}else{
						
						$limit = $total_counter_offers_other_party;
						
					}	
					
					
					$counter_offer_listing_other_party = $this->db // get the user detail
					->select('*')
					->from($counter_offer_table)
					->where('dispute_reference_id', $dispute_ref_id)
					->where('countered_by_user_id !=', $user[0]->user_id)
					->order_by('counter_date ASC')
					->limit($limit, 0)
					->get()->result_array();
				}else{
					$counter_offer_listing_other_party = array();
				}
				
				$total_counter_offers_login_user = $this->db->where(['dispute_reference_id' => $dispute_ref_id,'countered_by_user_id'=> $user[0]->user_id])->from($counter_offer_table)->count_all_results();
				
				if($total_counter_offers_login_user >= 1){
				
					if($disputed_initiated_status == 1 && $project_dispute_status == 1){
						if(isset($projects_active_disputes_data['dispute_status']) && $projects_active_disputes_data['dispute_status'] == 'under_admin_review'){
							$limit = $total_counter_offers_login_user;
							
						}else{	
							if($latest_counter_offer_data['countered_by_user_id'] == $user[0]->user_id){
								$limit = $total_counter_offers_login_user-1;
							}else{	
							 $limit = $total_counter_offers_login_user;
							}	
						}
					}else{
						$limit = $total_counter_offers_login_user;
					}	
					$counter_offer_listing_login_user = $this->db // get the user detail
					->select('*')
					->from($counter_offer_table)
					->where('dispute_reference_id', $dispute_ref_id)
					->where('countered_by_user_id', $user[0]->user_id)
					->order_by('counter_date ASC')
					->limit($limit, 0)
					->get()->result_array();
				}else{
					$counter_offer_listing_login_user = array();
				}		
				
				
				$dispute_initiated_by_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$dispute_initiated_by)->get()->row_array();
				
				$disputed_against_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$disputed_against_user_id)->get()->row_array();
				
				$dispute_initiated_by_user_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] : $dispute_initiated_by_user_data['company_name'];
				
				$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
				
				$data['dispute_initiated_by_user_name'] = $dispute_initiated_by_user_name;
				$data['disputed_against_user_name'] = $disputed_against_user_name;
				
				$data['disputed_against_user_id'] = $disputed_against_user_id;
				$data['dispute_initiated_by'] = $dispute_initiated_by;
				$data['dispute_ref_id'] = $dispute_ref_id;
				$data['latest_projects_dispute_message_data'] = $latest_projects_dispute_message_data;
				$data['latest_counter_offer_data'] = $latest_counter_offer_data;
				$data['counter_offer_listing_other_party'] = $counter_offer_listing_other_party;
				$data['counter_offer_listing_login_user'] = $counter_offer_listing_login_user;
				
				$projects_disputed_escrows_history_listing = $this->Projects_disputes_model->get_disputed_project_escrows_history_listing($latest_projects_dispute_message_data['project_type'],array('dispute_reference_id'=>$dispute_ref_id),array('dispute_initiate_status'=>1));
				
				
				
				$data['projects_disputed_escrows_history_listing'] = $projects_disputed_escrows_history_listing;
				//$data['sum_disputed_escrows_amount_project'] = $sum_disputed_escrows_amount_project;
				
				
				$data['dispute_messages']	= $this->Projects_disputes_model->get_project_disputed_message_listing($dispute_ref_id);
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($data['project_id']);
			
				
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.employer_id as project_owner_id,u.first_name,u.last_name,u.company_name,u.account_type')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
					
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_owner_id,u.first_name,u.last_name,u.company_name,u.account_type')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $data['project_id'])
					->get()->row_array();
				} ;
				
				//$count_counter_offers_entires =  $this->db->where(['dispute_reference_id',$dispute_ref_id])->from('fsdfsdfsd')->count_all_results();
				
				$count_counter_offers_entires = $this->db->where(['dispute_reference_id' => $dispute_ref_id])->from($active_counter_offer_table)->count_all_results();
				
				$show_create_counter_offer_section = 1;
				if($count_counter_offers_entires == 0){
					//$data['validation_msg'] = "*Enter the offer less then ".str_replace(".00","",number_format($data['disputed_amount'],  2, '.', ' '))." ". CURRENCY;
					
					$counter_offer_min_amount = "1 ".CURRENCY;
					$counter_offer_max_amount = str_replace(".00","",number_format($data['disputed_amount']-1,  2, '.', ' ')).' '.CURRENCY;
					
					$data['validation_msg'] = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array($counter_offer_min_amount,$counter_offer_max_amount),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
					
					
				}else if($count_counter_offers_entires == 1){
					
					
					$sp_id = ($latest_projects_dispute_message_data['project_type'] == 'fulltime')?$data['projects_disputes_data']['employee_winner_id_of_disputed_fulltime_project']:$data['projects_disputes_data']['sp_winner_id_of_disputed_project'];
					
					
					$latest_counter_offer_created_by_sp_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$dispute_ref_id,'countered_by_user_id'=>$sp_id ])->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					$counter_offer_min_amount = "1 ".CURRENCY;
					$counter_offer_max_amount = str_replace(".00","",number_format($latest_counter_offer_created_by_sp_data['counter_amount_value']-1,  2, '.', ' ')).' '.CURRENCY;
					
					
					
					
					$data['validation_msg'] = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array($counter_offer_min_amount,$counter_offer_max_amount),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
					
					
					/* $data['validation_msg'] = "*Enter the offer less then ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY; */
				
				}else{
					
					$sp_id = ($latest_projects_dispute_message_data['project_type'] == 'fulltime')?$data['projects_disputes_data']['employee_winner_id_of_disputed_fulltime_project']:$data['projects_disputes_data']['sp_winner_id_of_disputed_project'];
					
					$po_id = ($latest_projects_dispute_message_data['project_type'] == 'fulltime')?$data['projects_disputes_data']['employer_id_of_disputed_fulltime_project']:$data['projects_disputes_data']['project_owner_id_of_disputed_project'];
					
					$latest_counter_offer_created_by_po_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$dispute_ref_id,'countered_by_user_id'=>$po_id])->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					$latest_counter_offer_created_by_sp_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$dispute_ref_id,'countered_by_user_id'=>$sp_id])->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					$counter_offer_min_amount = $latest_counter_offer_created_by_po_data['counter_amount_value'];
					$counter_offer_max_amount = $latest_counter_offer_created_by_sp_data['counter_amount_value'] ;
					
					/* echo $counter_offer_min_amount;
					echo "hiiii";
					echo $counter_offer_max_amount;
					die; */
					
					$diff = floatval($counter_offer_max_amount) - floatval($counter_offer_min_amount);
					
					
					//$counter_offer_max_amount = str_replace(".00","",number_format($latest_counter_offer_created_by_sp_data['counter_amount_value']-1,  2, '.', ' ')).' '.CURRENCY;
					
					/* if($po_id  != $user[0]->user_id){ */
					 	
						if($diff >2){
							
						$counter_offer_min_amount = $latest_counter_offer_created_by_po_data['counter_amount_value']+1;
						$counter_offer_max_amount = $latest_counter_offer_created_by_sp_data['counter_amount_value']-1 ;
						
						$data['validation_msg'] = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array(str_replace(".00","",number_format($counter_offer_min_amount,  2, '.', ' ')).' '.CURRENCY,str_replace(".00","",number_format($counter_offer_max_amount,  2, '.', ' ')).' '.CURRENCY),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
						}
						if($diff == 2  && $po_id  == $user[0]->user_id){
							$counter_offer_amount  = $latest_counter_offer_created_by_po_data['counter_amount_value']+1;
							$data['validation_msg'] = str_replace(array('{dispute_counter_offer_amount_no_range_available}'),array($counter_offer_amount.' '.CURRENCY),$this->config->item('project_dispute_details_page_counter_offer_no_range_available_validation_project_dispute_form_message'));
						}
						if($diff == 2  && $po_id  != $user[0]->user_id){
							$counter_offer_amount  = $latest_counter_offer_created_by_sp_data['counter_amount_value']-1;
							$data['validation_msg'] = str_replace(array('{dispute_counter_offer_amount_no_range_available}'),array($counter_offer_amount.' '.CURRENCY),$this->config->item('project_dispute_details_page_counter_offer_no_range_available_validation_project_dispute_form_message'));
						}
						if($diff == 1){
						$show_create_counter_offer_section = 0;
						}
						
				}
				$total_counter_offers = $this->db->where(['dispute_reference_id' => $dispute_ref_id])->from($counter_offer_table)->count_all_results();
				$data['total_counter_offers']  = $total_counter_offers;
				
				
			}
			$data['project_data']	= $project_data;
			$data['show_create_counter_offer_section']	= $show_create_counter_offer_section;
			$data['disputed_initiated_status']	= $disputed_initiated_status;
			########## set the profile title meta tag and meta description  start here #########
			$title_meta_tag = $this->config->item('project_dispute_details_page_title_meta_tag');
			$title_meta_tag = str_replace(array('{dispute_initiator_user_name}','{disputee_user_name}','{project_title}'),array($dispute_initiated_by_user_name,$disputed_against_user_name,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$title_meta_tag);
			$description_meta_tag = $this->config->item('project_dispute_details_page_description_meta_tag');
			$description_meta_tag = str_replace(array('{dispute_initiator_user_name}','{disputee_user_name}','{project_title}'),array($dispute_initiated_by_user_name,$disputed_against_user_name,htmlspecialchars($project_data['project_title'], ENT_QUOTES)),$description_meta_tag);

			$data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
			########## set the profile title tag start end #########
			$this->session->unset_userdata('check_project_dispute_redirection'); 
			$this->layout->view ('project_dispute_details', '', $data, 'include');
		} else {
            redirect(site_url());
        }
    }
	
	/**
	* This function is used to upload the attachment regarding project dispute .
	*/
	public function upload_project_dispute_attachment(){
	
		if($this->input->is_ajax_request ()){
			
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				
				$image_name = '';
				if(!empty($_FILES)){
				
					$initiate_projects_dispute_message_data = $this->db->select('project_type')->from('projects_disputes_messages_activity_tracking')->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
					if(empty($initiate_projects_dispute_message_data)){
						
						
						if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id ){
							 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
							 die;
						}
						
						foreach($_FILES['files']['name'] as $key => $val) {
						$temp 		= 	explode(".", $val);
						$extension 	= 	end($temp);
						$attachment_name = round(microtime(true) * 1000);
						$image_name = $attachment_name.'.'.$extension;
							if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$image_name)){
							
								$this->db->insert('temp_disputes_projects_attachments', array('temp_dispute_reference_id'=>$post_data['dispute_ref_id'],'temp_disputed_project_id'=>$post_data['project_id'],'temp_dispute_attachment_name'=>$image_name));
								$last_insert_id = $this->db->insert_id();
								// check the number of attachment of user into database
								$no_project_attachment_uploaded = $this->db->where(['temp_dispute_reference_id'=>$post_data['dispute_ref_id'],'temp_disputed_project_id'=>$post_data['project_id']])->from('temp_disputes_projects_attachments')->count_all_results();
								$type= "temp";
							}
						}
					}else{
						
						if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
						 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						 die;
						}
					
						if($post_data['project_type'] == 'fixed'){
							$dispute_active_table = 'fixed_budget_projects_active_disputes';
							$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
							
						}
						if($post_data['project_type'] == 'hourly'){
							$dispute_active_table = 'hourly_rate_based_projects_active_disputes';
							$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
						
						}
						if($post_data['project_type'] == 'fulltime'){
							$dispute_active_table = 'fulltime_projects_active_disputes';
							$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
						
						}
						$projects_active_disputes_data = $this->db->get_where($dispute_active_table, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
						if(empty($projects_active_disputes_data)){
						
							if($post_data['project_type'] == 'fulltime'){
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_upload_attchment_on_already_closed_dispute')]);
								die;
							}else{
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_upload_attchment_on_already_closed_dispute')]);
								die;
							}
						
						}
						if(!empty($projects_active_disputes_data) && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] < $project_value_for_admin_arbitration){
							
							if($post_data['project_type'] == 'fulltime'){
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_upload_attachment_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
								die;
							}else{
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_upload_attachment_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
								die;
							}
						}
						if(!empty($projects_active_disputes_data) && ((strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] >= $project_value_for_admin_arbitration) || $projects_active_disputes_data['dispute_status'] == 'under_admin_review')){	
							if($post_data['project_type'] == 'fulltime'){
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_upload_attachment_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
								die;
							}else{
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_upload_attachment_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
								die;
							}
						}
						
						$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
							$project_data = $this->db // get the user detail
							->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
							->from($project_status_table_array['table_name'].' pd')
							->join('users u', 'u.user_id = pd.employer_id', 'left')
							->where('pd.fulltime_project_id', $post_data['project_id'])
							->get()->row_array();
						}else{
							$project_data = $this->db // get the user detail
							->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
							->from($project_status_table_array['table_name'].' pd')
							->join('users u', 'u.user_id = pd.project_owner_id', 'left')
							->where('pd.project_id', $post_data['project_id'])
							->get()->row_array();
						}
						if($project_data['project_owner_id'] == $user[0]->user_id){
							if($user[0]->user_id == Cryptor::doDecrypt($post_data['dispute_initiated_by'])){
								$sp_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
							}
							if($user[0]->user_id == Cryptor::doDecrypt($post_data['disputed_against_user_id'])){
								$sp_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
							}
						}if($project_data['project_owner_id'] != $user[0]->user_id){
							$sp_id  = $user[0]->user_id;
						}
						
						
						$profile_folder     = $project_data['profile_name'];
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
						$project_dispute_dir = PROJECT_DISPUTE_DIR;
						$profile_folder     = $project_data['profile_name'];
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir);
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR);
						
						$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR);
						
						foreach($_FILES['files']['name'] as $key => $val) {
						$temp 		= 	explode(".", $val);
						$extension 	= 	end($temp);
						$attachment_name = round(microtime(true) * 1000);
						$image_name = $attachment_name.'.'.$extension;
							if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$image_name)){
							
							
								$source_path = TEMP_DIR.$image_name;
								$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR .$image_name;
								
								$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // 
								$this->db->insert('projects_disputes_attachments', array('dispute_reference_id'=>$post_data['dispute_ref_id'],'disputed_project_id'=>$post_data['project_id'],'dispute_attachment_name'=>$image_name));
								unlink($source_path);
								$last_insert_id = $this->db->insert_id();
								$type= "active";
							}
						}
					
					}
					$msg['status'] = 200;
					$msg['image_name'] = $image_name;
					$msg['type'] = $type;
					$msg['row_id'] = $last_insert_id;
					echo json_encode($msg);
					die;
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	
	}
	
	/**
	This function is used to remove the temporary attachment of projects.
	*/
	public function delete_disputed_project_attachment (){
      
        if ($this->input->is_ajax_request ())
        {
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				if($post_data['type'] == 'temp'){
					
					if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id ){
						 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						 die;
					}
					
					$projects_temp_dir = PROJECT_TEMPORARY_DIR;
					$attachment_name = $post_data['attachment_name'];
					$attachment_id = $post_data['row_id'];
					$attachment_data = $this->db->get_where('temp_disputes_projects_attachments', ['id' => $attachment_id])->row_array();
					if(!empty($attachment_data)){
						if(file_exists(TEMP_DIR.$attachment_data['temp_dispute_attachment_name'])){
							unlink(TEMP_DIR.$attachment_data['temp_dispute_attachment_name']);
						}
						if(file_exists(TEMP_DIR.$attachment_name)){
							unlink(TEMP_DIR.$attachment_name);
						}
						$this->db->delete('temp_disputes_projects_attachments', array('id' => $attachment_id));
					
					}else{
						if(file_exists(TEMP_DIR.$attachment_name)){
							unlink(TEMP_DIR.$attachment_name);
						}
						$this->db->delete('temp_disputes_projects_attachments', array('id' => $attachment_id));
					}
					
				}else if($post_data['type'] = 'active'){
				
					if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
						 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						 die;
					}
					$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $post_data['project_id'])
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $post_data['project_id'])
						->get()->row_array();
					}
					if($project_data['project_owner_id'] == $user[0]->user_id){
						if($user[0]->user_id == Cryptor::doDecrypt($post_data['dispute_initiated_by'])){
							$sp_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
						}
						if($user[0]->user_id == Cryptor::doDecrypt($post_data['disputed_against_user_id'])){
							$sp_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
						}
					}if($project_data['project_owner_id'] != $user[0]->user_id){
						$sp_id  = $user[0]->user_id;
					}
					
					
					$profile_folder     = $project_data['profile_name'];
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
					$project_dispute_dir = PROJECT_DISPUTE_DIR;
					$profile_folder     = $project_data['profile_name'];
					
					$attachment_id = $post_data['row_id'];
					$attachment_data = $this->db->get_where('projects_disputes_attachments', ['id' => $attachment_id])->row_array();
					if(!empty($attachment_data)){
					
						
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR .$attachment_data['dispute_attachment_name'];
					
					
						$file_size = $this->ftp->get_filesize($source_path);
						if($file_size != '-1')
						{
							$this->ftp->delete_file($source_path);
						}
						$this->db->delete('projects_disputes_attachments', array('id' => $attachment_id));
					
					}else{
						if(file_exists(TEMP_DIR.$attachment_name)){
							unlink(TEMP_DIR.$attachment_name);
						}
						$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR .$attachment_name;
						
						$this->db->delete('disputes_projects_attachment', array('id' => $attachment_id));
					}
				
				}
				$msg['status'] = 200;
				$msg['message'] = '';
				
				echo json_encode ($msg);
			}else{
			
			}
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	/**
	This function is used to check that project dispute attachment exists or not in disk or into database
	*/
	public function check_project_dispute_attachment_exists ()
    {	
		if( $this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				$attachment_id = $post_data['row_id'];
				if($post_data['type'] == 'temp'){
					
					if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id ){
						 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						 die;
					}
					$attachment_data = $this->db->get_where('temp_disputes_projects_attachments', ['id' => $attachment_id])->row_array();
					$attachment_path = TEMP_DIR.$attachment_data['temp_dispute_attachment_name'];
					
					if(file_exists($attachment_path)){
						$msg['status'] = 200;
						$msg['error'] = '';
						$msg['location'] = VPATH . 'projects_disputes/download_attachment_disputed_project/'.$attachment_id.'/'.$post_data['type'];
					}else{
						$msg['status'] = 400;
						$msg['error'] = $this->config->item('project_dispute_details_page_attachment_not_exist_validation_message');
						$msg['location'] = '';
					
					}
					
					
				}
				else if($post_data['type'] == 'active'){
					
					if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
						 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						 die;
					}
					
					$attachment_data = $this->db->get_where('projects_disputes_attachments', ['id' => $attachment_id])->row_array();
					
					$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
					
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.project_type,u.user_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.project_id', $post_data['project_id'])
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_type,u.user_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $post_data['project_id'])
						->get()->row_array();
					}
					
					
					
					if($project_data['project_type'] == 'fixed'){
						$active_dispute_table = 'fixed_budget_projects_active_disputes';
						$closed_dispute_table = 'fixed_budget_projects_closed_disputes';
					}
					if($project_data['project_type'] == 'hourly'){
						$active_dispute_table = 'hourly_rate_based_projects_active_disputes';
						$closed_dispute_table = 'hourly_rate_based_projects_closed_disputes';
					}
					if($project_data['project_type'] == 'fulltime'){
						$active_dispute_table = 'fulltime_projects_active_disputes';
						$closed_dispute_table = 'fulltime_projects_closed_disputes';
					}					
					
					$projects_active_disputes_data = $this->db->get_where($active_dispute_table, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
					$projects_closed_disputes_data = $this->db->get_where($closed_dispute_table , ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
					$project_dispute_status = !empty($projects_active_disputes_data) ? "1" : "0";
					if($project_dispute_status == 1){
						$dispute_initiated_by = $projects_active_disputes_data['dispute_initiated_by_user_id'];
						$disputed_against_user_id = $projects_active_disputes_data['disputed_against_user_id'];
					}else{
						$dispute_initiated_by = $projects_closed_disputes_data['dispute_initiated_by_user_id'];
						$disputed_against_user_id = $projects_closed_disputes_data['disputed_against_user_id'];
					}
					
					if($project_data['user_id'] ==$dispute_initiated_by){
						$po_id = $dispute_initiated_by;
						$sp_id = $disputed_against_user_id;
					}else if($project_data['user_id'] ==$disputed_against_user_id){
						$po_id = $disputed_against_user_id;
						$sp_id = $dispute_initiated_by;
					}
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
					$project_dispute_dir = PROJECT_DISPUTE_DIR;
					$profile_folder     = $project_data['profile_name'];
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR .$attachment_data['dispute_attachment_name'];
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$msg['status'] = 200;
						$msg['location'] = VPATH . 'projects_disputes/download_attachment_disputed_project/'.$attachment_id.'/'.$post_data['type'].'/'.$post_data['project_id'];
					}else{
						$msg['status'] = 400;
						$msg['error'] = $this->config->item('project_dispute_details_page_attachment_not_exist_validation_message');
						$msg['location'] = '';
						$msg['popup_heading'] = $this->config->item('popup_alert_heading');
					}
					$this->ftp->close();
					
				}
				echo json_encode ($msg);die;
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	
	/**
	This function is used to download the attachment regarding project dispute.
	*/
	public function download_attachment_disputed_project()
    {
		if($this->session->userdata ('user')){
			$this->load->helper('download');
			$attachment_id = $this->uri->segment(3);
			$type = $this->uri->segment(4);
			if(empty($this->uri->segment(3)) && empty($this->uri->segment(4))){
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$user = $this->session->userdata ('user');
			if($type  == 'temp'){
				$attachment_data = $this->db->get_where('temp_disputes_projects_attachments', ['id' => $attachment_id])->row_array();
				
				$attchment_name = $attachment_data['temp_dispute_attachment_name'];
				$attchment_path = TEMP_DIR.$attchment_name;
				if(!empty($attachment_data)){
					if(file_exists($attchment_path))
					{
						$data = file_get_contents ($attchment_path);
						force_download ($attchment_name,$data);
					}else{
						show_404();
					}
				}else{
					show_404();
				}
			}else if($type  == 'active'){
				$project_id = $this->uri->segment(5);
				
				$attachment_data = $this->db->get_where('projects_disputes_attachments', ['id' => $attachment_id])->row_array();
					
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.project_type,u.user_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_type,u.user_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
			
				
				if($project_data['project_type'] == 'fixed'){
					$active_dispute_table = 'fixed_budget_projects_active_disputes';
					$closed_dispute_table = 'fixed_budget_projects_closed_disputes';
				}
				if($project_data['project_type'] == 'hourly'){
					$active_dispute_table = 'hourly_rate_based_projects_active_disputes';
					$closed_dispute_table = 'hourly_rate_based_projects_closed_disputes';
				}
				if($project_data['project_type'] == 'fulltime'){
					$active_dispute_table = 'fulltime_projects_active_disputes';
					$closed_dispute_table = 'fulltime_projects_closed_disputes';
				}	
				
				
				//if($project_data['project_type'] == 'fixed'){
				
				$projects_active_disputes_data = $this->db->get_where($active_dispute_table, ['dispute_reference_id' => $attachment_data['dispute_reference_id']])->row_array();
				$projects_closed_disputes_data = $this->db->get_where($closed_dispute_table, ['dispute_reference_id' => $attachment_data['dispute_reference_id']])->row_array();
				$project_dispute_status = !empty($projects_active_disputes_data) ? "1" : "0";
				if($project_dispute_status == 1){
					$dispute_initiated_by = $projects_active_disputes_data['dispute_initiated_by_user_id'];
					$disputed_against_user_id = $projects_active_disputes_data['disputed_against_user_id'];
				}else{
					$dispute_initiated_by = $projects_closed_disputes_data['dispute_initiated_by_user_id'];
					$disputed_against_user_id = $projects_closed_disputes_data['disputed_against_user_id'];
				}
				//}
				
					
				if($project_data['user_id'] ==$dispute_initiated_by){
					$po_id = $dispute_initiated_by;
					$sp_id = $disputed_against_user_id;
				}else if($project_data['user_id'] ==$disputed_against_user_id){
					$po_id = $disputed_against_user_id;
					$sp_id = $dispute_initiated_by;
				}
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
				$project_dispute_dir = PROJECT_DISPUTE_DIR;
				$profile_folder     = $project_data['profile_name'];
				echo $source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$attachment_data['dispute_reference_id'].DIRECTORY_SEPARATOR .$attachment_data['dispute_attachment_name'];
				
				$file_size = $this->ftp->get_filesize($source_path);
				if($file_size != '-1')
				{
					$destination_path =  FCPATH .TEMP_DIR.$attachment_data['dispute_attachment_name'];
					$this->ftp->download($source_path,$destination_path, 'auto', 0777);
					$this->ftp->close();
					$data = file_get_contents (TEMP_DIR.$attachment_data['dispute_attachment_name'] );// read the content of file
					unlink(TEMP_DIR.$attachment_data['dispute_attachment_name'] );
					force_download ($attachment_data['dispute_attachment_name'],$data);
				}else{
					
					show_404();
				}
				$this->ftp->close();
			}
			
		}else{
			show_404();
		}
    }
	
	
	/**
	* This function is used to make the confirmation popup for initiate the popup . - Done
	*/
	public function initiate_project_dispute_confirmation_popup_body(){
		
		if($this->input->is_ajax_request ()){
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
			
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id ){
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				}
				
				
				$project_data['project_type'] = $post_data['project_type'];
				$validation_data_array = $this->Projects_disputes_model->project_dispute_form_validation($post_data,$project_data);
				if($validation_data_array['status'] == 'SUCCESS'){
				
					$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $post_data['project_id'])
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $post_data['project_id'])
						->get()->row_array();
					}
					
					if($project_data['project_owner_id'] == Cryptor::doDecrypt($post_data['dispute_initiated_by'])){
						$po_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
						$sp_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
					}else if($project_data['project_owner_id'] == Cryptor::doDecrypt($post_data['disputed_against_user_id'])){
						$po_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
						$sp_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
					}
					
					
					$disputed_against_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name')->from('users')->where('user_id',Cryptor::doDecrypt($post_data['disputed_against_user_id']))->get()->row_array();

										
					$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];

					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
					
					$user_profile_url_link = VPATH.$disputed_against_user_data['profile_name'];
					if($post_data['project_type'] == 'fixed'){
						$active_escrow_table = 'fixed_budget_projects_active_escrows';
						$conditions = array('project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
					}
					if($post_data['project_type'] == 'hourly'){
						$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
						$conditions = array('project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
					}
					if($post_data['project_type'] == 'fulltime'){
						$active_escrow_table = 'fulltime_projects_active_escrows';
						$conditions = array('fulltime_project_id'=>$post_data['project_id'],'employer_id'=>$po_id,'employee_id'=>$sp_id);
					}
					
					$this->db->select('SUM(created_escrow_amount) as sum_escrow_amount_value');
					$this->db->from($active_escrow_table);
					$this->db->where($conditions);
					$sum_active_escrow_result = $this->db->get();
					$sum_active_escrow_result = $sum_active_escrow_result->row_array();

					$sum_escrow_amount_value =  str_replace(".00","",number_format($sum_active_escrow_result['sum_escrow_amount_value'],  2, '.', ' '))." ". CURRENCY;
					
					
					
					if($post_data['project_type'] == 'fixed' || $post_data['project_type'] == 'hourly'){
					
						$confirmation_modal_title = $this->config->item('project_dispute_details_page_dispute_initiate_confirmation_project_modal_title');
						$confirmation_modal_body_txt = $this->config->item('project_dispute_details_page_dispute_initiate_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{project_url_link}','{project_title}','{user_profile_url_link}','{user_first_name_last_name_or_company_name}','{project_disputed_amount}'),array($project_url_link,$project_title,$user_profile_url_link,$disputed_against_user_name,$sum_escrow_amount_value),$confirmation_modal_body_txt);
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body_txt.'</div>';
						$confirmation_modal_footer = '<button id="initiate_project_dispute" type="button" class="btn blue_btn default_btn cancel_requested_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('project_dispute_details_page_dispute_initiate_confirmation_project_modal_dispute_initiate_btn_txt').'</button><button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>';
					}
					if($post_data['project_type'] == 'fulltime'){
					
						$confirmation_modal_title = $this->config->item('fulltime_project_dispute_details_page_dispute_initiate_confirmation_project_modal_title');
						$confirmation_modal_body_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_initiate_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{project_url_link}','{fulltime_project_title}','{user_profile_url_link}','{user_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}'),array($project_url_link,$project_title,$user_profile_url_link,$disputed_against_user_name,$sum_escrow_amount_value),$confirmation_modal_body_txt);
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body_txt.'</div>';
						$confirmation_modal_footer = '<button id="initiate_project_dispute" type="button" class="btn blue_btn default_btn cancel_requested_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('fulltime_project_dispute_details_page_dispute_initiate_confirmation_project_modal_dispute_initiate_btn_txt').'</button><button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>';
					} 
					//cancel_requested_escrow_disclaimer_message
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"></div></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="initiate_dispute_checkbox"><span class="checkmark"></span></label><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></div></div></div>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
					die;
				}else{
					//echo json_encode($msg);
					echo json_encode ($validation_data_array);
					die;
				}	
			}else{
				show_custom_404_page(); //show custom 404 page
			
			}
				
			
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	## This function is used initiate the dispute
	public function initiate_project_dispute(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
			die;
		}
		
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			$post_data = $this->input->post ();
			
			if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id){
			 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			 die;
			}
			
			
			$project_data['project_type'] = $post_data['project_type'];
			$validation_data_array = $this->Projects_disputes_model->project_dispute_form_validation($post_data,$project_data);
			if($validation_data_array['status'] == 'SUCCESS'){
				$project_id = $this->input->post ('project_id');
				$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $post_data['project_id'])
					->get()->row_array();
				}
				
				if($project_data['project_owner_id'] == Cryptor::doDecrypt($post_data['dispute_initiated_by'])){
					$po_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
					$sp_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
				}else if($project_data['project_owner_id'] == Cryptor::doDecrypt($post_data['disputed_against_user_id'])){
					$po_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
					$sp_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
				}
				
			
				if($project_data['project_type'] == 'fixed'){
					$disputed_escrows_history_tracking_table = 'fixed_budget_projects_disputed_escrows_history_tracking';
					$dispute_active_table = 'fixed_budget_projects_active_disputes';
					$dispute_closed_table = 'fixed_budget_projects_closed_disputes';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$inprogress_project_table_name = 'fixed_budget_projects_progress';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					//$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$incomplete_project_table_name = 'fixed_budget_projects_incomplete';
					$active_escrow_table = 'fixed_budget_projects_active_escrows';
					$conditions = array('project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
				}
				if($project_data['project_type'] == 'hourly'){
					$disputed_escrows_history_tracking_table = 'hourly_rate_projects_disputed_escrows_history_tracking';
					$dispute_active_table = 'hourly_rate_based_projects_active_disputes';
					$dispute_closed_table = 'hourly_rate_based_projects_closed_disputes';
					$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
					$conditions = array('project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id);
					//$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
				}
				if($project_data['project_type'] == 'fulltime'){
					$disputed_escrows_history_tracking_table = 'fulltime_projects_disputed_escrows_history_tracking';
					$dispute_active_table = 'fulltime_projects_active_disputes';
					$dispute_closed_table = 'fulltime_projects_closed_disputes';
					$active_escrow_table = 'fulltime_projects_active_escrows';
					$conditions = array('fulltime_project_id'=>$post_data['project_id'],'employer_id'=>$po_id,'employee_id'=>$sp_id);
					$fulltime_bid_table_name = 'fulltime_projects_hired_employees_tracking';
				}
				
				//$completed_bid_detail = $this->db->get_where($completed_bid_table_name, ['project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id])->row_array();
				
				
				// get sum of created escrow and business fees
				
				$this->db->select('SUM(created_escrow_amount) as sum_escrow_amount_value,SUM(service_fee_charges) as sum_service_fee_charges');
				$this->db->from($active_escrow_table);
				$this->db->where($conditions);
				$sum_active_escrow_result = $this->db->get();
				$sum_active_escrow_result = $sum_active_escrow_result->row_array();
				
				
				
				$time_arr = explode(':', $this->config->item('dispute_negotiation_availability_time'));
				$current_date = date('Y-m-d H:i:s');
				$dispute_negotiation_end_date =  date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($current_date)));
				
				$active_dispute_data['dispute_reference_id'] = $post_data['dispute_ref_id'];
				if($project_data['project_type'] == 'fulltime'){
					
					$active_dispute_data['disputed_fulltime_project_id'] = $post_data['project_id'];
					$active_dispute_data['employer_id_of_disputed_fulltime_project'] = $po_id;
					$active_dispute_data['employee_winner_id_of_disputed_fulltime_project'] = $sp_id;
					$total_released_escrow = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($post_data['project_type'],array('fulltime_project_id'=>$post_data['project_id'],'employer_id'=>$po_id,'employee_id'=>$sp_id));
					$active_dispute_data['fulltime_project_value_before_dispute_start'] = $total_released_escrow;
					
				}else{
					$active_dispute_data['disputed_project_id'] = $post_data['project_id'];
					$active_dispute_data['project_owner_id_of_disputed_project'] = $po_id;
					$active_dispute_data['sp_winner_id_of_disputed_project'] = $sp_id;
					$total_released_escrow = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($post_data['project_type'],array('project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
					$active_dispute_data['project_value_before_dispute_start'] = $total_released_escrow;
				}		
				
				$active_dispute_data['dispute_initiated_by_user_id'] = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
				$active_dispute_data['disputed_against_user_id'] = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
				$active_dispute_data['dispute_start_date'] = $current_date;
				$active_dispute_data['disputed_amount'] = $sum_active_escrow_result['sum_escrow_amount_value'];
				$active_dispute_data['disputed_service_fees'] = $sum_active_escrow_result['sum_service_fee_charges'];
				$active_dispute_data['dispute_negotiation_end_date'] = $dispute_negotiation_end_date;
				$active_dispute_data['disputed_latest_message_sent_time'] = $current_date;
				
				
				
				
				
				
				/* if(!empty($completed_bid_detail)){
					$active_dispute_data['project_value_before_dispute_start'] = $completed_bid_detail['total_project_amount'];
				} */	
			
				if($this->db->insert ($dispute_active_table, $active_dispute_data)){
				
					######## active escrows moves ####
					$result_array = $this->db // get the user detail
					->select('*')
					->from($active_escrow_table)
					->where($conditions)
					->get()->result_array();
					if(!empty($result_array)){
						foreach($result_array as $escrow_key=>$escrow_value){
							
							unset($escrow_value['id']);
							
							$id  = $escrow_value['id'];
							
							$escrow_value['dispute_reference_id'] = $post_data['dispute_ref_id'];
							$this->db->insert($disputed_escrows_history_tracking_table, $escrow_value);
						}
						$this->db->delete($active_escrow_table, $conditions);
					}
					############################
					$this->db->delete('temp_projects_active_disputes', array('temp_dispute_reference_id' => $post_data['dispute_ref_id']));
					
					
					$disputes_messages_data['dispute_reference_id'] = $post_data['dispute_ref_id'];
					$disputes_messages_data['disputed_project_id']  = $post_data['project_id'];
					$disputes_messages_data['project_type'] = $project_data['project_type'];
					$disputes_messages_data['message_sent_by_user_id'] = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
					$disputes_messages_data['message_sent_to_user_id'] = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
					$disputes_messages_data['description'] = trim($post_data['dispute_description']);
					$disputes_messages_data['message_sent_date'] = $current_date;
					
					
					if($this->db->insert ('projects_disputes_messages_activity_tracking', $disputes_messages_data)){
						
						$last_insert_id = $this->db->insert_id();
						
						$result_array = $this->db // get the user detail
						->select('*')
						->from('temp_disputes_projects_attachments')
						->where('temp_dispute_reference_id', $post_data['dispute_ref_id'])
						->get()->result_array();
						if(!empty($result_array)){
							
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
							$project_dispute_dir = PROJECT_DISPUTE_DIR;
							$profile_folder     = $project_data['profile_name'];
							
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR);
							$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR);
						
							//$this->ftp->mkdir($users_ftp_dir.$profile_folder, 0777);// create project owner directory if not exists
							//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);//
							//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir, 0777);
							//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id, 0777);
							//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'], 0777);
							
							
							foreach($result_array as $key=>$value){
								if(file_exists(TEMP_DIR.$value['temp_dispute_attachment_name'])){
									$source_path = TEMP_DIR.$value['temp_dispute_attachment_name'];
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR .$value['temp_dispute_attachment_name'];
									$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
									$attachment_data['dispute_reference_id'] =$post_data['dispute_ref_id']; 
									$attachment_data['disputed_project_id'] =$post_data['project_id']; 
									$attachment_data['dispute_message_id'] =$last_insert_id; 
									$attachment_data['dispute_attachment_name'] =$value['temp_dispute_attachment_name']; 
									if($this->db->insert ('projects_disputes_attachments', $attachment_data)){
										unlink($source_path);
									
									}
								}
							}
							$this->db->delete('temp_disputes_projects_attachments', array('temp_dispute_reference_id' => $post_data['dispute_ref_id']));
							
							$this->ftp->close();
							
						}
					}
					
					$disputed_initiated_status = 1;
					$latest_projects_dispute_message_data = $this->db->select('*')->from('projects_disputes_messages_activity_tracking')->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
					
					$projects_active_disputes_data = $this->db->get_where($dispute_active_table, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
				
					$projects_closed_disputes_data = $this->db->get_where($dispute_closed_table, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
					
					$project_dispute_status = 0;
					if(!empty($projects_active_disputes_data)){
						$data['project_id'] = $projects_active_disputes_data['disputed_project_id'];
						if($projects_active_disputes_data['dispute_status'] == 'active' && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) > time()){
							$project_dispute_stage = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase');
							$project_dispute_status = 1;
						}
						if($projects_active_disputes_data['dispute_status'] == 'active' && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) <= time()){
							$project_dispute_stage = $this->config->item('project_dispute_details_page_dispute_under_admin_review_phase');
							
						}
						if($projects_active_disputes_data['dispute_status'] == 'under_admin_review'){
							$project_dispute_stage = $this->config->item('project_dispute_details_page_dispute_under_admin_review_phase');
						}
					
					}
					else if(!empty($projects_closed_disputes_data)){
						$data['project_id'] = $projects_closed_disputes_data['disputed_project_id'];
						$project_dispute_stage = $this->config->item('project_dispute_details_page_dispute_closed_phase');
						
					}
					
					//$data['project_dispute_status'] = $project_dispute_status;
					if(!empty($projects_active_disputes_data)){
					
						$data['projects_disputes_data'] = $projects_active_disputes_data;
						$dispute_initiated_by = $projects_active_disputes_data['dispute_initiated_by_user_id'];
						$disputed_against_user_id = $projects_active_disputes_data['disputed_against_user_id'];
					
						
						$dispute_initiated_by_user_data = $this->db->select('first_name,last_name,company_name,account_type')->from('users')->where('user_id',$dispute_initiated_by)->get()->row_array();
						
					
						$project_dispute_start_date = !empty($projects_active_disputes_data) ? $projects_active_disputes_data['dispute_start_date'] : "";
						$dispute_started_heading = 'Dispute Started on '. date(DATE_FORMAT,strtotime($project_dispute_start_date)).'  at '.date(TIME_FORMAT,strtotime($project_dispute_start_date));
						
						$disputed_amount = $projects_active_disputes_data['disputed_amount'];
						
						
					}else{
						$data['projects_disputes_data'] = $projects_closed_disputes_data;
						$dispute_initiated_by = $projects_closed_disputes_data['dispute_initiated_by'];
						$disputed_against_user_id = $projects_closed_disputes_data['disputed_against_user_id'];
					
						$project_dispute_end_date = !empty($projects_closed_disputes_data) ? $projects_closed_disputes_data['dispute_end_date'] : "";
						$dispute_closed_heading = 'Dispute ends on '. date(DATE_FORMAT,strtotime($project_dispute_end_date)).'  at '.date(TIME_FORMAT,strtotime($project_dispute_end_date)).' (7 hours 6 minutes and 20 seconds remaining)';
						$data['dispute_closed_heading'] = $dispute_closed_heading;
						$disputed_amount = $projects_closed_disputes_data['disputed_amount'];
					}
					
					
					$dispute_initiated_by_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$dispute_initiated_by)->get()->row_array();
				
					$disputed_against_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$disputed_against_user_id)->get()->row_array();

					$dispute_initiated_by_user_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] : $dispute_initiated_by_user_data['company_name'];

					$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
					
					
					############# activity log message start here ############
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
					
					$sum_escrow_amount_value =  str_replace(".00","",number_format($sum_active_escrow_result['sum_escrow_amount_value'],  2, '.', ' '))." ". CURRENCY;
					$sum_service_fee_charges =  str_replace(".00","",number_format($sum_active_escrow_result['sum_service_fee_charges'],  2, '.', ' '))." ". CURRENCY;
					
					if($dispute_initiated_by == $project_data['project_owner_id']){
						/* $sp_id = $disputed_against_user_id;
						$po_id = $dispute_initiated_by; */
					
						$po_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] :$dispute_initiated_by_user_data['company_name'];
						
						$sp_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] :$disputed_against_user_data['company_name'];
						
						$sp_profile_url_link = VPATH.$disputed_against_user_data['profile_name'];
						$po_profile_url_link = VPATH.$dispute_initiated_by_user_data['profile_name'];
						
						if($project_data['project_type'] == 'fixed'){
							$po_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_po_message_sent_to_po_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{fixed_budget_project_disputed_amount}","{fixed_budget_project_disputed_amount_service_fees}"),array($sum_escrow_amount_value,$sum_service_fee_charges),$po_activity_log_message);
						}
						if($project_data['project_type'] == 'hourly'){
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_po_message_sent_to_po_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{hourly_rate_based_project_disputed_amount}","{hourly_rate_based_project_disputed_amount_service_fees}"),array($sum_escrow_amount_value,$sum_service_fee_charges),$po_activity_log_message);
						}
						if($project_data['project_type'] == 'fulltime'){
							$po_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_employer_message_sent_to_employer_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{fulltime_project_disputed_amount}","{fulltime_project_disputed_amount_service_fees}"),array($sum_escrow_amount_value,$sum_service_fee_charges),$po_activity_log_message);
						}
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$project_url_link,$project_title),$po_activity_log_message);
						
						
						if($dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($dispute_initiated_by_user_data['gender'] == 'M'){
								if($project_data['project_type'] == 'fixed'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_male_po_message_sent_to_sp_user_activity_log_displayed_message');
								}
								if($project_data['project_type'] == 'hourly'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_male_po_message_sent_to_sp_user_activity_log_displayed_message');
								}
								if($project_data['project_type'] == 'fulltime'){
									$sp_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_male_employer_message_sent_to_employee_user_activity_log_displayed_message');
								}
								
							}else{
								if($project_data['project_type'] == 'fixed'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_female_po_message_sent_to_sp_user_activity_log_displayed_message');
									
								}
								if($project_data['project_type'] == 'hourly'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_female_po_message_sent_to_sp_user_activity_log_displayed_message');
								}
								if($project_data['project_type'] == 'fulltime'){
									$sp_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_female_employer_message_sent_to_employer_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}"),array($po_name,),$sp_activity_log_message);
						
						}else{
							if($project_data['project_type'] == 'fixed'){
								$sp_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_company_po_message_sent_to_sp_user_activity_log_displayed_message');
							}if($project_data['project_type'] == 'hourly'){
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_company_po_message_sent_to_sp_user_activity_log_displayed_message');
							}
							if($project_data['project_type'] == 'fulltime'){
								$sp_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_company_employer_message_sent_to_employer_user_activity_log_displayed_message');
							}
							$sp_activity_log_message = str_replace(array("{user_company_name}"),array($po_name),$sp_activity_log_message);
						}
						
						if($project_data['project_type'] == 'fixed'){
							$sp_activity_log_message = str_replace(array("{fixed_budget_project_disputed_amount}"),array($sum_escrow_amount_value),$sp_activity_log_message);
						}
						if($project_data['project_type'] == 'hourly'){
							$sp_activity_log_message = str_replace(array("{hourly_rate_based_project_disputed_amount}"),array($sum_escrow_amount_value),$sp_activity_log_message);
						}
						if($project_data['project_type'] == 'fulltime'){
							$sp_activity_log_message = str_replace(array("{fulltime_project_disputed_amount}"),array($sum_escrow_amount_value),$sp_activity_log_message);
						}
						
						$sp_activity_log_message = str_replace(array("{po_profile_url_link}","{project_url_link}","{project_title}"),array($po_profile_url_link,$project_url_link,$project_title),$sp_activity_log_message);
						
						
					}else{
						/* $po_id = $disputed_against_user_id;
						$sp_id = $dispute_initiated_by; */
						
						$po_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] :$disputed_against_user_data['company_name'];

						$sp_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] :$dispute_initiated_by_user_data['company_name'];
						
						$sp_profile_url_link = VPATH.$dispute_initiated_by_user_data['profile_name'];
						$po_profile_url_link = VPATH.$disputed_against_user_data['profile_name'];
						
						if($project_data['project_type'] == 'fixed'){
							$sp_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_sp_message_sent_to_sp_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{fixed_budget_project_disputed_amount}"),array($sum_escrow_amount_value),$sp_activity_log_message);
						}
						if($project_data['project_type'] == 'hourly'){
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_sp_message_sent_to_sp_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{hourly_rate_based_project_disputed_amount}"),array($sum_escrow_amount_value),$sp_activity_log_message);
						}
						if($project_data['project_type'] == 'fulltime'){
							$sp_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_employee_message_sent_to_employee_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{fulltime_project_disputed_amount}"),array($sum_escrow_amount_value),$sp_activity_log_message);
						}						
						
						$sp_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$project_url_link,$project_title),$sp_activity_log_message);
						
						
						if($dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($dispute_initiated_by_user_data['gender'] == 'M'){
								if($project_data['project_type'] == 'fixed'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_male_sp_message_sent_to_po_user_activity_log_displayed_message');
									
								}if($project_data['project_type'] == 'hourly'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_male_sp_message_sent_to_po_user_activity_log_displayed_message');
								}if($project_data['project_type'] == 'fulltime'){
									$po_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_male_employee_message_sent_to_employer_user_activity_log_displayed_message');
								}
							}else{
								if($project_data['project_type'] == 'fixed'){	
									$po_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_female_sp_message_sent_to_po_user_activity_log_displayed_message');
									
								}
								if($project_data['project_type'] == 'hourly'){	
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_female_sp_message_sent_to_po_user_activity_log_displayed_message');
								
								}
								if($project_data['project_type'] == 'fulltime'){	
									$po_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_female_employee_message_sent_to__employer_user_activity_log_displayed_message');
								
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}"),array($sp_name),$po_activity_log_message);
						
						}else{
							if($project_data['project_type'] == 'fixed'){
								$po_activity_log_message = $this->config->item('fixed_budget_project_dispute_initiated_by_company_sp_message_sent_to_po_user_activity_log_displayed_message');
							}
							if($project_data['project_type'] == 'hourly'){	
								$po_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_initiated_by_male_sp_message_sent_to_po_user_activity_log_displayed_message');
							}
							if($project_data['project_type'] == 'fulltime'){	
								$po_activity_log_message = $this->config->item('fulltime_project_dispute_initiated_by_company_employee_message_sent_to_employer_user_activity_log_displayed_message');
							}
							$po_activity_log_message = str_replace(array("{user_company_name}"),array($sp_name),$po_activity_log_message);
						}
						
						if($project_data['project_type'] == 'fixed'){	
							$po_activity_log_message = str_replace(array("{fixed_budget_project_disputed_amount}","{fixed_budget_project_disputed_amount_service_fees}"),array($sum_escrow_amount_value,$sum_service_fee_charges),$po_activity_log_message);
						}
						if($project_data['project_type'] == 'hourly'){	
							$po_activity_log_message = str_replace(array("{hourly_rate_based_project_disputed_amount}","{hourly_rate_based_project_disputed_amount_service_fees}"),array($sum_escrow_amount_value,$sum_service_fee_charges),$po_activity_log_message);
						}
						if($project_data['project_type'] == 'fulltime'){	
							$po_activity_log_message = str_replace(array("{fulltime_project_disputed_amount}","{fulltime_project_disputed_amount_service_fees}"),array($sum_escrow_amount_value,$sum_service_fee_charges),$po_activity_log_message);
						}
						$po_activity_log_message = str_replace(array("{sp_profile_url_link}","{project_url_link}","{project_title}"),array($sp_profile_url_link,$project_url_link,$project_title),$po_activity_log_message);
						
						
					}
					user_display_log($sp_activity_log_message,$sp_id); // activity log message for service provider
					user_display_log($po_activity_log_message,$po_id); // activity log message for project owner
					############# activity log message end here #######
					####### Email code start here ###
					
					if($project_data['project_type'] == 'fixed'){
						$admin_dispute_arbitration_fee = $this->config->item('fixed_budget_project_admin_dispute_arbitration_percentage_fee');
						$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
						
						
					}if($project_data['project_type'] == 'hourly'){
						$admin_dispute_arbitration_fee = $this->config->item('hourly_rate_based_project_admin_dispute_arbitration_percentage_fee');
						$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
					}
					if($project_data['project_type'] == 'fulltime'){
						$admin_dispute_arbitration_fee = $this->config->item('fulltime_project_admin_dispute_arbitration_percentage_fee');
						$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
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
					
					$dispute_negotiation_period  = date(DATE_TIME_FORMAT,strtotime($active_dispute_data['dispute_negotiation_end_date']));
					
					$dispute_initiated_date  = date(DATE_TIME_FORMAT,strtotime($current_date));
					
					
					if($disputed_amount >= $project_value_for_admin_arbitration){
						
						if($project_data['project_type'] == 'fulltime'){
							// Email for initiator
							
							$initiator_to = $dispute_initiated_by_user_data['email'];
							
							$initiator_cc = $this->config->item('initiate_fulltime_project_dispute_email_cc_sent_to_initiator_dispute_decided_admin_arbitration');
							$initiator_bcc = $this->config->item('initiate_fulltime_project_dispute_email_bcc_sent_to_initiator_dispute_decided_admin_arbitration');
							
							$initiator_from = $this->config->item('initiate_fulltime_project_dispute_email_from_sent_to_initiator_dispute_decided_admin_arbitration');
							$initiator_reply_to = $this->config->item('initiate_fulltime_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_admin_arbitration');
							$initiator_from_name = $this->config->item('initiate_fulltime_project_dispute_email_from_name_sent_to_initiator_dispute_decided_admin_arbitration');
							//$initiator_subject = mb_encode_mimeheader($this->config->item('initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration'), "UTF-8", 'B');
							$initiator_subject = $this->config->item('initiate_fulltime_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration');
							
							$initiator_message = $this->config->item('initiate_fulltime_project_dispute_email_message_sent_to_initiator_dispute_decided_admin_arbitration');
							
							$initiator_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY ),$initiator_message);
							
							
						}else{	
							// Email for initiator
							$initiator_to = $dispute_initiated_by_user_data['email'];
							
							$initiator_cc = $this->config->item('initiate_project_dispute_email_cc_sent_to_initiator_dispute_decided_admin_arbitration');
							$initiator_bcc = $this->config->item('initiate_project_dispute_email_bcc_sent_to_initiator_dispute_decided_admin_arbitration');
							
							$initiator_from = $this->config->item('initiate_project_dispute_email_from_sent_to_initiator_dispute_decided_admin_arbitration');
							$initiator_reply_to = $this->config->item('initiate_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_admin_arbitration');
							$initiator_from_name = $this->config->item('initiate_project_dispute_email_from_name_sent_to_initiator_dispute_decided_admin_arbitration');
							//$initiator_subject = mb_encode_mimeheader($this->config->item('initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration'), "UTF-8", 'B');
							$initiator_subject = $this->config->item('initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration');
							
							$initiator_message = $this->config->item('initiate_project_dispute_email_message_sent_to_initiator_dispute_decided_admin_arbitration');
							
							$initiator_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY ),$initiator_message);
							
						}
						
						$initiator_message = str_replace(array('{dispute_reference_id}','{user_first_name_last_name_or_company_name}','{other_party_first_name_last_name_or_company_name}','{project_title}','{dispute_negotiation_period}','{admin_dispute_arbitration_fee}','{project_url_link}','{other_party_profile_url_link}'),array($post_data['dispute_ref_id'],$dispute_initiated_by_user_name,$disputed_against_user_name,$project_title,$dispute_negotiation_period,$admin_dispute_arbitration_fee,$project_url_link,VPATH.$disputed_against_user_data['profile_name'] ),$initiator_message);
						//$initiator_from_name = mb_encode_mimeheader($initiator_from_name, "UTF-8", 'B');
						//$initiator_from_name = mb_convert_encoding($initiator_from_name, "UTF-8");
						$initiator_from_name = '=?utf-8?B?'.base64_encode($initiator_from_name).'?=';
						
						$this->email->from ($initiator_from,$initiator_from_name);
						$this->email->to ($initiator_to);
						if($initiator_cc){
							$this->email->cc ($initiator_cc);
						}
						if($initiator_bcc){
							$this->email->bcc ($initiator_bcc);
						}
						$this->email->subject ($initiator_subject);
						$this->email->reply_to($initiator_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($initiator_message);
						$this->email->send ();
					
						
						
						// Email for disputee
						
						//When initiator gender is male
						
						if($dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($dispute_initiated_by_user_data['gender'] == 'M'){
								
								if($project_data['project_type'] == 'fulltime'){
									
									$disputee_cc = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_bcc = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_reply_to = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from_name = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_subject = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_message = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration');
									
								}else{
								
									$disputee_cc = $this->config->item('initiate_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_bcc = $this->config->item('initiate_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from = $this->config->item('initiate_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_reply_to = $this->config->item('initiate_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from_name = $this->config->item('initiate_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_subject = $this->config->item('initiate_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_message = $this->config->item('initiate_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration');
									}
								
							}else{
								
								if($project_data['project_type'] == 'fulltime'){
									
									$disputee_cc = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_bcc = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_reply_to = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from_name = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_subject = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_message = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration');
									
								}else{	
									$disputee_cc = $this->config->item('initiate_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_bcc = $this->config->item('initiate_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from = $this->config->item('initiate_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_reply_to = $this->config->item('initiate_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_from_name = $this->config->item('initiate_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_subject = $this->config->item('initiate_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration');
									$disputee_message = $this->config->item('initiate_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration');
								}	
							}	
						}else{
							if($project_data['project_type'] == 'fulltime'){
								$disputee_cc = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_bcc = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_from = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_reply_to = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_from_name = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_subject = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_message = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration');
								
							}else{	
								$disputee_cc = $this->config->item('initiate_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_bcc = $this->config->item('initiate_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_from = $this->config->item('initiate_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_reply_to = $this->config->item('initiate_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_from_name = $this->config->item('initiate_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_subject = $this->config->item('initiate_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_admin_arbitration');
								$disputee_message = $this->config->item('initiate_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_admin_arbitration');
							}	

						}	
						//$disputee_subject = mb_encode_mimeheader($disputee_subject, "UTF-8", 'B');			
						//$disputee_subject = mb_encode_mimeheader($disputee_subject, "UTF-8", 'B');			
						$disputee_to = $disputed_against_user_data['email'];
						
						if($project_data['project_type'] == 'fulltime'){
							
							$disputee_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY),$disputee_message);
							
						}else{
							$disputee_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY),$disputee_message);
						}		
						
						
						$disputee_message = str_replace(array('{dispute_reference_id}','{user_first_name_last_name_or_company_name}','{other_party_first_name_last_name_or_company_name}','{project_title}','{dispute_negotiation_period}','{dispute_initiated_date}','{admin_dispute_arbitration_fee}','{project_url_link}','{other_party_profile_url_link}'),array($post_data['dispute_ref_id'],$disputed_against_user_name,$dispute_initiated_by_user_name,$project_title,$dispute_negotiation_period,$dispute_initiated_date,$admin_dispute_arbitration_fee,$project_url_link,VPATH.$dispute_initiated_by_user_data['profile_name'] ),$disputee_message);
						
						//$disputee_from_name = mb_convert_encoding($disputee_from_name, "UTF-8");
						
						$disputee_from_name = '=?utf-8?B?'.base64_encode($disputee_from_name).'?=';
						
						
						$this->email->from ($disputee_from,$disputee_from_name);
						$this->email->to ($disputee_to);
						if($disputee_cc){
							$this->email->cc ($disputee_cc);
						}
						if($disputee_bcc){
							$this->email->bcc ($disputee_bcc);
						}
						$this->email->subject ($disputee_subject);
						$this->email->reply_to($disputee_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($disputee_message);
						$this->email->send ();
						
						
						
					}else{
							
						if($project_data['project_type'] == 'fulltime'){
							
							// Email for initiator
							$initiator_to = $dispute_initiated_by_user_data['email'];
							$initiator_cc = $this->config->item('initiate_fulltime_project_dispute_email_cc_sent_to_initiator_dispute_decided_automatic');
							$initiator_bcc = $this->config->item('initiate_fulltime_project_dispute_email_bcc_sent_to_initiator_dispute_decided_automatic');
							$initiator_from = $this->config->item('initiate_fulltime_project_dispute_email_from_sent_to_initiator_dispute_decided_automatic');
							$initiator_reply_to = $this->config->item('initiate_fulltime_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_automatic');
							$initiator_from_name = $this->config->item('initiate_fulltime_project_dispute_email_from_name_sent_to_initiator_dispute_decided_automatic');
							$initiator_from_name = '=?utf-8?B?'.base64_encode($initiator_from_name).'?=';
							//$initiator_subject = mb_encode_mimeheader($this->config->item('initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration'), "UTF-8", 'B');
							$initiator_subject = $this->config->item('initiate_fulltime_project_dispute_email_subject_sent_to_initiator_dispute_decided_automatic');
							
							$initiator_message = $this->config->item('initiate_fulltime_project_dispute_email_message_sent_to_initiator_dispute_decided_automatic');
							
						}else{							
							// Email for initiator
							$initiator_to = $dispute_initiated_by_user_data['email'];
							$initiator_cc = $this->config->item('initiate_project_dispute_email_cc_sent_to_initiator_dispute_decided_automatic');
							$initiator_bcc = $this->config->item('initiate_project_dispute_email_bcc_sent_to_initiator_dispute_decided_automatic');
							$initiator_from = $this->config->item('initiate_project_dispute_email_from_sent_to_initiator_dispute_decided_automatic');
							$initiator_reply_to = $this->config->item('initiate_project_dispute_email_reply_to_sent_to_initiator_dispute_decided_automatic');
							$initiator_from_name = $this->config->item('initiate_project_dispute_email_from_name_sent_to_initiator_dispute_decided_automatic');
							$initiator_from_name = '=?utf-8?B?'.base64_encode($initiator_from_name).'?=';
							//$initiator_subject = mb_encode_mimeheader($this->config->item('initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_admin_arbitration'), "UTF-8", 'B');
							$initiator_subject = $this->config->item('initiate_project_dispute_email_subject_sent_to_initiator_dispute_decided_automatic');
							
							$initiator_message = $this->config->item('initiate_project_dispute_email_message_sent_to_initiator_dispute_decided_automatic');
						}
						
						if($project_data['project_type'] == 'fulltime'){
							$initiator_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY),$initiator_message);
						}else{
							$initiator_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY),$initiator_message);
						}			
						
						$initiator_message = str_replace(array('{dispute_reference_id}','{user_first_name_last_name_or_company_name}','{other_party_first_name_last_name_or_company_name}','{project_title}','{dispute_negotiation_period}','{project_url_link}','{other_party_profile_url_link}'),array($post_data['dispute_ref_id'],$dispute_initiated_by_user_name,$disputed_against_user_name,$project_title,$dispute_negotiation_period,$project_url_link,VPATH.$disputed_against_user_data['profile_name'] ),$initiator_message);
						
						//$initiator_from_name = mb_encode_mimeheader($initiator_from_name, "UTF-8", 'B');
						$initiator_from_name = mb_convert_encoding($initiator_from_name, "UTF-8");
						
						
						$this->email->from ($initiator_from,$initiator_from_name);
						$this->email->to ($initiator_to);
						if($initiator_cc){
							$this->email->cc ($initiator_cc);
						}
						if($initiator_bcc){
							$this->email->bcc ($initiator_bcc);
						}
						$this->email->subject ($initiator_subject);
						$this->email->reply_to($initiator_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($initiator_message);
						$this->email->send ();
					
						// Email for disputee
						//When initiator gender is male
						
						if($dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							if($dispute_initiated_by_user_data['gender'] == 'M'){
								
								if($project_data['project_type'] == 'fulltime'){
									$disputee_cc = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_automatic');
									$disputee_bcc = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic');
									$disputee_from = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_automatic');
									$disputee_reply_to = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic');
									$disputee_from_name = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic');
									$disputee_subject = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_automatic');
									$disputee_message = $this->config->item('initiate_fulltime_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_automatic');
								}else{
									$disputee_cc = $this->config->item('initiate_project_dispute_by_male_initiator_email_cc_sent_to_disputee_dispute_decided_automatic');
									$disputee_bcc = $this->config->item('initiate_project_dispute_by_male_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic');
									$disputee_from = $this->config->item('initiate_project_dispute_by_male_initiator_email_from_sent_to_disputee_dispute_decided_automatic');
									$disputee_reply_to = $this->config->item('initiate_project_dispute_by_male_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic');
									$disputee_from_name = $this->config->item('initiate_project_dispute_by_male_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic');
									$disputee_subject = $this->config->item('initiate_project_dispute_by_male_initiator_email_subject_sent_to_disputee_dispute_decided_automatic');
									$disputee_message = $this->config->item('initiate_project_dispute_by_male_initiator_email_message_sent_to_disputee_dispute_decided_automatic');
								}
								
							}else{
								if($project_data['project_type'] == 'fulltime'){
									
									$disputee_cc = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_automatic');
									$disputee_bcc = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic');
									$disputee_from = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_automatic');
									$disputee_reply_to = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic');
									$disputee_from_name = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic');
									$disputee_subject = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_automatic');
									$disputee_message = $this->config->item('initiate_fulltime_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_automatic');
								}else{
									$disputee_cc = $this->config->item('initiate_project_dispute_by_female_initiator_email_cc_sent_to_disputee_dispute_decided_automatic');
									$disputee_bcc = $this->config->item('initiate_project_dispute_by_female_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic');
									$disputee_from = $this->config->item('initiate_project_dispute_by_female_initiator_email_from_sent_to_disputee_dispute_decided_automatic');
									$disputee_reply_to = $this->config->item('initiate_project_dispute_by_female_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic');
									$disputee_from_name = $this->config->item('initiate_project_dispute_by_female_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic');
									$disputee_subject = $this->config->item('initiate_project_dispute_by_female_initiator_email_subject_sent_to_disputee_dispute_decided_automatic');
									$disputee_message = $this->config->item('initiate_project_dispute_by_female_initiator_email_message_sent_to_disputee_dispute_decided_automatic');
								}	
							}	
						}else{
							if($project_data['project_type'] == 'fulltime'){
								
								$disputee_cc = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_automatic');
								$disputee_bcc = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic');
								$disputee_from = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_automatic');
								$disputee_reply_to = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic');
								$disputee_from_name = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic');
								$disputee_subject = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_automatic');
								$disputee_message = $this->config->item('initiate_fulltime_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_automatic');
								
								
							}else{	
								$disputee_cc = $this->config->item('initiate_project_dispute_by_company_initiator_email_cc_sent_to_disputee_dispute_decided_automatic');
								$disputee_bcc = $this->config->item('initiate_project_dispute_by_company_initiator_email_bcc_sent_to_disputee_dispute_decided_automatic');
								$disputee_from = $this->config->item('initiate_project_dispute_by_company_initiator_email_from_sent_to_disputee_dispute_decided_automatic');
								$disputee_reply_to = $this->config->item('initiate_project_dispute_by_company_initiator_email_reply_to_sent_to_disputee_dispute_decided_automatic');
								$disputee_from_name = $this->config->item('initiate_project_dispute_by_company_initiator_email_from_name_sent_to_disputee_dispute_decided_automatic');
								$disputee_subject = $this->config->item('initiate_project_dispute_by_company_initiator_email_subject_sent_to_disputee_dispute_decided_automatic');
								$disputee_message = $this->config->item('initiate_project_dispute_by_company_initiator_email_message_sent_to_disputee_dispute_decided_automatic');
							}	

						}	
						//$disputee_subject = mb_encode_mimeheader($disputee_subject, "UTF-8", 'B');			
						//$disputee_subject = mb_encode_mimeheader($disputee_subject, "UTF-8", 'B');			
						$disputee_to = $disputed_against_user_data['email'];
						
						if($project_data['project_type'] == 'fulltime'){
							
							$disputee_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY),$disputee_message);
							
						}else{

							$disputee_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ". CURRENCY),$disputee_message);
						}			
						
						
						$disputee_message = str_replace(array('{dispute_reference_id}','{user_first_name_last_name_or_company_name}','{other_party_first_name_last_name_or_company_name}','{project_title}','{dispute_negotiation_period}','{dispute_initiated_date}','{project_url_link}','{other_party_profile_url_link}'),array($post_data['dispute_ref_id'],$disputed_against_user_name,$dispute_initiated_by_user_name,$project_title,$dispute_negotiation_period,$dispute_initiated_date,$project_url_link,VPATH.$dispute_initiated_by_user_data['profile_name'] ),$disputee_message);
						
						//$disputee_from_name = mb_convert_encoding($disputee_from_name, "UTF-8");
						$disputee_from_name = '=?utf-8?B?'.base64_encode($disputee_from_name).'?=';
						
						$this->email->from ($disputee_from,$disputee_from_name);
						$this->email->to ($disputee_to);
						if($disputee_cc){
							$this->email->cc ($disputee_cc);
						}
						if($disputee_bcc){
							$this->email->bcc ($disputee_bcc);
						}
						$this->email->subject ($disputee_subject);
						$this->email->reply_to($disputee_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($disputee_message);
						$this->email->send ();
					}
					####### Email code end here ####
					
					$data['disputed_against_user_id'] = $disputed_against_user_id;
					$data['dispute_initiated_by'] = $dispute_initiated_by;
					$data['projects_disputes_data'] = $projects_active_disputes_data;
					$data['project_type'] = $project_data['project_type'];
					
					$response['status'] = 200;
					
					######################### For heading start #####
					
					if($project_data['project_type'] == 'fixed'){
						$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
					}
					if($project_data['project_type'] == 'hourly'){
						$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
					}
					if($project_data['project_type'] == 'fulltime'){
						$minimum_required_disputed_project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
					}					
					
					
					
					if($user[0]->user_id == $projects_active_disputes_data['dispute_initiated_by_user_id']){
						if($disputed_amount < $minimum_required_disputed_project_value_for_admin_arbitration){
							
							if($project_data['project_type'] == 'fulltime'){
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_autodecided_dispute');
							
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_autodecided_dispute');
							}else{
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_autodecided_dispute');
							
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_autodecided_dispute');
							}
							
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}'),array($disputed_against_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_active_disputes_data['dispute_negotiation_end_date']))),$phase_rules_txt);
							
							
						}else{

							if($project_data['project_type'] == 'fulltime'){
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_admin_moderated_dispute');
								$phase_rules_txt = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
							}else{	
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_initiator_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_initiator_view_admin_moderated_dispute');
								$phase_rules_txt = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
							}
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}','{admin_dispute_arbitration_fee}'),array($disputed_against_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_active_disputes_data['dispute_negotiation_end_date'])),$admin_dispute_arbitration_fee),$phase_rules_txt);
									
						}
					}
					if($user[0]->user_id == $projects_active_disputes_data['disputed_against_user_id']){
						if($disputed_amount < $minimum_required_disputed_project_value_for_admin_arbitration){
							
							if($project_data['project_type'] == 'fulltime'){
								
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_autodecided_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_autodecided_dispute');
								
							}else{	
							
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_autodecided_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_autodecided_dispute');
							}
							
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}'),array($dispute_initiated_by_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_active_disputes_data['dispute_negotiation_end_date']))),$phase_rules_txt);
							
						}else{


							if($project_data['project_type'] == 'fulltime'){
								$phase_rules_heading = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_admin_moderated_dispute');
							}else{
								$phase_rules_heading = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_heading_disputee_view_admin_moderated_dispute');
								$phase_rules_txt = $this->config->item('project_dispute_details_page_dispute_under_negotiation_phase_rules_txt_disputee_view_admin_moderated_dispute');
							}
							
							if($project_data['project_type'] == 'fulltime'){
								$phase_rules_txt = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
							}else{
								$phase_rules_txt = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY),$phase_rules_txt);
							}			
							
							$phase_rules_txt = str_replace(array('{other_party_first_name_last_name_or_company_name}','{dispute_negotiation_end_date}','{admin_dispute_arbitration_fee}'),array($dispute_initiated_by_user_name,date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_active_disputes_data['dispute_negotiation_end_date'])),$admin_dispute_arbitration_fee),$phase_rules_txt);
						}		
						
					}					
					
					
					
					
					######################### For heading end #######
					$response['phase_rules_heading'] = $phase_rules_heading;
					$response['phase_rules_txt'] = $phase_rules_txt; 
					
					
					
					
					$dispute_messages = $this->Projects_disputes_model->get_disputed_project_message_row_detail($post_data['dispute_ref_id']);
					
					$response['project_dispute_details_heading'] = $this->load->view('projects_disputes/heading_dispute_details',array('disputed_initiated_status'=>$disputed_initiated_status,'project_dispute_status'=>$project_dispute_status,'dispute_initiated_by_user_name'=>$dispute_initiated_by_user_name,'disputed_against_user_name'=>$disputed_against_user_name,'disputed_amount'=>$disputed_amount,'dispute_started_heading'=>$dispute_started_heading,'dispute_closed_heading'=>$dispute_closed_heading,'project_dispute_stage'=>$project_dispute_stage), true);
					
					$response['project_dispute_message_data'] = $this->load->view('projects_disputes/dispute_message_row_project_dispute_details',array('dispute_message_data'=>$dispute_messages,'dispute_initiated_by'=>$dispute_initiated_by,'disputed_against_user_id'=>$disputed_against_user_id), true);
					if($po_id == $user[0]->user_id){
						$response['project_dispute_make_counter_offer_section_data'] = '';
						
					}else{
						$latest_counter_offer_data = array();
						
						$counter_offer_min_amount = "1 ".CURRENCY;
						$counter_offer_max_amount = str_replace(".00","",number_format($disputed_amount-1,  2, '.', ' ')).' '.CURRENCY;
						
						$validation_msg = str_replace(array('{counter_offer_min_amount}','{counter_offer_max_amount}'),array($counter_offer_min_amount,$counter_offer_max_amount),$this->config->item('project_dispute_details_page_minimum_maximum_counter_offer_validation_project_dispute_form_message'));
						
						
						$response['project_dispute_make_counter_offer_section_data'] = $this->load->view('projects_disputes/project_dispute_details_make_projects_dispute_counter_offer_section',array('project_type'=>$project_data['project_type'],'disputed_amount'=>$disputed_amount,'projects_disputes_data'=>$projects_active_disputes_data,'latest_counter_offer_data'=>$latest_counter_offer_data,'disputed_against_user_id'=>$disputed_against_user_id,'disputed_against_user_name'=>$disputed_against_user_name,'dispute_initiated_by'=>$dispute_initiated_by,'dispute_initiated_by_user_name'=>$dispute_initiated_by_user_name,'validation_msg'=>$validation_msg), true);
					}
					
					
					
					echo json_encode($response);die;
				}
				
				
			}else{
				//echo json_encode($msg);
				echo json_encode ($validation_data_array);
				die;
			}
		}
	}
	
	
	/**
	* This function is used to make the confirmation popup for cancel the project dispute under negotiation stage by dispute initiator . - Done
	*/
	public function cancel_project_dispute_confirmation_popup_body(){
		
		if($this->input->is_ajax_request ()){
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
			
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id ){
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				}
				
				if($post_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				}				
				$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
				
				if(empty($projects_active_disputes_data)){
					if($post_data['project_type'] == 'fulltime')
					{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_already_closed_dispute')]);
						die;	
							
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_already_closed_dispute')]);
						die;
					}
				}	
				if(!empty($projects_active_disputes_data) && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] < $project_value_for_admin_arbitration){
					
					if($post_data['project_type'] == 'fulltime')
					{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}
					
				}
				if(!empty($projects_active_disputes_data) && ((strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] >= $project_value_for_admin_arbitration) || $projects_active_disputes_data['dispute_status'] == 'under_admin_review' )){	
					if($post_data['project_type'] == 'fulltime')
					{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $post_data['project_id'])
					->get()->row_array();
				}
				
				$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
				
				$disputed_against_user_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
				
				$disputed_against_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$disputed_against_user_id)->get()->row_array();
				
				$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
				
				
				$disputed_amount =  str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY;
				$service_fees =  str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY;
				
				if($post_data['project_type'] == 'fulltime' ){
					$confirmation_modal_title = $this->config->item('fulltime_project_dispute_details_page_dispute_cancel_confirmation_project_modal_title');
				}else{	
					$confirmation_modal_title = $this->config->item('project_dispute_details_page_dispute_cancel_confirmation_project_modal_title');
				}
				
				//if($post_data['project_type'] == 'fixed' ){
				$po_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_active_disputes_data['project_owner_id_of_disputed_project'];

				$sp_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_active_disputes_data['sp_winner_id_of_disputed_project'];		
					
					if($po_id == $user[0]->user_id){
						if($post_data['project_type'] == 'fixed' ){
							$confirmation_modal_body_txt = $this->config->item('fixed_budget_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body');
							$confirmation_modal_body_txt = str_replace(array('{fixed_budget_project_disputed_amount}'),array($disputed_amount),$confirmation_modal_body_txt);
						}
						if($post_data['project_type'] == 'hourly' ){
							$confirmation_modal_body_txt = $this->config->item('hourly_rate_based_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body');
							$confirmation_modal_body_txt = str_replace(array('{hourly_rate_based_project_disputed_amount}'),array($disputed_amount),$confirmation_modal_body_txt);
							
						}
						if($post_data['project_type'] == 'fulltime' ){
							$confirmation_modal_body_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_cancel_by_po_confirmation_project_modal_body');
							$confirmation_modal_body_txt = str_replace(array('{fulltime_project_disputed_amount}'),array($disputed_amount),$confirmation_modal_body_txt);
							
						}
						$confirmation_modal_body_txt = str_replace(array('{user_profile_url_link}','{user_first_name_last_name_or_company_name}'),array(VPATH.$disputed_against_user_data['profile_name'],$disputed_against_user_name),$confirmation_modal_body_txt);
					
					}
					if($sp_id == $user[0]->user_id){
						if($post_data['project_type'] == 'fixed' ){
							$confirmation_modal_body_txt = $this->config->item('fixed_budget_project_dispute_details_page_dispute_cancel_by_sp_confirmation_project_modal_body');
						
							$confirmation_modal_body_txt = str_replace(array('{fixed_budget_project_disputed_amount}'),array($disputed_amount),$confirmation_modal_body_txt);
						
						
						}
						if($post_data['project_type'] == 'hourly' ){
							$confirmation_modal_body_txt = $this->config->item('hourly_rate_based_project_dispute_details_page_dispute_cancel_by_sp_confirmation_project_modal_body');
							$confirmation_modal_body_txt = str_replace(array('{hourly_rate_based_project_disputed_amount}'),array($disputed_amount),$confirmation_modal_body_txt);
						}
						if($post_data['project_type'] == 'fulltime' ){
							$confirmation_modal_body_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_cancel_by_sp_confirmation_project_modal_body');
							$confirmation_modal_body_txt = str_replace(array('{fulltime_project_disputed_amount}'),array($disputed_amount),$confirmation_modal_body_txt);
						}
						
						$confirmation_modal_body_txt = str_replace(array('{user_profile_url_link}','{user_first_name_last_name_or_company_name}'),array(VPATH.$disputed_against_user_data['profile_name'],$disputed_against_user_name),$confirmation_modal_body_txt);
					
					}
					
					if($post_data['project_type'] == 'fulltime' ){
						$dispute_cancel_confirmation_project_modal_btn_txt = $this->config->item('fulltime_project_dispute_details_page_dispute_cancel_confirmation_project_modal_dispute_cancel_btn_txt');
					}else{
						$dispute_cancel_confirmation_project_modal_btn_txt = $this->config->item('project_dispute_details_page_dispute_cancel_confirmation_project_modal_dispute_cancel_btn_txt');
					}
					
				
					$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body_txt.'</div>';
					$confirmation_modal_footer = '<button id="cancel_project_dispute" data-dispute-ref-id = "'.$post_data['dispute_ref_id'].'" type="button" class="btn blue_btn default_btn cancel_requested_escrow_sp width-auto" disabled style="opacity:0.65">'.$dispute_cancel_confirmation_project_modal_btn_txt.'</button><button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>';
				//}
				
				$confirmation_modal_body.= '<div class="row"><div class="col-md-12"></div></div>';
				$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="cancel_dispute_checkbox"><span class="checkmark"></span></label><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></div></div></div>';
				echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
				die;
				
			}else{
				show_custom_404_page(); //show custom 404 page
			
			}
				
			
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to cancel the project dispute- Done
	*/
	public function cancel_project_dispute(){
		
		if($this->input->is_ajax_request ()){
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
			
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id ){
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				}
				
				
				
				if($post_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				}				
				$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
				
				if(empty($projects_active_disputes_data)){
					if($post_data['project_type'] == 'fulltime')
					{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_already_closed_dispute')]);
						die;	
							
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_already_closed_dispute')]);
						die;
					}
				}	
				if(!empty($projects_active_disputes_data) && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] < $project_value_for_admin_arbitration){
					
					if($post_data['project_type'] == 'fulltime')
					{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}
					
				}
				if(!empty($projects_active_disputes_data) && ((strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] >= $project_value_for_admin_arbitration) || $projects_active_disputes_data['dispute_status'] == 'under_admin_review' )){	
					if($post_data['project_type'] == 'fulltime')
					{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_initiator_view_initiator_try_to_cancel_project_dispute_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $post_data['project_id'])
					->get()->row_array();
				}
				$profile_name = $project_data['profile_name'];
				$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
				if($post_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$active_dispute_counter_offer_table_name = 'fixed_budget_projects_active_disputes_counter_offers';
					$closed_dispute_table_name = 'fixed_budget_projects_closed_disputes';
					$closed_dispute_counter_offer_table_name = 'fixed_budget_projects_closed_disputes_counter_offers';
					$released_escrow_table_name = 'fixed_budget_projects_released_escrows';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$incomplete_project_table_name = 'fixed_budget_projects_incomplete';
					$completed_project_table = 'fixed_budget_projects_completed';
					
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$cancelled_escrow_tracking = 'fixed_budget_projects_cancelled_escrows_tracking';
					$disputed_escrow_history_tracking = 'fixed_budget_projects_disputed_escrows_history_tracking';
					
					//$closed_counter_offer_table = 'fixed_budget_projects_closed_disputes_counter_offers';
					$inprogress_project_table_name = 'fixed_budget_projects_progress';
					
				}	

				if($post_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$active_dispute_counter_offer_table_name = 'hourly_rate_based_projects_active_disputes_counter_offers';
					$closed_dispute_table_name = 'hourly_rate_based_projects_closed_disputes';
					$closed_dispute_counter_offer_table_name = 'hourly_rate_based_projects_closed_disputes_counter_offers';
					$released_escrow_table_name = 'hourly_rate_based_projects_released_escrows';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_project_table_name = 'hourly_rate_based_projects_incomplete';
					$completed_project_table = 'hourly_rate_based_projects_completed';
					
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$cancelled_escrow_tracking = 'hourly_rate_based_projects_cancelled_escrows_tracking';
					$disputed_escrow_history_tracking = 'hourly_rate_projects_disputed_escrows_history_tracking';
					
					//$closed_counter_offer_table = 'hourly_rate_based_projects_closed_disputes_counter_offers';
					$inprogress_project_table_name = 'hourly_rate_based_projects_progress';
					
				}
				if($post_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$active_dispute_counter_offer_table_name = 'fulltime_projects_active_disputes_counter_offers';
					$closed_dispute_table_name = 'fulltime_projects_closed_disputes';
					$closed_dispute_counter_offer_table_name = 'fulltime_projects_closed_disputes_counter_offers';
					$released_escrow_table_name = 'fulltime_projects_released_escrows';
					$cancelled_escrow_tracking = 'fulltime_projects_cancelled_escrows_tracking';
					$disputed_escrow_history_tracking = 'fulltime_projects_disputed_escrows_history_tracking';
					
				}
				
				$project_status = $project_status_table_array['project_status'];
				$check_already_closed_dispute = $this->db->where(['dispute_reference_id' => $post_data['dispute_ref_id']])->from($closed_dispute_table_name)->count_all_results();
				
				
				
				$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
				
				$project_dispute_details_page_url = VPATH.$this->config->item('project_dispute_details_page_url')."?id=".$post_data['dispute_ref_id'];
				
				
				/* $po_id = $projects_active_disputes_data['project_owner_id_of_disputed_project'];
				$sp_id = $projects_active_disputes_data['sp_winner_id_of_disputed_project']; */
				
				$po_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_active_disputes_data['project_owner_id_of_disputed_project'];

				$sp_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_active_disputes_data['sp_winner_id_of_disputed_project'];	
				
				
				
				$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$po_id)->get()->row_array();
				
				$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
				
				$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,email,profile_name,gender')->from('users')->where('user_id',$sp_id)->get()->row_array();
				
				$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
				$current_date = date('Y-m-d H:i:s');
				
				if($po_id == $user[0]->user_id && $check_already_closed_dispute == 0){
					
					$projects_closed_disputes_data = $projects_active_disputes_data;
					
					unset($projects_closed_disputes_data['id']);
					unset($projects_closed_disputes_data['disputed_latest_message_sent_time']);
					
					$projects_closed_disputes_data['dispute_end_date'] =$current_date; 
					$projects_closed_disputes_data['disputed_winner_id'] =$sp_id; 
					$projects_closed_disputes_data['disputed_final_settlement_amount'] =$projects_active_disputes_data['disputed_amount']; 
					$projects_closed_disputes_data['disputed_final_settlement_service_fees'] =$projects_active_disputes_data['disputed_service_fees']; 
					$projects_closed_disputes_data['dispute_status'] =($post_data['project_type'] == 'fulltime')?'dispute_cancelled_by_initiator_employer':'dispute_cancelled_by_initiator_po'; 
					
				
					if($this->db->insert ($closed_dispute_table_name, $projects_closed_disputes_data)){
						
						$this->db->update('projects_disputes_attachments', ['dispute_status'=>'closed'], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						
						####\
						$this->db->delete($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']]);
						// move data of counter offer
						$counter_offer_result_array = $this->db // get the user detail
						->select('*')
						->from($active_dispute_counter_offer_table_name)
						->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])
						->get()->result_array();

						if(!empty($counter_offer_result_array)){
							foreach($counter_offer_result_array as $counter_offer_key=>$counter_offer_value){
								$counter_offer_value['counter_status'] = ($post_data['project_type'] == 'fulltime')?'dispute_cancelled_by_initiator_employer':'dispute_cancelled_by_initiator_po';
								unset($counter_offer_value['id']);
								$this->db->insert($closed_dispute_counter_offer_table_name, $counter_offer_value);
							}
							$this->db->delete($active_dispute_counter_offer_table_name, ['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']]);
							
						}


						//if($post_data['project_type'] == 'fixed'){
						if($post_data['project_type'] == 'fixed'){	
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_cancelled_dispute_project_user_activity_log_displayed_message'); 
							
							$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
							
						}
						if($post_data['project_type'] == 'hourly'){	
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_cancelled_dispute_project_user_activity_log_displayed_message'); 
							
							$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						if($post_data['project_type'] == 'fulltime'){	
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_cancelled_dispute_project_user_activity_log_displayed_message'); 
							
							$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						
						$po_activity_log_message = str_replace(array('{user_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}'),array($sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id']),$po_activity_log_message);
						
						if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							
							if($po_data['gender'] == 'M'){
								if($post_data['project_type'] == 'fixed'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
							}else{
								if($post_data['project_type'] == 'fixed'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}

							}
							$sp_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($po_name),$sp_activity_log_message);
							
						}else{
							if($post_data['project_type'] == 'fixed'){
								$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'hourly'){
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'fulltime'){
								$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_cancelled_dispute_project_user_activity_log_displayed_message'); 
							}
							$sp_activity_log_message = str_replace(array('{user_company_name}'),array($po_name),$sp_activity_log_message);
							
						}
						if($post_data['project_type'] == 'fixed'){
							$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);		
						}
						if($post_data['project_type'] == 'hourly'){
							$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);		
						}
						if($post_data['project_type'] == 'fulltime'){
							$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);		
						}
						
						$sp_activity_log_message = str_replace(array('{po_profile_url_link}','{dispute_reference_id}'),array(VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],),$sp_activity_log_message);			
						//}
						user_display_log($po_activity_log_message,$po_data['user_id']); 
						user_display_log($sp_activity_log_message,$sp_data['user_id']); 
						
						$digits = $this->config->item('reference_id_digits_limit');
						$vat_percentage = $this->config->item('vat_percentage');
						
						$digits = $this->config->item('reference_id_digits_limit');
						
						if($post_data['project_type'] == 'fixed'){	
						
							$release_escrow_reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
							$charged_service_fees_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
							$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();

							$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();
							
							
							if(empty($latest_released_escrow_data)) {
								$release_escrow_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
							} else {
								$exclude_inital_number = str_replace($release_escrow_reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
								$exclude_inital_number = ltrim($exclude_inital_number, '0');
								$exclude_inital_number = (int)$exclude_inital_number + 1;
								$release_escrow_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
							}
							
							

							if(empty($latest_charged_service_fees)) {
								$charged_service_fees_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
							} else {
								$exclude_inital_number = str_replace($charged_service_fees_reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
								$exclude_inital_number = ltrim($exclude_inital_number, '0');
								$exclude_inital_number = (int)$exclude_inital_number + 1;
								$charged_service_fees_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
							}
						}

						if($post_data['project_type'] == 'fulltime'){	
						
							$release_escrow_reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
							$charged_service_fees_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
							$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();

							$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();
							
							
							if(empty($latest_released_escrow_data)) {
								$release_escrow_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
							} else {
								$exclude_inital_number = str_replace($release_escrow_reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
								$exclude_inital_number = ltrim($exclude_inital_number, '0');
								$exclude_inital_number = (int)$exclude_inital_number + 1;
								$release_escrow_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
							}
							
							

							if(empty($latest_charged_service_fees)) {
								$charged_service_fees_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
							} else {
								$exclude_inital_number = str_replace($charged_service_fees_reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
								$exclude_inital_number = ltrim($exclude_inital_number, '0');
								$exclude_inital_number = (int)$exclude_inital_number + 1;
								$charged_service_fees_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
							}
						}		
						
						//}
						// move data regarding released escrows 
						if($post_data['project_type'] == 'fulltime'){
							
							$released_escrow_data['fulltime_project_id'] = $projects_active_disputes_data['disputed_fulltime_project_id'];
							$released_escrow_data['employer_id'] = $projects_active_disputes_data['employer_id_of_disputed_fulltime_project'];
							$released_escrow_data['employee_id'] = $projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project'];

							
						}else{
							$released_escrow_data['project_id'] = $projects_active_disputes_data['disputed_project_id'];
							$released_escrow_data['project_owner_id'] = $projects_active_disputes_data['project_owner_id_of_disputed_project'];
							$released_escrow_data['winner_id'] = $projects_active_disputes_data['sp_winner_id_of_disputed_project'];
						}
						
						
						$released_escrow_data['payment_via_dispute'] = 'Y';
						$released_escrow_data['escrow_payment_release_date'] = $current_date;
						$released_escrow_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
						
						if($post_data['project_type'] == 'fixed'){
							$released_escrow_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
							$released_escrow_data['released_escrow_payment_amount'] = $projects_active_disputes_data['disputed_amount'];
							$released_escrow_data['service_fee_charges'] = $projects_active_disputes_data['disputed_service_fees'];
							$released_escrow_data['total_escrow_payment_value'] = $projects_active_disputes_data['disputed_amount']+$projects_active_disputes_data['disputed_service_fees'];
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							
							############# charged fees start ##
									
							$charged_service_fees_data['charged_service_fee_reference_id'] = $charged_service_fees_reference_id;
							$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
							$charged_service_fees_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
							$charged_service_fees_data['project_owner_id'] = $released_escrow_data['project_owner_id'];
							
							$charged_service_fees_data['payment_via_dispute'] = 'Y';
							$charged_service_fees_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
							
							$charged_service_fees_data['winner_id'] = $released_escrow_data['winner_id'];
							
							$charged_service_fees_data['project_type'] = 'fixed_budget';
							$charged_service_fees_data['escrow_payment_release_date'] = $released_escrow_data['escrow_payment_release_date'];
							$charged_service_fees_data['released_escrow_payment_amount'] = $released_escrow_data['released_escrow_payment_amount'];
							$charged_service_fees_data['charged_service_fee_value'] = $released_escrow_data['service_fee_charges'];
							
							$charged_service_fees_data['vat_percentage_value'] = $vat_percentage;
							
							$service_fee_value_excl_vat = ($released_escrow_data['service_fee_charges'] * 100 ) / ($vat_percentage + 100);
							
							$vat_amount = $released_escrow_data['service_fee_charges'] - $service_fee_value_excl_vat;
							
							$charged_service_fees_data['charged_service_fee_value_excl_vat'] = $service_fee_value_excl_vat;
						
							$charged_service_fees_data['charged_service_fee_vat_amount_value'] = $vat_amount;
							
							
							
							$this->Projects_disputes_model->insert_data_into_charged_service_fees_tracking_disputes($charged_service_fees_data, $released_escrow_data);
							############# charged fees end ##
							
						}
						if($post_data['project_type'] == 'fulltime'){
							$released_escrow_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
							$released_escrow_data['released_escrow_payment_amount'] = $projects_active_disputes_data['disputed_amount'];
							$released_escrow_data['service_fee_charges'] = $projects_active_disputes_data['disputed_service_fees'];
							$released_escrow_data['total_escrow_payment_value'] = $projects_active_disputes_data['disputed_amount']+$projects_active_disputes_data['disputed_service_fees'];
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							
							############# charged fees start ##
									
							$charged_service_fees_data['charged_service_fee_reference_id'] = $charged_service_fees_reference_id;
							$charged_service_fees_data['project_id'] = $released_escrow_data['fulltime_project_id'];
							$charged_service_fees_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
							$charged_service_fees_data['project_owner_id'] = $released_escrow_data['employer_id'];
							
							$charged_service_fees_data['payment_via_dispute'] = 'Y';
							$charged_service_fees_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
							
							$charged_service_fees_data['winner_id'] = $released_escrow_data['employee_id'];
							
							$charged_service_fees_data['project_type'] = 'fulltime';
							$charged_service_fees_data['escrow_payment_release_date'] = $released_escrow_data['escrow_payment_release_date'];
							$charged_service_fees_data['released_escrow_payment_amount'] = $released_escrow_data['released_escrow_payment_amount'];
							$charged_service_fees_data['charged_service_fee_value'] = $released_escrow_data['service_fee_charges'];
							
							$charged_service_fees_data['vat_percentage_value'] = $vat_percentage;
							
							$service_fee_value_excl_vat = ($released_escrow_data['service_fee_charges'] * 100 ) / ($vat_percentage + 100);
							
							$vat_amount = $released_escrow_data['service_fee_charges'] - $service_fee_value_excl_vat;
							
							$charged_service_fees_data['charged_service_fee_value_excl_vat'] = $service_fee_value_excl_vat;
						
							$charged_service_fees_data['charged_service_fee_vat_amount_value'] = $vat_amount;
							
							
							$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
							############# charged fees end ##
						}
						
						if($post_data['project_type'] == 'hourly'){
						
							$cancelled_disputed_escrow_data = $this->db // get the user detail
							->select('*')
							->from($disputed_escrow_history_tracking)
							->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])
							->get()->result_array();

							if(!empty($cancelled_disputed_escrow_data)){
								foreach($cancelled_disputed_escrow_data as $key=>$value){
									
									
									$release_escrow_reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
									$charged_service_fees_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
									
									$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();

									$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();
									
									
									if(empty($latest_released_escrow_data)) {
										$release_escrow_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
									} else {
										$exclude_inital_number = str_replace($release_escrow_reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
										$exclude_inital_number = ltrim($exclude_inital_number, '0');
										$exclude_inital_number = (int)$exclude_inital_number + 1;
										$release_escrow_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
									}
									
									

									if(empty($latest_charged_service_fees)) {
										$charged_service_fees_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
									} else {
										$exclude_inital_number = str_replace($charged_service_fees_reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
										$exclude_inital_number = ltrim($exclude_inital_number, '0');
										$exclude_inital_number = (int)$exclude_inital_number + 1;
										$charged_service_fees_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
									}
									
									
									
									$released_escrow_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
									$released_escrow_data['released_escrow_payment_amount'] = $value['created_escrow_amount'];
									$released_escrow_data['service_fee_charges'] = $value['service_fee_charges'];
									$released_escrow_data['total_escrow_payment_value'] = $value['created_escrow_amount']+$value['service_fee_charges'];
									
									
									$released_escrow_data['released_escrow_payment_number_of_hours'] =$value['escrow_considered_number_of_hours'];
									
									$released_escrow_data['released_escrow_payment_hourly_rate'] =$value['escrow_considered_hourly_rate'];
									$this->db->insert ($released_escrow_table_name, $released_escrow_data);
									
									############# charged fees start ##
									
									$charged_service_fees_data['charged_service_fee_reference_id'] = $charged_service_fees_reference_id;
									$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
									$charged_service_fees_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
									$charged_service_fees_data['project_owner_id'] = $released_escrow_data['project_owner_id'];
									
									$charged_service_fees_data['payment_via_dispute'] = 'Y';
									$charged_service_fees_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
									$charged_service_fees_data['winner_id'] = $released_escrow_data['winner_id'];
									$charged_service_fees_data['project_type'] = 'hourly_rate';
									$charged_service_fees_data['escrow_payment_release_date'] = $released_escrow_data['escrow_payment_release_date'];
									$charged_service_fees_data['released_escrow_payment_amount'] = $released_escrow_data['released_escrow_payment_amount'];
									$charged_service_fees_data['charged_service_fee_value'] = $released_escrow_data['service_fee_charges'];
									
									$charged_service_fees_data['vat_percentage_value'] = $vat_percentage;
									
									$service_fee_value_excl_vat = ($released_escrow_data['service_fee_charges'] * 100 ) / ($vat_percentage + 100);
									
									$vat_amount = $released_escrow_data['service_fee_charges'] - $service_fee_value_excl_vat;
									
									$charged_service_fees_data['charged_service_fee_value_excl_vat'] = $service_fee_value_excl_vat;
								
									$charged_service_fees_data['charged_service_fee_vat_amount_value'] = $vat_amount;
									
									
									$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
									############# charged fees end ##
									
								}
							}		
						
						}
						$this->db->set('user_account_balance', 'user_account_balance+'.$projects_active_disputes_data['disputed_amount'], false);
						$this->db->where('user_id',$sp_data['user_id']);
						$this->db->update('users_details');
						
						if($post_data['project_type'] == 'fulltime'){
							
							$released_escrow_condition = array('fulltime_project_id'=>$projects_active_disputes_data['disputed_fulltime_project_id'],'employer_id'=>$projects_active_disputes_data['employer_id_of_disputed_fulltime_project'],'employee_id'=>$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']);
							
						}else{
							$released_escrow_condition = array('project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']);
						}	
						
						$total_released_escrow = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($post_data['project_type'],$released_escrow_condition);
						
						if($post_data['project_type'] == 'fulltime'){
							$this->db->update($closed_dispute_table_name, ['fulltime_project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						}else{	
							$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						}
						if($post_data['project_type'] != 'fulltime'){
							
							
							$check_complete_bid_data_exists =  $this->db->where(['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($completed_bid_table_name)->count_all_results(); // check dsipute exists or not
							
							$released_escrow_condition = array('project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']);
							
							// update when bid exists in completed bid table
							if($check_complete_bid_data_exists != 0){
								
								$this->db->update($completed_bid_table_name, ['total_project_amount'=>$total_released_escrow], ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
								
								
							}
							
							$incomplete_bid_data = $this->db->get_where($incomplete_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
							if(!empty($incomplete_bid_data)){
								$bid_data = $incomplete_bid_data;;
							}
							$inprogress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();



							
							if(!empty($inprogress_bid_data)){
								$bid_data = $inprogress_bid_data;;
							}
							
							if(!empty($bid_data)){
								
								if($total_released_escrow >= $bid_data['initial_project_agreed_value'] && $bid_data['initial_project_agreed_value'] != 0){
									
									$completed_bid_data = $bid_data;
									$completed_bid_data['project_winner_work_completion_date'] = $current_date;
									$completed_bid_data['project_winner_work_start_date'] = $bid_data['project_start_date'];
									
									$completed_bid_data['total_project_amount'] = $total_released_escrow;
									unset($completed_bid_data['id']);
									unset($completed_bid_data['project_start_date']);
									unset($completed_bid_data['initial_project_agreed_delivery_period']);
									
									
									
									if($check_complete_bid_data_exists == 0){
										$this->db->insert ($completed_bid_table_name, $completed_bid_data);// move data to completed internally tracking
										$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
									}
									
								//if($post_data['project_type'] == 'fixed'){
									if($post_data['project_type'] == 'fixed'){
										$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_cancelled_dispute_project_completed_user_activity_log_displayed_message'); 
									}
									if($post_data['project_type'] == 'hourly'){
										$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_cancelled_dispute_project_completed_user_activity_log_displayed_message'); 
									}
									
									$po_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{sp_profile_url_link}'),array($project_url_link,$project_title,$sp_name,VPATH.$sp_data['profile_name']),$po_activity_log_message);
									
									if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
										
										if($po_data['gender'] == 'M'){
											if($post_data['project_type'] == 'fixed'){
												$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_completed_user_activity_log_displayed_message'); 
											}
											if($post_data['project_type'] == 'hourly'){
												$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_cancelled_dispute_project_completed_user_activity_log_displayed_message'); 
											}
										}else{
											if($post_data['project_type'] == 'fixed'){
												$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_completed_user_activity_log_displayed_message'); 
											}
											if($post_data['project_type'] == 'hourly'){
												$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_cancelled_dispute_project_completed_user_activity_log_displayed_message'); 
											}

										}
										
										$sp_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($po_name),$sp_activity_log_message);
										
									}else{
										if($post_data['project_type'] == 'fixed'){
											$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_completed_user_activity_log_displayed_message');
										}
										if($post_data['project_type'] == 'hourly'){
											$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_cancelled_dispute_project_completed_user_activity_log_displayed_message');
										}											
										$sp_activity_log_message = str_replace(array('{user_company_name}'),array($po_name),$sp_activity_log_message);
									}
									$sp_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{po_profile_url_link}'),array($project_url_link,$project_title,VPATH.$po_data['profile_name']),$sp_activity_log_message);
									
									//}
									user_display_log($po_activity_log_message,$po_data['user_id']); 
									user_display_log($sp_activity_log_message,$sp_data['user_id']); 
									$this->db->delete($incomplete_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
									$this->db->delete($inprogress_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
									
									 #### project move to completed table start####
									 
									 
									 $count_incomplete_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_bid_table_name)->count_all_results();
								
									$count_inprogress_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($inprogress_bid_table_name)->count_all_results();
									
									if($count_incomplete_bids == 0 && $count_inprogress_bids ==0){
											
										$project_completed_table_data_exists = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($completed_project_table)->count_all_results();
										if($project_completed_table_data_exists == 0){
											
											
											if($project_status == 'incomplete'){
												$incomplete_project_detail = $this->db->get_where($incomplete_project_table_name, ['project_id'=>$project_data['project_id']])->row_array();
												$complete_project_detail = $incomplete_project_detail;
											}
											if($project_status == 'in_progress'){
												$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$project_data['project_id']])->row_array();
												$complete_project_detail = $inprogress_project_detail;
											}
											
											//unset($project_data['profile_name']);
											unset($complete_project_detail['id']);
											$complete_project_detail['project_completion_date'] = $current_date;
											$this->db->insert ($completed_project_table, $complete_project_detail);// 
											
											$this->db->delete($project_status_table_array['table_name'], ['project_id' =>$projects_active_disputes_data['disputed_project_id']]); 
											
											$project_id = $projects_active_disputes_data['disputed_project_id'];
											
											
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
											$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
											$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
											$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
										
											$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
											$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
											$project_completed_dir = PROJECT_COMPLETED_DIR;
											
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
											
											
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir);
											
											
											
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
											
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir, 0777);// create awaiting_moderation directory in projects folder
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id , 0777); // create the directory by using  project id
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
											if($project_status == 'incomplete'){
												$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir;
											}
											if($project_status == 'in_progress'){
												$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
											}
											
											$source_list = $this->ftp->list_files($source_path);
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
														//die("sdfsdff");
														
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
														//die("fsdffdf");
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
														
														if($project_status == 'incomplete'){
															$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
														}
														if($project_status == 'in_progress'){
															
															$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
														}
														$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
														if($file_size != '-1')
														{
															$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
															$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
															
														}
													}
												}
											}
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id))) {
												$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id);
											}
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
												$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
											}
											$this->ftp->close(); 
											
										}
										
									}
								#### project move to completed table end####
								}else{
									
									
								 // if project value is not equal to throsold value project/ bid moves to incomplete if project/ bid exists into inprogress exists	
								 
									$inprogress_bid_detail = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
									if(!empty($inprogress_bid_detail)){
										$check_already_in_complete_bid = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($incomplete_bid_table_name)->count_all_results();
										if($check_already_in_complete_bid == 0){
											unset($inprogress_bid_detail['id']);
											$this->db->insert ($incomplete_bid_table_name, $inprogress_bid_detail);
											$this->db->delete($inprogress_bid_table_name, ['project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id]); 
											 
											//$count_complete_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($completed_bid_table_name)->count_all_results();
											
											/* if($count_incomplete_bids == 0 && $count_complete_bids == 0){ */
											$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']])->row_array();
											
											if(!empty($inprogress_project_detail)){
												
												$check_already_in_complete_project = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_project_table_name)->count_all_results();
												
												if($check_already_in_complete_project == 0){
													
													unset($inprogress_project_detail['id']);
													$this->db->insert ($incomplete_project_table_name, $inprogress_project_detail);
													
													$this->db->delete($inprogress_project_table_name, ['project_id'=>$post_data['project_id']]); 
													
													
													######## connectivity of remote server start#########
													$this->load->library('ftp');
													$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
													$config['ftp_username'] = FTP_USERNAME;
													$config['ftp_password'] = FTP_PASSWORD;
													$config['ftp_port'] 	= FTP_PORT;
													$config['debug']    = TRUE;
													$this->ftp->connect($config); 
													######## connectivity of remote server end #######
													
													$project_id = $projects_active_disputes_data['disputed_project_id'];
													$users_ftp_dir 	= USERS_FTP_DIR; 
													$projects_ftp_dir = PROJECTS_FTP_DIR;
													$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
													$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
													
													$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
													$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir);
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR);
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir);
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
													
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir, 0777);// create awaiting_moderation directory in projects folder
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id , 0777); // create the directory by using  project id
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
													$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR;
													
													$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
													
													
													$source_list = $this->ftp->list_files($source_path);
													if(!empty($source_list)) {
														foreach($source_list as $path) {
															$arr = explode('/', $path);
															$file_size = $this->ftp->get_filesize($path);
															if($file_size != '-1') {
																$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir.end($arr);
																$this->ftp->move($path, $destination_path);
															}
														}
													}
													$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
													
													if(!empty($bid_attachments)){
														foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
															$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
															if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
																//die("sdfsdff");
																
																$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir);
																
																
																//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
																
																
																$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
																
																//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
																//die("fsdffdf");
																
																
																	
																$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																
																$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
																if($file_size != '-1')
																{
																	$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																	$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
																	
																}
															}
														}
													}
													
													if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
														$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
													}
													$this->ftp->close();
													
												}	
												
											}
												
											//}
										}
									}
								}	
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
						$this->load->library ('email');
						$this->email->initialize($config);
						// email for po

						$po_to = $po_data['email'];
						if($post_data['project_type'] == 'fulltime'){
							
							$po_cc = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_cc_sent_to_employer');
							$po_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_bcc_sent_to_employer');
							$po_from = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_from_sent_to_employer');
							$po_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_reply_to_sent_to_employer');
							$po_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_from_name_sent_to_employer');
							$po_subject = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_subject_sent_to_employer');
							$po_message = $this->config->item('fulltime_project_dispute_cancelled_by_employer_email_message_sent_to_employer');
							$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_message);
							
						}else{	
							$po_cc = $this->config->item('project_dispute_cancelled_by_po_email_cc_sent_to_po');
							$po_bcc = $this->config->item('project_dispute_cancelled_by_po_email_bcc_sent_to_po');
							$po_from = $this->config->item('project_dispute_cancelled_by_po_email_from_sent_to_po');
							$po_reply_to = $this->config->item('project_dispute_cancelled_by_po_email_reply_to_sent_to_po');
							$po_from_name = $this->config->item('project_dispute_cancelled_by_po_email_from_name_sent_to_po');
							$po_subject = $this->config->item('project_dispute_cancelled_by_po_email_subject_sent_to_po');
							$po_message = $this->config->item('project_dispute_cancelled_by_po_email_message_sent_to_po');
								
							$po_message = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_message);	
						}
						$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
						
						$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$po_message);
						
						$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
						$this->email->from ($po_from,$po_from_name);
						$this->email->to ($po_to);
						if($po_cc){
							$this->email->cc ($po_cc);
						}
						if($po_bcc){
							$this->email->bcc ($po_bcc);
						}
						$this->email->subject ($po_subject);
						$this->email->reply_to($po_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($po_message);
						$this->email->send ();

						// email for sp
						$sp_to = $sp_data['email'];
						if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								
							if($po_data['gender'] == 'M'){
								if($post_data['project_type'] == 'fulltime'){
									
									$sp_cc = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_cc_sent_to_employee');
									$sp_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_bcc_sent_to_employee');
									$sp_from = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_from_sent_to_employee');
									$sp_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_reply_to_sent_to_employee');
									$sp_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_from_name_sent_to_employee');
									$sp_subject = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_subject_sent_to_employee');
									$sp_message = $this->config->item('fulltime_project_dispute_cancelled_by_male_employer_email_message_sent_to_employee');
									
								}else{
									
									$sp_cc = $this->config->item('project_dispute_cancelled_by_male_po_email_cc_sent_to_sp');
									$sp_bcc = $this->config->item('project_dispute_cancelled_by_male_po_email_bcc_sent_to_sp');
									$sp_from = $this->config->item('project_dispute_cancelled_by_male_po_email_from_sent_to_sp');
									$sp_reply_to = $this->config->item('project_dispute_cancelled_by_male_po_email_reply_to_sent_to_sp');
									$sp_from_name = $this->config->item('project_dispute_cancelled_by_male_po_email_from_name_sent_to_sp');
									$sp_subject = $this->config->item('project_dispute_cancelled_by_male_po_email_subject_sent_to_sp');
									$sp_message = $this->config->item('project_dispute_cancelled_by_male_po_email_message_sent_to_sp');
								}
								
							}else{
								
								if($post_data['project_type'] == 'fulltime'){
									
									$sp_cc = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_cc_sent_to_employee');
									$sp_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_bcc_sent_to_employee');
									$sp_from = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_from_sent_to_employee');
									$sp_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_reply_to_sent_to_employee');
									$sp_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_from_name_sent_to_employee');
									$sp_subject = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_subject_sent_to_employee');
									$sp_message = $this->config->item('fulltime_project_dispute_cancelled_by_female_employer_email_message_sent_to_employee');
									
								}else{	
									$sp_cc = $this->config->item('project_dispute_cancelled_by_female_po_email_cc_sent_to_sp');
									$sp_bcc = $this->config->item('project_dispute_cancelled_by_female_po_email_bcc_sent_to_sp');
									$sp_from = $this->config->item('project_dispute_cancelled_by_female_po_email_from_sent_to_sp');
									$sp_reply_to = $this->config->item('project_dispute_cancelled_by_female_po_email_reply_to_sent_to_sp');
									$sp_from_name = $this->config->item('project_dispute_cancelled_by_female_po_email_from_name_sent_to_sp');
									$sp_subject = $this->config->item('project_dispute_cancelled_by_female_po_email_subject_sent_to_sp');
									$sp_message = $this->config->item('project_dispute_cancelled_by_female_po_email_message_sent_to_sp');
								}

							}
							$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
							
							if($post_data['project_type'] == 'fulltime'){
								$sp_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_message);
							}else{
								$sp_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_message);
							}	
							
							
							$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_first_name_last_name}','{po_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$sp_message);
							
						}else{

							if($post_data['project_type'] == 'fulltime'){
								
								$sp_cc = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_cc_sent_to_employee');
								$sp_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_bcc_sent_to_employee');
								$sp_from = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_from_sent_to_employee');
								$sp_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_reply_to_sent_to_employee');
								$sp_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_from_name_sent_to_employee');
								$sp_subject = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_subject_sent_to_employee');
								$sp_subject = str_replace(array('{dispute_reference_id}','{po_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
								$sp_message = $this->config->item('fulltime_project_dispute_cancelled_by_company_employer_email_message_sent_to_employee');
								
							}else{
								$sp_cc = $this->config->item('project_dispute_cancelled_by_company_po_email_cc_sent_to_sp');
								$sp_bcc = $this->config->item('project_dispute_cancelled_by_company_po_email_bcc_sent_to_sp');
								$sp_from = $this->config->item('project_dispute_cancelled_by_company_po_email_from_sent_to_sp');
								$sp_reply_to = $this->config->item('project_dispute_cancelled_by_company_po_email_reply_to_sent_to_sp');
								$sp_from_name = $this->config->item('project_dispute_cancelled_by_company_po_email_from_name_sent_to_sp');
								$sp_subject = $this->config->item('project_dispute_cancelled_by_company_po_email_subject_sent_to_sp');
								$sp_subject = str_replace(array('{dispute_reference_id}','{po_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
								$sp_message = $this->config->item('project_dispute_cancelled_by_company_po_email_message_sent_to_sp');
							}
							
							if($post_data['project_type'] == 'fulltime'){
								$sp_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_message);
								
							}else{
								$sp_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_message);
							}		
							
							$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_company_name}','{po_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$sp_message);
						}
						
						
						$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';

						$this->email->from ($sp_from,$sp_from_name);
						$this->email->to ($sp_to);
						if($sp_cc){
							$this->email->cc ($sp_cc);
						}
						if($sp_bcc){
							$this->email->cc ($sp_bcc);
						}
						$this->email->subject ($sp_subject);
						$this->email->reply_to($sp_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($sp_message);
						$this->email->send ();
						
						
					}
				}else if($sp_id == $user[0]->user_id && $check_already_closed_dispute == 0){
					
					$projects_closed_disputes_data = $projects_active_disputes_data;
					
					unset($projects_closed_disputes_data['id']);
					unset($projects_closed_disputes_data['disputed_latest_message_sent_time']);
					$projects_closed_disputes_data['dispute_end_date'] =$current_date; 
					$projects_closed_disputes_data['disputed_winner_id'] =$po_id; 
					
					$projects_closed_disputes_data['disputed_final_settlement_amount'] =$projects_active_disputes_data['disputed_amount']; 
					$projects_closed_disputes_data['disputed_final_settlement_service_fees'] =$projects_active_disputes_data['disputed_service_fees']; 
					$projects_closed_disputes_data['dispute_status'] =($post_data['project_type'] == 'fulltime')?'dispute_cancelled_by_initiator_employee':'dispute_cancelled_by_initiator_sp'; 
					
					
					if($this->db->insert ($closed_dispute_table_name, $projects_closed_disputes_data)){
						$this->db->update('projects_disputes_attachments', ['dispute_status'=>'closed'], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						
						$this->db->delete($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']]);
						
						
						$cancelled_escrow_data['dispute_reference_id'] = $post_data['dispute_ref_id'];
						$cancelled_escrow_data['cancelled_via_dispute'] = 'Y';
						$cancelled_escrow_data['escrow_cancellation_date'] = $current_date;
						
						if($post_data['project_type'] == 'fulltime'){
							
							$cancelled_escrow_data['fulltime_project_id'] = $projects_active_disputes_data['disputed_fulltime_project_id'];
							$cancelled_escrow_data['employer_id'] = $projects_active_disputes_data['employer_id_of_disputed_fulltime_project'];
							$cancelled_escrow_data['employee_id'] = $projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project'];
							
						}else{	
						
							$cancelled_escrow_data['project_id'] = $projects_active_disputes_data['disputed_project_id'];
							$cancelled_escrow_data['project_owner_id'] = $projects_active_disputes_data['project_owner_id_of_disputed_project'];
							$cancelled_escrow_data['winner_id'] = $projects_active_disputes_data['sp_winner_id_of_disputed_project'];
						}
						
						
						if($post_data['project_type'] == 'fixed'){
							// save entry into  escrow cancelled table
							$this->db->select('SUM(created_escrow_amount) as sum_created_escrow_amount_value, SUM(service_fee_charges) as sum_service_fee_charges');
							//$this->db->from($disputed_escrowd_history_tracking);
							$this->db->from($disputed_escrow_history_tracking);
							$this->db->where(['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project'],'dispute_reference_id'=>$post_data['dispute_ref_id']]);
							
							$sum_cancelled_escrow_result = $this->db->get();
							$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
							
							$cancelled_escrow_data['reverted_escrowed_amount'] = $sum_cancelled_escrow['sum_created_escrow_amount_value'];
							$cancelled_escrow_data['reverted_service_fee_charges'] = $sum_cancelled_escrow['sum_service_fee_charges'];
							$cancelled_escrow_data['total_reverted_escrow_payment_value'] = $sum_cancelled_escrow['sum_created_escrow_amount_value']+$sum_cancelled_escrow['sum_service_fee_charges'];
							$this->db->insert ($cancelled_escrow_tracking, $cancelled_escrow_data);
						}
						if($post_data['project_type'] == 'fulltime'){
							// save entry into  escrow cancelled table
							$this->db->select('SUM(created_escrow_amount) as sum_created_escrow_amount_value, SUM(service_fee_charges) as sum_service_fee_charges');
							//$this->db->from($disputed_escrowd_history_tracking);
							$this->db->from($disputed_escrow_history_tracking);
							$this->db->where(['fulltime_project_id'=>$projects_active_disputes_data['disputed_fulltime_project_id'],'employer_id'=>$projects_active_disputes_data['employer_id_of_disputed_fulltime_project'],'employee_id'=>$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project'],'dispute_reference_id'=>$post_data['dispute_ref_id']]);
							
							$sum_cancelled_escrow_result = $this->db->get();
							$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
							
							$cancelled_escrow_data['reverted_escrowed_amount'] = $sum_cancelled_escrow['sum_created_escrow_amount_value'];
							$cancelled_escrow_data['reverted_service_fee_charges'] = $sum_cancelled_escrow['sum_service_fee_charges'];
							$cancelled_escrow_data['total_reverted_escrow_payment_value'] = $sum_cancelled_escrow['sum_created_escrow_amount_value']+$sum_cancelled_escrow['sum_service_fee_charges'];
							$this->db->insert ($cancelled_escrow_tracking, $cancelled_escrow_data);
						}
						if($post_data['project_type'] == 'hourly'){
							// move data of counter offer
							$cancelled_disputed_escrow_data = $this->db // get the user detail
							->select('*')
							->from($disputed_escrow_history_tracking)
							->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])
							->get()->result_array();

							if(!empty($cancelled_disputed_escrow_data)){
								foreach($cancelled_disputed_escrow_data as $key=>$value){
									
									$cancelled_escrow_data['reverted_escrowed_amount'] = $value['created_escrow_amount'];
									
									$cancelled_escrow_data['reverted_service_fee_charges'] = $value['service_fee_charges'];
									
									$cancelled_escrow_data['total_reverted_escrow_payment_value'] = $value['created_escrow_amount']+$value['service_fee_charges'];
									
									
									$cancelled_escrow_data['reverted_escrow_considered_number_of_hours'] =$value['escrow_considered_number_of_hours'];
									
									$cancelled_escrow_data['reverted_escrow_considered_hourly_rate'] =$value['escrow_considered_hourly_rate'];
									
									$this->db->insert ($cancelled_escrow_tracking, $cancelled_escrow_data);
								}
							}
						}
						// move data of counter offer
						$counter_offer_result_array = $this->db // get the user detail
						->select('*')
						->from($active_dispute_counter_offer_table_name)
						->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])
						->get()->result_array();
						
						if(!empty($counter_offer_result_array)){
							foreach($counter_offer_result_array as $counter_offer_key=>$counter_offer_value){
								$counter_offer_value['counter_status'] = ($post_data['project_type'] == 'fulltime')?'dispute_cancelled_by_initiator_employee':'dispute_cancelled_by_initiator_sp';
								unset($counter_offer_value['id']);
								$this->db->insert($closed_dispute_counter_offer_table_name, $counter_offer_value);
							}
							$this->db->delete($active_dispute_counter_offer_table_name, ['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']]);
						}
						
						if($post_data['project_type'] == 'fulltime'){
							
							$released_escrow_condition = array('fulltime_project_id'=>$projects_active_disputes_data['disputed_fulltime_project_id'],'employer_id'=>$projects_active_disputes_data['employer_id_of_disputed_fulltime_project'],'employee_id'=>$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']);
							
						}else{
							$released_escrow_condition = array('project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']);
						}	
						
						$total_released_escrow = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($post_data['project_type'],$released_escrow_condition);
						
						if($post_data['project_type'] == 'fulltime'){
							$this->db->update($closed_dispute_table_name, ['fulltime_project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						}else{	
							$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						}
						
						
						
						
						$reverter_amount_to_po = $projects_active_disputes_data['disputed_amount']+$projects_active_disputes_data['disputed_service_fees'];
						$this->db->set('user_account_balance', 'user_account_balance+'.$reverter_amount_to_po, false);
						$this->db->where('user_id',$po_data['user_id']);
						$this->db->update('users_details');
						
						if($post_data['project_type'] != 'fulltime')
						{
						
							################## Bid and project moves to incompelete section if it is exist into in progress status
							$inprogress_bid_detail = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
							
							if(!empty($inprogress_bid_detail)){
								$check_already_in_complete_bid = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($incomplete_bid_table_name)->count_all_results();
								if($check_already_in_complete_bid == 0){
									unset($inprogress_bid_detail['id']);
									$this->db->insert ($incomplete_bid_table_name, $inprogress_bid_detail);
									$this->db->delete($inprogress_bid_table_name, ['project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id]); 
									
									$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']])->row_array();
									
									if(!empty($inprogress_project_detail)){
										
										$check_already_in_complete_project = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_project_table_name)->count_all_results();
										
										if($check_already_in_complete_project == 0){
											
											unset($inprogress_project_detail['id']);
											$this->db->insert ($incomplete_project_table_name, $inprogress_project_detail);
											
											$this->db->delete($inprogress_project_table_name, ['project_id'=>$post_data['project_id']]); 
											
											
											######## connectivity of remote server start#########
											$this->load->library('ftp');
											$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
											$config['ftp_username'] = FTP_USERNAME;
											$config['ftp_password'] = FTP_PASSWORD;
											$config['ftp_port'] 	= FTP_PORT;
											$config['debug']    = TRUE;
											$this->ftp->connect($config); 
											######## connectivity of remote server end #######
											
											$project_id = $projects_active_disputes_data['disputed_project_id'];
											$users_ftp_dir 	= USERS_FTP_DIR; 
											$projects_ftp_dir = PROJECTS_FTP_DIR;
											$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
											$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
											
											$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
											$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
											
											
											$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
											
											$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir, 0777);// create awaiting_moderation directory in projects folder
											$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id , 0777); // create the directory by using  project id
											$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR;
											
											$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
											
											
											$source_list = $this->ftp->list_files($source_path);
											if(!empty($source_list)) {
												foreach($source_list as $path) {
													$arr = explode('/', $path);
													$file_size = $this->ftp->get_filesize($path);
													if($file_size != '-1') {
														$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir.end($arr);
														$this->ftp->move($path, $destination_path);
													}
												}
											}
											$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
											
											if(!empty($bid_attachments)){
												foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
													$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
													if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
														//die("sdfsdff");
														$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
														
														$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
														//die("fsdffdf");
														
														
															
														$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
														
														$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
														if($file_size != '-1')
														{
															$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
															$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
															
														}
													}
												}
											}
											
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
												$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
											}
											$this->ftp->close();
											
										}	
										
									}
										
									//}
								}
							}
							######################################################
						}	
						//if($post_data['project_type'] == 'fixed'){
						if($post_data['project_type'] == 'fixed'){	
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_cancelled_dispute_project_user_activity_log_displayed_message');
							
							$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}
						if($post_data['project_type'] == 'hourly'){	
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_sp_cancelled_dispute_project_user_activity_log_displayed_message');
							
							$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}							
						if($post_data['project_type'] == 'fulltime'){	
							$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_cancelled_dispute_project_user_activity_log_displayed_message');
							
							$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
							
						}
						$sp_activity_log_message = str_replace(array('{user_first_name_last_name_or_company_name}','{po_profile_url_link}','{dispute_reference_id}'),array($po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id']),$sp_activity_log_message);
						
						if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							
							if($sp_data['gender'] == 'M'){
								if($post_data['project_type'] == 'fixed'){	
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){	
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_male_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){	
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}
							}else{
								if($post_data['project_type'] == 'fixed'){	
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_cancelled_dispute_project_user_activity_log_displayed_message');
								}
								if($post_data['project_type'] == 'hourly'){	
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_female_cancelled_dispute_project_user_activity_log_displayed_message');
								}
								if($post_data['project_type'] == 'fulltime'){	
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_cancelled_dispute_project_user_activity_log_displayed_message'); 
								}	

							}
							$po_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($sp_name),$po_activity_log_message);
							
						}else{
							if($post_data['project_type'] == 'fixed'){
								$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_cancelled_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'hourly'){
								$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_cancelled_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'fulltime'){	
								$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_cancelled_dispute_project_user_activity_log_displayed_message'); 
							}
							$po_activity_log_message = str_replace(array('{user_company_name}'),array($sp_name),$po_activity_log_message);
						}
						
						if($post_data['project_type'] == 'fixed'){
							$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						if($post_data['project_type'] == 'hourly'){	
							$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						if($post_data['project_type'] == 'fulltime'){	
							$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						$po_activity_log_message = str_replace(array('{user_company_name}','{po_profile_url_link}','{dispute_reference_id}'),array($sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id']),$po_activity_log_message);
						//}
						user_display_log($po_activity_log_message,$po_data['user_id']); 
						user_display_log($sp_activity_log_message,$sp_data['user_id']); 
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
						// email for sp
						
						$sp_to = $sp_data['email'];
						if($post_data['project_type'] == 'fulltime'){
							
							$sp_cc = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_cc_sent_to_employee');
							$sp_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_bcc_sent_to_employee');
							$sp_from = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_from_sent_to_employee');
							$sp_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_reply_to_sent_to_employee');
							$sp_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_from_name_sent_to_employee');
							$sp_subject = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_subject_sent_to_employee');
							$sp_message = $this->config->item('fulltime_project_dispute_cancelled_by_employee_email_message_sent_to_employee');
							$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
								
							$sp_message = str_replace(array('{fulltime_project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_message);

							
						}else{	
							$sp_cc = $this->config->item('project_dispute_cancelled_by_sp_email_cc_sent_to_sp');
							$sp_bcc = $this->config->item('project_dispute_cancelled_by_sp_email_bcc_sent_to_sp');
							$sp_from = $this->config->item('project_dispute_cancelled_by_sp_email_from_sent_to_sp');
							$sp_reply_to = $this->config->item('project_dispute_cancelled_by_sp_email_reply_to_sent_to_sp');
							$sp_from_name = $this->config->item('project_dispute_cancelled_by_sp_email_from_name_sent_to_sp');
							$sp_subject = $this->config->item('project_dispute_cancelled_by_sp_email_subject_sent_to_sp');
							$sp_message = $this->config->item('project_dispute_cancelled_by_sp_email_message_sent_to_sp');
							$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
							$sp_message = str_replace(array('{project_disputed_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY),$sp_message);
						
						}
						
						$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_first_name_last_name_or_company_name}','{po_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$sp_message);
						
						$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';
						$this->email->from ($sp_from,$sp_from_name);
						$this->email->to ($sp_to);
						if($sp_cc){
							$this->email->cc ($sp_cc);
						}
						if($sp_bcc){
							$this->email->bcc ($sp_bcc);
						}
						$this->email->subject ($sp_subject);
						$this->email->reply_to($sp_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($sp_message);
						$this->email->send ();
						
						
						// email for po
						$po_to = $po_data['email'];
						if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								
							if($sp_data['gender'] == 'M'){
								
								if($post_data['project_type'] == 'fulltime'){
									
									$po_cc = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_cc_sent_to_employer');
									$po_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_bcc_sent_to_employer');
									$po_from = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_from_sent_to_employer');
									$po_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_reply_to_sent_to_employer');
									$po_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_from_name_sent_to_employer');
									$po_subject = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_subject_sent_to_employer');
									$po_message = $this->config->item('fulltime_project_dispute_cancelled_by_male_employee_email_message_sent_to_employer');
									
								}else{
									$po_cc = $this->config->item('project_dispute_cancelled_by_male_sp_email_cc_sent_to_po');
									$po_bcc = $this->config->item('project_dispute_cancelled_by_male_sp_email_bcc_sent_to_po');
									$po_from = $this->config->item('project_dispute_cancelled_by_male_sp_email_from_sent_to_po');
									$po_reply_to = $this->config->item('project_dispute_cancelled_by_male_sp_email_reply_to_sent_to_po');
									$po_from_name = $this->config->item('project_dispute_cancelled_by_male_sp_email_from_name_sent_to_po');
									$po_subject = $this->config->item('project_dispute_cancelled_by_male_sp_email_subject_sent_to_po');
									$po_message = $this->config->item('project_dispute_cancelled_by_male_sp_email_message_sent_to_po');
								}
							}else{
								
								if($post_data['project_type'] == 'fulltime'){
									$po_cc = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_cc_sent_to_employer');
									$po_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_bcc_sent_to_employer');
									$po_from = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_from_sent_to_employer');
									$po_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_reply_to_sent_to_employer');
									$po_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_from_name_sent_to_employer');
									$po_subject = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_subject_sent_to_employer');
									$po_message = $this->config->item('fulltime_project_dispute_cancelled_by_female_employee_email_message_sent_to_employer');
									
								}else{
								
									$po_cc = $this->config->item('project_dispute_cancelled_by_female_sp_email_cc_sent_to_po');
									$po_bcc = $this->config->item('project_dispute_cancelled_by_female_sp_email_bcc_sent_to_po');
									$po_from = $this->config->item('project_dispute_cancelled_by_female_sp_email_from_sent_to_po');
									$po_reply_to = $this->config->item('project_dispute_cancelled_by_female_sp_email_reply_to_sent_to_po');
									$po_from_name = $this->config->item('project_dispute_cancelled_by_female_sp_email_from_name_sent_to_po');
									$po_subject = $this->config->item('project_dispute_cancelled_by_female_sp_email_subject_sent_to_po');
									$po_message = $this->config->item('project_dispute_cancelled_by_female_sp_email_message_sent_to_po');
								}

							}
							$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
							
							if($post_data['project_type'] == 'fulltime'){
								$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_message);
							}else{
								$po_message = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_message);
							}		
							
							$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_first_name_last_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$po_message);
							
							
						}else{

							if($post_data['project_type'] == 'fulltime'){
								
								$po_cc = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_cc_sent_to_po');
								$po_bcc = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_bcc_sent_to_po');
								$po_from = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_from_sent_to_po');
								$po_reply_to = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_reply_to_sent_to_po');
								$po_from_name = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_from_name_sent_to_po');
								$po_subject = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_subject_sent_to_po');
								$po_subject = str_replace(array('{dispute_reference_id}','{sp_company_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
								$po_message = $this->config->item('fulltime_project_dispute_cancelled_by_company_sp_email_message_sent_to_po');
								
								
								$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_message);

								
							}else{
								$po_cc = $this->config->item('project_dispute_cancelled_by_company_sp_email_cc_sent_to_po');
								$po_bcc = $this->config->item('project_dispute_cancelled_by_company_sp_email_bcc_sent_to_po');
								$po_from = $this->config->item('project_dispute_cancelled_by_company_sp_email_from_sent_to_po');
								$po_reply_to = $this->config->item('project_dispute_cancelled_by_company_sp_email_reply_to_sent_to_po');
								$po_from_name = $this->config->item('project_dispute_cancelled_by_company_sp_email_from_name_sent_to_po');
								$po_subject = $this->config->item('project_dispute_cancelled_by_company_sp_email_subject_sent_to_po');
								$po_subject = str_replace(array('{dispute_reference_id}','{sp_company_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
								
								$po_message = $this->config->item('project_dispute_cancelled_by_company_sp_email_message_sent_to_po');
								$po_message = str_replace(array('{project_disputed_amount}','{project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_message);
							}
							$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$po_message);
						}
						$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
						
						
						$this->email->from ($po_from,$po_from_name);
						$this->email->to ($po_to);
						if($po_cc){
							$this->email->cc ($po_cc);
						}
						if($po_bcc){
							$this->email->bcc ($po_bcc);
						}
						$this->email->subject ($po_subject);
						$this->email->reply_to($po_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($po_message);
						$this->email->send ();
						// End of email
					}

				}	
				
				$disputed_initiated_status = 1;
				$projects_disputes_data = $this->db->get_where($closed_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
				
				
				
				
				$counter_offer_listing_other_party = $this->db // get the user detail
				->select('*')
				->from($closed_dispute_counter_offer_table_name)
				->where('dispute_reference_id', $post_data['dispute_ref_id'])
				->where('countered_by_user_id !=', $user[0]->user_id)
				->order_by('counter_date ASC')
				->get()->result_array();
				
				$counter_offer_listing_login_user = $this->db // get the user detail
				->select('*')
				->from($closed_dispute_counter_offer_table_name)
				->where('dispute_reference_id', $post_data['dispute_ref_id'])
				->where('countered_by_user_id', $user[0]->user_id)
				->order_by('counter_date ASC')
				->get()->result_array();
				
				$dispute_initiated_by = $projects_disputes_data['dispute_initiated_by_user_id'];
				$disputed_against_user_id = $projects_disputes_data['disputed_against_user_id'];
				
				$dispute_initiated_by_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$projects_disputes_data['dispute_initiated_by_user_id'])->get()->row_array();
				
				$disputed_against_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$projects_disputes_data['disputed_against_user_id'])->get()->row_array();
				
				$dispute_initiated_by_user_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] : $dispute_initiated_by_user_data['company_name'];
				
				$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
				
				
				$latest_counter_offer_data = $this->db->select('*')->from($closed_dispute_counter_offer_table_name)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				
				$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_resolved_txt');
				
				$dispute_heading = '<p>Dispute status:<span> '.$this->config->item('project_dispute_details_page_dispute_resolved_phase').'</span>&nbsp;&nbsp;Dispute Closed Time : <span>'. date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_disputes_data['dispute_end_date'])).'</span></p>';
				$response['dispute_heading'] = $dispute_heading;
				//$response['dispute_closed_reason']=  $this->load->view('projects_disputes/project_dispute_details_closed_dispute_section', array('dispute_closed_reason'=>$dispute_closed_reason,'dispute_result_txt'=>$dispute_result_txt), true);
				
				$response['dispute_closed_reason']  = $this->load->view('projects_disputes/project_dispute_details_closed_dispute_section', array('projects_disputes_data'=>$projects_disputes_data,'sp_data'=>$sp_data,'po_data'=>$po_data,'project_type'=>$post_data['project_type']), true);
				
				
				$response['project_dispute_make_counter_offer_section_data'] = $this->load->view('projects_disputes/project_dispute_details_make_projects_dispute_counter_offer_section',array('dispute_initiated_by'=>$dispute_initiated_by,'disputed_against_user_id'=>$disputed_against_user_id,'latest_counter_offer_data'=>$latest_counter_offer_data,'projects_disputes_data'=>$projects_disputes_data,'counter_offer_listing_other_party'=>$counter_offer_listing_other_party,'counter_offer_listing_login_user'=>$counter_offer_listing_login_user,'disputed_against_user_name'=>$disputed_against_user_name,'dispute_initiated_by_user_name'>$dispute_initiated_by_user_name), true);
				$response['status'] = 200;
				$response['location'] ='';
				
				
				echo json_encode($response);die;
				
			}else{
				show_custom_404_page(); //show custom 404 page
			
			}
				
			
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	// This function is using to save dispute message 
	public function post_project_dispute_message(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
			die;
		}
		if($this->input->method(TRUE) === 'POST'){
			$user = $this->session->userdata('user');
			$post_data = $this->input->post ();
			
			if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
			 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			 die;
			}
			if($post_data['project_type'] == 'fixed'){
				$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
			}if($post_data['project_type'] == 'hourly'){
				$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
			}if($post_data['project_type'] == 'fulltime'){
				$active_dispute_table_name = 'fulltime_projects_active_disputes';
				$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
			}
			
			$active_dispute_data = $this->db // get the active dispute detail
			->select('*')
			->from($active_dispute_table_name)
			->where('dispute_reference_id', $post_data['dispute_ref_id'])
			->get()->row_array();
			if(empty($active_dispute_data)){
				if($post_data['project_type'] == 'fulltime'){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_post_message_on_already_closed_dispute')]);
					die;
				}else{
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_post_message_on_already_closed_dispute')]);
					die;
				}
			}
	
			if(!empty($active_dispute_data) && strtotime($active_dispute_data['dispute_negotiation_end_date']) < time() && $active_dispute_data['disputed_amount'] < $project_value_for_admin_arbitration){
				
				if($post_data['project_type'] == 'fulltime'){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
					die;
				}else{
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
					die;
				}
			}
			if(!empty($active_dispute_data) && ((strtotime($active_dispute_data['dispute_negotiation_end_date']) < time() && $active_dispute_data['disputed_amount'] >= $project_value_for_admin_arbitration) || $active_dispute_data['dispute_status'] == 'under_admin_review') ){	
				if($post_data['project_type'] == 'fulltime'){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
					die;
				}else{
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_post_message_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
					die;
				}
			}
			$project_data['project_type'] = $post_data['project_type'];
			$validation_data_array = $this->Projects_disputes_model->project_dispute_form_validation($post_data,$project_data);
			if($validation_data_array['status'] == 'SUCCESS'){
			
				$project_id = $this->input->post ('project_id');
				$user_id = $user[0]->user_id;
				$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $post_data['project_id'])
					->get()->row_array();
				}
				
				
				
				
				$message_sent_by = $user_id;
				if($user_id == Cryptor::doDecrypt($post_data['dispute_initiated_by'])){
					$message_sent_to = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
				}else if($user_id == Cryptor::doDecrypt($post_data['disputed_against_user_id'])){
					$message_sent_to = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
				}
				
				
				$current_date = date('Y-m-d H:i:s');
				$disputes_messages_data['dispute_reference_id'] = $post_data['dispute_ref_id'];
				$disputes_messages_data['disputed_project_id']  = $post_data['project_id'];
				$disputes_messages_data['project_type'] = $post_data['project_type'];
				$disputes_messages_data['message_sent_by_user_id'] = $user_id;
				$disputes_messages_data['message_sent_to_user_id'] = $message_sent_to;
				$disputes_messages_data['description'] = trim($post_data['dispute_description']);
				$disputes_messages_data['message_sent_date'] = $current_date;
				if($this->db->insert ('projects_disputes_messages_activity_tracking', $disputes_messages_data)){
					$last_insert_id = $this->db->insert_id();
					if(!empty($post_data['attachments'])){
						foreach($post_data['attachments'] as $attachment_key=>$attachment_value){
							$this->db->update('projects_disputes_attachments', ['dispute_message_id'=>$last_insert_id], ['id'=>$attachment_value]);
						}
					}
					
					$dispute_message_sender_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name')->from('users')->where('user_id',$user_id)->get()->row_array();
					
					$dispute_sender_name = $dispute_message_sender_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_message_sender_user_data['first_name'] . ' ' . $dispute_message_sender_user_data['last_name'] : $dispute_message_sender_user_data['company_name'];
					
			
					$dispute_message_reciever_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name')->from('users')->where('user_id',$message_sent_to)->get()->row_array();
					
					$dispute_reciever_name = $dispute_message_reciever_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_message_reciever_user_data['first_name'] . ' ' . $dispute_message_reciever_user_data['last_name'] : $dispute_message_reciever_user_data['company_name'];
					
					
					
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
					
					if($post_data['project_type'] == 'fixed'){
						$sender_activity_log_message = $this->config->item('fixed_budget_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster');
						$reciever_activity_log_message = $this->config->item('fixed_budget_project_dispute_message_posted_activity_log_displayed_message_sent_to_other_party');
					}if($post_data['project_type'] == 'hourly'){
						$sender_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster');
						$reciever_activity_log_message = $this->config->item('hourly_rate_based_project_dispute_message_posted_activity_log_displayed_message_sent_to_other_party');
					}if($post_data['project_type'] == 'fulltime'){
						$sender_activity_log_message = $this->config->item('fulltime_project_dispute_message_posted_activity_log_displayed_message_sent_to_poster');
						$reciever_activity_log_message = $this->config->item('fulltime_project_dispute_message_posted_activity_log_displayed_message_sent_to_other_party');
					}
					$sender_activity_log_message = str_replace(array("{project_url_link}","{project_title}","{dispute_reference_id}","{user_first_name_last_name_or_company_name}","{user_profile_url_link}","{dispute_negotiation_end_date}"),array($project_url_link,$project_title,$post_data['dispute_ref_id'],$dispute_reciever_name,VPATH.$dispute_message_reciever_user_data['profile_name'],date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($active_dispute_data['dispute_negotiation_end_date']))),$sender_activity_log_message);
					
					
					
					$reciever_activity_log_message = str_replace(array("{project_url_link}","{project_title}","{dispute_reference_id}","{user_first_name_last_name_or_company_name}","{user_profile_url_link}","{dispute_negotiation_end_date}"),array($project_url_link,$project_title,$post_data['dispute_ref_id'],$dispute_sender_name,VPATH.$dispute_message_sender_user_data['profile_name'],date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($active_dispute_data['dispute_negotiation_end_date']))),$reciever_activity_log_message);
					
					/* echo $sender_activity_log_message;
					echo "<br>";
					echo $reciever_activity_log_message;
					die;
					 */
					
					
					user_display_log($sender_activity_log_message,$dispute_message_sender_user_data['user_id']); 
					user_display_log($reciever_activity_log_message,$dispute_message_reciever_user_data['user_id']); 
					
					#### fetch the ajax response for show dispute message
					
					$response['status'] = 200;
					$dispute_messages = $this->Projects_disputes_model->get_disputed_project_message_row_detail($post_data['dispute_ref_id']);
					$response['project_dispute_message_data'] = $this->load->view('projects_disputes/dispute_message_row_project_dispute_details',array('dispute_message_data'=>$dispute_messages,'dispute_initiated_by'=>Cryptor::doDecrypt($post_data['dispute_initiated_by']),'disputed_against_user_id'=>Cryptor::doDecrypt($post_data['disputed_against_user_id'])), true);
					echo json_encode($response);die;
					
				}
			
			
			}else{
				//echo json_encode($msg);
				echo json_encode ($validation_data_array);
				die;
			}
		}else{
			//echo json_encode($msg);
			echo json_encode ($validation_data_array);
			die;
		}
	}	
	
	// This function is using to save dispute counter offer 
	public function save_project_dispute_counter_offer(){
		if($this->input->is_ajax_request ()){
			
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				$post_data = $this->input->post ();
				
				if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				}
				
				
				
				$param_data = array();
				/* if($post_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				} */
				if($post_data['project_type'] == 'fixed'){
					//$projects_active_disputes_counter_offers_trck_table_name = 'fixed_budget_projects_active_disputes_counter_offers';
					$active_counter_offer_table = 'fixed_budget_projects_active_disputes_counter_offers';
					$dispute_active_table = 'fixed_budget_projects_active_disputes';
					$dispute_closed_table = 'fixed_budget_projects_closed_disputes';
				
				}
				if($post_data['project_type'] == 'hourly'){
					//$projects_active_disputes_counter_offers_trck_table_name = 'hourly_rate_based_projects_active_disputes_counter_offers';
					$active_counter_offer_table = 'hourly_rate_based_projects_active_disputes_counter_offers';
					$dispute_active_table = 'hourly_rate_based_projects_active_disputes';
					$dispute_closed_table = 'hourly_rate_based_projects_closed_disputes';
				
				}
				if($post_data['project_type'] == 'fulltime'){
					//$projects_active_disputes_counter_offers_trck_table_name = 'fulltime_projects_active_disputes_counter_offers';
					$active_counter_offer_table = 'fulltime_projects_active_disputes_counter_offers';
					$dispute_active_table = 'fulltime_projects_active_disputes';
					$dispute_closed_table = 'fulltime_projects_closed_disputes';
				
				}
					
				
				$active_dispute_data = $this->db // get the active dispute detail
				->select('*')
				->from($dispute_active_table)
				->where('dispute_reference_id', $post_data['dispute_ref_id'])
				->get()->row_array();
				
				if(empty($active_dispute_data)){
					
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_make_counter_offer_on_already_closed_dispute')]);
						die;
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_make_counter_offer_on_already_closed_dispute')]);
						die;
					}	
				}	
				if($post_data['project_type'] == 'fixed'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'hourly'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'fulltime'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				}				
				if(!empty($active_dispute_data) && strtotime($active_dispute_data['dispute_negotiation_end_date']) < time() && $active_dispute_data['disputed_amount'] < $project_value_for_admin_arbitration){
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}
				}
				if(!empty($active_dispute_data) && ((strtotime($active_dispute_data['dispute_negotiation_end_date']) < time() && $active_dispute_data['disputed_amount'] >= $project_value_for_admin_arbitration ) || $active_dispute_data['dispute_status'] == 'under_admin_review')){	
				
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}else{	
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_make_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}
				}
				
				
				$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				if(!empty($latest_counter_offer_data) && $latest_counter_offer_data['countered_by_user_id'] == $user_id ){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_make_counter_offer_when_already_received_counter_offer_in_background')]);
					die;
					
				}
				if(!empty($latest_counter_offer_data)){
				
					$total_counter_offers = $this->db->where(['dispute_reference_id' => $active_dispute_data['dispute_reference_id']])->from($active_counter_offer_table)->count_all_results();
					if($post_data['total_counter_offers']!= $total_counter_offers){
						if($post_data['project_type'] == 'fulltime'){
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_make_counter_offer_when_already_received_counter_offer_in_background')]);
							die;
						}else{
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_make_counter_offer_when_already_received_counter_offer_in_background')]);
							die;
						}
					}	
				
				}
				
				$param_data['project_type'] = $post_data['project_type'];
				$param_data['disputed_amount'] = $active_dispute_data['disputed_amount'];
				$param_data['po_id'] = ($post_data['project_type'] =='fulltime')?$active_dispute_data['employer_id_of_disputed_fulltime_project']:$active_dispute_data['project_owner_id_of_disputed_project'];
				$param_data['sp_id'] = ($post_data['project_type'] =='fulltime')?$active_dispute_data['employee_winner_id_of_disputed_fulltime_project']:$active_dispute_data['sp_winner_id_of_disputed_project'];
				$param_data['user_id'] = $user_id;
				$param_data['dispute_ref_id'] = $active_dispute_data['dispute_reference_id'];
				
				/* echo "<pre>";
				print_r($param_data);
				die; */
				
				$validation_data_array = $this->Projects_disputes_model->project_dispute_create_counter_offer_validation($post_data,$param_data);
				
				if($validation_data_array['status'] == 'SUCCESS'){
					
					
					$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $post_data['project_id'])
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $post_data['project_id'])
						->get()->row_array();
					}
					
					
					
					
					$dispute_initiated_by = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
					$disputed_against_user_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
					if($user_id == $dispute_initiated_by){
						$countered_to = $disputed_against_user_id;
					}if($user_id == $disputed_against_user_id){
						$countered_to = $dispute_initiated_by;
					}
					
					$counter_offer_data['dispute_reference_id'] = $post_data['dispute_ref_id'];
					if($post_data['project_type'] == 'fulltime'){
						$counter_offer_data['disputed_fulltime_project_id'] = $post_data['project_id'];
					}else{	
						$counter_offer_data['disputed_project_id'] = $post_data['project_id'];
					}
					$counter_offer_data['counter_amount_value'] = str_replace(" ","",$post_data['counter_offer']);
					$counter_offer_data['countered_by_user_id'] = $user_id;
					$counter_offer_data['countered_to_user_id'] = $countered_to;
					$counter_offer_data['counter_date'] = date('Y-m-d h:i:s');
					
					$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					if($this->db->insert ($active_counter_offer_table, $counter_offer_data)){
						
						$counter_offer_value = str_replace(" ","",$post_data['counter_offer']);
						
						$counter_offer_value =   str_replace(".00","",number_format($counter_offer_value,  2, '.', ' '))." ". CURRENCY;
						
						$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
							
						$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
						
						$countered_to_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name')->from('users')->where('user_id',$countered_to)->get()->row_array();
							
						$countered_to_name = $countered_to_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $countered_to_user_data['first_name'] . ' ' . $countered_to_user_data['last_name'] : $countered_to_user_data['company_name'];
						
				
						$countered_by_user_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name')->from('users')->where('user_id',$user_id)->get()->row_array();
						
						$countered_by_name = $countered_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $countered_by_user_data['first_name'] . ' ' . $countered_by_user_data['last_name'] : $countered_by_user_data['company_name'];
						
						if($post_data['project_type'] == 'fixed'){
							$countered_by_activity_log_msg = $this->config->item('fixed_budget_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_creator');
						}
						if($post_data['project_type'] == 'hourly'){
							$countered_by_activity_log_msg = $this->config->item('hourly_rate_based_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_creator');
						}
						if($post_data['project_type'] == 'fulltime'){
							$countered_by_activity_log_msg = $this->config->item('fulltime_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_creator');
						}
						
						$countered_by_activity_log_msg = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{user_profile_url_link}','{counter_amount_value}','{dispute_reference_id}'),array($project_url_link,$project_title,$countered_to_name,VPATH.$countered_to_user_data['profile_name'],$counter_offer_value,$post_data['dispute_ref_id']),$countered_by_activity_log_msg);
						
						if($post_data['project_type'] == 'fixed'){
							$countered_to_activity_log_msg = $this->config->item('fixed_budget_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_other_party');
						}
						if($post_data['project_type'] == 'hourly'){
							$countered_to_activity_log_msg = $this->config->item('hourly_rate_based_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_other_party');
						}
						if($post_data['project_type'] == 'fulltime'){
							$countered_to_activity_log_msg = $this->config->item('fulltime_project_dispute_make_counter_offer_activity_log_displayed_message_sent_to_other_party');
						}
						$countered_to_activity_log_msg = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{user_profile_url_link}','{counter_amount_value}','{dispute_reference_id}'),array($project_url_link,$project_title,$countered_by_name,VPATH.$countered_by_user_data['profile_name'],$counter_offer_value,$post_data['dispute_ref_id']),$countered_to_activity_log_msg);
						user_display_log($countered_by_activity_log_msg,$countered_by_user_data['user_id']); 
						user_display_log($countered_to_activity_log_msg,$countered_to_user_data['user_id']); 
						
					
						$dispute_initiated_by_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$dispute_initiated_by)->get()->row_array();
					
						$disputed_against_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$disputed_against_user_id)->get()->row_array();
						
						$dispute_initiated_by_user_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] : $dispute_initiated_by_user_data['company_name'];
						
						$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
					
					
						######## fetch the ajax response after creted the counter offer
						if(empty($latest_counter_offer_data)){
						
								
							$projects_disputes_data = $this->db->get_where($dispute_active_table, ['dispute_reference_id' => $post_data['dispute_ref_id'],'dispute_status'=>'active'])->row_array();
						
						
						
							$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
							
							
							if($latest_counter_offer_data['countered_to_user_id'] == $dispute_initiated_by && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
											 
								$latest_counter_offer_msg = $disputed_against_user_name." created the counter offer to you ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							else if($latest_counter_offer_data['countered_to_user_id'] == $disputed_against_user_id && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
							 
							  $latest_counter_offer_msg =  $dispute_initiated_by_user_name." created the counter offer to you ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							else if($latest_counter_offer_data['countered_by_user_id'] == $disputed_against_user_id && $user[0]->user_id == $latest_counter_offer_data['countered_by_user_id']){
							 
							  $latest_counter_offer_msg = "you sent the counter offer of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							else if($latest_counter_offer_data['countered_by_user_id'] == $dispute_initiated_by && $user[0]->user_id == $latest_counter_offer_data['countered_by_user_id']){
							 
							  $latest_counter_offer_msg =  "you sent the counter offer of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							$response['initial_view_status'] = '1';
							$response['latest_counter_offer_msg'] = $latest_counter_offer_msg;
							$response['counter_offer_history_latest_data'] = '<div class="kcDate"><p>'.str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY .'</p><div>'.date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($latest_counter_offer_data['counter_date'])).'</div></div>';
						
						
						}else{
						
							$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
							
							if($latest_counter_offer_data['countered_to_user_id'] == $dispute_initiated_by && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
											 
							 $latest_counter_offer_msg = $disputed_against_user_name." created the counter offer to you ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							else if($latest_counter_offer_data['countered_to_user_id'] == $disputed_against_user_id && $user[0]->user_id == $latest_counter_offer_data['countered_to_user_id']){
							 
							  $latest_counter_offer_msg =  $dispute_initiated_by_user_name." created the counter offer to you ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							else if($latest_counter_offer_data['countered_by_user_id'] == $disputed_against_user_id && $user[0]->user_id == $latest_counter_offer_data['countered_by_user_id']){
							 
							  $latest_counter_offer_msg = "you created the counter offer for ". $dispute_initiated_by_user_name." of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							
							}
							else if($latest_counter_offer_data['countered_by_user_id'] == $dispute_initiated_by && $user[0]->user_id == $latest_counter_offer_data['countered_by_user_id']){
							  $latest_counter_offer_msg =  "you created the counter offer for ". $disputed_against_user_name." of ".str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ".CURRENCY;
							}
							$response['initial_view_status'] = '0';
							$response['latest_counter_offer_msg'] = $latest_counter_offer_msg;
							$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
						}
						$response['status'] = 200;
						echo json_encode($response);die;
					}
				}else{
					echo json_encode ($validation_data_array);
					die;
				}
			}else{
				show_custom_404_page();
			}
		}else{

		 show_custom_404_page();

		}		
	}	
	
	
	/**
	* This function is used to make the confirmation popup for accept the counter offer project dispute under negotiation stage by po/sp . - Done
	*/
	public function accept_offer_project_dispute_confirmation_popup_body(){
		
		if($this->input->is_ajax_request ()){
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('dashboard_page_url')]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
			
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				
				if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
					
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				
				}
				
				if($post_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$active_counter_offer_table = 'fixed_budget_projects_active_disputes_counter_offers';
				}if($post_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$active_counter_offer_table = 'hourly_rate_based_projects_active_disputes_counter_offers';
				}if($post_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$active_counter_offer_table = 'fulltime_projects_active_disputes_counter_offers';
				}	
				$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
				
				if(empty($projects_active_disputes_data)){
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_accept_offer_dispute_already_closed_dispute')]);
						die;
						
					}else{	
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_accept_offer_dispute_already_closed_dispute')]);
						die;
					}
				}	
				
				if($post_data['project_type'] == 'fixed'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'hourly'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'fulltime'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				}	
				
				if(!empty($projects_active_disputes_data) && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] < $project_value_for_admin_arbitration){
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
						
					}else{	
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}
				}
				if(!empty($projects_active_disputes_data) && ((strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] >= $project_value_for_admin_arbitration) || $projects_active_disputes_data['dispute_status'] == 'under_admin_review' )){	
					if($post_data['project_type'] == 'fulltime'){
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
						
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}
				}
				
				
				$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				if(!empty($latest_counter_offer_data ) && $latest_counter_offer_data['countered_to_user_id'] !=  $user[0]->user_id){
					 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					 die;
					
				}	
			
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $post_data['project_id'])
					->get()->row_array();
				}
				
				
				
				
				
				$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
				
				
				$po_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_active_disputes_data['project_owner_id_of_disputed_project'];

				$sp_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_active_disputes_data['sp_winner_id_of_disputed_project'];	
				
				
				$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$po_id)->get()->row_array();
				
				$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
				
				$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,email,profile_name,gender')->from('users')->where('user_id',$sp_id)->get()->row_array();
				
				$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
				//if($post_data['project_type'] == 'fixed'){
					
					
				if($post_data['project_type'] == 'fulltime'){
					$confirmation_modal_title = $this->config->item('fulltime_project_dispute_details_page_accept_counter_offer_confirmation_project_modal_title');
				}else{					
					$confirmation_modal_title = $this->config->item('project_dispute_details_page_accept_counter_offer_confirmation_project_modal_title');
				}
				
				
				$counter_offer_value = str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY;
				if($po_id == $user[0]->user_id){
					
					$latest_counter_offer_data = $this->db->select('*')->from($active_counter_offer_table)->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id'],'countered_by_user_id'=>$sp_id])->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					
					
					$remainig_amount_for_po = $projects_active_disputes_data['disputed_amount'] - $latest_counter_offer_data['counter_amount_value'];
					
					
					$remainig_amount_for_po = str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY;
					
					
					$percent = $latest_counter_offer_data['counter_amount_value']/$projects_active_disputes_data['disputed_amount'];
					
					
					//$percent_friendly = number_format( $percent * 100, 2 );
					$percent_friendly = $percent * 100;
					
					
					
					 $service_fee_charged_from_po = ($percent_friendly /100)*$projects_active_disputes_data['disputed_service_fees'];
					 
					$service_fee_transfer_to_po = $projects_active_disputes_data['disputed_service_fees']-$service_fee_charged_from_po;
					if($post_data['project_type'] == 'fixed'){
						$confirmation_modal_body_txt = $this->config->item('fixed_budget_project_dispute_details_page_accept_counter_offer_by_po_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{fixed_budget_project_counter_offer_value}','{fixed_budget_project_remaining_amount}'),array($counter_offer_value,$remainig_amount_for_po),$confirmation_modal_body_txt);
					}
					if($post_data['project_type'] == 'hourly'){
						$confirmation_modal_body_txt = $this->config->item('hourly_rate_based_project_dispute_details_page_accept_counter_offer_by_po_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{hourly_rate_based_project_counter_offer_value}','{hourly_rate_based_project_remaining_amount}'),array($counter_offer_value,$remainig_amount_for_po),$confirmation_modal_body_txt);
					}
					if($post_data['project_type'] == 'fulltime'){
						$confirmation_modal_body_txt = $this->config->item('fulltime_project_dispute_details_page_accept_counter_offer_by_employer_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{fulltime_project_counter_offer_value}','{fulltime_project_remaining_amount}'),array($counter_offer_value,$remainig_amount_for_po),$confirmation_modal_body_txt);
					}
					$confirmation_modal_body_txt = str_replace(array('{user_profile_url_link}','{user_first_name_last_name_or_company_name}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array(VPATH.$sp_data['profile_name'],$sp_name, str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$confirmation_modal_body_txt);
				
				}
				if($sp_id == $user[0]->user_id){
					if($post_data['project_type'] == 'fixed'){
						$confirmation_modal_body_txt = $this->config->item('fixed_budget_project_dispute_details_page_accept_counter_offer_by_sp_confirmation_project_modal_body');
						
						$confirmation_modal_body_txt = str_replace(array('{fixed_budget_project_counter_offer_value}'),array($counter_offer_value),$confirmation_modal_body_txt);
						
					}
					if($post_data['project_type'] == 'hourly'){
						$confirmation_modal_body_txt = $this->config->item('hourly_rate_based_project_dispute_details_page_accept_counter_offer_by_sp_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{hourly_rate_based_project_counter_offer_value}'),array($counter_offer_value),$confirmation_modal_body_txt);
						
					}
					if($post_data['project_type'] == 'fulltime'){
						$confirmation_modal_body_txt = $this->config->item('fulltime_project_dispute_details_page_accept_counter_offer_by_employee_confirmation_project_modal_body');
						$confirmation_modal_body_txt = str_replace(array('{fulltime_project_counter_offer_value}'),array($counter_offer_value),$confirmation_modal_body_txt);
						
					}
					$confirmation_modal_body_txt = str_replace(array('{user_profile_url_link}','{user_first_name_last_name_or_company_name}'),array(VPATH.$po_data['profile_name'],$po_name),$confirmation_modal_body_txt);
				
				}
				
				$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmation_modal_body_txt.'</div>';
				$confirmation_modal_footer = '<button id="accept_counter_offer" data-dispute-ref-id = "'.$post_data['dispute_ref_id'].'" type="button" class="btn blue_btn default_btn cancel_requested_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('accept_btn_txt').'</button><button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>';
				//}
				
				$confirmation_modal_body.= '<div class="row"><div class="col-md-12"></div></div>';
				$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="accept_counter_offer_checkbox"><span class="checkmark"></span></label><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></div></div></div>';
				echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
				die;
				
			}else{
				show_custom_404_page(); //show custom 404 page
			
			}
				
			
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to accept counter offer- Done
	*/
	public function accept_counter_offer(){
		if($this->input->is_ajax_request ()){
			
			if($this->input->method(TRUE) === 'POST'){
				
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				
				if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id ){
				 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
				 die;
				}
				if($post_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$active_dispute_counter_offer_table_name = 'fixed_budget_projects_active_disputes_counter_offers';
					$closed_dispute_table_name = 'fixed_budget_projects_closed_disputes';
					$closed_dispute_counter_offer_table_name = 'fixed_budget_projects_closed_disputes_counter_offers';
					$released_escrow_table_name = 'fixed_budget_projects_released_escrows';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$incomplete_project_table_name = 'fixed_budget_projects_incomplete';
					$completed_project_table = 'fixed_budget_projects_completed';
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$cancelled_escrow_tracking = 'fixed_budget_projects_cancelled_escrows_tracking';
					$disputed_escrowd_history_tracking = 'fixed_budget_projects_disputed_escrows_history_tracking';
					$closed_counter_offer_table = 'fixed_budget_projects_closed_disputes_counter_offers';
					$inprogress_project_table_name = 'fixed_budget_projects_progress';
					$po_reverted_amount_tracking_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
					
				}
				if($post_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$active_dispute_counter_offer_table_name = 'hourly_rate_based_projects_active_disputes_counter_offers';
					$closed_dispute_table_name = 'hourly_rate_based_projects_closed_disputes';
					$closed_dispute_counter_offer_table_name = 'hourly_rate_based_projects_closed_disputes_counter_offers';
					$released_escrow_table_name = 'hourly_rate_based_projects_released_escrows';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_project_table_name = 'hourly_rate_based_projects_incomplete';
					$completed_project_table = 'hourly_rate_based_projects_completed';
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$cancelled_escrow_tracking = 'hourly_rate_based_projects_cancelled_escrows_tracking';
					$disputed_escrowd_history_tracking = 'hourly_rate_projects_disputed_escrows_history_tracking';
					//$closed_counter_offer_table = 'hourly_rate_based_projects_closed_disputes_counter_offers';
					$inprogress_project_table_name = 'hourly_rate_based_projects_progress';
					$po_reverted_amount_tracking_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';
					
				}
				if($post_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$active_dispute_counter_offer_table_name = 'fulltime_projects_active_disputes_counter_offers';
					$closed_dispute_table_name = 'fulltime_projects_closed_disputes';
					$closed_dispute_counter_offer_table_name = 'fulltime_projects_closed_disputes_counter_offers';
					$released_escrow_table_name = 'fulltime_projects_released_escrows';
					$po_reverted_amount_tracking_table = 'fulltime_projects_closed_disputes_employer_reverted_amounts';
					
				}			
				
				$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
				
				if(empty($projects_active_disputes_data)){
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_accept_offer_dispute_already_closed_dispute')]);
						die;
						
					}else{	
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_accept_offer_dispute_already_closed_dispute')]);
						die;
					}
				}	
				
				if($post_data['project_type'] == 'fixed'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'hourly'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration');
				}
				if($post_data['project_type'] == 'fulltime'){
					$project_value_for_admin_arbitration = $this->config->item('minimum_required_disputed_fulltime_project_value_for_admin_arbitration');
				}	
					
				if(!empty($projects_active_disputes_data) && strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] < $project_value_for_admin_arbitration){
					if($post_data['project_type'] == 'fulltime'){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
						
					}else{	
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_autodecided_dispute')]);
						die;
					}
				}
				if(!empty($projects_active_disputes_data) && ((strtotime($projects_active_disputes_data['dispute_negotiation_end_date']) < time() && $projects_active_disputes_data['disputed_amount'] >= $project_value_for_admin_arbitration) || $projects_active_disputes_data['dispute_status'] == 'under_admin_review' )){	
					if($post_data['project_type'] == 'fulltime'){
						
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('fulltime_project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
						
					}else{
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_dispute_details_page_user_try_to_accept_counter_offer_when_negotiation_time_expired_dispute_on_admin_arbitration')]);
						die;
					}
				}
				
			
				
				$latest_counter_offer_data = $this->db->select('*')->from($active_dispute_counter_offer_table_name)->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
				if(!empty($latest_counter_offer_data ) && $latest_counter_offer_data['countered_to_user_id'] !=  $user[0]->user_id){
					 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					 die;
					
				}	
			
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $post_data['project_id'])
					->get()->row_array();
				}else{
					$project_data = $this->db // get the user detail
					->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $post_data['project_id'])
					->get()->row_array();
				}
				$profile_name = $project_data['profile_name'];
				$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
				
				$project_status = $project_status_table_array['project_status'];
				
				
				
				
				$check_already_closed_dispute = $this->db->where(['dispute_reference_id' => $post_data['dispute_ref_id']])->from($closed_dispute_table_name)->count_all_results();
				$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$post_data['project_id'];
				
				$project_dispute_details_page_url = VPATH.$this->config->item('project_dispute_details_page_url')."?id=".$post_data['dispute_ref_id'];
				
				$po_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_active_disputes_data['project_owner_id_of_disputed_project'];

				$sp_id = ($post_data['project_type'] == 'fulltime')?$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_active_disputes_data['sp_winner_id_of_disputed_project'];
				
				$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$po_id)->get()->row_array();
				
				$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
				
				$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,email,profile_name,gender')->from('users')->where('user_id',$sp_id)->get()->row_array();
				
				$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
				$current_date = date('Y-m-d H:i:s');
				
				$projects_closed_disputes_data = $projects_active_disputes_data;
				$counter_offer_value = str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY;
				$remainig_amount_for_po = $projects_active_disputes_data['disputed_amount'] - $latest_counter_offer_data['counter_amount_value'];
				
				
				$percent = $latest_counter_offer_data['counter_amount_value']/$projects_active_disputes_data['disputed_amount'];
				$percent_friendly =$percent * 100;
				//$percent_friendly = number_format( $percent * 100, 2 );
				 $service_fee_charged_from_po = ($percent_friendly /100)*$projects_active_disputes_data['disputed_service_fees'];
				 

				 
				$service_fee_transfer_to_po = $projects_active_disputes_data['disputed_service_fees']-$service_fee_charged_from_po;
				
			
				
				unset($projects_closed_disputes_data['id']);
				unset($projects_closed_disputes_data['disputed_latest_message_sent_time']);
				$projects_closed_disputes_data['dispute_end_date'] =$current_date; 
				
				$projects_closed_disputes_data['disputed_final_settlement_amount'] =$latest_counter_offer_data['counter_amount_value']; 
				$projects_closed_disputes_data['disputed_final_settlement_service_fees'] =$service_fee_charged_from_po;
				$projects_closed_disputes_data['dispute_status'] ='parties_agreement'; 
				if($po_id == $user[0]->user_id){
					$projects_closed_disputes_data['disputed_winner_id'] = $sp_id; 
				}
				if($sp_id == $user[0]->user_id){
					$projects_closed_disputes_data['disputed_winner_id'] =$po_id; 
				}
				
				
				if($this->db->insert ($closed_dispute_table_name, $projects_closed_disputes_data)){
				//if(1){
					
					$this->db->update('projects_disputes_attachments', ['dispute_status'=>'closed'], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
					####
					$this->db->delete($active_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']]);	
						
					// move data of counter offer
					$counter_offer_result_array = $this->db // get the user detail
					->select('*')
					->from($active_dispute_counter_offer_table_name)
					->where(['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']])
					->get()->result_array();

					if(!empty($counter_offer_result_array)){
						foreach($counter_offer_result_array as $counter_offer_key=>$counter_offer_value){
							unset($counter_offer_value['id']);
							$counter_offer_value['counter_status'] = 'parties_agreement';
							$this->db->insert($closed_dispute_counter_offer_table_name, $counter_offer_value);
						}
						$this->db->delete($active_dispute_counter_offer_table_name, ['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']]);
					}
				
					if($po_id == $user[0]->user_id){
					
						if($post_data['project_type'] == 'fixed'){
					
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							
							$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_counter_offer_value}','{fixed_budget_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
							
						}
						if($post_data['project_type'] == 'hourly'){
					
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							
							$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_counter_offer_value}','{hourly_rate_based_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
							
						}
						if($post_data['project_type'] == 'fulltime'){
					
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							
							
							$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}','{fulltime_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
							
						}
						
						$po_activity_log_message = str_replace(array('{user_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array($sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'], str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$po_activity_log_message);
						
						if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							
							if($po_data['gender'] == 'M'){
								if($post_data['project_type'] == 'fixed'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_male_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_male_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_male_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
							}else{
								if($post_data['project_type'] == 'fixed'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_female_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_female_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_female_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}

							}
							$sp_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($po_name),$sp_activity_log_message);
							
						}else{
							if($post_data['project_type'] == 'fixed'){
								$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_company_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'hourly'){
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_company_po_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'fulltime'){
								$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_company_employer_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							}
							$sp_activity_log_message = str_replace(array('{user_company_name}'),array($po_name),$sp_activity_log_message);
							
						}
						
						if($post_data['project_type'] == 'fixed'){
							$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}
						if($post_data['project_type'] == 'hourly'){
							$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}
						if($post_data['project_type'] == 'fulltime'){
							$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}						
						
						$sp_activity_log_message = str_replace(array('{user_company_name}','{po_profile_url_link}','{dispute_reference_id}'),array($po_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id']),$sp_activity_log_message);
						
					}
					if($sp_id == $user[0]->user_id){
						
						if($post_data['project_type'] == 'fixed'){
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
							
						}
						if($post_data['project_type'] == 'hourly'){
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}
						if($post_data['project_type'] == 'fulltime'){
							$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						}
						$sp_activity_log_message = str_replace(array('{user_first_name_last_name_or_company_name}','{po_profile_url_link}','{dispute_reference_id}'),array($po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id']),$sp_activity_log_message);
						
						
						if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
							
							if($sp_data['gender'] == 'M'){
								if($post_data['project_type'] == 'fixed'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_male_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_male_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_male_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
							}else{
								if($post_data['project_type'] == 'fixed'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_female_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'hourly'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_female_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
								if($post_data['project_type'] == 'fulltime'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_female_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
								}
							}
							
							$po_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($sp_name),$po_activity_log_message);
							
							
						}else{
							if($post_data['project_type'] == 'fixed'){
								$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_company_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'hourly'){
								$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_company_sp_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							}
							if($post_data['project_type'] == 'fulltime'){
								$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_company_employee_accept_counter_offer_dispute_project_user_activity_log_displayed_message'); 
							}
							$po_activity_log_message = str_replace(array('{user_company_name}'),array($sp_name),$po_activity_log_message);
							
							
						}
						
						if($post_data['project_type'] == 'fixed'){
							$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_counter_offer_value}','{fixed_budget_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						if($post_data['project_type'] == 'hourly'){
							$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_counter_offer_value}','{hourly_rate_based_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}
						if($post_data['project_type'] == 'fulltime'){
							$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}','{fulltime_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
						}						
						$po_activity_log_message = str_replace(array('{user_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array($sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'], str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$po_activity_log_message);
						

					}		
					
					//}
					user_display_log($po_activity_log_message,$po_data['user_id']); 
					user_display_log($sp_activity_log_message,$sp_data['user_id']); 
					
					
					$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();

					if($post_data['project_type'] == 'fixed'){
						$release_escrow_reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}
					if($post_data['project_type'] == 'hourly'){
						$release_escrow_reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}
					if($post_data['project_type'] == 'fulltime'){
						$release_escrow_reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}					
					$digits = $this->config->item('reference_id_digits_limit');
					if(empty($latest_released_escrow_data)) {
						$release_escrow_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($release_escrow_reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = (int)$exclude_inital_number + 1;
						$release_escrow_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					
					$vat_percentage = $this->config->item('vat_percentage');

					$charged_service_fees_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
					$digits = $this->config->item('reference_id_digits_limit');

					if(empty($latest_charged_service_fees)) {
						$charged_service_fees_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($charged_service_fees_reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = (int)$exclude_inital_number + 1;
						$charged_service_fees_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					// move data regarding released escrows 
					$released_escrow_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
					
					if($post_data['project_type'] == 'fulltime'){
						$released_escrow_data['fulltime_project_id'] = $projects_active_disputes_data['disputed_fulltime_project_id'];
						$released_escrow_data['employer_id'] = $projects_active_disputes_data['employer_id_of_disputed_fulltime_project'];
						$released_escrow_data['employee_id'] = $projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project'];
					}else{
						$released_escrow_data['project_id'] = $projects_active_disputes_data['disputed_project_id'];
						$released_escrow_data['project_owner_id'] = $projects_active_disputes_data['project_owner_id_of_disputed_project'];
						$released_escrow_data['winner_id'] = $projects_active_disputes_data['sp_winner_id_of_disputed_project'];
					}
					
					
					$released_escrow_data['released_escrow_payment_amount'] = $latest_counter_offer_data['counter_amount_value'];
					$released_escrow_data['service_fee_charges'] = $service_fee_charged_from_po;
					$released_escrow_data['total_escrow_payment_value'] = $latest_counter_offer_data['counter_amount_value']+$service_fee_charged_from_po;
					$released_escrow_data['payment_via_dispute'] = 'Y';
					$released_escrow_data['escrow_payment_release_date'] = $current_date;
					$released_escrow_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
					if($this->db->insert ($released_escrow_table_name, $released_escrow_data)){
					//if(1){
						#### tracking the amount transfer to po
						
						$po_reverted_amount_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
						
						if($post_data['project_type'] == 'fulltime'){
							$po_reverted_amount_data['disputed_fulltime_project_id'] = $projects_active_disputes_data['disputed_fulltime_project_id'];
							$po_reverted_amount_data['employer_id'] = $projects_active_disputes_data['employer_id_of_disputed_fulltime_project'];
							$po_reverted_amount_data['employee_id'] = $projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project'];

							
						}else{	
						
							$po_reverted_amount_data['disputed_project_id'] = $projects_active_disputes_data['disputed_project_id'];
							$po_reverted_amount_data['po_id'] = $projects_active_disputes_data['project_owner_id_of_disputed_project'];
							$po_reverted_amount_data['sp_id'] = $projects_active_disputes_data['sp_winner_id_of_disputed_project'];
						}
						
						$po_reverted_amount_data['dispute_close_date'] = $current_date;
						$po_reverted_amount_data['dispute_resolution_method'] = 'parties_agreement';
						$po_reverted_amount_data['reverted_amount'] = str_replace(',','',number_format($remainig_amount_for_po,2));
						$po_reverted_amount_data['reverted_service_fee_amount'] =str_replace(',','',number_format($service_fee_transfer_to_po,2)); 
						$po_reverted_amount_data['reverted_total_amount'] = str_replace(',','',number_format(($remainig_amount_for_po+$service_fee_transfer_to_po),2));
					
						$this->db->insert ($po_reverted_amount_tracking_table, $po_reverted_amount_data);
						
						
						
						
						$this->db->set('user_account_balance', 'user_account_balance+'.$latest_counter_offer_data['counter_amount_value'], false);
						$this->db->where('user_id',$sp_data['user_id']);
						$this->db->update('users_details');
						
						$this->db->set('user_account_balance', 'user_account_balance+'.str_replace(",","",number_format(($remainig_amount_for_po+$service_fee_transfer_to_po),2)), false);
						$this->db->where('user_id',$po_data['user_id']);
						$this->db->update('users_details');
						
						$charged_service_fees_data['charged_service_fee_reference_id'] = $charged_service_fees_reference_id;
						
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
						
						
						$charged_service_fees_data['payment_via_dispute'] = 'Y';
						$charged_service_fees_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $po_id;
						$charged_service_fees_data['winner_id'] = $sp_id;
						
						if($post_data['project_type'] == 'fixed'){
							$charged_service_fees_data['project_type'] = 'fixed_budget';
							$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
						}
						if($post_data['project_type'] == 'hourly'){
							$charged_service_fees_data['project_type'] = 'hourly_rate';
							$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
						}
						if($post_data['project_type'] == 'fulltime'){
							$charged_service_fees_data['project_type'] = 'fulltime';
							$charged_service_fees_data['project_id'] = $released_escrow_data['fulltime_project_id'];
						}
						$charged_service_fees_data['escrow_payment_release_date'] = $released_escrow_data['escrow_payment_release_date'];
						$charged_service_fees_data['released_escrow_payment_amount'] = $released_escrow_data['released_escrow_payment_amount'];
						$charged_service_fees_data['charged_service_fee_value'] = $released_escrow_data['service_fee_charges'];
						$charged_service_fees_data['vat_percentage_value'] = $vat_percentage;
						
						$service_fee_value_excl_vat = ($released_escrow_data['service_fee_charges'] * 100 ) / ($vat_percentage + 100);
						
						
						$vat_amount = $released_escrow_data['service_fee_charges'] - $service_fee_value_excl_vat;
						
						
						$charged_service_fees_data['charged_service_fee_value_excl_vat'] = $service_fee_value_excl_vat;
						
						$charged_service_fees_data['charged_service_fee_vat_amount_value'] = $vat_amount;
						
						//$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
						
						$this->Projects_disputes_model->insert_data_into_charged_service_fees_tracking_disputes($charged_service_fees_data, $released_escrow_data);
						
						
						
						if($post_data['project_type'] == 'fulltime'){
							
							$released_escrow_condition = array('fulltime_project_id'=>$projects_active_disputes_data['disputed_fulltime_project_id'],'employer_id'=>$projects_active_disputes_data['employer_id_of_disputed_fulltime_project'],'employee_id'=>$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']);
							
						}else{
							$released_escrow_condition = array('project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']);
						}	
						
							
							
						$total_released_escrow = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($post_data['project_type'],$released_escrow_condition);
						
						
						if($post_data['project_type'] == 'fulltime'){
							$this->db->update($closed_dispute_table_name, ['fulltime_project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						}else{	
							$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
						}
						if($post_data['project_type'] != 'fulltime'){
							
							$check_complete_bid_data_exists =  $this->db->where(['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($completed_bid_table_name)->count_all_results(); // check dsipute exists or not
							// update when bid exists in completed bid table
							if($check_complete_bid_data_exists != 0){
								
								
								$this->db->update($completed_bid_table_name, ['total_project_amount'=>$total_released_escrow], ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
								
								
							}
							$incomplete_bid_data = $this->db->get_where($incomplete_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
							if(!empty($incomplete_bid_data)){
									$bid_data = $incomplete_bid_data;;
							}
							$inprogress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
							
							if(!empty($inprogress_bid_data)){
									$bid_data = $inprogress_bid_data;;
							}
							if(!empty($bid_data)){
								
								if($total_released_escrow >= $bid_data['initial_project_agreed_value'] && $bid_data['initial_project_agreed_value'] != 0){
		
									$completed_bid_data = $bid_data;
									$completed_bid_data['project_winner_work_completion_date'] = $current_date;
									$completed_bid_data['project_winner_work_start_date'] = $bid_data['project_start_date'];
									
									$completed_bid_data['total_project_amount'] = $total_released_escrow;
									unset($completed_bid_data['id']);
									unset($completed_bid_data['project_start_date']);
									unset($completed_bid_data['initial_project_agreed_delivery_period']);
									
									
									
									if($check_complete_bid_data_exists == 0){
										$this->db->insert ($completed_bid_table_name, $completed_bid_data);// move data to completed internally tracking
										$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$post_data['dispute_ref_id']]);
									}
									
									if($post_data['project_type'] == 'fixed'){
										
										$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'); 
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'); 
									}
									if($post_data['project_type'] == 'hourly'){
										
										$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'); 
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_user_accept_counter_offer_project_completed_user_activity_log_displayed_message'); 
									}
									
									$po_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{sp_profile_url_link}'),array($project_url_link,$project_title,$sp_name,VPATH.$sp_data['profile_name']),$po_activity_log_message);
									
									
									$sp_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{po_profile_url_link}'),array($project_url_link,$project_title,$po_name,VPATH.$po_data['profile_name']),$sp_activity_log_message);
										
									
									user_display_log($po_activity_log_message,$po_data['user_id']); 
									user_display_log($sp_activity_log_message,$sp_data['user_id']); 
									$this->db->delete($incomplete_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
									$this->db->delete($inprogress_bid_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']]);
									
									 #### project move to completed table start####
									 
									 
									 $count_incomplete_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_bid_table_name)->count_all_results();

									$count_inprogress_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($inprogress_bid_table_name)->count_all_results();
									
									if($count_incomplete_bids == 0 && $count_inprogress_bids ==0){
											
										$project_completed_table_data_exists = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($completed_project_table)->count_all_results();
										if($project_completed_table_data_exists == 0){
											
											
											if($project_status == 'incomplete'){
												$incomplete_project_detail = $this->db->get_where($incomplete_project_table_name, ['project_id'=>$project_data['project_id']])->row_array();
												$complete_project_detail = $incomplete_project_detail;
											}
											if($project_status == 'in_progress'){
												$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$project_data['project_id']])->row_array();
												$complete_project_detail = $inprogress_project_detail;
											}
											
											//unset($project_data['profile_name']);
											unset($complete_project_detail['id']);
											$complete_project_detail['project_completion_date'] = $current_date;
											$this->db->insert ($completed_project_table, $complete_project_detail);// 
											
											$this->db->delete($project_status_table_array['table_name'], ['project_id' =>$projects_active_disputes_data['disputed_project_id']]); 
											
											$project_id = $projects_active_disputes_data['disputed_project_id'];
											
											
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
											$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
											$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
											$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
										
											$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
											$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
											$project_completed_dir = PROJECT_COMPLETED_DIR;
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir);
											
											
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
											
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir, 0777);// create awaiting_moderation directory in projects folder
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id , 0777); // create the directory by using  project id
											//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
											$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
											if($project_status == 'incomplete'){
												$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir;
											}
											if($project_status == 'in_progress'){
												$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
											}
											
											$source_list = $this->ftp->list_files($source_path);
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
														//die("sdfsdff");
														
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
														$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
														
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
														
														//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
														//die("fsdffdf");
														
														if($project_status == 'incomplete'){
															$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
														}
														if($project_status == 'in_progress'){
															
															$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
														}
														$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
														if($file_size != '-1')
														{
															$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
															$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
															
														}
													}
												}
											}
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id))) {
												$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id);
											}
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
												$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
											}
											$this->ftp->close(); 
											
										}
										
									}
								#### project move to completed table end####
								}else{
									
									
								 // if project value is not equal to throsold value project/ bid moves to incomplete if project/ bid exists into inprogress exists	
								 
									$inprogress_bid_detail = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
									if(!empty($inprogress_bid_detail)){
										$check_already_in_complete_bid = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($incomplete_bid_table_name)->count_all_results();
										if($check_already_in_complete_bid == 0){
											unset($inprogress_bid_detail['id']);
											$this->db->insert ($incomplete_bid_table_name, $inprogress_bid_detail);
											$this->db->delete($inprogress_bid_table_name, ['project_id'=>$post_data['project_id'],'project_owner_id'=>$po_id,'winner_id'=>$sp_id]); 
											 
											//$count_complete_bids = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($completed_bid_table_name)->count_all_results();
											
											/* if($count_incomplete_bids == 0 && $count_complete_bids == 0){ */
											$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']])->row_array();
											
											if(!empty($inprogress_project_detail)){
												
												$check_already_in_complete_project = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_project_table_name)->count_all_results();
												
												if($check_already_in_complete_project == 0){
													
													unset($inprogress_project_detail['id']);
													$this->db->insert ($incomplete_project_table_name, $inprogress_project_detail);
													
													$this->db->delete($inprogress_project_table_name, ['project_id'=>$post_data['project_id']]); 
													
													
													######## connectivity of remote server start#########
													$this->load->library('ftp');
													$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
													$config['ftp_username'] = FTP_USERNAME;
													$config['ftp_password'] = FTP_PASSWORD;
													$config['ftp_port'] 	= FTP_PORT;
													$config['debug']    = TRUE;
													$this->ftp->connect($config); 
													######## connectivity of remote server end #######
													
													$project_id = $projects_active_disputes_data['disputed_project_id'];
													$users_ftp_dir 	= USERS_FTP_DIR; 
													$projects_ftp_dir = PROJECTS_FTP_DIR;
													$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
													$project_incomplete_dir = PROJECT_INCOMPLETE_DIR;
													
													$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
													$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
													
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR);
													$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir);
													
													
													
													
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir, 0777);// create projects directory if not exists
													
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir, 0777);// create awaiting_moderation directory in projects folder
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id , 0777); // create the directory by using  project id
													//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
													$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR;
													
													$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
													
													
													$source_list = $this->ftp->list_files($source_path);
													if(!empty($source_list)) {
														foreach($source_list as $path) {
															$arr = explode('/', $path);
															$file_size = $this->ftp->get_filesize($path);
															if($file_size != '-1') {
																$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir.end($arr);
																$this->ftp->move($path, $destination_path);
															}
														}
													}
													$bid_attachments = $this->db->where ('project_id', $project_id)->get ('projects_active_bids_users_attachments_tracking')->result_array ();
													
													if(!empty($bid_attachments)){
														foreach($bid_attachments as $bid_attachment_key=>$bid_attachment_value){
															$bid_attachment_directory_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'];
															if(empty($this->ftp->check_ftp_directory_exist($bid_attachment_directory_path))){
																//die("sdfsdff");
																
																$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir);
																$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
																
																//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir , 0777); // create the owner attachment directory by using  project id
																
																//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); // create the owner attachment directory by using  project id
																//die("fsdffdf");
																
																
																	
																$source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																
																$file_size = $this->ftp->get_filesize($source_bid_attachment_path);
																if($file_size != '-1')
																{
																	$destination_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
																	$this->ftp->move($source_bid_attachment_path, $destination_bid_attachment_path);
																	
																}
															}
														}
													}
													
													if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
														$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
													}
													$this->ftp->close();
													
												}	
												
											}
												
											//}
										}
									}
								}
							}
						
						}
							
					}
					
					//### Add email code here start ####
					
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
					
					
					
					$po_to = $po_data['email'];
					if($po_id == $user[0]->user_id){
					
						if($post_data['project_type'] == 'fulltime'){
							
							$po_cc = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_cc_sent_to_employer');
							$po_bcc = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_bcc_sent_to_employer');
							$po_from = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_from_sent_to_employer');
							$po_reply_to = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_reply_to_sent_to_employer');
							$po_from_name = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_from_name_sent_to_employer');
							$po_subject = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_subject_sent_to_employer');
							$po_message = $this->config->item('fulltime_project_dispute_employer_accept_counter_offer_email_message_sent_to_employer');
							
							$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
							
							 
							$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{fulltime_project_dispute_details_page_url}','{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}','{fulltime_project_remaining_amount}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY, str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$po_message);
							
						}else{
						
							$po_cc = $this->config->item('project_dispute_po_accept_counter_offer_email_cc_sent_to_po');
							$po_bcc = $this->config->item('project_dispute_po_accept_counter_offer_email_bcc_sent_to_po');
							$po_from = $this->config->item('project_dispute_po_accept_counter_offer_email_from_sent_to_po');
							$po_reply_to = $this->config->item('project_dispute_po_accept_counter_offer_email_reply_to_sent_to_po');
							$po_from_name = $this->config->item('project_dispute_po_accept_counter_offer_email_from_name_sent_to_po');
							$po_subject = $this->config->item('project_dispute_po_accept_counter_offer_email_subject_sent_to_po');
							$po_message = $this->config->item('project_dispute_po_accept_counter_offer_email_message_sent_to_po');
							
							$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
							
							
							$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_first_name_last_name_or_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}','{project_disputed_amount}','{project_counter_offer_value}','{project_remaining_amount}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY, str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$po_message);
						
						}
						
						
						$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
						$this->email->from ($po_from,$po_from_name);
						$this->email->to ($po_to);
						if($po_cc){
							$this->email->cc ($po_cc);
						}
						if($po_bcc){
							$this->email->bcc ($po_bcc);
						}
						$this->email->subject ($po_subject);
						$this->email->reply_to($po_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($po_message);
						$this->email->send ();
						
						//// For Sp
						$sp_to = $sp_data['email'];
						if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								
							if($po_data['gender'] == 'M'){
									
								if($post_data['project_type'] == 'fulltime'){
									$sp_cc = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_cc_sent_to_employee');
									$sp_bcc = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_bcc_sent_to_employee');
									$sp_from = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_from_sent_to_employee');
									$sp_reply_to = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_reply_to_sent_to_employee');
									$sp_from_name = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_from_name_sent_to_employee');
									$sp_subject = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_subject_sent_to_employee');
									$sp_message = $this->config->item('fulltime_project_dispute_male_employer_accept_counter_offer_email_message_sent_to_employee');
									$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);

									
								}else{
									$sp_cc = $this->config->item('project_dispute_male_po_accept_counter_offer_email_cc_sent_to_sp');
									$sp_bcc = $this->config->item('project_dispute_male_po_accept_counter_offer_email_bcc_sent_to_sp');
									$sp_from = $this->config->item('project_dispute_male_po_accept_counter_offer_email_from_sent_to_sp');
									$sp_reply_to = $this->config->item('project_dispute_male_po_accept_counter_offer_email_reply_to_sent_to_sp');
									$sp_from_name = $this->config->item('project_dispute_male_po_accept_counter_offer_email_from_name_sent_to_sp');
									$sp_subject = $this->config->item('project_dispute_male_po_accept_counter_offer_email_subject_sent_to_sp');
									$sp_message = $this->config->item('project_dispute_male_po_accept_counter_offer_email_message_sent_to_sp');
									$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
								}
									
							}else{
								
								
								if($post_data['project_type'] == 'fulltime'){
									
									$sp_cc = $this->config->item('fulltime_project_dispute_female_employer_accept_counter_offer_email_cc_sent_to_employee');
									$sp_bcc = $this->config->item('fulltime_project_dispute_female_employer_accept_counter_offer_email_bcc_sent_to_employee');
									$sp_from = $this->config->item('fulltime_project_dispute_female_employer_accept_counter_offer_email_from_sent_to_employee');
									$sp_reply_to = $this->config->item('fulltime_project_dispute_female_employer_accept_counter_offer_email_reply_to_sent_to_employee');
									$sp_from_name = $this->config->item('fulltime_project_dispute_female_employer_accept_counter_offer_email_from_name_sent_to_employee');
									$sp_subject = $this->config->item('fulltime_project_dispute_female_po_accept_counter_offer_email_subject_sent_to_employee');
									$sp_message = $this->config->item('fulltime_project_dispute_female_employer_accept_counter_offer_email_message_sent_to_employee');
									$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
								
								}else{
								
									$sp_cc = $this->config->item('project_dispute_female_po_accept_counter_offer_email_cc_sent_to_sp');
									$sp_bcc = $this->config->item('project_dispute_female_po_accept_counter_offer_email_bcc_sent_to_sp');
									$sp_from = $this->config->item('project_dispute_female_po_accept_counter_offer_email_from_sent_to_sp');
									$sp_reply_to = $this->config->item('project_dispute_female_po_accept_counter_offer_email_reply_to_sent_to_sp');
									$sp_from_name = $this->config->item('project_dispute_female_po_accept_counter_offer_email_from_name_sent_to_sp');
									$sp_subject = $this->config->item('project_dispute_female_po_accept_counter_offer_email_subject_sent_to_sp');
									$sp_message = $this->config->item('project_dispute_female_po_accept_counter_offer_email_message_sent_to_sp');
									$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
								}

							}
							
							if($post_data['project_type'] == 'fulltime'){
								
								$sp_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_message);
								
							}else{
								$sp_message = str_replace(array('{project_disputed_amount}','{project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_message);
							}			
							
							$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_first_name_last_name}','{po_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$sp_message);
							
								
						}else{
								
							if($post_data['project_type'] == 'fulltime'){
								
								$sp_cc = $this->config->item('fulltime_project_dispute_company_employer_accept_counter_offer_email_cc_sent_to_employee');
								$sp_bcc = $this->config->item('fulltime_project_dispute_company_employer_accept_counter_offer_email_bcc_sent_to_employee');
								$sp_from = $this->config->item('fulltime_project_dispute_company_employer_accept_counter_offer_email_from_sent_to_employee');
								$sp_reply_to = $this->config->item('fulltime_project_dispute_company_employer_accept_counter_offer_email_reply_to_sent_to_employee');
								$sp_from_name = $this->config->item('fulltime_project_dispute_company_employer_accept_counter_offer_email_from_name_sent_to_employee');
								$sp_subject = $this->config->item('fulltime_project_dispute_company_po_accept_counter_offer_email_subject_sent_to_employee');
								$sp_message = $this->config->item('fulltime_project_dispute_company_employer_accept_counter_offer_email_message_sent_to_employee');
								
								
							}else{	
								$sp_cc = $this->config->item('project_dispute_company_po_accept_counter_offer_email_cc_sent_to_sp');
								$sp_bcc = $this->config->item('project_dispute_company_po_accept_counter_offer_email_bcc_sent_to_sp');
								$sp_from = $this->config->item('project_dispute_company_po_accept_counter_offer_email_from_sent_to_sp');
								$sp_reply_to = $this->config->item('project_dispute_company_po_accept_counter_offer_email_reply_to_sent_to_sp');
								$sp_from_name = $this->config->item('project_dispute_company_po_accept_counter_offer_email_from_name_sent_to_sp');
								$sp_subject = $this->config->item('project_dispute_company_po_accept_counter_offer_email_subject_sent_to_sp');
								$sp_message = $this->config->item('project_dispute_company_po_accept_counter_offer_email_message_sent_to_sp');
								
								
							}	
							$sp_subject = str_replace(array('{dispute_reference_id}','{po_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
							if($post_data['project_type'] == 'fulltime'){
								
								$sp_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_message);
								
							}else{
								$sp_message = str_replace(array('{project_disputed_amount}','{project_counter_offer_value}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_message);
							}		
							
							$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_company_name}','{po_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url),$sp_message);
							
							
						}
						$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';
						$this->email->from ($sp_from,$sp_from_name);
						$this->email->to ($sp_to);
						if($sp_cc){
							$this->email->cc ($sp_cc);
						}
						if($sp_bcc){
							$this->email->bcc ($sp_bcc);
						}
						$this->email->subject ($sp_subject);
						$this->email->reply_to($sp_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($sp_message);
						$this->email->send ();
					}	
					if($sp_id == $user[0]->user_id){

						// For sp
						$sp_to = $sp_data['email'];
						
						if($post_data['project_type'] == 'fulltime'){
							$sp_cc = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_cc_sent_to_employee');
							$sp_bcc = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_bcc_sent_to_employee');
							$sp_from = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_from_sent_to_employee');
							$sp_reply_to = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_reply_to_sent_to_employee');
							$sp_from_name = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_from_name_sent_to_employee');
							$sp_subject = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_subject_sent_to_employee');
							$sp_message = $this->config->item('fulltime_project_dispute_employee_accept_counter_offer_email_message_sent_to_employee');
							
							$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
							
							
							$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_first_name_last_name_or_company_name}','{po_profile_url_link}','{dispute_reference_id}','{fulltime_project_dispute_details_page_url}','{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_message);
						}else{	
						
							$sp_cc = $this->config->item('project_dispute_sp_accept_counter_offer_email_cc_sent_to_sp');
							$sp_bcc = $this->config->item('project_dispute_sp_accept_counter_offer_email_bcc_sent_to_sp');
							$sp_from = $this->config->item('project_dispute_sp_accept_counter_offer_email_from_sent_to_sp');
							$sp_reply_to = $this->config->item('project_dispute_sp_accept_counter_offer_email_reply_to_sent_to_sp');
							$sp_from_name = $this->config->item('project_dispute_sp_accept_counter_offer_email_from_name_sent_to_sp');
							$sp_subject = $this->config->item('project_dispute_sp_accept_counter_offer_email_subject_sent_to_sp');
							$sp_message = $this->config->item('project_dispute_sp_accept_counter_offer_email_message_sent_to_sp');
							
							$sp_subject = str_replace(array('{dispute_reference_id}','{po_first_name_last_name_or_company_name}'),array($post_data['dispute_ref_id'],$po_name),$sp_subject);
							
							
							$sp_message = str_replace(array('{sp_first_name_last_name_or_company_name}','{po_first_name_last_name_or_company_name}','{po_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}','{project_disputed_amount}','{project_counter_offer_value}'),array($sp_name,$po_name,VPATH.$po_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY),$sp_message);
						}
						
						
						$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';
						$this->email->from ($sp_from,$sp_from_name);
						$this->email->to ($sp_to);
						if($sp_cc){
							$this->email->cc ($sp_cc);
						}
						if($sp_bcc){
							$this->email->bcc ($sp_bcc);
						}
						$this->email->subject ($sp_subject);
						$this->email->reply_to($sp_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($sp_message);
						$this->email->send ();
						
						
						//// For po
						$po_to = $po_data['email'];
						if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								
							if($sp_data['gender'] == 'M'){
									
								
								if($post_data['project_type'] == 'fulltime'){
									$po_cc = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_cc_sent_to_employer');
									$po_bcc = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_bcc_sent_to_employer');
									$po_from = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_from_sent_to_employer');
									$po_reply_to = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_reply_to_sent_to_employer');
									$po_from_name = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_from_name_sent_to_employer');
									$po_subject = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_subject_sent_to_employer');
									$po_message = $this->config->item('fulltime_project_dispute_male_employee_accept_counter_offer_email_message_sent_to_employer');
									$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);

									
								}else{
									$po_cc = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_cc_sent_to_po');
									$po_bcc = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_bcc_sent_to_po');
									$po_from = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_from_sent_to_po');
									$po_reply_to = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_reply_to_sent_to_po');
									$po_from_name = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_from_name_sent_to_po');
									$po_subject = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_subject_sent_to_po');
									$po_message = $this->config->item('project_dispute_male_sp_accept_counter_offer_email_message_sent_to_po');
									$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
								}
									
							}else{
								if($post_data['project_type'] == 'fulltime'){
									
									$po_cc = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_cc_sent_to_employer');
									$po_bcc = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_bcc_sent_to_employer');
									$po_from = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_from_sent_to_employer');
									$po_reply_to = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_reply_to_sent_to_employer');
									$po_from_name = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_from_name_sent_to_employer');
									$po_subject = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_subject_sent_to_employer');
									$po_message = $this->config->item('fulltime_project_dispute_female_employee_accept_counter_offer_email_message_sent_to_employer');
									$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
								}else{
									$po_cc = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_cc_sent_to_po');
									$po_bcc = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_bcc_sent_to_po');
									$po_from = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_from_sent_to_po');
									$po_reply_to = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_reply_to_sent_to_po');
									$po_from_name = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_from_name_sent_to_po');
									$po_subject = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_subject_sent_to_po');
									$po_message = $this->config->item('project_dispute_female_sp_accept_counter_offer_email_message_sent_to_po');
									$po_subject = str_replace(array('{dispute_reference_id}','{sp_first_name_last_name}'),array($post_data['dispute_ref_id'],$sp_name),$po_subject);
								}	

							}
							
							if($post_data['project_type'] == 'fulltime'){
							
								$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}','{fulltime_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_message);	
							}else{
								$po_message = str_replace(array('{project_disputed_amount}','{project_counter_offer_value}','{project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_message);	
							}			
							
							$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_first_name_last_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url, str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$po_message);
								
						}else{
								
							if($post_data['project_type'] == 'fulltime'){
								$po_cc = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_cc_sent_to_employer');
								$po_bcc = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_bcc_sent_to_employer');
								$po_from = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_from_sent_to_employer');
								$po_reply_to = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_reply_to_sent_to_employer');
								$po_from_name = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_from_name_sent_to_employer');
								$po_subject = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_subject_sent_to_employer');
								$po_message = $this->config->item('fulltime_project_dispute_company_employee_accept_counter_offer_email_message_sent_to_employer');

							}else{
								$po_cc = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_cc_sent_to_po');
								$po_bcc = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_bcc_sent_to_po');
								$po_from = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_from_sent_to_po');
								$po_reply_to = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_reply_to_sent_to_po');
								$po_from_name = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_from_name_sent_to_po');
								$po_subject = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_subject_sent_to_po');
								$po_message = $this->config->item('project_dispute_company_sp_accept_counter_offer_email_message_sent_to_po');
							}
							$po_subject = str_replace(array('{dispute_reference_id}','{sp_company_name}'),array($post_data['dispute_ref_id'],$sp_name),
							$po_subject);
							
							if($post_data['project_type'] == 'fulltime'){
								
								$po_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_counter_offer_value}','{fulltime_project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_message);
							}else{
								$po_message = str_replace(array('{project_disputed_amount}','{project_counter_offer_value}','{project_remaining_amount}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($latest_counter_offer_data['counter_amount_value'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($remainig_amount_for_po,  2, '.', ' '))." ". CURRENCY),$po_message);
							}		
							$po_message = str_replace(array('{po_first_name_last_name_or_company_name}','{sp_company_name}','{sp_profile_url_link}','{dispute_reference_id}','{project_dispute_details_page_url}','{service_fees_charged_from_po}','{service_fees_return_to_po}'),array($po_name,$sp_name,VPATH.$sp_data['profile_name'],$post_data['dispute_ref_id'],$project_dispute_details_page_url,str_replace(".00","",number_format($service_fee_charged_from_po,  2, '.', ' '))." ".CURRENCY,str_replace(".00","",number_format($service_fee_transfer_to_po,  2, '.', ' '))." ".CURRENCY),$po_message);

							
							
						}
						$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
						$this->email->from ($po_from,$po_from_name);
						$this->email->to ($po_to);
						if($po_cc){
							$this->email->cc ($po_cc);
						}
						if($po_bcc){
							$this->email->bcc ($po_bcc);
						}
						$this->email->subject ($po_subject);
						$this->email->reply_to($po_reply_to);
						$this->email->set_mailtype ('html');
						$this->email->set_newline("\r\n");
						$this->email->message ($po_message);
						$this->email->send ();
					
					}
					
					
					//### Add email code here  end####
					$disputed_initiated_status = 1;
					$projects_disputes_data = $this->db->get_where($closed_dispute_table_name, ['dispute_reference_id' => $post_data['dispute_ref_id']])->row_array();
					
					
					$counter_offer_listing_other_party = $this->db // get the user detail
					->select('*')
					->from($closed_dispute_counter_offer_table_name)
					->where('dispute_reference_id', $post_data['dispute_ref_id'])
					->where('countered_by_user_id !=', $user[0]->user_id)
					->order_by('counter_date ASC')
					->get()->result_array();
					
					$counter_offer_listing_login_user = $this->db // get the user detail
					->select('*')
					->from($closed_dispute_counter_offer_table_name)
					->where('dispute_reference_id', $post_data['dispute_ref_id'])
					->where('countered_by_user_id', $user[0]->user_id)
					->order_by('counter_date ASC')
					->get()->result_array();
					
					
					$dispute_initiated_by = $projects_disputes_data['dispute_initiated_by_user_id'];
					$disputed_against_user_id = $projects_disputes_data['disputed_against_user_id'];
					
					$dispute_initiated_by_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$projects_disputes_data['dispute_initiated_by_user_id'])->get()->row_array();
					
					$disputed_against_user_data = $this->db->select('first_name,last_name,company_name,account_type,gender')->from('users')->where('user_id',$projects_disputes_data['disputed_against_user_id'])->get()->row_array();
					
					$dispute_initiated_by_user_name = $dispute_initiated_by_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $dispute_initiated_by_user_data['first_name'] . ' ' . $dispute_initiated_by_user_data['last_name'] : $dispute_initiated_by_user_data['company_name'];
					
					$disputed_against_user_name = $disputed_against_user_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $disputed_against_user_data['first_name'] . ' ' . $disputed_against_user_data['last_name'] : $disputed_against_user_data['company_name'];
					
					$latest_counter_offer_data = $this->db->select('*')->from($closed_dispute_counter_offer_table_name)->where('dispute_reference_id',$post_data['dispute_ref_id'])->order_by('id', 'DESC')->limit(1)->get()->row_array();
				
					$dispute_result_txt = $this->config->item('project_dispute_details_page_dispute_resolved_txt');
					
					$dispute_heading = '<p>Dispute status:<span> '.$this->config->item('project_dispute_details_page_dispute_resolved_phase').'</span>&nbsp;&nbsp;Dispute Closed Time : <span>'. date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($projects_disputes_data['dispute_end_date'])).'</span></p>';
					$response['dispute_heading'] = $dispute_heading;
					//$response['dispute_closed_reason']=  $this->load->view('projects_disputes/project_dispute_details_closed_dispute_section', array('dispute_closed_reason'=>$dispute_closed_reason,'dispute_result_txt'=>$dispute_result_txt), true);
					
					$response['dispute_closed_reason']  = $this->load->view('projects_disputes/project_dispute_details_closed_dispute_section', array('projects_disputes_data'=>$projects_disputes_data,'sp_data'=>$sp_data,'po_data'=>$po_data,'project_type'=>$post_data['project_type']), true);
					
					
					$response['project_dispute_make_counter_offer_section_data'] = $this->load->view('projects_disputes/project_dispute_details_make_projects_dispute_counter_offer_section',array('dispute_initiated_by'=>$dispute_initiated_by,'disputed_against_user_id'=>$disputed_against_user_id,'latest_counter_offer_data'=>$latest_counter_offer_data,'projects_disputes_data'=>$projects_disputes_data,'counter_offer_listing_other_party'=>$counter_offer_listing_other_party,'counter_offer_listing_login_user'=>$counter_offer_listing_login_user,'disputed_against_user_name'=>$disputed_against_user_name,'dispute_initiated_by_user_name'>$dispute_initiated_by_user_name), true);
					$response['status'] = 200;
					$response['location'] ='';
					echo json_encode($response);die;
					
					
					
				}	
				
			}else{
				show_custom_404_page(); //show custom 404 page

			}		
			
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}	
	
	
	/**
	This functions is using to redircet the user from dispute detail page to dashboard when dispute is temporay,. But when project is disputed and user click on cancel button on dipute form it reset the dispute form data and remove the all oprhan  disputed projects attachments from table disputes_attachments and disk whose message id is zero
	*/
	public function reset_project_dispute_data ()
    {
		if( $this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$user = $this->session->userdata ('user');
				$post_data = $this->input->post ();
				if($post_data['type'] == 'temp' ){
					$result_array = $this->db // get the user detail
					->select('*')
					->from('temp_disputes_projects_attachments')
					->where('temp_dispute_reference_id', $post_data['dispute_ref_id'])
					->get()->result_array();
					if(!empty($result_array)){
						foreach($result_array as $key=>$value){
							if(file_exists(TEMP_DIR.$value['temp_dispute_attachment_name'])){
								unlink(TEMP_DIR.$value['temp_dispute_attachment_name']);
							}
						}
						$this->db->delete('temp_disputes_projects_attachments', array('temp_dispute_reference_id' => $post_data['dispute_ref_id']));
						$this->db->delete('temp_projects_active_disputes', array('temp_dispute_reference_id' => $post_data['dispute_ref_id']));
					}
					$msg['status'] = 200;
					$msg['location'] =  VPATH.$this->config->item('dashboard_page_url');
					echo json_encode ($msg);die;
				}else if($post_data['type'] == 'active' ){
					
					
					$project_id = $this->input->post ('project_id');
					$user_id = $user[0]->user_id;
					
					if(Cryptor::doDecrypt($post_data['dispute_initiated_by']) != $user[0]->user_id  && Cryptor::doDecrypt($post_data['disputed_against_user_id']) != $user[0]->user_id){
					 echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					 die;
					}
					
					$project_status_table_array = $this->Projects_model->get_project_status_type($post_data['project_id']);
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$project_data = $this->db // get the user detail
						->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.employer_id', 'left')
						->where('pd.fulltime_project_id', $post_data['project_id'])
						->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
						->from($project_status_table_array['table_name'].' pd')
						->join('users u', 'u.user_id = pd.project_owner_id', 'left')
						->where('pd.project_id', $post_data['project_id'])
						->get()->row_array();
					}
					
					/* if($post_data['project_type'] == 'fixed'){
						$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					}
					$active_dispute_data = $this->db // get the active dispute detail
					->select('*')
					->from($active_dispute_table_name)
					->where('dispute_ref_id', $post_data['dispute_ref_id'])
					->get()->row_array(); */
					
					if($project_data['project_owner_id'] == $user[0]->user_id){
						if($user[0]->user_id == Cryptor::doDecrypt($post_data['dispute_initiated_by'])){
							$sp_id = Cryptor::doDecrypt($post_data['disputed_against_user_id']);
						}
						if($user[0]->user_id == Cryptor::doDecrypt($post_data['disputed_against_user_id'])){
							$sp_id = Cryptor::doDecrypt($post_data['dispute_initiated_by']);
						}
					}if($project_data['project_owner_id'] != $user[0]->user_id){
						$sp_id  = $user[0]->user_id;
					}
					$profile_folder     = $project_data['profile_name'];
				
					
					
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
					$project_dispute_dir = PROJECT_DISPUTE_DIR;
					$profile_folder     = $project_data['profile_name'];
					
					
					$result_array = $this->db // get the user detail
					->select('*')
					->from('projects_disputes_attachments')
					->where('dispute_reference_id', $post_data['dispute_ref_id'])
					->where('dispute_message_id', '0')
					->get()->result_array();
					if(!empty($result_array)){
					
						foreach($result_array as $key=>$value){
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dispute_dir.$sp_id.DIRECTORY_SEPARATOR .$post_data['dispute_ref_id'].DIRECTORY_SEPARATOR .$value['dispute_attachment_name'];
							$file_size = $this->ftp->get_filesize($source_path);
							if($file_size != '-1')
							{
								$this->ftp->delete_file($source_path);
							}
							$this->db->delete('projects_disputes_attachments', array('id' => $value['id']));
							
						
						}
					}
					
				
					$msg['status'] = 200;
					$msg['location'] =  '';
					echo json_encode ($msg);die;
				
				}
				
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		
    }
	
	public function projects_disputes() { 
		if(!$this->session->userdata('user')) {
				redirect(base_url());
		}
		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			$data['current_page'] = 'projects-disputes';
			########## set the profile title meta tag and meta description  start here #########
			
			 $user_detail = $this->db // get the user detail
			->select('u.user_id, u.account_type, u.first_name, u.last_name, u.company_name,u.is_authorized_physical_person')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
            $name = $user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y') ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			$title_meta_tag = $this->config->item('projects_disputes_page_title_meta_tag');
			$title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
			$description_meta_tag = $this->config->item('projects_disputes_page_description_meta_tag');
			$description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
			$data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';

			########## set the profile title tag start end #########
			$this->layout->view ('projects_disputes', '', $data, 'include');
		}
	}

	
	// This function used to update the tabs(new dispute/active dispute/closed dispute) on project disputed page
	public function update_user_projects_disputes_page_section_tabs_data(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				
					if(Cryptor::doDecrypt($this->input->post ('session_uid')) != $user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					$data = array();	
					$view_type = $this->input->post ('view_type');
					$tab_type = $this->input->post ('tab_type');
					$start = 0;
					if($tab_type == 'new_dispute'){
						if($view_type == 'po'){
							
							$new_disputes_projects_listing_data = $this->Projects_disputes_model->get_all_new_disputes_projects_listing_po($user_id);
							$data["new_disputes_projects_listing_data"] = $new_disputes_projects_listing_data['data'];
							$data['new_disputes_projects_listing_data_count'] = $new_disputes_projects_listing_data['total'];
							
							$published_projects_count = $this->User_projects_payments_overview_model->get_po_published_projects_count($user_id);
							$projects_active_escrows_count = $this->Projects_disputes_model->get_po_all_projects_active_escrows_count($user_id);
							
						}
						if($view_type == 'sp'){
							
							$new_disputes_projects_listing_data = $this->Projects_disputes_model->get_all_new_disputes_projects_listing_sp($user_id);
							$data["new_disputes_projects_listing_data"] = $new_disputes_projects_listing_data['data'];
							$data['new_disputes_projects_listing_data_count'] = $new_disputes_projects_listing_data['total'];
							
							$published_projects_count = $this->User_projects_payments_overview_model->get_sp_all_projects_count($user_id);
							$projects_active_escrows_count = $this->Projects_disputes_model->get_sp_all_projects_active_escrows_count($user_id);
							
						}
						$data['view_type'] = $view_type;
						$data['published_project_count'] = $published_projects_count;
						$data['projects_active_escrows_count'] = $projects_active_escrows_count;
						echo json_encode(['status' => 200,'data'=>$this->load->view('projects_disputes/user_projects_disputes_management_page_start_new_disputes_section',$data, true)]);
						die;
						
					}
					if($tab_type == 'active_dispute'){
						if($view_type == 'po'){
							$active_disputes_listing_project_data = $this->Projects_disputes_model->get_all_active_disputes_listing_projects_po($user_id,$start,$this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'));
						}
						if($view_type == 'sp'){
							$active_disputes_listing_project_data = $this->Projects_disputes_model->get_all_active_disputes_listing_projects_sp($user_id,$start,$this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'));
						}
						$data["active_disputes_listing_project_data"] = $active_disputes_listing_project_data['data'];
						$data['active_disputes_listing_project_data_count'] = $active_disputes_listing_project_data['total'];
						
						$paginations = generate_pagination_links($active_disputes_listing_project_data['total'], $this->config->item('user_projects_disputes_management_page_paging_url'), $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'), $this->config->item('user_projects_disputes_management_page_number_of_pagination_links'));
						$data['generate_pagination_links_user_projects_disputes_management'] = $paginations['links'];
						echo json_encode(['status' => 200,'data'=>$this->load->view('projects_disputes/user_projects_disputes_management_page_active_disputes_section',$data, true)]);
						die;
					}
					if($tab_type == 'closed_dispute'){
						if($view_type == 'po'){
							$closed_disputes_listing_project_data = $this->Projects_disputes_model->get_all_closed_disputes_listing_projects_po($user_id,$start,$this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'));
						}
						if($view_type == 'sp'){
							$closed_disputes_listing_project_data = $this->Projects_disputes_model->get_all_closed_disputes_listing_projects_sp($user_id,$start,$this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'));
						}
						/* echo "<pre>";
						print_r($closed_disputes_listing_project_data);
						die;
					
						 */
						
						$data["closed_disputes_listing_project_data"] = $closed_disputes_listing_project_data['data'];
						$data['closed_disputes_listing_project_data_count'] = $closed_disputes_listing_project_data['total'];
						
						
						$paginations = generate_pagination_links($closed_disputes_listing_project_data['total'], $this->config->item('user_projects_disputes_management_page_paging_url'), $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'), $this->config->item('user_projects_disputes_management_page_number_of_pagination_links'));
						$data['generate_pagination_links_user_projects_disputes_management'] = $paginations['links'];
						echo json_encode(['status' => 200,'data'=>$this->load->view('projects_disputes/user_projects_disputes_management_page_closed_disputes_section',$data, true)]);
						die;
					}
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
		}else{
			show_custom_404_page(); //show custom 404 page	
		}	
	}	

		
	/* This function will work when paging will execute on project dsiputed page(new dispute/active dispute/closed dispute) tabs - Done */
	public function load_user_projects_disputes_page_section_tabs_next_page_data () {
		if($this->input->is_ajax_request ()){
		
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				if(Cryptor::doDecrypt($this->input->post ('session_uid')) != $user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$tab_type = $this->input->post('tab_type');
				$view_type = $this->input->post ('view_type');
				$data["view_type"] = $view_type;
				if($tab_type == 'active_dispute'){
					
					if($view_type == 'po'){
						
						$total_record = $this->Projects_disputes_model->get_all_active_disputes_listing_projects_count_po($user_id);
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_disputes_management_page_paging_url'), $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'), $this->config->item('user_projects_disputes_management_page_number_of_pagination_links'));
						
						$active_disputes_listing_project_data = $this->Projects_disputes_model->get_all_active_disputes_listing_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'));
						
					}
					if($view_type == 'sp'){
						
						$total_record = $this->Projects_disputes_model->get_all_active_disputes_listing_projects_count_sp($user_id);
						
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_disputes_management_page_paging_url'), $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'), $this->config->item('user_projects_disputes_management_page_number_of_pagination_links'));
						
						$active_disputes_listing_project_data = $this->Projects_disputes_model->get_all_active_disputes_listing_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit'));
						
					}
					
					$data['generate_pagination_links_user_projects_disputes_management'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					$data["active_disputes_listing_project_data"] = $active_disputes_listing_project_data['data'];
					$data['active_disputes_listing_project_data_count'] = $active_disputes_listing_project_data['total'];
					$multiplication = $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit') - count($data['active_disputes_listing_project_data'])));
					$record_per_page = count($data['active_disputes_listing_project_data']) < $this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_disputes_management_page_active_disputes_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_disputes_listing_project_data['total'],'data'=>$this->load->view('projects_disputes/user_projects_disputes_management_page_active_disputes_section',$data, true)]);
					die;
				}
				if($tab_type == 'closed_dispute'){
					/* echo $view_type; */
					if($view_type == 'po'){
						//die("po");
						$total_record = $this->Projects_disputes_model->get_all_closed_disputes_listing_projects_count_po($user_id);
						
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_disputes_management_page_paging_url'), $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'), $this->config->item('user_projects_disputes_management_page_number_of_pagination_links'));
						
						$closed_disputes_listing_project_data = $this->Projects_disputes_model->get_all_closed_disputes_listing_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'));
						
						
						
					}
					if($view_type == 'sp'){
						
						$total_record = $this->Projects_disputes_model->get_all_closed_disputes_listing_projects_count_sp($user_id);
						
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_disputes_management_page_paging_url'), $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'), $this->config->item('user_projects_disputes_management_page_number_of_pagination_links'));
						
						$closed_disputes_listing_project_data = $this->Projects_disputes_model->get_all_closed_disputes_listing_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit'));
						
					}
					
					
					$data['generate_pagination_links_user_projects_disputes_management'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					$data["closed_disputes_listing_project_data"] = $closed_disputes_listing_project_data['data'];
					$data['closed_disputes_listing_project_data_count'] = $closed_disputes_listing_project_data['total'];
					$multiplication = $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit') - count($data['closed_disputes_listing_project_data'])));
					$record_per_page = count($data['closed_disputes_listing_project_data']) < $this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_disputes_management_page_closed_disputes_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$closed_disputes_listing_project_data['total'],'data'=>$this->load->view('projects_disputes/user_projects_disputes_management_page_closed_disputes_section',$data, true)]);
					die;
						
					
				}
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}	
	
    
}
?>