<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fulltime_salary extends MX_Controller {

    //private $auto_model;
	 
    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('fulltime_salary_model');
        $this->load->library('form_validation');        
		parent::__construct();
		$this->load->helper('url');
    }
    // This method is used to display listing of fulltime salary range    
    public function index(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        $data['list'] = $this->fulltime_salary_model->get_all_data();
        $this->layout->view('list', $lay, $data);
    }
    // This method is used to insert new fulltime salary range 
    public function add(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        if ($this->input->post()) {
            $this->form_validation->set_rules('min', 'Min Salary', 'required');            
            $this->form_validation->set_rules('max', 'Max Salary', 'required');                        
            $this->form_validation->set_rules('status', '', '');            

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add', $lay, $data);
            } else {
                $post_data = $this->input->post();
                $post['min_salary'] = $post_data['min'];
                $post['max_salary'] = $post_data['max'];
                $insert = $this->fulltime_salary_model->add($post);

                if ($insert) {
                    $this->session->set_flashdata('succ_msg', 'Fulltime salary Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'fulltime_salary');
            }
        } else {
            $this->layout->view('add', $lay, $data);
        }
    }   
    // This method is used to manage edit and update functionality of fulltime salary range
    public function edit() {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $id = $this->uri->segment(3);
        if ($id == '' OR $id == 0) {
            $id = set_value('id');
        }
        if ($this->input->post()) {
			$post_data = $this->input->post();
			$id = $post_data['id'];
            $data['min_salary'] = $post_data['min'];
            $post['min_salary'] = $post_data['min'];
            if($post_data['max'] == ""){
                $data['max_salary'] = $post_data['max'] = "All";
                $post['max_salary'] = $post_data['max'];
            }else{
                $data['max_salary'] = $post_data['max'];
                $post['max_salary'] = $post_data['max'];
            }		            		
            $this->form_validation->set_rules('min', 'Min Salary', 'required');                     
            $this->form_validation->set_rules('status', '', '');            

            if ($this->form_validation->run() == FALSE) {				
                $this->layout->view('edit', $lay,$data);
            } else {
                $update = $this->fulltime_salary_model->update($post, $id);
				
                if ($update) {
                    $this->session->set_flashdata('succ_msg', 'Fulltime salary Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Update Successfully');
                }
                redirect(base_url() . 'fulltime_salary');                
            }
        } else {

            $data['id'] = $id;
            $data['min'] = $this->auto_model->getFeild('min_salary', 'fulltime_projects_salaries_range', 'id', $id);
            $data['max'] = $this->auto_model->getFeild('max_salary', 'fulltime_projects_salaries_range', 'id', $id);

            $this->layout->view('edit', $lay, $data);
        }
    } 
    // this method is used to remove entry from fulltime project salary range table
    public function delete() {
        $id = $this->uri->segment(3);        
        $delete = $this->fulltime_salary_model->delete($id);     
        if ($delete) {
            $this->session->set_flashdata('succ_msg', 'Fulltime salary Deleted Successfully');
        } else {
            $this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');
        }
        redirect(base_url() . 'fulltime_salary');          
    }
}    