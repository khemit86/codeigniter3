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
                "yearly_price" => $val->yearly_price,
                "default_plan" => $val->default_plan,
                "monthly_days" => $val->monthly_availability_days,
                "yearly_days" => $val->yearly_availability_days,
            );
        }
        return $data;
    }
    
	/*
	 * @sid get current membership plan details
	 * used in membership controller membership_plan method
	*/
	public function get_current_running_plan_by_user_id($user_id) {
		$this->db->select('ud.id, ud.user_id, ud.current_membership_plan_id, mp.membership_plan_name, ud.membership_plan_type, ud.current_membership_start_date, ud.user_account_balance, ud.current_membership_end_date, ud.membership_auto_renews_status, mp.monthly_availability_days, mp.monthly_price, mp.yearly_availability_days, mp.yearly_price');
		$this->db->from('user_details ud');
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
	/*
	 * @sid get membership plan pending downgrade tracking data by user id
	 * used in membership controller upgrade_plan method
	*/
	public function get_membership_plan_pending_downgrade_data_by_user_id($user_id) {
		$this->db->select('*');
		$this->db->from('users_membership_plans_pending_downgrade_tracking');
		$this->db->where('user_id', $user_id);
		return $this->db->get()->row_array();
	}
	/*
	 * @sid Get manual upgraded plan info based on user id and end date
	 * used in membership controller upgrade_plan method
	*/
	public function get_manual_upgraded_membership_plan_detail($user_id) {
		$this->db->select('*');
		$this->db->from('manual_renewal_membership_plan_tracking');
		$this->db->where('user_id', $user_id);
		// $this->db->where('membership_plan_expiration_date', $end_dt);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

    /**
     * @Kamil get all membership plans prices to display on membership page
     * Used by Membership controller in membership_plan() method
     */
	public function get_all_membership_prices() {
	    $this->db->select('id, monthly_price, yearly_price');
	    $query = $this->db->get('membership_plans');
	    return $query->result();
    }
	/**
     * @sid Get count of renewal membership plan based on membership_plan_renewal_period
     * Used by Membership controller in membership_plan() method
    */
	public function get_count_of_renewal_membership_plan($uid) {
		$this->db->select('membership_plan_renewal_period, count(id) as cnt');
		$this->db->from('serv_manual_renewal_membership_plan_tracking');
		$this->db->where('user_id', $uid);
		$this->db->group_by('membership_plan_renewal_period');
		return $this->db->get()->result_array();
	}
	/**
     * @sid Get sum of renewal membership plan based on membership_plan_renewal_period
     * Used by Membership controller in membership_plan() method
    */
	public function get_sum_of_price_of_renewal_membership_plan_by_renewal_period($uid) {
		$this->db->select('membership_plan_renewal_period, sum(membership_renewal_price) as total');
		$this->db->from('serv_manual_renewal_membership_plan_tracking');
		$this->db->where('user_id', $uid);
		$this->db->group_by('membership_plan_renewal_period');
		return $this->db->get()->result_array();
	}
	/**
     * @sid Get sum of renewed upgraded membership plan based on old_membership_plan_renewal_period
     * Used by Membership controller in membership_plan() method
    */
	public function get_sum_of_price_of_renewed_upgraded_membership_plan_by_renewal_period($uid, $upgraded_date) {
		$this->db->select('old_membership_plan_renewal_period, sum(old_membership_renewal_price) as total');
		$this->db->from('serv_manual_renewed_upgraded_membership_plans_tracking');
		$this->db->where('user_id', $uid);
		$this->db->where('membership_upgrade_date', $upgraded_date);
		$this->db->group_by('old_membership_plan_renewal_period');
		return $this->db->get()->result_array();
	}
}