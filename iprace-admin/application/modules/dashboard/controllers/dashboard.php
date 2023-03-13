<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('user_model');        
        parent::__construct();
    }

    public function index() { 
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft'] = "inc/section_left";
		$data['total_member'] = $this->user_model->count_member();
		$data['active_member'] = $this->user_model->count_member('Y');
		$data['inactive_member'] = $this->user_model->count_member('N');
		$data['suspended_member'] = $this->user_model->count_member('S');
        $data['total_project'] = 0;
		$data['open_project'] = 0;
		$data['working_project'] = 0;
		$data['frozen_project'] = 0;
		$data['complete_project'] = 0;
		$data['expire_project'] = 0;
		$data['free_member'] = 0;
		$data['silver_member'] = 0;
		$data['gold_member'] = 0;
		$data['platinum_member'] = 0;
		$data['total_registration_2']=0;
		$data['paid_registration_2']=0;
		$data['free_registration_2']=0;
		$data['total_registration_1']=0;
		$data['paid_registration_1']=0;
		$data['free_registration_1']=0;
		$data['total_registration_current']=0;
		$data['paid_registration_current']=0;
		$data['free_registration_current']=0;
		
        $this->layout->view('dashboard', $lay, $data);
    }

    public function login() {
        $result = $this->user_model->login();
        echo json_encode($result);
    }

    public function register() {
        if ($this->input->post('submit')) {
            $result = $this->user_model->register();
            echo json_encode($result);
            return;
        }
        $param = "";
        $this->load->view('register', $param);
    }

    public function logout() {
        $this->session->unset_userdata('user');
    }             
    
}
