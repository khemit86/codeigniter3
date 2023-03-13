<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminuser extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('adminuser_model');
        $this->load->library('form_validation');
		$this->load->library('pagination');
        $this->load->library('editor');
        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('ckeditor');
    }

    public function index() {
	    redirect (base_url());
       
    }

  
    public function user_list($limit_from='') {
			$data['data'] = $this->auto_model->leftPannel();
			$lay['lft'] = "inc/section_left";
			$this->load->library('pagination');
			$config['base_url'] = base_url().'adminuser/user_list/';
			$config['total_rows'] =$this->db->get('admin')->num_rows();
			$config['per_page'] = 10; 
			$config["uri_segment"] = 3;
			$config['use_page_numbers'] = TRUE;   
			$this->pagination->initialize($config); 
			$page = ($limit_from) ? $limit_from : 0;
									$per_page = $config["per_page"];
									$start = 0;
									if ($page > 0) {
											for ($i = 1; $i < $page; $i++) {
													$start = $start + $per_page;
											}
									}
			$data['all_data'] = $this->adminuser_model->getAllUserList($config['per_page'], $start);
			$data['links']=$this->pagination->create_links();
   		$this->layout->view('list', $lay, $data);
		
	}
	
	public function add_user()
	{
		$data['ckeditor'] = $this->editor->geteditor('knowledge_content','Full');
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		$data['type_data']= [];
		
		if($this->input->post('submit'))
		{
			
            $this->form_validation->set_rules('username', 'username', 'required|is_unique[admin.username]');
            $this->form_validation->set_rules('user_type', 'user type', 'required');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email');
	    	$this->form_validation->set_rules('password', 'password', 'required|min_length[5]|matches[cpassword]');
            $this->form_validation->set_rules('cpassword', 'confirm password', 'required|min_length[5]');
            
            $this->form_validation->set_rules('status', 'Status', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add', $lay, $data);
            } else {
                //$post_data = $this->input->post();
                $post_data['username'] = $this->input->post('username');
								// $post_data['type'] = $this->input->post('user_type');
                $post_data['email'] = $this->input->post('email');
                $post_data['password']= md5( $this->input->post('password'));
                		
                $post_data['status'] = $this->input->post('status');
             
                $insert_faq = $this->adminuser_model->add_user($post_data);
                
                if ($insert_faq) {
                    $this->session->set_flashdata('succ_msg', 'User Inserted Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'adminuser/add_user/');
            }
        
		
		
		}
		else
		{
			$this->layout->view('add', $lay, $data);
		}
		
	}
	
	public function change_status()
	{
		$id = $this->uri->segment(3);
		$type=$this->uri->segment(5);
		if($this->uri->segment(4) == 'inact')
			$data['status'] = 'N';
		if($this->uri->segment(4) == 'act')
			$data['status'] = 'Y';
			
		if($this->uri->segment(4) == 'del')
		{
			$update = $this->adminuser_model->deleteUser($id);	
		}
		else
		{
			$update = $this->adminuser_model->updateUser($data,$id);
		}
		
		if ($update) {
			if($this->uri->segment(4) == 'inact')
				$this->session->set_flashdata('succ_msg', 'Inactive Successfully Done...');
			if($this->uri->segment(4) == 'act')
				$this->session->set_flashdata('succ_msg', 'Activation Successfully Done...');
			if($this->uri->segment(4) == 'del')
				$this->session->set_flashdata('succ_msg', 'Deletion Successfully Done...');
		} else {
			$this->session->set_flashdata('error_msg', 'unable to update');
		}
		redirect(base_url() . 'adminuser/user_list/');
		
	}
	
	public function edit_user()
	{    
	
	    $data['ckeditor'] = $this->editor->geteditor('knowledge_content','Full');
		$id = $this->uri->segment(3);
		$data['data'] = $this->auto_model->leftPannel();
		$lay['lft'] = "inc/section_left";
		
		if($id)
		{   
		    $data['type_data']= [];
			$data['all_data'] = $this->adminuser_model->getAPerticulerFooterDataUsingId($id);
			
		
		}
		if($this->input->post('submit'))
		{
			//print_r($this->input->post());die();
            $this->form_validation->set_rules('username', 'username', 'required');
            $this->form_validation->set_rules('user_type', 'user type', 'required');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email');
            
            $this->form_validation->set_rules('status', 'Status', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('edit', $lay, $data);
            } else {
			$new_data['username'] = $this->input->post('username');
			// $new_data['type'] = $this->input->post('user_type');
			$new_data['email'] = $this->input->post('email');
			$new_data['status'] = $this->input->post('status');
                        
                        
			$update = $this->adminuser_model->updateUser($new_data,$id);
			if ($update) {
				$this->session->set_flashdata('succ_msg', 'Update Successfully');
			} else {
				$this->session->set_flashdata('error_msg', 'unable to Update');
			}
			redirect(base_url() . 'adminuser/edit_user/'.$id.'/');
			}
		
		
		}
		else
		{
			$this->layout->view('edit', $lay, $data);
		}
		
	}
}
