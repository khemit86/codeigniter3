<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Referral_earnings extends MX_Controller {
  public function __construct() {
    $this->load->model('referral_earnings_model');
    $this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->helper('url');    
		parent::__construct();
  }

  /**
   * This method is used to load transaction detail related to deposit funds
  */
  public function withdraw_funds_request() {
    $lay['lft'] = "inc/section_left";
    $data['data'] = $this->auto_model->leftPannel();
    // Row per page
    $rowperpage = PAGING_LIMIT;
    // Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
    $withdraw_funds = $this->referral_earnings_model->get_all_withdraw_funds_requests($rowno,$rowperpage);
    $data['withdraw_funds'] = $withdraw_funds['data'];
    // Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url('referral_earnings/withdraw_funds_request/?');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $withdraw_funds['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
    $this->layout->view('withdraw_requests', $lay, $data);
  }
  /**
	 * This method is used to manage withdraw request related action which performed by admin
	*/
	public function manage_referral_earnings_withdrawal_requests($param = '', $param1 = '') {
		include_once '../application/config/'.SITE_LANGUAGE.'_invite_friends_custom_config.php';
		if($param == 'reject') {
			$withdraw_detail = $this->db->get_where('users_referrals_earnings_withdraw_transactions_history', ['id' => $param1])->row_array();
			if(!empty($withdraw_detail)) {
        $update_data = [
          'referral_earnings_withdrawal_request_status' => 'rejected',
          'referral_earnings_withdrawal_request_admin_processing_date' => date('Y-m-d H:i:s')
        ];
        $this->db->update('users_referrals_earnings_withdraw_transactions_history', $update_data, ['id' => $withdraw_detail['id']]);
        
        $activity_msg = $config['admin_rejected_withdraw_amount_request_from_referral_earnings_user_activity_log'];
        $activity_msg = str_replace('{withdraw_amount}', $withdraw_detail['referral_earnings_withdrawal_requested_amount'], $activity_msg);
        user_display_log($activity_msg, $withdraw_detail['user_id']);
				
				$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
			}
			
		} else {
      $withdraw_detail = $this->db->get_where('users_referrals_earnings_withdraw_transactions_history', ['id' => $param1])->row_array();
      if(!empty($withdraw_detail)) { 
        $update_data = [
          'referral_earnings_withdrawal_request_status' => 'approved',
          'referral_earnings_withdrawal_request_admin_processing_date' => date('Y-m-d H:i:s')
        ];
        $this->db->update('users_referrals_earnings_withdraw_transactions_history', $update_data, ['id' => $withdraw_detail['id']]);
        
        $activity_msg = $config['admin_approved_withdraw_amount_request_from_referral_earnings_user_activity_log'];
        $activity_msg = str_replace('{withdraw_amount}', $withdraw_detail['referral_earnings_withdrawal_requested_amount'], $activity_msg);
        user_display_log($activity_msg, $withdraw_detail['user_id']);
				
        $this->db->set('user_account_balance', 'user_account_balance + ' . $withdraw_detail['referral_earnings_withdrawal_requested_amount'], FALSE);
        $this->db->where('user_id', $withdraw_detail['user_id']);
        $this->db->update('users_details');

        $this->session->set_flashdata('succ_msg', 'Action performed successfully.');
      }
			
		}
		redirect(base_url('referral_earnings/withdraw_funds_request/?per_page='.$this->input->get('per_page')));
	}
}