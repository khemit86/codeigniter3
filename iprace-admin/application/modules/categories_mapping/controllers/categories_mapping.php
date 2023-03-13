<?php
	if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories_mapping extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('categories_mapping_model');
        $this->load->library('form_validation');
        parent::__construct();
    }
    // This method is used to load parent category listing
    public function index() {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
        $data['projects_category'] = $this->categories_mapping_model->get_all_parent_projects_categories();
        $data['professional_category'] = $this->categories_mapping_model->get_all_parent_professionals_categories();
        $data['categories_mapping_data'] = $this->categories_mapping_model->get_all_projects_professionals_categories_mapping_data();
        $this->layout->view('index', $lay, $data);
    }
    /**
     *  This method is used to save detail of categories mapping details to table
     * */ 
    public function save_projects_professionals_categories_mapping_data() {
        $data = $this->input->post('data');
        $this->categories_mapping_model->save_projects_professionals_categories_mapping_data($data);
        $msg['status'] = 200;
        echo json_encode($msg);
        return;
    }
}
