<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Referral_earnings_model extends BaseModel {
  public function __construct() {
      return parent::__construct();
  }
  /**
   * This method is used get all transaction related to withdrawal funds by users
   */
  public function get_all_withdraw_funds_requests($rowno,$rowperpage) {
    $this->db->select('SQL_CALC_FOUND_ROWS ufdwth.*, u.first_name, u.last_name, u.company_name, u.account_type, u.profile_name', false);
    $this->db->from('users_referrals_earnings_withdraw_transactions_history ufdwth');
    $this->db->join('users u', 'u.user_id = ufdwth.user_id', 'left');
    $this->db->order_by('ufdwth.id', 'desc');
    $this->db->limit($rowperpage, $rowno);  
    $result =  $this->db->get()->result_array();
    $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');

    return ['data' => $result, 'total' => $query->row()->Count];
  }
}