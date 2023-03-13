<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Finances_model extends BaseModel {
  public function __construct() {
    parent::__construct();
    $this->load->model('projects/Projects_model');
  }

  // This method is used to get all deposit funds data which was done so far
  public function get_all_deposit_funds_transactions($limit = '') {

    $user = $this->session->userdata('user');

    $this->db->select('*');
    $this->db->from('users_funds_paypal_deposits_transactions_history');
    $this->db->where('user_id', $user[0]->user_id);
    $this->db->order_by('id', 'desc');
    if(!empty($limit)) {
      $this->db->limit($limit);
    }
    return $this->db->get()->result_array();
  }
  // This method is used to get all withdraw funds data which was done so far
  public function get_all_withdraw_funds_transactions($limit = '') {
    $user = $this->session->userdata('user');
    $this->db->select('*');
    $this->db->from('users_funds_paypal_withdraw_transactions_history');
    $this->db->where('user_id', $user[0]->user_id);
    $this->db->order_by('id', 'desc');
    if(!empty($limit)) {
      $this->db->limit($limit);
    }
    $result = $this->db->get()->result_array(); 
    foreach($result as &$value) {
      $value['display_withdrawal_request_submit_date'] = date(DATE_TIME_FORMAT, strtotime($value['withdrawal_request_submit_date']));
      $value['display_request_rejection_date'] = date(DATE_TIME_FORMAT, strtotime($value['request_rejection_date']));
      $value['display_transaction_date'] = date(DATE_TIME_FORMAT, strtotime($value['transaction_date']));
      $value['request_status'] = ucfirst($value['request_status']);
      $value['transaction_status'] = ucfirst($value['transaction_status']);
      $value['withdrawal_requested_amount'] = format_money_amount_display($value['withdrawal_requested_amount']);
    }
    return $result;
  }

  // This method is used to get all direct bank transfer deposits transactions
  public function get_all_direct_bank_transfer_deposits_transactions($limit = '') {
    $user = $this->session->userdata('user');
    $this->db->select('dbt.*, c.country_name');
    $this->db->from('users_funds_direct_bank_transfer_deposits_transactions dbt');
    $this->db->join('countries c', 'c.id=dbt.country');
    $this->db->where('dbt.user_id', $user[0]->user_id);
    $this->db->order_by('id', 'desc');
    if(!empty($limit)) {
      $this->db->limit($limit);
    }
    $result = $this->db->get()->result_array(); 
    foreach($result as &$value) {
      $value['bank_account_owner_name'] = htmlspecialchars($value['bank_account_owner_name'], ENT_QUOTES);
      $value['bank_account_number'] = htmlspecialchars($value['bank_account_number'], ENT_QUOTES);
      $value['bank_name'] = htmlspecialchars($value['bank_name'], ENT_QUOTES);
      $value['bank_code'] = htmlspecialchars($value['bank_code'], ENT_QUOTES);
      $value['bank_account_iban_code'] = htmlspecialchars($value['bank_account_iban_code'], ENT_QUOTES);
      $value['bank_account_bic_swift_code'] = htmlspecialchars($value['bank_account_bic_swift_code'], ENT_QUOTES);

      $value['bank_transaction_date'] = date(DATE_FORMAT, strtotime($value['bank_transaction_date']));
      $value['deposited_amount'] = format_money_amount_display($value['deposited_amount']);
      if($value['status'] == 'transaction_pending_admin_confirmation') {
        $value['status'] = $this->config->item('deposit_funds_direct_bank_transfer_pending_confirmation_status_txt');
      } else if($value['status'] == 'transaction_confirmed_by_admin') {
        $value['status'] = ($value['admin_manual_entry'] ? $this->config->item('deposit_funds_direct_bank_transfer_added_by_admin_status_txt') : $this->config->item('deposit_funds_direct_bank_transfer_confirmed_status_txt'));
      }
    }
    // pre($result);
    return $result;
  }

  // This method is used to get all direct bank transfer withdraw transactions
  public function get_all_direct_bank_transfer_withdraw_transactions($limit = '') {
    $user = $this->session->userdata('user');
    $this->db->select('dbt.*, c.country_name');
    $this->db->from('users_funds_direct_bank_transfer_withdraw_transactions dbt');
    $this->db->join('countries c', 'c.id=dbt.country');
    $this->db->where('dbt.user_id', $user[0]->user_id);
    $this->db->order_by('id', 'desc');
    if(!empty($limit)) {
      $this->db->limit($limit);
    }
    $result = $this->db->get()->result_array(); 
    foreach($result as &$value) {

      $value['bank_account_owner_name'] = htmlspecialchars($value['bank_account_owner_name'], ENT_QUOTES);
      $value['bank_account_number'] = htmlspecialchars($value['bank_account_number'], ENT_QUOTES);
      $value['bank_name'] = htmlspecialchars($value['bank_name'], ENT_QUOTES);
      $value['bank_code'] = htmlspecialchars($value['bank_code'], ENT_QUOTES);
      $value['bank_account_iban_code'] = htmlspecialchars($value['bank_account_iban_code'], ENT_QUOTES);
      $value['bank_account_bic_swift_code'] = htmlspecialchars($value['bank_account_bic_swift_code'], ENT_QUOTES);

      if($value['status'] == 'request_confirmed_by_admin') {
        $value['bank_transaction_date'] = date(DATE_FORMAT, strtotime($value['bank_transaction_date']));
      }
      $value['user_withdraw_request_date'] = date(DATE_TIME_FORMAT, strtotime($value['user_withdraw_request_date']));
      $value['withdraw_amount'] = format_money_amount_display($value['withdraw_amount']);
    }
    return $result;
  }

  // This method is used to display invoices listing
  public function get_all_user_invoices_based_on_filter($start = '', $limit = '', $filter = []) {
    $user = $this->session->userdata('user');
    $this->db->select('SQL_CALC_FOUND_ROWS *', false);
    $this->db->from('users_invoices_tracking');
    $this->db->where('user_id', $user[0]->user_id);
    
    if(!empty($filter)) {
      $this->db->where('YEAR(invoice_generation_date)', $filter['year']);
    }
    $this->db->order_by('id', 'DESC');
    if($start != '' && $limit != '') {
      $this->db->limit($limit, $start);
    } else if(isset($start)) {
      $this->db->limit($limit);
    }
    $invoices =  $this->db->get()->result_array();
    $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
    $total_rec = $query->row()->Count;
    return ['data' => $invoices,'total' => $total_rec ];
  }

  // This method is used to get details of user which will be reflect into invoice
  public function get_user_details_to_display_in_invoice_by_user_id($user_id) {
    $this->db->select('u.*, uad.street_address,c.country_name,ps.postal_code,l.name as locality');
    $this->db->from('users u');
    $this->db->join('users_address_details uad', 'uad.user_id=u.user_id', 'left');
    $this->db->join('countries c', 'c.id=uad.country_id', 'left');
    $this->db->join('postal_codes ps', 'ps.id=uad.postal_code_id', 'left');
    $this->db->join('localities l', 'l.id=uad.locality_id', 'left');
    $this->db->where('u.user_id', $user_id);
    return $this->db->get()->row_array();
  }
  // This method is used to get all service fees paid by user based on filter
  public function get_all_service_fees_based_filter_to_display_in_invoice_by_user_id($user_id, $filter_arr = []) {
    $this->db->select('csf.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,appt.project_title');
    $this->db->from('projects_charged_service_fees_tracking csf');
    $this->db->join('users u', 'u.user_id=csf.winner_id', 'left');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=csf.project_id', 'left');
    $this->db->where('csf.project_owner_id', $user_id);
    if(!empty($filter_arr)) {
      $this->db->where('DATE(csf.escrow_payment_release_date) >=', $filter_arr['start_date']);
      $this->db->where('DATE(csf.escrow_payment_release_date) <=', $filter_arr['end_date']);
    }
    $this->db->order_by('csf.escrow_payment_release_date', 'DESC');
    return $this->db->get()->result_array();
  }
  // This method is used to get all service fees paid by user based on filter
  public function get_all_admin_dispute_service_fees_based_filter_to_display_in_invoice_by_user_id($user_id, $filter_arr = []) {
    $this->db->select('csf.*,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,appt.project_title');
    $this->db->from('projects_disputes_admin_arbitration_fees_tracking csf');
    $this->db->join('users u', 'u.user_id=csf.sp_winner_id_of_disputed_project', 'left');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=csf.disputed_project_id', 'left');
    $this->db->where('csf.project_owner_id_of_disputed_project', $user_id);
    if(!empty($filter_arr)) {
      $this->db->where('DATE(csf.dispute_negotiation_end_date) >=', $filter_arr['start_date']);
      $this->db->where('DATE(csf.dispute_negotiation_end_date) <=', $filter_arr['end_date']);
    }
    $this->db->order_by('csf.dispute_negotiation_end_date', 'DESC');
    return $this->db->get()->result_array();
  }
  // This method is used to get all purchase upgrade by user based on filter
  public function get_all_purchased_upgrades_based_filter_to_display_in_invoice_by_user_id($user_id, $filter_arr = []) {
    $this->db->select('pu.*,appt.project_title,appt.project_type');
    $this->db->from('projects_upgrades_purchases_tracking pu');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=pu.project_id', 'left');
    $this->db->where('pu.project_owner_id', $user_id);
    if(!empty($filter_arr)) {
      $this->db->where('DATE(pu.project_upgrade_purchase_date) >=', $filter_arr['start_date']);
      $this->db->where('DATE(pu.project_upgrade_purchase_date) <=', $filter_arr['end_date']);
    }
    $this->db->order_by('pu.project_upgrade_purchase_date', 'DESC');
    return $this->db->get()->result_array();
  }
  // This method is used to get all deposit funds via payapl charges by user based on filter
  public function get_all_deposit_funds_via_paypal_charges_based_filter_to_display_in_invoice_by_user_id($user_id, $filter_arr = []) {
    $this->db->select('ufpdt.*');
    $this->db->from('users_funds_paypal_deposits_transactions_history ufpdt');
    $this->db->where('ufpdt.user_id', $user_id);
    if(!empty($filter_arr)) {
      $this->db->where('DATE(ufpdt.transaction_date) >=', $filter_arr['start_date']);
      $this->db->where('DATE(ufpdt.transaction_date) <=', $filter_arr['end_date']);
    }
    $this->db->order_by('ufpdt.transaction_date', 'DESC');
    return $this->db->get()->result_array();
  }
  // This method is used to get all deposit funds via payment processor charges by user based on filter
  public function get_all_deposit_funds_via_payment_processor_charges_based_filter_to_display_in_invoice_by_user_id($user_id, $filter_arr = []) {
    $this->db->select('ufdppt.*, ct.card_brand, bt.bank_name');
    $this->db->from('users_funds_deposits_via_payment_processor_transactions ufdppt');
    $this->db->join('users_funds_deposits_via_payment_processor_card_trans ct', 'ct.payment_id=ufdppt.payment_id', 'left');
    $this->db->join('users_funds_deposits_via_payment_processor_bank_trans bt', 'bt.payment_id=ufdppt.payment_id', 'left');
    $this->db->where('ufdppt.user_id', $user_id);
    if(!empty($filter_arr)) {
      $this->db->where('DATE(ufdppt.transaction_completion_date) >=', $filter_arr['start_date']);
      $this->db->where('DATE(ufdppt.transaction_completion_date) <=', $filter_arr['end_date']);
    }
    $this->db->where_in('ufdppt.status_code', [2,6]);
    $this->db->order_by('ufdppt.transaction_completion_date', 'DESC');
    return $this->db->get()->result_array();
  }

  // validation for deposited funds
  public function save_direct_bank_transfer_funds_validation($post_data) {
    $i = 0;
    if(empty($post_data['deposited_amount'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'deposit_amount_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_deposited_amount_required_error_message');
      $i++;
    } else if(!empty($post_data['deposited_amount']) && !is_numeric($post_data['deposited_amount'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'deposit_amount_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_deposited_amount_invalid_error_message');
      $i++;
    }

    if(empty($post_data['account_owner'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'account_owner_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_account_owner_required_error_message');
      $i++;
    }
    if(empty($post_data['account_number'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'account_number_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_account_number_required_error_message');
      $i++;
    }
    if(empty($post_data['bank_name'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'bank_name_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_bank_name_required_error_message');
      $i++;
    }
    if(empty($post_data['bank_code'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'bank_code_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_bank_code_required_error_message');
      $i++;
    }
    if(empty($post_data['country'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'country_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_country_required_error_message');
      $i++;
    } else if(!empty($post_data['country']) && $post_data['country'] != $this->config->item('reference_country_id')) {
      if(empty($post_data['iban'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'iban_err';
        $msg['error'][$i]['message'] = $this->config->item('deposit_funds_bank_iban_required_error_message');
        $i++;
      }
      if(empty($post_data['swift_code'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'bic_swift_code_err';
        $msg['error'][$i]['message'] = $this->config->item('deposit_funds_bank_bic_swift_code_required_error_message');
        $i++;
      }
    } 
    if(empty($post_data['transaction_date'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'transaction_date_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_transaction_date_required_error_message');
      $i++;
    } else if(!empty($post_data['transaction_date']) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $post_data['transaction_date'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'transaction_date_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_transaction_date_invalid_format_error_message');
      $i++;
    } else if(!empty($post_data['transaction_date']) && preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $post_data['transaction_date'])) {
      $test_array = explode('.', $post_data['transaction_date']);
      if(count($test_array) == 3) {
        if (!checkdate((int)trim($test_array[1]), (int)trim($test_array[0]), (int)trim($test_array[2]))) { 
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'transaction_date_err';
          $msg['error'][$i]['message'] = $this->config->item('deposit_funds_transaction_date_invalid_error_message');
          $i++;
        }
      } else {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_date_err';
        $msg['error'][$i]['message'] = $this->config->item('deposit_funds_transaction_date_invalid_error_message');
        $i++;
      }
    }
    if(empty($post_data['transaction_id'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'transaction_id_err';
      $msg['error'][$i]['message'] = $this->config->item('deposit_funds_transaction_id_required_error_message');
      $i++;
    }

    if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
  }
  // validation for withdraw funds
  public function direct_bank_transfer_funds_withdraw_validation($post_data) {
    $i = 0;
    $user = $this->session->userdata('user');
    $userdata = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
    if(empty($post_data['withdraw_amount'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'withdraw_amount_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_withdrawal_amount_required_error_message');
      $i++;
    } else if(!empty($post_data['withdraw_amount']) && !is_numeric($post_data['withdraw_amount'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'withdraw_amount_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_withdrawal_amount_invalid_error_message');
      $i++;
    } else if(!empty($post_data['withdraw_amount']) && $post_data['withdraw_amount'] > $userdata['user_account_balance']) {
       $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'withdraw_amount_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_withdrawal_amount_greater_than_available_balance_error_message');
      $i++;
    }

    if(empty($post_data['account_owner'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'account_owner_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_account_owner_required_error_message');
      $i++;
    }
    if(empty($post_data['account_number'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'account_number_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_account_number_required_error_message');
      $i++;
    }
    if(empty($post_data['bank_name'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'bank_name_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_bank_name_required_error_message');
      $i++;
    }
    if(empty($post_data['bank_code'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'bank_code_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_bank_code_required_error_message');
      $i++;
    }
    if(empty($post_data['country'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'country_err';
      $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_country_required_error_message');
      $i++;
    } else if(!empty($post_data['country']) && $post_data['country'] != $this->config->item('reference_country_id')) {
      if(empty($post_data['iban'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'iban_err';
        $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_bank_iban_required_error_message');
        $i++;
      }
      if(empty($post_data['swift_code'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'bic_swift_code_err';
        $msg['error'][$i]['message'] = $this->config->item('withdraw_funds_bank_bic_swift_code_required_error_message');
        $i++;
      }
    } 
    if($i == 0){
			$msg['status'] = 'SUCCESS';
      $msg['message'] = '';
		}
		return $msg;
  }

  // Get all transactions history from user id and filters
  public function get_all_transactions_history_based_on_filter($user_id, $start, $limit, $filter_arr) {
    $limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
    }
    $deposit_paypal_fields = 'depay.user_id,depay.deposit_amount as amount,"" as service_fee_charges,depay.transaction_id,depay.transaction_date';
    $deposit_bank_fields = 'debank.user_id,debank.deposited_amount as amount,"" as service_fee_charges,debank.bank_transaction_id as transaction_id,debank.bank_transaction_date as transaction_date, debank.bank_account_owner_name,debank.bank_account_number,debank.bank_name,debank.bank_code,debank.bank_account_iban_code,debank.bank_account_bic_swift_code, c.country_name,debank.status,debank.admin_manual_entry';
    $deposit_payment_processor_fields = 'ppt.user_id,ppt.amount,"" as service_fee_charges,ppt.payment_id as transaction_id,ppt.transaction_date';
    
    $withdraw_paypal_fields = 'withpay.user_id,withpay.withdrawal_requested_amount as amount,"" as service_fee_charges,withpay.transaction_id,withpay.transaction_date';
    $withdraw_bank_fields = 'withbank.user_id,withbank.withdraw_amount as amount,"" as service_fee_charges,withbank.bank_transaction_id as transaction_id,withbank.bank_transaction_date as transaction_date, withbank.bank_account_owner_name,withbank.bank_account_number,withbank.bank_name,withbank.bank_code,withbank.bank_account_iban_code,withbank.bank_account_bic_swift_code, c.country_name,withbank.status,withbank.admin_manual_entry';

    $withdraw_referral_earning_fields = 'wrefe.user_id,wrefe.referral_earnings_withdrawal_requested_amount as amount,"" as service_fee_charges,wrefe.referral_earnings_withdrawal_transaction_id as transaction_id,wrefe.referral_earnings_withdrawal_request_admin_processing_date as transaction_date';

    $project_upgrades_fields = 'pu.project_owner_id as user_id,pu.project_upgrade_purchase_value as amount,"" as service_fee_charges,pu.project_upgrade_purchase_reference_id as transaction_id,pu.project_upgrade_purchase_date as transaction_date';

    $fixed_budget_project_release_escrow_fields = 'fbpre.project_owner_id as user_id,fbpre.released_escrow_payment_amount as amount,fbpre.service_fee_charges,fbpre.released_escrow_payment_reference_id as transaction_id,fbpre.escrow_payment_release_date as transaction_date';
    $hourly_rate_based_project_release_escrow_fields = 'hrpre.project_owner_id as user_id,hrpre.released_escrow_payment_amount as amount,hrpre.service_fee_charges,hrpre.released_escrow_payment_reference_id as transaction_id,hrpre.escrow_payment_release_date as transaction_date';
    $fulltime_project_release_escrow_fields = 'fulpre.employer_id as user_id,fulpre.released_escrow_payment_amount as amount,fulpre.service_fee_charges,fulpre.released_escrow_payment_reference_id as transaction_id,fulpre.escrow_payment_release_date as transaction_date';

    $fixed_budget_project_received_escrow_fields = 'fbprce.project_owner_id as user_id,fbprce.released_escrow_payment_amount as amount,"" as service_fee_charges,fbprce.released_escrow_payment_reference_id as transaction_id,fbprce.escrow_payment_release_date as transaction_date';
    $hourly_rate_based_project_received_escrow_fields = 'hrprce.project_owner_id as user_id,hrprce.released_escrow_payment_amount as amount,"" as service_fee_charges,hrprce.released_escrow_payment_reference_id as transaction_id,hrprce.escrow_payment_release_date as transaction_date';
    $fulltime_project_received_escrow_fields = 'fulprce.employer_id as user_id,fulprce.released_escrow_payment_amount as amount,"" as service_fee_charges,fulprce.released_escrow_payment_reference_id as transaction_id,fulprce.escrow_payment_release_date as transaction_date';

    $charged_service_fees_fields = 'csf.project_owner_id as user_id,csf.charged_service_fee_value as amount,"" as service_fee_charges,csf.released_escrow_payment_reference_id as transaction_id,csf.escrow_payment_release_date as transaction_date';
    $fulltime_charged_service_fees_fields = 'fullcsf.project_owner_id as user_id,fullcsf.charged_service_fee_value as amount,"" as service_fee_charges,fullcsf.released_escrow_payment_reference_id as transaction_id,fullcsf.escrow_payment_release_date as transaction_date';

    $bank_fields = '"" as bank_account_owner_name, "" as bank_account_number,"" as bank_name,"" as bank_code,"" as bank_account_iban_code,"" as bank_account_bic_swift_code,"" as country_name,"" as status,"" as admin_manual_entry';
    $proj_fields = '"" as project_id, "" as project_title, "" as project_upgrade_type, "" as project_upgrade_purchase_source';
    $payment_processsor_fields = '"" as deposit_transfer_type, "" as status_code, "" as method_id, "" as pp_bank_name, "" as pp_bank_account_number, "" as pp_bank_account_owner_name, "" as card_number, "" as card_brand, "" as country_code,"" as card_bank_name, "" as card_type';
    $user_fields = 'u.account_type,u.first_name,u.last_name,u.company_name,u.profile_name,u.is_authorized_physical_person';



    $this->db->select('SQL_CALC_FOUND_ROWS '.$deposit_paypal_fields, false);
    $this->db->select(','.$bank_fields.','.$proj_fields.', "deposit_paypal" as source, depay.paypal_account,depay.total_transaction_charged_fee as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('users_funds_paypal_deposits_transactions_history depay');
    $this->db->join('users u', 'u.user_id=depay.user_id');
    $this->db->where('depay.user_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW()) AND MONTH(transaction_date) = MONTH(NOW()) AND DAY(transaction_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW()) AND MONTH(transaction_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(transaction_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(transaction_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(transaction_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(transaction_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['deposits']) && (count(array_intersect(['deposits_via_bank', 'deposit_via_payment_card', 'deposits_via_bank_transfer', 'deposits_none'], $filter_arr['deposits'])) > 0) && !in_array('deposits_via_paypal', $filter_arr['deposits'])) {
        $this->db->where('depay.id IS NULL');
      }
    }

    $deposits_funds_via_paypal_query = $this->db->get_compiled_select();

    $this->db->select($deposit_bank_fields);
    $this->db->select(','.$proj_fields.',"deposit_bank" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('users_funds_direct_bank_transfer_deposits_transactions debank');
    $this->db->join('countries c', 'c.id=debank.country');
    $this->db->join('users u', 'u.user_id=debank.user_id');
    $this->db->where('debank.user_id', $user_id);
    $this->db->where('debank.status', 'transaction_confirmed_by_admin');

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(debank.bank_transaction_date) = YEAR(NOW()) AND MONTH(debank.bank_transaction_date) = MONTH(NOW()) AND DAY(debank.bank_transaction_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(debank.bank_transaction_date) = YEAR(NOW()) AND MONTH(debank.bank_transaction_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(debank.bank_transaction_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(debank.bank_transaction_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(debank.bank_transaction_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(debank.bank_transaction_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(debank.bank_transaction_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }  
      if(!empty($filter_arr['deposits']) && (count(array_intersect(['deposits_via_paypal', 'deposit_via_payment_card', 'deposits_via_bank_transfer', 'deposits_none'], $filter_arr['deposits'])) > 0) && !in_array('deposits_via_bank', $filter_arr['deposits'])) {
        $this->db->where('debank.id IS NULL');
      }
    }

    $deposits_funds_via_bank_query = $this->db->get_compiled_select();

    $this->db->select($deposit_payment_processor_fields);
    $this->db->select(','.$bank_fields.','.$proj_fields.', "payment_processor" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,ppt.business_transaction_charged_fee as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(',ppt.deposit_transfer_type,ppt.status_code,ppt.method_id,pbt.bank_name as pp_bank_name,pbt.bank_account_number as pp_bank_account_number,pbt.bank_account_owner_name as pp_bank_account_owner_name,pct.card_number,pct.card_brand,pct.country_code,pct.bank_name as card_bank_name,pct.card_type');
    $this->db->from('users_funds_deposits_via_payment_processor_transactions ppt');
    $this->db->join('users_funds_deposits_via_payment_processor_bank_trans pbt', 'pbt.payment_id=ppt.payment_id', 'left');
    $this->db->join('users_funds_deposits_via_payment_processor_card_trans pct', 'pct.payment_id=ppt.payment_id', 'left');
    $this->db->join('users u', 'u.user_id=ppt.user_id');
    $this->db->where('ppt.user_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW()) AND MONTH(transaction_date) = MONTH(NOW()) AND DAY(transaction_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW()) AND MONTH(transaction_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(transaction_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(transaction_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(transaction_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(transaction_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['deposits']) && in_array('deposit_via_payment_card', $filter_arr['deposits']) && !in_array('deposits_via_bank_transfer', $filter_arr['deposits'])){
        $this->db->where('ppt.deposit_transfer_type', 'payment_card_transaction');
      } else if(!empty($filter_arr['deposits']) && in_array('deposits_via_bank_transfer', $filter_arr['deposits']) && !in_array('deposit_via_payment_card', $filter_arr['deposits'])) {
        $this->db->where('ppt.deposit_transfer_type', 'bank_transfer_transaction');
      }  
      if(!empty($filter_arr['deposits']) && (count(array_intersect(['deposits_via_paypal','deposits_via_bank', 'deposits_none'], $filter_arr['deposits'])) > 0) && !in_array('deposit_via_payment_card', $filter_arr['deposits']) && !in_array('deposits_via_bank_transfer', $filter_arr['deposits'])) {
        $this->db->where('ppt.id IS NULL');
      }
    }

    $deposits_funds_via_payment_processor_query = $this->db->get_compiled_select();

    $this->db->select($withdraw_paypal_fields);
    $this->db->select(','.$bank_fields.','.$proj_fields.', "withdraw_paypal" as source, withpay.withdraw_to_paypal_account as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('users_funds_paypal_withdraw_transactions_history withpay');
    $this->db->join('users u', 'u.user_id=withpay.user_id');
    $this->db->where('withpay.user_id', $user_id);
    $this->db->where('withpay.request_status', 'approved');
    $this->db->where('withpay.transaction_status', 'successful');

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW()) AND MONTH(transaction_date) = MONTH(NOW()) AND DAY(transaction_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(transaction_date) = YEAR(NOW()) AND MONTH(transaction_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(transaction_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(transaction_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') { 
        $this->db->where('YEAR(transaction_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(transaction_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(transaction_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['withdraws']) && (count(array_intersect(['withdraws_via_bank', 'referral_earnings_withdraws', 'withdraws_none'], $filter_arr['withdraws'])) > 0) && !in_array('withdraws_via_paypal', $filter_arr['withdraws'])) {
        $this->db->where('withpay.id IS NULL');
      }
    }

    $withdraw_funds_via_paypal_query = $this->db->get_compiled_select();

    $this->db->select($withdraw_bank_fields);
    $this->db->select(','.$proj_fields.',"withdraw_bank" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('users_funds_direct_bank_transfer_withdraw_transactions withbank');
    $this->db->join('countries c', 'c.id=withbank.country');
    $this->db->join('users u', 'u.user_id=withbank.user_id');
    $this->db->where('withbank.user_id', $user_id);
    $this->db->where('withbank.status', 'request_confirmed_by_admin');

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(withbank.bank_transaction_date) = YEAR(NOW()) AND MONTH(withbank.bank_transaction_date) = MONTH(NOW()) AND DAY(withbank.bank_transaction_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(withbank.bank_transaction_date) = YEAR(NOW()) AND MONTH(withbank.bank_transaction_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(withbank.bank_transaction_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(withbank.bank_transaction_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(withbank.bank_transaction_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(withbank.bank_transaction_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(withbank.bank_transaction_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['withdraws']) && (count(array_intersect(['withdraws_via_paypal', 'referral_earnings_withdraws', 'withdraws_none'], $filter_arr['withdraws'])) > 0) && !in_array('withdraws_via_bank', $filter_arr['withdraws'])) {
        $this->db->where('withbank.id IS NULL');
      }
    }

    $withdraw_funds_via_bank_query = $this->db->get_compiled_select();

    $this->db->select($withdraw_referral_earning_fields);
    $this->db->select(','.$bank_fields.','.$proj_fields.', "withdraw_referral_earnings" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('users_referrals_earnings_withdraw_transactions_history wrefe');
    $this->db->join('users u', 'u.user_id=wrefe.user_id');
    $this->db->where('wrefe.user_id', $user_id);
    $this->db->where('wrefe.referral_earnings_withdrawal_request_status', 'approved');

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = YEAR(NOW()) AND MONTH(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = MONTH(NOW()) AND DAY(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = YEAR(NOW()) AND MONTH(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(wrefe.referral_earnings_withdrawal_request_admin_processing_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(wrefe.referral_earnings_withdrawal_request_admin_processing_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(wrefe.referral_earnings_withdrawal_request_admin_processing_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['withdraws']) && (count(array_intersect(['withdraws_via_bank', 'withdraws_via_paypal', 'withdraws_none'], $filter_arr['withdraws'])) > 0) && !in_array('referral_earnings_withdraws', $filter_arr['withdraws'])) {
        $this->db->where('wrefe.id IS NULL');
      }
    }

    $withdraw_referral_earning_query = $this->db->get_compiled_select();

    $this->db->select($project_upgrades_fields);
    $this->db->select(','.$bank_fields.',pu.project_id,appt.project_title,pu.project_upgrade_type,pu.project_upgrade_purchase_source, "project_upgrades" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('projects_upgrades_purchases_tracking pu');
    $this->db->join('users u', 'u.user_id=pu.project_owner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=pu.project_id', 'left');
    $this->db->where('pu.project_owner_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(pu.project_upgrade_purchase_date) = YEAR(NOW()) AND MONTH(pu.project_upgrade_purchase_date) = MONTH(NOW()) AND DAY(pu.project_upgrade_purchase_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(pu.project_upgrade_purchase_date) = YEAR(NOW()) AND MONTH(pu.project_upgrade_purchase_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(pu.project_upgrade_purchase_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(pu.project_upgrade_purchase_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(pu.project_upgrade_purchase_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(pu.project_upgrade_purchase_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(pu.project_upgrade_purchase_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }  

      if(!empty($filter_arr['upgrades'])) {
        $this->db->group_start();
      }

      if(!empty($filter_arr['upgrades']) && count(array_intersect(['Featured_upgrade_prolongations', 'Urgent_upgrade_prolongations'], $filter_arr['upgrades'])) > 0) {
        $upgrade_type = [];
        if(in_array('Featured_upgrade_prolongations', $filter_arr['upgrades'])) {
          $upgrade_type[] = 'featured';
        }
        if(in_array('Urgent_upgrade_prolongations', $filter_arr['upgrades'])) {
          $upgrade_type[] = 'urgent';
        }
        
        if(!empty($upgrade_type)) {
          if(count(array_intersect(['featured', 'urgent'], $filter_arr['upgrades'])) == 0) {
            $this->db->group_start();
            $this->db->where('pu.project_upgrade_purchase_type', 'upgrade_availability_prolong');
            $this->db->where_in('pu.project_upgrade_type', $upgrade_type);
            $this->db->group_end();
          } else {
            
            if(in_array('featured', $filter_arr['upgrades']) && in_array('urgent', $upgrade_type) ) {
              $this->db->group_start();
              $this->db->where('pu.project_upgrade_purchase_type', 'upgrade_availability_prolong');
              $this->db->where('pu.project_upgrade_type',  'urgent');
              $this->db->group_end();
            }
            if(in_array('featured', $filter_arr['upgrades']) && in_array('featured', $upgrade_type) ) {
              $this->db->group_start();
              $this->db->where('pu.project_upgrade_purchase_type', 'upgrade_availability_prolong');
              $this->db->where('pu.project_upgrade_type',  'featured');
              $this->db->group_end();
            }
            if(in_array('urgent', $filter_arr['upgrades']) && in_array('featured', $upgrade_type)) {
              $this->db->group_start();
              $this->db->where('pu.project_upgrade_purchase_type', 'upgrade_availability_prolong');
              $this->db->where('pu.project_upgrade_type', 'featured');
              $this->db->group_end();
            }
            if(in_array('urgent', $filter_arr['upgrades']) && in_array('urgent', $upgrade_type) ) {
              $this->db->group_start();
              $this->db->where('pu.project_upgrade_purchase_type', 'upgrade_availability_prolong');
              $this->db->where('pu.project_upgrade_type',  'urgent');
              $this->db->group_end();
            }
          }
        }
        
      }

      if(!empty($filter_arr['upgrades']) && count(array_intersect(['featured', 'urgent', 'sealed' , 'hidden'], $filter_arr['upgrades'])) > 0) {
        $upgrade_type = [];
        if(in_array('featured', $filter_arr['upgrades']) ) {
          $upgrade_type[] = 'featured';
        }
        if(in_array('urgent', $filter_arr['upgrades']) ) {
          $upgrade_type[] = 'urgent';
        }
        if(in_array('sealed', $filter_arr['upgrades']) ) {
          $upgrade_type[] = 'sealed';
        }
        if(in_array('hidden', $filter_arr['upgrades']) ) {
          $upgrade_type[] = 'hidden';
        }
        if(!empty($upgrade_type)) {
          if(count(array_intersect(['Featured_upgrade_prolongations', 'Urgent_upgrade_prolongations'], $filter_arr['upgrades'])) > 0) {
            $this->db->or_where_in('pu.project_upgrade_type', $upgrade_type);
          } else {
            $this->db->where_in('pu.project_upgrade_type', $upgrade_type);
          }
        }
      }
      
      if(!empty($filter_arr['upgrades']) && in_array('project_upgrades_none', $filter_arr['upgrades'])) {
        $this->db->where('pu.id IS NULL');
      }

      if(!empty($filter_arr['upgrades'])) {
        $this->db->group_end();
      }
    }

    $project_upgrades_query = $this->db->get_compiled_select();
    // pre($project_upgrades_query);
    
    $this->db->select($fixed_budget_project_release_escrow_fields);
    $this->db->select(','.$bank_fields.',fbpre.project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "payments_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('fixed_budget_projects_released_escrows fbpre');
    $this->db->join('users u', 'u.user_id=fbpre.winner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=fbpre.project_id', 'left');
    $this->db->where('fbpre.project_owner_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(fbpre.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fbpre.escrow_payment_release_date) = MONTH(NOW()) AND DAY(fbpre.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(fbpre.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fbpre.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(fbpre.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(fbpre.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(fbpre.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(fbpre.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(fbpre.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }  
      if(!empty($filter_arr['proj_payments']) && count(array_intersect(['payments_on_hourly_rate_based_projects', 'payments_projects_none'], $filter_arr['proj_payments'])) > 0 ) {
        $this->db->where('fbpre.id IS NULL');
      }
    }

    $fixed_budget_project_release_escrow_query = $this->db->get_compiled_select();

    $this->db->select($hourly_rate_based_project_release_escrow_fields);
    $this->db->select(','.$bank_fields.',hrpre.project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "payments_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('hourly_rate_based_projects_released_escrows hrpre');
    $this->db->join('users u', 'u.user_id=hrpre.winner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=hrpre.project_id', 'left');
    $this->db->where('hrpre.project_owner_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(hrpre.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(hrpre.escrow_payment_release_date) = MONTH(NOW()) AND DAY(hrpre.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(hrpre.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(hrpre.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(hrpre.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(hrpre.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(hrpre.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(hrpre.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(hrpre.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['proj_payments']) && count(array_intersect(['payments_on_fixed_budget_projects', 'payments_projects_none'], $filter_arr['proj_payments'])) > 0 ) {
        $this->db->where('hrpre.id IS NULL');
      }
    }

    $hourly_rate_based_project_release_escrow_query = $this->db->get_compiled_select();

    $this->db->select($fulltime_project_release_escrow_fields);
    $this->db->select(','.$bank_fields.',fulpre.fulltime_project_id as project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "payments_fulltime_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('fulltime_projects_released_escrows fulpre');
    $this->db->join('users u', 'u.user_id=fulpre.employee_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=fulpre.fulltime_project_id', 'left');
    $this->db->where('fulpre.employer_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(fulpre.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fulpre.escrow_payment_release_date) = MONTH(NOW()) AND DAY(fulpre.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(fulpre.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fulpre.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(fulpre.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(fulpre.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(fulpre.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(fulpre.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(fulpre.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      } 
      if(!empty($filter_arr['fulltime_payments']) && in_array('salary_payments_on_fulltime_jobs_none', $filter_arr['fulltime_payments']) ) {
        $this->db->where('fulpre.id IS NULL');
      }
    }

    $fulltime_project_release_escrow_query = $this->db->get_compiled_select();

    $this->db->select($fixed_budget_project_received_escrow_fields);
    $this->db->select(','.$bank_fields.',fbprce.project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "payments_receive_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('fixed_budget_projects_released_escrows fbprce');
    $this->db->join('users u', 'u.user_id=fbprce.project_owner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=fbprce.project_id', 'left');
    $this->db->where('fbprce.winner_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(fbprce.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fbprce.escrow_payment_release_date) = MONTH(NOW()) AND DAY(fbprce.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(fbprce.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fbprce.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(fbprce.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(fbprce.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(fbprce.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(fbprce.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(fbprce.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }
      if(!empty($filter_arr['rec_proj_payments']) && count(array_intersect(['payments_received_hourly_rate_based_projects', 'payments_received_projects_none'], $filter_arr['rec_proj_payments'])) > 0 ) {
        $this->db->where('fbprce.id IS NULL');
      }
    }

    $fixed_budget_project_received_escrow_query = $this->db->get_compiled_select();

    $this->db->select($hourly_rate_based_project_received_escrow_fields);
    $this->db->select(','.$bank_fields.',hrprce.project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "payments_receive_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('hourly_rate_based_projects_released_escrows hrprce');
    $this->db->join('users u', 'u.user_id=hrprce.project_owner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=hrprce.project_id', 'left');
    $this->db->where('hrprce.winner_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(hrprce.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(hrprce.escrow_payment_release_date) = MONTH(NOW()) AND DAY(hrprce.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(hrprce.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(hrprce.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(hrprce.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(hrprce.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(hrprce.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(hrprce.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(hrprce.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }
      if(!empty($filter_arr['rec_proj_payments']) && count(array_intersect(['payments_received_fixed_budget_projects', 'payments_received_projects_none'], $filter_arr['rec_proj_payments'])) > 0 ) {
        $this->db->where('hrprce.id IS NULL');
      }
    }

    $hourly_rate_based_project_received_escrow_query = $this->db->get_compiled_select();

    $this->db->select($fulltime_project_received_escrow_fields);
    $this->db->select(','.$bank_fields.',fulprce.fulltime_project_id as project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "payments_receive_fulltime_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('fulltime_projects_released_escrows fulprce');
    $this->db->join('users u', 'u.user_id=fulprce.employer_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=fulprce.fulltime_project_id', 'left');
    $this->db->where('fulprce.employee_id', $user_id);

    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(fulprce.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fulprce.escrow_payment_release_date) = MONTH(NOW()) AND DAY(fulprce.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(fulprce.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fulprce.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(fulprce.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(fulprce.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(fulprce.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(fulprce.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(fulprce.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }
      if(!empty($filter_arr['rec_fulltime_payments']) && in_array('salary_payments_received_fulltime_jobs_none', $filter_arr['rec_fulltime_payments'])) {
        $this->db->where('fulprce.id IS NULL');
      }
    }

    $fulltime_project_received_escrow_query = $this->db->get_compiled_select();

    $this->db->select($charged_service_fees_fields);
    $this->db->select(','.$bank_fields.',csf.project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "service_fees_project" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('projects_charged_service_fees_tracking csf');
    $this->db->join('users u', 'u.user_id=csf.winner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id=csf.project_id', 'left');
    $this->db->where('csf.project_owner_id', $user_id);
    
    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(csf.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(csf.escrow_payment_release_date) = MONTH(NOW()) AND DAY(csf.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(csf.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(csf.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(csf.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(csf.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(csf.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(csf.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(csf.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }
	
      if(!empty($filter_arr['service_fees']) && count(array_intersect(['service_fees_payments_fixed_budget_projects', 'service_fees_payments_hourly_rate_based_projects'], $filter_arr['service_fees'])) > 0) {
        $p_type = [];
        if(in_array('service_fees_payments_fixed_budget_projects', $filter_arr['service_fees'])) {
          $p_type[] = 'fixed_budget';
        }
        if(in_array('service_fees_payments_hourly_rate_based_projects', $filter_arr['service_fees'])) {
          $p_type[] = 'hourly_rate';
        }
        if(!empty($p_type)) {
          $this->db->where_in('csf.project_type', $p_type);
        }
      } else if(!empty($filter_arr['service_fees']) && count(array_intersect(['service_fees_payments_fulltime_jobs', 'service_fees_payments_none'], $filter_arr['service_fees']))) {
        $this->db->where('csf.id IS NULL');
      } else {
        $this->db->where_in('csf.project_type', ['hourly_rate', 'fixed_budget']);
      }
      
    } else {
      $this->db->where_in('csf.project_type', ['hourly_rate', 'fixed_budget']);
    }

    $charged_service_fees_project_query = $this->db->get_compiled_select();

    $this->db->select($fulltime_charged_service_fees_fields);
    $this->db->select(','.$bank_fields.',fullcsf.project_id,appt.project_title,"" as project_upgrade_type, "" as project_upgrade_purchase_source, "service_fees_fulltime" as source, "" as paypal_account,"" as deposit_funds_paypal_charge,"" as payment_processor_business_transaction_charged_fee,'.$user_fields);
    $this->db->select(','.$payment_processsor_fields);
    $this->db->from('projects_charged_service_fees_tracking fullcsf');
    $this->db->join('users u', 'u.user_id = fullcsf.winner_id');
    $this->db->join('users_alltime_published_projects_tracking appt', 'appt.project_id = fullcsf.project_id', 'left');
    $this->db->where('fullcsf.project_owner_id', $user_id);
    
    if(!empty($filter_arr)) {
      if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'today') {
        $this->db->where('YEAR(fullcsf.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fullcsf.escrow_payment_release_date) = MONTH(NOW()) AND DAY(fullcsf.escrow_payment_release_date) = DAY(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'this_month') {
        $this->db->where('YEAR(fullcsf.escrow_payment_release_date) = YEAR(NOW()) AND MONTH(fullcsf.escrow_payment_release_date) = MONTH(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'last_month') {
        $this->db->where('YEAR(fullcsf.escrow_payment_release_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(fullcsf.escrow_payment_release_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'begining_of_year') {
        $this->db->where('YEAR(fullcsf.escrow_payment_release_date) = YEAR(NOW())');
      } else if(!empty($filter_arr['date_filter']) && $filter_arr['date_filter'] == 'custom_date') {
        $this->db->where('DATE(fullcsf.escrow_payment_release_date) >=', date('Y-m-d', strtotime($filter_arr['start_date'])));
        $this->db->where('DATE(fullcsf.escrow_payment_release_date) <=', date('Y-m-d', strtotime($filter_arr['end_date'])));
      }
      
      if(!empty($filter_arr['service_fees']) && in_array('service_fees_payments_fulltime_jobs', $filter_arr['service_fees'])) {
        $this->db->where('fullcsf.project_type', 'fulltime');
      } else if(!empty($filter_arr['service_fees']) && count(array_intersect(['service_fees_payments_fixed_budget_projects', 'service_fees_payments_hourly_rate_based_projects' , 'service_fees_payments_none'], $filter_arr['service_fees']))) {
        $this->db->where('fullcsf.id IS NULL');
      } else {
        $this->db->where('fullcsf.project_type', 'fulltime');
      }
      
    } else {
      $this->db->where('fullcsf.project_type', 'fulltime');
    }

    $charged_service_fees_fulltime_query = $this->db->get_compiled_select();    

    $union_table_name = [
      $deposits_funds_via_paypal_query,
      $deposits_funds_via_bank_query,
		$deposits_funds_via_payment_processor_query,
      $withdraw_funds_via_paypal_query,
      $withdraw_funds_via_bank_query,
      $withdraw_referral_earning_query,
      $project_upgrades_query,
      $fixed_budget_project_release_escrow_query,
      $hourly_rate_based_project_release_escrow_query,
      $fulltime_project_release_escrow_query,
      $fixed_budget_project_received_escrow_query,
      $hourly_rate_based_project_received_escrow_query,
      $fulltime_project_received_escrow_query,
     /*  $charged_service_fees_project_query,
      $charged_service_fees_fulltime_query */

    ];
    $query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY transaction_date DESC LIMIT '.$limit_range);
    $result  = $query->result_array();
	

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
	
		return ['data' => $result, 'total' => $total_rec];
  }
  // This method is used to calculate processing fees which will be applied on payment when payment done via payment processor
  public function calculate_processing_fees_for_deposited_amounts_via_payment_processor($amount) {
    $charges = 0;
    $charges_data = $this->db->get('users_deposits_via_payment_processor_charged_fees_ranges')->result_array();
    if(!empty($charges_data)) {
      foreach($charges_data as $charge_value){
        if($charge_value['max_deposited_amount'] != 'All'){
          if($amount >= (int)$charge_value['min_deposited_amount'] && $amount <= (int)$charge_value['max_deposited_amount']){
            $charges = $charge_value['charged_fee_value'];
            break;
          }
        } else {
          if($amount >= (int)$charge_value['min_deposited_amount']){
            $charges = $charge_value['charged_fee_value'];
            break;
          }
        }
      }
    }
    return $charges;
  }
  // This method is used to get all payment processor related transactions
  public function get_all_payments_processor_transactions($limit, $user_id) {
    $this->db->select('pt.*');
    $this->db->select('ct.card_number,ct.card_brand,ct.country_code,ct.bank_name as card_bank_name,ct.card_type,ct.card_level');
    $this->db->select('bt.bank_name, bt.bank_account_number, bt.bank_account_owner_name');
    $this->db->from('users_funds_deposits_via_payment_processor_transactions pt');
    $this->db->join('users_funds_deposits_via_payment_processor_card_trans ct', 'ct.payment_id=pt.payment_id', 'left');
    $this->db->join('users_funds_deposits_via_payment_processor_bank_trans bt', 'bt.payment_id=pt.payment_id', 'left');
    $this->db->where('pt.user_id', $user_id);
    $this->db->order_by('pt.id', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result_array();
  }
  // validation for invoicing details
  public function company_invoicing_details_validation($post_data) {
    $i = 0;
    $user = $this->session->userdata('user');
    if(empty($post_data['company_name'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'company_name_error';
      $msg['error'][$i]['message'] = $user[0]->is_authorized_physical_person  == 'Y' ? $this->config->item('company_app_invoicing_details_company_name_required_error_message') : $this->config->item('company_invoicing_details_company_name_required_error_message');
      $i++;
    }
    if(empty($post_data['company_address_1']) || empty($post_data['company_address_2'])) {
      if(empty($post_data['company_address_2']) && empty($post_data['company_address_1'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'company_address_1_error';
        $msg['error'][$i]['message'] = $user[0]->is_authorized_physical_person  == 'Y' ? $this->config->item('company_app_invoicing_details_company_address_required_error_message') : $this->config->item('company_invoicing_details_company_address_required_error_message');
        $i++;
      }
    }
    
    if(empty($post_data['company_country'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'company_country_error';
      $msg['error'][$i]['message'] = $user[0]->is_authorized_physical_person  == 'Y' ? $this->config->item('company_app_invoicing_details_country_required_error_message') : $this->config->item('company_invoicing_details_country_required_error_message');
      $i++;
    }
    if(empty($post_data['company_registration_number'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'company_registration_number_error';
      $msg['error'][$i]['message'] = $user[0]->is_authorized_physical_person  == 'Y' ? $this->config->item('company_app_invoicing_details_company_registration_number_required_error_message') :$this->config->item('company_invoicing_details_company_registration_number_required_error_message');
      $i++;
    }
    if(empty($post_data['no_vat']) && empty($post_data['company_vat_number'])) {
      $msg['status'] = 'FAILED';
      $msg['error'][$i]['id'] = 'company_vat_number_error';
      $msg['error'][$i]['message'] = $user[0]->is_authorized_physical_person  == 'Y' ?  $this->config->item('company_app_invoicing_details_company_vat_number_required_error_message') :$this->config->item('company_invoicing_details_company_vat_number_required_error_message');
      $i++;
    }

    if($i == 0){
			$msg['status'] = 'SUCCESS';
      $msg['message'] = '';
		}
		return $msg;
  }
  // get user invoicing details by user id
  public function get_company_invoicing_details_by_user_id($user_id) {
    $this->db->select('uci.*,c.country_name');
    $this->db->from('users_company_accounts_invoicing_details uci');
    $this->db->join('countries c', 'c.id = uci.company_country', 'left');
    $this->db->where('uci.user_id', $user_id);
    return $this->db->get()->row_array();
  }
  
}
?>