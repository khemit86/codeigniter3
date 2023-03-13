<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminsettings extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password 
     */
    public function __construct() {
        $this->load->model('Adminsettings_model');
        $this->load->library('form_validation');
        parent::__construct();
    }

    public function index() {
        $data['data'] = $this->auto_model->leftPannel();
        $this->load->helper('date');
        $lay['lft'] = "inc/section_left";
		
        /*$ses_data = $this->session->userdata('user');
        $id = $ses_data->admin_id;

        if ($this->input->post()) {


            $this->input->post('admin_id');
            $this->form_validation->set_rules('user_name', 'username', '');
            $this->form_validation->set_rules('password', 'Password', '');
            $this->form_validation->set_rules('reg_date', 'Reg date', '');



            if ($this->form_validation->run() == FALSE) {
                $this->layout->view('list', $lay, $data);
            } else {

                $post_data = $this->input->post();
                $update = $this->Adminsettings_model->update_admin($post_data);

                if ($update) {
                    $this->session->set_flashdata('succ_msg', 'City Updated Successfully');
                } else {
                    $this->session->set_flashdata('error_msg', 'Unable to User ');
                }
                redirect(base_url() . 'adminsettings');
            }
        } else {

            $data['admin_id'] = $id;
            $data['username'] = $this->auto_model->getFeild('username', 'admin', 'admin_id', $id);
            $data['email'] = $this->auto_model->getFeild('email', 'admin', 'admin_id', $id);
            $data['password'] = $this->auto_model->getFeild('password', 'admin', 'admin_id', $id);
            $data['type'] = $this->auto_model->getFeild('type', 'admin', 'admin_id', $id);
            $data['reg_date'] = $this->auto_model->getFeild('reg_date', 'admin', 'admin_id', $id);
        }
*/


        $this->edit();
    }

    /////edit admin/////

     public function edit()
      {
	  
	 $data['data'] = $this->auto_model->leftPannel();
      $lay['lft']="inc/section_left";
      $ses_data =$this->session->userdata('user');
      $id = $ses_data->admin_id;
	  $data['admin_id'] = $id;
      $data['username'] = $this->auto_model->getFeild('username', 'admin', 'admin_id', $id);
      $data['email'] = $this->auto_model->getFeild('email', 'admin', 'admin_id', $id);
      $data['password'] = $this->auto_model->getFeild('password', 'admin', 'admin_id', $id);
      $data['type'] = $this->auto_model->getFeild('type', 'admin', 'admin_id', $id);
      $data['reg_date'] = $this->auto_model->getFeild('reg_date', 'admin', 'admin_id', $id);
      if ($this->input->post()) {

      //$this->form_validation->set_rules('avail_to_do_task_in', 'Available', 'required');
      $this->input->post('admin_id');
      $this->form_validation->set_rules('user_name', 'username', 'required|min_lengh[5]');
	  $this->form_validation->set_rules('email', 'email', 'required|valid_email');
	  if($this->input->post('oldpass')!='' || $this->input->post('newpass')!='' || $this->input->post('confpass')!='')
	  {
      $this->form_validation->set_rules('oldpass', 'Old Password', 'required|min_lengh[5]');
      $this->form_validation->set_rules('newpass', 'New Password', 'required|min_lengh[5]|matches[confpass]');
	  $this->form_validation->set_rules('confpass', 'Confirm Password', 'required|min_lengh[5]');
	  }



      if ($this->form_validation->run() == FALSE) {
      $this->layout->view('list', $lay, $data);
      }
      else
      {
		  if($this->input->post('oldpass')!='' || $this->input->post('newpass')!='' || $this->input->post('confpass')!='')
	  {
		  $passcheck=$this->Adminsettings_model->checkPass( $this->input->post('admin_id'), $this->input->post('oldpass'));
		if($passcheck>0)
		{
		  $post_data = $this->input->post();
		  $update = $this->Adminsettings_model->update_admin($post_data);
		
		  if ($update) {
			$from=ADMIN_EMAIL;
			$to=$this->input->post('email');
			$template='change_password';
			$data_parse=array('username'=>$this->input->post('username'),
								'password'=>$this->input->post('newpass')
								);
		  	$this->session->set_flashdata('succ_msg', 'Admin settings Updated Successfully');
		  } else {
		  	$this->session->set_flashdata('error_msg', 'Unable to Update');
		  }
		}
		else
		{
			$this->session->set_flashdata('error_msg',"Old password doesn't match. Please try again.");	
		}
	  }
	  else
	  {
			$post_data = $this->input->post();
		  $update = $this->Adminsettings_model->update_admin_profile($post_data);
		
		  if ($update) {
		  	$this->session->set_flashdata('succ_msg', 'Admin settings Updated Successfully');
		  } else {
		  	$this->session->set_flashdata('error_msg', 'Unable to Update');
		  }
		}
      redirect(base_url() . 'adminsettings');
      }
      } else {

      $data['admin_id'] = $id;
      $data['username'] = $this->auto_model->getFeild('username', 'admin', 'admin_id', $id);
      $data['email'] = $this->auto_model->getFeild('email', 'admin', 'admin_id', $id);
      $data['password'] = $this->auto_model->getFeild('password', 'admin', 'admin_id', $id);
      $data['type'] = $this->auto_model->getFeild('type', 'admin', 'admin_id', $id);
      $data['reg_date'] = $this->auto_model->getFeild('reg_date', 'admin', 'admin_id', $id);

      }




      $this->layout->view('list', $lay, $data);
      } 
}
