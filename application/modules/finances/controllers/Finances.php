<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

Class Finances extends MX_Controller {
  public function __construct () {
		parent::__construct ();
		$this->load->library('generate_pdf', null, 'pdf');
    $this->load->model('finances_model');
    $this->load->model('dashboard/Dashboard_model');;
	}	
  /**
   * This method trigger via paypal when payment successfully done from user
   */
  public function deposit_funds_via_paypal($param = '') {
    if($this->input->method() != 'post') {
      show_custom_404_page();
    } else {
      if($param == 'notify') {
        $row = $this->input->post();
        if($this->verifyTransaction($row)) {
            $custom = json_decode($row['custom'], true);

            // file_put_contents('temp/test.txt', json_encode($row));

            $user_detail = $this->db->get_where('users_details', ['user_id' => (int)$custom['user_id']])->row_array();
            $deposit_funds = [
              'user_id' => (int)$custom['user_id'],
              'transaction_id' => $row['txn_id'],
              'deposit_amount' => floatval($row['mc_gross'] - $custom['paypal_charges']),
              'transaction_date' => date('Y-m-d H:i:s'),
              'paypal_account' => $row['payer_email'],
              'account_available_funds' => ($user_detail['user_account_balance'] + floatval($row['mc_gross'] - $custom['paypal_charges'])),
              'total_transaction_charged_fee' => $custom['paypal_charges'],
              'paypal_transaction_charged_fee' => $row['mc_fee'],
              'business_transaction_charged_fee' => ($custom['paypal_charges'] - $row['mc_fee'])
            ];
            $this->db->insert('users_funds_paypal_deposits_transactions_history', $deposit_funds);
            
            // update funds to user account
            $this->db->set('user_account_balance', 'user_account_balance + ' . floatval($row['mc_gross'] - $custom['paypal_charges']), FALSE);
            $this->db->where('user_id', (int)$custom['user_id']);
            $this->db->update('users_details');

            $activity_msg = $this->config->item('deposit_funds_via_paypal_display_activity_log_message');
            $activity_msg = str_replace('{transaction_amount}', format_money_amount_display(floatval($row['mc_gross'] - $custom['paypal_charges'])), $activity_msg);
            user_display_log($activity_msg, (int)$custom['user_id']);
            
        }       
      } else {
        show_custom_404_page();
      }
    }
    
  }
  /**
   * This method is used to verify transaction done through paypal is valid or not 
  */
  private function verifyTransaction($data) {
  
      $paypalUrl = $this->config->item('sandbox') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

      $req = 'cmd=_notify-validate';
      foreach ($data as $key => $value) {
          $value = urlencode(stripslashes($value));
          $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
          $req .= "&$key=$value";
      }

      $ch = curl_init($paypalUrl);
      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
      curl_setopt($ch, CURLOPT_SSLVERSION, 6);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
      $res = curl_exec($ch);

      if (!$res) {
          $errno = curl_errno($ch);
          $errstr = curl_error($ch);
          curl_close($ch);
          throw new Exception("cURL error: [$errno] $errstr");
      }

      $info = curl_getinfo($ch);

      // Check the http response
      $httpCode = $info['http_code'];
      if ($httpCode != 200) {
          throw new Exception("PayPal responded with http code $httpCode");
      }

      curl_close($ch);

      return $res === 'VERIFIED';
  }
	//deposit funds page
	public function deposit_funds($param = '', $param1 = '') { 
      if(!$this->session->userdata('user')) {
        if($this->input->is_ajax_request ()){ 
          $res['status'] = 400;
          echo json_encode($res);
          return;
        } else {
          redirect(base_url());
        }
      }

      if(check_session_validity()) { 
        $user = $this->session->userdata('user');
        $user_id = $user[0]->user_id;
        if($this->input->method() == 'post') {
          $row = $this->input->post();
          if($this->input->is_ajax_request ()){
            if($user_id != $row['user_id']) {
              $res['popup_heading'] = $this->config->item('popup_alert_heading');
              $res['warning'] = $this->config->item('different_users_session_conflict_message');
              $res['status'] = 440;
            } else {
              $res['status'] = 200;
            }
            echo json_encode($res);
            return;
          }
          if(empty($param) && !isset($row["txn_id"]) && !isset($row["txn_type"])) {
            $amt = $row['deposit_amt'];
            $amt = preg_replace('/\s+/', '', $amt);
            $amt = floatval($amt);
            $paypal_amt = $amt;
            if($amt >= $this->config->item('deposit_funds_via_paypal_first_amounts_range_min_value') && $amt <= $this->config->item('deposit_funds_via_paypal_first_amounts_range_max_value')) {
              $percentage_chrg = (($amt * $this->config->item('deposit_funds_via_paypal_processing_fees_percentage_charge_first_amounts_range')) / 100);
            } else if($amt >= $this->config->item('deposit_funds_via_paypal_second_amounts_range_min_value') && $amt <= $this->config->item('deposit_funds_via_paypal_second_amounts_range_max_value')) {
              $percentage_chrg = (($amt * $this->config->item('deposit_funds_via_paypal_processing_fees_percentage_charge_second_amounts_range')) / 100);
            }
            

            $amt += $percentage_chrg;


            $min = $this->config->item('deposit_funds_via_paypal_first_amounts_range_min_value');
            $max = $this->config->item('deposit_funds_via_paypal_second_amounts_range_max_value');
            if($amt < $min || $amt > $max) {
              redirect(base_url($this->config->item('finance_deposit_funds_page_url')));
            }

            $paypalUrl = $this->config->item('sandbox') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
            // Set the PayPal account.
            $data['business'] = $this->config->item('paypal_business_email');
            $data['return'] = stripslashes(site_url('finances/deposit_funds/success/'.$paypal_amt));
            $data['cancel_return'] = stripslashes(site_url($this->config->item('finance_deposit_funds_page_url')));
            $data['notify_url'] = stripslashes(site_url('finances/deposit_funds_via_paypal/notify/'));
            $data['item_name'] = 'Deposit fund';
            $data['amount'] = $amt;
            $data['currency_code'] = 'CZK';
            $data['cmd'] = '_xclick';
            $data['no_shipping'] = 1;
            $data['custom'] = json_encode(['user_id' => $user[0]->user_id, 'transaction_type' => 'deposit', 'paypal_charges' => ($percentage_chrg)]);
            // Build the query string from the data.
            $queryString = http_build_query($data);

            header('location:' . $paypalUrl . '?' . $queryString);
            exit();
          } 
        } else {
          if(!empty($param) && $param == 'success') {
            $deposit_funds_success_message = $this->config->item('deposit_funds_via_paypal_success_message');
            $deposit_funds_success_message = str_replace('{transaction_amount}', format_money_amount_display($param1), $deposit_funds_success_message);
            $data['deposit_funds_success_message'] = $deposit_funds_success_message;
            $this->session->set_userdata('deposit_funds_success', $deposit_funds_success_message);
            redirect(site_url($this->config->item('finance_deposit_funds_page_url')));
          }
          $data['current_page'] = 'deposit-funds';
          ########## set the profile title meta tag and meta description  start here #########
          $account_type = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id);
          $name =  $account_type == USER_PERSONAL_ACCOUNT_TYPE || ($account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
          $title_meta_tag = $this->config->item('deposit_funds_page_title_meta_tag');
          $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
          $description_meta_tag = $this->config->item('deposit_funds_page_description_meta_tag');
          $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
          $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
          ########## set the profile title tag start end #########
          $data['countries'] = $this->Dashboard_model->get_countries();
          $data['paypal_transactions'] = $this->finances_model->get_all_deposit_funds_transactions($this->config->item('deposit_funds_via_paypal_transaction_listing_limit'));
          $data['payment_card_transactions'] = [];
          $data['bank_transactions'] = $this->finances_model->get_all_direct_bank_transfer_deposits_transactions($this->config->item('deposit_funds_direct_bank_transfer_transaction_listing_limit'));
          $data['payment_card_charges'] = $this->db->from('users_deposits_via_payment_processor_charged_fees_ranges')->limit(1)->get()->row_array();
          $data['payment_processor_transactions'] = $this->finances_model->get_all_payments_processor_transactions($this->config->item('deposit_funds_via_payment_processor_transaction_listing_limit'), $user_id);
          $this->layout->view ('deposit_funds', '', $data, 'include');
        }
      }
      
  }
  
  // This method is used to get processing fees for deposited amount via payment processor
  public function ajax_get_processing_fee_for_deposited_amounts_via_payment_processor() {
	  if(!$this->input->is_ajax_request ()){ 
       show_custom_404_page();
        return;
     }
    if(!$this->session->userdata('user')) {
       //else {
        $res['status'] = 400;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) {
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }
      $amount = $row['amount'];
      $charges = $this->finances_model->calculate_processing_fees_for_deposited_amounts_via_payment_processor($amount);
      
      $deposit_funds_via_payment_card_processing_fee_info = $this->config->item('deposit_funds_via_payment_processor_processing_fee_info');
      $deposit_funds_via_payment_card_processing_fee_info = str_replace('{fee_amount}', $charges, $deposit_funds_via_payment_card_processing_fee_info);
      $res['status'] = 200;
      $res['charges'] = $charges;
      $res['tooltip_msg'] = $deposit_funds_via_payment_card_processing_fee_info;
      echo json_encode($res);
      return;
    }
  }

  public function deposit_funds_via_payment_processor($param1 = '', $param2 = '') {
    if($param1 == 'notify') {
      $result = $this->input->get();
      $param2 = cryptor_decrypt($param2);
      if(!empty($result) && $this->verifySignature($result['signature'], $result)) {
        
        $payment_detail = $this->getPaymentDetails($result);
        $user_detail = $this->db->get_where('users_details', ['user_id' => $param2])->row_array();
        // pre($payment_detail);
        $activity_log = $this->config->item('deposit_funds_via_payment_processor_user_activity_log_message');
        if($result['methodId'] == 31) {
          $card = $this->getTransactionRequestCardInfo($result);
          if(!empty($card)) {

            $amount = is_null($payment_detail['receivedValue']) ? $payment_detail['value'] : $payment_detail['receivedValue'];

            $pg_charge = 0;
            $deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway = $this->config->item('deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway');
            $deposit_funds_via_payment_processor_fixed_charge_for_payment_getway = $this->config->item('deposit_funds_via_payment_processor_fixed_charge_for_payment_getway');
            $pg_charge = (($amount * $deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway) / 100 ) + $deposit_funds_via_payment_processor_fixed_charge_for_payment_getway;

            $charges = $this->finances_model->calculate_processing_fees_for_deposited_amounts_via_payment_processor($amount);
            $amount -= $charges;

            

            // Insert data into payment processor table
            $payment_processor_data = [
              'user_id' => $param2,
              'transaction_date' => date('Y-m-d H:i:s'),
              'deposit_transfer_type' => 'payment_card_transaction',
              'amount_requested' => $payment_detail['value'],
              'amount' => $amount,
              'business_transaction_charged_fee' => $charges,
              'payment_gateway_transaction_charged_fee' => $pg_charge,
              'amount_credited' => is_null($payment_detail['receivedValue']) ? $payment_detail['value'] : $payment_detail['receivedValue'],
              'user_account_balance' => $user_detail['user_account_balance'],
              'payment_id' => $payment_detail['id'],
              'signature' => $result['signature'],
              'status_code' => $result['status'],
              'deposit' => $payment_detail['deposit'],
              'user_ip' => $payment_detail['ip'],
              'transaction_completion_date' => is_null($payment_detail['finishedOn']) ? null : date('Y-m-d H:i:s', strtotime($payment_detail['finishedOn'])),
              'transaction_cancellation_date' => is_null($payment_detail['canceledOn']) ? null : date('Y-m-d H:i:s', strtotime($payment_detail['canceledOn'])),
              'method_id' => $result['methodId']
            ];
            $this->db->insert('users_funds_deposits_via_payment_processor_transactions', $payment_processor_data);

            $card_brand = [
              'mc' => 'Mastercard',
              'visa' => 'Visa'
            ];
            // Insert data into card transaction table
            $card_detail = [
              'payment_id' => $result['paymentId'],
              'card_number' => $card['cardNumberMasked'],
              'card_brand' => array_key_exists(strtolower($card['cardBrand']), $card_brand) ? $card_brand[strtolower($card['cardBrand'])] : ucfirst($card['cardBrand']),
              'country_code' => $card['countryCode'],
              'bank_name' => $card['bankName'],
              'card_type' => $card['cardType'],
              'card_level' => $card['cardLevel']
            ];
            $this->db->insert('users_funds_deposits_via_payment_processor_card_trans', $card_detail);
            if(in_array($result['status'], [2,6])) {
              // Update user account balance
              $this->db->set('user_account_balance', 'user_account_balance + '.$amount, false);
              $this->db->where('user_id', $user_detail['user_id']);
              $this->db->update('users_details');

              $activity_log = str_replace('{deposited_amount}', format_money_amount_display($amount), $activity_log);
              user_display_log($activity_log, (int)$user_detail['user_id']);
            }

            if(in_array($result['status'], [2,6])) {
              $succ = $this->config->item('deposit_funds_via_payment_processor_transaction_success_msg');
              $succ = str_replace('{transaction_amount}', format_money_amount_display($amount), $succ);
              $this->session->set_userdata('succ', $succ);
            } else if($result['status'] == 3) { // payment cancelled
              $this->session->set_userdata('error', $this->config->item('deposit_funds_via_payment_processor_transaction_cancelled_error_msg'));
            } else if($result['status'] == 4) { // Error
              $this->session->set_userdata('error', $this->config->item('deposit_funds_via_payment_processor_transaction_failed_error_msg'));
            } else if($result['status'] == 7) { // waiting for confirmation
              $this->session->set_userdata('error', $this->config->item('deposit_funds_via_payment_processor_transaction_success_waiting_for_confirmation_msg'));
            }
            
          }
        } else {
          $amount = is_null($payment_detail['receivedValue']) ? $payment_detail['value'] : $payment_detail['receivedValue'];

          $pg_charge = 0;
          $deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway = $this->config->item('deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway');
          $deposit_funds_via_payment_processor_fixed_charge_for_payment_getway = $this->config->item('deposit_funds_via_payment_processor_fixed_charge_for_payment_getway');
          $pg_charge = (($amount * $deposit_funds_via_payment_processor_processing_fees_percentage_charge_for_payment_getway) / 100 ) + $deposit_funds_via_payment_processor_fixed_charge_for_payment_getway;

          $charges = $this->finances_model->calculate_processing_fees_for_deposited_amounts_via_payment_processor($amount);
          $amount -= $charges;

          

          // Insert data into payment processor table
          $payment_processor_data = [
            'user_id' => $param2,
            'transaction_date' => date('Y-m-d H:i:s'),
            'deposit_transfer_type' => 'bank_transfer_transaction',
            'amount_requested' => $payment_detail['value'],
            'amount' => $amount,
            'business_transaction_charged_fee' => $charges,
            'payment_gateway_transaction_charged_fee' => $pg_charge,
            'amount_credited' => is_null($payment_detail['receivedValue']) ? $payment_detail['value'] : $payment_detail['receivedValue'],
            'user_account_balance' => $user_detail['user_account_balance'],
            'payment_id' => $payment_detail['id'],
            'signature' => $result['signature'],
            'status_code' => $result['status'],
            'deposit' => $payment_detail['deposit'],
            'user_ip' => $payment_detail['ip'],
            'transaction_completion_date' => is_null($payment_detail['finishedOn']) ? null : date('Y-m-d H:i:s', strtotime($payment_detail['finishedOn'])),
            'transaction_cancellation_date' => is_null($payment_detail['canceledOn']) ? null : date('Y-m-d H:i:s', strtotime($payment_detail['canceledOn'])),
            'method_id' => $result['methodId']
          ];
          $this->db->insert('users_funds_deposits_via_payment_processor_transactions', $payment_processor_data);

          $methods = array_flip($this->config->item('deposit_funds_via_payment_processor_methods_id'));
          // Insert data into card transaction table
          $bank_detail = [
            'payment_id' => $result['paymentId'],
            'bank_name' => $this->config->item('deposit_funds_via_payment_processor_method_id_associated_bank_name')[$result['methodId']],
            'bank_account_number' => !empty($payment_detail['accountNumber']) ? $payment_detail['accountNumber']  : '',
            'bank_account_owner_name' => !empty($payment_detail['accountOwnerName']) ? $payment_detail['accountOwnerName'] : ''
          ];
          $this->db->insert('users_funds_deposits_via_payment_processor_bank_trans', $bank_detail);
          if(in_array($result['status'], [2,6])) {
            // Update user account balance
            $this->db->set('user_account_balance', 'user_account_balance + '.$amount, false);
            $this->db->where('user_id', $user_detail['user_id']);
            $this->db->update('users_details');

            $activity_log = str_replace('{deposited_amount}', format_money_amount_display($amount), $activity_log);
            user_display_log($activity_log, (int)$user_detail['user_id']);
          }

          if(in_array($result['status'], [2,6])) {
              $succ = $this->config->item('deposit_funds_via_payment_processor_transaction_success_msg');
              $succ = str_replace('{transaction_amount}', format_money_amount_display($amount), $succ);
              $this->session->set_userdata('succ', $succ);
            } else if($result['status'] == 3) { // payment cancelled
              $this->session->set_userdata('error', $this->config->item('deposit_funds_via_payment_processor_transaction_cancelled_error_msg'));
            } else if($result['status'] == 4) { // Error
              $this->session->set_userdata('error', $this->config->item('deposit_funds_via_payment_processor_transaction_failed_error_msg'));
            } else if($result['status'] == 7) { // waiting for confirmation
              $this->session->set_userdata('error', $this->config->item('deposit_funds_via_payment_processor_transaction_success_waiting_for_confirmation_msg'));
            }
        }
        redirect(base_url($this->config->item('finance_deposit_funds_page_url')));
      }
    }
    if(!$this->session->userdata('user')) {
      redirect(base_url());
    }
    if(check_session_validity()) {
      $row = $this->input->post();  
      $user = $this->session->userdata('user');    
      if($row['user_id'] != $user[0]->user_id) {
        redirect(base_url());
      }
      
      $param = [];
      $payment_method_ids = $this->config->item('deposit_funds_via_payment_processor_methods_id');
      
      $param['merchantId'] = $this->config->item('deposit_funds_thepay_merchant_id');
      $param['accountId'] = $this->config->item('deposit_funds_thepay_account_id');
      if(!is_null($row['deposit_amt'])) {
        $amount = str_replace(' ', '',$row['deposit_amt']);
        // $param['value'] = $amount;
        $param['value'] = number_format($amount, 2, '.', '');
      }
      $param['currency'] = 1;
      $param['description'] = $this->config->item('deposit_funds_thepay_description_txt');
      $param['merchantData'] = 1;
      $param['customerData'] = cryptor_encrypt($user[0]->user_id);
      $param['returnUrl'] = site_url('finances/deposit_funds_via_payment_processor/notify/'.cryptor_encrypt($user[0]->user_id));
      $param['backToEshopUrl'] = site_url('finances/deposit_funds');
      // $param['deposit'] = 1;
      if(!empty($row['payment_method']) && array_key_exists($row['payment_method'], $payment_method_ids)) {
        $param['methodId'] = $payment_method_ids[$row['payment_method']];
      }
      $signature = '';

      foreach ($param as $key => $val) {
        $signature .= $key."=".$val."&";
      }
		  $signature .= "password=".$this->config->item('deposit_funds_thepay_password');
      $param['signature'] = md5($signature);
      $url = $this->config->item('deposit_funds_via_payment_processor_is_use_test_url') ? 'https://www.thepay.cz/demo-gate/' : 'https://www.thepay.cz/gate/';
      $url .= '?'.http_build_query($param);
      header('Location:'.$url);
      // pre($url);
    }
  }
  // This method is used to verfiry signature of transaction happened through payment getway
  private function verifySignature($signature, $response) {
    $param['merchantId'] = $response['merchantId'];
    $param['accountId'] = $response['accountId'];
    $param['value'] = $response['value'];
    $param['currency'] = $response['currency'];
    $param['methodId'] = $response['methodId'];
    $param['description'] = $response['description'];
    $param['merchantData'] = $response['merchantData'];
    $param['status'] = $response['status'];
    $param['paymentId'] = $response['paymentId'];
    $param['ipRating'] = $response['ipRating'];
    $param['isOffline'] = $response['isOffline'];
    $param['needConfirm'] = $response['needConfirm'];
    if(!empty($response['isConfirm'])) {
      $param['isConfirm'] = $response['isConfirm'];
    }
    if(!empty($response['isConfirm'])) {
      $param['variableSymbol'] = $response['variableSymbol'];
    }
    if(!empty($response['specificSymbol'])) {
      $param['specificSymbol'] = $response['specificSymbol'];
    }
    if(!empty($response['deposit'])) {
      $param['deposit'] = $response['deposit'];
    }
    if(!empty($response['isRecurring'])) {
      $param['isRecurring'] = $response['isRecurring'];
    }
    if(!empty($response['customerAccountNumber'])) {
      $param['customerAccountNumber'] = $response['customerAccountNumber'];
    }
    if(!empty($response['customerAccountName'])) {
      $param['customerAccountName'] = $response['customerAccountName'];
    }
    $sign = '';
    foreach ($param as $key => $val) {
      $sign .= $key."=".$val."&";
    }
    $sign .= "password=".$this->config->item('deposit_funds_thepay_password');
    if($signature == md5($sign)) {
      return true;
    }
    return false;
  }
  //  This method is used to get card which card user used during payment and other information related to card
  private function getTransactionRequestCardInfo($result) {
    $url = $this->config->item('deposit_funds_via_payment_processor_is_use_test_url') ? 'https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl' : 'https://www.thepay.cz/gate/api/gate-api.wsdl';
    $client = new SoapClient($url);
    $param['merchantId'] = $this->config->item('deposit_funds_thepay_merchant_id');
    $param['accountId'] = $this->config->item('deposit_funds_thepay_account_id');
    $param['paymentId'] = $result['paymentId'];
    $param['password'] = $this->config->item('deposit_funds_thepay_password');
    $signature = md5(http_build_query(array_filter($param)));

    $param1['merchantId'] = $this->config->item('deposit_funds_thepay_merchant_id');
    $param1['accountId'] = $this->config->item('deposit_funds_thepay_account_id');
    $param1['paymentId'] = $result['paymentId'];
    $param1['signature'] = $signature;

    $result = $client->getCardInfoRequest($param1);
    return (array)$result;
    
  }
  // This method is used to get payment information of transaction done through payment getway
  private function getPaymentDetails($result) {
    $url = $this->config->item('deposit_funds_via_payment_processor_is_use_test_url') ? 'https://www.thepay.cz/demo-gate/api/data-demo.wsdl' : 'https://www.thepay.cz/gate/api/data.wsdl';
    $client = new SoapClient($url, array('features' => SOAP_SINGLE_ELEMENT_ARRAYS));
    $param = [
      'merchantId' => $this->config->item('deposit_funds_thepay_merchant_id'),
      'paymentId' => $result['paymentId']
    ];
   
    $param1 = [
      'merchantId' => $this->config->item('deposit_funds_thepay_merchant_id'),
      'paymentId' => $result['paymentId'],
      'password' => $this->config->item('deposit_funds_thepay_password')
    ];

    $stringParts = array();
		foreach($param1 as $key => $value) {
			if($value == '') {
				// Empty values are not part of the digest. Not even its key.
				continue;
			}
			$stringParts[] = $key . '=' . $value;
		}
		$string = implode('&', $stringParts);
		$digest = hash('sha256', $string);
    $param['signature'] = $digest;
    
    $raw_response = $client->getPayment($param);
    return (array)$raw_response->payment;
  }
  // deposited funds from bank - assets/js/modules/deposit_funds.js
  public function ajax_deposited_funds_bank() {
	  if(!$this->input->is_ajax_request ()){ 
		show_custom_404_page();
		return;
	 }
    if(!$this->session->userdata('user')) {
       //else {
        $res['status'] = 400;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) { 
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }
      if(!empty($row['deposited_amount'])) {
        $row['deposited_amount'] = (double)str_replace(' ', '', $row['deposited_amount']);
      }
      
      $validation_data_array = $this->finances_model->save_direct_bank_transfer_funds_validation($row);
      if($validation_data_array['status'] == 'SUCCESS') {
        $direct_bank_transfer_deposits = [
          'user_id' => $user[0]->user_id,
          'user_profile_name' => $user[0]->profile_name,
          'deposited_amount' => $row['deposited_amount'],
          'bank_transaction_date' => date('Y-m-d', strtotime($row['transaction_date'])),
          'bank_transaction_id' => $row['transaction_id'],
          'bank_account_owner_name' => $row['account_owner'],
          'bank_account_number' => $row['account_number'],
          'bank_name' => $row['bank_name'],
          'bank_code' => $row['bank_code'],
          'bank_account_iban_code' => $row['iban'],
          'bank_account_bic_swift_code' => $row['swift_code'],
          'country' => $row['country'],
        ];
        $this->db->insert('users_funds_direct_bank_transfer_deposits_transactions', $direct_bank_transfer_deposits);

        $activity_log = $this->config->item('deposit_funds_direct_bank_transfer_user_activity_log_message');
        $activity_log = str_replace('{deposited_amount}', format_money_amount_display($row['deposited_amount']), $activity_log);
        user_display_log($activity_log);

        $res['status'] = 200;
        $res['data'] = $this->finances_model->get_all_direct_bank_transfer_deposits_transactions($this->config->item('deposit_funds_direct_bank_transfer_transaction_listing_limit'));
        $res['msg'] = $this->config->item('deposit_funds_direct_bank_transfer_confirmation_message');
        echo json_encode($res);
      } else {
        echo json_encode($validation_data_array);
      }
      return;
    }
  }
  // withdraw funds from bank - assets/js/modules/withdraw_funds.js
  public function ajax_withdraw_funds_bank() {
  
	  if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
    }
    if(!$this->session->userdata('user')) {
       //else {
        $res['status'] = 400;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) { 
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }
      if(!empty($row['withdraw_amount'])) {
        $row['withdraw_amount'] = (double)str_replace(' ', '', $row['withdraw_amount']);
      }
      
      $validation_data_array = $this->finances_model->direct_bank_transfer_funds_withdraw_validation($row);
      if($validation_data_array['status'] == 'SUCCESS') {
        $direct_bank_transfer_deposits = [
          'user_id' => $user[0]->user_id,
          'user_profile_name' => $user[0]->profile_name,
          'withdraw_amount' => $row['withdraw_amount'],
          'user_withdraw_request_date' => date('Y-m-d H:i:s'),
          'bank_account_owner_name' => $row['account_owner'],
          'bank_account_number' => $row['account_number'],
          'bank_name' => $row['bank_name'],
          'bank_code' => $row['bank_code'],
          'bank_account_iban_code' => !empty($row['iban']) ? $row['iban'] : null,
          'bank_account_bic_swift_code' => !empty($row['swift_code']) ? $row['swift_code'] : null,
          'country' => $row['country'],
        ];
        $this->db->insert('users_funds_direct_bank_transfer_withdraw_transactions', $direct_bank_transfer_deposits);

        $this->db->where('user_id', $user[0]->user_id);
        $this->db->set('user_account_balance', 'user_account_balance - '.$row['withdraw_amount'], false);
        $this->db->update('users_details');

        $activity_log = $this->config->item('withdraw_funds_direct_bank_transfer_user_activity_log_message');
        $activity_log = str_replace('{withdraw_amount}', format_money_amount_display($row['withdraw_amount']), $activity_log);
        user_display_log($activity_log);

        $res['status'] = 200;
        $res['data'] = $this->finances_model->get_all_direct_bank_transfer_withdraw_transactions($this->config->item('withdraw_funds_direct_bank_transfer_transaction_listing_limit'));
        $res['msg'] = $this->config->item('withdraw_funds_direct_bank_transfer_confirmation_message');
        echo json_encode($res);
      } else {
        echo json_encode($validation_data_array);
      }
      return;
    }
  } 
	//withdraw funds page
	public function withdraw_funds() 
  { 
      if(!$this->session->userdata('user')) {
          redirect(base_url());
      }
      if(check_session_validity()) {
        $user = $this->session->userdata('user');
        $user_id = $user[0]->user_id;
        $data['current_page'] = 'withdraw-funds';
        ########## set the profile title meta tag and meta description  start here #########
        $account_type = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id);
        $name = $account_type == USER_PERSONAL_ACCOUNT_TYPE || ($account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
        $title_meta_tag = $this->config->item('withdraw_funds_page_title_meta_tag');
        $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
        $description_meta_tag = $this->config->item('withdraw_funds_page_description_meta_tag');
        $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
        $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
        $data['countries'] = $this->Dashboard_model->get_countries();
        $data['paypal_transactions'] = $this->finances_model->get_all_withdraw_funds_transactions($this->config->item('withdraw_funds_via_paypal_transaction_listing_limit'));
        $data['bank_transactions'] = $this->finances_model->get_all_direct_bank_transfer_withdraw_transactions($this->config->item('withdraw_funds_direct_bank_transfer_transaction_listing_limit'));
        ########## set the profile title tag start end #########
        $this->layout->view ('withdraw_funds', '', $data, 'include');
      }
  }
  /**
   * This method is used to withdraw user account balance through paypal account - /assets/js/modules/withdraw_funds.js
   */
  public function ajax_withdraw_funds_via_paypal() {
    if(!$this->input->is_ajax_request ()){
      show_custom_404_page(); //show custom 404 page
      return;
    }
    if(check_session_validity()) {
      $row = $this->input->post();
      $user = $this->session->userdata('user');
      if($user[0]->user_id != $row['user_id']) {
        $res['popup_heading'] = $this->config->item('popup_alert_heading');
        $res['warning'] = $this->config->item('different_users_session_conflict_message');
        $res['status'] = 440;
        echo json_encode($res);
        return;
      }
      $user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
      if($user_detail['user_account_balance'] < $row['withdraw_fund_amount']) {
        $res['status'] = 200;
        $res['error'] = $this->config->item('withdraw_funds_via_paypal_insufficent_balance_error_message');
        echo json_encode($res);
        return;
      }
      if($row['withdraw_fund_amount'] <= 20000) {
        $paypal_charges = ($row['withdraw_fund_amount'] * $this->config->item('withdraw_funds_processing_fees_percentage_charge')) / 100;
        $row['withdraw_fund_amount'] += $paypal_charges;
      } else {
        $paypal_charges = $this->config->item('withdraw_funds_processing_fees_flat_charge');
        $row['withdraw_fund_amount'] += $paypal_charges;
      }
      $withdraw_funds_data = [
        'user_id' => $user[0]->user_id,
        'withdrawal_requested_amount' => $row['withdraw_fund_amount'],
        'withdrawal_request_submit_date' => date('Y-m-d H:i:s'),
        'withdraw_to_paypal_account' => $row['email'],
        'paypal_charges' => $paypal_charges
      ];
      $this->db->insert('users_funds_paypal_withdraw_transactions_history', $withdraw_funds_data);

      $this->db->set('user_account_balance', 'user_account_balance - ' . $row['withdraw_fund_amount'], FALSE);
      $this->db->where('user_id', $user[0]->user_id);
      $this->db->update('users_details');

      $activity_msg = $this->config->item('withdraw_funds_request_via_paypal_display_activity_log_message');
      $activity_msg = str_replace('{transaction_amount}', format_money_amount_display($row['withdraw_fund_amount']), $activity_msg);
      user_display_log($activity_msg, $user[0]->user_id);

      $res['data'] = $this->finances_model->get_all_withdraw_funds_transactions($this->config->item('withdraw_funds_via_paypal_transaction_listing_limit'));

      $res['status'] = 200;
    } else {
      $res['status'] = 404;
    }
    echo json_encode($res);
    return;
  }
  /**
   * Update listing on transaction history page based on user selection
  */
  public function ajax_update_transaction_history_listing_based_on_filteration() {
	  if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
     }
    if(!$this->session->userdata('user')) {
       //else {
        $res['status'] = 404;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) {
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }
      $limit = $this->config->item('transactions_history_listing_limit');

      $filter_arr = [];
      if(!empty($row['filter_arr'])) {
        $filter_arr = $row['filter_arr'];
      }

      $transacton_result = $this->finances_model->get_all_transactions_history_based_on_filter($user[0]->user_id, $row['page'], $limit, $filter_arr);
      $current_page = $row['page'];
      $total = $transacton_result['total'];
      // $c = ceil(($total / $limit));
      // $page = $current_page <= $c ? $current_page : $c;
      $page = $current_page;
      if($page == 1 || $total == 0) {
        $offset = 0;
      } else {
        $offset = (($page - 1) * $limit);
      }
      $transactions_history = $this->finances_model->get_all_transactions_history_based_on_filter($user[0]->user_id, $offset, $limit, $filter_arr);
      $data['transactions_histroy'] = $transactions_history['data'];
      $data['transactions_histroy_cnt'] = $transactions_history['total'];
      $data['limit'] = $limit;
      $data['page_no'] = $page;
      
      if(!empty($transactions_history['data'])) {
        $data['is_last_page'] = (($page ) == ceil(($total / $limit))) ? true : false;
      } else {
        $data['is_last_page'] = true;
      } 
      $res['status'] = 200;
      $res['total'] = $transactions_history['total'];
      $res['total_page'] = ceil(($total / $limit));
      $res['data'] = $this->load->view('ajax_transactions_history_listing', $data, true);
      echo json_encode($res);
    }
    return;
  }
  /**
   * This method is used to update invoices listing based on filter and pagination link which passed
   */
  public function ajax_update_invoices_listing_based_on_filteration() {
	  if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
     }
    if(!$this->session->userdata('user')) {
       //else {
        $res['status'] = 404;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) {
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }
      $filter_arr = [];
      $cond = [
        'user_id' => $user[0]->user_id
      ];
      if(!empty($row['year'])) {
        $filter_arr['year'] = $row['year'];
        $cond['YEAR(invoice_generation_date)'] = $row['year'];
      }
      
      $limit = $this->config->item('invoices_tracking_listing_limit');
      $total_record = $this->db->from('users_invoices_tracking')->where($cond)->count_all_results();
      $pagination = generate_pagination_links($total_record, $this->config->item('finance_invoices_page_url'), $limit, $this->config->item('invoices_tracking_number_of_pagination_links'), '');
      $invoices = $this->finances_model->get_all_user_invoices_based_on_filter($pagination['offset'], $limit, $filter_arr);
      
      $page = $pagination['current_page_no'];
      $data['invoices'] = $invoices['data'];
      $data['pagination_links'] = $pagination['links'];
      $data['invoices_count'] = $invoices['total'];
      $data['limit'] = $limit;
      $multiplication = $data['limit'] * $page;
      $subtraction = ($multiplication - ($data['limit'] - count($invoices['data'])));
      $record_per_page = count($invoices['data']) < $data['limit'] ? $subtraction : $multiplication;
      $page_no = ($data['limit'] * ($page - 1)) + 1;
      $data['rec_per_page'] = $record_per_page;
      $data['page_no'] = $page_no;
      $res['total'] = $data['invoices_count'];
      $res['status'] = 200;
      $res['data'] = $this->load->view('ajax_user_invoices_listing', $data, true);
      echo json_encode($res);
      return;
    } 
  }
  /*
   * This method is used to allow user to download invoice as pdf
  */
  public function download_user_invoice($param = '') {
    if(!$this->session->userdata('user')) {
      redirect(base_url());
    }
    if(check_session_validity()) { 
      $user = $this->session->userdata('user');
      if(isset($param) && !empty($param)) {
        $invoice_tracking_data = $this->db->get_where('users_invoices_tracking', ['id' => $param, 'user_id' => $user[0]->user_id])->row_array();
        if(empty($invoice_tracking_data)) {
          redirect(base_url($this->config->item('finance_invoices_page_url')));
          return;
        }
        $data['invoice_tracking_data'] = $invoice_tracking_data;
        $data['user_details'] = $this->finances_model->get_user_details_to_display_in_invoice_by_user_id($user[0]->user_id);
        $data['invoicing_details'] = $this->finances_model->get_company_invoicing_details_by_user_id($user[0]->user_id);

        $start_date = date('Y-m-d', strtotime('-1 month', strtotime($invoice_tracking_data['invoice_generation_date'])));
        $end_date = date('Y-m-d', strtotime('-1 day', strtotime($invoice_tracking_data['invoice_generation_date'])));
        $filter_arr = [
          'start_date' => $start_date,
          'end_date' => $end_date
        ];
        $data['service_fees'] = $this->finances_model->get_all_service_fees_based_filter_to_display_in_invoice_by_user_id($user[0]->user_id, $filter_arr);
        $data['admin_dispute_service_fees'] = $this->finances_model->get_all_admin_dispute_service_fees_based_filter_to_display_in_invoice_by_user_id($user[0]->user_id, $filter_arr);
        $data['purchased_upgrades'] = $this->finances_model->get_all_purchased_upgrades_based_filter_to_display_in_invoice_by_user_id($user[0]->user_id, $filter_arr);
        $data['deposit_funds_paypal_charges'] = $this->finances_model->get_all_deposit_funds_via_paypal_charges_based_filter_to_display_in_invoice_by_user_id($user[0]->user_id, $filter_arr);
        $data['deposit_funds_payment_procesor_charges'] = $this->finances_model->get_all_deposit_funds_via_payment_processor_charges_based_filter_to_display_in_invoice_by_user_id($user[0]->user_id, $filter_arr);

        //actually, you can pass mPDF parameter on this load() function
        $pdf = $this->pdf->load();
        //generate the PDF!
        $stylesheet = '<style>';
        $stylesheet .= file_get_contents('assets/css/bootstrap.min.css');
        $stylesheet .= file_get_contents('assets/css/main.css');
        $stylesheet .= file_get_contents('assets/css/modules/invoices.css');
        $stylesheet .= '</style>';
        // apply external css
        $pdf->WriteHTML($stylesheet,1);
        $html = $this->load->view('invoice_format', $data, true);
        $pdf->WriteHTML($html,2);
        //offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $download_file_name = $this->config->item('invoice_download_file_prefix').time().".pdf";		
        $pdf->Output($download_file_name, "D");
        exit;
      } else {
        redirect(base_url($this->config->item('finance_invoices_page_url')));
      } 
    }
  }
  //transaction history page
  public function transactions_history() 
  { 
      if(!$this->session->userdata('user')) {
          redirect(base_url());
      }
      if(check_session_validity()) {
        $user = $this->session->userdata('user');
        $user_id = $user[0]->user_id;
        $data['current_page'] = 'transaction-history';
        ########## set the profile title meta tag and meta description  start here #########
        $account_type = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id);
        $name = $account_type == USER_PERSONAL_ACCOUNT_TYPE || ($account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
        $title_meta_tag = $this->config->item('transactions_history_page_title_meta_tag');
        $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
        $description_meta_tag = $this->config->item('transactions_history_page_description_meta_tag');
        $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
        $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
        
        ########## set the profile title tag start end #########
        $limit = $this->config->item('transactions_history_listing_limit');
        $transactions_history = $this->finances_model->get_all_transactions_history_based_on_filter($user_id, 0, $limit, []);
        if($_GET['id']) {
          pre($transactions_history);
        }
        $data['transactions_histroy'] = $transactions_history['data'];
        $data['transactions_histroy_cnt'] = $transactions_history['total'];
        $total = $transactions_history['total'];
        $data['limit'] = $limit;
        $data['page_no'] = 1;
        if(!empty($transactions_history['data'])) {
          $data['is_last_page'] = (($data['page_no'] ) == ceil(($total / $limit))) ? true : false;
        } else {
          $data['is_last_page'] = true;
        } 
        $this->layout->view ('transactions_history', '', $data, 'include');
      }
  }
  //invoices page
  public function invoices() { 
      if(!$this->session->userdata('user')) {
          redirect(base_url());
      }
      if(check_session_validity()) {
        $user = $this->session->userdata('user');
        $user_id = $user[0]->user_id;
        $account_validation_date = $user[0]->account_validation_date;
        $data['current_page'] = 'invoices';
        ########## set the profile title meta tag and meta description  start here #########
        $account_type = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id);
        $name = $account_type == USER_PERSONAL_ACCOUNT_TYPE || ($account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
        $title_meta_tag = $this->config->item('invoices_page_title_meta_tag');
        $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
        $description_meta_tag = $this->config->item('invoices_page_description_meta_tag');
        $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
        $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
        
        $invoice = $this->db->from('users_invoices_tracking')->where('user_id', $user_id)->order_by('id','desc')->limit(1)->get()->row_array();
        if(!empty($invoice)) {
          $date = new DateTime();
          $ctime = strtotime(date('Y-m-d'));
          $validation_day = strtotime($invoice['invoice_generation_date']);
          $date->setDate(date('Y', $validation_day), (date('m', $validation_day) + 1), date('d', $validation_day));
          $dt = $date->format(DATE_FORMAT);
          $data['next_invoice_date'] = $dt;
        } else {
          $validation_day = date('d', strtotime($account_validation_date));
          $ndate = date('Y-m-'.$validation_day, strtotime(date('Y-m-d')));
          $cdate = date('Y-m-d');
          $ctime = strtotime(date('Y-m-d'));
          $date = new DateTime();         
          if($ctime < strtotime($ndate)) {
            $date->setDate(date('Y', $ctime), (date('m', $ctime)), $validation_day);
            $dt = $date->format(DATE_FORMAT);
          } else {
            
            $date->setDate(date('Y', $ctime), (date('m', $ctime) + 1), $validation_day);
            $dt = $date->format(DATE_FORMAT);
          }

          $data['next_invoice_date'] = $dt; 
        }

        $limit = $this->config->item('invoices_tracking_listing_limit');
        $total_record = $this->db->from('users_invoices_tracking')->where('user_id' , $user[0]->user_id)->count_all_results();
        $pagination = generate_pagination_links($total_record, $this->config->item('finance_invoices_page_url'), $limit, $this->config->item('invoices_tracking_number_of_pagination_links'), '');
        $invoices = $this->finances_model->get_all_user_invoices_based_on_filter($pagination['offset'], $limit);

        $page = $pagination['current_page_no'];
        $data['invoices'] = $invoices['data'];
        $data['pagination_links'] = $pagination['links'];
        $data['invoices_count'] = $invoices['total'];
        $data['limit'] = $limit;
        $multiplication = $data['limit'] * $page;
        $subtraction = ($multiplication - ($data['limit'] - count($invoices['data'])));
        $record_per_page = count($invoices['data']) < $data['limit'] ? $subtraction : $multiplication;
        $page_no = ($data['limit'] * ($page - 1)) + 1;
        $data['rec_per_page'] = $record_per_page;
        $data['page_no'] = $page_no;

        $invoices_years = $this->db->select('DISTINCT YEAR(invoice_generation_date) as year')->from('users_invoices_tracking')->where('user_id', $user_id)->order_by('year', 'DESC')->get()->result_array();
        if(!empty($invoices_years)) {
          $invoices_years = array_column($invoices_years, 'year');
        }
        $data['years'] = $invoices_years; 
        ########## set the profile title tag start end #########
        $this->layout->view ('invoices', '', $data, 'include');
      }
  }
  /**
   * This method is used to check user invoicing details 
  */
  public function ajax_check_company_invoicing_details() {
	if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
     }
    if(!$this->session->userdata('user')) {
       //else {
        $res['status'] = 404;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) {
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }

      $result = $this->finances_model->get_company_invoicing_details_by_user_id($user[0]->user_id);
      $res['status'] = 200;
      $data['countries'] = $this->Dashboard_model->get_countries();
      $data['invoicing_details'] = $result;
      $res['data'] = $this->load->view('ajax_company_invoicing_details', $data, true);
      echo json_encode($res);
      return;
    }
  }
  /**
   * This method is used to save invocing information to db 
  */
  public function ajax_save_company_invoicing_details() {
	 if(!$this->input->is_ajax_request ()){ 
        show_custom_404_page();
        return;
      }
    if(!$this->session->userdata('user')) {
      //else {
        $res['status'] = 404;
        echo json_encode($res);
      //}
    }
    if(check_session_validity()) { 
      $row = $this->input->post();
      $user = $this->session->userdata ('user');
	  	if(!empty($_GET['user_id']) && $_GET['user_id'] != $user[0]->user_id){
        echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
        die;
      }
      $invoicing_data = $this->db->get_where('users_company_accounts_invoicing_details', ['user_id' => $user[0]->user_id])->row_array();
      if(!empty($invoicing_data)) {
        $res['status'] = 401;
        $res['error'] = $this->config->item('company_invoicing_details_confirmation_modal_data_already_saved_error_message');
        echo json_encode($res);
        return;
      }
      $validation_array = $this->finances_model->company_invoicing_details_validation($row);
      if($validation_array['status'] == 'SUCCESS') {

        if(!empty($row['action']) && $row['action']=='save_confirm_popup') {
          $invoicing_detail = [
            'user_id' => $user[0]->user_id,
            'company_name' => trim($row['company_name']),
            'company_address_line_1' => trim($row['company_address_1']),
            'company_address_line_2' => trim($row['company_address_2']),
            'company_country' => $row['company_country'],
            'company_registration_number' => $row['company_registration_number'],
            'company_vat_number' => $row['company_vat_number'],
            'company_not_vat_registered' => !empty($row['no_vat']) ? $row['no_vat'] : 'N'
          ];
          $this->db->insert('users_company_accounts_invoicing_details', $invoicing_detail);

          if($user[0]->is_authorized_physical_person == 'N') {
            // profile completion code start 
            $profile_completion_parameters = $this->config->item('user_company_account_type_profile_completion_parameters_tracking_options_value');
            $user_profile_completion_data['has_company_invoicing_details_indicated'] = 'Y';
            $user_profile_completion_data['company_invoicing_details_strength_value'] = $profile_completion_parameters['company_invoicing_details_strength_value'];
            $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
            // profile completion code end 
          }
          
		  
		  
          $invoicing_data = $this->finances_model->get_company_invoicing_details_by_user_id($user[0]->user_id);
          $data['countries'] = $this->Dashboard_model->get_countries();
          $data['invoicing_details'] = $invoicing_data;
          $res['status'] = 'SUCCESS';
          $res['data'] = $this->load->view('ajax_company_invoicing_details', $data, true);
        } else {
          $res['status'] = 'SUCCESS';
        }
        echo json_encode($res);
      } else {
        echo json_encode($validation_array);
      }
      return;
    }
  }
  //invoicing details page
  public function company_invoicing_details() { 
      if(!$this->session->userdata('user')) {
          redirect(base_url());
      }
      if(check_session_validity()) {
        $user = $this->session->userdata('user');
        if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) {
          show_custom_404_page();
          return;
        }
        $user_id = $user[0]->user_id;
        $data['current_page'] = 'invoicing-details';
        $data['countries'] = $this->Dashboard_model->get_countries();
        $data['invoicing_details'] = $this->finances_model->get_company_invoicing_details_by_user_id($user[0]->user_id);
       
        ########## set the profile title meta tag and meta description  start here #########
        $account_type = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id);
        $name = $account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y' ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
        if($account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') {
          $title_meta_tag = $this->config->item('company_app_invoicing_details_page_title_meta_tag');
          $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
          $description_meta_tag = $this->config->item('company_app_invoicing_details_description_meta_tag');
          $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
        } else {
          $title_meta_tag = $this->config->item('company_invoicing_details_page_title_meta_tag');
          $title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
          $description_meta_tag = $this->config->item('company_invoicing_details_description_meta_tag');
          $description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
        }
        
        $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
        
        ########## set the profile title tag start end #########
        $this->layout->view ('company_invoicing_details', '', $data, 'include');
      }
  }
}
?>