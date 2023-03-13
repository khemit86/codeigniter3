<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projects_disputes_model extends BaseModel {

    public function __construct() {
         parent::__construct();
    }

	// Listing the active disputes
    public function active_projects_disputes_listing($rowno, $rowperpage, $search = ''){
		
		
		$fixed_budget_projects_active_disputes_tbl = $this->db->dbprefix."fixed_budget_projects_active_disputes";
		$fixed_budget_projects_complete_tbl = $this->db->dbprefix."fixed_budget_projects_completed";
		$fixed_budget_projects_incomplete_tbl = $this->db->dbprefix."fixed_budget_projects_incomplete";
		$fixed_budget_projects_inprogress_tbl = $this->db->dbprefix."fixed_budget_projects_progress";
		
		
		$hourly_projects_active_disputes_tbl = $this->db->dbprefix."hourly_rate_based_projects_active_disputes";
		$hourly_projects_complete_tbl = $this->db->dbprefix."hourly_rate_based_projects_completed";
		$hourly_projects_incomplete_tbl = $this->db->dbprefix."hourly_rate_based_projects_incomplete";
		$hourly_projects_inprogress_tbl = $this->db->dbprefix."hourly_rate_based_projects_progress";
		
		$fulltime_projects_active_disputes_tbl = $this->db->dbprefix."fulltime_projects_active_disputes";
		$fulltime_project_tbl = $this->db->dbprefix."projects_open_bidding";

		$fulltime_expired_project_tbl = $this->db->dbprefix."fulltime_projects_expired";
		$fulltime_cancelled_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled";
		$fulltime_cancelled_admin_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled_by_admin";
		
		
		$users_tbl = $this->db->dbprefix."users";
		
		
		$user_column_fields = ",initiator.account_type as initiator_account_type,initiator.first_name as initiator_first_name,initiator.last_name as initiator_last_name,initiator.company_name as initiator_company_name,disputee.account_type as disputee_account_type,disputee.first_name as disputee_first_name,disputee.last_name as disputee_last_name,disputee.company_name as disputee_company_name";
		
		$query = "";
		$query .= "(SELECT SQL_CALC_FOUND_ROWS   d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$fixed_budget_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_active_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fixed_budget_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'fixed' as project_types".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fixed_budget_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		
		// For hourly project
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$hourly_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$hourly_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		// For fulltime projects
		
		$query .= ") UNION (";
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$fulltime_project_tbl." as pd ON pd.project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		// for expired project

		$query .= ") UNION (";
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.fulltime_project_title as project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$fulltime_expired_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}

		// for cancelled  project by po

		$query .= ") UNION (";
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.fulltime_project_title as project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$fulltime_cancelled_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		// for cancelled  project by admin

		$query .= ") UNION (";
		
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,pd.fulltime_project_title as project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$fulltime_cancelled_admin_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date > NOW() ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}	
		
	
		
		$query .= ")";
		$query .= " ORDER BY dispute_start_date DESC LIMIT ".$rowno.",".$rowperpage;
		$projects_disputes_listing = $this->db->query($query)->result_array();
		return $projects_disputes_listing;
	}

	// Listing the awating arbitration projects disputes
	 public function awaiting_arbitration_projects_disputes_listing($rowno, $rowperpage, $search = '',$params){
		
		$fixed_minimum_required_admin_arbitration_value = $params['minimum_required_disputed_fixed_budget_project_value_for_admin_arbitration'];
		$hourly_minimum_required_admin_arbitration_value = $params['minimum_required_disputed_hourly_rate_based_project_value_for_admin_arbitration'];
		
		$fulltime_required_admin_arbitration_value = $params['minimum_required_disputed_fulltime_project_value_for_admin_arbitration'];
		
		
		
		$fixed_budget_projects_active_disputes_tbl = $this->db->dbprefix."fixed_budget_projects_active_disputes";
		$fixed_budget_projects_complete_tbl = $this->db->dbprefix."fixed_budget_projects_completed";
		$fixed_budget_projects_incomplete_tbl = $this->db->dbprefix."fixed_budget_projects_incomplete";
		$fixed_budget_projects_inprogress_tbl = $this->db->dbprefix."fixed_budget_projects_progress";
		
		$hourly_projects_active_disputes_tbl = $this->db->dbprefix."hourly_rate_based_projects_active_disputes";
		$hourly_projects_complete_tbl = $this->db->dbprefix."hourly_rate_based_projects_completed";
		$hourly_projects_incomplete_tbl = $this->db->dbprefix."hourly_rate_based_projects_incomplete";
		$hourly_projects_inprogress_tbl = $this->db->dbprefix."hourly_rate_based_projects_progress";
		
		
		$fulltime_projects_active_disputes_tbl = $this->db->dbprefix."fulltime_projects_active_disputes";
		$fulltime_project_tbl = $this->db->dbprefix."projects_open_bidding";
		$fulltime_expired_project_tbl = $this->db->dbprefix."fulltime_projects_expired";
		$fulltime_cancelled_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled";
		$fulltime_cancelled_admin_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled_by_admin";
		
		
		$users_tbl = $this->db->dbprefix."users";
		
		
		$user_column_fields = ",initiator.account_type as initiator_account_type,initiator.first_name as initiator_first_name,initiator.last_name as initiator_last_name,initiator.company_name as initiator_company_name,disputee.account_type as disputee_account_type,disputee.first_name as disputee_first_name,disputee.last_name as disputee_last_name,disputee.company_name as disputee_company_name";
		
		$query = "";
		$query .= "(SELECT SQL_CALC_FOUND_ROWS   d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_active_disputes_tbl ." as d";
		$query .= " JOIN ".$fixed_budget_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fixed_minimum_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_active_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fixed_budget_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fixed_minimum_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'fixed' as project_types".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fixed_budget_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fixed_minimum_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		
		
		// for hourly
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$hourly_minimum_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$hourly_minimum_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$hourly_minimum_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// for fulltime
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fulltime_project_tbl." as pd ON pd.project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fulltime_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
	
		// fulltime expired

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.fulltime_project_title as project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fulltime_expired_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fulltime_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// fulltime cancelled by po

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.fulltime_project_title as project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fulltime_cancelled_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fulltime_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}

		// fulltime cancelled by admin

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.disputed_amount,d.disputed_service_fees,d.dispute_negotiation_end_date,d.dispute_initiated_by_user_id,d.disputed_against_user_id,pd.fulltime_project_title as project_title,'fulltime' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_active_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fulltime_cancelled_admin_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_negotiation_end_date < NOW() AND  d.disputed_amount >= '".$fulltime_required_admin_arbitration_value."' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}	
		
		$query .= ")";
		
		$query .= " ORDER BY dispute_start_date DESC LIMIT ".$rowno.",".$rowperpage;
		$projects_disputes_listing = $this->db->query($query)->result_array();
		
		
		return $projects_disputes_listing;
	}	
   
   // Listing the auto closed projects disputes
	public function auto_closed_projects_disputes_listing($rowno, $rowperpage, $search = ''){
		
		
		$fixed_budget_projects_closed_disputes_tbl = $this->db->dbprefix."fixed_budget_projects_closed_disputes";
		$fixed_budget_projects_complete_tbl = $this->db->dbprefix."fixed_budget_projects_completed";
		$fixed_budget_projects_incomplete_tbl = $this->db->dbprefix."fixed_budget_projects_incomplete";
		$fixed_budget_projects_inprogress_tbl = $this->db->dbprefix."fixed_budget_projects_progress";
		
		$hourly_projects_closed_disputes_tbl = $this->db->dbprefix."hourly_rate_based_projects_closed_disputes";
		$hourly_projects_complete_tbl = $this->db->dbprefix."hourly_rate_based_projects_completed";
		$hourly_projects_incomplete_tbl = $this->db->dbprefix."hourly_rate_based_projects_incomplete";
		$hourly_projects_inprogress_tbl = $this->db->dbprefix."hourly_rate_based_projects_progress";
		
		$fulltime_projects_closed_disputes_tbl = $this->db->dbprefix."fulltime_projects_closed_disputes";
		$fulltime_project_tbl = $this->db->dbprefix."projects_open_bidding";

		$fulltime_expired_project_tbl = $this->db->dbprefix."fulltime_projects_expired";
		$fulltime_cancelled_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled";
		$fulltime_cancelled_admin_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled_by_admin";
		
		$users_tbl = $this->db->dbprefix."users";
		
		
		$user_column_fields = ",initiator.account_type as initiator_account_type,initiator.first_name as initiator_first_name,initiator.last_name as initiator_last_name,initiator.company_name as initiator_company_name,disputee.account_type as disputee_account_type,disputee.first_name as disputee_first_name,disputee.last_name as disputee_last_name,disputee.company_name as disputee_company_name";
		
		$query = "";
		$query .= "(SELECT SQL_CALC_FOUND_ROWS   d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_closed_disputes_tbl ." as d";
		$query .= " JOIN ".$fixed_budget_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fixed_budget_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'fixed' as project_types".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fixed_budget_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// Hourly
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'hourly' as project_type".$user_column_fields;
		$query .= " FROM ".$hourly_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$hourly_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'hourly' as project_type".$user_column_fields;
		$query .= " FROM ".$hourly_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$hourly_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'hourly' as project_type".$user_column_fields;
		$query .= " FROM ".$hourly_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$hourly_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// for Fulltime project
		
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.project_title,'fulltime' as project_type".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fulltime_project_tbl." as pd ON pd.project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// for expired project

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.fulltime_project_title as project_title,'fulltime' as project_type".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fulltime_expired_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// for cancelled project by po

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.fulltime_project_title as project_title,'fulltime' as project_type".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fulltime_cancelled_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// for cancelled project by admin

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,pd.fulltime_project_title as project_title,'fulltime' as project_type".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fulltime_cancelled_admin_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " WHERE  d.dispute_status = 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ")";
		$query .= " ORDER BY dispute_start_date DESC LIMIT ".$rowno.",".$rowperpage;
		$projects_disputes_listing = $this->db->query($query)->result_array();
		
		return $projects_disputes_listing;
	}
	
	// Listing the resolved projects disputes
	public function resolved_projects_disputes_listing($rowno, $rowperpage, $search = ''){
		
		
		$fixed_budget_projects_closed_disputes_tbl = $this->db->dbprefix."fixed_budget_projects_closed_disputes";
		$fixed_budget_projects_complete_tbl = $this->db->dbprefix."fixed_budget_projects_completed";
		$fixed_budget_projects_incomplete_tbl = $this->db->dbprefix."fixed_budget_projects_incomplete";
		$fixed_budget_projects_inprogress_tbl = $this->db->dbprefix."fixed_budget_projects_progress";
		
		$hourly_projects_closed_disputes_tbl = $this->db->dbprefix."hourly_rate_based_projects_closed_disputes";
		$hourly_projects_complete_tbl = $this->db->dbprefix."hourly_rate_based_projects_completed";
		$hourly_projects_incomplete_tbl = $this->db->dbprefix."hourly_rate_based_projects_incomplete";
		$hourly_projects_inprogress_tbl = $this->db->dbprefix."hourly_rate_based_projects_progress";
		
		$fulltime_projects_closed_disputes_tbl = $this->db->dbprefix."fulltime_projects_closed_disputes";
		$fulltime_project_tbl = $this->db->dbprefix."projects_open_bidding";

		$fulltime_expired_project_tbl = $this->db->dbprefix."fulltime_projects_expired";
		$fulltime_cancelled_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled";
		$fulltime_cancelled_admin_project_tbl = $this->db->dbprefix."fulltime_projects_cancelled_by_admin";		
		
		$users_tbl = $this->db->dbprefix."users";
		
		
		$user_column_fields = ",initiator.account_type as initiator_account_type,initiator.first_name as initiator_first_name,initiator.last_name as initiator_last_name,initiator.company_name as initiator_company_name,disputee.account_type as disputee_account_type,disputee.first_name as disputee_first_name,disputee.last_name as disputee_last_name,disputee.company_name as disputee_company_name,d.disputed_winner_id,dispute_winner.account_type as dispute_winner_account_type,dispute_winner.first_name as dispute_winner_first_name,dispute_winner.last_name as dispute_winner_last_name,dispute_winner.company_name as dispute_winner_company_name";
		
		$query = "";
		$query .= "(SELECT SQL_CALC_FOUND_ROWS   d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_closed_disputes_tbl ." as d";
		$query .= " JOIN ".$fixed_budget_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'fixed' as project_type".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_closed_disputes_tbl ." as d";
		
		$query .= " JOIN ".$fixed_budget_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'fixed' as project_types".$user_column_fields;
		$query .= " FROM ".$fixed_budget_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fixed_budget_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// hourly project
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_complete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_incomplete_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$hourly_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$hourly_projects_inprogress_tbl." as pd ON pd.project_id = d.disputed_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// For fulltime project
		
		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fulltime_project_tbl." as pd ON pd.project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		// for expired project

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.fulltime_project_title as project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		
		
		$query .= " JOIN ".$fulltime_expired_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}

		// for cancelled project by PO

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.fulltime_project_title as project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		$query .= " JOIN ".$fulltime_cancelled_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}

		// for cancelled project by admin

		$query .= ") UNION (";
		$query .= "SELECT    d.dispute_reference_id,d.dispute_start_date,d.dispute_end_date,d.disputed_amount,d.disputed_service_fees,d.dispute_status,pd.fulltime_project_title as project_title,'hourly' as project_types".$user_column_fields;
		$query .= " FROM ".$fulltime_projects_closed_disputes_tbl ." as d";
		$query .= " JOIN ".$fulltime_cancelled_admin_project_tbl." as pd ON pd.fulltime_project_id = d.disputed_fulltime_project_id";
		$query .= " JOIN ".$users_tbl." as initiator ON initiator.user_id = d.dispute_initiated_by_user_id";
		$query .= " JOIN ".$users_tbl." as disputee ON disputee.user_id = d.disputed_against_user_id";
		$query .= " JOIN ".$users_tbl." as dispute_winner ON dispute_winner.user_id = d.disputed_winner_id";
		$query .= " WHERE  d.dispute_status != 'automatic_decision' ";
		if(!empty($search)) {
			$query .= " AND (pd.fulltime_project_title LIKE '%".$search."%' OR  initiator.first_name LIKE '%".$search."%'  OR  initiator.last_name LIKE '%".$search."%' OR CONCAT(initiator.first_name,' ', initiator.last_name) LIKE '%".$search."%' OR  initiator.company_name LIKE '%".$search."%' OR  disputee.first_name LIKE '%".$search."%' OR  disputee.last_name LIKE '%".$search."%' OR CONCAT(disputee.first_name,' ', disputee.last_name) LIKE '%".$search."%' OR  disputee.company_name LIKE '%".$search."%'  OR  d.dispute_reference_id = '".$search."')";
		}
		
		
		$query .= ")";
		$query .= " ORDER BY dispute_start_date DESC LIMIT ".$rowno.",".$rowperpage;

		$projects_disputes_listing = $this->db->query($query)->result_array();
		/* echo "<pre>";
		print_r($projects_disputes_listing);
		die; */
		
		return $projects_disputes_listing;
	}
	
	// This method is used to store data into charged_service_fees table but not considered the affiliate income
	function insert_data_into_charged_service_fees_tracking_disputes($charged_service_fees_data, $released_escrow_data) {
		
		if($charged_service_fees_data['project_type'] != 'fulltime') {
			$po = $released_escrow_data['project_owner_id'];
			$sp = $released_escrow_data['winner_id'];
		} else {
			$po = $released_escrow_data['employer_id'];
			$sp = $released_escrow_data['employee_id'];
		}

		$service_fee_value_excl_vat = $charged_service_fees_data['charged_service_fee_value_excl_vat'];
		
		

		$charged_service_fees_data['charged_service_fee_net_value'] = $service_fee_value_excl_vat;

		if(!empty($charged_service_fees_data)) {
			$this->db->insert('projects_charged_service_fees_tracking', $charged_service_fees_data);
		}
	}
}
