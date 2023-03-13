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

    public function leftPannel() {
        $result = array();
        $this->db->select();
        $query = $this->db->get_where("adminmenu", array("status" => 'Y', "parent_id" => 0));

        $i = 0;
        foreach ($query->result() as $row) {
            $i++;
            $result[$i]['id'] = $row->id;
            $result[$i]['name'] = $row->name;
            $result[$i]['url'] = $row->url;
            $result[$i]['parent_id'] = $row->parent_id;
            $result[$i]['status'] = $row->status;
            $result[$i]['title'] = $row->title;
            $result[$i]['style_class'] = $row->style_class;
        }
        return $result;
    }

    public function leftpanelchild($id) {
        $result = array();
        $this->db->select();
        $query = $this->db->get_where("adminmenu", array("status" => 'Y', "parent_id" => $id));
        $i = 0;
        foreach ($query->result() as $row) {
            $i++;
            $result[$i]['id'] = $row->id;
            $result[$i]['name'] = $row->name;
            $result[$i]['url'] = $row->url;
            $result[$i]['parent_id'] = $row->parent_id;
            $result[$i]['status'] = $row->status;
            $result[$i]['title'] = $row->title;
            $result[$i]['style_class'] = $row->style_class;
        }
        return $result;
    }

    public function setting() {
        $result = array();
        $this->db->select(setting);
        $query = $this->db->get_where("setting", array());
        $query->result();

        return $result;
    }

}
