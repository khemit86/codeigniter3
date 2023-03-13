<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminsettings_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
	
	
	
	///// Edit user ///////////////////////////////
	public function update_admin($post)
	{
		$data = array(
               'username' => $post['user_name'],
               'password' => md5($post['newpass']),
			   'email' => $post['email']);
		$this->db->where('admin_id', $post['admin_id']);
		return $this->db->update('admin', $data);
	}
	public function update_admin_profile($post)
	{
		$data = array(
               'username' => $post['user_name'],
			   'email' => $post['email']);
		$this->db->where('admin_id', $post['admin_id']);
		return $this->db->update('admin', $data);
	}
	public function checkPass($id,$pass)
	{
		$this->db->select('username');
		$this->db->where('admin_id',$id);
		$this->db->where('password',md5($pass));
		$this->db->from('admin');
		return $this->db->count_all_results();	
	}

	
}
