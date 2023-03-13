<?php
if ( ! defined ('BASEPATH')) {
	exit ('No direct script access allowed');
}

class Find_professionals extends MX_Controller {

	/**
	 * Description: this used for check the user is exsts or not if exists then it redirect to this site
	 * Paremete: username and password
	 */
	public function __construct () {
		$this->load->helper('text');
		$this->load->library ('pagination');
		$this->load->library ('form_validation');
		$this->load->model('Find_professionals_model');
		parent::__construct ();
	}
	
	public function index(){
		
		$data['categories'] = $this->Find_professionals_model->get_category_tree();
		$limit = $this->config->item('find_professionals_listing_limit_per_page');
		$num_pagination_links = $this->config->item('find_professionals_number_of_pagination_links');
		if($this->session->userdata('filter_arr') != null || $this->session->userdata('page') != null) {

			$this->db->from('users u');
			$this->db->join('users_profile_base_information ubi', 'ubi.user_id = u.user_id', 'left');
			$this->db->where("IFNULL(ubi.description, '') != ''");
			$this->db->where("IFNULL(ubi.headline, '') != ''"); 
			$total_users = $this->db->count_all_results();

			if($total_users != 0) {
				$filter_arr = $this->session->userdata('filter_arr');
				$page = $this->session->userdata('page');
				$this->session->unset_userdata('page');
			} else {
				$filter_arr = [];
        $this->session->unset_userdata('page');
        $this->session->unset_userdata('filter_arr');
			}
			
			if($page != null) {
				if($page == 1) {
						$start = 0;
				} else {
						$start = ($page - 1) * $limit;
				}
			} else {
					$start = 0;
			}
			$data['page'] = $page;
			$professionals = $this->Find_professionals_model->get_all_professionals_based_on_filter($filter_arr, $start, $limit);
			$data['professionals'] = $professionals['data'];
			$data['professionals_count'] = $professionals['total'];
			$pagination = generate_pagination_links($professionals['total'], $this->config->item('find_professionals_page_url'),$limit, $num_pagination_links, $page);
			$data['pagination_links'] = $pagination['links'];
			$multiplication = $limit * $page;
			$subtraction = ($multiplication - ($limit - count($professionals['data'])));
			
			$data['record_per_page'] = count($professionals['data']) < $limit ? $subtraction : $multiplication;
			$data['page_no'] = ($limit * ($page - 1)) + 1;
		} else {
			
				########## fetch data for find professional ####
		
				$professionals = $this->Find_professionals_model->get_all_professionals(0, $limit);
				// $data['professionals'] = [];
				// $data['professionals_count'] = 0;
				$data['professionals'] = $professionals['data'];
				$data['professionals_count'] = $professionals['total'];
				$data['page_no'] = 1;
				$pagination = generate_pagination_links($professionals['total'], $this->config->item('find_professionals_page_url'),$limit, $num_pagination_links);
				$data['pagination_links'] = $pagination['links'];
				########## fetch data for find professional end #####
				
		}
				
		$data['current_page'] = 'find_professionals';
		########## set the title meta tag and meta description  start here #########
		$find_professionals_title_meta_tag = $this->config->item('find_professionals_page_title_meta_tag');
		$find_professionals_description_meta_tag = $this->config->item('find_professionals_page_description_meta_tag');
		// $this->session->set_userdata('share_title_short',$find_professionals_title_meta_tag);
		// $this->session->set_userdata('share_description',$find_professionals_description_meta_tag);
		$data['meta_tag'] = '<title>' . $find_professionals_title_meta_tag . '</title><meta name="description" content="' . $find_professionals_description_meta_tag . '"/>';
		########## set the title tag start end #########
		$data['locations'] = $this->Find_professionals_model->get_all_county_locality_to_display('');
		
		$data['hourly_rate_range'] = $this->Find_professionals_model->get_find_professionals_hourly_rate_ranges();

		if(check_session_validity()) {
			$user = $this->session->userdata('user');
			$data['user_additional_settings'] = $this->db->get_where('users_additional_accesses_settings', ['user_id' => $user[0]->user_id])->row_array(); 
			
			$this->layout->view ('find_professionals_logged_in', '', $data, 'include');
		} else {
			$this->layout->view ('find_professionals_logged_off', '', $data, 'include');
		}
	}
	// This method is used to get filter critera from find professional page and based on those critera it filtered professionals and send updated listing back to find professional page
	public function ajax_update_listing_based_filter() { 
		if(!$this->input->is_ajax_request ()){ 
		   show_custom_404_page();
			return;
		 }
		$filter_arr = $this->input->post();
		$find_professional_page_url = $this->config->item('find_professionals_page_url');
		$per_page_limit = $this->config->item('find_professionals_listing_limit_per_page');
		$num_pagination_links = $this->config->item('find_professionals_number_of_pagination_links');
		$page = $this->uri->segment(3);
			if(!check_session_validity()) {

				if(!empty($filter_arr['user_registration_time'])) {
					unset($filter_arr['user_registration_time']);
				} 
				if(!empty($filter_arr['profile_last_update_time'])) {
					unset($filter_arr['profile_last_update_time']);
				} 


				$this->session->set_userdata('filter_arr',$filter_arr);
				$this->session->set_userdata('page',$page);
				echo json_encode(['status' => 404]);
				return;
			}
			$user = $this->session->userdata('user');
			if($filter_arr['user_id'] != $user[0]->user_id) {
				echo json_encode(['status' => 404]);
				return;  
			}
			if($page != null) {
				if($page == 1) {
					$start = 0;
				} else {
					$start = ($page - 1) * $per_page_limit;
				}
			} else {
				$start = 0;
			}
			$professionals = $this->Find_professionals_model->get_all_professionals_based_on_filter($filter_arr, 0, $per_page_limit);
			$total_user_profile_count = $professionals['total'];
			$paginations = generate_pagination_links($total_user_profile_count, $find_professional_page_url, $per_page_limit, $num_pagination_links);
			$professionals = $this->Find_professionals_model->get_all_professionals_based_on_filter($filter_arr, $paginations['offset'], $per_page_limit);
			
			//pre($professionals);
			
			
			$data['professionals'] = $professionals['data'];
			
			$data['professionals_count'] = $total_user_profile_count;
			$res['status'] = 200;
			$res['total_records'] = $total_user_profile_count;
			$page = $paginations['current_page_no'];
			$res['pagination'] = $paginations['links'];
			if($data['professionals_count'] > 0) {
				$data['current_page'] = 'find_professionals';
				$data['page'] = $page;
				$res['data'] = $this->load->view('ajax_filtered_listing', $data, true);
				$res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
				$multiplication = $per_page_limit * $page;
				$subtraction = ($multiplication - ($per_page_limit - count($data['professionals'])));
				$res['record_per_page'] = count($data['professionals']) < $per_page_limit ? $subtraction : $multiplication;
				$res['page_no'] = ($per_page_limit * ($page - 1)) + 1;
			} else {
				$res['data'] = '<div class="initialViewNorecord">'.$this->config->item('find_professionals_search_no_results_returned_message').'</div>';
			}
			
			echo json_encode($res);
			return;
	}
	// This method is used to get filter critera from find professional page and based on those critera it filtered professionals and send updated listing back to find professional page
	public function ajax_update_listing_based_filter_logged_off() { 
		if(!$this->input->is_ajax_request ()){ 
			show_custom_404_page();
			return;
		}
			$filter_arr = $this->input->post();
			$find_professional_page_url = $this->config->item('find_professionals_page_url');
			$per_page_limit = $this->config->item('find_professionals_listing_limit_per_page');
			$num_pagination_links = $this->config->item('find_professionals_number_of_pagination_links');
			$page = $this->uri->segment(3);
			if(check_session_validity()) {
				$this->session->set_userdata('filter_arr', $filter_arr);
				$this->session->set_userdata('page',$page);
				echo json_encode(['status' => 201]);
				return;
			}
			if($page != null) {
				if($page == 1) {
					$start = 0;
				} else {
					$start = ($page - 1) * $per_page_limit;
				}
			} else {
				$start = 0;
			}
			
			$professionals = $this->Find_professionals_model->get_all_professionals_based_on_filter($filter_arr, 0, $per_page_limit);
			$total_user_profile_count = $professionals['total'];
			$paginations = generate_pagination_links($total_user_profile_count, $find_professional_page_url, $per_page_limit, $num_pagination_links);
			$professionals = $this->Find_professionals_model->get_all_professionals_based_on_filter($filter_arr, $paginations['offset'], $per_page_limit);
			$data['professionals'] = $professionals['data'];
			$filtered_rec_cnt = $professionals['total'];
			$data['professionals_count'] = $total_user_profile_count;
			$res['status'] = 200;
			$page = $paginations['current_page_no'];
			$res['total_records'] = $filtered_rec_cnt;
			$res['pagination'] = $paginations['links'];
			if($data['professionals_count'] > 0) {
				$data['current_page'] = 'find_professionals';
				$data['page'] = $page;
				$res['data'] = $this->load->view('ajax_filtered_listing', $data, true);
				$res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
				$multiplication = $per_page_limit * $page;
				$subtraction = ($multiplication - ($per_page_limit - count($data['professionals'])));
				$res['record_per_page'] = count($data['professionals']) < $per_page_limit ? $subtraction : $multiplication;
				$res['page_no'] = ($per_page_limit * ($page - 1)) + 1;
			} else {
				$res['data'] = '<div class="initialViewNorecord">'.$this->config->item('find_professionals_search_no_results_returned_message').'</div>';
			}
		
			echo json_encode($res);
	}
	// This method is used to save user selected filter to keep filter as selected when click on logout button from find professional page -> assets/js/modules/find_professionals.js
	public function ajax_save_user_selected_filters() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
		}
		if($this->input->post('page_no')) {
			$this->session->set_userdata('page', $this->input->post('page_no'));
		}
		if($this->input->post('filter_arr')) {
			$filter_arr = $this->input->post('filter_arr');
			if(!empty($filter_arr['user_registration_time'])) {
					unset($filter_arr['user_registration_time']);
				} 
				if(!empty($filter_arr['profile_last_update_time'])) {
					unset($filter_arr['profile_last_update_time']);
				} 
			$this->session->set_userdata('filter_arr', $this->input->post('filter_arr'));
		}
		return;
	}
}
?>