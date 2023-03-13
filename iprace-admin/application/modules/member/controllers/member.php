<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends MX_Controller {

    //private $auto_model;

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
      $this->load->model('member_model');
      
      $this->load->library('pagination');
      
      $this->load->helper('url'); //You should autoload this one ;)
      parent::__construct();
    }

    public function index() {
	    redirect (base_url());
    }

    public function member_list($limit_from = ''){	
		
			$data['data'] = $this->auto_model->leftPannel();
			$lay['lft'] = "inc/section_left";
			$rowperpage = PAGING_LIMIT;
      
      $members = $this->member_model->getFilterMemberList([], 0, PAGING_LIMIT);
      $total_rows = $members['total'];
      $data['total_rows'] = $total_rows;
      $paginations = generate_pagination_links($total_rows, 'member/member_list', $rowperpage, 3);

      $data["links"] = $paginations['links'];
      $data['page_no'] = $paginations['current_page_no'];
      $data['locations'] = $this->member_model->get_all_county_locality_to_display('');
			$data['all_data'] = $this->member_model->getAllMemberList($rowperpage, $paginations['offset']);
			$this->layout->view('list', $lay, $data);
    }
	       
    public function change_status()
    {
      $id = $this->uri->segment(3);
      //$type=$this->uri->segment(5);
      if($this->uri->segment(4) == 'inact')
        $data['status'] = 'N';
      if($this->uri->segment(4) == 'act')
        $data['status'] = 'Y';
        
      if($this->uri->segment(4) == 'del')
      {
        include_once '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
        $update = $this->member_model->deleteMember($id, $config);	
        if(!$update) {
          $this->session->set_flashdata('error_msg', 'You can not delete this user.');
          redirect(base_url() . 'member/member_list/');
          return;
        }
      }
      else
      {
        $update = $this->member_model->updateMemberStatus($data,$id);        
      }
      
      if ($update) {
        if($this->uri->segment(4) == 'inact')
          $this->session->set_flashdata('succ_msg', 'Inactivation Successfully Done...');
        if($this->uri->segment(4) == 'act')
          $this->session->set_flashdata('succ_msg', 'Activation Successfully Done...');
        if($this->uri->segment(4) == 'del')
          $this->session->set_flashdata('succ_msg', 'Deletion Successfully Done...');
      } else {
        $this->session->set_flashdata('error_msg', 'Unable to Process.');
      }
      redirect(base_url() . 'member/member_list/');
      
    }
	
    public function ajax_filter_member($param = '') {
      
      $rowperpage = PAGING_LIMIT;
      $rowno = 0;
      
      $filter_arr = $this->input->post();

      $members = $this->member_model->getFilterMemberList($filter_arr, $rowperpage, '0');
      $member_count = $members['total'];

      $paginations = generate_pagination_links($member_count, 'member/member_list', $rowperpage, 3);

      $data['total_rows'] = $member_count;
      $page = $paginations['current_page_no'];

      $all_data = $this->member_model->getFilterMemberList($filter_arr,$rowperpage, $paginations['offset']);	
      
      $data['all_data'] = $all_data['data'];	
      
      $data["links"] = $paginations['links'];
      $multiplication = $rowperpage * $page;
      $subtraction = ($multiplication - ($rowperpage - count($data['all_data'])));
      $data['record_per_page'] = count($data['all_data']) < $rowperpage ? $subtraction : $multiplication;
      $data['page_no'] = ($rowperpage * ($page - 1)) + 1;

      $res['status'] = 200;
      $res['data'] = $this->load->view('ajax_member_list', $data, true);
      $res['data'] = mb_convert_encoding($res['data'], 'UTF-8', 'UTF-8');
      echo json_encode($res);
      return;
    }

    public function ajax_save_users_additional_settings() {
      if($this->input->is_ajax_request()) {
        $row = $this->input->post();
        $result = $this->db->get_where('users_additional_accesses_settings', ['user_id' => $row['user_id']])->row_array();
        if(empty($result)) {
          $data = [
            'user_id' => $row['user_id']
          ];
          if($row['type'] == 'additional_dropdown') {
            $data['additional_dropdpwn_on_find_professionals_page'] = 'Y';
          } else if($row['type'] == 'banned_on_find_professional_page') {
            $data['user_profile_not_displayed_on_find_professionals_page'] = 'Y';
          } else if($row['type'] == 'banned_on_user_profile_page') {
            $data['user_profile_page_not_accessible'] = 'Y';
          }
          
          $this->db->insert('users_additional_accesses_settings', $data);
        } else {
          if($row['type'] == 'additional_dropdown') {
            $data = [
              'additional_dropdpwn_on_find_professionals_page' => $row['state']
            ];
          } else if($row['type'] == 'banned_on_find_professional_page') {
            $data = [
              'user_profile_not_displayed_on_find_professionals_page' => $row['state']
            ];
          } else if($row['type'] == 'banned_on_user_profile_page') {
            $data = [
              'user_profile_page_not_accessible' => $row['state']
            ];
          }
          
          $this->db->update('users_additional_accesses_settings', $data, ['user_id' => $row['user_id']]);
        }
      }
      $res['status'] = 200;
      $res['msg'] = 'Success';
      echo json_encode($res);
      return;
    }

    // This method is used to save profile as featured on find profesionals page
    public function ajax_save_featured_profile_on_find_professionals_page() {
      if($this->input->is_ajax_request()) {
        $row = $this->input->post();
        $result = $this->db->get_where('users_featured_profiles_tracking_on_find_professionals_page', ['user_id' => $row['user_id']])->row_array();
        $dt = '';
        if(empty($result)) {
          $data = [
            'user_id' => $row['user_id'],
            'featured_profile_enabled_date' => date('Y-m-d H:i:s')
          ];
          $this->db->insert('users_featured_profiles_tracking_on_find_professionals_page', $data);
          $dt = date(DATE_TIME_FORMAT);
        } else {
          $this->db->delete('users_featured_profiles_tracking_on_find_professionals_page', ['user_id' => $row['user_id']]);
        }
      }
      $res['status'] = 200;
      $res['msg'] = 'Success';
      $res['featured_enabled_date'] = $dt;
      echo json_encode($res);
      return;
    }
}