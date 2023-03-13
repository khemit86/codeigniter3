<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_activity_log extends MX_Controller { 
    public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('user_activity_log_model');
        if (!check_session_validity()) {
            redirect(base_url());
        }
    }

    // This method is used to show user display activity messages
    public function user_activity() {
        if (!check_session_validity()) {
            echo json_encode(['status' => 404]);
            return;
        }
        $user = $this->session->userdata('user');
        $user_id = $user[0]->user_id;
         $name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
        $data['current_page'] = 'activity';
        ########## set the dashboard title meta tag and meta description  start here #########
        $activity_title_meta_tag = $this->config->item('activity_page_title_meta_tag');
        $activity_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $activity_title_meta_tag);

        $activity_description_meta_tag = $this->config->item('activity_page_description_meta_tag');
        $activity_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $activity_description_meta_tag);
        $data['meta_tag'] = '<title>' . $activity_title_meta_tag . '</title><meta name="description" content="' . $activity_description_meta_tag . '"/>';
        //start pagination
        $limit = $this->config->item('user_display_activity_listing_limit_per_page');
        $no_of_links = $this->config->item('user_display_activity_number_of_pagination_links');

        $activitiesArr = $this->user_activity_log_model->get_displayed_user_activity_data(0, $limit, $user_id);
        $searchVal = $this->input->post('sval');

        
        if (count($activitiesArr) > 1) {
            $totalRecord = $activitiesArr['total'];
            $paginations = generate_pagination_links($totalRecord, $this->config->item('activity_page_url'), $limit, $no_of_links);
            $data['pagination'] = $paginations['links'];
        }


        $data['activities'] = $activitiesArr['data'];
        $data['activities_count'] = $activitiesArr['total'];
        ########## set the dashboard title tag start end #########
        $this->layout->view('user_activity_log', '', $data, 'include');
    }
    // This method is used to update user display activity based on page number or search criteria on activity page
    public function ajax_user_display_activity() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if (!check_session_validity()) {
          echo json_encode(['status' => 404]);
          return;
        }
        $user = $this->session->userdata('user');
        $user_id = $user[0]->user_id;
        $pageNo = $this->uri->segment(3);
        $searchVal = $this->input->post('sval');
        $limit = $this->config->item('user_display_activity_listing_limit_per_page');
        $no_of_links = $this->config->item('user_display_activity_number_of_pagination_links');
        $offset = ($pageNo - 1) * $limit;
        if(!empty($searchVal)) {
          $search_val = trim(htmlspecialchars($searchVal, ENT_QUOTES));
          $search_val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $search_val));

          if(!empty($search_val)) {
            $search_val = ''.$search_val.'*';
          } 
          $total_activites =  $this->db
                            ->from('users_activity_log_tracking')
                            ->where(['user_id' => $user_id])
                            ->where("match(activity_description) AGAINST ('".$search_val."' IN BOOLEAN MODE)")
                            ->count_all_results(); 
        } else {
          $total_activites = $this->db->from('users_activity_log_tracking')->where(['user_id' => $user_id])->count_all_results();
        }

        
      
        $paginations = generate_pagination_links($total_activites, $this->config->item('activity_page_url'), $limit, $no_of_links);
        $activitiesMainArr = $this->user_activity_log_model->get_displayed_user_activity_data($paginations['offset'], $limit, $user_id, ['search_str' => $searchVal] );
        $totalRecord = $activitiesMainArr['total'];
        $activities = $activitiesMainArr['data'];
        $return_data['total_records'] = $totalRecord;
        $var = '';
        $pageNo = $paginations['current_page_no'];
        if ($totalRecord > 0) {
          foreach ($activities as $k => $row) {
              $list[$k]['act'] = $row['activity_description'];
              $list[$k]['time'] = date(DATE_TIME_FORMAT,strtotime($row['activity_log_record_time']));
          }
          
          $return_data['listdata'] = $list;
          $return_data['pagination'] = $paginations['links'];
          $multiplication = $limit * $pageNo;
          $subtraction = ($multiplication - ($limit - count($activitiesMainArr['data'])));
          $return_data['record_per_page'] = count($activitiesMainArr['data']) < $limit ? $subtraction : $multiplication;
          $return_data['page_no'] = ($limit * ($pageNo - 1)) + 1;
      } else {
        if(empty($searchVal)){
          $error = '<div class="uaIntive"><div class="wPU"><i class="far fa-bell"></i>'.$this->config->item('activity_no_record').'</div></div>';
          $return_data['error'] = $error;
          $return_data['initial_view'] = '1';
        } else {
          $error = '<div class="">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-12 text-center">
                  <h6>' . $this->config->item('activity_no_record_found') . '</h6>
                </div>
              </div>
            </div>';
          $return_data['error'] = $error;
          $return_data['initial_view'] = '0';
        }
      }
        echo(json_encode($return_data));
    }
    
	//This function is used to reset the activity log message count
	public function reset_user_unread_activity_log_messages_count(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				$this->db->where ('user_id', $user[0]->user_id);
				$this->db->update ('users_activity_log_tracking',['is_message_read'=>'Y']); 
				echo json_encode(['status' => 200]);die;
			}else{
				
				echo json_encode(['status' => 'FAILED']);
				die;
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
}
?>