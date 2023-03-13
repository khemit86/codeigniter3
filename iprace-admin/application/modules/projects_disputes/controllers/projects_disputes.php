<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projects_disputes extends MX_Controller {

   
    public function __construct() {
        $this->load->model('projects_disputes_model');
        $this->load->model('project/project_model');
		 $this->load->model('member/member_model');
		$this->load->library('pagination');
        parent::__construct();
	}

	
	// Listing the active disputes
    public function active_projects_disputes_listing(){
		$search_text = "";
		if($this->input->get('search_element') != NULL ){
			
		  $search_text = trim($this->input->get('search_element'));
		}
		 // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		
		
		
		$projects_disputes_listing = $this->projects_disputes_model->active_projects_disputes_listing($rowno,$rowperpage,$search_text);

		$total_record = $this->db->query('SELECT FOUND_ROWS() as total')->row_array();
		// Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."projects_disputes/active_projects_disputes_listing/?search_element=" . $search_text;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $total_record['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
	 
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
		$data['search'] = $search_text;
		$data['projects_disputes_listing'] = $projects_disputes_listing;
		
   		$this->layout->view('active_projects_disputes_listing', $lay, $data);
		
	}	
	
	// Listing the awating arbitration projects disputes
    public function awaiting_arbitration_projects_disputes_listing(){
		$search_text = "";
		if($this->input->get('search_element') != NULL ){
			
		  $search_text = trim($this->input->get('search_element'));
		}
		 // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		
		include_once '../application/config/'.SITE_LANGUAGE.'_projects_disputes_custom_config.php';
		$params['minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration'] = $config['minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration'];
		
		$params['minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration'] = $config['minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration'];
		
		$params['minimum_required_disputed_fulltime_project_value_for_admin_arbitration'] = $config['minimum_required_disputed_fulltime_project_value_for_admin_arbitration'];
		
		$projects_disputes_listing = $this->projects_disputes_model->awaiting_arbitration_projects_disputes_listing($rowno,$rowperpage,$search_text,$params);
		
		$total_record = $this->db->query('SELECT FOUND_ROWS() as total')->row_array();
		// Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."projects_disputes/awaiting_arbitration_projects_disputes_listing/?search_element=" . $search_text;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $total_record['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
	 
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
		$data['search'] = $search_text;
		$data['projects_disputes_listing'] = $projects_disputes_listing;
		
   		$this->layout->view('awaiting_arbitration_projects_disputes_listing', $lay, $data);
		
	}	
	
	// Listing the auto closed projects disputes
    public function auto_closed_projects_disputes_listing(){
		$search_text = "";
		if($this->input->get('search_element') != NULL ){
			
		  $search_text = trim($this->input->get('search_element'));
		}
		 // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		
	
		$projects_disputes_listing = $this->projects_disputes_model->auto_closed_projects_disputes_listing($rowno,$rowperpage,$search_text);
		
		$total_record = $this->db->query('SELECT FOUND_ROWS() as total')->row_array();
		
		// Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."projects_disputes/auto_closed_projects_disputes_listing/?search_element=" . $search_text;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $total_record['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
	 
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
		$data['search'] = $search_text;
		$data['projects_disputes_listing'] = $projects_disputes_listing;
		
   		$this->layout->view('auto_closed_projects_disputes_listing', $lay, $data);
		
	}
	
	// Listing the resolved projects disputes
    public function resolved_projects_disputes_listing(){
		$search_text = "";
		if($this->input->get('search_element') != NULL ){
			
		  $search_text = trim($this->input->get('search_element'));
		}
		 // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		
	
		$projects_disputes_listing = $this->projects_disputes_model->resolved_projects_disputes_listing($rowno,$rowperpage,$search_text);
		
		$total_record = $this->db->query('SELECT FOUND_ROWS() as total')->row_array();
		
		// Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."projects_disputes/resolved_projects_disputes_listing/?search_element=" . $search_text;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $total_record['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
	 
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
		$data['search'] = $search_text;
		$data['projects_disputes_listing'] = $projects_disputes_listing;
		
   		$this->layout->view('resolved_projects_disputes_listing', $lay, $data);
		
	}
	
	/* This function is using for closed the dsipute by admin */
	public function closed_awaiting_arbitration_dispute($project_type,$dispute_reference_id,$winner_id){
		
		
		if($project_type == 'fixed'){
			
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
			//$closed_counter_offer_table = 'fixed_budget_projects_closed_disputes_counter_offers_trck';
			$inprogress_project_table_name = 'fixed_budget_projects_progress';
			
			$po_reverted_amount_tracking_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
			
		}
		
		if($project_type == 'hourly'){
			
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
			//$closed_counter_offer_table = 'fixed_budget_projects_closed_disputes_counter_offers_trck';
			$inprogress_project_table_name = 'hourly_rate_based_projects_progress';
			
			$po_reverted_amount_tracking_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';
			
		}
		if($project_type == 'fulltime'){
			
			$active_dispute_table_name = 'fulltime_projects_active_disputes';
			$active_dispute_counter_offer_table_name = 'fulltime_projects_active_disputes_counter_offers';
			$closed_dispute_table_name = 'fulltime_projects_closed_disputes';
			$closed_dispute_counter_offer_table_name = 'fulltime_projects_closed_disputes_counter_offers';
			$released_escrow_table_name = 'fulltime_projects_released_escrows';
			$cancelled_escrow_tracking = 'fulltime_projects_cancelled_escrows_tracking';
			$disputed_escrowd_history_tracking = 'fulltime_projects_disputed_escrows_history_tracking';
			$po_reverted_amount_tracking_table = 'fulltime_projects_closed_disputes_employer_reverted_amounts';
			
		}
		$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';	
		$projects_active_disputes_data = $this->db->get_where($active_dispute_table_name, ['dispute_reference_id' => $dispute_reference_id])->row_array();
		
		$projects_disputes_admin_arbitration_fees_data = $this->db->get_where('projects_disputes_admin_arbitration_fees_tracking', ['dispute_reference_id' => $dispute_reference_id])->row_array();
		if(!empty($projects_active_disputes_data ) && !empty($projects_disputes_admin_arbitration_fees_data)){
		
		
		
			$project_id = ($project_type == 'fulltime')?$projects_active_disputes_data['disputed_fulltime_project_id']:$projects_active_disputes_data['disputed_project_id'];
			$project_status_table_array = $this->project_model->get_project_status_type($project_id );
			include_once '../application/config/'.SITE_LANGUAGE.'_projects_custom_config.php';
			include_once '../application/config/'.SITE_LANGUAGE.'_projects_disputes_custom_config.php';
			include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
				$project_data = $this->db // get the user detail
				->select('pd.fulltime_project_title as project_title,pd.fulltime_project_id as project_id,pd.project_type,pd.employer_id as project_owner_id,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.employer_id', 'left')
				->where('pd.fulltime_project_id', $project_id)
				->get()->row_array();
			}else{
				$project_data = $this->db // get the user detail
				->select('pd.project_title,pd.project_id,pd.project_type,pd.project_owner_id,u.profile_name')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
			}
			$project_status = $project_status_table_array['project_status'];
			$profile_name = $project_data['profile_name'];
		
			$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
			$project_url_link = HTTP_WEBSITE_HOST.$config['project_detail_page_url']."?id=".$project_id;
			
			$project_dispute_details_page_url = HTTP_WEBSITE_HOST.$config['project_dispute_details_page_url']."?id=".$dispute_reference_id;
			
			
			$po_id = ($project_type == 'fulltime')?$projects_active_disputes_data['employer_id_of_disputed_fulltime_project']:$projects_active_disputes_data['project_owner_id_of_disputed_project'];

			$sp_id = ($project_type == 'fulltime')?$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']:$projects_active_disputes_data['sp_winner_id_of_disputed_project'];
			
			
			
			$po_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,gender,profile_name,email')->from('users')->where('user_id',$po_id)->get()->row_array();
		
			$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $po_data['first_name'] . ' ' . $po_data['last_name'] : $po_data['company_name'];
			
			$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,email,profile_name,gender')->from('users')->where('user_id',$sp_id)->get()->row_array();
			
			$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
			$current_date = date('Y-m-d H:i:s');
			
			
			
			
			
			
			$projects_closed_disputes_data = $projects_active_disputes_data;
			unset($projects_closed_disputes_data['id']);
			
			
			unset($projects_closed_disputes_data['disputed_latest_message_sent_time']);
			$projects_closed_disputes_data['dispute_end_date'] =$current_date; 
			
			$projects_closed_disputes_data['disputed_winner_id'] =$winner_id;
			
			$projects_closed_disputes_data['disputed_final_settlement_amount'] =$projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee']; 
			
			
			if($winner_id == $po_id){
				$service_fee_charges = $projects_active_disputes_data['disputed_service_fees'];
			}
			if($winner_id == $sp_id){
				$service_fee_charges = $projects_active_disputes_data['disputed_service_fees']+$projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'];
			}
			$projects_closed_disputes_data['disputed_final_settlement_service_fees'] =str_replace(',','',number_format($service_fee_charges,2)); 
			$projects_closed_disputes_data['dispute_status'] ='admin_decision'; 
			
			//if($this->db->insert ($closed_dispute_table_name, $projects_closed_disputes_data)){
			if($this->db->insert ($closed_dispute_table_name, $projects_closed_disputes_data)){
				
				$this->db->update('projects_disputes_attachments', ['dispute_status'=>'closed'], ['dispute_reference_id'=>$dispute_reference_id]);
			
				$this->db->update('projects_disputes_admin_arbitration_fees_tracking',array('dispute_winner_id'=>$winner_id,'dispute_end_date'=>$current_date),array('dispute_reference_id' => $dispute_reference_id));

				$this->db->delete($active_dispute_table_name, ['dispute_reference_id' => $dispute_reference_id]);
			
				$counter_offer_result_array = $this->db // get the user detail
				->select('*')
				->from($active_dispute_counter_offer_table_name)
				->where(['dispute_reference_id'=>$dispute_reference_id])
				->get()->result_array();

				if(!empty($counter_offer_result_array)){
					foreach($counter_offer_result_array as $counter_offer_key=>$counter_offer_value){
						$counter_offer_value['counter_status'] = 'admin_decision';
						unset($counter_offer_value['id']);
						$this->db->insert($closed_dispute_counter_offer_table_name, $counter_offer_value);
					}
					$this->db->delete($active_dispute_counter_offer_table_name, ['dispute_reference_id'=>$projects_active_disputes_data['dispute_reference_id']]);
					
				}
				
				if($winner_id == $sp_id){
					$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();

					$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id IS NOT NULL')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					if($project_type == 'fixed'){
						$release_escrow_reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}
					if($project_type == 'hourly'){
						$release_escrow_reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}
					if($project_type == 'fulltime'){
						$release_escrow_reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}					
					$digits = $config['reference_id_digits_limit'];
					if(empty($latest_released_escrow_data)) {
						$release_escrow_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($release_escrow_reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = (int)$exclude_inital_number + 1;
						$release_escrow_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					
					$vat_percentage = $config['vat_percentage'];

					$charged_service_fees_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
					$digits = $config['reference_id_digits_limit'];

					if(empty($latest_charged_service_fees)) {
						$charged_service_fees_reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
					} else {
						$exclude_inital_number = str_replace($charged_service_fees_reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
						$exclude_inital_number = ltrim($exclude_inital_number, '0');
						$exclude_inital_number = (int)$exclude_inital_number + 1;
						$charged_service_fees_reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
					}
					//}	
					
					$released_escrow_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
					
					if($project_type == 'fulltime'){
						$released_escrow_data['fulltime_project_id'] = $project_id;
						$released_escrow_data['employer_id'] = $po_id;
						$released_escrow_data['employee_id'] = $sp_id;
						
					}else{	
					
						$released_escrow_data['project_id'] = $project_id;
						$released_escrow_data['project_owner_id'] = $po_id;
						$released_escrow_data['winner_id'] = $sp_id;
					}
					
					$released_escrow_data['released_escrow_payment_amount'] = $projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'];
					
					
					
					
					$released_escrow_data['service_fee_charges'] = str_replace(',','',number_format($service_fee_charges,2));
					$released_escrow_data['total_escrow_payment_value'] = str_replace(',','',number_format(($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee']+$service_fee_charges),2));
					$released_escrow_data['payment_via_dispute'] = 'Y';
					$released_escrow_data['escrow_payment_release_date'] = $current_date;
					$released_escrow_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
					
					
					if($this->db->insert ($released_escrow_table_name, $released_escrow_data)){
					//if(1){
						
						$this->db->set('user_account_balance', 'user_account_balance+'.$projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'], false);
						$this->db->where('user_id',$sp_data['user_id']);
						$this->db->update('users_details');
						
						$charged_service_fees_data['charged_service_fee_reference_id'] = $charged_service_fees_reference_id;
						$charged_service_fees_data['project_id'] = $project_id;
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $release_escrow_reference_id;
						
						$charged_service_fees_data['project_owner_id'] = $po_id;
						$charged_service_fees_data['winner_id'] = $sp_id;
						
						$charged_service_fees_data['payment_via_dispute'] = 'Y';
						$charged_service_fees_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
						
						
						if($project_type == 'fixed'){
							$charged_service_fees_data['project_type'] = 'fixed_budget';
						}
						if($project_type == 'hourly'){
							$charged_service_fees_data['project_type'] = 'hourly_rate';
						}
						if($project_type == 'fulltime'){
							$charged_service_fees_data['project_type'] = 'fulltime';
						}
						
						$charged_service_fees_data['escrow_payment_release_date'] = $released_escrow_data['escrow_payment_release_date'];
						$charged_service_fees_data['released_escrow_payment_amount'] = $released_escrow_data['released_escrow_payment_amount'];
						$charged_service_fees_data['charged_service_fee_value'] = $released_escrow_data['service_fee_charges'];
						$charged_service_fees_data['vat_percentage_value'] = $vat_percentage;
						
						
						$service_fee_value_excl_vat = ($released_escrow_data['service_fee_charges'] * 100 ) / ($vat_percentage + 100);
						
						
						$vat_amount = $released_escrow_data['service_fee_charges'] - $service_fee_value_excl_vat;
						
						
						$charged_service_fees_data['charged_service_fee_value_excl_vat'] = $service_fee_value_excl_vat;
						
						$charged_service_fees_data['charged_service_fee_vat_amount_value'] = $vat_amount;
						
						//$this->project_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
						$this->projects_disputes_model->insert_data_into_charged_service_fees_tracking_disputes($charged_service_fees_data, $released_escrow_data);
						
						if($project_type == 'fulltime')
						{
							$released_escrow_condition = array('fulltime_project_id'=>$projects_active_disputes_data['disputed_fulltime_project_id'],'employer_id'=>$projects_active_disputes_data['employer_id_of_disputed_fulltime_project'],'employee_id'=>$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']);
						}else{
							$released_escrow_condition = array('project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']);
						}		
						$total_released_escrow = $this->project_model->get_sum_released_escrow_amounts_project_sp($project_type,$released_escrow_condition);
						
						
						if($project_type == 'fulltime'){
							$this->db->update($closed_dispute_table_name, ['fulltime_project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
						}else{	
							$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
						}
						
						if($project_type != 'fulltime')
						{	
							$check_complete_bid_data_exists =  $this->db->where(['project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($completed_bid_table_name)->count_all_results(); // check dsipute exists or not
							
							
							
							// update when bid exists in completed bid table
							if($check_complete_bid_data_exists != 0){
								$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
								
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
										$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
									}
									if($project_type == 'fixed'){
										$sp_activity_log_message = $config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_project_completed_sp_winner_user_activity_log_displayed_message'];
									}
									if($project_type == 'hourly'){
										$sp_activity_log_message = $config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_project_completed_sp_winner_user_activity_log_displayed_message'];
									}								
									
									
									$sp_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{po_profile_url_link}'),array($project_url_link,$project_title,$po_name,VPATH.$po_data['profile_name']),$sp_activity_log_message);
									
									if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
										
										if($sp_data['gender'] == 'M'){
											if($project_type == 'fixed'){
												$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_male_sp_winner_user_activity_log_displayed_message']; 
											}
											if($project_type == 'hourly'){
												$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_male_sp_winner_user_activity_log_displayed_message']; 
											}
										}else{
											if($project_type == 'fixed'){
												$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_female_sp_winner_user_activity_log_displayed_message']; 
											}
											if($project_type == 'hourly'){
												$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_female_sp_winner_user_activity_log_displayed_message']; 
											}

										}
										
										$po_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name}','{sp_profile_url_link}'),array($project_url_link,$project_title,$sp_name,VPATH.$sp_data['profile_name']),$po_activity_log_message);
										
									}else{

										if($project_type == 'fixed'){
											$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_company_sp_winner_user_activity_log_displayed_message']; 
										}
										if($project_type == 'hourly'){
											$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_project_completed_company_sp_winner_user_activity_log_displayed_message']; 
										}
										$po_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_company_name}','{sp_profile_url_link}'),array($project_url_link,$project_title,$sp_name,VPATH.$sp_data['profile_name']),$po_activity_log_message);
									}
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
											$ftp_config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
											$ftp_config['ftp_username'] = FTP_USERNAME;
											$ftp_config['ftp_password'] = FTP_PASSWORD;
											$ftp_config['ftp_port'] 	= FTP_PORT;
											$ftp_config['debug']    = TRUE;
											$this->ftp->connect($ftp_config); 
											######## connectivity of remote server end #######
											
											
											$users_ftp_dir 	= USERS_FTP_DIR; 
											$projects_ftp_dir = PROJECTS_FTP_DIR;
											$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
											$project_incomplete_dir = $config['project_incomplete_dir'];
											$users_bid_attachments_dir = $config['users_bid_attachments_dir'];
										
											$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
											$users_bid_attachments_dir = $config['users_bid_attachments_dir'];
											$project_completed_dir =PROJECT_COMPLETED_DIR;
											
											$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
											$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.DIRECTORY_SEPARATOR);
											
											
											$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
											$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir);
											$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR);
											$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$project_owner_attachments_dir);
											
											
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
														
														$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir);
														$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
														
														
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
									
								
									$inprogress_bid_detail = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->row_array();
									if(!empty($inprogress_bid_detail)){
										$check_already_in_complete_bid = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']])->from($incomplete_bid_table_name)->count_all_results();
										if($check_already_in_complete_bid == 0){
											unset($inprogress_bid_detail['id']);
											$this->db->insert ($incomplete_bid_table_name, $inprogress_bid_detail);
											$this->db->delete($inprogress_bid_table_name, ['project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id]); 
											$inprogress_project_detail = $this->db->get_where($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']])->row_array();
											
											if(!empty($inprogress_project_detail)){
												
												$check_already_in_complete_project = $this->db->where(['project_id' => $projects_active_disputes_data['disputed_project_id']])->from($incomplete_project_table_name)->count_all_results();
												
												if($check_already_in_complete_project == 0){
													
													unset($inprogress_project_detail['id']);
													$this->db->insert ($incomplete_project_table_name, $inprogress_project_detail);
													
													$this->db->delete($inprogress_project_table_name, ['project_id'=>$projects_active_disputes_data['disputed_project_id']]); 
													
													
													######## connectivity of remote server start#########
													$this->load->library('ftp');
													$ftp_config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
													$ftp_config['ftp_username'] = FTP_USERNAME;
													$ftp_config['ftp_password'] = FTP_PASSWORD;
													$ftp_config['ftp_port'] 	= FTP_PORT;
													$ftp_config['debug']    = TRUE;
													$this->ftp->connect($ftp_config); 
													######## connectivity of remote server end #######
													
													$project_id = $projects_active_disputes_data['disputed_project_id'];
													$users_ftp_dir 	= USERS_FTP_DIR; 
													$projects_ftp_dir = PROJECTS_FTP_DIR;
													$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
													$project_incomplete_dir = $config['project_incomplete_dir'];
													
													$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
													$users_bid_attachments_dir = $config['users_bid_attachments_dir'];
													
													$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir);
													$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir);
													$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.DIRECTORY_SEPARATOR);
													$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$project_owner_attachments_dir);
													
													
													
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
															
																$this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir);
																// $this->member_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'].DIRECTORY_SEPARATOR);
															
																//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir , 0777); 
																//$this->ftp->mkdir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_incomplete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] , 0777); 
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
										}
									}
								}	
							}
						}
					}
					########### Added code here ####
				}	
				
				//if($project_type == 'fixed'){
				if($winner_id == $po_id){
					
					
					if($project_type == 'fixed'){
						$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_po_winner_user_activity_log_displayed_message'];
					}
					if($project_type == 'hourly'){
						$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_po_winner_user_activity_log_displayed_message'];
					}
					if($project_type == 'fulltime'){
						$po_activity_log_message = $config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_employer_winner_user_activity_log_displayed_message'];
					}
					if($project_type == 'fixed'){
						$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}','{fixed_budget_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					}
					if($project_type == 'hourly'){
						$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}','{hourly_rate_based_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					}
					if($project_type == 'fulltime'){
						$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					}					
						
					$po_activity_log_message = str_replace(array('{dispute_reference_id}','{sp_profile_url_link}','{user_first_name_last_name_or_company_name}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					
					if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						
						if($po_data['gender']  == 'M'){
							if($project_type == 'fixed'){
								$sp_activity_log_message = $config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_male_po_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'hourly'){
								$sp_activity_log_message = $config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_male_po_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'fulltime'){
								$sp_activity_log_message = $config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_male_employer_winner_user_activity_log_displayed_message'];
							}
						}else{
							if($project_type == 'fixed'){
								$sp_activity_log_message = $config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_female_po_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'hourly'){
								$sp_activity_log_message = $config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_female_po_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'fulltime'){
								$sp_activity_log_message = $config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_female_employer_winner_user_activity_log_displayed_message'];
							}							
						}
						$sp_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($po_name),$sp_activity_log_message);
						
					}else{
						if($project_type == 'fixed'){
							$sp_activity_log_message = $config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_company_po_winner_user_activity_log_displayed_message'];
						}
						if($project_type == 'hourly'){
							$sp_activity_log_message = $config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_company_po_winner_user_activity_log_displayed_message'];
						}
						if($project_type == 'fulltime'){
							$sp_activity_log_message = $config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_company_employer_winner_user_activity_log_displayed_message'];
						}
						
						$sp_activity_log_message = str_replace(array('{user_company_name}'),array($po_name),$sp_activity_log_message);
					}
					
					
					if($project_type == 'fixed'){
						
						$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
						
					}
					if($project_type == 'hourly'){
						$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
					}
					if($project_type == 'fulltime'){
						$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
					}	
					
					$sp_activity_log_message = str_replace(array('{dispute_reference_id}','{po_profile_url_link}'),array($dispute_reference_id,HTTP_WEBSITE_HOST.$po_data['profile_name']),$sp_activity_log_message);
					
					
					user_display_log($po_activity_log_message,$po_data['user_id']);
					user_display_log($sp_activity_log_message,$sp_data['user_id']);
				}	
				
				if($winner_id == $sp_id){
					
					if($project_type == 'fixed'){
					
						$sp_activity_log_message = $config['fixed_budget_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_sp_winner_user_activity_log_displayed_message'];
						
						$sp_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
					
					}
					if($project_type == 'hourly'){
					
						$sp_activity_log_message = $config['hourly_rate_based_project_message_sent_to_sp_when_dispute_project_decided_admin_arbitration_sp_winner_user_activity_log_displayed_message'];
						
						$sp_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
					
					}
					if($project_type == 'fulltime'){
					
						$sp_activity_log_message = $config['fulltime_project_message_sent_to_employee_when_dispute_project_decided_admin_arbitration_employee_winner_user_activity_log_displayed_message'];
						
						$sp_activity_log_message = str_replace(array('{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_activity_log_message);
					
					}
					
					$sp_activity_log_message = str_replace(array('{dispute_reference_id}','{po_profile_url_link}','{user_first_name_last_name_or_company_name}'),array($dispute_reference_id,HTTP_WEBSITE_HOST.$po_data['profile_name'],$po_name),$sp_activity_log_message);
					
					
					if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						
						if($sp_data['gender']  == 'M'){
							
							if($project_type == 'fixed'){
							
								$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_male_sp_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'hourly'){
							
								$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_male_sp_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'fulltime'){
							
								$po_activity_log_message = $config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_male_employee_winner_user_activity_log_displayed_message'];
							}
						}else{
							if($project_type == 'fixed'){
								$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_female_sp_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'hourly'){
								$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_female_sp_winner_user_activity_log_displayed_message'];
							}
							if($project_type == 'fulltime'){
								$po_activity_log_message = $config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_female_employee_winner_user_activity_log_displayed_message'];
							}
						}
						
						$po_activity_log_message = str_replace(array('{user_first_name_last_name}'),array($sp_name),$po_activity_log_message); 
						
						
					}else{
						if($project_type == 'fixed'){
							$po_activity_log_message = $config['fixed_budget_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_company_sp_winner_user_activity_log_displayed_message'];
						}
						if($project_type == 'hourly'){
							$po_activity_log_message = $config['hourly_rate_based_project_message_sent_to_po_when_dispute_project_decided_admin_arbitration_company_sp_winner_user_activity_log_displayed_message'];
						}
						if($project_type == 'fulltime'){
							$po_activity_log_message = $config['fulltime_project_message_sent_to_employer_when_dispute_project_decided_admin_arbitration_company_employee_winner_user_activity_log_displayed_message'];
						}
						
						$po_activity_log_message = str_replace(array('{user_company_name}'),array($sp_name),$po_activity_log_message); 
					}
					
					if($project_type == 'fixed'){
						$po_activity_log_message = str_replace(array('{fixed_budget_project_disputed_amount}','{fixed_budget_project_disputed_amount_excluding_admin_arbitration_fee}','{fixed_budget_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					}
					if($project_type == 'hourly'){
						$po_activity_log_message = str_replace(array('{hourly_rate_based_project_disputed_amount}','{hourly_rate_based_project_disputed_amount_excluding_admin_arbitration_fee}','{hourly_rate_based_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					}
					if($project_type == 'fulltime'){
						$po_activity_log_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					}					
					
					
					$po_activity_log_message = str_replace(array('{dispute_reference_id}','{sp_profile_url_link}','{user_company_name}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_activity_log_message);
					
					user_display_log($po_activity_log_message,$po_data['user_id']);
					user_display_log($sp_activity_log_message,$sp_data['user_id']);	
				}	
				//}
				
				
				if($winner_id == $po_id){
					
					$this->db->update('projects_disputes_attachments', ['dispute_status'=>'closed'], ['dispute_reference_id'=>$dispute_reference_id]);
					
					// For po
					$this->db->set('user_account_balance', 'user_account_balance+'.str_replace(',','',number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee']+$service_fee_charges,2)), false);
					
					$this->db->where('user_id',$po_data['user_id']);
					$this->db->update('users_details');
					$po_reverted_amount_data['dispute_reference_id'] = $projects_active_disputes_data['dispute_reference_id'];
					
					if($project_type == 'fulltime'){
						$po_reverted_amount_data['disputed_fulltime_project_id'] = $project_id;
						$po_reverted_amount_data['employer_id'] = $po_id;
						$po_reverted_amount_data['employee_id'] = $sp_id;
					}else{
						$po_reverted_amount_data['disputed_project_id'] = $project_id;
						$po_reverted_amount_data['po_id'] = $po_id;
						$po_reverted_amount_data['sp_id'] = $sp_id;
					}	
					$po_reverted_amount_data['dispute_resolution_method'] = 'admin_decision';
					$po_reverted_amount_data['dispute_close_date'] = $current_date;
					$po_reverted_amount_data['reverted_amount'] =$projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'];
					$po_reverted_amount_data['reverted_service_fee_amount'] =$service_fee_charges; 
					$po_reverted_amount_data['reverted_total_amount'] = str_replace(',','',number_format(($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee']+$service_fee_charges),2));
					$this->db->insert ($po_reverted_amount_tracking_table, $po_reverted_amount_data);
					
					if($project_type == 'fulltime')
					{
						$released_escrow_condition = array('fulltime_project_id'=>$projects_active_disputes_data['disputed_fulltime_project_id'],'employer_id'=>$projects_active_disputes_data['employer_id_of_disputed_fulltime_project'],'employee_id'=>$projects_active_disputes_data['employee_winner_id_of_disputed_fulltime_project']);
					}else{
						$released_escrow_condition = array('project_id'=>$projects_active_disputes_data['disputed_project_id'],'project_owner_id'=>$projects_active_disputes_data['project_owner_id_of_disputed_project'],'winner_id'=>$projects_active_disputes_data['sp_winner_id_of_disputed_project']);
					}		
					$total_released_escrow = $this->project_model->get_sum_released_escrow_amounts_project_sp($project_type,$released_escrow_condition);
					
					
					if($project_type == 'fulltime'){
						$this->db->update($closed_dispute_table_name, ['fulltime_project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
					}else{	
						$this->db->update($closed_dispute_table_name, ['project_value_after_dispute_close'=>$total_released_escrow], ['dispute_reference_id'=>$dispute_reference_id]);
					}
					
					
				}
				
				// Email Code ///
				#### Email code when dispute is closed start ##
				//$project_dispute_details_page_url
				###################### SMTP variables start here ##########
				$email_config['protocol'] = $config['protocol'];
				$email_config['smtp_host']    = $config['smtp_host'];
				$email_config['smtp_port']    = $config['smtp_port'];
				$email_config['smtp_timeout'] = $config['smtp_timeout'];
				$email_config['smtp_user']    = $config['smtp_user'];
				$email_config['smtp_pass']    = $config['smtp_pass'];
				$email_config['charset'] =  $config['charset'];
				$email_config['mailtype'] =  $config['mailtype'];
				$email_config['newline'] =  $config['newline'];
				
				
				
				###################### SMTP variables end here ##########
				$this->load->library ('email');
				
				$this->email->initialize($email_config);
				$po_to = $po_data['email'];
				$sp_to = $sp_data['email'];
				if($winner_id == $po_id){
					if($project_type == 'fulltime'){
						$po_cc = $config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'];
						$po_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'];
						$po_from = $config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'];
						
						$po_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'];
						$po_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'];
						$po_subject = $config['project_dispute_email_subject_sent_to_employer_dispute_decided_admin_arbitration_po_winner'];
						$po_message = $config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_employer_winner'];
						
						$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_first_name_last_name_or_company_name}','{po_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);
					}else{	
						$po_cc = $config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						$po_bcc = $config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						$po_from = $config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						
						$po_reply_to = $config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						$po_from_name = $config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						$po_subject = $config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						$po_message = $config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_po_winner'];
						
						$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_first_name_last_name_or_company_name}','{po_first_name_last_name_or_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);
					}	
					
					$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
					$po_subject = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$po_subject);
					
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
				if($winner_id == $po_id){
					if($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								
						if($po_data['gender']  == 'M'){
							
							if($project_type == 'fulltime'){
								
								$sp_cc = $config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								$sp_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								$sp_from = $config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								
								$sp_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								$sp_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								$sp_subject = $config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								$sp_message = $config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_male_employer_winner'];
								
								
							}else{	
								$sp_cc = $config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
								$sp_bcc = $config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
								$sp_from = $config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
								
								$sp_reply_to = $config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
								$sp_from_name = $config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
								$sp_subject = $config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
								$sp_message = $config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_male_po_winner'];
							}	
						}else{
							
							if($project_type == 'fulltime'){
								
								$sp_cc = $config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								$sp_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								$sp_from = $config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								
								$sp_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								$sp_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								$sp_subject = $config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								$sp_message = $config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_female_employer_winner'];
								
							}else{
								$sp_cc = $config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
								$sp_bcc = $config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
								$sp_from = $config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
								
								$sp_reply_to = $config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
								$sp_from_name = $config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
								$sp_subject = $config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
								$sp_message = $config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_female_po_winner'];
							}	
								
						}	
						
						if($project_type == 'fulltime'){
							
							$sp_message = str_replace(array('{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_message);
							
						}else{
							$sp_message = str_replace(array('{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}'),array(str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_message);
						}		
						
						$sp_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{po_profile_url_link}','{po_first_name_last_name}','{sp_first_name_last_name_or_company_name}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$po_data['profile_name'],$po_name,$sp_name),$sp_message);
					}else{
						
						if($project_type == 'fulltime'){
							
							$sp_cc = $config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							$sp_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							$sp_from = $config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							$sp_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							$sp_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							$sp_subject = $config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							$sp_message = $config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_company_employer_winner'];
							
							$sp_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{po_profile_url_link}','{po_company_name}','{sp_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$po_data['profile_name'],$po_name,$sp_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_message);

							
						}else{
							$sp_cc = $config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'];
							$sp_bcc = $config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'];
							$sp_from = $config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'];
							
							$sp_reply_to = $config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_companypo_winner'];
							$sp_from_name = $config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'];
							$sp_subject = $config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'];
							$sp_message = $config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_company_po_winner'];
							
							$sp_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{po_profile_url_link}','{po_company_name}','{sp_first_name_last_name_or_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$po_data['profile_name'],$po_name,$sp_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_message);
						}

					}		
							
					$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';
					$sp_subject = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$sp_subject);
					$this->email->from ($sp_from,$sp_from_name);
					$this->email->to ($sp_to);
					if($po_cc){
						$this->email->cc ($po_cc);
					}
					if($po_bcc){
						$this->email->bcc ($po_bcc);
					}
					$this->email->subject ($sp_subject);
					$this->email->reply_to($sp_reply_to);
					$this->email->set_mailtype ('html');
					$this->email->set_newline("\r\n");
					$this->email->message ($sp_message);
					$this->email->send ();
				}	
				if($winner_id == $sp_id){
					
					if($project_type == 'fulltime'){
						$sp_cc = $config['fulltime_project_dispute_email_cc_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];
						$sp_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];
						$sp_from = $config['fulltime_project_dispute_email_from_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];
						
						$sp_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];
						$sp_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];
						$sp_subject = $config['fulltime_project_dispute_email_subject_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];
						$sp_message = $config['fulltime_project_dispute_email_message_sent_to_employee_dispute_decided_admin_arbitration_employee_winner'];	
						
						
						$sp_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{po_profile_url_link}','{po_first_name_last_name_or_company_name}','{sp_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$po_data['profile_name'],$po_name,$sp_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_message);
					}else{
					
						$sp_cc = $config['project_dispute_email_cc_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];
						$sp_bcc = $config['project_dispute_email_bcc_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];
						$sp_from = $config['project_dispute_email_from_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];
						
						$sp_reply_to = $config['project_dispute_email_reply_to_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];
						$sp_from_name = $config['project_dispute_email_from_name_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];
						$sp_subject = $config['project_dispute_email_subject_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];
						$sp_message = $config['project_dispute_email_message_sent_to_sp_dispute_decided_admin_arbitration_sp_winner'];	
						
						
						$sp_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{po_profile_url_link}','{po_first_name_last_name_or_company_name}','{sp_first_name_last_name_or_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$po_data['profile_name'],$po_name,$sp_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY),$sp_message);
					
					}
					
					$sp_from_name = '=?utf-8?B?'.base64_encode($sp_from_name).'?=';
					$sp_subject = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$sp_subject);
					
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
				if($winner_id == $sp_id){
					
					
					
					if($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
						if($sp_data['gender']  == 'M'){
							
							if($project_type == 'fulltime'){
								$po_cc = $config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_from = $config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_subject = $config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_message = $config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_male_employee_winner'];
								$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_first_name_last_name}','{po_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);

							}else{	
								$po_cc = $config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'];
								$po_bcc = $config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'];
								$po_from = $config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'];
								$po_reply_to = $config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'];
								$po_from_name = $config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_male_po_winner'];
								$po_subject = $config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'];
								$po_message = $config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_male_sp_winner'];
								$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_first_name_last_name}','{po_first_name_last_name_or_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);
							}
							
						}else{
							
							if($project_type == 'fulltime'){
								
								$po_cc = $config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_from = $config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_subject = $config['fulltime_project_dispute_email_subject_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_message = $config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_female_employee_winner'];
								$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_first_name_last_name}','{po_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);	
							}else{
								
								$po_cc = $config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'];
								$po_bcc = $config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'];
								$po_from = $config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'];
								$po_reply_to = $config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'];
								$po_from_name = $config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_female_po_winner'];
								$po_subject = $config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'];
								$po_message = $config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_female_sp_winner'];
								$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_first_name_last_name}','{po_first_name_last_name_or_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);	
							}	
						}
					}else{
						if($project_type == 'fulltime'){
							
							$po_cc = $config['fulltime_project_dispute_email_cc_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_bcc = $config['fulltime_project_dispute_email_bcc_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_from = $config['fulltime_project_dispute_email_from_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_reply_to = $config['fulltime_project_dispute_email_reply_to_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_from_name = $config['fulltime_project_dispute_email_from_name_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_subject = $config['fulltime_project_dispute_email_subject_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_message = $config['fulltime_project_dispute_email_message_sent_to_employer_dispute_decided_admin_arbitration_company_employee_winner'];
							$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_company_name}','{po_first_name_last_name_or_company_name}','{fulltime_project_disputed_amount}','{fulltime_project_disputed_amount_excluding_admin_arbitration_fee}','{fulltime_project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);
						}else{
							$po_cc = $config['project_dispute_email_cc_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'];
							$po_bcc = $config['project_dispute_email_bcc_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'];
							$po_from = $config['project_dispute_email_from_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'];
							$po_reply_to = $config['project_dispute_email_reply_to_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'];
							$po_from_name = $config['project_dispute_email_from_name_sent_to_po_dispute_decided_admin_arbitration_company_po_winner'];
							$po_subject = $config['project_dispute_email_subject_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'];
							$po_message = $config['project_dispute_email_message_sent_to_po_dispute_decided_admin_arbitration_company_sp_winner'];
							$po_message = str_replace(array('{dispute_reference_id}','{project_dispute_details_page_url}','{sp_profile_url_link}','{sp_company_name}','{po_first_name_last_name_or_company_name}','{project_disputed_amount}','{project_disputed_amount_excluding_admin_arbitration_fee}','{project_disputed_amount_service_fees}','{admin_dispute_arbitration_amount_fee}'),array($dispute_reference_id,$project_dispute_details_page_url,HTTP_WEBSITE_HOST.$sp_data['profile_name'],$sp_name,$po_name,str_replace(".00","",number_format($projects_active_disputes_data['disputed_amount'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['disputed_amount_excluding_admin_arbitration_fee'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_active_disputes_data['disputed_service_fees'],  2, '.', ' '))." ". CURRENCY,str_replace(".00","",number_format($projects_disputes_admin_arbitration_fees_data['admin_dispute_arbitration_amount_fee'],  2, '.', ' '))." ". CURRENCY),$po_message);
						}		
					}
					$po_from_name = '=?utf-8?B?'.base64_encode($po_from_name).'?=';
					$po_subject = str_replace(array('{dispute_reference_id}'),array($dispute_reference_id),$po_subject);
					
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
				
				
				############## code before this always ####
			}
		}
		$this->session->set_flashdata('succ_msg', 'Admin taken action successfully');

		if($this->input->get ('search_element')){
			$search_element = $this->input->get ('search_element');
		}else{
			$search_element = '';
		}
		if($this->input->get ('per_page')){
			$page = $this->input->get ('per_page');
		}else{
			$page = 1;
		}	
		$new_counter = $page;
		if($page != null) {
			if($page == 1) {
				$start = 0;
				} else {
				
				$start = ($page - 1) * PAGING_LIMIT;
			}
		} else {
			$start = 0;
		}
		
		
		$params['minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration'] = $config['minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration'];
		
		$params['minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration'] = $config['minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration'];
		
		$params['minimum_required_disputed_fulltime_project_value_for_admin_arbitration'] = $config['minimum_required_disputed_fulltime_project_value_for_admin_arbitration'];
		
		$projects_disputes_listing = $this->projects_disputes_model->awaiting_arbitration_projects_disputes_listing($start,PAGING_LIMIT,$search_element,$params);
		
		if(empty($projects_disputes_listing)){
			
			$page = $new_counter-1;
			if($page != null) {
			
				if($page == 1) {
					$start = 0;
				} else {
					
				$start = ($page - 1) *PAGING_LIMIT;
				}
			} else {
				$start = 0;
				}
		}
		redirect(base_url()."projects_disputes/awaiting_arbitration_projects_disputes_listing/?search_element=" . $search_element."&per_page=".$page);
	}	
	
}
