<?php
if ( ! defined ('BASEPATH')) {
    exit ('No direct script access allowed');
}
require_once APPPATH.'third_party/users_chat/connect_cassandra_db.php';
class Projects extends MX_Controller
{
	
	private $conn;
    public function __construct ()
    {	
			parent::__construct ();
			$this->load->model ('Projects_model');
			$this->load->model ('dashboard/Dashboard_model');
			$this->load->model ('bidding/Bidding_model');
			$this->load->model ('post_project/Post_project_model');
			$this->load->model('escrow/Escrow_model');
			$this->load->model('projects_disputes/Projects_disputes_model');
			// $this->load->model ('favorite_employers/Favorite_employers_model');
			$this->load->library ('form_validation');
			$this->load->model('user/User_model');
			$this->load->library ('pagination');
			$this->load->helper ('url');

			$this->conn = new connect_cassandra_db();
    }
	  
	/*
	 * @sid method used in assets/js/user_dashboard.js for updateing open bidding and expired project listing
	 */
	public function ajax_update_user_open_bidding_and_expired_project_listing() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata('user');
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
						
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		$data['page_type'] = 'dashboard';
		##################### fetch the expired projects from database and show on dashboard############
		$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
		$data["expired_project_data"] = $expired_project_data['data'];
		$data['expired_project_count'] = $expired_project_data['total'];
		$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
		$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
		$data['project_status'] = 'expired';
		$res['expired_project_tab_heading'] = $this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')';
		$res['expired_project_data'] = $this->load->view('projects/po_expired_projects_listing_my_projects', $data, true);
		##################### fetch the open bidding projects from database and show on dashboard ############
		$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
		$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
		$data['open_bidding_project_count'] = $open_bidding_project_data['total'];

		$open_project_cnt = $open_bidding_project_data['total'];
		$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
		$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
		$open_project_cnt -= $fulltime_open_project_count;

		$res['open_bidding_project_tab_heading'] = $this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')';
		$res['open_bidding_project_data'] = $this->load->view('projects/po_open_bidding_projects_listing_my_projects', $data, true);
		$res['status'] = 200;
		echo json_encode($res);
		return;
	}
	/*
	 * @sid method used in assets/js/user_dashboard.js for updateing draft and awaiting moderation view when user publish draft project
	*/
	public function ajax_update_user_draft_and_awaiting_moderation_project_view_dashboard() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata('user');
		$this->db->select('current_membership_plan_id');
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

			$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

			$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		$data['page_type'] = 'dashboard';
		##################### fetch the draft projects from database and show on dashboard############
		
		
		$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
		$data["draft_project_data"] = $draft_project_data['data'];
		$data["draft_project_count"] = $draft_project_data['total'];

		$draft_cnt = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
		$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
		
		$res['draft_project_data'] = $this->load->view('projects/po_draft_projects_listing_my_projects', $data, true);
		$res['draft_project_tab_heading'] = $this->config->item('my_projects_po_view_draft_tab_heading').' ('.$draft_cnt.'/'.($user_memebership_max_number_of_draft_projects - $draft_cnt).' + '.$fulltime_draft_cnt.'/'.($user_memebership_max_number_of_draft_fulltime_projects - $fulltime_draft_cnt).')';
		
		##################### fetch the awaiting moderation projects from database and show on dashboard############
	
		
		$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit'));
		$data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
		$data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];

		$awaiting_project_cnt = $awaiting_moderation_project_data['total'];
		$fulltime_awaiting_project_cnt = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_awaiting_moderation')->count_all_results();
		$awaiting_project_cnt -= $fulltime_awaiting_project_cnt;

		$res['awaiting_moderation_project_tab_heading'] = $this->config->item('my_projects_po_view_awaiting_moderation_tab_heading').' ('.$awaiting_project_cnt.' + '.$fulltime_awaiting_project_cnt.')';
		$res['awaiting_moderation_project_data'] = $this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects', $data, true);
		##################### fetch the open bidding projects from database and show on dashboard ############
		$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
		
		$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
		$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
		
		$open_project_cnt = $open_bidding_project_data['total'];
		$total_user_open_projects = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
		$total_user_open_fulltime_projects = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
		$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();

		$open_project_cnt -= $fulltime_open_project_count;
		
		$res['open_bidding_project_tab_heading']= $this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$total_user_open_projects).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $total_user_open_fulltime_projects).')';
		$res['open_bidding_project_data'] = $this->load->view('projects/po_open_bidding_projects_listing_my_projects', $data, true);
		$res['status'] = 200;
		echo json_encode($res);
		return;
	}
	/*
	 * @sid method used in assets/js/user_dashboard.js for updating draft view when user click on save project as draft
	*/
	public function ajax_update_user_draft_project_view_dashboard() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata('user');
		##################### fetch the draft projects from database and show on dashboard############
	
		$this->db->select('current_membership_plan_id');
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
			$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
		}
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
			$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
		}
		
		$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
						
		$data["page_type"] = "dashboard";
		$data["draft_project_data"] = $draft_project_data['data'];
		$data["draft_project_count"] = $draft_project_data['total'];

		$draft_cnt = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
		$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);

		$res['draft_project_tab_heading'] = $this->config->item('my_projects_po_view_draft_tab_heading').' ('.$draft_cnt.'/'.($user_memebership_max_number_of_draft_projects - $draft_cnt).' + '.$fulltime_draft_cnt.'/'.($user_memebership_max_number_of_draft_fulltime_projects - $fulltime_draft_cnt).')';
		$res['draft_project_data'] = $this->load->view('projects/po_draft_projects_listing_my_projects', $data, true);
		$res['status'] = 200;
		echo json_encode($res);
		return;
		}
	/*
	 * @sid method used in assets/js/user_dashboard.js for updating awaiting moderation or open for bidding view when user click on publish button
	*/
	
	public function ajax_update_user_awaiting_moderation_or_open_for_bidding_project_view_dashboard() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata('user');
		$this->db->select('current_membership_plan_id');
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		##################### fetch the awaiting moderation projects from database and show on dashboard############
		
		$data['page_type'] = 'dashboard';
		$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit'));
		$data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
		$data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];
		
		$awaiting_project_cnt = $awaiting_moderation_project_data['total'];
		$fulltime_awaiting_project_cnt = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_awaiting_moderation')->count_all_results();
		$awaiting_project_cnt -= $fulltime_awaiting_project_cnt;
		
		$res['awaiting_moderation_project_tab_heading'] = $this->config->item('my_projects_po_view_awaiting_moderation_tab_heading').' ('.$awaiting_project_cnt.' + '.$fulltime_awaiting_project_cnt.')';
		$res['awaiting_moderation_project_data'] = $this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects', $data, true);
		##################### fetch the open bidding projects from database and show on dashboard ############
		$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
		$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
		$data['open_bidding_project_count'] = $open_bidding_project_data['total'];

		$open_project_cnt = $open_bidding_project_data['total'];
		$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
		$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
		$open_project_cnt -= $fulltime_open_project_count;
		
		$res['open_bidding_project_tab_heading'] = $this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')';
		$res['open_bidding_project_data'] = $this->load->view('projects/po_open_bidding_projects_listing_my_projects', $data, true);
		$res['status'] = 200;
		echo json_encode($res);
		die;
	}
	/**
	 * @sid method used in assets/js/user_dashboard.js to update latest project section on dashboard when project auto approved or manually approved by admin or directly move to open bidding stage based on configuration done in custom config file
	*/
	public function ajax_update_latest_projects_dashboard_view($param = '') {
		if($this->input->is_ajax_request()){
			if(!check_session_validity()) {
				echo json_encode(['status' => 404]);
				return;
			}
			$cron_start_date = date('d.m.Y H:i:s');
			$cron_refreshed_project_info = '';
			// update expired project upgrade status from open bidding table
			$data = $this->Projects_model->get_all_latest_projects();
			$res['status'] = 200;
			$res['data'] = $this->load->view('ajax_latest_project_user_dashboard', $data, true);
			echo json_encode($res);
			return;
		} else { 
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	/**
	 * @sid method used in assets/js/user_dashboard.js to get listing either open bidding or expired project with cancel project
	*/
	public function ajax_update_open_bidding_or_expired_and_cancelled_project_dashboard_view($status) {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata('user');
		
		$this->db->select('current_membership_plan_id');
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		$data["page_type"] = 'dashboard';
		if($status == 'expired') {
			$data['project_status'] = 'expired';
			
			$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
			$data["expired_project_data"] = $expired_project_data['data'];
			$data['expired_project_count'] = $expired_project_data['total'];
			$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
			$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
			$expired_project_cnt = $expired_project_data['total'];
			$res['expired_project_tab_heading'] = $this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_cnt - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')';
			$res['expired_project_data'] = $this->load->view('projects/po_expired_projects_listing_my_projects', $data, true);
		} else {
			##################### fetch the open bidding projects from database and show on dashboard ############
			
			$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
			$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
			$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
			$open_project_cnt = $open_bidding_project_data['total'];
			$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
			$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
			$open_project_cnt -= $fulltime_open_project_count;

			$res['open_bidding_project_tab_heading'] = $this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')';
			$res['open_bidding_project_data'] = $this->load->view('projects/po_open_bidding_projects_listing_my_projects', $data, true);
		}
		
		$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_cancelled_projects_listing_limit'));
		$data["cancelled_project_data"] = $cancelled_project_data['data'];
		$data['cancelled_project_count'] = $cancelled_project_data['total'];
		$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
		$fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
		$data['project_status'] = 'cancelled';
		$res['cancelled_project_tab_heading'] = $this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')';
		$res['cancelled_project_data'] = $this->load->view('projects/po_cancelled_projects_listing_my_projects', $data, true);
		$res['status'] = 200;
		echo json_encode($res);
		return;
	}
	/**
	 * @sid method used in assets/js/user_dashboard.js to get listing of project based on status
	*/
	public function ajax_update_project_listing_dashboard_view_by_status($status) {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$user = $this->session->userdata('user');
		$this->db->select('current_membership_plan_id');
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		$data['page_type'] = 'dashboard';
		if($status == 'awaiting_moderation') {
			
			$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit'));
			$data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
			$data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];

			$awaiting_project_cnt = $awaiting_moderation_project_data['total'];
			$fulltime_awaiting_project_cnt = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_awaiting_moderation')->count_all_results();
			$awaiting_project_cnt -= $fulltime_awaiting_project_cnt;

			$res['awaiting_moderation_project_tab_heading'] = $this->config->item('my_projects_po_view_awaiting_moderation_tab_heading').' ('.$awaiting_project_cnt.' + '.$fulltime_awaiting_project_cnt.')';
			$res['awaiting_moderation_project_data'] = $this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects', $data, true);
		} else if($status == 'open') {
					
			$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
			$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
			$data['open_bidding_project_count'] = $open_bidding_project_data['total'];

			$open_project_cnt = $open_bidding_project_data['total'];
			$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
			$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
			$open_project_cnt -= $fulltime_open_project_count;

			$res['open_bidding_project_tab_heading'] = $this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')';
			$res['open_bidding_project_data'] = $this->load->view('projects/po_open_bidding_projects_listing_my_projects', $data, true);
		} else if($status == 'expired') {
			$data['project_status'] = 'expired';
			$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
			$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
			$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
			$res["expired_project_tab_heading"] = $this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')';
			$data["expired_project_data"] = $expired_project_data['data'];
			$data['expired_project_count'] = $expired_project_data['total'];
			$res['expired_project_data'] = $this->load->view('projects/po_expired_projects_listing_my_projects', $data, true);
		} else if($status == 'canceled') {
			$data['project_status'] = 'cancelled';
			$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_cancelled_projects_listing_limit'));
			$data["cancelled_project_data"] = $cancelled_project_data['data'];
			$data['cancelled_project_count'] = $cancelled_project_data['total'];
			$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
			$fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
			$res['cancelled_project_tab_heading'] = $this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')';
			$res['cancelled_project_data'] = $this->load->view('projects/po_cancelled_projects_listing_my_projects', $data, true);
		}
		$res['status'] = 200;
		echo json_encode($res);
		return;
	}
	
	/*
	This function update the my projects section tabs on dashboard page
	*/
	public function update_dasboard_myprojects_tabs(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
					$user = $this->session->userdata ('user');
					$tab_type = $this->input->post ('tab_type');
					$user_id = $this->input->post('user_id');
					if($user_id != $user[0]->user_id) {
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
					$data["page_type"] = 'dashboard';
					
					if($tab_type == 'draft' || $tab_type == 'openBid'){
						$this->db->select('current_membership_plan_id');
						$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
						
						if($user_detail['current_membership_plan_id'] == 1){
							$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
							$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

							$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
							$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
						}
						if($user_detail['current_membership_plan_id'] == 4){
							$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
							$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

							$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
							$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
						}
					}
					
					if($tab_type == 'draft'){
						$data['po_max_draft_projects_number'] = $user_memebership_max_number_of_draft_projects;
						$data['po_max_open_projects_number'] = $user_memebership_max_number_of_open_for_bidding_projects;
						$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
						$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
						$standard_valid_time_arr = array_filter($standard_check_valid_arr);
						$data['standard_valid_time_arr'] = $standard_valid_time_arr;
						
						$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
						########## fetch open bidding project information of logged in user ##########################
						$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
						
						
						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
						
						$data["draft_project_data"] = $draft_project_data['data'];
						$data["draft_project_count"] = $draft_project_data['total'];

						$draft_cnt = $draft_project_data['total'];
						$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
						$draft_cnt -= $fulltime_draft_cnt;

						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_draft_tab_heading').' ('.$draft_cnt.'/'.($user_memebership_max_number_of_draft_projects - $draft_cnt).' + '.$fulltime_draft_cnt.'/'.($user_memebership_max_number_of_draft_fulltime_projects - $fulltime_draft_cnt).')','data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'awaitingModeration'){
						$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit'));
						$data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
						$data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];

						$awaiting_project_cnt = $awaiting_moderation_project_data['total'];
						$fulltime_awaiting_project_cnt = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_awaiting_moderation')->count_all_results();
						$awaiting_project_cnt -= $fulltime_awaiting_project_cnt;

						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_awaiting_moderation_tab_heading').' ('.$awaiting_project_cnt.' + '.$fulltime_awaiting_project_cnt.')','data'=>$this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects',$data, true)]);
						die;
					
					}
					if($tab_type == 'openBid'){
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
						$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
						$data['open_bidding_project_count'] = $open_bidding_project_data['total'];

						$open_project_cnt = $open_bidding_project_data['total'];
						$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
						$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						$open_project_cnt -= $fulltime_open_project_count;

						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'awarded'){
						$awarded_project_data = $this->Projects_model->get_po_awarded_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awarded_projects_listing_limit'));
						$data["awarded_project_data"] = $awarded_project_data['data'];
						$data['awarded_project_count'] = $awarded_project_data['total'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_awarded_tab_heading').' ('.$awarded_project_data['total'].')','data'=>$this->load->view('projects/po_awarded_projects_listing_my_projects',$data, true)]);
						die;
						
					
					}
					if($tab_type == 'workPro'){
						$in_progress_project_data = $this->Projects_model->get_po_in_progress_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_in_progress_projects_listing_limit'));
						$data["in_progress_project_data"] = $in_progress_project_data['data'];
						$data['in_progress_project_count'] = $in_progress_project_data['total'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_work_in_progress_tab_heading').' ('.$in_progress_project_data['total'].')','data'=>$this->load->view('projects/po_in_progress_projects_listing_my_projects',$data, true)]);
						die;
					
					}
					if($tab_type == 'incomplete'){
						$in_complete_project_data = $this->Projects_model->get_po_incomplete_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_incomplete_projects_listing_limit'));
						$data["in_complete_project_data"] = $in_complete_project_data['data'];
						$data['in_complete_project_count'] = $in_complete_project_data['total'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_work_incomplete_tab_heading').' ('.$in_complete_project_data['total'].')','data'=>$this->load->view('projects/po_incomplete_projects_listing_my_projects',$data, true)]);
						die;
					
					}
					if($tab_type == 'completed'){
						
						$completed_project_data = $this->Projects_model->get_po_completed_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_completed_projects_listing_limit'));
						$data["completed_project_data"] = $completed_project_data['data'];
						$data['completed_project_count'] = $completed_project_data['total'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_completed_tab_heading').' ('.$completed_project_data['total'].')','data'=>$this->load->view('projects/po_completed_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'expired'){
						
						$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
						$data["expired_project_data"] = $expired_project_data['data'];
						$data['expired_project_count'] = $expired_project_data['total'];
						$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
            $fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'cancelled'){
						
						$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_cancelled_projects_listing_limit'));
						$data["cancelled_project_data"] = $cancelled_project_data['data'];
						$data['cancelled_project_count'] = $cancelled_project_data['total'];
						$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
            $fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')','data'=>$this->load->view('projects/po_cancelled_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'active_bids'){
						
						$active_bids_project_data = $this->Bidding_model->get_sp_active_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_active_bids_listing_limit'));
						$data["active_bids_project_data"] = $active_bids_project_data['data'];
						$data['active_bids_project_count'] = $active_bids_project_data['total'];
						$fulltime_active_bids_project_count = $this->db->where(['employee_id' => $user[0]->user_id])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
						$fulltime_active_bids_project_count += $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date <=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_active_bids_tab_heading').' ('.($active_bids_project_data['total'] - $fulltime_active_bids_project_count).' + '.$fulltime_active_bids_project_count.')','data'=>$this->load->view('bidding/sp_active_bids_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'awarded_bids'){
						$awarded_bids_project_data = $this->Bidding_model->get_sp_awarded_bids_listing_my_projects($user[0]->user_id,0,$this->config->item('user_dashboard_sp_view_awarded_bids_listing_limit'));
						$data["awarded_bids_project_data"] = $awarded_bids_project_data['data'];
						$data['awarded_bids_project_count'] = $awarded_bids_project_data['total'];
						
						$fulltime_awarded_bids_project_count = $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date >='=> date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
						
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_awarded_bids_tab_heading').' ('.($awarded_bids_project_data['total'] - $fulltime_awarded_bids_project_count).' + '.$fulltime_awarded_bids_project_count.')','data'=>$this->load->view('bidding/sp_awarded_bids_listing_my_projects',$data, true)]);
						die;
						
					} 
					if($tab_type == 'hired') {
						$hired_project_data = $this->Bidding_model->get_employee_hired_listing_my_projects($user[0]->user_id,0,$this->config->item('user_dashboard_fulltime_projects_employee_view_hired_listing_limit'));
						$data["hired_project_data"] = $hired_project_data['data'];
						$var_hired_project_data = $hired_project_data['data'];
						$data['hired_project_count'] = $hired_project_data['total'];
						foreach($var_hired_project_data as &$value) {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['fulltime_project_id' => $value['project_id'], 'employer_id' => $value['project_owner_id'], 'employee_id' => $user[0]->user_id]);
						}
						$data["hired_project_data"] = $var_hired_project_data;
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_employee_view_fulltime_projects_hired_tab_heading').' ('.$hired_project_data['total'].')','data'=>$this->load->view('bidding/employee_hired_application_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'in_progress_work'){
						$in_progress_bids_project_data = $this->Bidding_model->get_sp_in_progress_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_in_progress_bids_listing_limit'));
						$var_in_progress_bids_project_data = $in_progress_bids_project_data['data'];
						$data['in_progress_bids_project_count'] = $in_progress_bids_project_data['total'];
						foreach($var_in_progress_bids_project_data as &$value) {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user[0]->user_id]);
						}
						$data["in_progress_bids_project_data"] = $var_in_progress_bids_project_data;
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_projects_in_progress_tab_heading').' ('.$in_progress_bids_project_data['total'].')','data'=>$this->load->view('bidding/sp_in_progress_bids_listing_my_projects',$data, true)]);
						die;
						
					}if($tab_type == 'in_complete_work'){
						$in_complete_bids_project_data = $this->Bidding_model->get_sp_incomplete_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_incomplete_bids_listing_limit'));
						$var_in_complete_bids_project_data = $in_complete_bids_project_data['data'];
						$data['in_complete_bids_project_count'] = $in_complete_bids_project_data['total'];
						foreach($var_in_complete_bids_project_data as &$value) {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user[0]->user_id]);
						}
						$data["in_complete_bids_project_data"] = $var_in_complete_bids_project_data;
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_projects_incomplete_tab_heading').' ('.$in_complete_bids_project_data['total'].')','data'=>$this->load->view('bidding/sp_incomplete_bids_listing_my_projects',$data, true)]);
						die;
						
					}
					if($tab_type == 'completed_work'){
						
						$completed_bids_project_data = $this->Bidding_model->get_sp_completed_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_completed_bids_listing_limit'));
						$data["completed_bids_project_data"] = $completed_bids_project_data['data'];
						$data['completed_bids_project_count'] = $completed_bids_project_data['total'];

// pre($data["completed_bids_project_data"]);
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_completed_tab_heading').' ('.$completed_bids_project_data['total'].')','data'=>$this->load->view('bidding/sp_completed_bids_listing_my_projects',$data, true)]);
						die;
					}
				
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}else{
				show_custom_404_page(); //show custom 404 page
			}
			
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
	/*
	This function update the my projects section tabs on my projects page page
	*/
	public function update_myprojects_tabs(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
					$data["page_type"] = 'my_projects';
					$user = $this->session->userdata ('user');
					$user_id = $this->input->post('user_id');
					if($user_id != $user[0]->user_id) {
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
					}
          $tab_type = $this->input->post ('tab_type');
          $num_pagination_links = $this->config->item('my_projects_number_of_pagination_links');
          $my_projects_page_url = $this->config->item('my_projects_page_url');
					if($tab_type == 'draft' || $tab_type == 'openBid'){
						$this->db->select('current_membership_plan_id');
						$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
						
						if($user_detail['current_membership_plan_id'] == 1){
							$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
							$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

							$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
							$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
						}
						if($user_detail['current_membership_plan_id'] == 4){
							$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
							$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

							$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
							$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
						}
					}
					
					if($tab_type == 'draft'){
            ############## fetch the draft projects from database and show on myproject ############
            
            $data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
            $data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
            
            $per_page_limit = $this->config->item('my_projects_po_view_draft_projects_listing_limit');
            
            $paginations = generate_pagination_links(($data['draft_cnt'] + $data['fulltime_draft_cnt']),$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'draft'] );

						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $per_page_limit);
						$data["draft_project_data"] = $draft_project_data['data'];
						$data["draft_project_count"] = $draft_project_data['total'];
						$data['draft_pagination_links'] = $paginations['links'];
						
						$data['po_max_draft_projects_number'] = $user_memebership_max_number_of_draft_projects;
						$data['po_max_open_projects_number'] = $user_memebership_max_number_of_open_for_bidding_projects;
						$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
						$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
						$standard_valid_time_arr = array_filter($standard_check_valid_arr);
						$data['standard_valid_time_arr'] = $standard_valid_time_arr;
						
						

						########## fetch open bidding project information of logged in user ##########################
						$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
						
						
						
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_draft_tab_heading').' ('.$data['draft_cnt'].'/'.($user_memebership_max_number_of_draft_projects - $data['draft_cnt']).' + '.$data['fulltime_draft_cnt'].'/'.($user_memebership_max_number_of_draft_fulltime_projects - $data['fulltime_draft_cnt']).')','data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'awaitingModeration'){
            ######## fetch the awaiting moderation projects from database and show on myproject
            $per_page_limit = $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit');
						$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $per_page_limit);
            $data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
            $data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];
            
            $awaiting_project_cnt = $awaiting_moderation_project_data['total'];
            $fulltime_awaiting_project_cnt = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime' ])->from('projects_awaiting_moderation')->count_all_results();
            $awaiting_project_cnt -= $fulltime_awaiting_project_cnt;
            $paginations = generate_pagination_links($awaiting_moderation_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'awaiting_moderation'] );
            $data['awaiting_moderation_pagination_links'] = $paginations['links'];
            echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_awaiting_moderation_tab_heading').' ('.$awaiting_project_cnt.' + '.$fulltime_awaiting_project_cnt.')','data'=>$this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects',$data, true)]);
						die;
					
					}
					if($tab_type == 'openBid'){
            ######## fetch the open for bidding projects from database and show on myproject 	
            $per_page_limit = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
            $data["open_bidding_project_data"] = $open_bidding_project_data['data'];
            $data['open_bidding_project_count'] = $open_bidding_project_data['total'];

            $open_project_cnt = $open_bidding_project_data['total'];
            $fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
            $fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
            $open_project_cnt -= $fulltime_open_project_count;

            $paginations = generate_pagination_links($open_bidding_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'open_for_bidding'] );
            $data['open_bidding_pagination_links'] = $paginations['links'];
            echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
						die;
					
					}
					if($tab_type == 'awarded'){
            ######## fetch the awarded projects from database and show on myproject 	
            $per_page_limit = $this->config->item('my_projects_po_view_awarded_projects_listing_limit');
            $awarded_project_data = $this->Projects_model->get_po_awarded_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_awarded_projects_listing_limit'));
            $data["awarded_project_data"] = $awarded_project_data['data'];
            $data['awarded_project_count'] = $awarded_project_data['total'];
            $paginations = generate_pagination_links($awarded_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'awarded'] );
            $data['awarded_pagination_links'] = $paginations['links'];
            echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_awarded_tab_heading').' ('.$awarded_project_data['total'].')','data'=>$this->load->view('projects/po_awarded_projects_listing_my_projects',$data, true)]);
            die;
					}
			if($tab_type == 'workPro'){
				######## fetch the in progress projects from database and show on myproject 
				$per_page_limit = $this->config->item('my_projects_po_view_in_progress_projects_listing_limit');
				$in_progress_project_data = $this->Projects_model->get_po_in_progress_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_in_progress_projects_listing_limit'));
				$data["in_progress_project_data"] = $in_progress_project_data['data'];
				$data['in_progress_project_count'] = $in_progress_project_data['total'];
				$paginations = generate_pagination_links($in_progress_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'in_progress'] );
				$data['in_progress_pagination_links'] = $paginations['links'];
				echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_work_in_progress_tab_heading').' ('.$in_progress_project_data['total'].')','data'=>$this->load->view('projects/po_in_progress_projects_listing_my_projects',$data, true)]);
				die;
			
			}
			if($tab_type == 'incomplete'){
				######## fetch the in complete projects from database and show on myproject 
				$per_page_limit = $this->config->item('my_projects_po_view_incomplete_projects_listing_limit');
				$in_complete_project_data = $this->Projects_model->get_po_incomplete_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_incomplete_projects_listing_limit'));
				$data["in_complete_project_data"] = $in_complete_project_data['data'];
				$data['in_complete_project_count'] = $in_complete_project_data['total'];
				$paginations = generate_pagination_links($in_complete_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'in_complete'] );
				$data['in_complete_pagination_links'] = $paginations['links'];
				echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_work_incomplete_tab_heading').' ('.$in_complete_project_data['total'].')','data'=>$this->load->view('projects/po_incomplete_projects_listing_my_projects',$data, true)]);
				die;
			
			}
			if($tab_type == 'completed'){
            ######## fetch the completed projects from database and show on myproject
            $per_page_limit = $this->config->item('my_projects_po_view_completed_projects_listing_limit'); 	
						$completed_project_data = $this->Projects_model->get_po_completed_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_completed_projects_listing_limit'));
            $data["completed_project_data"] = $completed_project_data['data'];
            $data['completed_project_count'] = $completed_project_data['total'];
            $paginations = generate_pagination_links($completed_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'completed'] );
            $data['completed_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_completed_tab_heading').' ('.$completed_project_data['total'].')','data'=>$this->load->view('projects/po_completed_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'expired'){
            ######## fetch the expired projects from database and show on myproject 
            $per_page_limit = $this->config->item('my_projects_po_view_expired_projects_listing_limit');
						$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
						$data["expired_project_data"] = $expired_project_data['data'];
            $data['expired_project_count'] = $expired_project_data['total'];
            $paginations = generate_pagination_links($expired_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'expired'] );
						$data['expired_pagination_links'] = $paginations['links'];
						$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
            $fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'cancelled'){
            ######## fetch the cancelled projects from database and show on myproject 	
            $per_page_limit = $this->config->item('my_projects_po_view_cancelled_projects_listing_limit');
						$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));
						$data["cancelled_project_data"] = $cancelled_project_data['data'];
            $data['cancelled_project_count'] = $cancelled_project_data['total'];
            $paginations = generate_pagination_links($cancelled_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'cancelled'] );
						$data['cancelled_pagination_links'] = $paginations['links'];
						$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
            $fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')','data'=>$this->load->view('projects/po_cancelled_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'active_bids'){
            ######## fetch the active bids from database and show on myproject 
            $per_page_limit = $this->config->item('my_projects_sp_view_active_bids_listing_limit');
						$active_bids_project_data = $this->Bidding_model->get_sp_active_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_active_bids_listing_limit'));
						$data["active_bids_project_data"] = $active_bids_project_data['data'];
						$data['active_bids_project_count'] = $active_bids_project_data['total'];
						$fulltime_active_bids_project_count = $this->db->where(['employee_id' => $user[0]->user_id])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
            $fulltime_active_bids_project_count += $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date <=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
            $paginations = generate_pagination_links($active_bids_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'active_bids'] );
						$data['active_bids_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_active_bids_tab_heading').' ('.($active_bids_project_data['total'] - $fulltime_active_bids_project_count).' + '.$fulltime_active_bids_project_count.')','data'=>$this->load->view('bidding/sp_active_bids_listing_my_projects',$data, true)]);
						die;
					}
					if($tab_type == 'awarded_bids'){
            ######## fetch the awarded bids from database and show on myproject 	
            $per_page_limit = $this->config->item('my_projects_sp_view_awarded_bids_listing_limit');
						$awarded_bids_project_data = $this->Bidding_model->get_sp_awarded_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_awarded_bids_listing_limit'));
						$data["awarded_bids_project_data"] = $awarded_bids_project_data['data'];
						$data['awarded_bids_project_count'] = $awarded_bids_project_data['total'];
						$fulltime_awarded_bids_project_count = $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date >=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
            $paginations = generate_pagination_links($awarded_bids_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'awarded_bids'] );
						$data['awarded_bids_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_awarded_bids_tab_heading').' ('.($awarded_bids_project_data['total'] - $fulltime_awarded_bids_project_count).' + '.$fulltime_awarded_bids_project_count.')','data'=>$this->load->view('bidding/sp_awarded_bids_listing_my_projects',$data, true)]);
						die;
						
					}
					if($tab_type == 'hired'){
            ######## fetch the hired projects from database and show on myproject
            $per_page_limit = $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit'); 		
						$hired_project_data = $this->Bidding_model->get_employee_hired_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit'));
						$data["hired_project_data"] = $hired_project_data['data'];
						$var_hired_project_data = $hired_project_data['data'];
						$data['hired_project_count'] = $hired_project_data['total'];
						foreach($var_hired_project_data as &$value) {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['fulltime_project_id' => $value['project_id'], 'employer_id' => $value['project_owner_id'], 'employee_id' => $user[0]->user_id]);
						}
            $data["hired_project_data"] = $var_hired_project_data;
            $paginations = generate_pagination_links($hired_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'hired'] );
						$data['hired_application_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_employee_view_fulltime_projects_hired_tab_heading').' ('.$hired_project_data['total'].')','data'=>$this->load->view('bidding/employee_hired_application_listing_my_projects',$data, true)]);
						die;
						
					}
					if($tab_type == 'in_progress_work'){
            ######## fetch the in progress bids from database and show on myproject 
                     $per_page_limit = $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit');		
						$in_progress_bids_project_data = $this->Bidding_model->get_sp_in_progress_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit'));
						$data["in_progress_bids_project_data"] = $in_progress_bids_project_data['data'];
						$var_in_progress_bids_project_data = $in_progress_bids_project_data['data'];
						$data['in_progress_bids_project_count'] = $in_progress_bids_project_data['total'];
						foreach($var_in_progress_bids_project_data as &$value) {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user[0]->user_id]);
						}
					 $data["in_progress_bids_project_data"] = $var_in_progress_bids_project_data;
                      $paginations = generate_pagination_links($in_progress_bids_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'in_progress_bids'] );
						$data['in_progress_bids_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_projects_in_progress_tab_heading').' ('.$in_progress_bids_project_data['total'].')','data'=>$this->load->view('bidding/sp_in_progress_bids_listing_my_projects',$data, true)]);
						die;
						
					}
					if($tab_type == 'in_complete_work'){
            ######## fetch the in complete bids from database and show on myproject 
                     $per_page_limit = $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit');		
						$in_complete_bids_project_data = $this->Bidding_model->get_sp_incomplete_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit'));
						$data["in_complete_bids_project_data"] = $in_complete_bids_project_data['data'];
						$var_in_complete_bids_project_data = $in_complete_bids_project_data['data'];
						$data['in_complete_bids_project_count'] = $in_complete_bids_project_data['total'];
						foreach($var_in_complete_bids_project_data as &$value) {
							$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user[0]->user_id]);
						}
					 $data["in_complete_bids_project_data"] = $var_in_complete_bids_project_data;
                      $paginations = generate_pagination_links($in_complete_bids_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'in_complete_bids'] );
						$data['in_complete_bids_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_projects_incomplete_tab_heading').' ('.$in_complete_bids_project_data['total'].')','data'=>$this->load->view('bidding/sp_incomplete_bids_listing_my_projects',$data, true)]);
						die;
						
					}
					if($tab_type == 'completed_work'){
            ######## fetch the completed bids from database and show on myproject 	
            $per_page_limit = $this->config->item('my_projects_sp_view_completed_bids_listing_limit');			
						$completed_bids_project_data = $this->Bidding_model->get_sp_completed_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_completed_bids_listing_limit'));
						$data["completed_bids_project_data"] = $completed_bids_project_data['data'];
            $data['completed_bids_project_count'] = $completed_bids_project_data['total'];
            $paginations = generate_pagination_links($completed_bids_project_data['total'],$my_projects_page_url, $per_page_limit, $num_pagination_links,'', ['data-tab-type' => 'completed_bids'] );
						$data['completed_bids_pagination_links'] = $paginations['links'];
						echo json_encode(['status' => 200,'tab_heading'=>$this->config->item('my_projects_sp_view_completed_tab_heading').' ('.$completed_bids_project_data['total'].')','data'=>$this->load->view('bidding/sp_completed_bids_listing_my_projects',$data, true)]);
						die;
					}
				
				} else {
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			} else {
				show_custom_404_page(); //show custom 404 page
			}
			
		} else {
			
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
	/**
	This function is used to for edit draft project categroies not deleted/disabled
	*/
	public function check_edit_draft_project_categories () {
        if ( $this->input->is_ajax_request ())
        {
			if(!$this->input->post ('project_id')){
			
				show_custom_404_page(); //show custom 404 page
				return;
			}
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
				if($check_project_categories_available_or_not == 0){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
					die;
				}
				
				$msg['status'] = 200;
				echo json_encode($msg);
				die;
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			}
			
        }else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	* This function is used to edit the draft project by login user.
	**/
	
	public function edit_draft_project() {
		
		if(check_session_validity()){ // check session exists or not if exist then it will update user session
		
			if(empty($this->input->get('id'))){
				show_custom_404_page(); //show custom 404 page
			}
			$user = $this->session->userdata('user');
			$check_project_exists = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('projects_draft')
				->where('project_id',$this->input->get('id'))
				->where('project_owner_id', $user[0]->user_id)
				->get ()->num_rows ();
			if($check_project_exists == 0){
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			}
			$project_id = $this->input->get('id');
			
			$data['current_page'] = 'edit_draft_project';
			########## meta information of post project pag ##########
			$data['project_parent_categories'] = $this->Post_project_model->get_project_parent_categories();
			
			if(!empty($project_id)){
			
				$this->db->select('*');
				$this->db->from('projects_draft');
				$this->db->where('project_id',$project_id);
				$this->db->where('project_owner_id',$user[0]->user_id);
				$project_result = $this->db->get();
				$project_data = $project_result->row_array();
			
				if(!empty($project_data) && $project_data['project_owner_id'] != $user[0]->user_id){
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
			
			
				if(! $this->session->userdata('check_redirection_edit_draft_project')){ // if redirection is not set it will redirect to dashboard page
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
				$this->session->set_userdata ('check_redirection_preview_draft_project', 1); // set redirection for  preview page of draft project so when user refresh the edit draft page he will redirect to dasboard
				$data['project_id'] = $project_id;
				######## fetch project detail from temp_projects table ########
				//$this->Projects_model->check_update_invalid_combination_project_location('projects_draft','project_id',$project_id); // check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
				
				if(empty($project_data)){
					redirect(VPATH.$this->config->item('dashboard_page_url'));
				}
				
				$data['project_data'] = $project_data;
				$count_project_parent_category = $this->db
				->select ('id')
				->from ('categories_projects')
				->where ('parent_id', 0)
				->where ('status', 'Y')
				->get ()->num_rows ();
				$data['count_available_project_parent_category_count'] = 	$count_project_parent_category;
				
				$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
				$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
				########## fetch open bidding project information of logged in user ##########################
				$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
				$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
				
				$user_detail = $this->db->get_where('users_details', array('user_id' => $user[0]->user_id))->row();
				$data['user_details'] = $user_detail;
				
				$user_membership_plan_details = $this->db->get_where('membership_plans', array('id' => $user_detail->current_membership_plan_id))->row();
				$data['user_membership_plan_details'] = $user_membership_plan_details;
				$data['count_user_featured_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id);
				$data['count_user_urgent_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);
				$data['count_user_sealed_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user[0]->user_id);
				$data['count_user_hidden_membership_included_upgrades_monthly'] = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user[0]->user_id);
				
				
				########## fetch the categories of draft project and make the dynamic array start ###
				$this->db->select('*');
				$this->db->from('draft_projects_categories_listing_tracking');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_category_result = $this->db->get();
				$project_category_data = $project_category_result->result_array();
				
				$data['project_category_data'] = $project_category_data;
				
				########## fetch the  project attachments ###
				$data['project_attachment_array'] = $this->get_draft_project_attachments($project_id,$user[0]->profile_name);
			
				########## fetch the tags of draft project ###
				$this->db->select('*');
				$this->db->from('draft_projects_tags');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_tag_result = $this->db->get();
				$project_tag_data = $project_tag_result->result_array();
				
				$data['project_id'] = $project_id;
				$data['project_tag_data'] = $project_tag_data;
				
				########## fetch the temp project tags end ###
				$data['fixed_budget_projects_budget_range'] = $this->Post_project_model->get_fixed_budget_projects_budget_range();// drop down options for fixed budget project budget range
			
				$data['hourly_rate_based_budget_projects_budget_range'] = $this->Post_project_model->get_hourly_rate_based_projects_budget_range();// drop down options for hourly rate based project budget range
				
				$data['fulltime_project_salary_range'] = $this->Post_project_model->get_fulltime_projects_salaries_range();// drop down options for fulltime project salary range
				
				$data['counties'] = $this->Dashboard_model->get_counties(); // drop down options of counties
				$data['localities'] = $this->Dashboard_model->get_localities_selected_county($project_data['county_id']);// drop down options of localities
				$data['postal_codes'] = $this->Post_project_model->get_project_post_codes($project_data['locality_id']);// drop down options of localities
				########## fetch project information from temp_projects table with refrences table end #########
				
				########## fetch open bidding project information of logged in user ##########################
				$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
				################## get the user_details #################
				//$data['user_details'] = $this->db->get_where('user_details', array('user_id' => $user[0]->user_id))->row();
				###############################################
				
				$count_project_categories = $this->db // count the number of categories of draft project
					->select ('id')
					->from ('draft_projects_categories_listing_tracking')
					->where('project_id',$project_id)
					->get ()->num_rows ();
					
				$count_project_attachments = $this->db // count the number of attachment of draft project
					->select ('id')
					->from ('draft_projects_attachments')
					->where('project_id',$project_id)
					->get ()->num_rows ();
					
				$count_project_tags = $this->db // count the number of tags of draft project
					->select ('id')
					->from ('draft_projects_tags')
					->where('project_id',$project_id)
					->get ()->num_rows ();	
					
					$count_project_postal_codes = $this->db
				->select ('id')
				->from ('postal_codes')
				->where ('locality_id', $project_data['locality_id'])
				->get ()->num_rows ();

				$data['count_project_categories'] = $count_project_categories;
				$data['count_project_attachments'] = $count_project_attachments;
				$data['count_project_tags'] = $count_project_tags;
				$data['count_project_postal_codes'] = $count_project_postal_codes;
				########## meta information of edir draft project pag ##########
				
				$edit_draft_project_title_meta_tag = strip_tags($project_data['project_title']);
				$edit_draft_project_title_meta_tag = get_correct_string_based_on_limit($edit_draft_project_title_meta_tag, $this->config->item('project_title_meta_tag_character_limit'));
				$edit_draft_project_preview_description_meta_tag = strip_tags($project_data['project_description']);
				$edit_draft_project_preview_description_meta_tag = get_correct_string_based_on_limit($edit_draft_project_preview_description_meta_tag, $this->config->item('project_description_meta_description_character_limit'));
				
				$this->session->unset_userdata ('check_redirection_edit_preview');
				$data['meta_tag'] = '<title>' . $edit_draft_project_title_meta_tag . '</title><meta name="description" content="' . $edit_draft_project_preview_description_meta_tag . '"/>';
				$this->session->unset_userdata ('check_redirection_edit_draft_project'); // unset the redirection so it prevent the page direct hit from browser
				$this->layout->view('edit_draft_project', '', $data, 'normal');
			}else{
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			}
		}else{
			redirect (VPATH);
		}
    }
	
	
	
	
	
	/**
	*	This function is used to update the draft project by login user.
	**/
	public function update_draft_project() {
	
		if($this->input->is_ajax_request ()){
			if ($this->input->post () )
			{
				if(check_session_validity()){ 
				
					
					$project_id = $this->input->post ('project_id');
					$user = $this->session->userdata('user');
					$count_draft_project = $this->db // count the number of record in projects_draft table
					->select ('id')
					->from ('projects_draft')
					->where('project_id',$project_id)
					->where('project_owner_id',$user[0]->user_id)
					->get ()->num_rows ();
					if($count_draft_project > 0){
						if(!empty($this->input->get('preview_draft'))) {
							$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
							if($check_project_categories_available_or_not == 0){
								echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
								die;
							}
						}
						$post_data = $this->input->post ();
						$response = $this->Post_project_model->post_project_validation($post_data);
						if($response['status'] == 'SUCCESS'){
						
							$this->db->select('current_membership_plan_id');
							$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
							
							if(empty($this->input->get('preview_draft'))) {
								if($user_detail['current_membership_plan_id'] == 1){
									$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
									$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

									$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
									$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
								}
								if($user_detail['current_membership_plan_id'] == 4){
									$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
									$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

									$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
									$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
								}
								// standard project availability
								$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
								$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
								$standard_valid_time_arr = array_filter($standard_check_valid_arr);

								if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){ 
									$draft_cnt = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
									$open_project_cnt = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
									if($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr))){
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_draft_project_project_posting_disabled_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_draft_project_project_posting_disabled_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									} else if(($open_project_cnt >= $user_memebership_max_number_of_open_projects) && ($draft_cnt >= $user_memebership_max_number_of_draft_projects) ){ 
										if($user_detail['current_membership_plan_id'] == 1){
											//$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_project_as_draft_slots_not_available_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											//$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_project_as_draft_slots_not_available_message');
										}
										echo json_encode(['status' => 400, 'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									}
									
								} else {
									$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
									$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
									if($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr))){	
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_save_draft_fulltime_project_project_posting_disabled_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_save_draft_fulltime_project_project_posting_disabled_message');
										}
										echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									} else if(($fulltime_open_project_cnt >= $user_memebership_max_number_of_open_fulltime_projects) && ($fulltime_draft_cnt >= $user_memebership_max_number_of_draft_fulltime_projects) ){ 
										if($user_detail['current_membership_plan_id'] == 1){
											$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
										}
										if($user_detail['current_membership_plan_id'] == 4){
											$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
										}
										echo json_encode(['status' => 400, 'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
										die;
									}
								}
							}
							
						
							$project_locality_id = 0;$project_county_id = 0;$escrow_payment_method = 'N';
							$offline_payment_method = 'N';$upgrade_type_featured = 'N';$upgrade_type_urgent = 'N';
							$upgrade_type_sealed = 'N';$upgrade_type_hidden = 'N';$min_budget = 0;$max_budget = 0;
							$postal_code_id = 0;$not_sure_dropdown_option_selected = 'N';
							$confidential_dropdown_option_selected = 'N';$project_type = "fixed";
							if($this->input->post('escrow_payment_method') == 'Y'){
								$escrow_payment_method = $this->input->post('escrow_payment_method');
							}
							
							if($this->input->post ('offline_payment_method') == 'Y'){
								$offline_payment_method = $this->input->post ('offline_payment_method');
							}
							
							if(!empty($this->input->post ('project_budget'))){
								$project_budget = $this->input->post ('project_budget');
								if($project_budget == 'confidential_dropdown_option_selected'){
									$confidential_dropdown_option_selected = 'Y';
								}else if($project_budget == 'not_sure_dropdown_option_selected'){
									$not_sure_dropdown_option_selected = 'Y';
								}else{
									$project_budget_array = explode("_",$this->input->post ('project_budget'));
									$min_budget = $project_budget_array[0];
									$max_budget = $project_budget_array[1]; 
								}
							}
							//if(!empty($this->input->post ('location_option'))){
								if(!empty($this->input->post ('project_locality_id'))){
								$project_locality_id = $this->input->post ('project_locality_id');
								}if(!empty($this->input->post ('project_county_id'))){
									$project_county_id = $this->input->post ('project_county_id');
								}
							
							//}
							if(!empty($this->input->post ('upgrade_type_featured'))){
								$upgrade_type_featured = 'Y';
							}
							if(!empty($this->input->post ('upgrade_type_hidden'))){
								$upgrade_type_hidden = 'Y';
							}
							if(!empty($this->input->post ('upgrade_type_sealed'))){
								$upgrade_type_sealed = 'Y';
							}
							if(!empty($this->input->post ('upgrade_type_urgent'))){
								$upgrade_type_urgent = 'Y';
							}
							if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id'))){
							
								$postal_code_id = $this->input->post('project_postal_code_id');
								
							}
							if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
								$project_type = $this->input->post('project_type');
								
							
							}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
								$project_type = 'fulltime';
							}
							$draft_project_data = array (
								'project_owner_id'=>$user[0]->user_id,
								'project_save_date'=>date('Y-m-d H:i:s'),
								'project_title'=>trim($this->input->post('project_title')),
								'project_description'=>trim($this->input->post('project_description')),
								'locality_id'=>$project_locality_id,
								'county_id'=>$project_county_id,
								'postal_code_id'=>$postal_code_id,
								'project_type'=>$project_type,
								'min_budget'=>$min_budget,
								'max_budget'=>$max_budget,
								'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
								'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
								'escrow_payment_method'=>$escrow_payment_method,
								'offline_payment_method'=>$offline_payment_method,
								'featured'=>$upgrade_type_featured,
								'urgent'=>$upgrade_type_urgent,
								'sealed'=>$upgrade_type_sealed,
								'hidden'=>$upgrade_type_hidden
								   
							);
							$this->db->where ('project_id', $project_id);
							$this->db->update ('projects_draft', $draft_project_data); // save data in projects_draft table from edit draft form
							
							$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
							$this->db->delete('draft_projects_categories_listing_tracking', array('project_id' => $project_id));
							
							
							foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
							
								$project_category_id = 0;
								$project_parent_category_id = 0;
								if(!empty($project_category_value['project_parent_category'])){
									if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
									{
									
										$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

										$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);	
										
										if($check_project_parent_category_exist){
											if($check_project_child_category_exist){
												
												$project_category_id = $project_category_value['project_child_category'];
												$project_parent_category_id = $project_category_value['project_parent_category'];
											
											}else{
											
												$project_category_id =  $project_category_value['project_parent_category'];
												$project_parent_category_id = 0;
												
											}
										}
								
									
									}else{
									
										$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
										if($check_project_parent_category_exist){
											
											$project_category_id =  $project_category_value['project_parent_category'];
											$project_parent_category_id = 0;
										
										}
									}
									
								}
								
								if(!empty($project_category_id) || !empty($project_parent_category_id)){
									$this->db->insert ('draft_projects_categories_listing_tracking', array(
										'project_id' => $project_id,
										'draft_project_category_id' => $project_category_id,
										'draft_project_parent_category_id' => $project_parent_category_id 
									));
								}
							}
						  
							if(!empty($this->input->post('project_tag'))){
								foreach($this->input->post('project_tag') as $project_tag_key){
									if(!empty($project_tag_key['tag_name'])){
										$this->db->insert ('draft_projects_tags', array('project_id' => $project_id,
										'draft_project_tag_name' => trim($project_tag_key['tag_name'])));
										// save data in draft_projects_tags table from post project form
									}
								}	
							}
							// check attachment is valid on disk as well as on db table
							$this->get_draft_project_attachments($project_id, $user[0]->profile_name);
							$msg['status'] = 'SUCCESS';
							$msg['message'] = '';
							echo json_encode ($msg);
						
						}else{
						
							echo json_encode ($response);
						}
					}else{
						$msg['status'] = 400;
						$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
						echo json_encode ($msg);
						die;
					}
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
			
				}	
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
		
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	/**
	 * This function is used to show the preview of draft project .
	*/
	public function preview_draft_project(){
		if(check_session_validity()){ 
			if(empty($this->input->get('id'))){
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$project_id = $this->input->get('id');
			$user = $this->session->userdata('user');
			$count_project = $this->db // count the number of record in projects_draft table
					->select ('id')
					->from ('projects_draft')
					->where('project_id',$project_id)
					->where('project_owner_id',$user[0]->user_id)
					->get ()->num_rows ();
			
			if($count_project > 0){
				$this->session->set_userdata ('check_redirection_edit_draft_project', 1); // set redirection for edit draft page so when user refresh the edit draft page he will redirect to dasboard
				if(! $this->session->userdata('check_redirection_preview_draft_project')){ // if redirection is not set it will redirect to dashboard page
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
				// set redirection for edit draft page so when user refresh the edit draft page he will redirect to dasboard
				########## fetch the project data from temporary tables ###
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
				$data['user_detail'] = $user_detail;
				$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
				$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
				########## fetch open bidding project information of logged in user ##########################
				$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
				$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
				
				
				
				$this->db->select('projects_draft.*,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
				$this->db->from('projects_draft');
				$this->db->join('counties', 'counties.id = projects_draft.county_id', 'left');
				$this->db->join('localities', 'localities.id = projects_draft.locality_id', 'left');
				$this->db->join('postal_codes', 'postal_codes.id = projects_draft.postal_code_id', 'left');
				$this->db->where('projects_draft.project_id',$project_id);
				$this->db->where('projects_draft.project_owner_id',$user[0]->user_id);
				$project_result = $this->db->get();
				$project_data = $project_result->row_array();
				
				$data['project_id'] = $project_id;
				$data['project_data'] = $project_data;

				
				$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
				
				// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
				$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id); // count user membership featured  upgrade

				$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership urgent upgrade
				
				########## fetch the login user data ###
				$this->db->select('account_type,is_authorized_physical_person,first_name,last_name,company_name,account_validation_date');
				$this->db->from('users');
				$this->db->where('user_id',$project_data['project_owner_id']);
				$user_result = $this->db->get();
				$user_data = $user_result->result_array();
				//$data['user_data'] = $user_data;
				########## fetch the draft project tags ###
				$this->db->select('draft_projects_tags.draft_project_tag_name');
				$this->db->from('draft_projects_tags');
				$this->db->where('project_id',$project_id);
				$this->db->order_by('id',"asc");
				$project_tag_result = $this->db->get();
				$project_tag_data = $project_tag_result->result_array();
				$data['project_tag_data'] = $project_tag_data;
				$data['project_attachment_data'] = $this->get_draft_project_attachments($project_id,$user[0]->profile_name);
				
				########## fetch the draft project categories and make the dynamic array start ###
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
				$this->db->from('draft_projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.draft_project_category_id', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.draft_project_parent_category_id', 'left');
				$this->db->where('category_tracking.project_id',$project_id);
				$this->db->order_by('category_project.name',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
				if(!empty($category_data)){
					foreach($category_data as $key=>$value){
						if(!empty($value['parent_category_name'])){
							$category_data[$key]['category_order_value'] =  $value['parent_category_name'];
						}else{
							$category_data[$key]['category_order_value'] =  $value['category_name'];
						}
					}
					sortArrayBySpecificKeyValue('category_order_value',$category_data, 'asc');// sort category array by key:category_order_value value
				}
				
				$data['category_data'] = $category_data;
				$data['project_id'] = $project_id;
				$data['current_page'] = 'preview_draft_project';
				
				$draft_project_preview_title_meta_tag = strip_tags($project_data['project_title']);
				$draft_project_preview_title_meta_tag = get_correct_string_based_on_limit($draft_project_preview_title_meta_tag,$this->config->item('project_title_meta_tag_character_limit'));
				$draft_project_preview_description_meta_tag = strip_tags($project_data['project_description']);
				$draft_project_preview_description_meta_tag = get_correct_string_based_on_limit($draft_project_preview_description_meta_tag,$this->config->item('project_description_meta_description_character_limit'));
				
				########## fetch open bidding project information of logged in user ##########################
				$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
				################## get the user_details #################
				$data['user_details'] = $this->db->get_where('users_details', array('user_id' => $user[0]->user_id))->row();
				$data['meta_tag'] = '<title>' . $draft_project_preview_title_meta_tag . '</title><meta name="description" content="' . $draft_project_preview_description_meta_tag . '"/>';
				//$this->layout->view ('project_preview', '', $data, 'include');
				$this->session->unset_userdata ('check_redirection_preview_draft_project'); // unset the redirection so it prevent the page direct hit from browser of preview page of draft project page
				//$this->load->view('projects/preview_draft_project', $data);
				$lay = array();
				$this->layout->view ('projects/preview_draft_project', $lay, $data, 'normal');
				
			
			}else{
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			}
		}else{
			redirect (VPATH);
		
		}
	
	}
	
	/**
	 * This function is used to check posting of particular type of project is valid or not - assets/js/modules/post_projects.js, edit_temporary_project_preview.js, edit_draft_project.js
	*/
	public function ajax_check_project_posting_is_valid() {
		if( $this->input->is_ajax_request ()) {
			if ($this->input->post ()) {
			
				if(!$this->session->userdata('user')){
					$msg['status'] = 201;
					$msg['location'] = '';
					echo json_encode($msg);
					die;
				}
				
				if(check_session_validity()){ 
					$row = $this->input->post();
					$user = $this->session->userdata('user');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					// standard project availability
					$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
					$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
					$standard_valid_time_arr = array_filter($standard_check_valid_arr);
					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					$tab_deselected_status = 1;
					######### Start here ###########
					if(($user_memebership_max_number_of_draft_projects == 0 && $user_memebership_max_number_of_open_projects == 0 && $user_memebership_max_number_of_draft_fulltime_projects == 0 && $user_memebership_max_number_of_open_fulltime_projects == 0) || empty($standard_valid_time_arr)){
						$tab_deselected_status = 1;
						
					}
					$draft_cnt = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
					$open_project_cnt = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
					$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
					$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
					
					if(($open_project_cnt >= $user_memebership_max_number_of_open_projects) && ($draft_cnt >= $user_memebership_max_number_of_draft_projects) && ($fulltime_open_project_cnt >= $user_memebership_max_number_of_open_fulltime_projects) && ($fulltime_draft_cnt >= $user_memebership_max_number_of_draft_fulltime_projects)){
						$tab_deselected_status = 1;
						
					}
					if($row['section_show'] == 1){
						$tab_deselected_status = 0;
					}
					############ End here ###########
					if($row['type'] == 'post_project') {
						$draft_cnt = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
						$open_project_cnt = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
						
						if(($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr)))){
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
							}
							echo json_encode(['status' => 400,'tab_deselected_status'=>$tab_deselected_status ,'type' => $row['type'], 'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						} else if(($open_project_cnt >= $user_memebership_max_number_of_open_projects) && ($draft_cnt >= $user_memebership_max_number_of_draft_projects) ){ 
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
							}
							echo json_encode(['status' => 400,'tab_deselected_status'=>$tab_deselected_status, 'type' => $row['type'], 'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}


					} else {
						$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
						$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
						if(($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr)))){ 
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
							}
							echo json_encode(['status' => 400,'tab_deselected_status'=>$tab_deselected_status, 'type' => $row['type'],'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						} else if(($fulltime_open_project_cnt >= $user_memebership_max_number_of_open_fulltime_projects) && ($fulltime_draft_cnt >= $user_memebership_max_number_of_draft_fulltime_projects) ){ 
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
							}
							echo json_encode(['status' => 400,'tab_deselected_status'=>$tab_deselected_status, 'type' => $row['type'], 'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}

					if($row['type'] == 'post_project') {
						$draft_cnt = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
						$open_bidding_cnt = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
						if(!empty($standard_valid_time_arr) && ($user_memebership_max_number_of_open_projects != '0' && $open_bidding_cnt < $user_memebership_max_number_of_open_projects)){ 
							$res['project_publish_btn_show'] = true;
							$res['disclaimer_show'] = true;
						}
						if(($draft_cnt < $user_memebership_max_number_of_draft_projects && $user_memebership_max_number_of_draft_projects != 0) || ($open_bidding_cnt < $user_memebership_max_number_of_open_projects && (!empty($standard_valid_time_arr) || $user_memebership_max_number_of_open_projects != '0'))) {
							$res['project_preview_btn_show'] = true;
						}
						if($draft_cnt < $user_memebership_max_number_of_draft_projects && $user_memebership_max_number_of_draft_projects != 0){ 
							$res['project_draft_btn_show'] = true;
						}
					} else {
						$fulltime_draft_cnt = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
						$fulltime_open_bidding_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
						if(!empty($standard_valid_time_arr) && ($user_memebership_max_number_of_open_fulltime_projects != '0' && $fulltime_open_bidding_cnt < $user_memebership_max_number_of_open_fulltime_projects)){ 
							$res['project_publish_btn_show'] = true;
							$res['disclaimer_show'] = true;
						}
						if(($fulltime_draft_cnt < $user_memebership_max_number_of_draft_fulltime_projects && $user_memebership_max_number_of_draft_fulltime_projects != 0) || ($fulltime_open_bidding_cnt < $user_memebership_max_number_of_open_fulltime_projects && (!empty($standard_valid_time_arr) || $user_memebership_max_number_of_open_fulltime_projects != '0'))){
							$res['project_preview_btn_show'] = true;
						}
						if($fulltime_draft_cnt < $user_memebership_max_number_of_draft_fulltime_projects && $user_memebership_max_number_of_draft_fulltime_projects != 0){ 
							$res['project_draft_btn_show'] = true;
						}
					}
					$res['status'] = 200;
				} else {
					$msg['status'] = 201;
					$msg['location'] = '';
					echo json_encode($msg);
					die;
				}
			} else {
				show_custom_404_page();
				return;
			}
		} else {
			show_custom_404_page();
			return;
		}
		echo json_encode($res);
		return;
	}
	/**
	*	This function is used to move project from draft to waiting moderations status  by login user.
	**/
	public function publish_draft_project() {
		if( $this->input->is_ajax_request ()){
			if ($this->input->post ())
			{
				if(check_session_validity()){ // check session exists or not if exist then it will update user session
					
					$project_id = $this->input->post ('project_id');
					$page_type = $this->input->post ('page_type');
					$user = $this->session->userdata('user');
					$page_type_array = array('preview','form');
					$check_project_exists = $this->db // count the number of record in temp_projects table
					->select ('id')
					->from ('projects_draft')
					->where('project_id',$project_id)
					->where('project_owner_id', $user[0]->user_id)
					->get ()->num_rows ();
					if($check_project_exists > 0 )
					{
						$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
						if($check_project_categories_available_or_not == 0){
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
							die;
						}
						
						if (in_array($page_type, $page_type_array))
						{
						
							$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
							if($user_detail['current_membership_plan_id'] == 1){
								$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
								$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

								$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
								$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
								$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

								$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
								$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
							}
							// standard project availability
							$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
							$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
							$standard_valid_time_arr = array_filter($standard_check_valid_arr);
						
							
							######### apply validation for open projects ######
							$user_total_open_projects_count = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
							$user_total_open_fulltime_projects_count = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
							
							
							$count_awaiting_moderation_project = $this->db // count the number of record in temp_projects table
							->select ('id')
							->from ('projects_awaiting_moderation')
							->where('project_id',$project_id)
							->where('project_owner_id',$user[0]->user_id)
							->get ()->num_rows ();
							
							$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
							if($count_awaiting_moderation_project == 0) { 
								
								if($page_type == 'preview') { // this block will execute when user click "publish project" button on draft preview page
									
									$this->db->select('*');
									$this->db->from('projects_draft');
									$this->db->where('projects_draft.project_id',$project_id);
									$this->db->where('projects_draft.project_owner_id',$user[0]->user_id);
									$project_result = $this->db->get();
									$project_data = $project_result->result_array();

									if($project_data[0]['project_type'] != 'fulltime') { 
										if(($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr)))){ 
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
											}
											echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
											die;
										} else if($user_total_open_projects_count >= $user_memebership_max_number_of_open_projects ){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
											}
											echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
											die;
										}
									} else {
										if(($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr)))){ 
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
											}
											echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
											die;
										} else if($user_total_open_fulltime_projects_count >= $user_memebership_max_number_of_open_fulltime_projects ){
											if($user_detail['current_membership_plan_id'] == 1){
												$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
											}
											if($user_detail['current_membership_plan_id'] == 4){
												$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
											}
											echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
											die;
										}
									}
									// move data from project drafts table to projects_awaiting_moderation table
									$awaiting_moderation_project_data = array (
										'project_id'=>$project_id,
										'project_owner_id'=>$user[0]->user_id,
										'project_submission_to_moderation_date'=>date('Y-m-d H:i:s'),
										'project_title'=>trim($project_data[0]['project_title']),
										'project_description'=>trim($project_data[0]['project_description']),
										'locality_id'=>$project_data[0]['locality_id'],
										'county_id'=>$project_data[0]['county_id'],
										'postal_code_id'=>$project_data[0]['postal_code_id'],
										'project_type'=>$project_data[0]['project_type'],
										'min_budget'=>$project_data[0]['min_budget'],
										'max_budget'=>$project_data[0]['max_budget'],
										'confidential_dropdown_option_selected'=>$project_data[0]['confidential_dropdown_option_selected'],
										'not_sure_dropdown_option_selected'=>$project_data[0]['not_sure_dropdown_option_selected'],
										'escrow_payment_method'=>$project_data[0]['escrow_payment_method'],
										'offline_payment_method'=>$project_data[0]['offline_payment_method'],
										'featured'=>$project_data[0]['featured'],
										'urgent'=>$project_data[0]['urgent'],
										'sealed'=>$project_data[0]['sealed'],
										'hidden'=>$project_data[0]['hidden']
									
									);
									
									$this->db->select('draft_project_category_id,draft_project_parent_category_id');
									$this->db->from('draft_projects_categories_listing_tracking');
									$this->db->where('project_id',$project_id);
									$this->db->order_by('id',"asc");
									$project_category_result = $this->db->get();
									$project_category_data = $project_category_result->result_array();
									
									###################### check that user selected category is valid or not(admin deactive/delete the category)
									$check_project_parent_category_status = false;
									if(!empty($project_category_data)){
										foreach($project_category_data as $category_key => $category_value){
											$project_parent_category_id = 0;
											if(!empty($category_value['draft_project_parent_category_id'])){
												$project_parent_category_id = $category_value['draft_project_parent_category_id'];
											
											}else{
												$project_parent_category_id = $category_value['draft_project_category_id'];
											
											}
											$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_parent_category_id);
											if($check_project_parent_category_exist){
												$check_project_parent_category_status = true;
												break;
											}
											
										}
									}
									if(!$check_project_parent_category_status){
										$res = [
											'status' => 400,
											'error' => $this->config->item('post_project_valid_category_not_existent_popup_message'),
											'popup_heading'=>$this->config->item('popup_alert_heading'),
											'location'=>''
										];
										echo json_encode($res);
										die;
									}
									
									// @sid code to set auto approval date into projects_awaiting_moderation
									$auto_approval_flag = false; // This flag is used to identify where the entry of temporary project table move either [awaiting moderation / open for bidding]
									
									// @sid this code block is used to update user account balance based on project upgrade 
									
									
									// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
									$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id); // count user membership featured  upgrade
					
									$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership urgent upgrade
									
									$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership sealed upgrade
									
									$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership hidden upgrade
									
									
									
									$upgraded_project_price = 0;
									if($project_data[0]['featured'] == 'Y'){
										if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1' && $count_user_featured_membership_included_upgrades_monthly >=$user_membership_plan_detail['included_number_featured_upgrades']){
											$upgraded_project_price += $this->config->item('project_upgrade_price_featured');
										}
									}
									if($project_data[0]['urgent'] == 'Y'){
										if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1' && $count_user_urgent_membership_included_upgrades_monthly >=$user_membership_plan_detail['included_number_urgent_upgrades']){
											$upgraded_project_price += $this->config->item('project_upgrade_price_urgent');
										}
									}
									if($project_data[0]['sealed'] == 'Y'){
									
										if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1' && $count_user_sealed_membership_included_upgrades_monthly >=$user_membership_plan_detail['included_number_sealed_upgrades']){
											$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
										}
									
									
										//$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
									}
									if($project_data[0]['hidden'] == 'Y'){
										//$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
										
										if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1' && $count_user_hidden_membership_included_upgrades_monthly >=$user_membership_plan_detail['included_number_hidden_upgrades']){
											$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
										}
										
									}
									if(floatval($upgraded_project_price) > 0){
										$total_user_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'] + $user_detail['user_account_balance'];
										if(floatval($upgraded_project_price) > floatval($total_user_balance) ){
										
											if(($project_data[0]['featured'] == 'Y' && $project_data[0]['urgent'] == 'Y') || ($project_data[0]['featured'] == 'Y' && $project_data[0]['urgent'] == 'Y' && $project_data[0]['sealed'] == 'Y') || ($project_data[0]['featured'] == 'Y' && $project_data[0]['sealed'] == 'Y') || ($project_data[0]['urgent'] == 'Y' && $project_data[0]['sealed'] == 'Y')|| ($project_data[0]['sealed'] == 'Y' && $project_data[0]['hidden'] == 'Y')){
												$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_plural');
											}else{
												$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_singular');
											}
										
											$res = array(
													'status' => 400,
													'location' => '',
													'error' => $error_msg, // define in post_project_custom config
													'popup_heading'=>$this->config->item('popup_alert_heading')
												);
											echo json_encode($res);
											die;
										}
									}
									if($project_data[0]['featured'] == 'Y') {
										$project_upgrade_array['featured'] = 'Y';
									}
									if($project_data[0]['urgent'] == 'Y') {
										$project_upgrade_array['urgent'] = 'Y';
									}
									if($project_data[0]['sealed'] == 'Y') {
										$project_upgrade_array['sealed'] = 'Y';
									}
									if($project_data[0]['hidden'] == 'Y') {
										$project_upgrade_array['hidden'] = 'Y';
									}
									
									$auto_approve_min = get_project_auto_approve_min_time($project_upgrade_array,$user_detail['current_membership_plan_id']);
									if($auto_approve_min != 0) {
										$awaiting_moderation_project_data['auto_approval_date'] = date('Y-m-d H:i:s',$auto_approve_min);
									} else {
										$auto_approval_flag = true;
									}
									if($auto_approval_flag) {
										
										// $time_arr = explode(':', $this->config->item('standard_project_availability'));
										// $project_expire_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
										$project_expire_date = $this->Post_project_model->get_project_correct_expiration_date($project_data[0]);
										$open_for_bidding_project_data = [
											'project_id'=>$project_id,
											'project_owner_id'=>$user[0]->user_id,
											'project_posting_date' => date('Y-m-d H:i:s'),
											'project_expiration_date' => $project_expire_date,
											'project_title'=>trim($project_data[0]['project_title']),
											'project_description'=>trim($project_data[0]['project_description']),
											'locality_id'=>$project_data[0]['locality_id'],
											'county_id'=>$project_data[0]['county_id'],
											'postal_code_id' => $project_data[0]['postal_code_id'],
											'project_type' => $project_data[0]['project_type'],
											'min_budget'=>$project_data[0]['min_budget'],
											'max_budget'=>$project_data[0]['max_budget'],
											'confidential_dropdown_option_selected'=>$project_data[0]['confidential_dropdown_option_selected'],
											'not_sure_dropdown_option_selected'=>$project_data[0]['not_sure_dropdown_option_selected'],
											'escrow_payment_method'=>$project_data[0]['escrow_payment_method'],
											'offline_payment_method'=>$project_data[0]['offline_payment_method'],
											'featured'=>$project_data[0]['featured'],
											'urgent'=>$project_data[0]['urgent'],
											'sealed'=>$project_data[0]['sealed'],
											'hidden'=>$project_data[0]['hidden'],
											'views' => 0,
											'revisions'=> 0
										];
										$this->db->insert ('projects_open_bidding', $open_for_bidding_project_data); // save data in fixed_budget_projects_open_bidding table from post project form

										$publish_project = [
											'project_id' => $project_id,
											'project_owner_id' => $user[0]->user_id,
											'project_posting_date' => date('Y-m-d H:i:s'),
											'project_title' => trim($project_data[0]['project_title']),
											'project_type' => $project_data[0]['project_type']
										];
										$this->db->insert('users_alltime_published_projects_tracking', $publish_project);

										######### save use activity log message
										$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
										$project_title = trim($open_for_bidding_project_data['project_title']);
										if($open_for_bidding_project_data['project_type'] == 'fulltime'){
											$publish_project_log_message = $this->config->item('post_fulltime_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
										}else{
											$publish_project_log_message = $this->config->item('post_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
										}
										$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title, ENT_QUOTES)),$publish_project_log_message);
										user_display_log($publish_project_log_message);
										
										// insert the data regrading purchase tracking and refresh sequence and manage the user bonus balance and account balance
										$this->Post_project_model->user_project_upgrade_purchase_refresh_sequence_tracking_save($open_for_bidding_project_data,$user[0]->user_id); 
										
										// move data from draft_projects_tags table to awaiting_moderation_projects_tags table
										$this->db->select('draft_project_tag_name');
										$this->db->from('draft_projects_tags');
										$this->db->where('project_id',$project_id);
										$this->db->order_by('id',"asc");
										$project_tag_result = $this->db->get();
										$project_tag_data = $project_tag_result->result_array();
										if(!empty($project_tag_data)){
											foreach($project_tag_data as $tag_key => $tag_value){
												$this->db->insert ('projects_tags', array('project_id'=>$project_id,'project_tag_name'=> trim($tag_value['draft_project_tag_name'])));
											}
										
										}
										// move data from draft_projects_categories_listing_tracking table to projects_categories_listing_tracking table
										$parent_categories_id = [];
										if(!empty($project_category_data)){
											foreach($project_category_data as $category_key => $category_value){
												$project_category_id = 0;
												$project_parent_category_id = 0;
												if(!empty($category_value['draft_project_parent_category_id'])){
												
													$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['draft_project_parent_category_id']);
					
													$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($category_value['draft_project_parent_category_id'],$category_value['draft_project_category_id']);
													if($check_project_parent_category_exist){
														if($check_project_child_category_exist){
															
															$project_category_id = $category_value['draft_project_category_id'];
															$project_parent_category_id = $category_value['draft_project_parent_category_id'];
														
														}else{
														
														$project_category_id =  $category_value['draft_project_parent_category_id'];
															$project_parent_category_id = 0;
															
														}
													}
												
												}else{
													$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['draft_project_category_id']);
													if($check_project_parent_category_exist){
													
														$project_category_id =  $category_value['draft_project_category_id'];
														$project_parent_category_id = 0;
													
													}
												
												}
											
												if(!empty($project_category_id) || !empty($project_parent_category_id)){
													$this->db->insert ('projects_categories_listing_tracking', array('project_id'=>$project_id,'project_category_id'=> $project_category_id,'project_parent_category_id'=>$project_parent_category_id));
													if($project_parent_category_id == 0) {
														array_push($parent_categories_id, $project_category_id);
													} else {
														array_push($parent_categories_id, $project_parent_category_id);
													}
												}
											}
										
										}
										// move data from draft_projects_attachments table to awaiting_moderation_projects_attachments table
										$this->db->select('draft_project_attachment_name');
										$this->db->from('draft_projects_attachments');
										$this->db->where('project_id',$project_id);
										$this->db->order_by('id',"asc");
										$project_attachment_result = $this->db->get();
										$project_attachment_data = $project_attachment_result->result_array();
										// connect ftp for moving attachments
										$this->load->library('ftp');
										$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
										$config['ftp_username'] = FTP_USERNAME;
										$config['ftp_password'] = FTP_PASSWORD;
										$config['ftp_port'] 	= FTP_PORT;
										$config['debug']    = TRUE;
										$this->ftp->connect($config); 
										$users_ftp_dir 	= USERS_FTP_DIR; 
										$projects_ftp_dir = PROJECTS_FTP_DIR;
										$project_draft_dir = PROJECT_DRAFT_DIR;
										$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
										$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
										$profile_folder = $user[0]->profile_name;
										
										
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
										
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
										
										
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create awaiting_moderation directory in projects folder
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id , 0777); // create the directory by using  project id
										if(!empty($project_attachment_data)){
										
										
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir);
										
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
											foreach($project_attachment_data as $attachment_key => $attachment_value){
											
												if(!empty($attachment_value['draft_project_attachment_name'])){
													$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir .$attachment_value['draft_project_attachment_name'];
													$file_size = $this->ftp->get_filesize($source_path);
													if($file_size != '-1'){
														$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.$attachment_value['draft_project_attachment_name'];
														$this->ftp->move($source_path, $destination_path);
														$this->db->insert ('projects_attachments', array('project_id'=>$project_id,'project_attachment_name'=> $attachment_value['draft_project_attachment_name']));
													}
												}
											}
											$this->db->delete('draft_projects_attachments', array('project_id' => $project_id));
											$draft_project_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);
										}
										if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id))){
											$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.DIRECTORY_SEPARATOR);// delete project directory 
										}
										$this->ftp->close();

										if(!empty($open_for_bidding_project_data['hidden']) && $open_for_bidding_project_data['hidden'] == 'N') {
											$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
											$check_valid_arr = array_map('getInt', $time_arr); 
											$valid_time_arr = array_filter($check_valid_arr);
											$next_refresh_date = null;
											if(!empty($valid_time_arr)) {
												$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
												$refresh_sequence_data = [
													'project_id' => $project_id,
													'project_last_refresh_time' => null,
													'project_next_refresh_time' => $next_refresh_date,
													'project_upgrade_refresh_sequence_standard'=>$this->config->item('standard_project_refresh_sequence')
												];
												$standard_refresh_sequence_cnt = $this->db->from('standard_projects_refresh_sequence_tracking')->where(['project_id' => $project_id])->count_all_results();
												if($standard_refresh_sequence_cnt == 0) {
													$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
												}
											}

											// insert data in projects latest project refresh sequence tracking
											$latest_project_refresh_tracking = [
												'project_id' => $project_id,
												'project_last_refresh_time' => date('Y-m-d H:i:s'),
												'project_next_refresh_time' => $next_refresh_date
											];
											$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);

											// save data into users newly posted projects sent notification tracking table
											$category_mapping_data = $this->Post_project_model->get_projects_professionals_categories_mapping_data_based_on_categories_id($parent_categories_id);
											if(!empty($category_mapping_data)) {
												$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_for_bidding_project_data);
											}
											// save data into users newly posted projects sent notification tracking table when project posted by favorite employer
											$users = $this->Post_project_model->get_users_id_from_favorite_employer_id($open_for_bidding_project_data['project_owner_id']);
											if(!empty($users)) {
												$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_for_bidding_project_data);
											}
										}
										
										
										// trigger socket event to update latest project section on user dashboard
										$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateLatestProjectOnUserDashboard/".$user[0]->user_id.'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
										$options = array(
											CURLOPT_RETURNTRANSFER => true,
											CURLOPT_SSL_VERIFYPEER => false
										);
										try {
											$ch = curl_init( $url );
											curl_setopt_array( $ch, $options );
											curl_exec( $ch );
											curl_close( $ch );
										} catch(Exception $e) {
										}
										
									} else {
										$this->db->insert ('projects_awaiting_moderation', $awaiting_moderation_project_data);
										// move data from draft_projects_tags table to awaiting_moderation_projects_tags table
										
										######### save use activity log message
										$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$awaiting_moderation_project_data['project_id'];
										$project_title = trim($awaiting_moderation_project_data['project_title']);
										if($awaiting_moderation_project_data['project_type'] == 'fulltime'){
											$publish_project_log_message = $this->config->item('fulltime_project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
										}else{
											$publish_project_log_message = $this->config->item('project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
										}
										$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title, ENT_QUOTES)),$publish_project_log_message);
										user_display_log($publish_project_log_message);
										
										// insert the data regrading purchase tracking and manage the user bonus balance and account balance
										$this->Post_project_model->user_project_upgrade_purchase_tracking_save($awaiting_moderation_project_data,$user[0]->user_id); 
										$this->db->select('draft_project_tag_name');
										$this->db->from('draft_projects_tags');
										$this->db->where('project_id',$project_id);
										$this->db->order_by('id',"asc");
										$project_tag_result = $this->db->get();
										$project_tag_data = $project_tag_result->result_array();
										if(!empty($project_tag_data)){
											foreach($project_tag_data as $tag_key => $tag_value){
												$this->db->insert ('awaiting_moderation_projects_tags', array('project_id'=>$project_id,'awaiting_moderation_project_tag_name'=> $tag_value['draft_project_tag_name']));
											}
										
										}
										// move data from draft_projects_categories_listing_tracking table to awaiting_moderation_projects_categories_listing_tracking table and check that category is valid or not
										if(!empty($project_category_data)){
											foreach($project_category_data as $category_key => $category_value){
												$project_category_id = 0;
												$project_parent_category_id = 0;
												if(!empty($category_value['draft_project_parent_category_id'])){
												
													$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['draft_project_parent_category_id']);
					
													$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($category_value['draft_project_parent_category_id'],$category_value['draft_project_category_id']);
													if($check_project_parent_category_exist){
														if($check_project_child_category_exist){
															
															$project_category_id = $category_value['draft_project_category_id'];
															$project_parent_category_id = $category_value['draft_project_parent_category_id'];
															
														}else{
														
															$project_category_id =  $category_value['draft_project_parent_category_id'];
															$project_parent_category_id = 0;
															
														}
													}
												
												}else{
													$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($category_value['draft_project_category_id']);
													if($check_project_parent_category_exist){
													
														$project_category_id =  $category_value['draft_project_category_id'];
														$project_parent_category_id = 0;
													
													}
												
												}
											
												if(!empty($project_category_id) || !empty($project_parent_category_id)){
												$this->db->insert ('awaiting_moderation_projects_categories_listing_tracking', array('project_id'=>$project_id,'awaiting_moderation_project_category_id'=> $project_category_id,'awaiting_moderation_project_parent_category_id'=>$project_parent_category_id));
												
												}
											}
										
										}
										
										
										// move data from draft_projects_attachments table to awaiting_moderation_projects_attachments table
										$this->db->select('draft_project_attachment_name');
										$this->db->from('draft_projects_attachments');
										$this->db->where('project_id',$project_id);
										$this->db->order_by('id',"asc");
										$project_attachment_result = $this->db->get();
										$project_attachment_data = $project_attachment_result->result_array();
										$this->load->library('ftp');
										$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
										$config['ftp_username'] = FTP_USERNAME;
										$config['ftp_password'] = FTP_PASSWORD;
										$config['ftp_port'] 	= FTP_PORT;
										$config['debug']    = TRUE;
										$this->ftp->connect($config); 
										$users_ftp_dir 	= USERS_FTP_DIR; 
										$projects_ftp_dir = PROJECTS_FTP_DIR;
										$project_draft_dir = PROJECT_DRAFT_DIR;
										$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
										$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
										$profile_folder     = $user[0]->profile_name;
										
										
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
										
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.DIRECTORY_SEPARATOR);
										$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir);
										
										
										
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir, 0777);// create awaiting_moderation directory in projects folder
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using  project id
										//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir , 0777); // create the directory by using  project id
										if(!empty($project_attachment_data)){
											foreach($project_attachment_data as $attachment_key => $attachment_value){
											
												if(!empty($attachment_value['draft_project_attachment_name'])){
													$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir .$attachment_value['draft_project_attachment_name'];
													$file_size = $this->ftp->get_filesize($source_path);
													if($file_size != '-1'){
														$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir .$attachment_value['draft_project_attachment_name'];
														$this->ftp->move($source_path, $destination_path);
														$this->db->insert ('awaiting_moderation_projects_attachments', array('project_id'=>$project_id,'awaiting_moderation_project_attachment_name'=> $attachment_value['draft_project_attachment_name']));
													}
												}
											}
											$this->db->delete('draft_projects_attachments', array('project_id' => $project_id));
											//$draft_project_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);
											
										}
										if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id))){
											$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);// delete project directory 

										}
										$this->ftp->close();

										// trigger socket event to update latest project section on user dashboard
										$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$user[0]->user_id.'/awaiting_moderation/""?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
										$options = array(
											CURLOPT_RETURNTRANSFER => true,
											CURLOPT_SSL_VERIFYPEER => false
										);
										try {
											$ch = curl_init( $url );
											curl_setopt_array( $ch, $options );
											curl_exec( $ch );
											curl_close( $ch );
										} catch(Exception $e) {
										}		
									}
									
									########## Delete the record  from fixed budget draft table 
									$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
									$this->db->delete('draft_projects_categories_listing_tracking', array('project_id' => $project_id));
									$this->db->delete('projects_draft', array('project_id' => $project_id));
									
			
									$msg['status'] = 'SUCCESS';
									$msg['message'] = '';
									echo json_encode ($msg);
							
								} else if($page_type == 'form') { // this block will execute when user click "publish project" button on edit draft page
									
									$post_data = $this->input->post ();
									$response = $this->Post_project_model->post_project_validation($post_data);
									
									if($response['status'] == 'SUCCESS'){
									
										if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
											if(($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr))) || ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr))){ 
												if($user_detail['current_membership_plan_id'] == 1){
													$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
												}
												if($user_detail['current_membership_plan_id'] == 4){
													$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
												}
												echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
												die;
											} else if($user_total_open_projects_count >= $user_memebership_max_number_of_open_projects ){
												if($user_detail['current_membership_plan_id'] == 1){
													$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
												}
												if($user_detail['current_membership_plan_id'] == 4){
													$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
												}
												echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
												die;
											}
										} else {
											if(($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr))) || ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr))){ 
												if($user_detail['current_membership_plan_id'] == 1){
													$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
												}
												if($user_detail['current_membership_plan_id'] == 4){
													$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
												}
												echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
												die;
											} else if($user_total_open_fulltime_projects_count >= $user_memebership_max_number_of_open_fulltime_projects ){
												if($user_detail['current_membership_plan_id'] == 1){
													$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
												}
												if($user_detail['current_membership_plan_id'] == 4){
													$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
												}
												echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
												die;
											}
										}

										
										###################### check that user selected category is valid or not(admin deactive/delete the category)
										$check_project_parent_category_status = false;
										if($this->input->post('project_category')){
											foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
												if(isset($project_category_value['project_parent_category']) && !empty($project_category_value['project_parent_category'])){
													$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
													if($check_project_parent_category_exist){
														$check_project_parent_category_status = true;
														break;
													}
												}
											}
										
										}
										if(!$check_project_parent_category_status){
											$res = [
												'status' => 400,
												'error' => $this->config->item('post_project_valid_category_not_existent_popup_message'),
												'popup_heading'=>$this->config->item('popup_alert_heading'),
												'location'=>''
											];
											echo json_encode($res);
											die;
										}
									
										$project_locality_id = 0;$project_county_id = 0;$escrow_payment_method = 'N';
										$offline_payment_method = 'N';$upgrade_type_featured = 'N';$upgrade_type_urgent = 'N';
										$upgrade_type_sealed = 'N';$upgrade_type_hidden = 'N';$min_budget = 0;$max_budget = 0;$not_sure_dropdown_option_selected = 'N';
										$confidential_dropdown_option_selected = 'N';$postal_code_id = 0;
										$project_type = "fixed";
										if($this->input->post('escrow_payment_method') == 'Y'){
											$escrow_payment_method = $this->input->post('escrow_payment_method');
											}
										
										if($this->input->post ('offline_payment_method') == 'Y'){
											$offline_payment_method = $this->input->post ('offline_payment_method');
										}
										if(!empty($this->input->post ('project_budget'))){
											$project_budget = $this->input->post ('project_budget');
											if($project_budget == 'confidential_dropdown_option_selected'){
												$confidential_dropdown_option_selected = 'Y';
											}else if($project_budget == 'not_sure_dropdown_option_selected'){
												$not_sure_dropdown_option_selected = 'Y';
											}else{
												$project_budget_array = explode("_",$this->input->post ('project_budget'));
												$min_budget = $project_budget_array[0];
												$max_budget = $project_budget_array[1]; 
											}
										}
										//if(!empty($this->input->post ('location_option'))){
											if(!empty($this->input->post ('project_locality_id'))){
											$project_locality_id = $this->input->post ('project_locality_id');
											}if(!empty($this->input->post ('project_county_id'))){
												$project_county_id = $this->input->post ('project_county_id');
											}
										
										//}
										if(!empty($this->input->post ('upgrade_type_featured'))){
											$upgrade_type_featured = 'Y';
										}
										if(!empty($this->input->post ('upgrade_type_hidden'))){
											$upgrade_type_hidden = 'Y';
										}
										if(!empty($this->input->post ('upgrade_type_sealed'))){
											$upgrade_type_sealed = 'Y';
										}
										if(!empty($this->input->post ('upgrade_type_urgent'))){
											$upgrade_type_urgent = 'Y';
										}
										if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id'))){
										
											$postal_code_id = $this->input->post('project_postal_code_id');
											
										}
										if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_project'){
											$project_type = $this->input->post('project_type');
											
										
										}else if(!empty($this->input->post ('project_type_main')) && $this->input->post ('project_type_main') == 'post_fulltime_position'){
											$project_type = 'fulltime';
										}
										// move data from project drafts table to fixed_budget_projects_awaiting_moderation table
										/* $this->db->select('*');
										$this->db->from('projects_draft');
										$this->db->where('projects_draft.project_id',$project_id);
										$this->db->where('projects_draft.project_owner_id',$user[0]->user_id);
										$project_result = $this->db->get();
										$project_data = $project_result->result_array();*/
										$awaiting_moderation_project_data = array (
											'project_id'=>$project_id,
											'project_owner_id'=>$user[0]->user_id,
											'project_submission_to_moderation_date'=>date('Y-m-d H:i:s'),
											'project_title'=>trim($this->input->post('project_title')),
											'project_description'=>trim($this->input->post('project_description')),
											'locality_id'=>$project_locality_id,
											'county_id'=>$project_county_id,
											'postal_code_id'=>$postal_code_id,
											'project_type'=>$project_type,
											'min_budget'=>$min_budget,
											'max_budget'=>$max_budget,
											'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
											'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
											'escrow_payment_method'=>$escrow_payment_method,
											'offline_payment_method'=>$offline_payment_method,
											'featured'=>$upgrade_type_featured,
											'urgent'=>$upgrade_type_urgent,
											'sealed'=>$upgrade_type_sealed,
											'hidden'=>$upgrade_type_hidden
										
										); 
										// @sid code to set auto approval date into projects_awaiting_moderation
										$auto_approval_flag = false; // This flag is used to identify where the entry of temporary project table move either [awaiting moderation / open for bidding]
									
										// @sid this code block is used to update user account balance based on project upgrade 
										// check the user account balance,bonus balance,account balance is sufficient for purchase upgrade
										$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user[0]->user_id); // count user membership featured  upgrade
										
										$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user[0]->user_id);// count user membership urgent upgrade
										
										$count_user_sealed_membership_included_upgrades_monthly = $this->Post_project_model->count_user_sealed_membership_included_upgrades_monthly($user[0]->user_id);// count user membership sealed upgrade
										
										$count_user_hidden_membership_included_upgrades_monthly = $this->Post_project_model->count_user_hidden_membership_included_upgrades_monthly($user[0]->user_id);// count user membership sealed upgrade
										
										
										
										$upgraded_project_price = 0;
										if($upgrade_type_featured == 'Y'){
											if($user_membership_plan_detail['included_number_featured_upgrades'] != '-1' && $count_user_featured_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_featured_upgrades']){
												$upgraded_project_price += $this->config->item('project_upgrade_price_featured');
											}
										}
										if($upgrade_type_urgent == 'Y'){
											if($user_membership_plan_detail['included_number_urgent_upgrades'] != '-1' && $count_user_urgent_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_urgent_upgrades']){
												$upgraded_project_price += $this->config->item('project_upgrade_price_urgent');
											}
										}
										if($upgrade_type_sealed == 'Y'){
											//$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
											if($user_membership_plan_detail['included_number_sealed_upgrades'] != '-1' && $count_user_sealed_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_sealed_upgrades']){
												$upgraded_project_price += $this->config->item('project_upgrade_price_sealed');
											}
										}
										if($upgrade_type_hidden == 'Y'){
											//$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
											if($user_membership_plan_detail['included_number_hidden_upgrades'] != '-1' && $count_user_hidden_membership_included_upgrades_monthly >= $user_membership_plan_detail['included_number_hidden_upgrades']){
												$upgraded_project_price += $this->config->item('project_upgrade_price_hidden');
											}
										}
										if(floatval($upgraded_project_price) > 0){
											$total_user_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'] + $user_detail['user_account_balance'];
											if(floatval($upgraded_project_price) > floatval($total_user_balance) ){
											
												
												
												if(($upgrade_type_featured == 'Y' && $upgrade_type_urgent == 'Y') || ($upgrade_type_featured == 'Y' && $upgrade_type_urgent == 'Y' && $upgrade_type_sealed == 'Y') || ($upgrade_type_featured == 'Y' && $upgrade_type_sealed == 'Y') || ($upgrade_type_urgent == 'Y' && $upgrade_type_sealed == 'Y')|| ($upgrade_type_sealed == 'Y' && $upgrade_type_hidden == 'Y')){
												$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_plural');
												}else{
												$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_singular');
												}
										
												$res = array(
														'status' => 400,
														'location' => '',
														'error' => $error_msg, // define in post_project_custom config
														'popup_heading'=>$this->config->item('popup_alert_heading')
													);
												echo json_encode($res);
												die;
											}
										}
										
										if($upgrade_type_featured == 'Y') {
											$project_upgrade_array['featured'] = 'Y';
											}
											if($upgrade_type_urgent == 'Y') {
											$project_upgrade_array['urgent'] = 'Y';
										}
										if($upgrade_type_sealed == 'Y') {
											$project_upgrade_array['sealed'] = 'Y';
										}
										if($upgrade_type_hidden == 'Y') {
											$project_upgrade_array['hidden'] = 'Y';
										}

										$auto_approve_min = get_project_auto_approve_min_time($project_upgrade_array,$user_detail['current_membership_plan_id']);
										if($auto_approve_min != 0) {
											$awaiting_moderation_project_data['auto_approval_date'] = date('Y-m-d H:i:s',$auto_approve_min);
										} else {
											$auto_approval_flag = true;
										}
										
										
										if($auto_approval_flag) {
											// $time_arr = explode(':', $this->config->item('standard_project_availability'));
											// $project_expire_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
											$upgrades = [
												'featured' => $upgrade_type_featured,
												'urgent' => $upgrade_type_urgent,
												'sealed' => $upgrade_type_sealed,
												'hidden' => $upgrade_type_hidden
											];
											$project_expire_date = $this->Post_project_model->get_project_correct_expiration_date($upgrades);
											$open_for_bidding_project_data = [
												'project_id'=>$project_id,
												'project_owner_id'=>$user[0]->user_id,
												'project_posting_date' => date('Y-m-d H:i:s'),
												'project_expiration_date' => $project_expire_date,
												'project_title'=>trim($this->input->post('project_title')),
												'project_description'=>trim($this->input->post('project_description')),
												'locality_id'=>$project_locality_id,
												'county_id'=>$project_county_id,
												'postal_code_id' => $postal_code_id,
												'project_type' => $project_type,
												'min_budget'=>$min_budget,
												'max_budget'=>$max_budget,
												'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
												'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
												'escrow_payment_method'=>$escrow_payment_method,
												'offline_payment_method'=>$offline_payment_method,
												'featured'=>$upgrade_type_featured,
												'urgent'=>$upgrade_type_urgent,
												'sealed'=>$upgrade_type_sealed,
												'hidden'=>$upgrade_type_hidden,
												'views' => 0,
												'revisions'=> 0
											];
											$this->db->insert ('projects_open_bidding', $open_for_bidding_project_data); // save data in fixed_budget_projects_open_bidding table from post project form
											######### save use activity log message
											$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$open_for_bidding_project_data['project_id'];
											$project_title = trim($open_for_bidding_project_data['project_title']);
											if($open_for_bidding_project_data['project_type'] == 'fulltime'){
												$publish_project_log_message = $this->config->item('post_fulltime_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
											}else{
												$publish_project_log_message = $this->config->item('post_project_directly_move_open_bidding_user_activity_log_displayed_message_sent_to_po');
											}
											$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title, ENT_QUOTES)),$publish_project_log_message);
											user_display_log($publish_project_log_message);
											// insert the data regrading purchase tracking and refresh sequence and manage the user bonus balance and account balance
											$this->Post_project_model->user_project_upgrade_purchase_refresh_sequence_tracking_save($open_for_bidding_project_data,$user[0]->user_id); 
											
											
											// move data from draft_projects_tags table to awaiting_moderation_projects_tags table
											if(!empty($this->input->post('project_tag'))){
												foreach($this->input->post('project_tag') as $project_tag_key){
													if(!empty($project_tag_key['tag_name'])){
														$this->db->insert ('projects_tags', array('project_id' => $project_id,
														'project_tag_name' => trim($project_tag_key['tag_name'])));
														// save data in projects_tags table from post project form
													}
												}	
											}
											
											
											$parent_categories_id = [];
											foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
												$project_category_id = 0;
												$project_parent_category_id = 0;
											
												if(!empty($project_category_value['project_parent_category'])){
													if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
													{
														$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

														$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);	
														if($check_project_parent_category_exist){
															if($check_project_child_category_exist){
																
																$project_category_id = $project_category_value['project_child_category'];
																$project_parent_category_id = $project_category_value['project_parent_category'];
															
															}else{
															
																$project_category_id =  $project_category_value['project_parent_category'];
																$project_parent_category_id = 0;
																
															}
														}
													}else{
														
														$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
														if($check_project_parent_category_exist){
														
															$project_category_id =  $project_category_value['project_parent_category'];
															$project_parent_category_id = 0;
														
														}
													}
												}
												if(!empty($project_category_id) || !empty($project_parent_category_id)){
													$this->db->insert ('projects_categories_listing_tracking', array(
														'project_id' => $project_id,
														'project_category_id' => $project_category_id,
														'project_parent_category_id' => $project_parent_category_id 
													));
													if($project_parent_category_id == 0) {
														array_push($parent_categories_id, $project_category_id);
													} else {
														array_push($parent_categories_id, $project_parent_category_id);
													}
												}
												// save data in projects_categories_listing_tracking table from post project form
												
											}
											
											
											// move data from draft_projects_attachments table to awaiting_moderation_projects_attachments table
											$this->db->select('draft_project_attachment_name');
											$this->db->from('draft_projects_attachments');
											$this->db->where('project_id',$project_id);
											$this->db->order_by('id',"asc");
											$project_attachment_result = $this->db->get();
											$project_attachment_data = $project_attachment_result->result_array();
											// connect ftp to move attachments
											$this->load->library('ftp');
											$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
											$config['ftp_username'] = FTP_USERNAME;
											$config['ftp_password'] = FTP_PASSWORD;
											$config['ftp_port'] 	= FTP_PORT;
											$config['debug']    = TRUE;
											$this->ftp->connect($config); 
											$users_ftp_dir 	= USERS_FTP_DIR; 
											$projects_ftp_dir = PROJECTS_FTP_DIR;
											$project_draft_dir = PROJECT_DRAFT_DIR;
											$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
											$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
											$profile_folder     = $user[0]->profile_name;
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
											
											
											
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create awaiting_moderation directory in projects folder
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id , 0777); // create the directory by using  project id
											if(!empty($project_attachment_data)){
											
												$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir);
												//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir , 0777); // create the owner attachment directory by using  project id
												foreach($project_attachment_data as $attachment_key => $attachment_value){
												
													if(!empty($attachment_value['draft_project_attachment_name'])){
														$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir.$attachment_value['draft_project_attachment_name'];
														$file_size = $this->ftp->get_filesize($source_path);
														
														
														if($file_size != '-1'){
															$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir .$attachment_value['draft_project_attachment_name'];
															$this->ftp->move($source_path, $destination_path);
															$this->db->insert ('projects_attachments', array('project_id'=>$project_id,'project_attachment_name'=> $attachment_value['draft_project_attachment_name']));
														}
													}
												}
												$this->db->delete('draft_projects_attachments', array('project_id' => $project_id));
												$draft_project_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);

												
											}
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id))){
												$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.DIRECTORY_SEPARATOR);// delete draft project directory 
											}
											$this->ftp->close();

											if(!empty($open_for_bidding_project_data['hidden']) && $open_for_bidding_project_data['hidden'] == 'N') { 
												// insert into projects_refresh_sequence_tracking table
												$time_arr = explode(':', $this->config->item('standard_project_refresh_sequence'));
												$check_valid_arr = array_map('getInt', $time_arr); 
												$valid_time_arr = array_filter($check_valid_arr);
												$next_refresh_date = null;
												if(!empty($valid_time_arr)) {
													$next_refresh_date = get_next_refresh_time(date('Y-m-d H:i:s'), $time_arr);
													$refresh_sequence_data = [
														'project_id' => $project_id,
														'project_last_refresh_time' => null,
														'project_next_refresh_time' => $next_refresh_date,
														'project_upgrade_refresh_sequence_standard'=>$this->config->item('standard_project_refresh_sequence')
													];
													$standard_refresh_sequence_cnt = $this->db->from('standard_projects_refresh_sequence_tracking')->where(['project_id' => $project_id])->count_all_results();
													if($standard_refresh_sequence_cnt == 0) {
														$this->db->insert('standard_projects_refresh_sequence_tracking', $refresh_sequence_data);
													}
												}

												// insert data in projects latest project refresh sequence tracking
												$latest_project_refresh_tracking = [
													'project_id' => $project_id,
													'project_last_refresh_time' => date('Y-m-d H:i:s'),
													'project_next_refresh_time' => $next_refresh_date
												];
												$this->db->insert('projects_latest_refresh_sequence_tracking', $latest_project_refresh_tracking);

												// save data into users newly posted projects sent notification tracking table
												$category_mapping_data = $this->Post_project_model->get_projects_professionals_categories_mapping_data_based_on_categories_id($parent_categories_id);
												if(!empty($category_mapping_data)) {
													$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table($category_mapping_data, $parent_categories_id, $open_for_bidding_project_data);
												}
												// save data into users newly posted projects sent notification tracking table when project posted by favorite employer
												$users = $this->Post_project_model->get_users_id_from_favorite_employer_id($open_for_bidding_project_data['project_owner_id']);
												if(!empty($users)) {
													$this->Post_project_model->add_data_in_to_users_new_posted_projects_sent_notification_table_favorite_employer($users, $parent_categories_id, $open_for_bidding_project_data);
												}
											}
											// trigger socket event to update latest project section on user dashboard
											$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateLatestProjectOnUserDashboard/".$user[0]->user_id.'?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
											$options = array(
												CURLOPT_RETURNTRANSFER => true,
												CURLOPT_SSL_VERIFYPEER => false
											);
											try {
												$ch = curl_init( $url );
												curl_setopt_array( $ch, $options );
												curl_exec( $ch );
												curl_close( $ch );
											} catch(Exception $e) {
											}		
											
										} else {
											$this->db->insert ('projects_awaiting_moderation', $awaiting_moderation_project_data);
											// move data from draft_projects_tags table to awaiting_moderation_projects_tags table
											
											######### save use activity log message
											$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$awaiting_moderation_project_data['project_id'];
											$project_title = trim($awaiting_moderation_project_data['project_title']);
											if($awaiting_moderation_project_data['project_type'] == 'fulltime'){
												$publish_project_log_message = $this->config->item('fulltime_project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
											}else{
												$publish_project_log_message = $this->config->item('project_submited_by_po_for_awaiting_moderation_user_activity_log_displayed_message_sent_to_po');
											}
											$publish_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title, ENT_QUOTES)),$publish_project_log_message);
											user_display_log($publish_project_log_message);
											
											
											
											// insert the data regrading purchase tracking and manage the user bonus balance and account balance
											$this->Post_project_model->user_project_upgrade_purchase_tracking_save($awaiting_moderation_project_data,$user[0]->user_id); 
											/* $this->db->select('draft_project_tag_name');
											$this->db->from('draft_projects_tags');
											$this->db->where('project_id',$project_id);
											$this->db->order_by('id',"asc");
											$project_tag_result = $this->db->get();
											$project_tag_data = $project_tag_result->result_array();
											if(!empty($project_tag_data)){
												foreach($project_tag_data as $tag_key => $tag_value){
													$this->db->insert ('awaiting_moderation_projects_tags', array('project_id'=>$project_id,'awaiting_moderation_project_tag_name'=> $tag_value['draft_project_tag_name']));
												}
											
											} */
											
											if(!empty($this->input->post('project_tag'))){
												foreach($this->input->post('project_tag') as $project_tag_key){
													if(!empty($project_tag_key['tag_name'])){
														$this->db->insert ('awaiting_moderation_projects_tags', array('project_id' => $project_id,
														'awaiting_moderation_project_tag_name' => $project_tag_key['tag_name']));
														// save data in awaiting_moderation_project_tag_name table from post project form
													}
												}	
											}
											
											
											
											foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
												$project_category_id = 0;
												$project_parent_category_id = 0;
												if(!empty($project_category_value['project_parent_category'])){
													if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
													{
													
														$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

														$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);
														if($check_project_parent_category_exist){
															if($check_project_child_category_exist){
																$project_category_id = $project_category_value['project_child_category'];
																$project_parent_category_id = $project_category_value['project_parent_category'];
															
															}else{
															
																$project_category_id =  $project_category_value['project_parent_category'];
																$project_parent_category_id = 0;
																
															}
														}
													}else{
													
														$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
														if($check_project_parent_category_exist){
															
															$project_category_id =  $project_category_value['project_parent_category'];
															$project_parent_category_id = 0;
														
														}
													}
													
													if(!empty($project_category_id) || !empty($project_parent_category_id)){
														$this->db->insert ('awaiting_moderation_projects_categories_listing_tracking', array(
															'project_id' => $project_id,
															'awaiting_moderation_project_category_id' => $project_category_id,
															'awaiting_moderation_project_parent_category_id' => $project_parent_category_id
														));
													}
													// save data in awaiting_moderation_projects_categories_listing_tracking table from post project form
												}	
											}
											$this->db->select('draft_project_attachment_name');
											$this->db->from('draft_projects_attachments');
											$this->db->where('project_id',$project_id);
											$this->db->order_by('id',"asc");
											$project_attachment_result = $this->db->get();
											$project_attachment_data = $project_attachment_result->result_array();
											// connect ftp to move attachment
											$this->load->library('ftp');
											$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
											$config['ftp_username'] = FTP_USERNAME;
											$config['ftp_password'] = FTP_PASSWORD;
											$config['ftp_port'] 	= FTP_PORT;
											$config['debug']    = TRUE;
											$this->ftp->connect($config); 
											$users_ftp_dir 	= USERS_FTP_DIR; 
											$projects_ftp_dir = PROJECTS_FTP_DIR;
											$project_draft_dir = PROJECT_DRAFT_DIR;
											$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
											$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
											$profile_folder     = $user[0]->profile_name;
											
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.DIRECTORY_SEPARATOR);
											$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir);
											
											
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir, 0777);// create awaiting_moderation directory in projects folder
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using  project id
											//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir , 0777); // create the directory by using  project id
											if(!empty($project_attachment_data)){
												foreach($project_attachment_data as $attachment_key => $attachment_value){
												
													if(!empty($attachment_value['draft_project_attachment_name'])){
														$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir .$attachment_value['draft_project_attachment_name'];
														$file_size = $this->ftp->get_filesize($source_path);
														if($file_size != '-1'){
															$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_id.$project_owner_attachments_dir .$attachment_value['draft_project_attachment_name'];
															$this->ftp->move($source_path, $destination_path);
															$this->db->insert ('awaiting_moderation_projects_attachments', array('project_id'=>$project_id,'awaiting_moderation_project_attachment_name'=> $attachment_value['draft_project_attachment_name']));
														}
													}
												}
												$this->db->delete('draft_projects_attachments', array('project_id' => $project_id));
												//$draft_project_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);
												
											}
											if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id))){
												$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);// delete project directory 

											}
											$this->ftp->close();

											// trigger socket event to update latest project section on user dashboard
											$url = PROJECT_MANAGEMENT_SOCKET_URL."/updateProjectListingOnDashboardByStatus/".$user[0]->user_id.'/awaiting_moderation/""?authorization_key='.NODE_URL_AUTHORIZATION_KEY;
											$options = array(
												CURLOPT_RETURNTRANSFER => true,
												CURLOPT_SSL_VERIFYPEER => false
											);
											try {
												$ch = curl_init( $url );
												curl_setopt_array( $ch, $options );
												curl_exec( $ch );
												curl_close( $ch );
											} catch(Exception $e) {
											}		
										}
											
										########## Delete the record  from fixed budget draft table 
										$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
										$this->db->delete('draft_projects_categories_listing_tracking', array('project_id' => $project_id));
										$this->db->delete('projects_draft', array('project_id' => $project_id));
										$msg['status'] = 'SUCCESS';
										$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
										$msg['message'] = '';
										echo json_encode ($msg);
										}else{
										echo json_encode ($response);
									}
								
								} else {
									$msg['status'] = 400;
									$msg['message'] = '';
									$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
									echo json_encode ($msg);
								}
									
							} else {
								
								$msg['status'] = 400;
								$msg['message'] = '';
								$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
								echo json_encode ($msg);
							}
						}
					} else {
						$msg['status'] = 400;
						$msg['message'] = '';
						$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
						echo json_encode ($msg);
					}
					
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}	
			}else{
			
				show_custom_404_page(); //show custom 404 page
			
			}
		}else{
		
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	 * This function is used for load the edit view of project.
	*/	
	public function edit_project(){
		if(check_session_validity()){ 
		
			if(empty($this->input->get('id'))){
				show_404();
			}
			$user = $this->session->userdata('user');
			$check_project_exists = $this->db // count the project from projects_open_bidding table
				->select ('id')
				->from ('projects_open_bidding')
				->where('project_id',$this->input->get('id'))
				->where('project_owner_id', $user[0]->user_id)
				->get ()->num_rows ();
			
			if($check_project_exists == 0){
				redirect (VPATH . $this->config->item('dashboard_page_url'));
			}
			$project_id = $this->input->get('id');
			$data['project_parent_categories'] = $this->Post_project_model->get_project_parent_categories();
			
			$count_project_parent_category = $this->db
			->select ('id')
			->from ('categories_projects')
			->where ('parent_id', 0)
			->where ('status', 'Y')
			->get ()->num_rows ();
			$data['count_available_project_parent_category_count'] = 	$count_project_parent_category;
			
			//$this->Projects_model->check_update_invalid_combination_project_location('projects_open_bidding','project_id',$project_id); // check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
			
			
			
			$this->db->select('projects_open_bidding.*,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
			$this->db->from('projects_open_bidding');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
			
			
			$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
			
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
			
			
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
			
			
			$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
			
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
			$this->db->where('projects_open_bidding.project_id',$project_id);
			$this->db->where('projects_open_bidding.project_owner_id',$user[0]->user_id);
			$project_result = $this->db->get();
			$project_data = $project_result->row_array();
			$data['project_data'] = $project_data;
			
			############# fetch the additional information of project 
			
			$this->db->select('*');
			$this->db->from('projects_additional_information');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('additional_information_add_date','desc');
			$project_additional_information_result = $this->db->get();
			$project_additional_information_data = $project_additional_information_result->result_array();
			$data['project_additional_information_data'] = $project_additional_information_data;
			
			########## fetch the categories of draft project and make the dynamic array start ###
			$this->db->select('*');
			$this->db->from('projects_categories_listing_tracking');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_category_result = $this->db->get();
			$project_category_data = $project_category_result->result_array();
			$data['project_category_data'] = $project_category_data;
			########## fetch the  project attachments ###
			$data['project_attachment_array'] = $this->Projects_model->get_project_attachments($project_id,$user[0]->profile_name,'open_for_bidding');
			########## fetch the tags of draft project ###
			$this->db->select('*');
			$this->db->from('projects_tags');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_tag_result = $this->db->get();
			$project_tag_data = $project_tag_result->result_array();
			
			$data['project_id'] = $project_id;
			$data['project_tag_data'] = $project_tag_data;
			$data['fixed_budget_projects_budget_range'] = $this->Post_project_model->get_fixed_budget_projects_budget_range();// drop down options for fixed budget project budget range
			
			$data['hourly_rate_based_budget_projects_budget_range'] = $this->Post_project_model->get_hourly_rate_based_projects_budget_range();// drop down options for hourly rate based project budget range
			
			$data['fulltime_project_salary_range'] = $this->Post_project_model->get_fulltime_projects_salaries_range();// drop down options for fulltime project salary range
			
			$data['counties'] = $this->Dashboard_model->get_counties(); // drop down options of counties
			$data['localities'] = $this->Dashboard_model->get_localities_selected_county($project_data['county_id']);// drop down options of localities
			$data['postal_codes'] = $this->Post_project_model->get_project_post_codes($project_data['locality_id']);// drop down options of localities
			
			################## get the user_details #################
				$data['user_details'] = $this->db->get_where('users_details', array('user_id' => $user[0]->user_id))->row();
			###############################################
			
			$count_project_categories = $this->db // count the number of categories of draft project
				->select ('id')
				->from ('projects_categories_listing_tracking')
				->where('project_id',$project_id)
				->get ()->num_rows ();
				
			$count_project_attachments = $this->db // count the number of attachment of draft project
				->select ('id')
				->from ('projects_attachments')
				->where('project_id',$project_id)
				->get ()->num_rows ();
				
			$count_project_tags = $this->db // count the number of tags of draft project
				->select ('id')
				->from ('projects_tags')
				->where('project_id',$project_id)
				->get ()->num_rows ();	
				
			$count_project_postal_codes = $this->db
			->select ('id')
			->from ('postal_codes')
			->where ('locality_id', $project_data['locality_id'])
			->get ()->num_rows ();

			$data['count_project_categories'] = $count_project_categories;
			$data['count_project_attachments'] = $count_project_attachments;
			$data['count_project_tags'] = $count_project_tags;
			$data['count_project_postal_codes'] = $count_project_postal_codes;
			$data['project_id'] = $project_id;
			$data['current_page'] = 'edit_project';
			
			
			$edit_project_page_title_meta_tag = strip_tags($project_data['project_title']);
			$edit_project_page_title_meta_tag = get_correct_string_based_on_limit($edit_project_page_title_meta_tag, $this->config->item('project_title_meta_tag_character_limit'));
			$edit_project_page_description_meta_tag = strip_tags($project_data['project_description']);
			$edit_project_page_description_meta_tag = get_correct_string_based_on_limit($edit_project_page_description_meta_tag, $this->config->item('project_description_meta_description_character_limit'));
			
			$data['meta_tag'] = '<title>' . $edit_project_page_title_meta_tag . '</title><meta name="description" content="' . $edit_project_page_description_meta_tag . '"/>';
			$this->layout->view('edit_project', '', $data, 'normal');
		}else{
			redirect(VPATH . $this->config->item('signin_page_url'));
		
		}
	}
	/**
	* This function is used for update the project into the database.
	*/
	public  function update_project(){
		if($this->input->is_ajax_request ()){
			if ($this->input->post ())
			{
				if(check_session_validity()){ 
				
					
					$i = 0;
					$project_id = $this->input->post ('project_id');
					$user = $this->session->userdata('user');
					
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
					
					if(empty($project_status_table_array['table_name'])){
						if($project_type == 'fulltime'){
							$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view');
						}else{
							$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
						die;
					}
					
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.employer_id as project_owner_id,pd.project_type,pd.fulltime_project_expiration_date as project_expiration_date')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_owner_id,pd.project_type,pd.project_expiration_date')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
					if($project_data['project_owner_id'] != $user[0]->user_id){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
						die;
						
					}	
					if(($project_status_table_array['project_status'] != 'open_for_bidding') || ($project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) < time()) ){
						
						if($project_data['project_type'] == 'fulltime'){
							$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view');
						}else{
							$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view');
						}
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
						die;
					}
					
					
					$this->db->select('projects_open_bidding.project_id,projects_open_bidding.project_owner_id,projects_open_bidding.project_type');
					$this->db->from('projects_open_bidding');
					
					$this->db->where('project_id',$project_id);
					$project_result = $this->db->get();
					$project_data = $project_result->result_array();
					
					if(empty($project_data) || (!empty($project_data) && $project_data[0]['project_owner_id'] != $user[0]->user_id)){
						
						$msg['status'] = 400;
						$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
						echo json_encode($msg);
						die;
					}
					
					$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
					if($check_project_categories_available_or_not == 0){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
						die;
					}
				
					if(!$this->input->post('project_category')){
		
						$msg['status'] = 'FAILED';
						$msg['errors'][$i]['id'] = 'project_parent_category_0';
						$msg['errors'][$i]['message'] = $this->config->item('parent_category_validation_post_project_message');
						$msg['errors'][$i]['error_class'] = 'required';
						$i ++;
					
					}else{
						$project_category = $this->input->post('project_category');
						if(array_key_exists(500,$project_category ) && empty($project_category[500]['project_parent_category'])){
							$msg['status'] = 'FAILED';
							$msg['errors'][$i]['id'] = 'project_parent_category_0';
							$msg['errors'][$i]['message'] = $this->config->item('parent_category_validation_post_project_message');
							$msg['errors'][$i]['error_class'] = 'required';
							$i ++;
						
						}
					
					}
					if(!empty(trim($this->input->post ('project_additional_information')))){
						$project_additional_information_length = strlen(preg_replace('/\s+/', '', $this->input->post ('project_additional_information')));
						if($this->config->item('project_additional_information_minimum_length_words_limit_post_project') == 0){
							
							if($project_additional_information_length < $this->config->item('project_additional_information_minimum_length_character_limit_post_project')){
								$msg['status'] = 'FAILED';
								$msg['errors'][$i]['id'] = 'project_additional_information';
								$msg['errors'][$i]['error_class'] = 'min_length';
								if($project_data[0]['project_type'] == 'fulltime'){
									$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_additional_information_characters_min_length_validation_post_project_message');
								}else{
									$msg['errors'][$i]['message'] = $this->config->item('project_additional_information_characters_min_length_validation_message');
								}
								
								$i ++;
							}
						
						}else{
							//remove unwanted space between words
							$string_only_single_space = preg_replace("/\s+/", " ", $this->input->post ('project_additional_information'));
							$project_additional_information_word_count = count(explode(' ', trim($string_only_single_space)));
							
							if($project_additional_information_length < $this->config->item('project_additional_information_minimum_length_character_limit_post_project') ||$project_additional_information_word_count < $this->config->item('project_additional_information_minimum_length_words_limit_post_project') ){
								
								$msg['status'] = 'FAILED';
								$msg['errors'][$i]['id'] = 'project_additional_information';
								$msg['errors'][$i]['error_class'] = 'min_length';
								
								if($project_data[0]['project_type'] == 'fulltime'){
									$msg['errors'][$i]['message'] = $this->config->item('fulltime_position_additional_information_characters_words_min_length_validation_message');
								}else{
									$msg['errors'][$i]['message'] = $this->config->item('project_additional_information_characters_words_min_length_validation_message');
								}
								$i ++;
							}
						}
					}
					if($this->input->post ('location_option') == '0'){
					
					
						if(empty($this->input->post ('project_county_id'))){
							$msg['status'] = 'FAILED';
							$msg['errors'][$i]['id'] = 'project_county_id';
							$msg['errors'][$i]['message'] = $this->config->item('project_county_validation_post_project_message');
								$msg['errors'][$i]['error_class'] = 'required';
							$i ++;
						}
						/* if(!empty($this->input->post('project_county_id')) && empty($this->input->post ('project_locality_id'))){
							$msg['status'] = 'FAILED';
							$msg['errors'][$i]['id'] = 'project_locality_id';
							$msg['errors'][$i]['message'] = $this->config->item('project_locality_validation_post_project_message');
							$msg['errors'][$i]['error_class'] = 'required';
								$i ++;
						}
						if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id')) && empty($this->input->post ('project_postal_code_id'))){
							if(!empty($this->get_project_post_codes($this->input->post ('project_locality_id')))){
								$msg['status'] = 'FAILED';
								$msg['errors'][$i]['id'] = 'project_postal_code_id';
								$msg['errors'][$i]['message'] = $this->config->item('project_postal_code_validation_post_project_message');
								$msg['errors'][$i]['error_class'] = 'required';
								$i ++;
							}
							
						} */
						
					}
					if($i == 0){
						
						
						$user = $this->session->userdata('user');
						$count_project = $this->db // count the number of record in projects_open_bidding table
						->select ('id')
						->from ('projects_open_bidding')
						->where('project_id',$project_id)
						->where('project_owner_id',$user[0]->user_id)
						->get ()->num_rows ();
						if($count_project > 0){
						
									
						$project_locality_id = 0;
						$project_county_id = 0;
						$postal_code_id = 0;
						$escrow_payment_method = 'N';
						$offline_payment_method = 'N';
						$project_categories_old_values = array();$project_categories_new_values = array();
						$project_tag_old_values = array();$project_tag_new_values = array();
						$project_old_values = array();$project_new_values = array();
						$project_compare_fields = array('min_budget','max_budget','confidential_dropdown_option_selected','not_sure_dropdown_option_selected','locality_id','county_id','postal_code_id','escrow_payment_method','offline_payment_method');
						$this->db->select('projects_categories_listing_tracking.*');
						$this->db->from('projects_categories_listing_tracking');
						$this->db->where('projects_categories_listing_tracking.project_id',$project_id);
						$category_result = $this->db->get();
						$category_data = $category_result->result_array();
						if(!empty($category_data)){
							foreach($category_data  as $category_key=>$category_value){
								$project_categories_old_values[] = $category_value['project_category_id'];
								$project_categories_old_values[] = $category_value['project_parent_category_id'];
							}
						}
						$this->db->select('project_tag_name');
						$this->db->from('projects_tags');
						$this->db->where('project_id',$project_id);
						$this->db->order_by('id',"asc");
						$project_tag_result = $this->db->get();
						$project_tag_data = $project_tag_result->result_array();
						if(!empty($project_tag_data)){
							foreach($project_tag_data  as $tag_key=>$tag_value){
								$project_tag_old_values[] = $tag_value['project_tag_name'];
							}
						}
						
						
						
						###################### check that user selected category is valid or not(admin deactive/delete the category)
						$check_project_parent_category_status = false;
						if($this->input->post('project_category')){
							foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
								if(isset($project_category_value['project_parent_category']) && !empty($project_category_value['project_parent_category'])){
									$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
									if($check_project_parent_category_exist){
										$check_project_parent_category_status = true;
										break;
									}
								}
							}
						
						}
						if(!$check_project_parent_category_status){
							$res = [
								'status' => 400,
								'error' => $this->config->item('post_project_valid_category_not_existent_popup_message'),
								'location'=>''
							];
							echo json_encode($res);
							die;
						}
						
							$upgraded_project_price = 0;
							if($this->input->post ('upgrade_type_featured')){
								$upgraded_project_price += $this->config->item('project_upgrade_price_featured');
							}
							if($this->input->post ('upgrade_type_urgent')){
								$upgraded_project_price += $this->config->item('project_upgrade_price_urgent');
							}
							if(floatval($upgraded_project_price) > 0){
								$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
								$total_user_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'] + $user_detail['user_account_balance'];
								if(floatval($upgraded_project_price) > floatval($total_user_balance) ){
								
									if($this->input->post('upgrade_type_featured') && $this->input->post ('upgrade_type_urgent')){
										$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_plural');
										}else{
											$error_msg = $this->config->item('user_post_upgraded_project_insufficient_funds_error_message_singular');
										}
								
										$res = array(
												'status' => 400,
												'location' => '',
												'error' => $error_msg, // define in post_project_custom config
												'popup_heading'=>$this->config->item('popup_alert_heading')
											);
										echo json_encode($res);
										die;
								}
							}
							
							
							$project_locality_id = 0;$project_county_id = 0;
							$postal_code_id = 0;
							
							$this->db->select('projects_open_bidding.*,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date');
							$this->db->from('projects_open_bidding');
						
							$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
							$this->db->where('projects_open_bidding.project_id',$project_id);
							$this->db->where('projects_open_bidding.project_owner_id',$user[0]->user_id);
							$project_result = $this->db->get();
							$project_data = $project_result->result_array();
							
							$featured_max = 0;
							$urgent_max = 0;
							$expiration_featured_upgrade_date_array = array();
							$expiration_urgent_upgrade_date_array = array();
							if(!empty($project_data[0]['featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_data[0]['featured_upgrade_end_date'];
							}
							if(!empty($project_data[0]['bonus_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_data[0]['bonus_featured_upgrade_end_date'];
							}
							if(!empty($project_data[0]['membership_include_featured_upgrade_end_date'])){
								$expiration_featured_upgrade_date_array[] = $project_data[0]['membership_include_featured_upgrade_end_date'];
							}
							if(!empty($expiration_featured_upgrade_date_array)){
								$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
							}
							
							if(!empty($project_data[0]['urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_data[0]['urgent_upgrade_end_date'];
							}
							if(!empty($project_data[0]['bonus_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_data[0]['bonus_urgent_upgrade_end_date'];
							}
							if(!empty($project_data[0]['membership_include_urgent_upgrade_end_date'])){
								$expiration_urgent_upgrade_date_array[] = $project_data[0]['membership_include_urgent_upgrade_end_date'];
							}
							if(!empty($expiration_urgent_upgrade_date_array)){
								$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
							}
							if($project_data[0]['featured'] == 'N' && $featured_max < time() ){
								$project_compare_fields[] = 'featured';
							}
								
							if($project_data[0]['urgent'] == 'N' && $urgent_max < time() ){
								$project_compare_fields[] = 'urgent';
							}
							
							
							$project_old_values['locality_id'] = $project_data[0]['locality_id'];
							$project_old_values['county_id'] = $project_data[0]['county_id'];
							$project_old_values['postal_code_id'] = $project_data[0]['postal_code_id'];
							$project_old_values['escrow_payment_method'] = $project_data[0]['escrow_payment_method'];
							$project_old_values['offline_payment_method'] = $project_data[0]['offline_payment_method'];
							$project_old_values['featured'] = $project_data[0]['featured'];
							$project_old_values['urgent'] = $project_data[0]['urgent'];
							$project_old_values['min_budget'] = $project_data[0]['min_budget'];
							$project_old_values['max_budget'] = $project_data[0]['max_budget'];
							$project_old_values['confidential_dropdown_option_selected'] = $project_data[0]['confidential_dropdown_option_selected'];
							$project_old_values['not_sure_dropdown_option_selected'] = $project_data[0]['not_sure_dropdown_option_selected'];
						
							
							$featured_upgrade_end_date_timestamp = $project_data[0]['featured_upgrade_end_date'] != NULL ? strtotime ($project_data[0]['featured_upgrade_end_date']) : 0; // latest featured upgrade date
							$bonus_featured_upgrade_end_date_timestamp = $project_data[0]['bonus_featured_upgrade_end_date'] != NULL ? strtotime ($project_data[0]['bonus_featured_upgrade_end_date']) : 0; // latest featured upgrade date
							
							$urgent_upgrade_end_date_timestamp = $project_data[0]['urgent_upgrade_end_date'] != NULL ? strtotime ($project_data[0]['urgent_upgrade_end_date']) : 0;
							$bonus_urgent_upgrade_end_date_timestamp = $project_data[0]['bonus_urgent_upgrade_end_date'] != NULL ? strtotime ($project_data[0]['bonus_urgent_upgrade_end_date']) : 0;
							$featured_upgrade_end_date_max = 0;
							$urgent_upgrade_end_date_max = 0;
							$user_selected_upgrades = array();
							$user_selected_upgrades['project_id'] = $project_id;
							$user_selected_upgrades['project_title'] = $project_data[0]['project_title'];
							$user_selected_upgrades['project_type'] = $project_data[0]['project_type'];
							$expiration_upgrade_date_array[] = $project_data[0]['project_expiration_date'];
							
							$project_new_values['featured'] = 'N';
							if($this->input->post ('upgrade_type_featured')){
								$project_new_values['featured'] = 'Y';
								$time_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
								if((empty($featured_upgrade_end_date_timestamp) && empty($bonus_featured_upgrade_end_date_timestamp)) || ($featured_upgrade_end_date_timestamp < time() && $bonus_featured_upgrade_end_date_timestamp < time())){
										$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
										$project_upgrade_featured_prolong_status = '0';
										//$project_upgrade_start_date = date('Y-m-d H:i:s');
										
								}else{
									
									if($featured_upgrade_end_date_timestamp >= $bonus_featured_upgrade_end_date_timestamp && $featured_upgrade_end_date_timestamp != 0){
										$featured_upgrade_end_date_max = $project_data[0]['featured_upgrade_end_date'];
									
									}elseif($bonus_featured_upgrade_end_date_timestamp >= $featured_upgrade_end_date_timestamp && $bonus_featured_upgrade_end_date_timestamp !=0){
										$featured_upgrade_end_date_max = $project_data[0]['bonus_featured_upgrade_end_date'];
									}
									$upgrade_end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($featured_upgrade_end_date_max)));
									
									
									$project_upgrade_featured_prolong_status = '1';
									
									//$project_upgrade_start_date = date('Y-m-d H:i:s', strtotime($featured_upgrade_end_date_max));
									
								}
								if($featured_max > time()){
									$project_upgrade_start_date = date('Y-m-d H:i:s',$featured_max);
								
								}else{
									$project_upgrade_start_date = date('Y-m-d H:i:s');
								}
								$this->db->update('projects_open_bidding', ['featured' => 'Y'], ['project_id' => $project_id]);
								$expiration_upgrade_date_array[] = $upgrade_end_date;
								$user_selected_upgrades['featured'] = 'Y';
								$user_selected_upgrades['featured_upgrade_end_date'] = $upgrade_end_date;
								$user_selected_upgrades['project_upgrade_featured_prolong_status'] = $project_upgrade_featured_prolong_status;
								$user_selected_upgrades['project_upgrade_start_date'] = $project_upgrade_start_date; 
							
							}
							//if($count_featured_upgrade_project_row == 0 && $this->input->post ('upgrade_type_urgent')){
							$project_new_values['urgent'] = 'N';
							if($this->input->post ('upgrade_type_urgent')){
								$project_new_values['urgent'] = 'Y';
								$time_arr = explode(':', $this->config->item('project_upgrade_availability_urgent'));
								if((empty($urgent_upgrade_end_date_timestamp) && empty($bonus_urgent_upgrade_end_date_timestamp)) || ($urgent_upgrade_end_date_timestamp < time() && $bonus_urgent_upgrade_end_date_timestamp < time())){
										$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
										$project_upgrade_urgent_prolong_status = '0';
										//$project_upgrade_start_date = date('Y-m-d H:i:s');
								}else{
									
									if($urgent_upgrade_end_date_timestamp >= $bonus_urgent_upgrade_end_date_timestamp && $urgent_upgrade_end_date_timestamp != 0){
										$urgent_upgrade_end_date_max = $project_data[0]['urgent_upgrade_end_date'];
									
									}elseif($bonus_urgent_upgrade_end_date_timestamp >= $urgent_upgrade_end_date_timestamp && $bonus_urgent_upgrade_end_date_timestamp !=0){
										$urgent_upgrade_end_date_max = $project_data[0]['bonus_urgent_upgrade_end_date'];
									}
									$upgrade_end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($urgent_upgrade_end_date_max)));
									$project_upgrade_urgent_prolong_status = '1';
									
									//$project_upgrade_start_date = date('Y-m-d H:i:s', strtotime($urgent_upgrade_end_date_max));
									
								}
								$expiration_upgrade_date_array[] = $upgrade_end_date;
								$this->db->update('projects_open_bidding', ['urgent' => 'Y'], ['project_id' => $project_id]);
								$user_selected_upgrades['urgent'] = 'Y';
								$user_selected_upgrades['urgent_upgrade_end_date'] = $upgrade_end_date;
								$user_selected_upgrades['project_upgrade_urgent_prolong_status'] = $project_upgrade_urgent_prolong_status;
								
								if($urgent_max > time()){
									$project_upgrade_start_date = date('Y-m-d H:i:s',$urgent_max);
									
								}else{
									$project_upgrade_start_date = date('Y-m-d H:i:s');
								}
								$user_selected_upgrades['project_upgrade_start_date'] = $project_upgrade_start_date; 
							}
							
							
							if($this->input->post ('upgrade_type_featured') || $this->input->post ('upgrade_type_urgent') && !empty($expiration_upgrade_date_array)){
						
								$max = max(array_map('strtotime', $expiration_upgrade_date_array));
								$this->db->update('projects_open_bidding', ['project_expiration_date'=>date('Y-m-d H:i:s', $max)], ['project_id' => $project_id]);
								
								$this->Projects_model->user_project_upgrade_purchase_refresh_sequence_tracking_membership_exclude_save($user_selected_upgrades,$user[0]->user_id);// track purchasing and refresh sequence;
								
							}
							
							if($this->input->post('escrow_payment_method') == 'Y'){
								$escrow_payment_method = $this->input->post('escrow_payment_method');
							}
							
							if($this->input->post ('offline_payment_method') == 'Y'){
								$offline_payment_method = $this->input->post ('offline_payment_method');
								
							}
							if(!empty($this->input->post ('project_budget'))){
								$project_budget = $this->input->post ('project_budget');
								$not_sure_dropdown_option_selected = 'N';
								$confidential_dropdown_option_selected = 'N';
								$min_budget = 0;$max_budget = 0;
								if($project_budget == 'confidential_dropdown_option_selected'){
									$confidential_dropdown_option_selected = 'Y';
								}else if($project_budget == 'not_sure_dropdown_option_selected'){
									$not_sure_dropdown_option_selected = 'Y';
								}else{
									$project_budget_array = explode("_",$this->input->post ('project_budget'));
									$min_budget = $project_budget_array[0];
									$max_budget = $project_budget_array[1]; 
								}
							}
							$project_new_values['confidential_dropdown_option_selected'] = $confidential_dropdown_option_selected;
							$project_new_values['not_sure_dropdown_option_selected'] = $not_sure_dropdown_option_selected;
							$project_new_values['min_budget'] = $min_budget;
							$project_new_values['max_budget'] = $max_budget;
							
							
							//if(!empty($this->input->post ('location_option'))){
								if(!empty($this->input->post ('project_locality_id'))){
								$project_locality_id = $this->input->post ('project_locality_id');
								}if(!empty($this->input->post ('project_county_id'))){
									$project_county_id = $this->input->post ('project_county_id');
								}
							
							//}
							if(!empty($this->input->post('project_county_id')) && !empty($this->input->post ('project_locality_id'))){
							
								$postal_code_id = $this->input->post('project_postal_code_id');
								
							}
							
							$open_for_bidding_project_data = array (
								'min_budget'=>$min_budget,
								'max_budget'=>$max_budget,
								'not_sure_dropdown_option_selected'=>$not_sure_dropdown_option_selected,
								'confidential_dropdown_option_selected'=>$confidential_dropdown_option_selected,
								'locality_id'=>$project_locality_id,
								'county_id'=>$project_county_id,
								'postal_code_id'=>$postal_code_id,
								'escrow_payment_method'=>$escrow_payment_method,
								'offline_payment_method'=>$offline_payment_method
							);
							
							$project_new_values['locality_id'] = $project_locality_id;
							$project_new_values['county_id'] = $project_county_id;
							$project_new_values['postal_code_id'] = $postal_code_id;
							$project_new_values['escrow_payment_method'] = $escrow_payment_method;
							$project_new_values['offline_payment_method'] = $offline_payment_method;
							
							$this->db->where ('project_id', $project_id);
							$this->db->update ('projects_open_bidding', $open_for_bidding_project_data); // save data in projects_draft table from edit draft form
							
							$this->db->delete('projects_tags', array('project_id' => $project_id));
							$this->db->delete('projects_categories_listing_tracking', array('project_id' => $project_id));
							foreach($this->input->post('project_category') as $project_category_key=>$project_category_value){
							
								$project_category_id = 0;
								$project_parent_category_id = 0;
								if(!empty($project_category_value['project_parent_category'])){
									if(isset($project_category_value['project_child_category']) && !empty($project_category_value['project_child_category']))
									{
									
										$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);

										$check_project_child_category_exist = $this->Post_project_model->check_project_child_category_exist($project_category_value['project_parent_category'],$project_category_value['project_child_category']);	
										
										if($check_project_parent_category_exist){
											if($check_project_child_category_exist){
												
												$project_category_id = $project_category_value['project_child_category'];
												$project_parent_category_id = $project_category_value['project_parent_category'];
											
											}else{
											
												$project_category_id =  $project_category_value['project_parent_category'];
												$project_parent_category_id = 0;
												
											}
										}
								
									
									}else{
									
										$check_project_parent_category_exist = $this->Post_project_model->check_project_parent_category_exist($project_category_value['project_parent_category']);
										if($check_project_parent_category_exist){
											
											$project_category_id =  $project_category_value['project_parent_category'];
											$project_parent_category_id = 0;
										
										}
									}
									
								}
								
								if(!empty($project_category_id) || !empty($project_parent_category_id)){
									
									$project_categories_new_values[] = $project_category_id; // for check project revision count
									$project_categories_new_values[] = $project_parent_category_id;// for check project revision count
									
									$this->db->insert ('projects_categories_listing_tracking', array(
										'project_id' => $project_id,
										'project_category_id' => $project_category_id,
										'project_parent_category_id' => $project_parent_category_id 
									));
								}
							}
						  
							if(!empty($this->input->post('project_tag'))){
								foreach($this->input->post('project_tag') as $project_tag_key){
									if(!empty($project_tag_key['tag_name'])){
										$project_tag_new_values[] = $project_tag_key['tag_name'];
										$this->db->insert ('projects_tags', array('project_id' => $project_id,
										'project_tag_name' => trim($project_tag_key['tag_name'])));
										// save data in draft_projects_tags table from post project form
									}
								}	
							}
							// update project revision counter
							$project_revision_counter = false;
							if($project_categories_old_values != $project_categories_new_values){
								$project_revision_counter = true;
							}
							else if($project_tag_old_values != $project_tag_new_values){
								$project_revision_counter = true;
							}
							else if(!empty(trim($this->input->post ('project_additional_information')))){
							
								$this->db->insert ('projects_additional_information', array('project_id'=>$project_id,
								'additional_information_add_date'=> date('Y-m-d H:i:s'),'additional_information'=>trim($this->input->post ('project_additional_information'))));
								$project_revision_counter = true;
							}else{
								foreach($project_compare_fields as $project_field_value){
									if($project_new_values[$project_field_value] != $project_old_values[$project_field_value]){
										$project_revision_counter = true;
										break;
									
									}
								}
							}
							
							if($project_revision_counter){
								$this->db->where ('project_id', $project_id);
								$this->db->update ('projects_open_bidding',array(
								'revisions'=> ($project_data[0]['revisions'] + 1)));
							}
							//@sid check attachment is valid or not [28-01-2019] 
							$this->get_draft_project_attachments($project_id, $user[0]->profile_name);
							
							$msg['status'] = 'SUCCESS';
							$msg['message'] = '';
							$msg['location'] = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
							echo json_encode ($msg);
						}else{
							$msg['status'] = 400;
							$msg['message'] = '';
							echo json_encode ($msg);
						}
					}else{
						echo json_encode ($msg);
						die;
					}
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
			
				}		
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
		
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	/**
	* This function is used for detail page of projects with different different status(awaiting moderation,open for bidding etc).
	*/
  public function project_detail () {
		// pre(getHostByName(getHostName()));
		$lay = array();
	
		if(empty($this->input->get('id'))){
			
			set_status_header(404);
			//$data['current_page'] = '404_default';
			########## set the default 404 title meta tag and meta description  start here #########
			$default_404_page_title_meta_tag = $this->config->item('404_page_title_meta_tag');
			$default_404_page_description_meta_tag = $this->config->item('404_page_description_meta_tag');
			$data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
			########## set the default 404 title meta tag and meta description  end here #########
			$this->layout->view ('404defaultpage/404_default', $lay, $data, 'error_404'); 
			
		} else {
			
			$project_id = $this->input->get('id');
			$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
			
			if(in_array($project_status_table_array['project_status'],array('awarded','in_progress','incomplete','completed','expired'))){
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
					$get_project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.employer_id', 'left')
					->join('users_details ud', 'ud.user_id = pd.employer_id', 'left')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					
					
					$get_project_data['project_id'] = $get_project_data ['fulltime_project_id'];
					$get_project_data['project_id'] = $get_project_data ['fulltime_project_id'];
					$get_project_data['project_owner_id'] = $get_project_data ['employer_id'];
					$get_project_data['project_expiration_date'] = $get_project_data ['fulltime_project_expiration_date'];

				} else {
					$get_project_data = $this->db // get the user detail
					->select('pd.*,u.profile_name')
					->from($project_status_table_array['table_name'].' pd')
					->join('users u', 'u.user_id = pd.project_owner_id', 'left')
					->join('users_details ud', 'ud.user_id = pd.project_owner_id', 'left')
					->where('pd.project_id', $project_id)
					->get()->row_array();
				}
				
				//$this->Bidding_model->move_awarded_expired_bid_to_active_bids_dedicated_project($get_project_data_data['project_type'],$project_id);	
				$this->Bidding_model->move_awarded_expired_bid_to_active_bids_dedicated_project($get_project_data,$project_status_table_array['project_status']);	
				/*move awarded bids whose awarded expiration time is passed to active bids tables */
			}
			$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
			$project_bid_count = 0;
			$check_sp_active_bid_exists = 0;
			$check_sp_awarded_bid_exists = 0;
			$check_sp_completed_bid_exists = 0;
			$check_sp_in_progress_bid_exists = 0;
			$project_bidder_list = array();
			$project_awarded_bidder_list = array();
			$project_completed_bidder_list = array();
			$project_inprogress_bidder_list = array();
			$project_info = array();
			
			
			if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
				
				
				$user = $this->session->userdata('user');
				$count_open_for_bidding_project = $this->db 
				->select ('id')
				->from ('projects_open_bidding')
				->where('project_id',$project_id)
				->get ()->num_rows ();
				if($count_open_for_bidding_project > 0 ){
					$this->Projects_model->move_expired_project_entry_from_open_bidding_projects_table($project_id);
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
				}
				if(!empty($project_status_table_array['table_name'])){
					$data['project_id'] = $project_id;
					if($project_status_table_array['project_status'] == 'awaiting_moderation'){
						if(!$this->session->userdata ('user')){
							redirect(VPATH.$this->config->item('signin_page_url'));
						}
						$count_awaiting_moderation_project = $this->db 
						->select ('id')
						->from ('projects_awaiting_moderation')
						->where('project_id',$project_id)
						->where('project_owner_id',$user[0]->user_id)
						->get ()->num_rows ();
						if($count_awaiting_moderation_project == 0){
							redirect (VPATH . $this->config->item('dashboard_page_url'));
						}
						//$this->Projects_model->check_update_invalid_combination_project_location('projects_awaiting_moderation','project_id',$project_id); // check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
						// fetch the project infromation from projects_awaiting_moderation table
						$this->db->select('projects_awaiting_moderation.*,users.account_type,users.is_authorized_physical_person,users.first_name,users.last_name,users.company_name,users.profile_name,users.account_validation_date,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code');
						$this->db->from('projects_awaiting_moderation');
						$this->db->join('users', 'users.user_id = projects_awaiting_moderation.project_owner_id', 'left');
						$this->db->join('counties', 'counties.id = projects_awaiting_moderation.county_id', 'left');
						$this->db->join('localities', 'localities.id = projects_awaiting_moderation.locality_id', 'left');
						$this->db->join('postal_codes', 'postal_codes.id = projects_awaiting_moderation.postal_code_id', 'left');
						$this->db->where('projects_awaiting_moderation.project_id',$project_id);
						$this->db->where('projects_awaiting_moderation.project_owner_id',$user[0]->user_id);
						$project_result = $this->db->get();
						$project_data = $project_result->row_array();
					} else if($project_status_table_array['project_status'] == 'open_for_bidding'){
						$count_open_for_bidding_project = $this->db 
						->select ('id')
						->from ('projects_open_bidding')
						->where('project_id',$project_id)
						->get ()->num_rows ();
						if($count_open_for_bidding_project == 0){
							redirect (VPATH . $this->config->item('dashboard_page_url'));
						}
						//$this->Projects_model->check_update_invalid_combination_project_location('projects_open_bidding','project_id',$project_id); // check valid combination of locality_id,county_id,postal_code_id If the combination is not valid it will update locality_id,county_id,postal_code_id  to 0.
						// fetch the project information from projects_open_bidding table
						
						$this->db->select('projects_open_bidding.*, users_details.user_avatar,users_details.project_user_total_avg_rating_as_po,users_details.fulltime_project_user_total_avg_rating_as_employer, users.gender, users.account_type,users.is_authorized_physical_person,users.first_name,users.last_name,users.company_name,users.profile_name,users.account_validation_date,counties.name as county_name, localities.name as locality_name, postal_codes.postal_code,fpuucpt.project_cover_picture_name,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
						$this->db->select('users.sync_linkedin, users.sync_facebook');
						
						$this->db->from('projects_open_bidding');
						$this->db->join('users', 'users.user_id = projects_open_bidding.project_owner_id', 'left');
						$this->db->join('users_details', 'users_details.user_id = projects_open_bidding.project_owner_id', 'left');
						$this->db->join('counties', 'counties.id = projects_open_bidding.county_id', 'left');
						$this->db->join('localities', 'localities.id = projects_open_bidding.locality_id', 'left');
						$this->db->join('postal_codes', 'postal_codes.id = projects_open_bidding.postal_code_id', 'left');
						$this->db->join('featured_projects_users_upload_cover_pictures_tracking fpuucpt', 'fpuucpt.project_id = projects_open_bidding.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						$this->db->where('projects_open_bidding.project_id',$project_id);
						$project_result = $this->db->get();
						$project_data = $project_result->row_array();
						
						

						if($project_data['project_type'] == 'fulltime') {
							$this->Bidding_model->move_awarded_expired_bid_to_active_bids_dedicated_project($project_data, $project_status_table_array['project_status']);
						}

						$featured_max = 0;$urgent_max = 0;
						$expiration_featured_upgrade_date_array = array();$expiration_urgent_upgrade_date_array = array();
						if(!empty($project_data['featured_upgrade_end_date'])){
						$expiration_featured_upgrade_date_array[] = $project_data['featured_upgrade_end_date'];
						}
						if(!empty($project_data['bonus_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data['bonus_featured_upgrade_end_date'];
							}
						if(!empty($project_data['membership_include_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data['membership_include_featured_upgrade_end_date'];
						}
						if(!empty($expiration_featured_upgrade_date_array)){
							$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
						}
						if(!empty($project_data['urgent_upgrade_end_date'])){
						$expiration_urgent_upgrade_date_array[] = $project_data['urgent_upgrade_end_date'];
						}
						if(!empty($project_data['bonus_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data['bonus_urgent_upgrade_end_date'];
							}
						if(!empty($project_data['membership_include_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data['membership_include_urgent_upgrade_end_date'];
						}
						if(!empty($expiration_urgent_upgrade_date_array)){
							$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
						}
						
						$this->Projects_model->get_featured_project_upgrade_expiration_status($project_data['project_id'],array('project_type'=>$project_data['project_type'],'project_status'=>$project_status_table_array['project_status']));
						
						if($project_data['urgent'] == 'Y' && $urgent_max < time()){
						
							
						
							$this->db->update('projects_open_bidding', ['urgent' => 'N'], ['project_id' => $project_data['project_id']]);
							
							$project_url = VPATH.($this->config->item('project_detail_page_url').'?id='.$project_data['project_id']);
							
							if($project_data['project_type'] == 'fulltime'){
							$project_upgrade_expired_user_activity_displayed_message = $this->config->item('fulltime_project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
							}else{
								$project_upgrade_expired_user_activity_displayed_message = $this->config->item('project_urgent_upgrade_expired_user_activity_log_displayed_message_sent_to_po');
							}
							$project_upgrade_expired_user_activity_displayed_message = str_replace([ '{project_title}', '{urgent_upgrade_expiration_date}', '{project_url_link}'], [htmlspecialchars($project_data['project_title'], ENT_QUOTES), date('d.m.Y H:i:s', $urgent_max), $project_url], $project_upgrade_expired_user_activity_displayed_message);
							user_display_log($project_upgrade_expired_user_activity_displayed_message, $project_data['project_owner_id']);
							
						
						}
						$login_user_detail = array();
						if($this->session->userdata ('user')){
							$login_user_detail = $this->db // get the user detail
							->select('u.user_id,ud.current_membership_plan_id')
							->from('users u')
							->join('users_details ud', 'ud.user_id = u.user_id', 'left')
							->where('u.user_id', $user[0]->user_id)
							->get()->row_array();
						}
						$data['login_user_detail'] = $login_user_detail;
						$data['upgrade_cover_picture_exist_status'] = false;
						if(!empty($project_data['project_cover_picture_name'])){
						
							$this->load->library('ftp');
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->ftp->connect($config); 
							######## connectivity of remote server end #######
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
							$profile_folder     = $project_data['profile_name'];
							$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$featured_upgrade_cover_picture.$project_data['project_cover_picture_name'];
							$file_size = $this->ftp->get_filesize($source_path);
							if($file_size != '-1'){
								$data['upgrade_cover_picture_exist_status'] = true;
							}
						}
						
						
					} else if(in_array($project_status_table_array['project_status'], array('expired','awarded','in_progress','cancelled','completed','incomplete'))){
						
						if($project_status_table_array['project_status']  == 'awarded'){
						
							$this->db->select('featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date,fpuucpt.project_cover_picture_name');
						
							$this->db->from($project_status_table_array['table_name']." as pd");
							$this->db->join('featured_projects_users_upload_cover_pictures_tracking fpuucpt', 'fpuucpt.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = pd.project_id', 'left');
							
							$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = pd.project_id', 'left');
							$this->db->where('pd.project_id',$project_id);
							
							$project_result = $this->db->get();
							$project_info = $project_result->row_array();
							
						}
					
					
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
							$count_project_exists = $this->db 
							->select ('id')
							->from ($project_status_table_array['table_name'])
							->where('fulltime_project_id',$project_id)
							->get ()->num_rows ();
						} else {
							$count_project_exists = $this->db 
							->select ('id')
							->from ($project_status_table_array['table_name'])
							->where('project_id',$project_id)
							->get ()->num_rows ();
						}
						
						if($count_project_exists == 0){
							redirect (VPATH . $this->config->item('dashboard_page_url'));
						}
						// fetch the project infromation from projects_open_bidding table
						$this->db->select('pd.*,users_details.user_avatar, users.gender, users.account_type,users.is_authorized_physical_person,users.first_name,users.last_name,users.company_name,users.profile_name,users.account_validation_date,users.sync_linkedin, users.sync_facebook,counties.name as county_name, localities.name as locality_name, postal_codes.postal_code');
						$this->db->from($project_status_table_array['table_name'].' as pd');
						$this->db->join('counties', 'counties.id = pd.county_id', 'left');
						$this->db->join('localities', 'localities.id = pd.locality_id', 'left');
						$this->db->join('postal_codes', 'postal_codes.id = pd.postal_code_id', 'left');
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
							$this->db->join('users_details', 'users_details.user_id = pd.employer_id', 'left');
							$this->db->join('users', 'users.user_id = pd.employer_id', 'left');
							$this->db->where('pd.fulltime_project_id',$project_id);
						} else {
							$this->db->join('users_details', 'users_details.user_id = pd.project_owner_id', 'left');
								$this->db->join('users', 'users.user_id = pd.project_owner_id', 'left');
							$this->db->where('pd.project_id',$project_id);
						}
						$project_result = $this->db->get();
						$project_data = $project_result->row_array();
						
						$project_data = array_merge($project_data,$project_info);
						
						
						
						
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') { 
							$project_data['project_id'] = $project_data['fulltime_project_id'];
							$project_data['project_owner_id'] = $project_data['employer_id'];
							$project_data['project_posting_date'] = $project_data['fulltime_project_posting_date'];
							$project_data['project_expiration_date'] = $project_data['fulltime_project_expiration_date'];
							$project_data['project_title'] = $project_data['fulltime_project_title'];
							$project_data['project_description'] = $project_data['fulltime_project_description'];
							$project_data['min_budget'] = $project_data['min_salary'];
							$project_data['max_budget'] = $project_data['max_salary'];
							$project_data['project_cancellation_date'] = $project_data['fulltime_project_cancellation_date'];
						}
					}
					####check the avatar image of PO if there is a any dishappency then reset the PO avatar image from disk/database start#####
					if(in_array($project_status_table_array['project_status'], array('expired','awarded','in_progress','cancelled','completed','open_for_bidding','incomplete'))){
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] = FTP_PORT;
						$config['debug'] = TRUE;
						$this->ftp->connect($config);
						//avatar picture
						//start check avatar from ftp server
						$user_avatar = USER_AVATAR;
						$source_path_avatar = USERS_FTP_DIR . $project_data['profile_name'] . $user_avatar;
						$avatarlist = $this->ftp->list_files($source_path_avatar);
						$avatar_pic = $source_path_avatar . $project_data['user_avatar'];

						$exap = explode('.', $project_data['user_avatar']);
						$original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';
						
						if (count($avatarlist) > 0) {
							$acheck = true;
							if (!in_array($avatar_pic, $avatarlist) && $acheck) {
								$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $project_data['project_owner_id']));
								$this->ftp->delete_dir($source_path_avatar);
								$project_data['user_avatar'] = '';
								$acheck = false;
							} if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
								$this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$project_data['project_owner_id']));
								$this->ftp->delete_dir($source_path_avatar);
								$project_data['user_avatar'] = '';
								$acheck = false;
							}
						} if (count($avatarlist) == 0 && $project_data['user_avatar'] != '') {
							$this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $project_data['project_owner_id']));
							 $project_data['user_avatar'] = '';
						}
						
						$data['upgrade_cover_picture_exist_status'] = false;
						if(($project_status_table_array['project_status']  == 'awarded' || $project_status_table_array['project_status']  == 'open_for_bidding') && !empty($project_data['project_cover_picture_name'])){
							
							
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_awarded_dir = PROJECT_AWARDED_DIR;
							$featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
							
							if($project_status_table_array['project_status']  == 'awarded'){
								$project_status_dir = PROJECT_AWARDED_DIR;
							}else if($project_status_table_array['project_status']  == 'open_for_bidding'){
								$project_status_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							}
							
							
							
							$source_path = $users_ftp_dir.$project_data['profile_name'].$projects_ftp_dir.$project_status_dir.$project_id.$featured_upgrade_cover_picture.$project_data['project_cover_picture_name'];
							$file_size = $this->ftp->get_filesize($source_path);
							if($file_size != '-1'){
								$data['upgrade_cover_picture_exist_status'] = true;
							}
							//die($source_path);
						}
						
					}
					
					if(in_array($project_status_table_array['project_status'], array('open_for_bidding','expired','awarded','in_progress','cancelled','completed','incomplete'))){
					
						############### Statistics as Project Owner end ###########
						
						$already_in_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $project_data['project_owner_id']])->row_array();
						$already_in_project_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $project_data['project_owner_id'], 'project_id' => $project_data['project_id']])->row_array();
						if(!empty($already_in_contact) && !empty($already_in_project_contact)) {
							$data['is_general_chat'] = false;
						} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
							$data['is_general_chat'] = true;
						}
						if($project_status_table_array['project_status'] != 'open_for_bidding' && $project_status_table_array['project_status'] != 'awarded'){
							$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($project_data['profile_name'],$project_data['project_id']);
						} 
						$this->Bidding_model->check_and_remove_orphan_entries_from_disk_database_project_bid_attachments($project_data['project_id'],$project_status_table_array['project_status'],$project_data['profile_name']);//removed the orphans entries of bid attachment from disk and database
						/* die($project_status_table_array['project_status']); */
					}
					############# fetch the additional information of project 
					if($project_status_table_array['project_status'] != 'awaiting_moderation'){
						
						$this->db->select('*');
						$this->db->from('projects_additional_information');
						$this->db->where('project_id',$project_id);
						$this->db->order_by('additional_information_add_date','desc');
						$project_additional_information_result = $this->db->get();
						$project_additional_information_data = $project_additional_information_result->result_array();
						$data['project_additional_information_data'] = $project_additional_information_data;
						
					}
					if($this->session->userdata ('user') && $project_status_table_array['project_status'] != 'awaiting_moderation'){
						
						$check_sp_active_bid_exists = $this->Bidding_model->check_sp_active_bid_exists_project($project_data['project_id'],$user[0]->user_id,$project_data['project_type']); // check login user apply for bid
						$check_sp_awarded_bid_exists = $this->Bidding_model->check_sp_awarded_bid_exists($project_data['project_id'],$user[0]->user_id,$project_data['project_type']); // check login user apply for bid and his bid is exists in awarded status
						
						$check_sp_in_progress_bid_exists = $this->Bidding_model->check_sp_in_progress_bid_exists($project_data['project_id'],$user[0]->user_id,$project_data['project_type']); // check login user apply for bid and his bid is exists in inprogress status
						
						/* if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly') { */
							$check_sp_in_complete_bid_exists = $this->Bidding_model->check_sp_incomplete_bid_exists($project_data['project_id'],$user[0]->user_id,$project_data['project_type']); // check login user apply for bid and his bid exists incomplete status
							
							$check_sp_disputed_bid_exists = $this->Projects_disputes_model->check_sp_dispute_bid_exists_project_details($project_data['project_id'],$user[0]->user_id,$project_data['project_type']); // check login user apply for bid and his bid exists incomplete status
						/* }else{
						
							$check_sp_in_complete_bid_exists = 0;
							$check_sp_disputed_bid_exists = 0;
						} */
						if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly') {
							$check_sp_completed_bid_exists = $this->Bidding_model->check_sp_completed_bid_exists($project_data['project_id'],$user[0]->user_id,$project_data['project_type']);
						}
					}
					
					if(($project_data['project_type'] != 'fulltime' && in_array($project_status_table_array['project_status'], array('open_for_bidding','expired','awarded','in_progress','completed','incomplete'))) || 
					($project_data['project_type'] == 'fulltime' && in_array($project_status_table_array['project_status'], array('open_for_bidding','expired','cancelled')))){
						
						
						// $conn = new connect_cassandra_db();
						$bidder_list = []; // In-progress
						$completed_bidder_list = []; // completed
						$project_incomplete_bidder_list = []; // in-complete
						$project_active_disputes_listing = []; // active dispute
						if(!$this->session->userdata ('user') || ($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id'])) {
							
							$project_inprogress_bidder_list =  $bidder_list =  $this->Bidding_model->get_project_inprogress_bidder_list($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$project_data['project_owner_id']); // list of in progress bidders
							
							
							if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly') {
								$project_incomplete_bidder_list =  $this->Bidding_model->get_project_incomplete_bidder_list($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$project_data['project_owner_id']); // list of completed bidders
							}
							if($this->session->userdata ('user') && $user[0]->user_id == $project_data['project_owner_id']){
								
							//list of active dipsutes for project owner
								$project_active_disputes_listing =  $this->Projects_disputes_model->get_project_active_disputes_list_project_details($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status']);
								
							}
							if($project_data['project_type'] != 'fulltime'){
								$project_completed_bidder_list = $completed_bidder_list = $this->Bidding_model->get_project_completed_bidder_list($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$project_data['project_owner_id']); // list of completed bidders
							}
							
						} else if($this->session->userdata ('user')) {
									
							
							$already_in_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $project_data['project_owner_id']])->row_array();
							$already_in_project_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $project_data['project_owner_id'], 'project_id' => $project_data['project_id']])->row_array();
						
							if(!empty($already_in_contact) && empty($already_in_project_contact)) {
								$data['is_general_chat'] = true;
							}
							$open_bidding_chat_data_count = $this->conn->get_users_conversations_on_project($project_data['project_owner_id'], $user[0]->user_id, $project_data['project_id'], $this->config->item('project_details_page_messages_tab_users_conversation_listing_limit'));
							
							$data['open_bidding_chat_data_count'] = $open_bidding_chat_data_count->count();
							
							$project_inprogress_bidder_list =  $this->Bidding_model->get_project_inprogress_bidder_list_sp($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id); // list of in progress bidders
							
							$inprogress_bidder_data = $this->Bidding_model->get_sp_inprogress_bid_detail($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id);
							
							############ for incomplete bid data start here ###
							$active_dispute_bidder_data = $this->Projects_disputes_model->get_sp_project_active_dispute_bid_detail_project_details($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id);
							
							
							if($project_data['project_type'] == 'fixed' || $project_data['project_type'] == 'hourly') {
								$project_incomplete_bidder_list =  $this->Bidding_model->get_project_incomplete_bidder_list_sp($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id); // list of in progress bidders
							
								$incomplete_bidder_data = $this->Bidding_model->get_sp_incomplete_bid_detail($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id);
								
								
							}else{
							
								$project_incomplete_bidder_list = $incomplete_bidder_data =  array();
							}
							
							
							############ for incomplete bid data end here ###
							
							$data['inprogress_bidder_data'] = $inprogress_bidder_data;
							$data['incomplete_bidder_data'] = $incomplete_bidder_data;
							$data['active_dispute_bidder_data'] = $active_dispute_bidder_data;
							if(!empty($inprogress_bidder_data) && array_key_exists('user_id', $inprogress_bidder_data)) {
								array_push($bidder_list, $inprogress_bidder_data); //in progress bidder data
							}
							if(!empty($incomplete_bidder_data) && array_key_exists('user_id', $incomplete_bidder_data)) {
								array_push($bidder_list, $incomplete_bidder_data); //in progress bidder data
							}
							if(!empty($active_dispute_bidder_data) && array_key_exists('user_id', $active_dispute_bidder_data)) {
								array_push($bidder_list, $active_dispute_bidder_data); //in progress bidder data
							}
							#################################################
							if($project_data['project_type'] != 'fulltime'){
								$project_completed_bidder_list =  $this->Bidding_model->get_project_completed_bidder_list_sp($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id); // list of in progress bidders
								
								$completed_bidder_data = $this->Bidding_model->get_sp_completed_bid_detail($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id);
								$data['completed_bidder_data'] = $completed_bidder_data;
								
								if(!empty($completed_bidder_data) && array_key_exists('user_id', $completed_bidder_data)) {
									array_push($completed_bidder_list, $completed_bidder_data); //completed bidder data
								}
							}
						}
						
						$sender = $this->db->get_where('users', ['user_id' => $project_data['project_owner_id']])->row_array();
						$sender_profile = $this->db->get_where('users_details', ['user_id' => $project_data['project_owner_id']])->row_array();
						$profile_name = [];
						
						if(!empty($bidder_list)) {
							foreach($bidder_list as $bidder) {	

								if($project_data['project_type'] == 'fulltime') {
									$data['total_paid_amount'][$bidder['winner_id']] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'], array('fulltime_project_id'=>$project_data['project_id'],'employer_id'=>$project_data['project_owner_id'],'employee_id'=>$bidder['winner_id']));
								} else {
									$data['total_paid_amount'][$bidder['winner_id']] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'], array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bidder['winner_id']));
								}

								if($this->session->userdata('user') && $bidder['winner_id'] == $user[0]->user_id) {
									$data['user_already_place_bid'] = true;
									if(!empty($already_in_contact) && !empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									}
								} 
								
								$receiver = $this->db->get_where('users', ['user_id' => $bidder['winner_id']])->row_array();
								$receiver_profile = $this->db->get_where('users_details', ['user_id' => $bidder['winner_id']])->row_array();
								if(!empty($sender)) {
									$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
									   $common_source_path = USERS_FTP_DIR . $sender['profile_name'];
									   $user_avatar = USER_AVATAR;
									   $source_path_avatar = $common_source_path . $user_avatar;
									   ######## connectivity of remote server start #######
									   $this->load->library('ftp');
									   $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
									   $config['ftp_username'] = FTP_USERNAME;
									   $config['ftp_password'] = FTP_PASSWORD;
									   $config['ftp_port'] = FTP_PORT;
									   $config['debug'] = TRUE;
									   $this->ftp->connect($config);
									   $avatarlist = $this->ftp->list_files($source_path_avatar);
									   $avatar_pic = $source_path_avatar . $sender_profile['user_avatar'];
														   
									   if (!empty($sender_profile['user_avatar'])) {
										   $file_size = $this->ftp->get_filesize($avatar_pic);
										   if ($file_size != '-1') {
											$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
											}
									   }else { 
										if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
											if($sender['gender'] == 'M'){
													$sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($sender['gender'] == 'F'){
												$sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
											$sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();
									if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
										$profile_name[$sender['user_id']] = ['profile' => $sender['first_name'].' '.$sender['last_name']];
									} else {
										$profile_name[$sender['user_id']] = ['profile' => $sender['company_name']];
									}	
									$profile_name[$sender['user_id']]['avatar'] = $sender_profile_picture;
								}
								if(!empty($receiver)) {
								   $receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
								   $common_source_path_receiver = USERS_FTP_DIR . $receiver['profile_name'];
								   $user_avatar_receiver = USER_AVATAR;
								   $source_path_avatar_receiver = $common_source_path_receiver . $user_avatar_receiver;
								   ######## connectivity of remote server start #######
								   $this->load->library('ftp');
								   $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								   $config['ftp_username'] = FTP_USERNAME;
								   $config['ftp_password'] = FTP_PASSWORD;
								   $config['ftp_port'] = FTP_PORT;
								   $config['debug'] = TRUE;
								   $this->ftp->connect($config);
											 
								   $avatarlist_receiver = $this->ftp->list_files($source_path_avatar_receiver);
								   $avatar_pic_receiver = $source_path_avatar_receiver . $receiver_profile['user_avatar'];
													   
								   if (!empty($receiver_profile['user_avatar'])) {
									   $file_size = $this->ftp->get_filesize($avatar_pic_receiver);
									   if ($file_size != '-1') {
										$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
									}
								   }else { 									
										if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
											if($receiver['gender'] == 'M'){
													$receiver_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($receiver['gender'] == 'F'){
												$receiver_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
												$receiver_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();
									if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['first_name'].' '.$receiver['last_name']];
									} else {
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['company_name']];
									}
									$profile_name[$receiver['user_id']]['avatar'] = $receiver_profile_picture;
								} 
								
								if($this->session->userdata ('user')) {
									$data['project_chat_unread_messages_count'][$bidder['winner_id']] = $this->conn->get_unread_chat_messages_count($user[0]->user_id, $project_data['project_id'], ($user[0]->user_id != $bidder['winner_id']) ? $bidder['winner_id'] : $project_data['project_owner_id'] );
								}
							}
						}
						
						$data['profile_pic'] = $profile_name;
						$profile_name = [];
						
						if(!empty($completed_bidder_list)) {
							
							foreach($completed_bidder_list as $bidder) {	
								
								$data['total_paid_amount'][$bidder['winner_id']] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'], array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bidder['winner_id']));
								
								if($this->session->userdata('user') && $bidder['winner_id'] == $user[0]->user_id) {
									$data['user_already_place_bid'] = true;
									if(!empty($already_in_contact) && !empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									}
								} 

								$receiver = $this->db->get_where('users', ['user_id' => $bidder['winner_id']])->row_array();
								$receiver_profile = $this->db->get_where('users_details', ['user_id' => $bidder['winner_id']])->row_array();
								if(!empty($sender)) {
									   $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
									   $common_source_path = USERS_FTP_DIR . $sender['profile_name'];
									   $user_avatar = USER_AVATAR;
									   $source_path_avatar = $common_source_path . $user_avatar;
									   ######## connectivity of remote server start #######
									   $this->load->library('ftp');
									   $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
									   $config['ftp_username'] = FTP_USERNAME;
									   $config['ftp_password'] = FTP_PASSWORD;
									   $config['ftp_port'] = FTP_PORT;
									   $config['debug'] = TRUE;
									   $this->ftp->connect($config);
									   $avatarlist = $this->ftp->list_files($source_path_avatar);
									   $avatar_pic = $source_path_avatar . $sender_profile['user_avatar'];
														   
									   if (!empty($sender_profile['user_avatar'])) {
										   $file_size = $this->ftp->get_filesize($avatar_pic);
										   if ($file_size != '-1') {
											$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
											}
									   }else { 									
										if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
											if($sender['gender'] == 'M'){
													$sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($sender['gender'] == 'F'){
												$sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
											$sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();
									if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
										$profile_name[$sender['user_id']] = ['profile' => $sender['first_name'].' '.$sender['last_name']];
									} else {
										$profile_name[$sender['user_id']] = ['profile' => $sender['company_name']];
									}	
									$profile_name[$sender['user_id']]['avatar'] = $sender_profile_picture;
								}
								if(!empty($receiver)) {
									$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
									$common_source_path_receiver = USERS_FTP_DIR . $receiver['profile_name'];
								   $user_avatar_receiver = USER_AVATAR;
								   $source_path_avatar_receiver = $common_source_path_receiver . $user_avatar_receiver;
								   ######## connectivity of remote server start #######
								   $this->load->library('ftp');
								   $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
								   $config['ftp_username'] = FTP_USERNAME;
								   $config['ftp_password'] = FTP_PASSWORD;
								   $config['ftp_port'] = FTP_PORT;
								   $config['debug'] = TRUE;
								   $this->ftp->connect($config);
											 
								   $avatarlist_receiver = $this->ftp->list_files($source_path_avatar_receiver);
								   $avatar_pic_receiver = $source_path_avatar_receiver . $receiver_profile['user_avatar'];
													   
								   if (!empty($receiver_profile['user_avatar'])) {
									   $file_size = $this->ftp->get_filesize($avatar_pic_receiver);
									   if ($file_size != '-1') {
										$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
									}
								   }else { 									
										if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
											if($receiver['gender'] == 'M'){
													$receiver_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($receiver['gender'] == 'F'){
												$receiver_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
												$receiver_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();
									if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['first_name'].' '.$receiver['last_name']];
									} else {
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['company_name']];
									}
									$profile_name[$receiver['user_id']]['avatar'] = $receiver_profile_picture;
								} 
								
								if($this->session->userdata ('user')) {
									$data['completed_project_chat_unread_messages_count'][$bidder['winner_id']] = $this->conn->get_unread_chat_messages_count($user[0]->user_id, $project_data['project_id'], ($user[0]->user_id != $bidder['winner_id']) ? $bidder['winner_id'] : $project_data['project_owner_id'] );
								}
							}
						}
						$data['completed_profile_pic'] = $profile_name;

						$profile_name = [];
						if(!empty($project_active_disputes_listing)) {
							foreach($project_active_disputes_listing as $bidder) {	
								
								
								if($project_data['project_type'] == 'fulltime') {
									$data['total_paid_amount'][$bidder['winner_id']] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'], array('fulltime_project_id'=>$project_data['project_id'],'employer_id'=>$project_data['project_owner_id'],'employee_id'=>$bidder['winner_id']));
								} else {
									$data['total_paid_amount'][$bidder['winner_id']] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'], array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bidder['winner_id']));
								}
								if($this->session->userdata('user') && $bidder['winner_id'] == $user[0]->user_id) {
									$data['user_already_place_bid'] = true;
									if(!empty($already_in_contact) && !empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									}
								} 

								$receiver = $this->db->get_where('users', ['user_id' => $bidder['winner_id']])->row_array();
								$receiver_profile = $this->db->get_where('users_details', ['user_id' => $bidder['winner_id']])->row_array();
								if(!empty($sender)) {
									$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
									$common_source_path = USERS_FTP_DIR . $sender['profile_name'];
									$user_avatar = USER_AVATAR;
									$source_path_avatar = $common_source_path . $user_avatar;
									######## connectivity of remote server start #######
									$this->load->library('ftp');
									$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
									$config['ftp_username'] = FTP_USERNAME;
									$config['ftp_password'] = FTP_PASSWORD;
									$config['ftp_port'] = FTP_PORT;
									$config['debug'] = TRUE;
									$this->ftp->connect($config);
									$avatarlist = $this->ftp->list_files($source_path_avatar);
									$avatar_pic = $source_path_avatar . $sender_profile['user_avatar'];
														
									if (!empty($sender_profile['user_avatar'])) {
										$file_size = $this->ftp->get_filesize($avatar_pic);
										if ($file_size != '-1') {
										 $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
										 }
									}else {									
										if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
											if($sender['gender'] == 'M'){
													$sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($sender['gender'] == 'F'){
												$sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
											$sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();

									if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
										$profile_name[$sender['user_id']] = ['profile' => $sender['first_name'].' '.$sender['last_name']];
									} else {
										$profile_name[$sender['user_id']] = ['profile' => $sender['company_name']];
									}	
									$profile_name[$sender['user_id']]['avatar'] = $sender_profile_picture;
								}
								if(!empty($receiver)) {
									$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
										$common_source_path_receiver = USERS_FTP_DIR . $receiver['profile_name'];
										$user_avatar_receiver = USER_AVATAR;
										$source_path_avatar_receiver = $common_source_path_receiver . $user_avatar_receiver;
										######## connectivity of remote server start #######
										$this->load->library('ftp');
										$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
										$config['ftp_username'] = FTP_USERNAME;
										$config['ftp_password'] = FTP_PASSWORD;
										$config['ftp_port'] = FTP_PORT;
										$config['debug'] = TRUE;
										$this->ftp->connect($config);
												  
										$avatarlist_receiver = $this->ftp->list_files($source_path_avatar_receiver);
										$avatar_pic_receiver = $source_path_avatar_receiver . $receiver_profile['user_avatar'];
															
										if (!empty($receiver_profile['user_avatar'])) {
											$file_size = $this->ftp->get_filesize($avatar_pic_receiver);
											if ($file_size != '-1') {
											 $receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
										 }
										}else { 
										if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
											if($receiver['gender'] == 'M'){
													$receiver_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($receiver['gender'] == 'F'){
												$receiver_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
												$receiver_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();
									if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['first_name'].' '.$receiver['last_name']];
									} else {
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['company_name']];
									}
									$profile_name[$receiver['user_id']]['avatar'] = $receiver_profile_picture;
								} 
								
								if($this->session->userdata ('user')) {
									$data['completed_project_chat_unread_messages_count'][$bidder['winner_id']] = $this->conn->get_unread_chat_messages_count($user[0]->user_id, $project_data['project_id'], ($user[0]->user_id != $bidder['winner_id']) ? $bidder['winner_id'] : $project_data['project_owner_id'] );
								}
							}
						}
						$data['active_disputes_profile_pic'] = $profile_name;

						$profile_name = [];
						if(!empty($project_incomplete_bidder_list)) {
							foreach($project_incomplete_bidder_list as $bidder) {	
								
								$data['total_paid_amount'][$bidder['winner_id']] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($project_data['project_type'], array('project_id'=>$project_data['project_id'],'project_owner_id'=>$project_data['project_owner_id'],'winner_id'=>$bidder['winner_id']));
								
								if($this->session->userdata('user') && $bidder['winner_id'] == $user[0]->user_id) {
									$data['user_already_place_bid'] = true;
									if(!empty($already_in_contact) && !empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									}
								} 

								$receiver = $this->db->get_where('users', ['user_id' => $bidder['winner_id']])->row_array();
								$receiver_profile = $this->db->get_where('users_details', ['user_id' => $bidder['winner_id']])->row_array();
								if(!empty($sender)) {
									$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
									$common_source_path = USERS_FTP_DIR . $sender['profile_name'];
									$user_avatar = USER_AVATAR;
									$source_path_avatar = $common_source_path . $user_avatar;
									######## connectivity of remote server start #######
									$this->load->library('ftp');
									$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
									$config['ftp_username'] = FTP_USERNAME;
									$config['ftp_password'] = FTP_PASSWORD;
									$config['ftp_port'] = FTP_PORT;
									$config['debug'] = TRUE;
									$this->ftp->connect($config);
									$avatarlist = $this->ftp->list_files($source_path_avatar);
									$avatar_pic = $source_path_avatar . $sender_profile['user_avatar'];
														
									if (!empty($sender_profile['user_avatar'])) {
										$file_size = $this->ftp->get_filesize($avatar_pic);
										if ($file_size != '-1') {
											$sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
											}
									}else { 									
										if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
											if($sender['gender'] == 'M'){
													$sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($sender['gender'] == 'F'){
												$sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
											$sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}									
									//end check avatar
									$this->ftp->close();
									if(($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
										$profile_name[$sender['user_id']] = ['profile' => $sender['first_name'].' '.$sender['last_name']];
									} else {
										$profile_name[$sender['user_id']] = ['profile' => $sender['company_name']];
									}	
									$profile_name[$sender['user_id']]['avatar'] = $sender_profile_picture;
								}
								if(!empty($receiver)) {
									$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];

										$common_source_path_receiver = USERS_FTP_DIR . $receiver['profile_name'];
										$user_avatar_receiver = USER_AVATAR;
										$source_path_avatar_receiver = $common_source_path_receiver . $user_avatar_receiver;
										######## connectivity of remote server start #######
										$this->load->library('ftp');
										$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
										$config['ftp_username'] = FTP_USERNAME;
										$config['ftp_password'] = FTP_PASSWORD;
										$config['ftp_port'] = FTP_PORT;
										$config['debug'] = TRUE;
										$this->ftp->connect($config);
													
										$avatarlist_receiver = $this->ftp->list_files($source_path_avatar_receiver);
										$avatar_pic_receiver = $source_path_avatar_receiver . $receiver_profile['user_avatar'];
															
										if (!empty($receiver_profile['user_avatar'])) {
											$file_size = $this->ftp->get_filesize($avatar_pic_receiver);
											if ($file_size != '-1') {
												$receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
											}
										}else { 
										if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
											if($receiver['gender'] == 'M'){
													$receiver_profile_picture = URL . 'assets/images/avatar_default_male.png';
											}if($receiver['gender'] == 'F'){
												$receiver_profile_picture = URL . 'assets/images/avatar_default_female.png';
											}
										} else {
												$receiver_profile_picture = URL . 'assets/images/avatar_default_company.png';
										}
									}
									//end check avatar
									$this->ftp->close();
									if(($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['first_name'].' '.$receiver['last_name']];
									} else {
										$profile_name[$receiver['user_id']] = ['profile' => $receiver['company_name']];
									}
									$profile_name[$receiver['user_id']]['avatar'] = $receiver_profile_picture;
								} 
								
								if($this->session->userdata ('user')) {
									$data['completed_project_chat_unread_messages_count'][$bidder['winner_id']] = $this->conn->get_unread_chat_messages_count($user[0]->user_id, $project_data['project_id'], ($user[0]->user_id != $bidder['winner_id']) ? $bidder['winner_id'] : $project_data['project_owner_id'] );
								}
							}
						}
						$data['incomplete_profile_pic'] = $profile_name;
	
						
						
						
						
						
						if(!$this->session->userdata ('user') || ($this->session->userdata ('user') && ($user[0]->user_id == $project_data['project_owner_id'] || $check_sp_awarded_bid_exists == 0))){
							$project_awarded_bidder_list = $this->Bidding_model->get_project_awarded_bidder_list($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status']); // list of awarded bidders include SP
							foreach($project_awarded_bidder_list as $bidder) {
								if($this->session->userdata('user') && $bidder['user_id'] == $user[0]->user_id) {
									
									$data['user_already_place_bid'] = true;
									if(!empty($already_in_contact) && !empty($already_in_project_contact) ) {
										$data['is_general_chat'] = false;
									} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
										$data['is_general_chat'] = false;
									}
								} 
							}
						} else if($this->session->userdata ('user') && $check_sp_awarded_bid_exists > 0){
							$project_awarded_bidder_list = $this->Bidding_model->get_project_awarded_bidder_list_sp($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id); // list of awarded bidders but exclude SP
							$awarded_bidder_data = $this->Bidding_model->get_sp_awarded_bid_detail($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id);
							$data['awarded_bidder_data'] = $awarded_bidder_data;
							if($this->session->userdata('user') && $awarded_bidder_data['user_id'] == $user[0]->user_id) {
								
								$data['user_already_place_bid'] = true;
								if(!empty($already_in_contact) && !empty($already_in_project_contact) ) {
									$data['is_general_chat'] = false;
								} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
									$data['is_general_chat'] = false;
								}
							}
						}
					}
					
					if(in_array($project_status_table_array['project_status'], array('open_for_bidding','expired','awarded','in_progress','cancelled','completed','incomplete'))){
						if($this->session->userdata ('user') && $check_sp_active_bid_exists > 0){
							$project_bidder_list = $this->Bidding_model->get_project_bidder_list_exclude_bidder($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status'],$user[0]->user_id); // list of bidders
							
							
							
							// get bidder data seprately  start
							$active_bidder_data = $this->Bidding_model->get_user_bid_detail($project_data['project_id'],$user[0]->user_id,$project_data['project_type'],$project_status_table_array['project_status'],$project_data['profile_name']);
							// get bidder data seprately  end
							
							array_unshift($project_bidder_list,$active_bidder_data);
						} else {
							
							$project_bidder_list = $this->Bidding_model->get_project_bidder_list($project_data['project_id'],$project_data['project_type'],$project_data['profile_name'],$project_status_table_array['project_status']); // list of bidders
						
							
						}
						foreach($project_bidder_list as $bidder) {
							if($this->session->userdata('user') && $bidder['user_id'] == $user[0]->user_id) {
								
								$data['user_already_place_bid'] = true;
								if(!empty($already_in_contact) && !empty($already_in_project_contact) ) {
									$data['is_general_chat'] = false;
								} else if(!empty($already_in_contact) && empty($already_in_project_contact)) {
									$data['is_general_chat'] = false;
								}
							} 
						}
						
					}
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
						$po_id = $project_data['employer_id'];
					} else {
						$po_id = $project_data['project_owner_id'];
					}
					$po_detail = $this->db // get the user detail
					->select('u.user_id,u.account_type,u.is_authorized_physical_person, u.first_name, u.last_name, u.company_name, u.gender, u.profile_name,ud.fulltime_project_user_total_avg_rating_as_employer,ud.project_user_total_avg_rating_as_po')
					->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer where feedback_recived_by_employer_id = u.user_id AND employer_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
					->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po where feedback_recived_by_po_id = u.user_id AND po_already_placed_feedback= "Y") as project_user_total_reviews')
					->from('users u')
					->join('users_details ud', 'ud.user_id = u.user_id', 'left')
					->where('u.user_id', $po_id)
					->get()->row_array();
					if($project_data['project_type'] == 'fulltime'){
						$po_published_projects = $this->Projects_model->get_po_published_fulltime_projects_count($po_id);
						$get_po_hires_sp_on_fulltime_projects_count = $this->Projects_model->get_po_hires_sp_on_fulltime_projects_count($po_id);
						$data['get_po_hires_sp_on_fulltime_projects_count'] = $get_po_hires_sp_on_fulltime_projects_count;
						$po_rating = $po_detail['fulltime_project_user_total_avg_rating_as_employer'];
						$po_reviews = $po_detail['fulltime_project_user_total_reviews'];
					}else{
						$po_published_projects = $this->Projects_model->get_po_published_projects_count($po_id);
						$data['po_published_projects'] = $po_published_projects;
						$po_completed_projects_count_via_portal = $this->Bidding_model->get_sp_completed_projects_count(array('project_owner_id'=>$po_id,'project_completion_method'=>'via_portal'));
						$data['po_completed_projects_count_via_portal'] = $po_completed_projects_count_via_portal;
						$po_rating = $po_detail['project_user_total_avg_rating_as_po'];
						$po_reviews = $po_detail['project_user_total_reviews'];
					}
					$project_bid_count = $this->Bidding_model->get_project_bid_count($project_data['project_id'],$project_data['project_type']); // get bid count of project
					$data['po_published_projects'] = $po_published_projects;
					$data['po_rating'] = $po_rating;
					$data['po_reviews'] = $po_reviews;
					if($project_status_table_array['project_status'] != 'awaiting_moderation'){
						$data['page_visits'] = $this->Projects_model->save_projects_details_pages_visits_and_get_count($project_data['project_id']);
					}
					$data['project_attachment_data'] = $this->Projects_model->get_project_attachments($project_id,$project_data['profile_name'],$project_status_table_array['project_status']);
					$data['project_tag_data'] = $this->Projects_model->get_project_tags($project_id,$project_status_table_array['project_status']);
					$data['project_category_data'] = $this->Projects_model->get_project_categories($project_id,$project_status_table_array['project_status']);
					$data['project_status'] = $project_status_table_array['project_status'];
					
					$data['project_id'] = $project_id;
					$data['project_data'] = $project_data;
					$data['project_bid_count'] = $project_bid_count;
					$data['check_sp_active_bid_exists'] = $check_sp_active_bid_exists;
					$data['project_bidder_list'] = $project_bidder_list;
					
					$data['check_sp_awarded_bid_exists'] = $check_sp_awarded_bid_exists;
					
					$data['project_awarded_bidder_list'] = $project_awarded_bidder_list;
					$data['check_sp_in_progress_bid_exists'] = $check_sp_in_progress_bid_exists;
					$data['check_sp_in_complete_bid_exists'] = $check_sp_in_complete_bid_exists;
					$data['check_sp_disputed_bid_exists'] = $check_sp_disputed_bid_exists;
					$data['check_sp_completed_bid_exists'] = $check_sp_completed_bid_exists;
					$data['project_inprogress_bidder_list'] = $project_inprogress_bidder_list;
					$data['project_incomplete_bidder_list'] = $project_incomplete_bidder_list;
					$data['project_active_disputes_listing'] = $project_active_disputes_listing;
					$data['project_completed_bidder_list'] = $project_completed_bidder_list;
					
					######################## notification subscribe/unsubscribe ##################
					$name = $project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ? $project_data['first_name'] . ' ' . $project_data['last_name'] : $project_data['company_name'];
					$project_details_page_tooltip_subscribe_notifications_txt = $this->config->item('project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt');
					$project_details_page_tooltip_subscribe_notifications_txt = str_replace('{user_first_name_last_name_or_company_name}', $name, $project_details_page_tooltip_subscribe_notifications_txt);
					$data['project_details_page_tooltip_subscribe_to_po_new_projects_posted_notifications_txt'] = $project_details_page_tooltip_subscribe_notifications_txt;
					
					$project_details_page_tooltip_unsubscribe_notifications_txt = $this->config->item('project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt');
					
					$project_details_page_tooltip_unsubscribe_notifications_txt = str_replace('{user_first_name_last_name_or_company_name}', $name, $project_details_page_tooltip_unsubscribe_notifications_txt);
					$data['project_details_page_tooltip_unsubscribe_to_po_new_projects_posted_notifications_txt'] = $project_details_page_tooltip_unsubscribe_notifications_txt;
					######################## meta tag and meta description ##################
					
					$project_title_meta_tag = $project_data['project_title'];

					$project_title_meta_tag = htmlspecialchars($project_title_meta_tag, ENT_QUOTES);
					$project_title_meta_tag = get_correct_string_based_on_limit($project_title_meta_tag, $this->config->item('project_title_meta_tag_character_limit'));
					
					$project_title_meta_tag = $project_title_meta_tag;
					$project_description_meta_tag = htmlspecialchars($project_data['project_description'], ENT_QUOTES);
					$project_description_meta_tag = get_correct_string_based_on_limit($project_description_meta_tag, $this->config->item('project_description_meta_description_character_limit'));
					$data['meta_tag'] = '<title>' . $project_title_meta_tag . '</title><meta name="description" content="' . $project_description_meta_tag . '"/>';
					
					$data['current_page'] = 'project_detail';
					$_SESSION['share_title_short'] = $project_title_meta_tag;
					$_SESSION['share_description'] = get_correct_string_based_on_limit(htmlspecialchars($project_data['project_description'], ENT_QUOTES), $this->config->item('facebook_and_linkedin_share_project_description_character_limit'));
					$_SESSION['share_url'] = base_url().$this->config->item('project_detail_page_url').'?id='.$project_data['project_id'];

					$_SESSION['share_image'] = URL.$this->config->item('facebook_share_image_path').'?'.time();
					$_SESSION['share_image_height'] = $this->config->item('facebook_share_image_height');
					$_SESSION['share_image_width'] = $this->config->item('facebook_share_image_width');

					if(!$this->session->userdata('user') && !empty($_GET["rfrd"])) {
						$cookie= array(
						'name'   => 'referral_code',
						'value'  => $_GET["rfrd"],
						'expire' => '3600',
						'path' => '/',
						'httponly' => false
						);
						$this->input->set_cookie($cookie);
					}
					

					if($this->session->userdata('user')) {
						$data['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_fb']));
						$data['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_ln']));
						$data['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_twitter']));
						$data['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_email']));
					} else {
						$data['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'];
						$data['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'];
						$data['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'];
						$data['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_data['project_id'];
					}

					$data['favorite_employer_list'] = $this->db->get_where('users_favorite_employer_tracking', ['user_id' => $user[0]->user_id ])->result_array();
					$data['users_plan'] = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user[0]->profile_name.USER_AVATAR.$data['users_plan']['user_avatar'];
					$common_source_path = USERS_FTP_DIR . $user[0]->profile_name;
					$user_avatar = USER_AVATAR;
					$source_path_avatar = $common_source_path . $user_avatar;
					######## connectivity of remote server start #######
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);
					$avatarlist = $this->ftp->list_files($source_path_avatar);
					$avatar_pic = $source_path_avatar . $data['users_plan']['user_avatar'];
										
					if (!empty($data['users_plan']['user_avatar'])) {
						$file_size = $this->ftp->get_filesize($avatar_pic);
						if ($file_size != '-1') {
					 	$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user[0]->profile_name.USER_AVATAR.$data['users_plan']['user_avatar'];
				 		}
					}else { 					
						if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE){
								if($user[0]->gender == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($user[0]->gender == 'F'){
									$user_profile_picture = URL . 'assets/images/avatar_default_female.png';
								}
						} else {
							$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
						}
					}
					//end check avatar
					$this->ftp->close();

					$data['user_profile_picuter'] = $user_profile_picture;


					// project owner profile pic
					$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$project_data['profile_name'].USER_AVATAR.$project_data['user_avatar'];
					
						$common_source_path = USERS_FTP_DIR . $project_data['profile_name'];
						$user_avatar = USER_AVATAR;
						$source_path_avatar = $common_source_path . $user_avatar;
						######## connectivity of remote server start #######
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] = FTP_PORT;
						$config['debug'] = TRUE;
						$this->ftp->connect($config);
						$avatarlist = $this->ftp->list_files($source_path_avatar);
						$avatar_pic = $source_path_avatar . $project_data['user_avatar'];
											
						if (!empty($project_data['user_avatar'])) {
							$file_size = $this->ftp->get_filesize($avatar_pic);
							if ($file_size != '-1') {
					 			$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$project_data['profile_name'].USER_AVATAR.$project_data['user_avatar'];
							}
						}else { 
						if($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								if($project_data['gender'] == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
								}if($project_data['gender'] == 'F'){
									$user_profile_picture = URL . 'assets/images/avatar_default_female.png';
								}
						} else {
							$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
						}
					}
					//end check avatar
					$this->ftp->close();
					$data['po_profile_pic_url'] = $user_profile_picture;
					// load the view according to project status//
					if($project_status_table_array['project_status'] == 'awaiting_moderation'){
						$this->layout->view ('projects/awaiting_moderation_project_detail', $lay, $data, 'normal');
					}else if(in_array($project_status_table_array['project_status'], array('open_for_bidding','expired','awarded','in_progress','completed','incomplete'))){
						$check_sp_project_inviation = false;
						if($this->session->userdata ('user')){
							$count_project_invitation = $this->db->where(['project_id'=>$project_data['project_id'],'sp_id'=>$user[0]->user_id])->from('projects_invitations_tracking')->count_all_results();
							if($count_project_invitation > 0 ){
								$check_sp_project_inviation = true;
							}
						}
						
						if($project_data['hidden'] == 'N' || (($project_data['hidden'] == 'Y' && $this->session->userdata ('user') && $project_data['project_owner_id'] == $user[0]->user_id) || $check_sp_project_inviation )){
						 $this->layout->view ('projects/project_detail', $lay, $data, 'normal');
						}else{
							$data['current_page'] = 'hidden_project';
							$hidden_project_page_title_meta_tag = $this->config->item('hidden_project_page_title_meta_tag');
							$hidden_project_page_description_meta_tag = $this->config->item('hidden_project_page_description_meta_tag');
							$data['meta_tag'] = '<title>' . $hidden_project_page_title_meta_tag . '</title><meta name="description" content="' . $hidden_project_page_description_meta_tag . '"/>';
							$this->layout->view ('projects/hidden_project_default_page', $lay, $data, 'normal');
						}
					}else if($project_status_table_array['project_status'] == 'cancelled'){
					
						$check_sp_project_inviation = false;
						if($this->session->userdata ('user')){
							$count_project_invitation = $this->db->where(['project_id'=>$project_data['project_id'],'sp_id'=>$user[0]->user_id])->from('projects_invitations_tracking')->count_all_results();
							if($count_project_invitation > 0 ){
								$check_sp_project_inviation = true;
							}
						}
					
						if($project_data['hidden'] == 'N' || (($project_data['hidden'] == 'Y' && $this->session->userdata ('user') && $project_data['project_owner_id'] == $user[0]->user_id) || $check_sp_project_inviation )){
							$this->layout->view ('projects/cancelled_project_detail', $lay, $data, 'normal');
						}else{
							$data['current_page'] = 'hidden_project';
							$hidden_project_page_title_meta_tag = $this->config->item('hidden_project_page_title_meta_tag');
							$hidden_project_page_description_meta_tag = $this->config->item('hidden_project_page_description_meta_tag');
							$data['meta_tag'] = '<title>' . $hidden_project_page_title_meta_tag . '</title><meta name="description" content="' . $hidden_project_page_description_meta_tag . '"/>';
							$this->layout->view ('projects/hidden_project_default_page', $lay, $data, 'normal');
						}
					}
					
				}else{
					redirect (VPATH . $this->config->item('dashboard_page_url'));
				}
			}else{
				$data['current_page'] = 'project_not_existent_404';
				$this->session->unset_userdata('hire_me_user_id');
				/* $project_not_existent_404_page_title_meta_tag = $this->config->item('project_not_existent_404_page_title_meta_tag');
				$project_not_existent_404_page_description_meta_tag = $this->config->item('project_not_existent_404_page_description_meta_tag');
				$data['meta_tag'] = '<title>' . $project_not_existent_404_page_title_meta_tag . '</title><meta name="description" content="' . $project_not_existent_404_page_description_meta_tag . '"/>'; */
				//$this->layout->view ('projects/project_not_existent_404', $lay, $data, 'error_404');
				set_status_header(404);
				 $lay = array();
				 $default_404_page_title_meta_tag = $this->config->item('404_page_title_meta_tag');
				$default_404_page_description_meta_tag = $this->config->item('404_page_description_meta_tag');
				$data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
				 $this->layout->view('404defaultpage/404_default', $lay, $data, 'error_404');
				 
			}
		}
		
	}
	/* this is used to set the redirect for edit draft page*/
	public function set_redirection_edit_draft_project(){
	
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
			
				if ($this->input->post ()){
					$project_id = $this->input->post ('project_id');
					$page_type = $this->input->post ('page_type');
					$project_type = $this->input->post ('project_type');
					$action_type = $this->input->post ('action_type');
					$user = $this->session->userdata('user');
					$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
					if($user[0]->user_id != $po_id){
						$msg['status'] = 400;
						$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
						echo json_encode($msg);
						die;
					
					}
					$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
					if($check_project_categories_available_or_not == 0){
						echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
						die;
					}
					
					$this->db->select('current_membership_plan_id');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					
					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					// standard project availability
					$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
					$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
					$standard_valid_time_arr = array_filter($standard_check_valid_arr);
					$user_total_open_projects_count = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
					$user_total_open_fulltime_projects_count = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);

					if($project_type != 'fulltime'){ 
						if($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_projects == '0' || empty($standard_valid_time_arr))){
							if($user_detail['current_membership_plan_id'] == 1){
								if($action_type == 'edit_draft'){
									$error_message = $this->config->item('free_membership_subscriber_edit_draft_post_project_project_posting_disabled_message');
								}
								if($action_type == 'publish_draft'){
									$error_message = $this->config->item('free_membership_subscriber_publish_draft_post_project_project_posting_disabled_message');
								}
							}
							if($user_detail['current_membership_plan_id'] == 4){
								if($action_type == 'edit_draft'){
									$error_message = $this->config->item('gold_membership_subscriber_edit_draft_post_project_project_posting_disabled_message');
								}
								if($action_type == 'publish_draft'){
									$error_message = $this->config->item('gold_membership_subscriber_publish_draft_post_project_project_posting_disabled_message');
								}
							}
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						} else if($user_total_open_projects_count >= $user_memebership_max_number_of_open_projects && (!empty($standard_valid_time_arr) && $user_memebership_max_number_of_open_projects != '0') && $action_type == 'publish_draft'){
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_open_slots_not_available_message');
							}
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						} else if((empty($standard_valid_time_arr) || $user_memebership_max_number_of_open_projects == '0') && $action_type == 'publish_draft'){
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_project_project_posting_disabled_message');
							}
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					} else {
						if($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_fulltime_projects == '0' || empty($standard_valid_time_arr))){
							if($user_detail['current_membership_plan_id'] == 1){
								if($action_type == 'edit_draft'){
									$error_message = $this->config->item('free_membership_subscriber_edit_draft_post_project_fulltime_project_posting_disabled_message');
								}
								if($action_type == 'publish_draft'){
									$error_message = $this->config->item('free_membership_subscriber_publish_draft_post_project_fulltime_project_posting_disabled_message');
								}
							}
							if($user_detail['current_membership_plan_id'] == 4){
								if($action_type == 'edit_draft'){
									$error_message = $this->config->item('gold_membership_subscriber_edit_draft_post_project_fulltime_project_posting_disabled_message');
								}
								if($action_type == 'publish_draft'){
									$error_message = $this->config->item('gold_membership_subscriber_publish_draft_post_project_fulltime_project_posting_disabled_message');
								}
							}
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						} else if($user_total_open_fulltime_projects_count >= $user_memebership_max_number_of_open_fulltime_projects && (!empty($standard_valid_time_arr) && $user_memebership_max_number_of_open_fulltime_projects != '0') && $action_type == 'publish_draft'){
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_open_slots_not_available_message');
							}
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						} else if((empty($standard_valid_time_arr) || $user_memebership_max_number_of_open_fulltime_projects == '0') && $action_type == 'publish_draft'){
							if($user_detail['current_membership_plan_id'] == 1){
								$error_message =$this->config->item('free_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
							}
							if($user_detail['current_membership_plan_id'] == 4){
								$error_message =$this->config->item('gold_membership_subscriber_post_project_page_publish_fulltime_project_project_posting_disabled_message');
							}
							echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_message]);
							die;
						}
					}

					
					$draft_data_exists = $this->db->where(['project_id' => $project_id])->from('projects_draft')->count_all_results();
					
					$awaiting_moderation_data_exists = $this->db->where(['project_id' => $project_id])->from('projects_awaiting_moderation')->count_all_results();
					
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
					$error_msg = '';
					if($draft_data_exists == 0 && $awaiting_moderation_data_exists == 0 && empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					
						if($action_type == 'edit_draft'){
						
							if($project_type == 'fulltime'){
					
								$error_msg = $this->config->item('fulltime_project_draft_already_deleted_not_available_for_edit_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_draft_already_deleted_not_available_for_edit_dashboard_myprojects_po_view');
							}
							
						}
						if($action_type == 'publish_draft'){
							if($project_type == 'fulltime'){
					
								$error_msg = $this->config->item('fulltime_project_draft_already_deleted_not_available_for_publish_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_draft_already_deleted_not_available_for_publish_dashboard_myprojects_po_view');
							}
						}
					
					}
					if($awaiting_moderation_data_exists > 0 || !empty($project_status_table_array['project_status'])){
						if($action_type == 'edit_draft'){
							if($project_type == 'fulltime'){
					
								$error_msg = $this->config->item('fulltime_project_draft_status_changed_not_available_for_edit_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_draft_status_changed_not_available_for_edit_dashboard_myprojects_po_view');
							}
						}
						if($action_type == 'publish_draft'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_draft_status_changed_not_available_for_publish_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_draft_status_changed_not_available_for_publish_dashboard_myprojects_po_view');
							}
						}
					}
						
					if($page_type == "my_projects"){
						if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
						}else{
							$page = 1;
						}	
						$new_counter = $page;
						
						if($page != null) {
					
							if($page == 1) {
								$start = 0;
								} else {
								
							$start = ($page - 1) * $this->config->item('my_projects_po_view_draft_projects_listing_limit');
									
							}
						} else {
							$start = 0;
						}
						
						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_draft_projects_listing_limit'));
						$data["draft_project_data"] = $draft_project_data['data'];
						$page = $new_counter;
						if(empty($data["draft_project_data"])){
							$page = $new_counter-1;
							if($page != null) {
							
								if($page == 1) {
									$start = 0;
								} else {
									
								$start = ($page - 1) * $this->config->item('my_projects_po_view_draft_projects_listing_limit');
								
									
								}
							} else {
								$start = 0;
								}
						}
						
						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_draft_projects_listing_limit'));
					}
					if($page_type == "dashboard"){
						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
					}
					

					
					$data["page_type"] = $page_type;
					$data["draft_project_data"] = $draft_project_data['data'];
					$data["draft_project_count"] = $draft_project_data['total'];
					$this->session->set_userdata ('check_redirection_edit_draft_project', 1); // set redirection for edit draft page so when user refresh the edit draft page he will redirect to dasboard
					if($page_type == "my_projects"){
						$paginations = generate_pagination_links($draft_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_draft_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'', ['data-tab-type' => 'draft']);
						$data['draft_pagination_links'] = $paginations['links'];
						$multiplication = $this->config->item('my_projects_po_view_draft_projects_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_draft_projects_listing_limit') - count($data['draft_project_data'])));
						$record_per_page = count($data['draft_project_data']) < $this->config->item('my_projects_po_view_draft_projects_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('my_projects_po_view_draft_projects_listing_limit') * ($page - 1)) + 1;
						
						echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_draft_tab_heading').' ('.$draft_project_data['total'].'/'.($user_memebership_max_number_of_draft_projects - $draft_project_data['total']).')','location'=>VPATH . $this->config->item('edit_draft_project_page_url').'?id='.$project_id,'status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$draft_project_data['total'],'data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
					}
					if($page_type=="dashboard"){
						echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_draft_tab_heading').' ('.$draft_project_data['total'].'/'.($user_memebership_max_number_of_draft_projects-$draft_project_data['total']).')','location'=>VPATH . $this->config->item('edit_draft_project_page_url').'?id='.$project_id,'status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
					}
					die;

				}else{
					show_custom_404_page(); //show custom 404 page
				}
			
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		
		}
	}
	/**
	*	This function is used to delete the draft project from dedicated my projects page.
	**/
	public function delete_draft_po_my_projects(){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				if ($this->input->post ()){
				
					$project_id = $this->input->post ('project_id');
					$page_type = $this->input->post ('page_type');
					$project_type = $this->input->post ('project_type');
					$user = $this->session->userdata('user');
					$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
					if($user[0]->user_id != $po_id){
						$msg['status'] = 400;
						$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
						echo json_encode($msg);
						die;
					
					}
					$draft_data_exists = $this->db->where(['project_id' => $project_id])->from('projects_draft')->count_all_results();
					
					$awaiting_moderation_data_exists = $this->db->where(['project_id' => $project_id])->from('projects_awaiting_moderation')->count_all_results();
					
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
					$error_msg = '';
					if($draft_data_exists == 0 && $awaiting_moderation_data_exists == 0 && empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						if($project_type == 'fulltime'){
					
						$error_msg = $this->config->item('fulltime_project_draft_already_deleted_not_available_for_remove_dashboard_myprojects_po_view');
						}else{
							$error_msg = $this->config->item('project_draft_already_deleted_not_available_for_remove_dashboard_myprojects_po_view');
						}
					
					}
					if($awaiting_moderation_data_exists > 0 || !empty($project_status_table_array['project_status'])){
						if($project_type == 'fulltime'){
						$error_msg= $this->config->item('fulltime_project_draft_status_changed_not_available_for_remove_dashboard_myprojects_po_view');
						}else{
							$error_msg= $this->config->item('project_draft_status_changed_not_available_for_remove_dashboard_myprojects_po_view');
						}
					}
					if($draft_data_exists > 0){
					
					
						$this->db->select('*');
						$this->db->from('projects_draft');
						$this->db->where('project_id',$project_id);
						$this->db->where('project_owner_id',$user[0]->user_id);
						$draft_project_result = $this->db->get();
						$draft_project_data = $draft_project_result->row_array();
					
					
						$count_project_attachments = $this->db // count the number of attachments of draft project
						->select ('id')
						->from ('draft_projects_attachments')
						->where('project_id',$project_id)
						->get ()->num_rows ();
							
						if($count_project_attachments > 0)
						{
							$this->load->library('ftp');
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->ftp->connect($config); 
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_draft_dir = PROJECT_DRAFT_DIR;
							$profile_folder     = $user[0]->profile_name;
							//$draft_project_attachment_list = $this->ftp->list_files($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id);
							if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id))){
								$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id); // delete draft project directory 
							}
							$this->ftp->close();
						}
						$this->db->delete('draft_projects_attachments', array('project_id' => $project_id));
						$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
						$this->db->delete('draft_projects_categories_listing_tracking', array('project_id' => $project_id));
						$this->db->delete('projects_draft', array('project_id' => $project_id));
						$project_title = $draft_project_data['project_title'];
						if($draft_project_data['project_type'] == 'fulltime'){
							$delete_draft_project_log_message = $this->config->item('fulltime_remove_draft_user_activity_log_displayed_message_sent_to_po');
						}else{
							$delete_draft_project_log_message = $this->config->item('remove_draft_user_activity_log_displayed_message_sent_to_po');
						}
						$delete_draft_project_log_message = str_replace(array('{project_title}'),array(htmlspecialchars($project_title, ENT_QUOTES)),$delete_draft_project_log_message);
						user_display_log($delete_draft_project_log_message);
					}
					if($page_type == "my_projects"){
						if($this->input->post ('active_page')){
						$page = $this->input->post ('active_page');
						}else{
							$page = 1;
						}
						$start = 0;
						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_draft_projects_listing_limit'));
					}
					if($page_type == "dashboard"){
						$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
					}
					$data["page_type"] = $page_type;
					$data["draft_project_data"] = $draft_project_data['data'];
					$data["draft_project_count"] = $draft_project_data['total'];
					$this->db->select('current_membership_plan_id');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();

					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					
					$data['po_max_draft_projects_number'] = $user_memebership_max_number_of_draft_projects;
					$data['po_max_open_projects_number'] = $user_memebership_max_number_of_open_for_bidding_projects;
					
					$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
					$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
					########## fetch open bidding project information of logged in user ##########################
					$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
					$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
					
					
					if($page_type == "my_projects"){
						$paginations = generate_pagination_links($draft_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_draft_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'draft']);
						$data['draft_pagination_links'] = $paginations['links'];
						$multiplication = $this->config->item('my_projects_po_view_draft_projects_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_draft_projects_listing_limit') - count($data['draft_project_data'])));
						$record_per_page = count($data['draft_project_data']) < $this->config->item('my_projects_po_view_draft_projects_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('my_projects_po_view_draft_projects_listing_limit') * ($page - 1)) + 1;
						
						echo json_encode(['location'=>'','tab_heading'=>$this->config->item('my_projects_po_view_draft_tab_heading').' ('.$data['draft_cnt'].'/'.($user_memebership_max_number_of_draft_projects-$data['draft_cnt']).' + '.$data['fulltime_draft_cnt'].'/'.($user_memebership_max_number_of_draft_fulltime_projects - $data['fulltime_draft_cnt']).')','status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$draft_project_data['total'],'data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
					}
					if($page_type=="dashboard"){
						echo json_encode(['location'=>'','tab_heading'=>$this->config->item('my_projects_po_view_draft_tab_heading').' ('.$data['draft_cnt'].'/'.($user_memebership_max_number_of_draft_projects-$data['draft_cnt']).' + '.$data['fulltime_draft_cnt'].'/'.($user_memebership_max_number_of_draft_fulltime_projects - $data['fulltime_draft_cnt']).')','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
					}
					die;

				}else{
					show_custom_404_page(); //show custom 404 page
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	* This function is used to delete the project category from draft_projects_categories_listing_tracking table.
	*/
	public function delete_draft_project_category () {
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('category_project_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$category_project_id = $this->input->post ('category_project_id');
				$remove_category_id = $this->input->post ('remove_category_id');
				$category_project_array  = explode('_',$category_project_id);
				
				$count_draft_project = $this->db // count the number of record in projects_draft table
				->select ('id')
				->from ('projects_draft')
				->where('project_id',$category_project_array[1])
				->where('project_owner_id',$user[0]->user_id)
				->get ()->num_rows ();
				if($count_draft_project == 0 )
				{
					$msg['status'] = 400;
					$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
					echo json_encode($msg);
					die;
				}
				
				
				$this->db->delete('draft_projects_categories_listing_tracking', array('id' => $category_project_array[0]));
				$count_project_categories = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('draft_projects_categories_listing_tracking')
				->where('project_id',$category_project_array[1])
				->get ()->num_rows ();
					$msg['add_category_button_show_status']  = '1';
				if( $count_project_categories >= $this->config->item('number_project_category_post_project')){
					$msg['add_category_button_show_status']  = '0';
				}
				$msg['remove_category_id'] = $remove_category_id;
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	* This function is used to delete the project tag from draft_projects_tags table.
	*/
	public function delete_draft_project_tag () {
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_tag_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			if(check_session_validity()){ 
				$user = $this->session->userdata ('user');
				$project_tag_id = $this->input->post ('project_tag_id');
				$project_tag_array = explode("_",$project_tag_id);
				$count_draft_project = $this->db // count the number of record in projects_draft table
				->select ('id')
				->from ('projects_draft')
				->where('project_id',$project_tag_array[3])
				->where('project_owner_id',$user[0]->user_id)
				->get ()->num_rows ();
				if($count_draft_project == 0 )
				{
					$msg['status'] = 400;
					$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
					echo json_encode($msg);
					die;
				}
				$this->db->delete('draft_projects_tags', array('id' => $project_tag_array[2]));
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);die;
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	*	This function is used to upload draft project attachment.
	*/
	public function upload_draft_project_attachment (){
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){
				$user = $this->session->userdata ('user');
				$project_id = $this->uri->segment('3');
				$count_draft_project = $this->db // count the number of record in projects_draft table
				->select ('id')
				->from ('projects_draft')
				->where('project_id',$project_id)
				->where('project_owner_id',$user[0]->user_id)
				->get ()->num_rows ();
				if($count_draft_project > 0 )
				{
					$no_draft_project_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('draft_projects_attachments')
					->where ('project_id', $project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					
					$this->db->where('project_id', $project_id);
					
					$project_attachment_maximum_size_limit	 = $this->config->item('project_attachment_maximum_size_limit');
					
					$project_attachment_maximum_size_limit = ($project_attachment_maximum_size_limit * 1048576);
					if(!empty($_FILES['file']['tmp_name'])){
						$file_array = $_FILES['file'];
						if($file_array['size'] > $project_attachment_maximum_size_limit){
							$msg['status'] = 'FAILED';
							$msg['message'] = $this->config->item('project_attachment_maximum_size_validation_post_project_message');
							echo json_encode ($msg);die;
						}elseif($no_draft_project_attachment_uploaded_user >= $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
							$msg['status'] = 'FAILED';
							$msg['message'] = $this->config->item('post_project_page_maximum_allowed_number_of_project_attachments_violation_message');
							echo json_encode ($msg);die;
						
						}else{
							
							######## connectivity of remote server start#########
							$this->load->library('ftp');
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->ftp->connect($config); 
							######## connectivity of remote server end #######
							
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_draft_dir = PROJECT_DRAFT_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$profile_folder     = $user[0]->profile_name;
							//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
							
							if(!empty($project_id )){
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
							
							
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir);
							
								/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir, 0777);// create temporary directory in projects folder
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.DIRECTORY_SEPARATOR , 0777); // create the directory by using temporary project id
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir , 0777); // create the directory by using temporary project id */
								$temp 		= 	explode(".", $file_array["name"]);
								$extension 	= 	end($temp);
								$attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$project_id.'.'.$extension;// name of attachment
								
								if(move_uploaded_file($file_array['tmp_name'],TEMP_DIR.$attachment_name)){
									
									$source_path = FCPATH .TEMP_DIR. $attachment_name;
									
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir.$attachment_name;
									
									$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
									unlink(FCPATH .TEMP_DIR. $attachment_name);
									$projects_attachments_data = array('project_id'=>$project_id,'draft_project_attachment_name'=>$attachment_name);
									$this->db->insert ('draft_projects_attachments', $projects_attachments_data);
									$last_insert_id = $this->db->insert_id();
									
									$no_draft_project_attachment_uploaded_user = $this->db
									->select ('id')
									->from ('draft_projects_attachments')
									->where ('project_id', $project_id)
									->get ()->num_rows (); // check the number of attachment of user into database
									
									$msg['status'] = 'OK';
									$msg['message'] = 'uploaded';
									$msg['filename'] = $attachment_name;
									$msg['size'] = number_format($file_array['size']/1024). 'KB';
									$msg['id'] = $last_insert_id;
									$msg['encrypt_id'] = Cryptor::doEncrypt($last_insert_id);
									
									$upload_button_status = '0';
									if($no_draft_project_attachment_uploaded_user < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
										$upload_button_status = '1';
									}
									$msg['upload_button_status'] = $upload_button_status;
								}
							}
							$this->ftp->close();
						}	
						
					}else{
						$msg['status'] = 'FAILED';
						$msg['message'] = 'file is empty';
					}
					echo json_encode ($msg);die;	
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
					echo json_encode($msg);
					die;
				}
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	/**
	* This function is used to remove the attachment of draft projects.
	*/
	public function delete_draft_project_attachment () {
        if ($this->input->is_ajax_request ())
        {
			if(empty($this->input->post ('project_attachment_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			if(check_session_validity()){ 
				$project_id = $this->input->post ('project_id');
				$user = $this->session->userdata ('user');
				$count_draft_project = $this->db // count the number of record in projects_draft table
				->select ('id')
				->from ('projects_draft')
				->where('project_id',$project_id)
				->where('project_owner_id',$user[0]->user_id)
				->get ()->num_rows ();
				if($count_draft_project == 0 )
				{
					$msg['status'] = 400;
					$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
					echo json_encode($msg);
					die;
				}
			
				$user = $this->session->userdata ('user');
				
				######## connectivity of remote server start#########
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				######## connectivity of remote server end #######
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_draft_dir = PROJECT_DRAFT_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$profile_folder     = $user[0]->profile_name;
				
				$project_attachment_name = $this->input->post ('project_attachment_name');
				$project_attachment_id = $this->input->post ('project_attachment_id');
				
				$this->db->select('*');
				$this->db->from('draft_projects_attachments');
				$this->db->where('id',$project_attachment_id);
				$project_attachment_result = $this->db->get();
				$project_attachment_data = $project_attachment_result->result_array();
				if(!empty($project_attachment_data)){
					
					
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id .$project_owner_attachments_dir .$project_attachment_data[0]['draft_project_attachment_name'];
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir.$project_attachment_data[0]['draft_project_attachment_name']);
					}
					
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id .$project_owner_attachments_dir .$project_attachment_name;
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir.$project_attachment_name);
					}
					
					$this->db->delete('draft_projects_attachments', array('id' => $project_attachment_id));
					
					$no_draft_project_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('draft_projects_attachments')
					->where ('project_id', $project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$upload_button_status = '0';
					if($no_draft_project_attachment_uploaded_user < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$upload_button_status = '1';
					}
					$msg['upload_button_status'] = $upload_button_status;
					
				
				
				}else{
				
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id .$project_owner_attachments_dir .$project_attachment_name;
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir.$project_attachment_name);
					}
					$no_project_attachment_uploaded_user_temp = $this->db
					->select ('id')
					->from ('draft_projects_attachments')
					->where ('project_id', $project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$upload_button_status = '0';
					if($no_project_attachment_uploaded_user_temp < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$upload_button_status = '1';
					}
					$msg['upload_button_status'] = $upload_button_status;
				}
				
				$this->ftp->close();
				echo json_encode ($msg);
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			
			}
        }else{
			show_custom_404_page(); //show custom 404 page
		}
		
	}
	/**
	* This function is used to return the list of valid draft project attachments if any attachment is not exist in disk then this will remove the entry from table also.
	*/
	public function get_draft_project_attachments($project_id,$user_profile_name){
		if(!empty($project_id) && !empty($user_profile_name)){
			########## fetch the draft project attachments ###
			$this->db->select('*');
			$this->db->from('draft_projects_attachments');
			$this->db->where('project_id',$project_id);
			$this->db->order_by('id',"asc");
			$project_attachment_result = $this->db->get();
			$project_attachment_data = $project_attachment_result->result_array();
			
			$project_attachment_array = array();
			if(!empty($project_attachment_data)){
			
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_draft_dir = PROJECT_DRAFT_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$profile_folder     = $user_profile_name;
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				foreach($project_attachment_data as $attachment_key){
					$source_path =  $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_id.$project_owner_attachments_dir .$attachment_key['draft_project_attachment_name'];

					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1'){
						$project_attachment['id'] = $attachment_key['id'];
						$project_attachment['project_id'] = $attachment_key['project_id'];
						$project_attachment['project_attachment_name'] = $attachment_key['draft_project_attachment_name'];
						$project_attachment['size']  = number_format($file_size/1024). 'KB';
						$project_attachment_array[] = $project_attachment;
					}else{
						$this->db->delete('draft_projects_attachments', array('id' => $attachment_key['id'])); 
					}
				}
				$this->ftp->close();
			}
			return $project_attachment_array;
		}else{
		
			show_404();
		}
	}
	
	/**
	* This function is user the reset the the draft project entiries from database
	*/
	public function reset_draft_project_data () {
		if($this->input->is_ajax_request () ){
			if ($this->input->post ())
			{
				if(check_session_validity()){ // check session exists or not if exist then it will update user session
					/* $project_id = $this->input->post ('project_id');
					$this->db->select('*');
					$this->db->from('draft_projects_attachments');
					$this->db->where('project_id',$project_id);
					$project_attachment_result = $this->db->get();
					$project_attachment_data = $project_attachment_result->result_array(); 
					
					if(!empty($project_attachment_data)){
						$user = $this->session->userdata ('user');
						######## connectivity of remote server start#########
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						######## connectivity of remote server end #######
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$project_draft_dir = PROJECT_DRAFT_DIR;
						$profile_folder     = $user[0]->profile_name;
						foreach($project_attachment_data as $key=>$value){
							$file_size = $this->ftp->get_filesize($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$value['project_id'].DIRECTORY_SEPARATOR.$value['draft_project_attachment_name']);
							if($file_size != '-1'){
								$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$value['project_id'].DIRECTORY_SEPARATOR.$value['draft_project_attachment_name']);
								$this->db->delete('draft_projects_attachments', array('id' => $value['id'])); 
							}
						}
						$this->ftp->close();
					}
					$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
					*/
					//$project_id = $this->input->post('project_id');
					$draft_project_data = array (
						//'project_title'=>'',
						//'project_description'=>'',
						//'locality_id'=>0,
						//'county_id'=>0,
						//'postal_code_id'=>0,
						//'escrow_payment_method'=>'N',
					//	'offline_payment_method'=>'N',
						//'confidential_dropdown_option_selected'=>'N',
						//'not_sure_dropdown_option_selected'=>'N',
						//'min_budget'=>'',
						//'max_budget'=>'',
						//'featured'=>'N',
						//'urgent'=>'N',
						//'sealed'=>'N',
						//'hidden'=>'N'
					);
					
				   //$this->db->where ('project_id', $project_id);
				   //$this->db->update ('projects_draft', $draft_project_data);
				   //$this->db->delete('draft_projects_tags', array('project_id' => $project_id));
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
				
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	
	
	
	/**
	* This function is used to check that attachment exists or not in either in folder or into database
	*/
	public function check_project_attachment_exists () {
		if($this->input->is_ajax_request ()){
			/* if(check_session_validity()){  */
				check_session_validity();
				$attachment_id = $this->input->post ('attachment_id');
				//$encrypt_attachment_id = $this->input->post ('attachment_id');
				//$decrypt_attachment_id = Cryptor::doDecrypt($this->input->post('attachment_id'));
				$project_id = $this->input->post('project_id');
				$project_status = $this->input->post ('project_status');
				$page_type = $this->input->post ('page_type');
				$user = $this->session->userdata ('user');
				
				$check_draft_project_exists = $this->db->where(['project_id' => $project_id])->from('projects_draft')->count_all_results();
				if($check_draft_project_exists > 0 ){
					if(!$this->session->userdata ('user')){ 
						$msg['status'] = 400;
						$msg['location'] = VPATH;
						echo json_encode($msg);
						die;
					}else{	
						$project_status_table_array['project_status'] = 'draft';
						$project_status_table_array['table_name'] = 'projects_draft';
					}
				}else{
				
					$check_awaiting_moderation_project_exists = $this->db->where(['project_id' => $project_id])->from('projects_awaiting_moderation')->count_all_results();
					
					if($check_awaiting_moderation_project_exists > 0){
						if(!$this->session->userdata ('user')){ 
							$msg['status'] = 400;
							$msg['location'] = VPATH;
							echo json_encode($msg);
							die;
						}else{
							$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
						}
					
					}else{
						$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
					}
				}
				
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name'])){
				
					
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
					
						$this->db->select('project_detail.fulltime_project_id as project_id,project_detail.project_type,project_detail.employer_id as project_owner_id,users.profile_name');
						$this->db->from($project_status_table_array['table_name'].' as project_detail');
						$this->db->join('users', 'users.user_id = project_detail.employer_id', 'left');
						$this->db->where('project_detail.fulltime_project_id',$project_id);
						$project_result = $this->db->get();
						$project_data = $project_result->row_array();
					
					}else{
						
						$fields = 'project_detail.project_id,project_detail.project_type,project_detail.project_owner_id,project_detail.project_expiration_date,users.profile_name';
						
						if($project_status_table_array['project_status'] == 'draft' || $project_status_table_array['project_status'] == 'awaiting_moderation'){
							$fields = 'project_detail.project_id,project_detail.project_type,project_detail.project_owner_id,users.profile_name';
						
						}
						
					
						$this->db->select($fields);
						$this->db->from($project_status_table_array['table_name'].' as project_detail');
						$this->db->join('users', 'users.user_id = project_detail.project_owner_id', 'left');
						$this->db->where('project_detail.project_id',$project_id);
						$project_result = $this->db->get();
						$project_data = $project_result->row_array();
					}
					
					if(in_array($project_status_table_array['project_status'], array('open_for_bidding','awarded','in_progress','incomplete','completed','expired'))){
					
						$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row_array();
					}
					
					$project_attachment_not_exist_validation_message = '';
					if($this->session->userdata ('user'))
					{
						if($user[0]->user_id == $project_data['project_owner_id'] ){
							if($project_status_table_array['project_status'] == 'draft'){
								$project_attachment_detail = $this->db->get_where('draft_projects_attachments', array('draft_project_attachment_name' => $attachment_id))->row_array();
								if($page_type == 'post_project' ){
									if($project_type == 'post_fulltime_position'){
										$project_attachment_not_exist_validation_message = $this->config->item('fulltime_project_attachment_not_exist_validation_post_project_page_message');
									}else{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_post_project_page_message');
									}	
								
								}else{
									if($project_type == 'fulltime'){
										$project_attachment_not_exist_validation_message = $this->config->item('fulltime_project_attachment_not_exist_validation_preview_project_page_message');
									}else{
										$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_preview_project_page_message');
									}	
								
								}
							}
							else if($project_status_table_array['project_status'] == 'awaiting_moderation'){
								$project_attachment_detail = $this->db->get_where('awaiting_moderation_projects_attachments', array('awaiting_moderation_project_attachment_name' => $attachment_id))->row_array();
								if($project_data['project_type'] == 'fulltime')
								{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_awaiting_moderation_status_page_message_project_owner_view_fulltime_project');
								}else{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_awaiting_moderation_status_page_message_project_owner_view_project');
								}
							}else if($project_status_table_array['project_status'] == 'open_for_bidding'){
								
								if($page_type == 'edit_project'){
								
									if($project_data['project_type'] == 'fulltime')
									{
										$project_attachment_not_exist_validation_message = $this->config->item('fulltime_project_attachment_not_exist_validation_edit_project_page_message');
									}else{
										$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_edit_project_page_message');
									}
								
								
								}else{
								
									if(strtotime($project_data['project_expiration_date']) <= time()){
									
										if($project_data['project_type'] == 'fulltime')
										{
										
											$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_fulltime_project');
										}else{
											$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_project');
										}
									
									}else{
								
								
										if($project_data['project_type'] == 'fulltime')
										{
										
											$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_open_for_bidding_status_page_message_project_owner_view_fulltime_project');
										}else{
											$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_open_for_bidding_status_page_message_project_owner_view_project');
										}
									}
								}
							
							}else if($project_status_table_array['project_status'] == 'awarded'){
								if($project_data['project_type'] != 'fulltime')
								{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_awarded_project_status_page_message_project_owner_view');
								}
							
							}
							else if($project_status_table_array['project_status'] == 'in_progress'){
								if($project_data['project_type'] != 'fulltime')
								{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_in_progress_project_status_page_message_project_owner_view_project');
								}
							
							}else if($project_status_table_array['project_status'] == 'incomplete'){
								if($project_data['project_type'] != 'fulltime'){
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_incomplete_project_status_page_message_project_owner_view_project');
								}
							
							}else if($project_status_table_array['project_status'] == 'expired'){
								if($project_data['project_type'] == 'fulltime')
								{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_fulltime_project');
								}else{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_expired_project_status_page_message_project_owner_view_project');
								}
							
							}else if($project_status_table_array['project_status'] == 'cancelled'){
								if($project_data['project_type'] == 'fulltime')
								{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_cancelled_project_status_page_message_project_owner_view_fulltime_project');
								}else{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_cancelled_project_status_page_message_project_owner_view_project');
								}
							
							}else if($project_status_table_array['project_status'] == 'completed'){
								if($project_data['project_type'] != 'fulltime')
								{
									$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_completed_project_status_page_message_project_owner_view_project');
								}
							}
						}else{
						
							if($project_data['project_type'] == 'fulltime')
							{
								$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_project_detail_page_message_visitor_view_fulltime_project');
							}else{
								$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_project_detail_page_message_visitor_view_project');
							}
						}
						
					}else{
						if($project_data['project_type'] == 'fulltime')
						{
							$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_project_detail_page_message_visitor_view_fulltime_project');
						}else{
							$project_attachment_not_exist_validation_message = $this->config->item('project_attachment_not_exist_validation_project_detail_page_message_visitor_view_project');
						}
					}
					
					if(!empty($project_attachment_detail)){ 
						
						$this->load->library('ftp');
						$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
						$config['ftp_username'] = FTP_USERNAME;
						$config['ftp_password'] = FTP_PASSWORD;
						$config['ftp_port'] 	= FTP_PORT;
						$config['debug']    = TRUE;
						$this->ftp->connect($config); 
						$users_ftp_dir 	= USERS_FTP_DIR; 
						$projects_ftp_dir = PROJECTS_FTP_DIR;
						$profile_folder     = $project_data['profile_name'];
						
						
						
						if($project_status_table_array['project_status'] == 'draft'){
							$project_draft_dir = PROJECT_DRAFT_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir .$project_attachment_detail['draft_project_attachment_name'];
							
							
						}
						else if($project_status_table_array['project_status'] == 'awaiting_moderation'){
							$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir .$project_attachment_detail['awaiting_moderation_project_attachment_name'];
							
						}
						else if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir.$project_attachment_detail['project_attachment_name'];
						}
						else if($project_status_table_array['project_status'] == 'awarded'){
							$project_awarded_dir = PROJECT_AWARDED_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir.$project_attachment_detail['project_attachment_name'];
						}
						else if($project_status_table_array['project_status'] == 'in_progress'){
							$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir.$project_attachment_detail['project_attachment_name'];
						}else if($project_status_table_array['project_status'] == 'incomplete'){
							$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir.$project_attachment_detail['project_attachment_name'];
						}else if($project_status_table_array['project_status'] == 'completed'){
							$project_completed_dir = PROJECT_COMPLETED_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir.$project_attachment_detail['project_attachment_name'];
						}
						else if($project_status_table_array['project_status'] == 'expired'){
							//$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$project_expired_dir = PROJECT_EXPIRED_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$project_attachment_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_attachment_detail['project_id'] .$project_owner_attachments_dir.$project_attachment_detail['project_attachment_name'];
						}
						$file_size = $this->ftp->get_filesize($project_attachment_path);
						if($file_size != '-1')
						{
							
							$msg['status'] = 'SUCCESS';
							$msg['message'] = '';
							$msg['location'] = VPATH . 'projects/download_project_attachment/'.$attachment_id . '/'.$project_id;
						
							
						}else{
							$msg['status'] = 'FAILED';
							$msg['message'] = $project_attachment_not_exist_validation_message;
							$msg['location'] = '';
						}
						$this->ftp->close(); 
					}else{
						$msg['status'] = 'FAILED';
						$msg['message'] = $project_attachment_not_exist_validation_message;
						$msg['location'] = '';
						
					} 
				}else{
					$msg['status'] = 'FAILED';
					$msg['message'] = $this->config->item('deleted_open_project_attachment_not_exist_validation_project_detail_message_visitor_view');
					$msg['location'] = '';
				
				}
				echo json_encode ($msg);die;
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	* This function is used to download project attachment.
	*/
	public function download_project_attachment () {
		
		$this->load->helper('download');
		/* if($this->session->userdata ('user')){ */
		if(!empty($this->uri->segment(3)) && !empty($this->uri->segment(4))){
			//$attachment_id = Cryptor::doDecrypt($this->uri->segment(3));
			$attachment_id = $this->uri->segment(3);
			$project_id = $this->uri->segment(4);
			
			$check_draft_project_exists = $this->db->where(['project_id' => $project_id])->from('projects_draft')->count_all_results();
			if($check_draft_project_exists > 0 ){
				$project_status_table_array['project_status'] = 'draft';
				$project_status_table_array['table_name'] = 'projects_draft';
			}else{
				$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
			}
			
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
					
				$this->db->select('project_detail.fulltime_project_id as project_id,users.profile_name');
				$this->db->from($project_status_table_array['table_name'].' as project_detail');
				$this->db->join('users', 'users.user_id = project_detail.employer_id', 'left');
				$this->db->where('project_detail.fulltime_project_id',$project_id);
				$project_result = $this->db->get();
				$project_data = $project_result->result_array();
			

			}else{
				$this->db->select('project_detail.project_id,users.profile_name');
				$this->db->from($project_status_table_array['table_name'].' as project_detail');
				$this->db->join('users', 'users.user_id = project_detail.project_owner_id', 'left');
				$this->db->where('project_detail.project_id',$project_id);
				$project_result = $this->db->get();
				$project_data = $project_result->result_array();
			
			}
			
			
			
			######## connectivity of remote server start#########
			$this->load->library('ftp');
			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->ftp->connect($config); 
			######## connectivity of remote server end #######
			
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			
			$profile_folder     = $project_data[0]['profile_name'];
			$project_attachment_name = '';
			if($project_status_table_array['project_status'] == 'draft'){
				$project_draft_dir = PROJECT_DRAFT_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('draft_projects_attachments', array('draft_project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->draft_project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_draft_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
				
			}else if($project_status_table_array['project_status'] == 'awaiting_moderation'){
				$project_awaiting_moderation_dir = PROJECT_AWAITING_MODERATION_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('awaiting_moderation_projects_attachments', array('awaiting_moderation_project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->awaiting_moderation_project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awaiting_moderation_dir.$project_attachment_detail->project_id .$project_owner_attachments_dir .$project_attachment_name;
				
			}else if($project_status_table_array['project_status'] == 'open_for_bidding'){
			
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_attachment_detail->project_id.$project_owner_attachments_dir.$project_attachment_name;
				
			}
			else if($project_status_table_array['project_status'] == 'awarded'){
			
				
				$project_awarded_dir = PROJECT_AWARDED_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_attachment_detail->project_id.$project_owner_attachments_dir.$project_attachment_name;
				
			}
			else if($project_status_table_array['project_status'] == 'in_progress'){
			
				
				$project_in_progress_dir = PROJECT_IN_PROGRESS_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_progress_dir.$project_attachment_detail->project_id.$project_owner_attachments_dir.$project_attachment_name;
				
			}else if($project_status_table_array['project_status'] == 'incomplete'){
			
				
				$project_in_complete_dir = PROJECT_INCOMPLETE_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_in_complete_dir.$project_attachment_detail->project_id.$project_owner_attachments_dir.$project_attachment_name;
				
			}
			else if($project_status_table_array['project_status'] == 'completed'){
			
				
				$project_completed_dir = PROJECT_COMPLETED_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_completed_dir.$project_attachment_detail->project_id.$project_owner_attachments_dir.$project_attachment_name;
				
			}
			else if($project_status_table_array['project_status'] == 'expired'){
			
				//$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_expired_dir = PROJECT_EXPIRED_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$project_attachment_detail = $this->db->get_where('projects_attachments', array('project_attachment_name' => $attachment_id))->row();
				$project_attachment_name = $project_attachment_detail->project_attachment_name;
				$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_attachment_detail->project_id.$project_owner_attachments_dir.$project_attachment_name;
				
			}
			
			
			$file_size = $this->ftp->get_filesize($source_path);
			if($file_size != '-1')
			{
				$destination_path =  FCPATH .TEMP_DIR.$project_attachment_name;
				$this->ftp->download($source_path,$destination_path, 'auto', 0777);
				$this->ftp->close();
				$data = file_get_contents (TEMP_DIR.$project_attachment_name);// read the content of file
				unlink(TEMP_DIR.$project_attachment_name);
				force_download ($project_attachment_name,$data);
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
		/* }else{
			show_404();
		} */
	}
	
	/**
	*	This function is used to upload project attachment whose available for open for bidding.
	*/
	public function upload_project_attachment () {
		if($this->input->is_ajax_request ()){
		
			if(!$this->uri->segment('3')){ // if project temp id not coming it will show 404 page
			show_404();
			}
			if(check_session_validity()){
				$user = $this->session->userdata ('user');
				$project_id = $this->uri->segment('3');
				$count_open_for_bidding_project = $this->db // count the number of record in projects_open_bidding table
				->select ('id')
				->from ('projects_open_bidding')
				->where('project_id',$project_id)
				->where('project_owner_id',$user[0]->user_id)
				->get ()->num_rows ();
				if($count_open_for_bidding_project > 0 )
				{
					$no_project_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('projects_attachments')
					->where ('project_id', $project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					
					$this->db->where('project_id', $project_id);
					
					$project_attachment_maximum_size_limit	 = $this->config->item('project_attachment_maximum_size_limit');
					
					$project_attachment_maximum_size_limit = ($project_attachment_maximum_size_limit * 1048576);
					if(!empty($_FILES['file']['tmp_name'])){
						$file_array = $_FILES['file'];
						if($file_array['size'] > $project_attachment_maximum_size_limit){
							$msg['status'] = 'FAILED';
							$msg['message'] = $this->config->item('project_attachment_maximum_size_validation_post_project_message');
							echo json_encode ($msg);die;
						}elseif($no_project_attachment_uploaded_user >= $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
							$msg['status'] = 'FAILED';
							$msg['message'] = $this->config->item('post_project_page_maximum_allowed_number_of_project_attachments_violation_message');
							echo json_encode ($msg);die;
						
						}else{
							
							######## connectivity of remote server start#########
							$this->load->library('ftp');
							$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
							$config['ftp_username'] = FTP_USERNAME;
							$config['ftp_password'] = FTP_PASSWORD;
							$config['ftp_port'] 	= FTP_PORT;
							$config['debug']    = TRUE;
							$this->ftp->connect($config); 
							######## connectivity of remote server end #######
							
							$users_ftp_dir 	= USERS_FTP_DIR; 
							$projects_ftp_dir = PROJECTS_FTP_DIR;
							$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
							$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
							$profile_folder     = $user[0]->profile_name;
							
							
							if(!empty($project_id )){
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir);
							
							
								/* $this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists
					
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir, 0777);// create directory for open for bidding project
								
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id, 0777); // create the project directory if it is not exists
								$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir , 0777); // create the project attachment directory */
								$temp 		= 	explode(".", $file_array["name"]);
								$extension 	= 	end($temp);
								$attachment_name 	= 	$this->config->item('attachment_prefix_text').rand(0,1000).$project_id.'.'.$extension;// name of attachment
								
								if(move_uploaded_file($file_array['tmp_name'],TEMP_DIR.$attachment_name)){
									
									$source_path = FCPATH .TEMP_DIR. $attachment_name;
									
									$destination_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir .$attachment_name;
									
									$this->ftp->upload($source_path,$destination_path , 'auto', 0777); // upload the attachment into temporary folder of projects 
									unlink(FCPATH .TEMP_DIR. $attachment_name);
									$projects_attachments_data = array('project_id'=>$project_id,'project_attachment_name'=>$attachment_name);
									$this->db->insert ('projects_attachments', $projects_attachments_data);
									$last_insert_id = $this->db->insert_id();
									
									
									############ update revision counter ######
									$this->db->select('projects_open_bidding.project_id,projects_open_bidding.revisions');
									$this->db->from('projects_open_bidding');
									$this->db->where('projects_open_bidding.project_id',$project_id);
									$project_result = $this->db->get();
									$project_data = $project_result->result_array();
									$this->db->where ('project_id', $project_id);
									$this->db->update ('projects_open_bidding',array(
									'revisions'=> ($project_data[0]['revisions'] + 1)));
														
									
									
									
									
									$no_project_attachment_uploaded_user = $this->db
									->select ('id')
									->from ('projects_attachments')
									->where ('project_id', $project_id)
									->get ()->num_rows (); // check the number of attachment of user into database
									
									$msg['status'] = 'OK';
									$msg['message'] = 'uploaded';
									$msg['filename'] = $attachment_name;
									$msg['size'] = number_format($file_array['size']/1024). 'KB';
									$msg['id'] = $last_insert_id;
									$msg['encrypt_id'] = Cryptor::doEncrypt($last_insert_id);
									
									$upload_button_status = '0';
									if($no_project_attachment_uploaded_user < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
										$upload_button_status = '1';
									}
									$msg['upload_button_status'] = $upload_button_status;
								}
							}
							$this->ftp->close();
						}	
						
					}else{
						$msg['status'] = 'FAILED';
						$msg['message'] = 'file is empty';
					}
						
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
					$msg['message'] = '';
				}
				echo json_encode ($msg);die;
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
		
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	* This function is used to remove the attachment of  projects which are availabe for open for bidding.
	*/
	public function delete_project_attachment () {
		if($this->input->is_ajax_request ()){
			if(empty($this->input->post ('project_attachment_id'))){
			
				show_custom_404_page(); //show custom 404 page
			}
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				######## connectivity of remote server start#########
				$this->load->library('ftp');
				$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
				$config['ftp_username'] = FTP_USERNAME;
				$config['ftp_password'] = FTP_PASSWORD;
				$config['ftp_port'] 	= FTP_PORT;
				$config['debug']    = TRUE;
				$this->ftp->connect($config); 
				######## connectivity of remote server end #######
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_owner_attachments_dir = PROJECT_OWNER_ATTACHMENTS_DIR;
				$profile_folder     = $user[0]->profile_name;
				
				$project_attachment_name = $this->input->post ('project_attachment_name');
				$project_attachment_id = $this->input->post ('project_attachment_id');
				$project_id = $this->input->post ('project_id');
				$this->db->select('*');
				$this->db->from('projects_attachments');
				$this->db->where('id',$project_attachment_id);
				$project_attachment_result = $this->db->get();
				$project_attachment_data = $project_attachment_result->result_array();
				if(!empty($project_attachment_data)){
					
					
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_attachment_data[0]['project_id'].$project_owner_attachments_dir.$project_attachment_data[0]['project_attachment_name'];
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$this->ftp->delete_file($source_path);
					}
					
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.$project_attachment_name;
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$this->ftp->delete_file($source_path);
					}
					
					$this->db->delete('projects_attachments', array('id' => $project_attachment_id));
					
					
					############ update revision counter ######
					$this->db->select('projects_open_bidding.project_id,projects_open_bidding.revisions');
					$this->db->from('projects_open_bidding');
					$this->db->where('projects_open_bidding.project_id',$project_id);
					$project_result = $this->db->get();
					$project_data = $project_result->result_array();
					$this->db->where ('project_id', $project_id);
					$this->db->update ('projects_open_bidding',array(
					'revisions'=> ($project_data[0]['revisions'] + 1)));
					
					
					
					
					$no_project_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('projects_attachments')
					->where ('project_id', $project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$upload_button_status = '0';
					if($no_project_attachment_uploaded_user < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$upload_button_status = '1';
					}
					$msg['upload_button_status'] = $upload_button_status;
					
				
				
				}else{
					
					$source_path = $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.$project_attachment_name;
					$file_size = $this->ftp->get_filesize($source_path);
					if($file_size != '-1')
					{
						$this->ftp->delete_file($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_owner_attachments_dir.$project_attachment_name);
					}
					$no_project_attachment_uploaded_user = $this->db
					->select ('id')
					->from ('projects_attachments')
					->where ('project_id', $project_id)
					->get ()->num_rows ();// check the number of attachment of user into database
					$msg['status'] = 'SUCCESS';
					$msg['message'] = '';
					$upload_button_status = '0';
					if($no_project_attachment_uploaded_user < $this->config->item('maximum_allowed_number_of_attachments_on_projects')){
						$upload_button_status = '1';
					}
					$msg['upload_button_status'] = $upload_button_status;
				}
				
				$this->ftp->close();
				echo json_encode ($msg);die;
				
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	* This function is used to delete the project tag from projects_tags table.
	*/
	public function delete_project_tag () {
		if($this->input->is_ajax_request ()){
			if (empty($this->input->post ('project_tag_id')))
			{
				show_custom_404_page(); //show custom 404 page
				return;
			}
			if(check_session_validity()){ 
				$project_tag_id = $this->input->post ('project_tag_id');
				$project_tag_array = explode("_",$project_tag_id);
				$this->db->delete('projects_tags', array('id' => $project_tag_array[2]));
				
				####### update the project revision field ####
				$this->db->select('projects_open_bidding.project_id,projects_open_bidding.revisions');
				$this->db->from('projects_open_bidding');
				$this->db->where('projects_open_bidding.project_id',$project_tag_array[3]);
				$project_result = $this->db->get();
				$project_data = $project_result->result_array();

				$this->db->where ('project_id', $project_tag_array[3]);
				$this->db->update ('projects_open_bidding',array(
				'revisions'=> ($project_data[0]['revisions'] + 1)));
				
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);
				die;
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	/**
	* This function is used to delete the project category from projects_categories_listing_tracking table.
	*/
	public function delete_project_category () {
		if($this->input->is_ajax_request ()){
			if (empty($this->input->post ('category_project_id')))
			{
				show_custom_404_page(); //show custom 404 page
				return;
			}	
			if(check_session_validity()){ 	
				$category_project_id = $this->input->post ('category_project_id');
				$remove_category_id = $this->input->post ('remove_category_id');
				$category_project_array  = explode('_',$category_project_id);
				$this->db->delete('projects_categories_listing_tracking', array('id' => $category_project_array[0]));
				
				############ update revision counter ######
				$this->db->select('projects_open_bidding.project_id,projects_open_bidding.revisions');
				$this->db->from('projects_open_bidding');
				$this->db->where('projects_open_bidding.project_id',$category_project_array[1]);
				$project_result = $this->db->get();
				$project_data = $project_result->result_array();
				$this->db->where ('project_id', $category_project_array[1]);
				$this->db->update ('projects_open_bidding',array(
				'revisions'=> ($project_data[0]['revisions'] + 1)));
				
				
				
				
				$count_project_categories = $this->db // count the number of record in temp_projects table
				->select ('id')
				->from ('projects_categories_listing_tracking')
				->where('project_id',$category_project_array[1])
				->get ()->num_rows ();
					$msg['add_category_button_show_status']  = '1';
				if( $count_project_categories >= $this->config->item('number_project_category_post_project')){
					$msg['add_category_button_show_status']  = '0';
				}
				$msg['remove_category_id'] = $remove_category_id;
				$msg['status'] = 'SUCCESS';
				$msg['message'] = '';
				echo json_encode ($msg);
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
  }
	// create the html of edit upgrade/prolong availability  popup for open for bidding project
	public function ajax_project_upgrade_popup_body(){
	
		if($this->input->is_ajax_request ()){
			
			if (!empty($this->input->post ('project_id')))
			{
				if(check_session_validity()){ // check session exists or not if exist then it will update user session
					$project_id = $this->input->post ('project_id');
					$user = $this->session->userdata ('user');
					$po_id = $this->input->post ('po_id');
					if($user[0]->user_id != Cryptor::doDecrypt($po_id)){
						$msg['status'] = 400;
						$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
						echo json_encode($msg);
						die;
					
					}
					$project_type = $this->input->post ('project_type');
					$page_type = $this->input->post ('page_type');
			        $error_msg = '';
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
					
					
						if($this->input->post ('action_type') == 'prolong_availability_urgent'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}
						
							
						}elseif($this->input->post ('action_type') == 'prolong_availability_featured'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}
							
						}
						elseif($this->input->post ('action_type') == 'upgrade_as_urgent_project'){
						
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}
							
						}elseif($this->input->post ('action_type') == 'upgrade_as_featured_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}
							
						}
						elseif($this->input->post ('action_type') == 'upgrade_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view');
							}
						}
					
					}else{
					
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
							$project_data = $this->db // get the user detail
							->select('pd.fulltime_project_expiration_date as project_expiration_date')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.fulltime_project_id', $project_id)
							->get()->row_array();
						}else{
							$project_data = $this->db // get the user detail
							->select('pd.project_expiration_date')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.project_id', $project_id)
							->get()->row_array();
						}
					}
					
					
					if((!empty($project_status_table_array['project_status']) && $project_status_table_array['project_status'] != 'open_for_bidding') || (!empty($project_status_table_array['project_status']) && $project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) < time()) ){
					
					
						if($this->input->post ('action_type') == 'prolong_availability_urgent'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}
						
							
						}elseif($this->input->post ('action_type') == 'prolong_availability_featured'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}
							
						}
						elseif($this->input->post ('action_type') == 'upgrade_as_urgent_project'){
						
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}
							
						}elseif($this->input->post ('action_type') == 'upgrade_as_featured_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}
							
						}
						elseif($this->input->post ('action_type') == 'upgrade_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view');
							}
						}
					
					}
					$this->db->select('current_membership_plan_id');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();

					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					$popup_heading = "";
					if($this->input->post ('action_type') && in_array($this->input->post ('action_type'),array('prolong_availability_urgent','upgrade_as_urgent_project','prolong_availability_featured','upgrade_as_featured_project','upgrade_project'))){
					
						$this->db->select('projects_open_bidding.project_id,projects_open_bidding.project_title,projects_open_bidding.project_type,projects_open_bidding.project_owner_id,projects_open_bidding.project_expiration_date,projects_open_bidding.featured,projects_open_bidding.urgent,projects_open_bidding.sealed,projects_open_bidding.hidden,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
						$this->db->from('projects_open_bidding');
						
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->where('projects_open_bidding.project_id',$project_id);
						$this->db->where('projects_open_bidding.project_owner_id',$user[0]->user_id);
						
						$project_result = $this->db->get();
						$project_data = $project_result->row_array();
						
						if($this->input->post ('action_type') == 'upgrade_as_urgent_project' || $this->input->post ('action_type') == 'upgrade_as_featured_project'){
							if($project_data['project_type'] == 'fulltime'){
								$popup_heading = $this->config->item('fulltime_project_upgrade_popup_heading_singular');
							}else{
								$popup_heading = $this->config->item('project_upgrade_popup_heading_singular');
							}
						
						}
						if($this->input->post ('action_type') == 'upgrade_project'){
							if($project_data['project_type'] == 'fulltime'){
								$popup_heading = $this->config->item('fulltime_project_upgrade_popup_heading_plural');
							}else{	
								$popup_heading = $this->config->item('project_upgrade_popup_heading_plural');
							}
						
						}
						if($this->input->post ('action_type') == 'prolong_availability_urgent' || $this->input->post ('action_type') == 'prolong_availability_featured'){
							$popup_heading = $this->config->item('project_prolong_popup_heading');
						
						}
						
						
						if($page_type == "my_projects"){
							if($this->input->post ('active_page')){
							$page = $this->input->post ('active_page');
						} else {
							$page = 1;
						}	
							$new_counter = $page;
							
							if($page != null) {
						
								if($page == 1) {
									$start = 0;
									} else {
									
								$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
										
								}
							} else {
								$start = 0;
							}
							
							$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
							$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
							$page = $new_counter;
							if(empty($data["open_bidding_project_data"])){
								$page = $new_counter-1;
								if($page != null) {
								
									if($page == 1) {
										$start = 0;
									} else {
										
									$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
									
										
									}
								} else {
									$start = 0;
									}
							}
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
						
						}
						if($page_type == "dashboard"){
							$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
						}
						$data["page_type"] = $page_type;
						$data["project_type"] = $project_type;
						if($error_msg != ''){
							$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
							$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
						} else {
							$data['project_data'] = $project_data;
							$data['action_type'] = $this->input->post ('action_type');
						}
						$open_project_cnt = $open_bidding_project_data['total'];
						$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
						$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						$open_project_cnt -= $fulltime_open_project_count;
						if($page_type == "my_projects"){
							if($error_msg != ''){
								$paginations = generate_pagination_links($open_bidding_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => $tab_type]);
								$data['open_bidding_pagination_links'] = $paginations['links'];
								
								$multiplication = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * $page;
								$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') - count($data['open_bidding_project_data'])));
								$record_per_page = count($data['open_bidding_project_data']) < $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') ? $subtraction : $multiplication;
								$page_no = ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * ($page - 1)) + 1;
							
							
							
							
							
							
								echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','status' => 200,'error_msg'=>$error_msg,'location'=>'','record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$open_bidding_project_data['total'],'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
								die;
							}else{
								$data['po_id']= $po_id;
								$res['project_upgrade_popup_body'] = $this->load->view('ajax_project_upgrade_popup_body', $data, true);
								$res['popup_heading'] = $popup_heading;
								$res['error_msg'] = '';
								$res['location'] = '';
								$res['status'] = 200;
								echo json_encode($res);
							}
						}
						if($page_type=="dashboard"){
							if($error_msg != ''){
							echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
							die;
						} else {
								
								$data['po_id']= $po_id;
								$res['project_upgrade_popup_body'] = $this->load->view('ajax_project_upgrade_popup_body', $data, true);
								$res['popup_heading'] = $popup_heading;
								$res['error_msg'] = '';
								$res['location'] = '';
								$res['status'] = 200;
								echo json_encode($res);
							}
						}/* 
						
						
						
						
						
						$res['project_upgrade_popup_body'] = $this->load->view('ajax_project_upgrade_popup_body', $data, true);
						$res['popup_heading'] = $popup_heading;
						$res['status'] = 200;
						echo json_encode($res);
						die; */
					}else{
						$res['status'] = 400;
						$msg['location'] = VPATH . $this->config->item('dashboard_page_url');
						echo json_encode($res);
						die;
					}
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	// update the project upgrades into database(edit upgrade/prolong availability) for open for bidding project
	public function ajax_update_project_upgrade(){
	
		if($this->input->is_ajax_request ()){
			
			if (!empty($this->input->post ('project_id')))
			{
				if(check_session_validity()){ // check session exists or not if exist then it will update user session
					$expiration_upgrade_date_array = array();
					$project_id = $this->input->post ('project_id');
					$action_type = $this->input->post ('action_type');
					$project_type = $this->input->post ('project_type');
					$page_type = $this->input->post ('page_type');
					$user = $this->session->userdata ('user');
					$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
					if($user[0]->user_id != $po_id){
						$msg['status'] = 400;
						$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
						echo json_encode($msg);
						die;
					
					}
					$error_msg = '';
					
					$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
					
					if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						if($this->input->post ('action_type') == 'prolong_availability_urgent'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}
						
							
						}elseif($this->input->post ('action_type') == 'prolong_availability_featured'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
								}
							
						}
						elseif($this->input->post ('action_type') == 'upgrade_as_urgent_project'){
						
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}
							
						}elseif($this->input->post ('action_type') == 'upgrade_as_featured_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}
							
						}
						elseif($this->input->post ('action_type') == 'upgrade_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_upgrade_dashboard_myprojects_po_view');
							}
						}
					
					}else{
					
					
						if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
							$project_data = $this->db // get the user detail
							->select('pd.fulltime_project_expiration_date as project_expiration_date')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.fulltime_project_id', $project_id)
							->get()->row_array();
						}else{
							$project_data = $this->db // get the user detail
							->select('pd.project_expiration_date')
							->from($project_status_table_array['table_name'].' pd')
							->where('pd.project_id', $project_id)
							->get()->row_array();
						}
					}
					
					
					if((!empty($project_status_table_array['project_status']) && $project_status_table_array['project_status'] != 'open_for_bidding') || (!empty($project_status_table_array['project_status']) && $project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) < time()) ){
						if($this->input->post ('action_type') == 'prolong_availability_urgent'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_prolong_urgent_upgrade_dashboard_myprojects_po_view');
							}
						
							
						} else if($this->input->post ('action_type') == 'prolong_availability_featured'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_prolong_featured_upgrade_dashboard_myprojects_po_view');
							}
							
						} else if($this->input->post ('action_type') == 'upgrade_as_urgent_project'){
						
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_upgrade_as_urgent_dashboard_myprojects_po_view');
							}
							
						} else if($this->input->post ('action_type') == 'upgrade_as_featured_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_upgrade_as_featured_dashboard_myprojects_po_view');
							}
							
						} else if($this->input->post ('action_type') == 'upgrade_project'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_upgrade_dashboard_myprojects_po_view');
							}
						}
					}
					$this->db->select('current_membership_plan_id');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();

					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					$upgraded_project_price = 0;
					if($this->input->post ('upgrade_type_featured')){
						$upgraded_project_price += $this->config->item('project_upgrade_price_featured');
					}
					if($this->input->post ('upgrade_type_urgent')){
						$upgraded_project_price += $this->config->item('project_upgrade_price_urgent');
					}
					if(floatval($upgraded_project_price) > 0){
						$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
						$total_user_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'] + $user_detail['user_account_balance'];
						if(floatval($upgraded_project_price) > floatval($total_user_balance) ){
							$upgrade_error_messsage = '';
							if($action_type == 'upgrade_project'){
								if($this->input->post ('upgrade_type_featured') && $this->input->post ('upgrade_type_featured') == 'Y' && $this->input->post ('upgrade_type_urgent') && $this->input->post ('upgrade_type_urgent') == 'Y'){
								
								$upgrade_error_messsage = $this->config->item('user_upgrade_project_insufficient_funds_error_message_plural');
								
								}else if(($this->input->post ('upgrade_type_featured') && $this->input->post ('upgrade_type_featured') == 'Y') || ( $this->input->post ('upgrade_type_urgent') && $this->input->post ('upgrade_type_urgent') == 'Y')){
								
									$upgrade_error_messsage = $this->config->item('user_upgrade_project_insufficient_funds_error_message_singular');
								
								}
								
								
							}else if ($action_type == 'upgrade_as_featured_project' || $action_type == 'upgrade_as_urgent_project'){
							
								$upgrade_error_messsage = $this->config->item('user_upgrade_project_insufficient_funds_error_message_singular');
							
							}else if($action_type == 'prolong_availability_featured'){
								
								$upgrade_error_messsage = $this->config->item('user_prolong_featured_upgrade_project_insufficient_funds_error_message');
							
							}else if($action_type == 'prolong_availability_urgent'){
								$upgrade_error_messsage = $this->config->item('user_prolong_urgent_upgrade_project_insufficient_funds_error_message');
							}
							
							$res = array(
									'status' => 400,
									'location' => '',
									'error' => $upgrade_error_messsage // define in post_project_custom config
								);
							echo json_encode($res);
							die;
						}
					}
					$is_featured = '0';
				
					$this->db->select('projects_open_bidding.project_id,
						projects_open_bidding.project_type,
						projects_open_bidding.project_title,
						projects_open_bidding.project_owner_id,
						projects_open_bidding.project_expiration_date,
						projects_open_bidding.featured,
						projects_open_bidding.urgent,
						projects_open_bidding.sealed,
						projects_open_bidding.hidden,
						featured_purchasing_tracking.featured_upgrade_end_date,
						bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,
						urgent_purchasing_tracking.urgent_upgrade_end_date,
						bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,
						membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,
						membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
					$this->db->from('projects_open_bidding');
					
					
					$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'"  and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
				
					$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
					$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
					$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
					$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
					$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
					$this->db->where('projects_open_bidding.project_id',$project_id);
					$this->db->where('projects_open_bidding.project_owner_id',$user[0]->user_id);
					
					$project_result = $this->db->get();
					$project_data = $project_result->result_array();
					
					
					if($error_msg == '') {
						$featured_max = 0;
						$urgent_max = 0;
						$expiration_featured_upgrade_date_array = array();
						$expiration_urgent_upgrade_date_array = array();
						$user_selected_upgrades = array();
						$user_selected_upgrades['project_id'] = $project_id;
						$user_selected_upgrades['project_title'] = $project_data[0]['project_title'];
						$user_selected_upgrades['project_type'] = $project_data[0]['project_type'];
						
						if(!empty($project_data[0]['featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data[0]['featured_upgrade_end_date'];
						}
						if(!empty($project_data[0]['bonus_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data[0]['bonus_featured_upgrade_end_date'];
						}
						if(!empty($project_data[0]['membership_include_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data[0]['membership_include_featured_upgrade_end_date'];
						}
						if(!empty($expiration_featured_upgrade_date_array)){
							$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
						}

						##########

						if(!empty($project_data[0]['urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data[0]['urgent_upgrade_end_date'];
						}
						if(!empty($project_data[0]['bonus_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data[0]['bonus_urgent_upgrade_end_date'];
						}
						if(!empty($project_data[0]['membership_include_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data[0]['membership_include_urgent_upgrade_end_date'];
						}
						if(!empty($expiration_urgent_upgrade_date_array)){
							$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
						}
						
						$expiration_upgrade_date_array[] = $project_data[0]['project_expiration_date'];
						if($this->input->post ('upgrade_type_featured')){
						
							$time_arr = explode(':', $this->config->item('project_upgrade_availability_featured'));
							
							if(empty($featured_max) || $featured_max <= time()){
								$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
								$project_upgrade_featured_prolong_status = '0';
								$project_upgrade_start_date = date('Y-m-d H:i:s');
							}else{
								
								$upgrade_end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',$featured_max));
								$project_upgrade_featured_prolong_status = '1';
								$project_upgrade_start_date = date('Y-m-d H:i:s', $featured_max);
							}
							$this->db->update('projects_open_bidding', ['featured' => 'Y'], ['project_id' => $project_id]);
							$expiration_upgrade_date_array[] = $upgrade_end_date;
							$is_featured = '1';
							$user_selected_upgrades['featured'] = 'Y';
							$user_selected_upgrades['featured_upgrade_end_date'] = $upgrade_end_date;
							$user_selected_upgrades['project_upgrade_featured_prolong_status'] = $project_upgrade_featured_prolong_status;
							$user_selected_upgrades['project_upgrade_start_date'] = $project_upgrade_start_date; 
						
						}
						//if($count_featured_upgrade_project_row == 0 && $this->input->post ('upgrade_type_urgent')){
						if($this->input->post ('upgrade_type_urgent')){
							$time_arr = explode(':', $this->config->item('project_upgrade_availability_urgent'));
							if(empty($urgent_max) || $urgent_max <= time()){
								$upgrade_end_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds')); 
								$project_upgrade_urgent_prolong_status = '0';
								$project_upgrade_start_date = date('Y-m-d H:i:s');
							} else {
								
								$upgrade_end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',$urgent_max));
								$project_upgrade_urgent_prolong_status = '1';
								$project_upgrade_start_date = date('Y-m-d H:i:s', $urgent_max);
							}
						
							$expiration_upgrade_date_array[] = $upgrade_end_date;
							$this->db->update('projects_open_bidding', ['urgent' => 'Y'], ['project_id' => $project_id]);
							$user_selected_upgrades['urgent'] = 'Y';
							$user_selected_upgrades['urgent_upgrade_end_date'] = $upgrade_end_date;
							$user_selected_upgrades['project_upgrade_urgent_prolong_status'] = $project_upgrade_urgent_prolong_status;
							$user_selected_upgrades['project_upgrade_start_date'] = $project_upgrade_start_date; 
						}
						if($this->input->post ('upgrade_type_featured') || $this->input->post ('upgrade_type_urgent') && !empty($expiration_upgrade_date_array)){
							
							$max = max(array_map('strtotime', $expiration_upgrade_date_array));
							$this->db->update('projects_open_bidding', ['project_expiration_date'=>date('Y-m-d H:i:s', $max)], ['project_id' => $project_id]);
							$this->Projects_model->user_project_upgrade_purchase_refresh_sequence_tracking_membership_exclude_save($user_selected_upgrades,$user[0]->user_id);// track purchasing and refresh sequence;
							//$this->Projects_model->user_project_upgrade_purchase_refresh_sequence_tracking_membership_exclude_save($user_selected_upgrades,$user[0]->user_id,$expiration_upgrade_date_array);// track purchasing and refresh sequence;
							
							
						}
						
						/* fetch the information of project upgrade start */
						$this->db->select('projects_open_bidding.project_id,projects_open_bidding.project_title,projects_open_bidding.project_type,projects_open_bidding.project_owner_id,projects_open_bidding.project_expiration_date,projects_open_bidding.featured,projects_open_bidding.urgent,projects_open_bidding.sealed,projects_open_bidding.hidden,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
						$this->db->from('projects_open_bidding');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'"  and project_id = "'.$project_id.'" group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
					
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent" and project_owner_id = "'.$user[0]->user_id.'" and project_id = "'.$project_id.'" group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = projects_open_bidding.project_id', 'left');
						
						$this->db->where('projects_open_bidding.project_id',$project_id);
						$this->db->where('projects_open_bidding.project_owner_id',$user[0]->user_id);
						
						
						$project_result = $this->db->get();
						$project_data = $project_result->result_array();
					}
					
					if($error_msg != '' && $page_type == "my_projects"){
						if($this->uri->segment('3')){
						$page = $this->uri->segment('3');
						}else{
						$page = 1;
						}	
						$new_counter = $page;
						
						if($page != null) {
					
							if($page == 1) {
								$start = 0;
								} else {
								
							$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
									
							}
						} else {
							$start = 0;
						}
						
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
						$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
						$page = $new_counter;
						if(empty($data["open_bidding_project_data"])){
							$page = $new_counter-1;
							if($page != null) {
							
								if($page == 1) {
									$start = 0;
								} else {
									
								$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
								
									
								}
							} else {
								$start = 0;
								}
						}
						
					$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
					
					}
					if($error_msg != '' && $page_type == "dashboard"){
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
					}
					if($error_msg != ''){
					$data["page_type"] = $page_type;
					$data["project_type"] = $project_type;
					$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
					$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
					}
					$open_project_cnt = $open_bidding_project_data['total'];
					$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
					$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
					$open_project_cnt -= $fulltime_open_project_count;
					if($page_type == "my_projects" &&  $error_msg != ''){
						$paginations = generate_pagination_links($open_bidding_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => $tab_type]);
						$data['open_bidding_pagination_links'] = $paginations['links'];
						
						$multiplication = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') - count($data['open_bidding_project_data'])));
						$record_per_page = count($data['open_bidding_project_data']) < $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * ($page - 1)) + 1;
						echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','status' => 200,'error_msg'=>$error_msg,'location'=>'','record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$open_bidding_project_data['total'],'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($error_msg != '' && $page_type=="dashboard"){
						echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($error_msg == ''){
						$featured_max = 0;
						$urgent_max = 0;
						$expiration_featured_upgrade_date_array = array();
						$expiration_urgent_upgrade_date_array = array();

						if(!empty($project_data[0]['featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data[0]['featured_upgrade_end_date'];
						}
						if(!empty($project_data[0]['bonus_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data[0]['bonus_featured_upgrade_end_date'];
						}
						if(!empty($project_data[0]['membership_include_featured_upgrade_end_date'])){
							$expiration_featured_upgrade_date_array[] = $project_data[0]['membership_include_featured_upgrade_end_date'];
						}
						if(!empty($expiration_featured_upgrade_date_array)){
							$featured_max = max(array_map('strtotime', $expiration_featured_upgrade_date_array));
						}

						##########

						if(!empty($project_data[0]['urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data[0]['urgent_upgrade_end_date'];
						}
						if(!empty($project_data[0]['bonus_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data[0]['bonus_urgent_upgrade_end_date'];
						}
						if(!empty($project_data[0]['membership_include_urgent_upgrade_end_date'])){
							$expiration_urgent_upgrade_date_array[] = $project_data[0]['membership_include_urgent_upgrade_end_date'];
						}
						
						
						if(!empty($expiration_urgent_upgrade_date_array)){
							$urgent_max = max(array_map('strtotime', $expiration_urgent_upgrade_date_array));
						}
						
						$open_for_bidding_project_upgrades_badges_html = '';
						$open_for_bidding_project_upgrades_prolong_availability_information_html = '';
						$open_for_bidding_project_actions_html = '';
						if($project_data[0]['featured'] == 'Y' && $featured_max != 0 && $featured_max > time() ){
							$open_for_bidding_project_upgrades_badges_html .= '<button type="button" class="btn badge_feature">'.$this->config->item('post_project_page_upgrade_type_featured').'</button>';
						}if($project_data[0]['urgent'] == 'Y' && $urgent_max != 0 && $urgent_max > time() ){
							$open_for_bidding_project_upgrades_badges_html .= '<button type="button" class="btn badge_urgent">'.$this->config->item('post_project_page_upgrade_type_urgent').'</button>';
						}if($project_data[0]['sealed'] == 'Y'){
							$open_for_bidding_project_upgrades_badges_html .= '<button type="button" class="btn badge_sealed">'.$this->config->item('post_project_page_upgrade_type_sealed').'</button>';
						}
						if($project_data[0]['hidden'] == 'Y'){
							$open_for_bidding_project_upgrades_badges_html .= '<button type="button" class="btn badge_hidden">'.$this->config->item('post_project_page_upgrade_type_hidden').'</button>';
						}
						
						if($project_data[0]['featured'] == 'Y' && $featured_max != 0 && $featured_max > time() ){
						
						$open_for_bidding_project_upgrades_prolong_availability_information_html .= '<li><label><span class="default_black_bold">'.$this->config->item('project_featured_upgrade_txt_po_dashboard_myprojects_section_view').'</span><small class="default_black_regular">'.$this->config->item('project_featured_upgrade_expires_on_txt_po_dashboard_myprojects_section_view').'&nbsp;'.date(DATE_TIME_FORMAT,$featured_max).'</small></label><button type="button" data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'"  data-attr= "'.$project_id.'" data-project-type= "'.$project_data[0]['project_type'].'" class="btn upgrade_project default_btn blue_btn btnBold btn_style_2_16" data-action-type="prolong_availability_featured">'.$this->config->item('project_featured_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view').'</button></li>';
						}
						
						if($project_data[0]['urgent'] == 'Y' &&  $urgent_max != 0 && $urgent_max > time()){
							$open_for_bidding_project_upgrades_prolong_availability_information_html .= '<li><label><span class="default_black_bold">'.$this->config->item('project_urgent_upgrade_txt_po_dashboard_myprojects_section_view').'</span><small class="default_black_regular">'.$this->config->item('project_urgent_upgrade_expires_on_txt_po_dashboard_myprojects_section_view').'&nbsp;'.date(DATE_TIME_FORMAT,$urgent_max).'</small></label><button type="button" data-project-type= "'.$project_data[0]['project_type'].'" data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'"  data-attr= "'.$project_id.'" class="btn upgrade_project default_btn blue_btn btnBold btn_style_2_16" data-action-type="prolong_availability_urgent">'.$this->config->item('project_urgent_upgrade_prolong_availability_txt_po_dashboard_myprojects_section_view').'</button></li>';
						}
						
						
						$open_for_bidding_project_actions_html .= '<a class="dropdown-item edit_project" data-attr= "'.$project_id.'" style="cursor:pointer" data-po-id="'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'" data-project-type="'.$project_data[0]['project_type'].'">'.$this->config->item('myprojects_section_open_for_bidding_tab_option_edit_project_po_view').'</a>';
						if($project_data[0]['project_type'] == 'fulltime'){			
							$cancel_open_for_bidding_project_modal_title = $this->config->item('cancel_open_for_bidding_fulltime_project_modal_title');
							$cancel_open_for_bidding_project_modal_cancel_btn_txt = $this->config->item('cancel_open_for_bidding_fulltime_project_modal_cancel_btn_txt');
							$cancel_open_for_bidding_project_modal_close_btn_txt = $this->config->item('cancel_open_for_bidding_fulltime_project_modal_close_btn_txt');
						}else{
							$cancel_open_for_bidding_project_modal_title = $this->config->item('cancel_open_for_bidding_project_modal_title');
							$cancel_open_for_bidding_project_modal_cancel_btn_txt = $this->config->item('cancel_open_for_bidding_project_modal_cancel_btn_txt');
							$cancel_open_for_bidding_project_modal_close_btn_txt = $this->config->item('cancel_open_for_bidding_project_modal_close_btn_txt');
						}
						$open_for_bidding_project_actions_html .= '<a class="dropdown-item cancel_project" data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'" data-project_title = "'.$project_data[0]['project_title'].'" data-project-type="'.$project_data[0]['project_type'].'" data-modal-close-button-txt = "'.$cancel_open_for_bidding_project_modal_close_btn_txt.'" data-modal-cancel-button-txt = "'.$cancel_open_for_bidding_project_modal_cancel_btn_txt.'" data-modal-title = "'.$cancel_open_for_bidding_project_modal_title.'" data-project-status="open" data-attr="'.$project_id.'" style="cursor:pointer">'.$this->config->item('myprojects_section_open_for_bidding_tab_option_cancel_project_po_view').'</a>';
						
						
						
						
						if($project_data[0]['featured'] == 'N' && $project_data[0]['urgent'] == 'N' && (($featured_max == 0 && $urgent_max == 0) || ($featured_max < time() && $urgent_max < time()) )){
						
							$open_for_bidding_project_actions_html.= '<a id="upgrade_project_'.$project_id.'" data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'" data-project-type= "'.$project_data[0]['project_type'].'"  class="dropdown-item upgrade_project" style="cursor:pointer" data-attr= "'.$project_id.'"data-action-type="upgrade_project">'.$this->config->item('myprojects_section_open_for_bidding_tab_option_upgrade_project_po_view').'</a>';
						
						}elseif($project_data[0]['featured'] == 'N' && $project_data[0]['urgent'] == 'Y' && ($featured_max == 0 || $featured_max< time())){
							$open_for_bidding_project_actions_html.= '<a id="upgrade_project_'.$project_id.'" data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'"  data-project-type= "'.$project_data[0]['project_type'].'"  class="dropdown-item upgrade_project" style="cursor:pointer" data-attr= "'.$project_id.'"data-action-type="upgrade_as_featured_project">'.$this->config->item('myprojects_section_open_for_bidding_tab_option_upgrade_as_featured_project_po_view').'</a>';
						}elseif($project_data[0]['featured'] == 'Y' && $project_data[0]['urgent'] == 'N' &&  ($urgent_max == 0 ||  $urgent_max < time())){
							$open_for_bidding_project_actions_html.= '<a id="upgrade_project_'.$project_id.'" data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'"  data-project-type= "'.$project_data[0]['project_type'].'"  class="dropdown-item upgrade_project" style="cursor:pointer" data-attr= "'.$project_id.'"data-action-type="upgrade_as_urgent_project">'.$this->config->item('myprojects_section_open_for_bidding_tab_option_upgrade_as_urgent_project_po_view').'</a>';
						} 
						$open_for_bidding_project_actions_html.= '<a data-po-id= "'.Cryptor::doEncrypt($project_data[0]['project_owner_id']).'" data-project-type= "'.$project_data[0]['project_type'].'"  data-project-status ="open" class="dropdown-item repost_project"  data-attr="'.$project_id.'" style="cursor:pointer">'.$this->config->item('myprojects_section_open_for_bidding_tab_option_copy_into_new_project_po_view').'</a>';
						/* fetch the information of project upgrade end */
						$res['project_id'] = $project_id;
						$res['open_for_bidding_project_upgrades_badges_html'] = $open_for_bidding_project_upgrades_badges_html;
						$res['open_for_bidding_project_upgrades_prolong_availability_information_html'] = $open_for_bidding_project_upgrades_prolong_availability_information_html;
						$res['open_for_bidding_project_actions_html'] = $open_for_bidding_project_actions_html;
						$res['is_featured'] = $is_featured;
						$res['status'] = 200;
						$res['error_msg'] = '';
						echo json_encode($res);
						return;
					}	
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
				
			}else{
				show_custom_404_page(); //show custom 404 page
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	public function show_project_upgrade_balance_message(){
		if($this->input->is_ajax_request ()){
			$upgrade_message = '';
			if(!empty($this->input->post())){
				if(check_session_validity()){ // check session exists or not if exist then it will update user session
					$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
					$project_id  = $this->input->get('project_id');
					
					$project_data = $this->db // get the user detail
					->select('project_type')
					->from('projects_open_bidding')
					->where('project_id', $project_id)
					->get()->row_array();
					
					
					$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user_id); // count user membership featured  upgrade
					
					$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user_id);// count user membership urgent upgrade
					
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
					$total_user_upgrade_balance = false;
					
					if(!empty($this->input->post ('upgrade_type_featured'))){
					 
						$total_user_upgrade_balance = true;
					}
					if(!empty($this->input->post ('upgrade_type_urgent'))){
						
						$total_user_upgrade_balance = true;
						
					}
					if($total_user_upgrade_balance){
						$total_bonus_balance = $user_detail['bonus_balance'] + $user_detail['signup_bonus_balance'];
						$upgrade_message .= '<div class="form-group col-md-12 disclaimer default_terms_text"><div class="default_checkbox default_small_checkbox"><input class="checked_input" value="1" name=""  type="checkbox" checked><small class="checkmark"></small></div>';
						
						if($project_data['project_type'] == 'fulltime'){
							$upgrade_message.=	$this->config->item('post_fulltime_project_disclaimer_payments_for_upgrades_are_final');
								
						}else{
							$upgrade_message.= $this->config->item('post_project_disclaimer_payments_for_upgrades_are_final');
						}
						$upgrade_message.= '</div>';
						if(floatval($total_bonus_balance) > 0 ){
							
							if($project_data['project_type'] == 'fulltime'){
								$disclaimer_user_agreement_payment_bonus_project_upgrades = $this->config->item('post_fulltime_project_disclaimer_user_agreement_for_payment_from_bonus_for_project_upgrades');
							
							}else{
								$disclaimer_user_agreement_payment_bonus_project_upgrades = $this->config->item('post_project_disclaimer_user_agreement_for_payment_from_bonus_for_project_upgrades');
							}
							
							$disclaimer_user_agreement_payment_bonus_project_upgrades = str_replace('{bonus_balance}',str_replace(".00","",number_format($total_bonus_balance,  2, '.', ' '))."&nbsp;".CURRENCY,$disclaimer_user_agreement_payment_bonus_project_upgrades);
							
							$upgrade_message .= '<div class="col-md-12 bonus_balance default_terms_text">
							<div class="default_checkbox default_small_checkbox">
								<input class="checked_input" value="1" name=""  type="checkbox" checked>
								<small class="checkmark"></small>
							 </div>'.$disclaimer_user_agreement_payment_bonus_project_upgrades.'</div>';
						
						}
					}
					$msg['status'] = 'SUCCESS';
					$msg['upgrade_message'] = $upgrade_message;
					echo json_encode ($msg);die;
				}else{
					$msg['status'] = 400;
					$msg['location'] = VPATH;
					echo json_encode($msg);
					die;
				}
				
			}else{
				$msg['status'] = 'SUCCESS';
				$msg['upgrade_message'] = $upgrade_message;
				echo json_encode ($msg);die;
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	// This function is used to save the cover picture of featured project.
	public function save_featured_project_cover_picture() {
		if($this->input->is_ajax_request ()){
		
			if(empty($this->input->post ('id'))){
				show_404();
			}
			$project_id = $this->input->post ('id');
			$msg['location'] = '';
			if(check_session_validity()){ 
				$project_type = $this->input->post ('project_type');
				$user = $this->session->userdata ('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					if($project_type  == 'fulltime'){
						$error_msg = $this->config->item('project_details_page_featured_fulltime_project_deleted_error_message');
					}else{
						$error_msg = $this->config->item('project_details_page_featured_project_deleted_error_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name']) && $project_status_table_array['project_status'] != 'open_for_bidding' && $project_status_table_array['project_status'] != 'awarded'){
				
					if($project_type  == 'fulltime'){
						$error_msg = $this->config->item('project_details_page_featured_fulltime_project_status_changed_error_message');
					}else{
						$error_msg = $this->config->item('project_details_page_featured_project_status_changed_error_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				
				}
				$project_data = array();
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
						$project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
				}
				else if($project_status_table_array['project_status'] == 'awarded'){
					if($project_type  == 'fixed'){
						$project_data = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
					}
					if($project_type  == 'hourly'){
						$project_data = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
					}
				}
				
				
				
				$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
				if(!empty($project_data) && $project_expiration_date < time()){
					
					$msg['status'] = 400;
					if($project_data['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('fulltime_project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message');
					}else{
					$msg['error'] = $this->config->item('project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message');
					}
					echo json_encode($msg);
					die;
				}
				
				$expiration_status = $this->Projects_model->get_featured_project_upgrade_expiration_status($project_id,array('project_type'=>$project_data['project_type'],'project_status'=>$project_status_table_array['project_status']));
				
			
				if($expiration_status){
					$project_cover_picture_data = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_id))->row_array();
					
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);
					$users_ftp_dir 	= USERS_FTP_DIR;
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
					$profile_folder =$user[0]->profile_name;
					
					
					##############Delete the old cover picture of portfolio start ####
					
					if(!empty($project_cover_picture_data) && !empty($project_cover_picture_data['project_cover_picture_name'])){
						
						$source_path =$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture  ;
						$old_file = $project_cover_picture_data['project_cover_picture_name'];
						$check_cover_picture = $this->ftp->get_filesize($source_path . $old_file);
						
						if ($check_cover_picture != '-1') {
							$this->ftp->delete_file($source_path . $old_file);
						}
						$this->db->delete('featured_projects_users_upload_cover_pictures_tracking', array("project_id" => $project_id));
					}
					
					
					$image_name = '';
					if(!empty($_FILES)){
						foreach($_FILES['files']['name'] as $key => $val) {
						$temp 		= 	explode(".", $val);
						$extension 	= 	end($temp);
						$attachment_name = round(microtime(true) * 1000);
						$image_name = $attachment_name.'.'.$extension;
							if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$image_name)){
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir . $profile_folder.DIRECTORY_SEPARATOR);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
								$project_dir = '';
								if($project_status_table_array['project_status'] == 'open_for_bidding'){
								
									$project_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
								}else if($project_status_table_array['project_status'] == 'awarded'){
									$project_dir = PROJECT_AWARDED_DIR;
								}
								
					
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir);
								
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id.DIRECTORY_SEPARATOR);
							
								$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id.$project_featured_upgrade_cover_picture);
								$this->ftp->upload(FCPATH . TEMP_DIR . $image_name, $users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_dir.$project_id.$project_featured_upgrade_cover_picture .$image_name, 'auto', 0777);
								 unlink(FCPATH . TEMP_DIR . $image_name);
								 $this->db->insert('featured_projects_users_upload_cover_pictures_tracking', array('project_id'=>$project_id,'project_cover_picture_name'=>$image_name,'project_cover_picture_upload_date'=>date('Y-m-d H:i:s')));
							
							}
						}
					
					}
					$msg['status'] = 200;
					$msg['cover_picture'] = $image_name;
					/* echo "<pre>";
					print_r($_FILES['files']['name']);
					die; */
					
				}else{
					
					$msg['status'] = 'project_featured_upgrade_expired';
					if($project_data['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('fulltime_project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message');
					}else{
					$msg['error'] = $this->config->item('project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message');
					}
				}
				echo json_encode($msg);
				die;
			}else{
				//$this->Projects_model->get_featured_project_upgrade_expiration_status($project_id);
				$msg['status'] = 400;
				$msg['location'] =  VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}	
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	// This function is used to cancel the cover picture of featured project.
	public function cancel_featured_project_cover_picture() {
		 if ($this->input->is_ajax_request()) {
			$project_id = $this->input->post ('id');
			$project_type = $this->input->post ('project_type');
			$msg['location'] = '';
			if (check_session_validity()) {
				$user = $this->session->userdata('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					if($project_type  == 'fulltime'){
						$error_msg = $this->config->item('project_details_page_featured_fulltime_project_deleted_error_message');
					}else{
						$error_msg = $this->config->item('project_details_page_featured_project_deleted_error_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name']) && $project_status_table_array['project_status'] != 'open_for_bidding' && $project_status_table_array['project_status'] != 'awarded'){
				
					if($project_type  == 'fulltime'){
						$error_msg = $this->config->item('project_details_page_featured_fulltime_project_status_changed_error_message');
					}else{
						$error_msg = $this->config->item('project_details_page_featured_project_status_changed_error_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				
				}
				$project_data = array();
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
				}
				else if($project_status_table_array['project_status'] == 'awarded'){
					if($project_type  == 'fixed'){
						$project_data = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
					}
					if($project_type  == 'hourly'){
						$project_data = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
					}
				}
				$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
				if(!empty($project_data) && $project_expiration_date < time()){
					
					$msg['status'] = 400;
					if($project_data['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('fulltime_project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message');
					}else{
					$msg['error'] = $this->config->item('project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message');
					}
					echo json_encode($msg);
					die;
				}
				$expiration_status = $this->Projects_model->get_featured_project_upgrade_expiration_status($project_id,array('project_type'=>$project_data['project_type'],'project_status'=>$project_status_table_array['project_status']));
				if($expiration_status){
					$msg['location'] = '';
					$project_cover_picture_data = $this->db->get_where('featured_projects_users_upload_cover_pictures_tracking', array('project_id' => $project_id))->row_array();
					$cover_file = 	'';
					//$original_cover_name = '';
					
					if(!empty($project_cover_picture_data['project_cover_picture_name'])){	
						$cover_file = $project_cover_picture_data['project_cover_picture_name'];
						//$ex = explode('.', $project_cover_picture_data['project_cover_picture_name']);
						//$original_cover_name = $ex[0] . "_original.png";
					}
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);

					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] = FTP_PORT;
					$config['debug'] = TRUE;
					$this->ftp->connect($config);

					$users_ftp_dir = USERS_FTP_DIR;
					$profile_folder =$user[0]->profile_name;
					$projects_ftp_dir = PROJECTS_FTP_DIR;
					$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
					$project_awarded_dir = PROJECT_AWARDED_DIR;
					$project_featured_upgrade_cover_picture = PROJECT_FEATURED_UPGRADE_COVER_PICTURE;
					if($project_status_table_array['project_status'] == 'open_for_bidding'){
						$source_path =$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture;
					}
					else if($project_status_table_array['project_status'] == 'awarded'){
						$source_path =$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture;
					}
					
					//echo $users_ftp_dir.$profile_folder.$upload_folder.$data['org_image_name'];exit;
					//$source_path_original = $source_path . $original_cover_name;
					//$source_path_crop = $source_path . $cover_file;
					
					//$file_size_original = $this->ftp->get_filesize($source_path_original);
					//$file_size = $this->ftp->get_filesize($source_path_original);
					$file_size = $this->ftp->get_filesize($source_path . $cover_file);
					$file_exists = '0';
					if ($file_size == '-1') {
						$this->db->delete('featured_projects_users_upload_cover_pictures_tracking', array("project_id" => $portfolio_data['project_id']));
						if(!empty($this->ftp->check_ftp_directory_exist($source_path))){
							$this->ftp->delete_dir($source_path);
						}
						
					} else {
						$file_exists = '1';
						if($project_status_table_array['project_status'] == 'open_for_bidding'){
							$project_cover_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id.$project_featured_upgrade_cover_picture.$cover_file;
						}
						else if($project_status_table_array['project_status'] == 'awarded'){
						
							$project_cover_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_awarded_dir.$project_id.$project_featured_upgrade_cover_picture.$cover_file;
						}
					}
					$this->ftp->close();
					$msg['project_cover_picture'] = $project_cover_picture;
					$msg['file_exists'] = $file_exists;
					$msg['status'] = 200;
					
				}else{
					$msg['status'] = 'project_featured_upgrade_expired';
					if($project_data['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('fulltime_project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message');
					}else{
					$msg['error'] = $this->config->item('project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message');
					}
				}
				
				//echo URL .TEMP_DIR . $original_image_name;
			} else {
				$msg['status'] = 400;
                $msg['location'] =  VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
			}
			echo json_encode($msg);die;
		}else{
			show_custom_404_page(); //show custom 404 page
		}
    }
	
	// This function is used to delete the cover picture of featured project.
	public function delete_featured_project_cover_picture() {
		if($this->input->is_ajax_request ()){
			$msg['location'] = '';
			$project_id = $this->input->post ('id');
			$project_type = $this->input->post ('project_type');
			if(empty($this->input->post ('id'))){
				show_custom_404_page(); //show custom 404 page
			}
			if(check_session_validity()){ 
				
				$user = $this->session->userdata ('user');
				$user_id = Cryptor::doDecrypt($this->input->post ('uid'));
				if($user_id != $user[0]->user_id){
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
					if($project_type  == 'fulltime'){
						$error_msg = $this->config->item('project_details_page_featured_fulltime_project_deleted_error_message');
					}else{
						$error_msg = $this->config->item('project_details_page_featured_project_deleted_error_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
				if(!empty($project_status_table_array['project_status']) && !empty($project_status_table_array['table_name']) && $project_status_table_array['project_status'] != 'open_for_bidding' && $project_status_table_array['project_status'] != 'awarded'){
				
					if($project_type  == 'fulltime'){
						$error_msg = $this->config->item('project_details_page_featured_fulltime_project_status_changed_error_message');
					}else{
						$error_msg = $this->config->item('project_details_page_featured_project_status_changed_error_message');
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				
				}
				$project_data = array();
				
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
					}
						
				else if($project_status_table_array['project_status'] == 'awarded'){
					if($project_type  == 'fixed'){
						$project_data = $this->db->get_where('fixed_budget_projects_awarded', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
						
					}
					if($project_type  == 'hourly'){
						$project_data = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_id' => $project_id,'project_owner_id'=>$user[0]->user_id])->row_array();
					}
				}
				$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
				
				if(!empty($project_data) && $project_expiration_date < time()){
					
					$msg['status'] = 400;
					if($project_data['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('fulltime_project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message');
					}else{
					$msg['error'] = $this->config->item('project_details_page_expired_featured_project_upload_cover_picture_po_view_error_message');
					}
					echo json_encode($msg);
					die;
				}
				$expiration_status = $this->Projects_model->get_featured_project_upgrade_expiration_status($project_id,array('project_type'=>$project_data['project_type'],'project_status'=>$project_status_table_array['project_status']));
				if($expiration_status){
					$msg['status'] = 200;
					$profile_folder =$user[0]->profile_name;
					$this->Projects_model->delete_featured_project_upgrade_record_cover_picture($profile_folder,$project_id);
				}else{
					
					$msg['status'] = 'project_featured_upgrade_expired';
					if($project_data['project_type'] == 'fulltime'){
					$msg['error'] = $this->config->item('fulltime_project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message');
					}else{
					$msg['error'] = $this->config->item('project_details_page_expired_featured_upgrade_upload_cover_picture_po_view_error_message');
					}
					
				}
				echo json_encode($msg);
				die;
		
			}else{
				//$this->Projects_model->get_featured_project_upgrade_expiration_status($project_id);
				$msg['status'] = 400;
				$msg['location'] =  VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
				echo json_encode($msg);
				die;
			}
		
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	
	}
	
	
	
	//--- Cover Picture Upload Function Area End -----
	
	// This function is used two reset the project upgrade amount
	public function reset_project_upgrade_badge_amount_container(){
		if($this->input->is_ajax_request ()){
		
			if(empty($this->input->post('project_id'))){
				show_custom_404_page(); //show custom 404 page
			}
			$project_id = $this->input->post('project_id');
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
			
				$this->db->where('project_id', $project_id);
				$project_data = $this->db->get('projects_draft')->row_array();
				
				if(empty($project_data)) { // if project not exists it will redirect to dasboard page
					$res = [
						'status' => 400,
						'location'=>VPATH.$this->config->item('dasboard_page_url')
					];
					echo json_encode($res);
					die;
				}
				$user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				
				$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
				$user_membership_plan_detail = $this->db->get_where('membership_plans', ['id' => $user_detail['current_membership_plan_id']])->row_array();
				
				
				$upgrade_type_featured_amount_html = '';
				$upgrade_type_urgent_amount_html = '';
				
				$count_user_featured_membership_included_upgrades_monthly = $this->Post_project_model->count_user_featured_membership_included_upgrades_monthly($user_id); // count user membership featured  upgrade
					
				$count_user_urgent_membership_included_upgrades_monthly = $this->Post_project_model->count_user_urgent_membership_included_upgrades_monthly($user_id);// count user membership urgent upgrade
				
				if($count_user_featured_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_featured_upgrades']){
				
					$upgrade_type_featured_amount_html = '<span><span id="upgrade_type_featured_amount" data-attr="0">Free</span></span>';
				
				}else{
				
					$upgrade_type_featured_amount_html = '<span><span id="upgrade_type_featured_amount" data-attr="'.number_format($this->config->item('project_upgrade_price_featured')).'">'.number_format($this->config->item('project_upgrade_price_featured')).'</span> KÄ</span>';
				
				}
				if($count_user_urgent_membership_included_upgrades_monthly <$user_membership_plan_detail['included_number_urgent_upgrades']){
				
					$upgrade_type_urgent_amount_html = '<span><span id="upgrade_type_urgent_amount" data-attr="0">Free</span></span>';
				
				}else{
				
					$upgrade_type_urgent_amount_html = '<span><span id="upgrade_type_urgent_amount" data-attr="'.number_format($this->config->item('project_upgrade_price_urgent')).'">'.number_format($this->config->item('project_upgrade_price_urgent')).'</span> KÄ</span>';
				
				}
					
				$msg['upgrade_type_featured_amount_html'] = $upgrade_type_featured_amount_html;
				$msg['upgrade_type_urgent_amount_html'] = $upgrade_type_urgent_amount_html;
				$msg['status'] = 'SUCCESS';
				$msg['upgrade_message'] = '';
				echo json_encode ($msg);
			}else{
				$msg['status'] = 400;
				$msg['location'] = VPATH;
				echo json_encode($msg);
				die;
			}
			
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	// This method is used to repost/copy the project
	public function repost_project() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
		}if(empty($this->input->post ('id'))){
			show_custom_404_page(); //show custom 404 page
			return;
		}	
		
		if(check_session_validity()){ 
			
			$user = $this->session->userdata ('user');
			
			$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
			if($user[0]->user_id != $po_id){
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
				echo json_encode($msg);
				die;
			
			}
			$check_project_categories_available_or_not = $this->db->where(['status'=>'Y','parent_id'=>0])->from('categories_projects')->count_all_results();
			if($check_project_categories_available_or_not == 0){
				echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('projects_categories_disabled_or_deleted_project_posting_disabled_message')]);
				die;
			}
			$project_id = $this->input->post ('id');
			$project_status = $this->input->post ('project_status');
			$cancelled_by = $this->input->post ('cancelled_by');
			$project_type = $this->input->post ('project_type');
			$page_type = $this->input->post ('page_type');
			
			
			$error_msg = '';
			// standard project availability
			$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
			$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
			$standard_valid_time_arr = array_filter($standard_check_valid_arr);
			$this->db->select('current_membership_plan_id');
			$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();

			if($user_detail['current_membership_plan_id'] == 1){
				$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
				$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
				
				$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
				$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
			}
			if($user_detail['current_membership_plan_id'] == 4){
				$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
				$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
				
				$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
				$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
			}
			
			if($project_type != 'fulltime'){ 
				if($user_memebership_max_number_of_draft_projects == '0' && ($user_memebership_max_number_of_open_for_bidding_projects == '0' || empty($standard_valid_time_arr))){
					if($user_detail['current_membership_plan_id'] == 1){
						if($project_status == 'cancelled' && $cancelled_by == 'admin'){
							$error_msg = $this->config->item('free_membership_subscriber_copy_into_new_post_project_project_posting_disabled_message');
						}
						if($project_status == 'cancelled' && $cancelled_by == 'user'){
							$error_msg = $this->config->item('free_membership_subscriber_repost_project_project_posting_disabled_message');
						}
						if($project_status == 'open' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'incomplete' || $project_status == 'completed'){
							$error_msg = $this->config->item('free_membership_subscriber_copy_into_new_post_project_project_posting_disabled_message');
						}
						if($project_status == 'expired'){
							$error_msg = $this->config->item('free_membership_subscriber_repost_project_project_posting_disabled_message');
						}
					}
					if($user_detail['current_membership_plan_id'] == 4){
						if($project_status == 'cancelled' && $cancelled_by == 'admin'){
							$error_msg = $this->config->item('gold_membership_subscriber_copy_into_new_post_project_project_posting_disabled_message');
						}
						if($project_status == 'cancelled' && $cancelled_by == 'user'){
							$error_msg = $this->config->item('gold_membership_subscriber_repost_project_project_posting_disabled_message');
						}
						if($project_status == 'open' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'incomplete' || $project_status == 'completed'){
							$error_msg = $this->config->item('gold_membership_subscriber_copy_into_new_post_project_project_posting_disabled_message');
						}
						if($project_status == 'expired'){
							$error_msg = $this->config->item('gold_membership_subscriber_repost_project_project_posting_disabled_message');
						}
					}
					
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
			} else {
				if($user_memebership_max_number_of_draft_fulltime_projects == '0' && ($user_memebership_max_number_of_open_for_bidding_fulltime_projects == '0' || empty($standard_valid_time_arr))){
					if($user_detail['current_membership_plan_id'] == 1){
						if($project_status == 'cancelled' && $cancelled_by == 'admin'){
							$error_msg = $this->config->item('free_membership_subscriber_copy_into_new_post_project_fulltime_project_posting_disabled_message');
						}
						if($project_status == 'cancelled' && $cancelled_by == 'user'){
							$error_msg = $this->config->item('free_membership_subscriber_repost_project_fulltime_project_posting_disabled_message');
						}
						if($project_status == 'open' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'incomplete' || $project_status == 'completed'){
							$error_msg = $this->config->item('free_membership_subscriber_copy_into_new_post_project_fulltime_project_posting_disabled_message');
						}
						if($project_status == 'expired'){
							$error_msg = $this->config->item('free_membership_subscriber_repost_project_fulltime_project_posting_disabled_message');
						}
					}
					if($user_detail['current_membership_plan_id'] == 4){
						if($project_status == 'cancelled' && $cancelled_by == 'admin'){
							$error_msg = $this->config->item('gold_membership_subscriber_copy_into_new_post_project_fulltime_project_posting_disabled_message');
							}
						if($project_status == 'cancelled' && $cancelled_by == 'user'){
							$error_msg = $this->config->item('gold_membership_subscriber_repost_project_fulltime_project_posting_disabled_message');
						}
						if($project_status == 'open' || $project_status == 'awarded' || $project_status == 'in_progress' || $project_status == 'incomplete' || $project_status == 'completed'){
							$error_msg = $this->config->item('gold_membership_subscriber_copy_into_new_post_project_fulltime_project_posting_disabled_message');
						}
						if($project_status == 'expired'){
							$error_msg = $this->config->item('gold_membership_subscriber_repost_project_fulltime_project_posting_disabled_message');
						}
						
					}
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$error_msg]);
					die;
				}
			}
			
			
			$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
			if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
				
				if($project_status == 'open'){
					
					if($project_type == 'fulltime'){
						$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view');
					}else{
						$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view');
					}
					
					//$msg['error_message'] = $error_msg;
				}
				if($project_status == 'cancelled' && $cancelled_by == 'user'){
					if($project_type == 'fulltime'){
						$error_msg = $this->config->item('fulltime_project_cancelled_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view');
					}else{
						$error_msg = $this->config->item('project_cancelled_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view');
						
					}
					//$msg['error_message'] = $error_msg;
				}
				if($project_status == 'cancelled' && $cancelled_by == 'admin'){
					if($project_type == 'fulltime'){
						$error_msg = $this->config->item('fulltime_project_cancelled_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view');
					}else{
						$error_msg = $this->config->item('project_cancelled_admin_deleted_not_available_for_copy_into_new_dashboard_myprojects_po_view');
					}
					//$msg['error_message'] = $error_msg;
				}
				if($project_status == 'expired'){
					if($project_type == 'fulltime'){
						$error_msg = $this->config->item('fulltime_project_expired_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view');
					}else{
						$error_msg = $this->config->item('project_expired_admin_deleted_not_available_for_repost_dashboard_myprojects_po_view');
					}
					//$msg['error_message'] = $error_msg;
				}
			}
			
			/* if(!empty($project_status_table_array['project_status']) && $project_status == 'open' &&  $project_status_table_array['project_status'] != 'open_for_bidding' ){
			
				if($project_type == 'fulltime'){
					$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_copy_into_new_dashboard_myprojects_po_view');
				}else{
					$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_copy_into_new_dashboard_myprojects_po_view');
				}
				//$msg['error_message'] = $error_msg;
			} */
			/* if(!empty($project_status_table_array['project_status']) && $project_status == 'expired' &&  ($project_status_table_array['project_status'] != 'expired' && $project_status_table_array['project_status'] != 'open_for_bidding') ){
			
				if($project_type == 'fulltime'){
					$error_msg = $this->config->item('fulltime_project_expired_status_changed_not_available_for_repost_dashboard_myprojects_po_view');
				}else{
					$error_msg = $this->config->item('project_expired_status_changed_not_available_for_repost_dashboard_myprojects_po_view');
				}
			} */
			if($error_msg == '' && !empty($project_status_table_array['table_name']) && $project_status_table_array['project_status']){
				/* if($project_type != 'fulltime'){
					$this->db->select('project_title,project_description,locality_id,county_id,postal_code_id,project_type,min_budget,max_budget,confidential_dropdown_option_selected,not_sure_dropdown_option_selected,escrow_payment_method,offline_payment_method');
					$project_detail = $this->db->get_where($project_status_table_array['table_name'], ['project_id' => $project_id])->row_array();
				}else{
				
					$this->db->select('fulltime_project_title as project_title,fulltime_project_description as project_description,locality_id,county_id,postal_code_id,project_type,min_salary as min_budget,max_salary as max_budget,confidential_dropdown_option_selected,not_sure_dropdown_option_selected,escrow_payment_method,offline_payment_method');
					$project_detail = $this->db->get_where($project_status_table_array['table_name'], ['fulltime_project_id' => $project_id])->row_array();
					
				} */
				
				if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
				
				
					$this->db->select('fulltime_project_title as project_title,fulltime_project_description as project_description,locality_id,county_id,postal_code_id,project_type,min_salary as min_budget,max_salary as max_budget,confidential_dropdown_option_selected,not_sure_dropdown_option_selected,escrow_payment_method,offline_payment_method');
					$project_detail = $this->db->get_where($project_status_table_array['table_name'], ['fulltime_project_id' => $project_id])->row_array();
				
				}else{
					$this->db->select('project_title,project_description,locality_id,county_id,postal_code_id,project_type,min_budget,max_budget,confidential_dropdown_option_selected,not_sure_dropdown_option_selected,escrow_payment_method,offline_payment_method');
					$project_detail = $this->db->get_where($project_status_table_array['table_name'], ['project_id' => $project_id])->row_array();
				}	
				
				
				
				
				
				
				
				$temp_project_id = 0;
					if(!empty($project_detail)){
						$temp_project_id = generate_unique_project_id(); // genreate the unique project id	
						
						$time_arr = explode(':', $this->config->item('temp_project_expiration_time'));
						
						$temp_project_data = array (
							'temp_project_id'=>$temp_project_id,
							'temp_project_owner_id'=>$user[0]->user_id,
							'project_submission_date'=>date('Y-m-d H:i:s'),
							'project_title'=>$project_detail['project_title'],
							'project_description'=>$project_detail['project_description'],
							'locality_id'=>$project_detail['locality_id'],
							'county_id'=>$project_detail['county_id'],
							'postal_code_id'=>$project_detail['postal_code_id'],
							'project_type'=>$project_detail['project_type'],
							'min_budget'=>$project_detail['min_budget'],
							'max_budget'=>$project_detail['max_budget'],
							'confidential_dropdown_option_selected'=>$project_detail['confidential_dropdown_option_selected'],
							'not_sure_dropdown_option_selected'=>$project_detail['not_sure_dropdown_option_selected'],
							'escrow_payment_method'=>$project_detail['escrow_payment_method'],
							'offline_payment_method'=>$project_detail['offline_payment_method'],
							'project_expiration_date'=>date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds')),
							'project_owner_last_activity_date'=>date('Y-m-d H:i:s')
						);
						/* echo "<pre>";
						print_r($temp_project_data); */
						if ($this->db->insert ('temp_projects', $temp_project_data)){
						
							// fetch the category start //
							$this->db->select('project_category_id,project_parent_category_id');
							$this->db->from('projects_categories_listing_tracking');
							$this->db->where('project_id',$project_id);
							$this->db->order_by('id',"asc");
							$category_result = $this->db->get();
							$category_data = $category_result->result_array();
							if(!empty($category_data)){
								foreach($category_data as $category_key => $category_value){
									$this->db->insert ('temp_projects_categories_listing_tracking', array(
										'temp_project_id' => $temp_project_id,
										'temp_project_category_id' =>  $category_value['project_category_id'],
										'temp_project_parent_category_id' =>  $category_value['project_parent_category_id']
										)
									);
								}
							}
							// fetch the tag start //
							$this->db->select('project_tag_name');
							$this->db->from('projects_tags');
							$this->db->where('project_id',$project_id);
							$this->db->order_by('id',"asc");
							$project_tag_result = $this->db->get();
							$project_tag_data = $project_tag_result->result_array();
							if(!empty($project_tag_data)){
								foreach($project_tag_data as $tag_key => $tag_value){
									$this->db->insert ('temp_projects_tags', array(
										'temp_project_id' => $temp_project_id,
										'temp_project_tag_name' =>  $tag_value['project_tag_name']
										)
									);
								}
							}
						}
					}
				}
				
				if($error_msg != '' && $page_type == "my_projects"){
					if($this->input->post ('active_page')){
					$page = $this->input->post ('active_page');
					}else{
						$page = 1;
					}	
					$new_counter = $page;
					
					if($page != null) {
				
						if($page == 1) {
							$start = 0;
							} else {
							if($project_status == 'open'){
								$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
							}
							if($project_status == 'cancelled'){
								$start = ($page - 1) * $this->config->item('my_projects_po_view_cancelled_projects_listing_limit');
							}
							if($project_status == 'expired'){
								$start = ($page - 1) * $this->config->item('my_projects_po_view_expired_projects_listing_limit');
							}
								
						}
					} else {
						$start = 0;
					}
					if($project_status == 'open' && $page_type == "my_projects"){
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
						$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
						$page = $new_counter;
						if(empty($data["open_bidding_project_data"])){
							$page = $new_counter-1;
							if($page != null) {
							
								if($page == 1) {
									$start = 0;
								} else {
									
								$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
								
									
								}
							} else {
								$start = 0;
								}
						}
						
						$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
					}
					if($project_status == 'cancelled' && $page_type == "my_projects"){
						$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));
						$data["cancelled_project_data"] = $cancelled_project_data['data'];
						$page = $new_counter;
						if(empty($data["cancelled_project_data"])){
							$page = $new_counter-1;
							if($page != null) {
							
								if($page == 1) {
									$start = 0;
								} else {
									
								$start = ($page - 1) * $this->config->item('my_projects_po_view_cancelled_projects_listing_limit');
								
									
								}
							} else {
								$start = 0;
								}
						}
						
						$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));
					}
					if($project_status == 'expired'  && $page_type == "my_projects"){
						$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
						$data["expired_project_data"] = $expired_project_data['data'];
						$page = $new_counter;
						if(empty($data["expired_project_data"])){
							$page = $new_counter-1;
							if($page != null) {
							
								if($page == 1) {
									$start = 0;
								} else {
									
								$start = ($page - 1) * $this->config->item('my_projects_po_view_expired_projects_listing_limit');
								
									
								}
							} else {
								$start = 0;
								}
						}
						
						$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
					}
				
				}
			
				if($error_msg != ''){
					$data["page_type"] = $page_type;
					if($page_type == "dashboard"){
						if($project_status == 'open'){
							$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
							$fulltime_open_bidding_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
							$fulltime_open_project_count = $myproj_fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						}
						if($project_status == 'cancelled'){
							$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_cancelled_projects_listing_limit'));
						}
						if($project_status == 'expired'){
							$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
						}
					
					}
					if($project_status == 'open'){
						$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
						$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
					}
					if($project_status == 'expired'){
						$data["expired_project_data"] = $expired_project_data['data'];
						$data['expired_project_count'] = $expired_project_data['total'];
					}
					if($project_status == 'cancelled'){
						$data["cancelled_project_data"] = $cancelled_project_data['data'];
						$data['cancelled_project_count'] = $cancelled_project_data['total'];
					}
					if($page_type == "my_projects"){
						if($project_status == 'open'){
							$fulltime_open_bidding_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
							$fulltime_open_project_count = $myproj_fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
							$paginations = generate_pagination_links($open_bidding_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'open_for_bidding']);
							$data['open_bidding_pagination_links'] = $paginations['links'];
							
							$multiplication = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * $page;
							$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') - count($data['open_bidding_project_data'])));
							$record_per_page = count($data['open_bidding_project_data']) < $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') ? $subtraction : $multiplication;
							$page_no = ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * ($page - 1)) + 1;
							echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.($open_bidding_project_data['total'] - $fulltime_open_project_count).'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects-$fulltime_open_bidding_cnt).')','status' => 200,'error_msg'=>$error_msg,'location'=>'','record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$open_bidding_project_data['total'],'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
							die;
						}
						if($project_status == 'cancelled'){
							$paginations = generate_pagination_links($cancelled_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'cancelled']);
							$data['cancelled_pagination_links'] = $paginations['links'];
							
							$multiplication = $this->config->item('my_projects_po_view_cancelled_projects_listing_limit') * $page;
							$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_cancelled_projects_listing_limit') - count($data['cancelled_project_data'])));
							$record_per_page = count($data['cancelled_project_data']) < $this->config->item('my_projects_po_view_cancelled_projects_listing_limit') ? $subtraction : $multiplication;
							$page_no = ($this->config->item('my_projects_po_view_cancelled_projects_listing_limit') * ($page - 1)) + 1;
							$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
            	$fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
							echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')','status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$cancelled_project_data['total'],'data'=>$this->load->view('projects/po_cancelled_projects_listing_my_projects',$data, true)]);
							die;
						}
						if($project_status == 'expired'){
							
							$paginations = generate_pagination_links($expired_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'expired']);
							$data['expired_pagination_links'] = $paginations['links'];
							
							$multiplication = $this->config->item('my_projects_po_view_expired_projects_listing_limit') * $page;
							$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_expired_projects_listing_limit') - count($data['expired_project_data'])));
							$record_per_page = count($data['expired_project_data']) < $this->config->item('my_projects_po_view_expired_projects_listing_limit') ? $subtraction : $multiplication;
							$page_no = ($this->config->item('my_projects_po_view_expired_projects_listing_limit') * ($page - 1)) + 1;

							$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
            	$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
							
							echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$expired_project_data['total'],'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
							die;
						}
					}
					if($page_type=="dashboard"){
						if($project_status == 'open'){
							echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.($open_bidding_project_data['total'] - $fulltime_open_project_count).'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects-$fulltime_open_bidding_cnt).')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
							die;
						}
						if($project_status == 'cancelled'){
							$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
            	$fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
							echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_cancelled_projects_listing_my_projects',$data, true)]);
							die;
						}
						if($project_status == 'expired'){
							$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
            	$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
							echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
							die;
						}
					}
				}else{
					$this->session->set_userdata ('check_redirection', 1);// set the redirection for edit preview page
					$this->session->set_userdata ('repost', 1);// set value in session that action is repost
					$msg['status'] = 200;
					$msg['error_msg'] = $error_msg;
					$msg['location'] = VPATH.$this->config->item('post_project_page_url').'?id='.$temp_project_id;
					echo json_encode($msg);
					die;
				
				}
		}else{
			
			$msg['status'] = 400;
			$msg['location'] = VPATH;
			
		}
		echo json_encode($msg);
		die;
		
	
	}
	
	// This method is used to redirect login user to project detail page 
	public function redirect_login_user_project_detail_page(){
	
		if(!check_session_validity()){ 
			redirect (VPATH);
		}
		$project_id = $this->input->get('id');
		$this->session->set_userdata ('check_status_for_apply_now', 1);
		$url = VPATH . $this->config->item('project_detail_page_url').'?id='.$project_id;
		redirect ($url);
		
	}
	
	// This method is used to check project exist as open for bidding and cancelled
	public function check_project_exist_open_for_bidding_cancelled(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}if(empty($this->input->post ('project_id'))){
			show_custom_404_page(); //show custom 404 page
			return;
		}	
		
		if(check_session_validity()){ 
			$user = $this->session->userdata ('user');
			$project_id = $this->input->post ('project_id');
			$action_type = $this->input->post ('action_type');
			$project_type = $this->input->post ('project_type');
			$page_type = $this->input->post ('page_type');
			$project_status = $this->input->post ('project_status');
			$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
			if($user[0]->user_id != $po_id){
				$msg['status'] = 400;
				$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
				echo json_encode($msg);
				die;
			
			}
			$error_msg = '';
			if($action_type == 'project_edit'){
				$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
				$error_msg = '';
				
				
				
				
				
				if(empty($project_status_table_array['table_name'])){
					if($project_type == 'fulltime'){
						$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view');
					}else{
						$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_edit_dashboard_myprojects_po_view');
					}
					$msg['error_message'] = $error_msg;
				}
				
				if(!empty($project_status_table_array['table_name'])){
					if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime') {
					$project_data = $this->db // get the user detail
					->select('pd.employer_id as project_owner_id,pd.project_type,pd.fulltime_project_expiration_date as project_expiration_date')
					->from($project_status_table_array['table_name'].' pd')
					->where('pd.fulltime_project_id', $project_id)
					->get()->row_array();
					}else{
						$project_data = $this->db // get the user detail
						->select('pd.project_owner_id,pd.project_type,pd.project_expiration_date')
						->from($project_status_table_array['table_name'].' pd')
						->where('pd.project_id', $project_id)
						->get()->row_array();
					}
				}
				
				if(!empty($project_status_table_array['table_name']) && ($project_status_table_array['project_status'] != 'open_for_bidding') || ($project_status_table_array['project_status'] == 'open_for_bidding' && strtotime($project_data['project_expiration_date']) < time()) ){
					
					if($project_type == 'fulltime'){
						$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view');
					}else{
						$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_edit_dashboard_myprojects_po_view');
					}
					$msg['error_message'] = $error_msg;
				}
			}
			$this->db->select('current_membership_plan_id');
			$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();

			
			if($user_detail['current_membership_plan_id'] == 1){
				$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
				$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
			}
			if($user_detail['current_membership_plan_id'] == 4){
				$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
				$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
			}
			if($action_type == 'project_cancel'){
				
				$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
				
				if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
						if($project_status == 'open'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
							}
						}
						if($project_status == 'expired'){
							if($project_type == 'fulltime'){
								$error_msg = $this->config->item('fulltime_project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
							}else{
								$error_msg = $this->config->item('project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
							}
						}
						$msg['error_message'] = $error_msg;
					}else{
						if($project_status == 'open'){
							
							if($project_type != 'fulltime' && in_array($project_status_table_array['project_status'],array('awarded','in_progress','incomplete','completed','cancelled'))){
								$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
							}
							if($project_type == 'fulltime' && $project_status_table_array['project_status'] == 'cancelled')	{
								$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
								
							}

							if($project_type == 'fulltime' &&  ($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired')){
								$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_awarded_tracking')->count_all_results();
								if($check_awarded_bid > 0){
									$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view');
								
								}	
								
							}		
								
							$msg['error_message'] = $error_msg;
						}
						if($project_status == 'expired'){
							
							if($project_type != 'fulltime' && in_array($project_status_table_array['project_status'],array('awarded','in_progress','incomplete','completed','cancelled'))){
								$error_msg = $this->config->item('project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
							}
							if($project_type == 'fulltime' && $project_status_table_array['project_status'] == 'cancelled')	{
								$error_msg = $this->config->item('fulltime_project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
								
							}
							
							if($project_type == 'fulltime' &&  ($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired')){
								$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_awarded_tracking')->count_all_results();
								if($check_awarded_bid > 0){
									$error_msg = $this->config->item('fulltime_project_expired_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view');
								
								}	
								
							}	
							$msg['error_message'] = $error_msg;
						}
					
					}
				}
				if($error_msg != '' && $project_status == 'open' && $page_type == "my_projects"){
				
					
					if($this->input->post ('active_page')){
					$page = $this->input->post ('active_page');
					}else{
						$page = 1;
					}	
					$new_counter = $page;
					
					if($page != null) {
				
						if($page == 1) {
							$start = 0;
							} else {
							
						$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
								
						}
					} else {
						$start = 0;
					}
					
					$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
					$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
					$page = $new_counter;
					if(empty($data["open_bidding_project_data"])){
						$page = $new_counter-1;
						if($page != null) {
						
							if($page == 1) {
								$start = 0;
							} else {
								
							$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
							
								
							}
						} else {
							$start = 0;
							}
					}
					
					$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
				
				}
				if($error_msg != '' &&  $project_status == 'expired' && $page_type == "my_projects"){
				
					
					if($this->input->post ('active_page')){
					$page = $this->input->post ('active_page');
					}else{
						$page = 1;
					}	
					$new_counter = $page;
					
					if($page != null) {
				
						if($page == 1) {
							$start = 0;
							} else {
							
						$start = ($page - 1) * $this->config->item('my_projects_po_view_expired_projects_listing_limit');
								
						}
					} else {
						$start = 0;
					}
					
					$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
					$data["expired_project_data"] = $expired_project_data['data'];
					$page = $new_counter;
					if(empty($data["expired_project_data"])){
						$page = $new_counter-1;
						if($page != null) {
						
							if($page == 1) {
								$start = 0;
							} else {
								
							$start = ($page - 1) * $this->config->item('my_projects_po_view_expired_projects_listing_limit');
							
								
							}
						} else {
							$start = 0;
							}
					}
					
					$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,$start, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
				
				}
				if($error_msg != '' &&  $project_status == 'open' && $page_type == "dashboard"){
					$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
				}
				if($error_msg != '' &&  $project_status == 'expired' && $page_type == "dashboard"){
					$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
				}
				$data["page_type"] = $page_type;
				
				if($error_msg != '' &&  $page_type == "my_projects"){
					if($project_status == 'open'){
						
						$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
						$data['open_bidding_project_count'] = $open_bidding_project_data['total'];

						$open_project_cnt = $open_bidding_project_data['total'];
						$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
						$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						$open_project_cnt -= $fulltime_open_project_count;
						$paginations = generate_pagination_links($open_bidding_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'open_for_bidding']);
						$data['open_bidding_pagination_links'] = $paginations['links'];
						
						$multiplication = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') - count($data['open_bidding_project_data'])));
						$record_per_page = count($data['open_bidding_project_data']) < $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * ($page - 1)) + 1;
						echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','status' => 200,'error_msg'=>$error_msg,'location'=>VPATH . $this->config->item('edit_project_page_url').'?id='.$project_id,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$open_bidding_project_data['total'],'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
						die;
					}
					if($error_msg != '' &&  $project_status == 'expired'){
						$data["expired_project_data"] = $expired_project_data['data'];
						$data['expired_project_count'] = $expired_project_data['total'];
						
						$paginations = generate_pagination_links($expired_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_expired_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'expired']);
						$data['expired_pagination_links'] = $paginations['links'];
						
						$multiplication = $this->config->item('my_projects_po_view_expired_projects_listing_limit') * $page;
						$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_expired_projects_listing_limit') - count($data['expired_project_data'])));
						$record_per_page = count($data['expired_project_data']) < $this->config->item('my_projects_po_view_expired_projects_listing_limit') ? $subtraction : $multiplication;
						$page_no = ($this->config->item('my_projects_po_view_expired_projects_listing_limit') * ($page - 1)) + 1;

						$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
            $fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
						
						echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$expired_project_data['total'],'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
						die;
					}	
				}
				if($error_msg != '' &&  $project_status == 'open' && $page_type=="dashboard" ){
					
					$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
					$data['open_bidding_project_count'] = $open_bidding_project_data['total'];

					$open_project_cnt = $open_bidding_project_data['total'];
					$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
					$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
					$open_project_cnt -= $fulltime_open_project_count;
					
					echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($error_msg != '' &&  $project_status == 'expired' && $page_type=="dashboard" ){
					$data["expired_project_data"] = $expired_project_data['data'];
					$data['expired_project_count'] = $expired_project_data['total'];
					$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
					$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
					echo json_encode(['project_status'=>$project_status,'tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','location'=>'','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($action_type = 'project_edit' && $error_msg == '' ){
					echo json_encode(['location'=>VPATH . $this->config->item('edit_project_page_url').'?id='.$project_id,'status' => 200,'error_msg'=>'']);
					die;
				}
				
	
		}else{
			
			$msg['status'] = 400;
			$msg['location'] = VPATH;
			echo json_encode($msg);
			die;
			
		}
		echo json_encode($msg);
		die;
	
	}
	
	/* This function is used to show the dedicated my project page */
	public function my_projects_listing() { 
		if (check_session_validity()) {
			$this->session->unset_userdata('edit_page');
			$user = $this->session->userdata('user');
			$data['current_page'] = 'my_projects';
			
			$name = (($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) ? $user[0]->first_name . ' ' . $user[0]->last_name : $user[0]->company_name;
			
			
			$this->db->select('current_membership_plan_id');
			$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
			$data['user_detail'] = $user_detail;
			########## set the profile title meta tag and meta description  start here #########
			$title_meta_tag = $this->config->item('myprojects_page_title_meta_tag');
			$description_meta_tag = $this->config->item('myprojects_page_description_meta_tag');
			
			$title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
			$description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
			
			

			$data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
			########## set the profile title tag start end #########
			$standard_time_arr = explode(":", $this->config->item('standard_project_availability'));
			$standard_check_valid_arr = array_map('getInt', $standard_time_arr); 
			$standard_valid_time_arr = array_filter($standard_check_valid_arr);
			$data['standard_valid_time_arr'] = $standard_valid_time_arr;
			$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
			$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
			########## fetch open bidding project information of logged in user ##########################
			$data['open_bidding_cnt'] = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
			$data['fulltime_open_bidding_cnt'] = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
			$data['fulltime_open_project_count'] = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
			
			
			############## fetch the draft projects from database and show on myproject ############
			$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_draft_projects_listing_limit'));
			$data["draft_project_data"] = $draft_project_data['data'];
			$data["draft_project_count"] = $draft_project_data['total'];
			$paginations = generate_pagination_links($draft_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_draft_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'draft']);
			$data['draft_pagination_links'] = $paginations['links'];
			
			######## fetch the awaiting moderation projects from database and show on myproject 		
			$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit'));
			 $data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];
			 $data['fulltime_awaiting_moderation_project_count'] = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime'])->from('projects_awaiting_moderation')->count_all_results();
			 ######## fetch the open for bidding projects from database and show on myproject 		
			$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
			 $data['open_bidding_project_count'] = $open_bidding_project_data['total'];
			 ######## fetch the awarded projects from database and show on myproject 		
			$awarded_project_data = $this->Projects_model->get_po_awarded_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_awarded_projects_listing_limit'));
			 $data['awarded_project_count'] = $awarded_project_data['total'];
			
			 ######## fetch the in progress projects from database and show on myproject 		
			$in_progress_project_data = $this->Projects_model->get_po_in_progress_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_in_progress_projects_listing_limit'));
			 $data['in_progress_project_count'] = $in_progress_project_data['total'];
			 
			 ######## fetch the completed projects from database and show on myproject 		
			$completed_project_data = $this->Projects_model->get_po_completed_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_completed_projects_listing_limit'));
			 $data['completed_project_count'] = $completed_project_data['total'];
			 
			 ######## fetch the in complete projects from database and show on myproject 		
			$in_complete_project_data = $this->Projects_model->get_po_incomplete_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_incomplete_projects_listing_limit'));
			 $data['in_complete_project_count'] = $in_complete_project_data['total'];
			 
			######## fetch the expired projects from database and show on myproject 		
			$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
			$data['expired_project_count'] = $expired_project_data['total'];
			$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
			$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
			$data['fulltime_expired_project_count'] = $fulltime_expired_project_cnt;
			######## fetch the cancelled projects from database and show on myproject 		
			$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));
			$data['cancelled_project_count'] = $cancelled_project_data['total'];
			$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
			$fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
			$data['fulltime_cancelled_project_count'] = $fulltime_cancelled_project_count;
			 
		 ######## fetch the active bids from database and show on myproject 		
			$active_bids_project_data = $this->Bidding_model->get_sp_active_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_active_bids_listing_limit'));
			$data['active_bids_project_count'] = $active_bids_project_data['total'];
			$data['fulltime_active_bids_project_count'] = $this->db->where(['employee_id' => $user[0]->user_id])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
			$data['fulltime_active_bids_project_count'] += $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date <=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();

			######## fetch the awarded bids from database and show on myproject 		
			$awarded_bids_project_data = $this->Bidding_model->get_sp_awarded_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_awarded_bids_listing_limit'));
			$data['awarded_bids_project_count'] = $awarded_bids_project_data['total'];
			$data['fulltime_awarded_bids_project_count'] = $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date >=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
			
			######## fetch the in progress bids from database and show on myproject 		
			$in_progress_bids_project_data = $this->Bidding_model->get_sp_in_progress_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit'));
			$data['in_progress_bids_project_count'] = $in_progress_bids_project_data['total'];
			
			######## fetch the in complete bids from database and show on myproject 		
			$in_complete_bids_project_data = $this->Bidding_model->get_sp_incomplete_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit'));
			$data['in_complete_bids_project_count'] = $in_complete_bids_project_data['total'];
			
			
			######## fetch the hired projects from database and show on myproject 		
			$hired_project_data = $this->Bidding_model->get_employee_hired_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit'));
			$data['hired_project_count'] = $hired_project_data['total'];
			
			######## fetch the completed bids from database and show on myproject 		
			$completed_bids_project_data = $this->Bidding_model->get_sp_completed_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_completed_bids_listing_limit'));
			$data['completed_bids_project_count'] = $completed_bids_project_data['total'];
			
			##################### end ##############
			
		   $this->layout->view ('my_projects', '', $data, 'include');
	   } else {
			redirect(site_url());
	   }

    }
	
	
	public function load_pagination_my_projects(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ 
				$page = $this->uri->segment(3);
				$tab_type=$this->input->post('tab_type');
				$user_id = $this->input->post('user_id');
				$user = $this->session->userdata('user');
				$num_pagination_links = $this->config->item('my_projects_number_of_pagination_links');
				if($user_id != $user[0]->user_id) {
					echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
					die;
				}
				if($page != null) {
					
					if($page == 1) {
						$start = 0;
					} else {
						if($tab_type == 'draft') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_draft_projects_listing_limit');
						}
						if($tab_type == 'awaiting_moderation') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit');
						}
						if($tab_type == 'open_for_bidding') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit');
						}
						if($tab_type == 'awarded') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_awarded_projects_listing_limit');
						}
						if($tab_type == 'in_progress') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_in_progress_projects_listing_limit');
						}
						if($tab_type == 'in_complete') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_incomplete_projects_listing_limit');
						}
						if($tab_type == 'completed') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_completed_projects_listing_limit');
						}
						if($tab_type == 'expired') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_expired_projects_listing_limit');
						}
						if($tab_type == 'cancelled') {	
							$start = ($page - 1) * $this->config->item('my_projects_po_view_cancelled_projects_listing_limit');
						}
						if($tab_type == 'active_bids') {	
							$start = ($page - 1) * $this->config->item('my_projects_sp_view_active_bids_listing_limit');
						}
						if($tab_type == 'awarded_bids') {	
							$start = ($page - 1) * $this->config->item('my_projects_sp_view_awarded_bids_listing_limit');
						}
						if($tab_type == 'in_progress_bids') {	
							$start = ($page - 1) * $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit');	
						}
						if($tab_type == 'in_complete_bids') {	
							$start = ($page - 1) * $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit');	
						}
						if($tab_type == 'hired') {	
							$start = ($page - 1) * $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit');	
						}
						if($tab_type == 'completed_bids'){	
							$start = ($page - 1) * $this->config->item('my_projects_sp_view_completed_bids_listing_limit');
						}
						
					}
				} else {
					$start = 0;
				}
				$data["page_type"] = "my_projects";
				if($tab_type == 'draft'){	

					$draft_project_count = $this->db->from('projects_draft')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();
					$paginations = generate_pagination_links($draft_project_count, $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_draft_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_draft_projects_listing_limit'));
					
					$data["draft_project_data"] = $draft_project_data['data'];
					$data["draft_project_count"] = $draft_project_data['total'];
					$data['draft_pagination_links'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					$multiplication = $this->config->item('my_projects_po_view_draft_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_draft_projects_listing_limit') - count($data['draft_project_data'])));
					$record_per_page = count($data['draft_project_data']) < $this->config->item('my_projects_po_view_draft_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_draft_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$draft_project_data['total'],'data'=>$this->load->view('projects/po_draft_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'awaiting_moderation'){	
					$awaiting_project_count = $this->db->from('projects_awaiting_moderation')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();
					$paginations = generate_pagination_links($awaiting_project_count, $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit'));
					$data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
					$data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];
					$data['awaiting_moderation_pagination_links'] = $paginations['links'];
					
					$page = $paginations['current_page_no'];
					$multiplication = $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit') - count($data['awaiting_moderation_project_data'])));
					$record_per_page = count($data['awaiting_moderation_project_data']) < $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$awaiting_moderation_project_data['total'],'data'=>$this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'open_for_bidding'){
					$open_bidding_project_count = $this->db->from('projects_open_bidding')->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >=' => date('Y-m-d H:i:s')])->count_all_results();
					$paginations = generate_pagination_links($open_bidding_project_count, $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
					$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
					$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
					$data['open_bidding_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') - count($data['open_bidding_project_data'])));
					$record_per_page = count($data['open_bidding_project_data']) < $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * ($page - 1)) + 1;
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$open_bidding_project_data['total'],'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'awarded'){
					$awarded_project_count = $this->db->from('fixed_budget_projects_awarded')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();
					$awarded_project_count += $this->db->from('hourly_rate_based_projects_awarded')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();

					$paginations = generate_pagination_links($awarded_project_count, $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_awarded_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$awarded_project_data = $this->Projects_model->get_po_awarded_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_awarded_projects_listing_limit'));
					$data["awarded_project_data"] = $awarded_project_data['data'];
					$data['awarded_project_count'] = $awarded_project_data['total'];
					$data['awarded_pagination_links'] = $paginations['links'];
					
					$page = $paginations['current_page_no'];

					$multiplication = $this->config->item('my_projects_po_view_awarded_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_awarded_projects_listing_limit') - count($data['awarded_project_data'])));
					$record_per_page = count($data['awarded_project_data']) < $this->config->item('my_projects_po_view_awarded_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_awarded_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$awarded_project_data['total'],'data'=>$this->load->view('projects/po_awarded_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'in_progress'){

					$in_progress_project_count = $this->db->from('fixed_budget_projects_progress')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();
					$in_progress_project_count += $this->db->from('hourly_rate_based_projects_progress')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();

					$paginations = generate_pagination_links($in_progress_project_count, $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_in_progress_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$in_progress_project_data = $this->Projects_model->get_po_in_progress_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_in_progress_projects_listing_limit'));
					$data["in_progress_project_data"] = $in_progress_project_data['data'];
					$data['in_progress_project_count'] = $in_progress_project_data['total'];
					$data['in_progress_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_po_view_in_progress_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_in_progress_projects_listing_limit') - count($data['in_progress_project_data'])));
					$record_per_page = count($data['in_progress_project_data']) < $this->config->item('my_projects_po_view_in_progress_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_in_progress_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$in_progress_project_data['total'],'data'=>$this->load->view('projects/po_in_progress_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'in_complete'){

					$in_complete_project_count = $this->db->from('fixed_budget_projects_incomplete')->where(['project_owner_id' => $user[0]->user_id])->count_all_results();
					
					$paginations = generate_pagination_links($in_complete_project_count, $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_incomplete_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$in_complete_project_data = $this->Projects_model->get_po_incomplete_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_incomplete_projects_listing_limit'));
					$data["in_complete_project_data"] = $in_complete_project_data['data'];
					$data['in_complete_project_count'] = $in_complete_project_data['total'];
					$data['in_complete_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_po_view_incomplete_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_incomplete_projects_listing_limit') - count($data['in_complete_project_data'])));
					$record_per_page = count($data['in_complete_project_data']) < $this->config->item('my_projects_po_view_incomplete_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_incomplete_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$in_complete_project_data['total'],'data'=>$this->load->view('projects/po_incomplete_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'completed'){
					$completed_project_data = $this->Projects_model->get_po_completed_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_completed_projects_listing_limit'));
					$paginations = generate_pagination_links($completed_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_completed_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$completed_project_data = $this->Projects_model->get_po_completed_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_completed_projects_listing_limit'));
					$data["completed_project_data"] = $completed_project_data['data'];
					$data['completed_project_count'] = $completed_project_data['total'];
					$data['completed_pagination_links'] = $paginations['links'];
					
						$page = $paginations['current_page_no'];

					$multiplication = $this->config->item('my_projects_po_view_completed_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_completed_projects_listing_limit') - count($data['completed_project_data'])));
					$record_per_page = count($data['completed_project_data']) < $this->config->item('my_projects_po_view_completed_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_completed_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$completed_project_data['total'],'data'=>$this->load->view('projects/po_completed_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'expired'){

					$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id, 0, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));

					$paginations = generate_pagination_links($expired_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_expired_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
					$data["expired_project_data"] = $expired_project_data['data'];
					$data['expired_project_count'] = $expired_project_data['total'];
					$data['expired_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_po_view_expired_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_expired_projects_listing_limit') - count($data['expired_project_data'])));
					$record_per_page = count($data['expired_project_data']) < $this->config->item('my_projects_po_view_expired_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_expired_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$expired_project_data['total'],'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'cancelled'){

					$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));

					$paginations = generate_pagination_links($cancelled_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_cancelled_projects_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));
					$data["cancelled_project_data"] = $cancelled_project_data['data'];
					$data['cancelled_project_count'] = $cancelled_project_data['total'];
					$data['cancelled_pagination_links'] = $paginations['links'];
					$page = $paginations['current_page_no'];
					$multiplication = $this->config->item('my_projects_po_view_cancelled_projects_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_cancelled_projects_listing_limit') - count($data['cancelled_project_data'])));
					$record_per_page = count($data['cancelled_project_data']) < $this->config->item('my_projects_po_view_cancelled_projects_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_po_view_cancelled_projects_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$cancelled_project_data['total'],'data'=>$this->load->view('projects/po_cancelled_projects_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'active_bids'){	

					$active_bids_project_data = $this->Bidding_model->get_sp_active_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_active_bids_listing_limit'));

					$paginations = generate_pagination_links($active_bids_project_data['total'], 'bidding/load_sp_pagination_my_projects',$this->config->item('my_projects_sp_view_active_bids_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					
					$active_bids_project_data = $this->Bidding_model->get_sp_active_bids_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_sp_view_active_bids_listing_limit'));
					$data["active_bids_project_data"] = $active_bids_project_data['data'];
					$data['active_bids_project_count'] = $active_bids_project_data['total'];
					$data['page_type'] = 'my_projects';
					
					$data['active_bids_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_sp_view_active_bids_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_sp_view_active_bids_listing_limit') - count($data['active_bids_project_data'])));
					$record_per_page = count($data['active_bids_project_data']) < $this->config->item('my_projects_sp_view_active_bids_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_sp_view_active_bids_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$active_bids_project_data['total'],'data'=>$this->load->view('bidding/sp_active_bids_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'awarded_bids'){	
				
					$awarded_bids_project_data = $this->Bidding_model->get_sp_awarded_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_awarded_bids_listing_limit'));

					$paginations = generate_pagination_links($awarded_bids_project_data['total'], 'bidding/load_sp_pagination_my_projects',$this->config->item('my_projects_sp_view_awarded_bids_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					
					$awarded_bids_project_data = $this->Bidding_model->get_sp_awarded_bids_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_sp_view_awarded_bids_listing_limit'));
					$data["awarded_bids_project_data"] = $awarded_bids_project_data['data'];
					$data['awarded_bids_project_count'] = $awarded_bids_project_data['total'];
					$data['page_type'] = 'my_projects';
					
					$data['awarded_bids_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_sp_view_awarded_bids_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_sp_view_awarded_bids_listing_limit') - count($data['awarded_bids_project_data'])));
					$record_per_page = count($data['awarded_bids_project_data']) < $this->config->item('my_projects_sp_view_awarded_bids_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_sp_view_awarded_bids_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$awarded_bids_project_data['total'],'data'=>$this->load->view('bidding/sp_awarded_bids_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'in_progress_bids'){	
				
					$in_progress_bids_project_data = $this->Bidding_model->get_sp_in_progress_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit'));

					$paginations = generate_pagination_links($in_progress_bids_project_data['total'], 'bidding/load_sp_pagination_my_projects',$this->config->item('my_projects_sp_view_in_progress_bids_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					
					$in_progress_bids_project_data = $this->Bidding_model->get_sp_in_progress_bids_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit'));
					
					$var_in_progress_bids_project_data = $in_progress_bids_project_data['data'];
					$data['in_progress_bids_project_count'] = $in_progress_bids_project_data['total'];
					$data['page_type'] = 'my_projects';
					foreach($var_in_progress_bids_project_data as &$value) {
						$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user[0]->user_id]);
					}
					$data["in_progress_bids_project_data"] = $var_in_progress_bids_project_data;
					$data['in_progress_bids_pagination_links'] = $paginations['links'];
					
					$page = $paginations['current_page_no'];

					$multiplication = $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_sp_view_in_progress_bids_listing_limit') - count($data['in_progress_bids_project_data'])));
					$record_per_page = count($data['in_progress_bids_project_data']) < $this->config->item('my_projects_sp_view_in_progress_bids_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_sp_view_in_progress_bids_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$in_progress_bids_project_data['total'],'data'=>$this->load->view('bidding/sp_in_progress_bids_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'in_complete_bids'){	
				
					$in_complete_bids_project_data = $this->Bidding_model->get_sp_incomplete_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit'));

					$paginations = generate_pagination_links($in_complete_bids_project_data['total'], 'bidding/load_sp_pagination_my_projects',$this->config->item('my_projects_sp_view_incomplete_bids_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					
					$in_complete_bids_project_data = $this->Bidding_model->get_sp_in_complete_bids_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit'));
					
					$var_in_progress_bids_project_data = $in_complete_bids_project_data['data'];
					$data['in_complete_bids_project_count'] = $in_complete_bids_project_data['total'];
					$data['page_type'] = 'my_projects';
					foreach($var_in_progress_bids_project_data as &$value) {
						$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['project_id' => $value['project_id'], 'project_owner_id' => $value['project_owner_id'], 'winner_id' => $user[0]->user_id]);
					}
					$data["in_complete_bids_project_data"] = $var_in_progress_bids_project_data;
					$data['in_complete_bids_pagination_links'] = $paginations['links'];
					
					$page = $paginations['current_page_no'];

					$multiplication = $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_sp_view_incomplete_bids_listing_limit') - count($data['in_complete_bids_project_data'])));
					$record_per_page = count($data['in_complete_bids_project_data']) < $this->config->item('my_projects_sp_view_incomplete_bids_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_sp_view_incomplete_bids_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$in_complete_bids_project_data['total'],'data'=>$this->load->view('bidding/sp_incomplete_bids_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'hired') {	

					$hired_project_data = $this->Bidding_model->get_employee_hired_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit'));

					$paginations = generate_pagination_links($hired_project_data['total'], 'bidding/load_sp_pagination_my_projects',$this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);

					$hired_project_data = $this->Bidding_model->get_employee_hired_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit'));
					$var_hired_project_data = $hired_project_data['data'];
					$data['hired_project_count'] = $hired_project_data['total'];
					$data['page_type'] = 'my_projects';
					foreach($var_hired_project_data as &$value) {
						$value['total_paid_amount'] = $this->Escrow_model->get_sum_released_escrow_amounts_project_sp($value['project_type'], ['fulltime_project_id' => $value['project_id'], 'employer_id' => $value['project_owner_id'], 'employee_id' => $user[0]->user_id]);
					}
					$data["hired_project_data"] = $var_hired_project_data;
					$data['hired_application_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit') - count($data['hired_project_data'])));
					$record_per_page = count($data['hired_project_data']) < $this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_fulltime_projects_employee_view_hired_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$hired_project_data['total'],'data'=>$this->load->view('bidding/employee_hired_application_listing_my_projects',$data, true)]);
					die;
				}
				if($tab_type == 'completed_bids'){	
				
					$completed_bids_project_data = $this->Bidding_model->get_sp_completed_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_sp_view_completed_bids_listing_limit'));

					$paginations = generate_pagination_links($completed_bids_project_data['total'], 'bidding/load_sp_pagination_my_projects',$this->config->item('my_projects_sp_view_completed_bids_listing_limit'),$num_pagination_links, '', ['data-tab-type' => $tab_type]);
					
					$completed_bids_project_data = $this->Bidding_model->get_sp_completed_bids_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_sp_view_completed_bids_listing_limit'));
					$data["completed_bids_project_data"] = $completed_bids_project_data['data'];
					$data['completed_bids_project_count'] = $completed_bids_project_data['total'];
					$data['page_type'] = 'my_projects';
					
					$data['completed_bids_pagination_links'] = $paginations['links'];

					$page = $paginations['current_page_no'];
					
					$multiplication = $this->config->item('my_projects_sp_view_completed_bids_listing_limit') * $page;
					$subtraction = ($multiplication - ($this->config->item('my_projects_sp_view_completed_bids_listing_limit') - count($data['completed_bids_project_data'])));
					$record_per_page = count($data['completed_bids_project_data']) < $this->config->item('my_projects_sp_view_completed_bids_listing_limit') ? $subtraction : $multiplication;
					$page_no = ($this->config->item('my_projects_sp_view_completed_bids_listing_limit') * ($page - 1)) + 1;
					
					echo json_encode(['status' => 200,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$completed_bids_project_data['total'],'data'=>$this->load->view('bidding/sp_completed_bids_listing_my_projects',$data, true)]);
					die;
				}
				
				
			}else{
				echo json_encode(['status' => 400,'location'=>VPATH]);
				die;
			
			}
		}else{
			
			show_custom_404_page(); //show custom 404 page
		}	
		
	
	}
	
	/* This function is calling by node when user post draft project,publish project etc*/
	##File: assets\js\modules\my_projects_management_node.js using this function
	public function update_po_my_projects(){
		
		if($this->input->is_ajax_request ()){
			if(check_session_validity()){ // check session exists or not if exist then it will update user session
				$tab_types = $this->input->post ();
				$user = $this->session->userdata('user');
				$num_pagination_links = $this->config->item('my_projects_number_of_pagination_links');
				if(!empty($tab_types)){
				
					$this->db->select('current_membership_plan_id');
					$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
					
					if($user_detail['current_membership_plan_id'] == 1){
						$user_memebership_max_number_of_draft_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					if($user_detail['current_membership_plan_id'] == 4){
						$user_memebership_max_number_of_draft_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_projects');
						$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');

						$user_memebership_max_number_of_draft_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_draft_fulltime_projects');
						$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
					}
					$result['status'] = 200;
					$result['draft_data'] = '';
					$result['awaiting_moderation_data'] = '';
					$result['open_bidding_data'] = '';
					$result['cancelled_data'] = '';
					$data["page_type"] = 'my_projects';
					
					
					foreach($tab_types as $value){
						if($value == 'draft'){
							$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_draft_projects_listing_limit'));
							$data["draft_project_data"] = $draft_project_data['data'];
							$data["draft_project_count"] = $draft_project_data['total'];

							$paginations = generate_pagination_links($draft_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_draft_projects_listing_limit'),$num_pagination_links,'',['data-tab-type' => 'draft']);
							$data['draft_pagination_links'] = $paginations['links'];
							
							$data['po_max_draft_projects_number'] = $user_memebership_max_number_of_draft_projects;
							$data['po_max_open_projects_number'] = $user_memebership_max_number_of_open_for_bidding_projects;

							$data['draft_cnt'] = $this->Projects_model->get_user_draft_projects_count($user[0]->user_id);
							$data['fulltime_draft_cnt'] = $this->Projects_model->get_user_draft_fulltime_projects_count($user[0]->user_id);
							########## fetch open bidding project information of logged in user ##########################
							
							$result['draft_tab_heading'] = $this->config->item('my_projects_po_view_draft_tab_heading').' ('.$data['draft_cnt'].'/'.($user_memebership_max_number_of_draft_projects - $data['draft_cnt']).' + '.$data['fulltime_draft_cnt'].'/'.($user_memebership_max_number_of_draft_fulltime_projects - $data['fulltime_draft_cnt']).')';
							
							$result['draft_data'] = $this->load->view('projects/po_draft_projects_listing_my_projects',$data, true);
						}
						if($value == 'awaiting_moderation'){
							$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit'));
		
							 $data["awaiting_moderation_project_data"] = $awaiting_moderation_project_data['data'];
							 $data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];

							 $awaiting_project_cnt = $awaiting_moderation_project_data['total'];
							 $fulltime_awaiting_project_cnt = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime' ])->from('projects_awaiting_moderation')->count_all_results();
							 $awaiting_project_cnt -= $fulltime_awaiting_project_cnt;
							 
							 $paginations = generate_pagination_links($awaiting_moderation_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_awaiting_moderation_projects_listing_limit'),$num_pagination_links,'',['data-tab-type' => 'awaiting_moderation']);

							 $data['awaiting_moderation_pagination_links'] = $paginations['links'];
							 $result['awaiting_moderation_tab_heading'] = $this->config->item('my_projects_po_view_awaiting_moderation_tab_heading').' ('.$awaiting_project_cnt.' + '.$fulltime_awaiting_project_cnt.')';
							 
							 $result['awaiting_moderation_data'] = $this->load->view('projects/po_awaiting_moderation_projects_listing_my_projects',$data, true);
						}
						if($value == 'open_for_bidding'){
							$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
							 $data["open_bidding_project_data"] = $open_bidding_project_data['data'];
							 $data['open_bidding_project_count'] = $open_bidding_project_data['total'];

							 $paginations = generate_pagination_links($open_bidding_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$num_pagination_links,'',['data-tab-type' => 'open_for_bidding']);

							 $data['open_bidding_pagination_links'] = $paginations['links'];
							
							 $open_project_cnt = $open_bidding_project_data['total'];
							 $fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
							 $open_project_cnt -= $fulltime_open_project_count;


							 $total_user_open_projects = $this->Projects_model->get_user_open_projects_count($user[0]->user_id);
							 $total_user_open_fulltime_projects = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
							 
							  $result['open_for_bidding_tab_heading'] = $this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$total_user_open_projects).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $total_user_open_fulltime_projects).')';
							 
							 $result['open_bidding_data'] = $this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true);
						}
						if($value == 'expired'){
							
							$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
							$data["expired_project_data"] = $expired_project_data['data'];
							$data['expired_project_count'] = $expired_project_data['total'];

							$paginations = generate_pagination_links($expired_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_expired_projects_listing_limit'),$num_pagination_links,'',['data-tab-type' => 'expired']);

							$data['expired_pagination_links'] = $paginations['links'];
							 
							$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
							$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
							$result['expired_tab_heading'] = $this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')';
							 
							  $result['expired_data'] = $this->load->view('projects/po_expired_projects_listing_my_projects',$data, true);
							
						}
						if($value == 'cancelled'){
							$cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_cancelled_projects_listing_limit'));
							$data["cancelled_project_data"] = $cancelled_project_data['data'];
							$data['cancelled_project_count'] = $cancelled_project_data['total'];
							$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
							$fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
							
							$paginations = generate_pagination_links($cancelled_project_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_cancelled_projects_listing_limit'),$num_pagination_links,'',['data-tab-type' => 'cancelled']);

							$data['cancelled_pagination_links'] = $paginations['links'];
							 
							$result['cancelled_tab_heading'] = $this->config->item('my_projects_po_view_cancelled_tab_heading').' ('.($cancelled_project_data['total'] - $fulltime_cancelled_project_count).' + '.$fulltime_cancelled_project_count.')';
							
							$result['cancelled_data'] = $this->load->view('projects/po_cancelled_projects_listing_my_projects',$data, true);
						}
					}
					echo json_encode($result);
					die;
							
				}
				
			}else{
				$result['status'] = 400;
				$result['location'] = VPATH;
				echo json_encode($result);
				die;
			
			}
		}else{
			show_custom_404_page(); //show custom 404 page
		}
	}
	
	
	
	
	/* This function is used to cancelled the open for bidding project at dedicated my project page*/
	
	public function po_cancelled_open_bidding_project_my_projects() {

		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH]);
			return;
		}
		$error_msg = '';
		$user = $this->session->userdata('user');
		$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
		if($user[0]->user_id != $po_id){
			$msg['status'] = 400;
			$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
			echo json_encode($msg);
			die;
		
		}
		$project_id = $this->input->post ('project_id');
		$project_type = $this->input->post ('project_type');
		$page_type = $this->input->post ('page_type');
		$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
		if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
			if($project_type == 'fulltime'){
				$error_msg = $this->config->item('fulltime_project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
			}else{
				$error_msg = $this->config->item('project_open_for_bidding_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
			}
			$msg['error_message'] = $error_msg;
		}
		
		
		if($project_type != 'fulltime' && in_array($project_status_table_array['project_status'],array('awarded','in_progress','incomplete','completed','cancelled'))){
			$error_msg = $this->config->item('project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
		}
		if($project_type == 'fulltime' && $project_status_table_array['project_status'] == 'cancelled')	{
			$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
		}
		
		if($project_type == 'fulltime' &&  ($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired')){
			$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_awarded_tracking')->count_all_results();
			if($check_awarded_bid > 0){
				$error_msg = $this->config->item('fulltime_project_open_for_bidding_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view');
			}
		}	
		
		
		
		$user_detail = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
		if($user_detail['current_membership_plan_id'] == 1){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('free_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('free_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		if($user_detail['current_membership_plan_id'] == 4){
			$user_memebership_max_number_of_open_for_bidding_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_projects');
			$user_memebership_max_number_of_open_for_bidding_fulltime_projects = $this->config->item('gold_membership_subscriber_max_number_of_open_fulltime_projects');
		}
		$open_bidding_project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id])->row_array();
		$project_type = $open_bidding_project_data['project_type'];
		$project_title = $open_bidding_project_data['project_title'];
		$project_owner = $open_bidding_project_data['project_owner_id'];
		if($error_msg == '' && !empty($open_bidding_project_data)) {
			unset($open_bidding_project_data['id']);
			unset($open_bidding_project_data['views']);
			unset($open_bidding_project_data['urgent']);
			unset($open_bidding_project_data['featured']);
			$open_bidding_project_data['project_cancellation_date'] = date('Y-m-d H:i:s');
			
			
			if($open_bidding_project_data['project_type'] == 'fixed') { // insert into fixed budget project cancelled table
				unset($open_bidding_project_data['project_type']);
				$this->db->insert('fixed_budget_projects_cancelled', $open_bidding_project_data);
			}
			if($open_bidding_project_data['project_type'] == 'fulltime') { // insert into fixed budget project cancelled table
				
				unset($open_bidding_project_data['project_type']);
				$open_bidding_project_data['fulltime_project_id'] = $open_bidding_project_data['project_id'];
				$open_bidding_project_data['employer_id'] = $open_bidding_project_data['project_owner_id'];
				$open_bidding_project_data['fulltime_project_posting_date'] = $open_bidding_project_data['project_posting_date'];
				$open_bidding_project_data['fulltime_project_expiration_date'] = $open_bidding_project_data['project_expiration_date'];
				$open_bidding_project_data['fulltime_project_title'] = $open_bidding_project_data['project_title'];
				$open_bidding_project_data['fulltime_project_description'] = $open_bidding_project_data['project_description'];
				$open_bidding_project_data['fulltime_project_cancellation_date'] = $open_bidding_project_data['project_cancellation_date'];
				$open_bidding_project_data['min_salary'] = $open_bidding_project_data['min_budget'];
				$open_bidding_project_data['max_salary'] = $open_bidding_project_data['max_budget'];

				unset($open_bidding_project_data['project_id']);
				unset($open_bidding_project_data['project_owner_id']);
				unset($open_bidding_project_data['project_posting_date']);
				unset($open_bidding_project_data['project_expiration_date']);
				unset($open_bidding_project_data['project_title']);
				unset($open_bidding_project_data['project_description']);
				unset($open_bidding_project_data['project_cancellation_date']);
				unset($open_bidding_project_data['min_budget']);
				unset($open_bidding_project_data['max_budget']);

				$this->db->insert('fulltime_projects_cancelled', $open_bidding_project_data);
			}
			if($open_bidding_project_data['project_type'] == 'hourly') { // insert into fixed budget project cancelled table
				
				unset($open_bidding_project_data['project_type']);	
				$this->db->insert('hourly_rate_based_projects_cancelled', $open_bidding_project_data);
			}
			
			$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
			$po_detail = $this->db->get_where('users', ['user_id' => $project_owner])->row_array();
			if($project_type == 'fulltime'){
				if(($po_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_detail['is_authorized_physical_person'] =='Y')) {
					if($po_detail['gender'] == 'M') {
						
						if($po_detail['is_authorized_physical_person'] =='Y'){
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_company_app_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}else{
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}
					} else {
						if($po_detail['is_authorized_physical_person'] =='Y'){
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_company_app_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}else{
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}
					}
				} else {
					$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_company_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
				}
				
			} else {
				if(($po_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_detail['is_authorized_physical_person'] =='Y')) {
					if($po_detail['gender'] == 'M') {
						if($po_detail['is_authorized_physical_person'] =='Y'){
							
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_company_app_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
							
						}else{	
						
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_male_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}
					} else {
						if($po_detail['is_authorized_physical_person'] =='Y'){
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_company_app_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}else{	
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_female_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
						}
					}
				} else {
					$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_company_open_for_bidding_user_activity_log_displayed_message_sent_to_po');
				}
			}
			$cancelled_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title, ENT_QUOTES)),$cancelled_project_log_message);
			user_display_log($cancelled_project_log_message);
			
			// Remove project attachement based on project id
			$this->db->delete('projects_attachments', ['project_id' => $project_id]);
			// Remove sp attachments based on project id
			$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);

			// Set next refresh time to null for cancelled project
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);

			// remove entry from project latest refresh sequence tracking table
			$this->db->delete('projects_latest_refresh_sequence_tracking', array('project_id' => $project_id));

			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->load->library('ftp');
			$this->ftp->connect($config); 
			
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			$project_cancelled_dir = PROJECT_CANCELLED_DIR;
			$profile_folder = $user[0]->profile_name;
			
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
			
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id.DIRECTORY_SEPARATOR);
			
			// remov entry from open bidding table
			$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
			if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id))) {
				$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir.$project_id);
			}//$this->ajax_update_open_bidding_or_expired_and_cancelled_project_dashboard_view('open');
		}
		
		if($page_type == "my_projects"){
			if($this->input->post ('active_page')){
			$page = $this->input->post ('active_page');
			}else{
				$page = 1;
			}	
			
			$start = 0;
			$open_bidding_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
			$paginations = generate_pagination_links($open_bidding_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => $tab_type]);
			$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit'));
		
		}
		if($page_type == "dashboard"){
			$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
		}
		$data["page_type"] = $page_type;
		$data["open_bidding_project_data"] = $open_bidding_project_data['data'];
		$data['open_bidding_project_count'] = $open_bidding_project_data['total'];
		$open_project_cnt = $open_bidding_project_data['total'];
		$fulltime_open_project_cnt = $this->Projects_model->get_user_open_fulltime_projects_count($user[0]->user_id);
		$fulltime_open_project_count = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date >='=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
		$open_project_cnt -= $fulltime_open_project_count;
		if($page_type == "my_projects"){
			$page = $paginations['current_page_no'];
			$data['open_bidding_pagination_links'] = $paginations['links'];
			$multiplication = $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * $page;
			$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') - count($data['open_bidding_project_data'])));
			$record_per_page = count($data['open_bidding_project_data']) < $this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') ? $subtraction : $multiplication;
			$page_no = ($this->config->item('my_projects_po_view_open_bidding_projects_listing_limit') * ($page - 1)) + 1;
			echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$open_bidding_project_data['total'],'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
			die;
		}
		if($page_type=="dashboard"){
			echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_open_for_bidding_tab_heading').' ('.$open_project_cnt.'/'.($user_memebership_max_number_of_open_for_bidding_projects-$this->Projects_model->get_user_open_projects_count($user[0]->user_id)).' + '.$fulltime_open_project_count.'/'.($user_memebership_max_number_of_open_for_bidding_fulltime_projects - $fulltime_open_project_cnt).')','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_open_bidding_projects_listing_my_projects',$data, true)]);
		}
		die;
	}
	
	/* This function is used to cancelled the expired project at dedicated my project page*/
	public function po_cancelled_expired_project_my_projects() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH]);
			return;
		}
		$project_id = $this->input->post ('project_id');
		$user = $this->session->userdata('user');
		$po_id = Cryptor::doDecrypt($this->input->post ('po_id'));
		if($user[0]->user_id != $po_id){
			$msg['status'] = 400;
			$msg['location'] = VPATH.$this->config->item('dashboard_page_url');
			echo json_encode($msg);
			die;
		
		}
		$project_type = $this->input->post ('project_type');
		$page_type = $this->input->post ('page_type');
		$error_msg = '';
		$project_status_table_array = $this->Projects_model->get_project_status_table_name($project_id);
		if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
			if($project_type == 'fulltime'){
				$error_msg = $this->config->item('fulltime_project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
			}else{
				$error_msg = $this->config->item('project_expired_admin_deleted_not_available_for_cancel_dashboard_myprojects_po_view');
			}
			$msg['error_message'] = $error_msg;
		}
		
	
		
		if($project_type != 'fulltime' && in_array($project_status_table_array['project_status'],array('awarded','in_progress','incomplete','completed','cancelled'))){
			$error_msg = $this->config->item('project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
		}
		if($project_type == 'fulltime' && $project_status_table_array['project_status'] == 'cancelled')	{
			$error_msg = $this->config->item('fulltime_project_expired_status_changed_not_available_for_cancel_dashboard_myprojects_po_view');
			
		}
		if($project_type == 'fulltime' &&  ($project_status_table_array['project_status'] == 'open_for_bidding' || $project_status_table_array['project_status'] == 'expired')){
			$check_awarded_bid = $this->db->where(['fulltime_project_id' => $project_id])->from('fulltime_projects_awarded_tracking')->count_all_results();
			if($check_awarded_bid > 0){
				$error_msg = $this->config->item('fulltime_project_expired_status_awarded_bids_exist_not_available_for_cancel_myprojects_po_view');
			}
		}	
		
		
		//$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
		
		
		$expired_project_data = array();
		
		if($project_status_table_array['project_status'] == 'open_for_bidding'){
			$expired_project_data = $this->db->get_where('projects_open_bidding', ['project_id' => $project_id])->row_array();
		}else{
		
			$fixed_budget_expired_project_data = $this->db->get_where('fixed_budget_projects_expired', ['project_id' => $project_id])->row_array();
			if(!empty($fixed_budget_expired_project_data)){
				$expired_project_data = $fixed_budget_expired_project_data;
			}else{
				$hourly_rate_based_expired_project_data = $this->db->get_where('hourly_rate_based_projects_expired', ['project_id' => $project_id])->row_array();
				if(!empty($hourly_rate_based_expired_project_data)){
					$expired_project_data = $hourly_rate_based_expired_project_data;
				}else{
					$fulltime_expired_project_data = $this->db->get_where('fulltime_projects_expired', ['fulltime_project_id' => $project_id])->row_array();
					if(!empty($fulltime_expired_project_data)){
						$expired_project_data = $fulltime_expired_project_data;
					}
				}
			}
		}	
			
		
		if($error_msg == '' && !empty($expired_project_data)) {

			$project_type = $expired_project_data['project_type'];
			
			unset($expired_project_data['id']);

			if($expired_project_data['project_type'] == 'fulltime') {
				$project_title = $expired_project_data['fulltime_project_title'];
				$expired_project_data['fulltime_project_cancellation_date'] = date('Y-m-d H:i:s');
				$project_owner = $expired_project_data['employer_id'];
			} else {
				$project_title = $expired_project_data['project_title'];
				$expired_project_data['project_cancellation_date'] = date('Y-m-d H:i:s');
				$project_owner = $expired_project_data['project_owner_id'];
			}

			if($expired_project_data['project_type'] == 'fixed'){
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
				}
				
				unset($expired_project_data['project_type']);
				unset($expired_project_data['featured']);
				unset($expired_project_data['urgent']);
				$this->db->insert('fixed_budget_projects_cancelled', $expired_project_data);
				$this->db->delete('fixed_budget_projects_expired', ['project_id' => $project_id]);
			}
			if($expired_project_data['project_type'] == 'hourly'){
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
				}
				unset($expired_project_data['project_type']);
				unset($expired_project_data['featured']);
				unset($expired_project_data['urgent']);
				$this->db->insert('hourly_rate_based_projects_cancelled', $expired_project_data);
				$this->db->delete('hourly_rate_based_projects_expired', ['project_id' => $project_id]);
			}
			if($expired_project_data['project_type'] == 'fulltime'){
				if($project_status_table_array['project_status'] == 'open_for_bidding'){
					$this->db->delete('projects_open_bidding', ['project_id' => $project_id]);
					$expired_project_data['fulltime_project_id'] = $expired_project_data['project_id'];
					$expired_project_data['employer_id'] = $expired_project_data['project_owner_id'];
					$expired_project_data['fulltime_project_posting_date'] = $expired_project_data['project_posting_date'];
					$expired_project_data['fulltime_project_expiration_date'] = $expired_project_data['project_expiration_date'];
					$expired_project_data['fulltime_project_title'] = $expired_project_data['project_title'];
					$expired_project_data['fulltime_project_description'] = $expired_project_data['project_description'];
					$expired_project_data['min_salary'] = $expired_project_data['min_budget'];
					$expired_project_data['max_salary'] = $expired_project_data['max_budget'];

					unset($expired_project_data['project_id']);
					unset($expired_project_data['project_owner_id']);
					unset($expired_project_data['project_posting_date']);
					unset($expired_project_data['project_expiration_date']);
					unset($expired_project_data['project_title']);
					unset($expired_project_data['project_description']);
					unset($expired_project_data['min_budget']);
					unset($expired_project_data['max_budget']);
				}
				unset($expired_project_data['featured']);
				unset($expired_project_data['urgent']);
				unset($expired_project_data['project_type']);
				$this->db->insert('fulltime_projects_cancelled', $expired_project_data);
				$this->db->delete('fulltime_projects_expired', ['fulltime_project_id' => $project_id]);
			}
			
			$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_id;
			$po_detail = $this->db->get_where('users', ['user_id' => $project_owner])->row_array();
			if($project_type == 'fulltime'){
				if(($po_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_detail['is_authorized_physical_person'] =='Y')) {
					if($po_detail['gender'] == 'M') {
						
						if($po_detail['is_authorized_physical_person'] =='Y'){
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_company_app_male_expired_user_activity_log_displayed_message_sent_to_po');
						}else{	
						
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_male_expired_user_activity_log_displayed_message_sent_to_po');
						}
					} else {
						if($po_detail['is_authorized_physical_person'] =='Y'){
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_company_app_female_expired_user_activity_log_displayed_message_sent_to_po');
						}else{
							$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_female_expired_user_activity_log_displayed_message_sent_to_po');
						}
					}
				} else {
					$cancelled_project_log_message = $this->config->item('fulltime_project_cancelled_by_po_company_expired_user_activity_log_displayed_message_sent_to_po');
				}
				
			}else{
				if(($po_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($po_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $po_detail['is_authorized_physical_person'] =='Y')) {
					if($po_detail['gender'] == 'M') {
						if($po_detail['is_authorized_physical_person'] =='Y'){
							
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_company_app_male_expired_user_activity_log_displayed_message_sent_to_po');
							
						}else{
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_male_expired_user_activity_log_displayed_message_sent_to_po');
						}
					} else {
						if($po_detail['is_authorized_physical_person'] =='Y'){
							
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_company_app_female_expired_user_activity_log_displayed_message_sent_to_po');
							
						}else{
							$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_female_expired_user_activity_log_displayed_message_sent_to_po');
						}
					}
				} else {
					$cancelled_project_log_message = $this->config->item('project_cancelled_by_po_company_expired_user_activity_log_displayed_message_sent_to_po');
				}
				
			}
			
			$cancelled_project_log_message = str_replace(array('{project_url_link}','{project_title}'),array($project_url_link,htmlspecialchars($project_title, ENT_QUOTES)),$cancelled_project_log_message);
			user_display_log($cancelled_project_log_message);
			// Remove project attachement based on project id
			$this->db->delete('projects_attachments', ['project_id' => $project_id]);
			// Remove sp attachments based on project id
			$this->db->delete('projects_active_bids_users_attachments_tracking', ['project_id' => $project_id]);

			// Set next refresh time to null for cancelled project
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('standard_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('featured_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('urgent_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);
			$this->db->limit(1);
			$this->db->order_by('id', 'desc');
			$this->db->update('sealed_projects_refresh_sequence_tracking', ['project_next_refresh_time' => null], ['project_id' => $project_id]);

			$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
			$config['ftp_username'] = FTP_USERNAME;
			$config['ftp_password'] = FTP_PASSWORD;
			$config['ftp_port'] 	= FTP_PORT;
			$config['debug']    = TRUE;
			$this->load->library('ftp');
			$this->ftp->connect($config); 
			$users_ftp_dir 	= USERS_FTP_DIR; 
			$projects_ftp_dir = PROJECTS_FTP_DIR;
			if($project_status_table_array['project_status'] == 'open_for_bidding'){
				$project_expired_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
			}
			if($project_status_table_array['project_status'] == 'expired'){
				$project_expired_dir = PROJECT_EXPIRED_DIR;
			}
			
			$project_cancelled_dir = PROJECT_CANCELLED_DIR;
			$profile_folder = $user[0]->profile_name;
			
			
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR);
			
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir);
			$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id.DIRECTORY_SEPARATOR);
			
			
			//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir, 0777);// create projects directory if not exists							
			//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir, 0777);// create cancelled directory in projects folder
			//$this->ftp->mkdir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_cancelled_dir.$project_id , 0777); // create the directory by using  project id
			
			// remov entry from open bidding table
			
			if(!empty($this->ftp->check_ftp_directory_exist($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id))) {
				$this->ftp->delete_dir($users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_expired_dir.$project_id);
			}
		}	
		if($page_type == "my_projects"){
			if($this->input->post ('active_page')){
				$page = $this->input->post ('active_page');
			}else{
				$page = 1;
			}
			
			######################
			$start = 0;
			$expired_data  = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
			$paginations = generate_pagination_links($expired_data['total'], $this->config->item('my_projects_page_url'),$this->config->item('my_projects_po_view_expired_projects_listing_limit'),$this->config->item('my_projects_number_of_pagination_links'),'',['data-tab-type' => 'expired']);
			$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,$paginations['offset'], $this->config->item('my_projects_po_view_expired_projects_listing_limit'));
		}	
		if($page_type == "dashboard"){
			$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
		}	
			
		$data["page_type"] = $page_type;
		$data["expired_project_data"] = $expired_project_data['data'];
		$data["expired_project_count"] = $expired_project_data['total'];
		$fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
		$fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
		if($page_type == "my_projects"){
			$page = $paginations['current_page_no'];
			$data['expired_pagination_links'] = $paginations['links'];
			
			$multiplication = $this->config->item('my_projects_po_view_expired_projects_listing_limit') * $page;
			$subtraction = ($multiplication - ($this->config->item('my_projects_po_view_expired_projects_listing_limit') - count($data['expired_project_data'])));
			$record_per_page = count($data['expired_project_data']) < $this->config->item('my_projects_po_view_expired_projects_listing_limit') ? $subtraction : $multiplication;
			$page_no = ($this->config->item('my_projects_po_view_expired_projects_listing_limit') * ($page - 1)) + 1;
			echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','status' => 200,'error_msg'=>$error_msg,'record_per_page'=>$record_per_page,'page_no'=>$page_no,'total_records'=>$expired_project_data['total'],'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
			die;
		}
		if($page_type=="dashboard"){
			echo json_encode(['tab_heading'=>$this->config->item('my_projects_po_view_expired_tab_heading').' ('.($expired_project_data['total'] - $fulltime_expired_project_cnt).' + '.$fulltime_expired_project_cnt.')','status' => 200,'error_msg'=>$error_msg,'data'=>$this->load->view('projects/po_expired_projects_listing_my_projects',$data, true)]);
		}
		
		/* }else{
		
			echo json_encode(['status' => 400,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('refresh_page_validation_message')]);
			die;
		} */
	}
	
	
	/**
  * This function is used tosend real time notification when project is approved by admin or auto approved
  */
	public function send_project_approved_notification_po(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH]);
			die;
		}
		if($this->input->method(TRUE) === 'POST'){
			
			$project_id = $this->input->post ('project_id');
			
			$user = $this->session->userdata('user');
			$project_data = $this->db // get the user detail
			->select('pd.project_title,pd.project_id,pd.project_type')
			->from('projects_open_bidding pd')
			->where('pd.project_id', $project_id)
			->get()->row_array();
			
			if(!empty($project_data)){
				$notification_message = $this->config->item('project_approved_by_admin_realtime_notification_message_sent_to_po');
				if($project_data['project_type'] == 'fulltime'){
					$notification_message = $this->config->item('fulltime_project_approved_by_admin_realtime_notification_message_sent_to_po');
				}
				$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
				$project_title = $project_data['project_title'];
				$project_title = htmlspecialchars($project_title);
				$notification_message = str_replace(array("{project_title}","{project_url_link}"),array($project_title,$project_url_link),$notification_message);
				echo json_encode(['status' => 200,'msg'=>$notification_message,'location'=>'']);
				die;
				
			}else{
				//echo json_encode($msg);
				echo json_encode (['status' => 400,'location'=>'']);
				die;
			}
		}
	}
	
	
	/**
  * This function is used to send real time notification when project is rejected by admin
  */
	public function send_project_rejected_notification_po(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH]);
			die;
		}
		if($this->input->method(TRUE) === 'POST'){
			
			$project_id = $this->input->post ('project_id');
			
			$user = $this->session->userdata('user');
			$project_data = $this->db // get the user detail
			->select('pd.project_title,pd.project_id,pd.project_type')
			->from('projects_draft pd')
			->where('pd.project_id', $project_id)
			->get()->row_array();
			
			if(!empty($project_data)){
				$notification_message = $this->config->item('project_rejected_by_admin_realtime_notification_message_sent_to_po');
				if($project_data['project_type'] == 'fulltime'){
					$notification_message = $this->config->item('fulltime_project_rejected_by_admin_realtime_notification_message_sent_to_po');
				}
				
				$project_title = $project_data['project_title'];
				$project_title = htmlspecialchars($project_title);
				$notification_message = str_replace(array("{project_title}"),array($project_title,$project_url_link),$notification_message);
				echo json_encode(['status' => 200,'msg'=>$notification_message,'location'=>'']);
				die;
				
			}else{
				//echo json_encode($msg);
				echo json_encode (['status' => 400,'location'=>'']);
				die;
			}
		}
	}
	
	
	
	/**
 	* This function is used tosend real time notification when project is approved by admin or auto approved
  */
	public function send_project_delete_notification_po(){
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 400,'location'=>VPATH]);
			die;
		}
		if($this->input->method(TRUE) === 'POST'){
			
			$project_title = $this->input->post ('project_title');
			$project_title = base64_decode($project_title);
			$user = $this->session->userdata('user');
			$notification_message = $this->config->item('project_deleted_by_admin_realtime_notification_message_sent_to_po');
			$project_title = htmlspecialchars($project_title);
			$notification_message = str_replace(array("{project_title}"),array($project_title),$notification_message);
			echo json_encode(['status' => 200,'msg'=>$notification_message,'location'=>'']);
			die;
		}
	}
	
	
	/**
	* This function is used to fetch the open for bidding projects drop down for send invitations
    */
	public function get_user_open_bidding_projects_send_project_invitation(){
		
		$user = $this->session->userdata('user');
		$user_id = $user[0]->user_id;
		$sp_id  = $this->input->post ('sp_id');
		$current_page  = $this->input->post ('current_page');
		
		if($this->input->is_ajax_request ()){
			
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>$page_url,'error'=>'']);
				die;
			}
			
			
			if( Cryptor::doDecrypt($this->input->post ('uid')) != $user[0]->user_id){
				echo json_encode(['status' => 400,'location'=>$page_url,'error'=>'']);
				die;
			}
			
			$count_open_projects = $this->db->where(['project_owner_id'=>$user_id,'project_expiration_date >='=>date('Y-m-d H:i:s')])->from('projects_open_bidding')->count_all_results();	
				
				
			if($count_open_projects == 0){
				echo json_encode(['status' => 400,'error'=>$this->config->item('po_sent_project_invitation_open_projects_not_available_error_message')]);
				die;
			}
			
			$user_detail = $this->db->get_where('users_details', ['user_id' => $user_id])->row_array();
			if($user_detail['current_membership_plan_id'] == '1'){
				$per_month_limit = $this->config->item('free_subscriber_allowed_sent_invites_per_month');
				$per_project_limit = $this->config->item('free_subscriber_allowed_sent_invites_per_project');
			}
			if($user_detail['current_membership_plan_id'] == '4'){
				$per_month_limit = $this->config->item('gold_subscriber_allowed_sent_invites_per_month');
				$per_project_limit = $this->config->item('gold_subscriber_allowed_sent_invites_per_project');
			}
			
			$membership_included_project_invites_count_per_month = $this->Projects_model->count_po_sent_project_invitations_membership_included_monthly($user_id);// count project inites to current month
			
			if($per_month_limit != '0' && $membership_included_project_invites_count_per_month >= $per_month_limit){
				echo json_encode(['status' => 400,'error'=>$this->config->item('po_sent_project_invitation_allowed_sent_invites_per_month_error_message')]);
				die;
			
			
			}
			// check that PO send invitation for all projects
			$this->db->select('op.project_id,project_invitation.total_project_invitation');
			$this->db->from('projects_open_bidding op');
			$this->db->where(['op.project_owner_id'=>$user_id,'project_expiration_date >='=>date('Y-m-d H:i:s')]);
			$this->db->join('(select project_id, count(*) as total_project_invitation from '.$this->db->dbprefix .'projects_invitations_tracking where  po_id = "'.$user_id.'" and sp_id = "'.$sp_id.'" group by project_id ) as project_invitation', 'project_invitation.project_id = op.project_id', 'left');
			$open_bidding_project_result = $this->db->get();
			$open_bidding_project_data = $open_bidding_project_result->result_array();
			$check_per_project_invitation = '1';
			if(!empty($open_bidding_project_data)){
				foreach($open_bidding_project_data as $key=>$value){
					if($value['total_project_invitation'] != '1'){
						$check_per_project_invitation = '0';
						break;
					
					}
				}
			}
			/* if($check_per_project_invitation == 0 && ){
			
			
			} */
			
			if($check_per_project_invitation == '1'){
				$sp_user_detail = $this->db // get the user detail
				->select('u.user_id,u.email,u.account_type,u.first_name,u.last_name,u.company_name')
				->from('users u')
				->where('u.user_id', $sp_id)
				->get()->row_array();
			
				$sp_name = $sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ?  $sp_user_detail['first_name']." ".$sp_user_detail['last_name'] : $sp_user_detail['company_name'];	
			
			
				$error_msg = $this->config->item('po_sent_project_invitation_to_same_user_no_projects_available_left_error_message');
				$error_msg = str_replace('{user_first_name_last_name_or_company_name}',$sp_name,$error_msg);
				
				echo json_encode(['status' => 400,'error'=>$error_msg]);
				die;
			}
			
			// check per project invitation count
			$this->db->select('op.project_id,project_invitation.total_project_invitation');
			$this->db->from('projects_open_bidding op');
			$this->db->where(['op.project_owner_id'=>$user_id,'project_expiration_date >='=>date('Y-m-d H:i:s')]);
			$this->db->join('(select project_id, count(*) as total_project_invitation from '.$this->db->dbprefix .'projects_invitations_tracking where  po_id = "'.$user_id.'"  group by project_id ) as project_invitation', 'project_invitation.project_id = op.project_id', 'left');
			$open_bidding_project_result = $this->db->get();
			$open_bidding_project_data = $open_bidding_project_result->result_array();
			$check_per_project_invitation = '1';
			if(!empty($open_bidding_project_data)){
				foreach($open_bidding_project_data as $key=>$value){
					if($value['total_project_invitation'] < $per_project_limit){
						$check_per_project_invitation = '0';
						break;
					
					}
				}
			}
			
			if($check_per_project_invitation == '1'){
			
				//$error_msg = $this->config->item('po_sent_project_invitation_allowed_sent_invites_per_project_error_message');
				$error_msg = $this->config->item('po_sent_project_invitation_no_listing_available_to_send_invitation_to');
				echo json_encode(['status' => 400,'error'=>$error_msg]);
				die;
			}
			
			
			$options = "<option value=''>".$this->config->item('get_in_contact_popup_drop_down_option_select_project_for_send_invitations')."</option>";
			$this->db->select ('project_id,project_title,project_type');
			$this->db->order_by('project_title', 'ASC');
			$this->db->where (['project_owner_id'=>$user_id,'project_expiration_date >='=>date('Y-m-d H:i:s')]);
			$res = $this->db->get ('projects_open_bidding'); 
			
				$count_project_option = 0;
				if(!empty($res->result ())){
					foreach ($res->result () as $row)
					{
						$check_invitation_already_send = $this->db->where(['po_id'=>$user_id,'sp_id'=>$sp_id,'project_id'=>$row->project_id])->from('projects_invitations_tracking')->count_all_results();	
						
						$count_project_invitation = $this->db->where(['po_id'=>$user_id,'project_id'=>$row->project_id])->from('projects_invitations_tracking')->count_all_results();
						
						//implement the check if sp alrady apply bid
						
						if($row->project_type == 'fixed'){
							$check_sp_bid_exists = $this->db->where(['project_owner_id'=>$user_id,'bidder_id'=>$sp_id,'project_id'=>$row->project_id])->from('fixed_budget_projects_open_bidding_active_bids')->count_all_results();	
						}if($row->project_type == 'hourly'){
							$check_sp_bid_exists = $this->db->where(['project_owner_id'=>$user_id,'bidder_id'=>$sp_id,'project_id'=>$row->project_id])->from('hourly_rate_based_projects_open_bidding_active_bids')->count_all_results();	
						}
						if($row->project_type == 'fulltime'){
							$check_sp_bid_exists = $this->db->where(['employer_id'=>$user_id,'employee_id'=>$sp_id,'fulltime_project_id'=>$row->project_id])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
							
							$check_sp_awarded_exists = $this->db->where(['employer_id'=>$user_id,'employee_id'=>$sp_id,'fulltime_project_id'=>$row->project_id])->from('fulltime_projects_awarded_tracking')->count_all_results();
							
							$check_sp_hired_exists = $this->db->where(['employer_id'=>$user_id,'employee_id'=>$sp_id,'fulltime_project_id'=>$row->project_id])->from('fulltime_projects_hired_employees_tracking')->count_all_results();
						
						}
						
						
						
						$check_project = true;
						if($row->project_type != 'fulltime'){
							if($check_invitation_already_send > 0 || ($per_project_limit != '0' && $count_project_invitation >= $per_project_limit) || $check_sp_bid_exists >0){
								$check_project = false;
							
							}
						}else if($row->project_type == 'fulltime'){
							
							if($check_invitation_already_send > 0 || ($per_project_limit != '0' && $count_project_invitation >= $per_project_limit) || $check_sp_bid_exists >0 || $check_sp_awarded_exists || $check_sp_hired_exists){
								$check_project = false;
							
							}
							
						}	
						
						if($check_project){
							$options .= "<option value='".$row->project_id ."'>".$row->project_title."</option>";
							$count_project_option++;
						}
					}
					
					if($count_project_option != 0){
						$msg['status'] = 200;
						$msg['projects_options'] = $options;
					}else{
						
						$msg['status'] = 400;
						$msg['error'] = $this->config->item('po_sent_project_invitation_open_projects_not_available_error_message');
					}
					echo json_encode ($msg);die;
				}else{
					$msg['status'] = 400;
					$msg['error'] = $this->config->item('po_sent_project_invitation_open_projects_not_available_error_message');
					echo json_encode ($msg);die;
				}
			}else{
			show_custom_404_page(); //show custom 404 page
		}
		
	}
	
	/**
		* This function is used to send the invitation to SP by PO for projetcs
    */
	public function send_project_invitation(){
		
		$user = $this->session->userdata('user');
		$user_id = $user[0]->user_id;
		$current_page  = $this->input->post ('current_page');
		
		if($this->input->is_ajax_request ()){
			if(!check_session_validity()) {
				echo json_encode(['status' => 400,'location'=>$page_url,'error'=>'']);
				die;
			}
			if( Cryptor::doDecrypt($this->input->post ('uid')) != $user[0]->user_id){
				echo json_encode(['status' => 400,'location'=>$page_url,'error'=>'']);
				die;
			}
			$project_id = $this->input->post ('project_id');
			$sp_id  = $this->input->post ('sp_id');
			$project_status_table_array = $this->Projects_model->get_project_status_type($project_id);
			
			if(empty($project_status_table_array['project_status'])){
			
				$error_msg = $this->config->item('po_sent_project_invitation_to_sp_project_status_changed_error_message');
				echo json_encode(['status' => 400,'error'=>$error_msg]);
				die;
			}
			
			if(substr($project_status_table_array['table_name'], 0, strlen('fulltime')) === 'fulltime'){
				
				
				$project_data = $this->db // get the user detail
				->select('pd.fulltime_project_id as project_id,pd.project_type,pd.fulltime_project_expiration_date as project_expiration_date,pd.fulltime_project_title as project_title,pd.employer_id as project_owner_id,pd.hidden,u.gender,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.profile_name,ud.current_membership_plan_id')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.employer_id', 'left')
				->join('users_details ud', 'u.user_id = ud.user_id', 'left')
				->where('pd.fulltime_project_id', $project_id)
				->get()->row_array();
				/* echo "<pre>";
				print_r($project_data);
				die;
				 */
				
				
				
			}else{
				$project_data = $this->db // get the user detail
				->select('pd.project_id,pd.project_type,pd.project_expiration_date,pd.project_title,pd.project_owner_id,pd.hidden,u.gender,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.profile_name,ud.current_membership_plan_id')
				->from($project_status_table_array['table_name'].' pd')
				->join('users u', 'u.user_id = pd.project_owner_id', 'left')
				->join('users_details ud', 'u.user_id = ud.user_id', 'left')
				->where('pd.project_id', $project_id)
				->get()->row_array();
				
				
					
				
			}
			$project_expiration_date = $project_data['project_expiration_date']!= NULL ? strtotime ($project_data['project_expiration_date']) : 0;
			
		
			$sp_user_detail = $this->db // get the user detail
			->select('u.user_id,u.email,u.gender,u.account_type,u.is_authorized_physical_person,u.first_name,u.last_name,u.company_name,u.profile_name')
			->from('users u')
			->where('u.user_id', $sp_id)
			->get()->row_array();
			
			$sp_name = (($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' ))?  $sp_user_detail['first_name']." ".$sp_user_detail['last_name'] : $sp_user_detail['company_name'];
			
			$po_name = (($project_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($project_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $project_data['is_authorized_physical_person'] == 'Y') ) ?  $project_data['first_name']." ".$project_data['last_name'] : $project_data['company_name'];
			
			
			
			//if($project_status_table_array['project_status'] == 'open_for_bidding' && $project_expiration_date > time()){
				if($project_data['project_type'] == 'fixed'){
					$bid_table_name = 'fixed_budget_projects_open_bidding_active_bids';
					$award_bid_table_name = 'fixed_budget_projects_awarded_tracking';
					$in_progress_bid_table_name = 'fixed_budget_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'fixed_budget_projects_incomplete_tracking';
					$completed_bid_table_name = 'fixed_budget_projects_completed_tracking';
				}
				if($project_data['project_type'] == 'hourly'){
					$bid_table_name = 'hourly_rate_based_projects_open_bidding_active_bids';
					$award_bid_table_name = 'hourly_rate_based_projects_awarded_tracking';
					$in_progress_bid_table_name = 'hourly_rate_based_projects_progress_sp_bid_reference';
					$incomplete_bid_table_name = 'hourly_rate_based_projects_incomplete_tracking';
					$completed_bid_table_name = 'hourly_rate_based_projects_completed_tracking';
					
				}
				if($project_data['project_type'] == 'fulltime'){
					$bid_table_name = 'fulltime_projects_open_bidding_active_applications';
					$award_bid_table_name = 'fulltime_projects_awarded_tracking';
					$in_progress_bid_table_name = 'fulltime_projects_hired_employees_tracking';
					
				}
				
				// check sp already applied bid
				if($project_data['project_type'] == 'fulltime'){
					$check_sp_already_apply_bid = $this->db->where(['fulltime_project_id'=> $project_id,'employer_id'=> $project_data['project_owner_id'],'employee_id'=> $sp_id])->from($bid_table_name)->count_all_results();
				}else{	
					$check_sp_already_apply_bid = $this->db->where(['project_id'=> $project_id,'project_owner_id'=> $project_data['project_owner_id'],'bidder_id'=> $sp_id])->from($bid_table_name)->count_all_results();
				}
					
				if($check_sp_already_apply_bid > 0){
					
					if($project_data['project_type'] == 'fulltime'){
						
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_already_apply_bid_on_fulltime_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_already_apply_bid_on_fulltime_project_error_message');
							}			
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_already_apply_bid_on_fulltime_project_error_message');
						}
					}else{
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							
							
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_already_apply_bid_on_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_already_apply_bid_on_project_error_message');
							}
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_already_apply_bid_on_project_error_message');
						}	
					}	
					if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
						$error_msg = str_replace('{user_first_name_last_name}',$sp_name,$error_msg);
					}else{
						
						
						$error_msg = str_replace('{user_company_name}',$sp_name,$error_msg);
					}
					echo json_encode(['status' => 400,'error'=>$error_msg]);
					die;
				}
				
				
				// check po already award 
				if($project_data['project_type'] == 'fulltime'){
					$check_sp_already_award = $this->db->where(['fulltime_project_id'=> $project_id,'employer_id'=> $project_data['project_owner_id'],'employee_id'=> $sp_id])->from($award_bid_table_name)->count_all_results();
				}else{	
					$check_sp_already_award = $this->db->where(['project_id'=> $project_id,'project_owner_id'=> $project_data['project_owner_id'],'winner_id'=> $sp_id])->from($award_bid_table_name)->count_all_results();
				}
					
				if($check_sp_already_award > 0){
					
					if($project_data['project_type'] == 'fulltime'){
						
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_already_awarded_same_sp_on_fulltime_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_already_awarded_same_sp_on_fulltime_project_error_message');
							}			
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_already_awarded_same_sp_on_fulltime_project_error_message');
						}
					}else{
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							
							
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_already_awarded_same_sp_on_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_already_awarded_same_sp_on_project_error_message');
							}
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_already_awarded_same_sp_on_project_error_message');
						}	
					}	
					if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
						$error_msg = str_replace('{user_first_name_last_name}',$sp_name,$error_msg);
					}else{
						
						
						$error_msg = str_replace('{user_company_name}',$sp_name,$error_msg);
					}
					echo json_encode(['status' => 400,'error'=>$error_msg]);
					die;
				}
			
			
				// check sp already accepted the job
				if($project_data['project_type'] == 'fulltime'){
					$check_sp_already_in_progress = $this->db->where(['fulltime_project_id'=> $project_id,'employer_id'=> $project_data['project_owner_id'],'employee_id'=> $sp_id])->from($in_progress_bid_table_name)->count_all_results();
				}else{	
					$check_sp_already_in_progress = $this->db->where(['project_id'=> $project_id,'project_owner_id'=> $project_data['project_owner_id'],'winner_id'=> $sp_id])->from($in_progress_bid_table_name)->count_all_results();
				}
					
				if($check_sp_already_in_progress > 0){
					
					if($project_data['project_type'] == 'fulltime'){
						
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_hired_same_sp_on_fulltime_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_hired_same_sp_on_fulltime_project_error_message');
							}			
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_hired_same_sp_on_fulltime_project_error_message');
						}
					}else{
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							
							
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_in_progress_same_sp_on_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_in_progress_same_sp_on_project_error_message');
							}
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_in_progress_same_sp_on_project_error_message');
						}	
					}	
					if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
						$error_msg = str_replace('{user_first_name_last_name}',$sp_name,$error_msg);
					}else{
						
						
						$error_msg = str_replace('{user_company_name}',$sp_name,$error_msg);
					}
					echo json_encode(['status' => 400,'error'=>$error_msg]);
					die;
				}
				
				
				// check sp job status is incomplete (fixed/hourly)
				if($project_data['project_type'] != 'fulltime'){
					$check_sp_already_incomplete = $this->db->where(['project_id'=> $project_id,'project_owner_id'=> $project_data['project_owner_id'],'winner_id'=> $sp_id])->from($incomplete_bid_table_name)->count_all_results();
					
					if($check_sp_already_incomplete > 0){
						
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
								
								
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_in_progress_same_sp_on_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_in_progress_same_sp_on_project_error_message');
							}
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_in_progress_same_sp_on_project_error_message');
						}
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							$error_msg = str_replace('{user_first_name_last_name}',$sp_name,$error_msg);
						}else{
							$error_msg = str_replace('{user_company_name}',$sp_name,$error_msg);
						}
						echo json_encode(['status' => 400,'error'=>$error_msg]);
						die;
					}
					
				}
				
				// check sp job status is completed (fixed/hourly)
				if($project_data['project_type'] != 'fulltime'){
					$check_sp_already_completed = $this->db->where(['project_id'=> $project_id,'project_owner_id'=> $project_data['project_owner_id'],'winner_id'=> $sp_id])->from($completed_bid_table_name)->count_all_results();
					
					if($check_sp_already_completed > 0){
						
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
								
								
							if($sp_user_detail['gender'] == 'M'){
								$error_msg = $this->config->item('po_sent_project_invitation_to_male_sp_completed_same_sp_on_project_error_message');
							}else{
								$error_msg = $this->config->item('po_sent_project_invitation_to_female_sp_completed_same_sp_on_project_error_message');
							}
							
						}else{
							$error_msg = $this->config->item('po_sent_project_invitation_to_company_sp_completed_same_sp_on_project_error_message');
						}
						if($sp_user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sp_user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sp_user_detail['is_authorized_physical_person'] == 'Y' )){
							$error_msg = str_replace('{user_first_name_last_name}',$sp_name,$error_msg);
						}else{
							$error_msg = str_replace('{user_company_name}',$sp_name,$error_msg);
						}
						echo json_encode(['status' => 400,'error'=>$error_msg]);
						die;
					}
					
				}
					
				
				
				// check po already send invitation to SP
				
				$projects_sent_invitations_tracking_exists = $this->db->where(['project_id'=> $project_id,'po_id'=> $project_data['project_owner_id'],'sp_id'=> $sp_id])->from('projects_invitations_tracking')->count_all_results();
				
				
				if($projects_sent_invitations_tracking_exists > 0){
					$error_msg = $this->config->item('po_already_sent_project_invitation_to_sp_error_message');
					$error_msg = str_replace('{user_first_name_last_name_or_company_name}',$sp_name,$error_msg);
					echo json_encode(['status' => 400,'error'=>$error_msg]);
					die;
				
				}
				
			//}
			
			
			if($project_status_table_array['project_status'] != 'open_for_bidding' || $project_expiration_date <= time()){
			
				$error_msg = $this->config->item('po_sent_project_invitation_to_sp_project_status_changed_error_message');
				echo json_encode(['status' => 400,'error'=>$error_msg]);
				die;
			}
			
			
			
			if($project_data['current_membership_plan_id'] == '1'){
				$per_month_limit = $this->config->item('free_subscriber_allowed_sent_invites_per_month');
				$per_project_limit = $this->config->item('free_subscriber_allowed_sent_invites_per_project');
			}
			if($project_data['current_membership_plan_id'] == '4'){
				$per_month_limit = $this->config->item('gold_subscriber_allowed_sent_invites_per_month');
				$per_project_limit = $this->config->item('gold_subscriber_allowed_sent_invites_per_project');
			}
			
			$membership_included_project_invites_count_per_month = $this->Projects_model->count_po_sent_project_invitations_membership_included_monthly($user_id);// count project inites to current month
			
			if($per_month_limit != '0' && $membership_included_project_invites_count_per_month >= $per_month_limit){
				echo json_encode(['status' => 400,'error'=>$this->config->item('po_sent_project_invitation_allowed_sent_invites_per_month_error_message')]);
				die;
			
			}
			
			$count_project_invitation = $this->db->where(['po_id'=>$user_id,'project_id'=>$project_id])->from('projects_invitations_tracking')->count_all_results();
			if($per_project_limit != '0' && $count_project_invitation >= $per_project_limit){
			
				if($project_data['project_type'] != 'fulltime'){
					
					echo json_encode(['status' => 400,'error'=>$this->config->item('po_sent_project_invitation_allowed_sent_invites_per_project_error_message')]);
					die;
				}
				if($project_data['project_type'] == 'fulltime'){
				
					echo json_encode(['status' => 400,'error'=>$this->config->item('po_sent_project_invitation_allowed_sent_invites_per_fulltime_project_error_message')]);
					die;
				}
			
			}
			if($projects_sent_invitations_tracking_exists == 0){
			
				$project_is_hidden = 'no';
				if($project_data['hidden'] == 'Y'){
					$project_is_hidden = 'yes';
				}
				$projects_sent_invitations_tracking_data = array('project_id'=>$project_id,'po_id'=>$project_data['project_owner_id'],'sp_id'=>$sp_id,'invitation_sent_date'=>date('Y-m-d H:i:s'),'project_is_hidden'=>$project_is_hidden);
				if($this->db->insert('projects_invitations_tracking', $projects_sent_invitations_tracking_data)){
				
					//activity log message start here 
				
					if($project_data['project_type'] == 'fulltime'){
						$sp_activity_log_message = $this->config->item('fulltime_project_invitation_message_sent_to_sp_user_activity_log_displayed_message');
						$po_activity_log_message = $this->config->item('fulltime_project_invitation_message_sent_to_po_user_activity_log_displayed_message');
					}else{
						$sp_activity_log_message = $this->config->item('project_invitation_message_sent_to_sp_user_activity_log_displayed_message');
						$po_activity_log_message = $this->config->item('project_invitation_message_sent_to_po_user_activity_log_displayed_message');
					}
					
					$project_url_link = VPATH.$this->config->item('project_detail_page_url')."?id=".$project_data['project_id'];
				
					$sp_profile_url_link = VPATH.$sp_user_detail['profile_name'];
					$po_profile_url_link = VPATH.$project_data['profile_name'];
					
					
					$sp_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{po_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$po_name,$po_profile_url_link),$sp_activity_log_message);
					user_display_log($sp_activity_log_message,$sp_user_detail['user_id']); // activity log message for service provider
					
					
					$po_activity_log_message = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{sp_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$sp_name,$sp_profile_url_link),$po_activity_log_message);
					user_display_log($po_activity_log_message); // activity log message for service provider
					
					//activity log message end here 
					$project_category_data = $this->Projects_model->get_project_categories($project_data['project_id'],$project_status_table_array['project_status']);
					
					
					
					
					$to = $sp_user_detail['email'];
					if($project_data['project_type'] == 'fulltime'){
						$cc = $this->config->item('fulltime_project_invitation_email_cc');
						$bcc = $this->config->item('fulltime_project_invitation_email_bcc');
						$from = $this->config->item('fulltime_project_invitation_email_from');
						$reply_to = $this->config->item('fulltime_project_invitation_email_reply_to');
						$from_name = $this->config->item('fulltime_project_invitation_email_from_name');
						$subject = $this->config->item('fulltime_project_invitation_email_subject');
						$message = $this->config->item('fulltime_project_invitation_email_message');
						$message = str_replace(array('{sp_name}','{po_name}','{fulltime_project_title}','{fulltime_project_url_link}','{po_profile_url_link}'),array($sp_name,$po_name,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$project_url_link,$po_profile_url_link),$message);
					}else{
						$cc = $this->config->item('project_invitation_email_cc');
						$bcc = $this->config->item('project_invitation_email_bcc');
						$from = $this->config->item('project_invitation_email_from');
						$reply_to = $this->config->item('project_invitation_email_reply_to');
						$from_name = $this->config->item('project_invitation_email_from_name');
						$subject = $this->config->item('project_invitation_email_subject');
						$message = $this->config->item('project_invitation_email_message');
						
						$message = str_replace(array('{sp_name}','{po_name}','{project_title}','{project_url_link}','{po_profile_url_link}'),array($sp_name,$po_name,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$project_url_link,$po_profile_url_link),$message);
					}
					$this->load->library('email');
					###################### SMTP variables start here ##########
					$from_name = str_replace("{user_first_name_last_name_or_company_name}",$po_name,$from_name);
					
					$config['protocol'] 	= PROTOCOL;
					$config['smtp_host']    = SMTP_HOST;
					$config['smtp_port']    = SMTP_PORT;
					$config['smtp_timeout'] = SMTP_TIMEOUT;
					$config['smtp_user']    = SMTP_USER;
					$config['smtp_pass']    = SMTP_PASS;
					$config['charset'] = CHARSET;
					$config['mailtype'] = MAILTYPE;
					$config['newline'] = NEWLINE;	
					$from_name = '=?utf-8?B?'.base64_encode($from_name).'?=';
					
					###################### SMTP variables end here ##########
					$this->email->initialize($config);
					$this->email->from ($from,$from_name);
					$this->email->to ($to);
					if($cc){
						$this->email->cc ($cc);
					}
					if($bcc){
						$this->email->bcc ($bcc);
					}
					$this->email->subject ($subject);
					$this->email->reply_to($reply_to);
					$this->email->set_mailtype ('html');
					$this->email->set_newline("\r\n");
					$this->email->message ($message);
					if(!$this->email->send ()) {
					} 
				}
			}
			if($project_data['project_type'] == 'fulltime'){
			
				$success_msg = $this->config->item('fulltime_project_invitation_realtime_notification_message_sent_to_po');
				$sp_realtime_notification = $this->config->item('fulltime_project_invitation_realtime_notification_message_sent_to_sp');
			}else{
				$success_msg = $this->config->item('project_invitation_realtime_notification_message_sent_to_po');
				$sp_realtime_notification = $this->config->item('project_invitation_realtime_notification_message_sent_to_sp');
			}
			
			$success_msg  = str_replace(array('{user_first_name_last_name_or_company_name}','{sp_profile_url_link}'),array($sp_name,$sp_profile_url_link),$success_msg);
			
			
			
			$sp_realtime_notification = str_replace(array('{project_url_link}','{project_title}','{user_first_name_last_name_or_company_name}','{po_profile_url_link}'),array($project_url_link,htmlspecialchars($project_data['project_title'], ENT_QUOTES),$po_name,$po_profile_url_link),$sp_realtime_notification);
			
			
			$msg['project_id'] = $project_data['project_id'];
			//$msg['po_id'] = $user_id;
			$msg['sp_id'] = $sp_user_detail['user_id'];
			$msg['sp_realtime_notification'] = $sp_realtime_notification;
			$msg['status'] = 200;
			$msg['success_msg'] = $success_msg;
			echo json_encode ($msg);die;
			}else{
			show_custom_404_page(); //show custom 404 page
		}
		
	}
	/**
	 * @sid This method is used to sent violation report for project -> assets/js/modules/project_details.js
	*/
	public function ajax_submit_violation_report_on_project() {
		if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
		}
		if(!check_session_validity()) {
			echo json_encode(['status' => 404]);
			return;
		}
		$row = $this->input->post();
		$user = $this->session->userdata ('user');
		if($row['user_id'] != $user[0]->user_id){
			echo json_encode(['status' => 440,'popup_heading'=>$this->config->item('popup_alert_heading'),'location'=>'','error'=>$this->config->item('different_users_session_conflict_message')]);
			die;
		}
		$res['status'] = 200;
		$i = 0;
		if($row['reason'] == '') {
			$res['error'][$i]['id'] = 'reason_error'; 
			$res['error'][$i]['msg'] = $this->config->item('project_details_page_violation_report_popup_reason_required_error_message'); 
			$i++;
		}
		if(empty($row['detail'])) {
			$res['error'][$i]['id'] = 'detail_error'; 
			$res['error'][$i]['msg'] = $this->config->item('project_details_page_violation_report_popup_detail_required_error_message'); 
			$i++;
		} else if(!empty($row['detail'])) {
			$user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', trim($row['detail'])));
			if($this->config->item('project_details_page_violation_report_popup_minimum_length_word_limit') == 0) {
				if($user_description_charcaters_length < $this->config->item('project_details_page_violation_report_popup_minimum_length_character_limit')) {
					$res['error'][$i]['id'] = 'detail_error';
					$res['error'][$i]['msg'] = $this->config->item('project_details_page_violation_report_popup_minimum_length_error_message');
					$i++;
				}
			} else {
				$string_only_single_space = preg_replace("/\s+/", " ", trim($row['detail']));
				$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($user_description_charcaters_length < $this->config->item('project_details_page_violation_report_popup_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('project_details_page_violation_report_popup_minimum_length_word_limit') ){
					$res['error'][$i]['id'] = 'detail_error';
					$res['error'][$i]['msg'] = $this->config->item('project_details_page_violation_report_popup_word_minimum_length_error_message');
					$i++;
				}
			}
		}
		if($i == 0) {
			$project_url = base_url($this->config->item('project_detail_page_url').'?id='.$row['project_id']);


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
			$config['crlf']    = "\n"; 
			
			
			$subject = $this->config->item('email_subject_report_project_violation');
			$msg = $this->config->item('email_message_report_project_violation');

			if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE&&  $user[0]->is_authorized_physical_person == 'Y')) {
				$subject = str_replace('{user_first_name_last_name_or_company_name}', $user[0]->first_name.' '.$user[0]->last_name, $subject);
				$msg = str_replace('{user_first_name_last_name_or_company_name}', $user[0]->first_name.' '.$user[0]->last_name, $msg);
				
			} else {
				$subject = str_replace('{user_first_name_last_name_or_company_name}', $user[0]->company_name, $subject);
				$msg = str_replace('{user_first_name_last_name_or_company_name}', $user[0]->company_name, $msg);
				
			}
			$profile_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$user[0]->profile_name;
			$subject = str_replace('{project_id}', $row['project_id'], $subject);
			
			$msg = str_replace(['{user_profile_page_url}', '{project_url}', '{project_title}', '{project_violation_detail}', '{project_violation_reason}'], [$profile_url, $project_url, $row['project_title'],$row['detail'], $this->config->item('project_details_page_violation_report_popup_reasons_option_name')[$row['reason']]], $msg);

			$this->email->initialize($config);
			$this->email->from($this->config->item('email_from_report_project_violation'), $this->config->item('email_from_name_project_violation'));
			if($this->config->item('email_reply_to_report_project_violation')) {
				$this->email->reply_to($this->config->item('email_reply_to_report_project_violation'));
			}
			if($this->config->item('email_cc_report_project_violation')) {
				$this->email->cc($this->config->item('email_cc_report_project_violation'));
			}
			if($this->config->item('email_bcc_report_project_violation')) {
				$this->email->bcc($this->config->item('email_bcc_report_project_violation'));
			}
			$this->email->subject($subject);
			$this->email->to($user[0]->email);
			
			// $msg = str_replace('{referral_url}', $reffer_url, $msg);
			$this->email->message($msg);
			$this->email->send();
			// Activity log
			$activity_log = $this->config->item('project_details_page_violation_report_user_activity_log_mesage');
			$activity_log = str_replace(['{project_url}', '{project_title}', '{project_violation_reason}'],[$project_url, $row['project_title'], $this->config->item('project_details_page_violation_report_popup_reasons_option_name')[$row['reason']]], $activity_log);
			user_display_log($activity_log);
			// Confirmation message
			$confirm_msg = $this->config->item('project_details_page_violation_report_submit_confirmation_mesage');
			
			$confirm_msg = str_replace(['{project_url}', '{project_title}'],[$project_url, $row['project_title']], $confirm_msg);
			$res['confirm_msg'] = $confirm_msg;
		}
		echo json_encode($res);
		return;
	}
	
	/* public function test(){
	######## connectivity of remote server start#########
		$this->load->library('ftp');
		$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$config['ftp_username'] = FTP_USERNAME;
		$config['ftp_password'] = FTP_PASSWORD;
		$config['ftp_port'] 	= FTP_PORT;
		$config['debug']    = TRUE;
		$this->ftp->connect($config); 
		######## connectivity of remote server end #######
		
		$users_ftp_dir 	= USERS_FTP_DIR; 
		$projects_ftp_dir = PROJECTS_FTP_DIR;
		$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
		$profile_folder     = 'manishsharma';
		
		$destination_path = substr(FCPATH, 0, -1).$users_ftp_dir.$profile_folder.$projects_ftp_dir.$project_open_for_bidding_dir."index.html";
		
		$source_path = FCPATH. '404.php';
		
		exec('ln -s '.$source_path.' '.$destination_path);
		//exec('ln -s /var/www/html/services_portal/404.php /var/www/html/services_portal/users/manishsharma/projects/open_for_bidding/index.html');
		die("dfdsxxccs");
		
			
	} */
	
}
?>