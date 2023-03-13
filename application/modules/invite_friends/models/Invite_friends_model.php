<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Invite_friends_model extends BaseModel
{

	public function __construct ()
	{
			return parent::__construct ();
	}

	public function invited_friends_emails ($user_id)
	{
			$result = $this->db->select ('*')
			->from ('invited_friends_invitations_pending_acceptance')
			->where ('referrer_id', $user_id)
			->order_by ('invitee_email_address', 'ASC')
			->get ()->result_array ();
			return $result;
	}
	
	/*
	 * Get Pending invitation by id
	 * @sid
	*/
	public function get_pending_invitation($id) {
		$this->db->where('id', $id);
		return $this->db->get('invited_friends_invitations_pending_acceptance')->row_array();
	}
	/*
	 * Remove Pending invitation by id
	 * @sid
	*/
	public function remove_pending_friend_invitation_by_id($id) {
		$this->db->where('id', $id);
		$this->db->delete('invited_friends_invitations_pending_acceptance');
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	// is used to insert data into pending invitation table
	function insert_pending_invite_friends_data ($data)
	{
		if($this->db->insert ('invited_friends_invitations_pending_acceptance', $data)) {
			return $this->db->insert_id();
		}
		return 0;
	}
	
	/*
	 * Update pending invitation details
	 * @sid
	*/
	public function update_pending_friend_invitation_details($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('invited_friends_invitations_pending_acceptance', $data);
		if($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	/*
	 * Insert data into invited friends revoke table
	 * created by @sid
	*/
	public function insert_revoked_invited_friends_data($data) {
		if($this->db->insert('invited_friends_revoked_invitations', $data)) {
			return $this->db->insert_id();
		}
		return 0;
	}

	/*
	 * use to get users table data need for invite friends [use in invitefriends controller invitfrinends method]
	 * @sid
	*/
	public function getUserbyIdForInviteFriendsFeature ($id) {
		$this->db->select ();
		$row = $this->db->get_where ('users', array ('user_id' => $id))->row();
		$data = array ();
		$data[] = array (
						'user_id' => $row->user_id,
						'first_name' => $row->first_name,
						'last_name' => $row->last_name,
						'type_account' => $row->account_type,
						'company_name' => $row->company_name,
						'gender' => $row->gender,
						'email' => $row->email,
						'profile_name' => $row->profile_name,
						'is_authorized_physical_person' => $row->is_authorized_physical_person
					);
		return $data;
  }
	/*
	 * Get lvl1 all time registration information -> invite_friends/invite_friend_view
	 * @sid
	*/
	public function get_lvl1_all_time_registrations_from_referrer_id($id) {
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl1_referrer_id', $id);
		return $this->db->count_all_results();
	}
	/**
	 * Get lvl1 all time earnings information 
	*/
	public function get_lvl1_all_time_earnings_from_referrer_id($id) {
		$result = $this->db->get_where('users_referrals_lifetime_total_earnings_tracking', ['user_id' => $id])->row_array();
		if(!empty($result)) {
			if($result['aggregated_referral_earnings_value_lvl1'] == 0) {
				return 0;
			}
			return $result['aggregated_referral_earnings_value_lvl1'];
		}
		return 0;
	}
	/*
	 * Get lvl2 all time registration information -> invite_friends/invite_friend_view
	 * @sid
	*/
	public function get_lvl2_all_time_registrations_from_referrer_id($id, $user_detail) {
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl2_referrer_id', $id);

		if($user_detail['current_membership_plan_id'] == 4) {
			$this->db->where('user_account_validation_date >=', $user_detail['current_membership_start_date']);
		}

		return $this->db->count_all_results();
	}
	/**
	 * Get lvl2 all time earnings information 
	*/
	public function get_lvl2_all_time_earnings_from_referrer_id($id) {
		$result = $this->db->get_where('users_referrals_lifetime_total_earnings_tracking', ['user_id' => $id])->row_array();
		if(!empty($result)) {
			if($result['aggregated_referral_earnings_value_lvl2'] == 0) {
				return 0;
			}
			return $result['aggregated_referral_earnings_value_lvl2'];
		}
		return 0;
	}
	/*
	 * Get lvl1 registrations count based on specifier and user id -> invite_friends/invite_friend_view
	 * @sid
	*/
	public function get_lvl1_registrations_info_based_on_specifier_from_referrer_id($id, $specifier) {
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl1_referrer_id', $id);

		if($specifier == 'DAY') {
			$this->db->where('YEAR(user_account_validation_date) = YEAR(NOW()) AND MONTH(user_account_validation_date) = MONTH(NOW()) AND DAY(user_account_validation_date) = DAY(NOW())');
		} else if($specifier == 'WEEK') {
			
			$this->db->where('WEEKOFYEAR(user_account_validation_date) = WEEKOFYEAR(NOW())');
		} else {
			$this->db->where('YEAR(user_account_validation_date) = YEAR(NOW()) AND MONTH(user_account_validation_date) = MONTH(NOW())');
		}

		return $this->db->count_all_results();
	}
	/*
	 * Get lvl1 earnings based on specifier and user id -> invite_friends/invite_friend_view
	 * @sid
	*/
	public function get_lvl1_earnings_info_based_on_specifier_from_referrer_id($id, $specifier) {
		if($specifier == 'DAY') { 
			$this->db->from('users_referrals_daily_earnings_history_tracking');
			$this->db->where('YEAR(referral_earnings_date) = YEAR(NOW()) AND MONTH(referral_earnings_date) = MONTH(NOW()) AND DAY(referral_earnings_date) = DAY(NOW())');
		} else if($specifier == 'WEEK') { 
			$ts = strtotime(date('Y-m-d'));
			$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
			$start_date = date('Y-m-d', $start);

			$this->db->from('users_referrals_weekly_earnings_history_tracking');
			$this->db->where('referral_earnings_week_start_date', $start_date);
		} else {
			$this->db->from('users_referrals_monthly_earnings_history_tracking');
			$this->db->where('YEAR(referral_earnings_month) = YEAR(NOW()) AND MONTH(referral_earnings_month) = MONTH(NOW())');
		}
		$this->db->where('user_id', $id);

		$result = $this->db->get()->row_array();

		if(!empty($result)) {
			if($result['aggregated_referral_earnings_value_lvl1'] == 0) {
				return 0;
			}
			return $result['aggregated_referral_earnings_value_lvl1'];
		} 
		return 0;
	}
	/*
	 * Get lvl2 registrations count based on specifier and user id -> invite_friends/invite_friend_view
	 * @sid
	*/
	public function get_lvl2_registrations_info_based_on_specifier_from_referrer_id($id, $specifier, $user_detail) {
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl2_referrer_id', $id);
		if($specifier == 'DAY') {
			$this->db->where('YEAR(user_account_validation_date) = YEAR(NOW()) AND MONTH(user_account_validation_date) = MONTH(NOW()) AND DAY(user_account_validation_date) = DAY(NOW())');
		} else if($specifier == 'WEEK') {
			$this->db->where('WEEKOFYEAR(user_account_validation_date) = WEEKOFYEAR(NOW())');
		} else {
			$this->db->where('YEAR(user_account_validation_date) = YEAR(NOW()) AND MONTH(user_account_validation_date) = MONTH(NOW())');
		}
		if($user_detail['current_membership_plan_id'] == 4) {
			$this->db->where('user_account_validation_date >=', $user_detail['current_membership_start_date']);
		}
		return $this->db->count_all_results();
	}
	/*
	 * Get lvl1 earnings based on specifier and user id -> invite_friends/invite_friend_view
	 * @sid
	*/
	public function get_lvl2_earnings_info_based_on_specifier_from_referrer_id($id, $specifier) {
		if($specifier == 'DAY') { 
			$this->db->from('users_referrals_daily_earnings_history_tracking');
			$this->db->where('YEAR(referral_earnings_date) = YEAR(NOW()) AND MONTH(referral_earnings_date) = MONTH(NOW()) AND DAY(referral_earnings_date) = DAY(NOW())');
		} else if($specifier == 'WEEK') { 
			$this->db->from('users_referrals_weekly_earnings_history_tracking');
			$this->db->where('WEEKOFYEAR(referral_earnings_week_start_date) = WEEKOFYEAR(NOW())');
		} else {
			$this->db->from('users_referrals_monthly_earnings_history_tracking');
			$this->db->where('YEAR(referral_earnings_month) = YEAR(NOW()) AND MONTH(referral_earnings_month) = MONTH(NOW())');
		}
		$this->db->where('user_id', $id);

		$result = $this->db->get()->row_array();

		if(!empty($result)) {
			if($result['aggregated_referral_earnings_value_lvl2'] == 0) {
				return 0;
			}
			return $result['aggregated_referral_earnings_value_lvl2'];
		} 
		return 0;
	}
	/**
	 * Get lvl1 registrations by month from referrer id
	 * @sid
	*/
	public function get_lvl1_registrations_by_month_from_referrer_id($id) {
		$this->db->select('*, count(id) as cnt');
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl1_referrer_id', $id);
		$this->db->where('user_account_validation_date >= DATE(NOW()) - INTERVAL 12 MONTH');
		$this->db->group_by('YEAR(user_account_validation_date),MONTH(user_account_validation_date)');
		return $this->db->get()->result_array();
	}
	/**
	 * Get lvl2 registrations by month from referrer id
	 * @sid
	*/
	public function get_lvl2_registrations_by_month_from_referrer_id($id, $user_detail) {
		$this->db->select('*, count(id) as cnt');
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl2_referrer_id', $id);
		$this->db->where('user_account_validation_date >= DATE(NOW()) - INTERVAL 12 MONTH');
		if($user_detail['current_membership_plan_id'] == 4) {
			$this->db->where('user_account_validation_date >=', $user_detail['current_membership_start_date']);
		}
		$this->db->group_by('YEAR(user_account_validation_date),MONTH(user_account_validation_date)');
		return $this->db->get()->result_array();
	}

	/**
	 * Get referral earnings by month from referrer id 
	*/
	public function get_lvl1_lvl2_earnings_by_month_from_referrer_id($id) {
		$this->db->select('*');
		$this->db->from('users_referrals_monthly_earnings_history_tracking');
		$this->db->where('referral_earnings_month >= DATE(NOW()) - INTERVAL 12 MONTH');
		$this->db->where('user_id', $id);
		return $this->db->get()->result_array();
	}

	/**
	 * Get lvl1 registrations by day form referrer id
	 * @sid 
	*/
	public function get_lvl1_registrations_last_thirty_days_from_referrer_id($id) {
		$this->db->select('*, count(id) as cnt');
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl1_referrer_id', $id);
		$this->db->where('user_account_validation_date >= DATE(NOW()) - INTERVAL 30 DAY');
		$this->db->group_by('DAY(user_account_validation_date)');
		return $this->db->get()->result_array();
	}
	/**
	 * Get lvl2 registrations by day form referrer id
	 * @sid 
	*/
	public function get_lvl2_registrations_last_thirty_days_from_referrer_id($id, $user_detail) {
		$this->db->select('*, count(id) as cnt');
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl2_referrer_id', $id);
		$this->db->where('user_account_validation_date >= DATE(NOW()) - INTERVAL 30 DAY');
		if($user_detail['current_membership_plan_id'] == 4) {
			$this->db->where('user_account_validation_date >=', $user_detail['current_membership_start_date']);
		}
		$this->db->group_by('DAY(user_account_validation_date)');
		return $this->db->get()->result_array();
	}

	/**
	 * Get referral earnings by last thirty days from referrer id 
	*/
	public function get_lvl1_lvl2_earnings_last_thirty_days_from_referrer_id($id) {
		$this->db->select('*');
		$this->db->from('users_referrals_daily_earnings_history_tracking');
		$this->db->where('referral_earnings_date >= DATE(NOW()) - INTERVAL 30 DAY');
		$this->db->where('user_id', $id);
		return $this->db->get()->result_array();
	}
	/**
	 * Get lvl1 registrations by week form referrer id, it return last 52 week data
	 * @sid 
	*/
	public function get_lvl1_registrations_by_week_from_referrer_id($id) {
		$this->db->select('*, count(id) as cnt');
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl1_referrer_id', $id);
		$this->db->where('user_account_validation_date >= DATE(NOW()) - INTERVAL 52 WEEK');
		$this->db->group_by('WEEK(user_account_validation_date)');
		return $this->db->get()->result_array();
	}
	/**
	 * Get lvl2 registrations by week from referrer id, it return last 52 week data
	 * @sid 
	*/
	public function get_lvl2_registrations_by_week_from_referrer_id($id, $user_detail) {
		$this->db->select('*, count(id) as cnt');
		$this->db->from('users_referrals_tracking');
		$this->db->where('lvl2_referrer_id', $id);
		$this->db->where('user_account_validation_date >= DATE(NOW()) - INTERVAL 52 WEEK');
		if($user_detail['current_membership_plan_id'] == 4) {
			$this->db->where('user_account_validation_date >=', $user_detail['current_membership_start_date']);
		}
		$this->db->group_by('WEEK(user_account_validation_date)');
		return $this->db->get()->result_array();
	}
	/**
	 * Get lvl1 and lvl2 earnings by week from referrer id, it return last 52 week data 
	*/
	public function get_lvl1_lvl2_earnings_by_week_from_referrer_id($id) {
		$this->db->select('*');
		$this->db->from('users_referrals_weekly_earnings_history_tracking');
		$this->db->where('referral_earnings_week_start_date >= DATE(NOW()) - INTERVAL 52 WEEK');
		$this->db->where('user_id', $id);
		return $this->db->get()->result_array();
	}
	/*
	 * Get data for chart statistics by referrer id
	 * @sid
	*/
	public function get_data_for_chart_by_referrer_id($id) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('referrer_id', $id);
		return $this->db->get()->result_array();
	}
	// Get all pending invitations by referrer id
	public function get_all_pending_invitations_by_referrer_id($id, $start, $limit) {
		$this->db->select('SQL_CALC_FOUND_ROWS *', false);
		$this->db->from('invited_friends_invitations_pending_acceptance');
		$this->db->where('referrer_id', $id);
		$this->db->order_by('id', 'DESC');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$pending_invitations = $this->db->get()->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $pending_invitations, 'total' => $total_rec];
	}
	// Get all revoked invitations by referrer id
	public function get_all_revoked_invitations_by_referrer_id($id, $start, $limit) {
		$this->db->select('SQL_CALC_FOUND_ROWS *', false);
		$this->db->from('invited_friends_revoked_invitations');
		$this->db->where('referrer_id', $id);
		$this->db->order_by('id', 'DESC');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$revoked_invitations = $this->db->get()->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $revoked_invitations, 'total' => $total_rec];
	}
	// Get all revoked invitations by referrer id
	public function get_all_accepted_invitations_by_referrer_id($id, $start, $limit) {
		$this->db->select('SQL_CALC_FOUND_ROWS *', false);
		$this->db->from('invited_friends_registered');
		$this->db->where('referrer_id', $id);
		$this->db->order_by('id', 'DESC');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$this->db->order_by('id', 'desc');
		$accepted_invitations = $this->db->get()->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $accepted_invitations, 'total' => $total_rec];
	}
	/**
	 * This method is used to get sum of all time earnings from lvl1 and lvl2 
	*/
	public function get_sum_of_lvl1_lvl2_all_time_referral_earnings_from_referrer_id($id) {
		$this->db->select('(SUM(aggregated_referral_earnings_value_lvl1) + SUM(aggregated_referral_earnings_value_lvl2)) as total');
		$this->db->from('users_referrals_lifetime_total_earnings_tracking');
		$this->db->where('user_id', $id);
		$result = $this->db->get()->row_array();
		if(!empty($result) && !empty($result['total'])) {			
			return $result['total'];
		}
		return 0;
	}
	/**
	 * This method is used to get sum of referral earnings withdraw amount
	*/
	public function get_sum_of_all_withdrawn_referral_earnings_amount_from_user_id($id) {
		$this->db->select('SUM(referral_earnings_withdrawal_requested_amount) as total');
		$this->db->from('users_referrals_earnings_withdraw_transactions_history');
		$this->db->where('user_id', $id);
		$this->db->where('referral_earnings_withdrawal_request_status != ', 'rejected');
		$result = $this->db->get()->row_array();
		if(!empty($result) && !empty($result['total'])) {
			return $result['total'];
		}
		return 0;
	}
	/**
	 * This method is used to display all referral earnings withdrawal transaction from user id
	 */
	public function get_all_referral_earnings_withdrawal_transactions_from_user_id($id, $limit) {
		$this->db->select('*');
		$this->db->from('users_referrals_earnings_withdraw_transactions_history');
		$this->db->where('user_id', $id);
		$this->db->order_by('id', 'desc');
		$this->db->limit($limit);
		$result = $this->db->get()->result_array();
		foreach($result as &$val) {
			$val['referral_earnings_withdrawal_requested_amount'] = format_money_amount_display($val['referral_earnings_withdrawal_requested_amount']);
		}
		return $result;
	}
}
?>