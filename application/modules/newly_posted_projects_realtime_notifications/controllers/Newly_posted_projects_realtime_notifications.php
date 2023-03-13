<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Newly_posted_projects_realtime_notifications extends MX_Controller
{
    public function __construct ()
    {
        /* $this->load->helper ('recaptcha'); */
        $this->load->library ('pagination');
        $this->load->model ('Newly_posted_projects_realtime_notifications_model');
        $this->load->model('Find_project/Find_project_model');
        parent::__construct ();
        
    }

    /**
     * This method is trigger from node when any new project posted from mapped category into admin panel 
    */
    public function get_newly_posted_project_notification() {
      if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
      }
        $res = [];
        if(check_session_validity()) {
            $id = $this->input->post('project_id');
            $notification_type = $this->input->post('notification_type');
            $user = $this->session->userdata('user');
            $condition = [
                'unpsn.user_id' => $user[0]->user_id,
                'unpsn.project_id' => $id
            ];
            $receive_notification = $this->db->get_where('users_consent_receive_notifications_tracking', ['user_id' => $user[0]->user_id, 'sent_notification_type' => 'newly_posted_projects_areas_of_expertise'])->row_array();
            $favorite_employer_receive_notification = $this->db->get_where('users_consent_receive_notifications_tracking', ['user_id' => $user[0]->user_id, 'sent_notification_type' => 'newly_posted_projects_by_favorite_employer'])->row_array();
            // if(!empty($receive_notification) || !empty($favorite_employer_receive_notification)) {
            $result = $this->Newly_posted_projects_realtime_notifications_model->get_newly_posted_project_notification_by_user_and_project($condition);
            if($notification_type == $result['sent_notification_type']) {
                if($notification_type == 'newly_posted' && !empty($receive_notification)) {
                    $res['data'] = $result;
                } else if ($notification_type == 'favorite_employer' && !empty($favorite_employer_receive_notification)) {
                    $res['data'] = $result;
                }
            } else {
                $res['data'] = [];
            }
            $res['status'] = 200;
        } else {
            $res['status'] = 400;
        }
        echo json_encode($res);
        return;
    }
	
    public function projects_notification_feed() { 
      if(!check_session_validity()) {
          redirect(base_url());
      }
      $user = $this->session->userdata('user');
      $name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ? $user[0]->first_name . ' ' . $user[0]->last_name : $user[0]->company_name;
        $data['current_page'] = 'project-notification-feed';
        ########## set the profile title meta tag and meta description  start here #########
      $title_meta_tag = $this->config->item('projects_realtime_notification_page_title_meta_tag');
        $description_meta_tag = $this->config->item('projects_realtime_notification_page_description_meta_tag');
      $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
      $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
	  
	  
      $num_pagination_links = $this->config->item('newly_posted_projects_realtime_notification_number_of_pagination_links');
      $per_page_limit = $this->config->item('newly_posted_projects_realtime_notification_listing_limit_per_page');
      $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
      $user = $this->session->userdata('user');
      $project_notification = $this->Newly_posted_projects_realtime_notifications_model->get_all_projects_notification_feeds_based_on_filter(0, $per_page_limit, $user);
      $data['locations'] = $this->Find_project_model->get_all_county_locality_to_display('');
      $data['fixed_budget_range'] = $this->Find_project_model->get_fixed_budget_projects_budget_range();        
      $data['hourly_rate_budget_range'] = $this->Find_project_model->get_hourly_budget_projects_budget_range();
      $data['fulltime_salary_range'] = $this->Find_project_model->get_fulltime_projects_salaries_range();
      $data['project_notifications'] = $project_notification['data'];
      $data['project_notifications_count'] = $project_notification['total'];
      $paginations = generate_pagination_links($project_notification['total'], $this->config->item('projects_realtime_notification_feed_page_url'), $per_page_limit, $num_pagination_links);
      $data['pagination_links'] = $paginations['links'];
      ########## set the profile title tag start end #########
      $this->layout->view ('newly_posted_projects_realtime_notifications', '', $data, 'include');

    }
    
    // update listing of projects notification feeds based on user search or click on page link from frontend
    public function ajax_update_listing_based_filter() {
      if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
      }
      $filter_arr = $this->input->post();
      if(!check_session_validity()) {
        echo json_encode(['status' => 404]);
        return;
      }
      $page = $this->uri->segment(3);
      $num_pagination_links = $this->config->item('newly_posted_projects_realtime_notification_number_of_pagination_links');
      $per_page_limit = $this->config->item('newly_posted_projects_realtime_notification_listing_limit_per_page');
      $notfication_feed_page_url = $this->config->item('projects_realtime_notification_feed_page_url');
      if($page != null) {
        if($page == 1) {
          $start = 0;
        } else {
          $start = ($page - 1) * $per_page_limit;
        }
      } else {
        $start = 0;
      }
      $res['status'] = 200;
      $user = $this->session->userdata('user');
      if($filter_arr['user_id'] != $user[0]->user_id) {
        echo json_encode(['status' => 404]);
        return;
      }
      $project_notification = $this->Newly_posted_projects_realtime_notifications_model->get_all_projects_notification_feeds_based_on_filter(0, $per_page_limit, $user, $filter_arr);
      $paginations = generate_pagination_links($project_notification['total'], $notfication_feed_page_url, $per_page_limit, $num_pagination_links);
      $project_notification = $this->Newly_posted_projects_realtime_notifications_model->get_all_projects_notification_feeds_based_on_filter($paginations['offset'], $per_page_limit, $user, $filter_arr);
      $data['project_notifications'] = $project_notification['data'];
      $data['project_notifications_count'] = $project_notification['total'];
      $res['data'] = $this->load->view('ajax_filtered_listing', $data, true);
      $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
      $filtered_rec_cnt = $project_notification['total'];
      $page = $paginations['current_page_no'];
      $res['total_records'] = $filtered_rec_cnt;
      $multiplication = $per_page_limit * $page;
      $subtraction = ($multiplication - ($per_page_limit - count($data['project_notifications'])));
      $res['record_per_page'] = count($data['project_notifications']) < $per_page_limit ? $subtraction : $multiplication;
      $res['page_no'] = ($per_page_limit * ($page - 1)) + 1;
      $res['pagination'] = $paginations['links'];
      
      echo json_encode($res);
      return;
    }
	
	//This function is used to reset the newly project posted message count
	public function reset_user_unread_newly_posted_project_messages_count(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				$this->db->where ('user_id', $user[0]->user_id);
				$this->db->update ('users_newly_posted_projects_sent_notifications_tracking',['is_message_read'=>'Y']); 
				echo json_encode(['status' => 200]);die;
			} else {
				
				echo json_encode(['status' => 'FAILED']);
				die;
			}
			
		} else {
			
			show_custom_404_page(); //show custom 404 page
		}
	
  }
  
  /**
   * This method is used to manage user receive sent notificatoin
   * This method is triggered when user click on checkboxes available on front-end
  */
  public function manage_receive_sent_notification() {
		if(!$this->input->is_ajax_request ()){ 
			show_custom_404_page();
			return;
		}
      $type = $this->input->post('type');
      $res['status'] = 200;
      if(check_session_validity()) { 
          $user = $this->session->userdata('user');
          if($type == 'newly_posted') {
              $data = [
                  'user_id' => $user[0]->user_id,
                  'sent_notification_type' => 'newly_posted_projects_areas_of_expertise'
              ];
              $result = $this->Newly_posted_projects_realtime_notifications_model->add_or_remove_consent_receive_detail($data);
              $res['state'] = $result['state'];
              if($result['state'] == 1) {
                  $msg = $this->config->item('user_consent_newly_posted_projects_areas_of_expertise_receive_realtime_notification_success_message');
              } else {
                  $msg = $this->config->item('user_consent_newly_posted_projects_areas_of_expertise_stop_receiving_realtime_notification_success_message');
              }
              $res['msg'] = $msg;
          } else if($type == 'favorite_employer') {
              $data = [
                  'user_id' => $user[0]->user_id,
                  'sent_notification_type' => 'newly_posted_projects_by_favorite_employer'
              ];
              $result = $this->Newly_posted_projects_realtime_notifications_model->add_or_remove_consent_receive_detail($data);
              if($result['state'] == 1) {
                  if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
                    $msg = $this->config->item('pa_user_consent_newly_posted_projects_favorite_employer_receive_realtime_notification_success_message');
                  } else {
                    $msg = $this->config->item('ca_user_consent_newly_posted_projects_favorite_employer_receive_realtime_notification_success_message');
                  }
              } else {
                  if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
                    $msg = $this->config->item('pa_user_consent_newly_posted_projects_favorite_employer_stop_receiving_realtime_notification_success_message');
                  } else {
                    $msg = $this->config->item('ca_user_consent_newly_posted_projects_favorite_employer_stop_receiving_realtime_notification_success_message');
                  }
              }
              $res['msg'] = $msg;
          }
      } else {
          $res['status'] = 400;
      }
      echo json_encode($res);
      return;
	  }
  
 
}
?>