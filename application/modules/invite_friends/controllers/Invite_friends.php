<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Invite_friends extends MX_Controller
{

	public $user = null;

	public function __construct ()
	{
			parent::__construct ();
			$this->load->model ('Invite_friends_model');
			
	}	
	/*
	 * Revoke invitation which was sent to the user
	 * Created by @sid
	*/
	public function revoke_invitation() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}

		$check_accepted_invitaiton = $this->db->get_where('invited_friends_registered', ['invitation_id' => $row['invite_id']])->row_array();
		if(!empty($check_accepted_invitaiton)) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_revoke_invitation_already_accepted_error_message')]);
			die;
		}
		
		$result = $this->Invite_friends_model->get_pending_invitation($row['id']);
		$res['status'] = 200;
		if(!empty($result)) {
			$revoke_data = [
				'invitation_id' => $result['invitation_id'],
				'referrer_id' => $result['referrer_id'],
				'invitee_email_address' => $result['invitee_email_address'],
				'initial_invitation_sent_date' => $result['initial_invitation_sent_date'],
				'invitation_revoke_date' => date('Y-m-d H:i:s'),
				'no_of_resents_done' => $result['resends_available']
			];
			$this->Invite_friends_model->insert_revoked_invited_friends_data($revoke_data);
			$this->Invite_friends_model->remove_pending_friend_invitation_by_id($row['id']);

			$activity_log = $this->config->item('revoked_invitation_user_activity_log');
			$activity_log = str_replace('{invitee_email_address}', $result['invitee_email_address'], $activity_log);
			user_display_log($activity_log);
			$res['msg'] = $this->config->item('invite_friends_revoked_invitation_confirmation_message');
		} 
		
		$total_record = $this->db->from('invited_friends_invitations_pending_acceptance')->where('referrer_id' , $user[0]->user_id)->count_all_results();
		$pagination = generate_pagination_links($total_record, $this->config->item('invite_friends_page_url'), $this->config->item('pending_invitations_listing_limit'), $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'pending']);
		$pending_invitations = $this->Invite_friends_model->get_all_pending_invitations_by_referrer_id($user[0]->user_id, $pagination['offset'], $this->config->item('pending_invitations_listing_limit'));
		
		$page = $pagination['current_page_no'];
		$data['invitations'] = $pending_invitations['data'];
		$data['pagination_links'] = $pagination['links'];
		$data['section_name'] = 'pending';
		$data['invitations_count'] = $pending_invitations['total'];
		$data['limit'] = $this->config->item('pending_invitations_listing_limit');
		$multiplication = $data['limit'] * $page;
		$subtraction = ($multiplication - ($data['limit'] - count($pending_invitations['data'])));
		$record_per_page = count($pending_invitations['data']) < $data['limit'] ? $subtraction : $multiplication;
		$page_no = ($data['limit'] * ($page - 1)) + 1;
		$data['rec_per_page'] = $record_per_page;
		$data['page_no'] = $page_no;
		$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
		
		echo json_encode($res);
		return;
	}
	
	/*
	 * Resend invitation to selected user
	 * Created by @sid
	*/
	public function resend_invitation() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}

		$check_accepted_invitaiton = $this->db->get_where('invited_friends_registered', ['invitation_id' => $row['invite_id']])->row_array();
		if(!empty($check_accepted_invitaiton)) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_resend_invitation_already_accepted_error_message')]);
			die;
		}

		$check_revoked_invitation = $this->db->get_where('invited_friends_revoked_invitations', ['invitation_id' => $row['invite_id']])->row_array();
		if(!empty($check_revoked_invitation)) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_resend_invitation_already_revoked_error_message')]);
			die;
		}

		$invitation_detail = $this->Invite_friends_model->get_pending_invitation($row['id']);

		if(strtotime($invitation_detail['next_resent_available_date']) >= strtotime(date('Y-m-d H:i:s')) && $invitation_detail['resends_available'] > 0) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_resend_invitation_before_next_available_time_being_available_error_message')]);
			die;
		} else if(strtotime($invitation_detail['next_resent_available_date']) >= strtotime(date('Y-m-d H:i:s')) && $invitation_detail['resends_available'] == 0) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_available_resends_depleted_error_message')]);
			die;
		}

		$user = (array)$user[0];
		// fetch userinfo to get firstname and last name
		$userdata = $this->Invite_friends_model->getUserbyIdForInviteFriendsFeature($user['user_id']);
		$userdata = $userdata[0];
		$this->load->library('email');
		$config['protocol'] = PROTOCOL;
		$config['smtp_host']    = SMTP_HOST;
		$config['smtp_port']    = SMTP_PORT;
		$config['smtp_timeout'] = SMTP_TIMEOUT;
		$config['smtp_user']    = SMTP_USER;
		$config['smtp_pass']    = SMTP_PASS;
		$config['charset'] = CHARSET;
		$config['mailtype'] = MAILTYPE;
		$config['newline'] = NEWLINE;	
		
		$email_from_name = $this->config->item('email_from_name_reminder_invite_friend');
		$profile_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$userdata['profile_name'];

		if($userdata['type_account'] == 1 || ($userdata['type_account'] == 2 && $userdata['is_authorized_physical_person'] == 'Y') ) {
			if($userdata['gender'] == 'M') {
				$subject = $this->config->item('email_subject_reminder_invite_friend_male_sender');
				$msg = $this->config->item('email_message_reminder_invite_friend_male_sender');

				if($userdata['is_authorized_physical_person'] == 'Y') {
					$subject = $this->config->item('email_subject_reminder_invite_friend_company_app_male_sender');
					$msg = $this->config->item('email_message_reminder_invite_friend_company_app_male_sender');
				}

			} else {
				$subject = $this->config->item('email_subject_reminder_invite_friend_female_sender');
				$msg = $this->config->item('email_message_reminder_invite_friend_female_sender');

				if($userdata['is_authorized_physical_person'] == 'Y') {
					$subject = $this->config->item('email_subject_reminder_invite_friend_company_app_female_sender');
					$msg = $this->config->item('email_message_reminder_invite_friend_company_app_female_sender');
				}
			}
			$msg = str_replace('{user_first_name_last_name_or_company_name}', $userdata['first_name'].' '.$userdata['last_name'], $msg);
			$msg = str_replace('{profile_page_url}', $profile_url, $msg);
			$email_from_name = str_replace('{user_first_name_last_name_or_company_name}', $userdata['first_name'].' '.$userdata['last_name'], $email_from_name);
			
		} else {
			$subject = $this->config->item('email_subject_reminder_invite_friend_company_sender');
			$msg = $this->config->item('email_message_reminder_invite_friend_company_sender');
			$msg = str_replace('{user_first_name_last_name_or_company_name}', $userdata['company_name'], $msg);
			$msg = str_replace('{profile_page_url}', $profile_url, $msg);
			$email_from_name = str_replace('{user_first_name_last_name_or_company_name}', $userdata['company_name'], $email_from_name);
			
		} 
		$reffer_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$this->config->item('referrer_page_url').'?rfrd=';
				
		$this->email->initialize($config);
		//$email_from_name = '=?utf-8?B?'.base64_encode($email_from_name).'?=';
		
		$email_from_name = $this->getEncodedSubject($email_from_name);

		$this->email->from($this->config->item('email_from_reminder_invite_friend'), $email_from_name);
		$this->email->reply_to($this->config->item('email_reply_to_reminder_invite_friend'));
		if($this->config->item('email_cc_reminder_invite_friend')) {
			$this->email->cc($this->config->item('email_cc_reminder_invite_friend'));
		}
		if($this->config->item('email_bcc_reminder_invite_friend')) {
			$this->email->bcc($this->config->item('email_bcc_reminder_invite_friend'));
		}
		$this->email->subject($subject);
		$this->email->to($invitation_detail['invitee_email_address']);
		$reffer_url .= base64_encode(json_encode(['code' => $user['referral_code'], 'source' => 'user_self_url_share_email', 'invite_frnd_id' => 	$invitation_detail['id']]));
		$msg = str_replace('{referral_url}', $reffer_url, $msg);
		$this->email->message($msg);
		$res['status'] = 200;
		if($this->email->send()){
			$time_arr = explode(':', $this->config->item('time_left_till_next_resent'));
			$update_data = [
				'invitation_resent_date' => date('Y-m-d H:i:s'),
				'next_resent_available_date' => !empty($time_arr) ? date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds', strtotime(date('Y-m-d H:i:s')))): date('Y-m-d H:i:s'),
				'resends_available' => --$invitation_detail['resends_available']
			];
			$this->Invite_friends_model->update_pending_friend_invitation_details($row['id'], $update_data);

			$activity_log = $this->config->item('reminder_email_invitation_sent_activity_log_message');
			$activity_log = str_replace('{invitee_email_address}',$invitation_detail['invitee_email_address'], $activity_log);
			user_display_log($activity_log);
			$res['msg'] = $this->config->item('resend_invitation_confirmation_message');
			$res['resend_available'] = $update_data['resends_available'];

			$dt1 = new DateTime($update_data['next_resent_available_date']);
			$dt2 = new DateTime();
			$interval = dateDifference(date('Y-m-d H:i:s', strtotime($update_data['next_resent_available_date'])), date('Y-m-d H:i:s'));
			// $interval = $dt1->diff($dt2);
			if($dt2 > $dt1) {
				$res['time_left'] = '';
			} else {
				$res['time_left'] = $interval;
			}
		}
		
		echo json_encode($res);
		return;
	}
	// send invitation to friends via email
	public function send_invite_friend_request_by_email() {
		if(!$this->input->is_ajax_request ()){ 
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata ('user');
		$row = $this->input->post();
		
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}
		$user = (array)$user[0];
		// fetch userinfo to get firstname and last name
		$userdata = $this->Invite_friends_model->getUserbyIdForInviteFriendsFeature($user['user_id']);
		$userdata = $userdata[0];
		$input = json_decode($this->input->post('emails'));
		$this->load->library('email');
		$config['protocol'] = PROTOCOL;
		$config['smtp_host']    = SMTP_HOST;
		$config['smtp_port']    = SMTP_PORT;
		$config['smtp_timeout'] = SMTP_TIMEOUT;
		$config['smtp_user']    = SMTP_USER;
		$config['smtp_pass']    = SMTP_PASS;
		$config['charset'] = CHARSET;
		$config['mailtype'] = MAILTYPE;
		$config['newline'] = NEWLINE;	
		
		$reffer_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$this->config->item('referrer_page_url').'?rfrd=';
		
		$email_from_name = $this->config->item('email_from_name_invite_friend');
		$profile_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$userdata['profile_name'];
		if($userdata['type_account'] == 1 || ($userdata['type_account'] == 2 && $userdata['is_authorized_physical_person'] == 'Y')) {
			if($userdata['gender'] == 'M') {
				if($userdata['is_authorized_physical_person'] == 'Y') {
					$subject = $this->config->item('email_subject_invite_friend_company_app_male_sender');
					$msg = $this->config->item('email_message_invite_friend_company_app_male_sender');
				} else {
					$subject = $this->config->item('email_subject_invite_friend_male_sender');
					$msg = $this->config->item('email_message_invite_friend_male_sender');
				}
				
			} else {
				if($userdata['is_authorized_physical_person'] == 'Y') { 
					$subject = $this->config->item('email_subject_invite_friend_company_app_female_sender');
					$msg = $this->config->item('email_message_invite_friend_company_app_female_sender');
				} else {
					$subject = $this->config->item('email_subject_invite_friend_female_sender');
					$msg = $this->config->item('email_message_invite_friend_female_sender');
				}
				
			}
			$msg = str_replace('{user_first_name_last_name_or_company_name}', $userdata['first_name'].' '.$userdata['last_name'], $msg);
			$msg = str_replace('{profile_page_url}', $profile_url, $msg);
			$email_from_name = str_replace('{user_first_name_last_name_or_company_name}', $userdata['first_name'].' '.$userdata['last_name'], $email_from_name);
		} else {
			$subject = $this->config->item('email_subject_invite_friend_company_sender');
			$msg = $this->config->item('email_message_invite_friend_company_sender');
			$msg = str_replace('{user_first_name_last_name_or_company_name}', $userdata['company_name'], $msg);
			$msg = str_replace('{profile_page_url}', $profile_url, $msg);
			$email_from_name = str_replace('{user_first_name_last_name_or_company_name}', $userdata['company_name'], $email_from_name);
		} 
		
		$time_arr = explode(':', $this->config->item('time_left_till_next_resent'));
		// Invite friends data
		$invite_friend_data = [
			'invitation_id' => time(),
			'referrer_id' => $user['user_id'],
			'resends_available' => $this->config->item('resends_available')
		];
		$already_invited = $this->Invite_friends_model->invited_friends_emails($user['user_id']);
		$already_invited_email = array_column($already_invited, 'invitee_email_address');
		$already_invited_email = array_map('strtolower', $already_invited_email);

		$already_registered_users = $this->db->from('users')->where_in('email', $input)->get()->result_array();
		$already_registered_users = array_column($already_registered_users, 'email');
		$already_registered_users = array_map('strtolower', $already_registered_users);

		$accepted_invitation_users = $this->db->get_where('invited_friends_registered', ['referrer_id' => $user['user_id']])->result_array();
		$accepted_email = array_column($accepted_invitation_users, 'invitee_email_address');
		$accepted_email = array_map('strtolower', $accepted_email);

		$revoked_invitations = $this->db->get_where('invited_friends_revoked_invitations', ['referrer_id' => $user['user_id']])->result_array();
		$revoked_email = array_column($revoked_invitations, 'invitee_email_address');
		$revoked_email = array_map('strtolower', $revoked_email);
		
		$success = [];
		$error = [];
		$already_sent = [];
		$already_registered = [];
		$logged_in_email = [];
		$activity_log = '';
		$this->email->clear();
		$this->email->initialize($config);
		foreach($input as $em){
			if(in_array(strtolower($em), $already_invited_email) || in_array(strtolower($em), $revoked_email)) {
				$already_sent[] = $em;
				continue;
			} else if(strtolower($em) == strtolower($user['email'])) {
				$logged_in_email[] = $em;
				continue;
			} else if(in_array(strtolower($em), $already_registered_users) || in_array(strtolower($em), $accepted_email)) {
				$already_registered[] = $em;
				continue;
			}
			
			if($this->config->item('email_from_invite_friend')) {
				//$email_from_name = '=?utf-8?B?'.base64_encode($email_from_name).'?=';
				$email_from_name = $this->getEncodedSubject($email_from_name);
				$this->email->from($this->config->item('email_from_invite_friend'), $email_from_name);
			}
			if($this->config->item('email_reply_to_invite_friend')) {
				$this->email->reply_to($this->config->item('email_reply_to_invite_friend'));
			}
			$this->email->subject($subject);
			if($this->config->item('email_cc_invite_friend')) {
				$this->email->cc($this->config->item('email_cc_invite_friend'));
			}
			if($this->config->item('email_bcc_invite_friend')) {
				$this->email->bcc($this->config->item('email_bcc_invite_friend'));
			}
			$this->email->to($em);
			$invite_friend_data['initial_invitation_sent_date'] = date('Y-m-d H:i:s');
			$invite_friend_data['next_resent_available_date'] = !empty($time_arr) ? date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds', strtotime(date('Y-m-d H:i:s')))): date('Y-m-d H:i:s');
			$invite_friend_data['invitee_email_address'] = $em;
			$invite_friend_data['referrer_code'] = $user['referral_code'];
			$invite_frnd_id = $this->Invite_friends_model->insert_pending_invite_friends_data($invite_friend_data);
			$reffer_url .= base64_encode(json_encode(['code' => $user['referral_code'], 'source' => 'user_self_url_share_email', 'invite_frnd_id' => $invite_frnd_id]));
			$msg = str_replace('{referral_url}', $reffer_url, $msg);
			$this->email->message($msg);
			if($this->email->send()){
				$success[] = $em;
			}else{
				$error[] = $em;
			}
		}

		if(!empty($success)) {
			$msg = $this->config->item('total_successfully_sent_invitations_user_activity_log_message');
			$msg = str_replace('{total}', count($success), $msg);
			$activity_log .= $msg;
		} 
		if(!empty($already_sent)) {
			$msg = $this->config->item('already_sent_invitation_warning_user_activity_log_message');
			$msg = str_replace('{already_sent}', count($already_sent), $msg);
			$activity_log .= $msg;
		} 
		if(!empty($error)) {
			$msg = $this->config->item('invalid_email_user_activity_log_message');
			$msg = str_replace('{invalid_email}', count($error), $msg);
			$activity_log .= $msg;
		} 
		if(!empty($already_registered)) {
			if(count($already_registered) == 1) {
				$msg = $this->config->item('sent_invitation_to_invite_friends_email_already_registered_user_activity_log_message_1_failed_send');
			} else if(count($already_registered) <= 4) {
				$msg = $this->config->item('sent_invitation_to_invite_friends_email_already_registered_user_activity_log_message_2_to_4_failed_sends');
			} else {
				$msg = $this->config->item('sent_invitation_to_invite_friends_email_already_registered_user_activity_log_message_more_or_equal_5_failed_sends');
			}
			$msg = str_replace('{already_exist}', count($already_registered), $msg);
			$activity_log .= $msg;
		} 
		if(!empty($logged_in_email)) {
			$msg = $this->config->item('sent_yourself_invitation_user_activity_log_message');
			$msg = str_replace('{your_self}', count($logged_in_email), $msg);
			$activity_log .= $msg;
		} 
		user_display_log($activity_log);
		$notfication_msg = $this->config->item('send_invite_friends_request_sent_confirmation_msg');

		#################################### pending invitation listing ############################################################################
		$limit = $this->config->item('pending_invitations_listing_limit');
		$pending_invitations = $this->Invite_friends_model->get_all_pending_invitations_by_referrer_id($user['user_id'], 0, $limit);
		$data['invitations'] = $pending_invitations['data'];
		$data['invitations_count'] = $pending_invitations['total'];
		$pagination = generate_pagination_links($pending_invitations['total'], $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'pending']);
		$data['pagination_links'] = $pagination['links'];
		$data['limit'] = $limit;
		$data['page_no'] = 1;
		$data['rec_per_page'] = ($data['invitations_count'] > $data['limit']) ? $data['limit'] : $data['invitations_count'];
		$data['section_name'] = 'pending';
		$data = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
		#############################################################################################################################################

		echo json_encode(['status' => 200, 'msg' => $notfication_msg, 'data' => $data]);
		return;
	}
	// Manage pagination of different listing of manage email invitations section [pending/accepted/revoked] ->  assets/js/modules/invite_friends.js
	public function manage_paginations() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}
		$page = $this->uri->segment(3);
		$section_name = $this->input->post('section_name');
		$res['status'] = 200;
		if($section_name == 'pending') {
			$limit = $this->config->item('pending_invitations_listing_limit');
			$total_record = $this->db->from('invited_friends_invitations_pending_acceptance')->where('referrer_id' , $user[0]->user_id)->count_all_results();
			$pagination = generate_pagination_links($total_record, $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'pending']);
			$pending_invitations = $this->Invite_friends_model->get_all_pending_invitations_by_referrer_id($user[0]->user_id, $pagination['offset'], $limit);
			
			$page = $pagination['current_page_no'];
			$data['invitations'] = $pending_invitations['data'];
			$data['pagination_links'] = $pagination['links'];
			$data['section_name'] = $section_name;
			$data['invitations_count'] = $pending_invitations['total'];
			$data['limit'] = $limit;
			$multiplication = $data['limit'] * $page;
			$subtraction = ($multiplication - ($data['limit'] - count($pending_invitations['data'])));
			$record_per_page = count($pending_invitations['data']) < $data['limit'] ? $subtraction : $multiplication;
			$page_no = ($data['limit'] * ($page - 1)) + 1;
			$data['rec_per_page'] = $record_per_page;
			$data['page_no'] = $page_no;
			$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
			echo json_encode($res);
			return;
		} else if($section_name == 'revoked') {
			$limit = $this->config->item('pending_invitations_listing_limit');
			$total_record = $this->db->from('invited_friends_revoked_invitations')->where('referrer_id' , $user[0]->user_id)->count_all_results();
			$pagination = generate_pagination_links($total_record, $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'revoked']);
			$revoked_invitations = $this->Invite_friends_model->get_all_revoked_invitations_by_referrer_id($user[0]->user_id, $pagination['offset'], $limit);
			
			$page = $pagination['current_page_no'];
			$data['invitations'] = $revoked_invitations['data'];
			$data['pagination_links'] = $pagination['links'];
			$data['section_name'] = $section_name;
			$data['invitations_count'] = $revoked_invitations['total'];
			$data['limit'] = $limit;
			$multiplication = $data['limit'] * $page;
			$subtraction = ($multiplication - ($data['limit'] - count($revoked_invitations['data'])));
			$record_per_page = count($revoked_invitations['data']) < $data['limit'] ? $subtraction : $multiplication;
			$page_no = ($data['limit'] * ($page - 1)) + 1;
			$data['rec_per_page'] = $record_per_page;
			$data['page_no'] = $page_no;
			$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
			echo json_encode($res);
			return;
		} else if($section_name == 'accepted') {
			$limit = $this->config->item('accepted_invitations_listing_limit');
			$total_record = $this->db->from('invited_friends_registered')->where('referrer_id' , $user[0]->user_id)->count_all_results();
			$pagination = generate_pagination_links($total_record, $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'accepted']);
			$accepted_invitations = $this->Invite_friends_model->get_all_accepted_invitations_by_referrer_id($user[0]->user_id, $pagination['offset'], $limit);
			
			$page = $pagination['current_page_no'];
			$data['invitations'] = $accepted_invitations['data'];
			$data['pagination_links'] = $pagination['links'];
			$data['section_name'] = $section_name;
			$data['invitations_count'] = $accepted_invitations['total'];
			$data['limit'] = $limit;
			$multiplication = $data['limit'] * $page;
			$subtraction = ($multiplication - ($data['limit'] - count($accepted_invitations['data'])));
			$record_per_page = count($accepted_invitations['data']) < $data['limit'] ? $subtraction : $multiplication;
			$page_no = ($data['limit'] * ($page - 1)) + 1;
			$data['rec_per_page'] = $record_per_page;
			$data['page_no'] = $page_no;
			$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
			echo json_encode($res);
			return;
		}
	}
	// Manage different listing when user click on tabs [pending/accepted/revoked] ->  assets/js/modules/invite_friends.js
	public function get_invitations_listing_based_on_tab() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}
		$tab_name = $this->input->post('tab_name');
		$res['status'] = 200;
		$res['container_id'] = $row['container_id'];
		if($tab_name == 'pending_invitations') {
			$limit = $this->config->item('pending_invitations_listing_limit');
			$pending_invitations = $this->Invite_friends_model->get_all_pending_invitations_by_referrer_id($user[0]->user_id, 0, $limit);
			$data['invitations'] = $pending_invitations['data'];
			$data['invitations_count'] = $pending_invitations['total'];
			$pagination = generate_pagination_links($pending_invitations['total'], $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'pending']);
			$data['pagination_links'] = $pagination['links'];
			$data['limit'] = $limit;
			$data['page_no'] = 1;
			$data['rec_per_page'] = ($data['invitations_count'] > $data['limit']) ? $data['limit'] : $data['invitations_count'];
			$data['section_name'] = 'pending';
			$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
			echo json_encode($res);
			return;
		} else if($tab_name == 'revoked_invitations') {
			$limit = $this->config->item('revoked_invitations_listing_limit');
			$revoked_invitations = $this->Invite_friends_model->get_all_revoked_invitations_by_referrer_id($user[0]->user_id, 0, $limit);
			$data['invitations'] = $revoked_invitations['data'];
			$data['invitations_count'] = $revoked_invitations['total'];
			$pagination = generate_pagination_links($revoked_invitations['total'], $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'revoked']);
			$data['pagination_links'] = $pagination['links'];
			$data['limit'] = $limit;
			$data['page_no'] = 1;
			$data['rec_per_page'] = ($data['invitations_count'] > $data['limit']) ? $data['limit'] : $data['invitations_count'];
			$data['section_name'] = 'revoked';
			$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
			echo json_encode($res);
		} else if($tab_name == 'accepted_invitations') {
			$limit = $this->config->item('accepted_invitations_listing_limit');
			$accepted_invitations = $this->Invite_friends_model->get_all_accepted_invitations_by_referrer_id($user[0]->user_id, 0, $limit);
			$data['invitations'] = $accepted_invitations['data'];
			$data['invitations_count'] = $accepted_invitations['total'];
			$pagination = generate_pagination_links($accepted_invitations['total'], $this->config->item('invite_friends_page_url'), $limit, $this->config->item('manage_email_invitations_number_of_pagination_links'), '', ['data-section-name' => 'accepted']);
			$data['pagination_links'] = $pagination['links'];
			$data['limit'] = $limit;
			$data['page_no'] = 1;
			$data['rec_per_page'] = ($data['invitations_count'] > $data['limit']) ? $data['limit'] : $data['invitations_count'];
			$data['section_name'] = 'accepted';
			$res['data'] = $data['invitations_count'] > 0 ? $this->load->view('ajax_manage_email_invitations_listing', $data, true) : '';
			echo json_encode($res);
		}
	}
	// Revoke invitation confirmation popup body ->  assets/js/modules/invite_friends.js
	public function revoke_invitations_confirmation_popup_body() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}

		$check_accepted_invitaiton = $this->db->get_where('invited_friends_registered', ['invitation_id' => $row['invite_id']])->row_array();
		if(!empty($check_accepted_invitaiton)) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_revoke_invitation_already_accepted_error_message')]);
			die;
		}


		$confirmation_modal_title = $this->config->item('revoke_confirmation_title');
		$confirmationModalBody = $this->config->item('revoke_confirmation_text');
		$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmationModalBody.'</div>';
		$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" id="revoke_invi_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></label></div></div></div>';
		$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_popup_width_btn revoke_invitations_confirmation_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled class="btn blue_btn default_btn revoke_invitation revoke_invitations_confirmation_btn"  data-id="'.$row['id'].'" data-invite-id="'.$row['invite_id'].'">'.$this->config->item('revoke_confirmation_btn').' <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>';
		echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
		return;
	}
	// Resend invitation confirmation popup body -> assets/js/modules/invite_friends.js
	public function resend_invitations_confirmation_popup_body() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}

		$invitation_detail = $this->Invite_friends_model->get_pending_invitation($row['id']);

		if(strtotime($invitation_detail['next_resent_available_date']) >= strtotime(date('Y-m-d H:i:s')) && $invitation_detail['resends_available'] > 0) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_resend_invitation_before_next_available_time_being_available_error_message')]);
			die;
		} else if(strtotime($invitation_detail['next_resent_available_date']) >= strtotime(date('Y-m-d H:i:s')) && $invitation_detail['resends_available'] == 0) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_available_resends_depleted_error_message')]);
			die;
		}

		$check_accepted_invitaiton = $this->db->get_where('invited_friends_registered', ['invitation_id' => $row['invite_id']])->row_array();
		if(!empty($check_accepted_invitaiton)) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_resend_invitation_already_accepted_error_message')]);
			die;
		}

		$check_revoked_invitation = $this->db->get_where('invited_friends_revoked_invitations', ['invitation_id' => $row['invite_id']])->row_array();
		if(!empty($check_revoked_invitation)) {
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('invite_friends_user_try_resend_invitation_already_revoked_error_message')]);
			die;
		}

		$pending_acceptance = $this->db->get_where('invited_friends_invitations_pending_acceptance', ['id' => $row['id']])->row_array();
		if(!empty($pending_acceptance)) {

			if($pending_acceptance['resends_available'] == 0) {
				$res['status'] = 200;
				$res['error'] = $this->config->item('invite_friends_user_available_resends_depleted_error_message');
				$res['popup_heading'] = $this->config->item('popup_alert_heading');
				return;
			}

			$confirmation_modal_title = $this->config->item('resend_confirmation_title');
			$confirmationModalBody = $this->config->item('resend_confirmation_text');

			$confirmationModalBody = str_replace(['{invitee_email_address}', '{available_resend}'], [$pending_acceptance['invitee_email_address'], --$pending_acceptance['resends_available']], $confirmationModalBody);

			$confirmation_modal_body = '<div class="popup_body_semibold_title">'.$confirmationModalBody.'</div>';
			$confirmation_modal_body.= '<div class="row"><div class="col-md-12"><div class="disclaimer_separator"><label class="default_checkbox"><input type="checkbox" id="resend_invi_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$this->config->item('user_confirmation_check_box_txt').'</span></label></div></div></div>';
			$confirmation_modal_footer = '<button type="button" class="btn red_btn default_popup_width_btn default_btn resend_invitations_confirmation_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled class="btn blue_btn default_btn resend_invitation default_popup_width_btn resend_invitations_confirmation_btn"  data-id="'.$row['id'].'" data-invite-id="'.$row['invite_id'].'">'.$this->config->item('resend_confirmation_btn').' <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>';
			echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
		} 
		return;
	}

	// This method is used to display confirmation popup body for referral earnings withdraw amount 
	public function referral_earnings_withdraw_amount_confirmation_popup_body() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}
		$total_earnings_lvl1_lvl2 = $this->Invite_friends_model->get_sum_of_lvl1_lvl2_all_time_referral_earnings_from_referrer_id($user[0]->user_id);
		$total_earnings_lvl1_lvl2 -= $this->Invite_friends_model->get_sum_of_all_withdrawn_referral_earnings_amount_from_user_id($user[0]->user_id);

		if($total_earnings_lvl1_lvl2 < $this->config->item('minimum_allowed_referral_earnings_withdraw_funds_amount')) {
			echo json_encode(['status' => 200, 'error' => $this->config->item('minimum_allowed_referral_earnings_withdraw_funds_error_message')]);
			return;
		}

		$disclaimer_message = $this->config->item('user_confirmation_check_box_txt');
		$amount_input_label = $this->config->item('withdraw_referral_earnings_confirmation_popup_amount_lbl');
		$amount_tooltip_msg = $this->config->item('withdraw_referral_earnings_confirmation_popup_amount_tooltip_msg');

		$confirmation_modal_title = $this->config->item('withdraw_referral_earnings_confirmation_popup_title');
		$confirmation_modal_footer = '<button type="button" class="btn red_btn default_btn default_popup_width_btn" data-dismiss="modal" >'.$this->config->item('close_btn_txt').'</button><button type="button" disabled class="btn blue_btn withdraw_funds default_btn referral_earnings_withdraw_amount_btn">'.$this->config->item('withdraw_referral_earnings_funds_btn').' <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button>';

		$partial_release_escrow_form = '<div class="row"><div class="col-md-12"><div class="popup_body_semibold_title topLabel confirmationModal_amount_label_position">';
		$partial_release_escrow_form .= '<span><i class="far fa-credit-card"></i><label>'.$this->config->item('user_referral_earnings_account_balance_lbl').':</label><small class="touch_line_break">'.format_money_amount_display($total_earnings_lvl1_lvl2).' '. CURRENCY . '</small></span></div></div></div>';

		$partial_release_escrow_form .= '<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="radio_modal_separator releaseMilestone margin_bottom5">
					
				</div>
			</div>
		</div>';

		$partial_release_escrow_form .= '<div class="releaseMilestone">';

		$partial_release_escrow_form .= '
			<div class="rmMR">
				<span class="default_black_bold_medium" style="display:block;"><span class="rmMR_text">'.$amount_input_label.'</span><span><i class="fa fa-question-circle default_icon_help" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$amount_tooltip_msg.'"></i></span><label class="modal_label"><input type="text" style="cursor: text;" id="withdraw_amount" class="amount-text withdraw_amount default_input_field" name="withdraw_amount" placeholder="" ><span class="kcclr" >'.CURRENCY.'</span></label>
				</span>
				<div class="error_div_sectn clearfix">
					<span id="withdraw_funds_amount_error" class="error_msg"></span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>';
		$confirmation_modal_body .= $partial_release_escrow_form;
		$confirmation_modal_body .= '<div class="row"><div class="col-md-12"><div class="radio_modal_separator mt-0"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="withdraw_amount_checkbox_po"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text">'.$disclaimer_message.'</span></label></div></div></div>';
		echo json_encode(['status' => 200,'location'=>'','confirmation_modal_title'=>$confirmation_modal_title,'confirmation_modal_body'=>$confirmation_modal_body,'confirmation_modal_footer'=>$confirmation_modal_footer]);
		die;

	}
	// This method is used to store data of referral earnings into withdraw fund tracking table
	public function referral_earnings_withdraw_amount() {
		if (!$this->input->is_ajax_request()) {
			show_custom_404_page();
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		
		$row = $this->input->post();
		
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}
		$total_earnings_lvl1_lvl2 = $this->Invite_friends_model->get_sum_of_lvl1_lvl2_all_time_referral_earnings_from_referrer_id($user[0]->user_id);
		$total_earnings_lvl1_lvl2 -= $this->Invite_friends_model->get_sum_of_all_withdrawn_referral_earnings_amount_from_user_id($user[0]->user_id);
		
		if(empty($row['amount'])) {
			echo json_encode(['status' => 200, 'error' => $this->config->item('withdraw_amount_required_error_message')]);
			die;
		}
		if($row['amount'] < $this->config->item('minimum_allowed_referral_earnings_withdraw_funds_amount')) {
			echo json_encode(['status' => 200, 'error' => $this->config->item('minimum_allowed_referral_earnings_withdraw_funds_error_message')]);
			die;
		}
		if($row['amount'] > $total_earnings_lvl1_lvl2) {
			echo json_encode(['status' => 200, 'error' => $this->config->item('withdraw_amount_greater_then_total_referral_earnings_account_balance_error_message')]);
			die;
		}
		$reference_id = $this->config->item('REFERRAL_EARNINGS_WITHDRAWAL_TRANSACTION_ID_PREFIX');
		$latest_withdraw_funds_data = $this->db->from('users_referrals_earnings_withdraw_transactions_history')->where('referral_earnings_withdrawal_transaction_id REGEXP', $reference_id.'[0-9]')->order_by('id', 'DESC')->limit(1)->get()->row_array();
		$digits = $this->config->item('reference_id_digits_limit');
		if(empty($latest_withdraw_funds_data)) {
			$reference_id .= str_pad(1, $digits, "0", STR_PAD_LEFT);
		} else {
			$exclude_inital_number = str_replace($reference_id, '', $latest_withdraw_funds_data['referral_earnings_withdrawal_transaction_id']);
			$exclude_inital_number = ltrim($exclude_inital_number, '0');
			$exclude_inital_number = (int)$exclude_inital_number + 1;
			$reference_id .= str_pad($exclude_inital_number, $digits, "0", STR_PAD_LEFT);
		}

		$withdraw_funds = [
			'user_id' => $user[0]->user_id,
			'referral_earnings_withdrawal_transaction_id' => $reference_id,
			'referral_earnings_withdrawal_requested_amount' => $row['amount'],
			'referral_earnings_withdrawal_request_submit_date' => date('Y-m-d H:i:s')
		];
		$this->db->insert('users_referrals_earnings_withdraw_transactions_history', $withdraw_funds);

		$activity_log = $this->config->item('withdraw_amount_request_from_referral_earnings_user_activity_log');
		$activity_log = str_replace('{withdraw_amount}', $row['amount'], $activity_log);
		user_display_log($activity_log);

		$confirm_msg = $this->config->item('withdraw_amount_request_success_confirmation_message');
		$confirm_msg = str_replace('{withdraw_amount}', $row['amount'], $confirm_msg);

		$res['status'] = 200;
		$total_earnings_lvl1_lvl2 = $this->Invite_friends_model->get_sum_of_lvl1_lvl2_all_time_referral_earnings_from_referrer_id($user[0]->user_id);
		$total_earnings_lvl1_lvl2 -= $this->Invite_friends_model->get_sum_of_all_withdrawn_referral_earnings_amount_from_user_id($user[0]->user_id);
		$res['total_referral_earnings'] = format_money_amount_display($total_earnings_lvl1_lvl2).''.CURRENCY;
		$res['user_account_balance_title'] = $this->config->item('user_referral_earnings_account_balance_lbl').': '.format_money_amount_display($total_earnings_lvl1_lvl2).' '.CURRENCY;
		$res['msg'] = $confirm_msg;
		$withdraw_funds = $this->Invite_friends_model->get_all_referral_earnings_withdrawal_transactions_from_user_id($user[0]->user_id, $this->config->item('referral_earnings_withdraw_transaction_listing_limit'));

		foreach($withdraw_funds as &$value) {
			$value['referral_earnings_withdrawal_request_submit_date'] = date(DATE_TIME_FORMAT, strtotime($value['referral_earnings_withdrawal_request_submit_date']));
			$value['referral_earnings_withdrawal_request_admin_processing_date'] = date(DATE_TIME_FORMAT, strtotime($value['referral_earnings_withdrawal_request_admin_processing_date']));
			$value['referral_earnings_withdrawal_request_status'] = ucfirst($value['referral_earnings_withdrawal_request_status']);
		}

		$res['data'] = $withdraw_funds;
		echo json_encode($res);
		return;
	}
	
	public function invite_friend_view() { 
		if(!$this->session->userdata ('user')) {
			redirect (site_url());
		}
		
		$user = $this->session->userdata('user');
		$data['current_page'] = 'invite_friends';
		########## set the find_job title meta tag and meta description  start here #########
		$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ? $user[0]->first_name . ' ' . $user[0]->last_name : $user[0]->company_name;
		
		
		
		$title_meta_tag = $this->config->item('invite_friends_page_title_meta_tag');
		$description_meta_tag = $this->config->item('invite_friends_page_description_meta_tag');
		
		
		$title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
		$description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
		

		$data["share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_referral_url_direct_usage']));
		$data["fb_share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_self_url_share_fb']));
		$data["twitter_share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_self_url_share_twitter']));
		$data["linkedin_share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_self_url_share_ln']));

		$twitter_share_message = $this->config->item('user_referral_url_twitter_share_message');
		$twitter_share_message = str_replace('{referral_url}',$data["twitter_share_link"], $twitter_share_message);
		$data['twitter_share_message'] = $twitter_share_message;
		$data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
		########## set the find_job title tag start end #########
		
		$data['invited_friends_registered_via_fb_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_fb', 'project_url_share_fb', 'user_profile_share_fb', 'user_portfolio_sap_share_fb'])->count_all_results();
		$data['invited_friends_registered_via_twitter_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_twitter', 'project_url_share_twitter', 'user_profile_share_twitter', 'user_portfolio_sap_share_twitter'])->count_all_results();
		$data['invited_friends_registered_via_ln_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_ln', 'project_url_share_ln', 'user_profile_share_ln', 'user_portfolio_sap_share_ln'])->count_all_results();
					
		$data['invited_friends_registered_via_code_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id, 'referee_source' => 'user_referral_code_direct_usage'])->count_all_results();
		$data['invited_friends_registered_via_url_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id, 'referee_source' => 'user_referral_url_direct_usage'])->count_all_results();
		$data['invited_friends_registered_via_email_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_email', 'project_url_share_email', 'user_profile_share_email', 'user_portfolio_sap_share_email'])->count_all_results();

		########################################################  Referral registration #######################################################################

		$user_details = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		
		$data['lvl1_all_time_registration_cnt'] = $this->Invite_friends_model->get_lvl1_all_time_registrations_from_referrer_id($user[0]->user_id);
		$data['lvl1_today_registration_cnt'] = $this->Invite_friends_model->get_lvl1_registrations_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'DAY');	
		$data['lvl1_this_week_registration_cnt'] = $this->Invite_friends_model->get_lvl1_registrations_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'WEEK');		
		$data['lvl1_this_month_registration_cnt'] = $this->Invite_friends_model->get_lvl1_registrations_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'MONTH');

		$data['lvl2_all_time_registration_cnt'] = $this->Invite_friends_model->get_lvl2_all_time_registrations_from_referrer_id($user[0]->user_id, $user_details);
		$data['lvl2_today_registration_cnt'] = $this->Invite_friends_model->get_lvl2_registrations_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'DAY', $user_details);		
		$data['lvl2_this_week_registration_cnt'] = $this->Invite_friends_model->get_lvl2_registrations_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'WEEK', $user_details);		
		$data['lvl2_this_month_registration_cnt'] = $this->Invite_friends_model->get_lvl2_registrations_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'MONTH', $user_details);
		##################################################################################################################################################

		########################################################  Referral earnings #######################################################################
		$data['lvl1_all_time_earnings_value'] = $this->Invite_friends_model->get_lvl1_all_time_earnings_from_referrer_id($user[0]->user_id);
		$data['lvl1_today_earnings_value'] = $this->Invite_friends_model->get_lvl1_earnings_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'DAY');	
		$data['lvl1_this_week_earnings_value'] = $this->Invite_friends_model->get_lvl1_earnings_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'WEEK');		
		$data['lvl1_this_month_earnings_value'] = $this->Invite_friends_model->get_lvl1_earnings_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'MONTH');

		$data['lvl2_all_time_earnings_value'] = $this->Invite_friends_model->get_lvl2_all_time_earnings_from_referrer_id($user[0]->user_id);
		$data['lvl2_today_earnings_value'] = $this->Invite_friends_model->get_lvl2_earnings_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'DAY');		
		$data['lvl2_this_week_earnings_value'] = $this->Invite_friends_model->get_lvl2_earnings_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'WEEK');		
		$data['lvl2_this_month_earnings_value'] = $this->Invite_friends_model->get_lvl2_earnings_info_based_on_specifier_from_referrer_id($user[0]->user_id, 'MONTH');
		##################################################################################################################################################

		$month_name = $this->config->item('invite_friends_page_month_chart_months_names');
		for ($i = 0; $i < 12; $i++) {
			$month = date("n", strtotime( date( 'Y-m-01' )." -$i months"));
			$year = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
			$months[$month.'_'.$year] = $month_name[$month].' '.$year;
		}
		$data['month_chart_label_values'] = array_values(array_reverse($months));
		
		//###################################### Lvl1 registration chart data by month
		$lvl1_regi_month_chart = array_reverse($months);
		foreach($lvl1_regi_month_chart as &$val) {
			$val = 0;
		}
		
		$lvl1_registration_by_month = $this->Invite_friends_model->get_lvl1_registrations_by_month_from_referrer_id($user[0]->user_id);
		foreach($lvl1_registration_by_month as $value) {
			$month = date('n', strtotime($value['user_account_validation_date']));
			$year = date('Y', strtotime($value['user_account_validation_date']));
			$lvl1_regi_month_chart[$month.'_'.$year] = $value['cnt'];
		}

		$data['lvl1_registraiton_month_chart_data_values'] = json_encode(array_values($lvl1_regi_month_chart));

		// Lvl2 registration chart data by month
		$lvl2_regi_month_chart = array_reverse($months);
		foreach($lvl2_regi_month_chart as &$val) {
			$val = 0;
		}
		$lvl1_registration_by_month = $this->Invite_friends_model->get_lvl2_registrations_by_month_from_referrer_id($user[0]->user_id, $user_details);
		foreach($lvl1_registration_by_month as $value) {
			$month = date('n', strtotime($value['user_account_validation_date']));
			$year = date('Y', strtotime($value['user_account_validation_date']));
			$lvl2_regi_month_chart[$month.'_'.$year] = format_money_amount_display($value['cnt']);
		}
		$data['lvl2_registraiton_month_chart_data_values'] = json_encode(array_values($lvl2_regi_month_chart));
		//#######################################

		//###################################### Lvl1 and Lvl2 earnings chart data by month
		$lvl1_earn_month_chart = $lvl2_earn_month_chart = array_reverse($months);
		foreach($lvl1_earn_month_chart as &$val) {
			$val = 0;
		}
		foreach($lvl2_earn_month_chart as &$val) {
			$val = 0;
		}
		$earning_by_month = $this->Invite_friends_model->get_lvl1_lvl2_earnings_by_month_from_referrer_id($user[0]->user_id);
		
		foreach($earning_by_month as $value) {
			$month = date('n', strtotime($value['referral_earnings_month']));
			$year = date('Y', strtotime($value['referral_earnings_month']));
			$lvl1_earn_month_chart[$month.'_'.$year] = $value['aggregated_referral_earnings_value_lvl1'];
			$lvl2_earn_month_chart[$month.'_'.$year] = $value['aggregated_referral_earnings_value_lvl2'];
		}
		$data['lvl1_earnings_month_chart_data_values'] = json_encode(array_values($lvl1_earn_month_chart));
		$data['lvl2_earnings_month_chart_data_values'] = json_encode(array_values($lvl2_earn_month_chart));
		//##############################################################################################################

		//################################ lvl1 registration chart data of last 30 days
		$dates = [];
		for($i=0; $i<30; $i++) {
			$dt = date(DATE_FORMAT, strtotime('today - '.$i.' days'));
			$dates[$dt] = $dt;
		}
		$data['day_chart_label_values'] = array_values(array_reverse($dates));
		$lvl1_regi_day_chart = array_reverse($dates);
		foreach($lvl1_regi_day_chart as &$lvl1_chart_val) {
			$lvl1_chart_val = 0;
		}
		$lvl1_registration_by_day = $this->Invite_friends_model->get_lvl1_registrations_last_thirty_days_from_referrer_id($user[0]->user_id);
		
		foreach($lvl1_registration_by_day as $day_data) {
			$dt = date(DATE_FORMAT, strtotime($day_data['user_account_validation_date']));
			$lvl1_regi_day_chart[$dt] = $day_data['cnt'];
		}
		$data['lvl1_registration_day_chart_data_values'] = json_encode(array_values($lvl1_regi_day_chart));

		$lvl2_regi_day_chart = array_reverse($dates);
		foreach($lvl2_regi_day_chart as &$lvl2_chart_val) {
			$lvl2_chart_val = 0;
		}
		$lvl2_registration_by_day = $this->Invite_friends_model->get_lvl2_registrations_last_thirty_days_from_referrer_id($user[0]->user_id,  $user_details);
		foreach($lvl2_registration_by_day as $day_data) {
			$dt = date(DATE_FORMAT, strtotime($day_data['user_account_validation_date']));
			$lvl2_regi_day_chart[$dt] = $day_data['cnt'];
		}
		$data['lvl2_registration_day_chart_data_values'] = json_encode(array_values($lvl2_regi_day_chart));
		//#################################

		//############################### lvl1 and lvl2 earnings chart data of last 30 days
		$lvl1_earn_day_chart = $lvl2_earn_day_chart = array_reverse($dates);
		foreach($lvl1_earn_day_chart as &$lvl1_chart_val) {
			$lvl1_chart_val = 0;
		}
		foreach($lvl2_earn_day_chart as &$lvl2_chart_val) {
			$lvl2_chart_val = 0;
		}
		$earn_by_day = $this->Invite_friends_model->get_lvl1_lvl2_earnings_last_thirty_days_from_referrer_id($user[0]->user_id);
		
		foreach($earn_by_day as $day_data) {
			$dt = date(DATE_FORMAT, strtotime($day_data['referral_earnings_date']));
			$lvl1_earn_day_chart[$dt] = $day_data['aggregated_referral_earnings_value_lvl1'];
			$lvl2_earn_day_chart[$dt] = $day_data['aggregated_referral_earnings_value_lvl2'];
		}
		$data['lvl1_earnings_day_chart_data_values'] = json_encode(array_values($lvl1_earn_day_chart));
		$data['lvl2_earnings_day_chart_data_values'] = json_encode(array_values($lvl2_earn_day_chart));

		//##################################################################################

		//#################################### lvl1 registration chart data of last 52 weeks
		$lvl1_registration_by_week = $this->Invite_friends_model->get_lvl1_registrations_by_week_from_referrer_id($user[0]->user_id);
		$dates = [];
		for($i=0; $i<52; $i++) {
			$ts = strtotime('today - '.$i.' week');
			$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
			$start_date = date(DATE_FORMAT, $start);
			$dates[$start_date] = 0;
		}
		$data['week_chart_label_values'] = array_reverse(array_keys($dates));

		$lvl1_regi_week_chart = array_reverse($dates);
		foreach($lvl1_registration_by_week as $week_data) {
			$dt = date(DATE_FORMAT, strtotime('last sunday', strtotime($week_data['user_account_validation_date'])));
			$lvl1_regi_week_chart[$dt] = $week_data['cnt'];
		}
		$data['lvl1_registration_week_chart_data_values'] = json_encode(array_values($lvl1_regi_week_chart));
		$lvl2_registration_by_week = $this->Invite_friends_model->get_lvl2_registrations_by_week_from_referrer_id($user[0]->user_id, $user_details);
		$lvl2_regi_week_chart = array_reverse($dates);
		foreach($lvl2_registration_by_week as $week_data) {
			$dt = date(DATE_FORMAT, strtotime('last sunday', strtotime($week_data['user_account_validation_date'])));
			$lvl2_regi_week_chart[$dt] = $week_data['cnt'];
		}
		$data['lvl2_registration_week_chart_data_values'] = json_encode(array_values($lvl2_regi_week_chart));

		//########################################

		//####################################### lvl1 and lvl2 earnings by week
		$lvl1_earn_week_chart =  $lvl2_earn_week_chart = array_reverse($dates);
		$earning_by_week = $this->Invite_friends_model->get_lvl1_lvl2_earnings_by_week_from_referrer_id($user[0]->user_id);

		foreach($earning_by_week as $week_data) {
			$dt = date(DATE_FORMAT, strtotime($week_data['referral_earnings_week_start_date']));
			$lvl1_earn_week_chart[$dt] = $week_data['aggregated_referral_earnings_value_lvl1'];
			$lvl2_earn_week_chart[$dt] = $week_data['aggregated_referral_earnings_value_lvl2'];
		}
		$data['lvl1_earnings_week_chart_data_values'] = json_encode(array_values($lvl1_earn_week_chart));
		$data['lvl2_earnings_week_chart_data_values'] = json_encode(array_values($lvl2_earn_week_chart));
		//####################################################################################

		$data['user_details'] = $user_details;
		$data['user_downgrade_plan_details'] = $this->db->from('users_membership_plans_downgrade_tracking')->where('user_id', $user[0]->user_id)->order_by('id', 'DESC')->limit(1)->get()->row_array();
		$total_earnings_lvl1_lvl2 = $this->Invite_friends_model->get_sum_of_lvl1_lvl2_all_time_referral_earnings_from_referrer_id($user[0]->user_id);
		$total_earnings_lvl1_lvl2 -= $this->Invite_friends_model->get_sum_of_all_withdrawn_referral_earnings_amount_from_user_id($user[0]->user_id);
		$data['total_earnings_lvl1_lvl2'] = $total_earnings_lvl1_lvl2;

		$data['withdraw_funds'] = $this->Invite_friends_model->get_all_referral_earnings_withdrawal_transactions_from_user_id($user[0]->user_id, $this->config->item('referral_earnings_withdraw_transaction_listing_limit'));
		$this->layout->view ('invite_friends', '', $data, 'include');
	}

	//this function is encoding email subject in UTF-8 - In case there will be other functionalities that will allow users of that site to send multiple emails at once to other people, than this function to be moved to helper and made available site wide
	public function getEncodedSubject($subject){
		if (!preg_match('/[^\x20-\x7e]/', $subject)) {
			// ascii-only subject, return as-is
			return $subject;
		}
		// Subject is non-ascii, needs encoding
		$encoded = quoted_printable_encode($subject);
		$prefix = '=?UTF-8?q?';
		$suffix = '?=';
		return str_replace('"',' ',$prefix . str_replace("=\r\n", $suffix . "\r\n  " . $prefix, $encoded) . $suffix);
	}
}
?>