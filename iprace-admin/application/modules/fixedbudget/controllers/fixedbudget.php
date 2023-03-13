<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fixedbudget extends MX_Controller {

    //private $auto_model;
	 
    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('fixedbudget_model');
        $this->load->library('form_validation');        
		parent::__construct();
		$this->load->helper('url');
    }
    
    public function index(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $data['list'] = $this->fixedbudget_model->getFixed();

        $this->layout->view('list', $lay, $data);
    }
    
    public function add(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        if ($this->input->post()) {
            $this->form_validation->set_rules('min_budget', 'Min Budget', 'required');            
            $this->form_validation->set_rules('max_budget', 'Max Budget', 'required'); 

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add', $lay, $data);
            } else {
                $post_data = $this->input->post();
                $post['min_budget'] = $post_data['min_budget'];
                $post['max_budget'] = $post_data['max_budget'];
                $insert = $this->fixedbudget_model->add_fixed($post);

                if ($insert) {
                    $this->session->set_flashdata('succ_msg', 'Fixed Budget Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'fixedbudget');
            }
        } else {
            $this->layout->view('add', $lay, $data);
        }
    }   
    
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
			$data['min_budget'] = $post['min_budget'] = $post_data['min_budget'];
            if($post_data['max_budget'] == ""){
                $data['max_budget'] = $post['max_budget'] = "All";
            }else{
                $data['max_budget'] = $post['max_budget'] = $post_data['max_budget'];
            }	
            $this->form_validation->set_rules('min_budget', 'Min Budget', 'required');   

            if ($this->form_validation->run() == FALSE) {				
                $this->layout->view('edit', $lay,$data);
            } else {
                $update = $this->fixedbudget_model->updatefixed($post,$id);
				
                if ($update) {
                    $this->session->set_flashdata('succ_msg', 'Fixed Budget Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Update Successfully');
                }
                redirect(base_url() . 'fixedbudget');                
            }
        } else {

            $data['id'] = $id;
            $data['min_budget'] = $this->auto_model->getFeild('min_budget', 'fixed_budget_projects_budgets_range', 'id', $id);
            $data['max_budget'] = $this->auto_model->getFeild('max_budget', 'fixed_budget_projects_budgets_range', 'id', $id);

            $this->layout->view('edit', $lay, $data);
        }
    } 
    
    public function deletefixed() {
     
        $id = $this->uri->segment(3);        
                                       
        $delete = $this->fixedbudget_model->delete_all($id);     
        if ($delete) {
            $this->session->set_flashdata('succ_msg', 'Fixed Budget Deleted Successfully');
        } else {
            $this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');
        }
         redirect(base_url() . 'fixedbudget');          
    }
    
   
    
}    