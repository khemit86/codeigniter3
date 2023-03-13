<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class User_projects_payments_overview extends MX_Controller
{
    public function __construct ()
    {
        /* $this->load->helper ('recaptcha'); */
        $this->load->library ('pagination');
        $this->load->model ('User_projects_payments_overview_model');
        parent::__construct ();
        
    }

	//payments_management page user_projects_payments_overview_page_url
	public function user_projects_payments_overview() { 
		if(!$this->session->userdata('user')) {
				redirect(base_url());
		}
		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			$user_id = $user[0]->user_id;
			$data['current_page'] = 'user-projects-payments-overview';
			########## set the profile title meta tag and meta description  start here #########
			
			 $user_detail = $this->db // get the user detail
			->select('u.user_id, u.account_type, u.first_name, u.last_name, u.company_name, u.is_authorized_physical_person')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
            $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE &&  $user_detail['is_authorized_physical_person'] =='Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
			$title_meta_tag = $this->config->item('user_projects_payments_overview_page_title_meta_tag');
			$title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
			$description_meta_tag = $this->config->item('user_projects_payments_overview_page_description_meta_tag');
			$description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
			$data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';

			########## set the profile title tag start end #########
			$this->layout->view ('user_projects_payments_overview', '', $data, 'include');
		}
	}
	
	
	/* This function is used to po/sp view on user projects payment overview page - Done */
	public function update_projects_payments_overview_sp_po_initial_view (){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if(Cryptor::doDecrypt($this->input->post ('uid')) != $user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$data_target = $this->input->post ('data_target');
				if($data_target == 'project_owner_container'){
					$po_published_projects_count = $this->User_projects_payments_overview_model->get_po_published_projects_count($user_id);
					$po_all_projects_created_escrows_count = $this->User_projects_payments_overview_model->get_po_all_projects_created_escrows_count($user_id);
					if($po_all_projects_created_escrows_count > 0){
						$get_po_all_projects_dropdown_list = $this->User_projects_payments_overview_model->get_po_all_projects_dropdown_list($user_id);
						 $po_all_projects_dropdown_list  = array_unique($get_po_all_projects_dropdown_list['data'],SORT_REGULAR);
						 $po_all_projects_dropdown_list_count  = $get_po_all_projects_dropdown_list['total'];
						$data["po_all_projects_dropdown_list"] = $po_all_projects_dropdown_list;
						$data["po_all_projects_dropdown_list_count"] = $po_all_projects_dropdown_list_count;
					}
					$data["po_published_projects_count"] = $po_published_projects_count;
					$data["po_all_projects_created_escrows_count"] = $po_all_projects_created_escrows_count;
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_po_view',$data, true)]);
					die;
				}
				if($data_target == 'service_provider_container'){
				
					$sp_projects_count = $this->User_projects_payments_overview_model->get_sp_all_projects_count($user_id);
					$sp_all_projects_escrows_count = $this->User_projects_payments_overview_model->get_sp_all_projects_created_escrows_count($user_id);
					if($sp_all_projects_escrows_count > 0){
						$get_sp_all_projects_dropdown_list = $this->User_projects_payments_overview_model->get_sp_all_projects_dropdown_list($user_id);
						$sp_all_projects_dropdown_list  = array_unique($get_sp_all_projects_dropdown_list['data'],SORT_REGULAR);
						 $sp_all_projects_dropdown_list_count  = $get_sp_all_projects_dropdown_list['total'];
						$data["sp_all_projects_dropdown_list"] = $sp_all_projects_dropdown_list;
						$data["sp_all_projects_dropdown_list_count"] = $sp_all_projects_dropdown_list_count;
					}
					$data["sp_projects_count"] = $sp_projects_count;
					$data["sp_all_projects_escrows_count"] = $sp_all_projects_escrows_count;
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_sp_view',$data, true)]);
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
	
	
	/* This function is used to update the all escrow tabs for sp/po - Done */
	public function update_user_projects_payments_overview_section_tabs_data(){
	
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$user_id = $user[0]->user_id;
				if(Cryptor::doDecrypt($this->input->post ('uid')) != $user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				
				$view_type = $this->input->post ('view_type');
				$tab_type = $this->input->post ('tab_type');
				$project_type = '';
				if($this->input->post ('project_type') && !empty($this->input->post ('project_type'))){
					$project_type = $this->input->post ('project_type');
				}
				
				$project_id = $this->input->post ('project_id');
				$start = 0;
				$data["view_type"] = $view_type;
				if($tab_type == 'requested_escrows'){
					if($view_type == 'po'){
						$sum_all_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_requested_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_requested_escrows_listing_all_projects_po($user_id,$start, $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					if($view_type == 'sp'){
						$sum_all_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_requested_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_requested_escrows_listing_all_projects_sp($user_id,$start, $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					
					$data["requested_escrows_listing_project_data"] = $requested_escrows_listing_project_data['data'];
					$data['requested_escrows_listing_project_data_count'] = $requested_escrows_listing_project_data['total'];
					$data['sum_all_requested_escrows_amount_all_projects'] = $sum_all_requested_escrows_amount_all_projects;
					$data['project_id'] = $project_id;
					$data['project_type'] = $project_type;
					
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($requested_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'));  */
					
					$paginations = generate_pagination_links($requested_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					
					
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_requested_payments_section',$data, true)]);
					die;
				}
				if($tab_type == 'active_escrows'){
					if($view_type == 'po'){
						$sum_all_active_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_active_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$active_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_active_escrows_listing_all_projects_po($user_id,$start, $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					if($view_type == 'sp'){
						$sum_all_active_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_active_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						
						$active_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_active_escrows_listing_all_projects_sp($user_id,$start, $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'),array('project_id'=>$project_id));
						
					}
					
					$data["active_escrows_listing_project_data"] = $active_escrows_listing_project_data['data'];
					$data['active_escrows_listing_project_data_count'] = $active_escrows_listing_project_data['total'];
					$data['sum_all_active_escrows_amount_all_projects'] = $sum_all_active_escrows_amount_all_projects;
					$data['project_id'] = $project_id;
					$data['project_type'] = $project_type;
					
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($active_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'));  */
					
					$paginations = generate_pagination_links($active_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					
					
					
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_active_escrows_section',$data, true)]);
					die;
				}
				if($tab_type == 'cancelled_escrows'){
					if($view_type == 'po'){
						$sum_all_cancelled_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_cancelled_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$cancelled_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_cancelled_escrows_listing_all_projects_po($user_id,$start, $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					if($view_type == 'sp'){
						$sum_all_cancelled_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_cancelled_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$cancelled_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_cancelled_escrows_listing_all_projects_sp($user_id,$start, $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					$data["cancelled_escrows_listing_project_data"] = $cancelled_escrows_listing_project_data['data'];
					$data['cancelled_escrows_listing_project_data_count'] = $cancelled_escrows_listing_project_data['total'];
					$data['sum_all_cancelled_escrows_amount_all_projects'] = $sum_all_cancelled_escrows_amount_all_projects;
					$data['project_id'] = $project_id;
					$data['project_type'] = $project_type;
					
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($cancelled_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'));  */
					
					$paginations = generate_pagination_links($cancelled_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					
					
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_cancelled_escrows_section',$data, true)]);
					die;
				}
				if($tab_type == 'released_escrows'){
					if($view_type == 'po'){
						$sum_all_released_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_released_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$released_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_released_escrows_listing_all_projects_po($user_id,$start, $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					if($view_type == 'sp'){
						$sum_all_released_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_released_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$released_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_released_escrows_listing_all_projects_sp($user_id,$start, $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					$data["released_escrows_listing_project_data"] = $released_escrows_listing_project_data['data'];
					$data['released_escrows_listing_project_data_count'] = $released_escrows_listing_project_data['total'];
					$data['sum_all_released_escrows_amount_all_projects'] = $sum_all_released_escrows_amount_all_projects;
					$data['project_id'] = $project_id;
					$data['project_type'] = $project_type;
					
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($released_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'));  */
					
					$paginations = generate_pagination_links($released_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_released_escrows_section',$data, true)]);
					die;
				}
				if($tab_type == 'rejected_requested_escrows'){
					if($view_type == 'po'){
						$sum_all_rejected_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_rejected_requested_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$rejected_requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_rejected_requested_escrows_listing_all_projects_po($user_id,$start, $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					if($view_type == 'sp'){
						$sum_all_rejected_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_rejected_requested_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$rejected_requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_rejected_requested_escrows_listing_all_projects_sp($user_id,$start, $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					$data["rejected_requested_escrows_listing_project_data"] = $rejected_requested_escrows_listing_project_data['data'];
					$data['rejected_requested_escrows_listing_project_data_count'] = $rejected_requested_escrows_listing_project_data['total'];
					$data['sum_all_rejected_requested_escrows_amount_all_projects'] = $sum_all_rejected_requested_escrows_amount_all_projects;
					$data['project_id'] = $project_id;
					$data['project_type'] = $project_type;
					
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($rejected_requested_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'));  */
					
					$paginations = generate_pagination_links($rejected_requested_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					
					echo json_encode(['status' => 200,'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_rejected_requested_payments_section',$data, true)]);
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
	
	
	/* This function will work when paging will execute on user projects payment overview page - Done */
	public function load_user_projects_payments_overview_section_tabs_next_page_data () {
		if($this->input->is_ajax_request ()){
		
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				if(Cryptor::doDecrypt($this->input->post ('uid')) != $user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$tab_type = $this->input->post('tab_type');
				$view_type = $this->input->post ('view_type');
				$project_id = $this->input->post ('project_id');
				$data["view_type"] = $view_type;
				if($tab_type == 'requested_escrows'){
					if($view_type == 'po'){
					
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_requested_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_requested_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
						}else{
						
							$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_requested_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_requested_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
					
						$sum_all_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_requested_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_requested_escrows_listing_all_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					if($view_type == 'sp'){
					
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_requested_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_requested_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
						}else{
						
							$total_record = $this->db->from('fulltime_projects_requested_escrows')->where(['employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_requested_escrows')->where(['winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_requested_escrows')->where(['winner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					
					
						$sum_all_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_requested_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_requested_escrows_listing_all_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					}
					
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
							 
					/* $paginations = generate_pagination_links($total_record, $this->config->item('project_detail_page_payments_section_paging_url'),$this->config->item('project_detail_page_requested_escrow_listing_limit'),$this->config->item('project_detail_page_escrow_number_of_pagination_links'),'',['data-project-id' =>$project_id,'data-po-id' =>Cryptor::doEncrypt($po_id),'data-sp-id' =>Cryptor::doEncrypt($sp_id),'data-p-type'=>$p_type,'data-section-id'=>$bid_id,'data-tab-type'=>$tab_type,'data-section-name'=>$section_name ]); */
					
						
					$data["requested_escrows_listing_project_data"] = $requested_escrows_listing_project_data['data'];
					$data['requested_escrows_listing_project_data_count'] = $requested_escrows_listing_project_data['total'];
					$data['sum_all_requested_escrows_amount_all_projects'] = $sum_all_requested_escrows_amount_all_projects;
					
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($requested_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit'));  */
					$multiplication = $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit') - count($data['requested_escrows_listing_project_data'])));
					$record_per_page = count($data['requested_escrows_listing_project_data']) < $this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_payments_overview_page_requested_escrow_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$requested_escrows_listing_project_data['total'],'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_requested_payments_section',$data, true)]);
					die;
						
					
				}
				if($tab_type == 'active_escrows'){
					if($view_type == 'po'){
					
						if($project_id != 0){
						
							$total_record = $this->db->from('fulltime_projects_active_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_active_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_active_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
						}else{

							$total_record = $this->db->from('fulltime_projects_active_escrows')->where(['employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_active_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_active_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
					
						$sum_all_active_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_active_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$active_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_active_escrows_listing_all_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					if($view_type == 'sp'){
					
						if($project_id != 0){
						
							$total_record = $this->db->from('fulltime_projects_active_escrows')->where(['fulltime_project_id'=>$project_id,'employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_active_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_active_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
						}else{

							$total_record = $this->db->from('fulltime_projects_active_escrows')->where(['employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_active_escrows')->where(['winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_active_escrows')->where(['winner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						$sum_all_active_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_active_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$active_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_active_escrows_listing_all_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					
					
					$data["active_escrows_listing_project_data"] = $active_escrows_listing_project_data['data'];
					$data['active_escrows_listing_project_data_count'] = $active_escrows_listing_project_data['total'];
					$data['sum_all_active_escrows_amount_all_projects'] = $sum_all_active_escrows_amount_all_projects;
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($active_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit')); */ 
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit') - count($data['active_escrows_listing_project_data'])));
					$record_per_page = count($data['active_escrows_listing_project_data']) < $this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_payments_overview_page_active_escrow_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_escrows_listing_project_data['total'],'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_active_escrows_section',$data, true)]);
					die;
				}	
				
				if($tab_type == 'cancelled_escrows'){
					if($view_type == 'po'){
						
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_cancelled_escrows_tracking')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_cancelled_escrows_tracking')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
							
							$total_record += $this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts')->where(['disputed_project_id'=>$project_id,'po_id'=>$user_id])->count_all_results();
							
							$total_record += $this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts')->where(['disputed_project_id'=>$project_id,'po_id'=>$user_id])->count_all_results();
							
							$total_record += $this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts')->where(['disputed_fulltime_project_id'=>$project_id,'employer_id'=>$user_id])->count_all_results();
							
						}else{

							$total_record = $this->db->from('fulltime_projects_cancelled_escrows_tracking')->where(['employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_cancelled_escrows_tracking')->where(['project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking')->where(['project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts')->where(['po_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts')->where(['po_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts')->where(['employer_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						$sum_all_cancelled_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_cancelled_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$cancelled_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_cancelled_escrows_listing_all_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					if($view_type == 'sp'){
						
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_cancelled_escrows_tracking')->where(['fulltime_project_id'=>$project_id,'employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_cancelled_escrows_tracking')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
							
							$total_record += $this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts')->where(['disputed_project_id'=>$project_id,'sp_id'=>$user_id])->count_all_results();
							
							$total_record += $this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts')->where(['disputed_project_id'=>$project_id,'sp_id'=>$user_id])->count_all_results();
							
							$total_record += $this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts')->where(['disputed_fulltime_project_id'=>$project_id,'employee_id'=>$user_id])->count_all_results();
							
							
							
						}else{

							$total_record = $this->db->from('fulltime_projects_cancelled_escrows_tracking')->where(['employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_cancelled_escrows_tracking')->where(['winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking')->where(['winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts')->where(['sp_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts')->where(['sp_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts')->where(['employee_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						$sum_all_cancelled_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_cancelled_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$cancelled_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_cancelled_escrows_listing_all_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					$data["cancelled_escrows_listing_project_data"] = $cancelled_escrows_listing_project_data['data'];
					$data['cancelled_escrows_listing_project_data_count'] = $cancelled_escrows_listing_project_data['total'];
					$data['sum_all_cancelled_escrows_amount_all_projects'] = $sum_all_cancelled_escrows_amount_all_projects;
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($cancelled_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit'));  */
					
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit') - count($data['cancelled_escrows_listing_project_data'])));
					$record_per_page = count($data['cancelled_escrows_listing_project_data']) < $this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_payments_overview_page_cancelled_escrow_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$cancelled_escrows_listing_project_data['total'],'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_cancelled_escrows_section',$data, true)]);
					die;
				}
				if($tab_type == 'released_escrows'){
					if($view_type == 'po'){
						
						
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_released_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_released_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_released_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
						}else{

							$total_record = $this->db->from('fulltime_projects_released_escrows')->where(['employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_released_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_released_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						$sum_all_released_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_released_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$released_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_released_escrows_listing_all_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					if($view_type == 'sp'){
						
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_released_escrows')->where(['fulltime_project_id'=>$project_id,'employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_released_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_released_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
						}else{

							$total_record = $this->db->from('fulltime_projects_released_escrows')->where(['employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_released_escrows')->where(['winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_released_escrows')->where(['winner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						
						$sum_all_released_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_released_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$released_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_released_escrows_listing_all_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					$data["released_escrows_listing_project_data"] = $released_escrows_listing_project_data['data'];
					$data['released_escrows_listing_project_data_count'] = $released_escrows_listing_project_data['total'];
					$data['sum_all_released_escrows_amount_all_projects'] = $sum_all_released_escrows_amount_all_projects;
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($released_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit'));  */
					
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit') - count($data['released_escrows_listing_project_data'])));
					$record_per_page = count($data['released_escrows_listing_project_data']) < $this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_payments_overview_page_released_escrow_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$released_escrows_listing_project_data['total'],'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_released_escrows_section',$data, true)]);
					die;
				}
				if($tab_type == 'rejected_requested_escrows'){
					if($view_type == 'po'){
						
						
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_rejected_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_rejected_requested_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_rejected_requested_escrows')->where(['project_id'=>$project_id,'project_owner_id'=>$user_id])->count_all_results();
						}else{

							$total_record = $this->db->from('fulltime_projects_rejected_requested_escrows')->where(['employer_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_rejected_requested_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_rejected_requested_escrows')->where(['project_owner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						$sum_all_rejected_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_rejected_requested_escrows_amount_all_projects_po($user_id,array('project_id'=>$project_id));
						$rejected_requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_rejected_requested_escrows_listing_all_projects_po($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					if($view_type == 'sp'){
						
						if($project_id != 0){
							$total_record = $this->db->from('fulltime_projects_rejected_requested_escrows')->where(['fulltime_project_id'=>$project_id,'employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_rejected_requested_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_rejected_requested_escrows')->where(['project_id'=>$project_id,'winner_id'=>$user_id])->count_all_results();
						}else{

							$total_record = $this->db->from('fulltime_projects_rejected_requested_escrows')->where(['employee_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('fixed_budget_projects_rejected_requested_escrows')->where(['winner_id'=>$user_id])->count_all_results();
							$total_record += $this->db->from('hourly_rate_based_projects_rejected_requested_escrows')->where(['winner_id'=>$user_id])->count_all_results();
						}
						
						$paginations = generate_pagination_links($total_record, $this->config->item('user_projects_payments_overview_page_paging_url'), $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'), $this->config->item('user_projects_payments_overview_page_number_of_pagination_links'));
						
						$sum_all_rejected_requested_escrows_amount_all_projects = $this->User_projects_payments_overview_model->get_sum_all_rejected_requested_escrows_amount_all_projects_sp($user_id,array('project_id'=>$project_id));
						$rejected_requested_escrows_listing_project_data = $this->User_projects_payments_overview_model->get_all_rejected_requested_escrows_listing_all_projects_sp($user_id,$paginations['offset'], $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'),array('project_id'=>$project_id));
					
					}
					$data["rejected_requested_escrows_listing_project_data"] = $rejected_requested_escrows_listing_project_data['data'];
					$data['rejected_requested_escrows_listing_project_data_count'] = $rejected_requested_escrows_listing_project_data['total'];
					$data['sum_all_rejected_requested_escrows_amount_all_projects'] = $sum_all_rejected_requested_escrows_amount_all_projects;
					/* $data['generate_pagination_links_user_projects_payments_overview'] = $this->User_projects_payments_overview_model->generate_pagination_links_user_projects_payments_overview($rejected_requested_escrows_listing_project_data['total'], $this->config->item('user_projects_payments_overview_page_paging_url'),$this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit'));  */
					
					$data['generate_pagination_links_user_projects_payments_overview'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit') - count($data['rejected_requested_escrows_listing_project_data'])));
					$record_per_page = count($data['rejected_requested_escrows_listing_project_data']) < $this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('user_projects_payments_overview_page_rejected_requested_escrow_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$rejected_requested_escrows_listing_project_data['total'],'data'=>$this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_rejected_requested_payments_section',$data, true)]);
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