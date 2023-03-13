<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Adminuser_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }

	
	public function getAllUserList($limit = '', $start = '')
	{
		$this->db->select('*');
		$this->db->order_by('admin_id','desc');
		$this->db->limit($limit, $start);
		$rs = $this->db->get('admin');
		$data = array();
		foreach($rs->result() as $row)
		{
			$data[] = array(
				'admin_id' => $row->admin_id,
				'username' => $row->username,
				'email' => $row->email,
				'type' => $row->type,
				'status' => $row->status
			);
			
		}
		
		return $data;
	}
	
	public function add_user($data)
	{
		return $this->db->insert('admin',$data);

	}
	
	public function updateUser($data,$id)
	{
		/*echo "<pre>";
			print_r($data);die;*/
		$this->db->where('admin_id',$id);
		return $this->db->update('admin',$data);
		
	
	}
	
	public function getAPerticulerFooterDataUsingId($id)
	{
		$this->db->select('*');
		
		$rs = $this->db->get_where('admin',array('admin_id'=>$id));
		$data = array();
		$gl=$rs->row();
			$data = array(
				'admin_id' => $gl->admin_id,
				'username' => $gl->username,
				'email' => $gl->email,
				'type' => $gl->type,
				'status' => $gl->status
			);
			
		/*echo "<pre>";
		print_r($data);die;*/
		return $data;
	}
	
	public function deleteUser($id)
	{
		return $this->db->delete('admin', array('admin_id' => $id)); 
	
	}
		
	public function getleftmenu()
	{
		$this->db->select('id,name');
		$this->db->where('parent_id','0');
		$res=$this->db->get_where('adminmenu',array('status'=>'Y'));
		$data=array();
		foreach($res->result() as $row)
		{
			$data[]=array(
			'id'=>$row->id,
			'name'=>$row->name
			);	
		}
		return $data;	
	}
	

}
