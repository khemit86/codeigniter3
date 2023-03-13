<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Fixedbudget_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
    
    public function getFixed(){
        $this->db->select('id,min_budget,max_budget');
        $this->db->order_by("id", "ASC");
        $rs = $this->db->get('fixed_budget_projects_budgets_range');
        $data = array();
        foreach ($rs->result() as $row) {
            $data[] = array(
                'id' => $row->id,
                'min_budget' => $row->min_budget,
                'max_budget' => $row->max_budget
            );
        }
        return $data;
    }
    
    public function add_fixed($data){
        return $this->db->insert('fixed_budget_projects_budgets_range', $data);
    }
    
    public function updatefixed($data,$id)
	{
		$this->db->where('id',$id);
		return $this->db->update('fixed_budget_projects_budgets_range',$data);
		
	} 
    
    public function delete_all($id){
        return $this->db->delete('fixed_budget_projects_budgets_range', array('id' => $id));
    }
    
}