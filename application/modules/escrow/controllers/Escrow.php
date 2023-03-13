<?php
if ( ! defined ('BASEPATH')) {
exit ('No direct script access allowed');
}
class Escrow extends MX_Controller
{
    public function __construct ()
    {	
		parent::__construct ();
        $this->load->model ('Escrow_model');
        $this->load->model('projects/Projects_model'); //used index, edit_job
		$this->load->model('user/User_model');
    }
		
	/*
	This function is used to save requested escrow into database which is created by SP - Done
	
	*/
	public function save_requested_escrow_sp(){
	
		if($this->input->is_ajax_request ()){
			$project_id = $this->input->post ('project_id');
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
				$post_data = $this->input->post ();
				$user = $this->session->userdata('user');
				
				$po_id = Cryptor::doDecrypt($post_data['po_id']);
				$sp_id = Cryptor::doDecrypt($post_data['sp_id']);
				
				if($sp_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				
				$section_name = $this->input->post ('section_name');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				}else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				}else if($project_data['project_type'] == 'fulltime'){
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>$user[0]->user_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employee_tries_create_escrow_request_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>$user[0]->user_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_sp_tries_create_escrow_request_on_active_disputed_project');
				}
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}	
				
				
				
				
				$validation_data_array = $this->Escrow_model->create_escrow_request_validation_sp($post_data,$project_data);
				if($validation_data_array['status'] == 'SUCCESS'){
					$post_data['escrow_request_amount'] =  str_replace(" ","",$post_data['escrow_request_amount']);
					if($project_data['project_type'] == 'fixed'){
					 $notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_created_escrow_request');
					 $requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';	
					 $requested_escrow_data['project_id'] = $project_id;
					 $requested_escrow_data['project_owner_id'] = $po_id;
					 $requested_escrow_data['winner_id']= $sp_id ;
					 $requested_escrow_data['requested_escrow_description']= trim($post_data['escrow_description']);
					 $requested_escrow_data['requested_escrow_amount']= $post_data['escrow_request_amount'];
					 $requested_escrow_data['escrow_requested_by_sp_date']= date('Y-m-d H:i:s');
					} else if($project_data['project_type'] == 'hourly') {
						$notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_created_escrow_request');
						
						$post_data['escrow_request_no_of_hours'] = str_replace(" ","",$post_data['escrow_request_no_of_hours']);
						$post_data['escrow_request_hourly_rate'] = str_replace(" ","",$post_data['escrow_request_hourly_rate']);
						
						$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';	
						$requested_escrow_data['project_id'] = $project_id;
						$requested_escrow_data['project_owner_id'] = $po_id;
						$requested_escrow_data['winner_id']= $sp_id ;
						$requested_escrow_data['requested_escrow_description']= trim($post_data['escrow_description']);
						$requested_escrow_data['requested_escrow_amount']= ($post_data['escrow_request_no_of_hours'] * $post_data['escrow_request_hourly_rate']);
						$requested_escrow_data['escrow_requested_by_sp_date']= date('Y-m-d H:i:s');
						$requested_escrow_data['sp_requested_escrow_creation_number_of_hours']= $post_data['escrow_request_no_of_hours'];
						$requested_escrow_data['sp_requested_escrow_creation_hourly_rate']= $post_data['escrow_request_hourly_rate'];
					} else if($project_data['project_type'] == 'fulltime') {
						$notification_message = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employee_when_employee_created_escrow_request');
						$requested_escrow_table_name = 'fulltime_projects_requested_escrows';	
						$requested_escrow_data['fulltime_project_id'] = $project_id;
						$requested_escrow_data['employer_id'] = $po_id;
						$requested_escrow_data['employee_id']= $sp_id ;
						$requested_escrow_data['requested_escrow_description']= trim($post_data['escrow_description']);
						$requested_escrow_data['requested_escrow_amount']= $post_data['escrow_request_amount'];
						$requested_escrow_data['escrow_requested_by_employee_date']= date('Y-m-d H:i:s');
					}
					$this->db->insert ($requested_escrow_table_name, $requested_escrow_data);
					$last_insert_id = $this->db->insert_id();
					$get_requested_escrow_data = $this->db 
					->select ('rm.*')
					->from ($requested_escrow_table_name. ' as rm')
					->where('id',$last_insert_id)
					->get ()->row_array ();

					if($project_data['project_type'] == 'fulltime') {
						$get_requested_escrow_data['winner_id'] = $get_requested_escrow_data['employee_id'];
						$get_requested_escrow_data['project_owner_id'] = $get_requested_escrow_data['employer_id'];
						$get_requested_escrow_data['project_id'] = $get_requested_escrow_data['fulltime_project_id'];
						$get_requested_escrow_data['escrow_requested_by_sp_date'] = $get_requested_escrow_data['escrow_requested_by_employee_date'];
					}
					
					if($project_data['project_type'] == 'fulltime') {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($project_data['project_type'],array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
					} else {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
					}
					
					######## Initiate paging again when new escrow is created###start
					if($project_data['project_type'] == 'fulltime') {
						$in_progress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$sp_id,'employer_id'=>$po_id])->row_array();
					} else {
						$in_progress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id,'project_owner_id'=>$po_id])->row_array();
					}
					if(!empty($completed_bid_table_name)) {
						$completed_bid_data = $this->db->get_where($completed_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id,'project_owner_id'=>$po_id])->row_array();
					}
					if($project_data['project_type'] == 'fulltime') {
						$requested_escrow_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($project_data['project_type'],array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$start, $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} else {
						$requested_escrow_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$start, $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					}
					
					$requested_escrow_data = $requested_escrow_listing_project_data['data'];
					$requested_escrow_count = $requested_escrow_listing_project_data['total'];
					
					/* $generate_pagination_links_escrow = $this->Escrow_model->generate_pagination_links_escrow($requested_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'bid_id'=>$bid_id,'tab_type'=>'requested_escrow','section_name'=>$section_name,'project_type'=>$project_data['project_type'] )); */
					
					
					$paginations = generate_pagination_links($requested_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_requested_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$project_data['project_type'],'data-section-id'=>$sp_id,'data-tab-type'=>'requested_escrow','data-section-name'=>$section_name ]);
					
					$rec_per_page = ($requested_escrow_count > $this->config->item('project_detail_page_requested_escrow_listing_limit')) ? $this->config->item('project_detail_page_requested_escrow_listing_limit') : $requested_escrow_count;
					######## Initiate paging again when new escrow is created###end
					
					######## get the detail of SP ##########
					
					$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', $sp_id)
					->get()->row_array();
					
					$sp_name =(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					
					
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					$requested_escrow_value =  str_replace(".00","",number_format($post_data['escrow_request_amount'],  2, '.', ' '))." ". CURRENCY;
					
					############# log message for create escrow request ####
					if($project_data['project_type'] == 'fixed') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_created_escrow_request_user_activity_log_displayed_message');
									
								}else{	
								
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_created_escrow_request_user_activity_log_displayed_message');
								}
							}else{
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_created_escrow_request_user_activity_log_displayed_message');
								}else{
									
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_created_escrow_request_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						}else{
						
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_created_escrow_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						
						$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_created_escrow_request_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					} else if($project_data['project_type'] == 'hourly') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								if( $sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_male_created_escrow_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_male_created_escrow_request_user_activity_log_displayed_message');
								}
							}else{
								if( $sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_female_created_escrow_request_user_activity_log_displayed_message');
								}else{
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_female_created_escrow_request_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						}else{
						
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_created_escrow_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						
						$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_sp_created_escrow_request_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					} else if($project_data['project_type'] == 'fulltime') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_created_escrow_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_created_escrow_request_user_activity_log_displayed_message');
								}
							} else {
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_created_escrow_request_user_activity_log_displayed_message');
								}else{
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_created_escrow_request_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						} else {
						
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_created_escrow_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						
						$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_created_escrow_request_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					}

					user_display_log($sp_activity_log_message,$sp_id); // activity log message for service provider
					
					user_display_log($po_activity_log_message,$po_id); // activity log message for project owner
					
					$notification_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}"),array($po_name,$po_profile_url_link),$notification_message);
					
					echo json_encode(['status' => 200,'project_id'=>$project_id,'po_id'=>$po_id,'m_id'=>$last_insert_id,'location'=>'','msg'=>$notification_message,'data'=>$this->load->view('requested_escrow_row_detail_project_detail',array('requested_escrow_value'=>$get_requested_escrow_data,'project_type'=>$project_data['project_type'],'project_id'=>$project_data['project_id'],'section_id'=>$sp_id,'section_name'=>$section_name), true),'sum_escrow_amount'=>str_replace(".00","",number_format($sum_requested_escrow_amount_project,  2, '.', ' ')),'paging_data'=>$this->load->view('escrow_section_paging_project_detail',array('escrow_count'=>$requested_escrow_count,'generate_pagination_links_escrow'=>$paginations['links'],'escrow_paging_limit'=>$this->config->item('project_detail_page_requested_escrow_listing_limit')), true)]);
					die;
					
				} else {
					//echo json_encode($msg);
					echo json_encode ($validation_data_array);
					die;
				}
			}
		} else {
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	* This function is used to make the popup of cancel requested escrow confirmation . - Done
	*/
	public function cancel_requested_escrow_confirmation_popup_body(){
	
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
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_cancel_requested_escrow_deleted_project')]);
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

				$cancel_requested_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_table_name = 'fulltime_projects_requested_escrows';
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$check_requested_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($requested_escrow_table_name)->count_all_results();
				} else {
					$check_requested_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($requested_escrow_table_name)->count_all_results();
				}
				if($check_requested_escrow_exists == 0){
					
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employee_view_cancel_invalid_requested_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_cancel_invalid_requested_escrow_project');
					}
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				$confirmation_modal_title = '';
				if(!empty($requested_escrow_data)){
					if($project_data['project_type'] == 'fulltime'){
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('cancel_requested_escrow_confirmation_fulltime_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" class="btn blue_btn default_btn cancel_requested_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('cancel_requested_escrow_confirmation_fulltime_project_modal_cancel_btn_txt').'</button>';
					} else {
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('cancel_requested_escrow_confirmation_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn cancel_requested_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('cancel_requested_escrow_confirmation_project_modal_cancel_btn_txt').'</button>';
					}
					$confirmation_modal_body.= '<form class="cancel_requested_escrow_form" name="cancel_requested_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"></div></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="cancel_requested_escrow_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$cancel_requested_escrow_disclaimer_message.'</span></label></div></div></div>';
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
	* This function is used to cancel the requested escrow by SP. - Done
	*/
	public function cancel_requested_escrow(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$tab_type = $this->input->post ('tab_type');
				$section_name = $this->input->post ('section_name');
				$section_id = $this->input->post ('section_id');
				$user = $this->session->userdata ('user');
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_cancel_requested_escrow_deleted_project')]);
					die;
					
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name','u.company_name','u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name','u.company_name','u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from('users u')
					->where('u.user_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
					
				if($project_data['project_type'] == 'fixed'){
					$requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_table_name = 'fulltime_projects_requested_escrows';
				}
		
				if($project_data['project_type'] == 'fulltime'){
					$check_requested_escrow_exists = $this->db->where(['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->from($requested_escrow_table_name)->count_all_results();
				} else {
					$check_requested_escrow_exists = $this->db->where(['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($requested_escrow_table_name)->count_all_results();
				}
				if($check_requested_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employee_view_cancel_invalid_requested_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_cancel_invalid_requested_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}

				if(!empty($requested_escrow_data)){
					
					if($project_data['project_type'] == 'fixed'){
						$sp_notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_cancelled_requested_escrow');
						
					} else if($project_data['project_type'] == 'hourly'){
						$sp_notification_message = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_sp_cancelled_requested_escrow');
					} else if($project_data['project_type'] == 'fulltime'){
						$sp_notification_message = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_requested_escrow');
					} 
					$p_type = $project_data['project_type'];
					
					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
						$page = 1;
					}
					
					if($project_data['project_type'] == 'fulltime') {
						$this->db->delete($requested_escrow_table_name, ['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)]); 
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
					} else {
						$this->db->delete($requested_escrow_table_name, ['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					}

					
					if($project_data['project_type'] == 'fulltime') {
					
						$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
							 
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} else {
					
						if($p_type=="fixed"){
						$requested_escrows_table = 'fixed_budget_projects_requested_escrows';
							}
						 if($p_type == "hourly"){
							$requested_escrows_table = 'hourly_rate_based_projects_requested_escrows';
						 }
						$total_record = $this->db->from($requested_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
						
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					}
					$data["requested_escrows_data"] = $requested_escrows_listing_project_data['data'];
					$data['requested_escrows_count'] = $requested_escrows_listing_project_data['total'];
					$data['generate_pagination_links_escrow'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					$data['project_type']= $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id;
					$data['section_name']= $section_name;
					
					$create_request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view');
					if($p_type == 'fulltime'){
						$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_request_payment_button_txt_employee_view');
					}
					$data['create_request_payment_button']= $create_request_payment_button;
					$multiplication = $this->config->item('project_detail_page_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_requested_escrow_listing_limit') - count($data['requested_escrows_data'])));
					$record_per_page = count($data['requested_escrows_data']) < $this->config->item('project_detail_page_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_requested_escrow_listing_limit') * ($page - 1)) + 1;
					
					################ sum of requested/eschrow_released escrow ########
					if($p_type == 'fulltime') { 
						$requested_escrow_count_project = get_requested_escrows_count_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
						$active_escrow_count_project = get_active_escrows_count_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
						$released_escrow_count_project = get_released_escrows_count_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
					} else {
						$requested_escrow_count_project = get_requested_escrows_count_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
						$active_escrow_count_project = get_active_escrows_count_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
						$released_escrow_count_project = get_released_escrows_count_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
					}

					if(($requested_escrow_count_project + $active_escrow_count_project + $released_escrow_count_project + $rejected_requested_escrow_count_project) > 0){
						$initial_requested_escrow_view_status = 1;
					} else {
						$initial_requested_escrow_view_status = 0;
					}
					
					######## get the detail of SP ##########
					$requested_escrow_value = str_replace(".00","",number_format($requested_escrow_data['requested_escrow_amount'],  2, '.', ' '));
					$requested_escrow_value = $requested_escrow_value." ".CURRENCY;

					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
				
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($project_data['project_type'] == 'fixed') { 
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_cancelled_escrow_request_user_activity_log_displayed_message');
								}else{								
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_cancelled_escrow_request_user_activity_log_displayed_message');
								}
							}else{
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_cancelled_escrow_request_user_activity_log_displayed_message');
								}else{
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_cancelled_escrow_request_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						}else{
						
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_cancelled_escrow_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						
						$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_cancelled_requested_escrow_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					} else if($project_data['project_type'] == 'hourly') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_male_cancelled_escrow_request_user_activity_log_displayed_message');
								}else{			
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_male_cancelled_escrow_request_user_activity_log_displayed_message');
								}
							}else{
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_female_cancelled_escrow_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_female_cancelled_escrow_request_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						} else {
						
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_cancelled_escrow_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						
						$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_sp_cancelled_requested_escrow_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					} else if($project_data['project_type'] == 'fulltime') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){	
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_cancelled_escrow_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_cancelled_escrow_request_user_activity_log_displayed_message');
								}
							} else {
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_cancelled_escrow_request_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_cancelled_escrow_request_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						} else {
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_cancelled_escrow_request_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_cancelled_requested_escrow_user_activity_log_displayed_message');
						$sp_activity_log_message = str_replace(array("{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					}
					
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					
					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner
					
					$data['sum_escrow_amount'] = $sum_requested_escrow_amount_project;
					
					
					echo json_encode(['status' => 200,'po_id'=>Cryptor::doDecrypt($po_id),'sp_msg'=>$sp_notification_message,'initial_requested_escrow_view_status'=>$initial_requested_escrow_view_status,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrows_listing_project_data['total'],'data'=>$this->load->view('escrow/requested_escrows_section_project_detail',$data, true)]);
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
	* This function is used to make the popup of requested escrow rejected confirmation . - Done
	*/
	public function rejected_requested_escrow_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_reject_requested_escrow_deleted_project')]);
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
				
				$reject_requested_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_table_name = 'fulltime_projects_requested_escrows';
					$reject_requested_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_requested_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($requested_escrow_table_name)->count_all_results();
				} else {
					$check_requested_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($requested_escrow_table_name)->count_all_results();
				}
				if($check_requested_escrow_exists == 0){
					
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_reject_invalid_requested_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_reject_invalid_requested_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				
				$confirmation_modal_title = '';
				if(!empty($requested_escrow_data)){
				
					if($project_data['project_type'] == 'fulltime'){
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('reject_requested_escrow_confirmation_fulltime_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" class="btn blue_btn default_btn reject_requested_escrow_po width-auto" disabled style="opacity:0.65">'.$this->config->item('reject_requested_escrow_confirmation_fulltime_project_modal_reject_btn_txt').'</button>';
					} else {
					
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('reject_requested_escrow_confirmation_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn blue_btn default_btn reject_requested_escrow_po width-auto" disabled style="opacity:0.65">'.$this->config->item('reject_requested_escrow_confirmation_project_modal_reject_btn_txt').'</button>';
					}
					$confirmation_modal_body.= '<form class="cancel_requested_escrow_form" name="reject_requested_milestone_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"></div></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="reject_requested_escrow_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$reject_requested_escrow_disclaimer_message.'</span></label></div></div></div>';
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
	* This function is used to reject the requested escrow by PO. - Done
	*/
	public function reject_requested_escrow(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				$user = $this->session->userdata ('user');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_reject_requested_escrow_deleted_project')]);
					die;
					
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				$project_type = $project_data['project_type'];
				if($project_data['project_type'] == 'fixed') {
					$requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';
	                $reject_requested_escrow_tracking_table_name = 'fixed_budget_projects_rejected_requested_escrows';
				   
				} else if($project_data['project_type'] == 'hourly'){
					$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';
					$reject_requested_escrow_tracking_table_name = 'hourly_rate_based_projects_rejected_requested_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_table_name = 'fulltime_projects_requested_escrows';
					$reject_requested_escrow_tracking_table_name = 'fulltime_projects_rejected_requested_escrows';
				}
		
		
				if($project_data['project_type'] == 'fulltime'){
					$check_requested_escrow_exists = $this->db->where(['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->from($requested_escrow_table_name)->count_all_results();
				} else {
					$check_requested_escrow_exists = $this->db->where(['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($requested_escrow_table_name)->count_all_results();
				}
				
				
				if($check_requested_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_reject_invalid_requested_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_reject_invalid_requested_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				
				/* if($requested_milestone_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_reject_requested_milestone_message')]);
					die;
				} */
				if(!empty($requested_escrow_data)){

					$p_type = $project_data['project_type'];
					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
						$page = 1;
					}
					############### delete the requested escrow
					if($project_data['project_type'] == 'fulltime'){
						$this->db->delete($requested_escrow_table_name, ['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)]); 
					} else {
						$this->db->delete($requested_escrow_table_name, ['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
					}
					############### track the history of reject the requested escrow
					if($project_data['project_type'] == 'fixed'){
						$reject_request_escrow_data['project_id'] = $requested_escrow_data['project_id'];
						$reject_request_escrow_data['project_owner_id'] = $requested_escrow_data['project_owner_id'];
						$reject_request_escrow_data['winner_id']= $requested_escrow_data['winner_id'] ;
						$reject_request_escrow_data['requested_escrow_description']= $requested_escrow_data['requested_escrow_description'] ;
						$reject_request_escrow_data['requested_escrow_amount']= $requested_escrow_data['requested_escrow_amount'];
						$reject_request_escrow_data['escrow_requested_by_sp_date']= $requested_escrow_data['escrow_requested_by_sp_date'];
						$reject_request_escrow_data['requested_escrow_rejection_date'] = date('Y-m-d H:i:s');
					} else if($project_data['project_type'] == 'hourly') {
						$reject_request_escrow_data['project_id'] = $requested_escrow_data['project_id'];
						$reject_request_escrow_data['project_owner_id'] = $requested_escrow_data['project_owner_id'];
						$reject_request_escrow_data['winner_id']= $requested_escrow_data['winner_id'] ;
						$reject_request_escrow_data['requested_escrow_description']= $requested_escrow_data['requested_escrow_description'] ;
						$reject_request_escrow_data['sp_requested_escrow_creation_hourly_rate']= $requested_escrow_data['sp_requested_escrow_creation_hourly_rate'];
						$reject_request_escrow_data['sp_requested_escrow_creation_number_of_hours']= $requested_escrow_data['sp_requested_escrow_creation_number_of_hours'];
						$reject_request_escrow_data['requested_escrow_amount']= $requested_escrow_data['requested_escrow_amount'];
						$reject_request_escrow_data['escrow_requested_by_sp_date']= $requested_escrow_data['escrow_requested_by_sp_date'];
						$reject_request_escrow_data['requested_escrow_rejection_date'] = date('Y-m-d H:i:s');
					} else if($project_data['project_type'] == 'fulltime') {
						$reject_request_escrow_data['fulltime_project_id'] = $requested_escrow_data['fulltime_project_id'];
						$reject_request_escrow_data['employer_id'] = $requested_escrow_data['employer_id'];
						$reject_request_escrow_data['employee_id']= $requested_escrow_data['employee_id'] ;
						$reject_request_escrow_data['requested_escrow_description']= $requested_escrow_data['requested_escrow_description'] ;
						$reject_request_escrow_data['requested_escrow_amount']= $requested_escrow_data['requested_escrow_amount'];
						$reject_request_escrow_data['escrow_requested_by_employee_date']= $requested_escrow_data['escrow_requested_by_employee_date'];
						$reject_request_escrow_data['requested_escrow_rejection_date'] = date('Y-m-d H:i:s');
					}
					
				   $this->db->insert ($reject_requested_escrow_tracking_table_name, $reject_request_escrow_data);
					$last_insert_id = $this->db->insert_id();
					
					if($project_data['project_type'] == 'fulltime'){ 
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
					} else {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					}
					
					/* $new_counter = $page;
						
					if($page != null) {
						
						if($page == 1) {
							$start = 0;
						} else {
							$start = ($page - 1) * $this->config->item('project_detail_page_requested_escrow_listing_limit');
						}
					} else {
						$start = 0;
					}
					if($project_data['project_type'] == 'fulltime'){
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} else {
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					}
					$data["requested_escrows_data"] = $requested_escrows_listing_project_data['data'];
					$page = $new_counter;
					if(empty($data["requested_escrows_data"])){
						$page = $new_counter-1;
						if($page != null) {
							if($page == 1) {
								$start = 0;
							} else {
								$start = ($page - 1) * $this->config->item('project_detail_page_requested_escrow_listing_limit');
							}
						} else {
						$start = 0;
						}
					}*/
					if($project_data['project_type'] == 'fulltime'){
					
					
						$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
							 
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
					
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} else {
					
						if($p_type=="fixed"){
						$requested_escrows_table = 'fixed_budget_projects_requested_escrows';
							}
						 if($p_type == "hourly"){
							$requested_escrows_table = 'hourly_rate_based_projects_requested_escrows';
						 }
						$total_record = $this->db->from($requested_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
						
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
					
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} 
					$data["requested_escrows_data"] = $requested_escrows_listing_project_data['data'];
					$data['requested_escrows_count'] = $requested_escrows_listing_project_data['total'];
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($requested_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					
					$data['generate_pagination_links_escrow'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$data['section_name']= $section_name;
					$data['project_type']= $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id;
					
					$create_request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view');
					if($p_type == 'fulltime'){
						$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_request_payment_button_txt_employee_view');
					}
					if($p_type== 'fixed'){
						$notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_po_when_po_rejected_escrow_creation_request');
					} else if($p_type== 'hourly') {
						$notification_message = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_po_rejected_escrow_creation_request');
					} else if($p_type== 'fulltime') {
						$notification_message = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employer_when_employer_rejected_escrow_creation_request');
					}
					$data['create_request_payment_button']= $create_request_payment_button;
					$multiplication = $this->config->item('project_detail_page_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_requested_escrow_listing_limit') - count($data['requested_escrows_data'])));
					$record_per_page = count($data['requested_escrows_data']) < $this->config->item('project_detail_page_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_requested_escrow_listing_limit') * ($page - 1)) + 1;
					
					################ sum of requested/eschrow_released escrow ########
					
					if($p_type== 'fulltime') { 
						$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
						$active_escrow_count_project = get_active_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
						$released_escrow_count_project = get_released_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
					} else {
						$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
						$active_escrow_count_project = get_active_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
						$released_escrow_count_project = get_released_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
					}

					if(($requested_escrow_count_project + $active_escrow_count_project + $released_escrow_count_project + $rejected_requested_escrow_count_project) > 0){
						$initial_requested_escrow_view_status = 1;
					} else {
						$initial_requested_escrow_view_status = 0;
					}

					######## get the detail of SP ##########
					$requested_escrow_value = str_replace(".00","",number_format($requested_escrow_data['requested_escrow_amount'],  2, '.', ' '));
					$requested_escrow_value = $requested_escrow_value." ".CURRENCY;
									
					$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
					
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
				
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($p_type== 'fixed') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] =='Y')){
							if($project_data['gender'] == 'M'){
								
								if($project_data['is_authorized_physical_person'] =='Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}else{	
								
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}								
							} else {
								if($project_data['is_authorized_physical_person'] =='Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}								
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
						
							
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_rejected_escrow_creation_request_user_activity_log_displayed_message');	
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}	
						$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_rejected_escrow_creation_request_user_activity_log_displayed_message');	
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
					} else if($p_type== 'hourly') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] =='Y')){
							if($project_data['gender'] == 'M'){
								
								if($project_data['is_authorized_physical_person'] =='Y'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}else{	
								
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}								
							} else {
								if($project_data['is_authorized_physical_person'] =='Y'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_rejected_escrow_creation_request_user_activity_log_displayed_message');	
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_rejected_escrow_creation_request_user_activity_log_displayed_message');	
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}	
						$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_rejected_escrow_creation_request_user_activity_log_displayed_message');	
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
					} else if($p_type== 'fulltime') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] =='Y')){
							if($project_data['gender'] == 'M'){
								
								if($project_data['is_authorized_physical_person'] =='Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}else{	
								
								
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}								
							} else {
								if($project_data['is_authorized_physical_person'] =='Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_female_rejected_escrow_creation_request_user_activity_log_displayed_message');
								}else{	
								
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_rejected_escrow_creation_request_user_activity_log_displayed_message');	
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_rejected_escrow_creation_request_user_activity_log_displayed_message');	
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}	
						$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_rejected_escrow_creation_request_user_activity_log_displayed_message');	
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
					}

					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					
					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner
					
					$notification_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}"),array($sp_name,$sp_profile_url_link),$notification_message);
	
					echo json_encode(['status' => 200,'project_id'=>$project_id,'sp_id'=>Cryptor::doDecrypt($sp_id),'m_id'=>$last_insert_id,'msg'=>$notification_message,'initial_requested_escrow_view_status'=>$initial_requested_escrow_view_status,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrows_listing_project_data['total'],'sum_escrow_amount'=>str_replace(".00","",number_format( $sum_requested_escrow_amount_project,  2, '.', ' ')),'data'=>$this->load->view('escrow/requested_escrows_section_project_detail',$data, true)]);
					die;
				}
				
			} else {
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
			
		} else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
		
	/**
	* This function is used to make the popup of pay requested escrow confirmation by PO. - Done
	*/
	public function create_requested_escrow_confirmation_popup_body_po(){
		
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()) { 
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_create_requested_escrow_deleted_project')]);
					die;
					
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,ud.user_account_balance,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,ud.user_account_balance,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				$requested_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';
					//$projects_minimum_guaranteed_business_charges = 'fixed_budget_projects_minimum_required_completion_threshold';
					$projects_minimum_guaranteed_business_charges = 'fixed_budget_projects_minimum_guaranteed_service_fees_charges';
					$projects_escrow_charges_percentage_ranges = 'fixed_budget_projects_escrow_charges_percentage_ranges';
					$business_fee_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_requested_escrow_tooltip_message_service_fee_charges_po_view');
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';					
				} else if($project_data['project_type'] == 'hourly'){
					$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';
					$projects_escrow_charges_percentage_ranges = 'hourly_rate_based_projects_escrow_charges_percentage_ranges';
					$business_fee_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_requested_escrow_tooltip_message_service_fee_charges_po_view');
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				} else if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_table_name = 'fulltime_projects_requested_escrows';
					$projects_escrow_charges_percentage_ranges = 'fulltime_projects_escrow_charges_percentage_ranges';
					$requested_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
					$business_fee_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_requested_escrow_tooltip_message_service_fee_charges_employer_view');
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employer_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($po_id),'employee_winner_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($sp_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_create_requested_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'project_owner_id_of_disputed_project'=>Cryptor::doDecrypt($po_id),'sp_winner_id_of_disputed_project'=>Cryptor::doDecrypt($sp_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_create_requested_escrow_on_active_disputed_project');
				}
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_requested_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($requested_escrow_table_name)->count_all_results();
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_requested_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($requested_escrow_table_name)->count_all_results();
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				if($check_requested_escrow_exists == 0){
					
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_create_invalid_requested_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_create_invalid_requested_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}

				$confirmation_modal_title = '';
				if(!empty($requested_escrow_data)){
					if($project_data['project_type'] == 'fulltime'){
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('create_requested_escrow_confirmation_fulltime_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn create_requested_escrow_po  width-auto">'.$this->config->item('create_requested_escrow_confirmation_fulltime_project_modal_confirm_btn_txt').'</button>';
					} else {
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('create_requested_escrow_confirmation_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" disabled style="opacity:0.65" class="btn  blue_btn default_btn create_requested_escrow_po width-auto">'.$this->config->item('create_requested_escrow_confirmation_project_modal_confirm_btn_txt').'</button>';
					}
					
					$bussiness_fee_for_tooltip = '0'." ".CURRENCY;
					if(!empty($projects_minimum_guaranteed_business_charges)) {
						$projects_minimum_guaranteed_business_charges_data = $this->db->get_where($projects_minimum_guaranteed_business_charges, ['po_membership_plan_id' => $project_data['current_membership_plan_id']])->row_array();
					}
					$business_charges = 0;
					if(!empty($projects_minimum_guaranteed_business_charges_data) && $requested_escrow_data['requested_escrow_amount'] <=  $projects_minimum_guaranteed_business_charges_data['upto_minimum_escrowed_amount']){
						
						$business_charges = $projects_minimum_guaranteed_business_charges_data['minimum_guaranteed_business_charge_value'];
						$bussiness_fee_for_tooltip = $projects_minimum_guaranteed_business_charges_data['minimum_guaranteed_business_charge_value'] ." ".CURRENCY;
						
					
					} else {
						if($project_data['project_type'] == 'fulltime'){
							$projects_escrow_charges_percentage_ranges_data = $this->db->get_where($projects_escrow_charges_percentage_ranges, ['employer_membership_plan_id' => $project_data['current_membership_plan_id']])->result_array();
						} else {
							$projects_escrow_charges_percentage_ranges_data = $this->db->get_where($projects_escrow_charges_percentage_ranges, ['po_membership_plan_id' => $project_data['current_membership_plan_id']])->result_array();
						}
						if(!empty($projects_escrow_charges_percentage_ranges_data)){
							foreach($projects_escrow_charges_percentage_ranges_data as $escrow_charge_key => $escrow_charge_value){
								
								if($escrow_charge_value['max_escrowed_amount'] != 'All'){
									
									if($requested_escrow_data['requested_escrow_amount'] >= (int)$escrow_charge_value['min_escrowed_amount'] &&  $requested_escrow_data['requested_escrow_amount'] <= (int)$escrow_charge_value['max_escrowed_amount']){
										
										$bussiness_fee_for_tooltip = $escrow_charge_value['escrow_charge_percentage_value']."%";
										
										$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
										$business_charges = ($escrow_charge_percentage_value/100) * $requested_escrow_data['requested_escrow_amount'];
										break;
									}
								} else {  
								
									if($requested_escrow_data['requested_escrow_amount'] >= (int)$escrow_charge_value['min_escrowed_amount']){
										$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
										$bussiness_fee_for_tooltip = $escrow_charge_value['escrow_charge_percentage_value']."%";
										$business_charges = ($escrow_charge_percentage_value/100) * $requested_escrow_data['requested_escrow_amount'];
										break;
									}
								
								
								}
							
							
							}
						
						}
					
					
					
					}
					$requested_escrow = $requested_escrow_data['requested_escrow_amount'];
					$total = $requested_escrow_data['requested_escrow_amount']+$business_charges;
					
					/* if(!empty($total) && $total > 0 && $total > $project_data['user_account_balance']){
				
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_not_sufficient_balance_validation_project_escrow_form_message')]);
						die;
					}
				 */
					
					$plans_names = $this->config->item('membership_plans_names');
					if($project_data['current_membership_plan_id'] == 1){
						$user_plan_name = $plans_names[$project_data['current_membership_plan_id']];
					}else if($project_data['current_membership_plan_id'] == 4){
						$user_plan_name = $plans_names[$project_data['current_membership_plan_id']];
					}
					if($project_data['project_type'] == 'fulltime'){
						$business_fee_tooltip_msg  = str_replace(array('{employer_membership_subscription_name}','{fulltime_service_fee_charges}'),array($user_plan_name,$bussiness_fee_for_tooltip),$business_fee_tooltip_msg );	
					} else {
						$business_fee_tooltip_msg  = str_replace(array('{po_membership_subscription_name}','{fixed_budget_service_fee_charges}'),array($user_plan_name,$bussiness_fee_for_tooltip),$business_fee_tooltip_msg );	
					}
						
					$business_fee_tooltip_msg = str_replace(".00","",$business_fee_tooltip_msg);	
					
					if($project_data['project_type'] == 'hourly') {
						$requested_hourly_rate = $requested_escrow_data['sp_requested_escrow_creation_hourly_rate'];
						$requested_number_of_hours = $requested_escrow_data['sp_requested_escrow_creation_number_of_hours'];
						$confirmation_modal_body.= '<form class="cancel_requested_escrow_form" name="cancel_requested_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"><div class="radio_modal_separator labelOnly create_requested_escrow_project"><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_hourly_rate_based_project_modal_requested_escrow_txt_po_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($requested_escrow,  2, '.', ' ')).' '. CURRENCY . '</span></label><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_hourly_rate_based_project_modal_requested_hourly_rate_txt_po_view').'</span><span class="touch_line_break">'.$requested_hourly_rate.' '. CURRENCY. $this->config->item('project_details_page_hourly_rate_based_project_per_hour'). '</span></label><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_hourly_rate_based_project_modal_requested_number_of_hours_txt_po_view').'</span>'.$requested_number_of_hours. '</label><label class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.$business_fee_tooltip_msg.'">'.$this->config->item('create_requested_escrow_confirmation_hourly_rate_based_project_modal_service_fee_txt_po_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($business_charges,  2, '.', ' ')).' '. CURRENCY . '</span></label><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_hourly_rate_based_project_modal_total_requested_escrow_txt_po_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($total,  2, '.', ' ')).' '. CURRENCY .'</span></label></div></div></div>';
					} else if($project_data['project_type'] == 'fixed') {
						$confirmation_modal_body.= '<form class="cancel_requested_escrow_form" name="cancel_requested_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"><div class="radio_modal_separator labelOnly create_requested_escrow_fulltime_project"><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_fixed_budget_project_modal_requested_escrow_txt_po_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($requested_escrow,  2, '.', ' ')).' '. CURRENCY . '</span></label><label class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.$business_fee_tooltip_msg.'">'.$this->config->item('create_requested_escrow_confirmation_fixed_budget_project_modal_service_fee_txt_po_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($business_charges,  2, '.', ' ')).' '. CURRENCY . '</span></label><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_fixed_budget_project_modal_total_requested_escrow_txt_po_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($total,  2, '.', ' ')).' '. CURRENCY .'</span></label></div></div></div>';
					} else if($project_data['project_type'] == 'fulltime') {
						$confirmation_modal_body.= '<form class="cancel_requested_escrow_form" name="cancel_requested_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"><div class="radio_modal_separator labelOnly create_requested_escrow_fulltime_project"><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_fulltime_project_modal_requested_escrow_txt_employer_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($requested_escrow,  2, '.', ' ')).' '. CURRENCY . '</span></label><label class="default_black_bold"><span class="tooltipAuto" data-toggle="tooltip" data-placement="top" data-original-title="'.$business_fee_tooltip_msg.'">'.$this->config->item('create_requested_escrow_confirmation_fulltime_project_modal_service_fee_txt_employer_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($business_charges,  2, '.', ' ')).' '. CURRENCY . '</span></label><label class="default_black_bold"><span>'.$this->config->item('create_requested_escrow_confirmation_fulltime_project_modal_total_requested_escrow_txt_employer_view').'</span><span class="touch_line_break">'.str_replace(".00","",number_format($total,  2, '.', ' ')).' '. CURRENCY .'</span></label></div></div></div>';
					}
					
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox">
					<input type="checkbox" class="receive_notification" id="create_requested_escrow_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$requested_escrow_disclaimer_message.'</span></label></div></div></div>';
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
	* This function is used to pay the requested escrow by SP. - Done
	*/
	public function create_requested_escrow_po(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$tab_type = $this->input->post ('tab_type');
				$section_name = $this->input->post ('section_name');
				$section_id = $this->input->post ('section_id');
				$user = $this->session->userdata ('user');
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_create_requested_escrow_deleted_project')]);
					die;
					
				}
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,ud.user_account_balance,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,ud.current_membership_plan_id')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,ud.user_account_balance,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,ud.current_membership_plan_id')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				$project_type = $project_data['project_type'];
				if($project_data['project_type'] == 'fixed'){
					$requested_escrow_table_name = 'fixed_budget_projects_requested_escrows';
					$projects_minimum_guaranteed_business_charges_table_name = 'fixed_budget_projects_minimum_required_completion_threshold';
					$projects_escrow_charges_percentage_ranges_table_name = 'fixed_budget_projects_escrow_charges_percentage_ranges';
					$projects_active_escrow_table = 'fixed_budget_projects_active_escrows';
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					
				} else if($project_data['project_type'] == 'hourly'){
					$requested_escrow_table_name = 'hourly_rate_based_projects_requested_escrows';
					$projects_escrow_charges_percentage_ranges_table_name = 'hourly_rate_based_projects_escrow_charges_percentage_ranges';
					$projects_active_escrow_table = 'hourly_rate_based_projects_active_escrows';
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				} else if($project_data['project_type'] == 'fulltime'){
					$requested_escrow_table_name = 'fulltime_projects_requested_escrows';
					$projects_escrow_charges_percentage_ranges_table_name = 'fulltime_projects_escrow_charges_percentage_ranges';
					$projects_active_escrow_table = 'fulltime_projects_active_escrows';
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employer_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($po_id),'employee_winner_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($sp_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_create_requested_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'project_owner_id_of_disputed_project'=>Cryptor::doDecrypt($po_id),'sp_winner_id_of_disputed_project'=>Cryptor::doDecrypt($sp_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_create_requested_escrow_on_active_disputed_project');
				}
				
				if($check_active_dispute > 0 ){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
		
				if($project_data['project_type'] == 'fulltime'){
					$check_requested_escrow_exists = $this->db->where(['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->from($requested_escrow_table_name)->count_all_results();
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_requested_escrow_exists = $this->db->where(['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($requested_escrow_table_name)->count_all_results();
					$requested_escrow_data = $this->db->get_where($requested_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				if($check_requested_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_create_invalid_requested_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_create_invalid_requested_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				
				
				
				/* if($requested_milestone_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_create_requested_milestone_message')]);
					die;
				} */
				if(!empty($requested_escrow_data)){
				
					if($project_data['project_type'] == 'fixed'){ 
						$business_charges = $this->Escrow_model->get_po_service_fee_escrow_fixed_project($requested_escrow_data['requested_escrow_amount'],$project_data['current_membership_plan_id']);
					} else if($project_data['project_type'] == 'hourly') {
						$business_charges = $this->Escrow_model->get_po_service_fee_escrow_hourly_project($requested_escrow_data['requested_escrow_amount'],$project_data['current_membership_plan_id']);
					} else if($project_data['project_type'] == 'fulltime') {
						$business_charges = $this->Escrow_model->get_employer_service_fee_charges_escrow_fulltime_project($requested_escrow_data['requested_escrow_amount'],$project_data['current_membership_plan_id']);
					}
					
					$requested_escrow_value = $requested_escrow_data['requested_escrow_amount'];
					$total = $requested_escrow_data['requested_escrow_amount']+$business_charges;
					
					if(!empty($total) && $total > 0 && $total > $project_data['user_account_balance']){
				
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_not_sufficient_balance_validation_project_escrow_form_message')]);
						die;
					}
				
				
					$p_type = $project_data['project_type'];
					
					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					}else{
						$page = 1;
					}

					if($project_data['project_type'] == 'fulltime'){
						$this->db->delete($requested_escrow_table_name, ['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)]); 
					} else {
						$this->db->delete($requested_escrow_table_name, ['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
					}

					##############save data into active escrow table #############
					if($project_data['project_type'] == 'fixed'){ 
						$active_escrow_data['project_id'] = $project_id ;
						$active_escrow_data['project_owner_id'] = Cryptor::doDecrypt($po_id);
						$active_escrow_data['winner_id'] = Cryptor::doDecrypt($sp_id) ;
						$active_escrow_data['escrow_description'] = $requested_escrow_data['requested_escrow_description'] ;
						$active_escrow_data['created_escrow_amount'] = $requested_escrow_value;
						$active_escrow_data['service_fee_charges'] = $business_charges;
						$active_escrow_data['total_escrow_payment_value'] = $total;
						$active_escrow_data['escrow_creation_date'] = date('Y-m-d H:i:s');
						$active_escrow_data['escrow_creation_requested_by_sp'] = 'Y';
						$active_escrow_data['escrow_creation_requested_by_sp_date'] = $requested_escrow_data['escrow_requested_by_sp_date'];
					} else if($project_data['project_type'] == 'hourly') {
						$active_escrow_data['project_id'] = $project_id ;
						$active_escrow_data['project_owner_id'] = Cryptor::doDecrypt($po_id);
						$active_escrow_data['winner_id'] = Cryptor::doDecrypt($sp_id) ;
						$active_escrow_data['escrow_description'] = $requested_escrow_data['requested_escrow_description'] ;
						$active_escrow_data['escrow_considered_number_of_hours'] = $requested_escrow_data['sp_requested_escrow_creation_number_of_hours'] ;
						$active_escrow_data['escrow_considered_hourly_rate'] = $requested_escrow_data['sp_requested_escrow_creation_hourly_rate'] ;
						$active_escrow_data['created_escrow_amount'] = $requested_escrow_value;
						$active_escrow_data['service_fee_charges'] = $business_charges;
						$active_escrow_data['total_escrow_payment_value'] = $total;
						$active_escrow_data['escrow_creation_date'] = date('Y-m-d H:i:s');
						$active_escrow_data['escrow_creation_requested_by_sp'] = 'Y';
						$active_escrow_data['escrow_creation_requested_by_sp_date'] = $requested_escrow_data['escrow_requested_by_sp_date'];
					} else if($project_data['project_type'] == 'fulltime') {
						$active_escrow_data['fulltime_project_id'] = $project_id ;
						$active_escrow_data['employer_id'] = Cryptor::doDecrypt($po_id);
						$active_escrow_data['employee_id'] = Cryptor::doDecrypt($sp_id) ;
						$active_escrow_data['escrow_description'] = $requested_escrow_data['requested_escrow_description'] ;
						$active_escrow_data['created_escrow_amount'] = $requested_escrow_value;
						$active_escrow_data['service_fee_charges'] = $business_charges;
						$active_escrow_data['total_escrow_payment_value'] = $total;
						$active_escrow_data['escrow_creation_date'] = date('Y-m-d H:i:s');
						$active_escrow_data['escrow_creation_requested_by_employee'] = 'Y';
						$active_escrow_data['escrow_creation_requested_by_employee_date'] = $requested_escrow_data['escrow_requested_by_employee_date'];
					}
					$this->db->insert ($projects_active_escrow_table,$active_escrow_data); // insert data into active escrow table
					
					$this->db->set('user_account_balance', 'user_account_balance-'.$total, false);
					$this->db->where('user_id' , $user[0]->user_id);
					$this->db->update('users_details');

					if($project_data['project_type'] == 'fulltime') {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
					} else {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					}
					
					
					if($project_data['project_type'] == 'fulltime') {
					
						$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
							 
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
					
					
					
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} else {
					
						if($p_type=="fixed"){
						$requested_escrows_table = 'fixed_budget_projects_requested_escrows';
						}
						if($p_type == "hourly"){
							$requested_escrows_table = 'hourly_rate_based_projects_requested_escrows';
						}
						$total_record = $this->db->from($requested_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
						
						
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
					
					
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					}
					$data["requested_escrows_data"] = $requested_escrows_listing_project_data['data'];
					$data['requested_escrows_count'] = $requested_escrows_listing_project_data['total'];
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($requested_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					
					$data['generate_pagination_links_escrow'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$data['project_type']= $p_type;
					$data['section_name']= $section_name;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id;
					
					$create_request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view');
					if($p_type == 'fulltime'){
						$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_request_payment_button_txt_employee_view');
					}
					$data['create_request_payment_button']= $create_request_payment_button;
					$multiplication = $this->config->item('project_detail_page_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_requested_escrow_listing_limit') - count($data['requested_escrows_data'])));
					$record_per_page = count($data['requested_escrows_data']) < $this->config->item('project_detail_page_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_requested_escrow_listing_limit') * ($page - 1)) + 1;
					
					################ sum of requested/eschrow_released milestone########
					if($project_data['project_type'] == 'fulltime') {
						$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
						$active_escrow_count_project = get_active_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id),)); // count the active escrow
						$released_escrow_count_project = get_released_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
					} else {
						$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
						$active_escrow_count_project = get_active_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id),)); // count the active escrow
						$released_escrow_count_project = get_released_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
					}
					
					if(($requested_escrow_count_project + $active_escrow_count_project + $released_escrow_count_project + $rejected_requested_escrow_count_project) > 0){
						$initial_requested_escrow_view_status = 1;
					}else{
						$initial_requested_escrow_view_status = 0;
					}
					
					
					######## get the detail of SP ##########
					$requested_escrow_value = str_replace(".00","",number_format($requested_escrow_value,  2, '.', ' '));
					$requested_escrow_value = $requested_escrow_value." ".CURRENCY;
					
				
					$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
					
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
				
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					
					
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($p_type == 'fixed'){ 
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_requested_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_created_requested_escrow_user_activity_log_displayed_message');
								}
							}else{
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_requested_escrow_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_created_requested_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_created_requested_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_created_requested_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);

						$po_msg = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_po_when_created_requested_escrow'); 
					} else if($p_type == 'hourly') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_created_requested_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_created_requested_escrow_user_activity_log_displayed_message');
								}
							}else{
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_created_requested_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_created_requested_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_created_requested_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_created_requested_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);

						$po_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_po_created_requested_escrow'); 
					} else if($p_type == 'fulltime') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_created_requested_escrow_user_activity_log_displayed_message');	
								}else{	
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_created_requested_escrow_user_activity_log_displayed_message');
								}
							}else{
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_female_created_requested_escrow_user_activity_log_displayed_message');	
								}else{
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_created_requested_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_created_requested_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_created_requested_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fulltime_project_requested_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$requested_escrow_value,$project_url_link,$project_title),$po_activity_log_message);

						$po_msg = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employer_when_employer_created_requested_escrow'); 
					}
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner

					echo json_encode(['status' => 200,'po_msg'=>$po_msg,'sp_id'=>Cryptor::doDecrypt($sp_id),'initial_requested_escrow_view_status'=>$initial_requested_escrow_view_status,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrows_listing_project_data['total'],'sum_escrow_amount'=>str_replace(".00","",number_format( $sum_requested_escrow_amount_project,  2, '.', ' ')),'data'=>$this->load->view('escrow/requested_escrows_section_project_detail',$data, true)]);
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
	* This function is used to calculate business charges when PO created escrow . - Done
	*/
	public function calculate_escrow_form_service_fee_charges_po(){
	
		if($this->input->is_ajax_request ()){
			
			$section_id = $this->input->post ('section_id');
			$section_name = $this->input->post ('section_name');
			$project_id = $this->input->post ('project_id');
			$escrow_amount = str_replace(" ","",$this->input->post ('escrow_amount'));
			
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_deleted_project')]);
					die;
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				
				if($project_data['project_type'] == 'fixed'){
					$projects_minimum_guaranteed_business_charges_table_name = 'fixed_budget_projects_minimum_required_completion_threshold';
					$projects_escrow_charges_percentage_ranges_table_name = 'fixed_budget_projects_escrow_charges_percentage_ranges';
				} else if($project_data['project_type'] == 'hourly'){
					$projects_minimum_guaranteed_business_charges_table_name = 'hourly_rate_based_projects_escrow_charges_percentage_ranges';
				} else if($project_data['project_type'] == 'fulltime'){
					$projects_minimum_guaranteed_business_charges_table_name = 'fulltime_projects_escrow_charges_percentage_ranges';
				}
				if($project_data['project_type'] == 'fixed'){
					$business_service_fee = $this->Escrow_model->get_po_service_fee_escrow_fixed_project($escrow_amount,$project_data['current_membership_plan_id']);
					
					$total_escrow_value = $escrow_amount +$business_service_fee;
					$business_fee_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_dynamic_tooltip_message_business_service_fee_po_view');
					//$projects_minimum_guaranteed_business_charges = 'fixed_budget_projects_minimum_required_completion_threshold';
					$projects_minimum_guaranteed_business_charges = 'fixed_budget_projects_minimum_guaranteed_service_fees_charges';
					$projects_escrow_charges_percentage_ranges = 'fixed_budget_projects_escrow_charges_percentage_ranges';
				} else if($project_data['project_type'] == 'hourly') {
					$business_service_fee = $this->Escrow_model->get_po_service_fee_escrow_hourly_project($escrow_amount,$project_data['current_membership_plan_id']);
					if($business_service_fee == 0){
						$business_service_fee = '';
					}	
					$total_escrow_value = $escrow_amount +$business_service_fee;
					$business_fee_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_dynamic_tooltip_message_business_service_fee_po_view');
					$projects_minimum_guaranteed_business_charges = '';
					$projects_escrow_charges_percentage_ranges = 'hourly_rate_based_projects_escrow_charges_percentage_ranges';
				} else if($project_data['project_type'] == 'fulltime') {
					$business_service_fee = $this->Escrow_model->get_employer_service_fee_charges_escrow_fulltime_project($escrow_amount,$project_data['current_membership_plan_id']);
					$total_escrow_value = $escrow_amount +$business_service_fee;
					$projects_minimum_guaranteed_business_charges = '';
					$projects_escrow_charges_percentage_ranges = 'fulltime_projects_escrow_charges_percentage_ranges';
					$business_fee_tooltip_msg = $this->config->item('project_details_page_fulltime_project_create_escrow_form_dynamic_tooltip_message_service_fee_employer_view');
				}
				$bussiness_fee_for_tooltip = '0'." ".CURRENCY;
				if(!empty($projects_minimum_guaranteed_business_charges)) {
					$projects_minimum_guaranteed_business_charges_data = $this->db->get_where($projects_minimum_guaranteed_business_charges, ['po_membership_plan_id' => $project_data['current_membership_plan_id']])->row_array();
				}
				
				if(!empty($projects_minimum_guaranteed_business_charges_data) && $escrow_amount <=  $projects_minimum_guaranteed_business_charges_data['upto_minimum_escrowed_amount']){
					
					$business_charges = $projects_minimum_guaranteed_business_charges_data['minimum_guaranteed_business_charge_value'];
					$bussiness_fee_for_tooltip = $projects_minimum_guaranteed_business_charges_data['minimum_guaranteed_business_charge_value'] ." ".CURRENCY;
					
				
				} else {
					if($project_data['project_type'] == 'fulltime') {
						$projects_escrow_charges_percentage_ranges_data = $this->db->get_where($projects_escrow_charges_percentage_ranges, ['employer_membership_plan_id' => $project_data['current_membership_plan_id']])->result_array();
					} else {
						$projects_escrow_charges_percentage_ranges_data = $this->db->get_where($projects_escrow_charges_percentage_ranges, ['po_membership_plan_id' => $project_data['current_membership_plan_id']])->result_array();
					}
					if(!empty($projects_escrow_charges_percentage_ranges_data)){
						foreach($projects_escrow_charges_percentage_ranges_data as $escrow_charge_key => $escrow_charge_value){
							
							if($escrow_charge_value['max_escrowed_amount'] != 'All'){
								
								if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount'] &&  $escrow_amount <= (int)$escrow_charge_value['max_escrowed_amount']){
									
									$bussiness_fee_for_tooltip = $escrow_charge_value['escrow_charge_percentage_value']."%";
									
									$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
									$business_charges = ($escrow_charge_percentage_value/100) * $escrow_amount;
									break;
								}
							} else {
							
								if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount']){
									$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
									$bussiness_fee_for_tooltip = $escrow_charge_value['escrow_charge_percentage_value']."%";
									$business_charges = ($escrow_charge_percentage_value/100) * $escrow_amount;
									break;
								}
							}
						
						
						}
					
					}
				}
				
				$bussiness_fee_for_tooltip = str_replace(".00","",$bussiness_fee_for_tooltip);
				
				$user_plan_name = '';
				$plans_names = $this->config->item('membership_plans_names');
				if(!empty($plans_names) && array_key_exists($project_data['current_membership_plan_id'], $plans_names)) {
					$user_plan_name = $plans_names[$project_data['current_membership_plan_id']];
				}
				
				if($project_data['project_type'] == 'fixed'){
					
					$business_fee_tooltip_msg  = str_replace(array('{po_membership_plan_name}','{fixed_budget_business_charges}'),array($user_plan_name,$bussiness_fee_for_tooltip),$business_fee_tooltip_msg );
					
				} else if($project_data['project_type'] == 'hourly') {
					$business_fee_tooltip_msg  = str_replace(array('{po_membership_plan_name}','{hourly_rate_based_business_charges}'),array($user_plan_name,$bussiness_fee_for_tooltip),$business_fee_tooltip_msg );
				} else if($project_data['project_type'] == 'fulltime') {
					$business_fee_tooltip_msg  = str_replace(array('{employer_membership_plan_name}','{fulltime_service_fees_charges}'),array($user_plan_name,$bussiness_fee_for_tooltip),$business_fee_tooltip_msg );
				}
				
				/* echo json_encode(['status' => 200,'business_fee_tooltip_msg'=>$business_fee_tooltip_msg,'total_escrow_value'=>str_replace(".00","",number_format( floatval($total_escrow_value),  2, '.', ' ')),'business_charges'=>str_replace(".00","",number_format( $business_service_fee,  2, '.', ' '))]);die; */
				$response['status'] = 200;
				$response['business_fee_tooltip_msg'] = $business_fee_tooltip_msg;
				$response['total_escrow_value'] = str_replace(".00","",number_format( floatval($total_escrow_value),  2, '.', ' '));
				$response['business_charges'] = str_replace(".00","",number_format( floatval($business_service_fee),  2, '.', ' '));
				echo json_encode($response);die;
			}else{
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	} 
	
	/**
	* This function is used to calculate business charges when PO release partial escrow . - Done
	*/
	public function calculate_partial_released_escrow_service_fees_charges_po(){
	
		if($this->input->is_ajax_request ()){
			
			$section_id = $this->input->post ('section_id');
			$section_name = $this->input->post ('section_name');
			$project_id = $this->input->post ('project_id');
			$escrow_amount = $this->input->post ('escrow_amount');
			$id = $this->input->post ('row_id');
			$ea = $this->input->post ('ea');
			$sf = $this->input->post ('sf');
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_place_bid_deleted_project')]);
					die;
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				if($project_data['project_type'] == 'fixed'){
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
				}
				/* if($project_data['project_type'] == 'fulltime'){ 
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
				}
			
				if($check_active_escrow_exists == 0){
					if($project_status_table_array['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_invalid_active_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_po_view_invalid_active_milestone_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				if($project_data['project_type'] == 'fulltime'){ 
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
					$active_escrow_data['project_owner_id'] = $active_escrow_data['employer_id'];
				} else {
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
			
				if($active_escrow_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_release_milestone_message')]);
					die;
				} */
				
				
				//$partial_escrow_data = $this->Escrow_model->service_fee_calculation_partial_release_escrow($active_escrow_data['created_escrow_amount'],$active_escrow_data['service_fee_charges'],$escrow_amount);
				
				
				
				$escrow_amount = str_replace(" ", "", $escrow_amount);
				$partial_escrow_data = $this->Escrow_model->service_fee_calculation_partial_release_escrow($ea,$sf,$escrow_amount);
					$service_fees_status = '1';
				if( $escrow_amount > $ea){
					$service_fees_status = '0';
				}	
				
				
				echo json_encode(['status' => 200,'service_fees_status'=>$service_fees_status,'business_charges'=>str_replace(".00","",number_format( $partial_escrow_data['partial_buisness_fee'],  2, '.', ' '))]);die;
				
				
			}else{
				
				//echo json_encode(['status' => 'FAILED','location'=>''), true)]);
				//die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	} 
	
	
	/**
	* This function is used to load the create escrow form for PO . - Done
	*/
	
	public function load_escrow_form_po(){
	
		if($this->input->is_ajax_request ()){
			$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
			$sp_id = Cryptor::doDecrypt($this->input->post ('sp_id'));
			
			$project_id = $this->input->post ('project_id');
			$section_id = $this->input->post ('section_id');
			$section_name = $this->input->post ('section_name');
			$initial_agreed_hourly_rate = $this->input->post ('initial_agreed_hourly_rate');
			$user_id = $this->input->post('user_id');
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_create_escrow_deleted_project')]);
					die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,ud.current_membership_plan_id')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,ud.current_membership_plan_id')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					
				} else if($project_data['project_type'] == 'fulltime'){
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$completed_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>$sp_id,'employer_id_of_disputed_fulltime_project'=>$po_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_create_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>$sp_id,'project_owner_id_of_disputed_project'=>$po_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_create_escrow_on_active_disputed_project');
				}
				
				
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				
				if($project_data['project_type'] != 'fulltime'){
				
					$inprogress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['winner_id'=>Cryptor::doDecrypt($this->input->post ('sp_id')),'project_id'=>$project_id])->row_array();
					
					$incomplete_bid_data = $this->db->get_where($incomplete_bid_table_name, ['winner_id'=>Cryptor::doDecrypt($this->input->post ('sp_id')),'project_id'=>$project_id])->row_array();
					
					
					$completed_bid_data = $this->db->get_where($completed_bid_table_name, ['winner_id'=>Cryptor::doDecrypt($this->input->post ('sp_id')),'project_id'=>$project_id])->row_array();
				}else{
					
					$inprogress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['employee_id'=>Cryptor::doDecrypt($this->input->post ('sp_id')),'fulltime_project_id'=>$project_id])->row_array();
					
					
					
					$completed_bid_data = $this->db->get_where($completed_bid_table_name, ['employee_id'=>Cryptor::doDecrypt($this->input->post ('sp_id')),'fulltime_project_id'=>$project_id])->row_array();
					
					
				}
				
				if(!empty($inprogress_bid_data)){
					$bid_id = $inprogress_bid_data['id'];
					$bid_table_name = $inprogress_bid_table_name;
				}
				if(!empty($completed_bid_data)){
					$bid_id = $completed_bid_data['id'];
					$bid_table_name = $completed_bid_table_name;
				}
				if(!empty($incomplete_bid_data)){
					$bid_id = $incomplete_bid_data['id'];
					$bid_table_name = $incomplete_bid_table_name;
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$this->db->select('bd.*,users.account_type,users.is_authorized_physical_person,users.first_name,users.last_name,users.company_name,users.profile_name');
					$this->db->from($bid_table_name.' as bd');
					$this->db->join('users', 'users.user_id = bd.employee_id', 'left');
					$this->db->where('bd.id',$bid_id);
					$bid_result = $this->db->get();
					$bid_data = $bid_result->row_array();
					$bid_data['winner_id'] = $bid_data['employee_id'];
					$bid_data['project_owner_id'] = $bid_data['employer_id'];
					
					
				} else {
					$this->db->select('bd.*,users.account_type,users.is_authorized_physical_person,users.first_name,users.last_name,users.company_name,users.profile_name');
					$this->db->from($bid_table_name.' as bd');
					$this->db->join('users', 'users.user_id = bd.winner_id', 'left');
					$this->db->where('bd.id',$bid_id);
					$bid_result = $this->db->get();
					$bid_data = $bid_result->row_array();
				}
				
				$name = (($bid_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($bid_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $bid_data['is_authorized_physical_person'] == 'Y')) ? $bid_data['first_name'] . ' ' . $bid_data['last_name'] : $bid_data['company_name'];
				
				$data['service_provider_name'] = $name;
				$data['project_id'] = $project_id;
				$data['project_type'] = $project_data['project_type'];
				$data['po_id'] =  Cryptor::doEncrypt($bid_data['project_owner_id']);
				$data['sp_id'] = Cryptor::doEncrypt($bid_data['winner_id']);
				$data['section_name'] = $section_name;
				//$data['bid_id'] = $bid_data['id'];
				$data['section_id'] = $section_id;
				if($project_data['project_type'] == 'hourly') {
					$data['initial_project_agreed_hourly_rate'] = $bid_data['initial_project_agreed_hourly_rate'];
				}
				echo json_encode(['status' => 200,'data'=>$this->load->view('escrow/escrow_form_po_project_detail',$data, true)]);
				die;

			} else {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
				die;
			}
		} else{
			show_custom_404_page(); //show custom 404 page
		}
	} 
	
	
	/*
	* This function is used to save escrow into database which is created by PO - Done
	*/
	public function save_escrow_po(){
		if($this->input->is_ajax_request ()){
			$project_id = $this->input->post ('project_id');
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
				$post_data = $this->input->post ();
				$user = $this->session->userdata('user');
				
				$po_id = Cryptor::doDecrypt($post_data['po_id']);
				$sp_id = Cryptor::doDecrypt($post_data['sp_id']);
				$section_id = $post_data['section_id'];
				if($po_id != $user[0]->user_id){
						
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_create_escrow_deleted_project')]);
					die;
				}
				$section_name = $post_data['section_name'];
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,ud.current_membership_plan_id')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,ud.current_membership_plan_id')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}

				
				if($project_data['project_type'] == 'fixed'){
					$inprogress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$projects_minimum_guaranteed_business_charges_table_name = 'fixed_budget_projects_minimum_required_completion_threshold';
					$projects_escrow_charges_percentage_ranges_table_name = 'fixed_budget_projects_escrow_charges_percentage_ranges';
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				} else if($project_data['project_type'] == 'hourly'){
					$inprogress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				} else if($project_data['project_type'] == 'fulltime'){
					$inprogress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>$sp_id,'employer_id_of_disputed_fulltime_project'=>$po_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_create_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>$sp_id,'project_owner_id_of_disputed_project'=>$po_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_create_escrow_on_active_disputed_project');
				}
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_in_progress_bid = $this->db->where(['fulltime_project_id' => $project_id,'employee_id'=>$sp_id,'employer_id'=>$po_id])->from($inprogress_bid_table_name)->count_all_results();
				} else {
					$check_in_progress_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$sp_id,'project_owner_id'=>$po_id])->from($inprogress_bid_table_name)->count_all_results();
				}
				if($check_in_progress_bid > 0){
					if($project_data['project_type'] == 'fulltime'){ 
						$in_progress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$sp_id,'employer_id'=>$po_id])->row_array();
					} else {
						$in_progress_bid_data = $this->db->get_where($inprogress_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id,'project_owner_id'=>$po_id])->row_array();
					}
				}
				if(!empty($completed_bid_table_name)) {
					$check_completed_bid = $this->db->where(['project_id' => $project_id,'winner_id'=>$sp_id,'project_owner_id'=>$po_id])->from($completed_bid_table_name)->count_all_results();
				}
				if(!empty($check_completed_bid) && $check_completed_bid > 0){
					$completed_bid_data = $this->db->get_where($completed_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id,'project_owner_id'=>$po_id])->row_array();
					if($completed_bid_data['project_owner_id'] != $user[0]->user_id){
						$error_message = $this->config->item('project_details_page_validation_create_milestone_message');
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);die;
					}
				
				}
				
				$validation_data_array = $this->Escrow_model->create_escrow_validation_po($post_data,$project_data,$post_data['section_id'],$post_data['section_name']);
				if($validation_data_array['status'] == 'SUCCESS'){
					if($project_data['project_type'] == 'fixed'){
					$post_data['escrow_amount'] = str_replace(" ","",$post_data['escrow_amount']);
					$business_charges = $this->Escrow_model->get_po_service_fee_escrow_fixed_project($post_data['escrow_amount'],$project_data['current_membership_plan_id']);
					 $total_escrow_value = $escrow_amount +$business_charges;
					 $active_escrow_table_name = 'fixed_budget_projects_active_escrows';	
					 $total_escrow_value = $post_data['escrow_amount']+$business_charges;
					 $active_escrow_data['project_id'] = $project_id;
					 $active_escrow_data['project_owner_id'] = $po_id;
					 $active_escrow_data['winner_id']= $sp_id ;
					 $active_escrow_data['escrow_description']= trim($post_data['escrow_description']);
					 $active_escrow_data['created_escrow_amount']=  $post_data['escrow_amount'];
					 $active_escrow_data['service_fee_charges']= $business_charges;
					 $active_escrow_data['total_escrow_payment_value']= $total_escrow_value;
					 $active_escrow_data['escrow_creation_date']= date('Y-m-d H:i:s');
					 $notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_po_when_po_created_escrow');
					 
					} else if($project_data['project_type'] == 'hourly') {
						
						$post_data['escrow_amount'] = str_replace(" ","",$post_data['escrow_amount']);
						$business_charges = $this->Escrow_model->get_po_service_fee_escrow_hourly_project($post_data['escrow_amount'],$project_data['current_membership_plan_id']);
						$total_escrow_value = $post_data['escrow_amount'] +$business_charges;

						$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';	
						$total_escrow_value = $post_data['escrow_amount']+$business_charges;
						$active_escrow_data['project_id'] = $project_id;
						$active_escrow_data['project_owner_id'] = $po_id;
						$active_escrow_data['winner_id']= $sp_id ;
						$active_escrow_data['escrow_description']= trim($post_data['escrow_description']);
						$active_escrow_data['escrow_considered_number_of_hours']= str_replace(" ","",$post_data['escrow_request_no_of_hours']);
						$active_escrow_data['escrow_considered_hourly_rate']= str_replace(" ","",$post_data['escrow_request_hourly_rate']);
						$active_escrow_data['created_escrow_amount']=  str_replace(" ","",$post_data['escrow_amount']);
						$active_escrow_data['service_fee_charges']= $business_charges;
						$active_escrow_data['total_escrow_payment_value']= $total_escrow_value;
						$active_escrow_data['escrow_creation_date']= date('Y-m-d H:i:s');
						$notification_message = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_po_created_escrow');
					} else if($project_data['project_type'] == 'fulltime') {
					
						$post_data['escrow_amount'] = str_replace(" ","",$post_data['escrow_amount']);
						$business_charges = $this->Escrow_model->get_employer_service_fee_charges_escrow_fulltime_project($post_data['escrow_amount'],$project_data['current_membership_plan_id']);
						$total_escrow_value = $post_data['escrow_amount'] +$business_charges;

						$active_escrow_table_name = 'fulltime_projects_active_escrows';	
						$total_escrow_value = $post_data['escrow_amount']+$business_charges;
						$active_escrow_data['fulltime_project_id'] = $project_id;
						$active_escrow_data['employer_id'] = $po_id;
						$active_escrow_data['employee_id']= $sp_id ;
						$active_escrow_data['escrow_description']= trim($post_data['escrow_description']);
						$active_escrow_data['created_escrow_amount']= $post_data['escrow_amount'];
						$active_escrow_data['service_fee_charges']= $business_charges;
						$active_escrow_data['total_escrow_payment_value']= $total_escrow_value;
						$active_escrow_data['escrow_creation_date']= date('Y-m-d H:i:s');
						$notification_message = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employer_when_employer_created_escrow');
					}
					$this->db->insert ($active_escrow_table_name, $active_escrow_data);
					$last_insert_id = $this->db->insert_id();
					$get_escrow_data = $this->db // count the number of record in projects_draft table
					->select ('am.*')
					->from ($active_escrow_table_name. ' as am')
					->where('id',$last_insert_id)
					->get ()->row_array ();
					
					#######update the account balance of po when crated the escrow(deduct account balance)
					
					$this->db->set('user_account_balance', 'user_account_balance-'.$total_escrow_value, false);
					$this->db->where('user_id' , $user[0]->user_id);
					$this->db->update('users_details');
					
					if($project_data['project_type'] == 'fulltime') { 
						$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_po($project_data['project_type'],array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
					} else {
						$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_po($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
					}
					
					######## Initiate paging again when new escrow is created###start
					$bid_id = $in_progress_bid_data['id'];
					if($project_data['project_type'] == 'fulltime') {
						$active_escrow_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($project_data['project_type'],array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$start, $this->config->item('project_detail_page_active_escrow_listing_limit'));
					} else {
						$active_escrow_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$start, $this->config->item('project_detail_page_active_escrow_listing_limit'));
					}
					$active_escrow_data = $active_escrow_listing_project_data['data'];
					$active_escrow_count = $active_escrow_listing_project_data['total'];
					
					$paginations = generate_pagination_links($active_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_active_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name]);
					
					/* $generate_pagination_links_escrow = $this->Escrow_model->generate_pagination_links_escrow($active_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'bid_id'=>$bid_id,'tab_type'=>'requested_milestone','section_name'=>$section_name,'project_type'=>$project_data['project_type'] )); */
					
					$rec_per_page = ($active_escrow_count > $this->config->item('project_detail_page_active_escrow_listing_limit')) ? $this->config->item('project_detail_page_active_escrow_listing_limit') : $active_escrow_count;
					######## Initiate paging again when new escrow is created###end
					
					######## get the detail of SP ##########
					
					$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', $sp_id)
					->get()->row_array();
					
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					
					
					
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					$escrow_value =  str_replace(".00","",number_format($post_data['escrow_amount'],  2, '.', ' '))." ". CURRENCY;
					
					############# log message for create escrow request ####
					if($project_data['project_type'] == 'fixed') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								
								if( $project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_created_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_created_escrow_user_activity_log_displayed_message');
								}
								
							} else {
								if( $project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_created_escrow_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_created_escrow_user_activity_log_displayed_message');
								}
								
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							
						} else {
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_created_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							
						}
						$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_created_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
					} else if($project_data['project_type'] == 'hourly') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								
								if( $project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_created_escrow_user_activity_log_displayed_message');
								}else{	
								
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_created_escrow_user_activity_log_displayed_message');
								}
							} else {
								if( $project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_created_escrow_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_created_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_created_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_created_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$escrow_value,$project_url_link,$project_title),$po_activity_log_message);

					} else if($project_data['project_type'] == 'fulltime') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								
								
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_created_escrow_user_activity_log_displayed_message');
								}else{
								
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_created_escrow_user_activity_log_displayed_message');
								}
							} else {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_female_created_escrow_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_created_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_created_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_created_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$escrow_value,$project_url_link,$project_title),$po_activity_log_message);
					}
					user_display_log($sp_activity_log_message,$sp_id); // activity log message for service provider
					user_display_log($po_activity_log_message,$po_id); // activity log message for project owner
					
					echo json_encode(['status' => 200,'sp_id'=>$sp_id,'msg'=>$notification_message,'location'=>'','data'=>$this->load->view('active_escrows_row_detail_project_detail',array('section_name'=>$section_name,'active_escrow_value'=>$get_escrow_data,'project_type'=>$project_data['project_type'],'project_id'=>$project_data['project_id']), true),'sum_escrow_amount'=>str_replace(".00","",number_format($sum_active_escrow_amount_project,  2, '.', ' ')),'paging_data'=>$this->load->view('escrow_section_paging_project_detail',array('escrow_count'=>$requested_escrow_count,'generate_pagination_links_escrow'=>$paginations['links'],'escrow_paging_limit'=>$this->config->item('project_detail_page_active_escrow_listing_limit')), true)]);
					die;
					
				} else {
					echo json_encode ($validation_data_array);
					die;
				}
			}
		} else {
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to make the popup of partial release escrow confirmation by PO. - Done
	*/
	public function partial_release_escrow_confirmation_popup_body_po(){
		
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				$sf = $this->input->post ('sf');
				$ea = $this->input->post ('ea');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_release_escrow_deleted_project')]);
					die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				$partial_release_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				/* if($project_data['project_type'] == 'fixed'){
					$business_service_fee_input_label = $this->config->item('fixed_budget_project_partial_release_escrow_confirmation_modal_business_service_fee_txt');
				} */
				
				if($project_data['project_type'] == 'fixed'){
					
					$business_service_fee_input_label = $this->config->item('fixed_budget_project_partial_release_escrow_confirmation_modal_business_service_fee_txt');
					
					$description_input_label = $this->config->item('fixed_budget_project_partial_release_escrow_confirmation_modal_description_txt');
					$amount_input_label = $this->config->item('fixed_budget_project_partial_release_escrow_confirmation_modal_amount_tobe_released_txt');
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
					$description_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_partial_release_escrow_form_tooltip_message_description_po_view');	
					$amount_tooltip_msg = $this->config->item('project_details_page_fixed_budget_project_partial_release_escrow_form_tooltip_message_amount_po_view');
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
					
					$business_service_fee_input_label = $this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_business_service_fee_txt');
					
					$description_input_label = $this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_description_txt');
					
					$amount_input_label = $this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_total_available_escrow_amount_tobe_released_txt');
					
					$hourly_rate_label = $this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_hourly_rate_tobe_released_txt');
					
					$number_of_hours_label = $this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_total_available_number_of_hours_tobe_released_txt');
					
					$description_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_tooltip_message_description_po_view');	
					$amount_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_tooltip_message_amount_po_view');

					$hourly_rate_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_tooltip_message_hourly_rate_po_view');
					
					$number_of_hours_tooltip_msg = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_tooltip_message_number_of_hours_po_view');
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					
				} else if($project_data['project_type'] == 'fulltime'){
					$active_escrow_table_name = 'fulltime_projects_active_escrows';

					$description_input_label = $this->config->item('fulltime_project_partial_release_escrow_confirmation_modal_description_txt');
					$amount_input_label = $this->config->item('fulltime_project_partial_release_escrow_confirmation_modal_amount_tobe_released_txt');
					
					$description_tooltip_msg = $this->config->item('project_details_page_fulltime_project_partial_release_escrow_form_tooltip_message_description_employer_view');	
					$amount_tooltip_msg = $this->config->item('project_details_page_fulltime_project_partial_release_escrow_form_tooltip_message_amount_employer_view');
					$business_service_fee_input_label = $this->config->item('fulltime_project_partial_release_escrow_confirmation_modal_business_service_fee_txt');
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($sp_id),'employer_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($po_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_release_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>Cryptor::doDecrypt($sp_id),'project_owner_id_of_disputed_project'=>Cryptor::doDecrypt($po_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_release_escrow_on_active_disputed_project');
				}
				
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				
				
				if($project_data['project_type'] == 'fulltime'){
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_invalid_active_escrow_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_po_view_invalid_active_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}	
				$confirmation_modal_title = '';
				if(!empty($active_escrow_data)){
				
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn partial_release_escrow_po width-auto default_btn_padding">'.$this->config->item('partial_release_escrow_confirmation_fulltime_project_modal_confirm_btn_txt').'</button>';
					} else {
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" disabled style="opacity:0.65" class="btn partial_release_escrow_po blue_btn default_btn width-auto">'.$this->config->item('partial_release_escrow_confirmation_project_modal_confirm_btn_txt').'</button>';
					}
					
					$partial_release_escrow_form = '<div class="row"><div class="col-md-12"><div class="popup_body_semibold_title topLabel">';
					if($project_data['project_type'] == 'hourly') {
						$partial_release_escrow_form .= '<span><i class="far fa-clock"></i><label>'.$this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_hourly_rate_txt').'</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['escrow_considered_hourly_rate'],  2, '.', ' ')).' '.CURRENCY.'</small></span>';
						$partial_release_escrow_form .= '<span><i class="fas fa-stopwatch"></i><label>'.$this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_total_available_number_of_hours_txt').'</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['escrow_considered_number_of_hours'],  2, '.', ' ')).'</small></span>';
					}
					
					
					
					$partial_release_escrow_form .= '<span><i class="far fa-credit-card"></i><label>';
					if($project_data['project_type'] =='fixed') {
						$partial_release_escrow_form .=  $this->config->item('fixed_budget_project_partial_release_escrow_confirmation_project_modal_available_escrowed_amount_txt');
					}
					if($project_data['project_type'] == 'hourly') {
						$partial_release_escrow_form .=  $this->config->item('hourly_rate_based_project_partial_release_escrow_confirmation_modal_total_available_escrow_amount_txt');
					}
					if($project_data['project_type'] == 'fulltime') {
						$partial_release_escrow_form .=  $this->config->item('fulltime_project_partial_release_escrow_confirmation_project_modal_available_escrowed_amount_txt');
					}
					
					$partial_release_escrow_form .='</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' ')).' '. CURRENCY . '</small></span></div></div></div>';
					$partial_release_escrow_form .= '<div class="row">
						<div class="col-md-12 col-sm-12 col-12">
							<div class="radio_modal_separator releaseMilestone margin_bottom5">
								<label class="default_black_bold"><span class="rmMR_text">'.$description_input_label.'</span><i class="fa fa-question-circle default_icon_help tooltipAuto" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$description_tooltip_msg.'"></i></label>
								<span><input type="text" class="description-text avoid_space default_input_field" id="partial_escrow_description" name="partial_escrow_description" placeholder="" maxlength="'.$this->config->item('escrow_description_maximum_length_character_limit_escrow_form').'">			
								</span>
								<div class="error_div_sectn clearfix default_error_div_sectn">
									<span class="content-count partial_escrow_description_length_count_message">'.$this->config->item('escrow_description_maximum_length_character_limit_escrow_form')."&nbsp;".$this->config->item('characters_remaining_message').'</span>
									<span id="partial_escrow_description_error" class="error_msg"></span>
								</div>
							</div>
						</div>
					</div>';
					
					$partial_release_escrow_form .= '<div class="releaseMilestone">';
					if($project_data['project_type'] == 'hourly') {
						$escrow_considered_hourly_rate = str_replace(".00","",number_format($active_escrow_data['escrow_considered_hourly_rate'],  2, '.', ' '));
						$partial_release_escrow_form .= '
							<div class="rmMR">
								<span class="default_black_bold" style="display:block;"><span class="label_rmMR_text no_of_hours">'.$number_of_hours_label.'<i class="rmMR_text fa fa-question-circle default_icon_help tooltipAuto" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$number_of_hours_tooltip_msg.'"></i></span><input type="text" data-section-name="'.$section_name.'" data-section-id="'.$section_id.'" data-row-id="'.$id.'" data-project-id="'.$project_id.'" style="cursor: text;" id="partial_escrow_number_of_hours" class="amount-text partial_escrow_number_of_hours default_input_field" name="partial_escrow_number_of_hours" placeholder="" maxlength="'.$this->config->item('escrow_amount_length_character_limit_escrow_form').'" style="text-align:right;" >
									<div class="error_div_sectn clearfix">
										<span id="partial_escrow_number_of_hours_error" class="error_msg"></span>
									</div>
								</span>
							</div>
							<div class="rmMR">
								<span class="default_black_bold" style="display:block;"><span class="label_rmMR_text">'.$hourly_rate_label.'<i class="fa fa-question-circle default_icon_help rmMR_text tooltipAuto" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$hourly_rate_tooltip_msg.'"></i></span><label class="modal_label noh_adjust_width"><input type="text" data-section-name="'.$section_name.'" data-section-id="'.$section_id.'" data-row-id="'.$id.'" data-project-id="'.$project_id.'" style="cursor: text;" id="partial_escrow_hourly_rate" class="amount-text partial_escrow_hourly_rate default_input_field" name="partial_escrow_hourly_rate" placeholder="" value="'.$escrow_considered_hourly_rate.'" style="text-align:right;" readonly ><span class="kcclr" >'.CURRENCY.'</span></label>
									<div class="error_div_sectn clearfix">
										<span id="partial_escrow_hourly_rate_error" class="error_msg"></span>
									</div>
								</span>
							</div>
						';
					}
					
					$partial_release_escrow_form .= '
						<div class="rmMR">
							<span class="default_black_bold" style="display:block;"><span class="label_rmMR_text">'.$amount_input_label.'<i class="fa fa-question-circle default_icon_help tooltipAuto" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$amount_tooltip_msg.'"></i></span><label class="modal_label inprogress_amountOnly"><input type="text" data-section-name="'.$section_name.'" data-section-id="'.$section_id.'" data-row-id="'.$id.'" data-project-id="'.$project_id.'" data-ea-id="'.$ea.'" data-sf-id="'.$sf.'" style="cursor: text;" id="partial_escrow_amount" class="amount-text partial_escrow_amount default_input_field" name="partial_escrow_amount" placeholder="" maxlength="'.$this->config->item('escrow_amount_length_character_limit_escrow_form').'" style="text-align:right;" '.($project_data['project_type'] == 'hourly' ? 'readonly' : '').'><span class="kcclr" >'.CURRENCY.'</span></label>
							</span>
							<div class="error_div_sectn clearfix">
								<span id="partial_escrow_amount_error" class="error_msg"></span>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="rmMR">
							<span class="default_black_bold" style="display:block;"><span class="label_rmMR_text">'.$business_service_fee_input_label.'</span><label class="modal_label inprogress_amountOnly partial_fee_adjust_width"><input type="text"  id="partial_escrow_bussiness_service_fee" class="amount-text default_input_field"  placeholder=""  readonly style="text-align:right;"><span class="kcclr">'.CURRENCY.'</span></label>
								<div class="error_div_sectn clearfix">
									<span class="error_msg"></span>
								</div>
							</span>
						</div>
					</div>';
					$confirmation_modal_body.= '<form id="partial_release_escrow_form" name="partial_release_escrow_form">'.$partial_release_escrow_form.'<input type="hidden" value="'.$id.'"  name="row_id"><input name="project_id" type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id" name="po_id"><input type="hidden" name="sp_id" id="sp_id" value="'.$sp_id.'"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator mt-0"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="partial_release_escrow_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$partial_release_escrow_disclaimer_message.'</span></label></div></div></div>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
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
	* This function is used to release the partial  escrow by PO. - Done
	*/
	public function partial_release_escrow_po(){
		if($this->input->is_ajax_request ()){
			$post_data = $this->input->post ();
			$project_id = $post_data['project_id'];
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
				$user = $this->session->userdata('user');
				
				$po_id = Cryptor::doDecrypt($post_data['po_id']);
				$sp_id = Cryptor::doDecrypt($post_data['sp_id']);
				$section_name = $post_data['section_name'];
				$section_id = $post_data['section_id'];
				$id = $post_data['row_id'];
				
				if($po_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}

				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				if($project_data['project_type'] == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
					$released_escrow_table_name = 'fixed_budget_projects_released_escrows';
					$in_progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$in_complete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$completed_project_table = 'fixed_budget_projects_completed';
					$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';

				} else if($project_data['project_type'] == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
					$released_escrow_table_name = 'hourly_rate_based_projects_released_escrows';
					$in_progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$completed_project_table = 'hourly_rate_based_projects_completed';
					$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
					$in_complete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';

				} else if($project_data['project_type'] == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
					$released_escrow_table_name = 'fulltime_projects_released_escrows';
					$in_progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>$sp_id,'employer_id_of_disputed_fulltime_project'=>$po_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_release_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>$sp_id,'project_owner_id_of_disputed_project'=>$po_id])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_release_escrow_on_active_disputed_project');
				}
				
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				
				if($project_data['project_type'] == 'fulltime'){
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				
				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_invalid_active_escrow_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_po_view_invalid_active_milestone_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				
				
				/* if($active_milestone_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_release_milestone_message')]);
					die;
				}
				 */
				
				
				$validation_data_array = $this->Escrow_model->partial_release_escrow_validation_po($post_data,$project_data,$active_escrow_data);
				if($validation_data_array['status'] == 'SUCCESS'){
					$vat_percentage = $this->config->item('vat_percentage');
					$is_full_released = 0;

					if($project_data['project_type'] == 'fixed') {
						$reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					} else if($project_data['project_type'] == 'hourly') {
						$reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					} else if($project_data['project_type'] == 'fulltime') {
						$reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}

					$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					$charged_reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
					$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id REGEXP', $charged_reference_id. '[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					if($project_data['project_type'] == 'fixed') {

						$reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						
						$release_date = date('Y-m-d H:i:s');	
						$released_escrow_data['released_escrow_payment_reference_id'] = $reference_id;
						$released_escrow_data['project_id'] = $project_id;
						$released_escrow_data['project_owner_id'] = $po_id;
						$released_escrow_data['winner_id']= $sp_id ;
						$released_escrow_data['released_escrow_payment_description']= $post_data['partial_escrow_description'];
						$released_escrow_data['escrow_payment_requested_by_sp']=$active_escrow_data['escrow_creation_requested_by_sp'];
						$released_escrow_data['escrow_payment_requested_by_sp_date']= $active_escrow_data['escrow_creation_requested_by_sp_date'];
					  $released_escrow_data['initial_escrow_creation_date']= $active_escrow_data['escrow_creation_date'];
					  $released_escrow_data['escrow_payment_release_date']= $release_date;
					   $post_data['partial_escrow_amount'] = str_replace(" ","",$post_data['partial_escrow_amount']);
						if($post_data['partial_escrow_amount'] < $active_escrow_data['created_escrow_amount']){
							$released_escrow_data['is_partial_released']= 'Y';
							$released_escrow_data['reference_active_escrow_id']= $active_escrow_data['id'];
							 
							$partial_escrow_data = $this->Escrow_model->service_fee_calculation_partial_release_escrow($active_escrow_data['created_escrow_amount'],$active_escrow_data['service_fee_charges'],$post_data['partial_escrow_amount']);
							$released_escrow_data['service_fee_charges'] = $partial_escrow_data['partial_buisness_fee'];
							$released_escrow_data['released_escrow_payment_amount'] = $partial_escrow_data['partial_escrow_amount'];
							$released_escrow_data['total_escrow_payment_value'] = $partial_escrow_data['partial_buisness_fee']+$partial_escrow_data['partial_escrow_amount'];
							
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							
							$update_active_escrow_data['created_escrow_amount'] = $partial_escrow_data['remaining_escrow_amount'];
							$update_active_escrow_data['service_fee_charges'] = $partial_escrow_data['remaining_buisness_fee'];
							$update_active_escrow_data['total_escrow_payment_value'] = $partial_escrow_data['remaining_buisness_fee']+$partial_escrow_data['remaining_escrow_amount'];
							
							$update_active_escrow_data['is_sp_requested_release'] =  'N';
							$update_active_escrow_data['sp_requested_release_date'] =  NULL;
							$update_active_escrow_data['partial_release_date'] =  $release_date;
							$update_active_escrow_data['is_partial_released'] =  'Y';
							
							
							$this->db->where ('id', $id);
							$this->db->update ($active_escrow_table_name,$update_active_escrow_data); 
						 }
						 
						if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
						
							$released_escrow_data['service_fee_charges'] = $active_escrow_data['service_fee_charges'];
							$released_escrow_data['released_escrow_payment_amount'] = $active_escrow_data['created_escrow_amount'];
							$released_escrow_data['total_escrow_payment_value'] = $active_escrow_data['total_escrow_payment_value'];
							$this->db->delete($active_escrow_table_name, ['id'=>$id]); 
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
						}


						// Following data will be inserted into Charged_service_fees_tracking table
						$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_charged_service_fees)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						$charged_service_fees_data['charged_service_fee_reference_id'] = $reference_id;
						$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $released_escrow_data['released_escrow_payment_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $released_escrow_data['project_owner_id'];
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

						// Insert data into charged service fees table
						$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);

					} else if($project_data['project_type'] == 'hourly') {
						$post_data['partial_escrow_amount'] = str_replace(" ","",$post_data['partial_escrow_amount']);
						
						$reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$release_date = date('Y-m-d H:i:s');	
						$released_escrow_data['released_escrow_payment_reference_id'] = $reference_id;
						$released_escrow_data['project_id'] = $project_id;
						$released_escrow_data['project_owner_id'] = $po_id;
						$released_escrow_data['winner_id']= $sp_id ;
						$released_escrow_data['released_escrow_payment_description']= $post_data['partial_escrow_description'];
						$released_escrow_data['released_escrow_payment_number_of_hours']= $post_data['partial_escrow_number_of_hours'];
						$released_escrow_data['released_escrow_payment_hourly_rate']= $active_escrow_data['escrow_considered_hourly_rate'];
						$released_escrow_data['escrow_payment_requested_by_sp']= $active_escrow_data['escrow_creation_requested_by_sp'];
						$released_escrow_data['escrow_payment_requested_by_sp_date']= $active_escrow_data['escrow_creation_requested_by_sp_date'];
					  $released_escrow_data['initial_escrow_creation_date']= $active_escrow_data['escrow_creation_date'];
					  $released_escrow_data['escrow_payment_release_date']= $release_date;
					
						if($post_data['partial_escrow_amount'] < $active_escrow_data['created_escrow_amount']){
							$released_escrow_data['is_partial_released']= 'Y';
							$released_escrow_data['reference_active_escrow_id']= $active_escrow_data['id'];
							 
							$partial_escrow_data = $this->Escrow_model->service_fee_calculation_partial_release_escrow($active_escrow_data['created_escrow_amount'],$active_escrow_data['service_fee_charges'],$post_data['partial_escrow_amount']);
							$released_escrow_data['service_fee_charges'] = $partial_escrow_data['partial_buisness_fee'];
							$released_escrow_data['released_escrow_payment_amount'] = $partial_escrow_data['partial_escrow_amount'];
							$released_escrow_data['total_escrow_payment_value'] = $partial_escrow_data['partial_buisness_fee']+$partial_escrow_data['partial_escrow_amount'];
							
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							$released_escrow_last_insert_id = $this->db->insert_id();

							$update_active_escrow_data['escrow_considered_number_of_hours'] = $active_escrow_data['escrow_considered_number_of_hours'] - $post_data['partial_escrow_number_of_hours'];
							$update_active_escrow_data['created_escrow_amount'] = $partial_escrow_data['remaining_escrow_amount'];
							$update_active_escrow_data['service_fee_charges'] = $partial_escrow_data['remaining_buisness_fee'];
							$update_active_escrow_data['total_escrow_payment_value'] = $partial_escrow_data['remaining_buisness_fee']+$partial_escrow_data['remaining_escrow_amount'];
							$update_active_escrow_data['is_sp_requested_release'] =  'N';
							$update_active_escrow_data['sp_requested_release_date'] =  NULL;
							$update_active_escrow_data['partial_release_date'] =  $release_date;
							$update_active_escrow_data['is_partial_released'] =  'Y';
							$this->db->where ('id', $id);
							$this->db->update ($active_escrow_table_name,$update_active_escrow_data); 
						 }
						 
						if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
							$released_escrow_data['service_fee_charges'] = $active_escrow_data['service_fee_charges'];
							$released_escrow_data['released_escrow_payment_amount'] = $active_escrow_data['created_escrow_amount'];
							$released_escrow_data['total_escrow_payment_value'] = $active_escrow_data['total_escrow_payment_value'];
							$this->db->delete($active_escrow_table_name, ['id'=>$id]); 
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							$released_escrow_last_insert_id = $this->db->insert_id();
							$is_full_released = 1;
						}
						
						// Following data will be inserted into Charged_service_fees_tracking table
						
						$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_charged_service_fees)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						$charged_service_fees_data['charged_service_fee_reference_id'] = $reference_id;
						$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $released_escrow_data['released_escrow_payment_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $released_escrow_data['project_owner_id'];
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

						// Insert data into charged service fees table
						$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);

					} else if($project_data['project_type'] == 'fulltime') {
						$post_data['partial_escrow_amount'] = str_replace(" ","",$post_data['partial_escrow_amount']);
						
						$reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$release_date = date('Y-m-d H:i:s');	
						$released_escrow_data['released_escrow_payment_reference_id'] = $reference_id;
						$released_escrow_data['fulltime_project_id'] = $project_id;
						$released_escrow_data['employer_id'] = $po_id;
						$released_escrow_data['employee_id']= $sp_id ;
						$released_escrow_data['released_escrow_payment_description']= $post_data['partial_escrow_description'];
						$released_escrow_data['escrow_payment_requested_by_employee']= $active_escrow_data['escrow_creation_requested_by_employee'];
						$released_escrow_data['escrow_payment_requested_by_employee_date']= $active_escrow_data['escrow_creation_requested_by_employee_date'];
					  $released_escrow_data['initial_escrow_creation_date']= $active_escrow_data['escrow_creation_date'];
					  $released_escrow_data['escrow_payment_release_date']= $release_date;
					
						if($post_data['partial_escrow_amount'] < $active_escrow_data['created_escrow_amount']){
							$released_escrow_data['is_partial_released']= 'Y';
							$released_escrow_data['reference_active_escrow_id']= $active_escrow_data['id'];
							 
							$partial_escrow_data = $this->Escrow_model->service_fee_calculation_partial_release_escrow($active_escrow_data['created_escrow_amount'],$active_escrow_data['service_fee_charges'],$post_data['partial_escrow_amount']);
							$released_escrow_data['service_fee_charges'] = $partial_escrow_data['partial_buisness_fee'];
							$released_escrow_data['released_escrow_payment_amount'] = $partial_escrow_data['partial_escrow_amount'];
							$released_escrow_data['total_escrow_payment_value'] = $partial_escrow_data['partial_buisness_fee']+$partial_escrow_data['partial_escrow_amount'];
							
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							$released_escrow_last_insert_id = $this->db->insert_id();

							$update_active_escrow_data['created_escrow_amount'] = $partial_escrow_data['remaining_escrow_amount'];
							$update_active_escrow_data['service_fee_charges'] = $partial_escrow_data['remaining_buisness_fee'];
							$update_active_escrow_data['total_escrow_payment_value'] = $partial_escrow_data['remaining_buisness_fee']+$partial_escrow_data['remaining_escrow_amount'];
							$update_active_escrow_data['is_employee_requested_release'] =  'N';
							$update_active_escrow_data['employee_requested_release_date'] =  NULL;
							$update_active_escrow_data['partial_release_date'] =  $release_date;
							$update_active_escrow_data['is_partial_released'] =  'Y';
							$this->db->where ('id', $id);
							$this->db->update ($active_escrow_table_name,$update_active_escrow_data); 
						}
						 
						if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
							$released_escrow_data['service_fee_charges'] = $active_escrow_data['service_fee_charges'];
							$released_escrow_data['released_escrow_payment_amount'] = $active_escrow_data['created_escrow_amount'];
							$released_escrow_data['total_escrow_payment_value'] = $active_escrow_data['total_escrow_payment_value'];
							$this->db->delete($active_escrow_table_name, ['id' => $id]); 
							$this->db->insert ($released_escrow_table_name, $released_escrow_data);
							$released_escrow_last_insert_id = $this->db->insert_id();
							// $is_full_released = 1;
						}
						
						// Following data will be inserted into Charged_service_fees_tracking table
						$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_charged_service_fees)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						$charged_service_fees_data['charged_service_fee_reference_id'] = $reference_id;
						$charged_service_fees_data['project_id'] = $released_escrow_data['fulltime_project_id'];
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $released_escrow_data['released_escrow_payment_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $released_escrow_data['employer_id'];
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

						// Insert data into charged service fees table
						$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
					}
					
					#######update the account balance of sp when po released escrow payment
					$this->db->set('user_account_balance', 'user_account_balance+'.$post_data['partial_escrow_amount'], false);
					$this->db->where('user_id' ,$sp_id);
					$this->db->update('users_details');

					$released_escrow_value = str_replace(".00","",number_format($post_data['partial_escrow_amount'],  2, '.', ' '));
					$released_escrow_value = $released_escrow_value." ".CURRENCY;
					
				
					$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id',$sp_id)
					->get()->row_array();
					
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];	
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($project_data['project_type'] == 'fixed') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
							
								if($post_data['partial_milestone_amount'] >= $active_escrow_data['created_escrow_amount']) {
									
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_released_escrow_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_released_escrow_user_activity_log_displayed_message');
									}
								} else {
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_partially_released_escrow_user_activity_log_displayed_message');
									}else{		
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_partially_released_escrow_user_activity_log_displayed_message');
									}
								}
								
							} else{
								if($post_data['partial_milestone_amount'] >= $active_escrow_data['created_escrow_amount']){
									
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_released_escrow_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_released_escrow_user_activity_log_displayed_message');
									}
								} else {
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_partially_released_escrow_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_partially_released_escrow_user_activity_log_displayed_message');
									}
								}
								
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						
						}else{
						
							if($post_data['partial_milestone_amount'] >= $active_escrow_data['created_escrow_amount']){
								$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_released_escrow_user_activity_log_displayed_message');
							
							}else{
						
								$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_partially_released_escrow_user_activity_log_displayed_message');
							}
							
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						if($post_data['partial_milestone_amount'] >= $active_escrow_data['created_escrow_amount']){
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_released_escrow_user_activity_log_displayed_message');
						}else{
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_partially_released_escrow_user_activity_log_displayed_message');
						}
						
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						/* $sp_notification_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$paid_milestone_value,$project_url_link,$project_title),$sp_notification_message); */
						
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) { 
							$sp_notification_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_notification_message);
						} else {
							$sp_notification_message = str_replace(array("{user_company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_notification_message);
						}
						
					} else if($project_data['project_type'] == 'hourly') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']) {
									
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_released_escrow_user_activity_log_displayed_message');
									}else{	
									
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_released_escrow_user_activity_log_displayed_message');
									}
								} else {
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_partially_released_escrow_user_activity_log_displayed_message');
									}else{
										
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_partially_released_escrow_user_activity_log_displayed_message');
									}
								}
								
							} else{
								if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_released_escrow_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_released_escrow_user_activity_log_displayed_message');
									}
								} else {
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_partially_released_escrow_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_partially_released_escrow_user_activity_log_displayed_message');
									}
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_released_escrow_user_activity_log_displayed_message');
							} else {
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_partially_released_escrow_user_activity_log_displayed_message');
							}
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_released_escrow_user_activity_log_displayed_message');
						}else{
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_partially_released_escrow_user_activity_log_displayed_message');
						}
						
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) { 
							$sp_notification_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_notification_message);
						} else {
							$sp_notification_message = str_replace(array("{user_company_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_notification_message);
						}

					} else if($project_data['project_type'] == 'fulltime') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']) {
									
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_released_escrow_user_activity_log_displayed_message');
									}else{	
									
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_released_escrow_user_activity_log_displayed_message');
									}
								} else {

									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_partially_released_escrow_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_partially_released_escrow_user_activity_log_displayed_message');
									}
								}
								
							} else{
								if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_FEmale_released_escrow_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_released_escrow_user_activity_log_displayed_message');
									}
								} else {
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_female_partially_released_escrow_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_partially_released_escrow_user_activity_log_displayed_message');
									}
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
								$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_released_escrow_user_activity_log_displayed_message');
							} else {
								$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_partially_released_escrow_user_activity_log_displayed_message');
							}
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						if($post_data['partial_escrow_amount'] >= $active_escrow_data['created_escrow_amount']){
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_released_escrow_user_activity_log_displayed_message');
						}else{
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_partially_released_escrow_user_activity_log_displayed_message');
						}
						
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) { 
							$sp_notification_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_notification_message);
						} else {
							$sp_notification_message = str_replace(array("{user_company_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_notification_message);
						}
					}
					
					############### start code for complete the bid/project here #####
					if($project_data['project_type'] == 'fulltime') {
						$in_progress_bid_data = $this->db->get_where($in_progress_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>$sp_id])->row_array();
					} else {
						$in_progress_bid_data = $this->db->get_where($in_progress_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
						
						$in_complete_bid_data = $this->db->get_where($in_complete_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
					}
					if(!empty($completed_bid_table_name)) {
						$get_completed_bid_data = $this->db->get_where($completed_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id])->row_array();
					}
					if($project_data['project_type'] == 'fulltime') {
						$total_released_escrow = $total_released_escrow_po = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'],array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
					} else {
						$total_released_escrow = $total_released_escrow_po = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
					}
					
					if($project_data['project_type'] != 'fulltime' && ((!empty($in_progress_bid_data) && $in_progress_bid_data['initial_project_agreed_value'] != 0 && $total_released_escrow >= $in_progress_bid_data['initial_project_agreed_value']) || (!empty($in_complete_bid_data) && $in_complete_bid_data['initial_project_agreed_value'] != 0 && $total_released_escrow >= $in_complete_bid_data['initial_project_agreed_value']))){
						// condition for configs if PO release escrow and aftre relase project moved to completed bid
						
						if($project_data['project_type'] == 'fixed'){
							if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y' )){
								if($project_data['gender'] == 'M'){
									
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{	
									
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}
									
								}else{
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}
									
								}
								$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							
							}else{
							
								$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_released_escrow_project_completed_user_activity_log_displayed_message');
								
								$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							}
							
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_released_escrow_project_completed_user_activity_log_displayed_message');
						
							$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
							
							$po_notification_msg = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_po_when_po_released_escrow_project_completed');
							
							$po_notification_msg = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_notification_msg);
							
						}
						else if($project_data['project_type'] == 'hourly') {
							if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y' )){
								if($project_data['gender'] == 'M'){
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}
								} else{
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{	
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}
								}
								$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
								} else {
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_released_escrow_project_completed_user_activity_log_displayed_message');
								$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);

								
							}
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_released_escrow_project_completed_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
							
							$po_notification_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_po_released_escrow_project_completed');
							$po_notification_msg = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_notification_msg);
							
						}
						
					}
					user_display_log($sp_activity_log_message,$sp_id); // activity log message for service provider
					user_display_log($po_activity_log_message,$po_id); // activity log message for project owner
					
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->load->library('ftp');
					$this->ftp->connect($config); 
					$project_completion_date = date('Y-m-d H:i:s');
					$show_feedback_tab = 0;
					//$remove_id = 0;
					$is_bid_completed = '0';
					$is_project_status_change = '0';
					$project_status = $project_data['project_type'] != 'fulltime' ? $this->config->item('project_status_in_progress') : '';
					$total_paid_amount = 0;
					if($project_data['project_type'] != 'fulltime' && (!empty($in_progress_bid_data) || !empty($in_complete_bid_data))){
						//$remove_id = $in_progress_bid_data['id'];
						if(!empty($in_progress_bid_data)){
						  $total_paid_amount = $in_progress_bid_data['initial_project_agreed_value'];
						}
						if(!empty($in_complete_bid_data)){
						  $total_paid_amount = $in_complete_bid_data['initial_project_agreed_value'];
						}
						
						
						
						if(($in_progress_bid_data['initial_project_agreed_value'] != 0 && $total_released_escrow >= $in_progress_bid_data['initial_project_agreed_value']) || ($in_complete_bid_data['initial_project_agreed_value'] != 0 && $total_released_escrow >= $in_complete_bid_data['initial_project_agreed_value'])){
							$total_paid_amount = $total_released_escrow_po;
							if($project_data['project_type'] == 'fixed'){
								$completed_bid_data['project_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_id'] : $in_complete_bid_data['project_id'];
								
								$completed_bid_data['winner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['winner_id'] : $in_complete_bid_data['winner_id'];
								
								$completed_bid_data['project_owner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_owner_id'] : $in_complete_bid_data['project_owner_id'];
								
								$completed_bid_data['initial_bid_description'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_bid_description'] : $in_complete_bid_data['initial_bid_description'];
								
								$completed_bid_data['bidding_dropdown_option'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['bidding_dropdown_option'] : $in_complete_bid_data['bidding_dropdown_option'];
								
								$completed_bid_data['initial_project_agreed_value'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_value'] : $in_complete_bid_data['initial_project_agreed_value'];
								
								$completed_bid_data['initial_bid_value'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_bid_value'] : $in_complete_bid_data['initial_bid_value'];
								
								$completed_bid_data['project_winner_work_start_date'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_start_date'] : $in_complete_bid_data['project_start_date'];
								
								$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
								$completed_bid_data['total_project_amount'] = $total_released_escrow;
							} else if($project_data['project_type'] == 'hourly') {
								$completed_bid_data['project_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_id'] : $in_complete_bid_data['project_id'];
								
								$completed_bid_data['winner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['winner_id'] : $in_complete_bid_data['winner_id'];
								
								$completed_bid_data['project_owner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_owner_id'] : $in_complete_bid_data['project_owner_id'];
								
								$completed_bid_data['initial_bid_description'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_bid_description'] : $in_complete_bid_data['initial_bid_description'];
								
								$completed_bid_data['bidding_dropdown_option'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['bidding_dropdown_option'] : $in_complete_bid_data['bidding_dropdown_option'];
								
								$completed_bid_data['initial_project_agreed_value'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_value'] : $in_complete_bid_data['initial_project_agreed_value'];
								
								
								
								$completed_bid_data['initial_project_agreed_number_of_hours'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_number_of_hours'] : $in_complete_bid_data['initial_project_agreed_number_of_hours'];
								
								
								$completed_bid_data['initial_project_agreed_hourly_rate'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_hourly_rate'] : $in_complete_bid_data['initial_project_agreed_hourly_rate'];
								
								$completed_bid_data['project_winner_work_start_date'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_start_date'] : $in_complete_bid_data['project_start_date'];
								
								$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
								
								$completed_bid_data['total_project_amount'] = $total_released_escrow;
							}
							if(empty($get_completed_bid_data)){
								$is_bid_completed = '1';
								$this->db->insert ($completed_bid_table_name, $completed_bid_data);// move data to completed internally tracking
								
								// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
								$ratings_feedbacks_exchange['project_id'] = $project_id;
								$ratings_feedbacks_exchange['project_title'] = $project_title;
								$ratings_feedbacks_exchange['project_type'] = $project_data['project_type'];
								$ratings_feedbacks_exchange['project_completion_date'] = $project_completion_date;
								$ratings_feedbacks_exchange['po_id'] = $po_id;
								$ratings_feedbacks_exchange['sp_id'] = $sp_id;
								$this->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($ratings_feedbacks_exchange);	
								// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
								
								
								
								$this->db->delete($in_progress_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id]); 
								$this->db->delete($in_complete_bid_table_name, ['project_id' => $project_id,'winner_id'=>$sp_id]); 
								
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
						} else {
							
							if($total_released_escrow >= $in_progress_bid_data['initial_project_agreed_value']) {
								
								$total_paid_amount  = $total_released_escrow_po;
							}
							if($is_full_released && $in_progress_bid_data['initial_project_agreed_value'] == 0) {
								$show_feedback_tab = 1;
								// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
								$ratings_feedbacks_exchange['project_id'] = $project_id;
								$ratings_feedbacks_exchange['project_title'] = $project_title;
								$ratings_feedbacks_exchange['project_type'] = $project_data['project_type'];
								$ratings_feedbacks_exchange['project_completion_date'] = date('Y-m-d H:i:s');
								$ratings_feedbacks_exchange['po_id'] = $po_id;
								$ratings_feedbacks_exchange['sp_id'] = $sp_id;
								$this->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($ratings_feedbacks_exchange);	
								// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
								
								
								
								
							}
						}
					} else if(!empty($in_progress_bid_data)) {
						if($total_released_escrow >= $in_progress_bid_data['initial_fulltime_project_agreed_salary']) {
							$total_paid_amount  = $total_released_escrow_po;
						}
						if($total_paid_amount >= $in_progress_bid_data['initial_fulltime_project_threshold_value'] && strtotime(date('Y-m-d H:i:s')) > strtotime($in_progress_bid_data['feedback_exchange_availability_date'])) {
							$show_feedback_tab = 1;
							// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
							$ratings_feedbacks_exchange['project_id'] = $project_id;
							$ratings_feedbacks_exchange['project_title'] = $project_title;
							$ratings_feedbacks_exchange['project_type'] = $project_data['project_type'];
							$ratings_feedbacks_exchange['project_completion_date'] = date('Y-m-d H:i:s');
							$ratings_feedbacks_exchange['po_id'] = $po_id;
							$ratings_feedbacks_exchange['sp_id'] = $sp_id;
							$this->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($ratings_feedbacks_exchange);	
							// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
						}
					}
					if(!empty($get_completed_bid_data)){
						$this->db->where ('id', $get_completed_bid_data['id']);
						$this->db->update ($completed_bid_table_name,['total_project_amount'=>$total_released_escrow]);
						$total_paid_amount = $total_released_escrow;
					}
					
					if($project_data['project_type'] == 'fulltime') {
						$count_in_progress_bids = $this->db->where(['fulltime_project_id' => $project_id])->from($in_progress_bid_table_name)->count_all_results();
					} else {
						$count_in_progress_bids = $this->db->where(['project_id' => $project_id])->from($in_progress_bid_table_name)->count_all_results();
						
						$count_in_complete_bids = $this->db->where(['project_id' => $project_id])->from($in_complete_bid_table_name)->count_all_results();
					}
					if($count_in_progress_bids ==0 && $count_in_complete_bids ==0){
						$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]); 
						$project_completed_table_data_exists = $this->db->where(['project_id' => $project_id])->from($completed_project_table)->count_all_results();
						$is_project_status_change = '1';
						$project_status = $this->config->item('project_status_completed');
						if($project_completed_table_data_exists == 0){
							$profile_name = $project_data['profile_name'];
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
							
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
							$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
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
							
							if(!empty($in_complete_bid_data)){
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$project_owner_attachments_dir;
							}
							if(!empty($in_progress_bid_data)){
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
							}
							
							
							
							$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
							
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
										if(!empty($in_progress_bid_data)){
										 $source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										if(!empty($in_complete_bid_data)){
										 $source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
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
							// remove in progress folder
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
							}
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id);
							}
						}
					}
					$this->ftp->close();
					if($project_data['project_type'] == 'fulltime') {
						$project_value= $this->config->item('fulltime_projects_employer_view_total_project_value').'<span class="touch_line_break">'.str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY .'</span>';
					} else {
						$project_value= $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY.'</span>';
					}
					
					$project_completion_date_container_html = '<span class="default_black_bold"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->config->item('project_details_page_completed_on').'</span>'.$project_completion_date;
					############### End code for complete the bid/project here #####
					if($is_bid_completed == '1' ){
					
						$completed_bidder_attachment_list = $this->db // get the user detail
						->select('id,bid_attachment_name,user_id')
						->from('projects_active_bids_users_attachments_tracking')
						->where('project_id', (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_id'] : $in_complete_bid_data['project_id'])
						->where('user_id', (!empty($in_progress_bid_data)) ? $in_progress_bid_data['winner_id'] : $in_complete_bid_data['winner_id'])
						->order_by('id DESC')
						->get()->result_array();

						$completed_bidder_data['bid_attachments'] = $completed_bidder_attachment_list;

						$total_project_value[$completed_bidder_data['winner_id']] = $completed_bidder_data['total_project_amount'];
						
						echo json_encode(['status' => 200,'section_name'=>$section_name,'total_paid_amount' => number_format($total_paid_amount, 0, '', ' ')." ".CURRENCY,'section_id' => $section_id,'sp_id'=>$sp_id,'po_id'=>$po_id,'po_msg'=>$po_notification_msg,'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'is_project_status_change'=>$is_project_status_change,
							'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status' => $project_status,'location'=>'','data'=>$this->load->view('bidding/project_completed_bidders_listing',array('project_data'=>$project_data,'completed_bidder_data'=>$completed_bidder_data, 'total_paid_amount' => $total_project_value), true)]);
						die;
					
					}else{
						echo json_encode(['status' => 200, 'total_paid_amount' => number_format($total_paid_amount, 0, '', ' ')." ".CURRENCY, 'show_feedback_tab' => $show_feedback_tab,'sp_id'=>$sp_id,'po_id'=>$po_id,'po_msg'=>$po_notification_msg,'sp_id'=>$sp_id,'sp_msg'=>$sp_notification_message,'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'is_project_status_change'=>$is_project_status_change,
							'project_completion_date_html'=>$project_completion_date_html, 'project_status' => $project_status, 'location'=>'','section_name'=>$section_name,'section_id'=>$section_id]);
						die;
					
					}
					
				}else{
					//echo json_encode($msg);
					echo json_encode ($validation_data_array);
					die;
				}
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	* This function is used to make the popup of release escrow confirmation by PO. - Done
	*/
	public function released_escrow_confirmation_popup_body_po(){
		
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
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_release_escrow_deleted_project')]);
					die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,ud.current_membership_plan_id,mp.membership_plan_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'u.user_id = ud.user_id', 'left')
					->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
			
				
				
				$release_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				} else if($project_data['project_type'] == 'fulltime'){
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($sp_id),'employer_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($po_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_release_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>Cryptor::doDecrypt($sp_id),'project_owner_id_of_disputed_project'=>Cryptor::doDecrypt($po_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_release_escrow_on_active_disputed_project');
				}
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				
				
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				
				
				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_invalid_active_escrow_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_po_view_invalid_active_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				/* if($active_milestone_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_release_milestone_message')]);
					die;
				} */
				
				$sp_data = $this->db->select('user_id,first_name,last_name,company_name,account_type,is_authorized_physical_person,gender')->from('users')->where('user_id',Cryptor::doDecrypt($sp_id))->get()->row_array();
				
				$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ? $sp_data['first_name'] . ' ' . $sp_data['last_name'] : $sp_data['company_name'];
				
				$confirmation_modal_title = '';
				if(!empty($active_escrow_data)){
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_btn_padding" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn release_escrow_po width-auto default_btn_padding">'.$this->config->item('release_escrow_confirmation_fulltime_project_modal_confirm_btn_txt').'</button>';
					} else {
						
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_btn_padding" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" disabled style="opacity:0.65" class="btn blue_btn default_btn release_escrow_po width-auto default_btn_padding">'.$this->config->item('release_escrow_confirmation_project_modal_confirm_btn_txt').'</button>';
					}
					
					$confirmation_modal_body.= '<form class="release_escrow_form" name="release_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form>';
					
					/* if($project_data['project_type'] == 'hourly') {
						$confirmation_modal_body .= '<span><i class="far fa-clock"></i><label>Hourly Rate:</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['escrow_considered_hourly_rate'],  2, '.', ' ')).' '.CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour').'</small></span>';$confirmation_modal_body .= '<span><i class="fas fa-stopwatch"></i><label>Number of hours:</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['escrow_considered_number_of_hours'],  2, '.', ' ')).'</small></span>';
					}
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_body .= '<span><i class="far fa-credit-card"></i><label>'.$this->config->item('release_escrow_confirmation_project_modal_available_escrowed_amount_txt').'</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' ')).' '. CURRENCY . '</small></span></div>';
					} else {
						$confirmation_modal_body .= '<span><i class="far fa-credit-card"></i><label>'.$this->config->item('release_escrow_confirmation_project_modal_available_escrowed_amount_txt').'</label><small class="touch_line_break">'.str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' ')).' '. CURRENCY . '</small></span></div>';
					} */
					
					
					if($project_data['project_type'] == 'fixed'){
						//class="popup_body_semibold_title"
						$confirmation_modal_message =  $this->config->item('fixed_budget_project_release_escrow_confirmation_project_modal_body');
						$confirmation_modal_message = str_replace(array('{fixed_budget_project_release_escrow_amount}','{user_first_name_last_name_or_company_name}'),array(str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' ')).' '.CURRENCY,$sp_name),$confirmation_modal_message);	
					}	
					if($project_data['project_type'] == 'hourly'){
						//class="popup_body_semibold_title"
						$confirmation_modal_message =  $this->config->item('hourly_rate_based_project_release_escrow_confirmation_project_modal_body');
						$confirmation_modal_message = str_replace(array('{hourly_rate_based_project_total_release_escrow_amount}','{number_of_hours}','{hourly_rate}','{user_first_name_last_name_or_company_name}'),array(str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' ')).' '.CURRENCY,str_replace(".00","",number_format($active_escrow_data['escrow_considered_number_of_hours'],  2, '.', ' ')),str_replace(".00","",number_format($active_escrow_data['escrow_considered_hourly_rate'],  2, '.', ' ')).' '.CURRENCY.$this->config->item('project_details_page_hourly_rate_based_project_per_hour'),$sp_name),$confirmation_modal_message);	
					}
					if($project_data['project_type'] == 'fulltime'){
						//class="popup_body_semibold_title"
						$confirmation_modal_message =  $this->config->item('fulltime_project_release_escrow_confirmation_project_modal_body');
						
						$confirmation_modal_message = str_replace(array('{fulltime_project_release_escrow_amount}','{user_first_name_last_name_or_company_name}'),array(str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' ')).' '.CURRENCY,$sp_name),$confirmation_modal_message);	
					}
										
					
					$confirmation_modal_body .= '<div class="popup_body_semibold_title">'.$confirmation_modal_message.'</div>';
					
					
					
					//$confirmation_modal_body .= '</div></div></div></div>';
					//$confirmation_modal_body .= '</div></div>';
					
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="release_escrow_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$release_escrow_disclaimer_message.'</span></label></div></div></div>';
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
	* This function is used to release escrow by PO. - Done
	*/
	public function release_escrow_po(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				$user = $this->session->userdata ('user');
				
				if(Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_release_escrow_deleted_project')]);
					die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				if($project_data['project_type'] == 'fixed'){
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
					$released_escrow_table_name = 'fixed_budget_projects_released_escrows';
					$in_progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$in_complete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
					$completed_project_table = 'fixed_budget_projects_completed';
					$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
					
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
					$released_escrow_table_name = 'hourly_rate_based_projects_released_escrows';
					$in_progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					$completed_project_table = 'hourly_rate_based_projects_completed';
					$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
					$in_complete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';

				} else if($project_data['project_type'] == 'fulltime') {
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
					$released_escrow_table_name = 'fulltime_projects_released_escrows';
					$in_progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					$charged_service_fees_table_name = 'projects_charged_service_fees_tracking';
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
				}
				
				if($project_data['project_type'] == 'fulltime'){ 
					$check_active_dispute = $this->db->where(['disputed_fulltime_project_id' => $project_id,'employee_winner_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($sp_id),'employer_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($po_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('fulltime_project_details_page_employer_tries_release_escrow_on_active_disputed_fulltime_project');
				} else {
					$check_active_dispute = $this->db->where(['disputed_project_id' => $project_id,'sp_winner_id_of_disputed_project'=>Cryptor::doDecrypt($sp_id),'project_owner_id_of_disputed_project'=>Cryptor::doDecrypt($po_id)])->from($active_dispute_table_name)->count_all_results();
					$error_msg = $this->config->item('project_details_page_po_tries_release_escrow_on_active_disputed_project');
				}
				
				if($check_active_dispute > 0 ){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				
				
				

				if($project_data['project_type'] == 'fulltime') { 
					$check_active_escrow_exists = $this->db->where(['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				
				
				
				

				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employer_view_invalid_active_escrow_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_po_view_invalid_active_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
			
				/* if($active_milestone_data['project_owner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_release_milestone_message')]);
					die;
				} */
				if(!empty($active_escrow_data)){
					############### delete the requested escrow
					if($project_data['project_type'] == 'fulltime') { 
						$this->db->delete($active_escrow_table_name, ['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)]); 
					} else {
						$this->db->delete($active_escrow_table_name, ['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
					}
					############### track the history of reject the requested escrow
					
					if($project_data['project_type'] == 'fixed') {
						$reference_id = FIXED_BUDGET_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					} else if($project_data['project_type'] == 'hourly') {
						$reference_id = HOURLY_RATE_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					} else if($project_data['project_type'] == 'fulltime') {
						$reference_id = FULLTIME_PROJECT_RELEASED_ESCROW_REFERENCE_ID_PREFIX;
					}

					$latest_released_escrow_data = $this->db->from($released_escrow_table_name)->where('released_escrow_payment_reference_id REGEXP', $reference_id. '[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					
					
					if($project_data['project_type'] == 'fixed') {
						
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}
						
						$released_escrow_data['released_escrow_payment_reference_id'] = $reference_id;
						$released_escrow_data['project_id'] = $active_escrow_data['project_id'];
						$released_escrow_data['project_owner_id'] = $active_escrow_data['project_owner_id'];
						$released_escrow_data['winner_id']= $active_escrow_data['winner_id'] ;
						$released_escrow_data['released_escrow_payment_description']= $active_escrow_data['escrow_description'] ;
						$released_escrow_data['released_escrow_payment_amount']= $active_escrow_data['created_escrow_amount'];
						$released_escrow_data['service_fee_charges']= $active_escrow_data['service_fee_charges'];
						$released_escrow_data['total_escrow_payment_value'] = $active_escrow_data['total_escrow_payment_value'];
						$released_escrow_data['escrow_payment_release_date'] = date('Y-m-d H:i:s');
						$released_escrow_data['initial_escrow_creation_date'] = $active_escrow_data['escrow_creation_date'];
						$released_escrow_data['escrow_payment_requested_by_sp'] =$active_escrow_data['escrow_creation_requested_by_sp'];
						$released_escrow_data['escrow_payment_requested_by_sp_date'] =$active_escrow_data['escrow_creation_requested_by_sp_date'];
						
					} else if($project_data['project_type'] == 'hourly') {
						
						
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$released_escrow_data['released_escrow_payment_reference_id'] = $reference_id;
						$released_escrow_data['project_id'] = $active_escrow_data['project_id'];
						$released_escrow_data['project_owner_id'] = $active_escrow_data['project_owner_id'];
						$released_escrow_data['winner_id']= $active_escrow_data['winner_id'] ;
						$released_escrow_data['released_escrow_payment_description']= $active_escrow_data['escrow_description'] ;
						$released_escrow_data['released_escrow_payment_number_of_hours']= $active_escrow_data['escrow_considered_number_of_hours'];
						$released_escrow_data['released_escrow_payment_hourly_rate']= $active_escrow_data['escrow_considered_hourly_rate'];
						$released_escrow_data['released_escrow_payment_amount']= $active_escrow_data['created_escrow_amount'];
						$released_escrow_data['service_fee_charges']= $active_escrow_data['service_fee_charges'];
						$released_escrow_data['total_escrow_payment_value'] = $active_escrow_data['total_escrow_payment_value'];
						$released_escrow_data['escrow_payment_release_date'] = date('Y-m-d H:i:s');
						$released_escrow_data['initial_escrow_creation_date'] = $active_escrow_data['escrow_creation_date'];
						$released_escrow_data['escrow_payment_requested_by_sp'] = $active_escrow_data['escrow_creation_requested_by_sp'];
						$released_escrow_data['escrow_payment_requested_by_sp_date'] = $active_escrow_data['escrow_creation_requested_by_sp_date'];
					} else if($project_data['project_type'] == 'fulltime') {
						
						
						$digits = $this->config->item('reference_id_digits_limit');
						if(empty($latest_released_escrow_data)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_released_escrow_data['released_escrow_payment_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$released_escrow_data['released_escrow_payment_reference_id'] = $reference_id;
						$released_escrow_data['fulltime_project_id'] = $active_escrow_data['fulltime_project_id'];
						$released_escrow_data['employer_id'] = $active_escrow_data['employer_id'];
						$released_escrow_data['employee_id']= $active_escrow_data['employee_id'];
						$released_escrow_data['released_escrow_payment_description']= $active_escrow_data['escrow_description'];
						$released_escrow_data['released_escrow_payment_amount']= $active_escrow_data['created_escrow_amount'];
						$released_escrow_data['service_fee_charges']= $active_escrow_data['service_fee_charges'];
						$released_escrow_data['total_escrow_payment_value'] = $active_escrow_data['total_escrow_payment_value'];
						$released_escrow_data['escrow_payment_release_date'] = date('Y-m-d H:i:s');
						$released_escrow_data['initial_escrow_creation_date'] = $active_escrow_data['escrow_creation_date'];
						$released_escrow_data['escrow_payment_requested_by_employee'] = $active_escrow_data['escrow_creation_requested_by_employee'];
						$released_escrow_data['escrow_payment_requested_by_employee_date'] = $active_escrow_data['escrow_creation_requested_by_employee_date'];
					}
				
					$this->db->insert ($released_escrow_table_name, $released_escrow_data);

					$released_escrow_last_insert_id = $this->db->insert_id();

					
					$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
					$latest_charged_service_fees = $this->db->from($charged_service_fees_table_name)->where('charged_service_fee_reference_id REGEXP', $reference_id. '[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
					// Insert data into charged servide fees tracking table
					if($project_data['project_type'] == 'fixed') {
						$vat_percentage = $this->config->item('vat_percentage');
						
						$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');

						if(empty($latest_charged_service_fees)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$charged_service_fees_data['charged_service_fee_reference_id'] = $reference_id;
						$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $released_escrow_data['released_escrow_payment_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $released_escrow_data['project_owner_id'];
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

						// Insert data into charged service fees table
						$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);

					} else if($project_data['project_type'] == 'hourly') {
						$vat_percentage = $this->config->item('vat_percentage');
						
						$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');

						if(empty($latest_charged_service_fees)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$charged_service_fees_data['charged_service_fee_reference_id'] = $reference_id;
						$charged_service_fees_data['project_id'] = $released_escrow_data['project_id'];
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $released_escrow_data['released_escrow_payment_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $released_escrow_data['project_owner_id'];
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

						// Insert data into charged service fees table
						$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
					} else if($project_data['project_type'] == 'fulltime') {
						$vat_percentage = $this->config->item('vat_percentage');
						
						$reference_id = PROJECTS_CHARGED_SERVICE_FEES_REFERENCE_ID_PREFIX;
						$digits = $this->config->item('reference_id_digits_limit');

						if(empty($latest_charged_service_fees)) {
							$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
						} else {
							$exclude_inital_number = str_replace($reference_id, '', $latest_charged_service_fees['charged_service_fee_reference_id']);
							$exclude_inital_number = ltrim($exclude_inital_number, '0');
							$exclude_inital_number = (int)$exclude_inital_number + 1;
							$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
						}

						$charged_service_fees_data['charged_service_fee_reference_id'] = $reference_id;
						$charged_service_fees_data['project_id'] = $released_escrow_data['fulltime_project_id'];
						$charged_service_fees_data['released_escrow_payment_reference_id'] = $released_escrow_data['released_escrow_payment_reference_id'];
						$charged_service_fees_data['project_owner_id'] = $released_escrow_data['employer_id'];
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

						// Insert data into charged service fees table
						$this->Escrow_model->insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data);
					}
					
					#######update the account balance of sp when po released escrow payment
					$this->db->set('user_account_balance', 'user_account_balance+'.$active_escrow_data['created_escrow_amount'], false);
					$this->db->where('user_id' , $project_data['project_type'] == 'fulltime' ? $active_escrow_data['employee_id'] : $active_escrow_data['winner_id']);
					$this->db->update('users_details');
					######## get the detail of SP ##########
					$released_escrow_value = str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' '));
					$released_escrow_value = $released_escrow_value." ".CURRENCY;
					$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name')
					->from('users u')
					->where('u.user_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($project_data['project_type'] == 'fixed') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_released_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_released_escrow_user_activity_log_displayed_message');
								}
							} else {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_released_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_released_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_released_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_released_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
					} else if($project_data['project_type'] == 'hourly') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								
								if($project_data['is_authorized_physical_person'] == 'Y'){
									
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_released_escrow_user_activity_log_displayed_message');
									
								}else{
								
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_released_escrow_user_activity_log_displayed_message');
								}
							} else {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_released_escrow_user_activity_log_displayed_message');
									
								}else{
									$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_released_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_released_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_released_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
					} else if($project_data['project_type'] == 'fulltime') {
						if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
							if($project_data['gender'] == 'M'){
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_male_released_escrow_user_activity_log_displayed_message');
								}else{	
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_male_released_escrow_user_activity_log_displayed_message');
								}
							} else {
								if($project_data['is_authorized_physical_person'] == 'Y'){
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_app_female_released_escrow_user_activity_log_displayed_message');
								}else{
									$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_female_released_escrow_user_activity_log_displayed_message');
								}
							}
							$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						} else {
							$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employer_company_released_escrow_user_activity_log_displayed_message');
							$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						}
						$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employer_released_escrow_user_activity_log_displayed_message');
						$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fulltime_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
					}

					############### start code for complete the bid/project here #####
					if($project_data['project_type'] == 'fulltime') { 
						$in_progress_bid_data = $this->db->get_where($in_progress_bid_table_name, ['fulltime_project_id' => $project_id,'employee_id'=>Cryptor::doDecrypt($sp_id)])->row_array();
					} else {
						$in_progress_bid_data = $this->db->get_where($in_progress_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->row_array();
						
						$in_complete_bid_data = $this->db->get_where($in_complete_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->row_array();
					}
					
					if(!empty($completed_bid_table_name)) {
						$get_completed_bid_data = $this->db->get_where($completed_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)])->row_array();
					}

					if($project_data['project_type'] == 'fulltime') { 
						$total_released_escrow = $total_released_escrow_po = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'],array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
					} else {
						$total_released_escrow = $total_released_escrow_po = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'],array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					}
					
					if($project_data['project_type'] != 'fulltime' && ((!empty($in_progress_bid_data) && $total_released_escrow >= $in_progress_bid_data['initial_project_agreed_value'] && $in_progress_bid_data['initial_project_agreed_value'] > 0) || (!empty($in_complete_bid_data) && $total_released_escrow >= $in_complete_bid_data['initial_project_agreed_value'] && $in_complete_bid_data['initial_project_agreed_value'] > 0))){
						// condition for configs if PO release escrow and aftre relase project moved to completed bid
						if($project_data['project_type'] == 'fixed'){
							if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
								if($project_data['gender'] == 'M'){
									
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{
									
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}
								} else{
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_app_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}
								}
								$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							} else {
								$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_po_company_released_escrow_project_completed_user_activity_log_displayed_message');
								$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							}
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_po_released_escrow_project_completed_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
							
							$po_notification_msg = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_po_when_po_released_escrow_project_completed');
							
							$po_notification_msg = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{fixed_budget_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_notification_msg);
							
						} else if($project_data['project_type'] == 'hourly') {
							if(($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')){
								if($project_data['gender'] == 'M'){
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_male_released_escrow_project_completed_user_activity_log_displayed_message');
									}
								} else{
									if($project_data['is_authorized_physical_person'] == 'Y'){
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_app_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}else{
										$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_female_released_escrow_project_completed_user_activity_log_displayed_message');
									}
								}
								$sp_activity_log_message = str_replace(array("{user_first_name_last_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
							} else {
								$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_po_company_released_escrow_project_completed_user_activity_log_displayed_message');
								$sp_activity_log_message = str_replace(array("{company_name}","{po_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);

								
							}
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_po_released_escrow_project_completed_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
							
							$po_notification_msg = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_po_when_po_released_escrow_project_completed');
							
							
							
						}
						
						$po_notification_msg = str_replace(array("{user_first_name_last_name_or_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$released_escrow_value,$project_url_link,$project_title),$po_notification_msg);
					}
					
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner	
					
					
					$show_feedback_tab = 0;
					//$remove_id = 0;
					$is_bid_completed = '0';
					$is_project_status_change = '0';
					$total_paid_amount = 0;
					$project_status = $project_data['project_type'] != 'fulltime' ? $this->config->item('project_status_in_progress') : '';
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->load->library('ftp');
					$this->ftp->connect($config); 
					$project_completion_date = date('Y-m-d H:i:s');
					if($project_data['project_type'] != 'fulltime' && (!empty($in_progress_bid_data) || !empty($in_complete_bid_data))){
						
						if(!empty($in_progress_bid_data)){
						 $total_paid_amount = $in_progress_bid_data['initial_project_agreed_value'];
						}
						if(!empty($in_complete_bid_data)){
						 $total_paid_amount = $in_complete_bid_data['initial_project_agreed_value'];
						}
						
						if(($in_progress_bid_data['initial_project_agreed_value'] != 0 && $total_released_escrow >= $in_progress_bid_data['initial_project_agreed_value']) || ($in_complete_bid_data['initial_project_agreed_value'] != 0 && $total_released_escrow >= $in_complete_bid_data['initial_project_agreed_value'])){
							$total_paid_amount = $total_released_escrow_po;
							$is_bid_completed = '1';
							//$remove_id = $in_progress_bid_data['id'];
							if($project_data['project_type'] == 'fixed'){
								
								$completed_bid_data['project_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_id'] : $in_complete_bid_data['project_id'];
								
								$completed_bid_data['winner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['winner_id'] : $in_complete_bid_data['winner_id'];
								
								$completed_bid_data['project_owner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_owner_id'] : $in_complete_bid_data['project_owner_id'];
								
								$completed_bid_data['initial_bid_description'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_bid_description'] : $in_complete_bid_data['initial_bid_description'];
								
								$completed_bid_data['bidding_dropdown_option'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['bidding_dropdown_option'] : $in_complete_bid_data['bidding_dropdown_option'];
								
								$completed_bid_data['initial_project_agreed_value'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_value'] : $in_complete_bid_data['initial_project_agreed_value'];
								
								$completed_bid_data['initial_bid_value'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_bid_value'] : $in_complete_bid_data['initial_bid_value'];
								
								$completed_bid_data['project_winner_work_start_date'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_start_date'] : $in_complete_bid_data['project_start_date'];
								
								$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
								$completed_bid_data['total_project_amount'] = $total_released_escrow;
							} else if($project_data['project_type'] == 'hourly') {
								
								
								
								
								$completed_bid_data['project_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_id'] : $in_complete_bid_data['project_id'];
								
								$completed_bid_data['winner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['winner_id'] : $in_complete_bid_data['winner_id'];
								
								$completed_bid_data['project_owner_id'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_owner_id'] : $in_complete_bid_data['project_owner_id'];
								
								$completed_bid_data['initial_bid_description'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_bid_description'] : $in_complete_bid_data['initial_bid_description'];
								
								$completed_bid_data['bidding_dropdown_option'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['bidding_dropdown_option'] : $in_complete_bid_data['bidding_dropdown_option'];
								
								
								$completed_bid_data['initial_project_agreed_value'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_value'] : $in_complete_bid_data['initial_project_agreed_value'];
								
								
								
								$completed_bid_data['initial_project_agreed_number_of_hours'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_number_of_hours'] : $in_complete_bid_data['initial_project_agreed_number_of_hours'];
								
								
								
								
								$completed_bid_data['initial_project_agreed_hourly_rate'] = (!empty($in_progress_bid_data)) ? $in_progress_bid_data['initial_project_agreed_hourly_rate'] : $in_complete_bid_data['initial_project_agreed_hourly_rate'];
								
								
								
								$completed_bid_data['project_winner_work_start_date'] = 
								(!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_start_date'] : $in_complete_bid_data['project_start_date'];
								
								$completed_bid_data['project_winner_work_completion_date'] = $project_completion_date;
								$completed_bid_data['total_project_amount'] = $total_released_escrow;
							}
							if(empty($get_completed_bid_data)){
								$this->db->insert ($completed_bid_table_name, $completed_bid_data);// move data to completed internally tracking
								
								// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
								$ratings_feedbacks_exchange['project_id'] = $project_id;
								$ratings_feedbacks_exchange['project_title'] = $project_title;
								$ratings_feedbacks_exchange['project_type'] = $project_data['project_type'];
								$ratings_feedbacks_exchange['project_completion_date'] = $project_completion_date;
								$ratings_feedbacks_exchange['po_id'] = Cryptor::doDecrypt($po_id);
								$ratings_feedbacks_exchange['sp_id'] =Cryptor::doDecrypt($sp_id);
								$this->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($ratings_feedbacks_exchange);	
								// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
								
								
								
								
								$this->db->delete($in_progress_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
								
								$this->db->delete($in_complete_bid_table_name, ['project_id' => $project_id,'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
								
								
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
						} else if($in_progress_bid_data['initial_project_agreed_value'] == 0) {
							$show_feedback_tab = 1;
							$total_paid_amount = $total_released_escrow_po;
							// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
							$ratings_feedbacks_exchange['project_id'] = $project_id;
							$ratings_feedbacks_exchange['project_title'] = $project_title;
							$ratings_feedbacks_exchange['project_type'] = $project_data['project_type'];
							$ratings_feedbacks_exchange['project_completion_date'] = date('Y-m-d H:i:s');
							$ratings_feedbacks_exchange['po_id'] = Cryptor::doDecrypt($po_id);
							$ratings_feedbacks_exchange['sp_id'] = Cryptor::doDecrypt($sp_id);
							$this->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($ratings_feedbacks_exchange);	
							// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
							
							
						}
					} else if(!empty($in_progress_bid_data)) {
						$total_paid_amount = $total_released_escrow_po;
						if($total_paid_amount >= $in_progress_bid_data['initial_fulltime_project_threshold_value'] && strtotime(date('Y-m-d H:i:s')) > strtotime($in_progress_bid_data['feedback_exchange_availability_date'])) {
							$show_feedback_tab = 1;
							// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
							$ratings_feedbacks_exchange['project_id'] = $project_id;
							$ratings_feedbacks_exchange['project_title'] = $project_title;
							$ratings_feedbacks_exchange['project_type'] = $project_data['project_type'];
							$ratings_feedbacks_exchange['project_completion_date'] = date('Y-m-d H:i:s');
							$ratings_feedbacks_exchange['po_id'] = Cryptor::doDecrypt($po_id);
							$ratings_feedbacks_exchange['sp_id'] = Cryptor::doDecrypt($sp_id);
							$this->Escrow_model->insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($ratings_feedbacks_exchange);	
							// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
						}
					}
					
					if(!empty($get_completed_bid_data)){
						$this->db->where ('id', $get_completed_bid_data['id']);
						$this->db->update ($completed_bid_table_name,['total_project_amount'=> $total_released_escrow]);
						$total_paid_amount = $total_released_escrow;
					}
					
					if($project_data['project_type'] == 'fulltime' ) {
						$count_in_progress_bids = $this->db->where(['fulltime_project_id' => $project_id])->from($in_progress_bid_table_name)->count_all_results();
					} else {
						$count_in_progress_bids = $this->db->where(['project_id' => $project_id])->from($in_progress_bid_table_name)->count_all_results();
						
						$count_in_complete_bids = $this->db->where(['project_id' => $project_id])->from($in_complete_bid_table_name)->count_all_results();
					}
					if($count_in_progress_bids == 0 && $count_in_complete_bids ==0){
						$is_project_status_change = '1';
						$project_status = $this->config->item('project_status_completed');
						$this->db->delete($project_status_table_array['table_name'], ['project_id' => $project_id]); 
						$project_completed_table_data_exists = $this->db->where(['project_id' => $project_id])->from($completed_project_table)->count_all_results();
						if($project_completed_table_data_exists == 0){
							$profile_name = $project_data['profile_name'];
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
							
							
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
							$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
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
							
							if(!empty($in_progress_bid_data)){
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$project_owner_attachments_dir;
							}
							if(!empty($in_complete_bid_data)){
								$source_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$project_owner_attachments_dir;
							}
							
							$destination_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_completed_dir.$project_id.DIRECTORY_SEPARATOR;
							
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
										if(!empty($in_progress_bid_data)){
										 $source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
										}
										if(!empty($in_complete_bid_data)){
										 $source_bid_attachment_path = $users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id.$users_bid_attachments_dir.$bid_attachment_value['user_id'] .DIRECTORY_SEPARATOR .$bid_attachment_value['bid_attachment_name'];
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
							// remove in progress folder
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_progress_dir.$project_id);
							}
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id))) {
								$this->ftp->delete_dir($users_ftp_dir.$profile_name.$projects_ftp_dir.$project_in_complete_dir.$project_id);
							}
						}
					}
					############### End code for complete the bid/project here #####
					$this->ftp->close();
					
					$project_completion_date_container_html = '<span class="default_black_bold"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$this->config->item('project_details_page_completed_on').'</span>'.$project_completion_date;
					
					if($project_data['project_type'] == 'fulltime' ) {
						$project_value= $this->config->item('fulltime_projects_employer_view_total_project_value').'<span class="touch_line_break">'.str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY.'</span>';
					} else {
						$project_value= $this->config->item('fixed_or_hourly_project_value').'<span class="touch_line_break">'.str_replace(".00","",number_format($total_released_escrow,  2, '.', ' '))." ".CURRENCY.'</span>';
					}

					if($is_bid_completed == '1' ) {
					
					
						
						
						$completed_bidder_attachment_list = $this->db // get the user detail
						->select('id,bid_attachment_name,user_id')
						->from('projects_active_bids_users_attachments_tracking')
						->where('project_id', (!empty($in_progress_bid_data)) ? $in_progress_bid_data['project_id'] : $in_complete_bid_data['project_id'])
						->where('user_id', (!empty($in_progress_bid_data)) ? $in_progress_bid_data['winner_id'] : $in_complete_bid_data['winner_id'])
						->order_by('id DESC')
						->get()->result_array();

						$completed_bidder_data['bid_attachments'] = $completed_bidder_attachment_list;

						$total_project_value[$completed_bidder_data['winner_id']] = $completed_bidder_data['total_project_amount'];

						echo json_encode(['status' => 200,'total_paid_amount' => number_format($total_paid_amount, 0, '', ' ')." ".CURRENCY, 'section_id' => $section_id, 'sp_id'=>Cryptor::doDecrypt($sp_id),'po_msg'=>$po_notification_msg,'po_id'=>Cryptor::doDecrypt($po_id),'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'is_project_status_change'=>$is_project_status_change,
						'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status'=>$project_status,'data'=>$this->load->view('bidding/project_completed_bidders_listing',array('project_data'=>$project_data,'completed_bidder_data'=>$completed_bidder_data, 'total_paid_amount' => $total_project_value), true)]);
						die;
					} else {
						echo json_encode(['status' => 200, 'total_paid_amount' => number_format($total_paid_amount, 0, '', ' ')." ".CURRENCY,'show_feedback_tab' => $show_feedback_tab,'sp_id'=>Cryptor::doDecrypt($sp_id),'po_msg'=>$po_notification_msg,'po_id'=>Cryptor::doDecrypt($po_id),'project_value'=>$project_value,'is_bid_completed'=>$is_bid_completed,'is_project_status_change'=>$is_project_status_change,'project_completion_date_container_html'=>$project_completion_date_container_html,'project_status'=>$project_status]);
						die;
					}
					
				}
				
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
	* This function is used to make the popup of cancel escrow confirmation . - Done
	*/
	public function cancel_escrow_sp_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_cancel_escrow_deleted_project')]);
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

				$cancel_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
				}
				if($project_data['project_type'] == 'fulltime'){
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employee_view_cancel_invalid_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_cancel_invalid_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}
				$confirmation_modal_title = '';
				if(!empty($active_escrow_data)){
				
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('cancel_escrow_confirmation_fulltime_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" class="btn blue_btn default_btn cancel_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('cancel_escrow_confirmation_fulltime_project_modal_cancel_btn_txt').'</button>';
					} else {
						
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('cancel_escrow_confirmation_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn cancel_escrow_sp blue_btn default_btn width-auto" disabled style="opacity:0.65">'.$this->config->item('cancel_escrow_confirmation_project_modal_cancel_btn_txt').'</button>';
					}
					$confirmation_modal_body.= '<form class="cancel_escrow_form" name="cancel_requested_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"></div></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="cancel_escrow_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$cancel_escrow_disclaimer_message.'</span></label></div></div></div>';
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
	* This function is used to cancel the escrow by SP which is created by PO. - Done
	*/
	public function cancelled_escrow_by_sp(){
		
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$tab_type = $this->input->post ('tab_type');
				$section_name = $this->input->post ('section_name');
				$section_id = $this->input->post ('section_id');
				$user = $this->session->userdata ('user');
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_cancel_escrow_deleted_project')]);
					die;
				}
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from('users u')
					->where('u.user_id', Cryptor::doDecrypt($sp_id))
					->get()->row_array();
				
				if($project_data['project_type'] == 'fixed'){
					$cancelled_escrow_table_name = 'fixed_budget_projects_cancelled_escrows_tracking';
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$cancelled_escrow_table_name = 'hourly_rate_based_projects_cancelled_escrows_tracking';
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$cancelled_escrow_table_name = 'fulltime_projects_cancelled_escrows_tracking';
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
				}
		
				if($project_data['project_type'] == 'fulltime'){
					$check_escrow_exists = $this->db->where(['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_escrow_exists = $this->db->where(['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				if($check_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employee_view_cancel_invalid_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_cancel_invalid_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}

				/* if($active_milestone_data['winner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_cancel_milestone_message')]);
					die;
				} */
				if(!empty($active_escrow_data)){
					
					if($project_data['project_type'] == 'fixed') {
						$sp_notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_cancelled_active_escrow');
						
							
					} else if($project_data['project_type'] == 'hourly'){
						$sp_notification_message = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_sp_cancelled_active_escrow');
						
					} else if($project_data['project_type'] == 'fulltime'){
						$sp_notification_message = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employee_when_employee_cancelled_active_escrow');
					}
					$p_type = $project_data['project_type'];
					
					if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
					} else {
						$page = 1;
					}
					
					if($p_type == 'fulltime') {
						$this->db->delete($active_escrow_table_name, ['fulltime_project_id' => $project_id,'id'=>$id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)]); 
					} else {
						$this->db->delete($active_escrow_table_name, ['project_id' => $project_id,'id'=>$id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)]); 
					}
					
					############### track the history of reject the requested escrow
					if($p_type == 'fixed'){
						$total_reverted_escrow_value = $active_escrow_data['created_escrow_amount']+$active_escrow_data['service_fee_charges'];
						$cancel_escrow_data['project_id'] = $active_escrow_data['project_id'];
						$cancel_escrow_data['project_owner_id'] = $active_escrow_data['project_owner_id'];
						$cancel_escrow_data['winner_id']= $active_escrow_data['winner_id'] ;
						$cancel_escrow_data['reference_milestone_id']= $active_escrow_data['id'] ;
						$cancel_escrow_data['cancelled_escrow_description']= $active_escrow_data['escrow_description'] ;
						$cancel_escrow_data['reverted_escrowed_amount']= $active_escrow_data['created_escrow_amount'];
						$cancel_escrow_data['reverted_service_fee_charges']= $active_escrow_data['service_fee_charges'];
						$cancel_escrow_data['total_reverted_escrow_payment_value']= $total_reverted_escrow_value;
						$cancel_escrow_data['initial_escrow_creation_date']= $active_escrow_data['escrow_creation_date'];
						$cancel_escrow_data['escrow_cancellation_date'] = date('Y-m-d H:i:s');
					} else if($p_type == 'hourly') {
						$total_reverted_escrow_value = $active_escrow_data['created_escrow_amount']+$active_escrow_data['service_fee_charges'];
						$cancel_escrow_data['project_id'] = $active_escrow_data['project_id'];
						$cancel_escrow_data['project_owner_id'] = $active_escrow_data['project_owner_id'];
						$cancel_escrow_data['winner_id']= $active_escrow_data['winner_id'] ;
						$cancel_escrow_data['cancelled_escrow_description']= $active_escrow_data['escrow_description'] ;
						$cancel_escrow_data['reverted_escrow_considered_hourly_rate']= $active_escrow_data['escrow_considered_hourly_rate'] ;
						$cancel_escrow_data['reverted_escrow_considered_number_of_hours']= $active_escrow_data['escrow_considered_number_of_hours'] ;
						$cancel_escrow_data['reverted_escrowed_amount']= $active_escrow_data['created_escrow_amount'];
						$cancel_escrow_data['reverted_service_fee_charges']= $active_escrow_data['service_fee_charges'];
						$cancel_escrow_data['total_reverted_escrow_payment_value']= $total_reverted_escrow_value;
						$cancel_escrow_data['initial_escrow_creation_date']= $active_escrow_data['escrow_creation_date'];
						$cancel_escrow_data['escrow_cancellation_date'] = date('Y-m-d H:i:s');
					} else if($p_type == 'fulltime') {
						$total_reverted_escrow_value = $active_escrow_data['created_escrow_amount']+$active_escrow_data['service_fee_charges'];
						$cancel_escrow_data['fulltime_project_id'] = $active_escrow_data['fulltime_project_id'];
						$cancel_escrow_data['employer_id'] = $active_escrow_data['employer_id'];
						$cancel_escrow_data['employee_id']= $active_escrow_data['employee_id'] ;
						$cancel_escrow_data['cancelled_escrow_description']= $active_escrow_data['escrow_description'];
						$cancel_escrow_data['reverted_escrowed_amount']= $active_escrow_data['created_escrow_amount'];
						$cancel_escrow_data['reverted_service_fee_charges']= $active_escrow_data['service_fee_charges'];
						$cancel_escrow_data['total_reverted_escrow_payment_value']= $total_reverted_escrow_value;
						$cancel_escrow_data['initial_escrow_creation_date']= $active_escrow_data['escrow_creation_date'];
						$cancel_escrow_data['escrow_cancellation_date'] = date('Y-m-d H:i:s');
					}
					$this->db->insert ($cancelled_escrow_table_name,$cancel_escrow_data);
					$last_insert_id = $this->db->insert_id();
					
					## revert back the deducted amount to PO account balance
					$this->db->set('user_account_balance', 'user_account_balance+'.$total_reverted_escrow_value, false);
					$this->db->where('user_id' , Cryptor::doDecrypt($po_id));
					$this->db->update('users_details');

					if($p_type == 'fulltime') {
						$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
					} else {
						$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					}
					
					if($p_type == 'fulltime') {
					
						$total_record = $this->db->from('fulltime_projects_active_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
							
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
					
					
						$active_escrow_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_active_escrow_listing_limit'));
					} else {
					
					
						if($p_type=="fixed"){
							$active_escrows_table = 'fixed_budget_projects_active_escrows';
						}
						if($p_type == "hourly"){
							$active_escrows_table = 'hourly_rate_based_projects_active_escrows';
						}
						$total_record = $this->db->from($active_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)])->count_all_results();
						
						
						
						$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
						$active_escrow_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$paginations['offset'], $this->config->item('project_detail_page_active_escrow_listing_limit'));
					}
					
					
					//$page = 1;
					
					$data["active_escrow_data"] = $active_escrow_listing_project_data['data'];
					$data['active_escrow_count'] = $active_escrow_listing_project_data['total'];
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($active_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					$data['generate_pagination_links_escrow'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					$data['project_type']= $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id;
					$data['section_name']= $section_name;
					
					
					$multiplication = $this->config->item('project_detail_page_active_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_active_escrow_listing_limit') - count($data['active_escrow_data'])));
					$record_per_page = count($data['active_escrow_data']) < $this->config->item('project_detail_page_active_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_active_escrow_listing_limit') * ($page - 1)) + 1;
					
					######## get the detail of SP ##########
					$cancelled_escrow_value = str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' '));
					$cancelled_escrow_value = $cancelled_escrow_value." ".CURRENCY;
					
					$sp_name = ($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
				
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($p_type == 'fixed') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');	
								}else{	
								
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
								}
							}else{
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');	
								}else{	
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fixed_buget_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$cancelled_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						}else{
						
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fixed_buget_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$cancelled_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}

						$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{fixed_buget_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($cancelled_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					} else if($p_type == 'hourly') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_male_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
									
								}else{
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_male_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
								}
							}else{
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_female_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
									
								}else{
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_female_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
								}
							}
							
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{hourly_rate_based_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$cancelled_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						}else{
						
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{hourly_rate_based_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$cancelled_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}

						$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_sp_cancelled_active_escrow_created_by_po_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{hourly_rate_based_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($cancelled_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					} else if($p_type == 'fulltime') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message');
								}else{
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message');
								}
							} else{
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message');
								}else{
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fulltime_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$cancelled_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						
						} else{
						
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fulltime_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$cancelled_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}

						$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_cancelled_active_escrow_created_by_employer_user_activity_log_displayed_message');
						
						$sp_activity_log_message = str_replace(array("{fulltime_project_cancelled_escrow_amount}","{project_url_link}","{project_title}"),array($cancelled_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
					}
					
					user_display_log($sp_activity_log_message,Cryptor::doDecrypt($sp_id)); // activity log message for service provider
					user_display_log($po_activity_log_message,Cryptor::doDecrypt($po_id)); // activity log message for project owner
					$data['sum_escrow_amount'] = $sum_active_escrow_amount_project;
					echo json_encode(['status' => 200,'po_id'=>Cryptor::doDecrypt($po_id),'sp_msg'=>$sp_notification_message,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/active_escrows_section_project_detail',$data, true)]);
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
 	* This function is used to make the popup of when Sp make request for release the escrow. - Done
 	*/
	public function request_escrow_release_sp_confirmation_popup_body(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_request_release_escrow_deleted_project')]);
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
				
				$request_release_escrow_disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
				if($project_data['project_type'] == 'fixed'){
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
				}
				if($project_data['project_type'] == 'fulltime'){
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employee_view_request_release_invalid_escrow_fulltime_project');
					}else{
						$error_message = $this->config->item('project_details_page_sp_view_request_release_invalid_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				
				}

				$confirmation_modal_title = '';
				if(!empty($active_escrow_data)){
				
					if($project_data['project_type'] == 'fulltime'){
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('request_release_escrow_confirmation_fulltime_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" class="btn blue_btn default_btn request_release_escrow_sp width-auto" disabled style="opacity:0.65">'.$this->config->item('request_release_escrow_confirmation_fulltime_project_modal_request_release_btn_txt').'</button>';
					} else {
					
						$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$this->config->item('request_release_escrow_confirmation_project_modal_body').'</div>';
						$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button>&nbsp;<button type="button" class="btn request_release_escrow_sp blue_btn default_btn width-auto" disabled style="opacity:0.65">'.$this->config->item('request_release_escrow_confirmation_project_modal_request_release_btn_txt').'</button>';
					}
					 
					$confirmation_modal_body.= '<form class="request_release_escrow_form" name="request_release_escrow_form"><input type="hidden" value="'.$id.'"  id= row_id><input type="hidden" value="'.$project_id.'" id="project_id"><input type="hidden" value="'.$po_id.'" id="po_id"><input type="hidden" id="sp_id" value="'.$sp_id.'" name="id"><input type="hidden" id="tab_type" value="'.$tab_type.'" name="tab_type" ><input type="hidden"  id="section_id" value="'.$section_id.'" name="section_id"><input type="hidden"  id="section_name" value="'.$section_name.'" name="section_name"></form><div class="row"><div class="col-md-12"></div></div>';
					$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="request_release_escrow_checkbox_sp"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$request_release_escrow_disclaimer_message.'</span></label></div></div></div>';
					echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
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
 	* This function is used to make the  Sp make request for release the escrow. - Done
	*/
	public function request_escrow_release_sp(){
		if($this->input->is_ajax_request ()){
			$post_data = $this->input->post ();
			$project_id = $post_data['project_id'];
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id]);
				die;
			}
			if($this->input->method(TRUE) === 'POST'){
				$user = $this->session->userdata('user');
				
				$po_id = Cryptor::doDecrypt($post_data['po_id']);
				$sp_id = Cryptor::doDecrypt($post_data['sp_id']);
				$section_name = $post_data['section_name'];
				$section_id = $post_data['section_id'];
				$id = $post_data['row_id'];
				
				if($sp_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					$project_data['project_id'] = $project_data['fulltime_project_id'];
					$project_data['project_owner_id'] = $project_data['employer_id'];
					$project_data['project_title'] = $project_data['fulltime_project_title'];
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
			
				$sp_data = $this->db // get the user detail
					->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,u.gender')
					->from('users u')
					->where('u.user_id', $sp_id)
					->get()->row_array();
				
				if($project_data['project_type'] == 'fixed'){
					$active_escrow_table_name = 'fixed_budget_projects_active_escrows';
				} else if($project_data['project_type'] == 'hourly'){
					$active_escrow_table_name = 'hourly_rate_based_projects_active_escrows';
				} else if($project_data['project_type'] == 'fulltime'){
					$active_escrow_table_name = 'fulltime_projects_active_escrows';
				}
				
				if($project_data['project_type'] == 'fulltime'){
					$check_active_escrow_exists = $this->db->where(['id' => $id,'fulltime_project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'fulltime_project_id'=>$project_id])->row_array();
				} else {
					$check_active_escrow_exists = $this->db->where(['id' => $id,'project_id'=>$project_id])->from($active_escrow_table_name)->count_all_results();
					$active_escrow_data = $this->db->get_where($active_escrow_table_name, ['id' => $id,'project_id'=>$project_id])->row_array();
				}
				
				if($check_active_escrow_exists == 0){
					if($project_data['project_type'] == 'fulltime'){
						$error_message = $this->config->item('project_details_page_employee_view_request_release_invalid_escrow_fulltime_project');
					} else {
						$error_message = $this->config->item('project_details_page_sp_view_request_release_invalid_escrow_project');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
					die;
				}
				
				/* if($active_milestone_data['winner_id'] != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_validation_request_release_milestone_message')]);
					die;
				}
				 */
				
				if(!empty($active_escrow_data)){
					if($project_data['project_type'] == 'fixed'){
						$sp_requested_release_date =  date('Y-m-d H:i:s');
						$update_active_escrow_data['is_sp_requested_release'] = 'Y';
						$update_active_escrow_data['sp_requested_release_date'] =$sp_requested_release_date;
						$this->db->where ('id', $id);
						$this->db->update ($active_escrow_table_name,$update_active_escrow_data);
						$sp_notification_message = $this->config->item('fixed_budget_project_realtime_notification_message_sent_to_sp_when_sp_requested_escrow_release');
						
					} else if($project_data['project_type'] == 'hourly') {
						$sp_requested_release_date =  date('Y-m-d H:i:s');
						$update_active_escrow_data['is_sp_requested_release'] = 'Y';
						$update_active_escrow_data['sp_requested_release_date'] =$sp_requested_release_date;
						$this->db->where ('id', $id);
						$this->db->update ($active_escrow_table_name,$update_active_escrow_data);
						$sp_notification_message = $this->config->item('hourly_rate_based_project_realtime_notification_message_sent_to_sp_when_sp_requested_escrow_release');

					} else if($project_data['project_type'] == 'fulltime') {
						$sp_requested_release_date =  date('Y-m-d H:i:s');
						$update_active_escrow_data['is_employee_requested_release'] = 'Y';
						$update_active_escrow_data['employee_requested_release_date'] =$sp_requested_release_date;
						$this->db->where ('id', $id);
						$this->db->update ($active_escrow_table_name,$update_active_escrow_data);

						$sp_notification_message = $this->config->item('fulltime_project_realtime_notification_message_sent_to_employee_when_employee_requested_escrow_release');
					}
					
					$active_escrow_value = str_replace(".00","",number_format($active_escrow_data['created_escrow_amount'],  2, '.', ' '));
					$active_escrow_value = $active_escrow_value." ".CURRENCY;
					
					
					$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
					
					$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y')) ?$project_data['first_name'] . ' ' . $project_data['last_name'] :$project_data['company_name'];
				
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					
					$sp_profile_url_link = VPATH.$sp_data['profile_name'];
					
					
					
					$project_title = htmlspecialchars($project_data['project_title'], ENT_QUOTES);
					
					############# log message for create escrow request ####
					if($project_data['project_type'] == 'fixed') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_male_requested_active_escrow_release_user_activity_log_displayed_message');
								}else{	
								
								
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_male_requested_active_escrow_release_user_activity_log_displayed_message');
								}
							} else {
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_app_female_requested_active_escrow_release_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_female_requested_active_escrow_release_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fixed_budget_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						} else {
							$po_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_po_when_sp_company_requested_active_escrow_release_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fixed_budget_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						$sp_activity_log_message = $this->config->item('fixed_budget_project_message_sent_to_sp_when_sp_requested_active_escrow_release_user_activity_log_displayed_message');
						$sp_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}","{fixed_budget_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);
						
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							$po_notification_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fixed_budget_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message);
						} else {
							$po_notification_message = str_replace(array("{user_company_name}","{sp_profile_url_link}","{fixed_budget_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message);
						}	
						

						/* $po_notification_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fixed_budget_request_release_milestone_value}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message); */
					} else if($project_data['project_type'] == 'hourly') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_male_requested_active_escrow_release_user_activity_log_displayed_message');
									
								}else{
								
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_male_requested_active_escrow_release_user_activity_log_displayed_message');
								}
							} else {
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_app_female_requested_active_escrow_release_user_activity_log_displayed_message');
									
								}else{
									$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_female_requested_active_escrow_release_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{hourly_rate_based_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						} else {
							$po_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_po_when_sp_company_requested_active_escrow_release_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{hourly_rate_based_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						$sp_activity_log_message = $this->config->item('hourly_rate_based_project_message_sent_to_sp_when_sp_requested_active_escrow_release_user_activity_log_displayed_message');
						$sp_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}","{hourly_rate_based_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);

						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							$po_notification_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{hourly_rate_based_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message);
						} else {
							$po_notification_message = str_replace(array("{user_company_name}","{sp_profile_url_link}","{hourly_rate_based_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message);
						}	
					} else if($project_data['project_type'] == 'fulltime') {
						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							if($sp_data['gender'] == 'M'){
								
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_male_requested_active_escrow_release_user_activity_log_displayed_message');
								}else{	
								
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_male_requested_active_escrow_release_user_activity_log_displayed_message');
								}
							} else {
								if($sp_data['is_authorized_physical_person'] == 'Y'){
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_app_female_requested_active_escrow_release_user_activity_log_displayed_message');
								}else{	
									$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_female_requested_active_escrow_release_user_activity_log_displayed_message');
								}
							}
							$po_activity_log_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fulltime_project_request_release_escrow_value}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						} else {
							$po_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employer_when_employee_company_requested_active_escrow_release_user_activity_log_displayed_message');
							$po_activity_log_message = str_replace(array("{company_name}","{sp_profile_url_link}","{fulltime_project_request_release_escrow_value}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_activity_log_message);
						}
						$sp_activity_log_message = $this->config->item('fulltime_project_message_sent_to_employee_when_employee_requested_active_escrow_release_user_activity_log_displayed_message');
						$sp_activity_log_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}","{fulltime_project_request_release_escrow_value}","{project_url_link}","{project_title}"),array($po_name,$po_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$sp_activity_log_message);

						if(($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y')){
							$po_notification_message = str_replace(array("{user_first_name_last_name}","{sp_profile_url_link}","{fulltime_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message);
						} else {
							$po_notification_message = str_replace(array("{user_company_name}","{sp_profile_url_link}","{fulltime_project_request_release_escrow_amount}","{project_url_link}","{project_title}"),array($sp_name,$sp_profile_url_link,$active_escrow_value,$project_url_link,$project_title),$po_notification_message);
						}	
					}
					
					user_display_log($sp_activity_log_message,$sp_id); // activity log message for service provider
					
					user_display_log($po_activity_log_message,$po_id); // activity log message for project owner
					if($project_data['project_type'] == 'fulltime') {
						$escrow_request_release_message = $this->config->item('project_details_page_employee_view_fulltime_project_requested_release_escrow_message');
						$escrow_request_release_message = str_replace(array('{user_first_name_last_name_or_company_name}','{fulltime_request_release_escrow_value}','{employee_requested_release_date}'),array($po_name,$active_escrow_value,date(DATE_TIME_FORMAT,strtotime($sp_requested_release_date))),$escrow_request_release_message);
					} else {
						$escrow_request_release_message = $this->config->item('project_details_page_sp_view_project_requested_release_escrow_message');
						$escrow_request_release_message = str_replace(array('{user_first_name_last_name_or_company_name}','{requested_release_escrow_amount}','{sp_requested_release_date}'),array($po_name,$active_escrow_value,date(DATE_TIME_FORMAT,strtotime($sp_requested_release_date))),$escrow_request_release_message);
					}
					
					$sp_notification_message = str_replace(array("{user_first_name_last_name_or_company_name}","{po_profile_url_link}"),array($po_name,$po_profile_url_link),$sp_notification_message);
					
					echo json_encode(['status' => 200,'po_id'=>$po_id,'sp_msg'=>$sp_notification_message,'location'=>'','escrow_request_release_message'=>$escrow_request_release_message,'section_name'=>$section_name,'section_id'=>$section_id]);
					die;
					
				} else {
					echo json_encode ($validation_data_array);
					die;
				}
			}
		}else{

			show_custom_404_page(); //show custom 404 page
		}		
	}
	
	
	/* This function is used to update escrow tab on project detail page - Done */
	public function update_user_escrows_section_tabs_project_detail(){
	
		if($this->input->is_ajax_request ()){
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				if(Cryptor::doDecrypt($this->input->post ('session_uid')) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
					
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);	
			
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_po_view_reject_requested_escrow_deleted_project')]);
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

				$p_type = $project_type = $project_data['project_type'];

				
				$sp_data = $this->db // get the user detail
				->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,u.is_authorized_physical_person')
				->from('users u')
				->where('u.user_id', Cryptor::doDecrypt($sp_id))
				->get()->row_array();
				
				$po_data = $this->db // get the user detail
				->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name,u.is_authorized_physical_person')
				->from('users u')
				->where('u.user_id', Cryptor::doDecrypt($po_id))
				->get()->row_array();
				
				$sp_name = (($sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_data['is_authorized_physical_person'] == 'Y' )) ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
				$po_name = (($po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_data['is_authorized_physical_person'] == 'Y' )) ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
				$data['sp_name'] = $sp_name;
				$data['po_name'] = $po_name;	

				$data['project_type']= $p_type;
				$data['section_name']= $section_name;
				$data['po_id']= Cryptor::doDecrypt($po_id);
				$data['sp_id']= Cryptor::doDecrypt($sp_id);
				$data['section_id']= $section_id;
				$data['project_id']= $project_id;
				$start = 0;
				
				$dispute_condition = array('disputed_project_id'=>$project_id,'project_owner_id_of_disputed_project'=>Cryptor::doDecrypt($po_id),'sp_winner_id_of_disputed_project'=>Cryptor::doDecrypt($sp_id));
				if($p_type == 'fixed'){
					$active_dispute_table_name = 'fixed_budget_projects_active_disputes';
				}if($p_type == 'hourly'){
					$active_dispute_table_name = 'hourly_rate_based_projects_active_disputes';
				}if($p_type == 'fulltime'){
					$active_dispute_table_name = 'fulltime_projects_active_disputes';
					$dispute_condition = array('disputed_fulltime_project_id'=>$project_id,'employer_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($po_id),'employee_winner_id_of_disputed_fulltime_project'=>Cryptor::doDecrypt($sp_id));
				}
				$check_is_project_disputed =  $this->db->where($dispute_condition)->from($active_dispute_table_name)->count_all_results(); // check dsipute exists or not
				
				if($p_type == 'fulltime') {
					$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
					$active_escrow_count_project = get_active_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
					$released_escrow_count_project = get_released_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
					$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
				} else {
					$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow
					$active_escrow_count_project = get_active_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
					$released_escrow_count_project = get_released_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow
					$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
				}
				

				$total_escrow_count = $requested_escrow_count_project + $active_escrow_count_project + $released_escrow_count_project + $rejected_requested_escrow_count_project;				
				$page=1;
				
				
				if($tab_type == 'requested_escrow'){
				
					if($p_type == 'fulltime') {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					} else {
						$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						$requested_escrows_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_requested_escrow_listing_limit'));
					}
					
					$data["requested_escrows_data"] = $requested_escrows_listing_project_data['data'];
					$data['requested_escrows_count'] = $requested_escrows_listing_project_data['total'];
					
					$paginations = generate_pagination_links($requested_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_requested_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
					
					
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($requested_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					$data['generate_pagination_links_escrow'] = $paginations['links'];
					/* $data['project_type']= $p_type;
					$data['section_name']= $section_name;
					
					$data['section_id']= $section_id;
					$data['project_id']= $project_id; */
					/* $data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id); */
					
					$create_request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view');
					
					if($p_type == 'fulltime'){
						//$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_escrow_request_button_txt_employee_view');
						$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_escrow_request_button_txt_employee_view');
					}
					$data['create_request_payment_button']= $create_request_payment_button;
					$multiplication = $this->config->item('project_detail_page_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_requested_escrow_listing_limit') - count($data['requested_escrows_data'])));
					$record_per_page = count($data['requested_escrows_data']) < $this->config->item('project_detail_page_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_requested_escrow_listing_limit') * ($page - 1)) + 1;
					
					################ sum of requested/eschrow_released escrow ########
				
					if($total_escrow_count > 0){
						$initial_requested_escrow_view_status = 1;
					}else{
						$initial_requested_escrow_view_status = 0;
					}
					$data["sum_escrow_amount"] = $sum_requested_escrow_amount_project;
					echo json_encode(['status' => 200,'check_is_project_disputed'=>$check_is_project_disputed,'total_escrow_count'=>$total_escrow_count,'initial_requested_escrow_view_status'=>$initial_requested_escrow_view_status,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrows_listing_project_data['total'],'data'=>$this->load->view('escrow/requested_escrows_section_project_detail',$data, true)]);
					die;
				
				
				}
				
				if($tab_type == 'active_escrow'){
				
					if($p_type == 'fulltime') {
						$active_escrows_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_active_escrow_listing_limit'));
					} else {
						$active_escrows_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_active_escrow_listing_limit'));
					}

					$data["active_escrow_data"] = $active_escrows_listing_project_data['data'];
					$data['active_escrow_count'] = $active_escrows_listing_project_data['total'];
					
					$paginations = generate_pagination_links($active_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_active_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name]);
					
					
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($active_escrows_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					$data['generate_pagination_links_escrow'] = $paginations['links'];;
					
					
					/* $data['section_name']= $section_name;
					$data['project_type']= $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id; */
					
					$multiplication = $this->config->item('project_detail_page_active_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_active_escrow_listing_limit') - count($data['active_escrow_data'])));
					$record_per_page = count($data['active_escrow_data']) < $this->config->item('project_detail_page_active_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_active_escrow_listing_limit') * ($page - 1)) + 1;
					
					########## sum of rejected escrow for escrow rejected tab #######
					
					// When PO open the tab
					if(Cryptor::doDecrypt($po_id) == $user[0]->user_id){
						if($p_type == 'fulltime') {
							$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						} else {
							$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						}
					}
					// When SP open the tab
					if(Cryptor::doDecrypt($sp_id) == $user[0]->user_id){
						if($p_type == 'fulltime') {
							$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						} else {
							$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						}
					}
					
					
					
					$data["sum_escrow_amount"] = $sum_active_escrow_amount_project;
					
					echo json_encode(['status' => 200,'check_is_project_disputed'=>$check_is_project_disputed,'total_escrow_count'=>$total_escrow_count,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_escrows_listing_project_data['total'],'data'=>$this->load->view('escrow/active_escrows_section_project_detail',$data, true)]);
					die;
				
				}
				if($tab_type == 'cancelled_escrow'){
				
					if($p_type == 'fulltime') {
						$cancelled_escrow_listing_project_data = $this->Escrow_model->get_cancelled_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_cancelled_escrow_listing_limit'));
					} else {
						$cancelled_escrow_listing_project_data = $this->Escrow_model->get_cancelled_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_cancelled_escrow_listing_limit'));
					}
					
					$data["cancelled_escrow_data"] = $cancelled_escrow_listing_project_data['data'];
					$data['cancelled_escrow_count'] = $cancelled_escrow_listing_project_data['total'];
					
					$paginations = generate_pagination_links($cancelled_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_cancelled_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
					
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($cancelled_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_cancelled_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					$data['generate_pagination_links_escrow'] = $paginations['links'];;
					/* $data['section_name']= $section_name;
					$data['project_type']= $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id; */
					
					$multiplication = $this->config->item('project_detail_page_cancelled_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_cancelled_escrow_listing_limit') - count($data['cancelled_escrow_data'])));
					$record_per_page = count($data['cancelled_escrow_data']) < $this->config->item('project_detail_page_cancelled_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_cancelled_escrow_listing_limit') * ($page - 1)) + 1;
					
					########## sum of rejected escrow for escrow rejected tab #######
					
					// When PO open the tab
					if(Cryptor::doDecrypt($po_id) == $user[0]->user_id){
						if($p_type == 'fulltime') {
							$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						} else {
							$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						}
					}
					// When SP open the tab
					if(Cryptor::doDecrypt($sp_id) == $user[0]->user_id){
						if($p_type == 'fulltime') {
							$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						} else {
							$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						}
					
					}
					$data["sum_escrow_amount"] = $sum_cancelled_escrow_amount_project;
					
					echo json_encode(['status' => 200,'check_is_project_disputed'=>$check_is_project_disputed,'total_escrow_count'=>$total_escrow_count,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/cancelled_escrows_section_project_detail',$data, true)]);
					die;
				
				}
				
				if($tab_type == 'released_escrow'){
				
					if($p_type == 'fulltime') {
						$released_escrow_listing_project_data = $this->Escrow_model->get_released_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_paid_escrow_listing_limit'));
					} else {
						$released_escrow_listing_project_data = $this->Escrow_model->get_released_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_paid_escrow_listing_limit'));
					}
					
					
					$data["released_escrow_data"] = $released_escrow_listing_project_data['data'];
					$data['released_escrow_count'] = $released_escrow_listing_project_data['total'];
					
					$paginations = generate_pagination_links($released_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_paid_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name]);
					
					
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($released_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_paid_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					$data['generate_pagination_links_escrow'] = $paginations['links'];;
					/* $data['section_name']= $section_name;
					$data['project_type']= $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id);
					$data['section_id']= $section_id;
					$data['project_id']= $project_id; */
					
					$multiplication = $this->config->item('project_detail_page_paid_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_paid_escrow_listing_limit') - count($data['released_escrow_data'])));
					$record_per_page = count($data['released_escrow_data']) < $this->config->item('project_detail_page_paid_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_paid_escrow_listing_limit') * ($page - 1)) + 1;
					
					########## sum of released escrow for escrow released tab for sp #######
					if(Cryptor::doDecrypt($sp_id) == $user[0]->user_id){
						if($p_type == 'fulltime') {
							$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						} else {
							$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						}
					}
					########## sum of released escrow for escrow released tab for po #######
					if(Cryptor::doDecrypt($po_id) == $user[0]->user_id){
						if($p_type == 'fulltime') {
							//$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
							$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
							$sum_escrow_bussiness_fee_charges = $this->Escrow_model->get_sum_released_escrow_service_fees_charges_amount_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
						} else {
							//$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
							$sum_escrow_bussiness_fee_charges = $this->Escrow_model->get_sum_released_escrow_service_fees_charges_amount_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
							$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
						}
					  $data['sum_escrow_bussiness_fee_charges'] = $sum_escrow_bussiness_fee_charges;
					}
					$data["sum_escrow_amount"] = $sum_released_escrow_amount_project;
					
					echo json_encode(['status' => 200,'check_is_project_disputed'=>$check_is_project_disputed,'total_escrow_count'=>$total_escrow_count,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$released_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/released_escrows_section_project_detail',$data, true)]);
					die;
				
				}
				
				if($tab_type == 'rejected_requested_escrow') {
				
					if($p_type == 'fulltime') {
						$rejected_requested_escrow_listing_project_data = $this->Escrow_model->get_rejected_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'));
					} else {
						$rejected_requested_escrow_listing_project_data = $this->Escrow_model->get_rejected_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)),$start, $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'));
					}
				
					$data["rejected_requested_escrow_data"] = $rejected_requested_escrow_listing_project_data['data'];
					$data['rejected_requested_escrow_count'] = $rejected_requested_escrow_listing_project_data['total'];
					
					$paginations = generate_pagination_links($rejected_requested_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'), $this->config->item('project_detail_page_paid_escrow_listing_limit'), $this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>$po_id,'data-sp-id' =>$sp_id,'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
					
					
					/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($rejected_requested_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>Cryptor::doDecrypt($po_id),'sp_id' =>Cryptor::doDecrypt($sp_id),'project_type'=>$p_type,'bid_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
					$data['generate_pagination_links_escrow'] = $paginations['links'];
					/* $data["section_id"] = $section_id;
					$data['project_type'] = $p_type;
					$data['po_id']= Cryptor::doDecrypt($po_id);
					$data['sp_id']= Cryptor::doDecrypt($sp_id); */
					
					$multiplication = $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') - count($data['requested_escrow_data'])));
					$record_per_page = count($data['requested_escrow_data']) < $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') * ($page - 1)) + 1;
					
					########## sum of rejected escrow for escrow rejected tab #######
					if($p_type == 'fulltime') {
						$sum_rejected_requested_escrow_amount_project = $this->Escrow_model->get_sum_rejected_requested_escrow_amounts_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));
					} else {
						$sum_rejected_requested_escrow_amount_project = $this->Escrow_model->get_sum_rejected_requested_escrow_amounts_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));
					}
					$data["sum_escrow_amount"] = $sum_rejected_requested_escrow_amount_project;
					
					echo json_encode(['status' => 200,'check_is_project_disputed'=>$check_is_project_disputed,'total_escrow_count'=>$total_escrow_count,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/rejected_requested_escrows_section_project_detail',$data, true)]);
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

	/* This function will work when paging will execute on escrow - Done */
	public function load_pagination_escrows () {
		
		if($this->input->is_ajax_request ()){
		
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				if(Cryptor::doDecrypt($this->input->post ('session_uid')) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$tab_type = $this->input->post('tab_type');
				$section_name = $this->input->post('section_name');
				/* if($page != null) {
					
					if($page == 1) {
						$start = 0;
					} else {
						if($section_name == 'inprogress' || $section_name == 'completed'){
							if($tab_type == 'requested_escrow'){
								$start = ($page - 1) * $this->config->item('project_detail_page_requested_escrow_listing_limit');
							}
							if($tab_type == 'rejected_requested_escrow'){
								$start = ($page - 1) * $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit');
							}
							if($tab_type == 'active_escrow'){
								$start = ($page - 1) * $this->config->item('project_detail_page_active_escrow_listing_limit');
							}
							if($tab_type == 'cancelled_escrow'){
								$start = ($page - 1) * $this->config->item('project_detail_page_cancelled_escrow_listing_limit');
							}
							if($tab_type == 'released_escrow'){
								$start = ($page - 1) * $this->config->item('project_detail_page_paid_escrow_listing_limit');
							}
						}	
						
					}
				} else {
					$start = 0;
				} */
				$project_id = $this->input->post('project_id');
				$po_id = Cryptor::doDecrypt($this->input->post('po_id'));
				$sp_id = Cryptor::doDecrypt($this->input->post('sp_id'));


				
				$sp_data = $this->db // get the user detail
				->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name')
				->from('users u')
				->where('u.user_id', $sp_id)
				->get()->row_array();
				
				$po_data = $this->db // get the user detail
				->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name')
				->from('users u')
				->where('u.user_id', $po_id)
				->get()->row_array();
				
				$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
				$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
				$data['sp_name'] = $sp_name;
				$data['po_name'] = $po_name;
				
				$p_type = $this->input->post('p_type');
				$section_id = $this->input->post('section_id');
				
				
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.id, pd.project_type')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.id, pd.project_type')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				$data['project_type']= $p_type;
				$data['section_name']= $section_name;
				$data['po_id']= $po_id;
				$data['sp_id']= $sp_id;
				$data['project_id']= $project_id;
				$data['section_id']= $section_id;
				
				if($section_name == 'inprogress' || $section_name == 'incomplete' || $section_name == 'completed' || $section_name == 'active_dispute'){
					if($tab_type == 'requested_escrow'){
						if($p_type == 'fulltime') {
						
							$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id])->count_all_results();
							 
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
						
							$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							$requested_escrow_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
						} else {
						
							if($p_type=="fixed"){
								$requested_escrows_table = 'fixed_budget_projects_requested_escrows';
								}
							 if($p_type == "hourly"){
								$requested_escrows_table = 'hourly_rate_based_projects_requested_escrows';
							 }
							$total_record = $this->db->from($requested_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id])->count_all_results();
						
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
								
						
							$sum_requested_escrow_amount_project = $this->Escrow_model->get_sum_requested_escrows_amount_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							$requested_escrow_listing_project_data = $this->Escrow_model->get_all_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_requested_escrow_listing_limit'));
						}
						
						$data["requested_escrows_data"] = $requested_escrow_listing_project_data['data'];
						$data['requested_escrows_count'] = $requested_escrow_listing_project_data['total'];
						/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($requested_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'project_type'=>$p_type,'section_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
						$data['generate_pagination_links_escrow'] = $paginations['links'];
						$page = $paginations['current_page_no'];
						/* $data['project_type']= $p_type;
						$data['section_name']= $section_name;
						$data['po_id']= $po_id;
						$data['sp_id']= $sp_id;
						$data['project_id']= $project_id;
						$data['section_id']= $section_id; */
						$data["sum_escrow_amount"] = $sum_requested_escrow_amount_project;
						$create_request_payment_button = $this->config->item('project_details_page_project_create_escrow_request_form_create_escrow_request_button_txt_sp_view');
						if($p_type == 'fulltime'){
							$create_request_payment_button = $this->config->item('project_details_page_fulltime_project_create_escrow_request_form_create_escrow_request_button_txt_employee_view');
						}
						$data['create_request_payment_button']= $create_request_payment_button;
						
						$multiplication = $this->config->item('project_detail_page_requested_escrow_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('project_detail_page_requested_escrow_listing_limit') - count($data['requested_escrows_data'])));
						$record_per_page = count($data['requested_escrows_data']) < $this->config->item('project_detail_page_requested_escrow_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('project_detail_page_requested_escrow_listing_limit') * ($page - 1)) + 1;
						echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/requested_escrows_section_project_detail',$data, true)]);
						
					}
					if($tab_type == 'rejected_requested_escrow'){

						if($p_type == 'fulltime') {
						
							$total_record = $this->db->from('fulltime_projects_rejected_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id])->count_all_results();
							 
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
							$sum_rejected_requested_escrow_amount_project = $this->Escrow_model->get_sum_rejected_requested_escrow_amounts_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							$rejected_requested_escrow_listing_project_data = $this->Escrow_model->get_rejected_requested_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'));
						} else {
						
							if($p_type=="fixed"){
								$rejected_requested_escrows_table = 'fixed_budget_projects_rejected_requested_escrows';
								}
							 if($p_type == "hourly"){
								$rejected_requested_escrows_table = 'hourly_rate_based_projects_rejected_requested_escrows';
							 }
							$total_record = $this->db->from($rejected_requested_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id])->count_all_results();
							
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
							
						
							$sum_rejected_requested_escrow_amount_project = $this->Escrow_model->get_sum_rejected_requested_escrow_amounts_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							$rejected_requested_escrow_listing_project_data = $this->Escrow_model->get_rejected_requested_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'));
						}

						$data["rejected_requested_escrow_data"] = $rejected_requested_escrow_listing_project_data['data'];
						/* $data["section_id"] = $section_id;
						$data['section_name']= $section_name;
						$data['project_id']= $project_id;
						$data['project_type'] = $project_data['project_type']; */
						$data['rejected_requested_escrow_count'] = $rejected_requested_escrow_listing_project_data['total'];
						/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($rejected_requested_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_rejected_requested_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'project_type'=>$p_type,'section_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
						
						$data['generate_pagination_links_escrow'] = $paginations['links'];
						$page = $paginations['current_page_no'];
						
						$multiplication = $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') - count($data['rejected_requested_escrow_data'])));
						$record_per_page = count($data['rejected_requested_escrow_data']) < $this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('project_detail_page_rejected_requested_escrow_listing_limit') * ($page - 1)) + 1;
						
						########## sum of rejected escrow for escrow rejected tab #######
						$data["sum_escrow_amount"] = $sum_rejected_requested_escrow_amount_project;
					
						echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$rejected_requested_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/rejected_requested_escrows_section_project_detail',$data, true)]);
						
					}
					if($tab_type == 'active_escrow'){

						if($p_type == 'fulltime') {
						
							$total_record = $this->db->from('fulltime_projects_active_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id])->count_all_results();
							
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
							$active_escrow_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_active_escrow_listing_limit'));
						} else {
						
							if($p_type=="fixed"){
								$active_escrows_table = 'fixed_budget_projects_active_escrows';
							}
							if($p_type == "hourly"){
								$active_escrows_table = 'hourly_rate_based_projects_active_escrows';
							}
							$total_record = $this->db->from($active_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id])->count_all_results();
							
							
							
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
							$active_escrow_listing_project_data = $this->Escrow_model->get_active_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_active_escrow_listing_limit'));
						}
					
						$data["active_escrow_data"] = $active_escrow_listing_project_data['data'];
						/* $data["section_id"] = $section_id;
						$data['section_name']= $section_name;
						$data['project_id']= $project_id;
						$data['project_type'] = $project_data['project_type']; */
						$data['active_escrow_count'] = $active_escrow_listing_project_data['total'];
						/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($active_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_active_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'project_type'=>$p_type,'section_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
						$data['generate_pagination_links_escrow'] = $paginations['links'];
						$page = $paginations['current_page_no'];
						
						$multiplication = $this->config->item('project_detail_page_active_escrow_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('project_detail_page_active_escrow_listing_limit') - count($data['active_escrow_data'])));
						
						$record_per_page = count($data['active_escrow_data']) < $this->config->item('project_detail_page_active_escrow_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('project_detail_page_active_escrow_listing_limit') * ($page - 1)) + 1;
						
						########## sum of active escrow for escrow escrow tab #######
						// for PO
						if($po_id == $user[0]->user_id){
							if($p_type == 'fulltime') {
								$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							} else {
								$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							}
						}
						// for sp
						if($sp_id == $user[0]->user_id){
							if($p_type == 'fulltime') {
								$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							} else {
								$sum_active_escrow_amount_project = $this->Escrow_model->get_sum_active_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							}
						}
						$sp_data = $this->db // get the user detail
						->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name')
						->from('users u')
						->where('u.user_id', $sp_id)
						->get()->row_array();
						
						$po_data = $this->db // get the user detail
						->select('u.profile_name,u.gender,u.first_name,u.last_name,u.company_name,u.account_type,u.profile_name')
						->from('users u')
						->where('u.user_id', $po_id)
						->get()->row_array();
						
						$sp_name = $sp_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$sp_data['first_name'] . ' ' . $sp_data['last_name'] :$sp_data['company_name'];
						$po_name = $po_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?$po_data['first_name'] . ' ' . $po_data['last_name'] :$po_data['company_name'];
						$data['sp_name'] = $sp_name;
						$data['po_name'] = $po_name;
						$data["sum_escrow_amount"] = $sum_active_escrow_amount_project;
						echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/active_escrows_section_project_detail',$data, true)]);
						
					}
					if($tab_type == 'cancelled_escrow'){
						if($p_type == 'fulltime') {
						
						
							$total_record = $this->db->from('fulltime_projects_cancelled_escrows_tracking')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id])->count_all_results();
							 
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_cancelled_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
						
							$cancelled_escrow_listing_project_data = $this->Escrow_model->get_cancelled_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_cancelled_escrow_listing_limit'));
						} else {
							
							if($p_type=="fixed"){
								$cancelled_escrows_table = 'fixed_budget_projects_cancelled_escrows_tracking';
								$cancelled_escrow_closed_dispute_reverted_po_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
								}
							 if($p_type == "hourly"){
								$cancelled_escrows_table = 'hourly_rate_based_projects_cancelled_escrows_tracking';
								$cancelled_escrow_closed_dispute_reverted_po_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';	
							 }
							$total_record = $this->db->from($cancelled_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id])->count_all_results();

							$total_record+=$this->db->from($cancelled_escrow_closed_dispute_reverted_po_table)->where(['disputed_project_id'=>$project_id,'po_id'=>$po_id,'sp_id'=>$sp_id])->count_all_results();

							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_cancelled_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
						
							$cancelled_escrow_listing_project_data = $this->Escrow_model->get_cancelled_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_cancelled_escrow_listing_limit'));
						}
					
						$data["cancelled_escrow_data"] = $cancelled_escrow_listing_project_data['data'];
						/* $data["section_id"] = $section_id;
						$data['section_name']= $section_name;
						$data['project_id']= $project_id;
						$data['project_type'] = $project_data['project_type']; */
						$data['cancelled_escrow_count'] = $cancelled_escrow_listing_project_data['total'];
						/* $data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($cancelled_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_cancelled_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'project_type'=>$p_type,'section_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
						
						$data['generate_pagination_links_escrow'] = $paginations['links'];
						$page = $paginations['current_page_no'];
						
						
						$multiplication = $this->config->item('project_detail_page_cancelled_escrow_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('project_detail_page_cancelled_escrow_listing_limit') - count($data['cancelled_escrow_data'])));
						
						$record_per_page = count($data['cancelled_escrow_data']) < $this->config->item('project_detail_page_cancelled_escrow_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('project_detail_page_cancelled_escrow_listing_limit') * ($page - 1)) + 1;
						
						########## sum of active escrow for escrow escrow tab #######
						// for PO
						if($po_id == $user[0]->user_id){
							if($p_type == 'fulltime') {
								$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							} else {
								$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							}
						}
						// for sp
						if($sp_id == $user[0]->user_id){
							if($p_type == 'fulltime') {
								$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							} else {
								$sum_cancelled_escrow_amount_project = $this->Escrow_model->get_sum_cancelled_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							}
						}
						$data["sum_escrow_amount"] = $sum_cancelled_escrow_amount_project;
						echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$cancelled_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/cancelled_escrows_section_project_detail',$data, true)]);
						
					}
					if($tab_type == 'released_escrow'){

						if($p_type == 'fulltime') {
						
						
							$total_record = $this->db->from('fulltime_projects_released_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id])->count_all_results();
							 
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_paid_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
						
						
							$released_escrow_listing_project_data = $this->Escrow_model->get_released_escrows_listing_project($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_paid_escrow_listing_limit'));
						} else {
						
						
							if($p_type=="fixed"){
								$released_escrows_table = 'fixed_budget_projects_released_escrows';
								}
							 if($p_type == "hourly"){
								$released_escrows_table = 'hourly_rate_based_projects_released_escrows';
							 }
							$total_record = $this->db->from($released_escrows_table)->where(['project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id])->count_all_results();
							
							
							$paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_paid_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$section_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]);
							
							$released_escrow_listing_project_data = $this->Escrow_model->get_released_escrows_listing_project($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id),$paginations['offset'], $this->config->item('project_detail_page_paid_escrow_listing_limit'));
						}
					
						$data["released_escrow_data"] = $released_escrow_listing_project_data['data'];
						/* $data["section_id"] = $section_id;
						$data['section_name']= $section_name;
						$data['project_id']= $project_id;
						$data['project_type'] = $project_data['project_type']; */
						$data['released_escrow_count'] = $released_escrow_listing_project_data['total'];
					/* 	$data['generate_pagination_links_escrow'] = $this->Escrow_model->generate_pagination_links_escrow($released_escrow_listing_project_data['total'], $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_paid_escrow_listing_limit'),array('project_id' =>$project_id,'po_id' =>$po_id,'sp_id' =>$sp_id,'project_type'=>$p_type,'section_id'=>$section_id,'tab_type'=>$tab_type,'section_name'=>$section_name )); */
						$data['generate_pagination_links_escrow'] = $paginations['links'];
						$page = $paginations['current_page_no'];
						
						
						$multiplication = $this->config->item('project_detail_page_paid_escrow_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('project_detail_page_paid_escrow_listing_limit') - count($data['released_escrow_data'])));
						$record_per_page = count($data['released_escrow_data']) < $this->config->item('project_detail_page_paid_escrow_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('project_detail_page_paid_escrow_listing_limit') * ($page - 1)) + 1;
						
						########## sum of active escrow for escrow tab #######
						// for PO
						if($po_id == $user[0]->user_id){
						
							if($p_type == 'fulltime') {
								$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
								$sum_escrow_bussiness_fee_charges = $this->Escrow_model->get_sum_released_escrow_service_fees_charges_amount_project_po($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							} else {
								$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
								$sum_escrow_bussiness_fee_charges = $this->Escrow_model->get_sum_released_escrow_service_fees_charges_amount_project_po($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							}
							$data['sum_escrow_bussiness_fee_charges'] = $sum_escrow_bussiness_fee_charges;
							
						}
						// for SP
						if($sp_id == $user[0]->user_id){
							if($p_type == 'fulltime') {
								$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($p_type,array('fulltime_project_id'=>$project_id,'employer_id'=>$po_id,'employee_id'=>$sp_id));
							} else {
								$sum_released_escrow_amount_project = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($p_type,array('project_id'=>$project_id,'project_owner_id'=>$po_id,'winner_id'=>$sp_id));
							}
						}
						
						$data["sum_escrow_amount"] = $sum_released_escrow_amount_project;
						$data["po_id"] = $po_id;
						$data["sp_id"] = $sp_id;
						echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$released_escrow_listing_project_data['total'],'data'=>$this->load->view('escrow/released_escrows_section_project_detail',$data, true)]);
						
					}
				}
				die;
				
				
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	public function check_sp_po_escrows_count(){
	
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_id'))){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->post ('project_id');
			if(check_session_validity()){ 
				
				
				$id = $this->input->post ('id');
				$po_id = $this->input->post ('po_id');
				$sp_id = $this->input->post ('sp_id');
				$user = $this->session->userdata ('user');
				$tab_type = $this->input->post ('tab_type');
				$section_id = $this->input->post ('section_id');
				$section_name = $this->input->post ('section_name');
				
				
				
				if(Cryptor::doDecrypt($sp_id) != $user[0]->user_id && Cryptor::doDecrypt($po_id) != $user[0]->user_id){
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('project_details_page_sp_view_cancel_requested_escrow_deleted_project')]);
					die;
				}

				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.id, pd.project_type')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
				} else {
					$project_data = $this->db // get the user detail
					->select('pd.id, pd.project_type')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				$project_type = $project_data['project_type'];
				
				if($project_type == 'fulltime') {
					$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow

					$active_escrow_count_project = get_active_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow

					$released_escrow_count_project = get_released_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow

					$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('fulltime_project_id'=>$project_id,'employer_id'=>Cryptor::doDecrypt($po_id),'employee_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
				} else {
					$requested_escrow_count_project = get_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the requested escrow

					$active_escrow_count_project = get_active_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow

					$released_escrow_count_project = get_released_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id))); // count the active escrow

					$rejected_requested_escrow_count_project = get_rejected_requested_escrows_count_project($project_type,array('project_id'=>$project_id,'project_owner_id'=>Cryptor::doDecrypt($po_id),'winner_id'=>Cryptor::doDecrypt($sp_id)));// count the rejected requested escrow
				}

				$total_escrow_count = $requested_escrow_count_project + $active_escrow_count_project + $released_escrow_count_project+$rejected_requested_escrow_count_project;
				if($total_escrow_count > 0){
					$initial_escrow_view_status = 1;
				}else{
					$initial_escrow_view_status = 0;
				}
				$msg['status'] = 200;
				$msg['initial_escrow_view_status'] = $initial_escrow_view_status;
				echo json_encode($msg);
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
}
?>