<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories_projects extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('categories_projects_model');
        $this->load->library('form_validation');
        parent::__construct();
		}

     public function index() {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $data['list'] = $this->categories_projects_model->get_categories();

        $this->layout->view('index', $lay, $data);
    }

    /////////////// Menu Add ///////////////////////////////////////////////
    public function add() {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";


        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required|is_unique[categories_projects.name]');
            $this->form_validation->set_rules('status', '', '');
            $this->form_validation->set_rules('parent_id', '', '');

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add', $lay, $data);
            } else {
                $post_data = $this->input->post();
                $insert = $this->categories_projects_model->add_category($post_data);

                if ($insert) {
                    $this->session->set_flashdata('succ_msg', 'Category Inserted Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'categories_projects/');
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
			$data['name'] = $post['name'] = $post_data['name'];
			$data['parent_id'] = $post['parent_id'] =  $post_data['parent_id'];
			//$data['parent_name'] = $post['parent_name'] =  $post_data['parent_name'];
			$data['status'] = $post['status'] =  $post_data['status'];
			//$parent = $post_data['parent'];
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('status', '', '');
            $this->form_validation->set_rules('parent_id', '', '');	

            if ($this->form_validation->run() == FALSE) {
				
                $this->layout->view('edit', $lay,$data);
            } else {
			
                $update = $this->categories_projects_model->update_category($post,$id);
				
                if ($update) {
                    $this->session->set_flashdata('succ_msg', 'Category Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Update Successfully');
                }
                if($post_data['parent_id'] == 0){
                    redirect(base_url() . 'categories_projects');
                }else{
                    redirect(base_url() . 'categories_projects/subcategories');
                }
                
            }
        } else {

            $data['id'] = $id;
            $data['name'] = $this->auto_model->getFeild('name', 'categories_projects', 'id', $id);
            $data['parent_id'] = $this->auto_model->getFeild('parent_id', 'categories_projects', 'id', $id);
            $data['parent_name'] = $this->auto_model->getFeild('name', 'categories_projects', 'id', $data['parent_id']);
            $data['status'] = $this->auto_model->getFeild('status', 'categories_projects', 'id', $id);
			if($data['parent_id'] != 0)
			{
				
				$this->db->where('id', $data['parent_id']);                          
				$rs = $this->db->get("categories_projects")->result_array();
				$rs = array_shift($rs);
				$data['parent_status'] = $rs['status'];
			}
			

            $this->layout->view('edit', $lay, $data);
        }
    }

   
    public function delete_category() {
        
        $id = $this->uri->segment(3);
        $is_sub = $this->db->where('id',$id)->get('categories_projects')->result_array();        
        $sub    = $is_sub[0]['parent_id'];
                                       
        if($sub == "0"){
			########### add the code to remove parent category start##########
			
			$this->categories_projects_model->delete_project_parent_category_tracking_data($id);
			########### add the code to remove parent category end############
            $delete = $this->categories_projects_model->delete_parent_child_categories($id);     
            if ($delete) {
                $this->session->set_flashdata('succ_msg', 'Category Deleted Successfully');
            } else {
                $this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');
            }
            redirect(base_url() . 'categories_projects');    
        }else{ 
			$this->categories_projects_model->update_project_child_category_tracking_data($id);
            $delete = $this->categories_projects_model->delete_category($id);       
            if ($delete) {
                $this->session->set_flashdata('succ_msg', 'Sub-category Deleted Successfully');
            } else {
                $this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');
            }
            redirect(base_url() . 'categories_projects/subcategories');
        }
        
    }
	
	
	public function change_category_status()
	{
		
		$id = $this->uri->segment(3);
		if($this->uri->segment(4) == 'inact')
			$data['status'] = 'N';
		if($this->uri->segment(4) == 'act')
			$data['status'] = 'Y';
		
		
		$update = $this->categories_projects_model->updatecategory($data, $id, $this->uri->segment(6));
		$get_child_categories = $this->categories_projects_model->get_child_categories_id($id);
		if($this->uri->segment(4) == 'inact'){
			########### add the code to remove parent category start##########
			
			$this->categories_projects_model->delete_project_parent_category_tracking_data($id);
			
			########### add the code to remove parent category end############
			if(!empty($get_child_categories))
			{
				foreach($get_child_categories as $key=>$value){
					 $this->db->where('id', $value->id);
					 $post = array('status'=>'N');
					 $this->db->update('categories_projects', $post);
				}
			} 
		}
		
		
		if ($update) {
			if($this->uri->segment(4) == 'inact')
				$this->session->set_flashdata('succ_msg', 'Inactive Successfully Done...');
			if($this->uri->segment(4) == 'act')
				$this->session->set_flashdata('succ_msg', 'Activation Successfully Done...');
			
		} else {
			$this->session->set_flashdata('error_msg', 'unable to update');
		}
		$status = $this->uri->segment(5);
		redirect(base_url() . 'categories_projects');
	
	}
	
	
	public function subcategories(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        $data['cat'] = $this->categories_projects_model->get_category_list();
        $data['listsub'] = $this->categories_projects_model->get_subcategories();           
        $this->layout->view('list_subcategory', $lay, $data);
	}
    
    public function loadajax(){
        $id = $this->input->post('pa_id');
        $result = $this->categories_projects_model->load_subcategory($id);
        echo $result;
    }
    
    public function add_subcategory(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $data['cat'] = $this->categories_projects_model->get_category_list();
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required|is_unique[categories_projects.name]');
            $this->form_validation->set_rules('status', '', '');
            $this->form_validation->set_rules('parent_id', 'Category Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add_subcategory', $lay, $data);
            } else {
                $post_data = $this->input->post();
                $insert = $this->categories_projects_model->add_category($post_data);

                if ($insert) {
                    $this->session->set_flashdata('succ_msg', 'Category Inserted Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'categories_projects/subcategories');
            }
        } else {
            $this->layout->view('add_subcategory', $lay, $data);
        }
    }
    
    public function change_subcategory_status()
	{
		$id = $this->uri->segment(3);
		if($this->uri->segment(4) == 'inact')
			$data['status'] = 'N';
		if($this->uri->segment(4) == 'act')
			$data['status'] = 'Y';
		
		
		$update = $this->categories_projects_model->updatecategory($data, $id, $this->uri->segment(6));
		
		if ($update) {
			if($this->uri->segment(4) == 'inact')
				$this->session->set_flashdata('succ_msg', 'Inactive Successfully Done...');
			if($this->uri->segment(4) == 'act')
				$this->session->set_flashdata('succ_msg', 'Activation Successfully Done...');
			
		} else {
			$this->session->set_flashdata('error_msg', 'unable to update');
		}
		$status = $this->uri->segment(5);
		redirect(base_url() . 'categories_projects/subcategories');
	
	}
    
}
