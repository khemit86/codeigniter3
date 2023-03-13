<?php
	if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings extends MX_Controller {
    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    public function __construct() {
        $this->load->model('settings_model');
        $this->load->library('form_validation');
        $this->load->library('editor');
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('ckeditor');
    }
 
    


  

    public function pass_edit($aid = '') {
        $data['data'] = $this->auto_model->leftPannel();
        $lay['lft']   = "inc/section_left";
        if ($aid == '') {
            $ses_data   = $this->session->userdata('user');
            $data['id'] = $id = $ses_data->admin_id;
        } else {
            $data['id'] = $id = $aid;
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('old_pass', 'Old Password', 'required');
            $this->form_validation->set_rules('new_pass', 'New Password', 'required');
            // $this->form_validation->set_rules('con_pass', 'Confirm Password', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('password_edit', $lay, $data);
            } else {
                $update = $this->settings_model->update_pass($id);
                if ($update > 0) {
                    $username   = $this->auto_model->getFeild('username', 'admin', 'admin_id', $id);
                    $email      = $this->auto_model->getFeild('email', 'admin', 'admin_id', $id);
                    $from       = ADMIN_EMAIL;
                    $to         = $email;
                    $template   = 'change_password';
                    $data_parse = ['username' => $username,
                                   'password' => $this->input->post('new_pass'),
                    ];
                    $this->session->set_flashdata('succ_msg', 'Password Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to Update');
                }
                redirect(base_url() . 'settings/pass_edit');
            }
        } else {
            $this->layout->view('password_edit', $lay, $data);
        }
    }

   
}
