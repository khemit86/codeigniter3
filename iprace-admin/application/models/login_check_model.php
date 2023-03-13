<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
	
    public function login() {
        $username = trim($this->input->post("username"));
        $password = $this->input->post("password");
        $response = array();
        $this->db->select('admin_id,  username, email,type,last_login,password');
        $query = $this->db->get_where("admin", array("username" => $username, "password" => md5($password), "status" => '1'));
        $result = $query->row();
        if (count($result) == 0) {
            $response['status'] = "error";
        } else {
            $response['status'] = "success";
            $this->session->set_userdata('user', $result);
        }
        return $response;
    }

    public function register() {
        $response = array();
        $data = array(
            'username' => $this->input->post('username'),
            'name' => $this->input->post('name'),
            'password' => md5($this->input->post('password')),
            'email' => $this->input->post('email'),
            'created_date' => date('Y-m-d h:m:s'),
            'status' => 1
        );
        parent::insert("users", $data);
//        if ($this->db->insert('users', $data)) {
        if (is_array($data)) {
            $response['status'] = "success";
            $response['id'] = "";
        } else {
            $response['status'] = "error";
            $response['id'] = "";
        }
        return $response;
    }

}
