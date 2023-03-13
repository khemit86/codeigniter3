<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Membership_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
    
    public function getplan(){ 
        $this->db->select("*");
        $this->db->order_by('id');
        // $res=$this->db->get_where("membership_plan",array("status"=>"Y"));
        $res=$this->db->get_where("membership_plans");
        $data=array();
        
        foreach($res->result() as $val){ 
            $data[]=array(
                "id" => $val->id,
                "name" => $val->membership_plan_name,
                "monthly_price" => $val->monthly_price,
                "default_plan" => $val->default_plan,
                "monthly_days" => $val->monthly_availability_days
            );
        }
        return $data;
    }
    
	/*
	 * @sid get current membership plan details
	 * used in membership controller membership_plan method
	*/
	public function get_current_running_plan_by_user_id($user_id) {
		$this->db->select('ud.id, ud.user_id, ud.current_membership_plan_id, mp.membership_plan_name, ud.current_membership_start_date, ud.user_account_balance, ud.bonus_balance, mp.monthly_availability_days, mp.monthly_price');
		$this->db->from('users_details ud');
		$this->db->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id');
		$this->db->where('ud.user_id', $user_id);
		return $this->db->get()->row_array();
	}
	/*
	 * @sid get membership plan tracking information by user id and start date
	 * used in membership controller membership_plan method
	*/
    public function get_membership_plan_tracking_by_user_id_and_start_date($user_id, $start_dt) {
		$this->db->where('user_id', $user_id);
		$this->db->where('current_membership_plan_start_date', $start_dt);
		return $this->db->get('users_membership_tracking')->row_array();
	}
	/*
	 * @sid get default membership plan details
	 * used in membership controller upgrade_plan method
	*/
	public function get_default_membership_plan() {
		$this->db->where('default_plan', 'Yes');
		return $this->db->get('membership_plans')->row_array();
	}

    /**
     * @Kamil get all membership plans prices to display on membership page
     * Used by Membership controller in membership_plan() method
     */
	public function get_all_membership_prices() {
	    $this->db->select('id, monthly_price');
	    $query = $this->db->get('membership_plans');
	    return $query->result();
    }
}
?>