<?php
if ( ! defined ('BASEPATH')) {
  exit ('No direct script access allowed');
}

class Find_project extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    public function __construct () {
      $this->load->helper('text');
      $this->load->library ('pagination');
      $this->load->library ('form_validation');
      $this->load->model('Find_project_model');
      $this->load->model('Projects/Projects_model');
      parent::__construct ();
    }
   
	  public function index() {
      
      $data['categories'] = $this->Find_project_model->get_category_tree();
      $limit = $this->config->item('find_project_listing_limit_per_page');
      $find_project_page_url = $this->config->item('find_projects_page_url');
      $num_pagination_links = $this->config->item('find_project_number_of_pagination_links');
      if($this->session->userdata('filter_arr') != null || $this->session->userdata('page') != null) {

        $total_open_bidding = $this->db->from('projects_open_bidding')->where('project_expiration_date >= NOW()')->count_all_results();
        if($total_open_bidding != 0) {
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
        $open_bidding_project_data = $this->Find_project_model->get_open_projects_based_on_filter($filter_arr, $start, $limit);
        $data['open_bidding_project_data'] = $open_bidding_project_data['data'];
        $filtered_rec_cnt = $open_bidding_project_data['total'];
        $data['open_bidding_project_count'] = $filtered_rec_cnt;
        $pagination = generate_pagination_links($filtered_rec_cnt, $find_project_page_url,$limit, $num_pagination_links, $page);
        $data['pagination_links'] = $pagination['links'];

        $multiplication = $limit * $page;
        $subtraction = ($multiplication - ($limit - count($data['open_bidding_project_data'])));
        $res['record_per_page'] = count($data['open_bidding_project_data']) < $limit ? $subtraction : $multiplication;
        $res['page_no'] = ($limit * ($page - 1)) + 1;
      } else {
        
        // $data['open_bidding_project_data'] = $open_bidding_project_data =  [];
        $data['open_bidding_project_data'] = $open_bidding_project_data =  $this->Find_project_model->get_all_open_projects(0, $this->config->item('find_project_listing_limit_per_page'));
        
        $open_bidding_project_count = $this->db->where('project_expiration_date >= NOW() AND hidden = "N"')->from('projects_open_bidding')->count_all_results();
        // $open_bidding_project_count = 0;
        $data['open_bidding_project_count'] = $open_bidding_project_count;
        $pagination = generate_pagination_links($open_bidding_project_count, $find_project_page_url,$limit, $num_pagination_links);
        $data['pagination_links'] = $pagination['links'];
        
      }
      $data['current_page'] = 'find_project';
      $data['locations'] = $this->Find_project_model->get_all_county_locality_to_display('');
     
      $data['fixed_budget_range'] = $this->Find_project_model->get_fixed_budget_projects_budget_range();        
      $data['hourly_rate_budget_range'] = $this->Find_project_model->get_hourly_budget_projects_budget_range();
      $data['fulltime_salary_range'] = $this->Find_project_model->get_fulltime_projects_salaries_range();
      
		  ########## set the find_job title meta tag and meta description  start here #########
		  $find_project_title_meta_tag = $this->config->item('find_project_page_title_meta_tag');
      $find_project_description_meta_tag = $this->config->item('find_project_page_description_meta_tag');

      // $this->session->set_userdata('share_title_short',$find_project_title_meta_tag);
		  // $this->session->set_userdata('share_description',$find_project_description_meta_tag);
      $data['meta_tag'] = '<title>' . $find_project_title_meta_tag . '</title><meta name="description" content="' . $find_project_description_meta_tag . '"/>';
      ########## set the find_job title tag start end #########
      if(check_session_validity()) {
        $this->layout->view ('find_project_logged_in', '', $data, 'include');
      } else {
        $this->layout->view ('find_project_logged_off', '', $data, 'include');
      }
    }
    // update listing of project based on user selection
    public function ajax_update_listing_based_filteration() {
      if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
      }
      $filter_arr = $this->input->post();
      $find_project_page_url = $this->config->item('find_projects_page_url');
      $per_page_limit = $this->config->item('find_project_listing_limit_per_page');
      $num_pagination_links = $this->config->item('find_project_number_of_pagination_links');
      if(!check_session_validity()) {
        $this->session->set_userdata('filter_arr',$filter_arr);
        echo json_encode(['status' => 404]);
        return;
      }
      $user = $this->session->userdata('user');
      if($filter_arr['user_id'] != $user[0]->user_id) {
        echo json_encode(['status' => 404]);
        return;  
      }
      $page = $this->uri->segment(3);
      if($page != null) {
        if($page == 1) {
          $start = 0;
        } else {
          $start = ($page - 1) * $per_page_limit;
        }
      } else {
        $start = 0;
      }

      $all_open_bidding_cnt = $this->db->from('projects_open_bidding')->where(['project_expiration_date >= '=> date('Y-m-d H:i:s'), 'hidden' => 'N'])->count_all_results();

      $open_bidding_project_data = $this->Find_project_model->get_open_projects_based_on_filter($filter_arr, 0, $per_page_limit);
      $total_open_bidding = $open_bidding_project_data['total'];
      $paginations = generate_pagination_links($total_open_bidding, $find_project_page_url,$per_page_limit, $num_pagination_links);
      $open_bidding_project_data = $this->Find_project_model->get_open_projects_based_on_filter($filter_arr, $paginations['offset'], $per_page_limit);
      $data['open_bidding_project_data'] = $open_bidding_project_data['data'];
      $filtered_rec_cnt = $open_bidding_project_data['total'];
      $data['open_bidding_project_count'] = $total_open_bidding;
      $res['status'] = 200;
      $page = $paginations['current_page_no'];
      $res['total_records'] = $filtered_rec_cnt;
      $res['pagination'] = $paginations['links'];

      if($all_open_bidding_cnt > 0) {
        $data['current_page'] = 'find_project';
        $data['page'] = $page;
        $res['data'] = $this->load->view('ajax_filtered_listing', $data, true);
        $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
        $multiplication = $per_page_limit * $page;
        $subtraction = ($multiplication - ($per_page_limit - count($data['open_bidding_project_data'])));
        $res['record_per_page'] = count($data['open_bidding_project_data']) < $per_page_limit ? $subtraction : $multiplication;
        $res['page_no'] = ($per_page_limit * ($page - 1)) + 1;
        
      } else {
        $res['data'] = '<div class="initialViewNorecord">'.$this->config->item('find_project_no_project_available_message').'</div>';
        // $res['data'] = '<div class="fjDnone initialViewNorecord">'.$this->config->item('find_project_search_no_results_returned_message').'</div>';
        
      }
      
      // pre($res);
      echo json_encode($res);
        return;
    }
    // update listing of project based on user selection for logged off user
    public function ajax_update_listing_based_filteration_logged_off() {
		if(!$this->input->is_ajax_request ()){ 
			show_custom_404_page();
			return;
		}
      $filter_arr = $this->input->post();
      $find_project_page_url = $this->config->item('find_projects_page_url');
      $per_page_limit = $this->config->item('find_project_listing_limit_per_page');
      $num_pagination_links = $this->config->item('find_project_number_of_pagination_links');
      if(check_session_validity()) {
        $this->session->set_userdata('filter_arr', $filter_arr);
        echo json_encode(['status' => 201]);
        return;
      }
      $page = $this->uri->segment(3);
      $filter_arr = $this->input->post();
      if($page != null) {
        if($page == 1) {
          $start = 0;
        } else {
          $start = ($page - 1) * $per_page_limit;
        }
      } else {
        $start = 0;
      }

      $all_open_bidding_cnt = $this->db->from('projects_open_bidding')->where(['project_expiration_date >= '=> date('Y-m-d H:i:s'), 'hidden' => 'N'])->count_all_results();

      $open_bidding_project_data = $this->Find_project_model->get_open_projects_based_on_filter($filter_arr, 0, $per_page_limit);
      $total_open_bidding = $open_bidding_project_data['total'];
      $paginations = generate_pagination_links($total_open_bidding, $find_project_page_url,$per_page_limit, $num_pagination_links);
      $open_bidding_project_data = $this->Find_project_model->get_open_projects_based_on_filter($filter_arr, $paginations['offset'], $per_page_limit);
      $data['open_bidding_project_data'] = $open_bidding_project_data['data'];
      $filtered_rec_cnt = $open_bidding_project_data['total'];
      $data['open_bidding_project_count'] = $total_open_bidding;
      $res['status'] = 200;
      $page = $paginations['current_page_no'];
      $res['total_records'] = $filtered_rec_cnt;
      $res['pagination'] = $paginations['links'];
      if($all_open_bidding_cnt > 0) {
        $data['current_page'] = 'find_project';
        $data['page'] = $page;
        $res['data'] = $this->load->view('ajax_filtered_listing', $data, true);
        $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
        $multiplication = $per_page_limit * $page;
        $subtraction = ($multiplication - ($per_page_limit - count($data['open_bidding_project_data'])));
        $res['record_per_page'] = count($data['open_bidding_project_data']) < $per_page_limit ? $subtraction : $multiplication;
        $res['page_no'] = ($per_page_limit * ($page - 1)) + 1;
      } else {
        $res['data'] = '<div class="initialViewNorecord">'.$this->config->item('find_project_no_project_available_message').'</div>';
        // $res['data'] = '<div class="fjDnone initialViewNorecord">'.$this->config->item('find_project_search_no_results_returned_message').'</div>';
      }
      
      
      echo json_encode($res, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
      return;
    }
    /**
     * This method is used to save user selected filter to keep filter as selected when click on logout button from find project page -> assets/js/modules/find_project.js
     */
    public function ajax_save_user_selected_filters() {
      if(!$this->input->is_ajax_request ()){
			  show_custom_404_page(); //show custom 404 page
      }
      if($this->input->post('page_no')) {
        $this->session->set_userdata('page', $this->input->post('page_no'));
      }

      if($this->input->post('filter_arr')) {
        $this->session->set_userdata('filter_arr', $this->input->post('filter_arr'));
      }
      return;
    }

    /**
     * This method is used to save user selected filter to keep filter as selected when click on category button from  project details page -> assets/js/modules/project_details.js
     */
    public function ajax_save_user_selected_project_category() {
      if(!$this->input->is_ajax_request ()){
			  show_custom_404_page(); //show custom 404 page
      }
      if($this->input->post('page_no')) {
        $this->session->set_userdata('page', $this->input->post('page_no'));
      }
      $this->session->unset_userdata('filter_arr');
      if($this->input->post('filter_arr')) {
        $row = $this->input->post('filter_arr');
        $row['search_more_option'] = true;
        $this->session->set_userdata('filter_arr', $row);
        $find_projects_page_url = $this->config->item('find_projects_page_url');
        $res['location'] = VPATH.$find_projects_page_url;
        echo json_encode($res);

      }
      return;
    }
}
?>