<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Finances_model extends BaseModel {

    public function __construct() {
        return parent::__construct();
    }
    /**
     * This method is used get all transaction related to deposit funds by users
     */
    public function get_all_deposit_funds($rowno,$rowperpage) {
      $this->db->select('SQL_CALC_FOUND_ROWS ufdwth.*, u.first_name, u.last_name, u.company_name, u.account_type,u.is_authorized_physical_person, u.profile_name, "paypal" as transaction_source', false);
      $this->db->from('users_funds_paypal_deposits_transactions_history ufdwth');
      $this->db->join('users u', 'u.user_id = ufdwth.user_id', 'left');
      $this->db->order_by('ufdwth.id', 'desc');
      $this->db->limit($rowperpage, $rowno);  
      $result =  $this->db->get()->result_array();
      $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');

      return ['data' => $result, 'total' => $query->row()->Count];
    }
    /**
     * This method is used get all direct bank transfer deposited funds transactions
     */
    public function get_all_direct_bank_transfer_deposited_funds_transactions($rowno,$rowperpage) {
      $this->db->select('SQL_CALC_FOUND_ROWS ufdwth.*, u.first_name, u.last_name, u.company_name, u.account_type, u.is_authorized_physical_person, u.profile_name, c.country_name,ud.user_bank_deposit_variable_symbol', false);
      $this->db->from('users_funds_direct_bank_transfer_deposits_transactions ufdwth');
      $this->db->join('users u', 'u.user_id = ufdwth.user_id', 'left');
      $this->db->join('users_details ud', 'ud.user_id = ufdwth.user_id', 'left');
      $this->db->join('countries c', 'c.id = ufdwth.country');
      $this->db->order_by('ufdwth.id', 'desc');
      $this->db->limit($rowperpage, $rowno);  
      $result =  $this->db->get()->result_array();
      $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');

      return ['data' => $result, 'total' => $query->row()->Count];
    }
    /**
     * This method is used get all transaction related to withdrawal funds by users
     */
    public function get_all_withdraw_funds($rowno,$rowperpage) {
      $this->db->select('SQL_CALC_FOUND_ROWS ufdwth.*, u.first_name, u.last_name, u.company_name, u.account_type, u.is_authorized_physical_person,u.profile_name, "paypal" as withdraw_to', false);
      $this->db->from('users_funds_paypal_withdraw_transactions_history ufdwth');
      $this->db->join('users u', 'u.user_id = ufdwth.user_id', 'left');
      $this->db->order_by('ufdwth.id', 'desc');
      $this->db->limit($rowperpage, $rowno);  
      $result =  $this->db->get()->result_array();
      $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');

      return ['data' => $result, 'total' => $query->row()->Count];
    }
    /**
     * This method is used get all direct bank transfer withdraw fundstransaction related to withdrawal funds by users
     */
    public function get_all_direct_bank_transfer_withdraw_funds_transactions($rowno,$rowperpage) {
      $this->db->select('SQL_CALC_FOUND_ROWS ufdwth.*, u.first_name, u.last_name, u.company_name, u.account_type, u.is_authorized_physical_person,u.profile_name, c.country_name, ud.user_bank_withdrawal_variable_symbol', false);
      $this->db->from('users_funds_direct_bank_transfer_withdraw_transactions ufdwth');
      $this->db->join('users u', 'u.user_id = ufdwth.user_id', 'left');
      $this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
      $this->db->join('countries c', 'c.id = ufdwth.country');
      $this->db->order_by('ufdwth.id', 'desc');
      $this->db->limit($rowperpage, $rowno);  
      $result =  $this->db->get()->result_array();
      $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');

      return ['data' => $result, 'total' => $query->row()->Count];
    }
    /**
     * This method is used to get all deposit funds via payment processor
    */
    public function get_all_deposit_funds_via_payment_processor($rowno, $rowperpage,$str = '') {
      $this->db->select('SQL_CALC_FOUND_ROWS ppt.*, u.first_name, u.last_name, u.company_name, u.account_type, u.is_authorized_physical_person,u.profile_name', false);
      $this->db->select('pct.card_number, pct.card_brand,pct.country_code,pct.bank_name as card_bank_name,pct.card_type,pbt.bank_name,pbt.bank_account_number,pbt.bank_account_owner_name');
      $this->db->from('users_funds_deposits_via_payment_processor_transactions ppt');
      $this->db->join('users_funds_deposits_via_payment_processor_card_trans pct', 'pct.payment_id = ppt.payment_id', 'left');
      $this->db->join('users_funds_deposits_via_payment_processor_bank_trans pbt', 'pbt.payment_id = ppt.payment_id', 'left');
      $this->db->join('users u', 'u.user_id = ppt.user_id', 'left');

      if(!is_null($str)) {
        $this->db->like('CONCAT(u.first_name," ",u.last_name)', $str);
        $this->db->or_like('pbt.bank_name', $str);
        $this->db->or_like('pct.bank_name', $str);
        $this->db->or_like('ppt.payment_id', $str);
        $this->db->or_like('pct.card_brand', $str);
        $this->db->or_like('pct.card_type', $str);
      }

      $this->db->order_by('ppt.id', 'desc');
      $this->db->limit($rowperpage, $rowno);  
      $result =  $this->db->get()->result_array();
      $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');

      return ['data' => $result, 'total' => $query->row()->Count];
    }
    // validation for deposited funds
    public function save_direct_bank_transfer_funds_validation($post_data) {
      include_once '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
      include_once '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';
      $i = 0;
      
      if(empty($post_data['user'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'user_err';
        $msg['error'][$i]['message'] = 'User is required';
        $i++;
      }

      if(empty($post_data['deposited_amount'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'deposit_amount_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_deposited_amount_required_error_message'];
        $i++;
      } else if(!empty($post_data['deposited_amount']) && !is_numeric($post_data['deposited_amount'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'deposit_amount_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_deposited_amount_invalid_error_message'];
        $i++;
      }

      if(empty($post_data['account_owner'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'account_owner_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_account_owner_required_error_message'];
        $i++;
      }
      if(empty($post_data['account_number'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'account_number_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_account_number_required_error_message'];
        $i++;
      }
      if(empty($post_data['bank_name'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'bank_name_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_bank_name_required_error_message'];
        $i++;
      }
      if(empty($post_data['bank_code'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'bank_code_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_bank_code_required_error_message'];
        $i++;
      }
      if(empty($post_data['country'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'country_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_country_required_error_message'];
        $i++;
      } else if(!empty($post_data['country']) && $post_data['country'] != $config['reference_country_id']) {
        if(empty($post_data['iban'])) {
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'iban_err';
          $msg['error'][$i]['message'] = $config['deposit_funds_bank_iban_required_error_message'];
          $i++;
        }
        if(empty($post_data['swift_code'])) {
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'bic_swift_code_err';
          $msg['error'][$i]['message'] = $config['deposit_funds_bank_bic_swift_code_required_error_message'];
          $i++;
        }
      } 
      if(empty($post_data['transaction_date'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_date_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_required_error_message'];
        $i++;
      } else if(!empty($post_data['transaction_date']) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $post_data['transaction_date'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_date_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_format_error_message'];
        $i++;
      } else if(!empty($post_data['transaction_date']) && preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $post_data['transaction_date'])) {
        $test_array = explode('.', $post_data['transaction_date']);
        if(count($test_array) == 3) {
          if (!checkdate((int)trim($test_array[1]), (int)trim($test_array[0]), (int)trim($test_array[2]))) { 
            $msg['status'] = 'FAILED';
            $msg['error'][$i]['id'] = 'transaction_date_err';
            $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_error_message'];
            $i++;
          }
        } else {
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'transaction_date_err';
          $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_error_message'];
          $i++;
        }
      }
      if(empty($post_data['transaction_id'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_id_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_transaction_id_required_error_message'];
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
      include_once '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
      include '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';
      
      $userdata = $this->db->get_where('users_details', ['user_id' => $post_data['user']])->row_array();

      if(empty($post_data['user'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'user_err';
        $msg['error'][$i]['message'] = 'User is required';
        $i++;
      }

      if(empty($post_data['withdraw_amount'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'withdraw_amount_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_withdrawal_amount_required_error_message'];
        $i++;
      } else if(!empty($post_data['withdraw_amount']) && !is_numeric($post_data['withdraw_amount'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'withdraw_amount_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_withdrawal_amount_invalid_error_message'];
        $i++;
      } else if(!empty($post_data['withdraw_amount']) && !empty($userdata) && $post_data['withdraw_amount'] > $userdata['user_account_balance']) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'withdraw_amount_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_withdrawal_amount_greater_than_available_balance_error_message'];
        $i++;
      }

      if(empty($post_data['account_owner'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'account_owner_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_account_owner_required_error_message'];
        $i++;
      }
      if(empty($post_data['account_number'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'account_number_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_account_number_required_error_message'];
        $i++;
      }
      if(empty($post_data['bank_name'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'bank_name_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_bank_name_required_error_message'];
        $i++;
      }
      if(empty($post_data['bank_code'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'bank_code_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_bank_code_required_error_message'];
        $i++;
      }
      if(empty($post_data['country'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'country_err';
        $msg['error'][$i]['message'] = $config['withdraw_funds_country_required_error_message'];
        $i++;
      } else if(!empty($post_data['country']) && $post_data['country'] != $config['reference_country_id']) {
        if(empty($post_data['iban'])) {
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'iban_err';
          $msg['error'][$i]['message'] = $config['withdraw_funds_bank_iban_required_error_message'];
          $i++;
        }
        if(empty($post_data['swift_code'])) {
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'bic_swift_code_err';
          $msg['error'][$i]['message'] = $config['withdraw_funds_bank_bic_swift_code_required_error_message'];
          $i++;
        }
      } 
      if(empty($post_data['transaction_date'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_date_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_required_error_message'];
        $i++;
      } else if(!empty($post_data['transaction_date']) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $post_data['transaction_date'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_date_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_format_error_message'];
        $i++;
      } else if(!empty($post_data['transaction_date']) && preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $post_data['transaction_date'])) {
        $test_array = explode('.', $post_data['transaction_date']);
        if(count($test_array) == 3) {
          if (!checkdate((int)trim($test_array[1]), (int)trim($test_array[0]), (int)trim($test_array[2]))) { 
            $msg['status'] = 'FAILED';
            $msg['error'][$i]['id'] = 'transaction_date_err';
            $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_error_message'];
            $i++;
          }
        } else {
          $msg['status'] = 'FAILED';
          $msg['error'][$i]['id'] = 'transaction_date_err';
          $msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_error_message'];
          $i++;
        }
      }
      if(empty($post_data['transaction_id'])) {
        $msg['status'] = 'FAILED';
        $msg['error'][$i]['id'] = 'transaction_id_err';
        $msg['error'][$i]['message'] = $config['deposit_funds_transaction_id_required_error_message'];
        $i++;
      }
      if($i == 0){
        $msg['status'] = 'SUCCESS';
        $msg['message'] = '';
      }
      return $msg;
    }

    // Get all users details
    public function get_all_users() {
      $this->db->select('u.*,ud.user_bank_deposit_variable_symbol,ud.user_bank_withdrawal_variable_symbol');
      $this->db->from('users u');
      $this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
      return $this->db->get()->result_array();
    }
}