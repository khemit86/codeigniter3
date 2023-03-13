<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Finances extends MX_Controller {
  public function __construct() {
    $this->load->model('finances_model');
    $this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->helper('url');    
		parent::__construct();
  }

  /**
   * This method is used to load transaction detail related to deposit funds
  */
  public function deposit_funds() {
    $lay['lft'] = "inc/section_left";
    $data['data'] = $this->auto_model->leftPannel();
    // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
    $deposit_funds = $this->finances_model->get_all_deposit_funds($rowno,$rowperpage);
    $data['deposit_funds'] = $deposit_funds['data'];
    // Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url('finances/deposit_funds/?');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $deposit_funds['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
    $this->layout->view('deposit_funds', $lay, $data);
	}
	/**
   * This method is used to load transaction detail related to direct bank transfer deposit funds
  */
  public function direct_bank_transfer_deposits($param = '', $param1 = '') {

		if(!empty($param) && $param == 'approve') {
			include_once '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
			$result = $this->db->get_where('users_funds_direct_bank_transfer_deposits_transactions', ['id' => $param1])->row_array();
			if(!empty($result)) {
				$this->db->update('users_funds_direct_bank_transfer_deposits_transactions', ['status' => 'transaction_confirmed_by_admin'], ['id' => $param1]);

				$this->db->where('user_id', $result['user_id']);
				$this->db->set('user_account_balance', 'user_account_balance + '.$result['deposited_amount'], false);
				$this->db->update('users_details');

				$activity_log = $config['deposit_funds_direct_bank_transfer_transaction_confirmed_by_admin_user_activity_log'];
				$activity_log = str_replace('{deposited_amount}', format_money_amount_display($result['deposited_amount']), $activity_log);
				user_display_log($activity_log, $result['user_id']);

				$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
			} else {
				$this->session->set_flashdata('error_msg', 'Invalid Action.');
			}
			
			redirect(base_url('finances/direct_bank_transfer_deposits/?per_page='.$this->input->get('per_page')));
		}

		include_once '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';

    $lay['lft'] = "inc/section_left";
    $data['data'] = $this->auto_model->leftPannel();
    // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
    $deposit_funds = $this->finances_model->get_all_direct_bank_transfer_deposited_funds_transactions($rowno,$rowperpage);
    $data['deposit_funds'] = $deposit_funds['data'];
    // Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url('finances/direct_bank_transfer_deposits/?');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $deposit_funds['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
		$data['countries'] = $this->db->get('countries')->result_array();
		$data['users'] = $this->finances_model->get_all_users();
		$data['reference_country_id'] = $config['reference_country_id'];
    $this->layout->view('direct_bank_transfer_deposits', $lay, $data);
	}
	/**
	 * This method is used to store deposit fund transaction related to direct bank transfer 
	*/
	public function ajax_deposit_funds_via_direct_bank_transfer() {
		$row = $this->input->post();
		$row['deposited_amount'] = str_replace(' ', '', $row['deposited_amount']);
		$validation_data_array = $this->finances_model->save_direct_bank_transfer_funds_validation($row);
		if($validation_data_array['status'] == 'SUCCESS') {
			include '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
			$user = $this->db->get_where('users', ['user_id' => $row['user']])->row();
			$row['deposited_amount'] = str_replace(' ', '', $row['deposited_amount']);
			$direct_bank_transfer_deposits = [
          'user_id' => $user->user_id,
          'user_profile_name' => $user->profile_name,
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
					'status' => 'transaction_confirmed_by_admin',
					'admin_manual_entry' => 1
        ];
				$this->db->insert('users_funds_direct_bank_transfer_deposits_transactions', $direct_bank_transfer_deposits);
				
				$this->db->where('user_id', $user->user_id);
				$this->db->set('user_account_balance', 'user_account_balance + '.$row['deposited_amount'], false);
				$this->db->update('users_details');

				$activity_log = $config['deposit_funds_direct_bank_transfer_transaction_confirmed_by_admin_user_activity_log'];
				$activity_log = str_replace('{deposited_amount}', format_money_amount_display($row['deposited_amount']), $activity_log);
				user_display_log($activity_log, $user->user_id);

				$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
        echo json_encode($validation_data_array);
		} else {
			echo json_encode($validation_data_array);
		}
		return;
	}
	/**
	 * This method is used to store withdraw fund transaction related to direct bank transfer 
	*/
	public function ajax_withdraw_funds_via_direct_bank_transfer() {
		$row = $this->input->post();
		$row['withdraw_amount'] = str_replace(' ', '', $row['withdraw_amount']);
		$validation_data_array = $this->finances_model->direct_bank_transfer_funds_withdraw_validation($row);
		if($validation_data_array['status'] == 'SUCCESS') {
			include '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
			$user = $this->db->get_where('users', ['user_id' => $row['user']])->row();
			$row['withdraw_amount'] = str_replace(' ', '', $row['withdraw_amount']);
			$direct_bank_transfer_deposits = [
				'user_id' => $user->user_id,
				'user_profile_name' => $user->profile_name,
				'withdraw_amount' => $row['withdraw_amount'],
				'user_withdraw_request_date' => date('Y-m-d H:i:s'),
				'bank_transaction_date' => date('Y-m-d', strtotime($row['transaction_date'])),
				'bank_transaction_id' => $row['transaction_id'],
				'bank_account_owner_name' => $row['account_owner'],
				'bank_account_number' => $row['account_number'],
				'bank_name' => $row['bank_name'],
				'bank_code' => $row['bank_code'],
				'bank_account_iban_code' => !empty($row['iban']) ? $row['iban'] : null,
				'bank_account_bic_swift_code' => !empty($row['swift_code']) ? $row['swift_code'] : null,
				'country' => $row['country'],
				'status' => 'request_confirmed_by_admin',
				'admin_manual_entry' => 1
			];
			$this->db->insert('users_funds_direct_bank_transfer_withdraw_transactions', $direct_bank_transfer_deposits);

			$this->db->where('user_id', $user->user_id);
			$this->db->set('user_account_balance', 'user_account_balance - '.$row['withdraw_amount'], false);
			$this->db->update('users_details');

			$activity_log = $config['withdraw_funds_direct_bank_transfer_transaction_request_confirmed_by_admin_user_activity_log'];
			$activity_log = str_replace('{withdraw_amount}', format_money_amount_display($row['withdraw_amount']), $activity_log);
			user_display_log($activity_log, $user->user_id);

			$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
			echo json_encode($validation_data_array);
		} else {
			echo json_encode($validation_data_array);
		}
		return;
	}
	/**
	 * This method is used to load transaction detail related to deposits funds via payment processor
	*/
	public function deposit_via_payment_processor($param = '', $param1 = '') {
		$lay['lft'] = "inc/section_left";
    $data['data'] = $this->auto_model->leftPannel();
    // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
		if($this->input->post('search_element')) {
			$str = $this->input->post('search_element');
			$this->session->set_userdata('search_str', $str);
		} else {
			$this->session->unset_userdata('search_str');
			$str = '';
		}
		if($this->session->userdata('search_str')) {
			$str = $this->session->userdata('search_str');
		} 
    $deposit_funds = $this->finances_model->get_all_deposit_funds_via_payment_processor($rowno,$rowperpage, $str);
    $data['deposit_funds'] = $deposit_funds['data'];
    // Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url('finances/deposit_via_payment_processor/?');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $deposit_funds['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
    $this->layout->view('deposit_funds_via_payment_processor', $lay, $data);
	}
	/**
	 * This method is used to load transaction detail related to direct bank transfer withdraw funds 
	*/
	public function direct_bank_transfer_withdraws($param = '', $param1 = '') {

		if(!empty($param) && $param == 'reject') {
			include_once '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
			$result = $this->db->get_where('users_funds_direct_bank_transfer_withdraw_transactions', ['id' => $param1])->row_array();
			if(!empty($result)) {
				$this->db->update('users_funds_direct_bank_transfer_withdraw_transactions', ['status' => 'request_rejected_by_admin'], ['id' => $param1]);

				$this->db->where('user_id', $result['user_id']);
				$this->db->set('user_account_balance', 'user_account_balance + '.$result['withdraw_amount'], false);
				$this->db->update('users_details');

				$activity_log = $config['withdraw_funds_direct_bank_transfer_transaction_request_rejected_by_admin_user_activity_log'];
				$activity_log = str_replace('{withdraw_amount}', format_money_amount_display($result['withdraw_amount']), $activity_log);
				user_display_log($activity_log, $result['user_id']);

				$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
			} else {
				$this->session->set_flashdata('error_msg', 'Invalid Action.');
			}
			
			redirect(base_url('finances/direct_bank_transfer_withdraws/?per_page='.$this->input->get('per_page')));
		}

		if($this->input->is_ajax_request()) {
			include_once '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
			$row = $this->input->post();
			$i = 0;
			if(empty($row['transaction_date'])) {
				$msg['status'] = 'FAILED';
				$msg['error'][$i]['id'] = 'transaction_date_err';
				$msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_required_error_message'];
				$i++;
			} else if(!empty($row['transaction_date']) && !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $row['transaction_date'])) {
				$msg['status'] = 'FAILED';
				$msg['error'][$i]['id'] = 'transaction_date_err';
				$msg['error'][$i]['message'] = $config['deposit_funds_transaction_date_invalid_format_error_message'];
				$i++;
			} else if(!empty($row['transaction_date']) && preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.[0-9]{4}$/", $row['transaction_date'])) {
				$test_array = explode('.', $row['transaction_date']);
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
			if(empty($row['transaction_id'])) {
				$msg['status'] = 'FAILED';
				$msg['error'][$i]['id'] = 'transaction_id_err';
				$msg['error'][$i]['message'] = $config['deposit_funds_transaction_id_required_error_message'];
				$i++;
			}
			if($i != 0) {
				echo json_encode($msg);
				return;
			} else {
				$param = $this->input->get('id');
				$result = $this->db->get_where('users_funds_direct_bank_transfer_withdraw_transactions', ['id' => $param])->row_array();
				if(!empty($result)) {
					$data = [
						'bank_transaction_id' => $row['transaction_id'],
						'bank_transaction_date' => date('Y-m-d', strtotime($row['transaction_date'])),
						'status' => 'request_confirmed_by_admin'
					];
					$this->db->update('users_funds_direct_bank_transfer_withdraw_transactions', $data, ['id' => $param]);

					$activity_log = $config['withdraw_funds_direct_bank_transfer_transaction_request_confirmed_by_admin_user_activity_log'];
					$activity_log = str_replace('{withdraw_amount}', format_money_amount_display($result['withdraw_amount']), $activity_log);
					user_display_log($activity_log, $result['user_id']);

					$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
				} else {
					$this->session->set_flashdata('error_msg', 'Invalid Action.');
				}
				echo json_encode(['status' => 200]);
				return;
			}
		}

		include_once '../application/config/'.SITE_LANGUAGE.'_server_settings_custom_config.php';
		
		$lay['lft'] = "inc/section_left";
    $data['data'] = $this->auto_model->leftPannel();
    // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
    $withdraw_funds = $this->finances_model->get_all_direct_bank_transfer_withdraw_funds_transactions($rowno,$rowperpage);
    $data['withdraw_funds'] = $withdraw_funds['data'];
    // Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url('finances/direct_bank_transfer_withdraws/?');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $withdraw_funds['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
		$data['countries'] = $this->db->get('countries')->result_array();
		$data['users'] = $this->finances_model->get_all_users();
		$data['reference_country_id'] = $config['reference_country_id'];
    $this->layout->view('direct_bank_transfer_withdraws', $lay, $data);
	}
	/**
	 * This method is used to load transaction detail related to withdraw funds
	 */
	public function withdraw_funds() {
		$lay['lft'] = "inc/section_left";
    $data['data'] = $this->auto_model->leftPannel();
    // Row per page
		$rowperpage = PAGING_LIMIT;

		// Row position
		$rowno = 0;
		if($this->input->get('per_page')){
		  $rowno = ($this->input->get('per_page')-1) * $rowperpage;
		}
		$withdraw_funds = $this->finances_model->get_all_withdraw_funds($rowno,$rowperpage);
    $data['withdraw_funds'] = $withdraw_funds['data'];
    // Pagination Configuration
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url('finances/withdraw_funds/?');
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $withdraw_funds['total'];
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['row'] = $rowno;
    $this->layout->view('withdraw_funds', $lay, $data);
	}
	/**
	 * This method is used to manage withdraw request related action which performed by admin
	*/
	public function manage_withdraw_requests($param = '', $param1 = '') {
		include_once '../application/config/'.SITE_LANGUAGE.'_finances_custom_config.php';
		if($param == 'reject' || $param == 'delete') {
			$withdraw_detail = $this->db->get_where('users_funds_paypal_withdraw_transactions_history', ['id' => $param1])->row_array();
			if(!empty($withdraw_detail)) {
				if($param == 'reject') {
					$update_data = [
						'request_status' => 'rejected',
						'request_rejection_date' => date('Y-m-d H:i:s')
					];
					$this->db->update('users_funds_paypal_withdraw_transactions_history', $update_data, ['id' => $withdraw_detail['id']]);
					$this->db->set('user_account_balance', 'user_account_balance + ' . $withdraw_detail['withdrawal_requested_amount'], FALSE);
					$this->db->where('user_id', $withdraw_detail['user_id']);
					$this->db->update('user_details');

					$activity_msg = $config['withdraw_funds_request_rejected_by_admin_display_activity_log_message'];
					$activity_msg = str_replace('{transaction_amount}', format_money_amount_display($withdraw_detail['withdrawal_requested_amount']), $activity_msg);
					user_display_log($activity_msg, $withdraw_detail['user_id']);
				} else {
					$this->db->delete('users_funds_paypal_withdraw_transactions_history', ['id' => $withdraw_detail['id']]);
					$this->db->set('user_account_balance', 'user_account_balance + ' . $withdraw_detail['withdrawal_requested_amount'], FALSE);
					$this->db->where('user_id', $withdraw_detail['user_id']);
					$this->db->update('user_details');
				}
				$this->session->set_flashdata('succ_msg', 'Action performed successfully.');
			}
			
		} else {
			
			$withdraw_detail = $this->db->get_where('users_funds_paypal_withdraw_transactions_history', ['id' => $param1])->row_array();
			// Get access token from PayPal client Id and secrate key
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $config['sandbox'] ? "https://api.sandbox.paypal.com/v1/oauth2/token" : "https://api.paypal.com/v1/oauth2/token");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERPWD, $config['paypal_client_id'] . ":" . $config['paypal_secret_key']);

			$headers = array();
			$headers[] = "Accept: application/json";
			$headers[] = "Accept-Language: en_US";
			$headers[] = "Content-Type: application/x-www-form-urlencoded";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$results = curl_exec($ch);
			$getresult = json_decode($results);
			
			// PayPal Payout API for Send Payment from PayPal to PayPal account
			curl_setopt($ch, CURLOPT_URL, $config['sandbox'] ? "https://api.sandbox.paypal.com/v1/payments/payouts" : "https://api.paypal.com/v1/payments/payouts");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$array = array('sender_batch_header' => array(
							"sender_batch_id" => time(),
							"email_subject" => "You have a payout!",
							"email_message" => "You have received a payout."
					),
					'items' => array(array(
									"recipient_type" => "EMAIL",
									"amount" => array(
											"value" => $withdraw_detail['withdrawal_requested_amount'] - $withdraw_detail['paypal_charges'],
											"currency" => "CZK"
									),
									"note" => "Thanks for the payout!",
									"sender_item_id" => time(),
									"receiver" => $withdraw_detail['withdraw_to_paypal_account']
							))
			);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
			curl_setopt($ch, CURLOPT_POST, 1);

			$headers = array();
			$headers[] = "Content-Type: application/json";
			$headers[] = "Authorization: Bearer $getresult->access_token";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$payoutResult = curl_exec($ch);
			$getPayoutResult = json_decode($payoutResult, true);
			if (curl_errno($ch)) {
					// echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
			sleep(2);
			if(!empty($getPayoutResult)) {

				if(array_key_exists('name', $getPayoutResult)) {
					$update_data = [
						'failure_reason' => $getPayoutResult['message'],
						'transaction_failure_code' => $getPayoutResult['name']
					];
					$this->db->update('users_funds_paypal_withdraw_transactions_history', $update_data, ['id' => $withdraw_detail['id']]);
					redirect(base_url('finances/withdraw_funds/?per_page='.$this->input->get('per_page')));
					return;
				}

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $getPayoutResult['links'][0]['href']);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				sleep(3);
				$payoutResult = curl_exec($ch);
				$payout_details = json_decode($payoutResult, true);
				if (curl_errno($ch)) {
						// echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				if(!empty($payout_details)) {
						if(array_key_exists('errors', $payout_details['items'][0])) {
							$error_msg = '';
							if(array_key_exists($payout_details['items'][0]['errors']['name'], $config['withdraw_funds_failure_reason'])) {
								$error_msg = $config['withdraw_funds_failure_reason'][$payout_details['items'][0]['errors']['name']];
							} else {
								$error_msg = $payout_details['items'][0]['errors']['message'];
							}
							$update_data = [
								'request_status' => 'approved',
								'transaction_id' => $payout_details['items'][0]['transaction_id'],
								'transaction_status' => 'failed',
								'transaction_date' => date('Y-m-d H:i:s'),
								'failure_reason' => $error_msg,
								'transaction_failure_code' => $payout_details['items'][0]['errors']['name']
							];
							$this->db->update('users_funds_paypal_withdraw_transactions_history', $update_data, ['id' => $withdraw_detail['id']]);
							redirect(base_url('finances/withdraw_funds/?per_page='.$this->input->get('per_page')));
							return;
						}

						if(array_key_exists('transaction_status', $payout_details['items'][0]) && ($payout_details['items'][0]['transaction_status'] == 'SUCCESS' || $payout_details['items'][0]['transaction_status'] == 'PENDING')) {
							$update_data = [
								'request_status' => 'approved',
								'transaction_id' => $payout_details['items'][0]['transaction_id'],
								'transaction_status' => 'successful',
								'transaction_date' => date('Y-m-d H:i:s')
							];
							$this->db->update('users_funds_paypal_withdraw_transactions_history', $update_data, ['id' => $withdraw_detail['id']]);
						}
					}
			}
		}
		redirect(base_url('finances/withdraw_funds/?per_page='.$this->input->get('per_page')));
	}
}