<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hourlyrate extends MX_Controller {

    //private $auto_model;
	 
    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('hourlyrate_model');
        $this->load->library('form_validation');        
		parent::__construct();
		$this->load->helper('url');
    }
    // This method is used to load all hourly rate range data
    public function index(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        $data['list'] = $this->hourlyrate_model->get_all_data();
        $this->layout->view('list', $lay, $data);
    }
    // This method is used to add new hourly rate range
    public function add(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        if ($this->input->post()) {
            $this->form_validation->set_rules('min', 'Min Hourly Rate', 'required');            
            $this->form_validation->set_rules('max', 'Max Hourly Rate', 'required');                        

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add', $lay, $data);
            } else {
                $post_data = $this->input->post();
                $post['min_hourly_rate'] = $post_data['min'];
                $post['max_hourly_rate'] = $post_data['max'];
                $insert = $this->hourlyrate_model->add($post);

                if ($insert) {
                    $this->session->set_flashdata('succ_msg', 'Hourly rate range inserted Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'hourlyrate');
            }
        } else {
            $this->layout->view('add', $lay, $data);
        }
    }   
    // This method is used to edit and update hourly rate range
    public function edit() {

        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $id = $this->uri->segment(3);
        if ($id == '' OR $id == 0) {
            $id = set_value('id');
        }
		
        if ($this->input->post()) {
		
			$post_data = $this->input->post();
			$data['id'] = $id = $post_data['id'];
			$data['min'] = $post['min_hourly_rate'] = $post_data['min'];
            if($post_data['max'] == ""){
                $data['max'] = $post['max_hourly_rate'] = "All";
            }else{
                $data['max'] = $post['max_hourly_rate'] = $post_data['max'];
            }		            
			
            $this->form_validation->set_rules('min', 'Min Hourly Rate', 'required');                     

            if ($this->form_validation->run() == FALSE) {				
                $this->layout->view('edit', $lay,$data);
            } else {
                $update = $this->hourlyrate_model->update($post,$id);
				
                if ($update) {
                    $this->session->set_flashdata('succ_msg', 'Hourly Rate range Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Update Successfully');
                }
                redirect(base_url() . 'hourlyrate');                
            }
        } else {

            $data['id'] = $id;
            $data['min'] = $this->auto_model->getFeild('min_hourly_rate', 'hourly_rate_based_projects_budgets_range', 'id', $id);
            $data['max'] = $this->auto_model->getFeild('max_hourly_rate', 'hourly_rate_based_projects_budgets_range', 'id', $id);
            $this->layout->view('edit', $lay, $data);
        }
    } 
    // This method is used to remove hourly rate range data
    public function delete() {
     
        $id = $this->uri->segment(3);        
                                       
        $delete = $this->hourlyrate_model->delete($id);     
        if ($delete) {
            $this->session->set_flashdata('succ_msg', 'Horuly rate range Deleted Successfully');
        } else {
            $this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');
        }
         redirect(base_url() . 'hourlyrate');          
    }
    
}    