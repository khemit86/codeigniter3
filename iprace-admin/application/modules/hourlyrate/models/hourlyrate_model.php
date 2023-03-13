<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Hourlyrate_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
    // get all hourly rate range data
    public function get_all_data(){
        $this->db->select('id,min_hourly_rate,max_hourly_rate');
        $this->db->order_by("min_hourly_rate", "ASC");
        $rs = $this->db->get('hourly_rate_based_projects_budgets_range');
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = array(
                'id' => $row->id,
                'min' => $row->min_hourly_rate,
                'max' => $row->max_hourly_rate
            );
        }
        return $data;
    }
    // Add new hourly rate range
    public function add($data){
        return $this->db->insert('hourly_rate_based_projects_budgets_range', $data);
    }
    // update existing hourly rate range
    public function update($data,$id)
	{
		$this->db->where('id',$id);
		return $this->db->update('hourly_rate_based_projects_budgets_range',$data);
	} 
    // remove existing hourly rate range
    public function delete($id){
        return $this->db->delete('hourly_rate_based_projects_budgets_range', array('id' => $id));
    }
    
}