<?php
	if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories_professionals extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('categories_professionals_model');
        $this->load->library('form_validation');
        parent::__construct();
    }
    // This method is used to load parent category listing
    public function index() {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $data['list'] = $this->categories_professionals_model->get_categories();

        $this->layout->view('index', $lay, $data);
    }

    /////////////// Menu Add ///////////////////////////////////////////////
    // This method is used to add new category into db
    public function add() {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required|is_unique[categories_professionals.name]');
            $this->form_validation->set_rules('status', '', '');
            $this->form_validation->set_rules('parent_id', '', '');

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add', $lay, $data);
            } else {
                $post_data = $this->input->post();
				$post_data['name'] = trim($post_data['name']);
				
                $insert = $this->categories_professionals_model->add_category($post_data);

                if ($insert) {
                    $this->session->set_flashdata('succ_msg', 'Category Inserted Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
                redirect(base_url() . 'categories_professionals/');
            }
        } else {
            $this->layout->view('add', $lay, $data);
        }
    }
    // This method is used to edit existing category
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
			$data['name'] = $post['name'] = trim($post_data['name']);
			$data['parent_id'] = $post['parent_id'] =  $post_data['parent_id'];
			//$data['parent_name'] = $post['parent_name'] =  $post_data['parent_name'];
			$data['status'] = $post['status'] =  $post_data['status'];
			$parent = $post_data['parent'];
			
			
			
			
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('status', '', '');
            $this->form_validation->set_rules('parent_id', '', '');	

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('edit', $lay,$data);
            } else {
                $update = $this->categories_professionals_model->update_category($post,$id);
                //$update = true;
                if ($update) {
					if($post_data['parent_id'] != 0){
						
						$type = 's';
					}else{
						$type = 'c';
					}
					
					if($data['status'] == 'N') {
						 if($type == 's') {
							 $category_listing = $this->db->get_where('professionals_areas_of_expertise_listings_tracking', ['professional_category_id' => $id])->result_array();
							$professional_categories = $this->db->get('professionals_areas_of_expertise_listings_tracking')->result_array();
							
							if(!empty($category_listing)) {
								foreach($category_listing as $val) {

									$cnt = 0;
									foreach($professional_categories as $pcat) {
										if($pcat['user_id'] == $val['user_id'] && $val['professional_parent_category_id'] == $pcat['professional_parent_category_id']) {
											$cnt++;
										}
									}
									if($cnt == 1) {
										$category_data = [
											'professional_category_id' => $val['professional_parent_category_id'],
											'professional_parent_category_id' => 0
										];
										$this->db->update('professionals_areas_of_expertise_listings_tracking', $category_data, ['id' => $val['id']]);
									} else {
										$this->db->where('professional_parent_category_id', $id);
										$this->db->or_where('professional_category_id', $id);
										$this->db->delete('professionals_areas_of_expertise_listings_tracking');            
									}
								}
							}
						 }else if($type == 'c') {
							$this->db->where('professional_parent_category_id', $id);
							$this->db->or_where('professional_category_id', $id);
							$this->db->delete('professionals_areas_of_expertise_listings_tracking');

							// remove entry from projects_professionals_categories_mapping_tracking table when professional category disabled by admin
							$this->db->delete('projects_professionals_categories_mapping_tracking', ['professionals_category_id' => $id]);
						}
					
					}
				
                    $this->session->set_flashdata('succ_msg', 'Category Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Update Successfully');
                }
                if($post_data['parent_id']== 0){
                    redirect(base_url() . 'categories_professionals');
                }else{
					$this->session->set_userdata('parent_id',$post_data['parent_id']); 
					redirect(base_url() . 'categories_professionals/subcategories');
                    //redirect(base_url() . 'categories_professionals/subcategories');
                }
                
            }
        } else {

            $data['id'] = $id;
            $data['name'] = $this->auto_model->getFeild('name', 'categories_professionals', 'id', $id);
            $data['parent_id'] = $this->auto_model->getFeild('parent_id', 'categories_professionals', 'id', $id);
            $data['parent_name'] = $this->auto_model->getFeild('name', 'categories_professionals', 'id', $data['parent_id']);
            $data['status'] = $this->auto_model->getFeild('status', 'categories_professionals', 'id', $id);
			
			if($data['parent_id'] != 0)
			{
				
				$this->db->where('id', $data['parent_id']);                          
				$rs = $this->db->get("categories_professionals")->result_array();
				$rs = array_shift($rs);
				$data['parent_status'] = $rs['status'];
			}
			
            $this->layout->view('edit', $lay, $data);
        }
    }
    // This method is used to remove existing category
    public function delete_category() {
        if($this->input->is_ajax_request ()){
			$id = $this->input->post('section_id');
			$is_sub = $this->db->where('id',$id)->get('categories_professionals')->result_array();        
			$sub    = $is_sub[0]['parent_id'];
			$status =200;							   
			if($sub == 0){
				$delete = $this->categories_professionals_model->delete_parent_child_categories($id);     
				if ($delete) {
					$message = 'Category Deleted Successfully';
					//$this->session->set_flashdata('succ_msg', 'Category Deleted Successfully');
				} else {
					$message = 'Unable to Delete Successfully';
					$status =400;
					//$this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');var_dump('asjg');exit;
				}
				//redirect(base_url() . 'categories_professionals');    
			}else{            
				$delete = $this->categories_professionals_model->delete_category($id);       
				if ($delete) {
					$message = 'Sub-category Deleted Successfully';
					//$this->session->set_flashdata('succ_msg', 'Sub-category Deleted Successfully');
				} else {
					//$this->session->set_flashdata('error_msg', 'Unable to Delete Successfully');
					$message = 'Unable to Delete Successfully';
					$status =400;	
				}
				//redirect(base_url() . 'categories_professionals/subcategories#listrefreshed');
			}
			$msg['status'] = $status;
			$msg['message'] = $message;
			echo json_encode ($msg);die;
		}	
    }
	// This method is used to update status of category
	public function change_category_status()
	{
		if($this->input->is_ajax_request ()){
		$status =200;
		$id = $this->input->post('section_id');
		$data_type = $this->input->post('data_type');
		$c_type = $this->input->post('c_type');
		if($data_type == 'inact')
			$data['status'] = 'N';
		if($data_type == 'act')
			$data['status'] = 'Y';
		
		$update = $this->categories_professionals_model->updatecategory($data,$id,$c_type);
		$get_child_categories = $this->categories_professionals_model->get_child_categories_id($id);
		if($data_type == 'inact'){
			if(!empty($get_child_categories))
			{
				foreach($get_child_categories as $key=>$value){
					 $this->db->where('id', $value->id);
					 $post = array('status'=>'N');
					 $this->db->update('categories_professionals', $post);
				}
			} 
		}
		
		if ($update) {
			if($data_type == 'inact'){
				//$this->session->set_flashdata('succ_msg', 'Inactive Successfully Done...');
				$message = 'Inactive Successfully Done...';
				$msg['data'] = "<a class='change_category_status i-checkmark-3 red' href='javascript:;' data-msg='Do you want to active this?' data-type='act' data-status='N' data-id='".$id."' >&nbsp;</a>";
			}	
			if($data_type == 'act'){
				//$this->session->set_flashdata('succ_msg', 'Activation Successfully Done...');
				$message = 'Activation Successfully Done...';
				$msg['data'] = "<a class='change_category_status i-checkmark-3 green' href='javascript:;' data-msg='Do you want to inactive this?' data-type='inact' data-status='Y' data-id='".$id."' >&nbsp;</a>";
			}	
			
		} else {
			//$this->session->set_flashdata('error_msg', 'unable to update');
			$message = 'unable to update';
			$msg['data'] = "";
			$status =400;
		}
		//$status = $this->uri->segment(5);
		//redirect(base_url() . 'categories_professionals');
			$msg['status'] = $status;
			$msg['message'] = $message;
			
			echo json_encode ($msg);die;
		}
	
	}
    // This method is used to load sub categories view
	public function subcategories(){
		
	
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        $data['cat'] = $this->categories_professionals_model->get_category_list();
        $data['listsub'] = $this->categories_professionals_model->get_subcategories(); 
		$parent_id = $this->session->userdata('parent_id');
		$this->session->unset_userdata('parent_id');
		$data['parent_id'] = $parent_id;
		
		
        $this->layout->view('list_subcategory', $lay, $data);
	}
    // This method is used to load sub category view via ajax
    public function loadajax(){
        $id = $this->input->post('pa_id');
        $result = $this->categories_professionals_model->load_subcategory($id);
        echo $result;
    }
    // This method is used to add new sub category to db
    public function add_subcategory(){
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";

        $data['cat'] = $this->categories_professionals_model->get_category_list();
        if ($this->input->post()) {
		
            $this->form_validation->set_rules('name', 'Name', 'required|is_unique[categories_professionals.name]');
            $this->form_validation->set_rules('status', '', '');
            $this->form_validation->set_rules('parent_id', 'Category Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('add_subcategory', $lay, $data);
            } else {
				
                $post_data = $this->input->post();
				$post_data['name'] = trim($post_data['name']);
                $insert = $this->categories_professionals_model->add_category($post_data);

                if ($insert) {//
                    $this->session->set_flashdata('succ_msg', 'Category Inserted Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Insert Successfully');
                }
				$this->session->set_userdata('parent_id', $post_data['parent_id']);
                redirect(base_url() . 'categories_professionals/subcategories');
            }
        } else {
            $this->layout->view('add_subcategory', $lay, $data);
        }
    }
    // This method is used to change status for sub category
    public function change_subcategory_status()
	{
		if($this->input->is_ajax_request ()){
			$status =200;
			$id = $this->input->post('section_id');
			$data_type = $this->input->post('data_type');
			$c_type = $this->input->post('c_type');
			if($data_type == 'inact'){
				$data['status'] = 'N';
			}	
			if($data_type == 'act'){
				$data['status'] = 'Y';
			}
			$update = $this->categories_professionals_model->updatecategory($data,$id,$c_type);
			
			if ($update) {
				if($data_type == 'inact'){
					
					//$this->session->set_flashdata('succ_msg', 'Inactive Successfully Done...');
					$message = 'Inactive Successfully Done...';
					$msg['data'] = "<a class='change_sub_category_status i-checkmark-3 red' href='javascript:;' title='Inactive' data-msg='Do you want to active this?' data-type='act' data-status='N' data-id='".$id."' >&nbsp;</a>";
				}	
				if($data_type == 'act'){
					//$this->session->set_flashdata('succ_msg', 'Activation Successfully Done...');
					$message = 'Activation Successfully Done...';
					$msg['data'] = "<a title='Active' class='change_sub_category_status i-checkmark-3 green' href='javascript:;' data-msg='Do you want to inactive this?' data-type='inact' data-status='Y' data-id='".$id."' >&nbsp;</a>";
				}	
				
			} else {
				$message = 'unable to update';
				$msg['data'] = "";
				$status =400;
				//$this->session->set_flashdata('error_msg', 'unable to update');
			}
			//$status = $this->uri->segment(5);
			//redirect(base_url() . 'categories_professionals/subcategories');
			$msg['status'] = $status;
			$msg['message'] = $message;
			
			echo json_encode ($msg);die;
		}
	
	}
}
