<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings_model extends BaseModel {
    public function __construct() {
        return parent::__construct();
    }

   

    public function update_pass($id) {
        $old_pass = md5($this->input->post('old_pass'));
        $new_pass = md5($this->input->post('new_pass'));
        $this->db->where(["admin_id" => $id, "password" => $old_pass]);
        $this->db->update("admin", ["password" => $new_pass]);
        return $this->db->affected_rows();
    }
    
}
