<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Fulltime_salary_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
    // Get all fulltime salary range data
    public function get_all_data(){
        $this->db->select('id,min_salary,max_salary');
        $this->db->order_by("min_salary", "ASC");
        $rs = $this->db->get('fulltime_projects_salaries_range');
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = array(
                'id' => $row->id,
                'min' => $row->min_salary,
                'max' => $row->max_salary
            );
        }
        return $data;
    }
    // Add new fulltime salary range
    public function add($data){
        return $this->db->insert('fulltime_projects_salaries_range', $data);
    }
    // Update existing fulltime salary range
    public function update($data,$id)
	{
		$this->db->where('id',$id);
		return $this->db->update('fulltime_projects_salaries_range',$data);
	} 
    // Remove fulltime salary range by id    
    public function delete($id){
        return $this->db->delete('fulltime_projects_salaries_range', array('id' => $id));
    }
    
}