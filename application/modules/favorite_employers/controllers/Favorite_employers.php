<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Favorite_employers extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    public function __construct() {
        $this->load->model('favorite_employers_model');
        $this->load->model('projects/Projects_model');
        $this->load->model('bidding/Bidding_model');
        $this->load->library ('pagination');
        parent::__construct();
		
    }

    public function favorite_employer_list() 
    { 
		
      if ($this->session->userdata('user') == null) {
          redirect(site_url());
      }
      
      $data['current_page'] = 'favorite_employers';
      $user = $this->session->userdata('user');
      $per_page_limit = $this->config->item('favorite_employers_listing_limit_per_page');
      $num_pagination_links = $this->config->item('favorite_employers_number_of_pagination_links');
      
		  $user_detail = $this->db // get the user detail
            ->select('u.account_type,u.is_authorized_physical_person, u.first_name, u.last_name, u.company_name, u.gender, u.profile_name')
            ->from('users u')
            ->where('u.user_id', $user[0]->user_id)
            ->get()->row_array();
      $name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] =='Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];	
      
      if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
        $favourite_employer_page_url = $this->config->item('ca_favorite_employers_page_url');
      } else {
        $favourite_employer_page_url = $this->config->item('pa_favorite_employers_page_url');
      }

      if($this->uri->segment(1) != $favourite_employer_page_url) {
        show_custom_404_page();
        return;
      }
      ########## set the profile title meta tag and meta description  start here #########
     
	  if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
			$title_meta_tag = $this->config->item('pa_favorite_employers_page_title_meta_tag');
			$description_meta_tag = $this->config->item('pa_favorite_employers_page_description_meta_tag');
	  }else if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] =='Y'){
			$title_meta_tag = $this->config->item('ca_app_favorite_employers_page_title_meta_tag');
			$description_meta_tag = $this->config->item('ca_app_favorite_employers_page_description_meta_tag');
	  }else {
			$title_meta_tag = $this->config->item('ca_favorite_employers_page_title_meta_tag');
			$description_meta_tag = $this->config->item('ca_favorite_employers_page_description_meta_tag');
	  }		
	  
      $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
	 $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);

        $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
        $favourite_employer_result = $this->favorite_employers_model->get_all_favorite_employers(0, $per_page_limit);
    
      foreach($favourite_employer_result['data'] as $key => &$value) {
          // number of published projects of po (fixed/hourly)
          $po_published_projects = $this->Projects_model->get_po_published_projects_count($value['user_id']);
          
          // number of published projects of po (fulltime)
          $po_published_fulltime_projects_count = $this->Projects_model->get_po_published_fulltime_projects_count($value['user_id']);
          
          // number of completed projects of po (fixed/hourly via portal)
          $po_completed_projects_count_via_portal = $this->Bidding_model->get_sp_completed_projects_count(array('project_owner_id'=>$value['user_id'],'project_completion_method'=>'via_portal'));
          
          // number of hires sp by po for fulltime projects
          $get_po_hires_sp_on_fulltime_projects_count = $this->Projects_model->get_po_hires_sp_on_fulltime_projects_count($user_detail['user_id']);
          
          $value['po_published_projects'] = $po_published_projects;
          $value['po_published_fulltime_projects_count'] = $po_published_fulltime_projects_count;
          $value['po_total_posted_projects'] = $po_published_projects+$po_published_fulltime_projects_count;
          $value['po_completed_projects_count_via_portal'] = $po_completed_projects_count_via_portal;
          $value['get_po_hires_sp_on_fulltime_projects_count'] = $get_po_hires_sp_on_fulltime_projects_count;
        }
        
        $data['favorite_employers'] = $favourite_employer_result['data'];
        $data['favorite_employers_count'] = $favourite_employer_result['total'];
        $paginations = generate_pagination_links($favourite_employer_result['total'], $favourite_employer_page_url, $per_page_limit, $num_pagination_links);
        $data['pagination_links'] = $paginations['links'];
        $user = $this->session->userdata('user');
        $cond = [
            'user_id' => $user[0]->user_id,
            'sent_notification_type' => 'newly_posted_projects_by_favorite_employer'
        ];
        $data['favorite_employer_notification'] = $this->db->get_where('users_consent_receive_notifications_tracking', $cond)->row_array();
        ########## set the profile title tag start end #########
        $this->layout->view ('favorite_employers', '', $data, 'include');

    }
    /**
     * This method trigger whenever any user subscribe to new employer from project detail page
    */
    public function manage_subscribe_or_unsubcribe_favorite_employer() {
		
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res['status'] = 200;
        if(check_session_validity()) {
            $employer_id = $this->input->post('employer_id');
            $user_id = $this->input->post('user_id');
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $user_id) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $data = [
                'user_id' => $user[0]->user_id,
                'favorite_employer_id' => $employer_id
            ];
            $employer = $this->db->get_where('users', ['user_id' => $employer_id])->row_array();
            $result = $this->favorite_employers_model->add_or_remove_employer_detail($data, $user);
            $username = (($employer['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($employer['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $employer['is_authorized_physical_person'] == 'Y')) ? $employer['first_name'].' '.$employer['last_name'] : $employer['company_name'];
            if(array_key_exists('error', $result)) {
                $res['status'] = 400;
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['error'] = $user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE ? $this->config->item('pa_favorite_employers_subcription_limit_reached_error_message') : $this->config->item('ca_favorite_employers_subcription_limit_reached_error_message');
            } else {
                if($result['state'] == 1) {
                    $msg = $this->config->item('favorite_employers_subscribe_success_message');
                } else {
                    $msg = $this->config->item('favorite_employers_unsubscribe_success_message');
                }
                $msg = str_replace(['{user_first_name_last_name_or_company_name}', '{user_profile_url_link}'], [$username, site_url($employer['profile_name'])], $msg);
                $res['msg'] = $msg;
                $res['state'] = $result['state'];
                // user display activity log message
                $user_display_activity_msg = '';
                if($result['state'] == 1) { 
                    $user_display_activity_msg = $this->config->item('favorite_employers_subscribe_success_user_activity_log_displayed_message');
                } else {
                    $user_display_activity_msg = $this->config->item('favorite_employers_unsubscribe_user_activity_log_displayed_message');
                }
                $user_display_activity_msg = str_replace(['{user_first_name_last_name_or_company_name}', '{user_profile_url_link}'], [$username, site_url($employer['profile_name'])], $user_display_activity_msg);
                user_display_log($user_display_activity_msg);
            }
            
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    // This method is trigger from frontend when user click on any page link
    public function get_favorite_employers_list_based_on_page_no() {
	
      if(!$this->input->is_ajax_request ()){
			  show_custom_404_page(); //show custom 404 page
			  return;
      }
      if(check_session_validity()) {
        $page = $this->uri->segment(3);
        $per_page_limit = $this->config->item('favorite_employers_listing_limit_per_page');
        $num_pagination_links = $this->config->item('favorite_employers_number_of_pagination_links');
        if($page != null) {
          if($page == 1) {
              $start = 0;
          } else {
              $start = ($page - 1) * $per_page_limit;
          }
        } else {
          $start = 0;
        }
        $user = $this->session->userdata('user');
        $user_detail = $this->db->get_where('users', ['user_id' => $user[0]->user_id])->row_array();
        $favourite_employer_page_url = $this->config->item('pa_favorite_employers_page_url');
        if($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
          $favourite_employer_page_url = $this->config->item('ca_favorite_employers_page_url');
        }
        $total_employer = $this->db->from('users_favorite_employer_tracking')->where(['user_id' => $user[0]->user_id])->count_all_results();
        $paginations = generate_pagination_links($total_employer, $favourite_employer_page_url,$per_page_limit, $num_pagination_links);

        $favourite_employer_result = $this->favorite_employers_model->get_all_favorite_employers($paginations['offset'], $per_page_limit);
        foreach($favourite_employer_result['data'] as $key => &$value) {
           // number of published projects of po (fixed/hourly)
			$po_published_projects = $this->Projects_model->get_po_published_projects_count($value['user_id']);
			
			// number of published projects of po (fulltime)
			$po_published_fulltime_projects_count = $this->Projects_model->get_po_published_fulltime_projects_count($value['user_id']);
			
			// number of completed projects of po (fixed/hourly via portal)
			$po_completed_projects_count_via_portal = $this->Bidding_model->get_sp_completed_projects_count(array('project_owner_id'=>$value['user_id'],'project_completion_method'=>'via_portal'));
			
			// number of hires sp by po for fulltime projects
			$get_po_hires_sp_on_fulltime_projects_count = $this->Projects_model->get_po_hires_sp_on_fulltime_projects_count($user_detail['user_id']);
			
			$value['po_published_projects'] = $po_published_projects;
			$value['po_published_fulltime_projects_count'] = $po_published_fulltime_projects_count;
			$value['po_total_posted_projects'] = $po_published_projects+$po_published_fulltime_projects_count;
			$value['po_completed_projects_count_via_portal'] = $po_completed_projects_count_via_portal;
			$value['get_po_hires_sp_on_fulltime_projects_count'] = $get_po_hires_sp_on_fulltime_projects_count;
        }
        $data['favorite_employers'] = $favourite_employer_result['data'];
        $res['status'] = 200;
        $res['data'] = $this->load->view('ajax_favorite_employers', $data, true);
        $page = $paginations['current_page_no'];
        $filtered_rec_cnt = $favourite_employer_result['total'];
        $data['favorite_employers_count'] = $favourite_employer_result['total'];
        $res['pagination'] = $paginations['links'];
        $multiplication = $per_page_limit * $page;
        $subtraction = ($multiplication - ($per_page_limit - count($data['favorite_employers'])));
        $res['total_records'] = $filtered_rec_cnt;
        $res['record_per_page'] = count($data['favorite_employers']) < $per_page_limit ? $subtraction : $multiplication;
        $res['page_no'] = ($per_page_limit * ($page - 1)) + 1;
        echo json_encode($res);
      } else {
        echo json_encode(['status' => 404]);
      }
      return;
    }
    // This method is trigger from frontend when user click on unfavorite button on particular employer
    public function make_unfavorite_employer() {
		
      if(!$this->input->is_ajax_request ()){
			  show_custom_404_page(); //show custom 404 page
			  return;
      }
      if(check_session_validity()) {
          $employer_id = $this->input->post('employer_id');
          $user_id = $this->input->post('user_id');
          $user = $this->session->userdata('user');
          $per_page_limit = $this->config->item('favorite_employers_listing_limit_per_page');
          $num_pagination_links = $this->config->item('favorite_employers_number_of_pagination_links');
          if($user[0]->user_id != $user_id) {
              $res['popup_heading'] = $this->config->item('popup_alert_heading');
              $res['warning'] = $this->config->item('different_users_session_conflict_message');
              $res['status'] = 440;
              echo json_encode($res);
              return;
          }
          $action = $this->input->post('action');
          $data = [
              'user_id' => $user[0]->user_id,
              'favorite_employer_id' => $employer_id
          ];
          $employer = $this->db->get_where('users', ['user_id' => $employer_id])->row_array();
          $result = $this->favorite_employers_model->add_or_remove_employer_detail($data, $user, $action);
          $username = $employer['account_type'] == 1 ? $employer['first_name'].' '.$employer['last_name'] : $employer['company_name'];
          $favourite_employer_page_url = $this->config->item('pa_favorite_employers_page_url');
          if($employer['account_type'] == USER_COMPANY_ACCOUNT_TYPE) {
              $favourite_employer_page_url = $this->config->item('ca_favorite_employers_page_url');
          }
          if(array_key_exists('error', $result)) {
              $res['status'] = 400;
              $res['error'] = $this->config->item('favorite_employers_subcription_limit_reached_error_message');
          } else {
              if($result['state'] == 1) {
                  $msg = $this->config->item('favorite_employers_subscribe_success_message');
              } else {
                  $msg = $this->config->item('favorite_employers_unsubscribe_success_message');
              }
              $msg = str_replace(['{user_first_name_last_name_or_company_name}', '{user_profile_url_link}'], [$username, site_url($employer['profile_name'])], $msg);
              $res['msg'] = $msg;
              $res['state'] = $result['state'];
          }
          $total_employer = $this->db->from('users_favorite_employer_tracking')->where(['user_id' => $user[0]->user_id])->count_all_results();
          $paginations = generate_pagination_links($total_employer, $favourite_employer_page_url,$per_page_limit, $num_pagination_links);
          $favourite_employer_result = $this->favorite_employers_model->get_all_favorite_employers($paginations['offset'], $per_page_limit);
          foreach($favourite_employer_result['data'] as $key => &$value) {
              // number of published projects of po (fixed/hourly)
			$po_published_projects = $this->Projects_model->get_po_published_projects_count($value['user_id']);
			
			// number of published projects of po (fulltime)
			$po_published_fulltime_projects_count = $this->Projects_model->get_po_published_fulltime_projects_count($value['user_id']);
			
			// number of completed projects of po (fixed/hourly via portal)
			$po_completed_projects_count_via_portal = $this->Bidding_model->get_sp_completed_projects_count(array('project_owner_id'=>$value['user_id'],'project_completion_method'=>'via_portal'));
			
			// number of hires sp by po for fulltime projects
			$get_po_hires_sp_on_fulltime_projects_count = $this->Projects_model->get_po_hires_sp_on_fulltime_projects_count($user_detail['user_id']);
			
			$value['po_published_projects'] = $po_published_projects;
			$value['po_published_fulltime_projects_count'] = $po_published_fulltime_projects_count;
			$value['po_total_posted_projects'] = $po_published_projects+$po_published_fulltime_projects_count;
			$value['po_completed_projects_count_via_portal'] = $po_completed_projects_count_via_portal;
			$value['get_po_hires_sp_on_fulltime_projects_count'] = $get_po_hires_sp_on_fulltime_projects_count;
          }
          
          $data['favorite_employers'] = $favourite_employer_result['data'];
          $res['status'] = 200;
          $res['data'] = $this->load->view('ajax_favorite_employers', $data, true);
          $data['favorite_employers_count'] = $favourite_employer_result['total'];
          $res['pagination'] = $paginations['links'];
          $page = $paginations['current_page_no'];
          $multiplication = $per_page_limit * $page;
          $subtraction = ($multiplication - ($per_page_limit - count($data['favorite_employers'])));
          $page = $page == 1 ? $page : ($per_page_limit * ($page - 1)) + 1;
          $res['total_records'] = $favourite_employer_result['total'];
          $res['record_per_page'] = count($data['favorite_employers']) < $per_page_limit ? $subtraction : $multiplication;
          $res['page_no'] = $page;
      } else {
          $res['status'] = 404;
      }
      echo json_encode($res);
      return;
    }
}
?>