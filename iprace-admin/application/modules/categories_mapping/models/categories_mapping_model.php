<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class categories_mapping_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
    /**
     * This method is used to get all parent project categories to fill project category select option
    */
    public function get_all_parent_projects_categories() {
        $this->db->select('*');
        $this->db->from('categories_projects');
        $this->db->where(['parent_id'=> 0, 'status' => 'Y']);
        $this->db->order_by('name');
        return $this->db->get()->result_array();
    }
    /**
     * This method is used to get all parent professional categories to fill professional category select option
    */
    public function get_all_parent_professionals_categories() {
        return $this->db->from('categories_professionals')->where(['parent_id'=> 0, 'status' => 'Y'])->order_by('name')->get()->result_array();
    }
    /**
     * This method is used to save project categories and professional category mapping detail into table
    */
    public function save_projects_professionals_categories_mapping_data($data) {
        $this->db->empty_table('projects_professionals_categories_mapping_tracking');
        if(!empty($data)) {
            $this->db->insert_batch('projects_professionals_categories_mapping_tracking', $data); 
        }
    }
    /**
     * This method id used to get all project professional category mapping data to display on category mapping view
    */
    public function get_all_projects_professionals_categories_mapping_data() {
        return $this->db->get('projects_professionals_categories_mapping_tracking')->result_array();
    }
}
