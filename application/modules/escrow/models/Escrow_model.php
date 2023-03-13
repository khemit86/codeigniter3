<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}
class Escrow_model extends BaseModel
{
    public function __construct ()
    {
        return parent::__construct ();
		
    }
	
	
	/*
	This functions is used to count the either released escrow  for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the count of released escrow.
	
	*/
	public function get_released_escrows_count_project($project_type,$conditions){
	
		
		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		} else if($project_type == 'fulltime') {
			$released_escrow_table = 'fulltime_projects_released_escrows';
		}
		$escrow_count = $this->db->where($conditions)->from($released_escrow_table)->count_all_results();
		return $escrow_count;
		
	}

	/*
	This functions is used to count the active escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the count of acive escrow.
	
	*/
	public function get_active_escrows_count_project($project_type,$conditions){
	
		
		if($project_type == 'fixed'){
			$active_escrow_table = 'fixed_budget_projects_active_escrows';
		} else if($project_type == 'hourly') {
			$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
		} else if($project_type == 'fulltime') {
			$active_escrow_table = 'fulltime_projects_active_escrows';
		}
		$escrow_count = $this->db->where($conditions)->from($active_escrow_table)->count_all_results();
		return $escrow_count;
		
	}
	
	/*
	This functions is used to count the cancelled escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the count of acive escrow.
	
	*/
	public function get_cancelled_escrows_count_project($project_type,$conditions){
	
		
		if($project_type == 'fixed'){
			$active_escrow_table = 'fixed_budget_projects_cancelled_escrows_tracking';
		} else if($project_type == 'hourly') {
			$active_escrow_table = 'hourly_rate_based_projects_cancelled_escrows_tracking';
		} else if($project_type == 'fulltime') {
			$active_escrow_table = 'fulltime_projects_cancelled_escrows_tracking';
		}
		$escrow_count = $this->db->where($conditions)->from($active_escrow_table)->count_all_results();
		return $escrow_count;
		
	}
	
	/*
	This functions is used to count the requested escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested escrow.
	
	*/
	public function get_requested_escrows_count_project($project_type,$conditions){
		
		if($project_type == 'fixed'){
			$requested_escrow_table = 'fixed_budget_projects_requested_escrows';
		} else if($project_type == 'hourly') {
			$requested_escrow_table = 'hourly_rate_based_projects_requested_escrows';
		} else if($project_type == 'fulltime') {
			$requested_escrow_table = 'fulltime_projects_requested_escrows';
		}
		
		$requested_escrow_count = $this->db->where($conditions)->from($requested_escrow_table)->count_all_results();
		return $requested_escrow_count;
		
	}
	
	/*
	This functions is used to count the rejected requested escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested escrow.
	
	*/
	public function get_rejected_requested_escrows_count_project($project_type,$conditions){
		if($project_type == 'fixed'){
			$rejected_requested_escrow_table = 'fixed_budget_projects_rejected_requested_escrows';
		} else if($project_type == 'hourly') {
			$rejected_requested_escrow_table = 'hourly_rate_based_projects_rejected_requested_escrows';
		} else if ($project_type == 'fulltime') {
			$rejected_requested_escrow_table = 'fulltime_projects_rejected_requested_escrows';
		}
		if(!empty($rejected_requested_escrow_table)) {
			$rejected_requested_escrow_count = $this->db->where($conditions)->from($rejected_requested_escrow_table)->count_all_results();
		}
		return $rejected_requested_escrow_count;
		
	}
	
	/*
	This functions is used to sum the requested escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested escrow.
	*/
	public function get_sum_requested_escrows_amount_project($project_type,$conditions){
		if($project_type == 'fixed'){
			$requested_escrow_table = 'fixed_budget_projects_requested_escrows';
		} else if($project_type == 'hourly') {
			$requested_escrow_table = 'hourly_rate_based_projects_requested_escrows';
		} else if($project_type == 'fulltime') {
			$requested_escrow_table = 'fulltime_projects_requested_escrows';
		}
		$this->db->select('SUM(requested_escrow_amount) as sum_requested_escrow_amount_value');
		$this->db->from($requested_escrow_table);
		$this->db->where($conditions);
		$sum_requested_escrow_result = $this->db->get();
		$sum_requested_escrow = $sum_requested_escrow_result->row_array();
		return $sum_requested_escrow['sum_requested_escrow_amount_value'];
	}
	
	
	
	
	/*
	This functions is used to sum the requested escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested escrow.
	*/
	public function get_sum_rejected_requested_escrow_amounts_project($project_type,$conditions){
		if($project_type == 'fixed'){
			$rejected_requested_escrow_table = 'fixed_budget_projects_rejected_requested_escrows';
		} else if($project_type == 'hourly') {
			$rejected_requested_escrow_table = 'hourly_rate_based_projects_rejected_requested_escrows';
		} else if($project_type == 'fulltime') {
			$rejected_requested_escrow_table = 'fulltime_projects_rejected_requested_escrows';
		}
		$this->db->select('SUM(requested_escrow_amount) as sum_rejected_requested_escrow_amount_value');
		$this->db->from($rejected_requested_escrow_table);
		$this->db->where($conditions);
		$sum_rejected_requested_escrow_result = $this->db->get();
		$sum_rejected_requested_escrow_result = $sum_rejected_requested_escrow_result->row_array();
		return $sum_rejected_requested_escrow_result['sum_rejected_requested_escrow_amount_value'];
	}
	
	
	
	/*
	This functions is used to sum the active escrow for project owner
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_active_escrow_amounts_project_po($project_type,$conditions){
		if($project_type == 'fixed'){
			$active_escrow_table = 'fixed_budget_projects_active_escrows';
		} else if($project_type == 'hourly') {
			$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
		} else if($project_type == 'fulltime') {
			$active_escrow_table = 'fulltime_projects_active_escrows';
		}
		$this->db->select('SUM(total_escrow_payment_value) as sum_escrow_amount_value');
		$this->db->from($active_escrow_table);
		$this->db->where($conditions);
		$sum_active_escrow_result = $this->db->get();
		$sum_active_escrow_result = $sum_active_escrow_result->row_array();
		return $sum_active_escrow_result['sum_escrow_amount_value'];
	}
	
	
	/*
		This functions is used to sum the active escrow for Service Provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_active_escrow_amounts_project_sp($project_type,$conditions){
		if($project_type == 'fixed'){
			$active_escrow_table = 'fixed_budget_projects_active_escrows';
		} else if($project_type == 'hourly') {
			$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
		} else if($project_type == 'fulltime') {
			$active_escrow_table = 'fulltime_projects_active_escrows';
		}
		$this->db->select('SUM(created_escrow_amount) as sum_escrow_amount_value');
		$this->db->from($active_escrow_table);
		$this->db->where($conditions);
		$sum_active_escrow_result = $this->db->get();
		$sum_active_escrow_result = $sum_active_escrow_result->row_array();
		return $sum_active_escrow_result['sum_escrow_amount_value'];
	}
	
	
	/*
	This functions is used to sum the cancelled escrow for project owner
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the cancelled escrow.
	*/
	public function get_sum_cancelled_escrow_amounts_project_po($project_type,$conditions){
		if($project_type == 'fixed'){
			$cancelled_escrow_table = 'fixed_budget_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
		} else if($project_type == 'hourly') {
			$cancelled_escrow_table = 'hourly_rate_based_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';	
		} else if($project_type == 'fulltime') {
			$cancelled_escrow_table = 'fulltime_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'fulltime_projects_closed_disputes_employer_reverted_amounts';
		}
		$this->db->select('SUM(total_reverted_escrow_payment_value) as sum_reverted_amount_value');
		$this->db->from($cancelled_escrow_table);
		$this->db->where($conditions);
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow_result = $sum_cancelled_escrow_result->row_array();
		$total = $sum_cancelled_escrow_result['sum_reverted_amount_value'];

		if($project_type == 'fixed' || $project_type == 'hourly'){
			$this->db->select('SUM(reverted_total_amount) as sum_reverted_disputed_amount_value');
			$this->db->from($cancelled_escrow_closed_dispute_reverted_po_table);
			$this->db->where(['disputed_project_id'=>$conditions['project_id'],'po_id'=>$conditions['project_owner_id'],'sp_id'=>$conditions['winner_id']]);
			$sum_disputed_cancelled_reverted_escrow_result = $this->db->get();
			$sum_disputed_cancelled_reverted_escrow_result = $sum_disputed_cancelled_reverted_escrow_result->row_array();
			$total+= $sum_disputed_cancelled_reverted_escrow_result['sum_reverted_disputed_amount_value'];
		}if($project_type == 'fulltime'){
			$this->db->select('SUM(reverted_total_amount) as sum_reverted_disputed_amount_value');
			$this->db->from($cancelled_escrow_closed_dispute_reverted_po_table);
			$this->db->where(['disputed_fulltime_project_id'=>$conditions['project_id'],'employer_id'=>$conditions['project_owner_id'],'employee_id'=>$conditions['winner_id']]);
			$sum_disputed_cancelled_reverted_escrow_result = $this->db->get();
			$sum_disputed_cancelled_reverted_escrow_result = $sum_disputed_cancelled_reverted_escrow_result->row_array();
			$total+= $sum_disputed_cancelled_reverted_escrow_result['sum_reverted_disputed_amount_value'];
		}
		
		return $total;
	}
	
	
	/*
		This functions is used to cancelled the active escrow for Service Provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_cancelled_escrow_amounts_project_sp($project_type,$conditions){
		if($project_type == 'fixed'){
			$cancelled_escrow_table = 'fixed_budget_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';
		} else if($project_type == 'hourly') {
			$cancelled_escrow_table = 'hourly_rate_based_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';	
		} else if($project_type == 'fulltime') {
			$cancelled_escrow_table = 'fulltime_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'fulltime_projects_closed_disputes_employer_reverted_amounts';
			
		}
		$this->db->select('SUM(reverted_escrowed_amount) as sum_reverted_amount_value');
		$this->db->from($cancelled_escrow_table);
		$this->db->where($conditions);
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow_result = $sum_cancelled_escrow_result->row_array();

		$total = $sum_cancelled_escrow_result['sum_reverted_amount_value'];
		if($project_type == 'fixed' || $project_type == 'hourly'){
			$this->db->select('SUM(reverted_amount) as sum_reverted_disputed_amount_value');
			$this->db->from($cancelled_escrow_closed_dispute_reverted_po_table);
			$this->db->where(['disputed_project_id'=>$conditions['project_id'],'po_id'=>$conditions['project_owner_id'],'sp_id'=>$conditions['winner_id']]);
			$sum_disputed_cancelled_reverted_escrow_result = $this->db->get();
			$sum_disputed_cancelled_reverted_escrow_result = $sum_disputed_cancelled_reverted_escrow_result->row_array();
			$total+= $sum_disputed_cancelled_reverted_escrow_result['sum_reverted_disputed_amount_value'];
		}
		if($project_type == 'fulltime'){
			$this->db->select('SUM(reverted_amount) as sum_reverted_disputed_amount_value');
			$this->db->from($cancelled_escrow_closed_dispute_reverted_po_table);
			$this->db->where(['disputed_fulltime_project_id'=>$conditions['project_id'],'employer_id'=>$conditions['project_owner_id'],'employee_id'=>$conditions['winner_id']]);
			$sum_disputed_cancelled_reverted_escrow_result = $this->db->get();
			$sum_disputed_cancelled_reverted_escrow_result = $sum_disputed_cancelled_reverted_escrow_result->row_array();
			$total+= $sum_disputed_cancelled_reverted_escrow_result['sum_reverted_disputed_amount_value'];
		}
		
		return $total;
	}
	
	
	/*
	This functions is used to sum the paid escrow for project owner
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_released_escrow_amounts_project_po($project_type,$conditions){
		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		} else if($project_type == 'fulltime') {
			$released_escrow_table = 'fulltime_projects_released_escrows';
		}
		$this->db->select('SUM(total_escrow_payment_value) as sum_escrow_amount_value');
		$this->db->from($released_escrow_table);
		$this->db->where($conditions);
		$sum_released_escrow_result = $this->db->get();
		$sum_released_escrow_result = $sum_released_escrow_result->row_array();
		return $sum_released_escrow_result['sum_escrow_amount_value'];
		
	}
	
	/*
	This functions is used to sum the (business charges only) paid escrow for project owner
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_released_escrow_service_fees_charges_amount_project_po($project_type,$conditions){
		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		} else if($project_type == 'fulltime') {
			$released_escrow_table = 'fulltime_projects_released_escrows';
		}
		$this->db->select('SUM(service_fee_charges) as sum_escrow_bussiness_fee_charges');
		$this->db->from($released_escrow_table);
		$this->db->where($conditions);
		$sum_released_escrow_result = $this->db->get();
		$sum_released_escrow_result = $sum_released_escrow_result->row_array();
		return $sum_released_escrow_result['sum_escrow_bussiness_fee_charges'];
	}
	
	/*
	This functions is used to sum the paid escrow for service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_released_escrow_amounts_project_sp($project_type,$conditions){

		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		} else if($project_type == 'fulltime') {
			$released_escrow_table = 'fulltime_projects_released_escrows';
		}
	
		
		$check_released_escrow_exists = $this->db->where($conditions)->from($released_escrow_table)->count_all_results();
		$sum_released_escrow_result['sum_escrow_amount_value'] = 0;
		if($check_released_escrow_exists > 0){
			$this->db->select('SUM(released_escrow_payment_amount) as sum_escrow_amount_value');
			$this->db->from($released_escrow_table);
			$this->db->where($conditions);
			$sum_released_escrow_result = $this->db->get();
			$sum_released_escrow_result = $sum_released_escrow_result->row_array();
		}
		return $sum_released_escrow_result['sum_escrow_amount_value'];
		
	}
	
	/*
	This functions is used to sum the paid escrow for project owner/service provider
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_sum_released_escrows_amount_project($project_type,$conditions){
		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		}
		$this->db->select('SUM(released_escrow_payment_amount) as sum_escrow_amount_value');
		$this->db->from($released_escrow_table);
		$this->db->where($conditions);
		$sum_released_escrow_result = $this->db->get();
		$sum_released_escrow_result = $sum_released_escrow_result->row_array();
		return $sum_released_escrow_result['sum_escrow_amount_value'];
	}
	
	/*
	This functions is used to fetch the requested escrow
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested escrow.
	*/
	public function get_all_requested_escrows_listing_project($project_type,$conditions,$start,$limit){
		
		if($project_type == 'fixed'){
			$requested_escrow_table = 'fixed_budget_projects_requested_escrows';
		} else if($project_type == 'hourly') {
			$requested_escrow_table = 'hourly_rate_based_projects_requested_escrows';
		} else if($project_type == 'fulltime') {
			$requested_escrow_table = 'fulltime_projects_requested_escrows';
		}
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS rm.*', false);
		$this->db->from($requested_escrow_table.' rm');
		$this->db->where($conditions);
		if($project_type == 'fulltime') {
			$this->db->order_by('rm.escrow_requested_by_employee_date','desc');
		} else {
			$this->db->order_by('rm.escrow_requested_by_sp_date','desc');
		}
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$requested_escrow_result = $this->db->get();
		$requested_escrow_data = $requested_escrow_result->result_array();

		if($project_type == 'fulltime') {
			foreach($requested_escrow_data as &$value) {
				$value['winner_id'] = $value['employee_id'];
				$value['project_owner_id'] = $value['employer_id'];
				$value['project_id'] = $value['fulltime_project_id'];
				$value['escrow_requested_by_sp_date'] = $value['escrow_requested_by_employee_date'];
			}
		}
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $requested_escrow_data, 'total' => $total_rec];
		
	}
	
		/*
	This functions is used to fetch the rejected requested escrow
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the requested escrow.
	*/
	public function get_rejected_requested_escrows_listing_project($project_type,$conditions,$start,$limit){
		
		if($project_type == 'fixed'){
			$rejected_requested_escrow_table = 'fixed_budget_projects_rejected_requested_escrows';
		} else if($project_type == 'hourly') {
			$rejected_requested_escrow_table = 'hourly_rate_based_projects_rejected_requested_escrows';
		} else if($project_type == 'fulltime') {
			$rejected_requested_escrow_table = 'fulltime_projects_rejected_requested_escrows';
		}
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS rrm.*', false);
		$this->db->from($rejected_requested_escrow_table.' rrm');
		$this->db->where($conditions);
		$this->db->order_by('rrm.requested_escrow_rejection_date','desc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$rejected_requested_escrow_result = $this->db->get();
		$rejected_requested_escrow_data = $rejected_requested_escrow_result->result_array();

		if($project_type == 'fulltime') {
			foreach($rejected_requested_escrow_data as &$value) {
				$value['project_id'] = $value['fulltime_project_id'];
				$value['winner_id'] = $value['employee_id'];
				$value['project_owner_id'] = $value['employer_id'];
				$value['escrow_requested_by_sp_date'] = $value['escrow_requested_by_employee_date'];
			}
		}
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $rejected_requested_escrow_data, 'total' => $total_rec];
		
	}
	
	/*
	This functions is used to fetch the active escrow
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the active escrow.
	*/
	public function get_active_escrows_listing_project($project_type,$conditions,$start,$limit){
		
		if($project_type == 'fixed'){
			$active_escrow_table = 'fixed_budget_projects_active_escrows';
		} else if($project_type == 'hourly') {
			$active_escrow_table = 'hourly_rate_based_projects_active_escrows';
		} else if($project_type == 'fulltime') {
			$active_escrow_table = 'fulltime_projects_active_escrows';
		}
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS am.*', false);
		$this->db->from($active_escrow_table.' am');
		$this->db->where($conditions);
		$this->db->order_by('am.escrow_creation_date','desc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$active_escrow_result = $this->db->get();
		$active_escrow_data = $active_escrow_result->result_array();

		if($project_type == 'fulltime') {
			foreach($active_escrow_data as &$value) {
				$value['winner_id'] = $value['employee_id'];
				$value['project_owner_id'] = $value['employer_id'];
				$value['project_id'] = $value['fulltime_project_id'];
				$value['is_sp_requested_release'] = $value['is_employee_requested_release'];
				$value['sp_requested_release_date'] = $value['employee_requested_release_date'];
				$value['escrow_creation_requested_by_sp'] = $value['escrow_creation_requested_by_employee'];
				$value['escrow_creation_requested_by_sp_date'] = $value['escrow_creation_requested_by_employee_date'];
			}
		}
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $active_escrow_data, 'total' => $total_rec];
		
	}
	
	/*
	This functions is used to fetch the cancelled escrow
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the cancelled escrow.
	*/
	public function get_cancelled_escrows_listing_project($project_type,$conditions,$start,$limit){
		
     /*   $start = 0;
		$limit = 20;

	
 */
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}

		if($project_type == 'fixed'){
			$cancelled_escrow_table = 'fixed_budget_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'fixed_budget_projects_closed_disputes_po_reverted_amounts';

			$cancelled_escrow_fields = 'cm.project_id,cm.project_owner_id,cm.winner_id,cm.cancelled_escrow_description,cm.reverted_escrowed_amount,cm.reverted_service_fee_charges,cm.total_reverted_escrow_payment_value,cm.initial_escrow_creation_date,cm.escrow_cancellation_date,cm.cancelled_via_dispute,cm.dispute_reference_id';

			$cancelled_disputed_escrow_po_reverted_amount_fields = 'cm.disputed_project_id as project_id,cm.po_id as project_owner_id,cm.sp_id as winner_id,"" as cancelled_escrow_description,cm.reverted_amount as reverted_escrowed_amount,cm.reverted_service_fee_amount as reverted_service_fee_charges,cm.reverted_total_amount as total_reverted_escrow_payment_value,"" as initial_escrow_creation_date,cm.dispute_close_date as escrow_cancellation_date,"Y" as cancelled_via_dispute,cm.dispute_reference_id';

			

		} else if($project_type == 'hourly') {
			$cancelled_escrow_table = 'hourly_rate_based_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'hourly_rate_projects_closed_disputes_po_reverted_amounts';	

			$cancelled_escrow_fields = 'cm.project_id,cm.project_owner_id,cm.winner_id,cm.cancelled_escrow_description,cm.reverted_escrow_considered_number_of_hours,cm.reverted_escrow_considered_hourly_rate,cm.reverted_escrowed_amount,cm.reverted_service_fee_charges,cm.total_reverted_escrow_payment_value,cm.initial_escrow_creation_date,cm.escrow_cancellation_date,cm.cancelled_via_dispute,cm.dispute_reference_id';


			$cancelled_disputed_escrow_po_reverted_amount_fields = 'cm.disputed_project_id as project_id,cm.po_id as project_owner_id,cm.sp_id as winner_id,"" as cancelled_escrow_description,"0" as reverted_escrow_considered_number_of_hours,"0" as reverted_escrow_considered_hourly_rate,cm.reverted_amount as reverted_escrowed_amount,cm.reverted_service_fee_amount as reverted_service_fee_charges,cm.reverted_total_amount as total_reverted_escrow_payment_value,"" as initial_escrow_creation_date,cm.dispute_close_date as escrow_cancellation_date,"Y" as cancelled_via_dispute,cm.dispute_reference_id';

		} else if($project_type == 'fulltime') {
			$cancelled_escrow_table = 'fulltime_projects_cancelled_escrows_tracking';
			$cancelled_escrow_closed_dispute_reverted_po_table = 'fulltime_projects_closed_disputes_employer_reverted_amounts';	
			
			 
			//$cancelled_escrow_fields = 'cm.fulltime_project_id as project_id,cm.employer_id as project_owner_id, cm.employee_id as winner_id,cm.cancelled_escrow_description,cm.reverted_escrowed_amount,cm.reverted_service_fee_charges,cm.total_reverted_escrow_payment_value,cm.initial_escrow_creation_date,cm.escrow_cancellation_date,cm.cancelled_via_dispute,cm.dispute_reference_id';

			$cancelled_escrow_fields = 'cm.fulltime_project_id,cm.employer_id as project_owner_id,cm.employee_id as winner_id,cm.cancelled_escrow_description,cm.reverted_escrowed_amount,cm.reverted_service_fee_charges,cm.total_reverted_escrow_payment_value,cm.initial_escrow_creation_date,cm.escrow_cancellation_date,cm.cancelled_via_dispute,cm.dispute_reference_id';

			$cancelled_disputed_escrow_po_reverted_amount_fields = 'cm.disputed_fulltime_project_id as project_id,cm.employer_id as project_owner_id,cm.employee_id as winner_id,"" as cancelled_escrow_description,cm.reverted_amount as reverted_escrowed_amount,cm.reverted_service_fee_amount as reverted_service_fee_charges,cm.reverted_total_amount as total_reverted_escrow_payment_value,"" as initial_escrow_creation_date,cm.dispute_close_date as escrow_cancellation_date,"Y" as cancelled_via_dispute,cm.dispute_reference_id';
			
			
			

		}

		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS '.$cancelled_escrow_fields, false);
		$this->db->from($cancelled_escrow_table.' cm');
		$this->db->where($conditions);
		$cancelled_escrow_query = $this->db->get_compiled_select();

		$this->db->select($cancelled_disputed_escrow_po_reverted_amount_fields);
		$this->db->from($cancelled_escrow_closed_dispute_reverted_po_table.' cm');
		
		if($project_type == 'fulltime') {
			$this->db->where(['disputed_fulltime_project_id'=>$conditions['project_id'],'employer_id'=>$conditions['project_owner_id'],'employee_id'=>$conditions['winner_id']]);
		}else{
			$this->db->where(['disputed_project_id'=>$conditions['project_id'],'po_id'=>$conditions['project_owner_id'],'sp_id'=>$conditions['winner_id']]);
		}
		$cancelled_disputed_escrow_po_reverted_query = $this->db->get_compiled_select();


		$union_table_name = [
			$cancelled_escrow_query,
			$cancelled_disputed_escrow_po_reverted_query
		];

		$result_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_cancellation_date DESC LIMIT '.$limit_range);
		$cancelled_escrow_data  = $result_query->result_array();
		
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $cancelled_escrow_data, 'total' => $total_rec];
		
	}
	
	/*
	This functions is used to fetch the released escrow
	If $project_type is either fixed/hourly/fulltime.
	If $condition = condition array for fetch the released escrow.
	*/
	public function get_released_escrows_listing_project($project_type,$conditions,$start,$limit){
		
		if($project_type == 'fixed'){
			$released_escrow_table = 'fixed_budget_projects_released_escrows';
		} else if($project_type == 'hourly') {
			$released_escrow_table = 'hourly_rate_based_projects_released_escrows';
		} else if ($project_type == 'fulltime') {
			$released_escrow_table = 'fulltime_projects_released_escrows';
		}
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS pm.*', false);
		$this->db->from($released_escrow_table.' pm');
		$this->db->where($conditions);
		$this->db->order_by('pm.escrow_payment_release_date','desc');
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		$released_escrow_result = $this->db->get();
		$released_escrow_data = $released_escrow_result->result_array();

		if ($project_type == 'fulltime') {
			foreach($released_escrow_data as &$value) {
				$value['project_id'] = $value['fulltime_project_id'];
				$value['project_owner_id'] = $value['employer_id'];
				$value['winner_id'] = $value['employee_id'];
				$value['escrow_payment_requested_by_sp'] = $value['escrow_payment_requested_by_employee'];
				$value['escrow_payment_requested_by_sp_date'] = $value['escrow_payment_requested_by_employee_date'];
			}
		}

		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		 return ['data' => $released_escrow_data, 'total' => $total_rec];
		
	}
	
	/*
	This function is used to validate the create milestobe request form by service provider
	*/
	
	public function create_escrow_request_validation_sp($post_data,$project_detail){
		$min_salary_amount = $this->config->item('project_details_page_min_salary_amount');
		
		$i = 0;
		if($project_detail['project_type'] != 'hourly') {
			if (empty($post_data['escrow_request_amount'])){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_request_amount';
				if($project_detail['project_type'] == 'fulltime'){
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_amount_validation_fulltime_project_escrow_form_message');
				}else{
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_escrow_amount_validation_message');
				}
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}
			if(!empty($post_data['escrow_request_amount']) && $post_data['escrow_request_amount'] == 0){
				if(substr(str_replace(" ","",$post_data['escrow_request_amount']),0,1) == 0){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'escrow_request_amount';
					if($project_detail['project_type'] == 'fulltime'){
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_invalid_milestone_amount_validation_fulltime_project_milestone_form_message');
					}else{
						$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_invalid_escrow_amount_validation_message');
					}
					
					$msg['errors'][$i]['error_class'] = 'invalid_amount';
					$i ++;
				}
			}
			
			
			
			
			if($project_detail['project_type'] == 'fulltime' && (!empty($post_data['escrow_request_amount']) && $post_data['escrow_request_amount'] > 0 && str_replace(" ","",$post_data['escrow_request_amount']) < $min_salary_amount && ($project_detail['confidential_dropdown_option_selected'] == 'Y' || $project_detail['not_sure_dropdown_option_selected'] == 'Y'))){
				
				
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_request_amount';
				$error_message = $this->config->item('project_details_page_minimum_required_salary_amount_validation_fulltime_project_create_escrow_form_message');
				$error_message = str_replace("{project_minimum_salay_amount}", str_replace(".00","",number_format($min_salary_amount,  2, '.', ' ')), $error_message);
				$msg['errors'][$i]['message'] = $error_message;
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}
		}
		if($project_detail['project_type'] == 'hourly' && empty($post_data['escrow_request_hourly_rate'])) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_request_hourly_rate';
			if($project_detail['project_type'] == 'hourly'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_hourly_rate_validation_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		} else if($project_detail['project_type'] == 'hourly' && isset($post_data['sp_hourly_rate']) && $post_data['sp_hourly_rate'] == 0 && !empty($post_data['escrow_request_hourly_rate'])) {
			$min_rate = $this->config->item('project_details_page_min_hourly_rate_value');
			if($min_rate > str_replace(" ","",$post_data['escrow_request_hourly_rate'])) {
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_request_hourly_rate';
				$error_message = $this->config->item('project_details_page_minimum_required_bid_amount_validation_hourly_project_bid_form_message');
				$error_message = str_replace("{project_minimum_hourly_bid_amount}", str_replace(".00","",number_format($min_rate, 0, '', ' ')), $error_message);
				$msg['errors'][$i]['message'] = $error_message;
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}
		}

		if($project_detail['project_type'] == 'hourly' && empty($post_data['escrow_request_no_of_hours'])) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_request_no_of_hours';
			if($project_detail['project_type'] == 'hourly'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_number_of_hours_validation_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		} 
		
		
		if(!empty($post_data['escrow_description']) && $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') && $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') != 0 &&  strlen($post_data['escrow_description']) < $this->config->item('escrow_description_minimum_length_character_limit_escrow_form')){
		
			
			if($project_detail['project_type'] == 'fulltime' && $this->config->item('project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message')){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message');
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_description';
				$msg['errors'][$i]['error_class'] = 'min_length';
				$i ++;
			}else if($project_detail['project_type'] != 'fulltime' && $this->config->item('project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message')){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message');
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_description';
				$msg['errors'][$i]['error_class'] = 'min_length';
				$i ++;
			}
			
		}
	
		if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
	
	}
	
	/*
	This function is used to validate the create milestobe request form by service provider
	*/
	public function create_escrow_validation_po($post_data,$project_detail,$section_id,$section_name){
		
		
		$project_owner_data = $this->db // get the user detail
		->select('u.user_id,ud.user_account_balance')
		->from('users u')
		->join('users_details ud', 'u.user_id = ud.user_id', 'left')
		->where('ud.user_id', $project_detail['project_owner_id'])
		->get()->row_array();
		$min_salary_amount = $this->config->item('project_details_page_min_salary_amount');	
		
		$i = 0;
		if ($project_detail['project_type'] != 'hourly' && empty($post_data['escrow_amount'])){
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_amount_'.$section_name.'_'.$section_id;
			if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_amount_validation_fulltime_project_escrow_form_message');
			}else{
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_escrow_amount_validation_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}
		if(!empty($post_data['escrow_amount']) && $post_data['escrow_amount'] == 0){
			if(substr(str_replace(" ","",$post_data['escrow_amount']),0,1) == 0){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_amount_'.$section_name.'_'.$section_id;;
				if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_invalid_milestone_amount_validation_fulltime_project_milestone_form_message');
				}else{
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_invalid_escrow_amount_validation_message');
				}
				
				$msg['errors'][$i]['error_class'] = 'invalid_amount';
				$i ++;
			}
		}
		if(!empty($post_data['escrow_amount']) && str_replace(" ","",$post_data['escrow_amount']) > 0 && (str_replace(" ","",$post_data['escrow_amount'])+ str_replace(" ","",$post_data['service_fee'])) > $project_owner_data['user_account_balance']){
			
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_amount_'.$section_name.'_'.$section_id;
			$msg['errors'][$i]['message'] = $this->config->item('project_details_page_po_not_sufficient_balance_validation_project_escrow_form_message');
			$msg['errors'][$i]['error_class'] = 'not_sufficient_account_balance';
			$i ++;
		}

		if($project_detail['project_type'] == 'fulltime' && (!empty($post_data['escrow_amount']) && str_replace(" ","",$post_data['escrow_amount']) > 0 && str_replace(" ","",$post_data['escrow_amount']) < $min_salary_amount)){
			
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_amount_'.$section_name.'_'.$section_id;
			$error_message = $this->config->item('project_details_page_minimum_required_salary_amount_validation_fulltime_project_create_escrow_form_message');
			$error_message = str_replace("{project_minimum_salay_amount}", str_replace(".00","",number_format($min_salary_amount,  2, '.', ' ')), $error_message);
			$msg['errors'][$i]['message'] = $error_message;
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		}

		if($project_detail['project_type'] == 'hourly' && empty($post_data['escrow_request_hourly_rate'])) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_request_hourly_rate';
			if($project_detail['project_type'] == 'hourly'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_hourly_rate_validation_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		} else if($project_detail['project_type'] == 'hourly' && isset($post_data['sp_hourly_rate']) && $post_data['sp_hourly_rate'] == 0 && !empty($post_data['escrow_request_hourly_rate'])) {
			$min_rate = $this->config->item('project_details_page_min_hourly_rate_value');
			if($min_rate > str_replace(" ","",$post_data['escrow_request_hourly_rate'])) {
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_request_hourly_rate';
				$error_message = $this->config->item('project_details_page_minimum_required_bid_amount_validation_hourly_project_bid_form_message');
				$error_message = str_replace("{project_minimum_hourly_bid_amount}", str_replace(".00","",number_format($min_rate, 0, '', ' ')), $error_message);
				$msg['errors'][$i]['message'] = $error_message;
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}
		}

		if($project_detail['project_type'] == 'hourly' && empty($post_data['escrow_request_no_of_hours'])) {
			$msg['status'] = 'FAILED';
			$msg['errors'][$i]['id'] = 'escrow_request_no_of_hours';
			if($project_detail['project_type'] == 'hourly'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_create_escrow_form_number_of_hours_validation_message');
			}
			$msg['errors'][$i]['error_class'] = 'required';
			$i ++;
		} 
		
		if(!empty($post_data['escrow_description'])){
		
			
			if($project_detail['project_type'] == 'fulltime' && $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') && $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') != 0 && strlen($post_data['escrow_description']) < $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') && $this->config->item('project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message')){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message');
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_description_'.$section_name.'_'.$section_id;;
				$msg['errors'][$i]['error_class'] = 'min_length';
				$i ++;
			}else if($project_detail['project_type'] != 'fulltime' && $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') && $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') != 0 && strlen($post_data['escrow_description']) < $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') && $this->config->item('project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message')){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message');
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'escrow_description_'.$section_name.'_'.$section_id;;
				$msg['errors'][$i]['error_class'] = 'min_length';
				$i ++;
			}
			
		}
		
		
		if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
	
	}
	
	/*
		This function is used to validate the partial release escrow form 
	*/
	public function partial_release_escrow_validation_po($post_data,$project_detail,$escrow_data){
		
		$i = 0;
		if(in_array($project_detail['project_type'], ['fixed', 'fulltime'])) {
			if (empty($post_data['partial_escrow_amount'])){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_amount';
				if($project_detail['project_type'] == 'fulltime'){
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_amount_validation_fulltime_project_escrow_form_message');
				}else{
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_escrow_amount_validation_message');
				}
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}
			if(!empty($post_data['milestone_amount']) && $post_data['partial_escrow_amount'] == 0){
				if(substr(str_replace(" ","",$post_data['partial_escrow_amount']),0,1) == 0){
					$msg['status'] = 'FAILED';
					$msg['errors'][$i]['id'] = 'partial_escrow_amount';
					if($project_detail['project_type'] == 'fulltime'){
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_invalid_escrow_amount_validation_fulltime_project_escrow_form_message');
					}else{
						$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_invalid_escrow_amount_validation_message');
					}
					
					$msg['errors'][$i]['error_class'] = 'invalid_amount';
					$i ++;
				} 
			}
			if(str_replace(" ","",$post_data['partial_escrow_amount']) > $escrow_data['created_escrow_amount']){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_amount';
				if($project_detail['project_type'] == 'fulltime'){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_partial_escrow_greater_then_amount_validation_fulltime_project_escrow_form_message');
				}else{
					$msg['errors'][$i]['message'] = $this->config->item('project_details_page_fixed_budget_project_create_escrow_form_partial_escrow_greater_then_amount_validation_message');
				}
				
				$msg['errors'][$i]['error_class'] = 'maximum_amount';
				$i ++;
			
			}
		} else if($project_detail['project_type'] == 'hourly') {
			if (empty($post_data['partial_escrow_number_of_hours'])){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_number_of_hours';
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_number_of_hours_validation_message_po_view');
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			} else if ((!empty($post_data['partial_escrow_number_of_hours']) && str_replace(" ","",$post_data['partial_escrow_number_of_hours']) > $escrow_data['escrow_considered_number_of_hours'])){
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_number_of_hours';
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_invalid_number_of_hours_validation_message_po_view');
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			} else if(isset($post_data['partial_escrow_number_of_hours']) && $post_data['partial_escrow_number_of_hours'] == 0) {
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_number_of_hours';
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_hourly_rate_based_project_partial_release_escrow_form_invalid_number_of_hours_validation_message_po_view');
				$msg['errors'][$i]['error_class'] = 'required';
				$i ++;
			}

		}
		
		if(!empty($post_data['partial_escrow_description']) &&  $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') &&  $this->config->item('escrow_description_minimum_length_character_limit_escrow_form') != 0 && strlen($post_data['partial_escrow_description']) < $this->config->item('escrow_description_minimum_length_character_limit_escrow_form')){
		
			
			if($project_detail['project_type'] == 'fulltime' && $this->config->item('project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message')){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_description_characters_min_length_validation_fulltime_project_escrow_form_message');
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_description';
				$msg['errors'][$i]['error_class'] = 'min_length';
				$i ++;
			}else if($project_detail['project_type'] != 'fulltime' && $this->config->item('project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message')){
				$msg['errors'][$i]['message'] = $this->config->item('project_details_page_escrow_description_characters_min_length_validation_project_escrow_form_message');
				$msg['status'] = 'FAILED';
				$msg['errors'][$i]['id'] = 'partial_escrow_description';
				$msg['errors'][$i]['error_class'] = 'min_length';
				$i ++;
			}
			
		}
	
		if($i == 0){
			$msg['status'] = 'SUCCESS';
            $msg['message'] = '';
		}
		return $msg;
	
	}
	
	/*
	This function is using to calculate the PO business charges when PO creating the escrow.
	*/
	public function get_po_service_fee_escrow_fixed_project($escrow_amount,$user_membership_plan_id){
	
		$business_charges = 0;
		//$projects_minimum_guaranteed_business_charges_data = $this->db->get_where('fixed_budget_projects_minimum_required_completion_threshold', ['po_membership_plan_id' =>$user_membership_plan_id])->row_array();
		$projects_minimum_guaranteed_business_charges_data = $this->db->get_where('fixed_budget_projects_minimum_guaranteed_service_fees_charges', ['po_membership_plan_id' =>$user_membership_plan_id])->row_array();
	
		if(!empty($projects_minimum_guaranteed_business_charges_data) && $escrow_amount <=  $projects_minimum_guaranteed_business_charges_data['upto_minimum_escrowed_amount']){
						
			$business_charges = $projects_minimum_guaranteed_business_charges_data['minimum_guaranteed_business_charge_value'];
			
		
		}else{
		
			$projects_escrow_charges_percentage_ranges_data = $this->db->get_where('fixed_budget_projects_escrow_charges_percentage_ranges', ['po_membership_plan_id' => $user_membership_plan_id])->result_array();
			if(!empty($projects_escrow_charges_percentage_ranges_data)){
				foreach($projects_escrow_charges_percentage_ranges_data as $escrow_charge_key => $escrow_charge_value){
					
					if($escrow_charge_value['max_escrowed_amount'] != 'All'){
						
						if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount'] && $escrow_amount <= (int)$escrow_charge_value['max_escrowed_amount']){
							
							$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
							$business_charges = ($escrow_charge_percentage_value/100)*$escrow_amount;
							break;
						}
					}else{
					
						if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount']){
							$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
							$business_charges = ($escrow_charge_percentage_value/100)*$escrow_amount;
							break;
						}
					}
				}
			}
		
		}
		return $business_charges;
	}
	/*
	This function is using to calculate the PO business charges when PO creating the escrow.
	*/
	public function get_po_service_fee_escrow_hourly_project($escrow_amount,$user_membership_plan_id) {
		$business_charges = 0;
		$projects_escrow_charges_percentage_ranges_data = $this->db->get_where('hourly_rate_based_projects_escrow_charges_percentage_ranges', ['po_membership_plan_id' => $user_membership_plan_id])->result_array();
			if(!empty($projects_escrow_charges_percentage_ranges_data)){
				foreach($projects_escrow_charges_percentage_ranges_data as $escrow_charge_key => $escrow_charge_value){
					
					if($escrow_charge_value['max_escrowed_amount'] != 'All'){
						
						if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount'] && $escrow_amount <= (int)$escrow_charge_value['max_escrowed_amount']){
							
							$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
							$business_charges = ($escrow_charge_percentage_value/100)*$escrow_amount;
							break;
						}
					}else{
					
						if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount']){
							$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
							$business_charges = ($escrow_charge_percentage_value/100)*$escrow_amount;
							break;
						}
					}
				}
			}
		return $business_charges;
	}
	/*
	This function is using to calculate the Employer service fees charges when Employer creating the escrow.
	*/
	public function get_employer_service_fee_charges_escrow_fulltime_project($escrow_amount,$user_membership_plan_id) {
		$business_charges = 0;
		$projects_escrow_charges_percentage_ranges_data = $this->db->get_where('fulltime_projects_escrow_charges_percentage_ranges', ['employer_membership_plan_id' => $user_membership_plan_id])->result_array();
			if(!empty($projects_escrow_charges_percentage_ranges_data)){
				foreach($projects_escrow_charges_percentage_ranges_data as $escrow_charge_key => $escrow_charge_value){
					
					if($escrow_charge_value['max_escrowed_amount'] != 'All'){
						
						if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount'] && $escrow_amount <= (int)$escrow_charge_value['max_escrowed_amount']){
							
							$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
							$business_charges = ($escrow_charge_percentage_value/100)*$escrow_amount;
							break;
						}
					}else{
					
						if($escrow_amount >= (int)$escrow_charge_value['min_escrowed_amount']){
							$escrow_charge_percentage_value = $escrow_charge_value['escrow_charge_percentage_value'];
							$business_charges = ($escrow_charge_percentage_value/100)*$escrow_amount;
							break;
						}
					}
				}
			}
		return $business_charges;
	}
	
	function service_fee_calculation_partial_release_escrow( $total_escrow_amount = 0 , $buisness_fee_charge = 0, $partial_escrow_amount = 0 ){
	
		/* echo $total_escrow_amount;
		echo "<br>";
		echo $buisness_fee_charge;
		echo "<br>";
		echo $partial_escrow_amount;
		die;
		 */
		
		$partial_escrow_amount = str_replace(" ","",$partial_escrow_amount);
		if( $total_escrow_amount == 0 || $buisness_fee_charge == 0 ){
		   $charge = 0;
		} else {
			$charge = $total_escrow_amount / $buisness_fee_charge;
		}

		$partial_escrow_amount = $partial_escrow_amount == '' ? 0 : $partial_escrow_amount;  

		if( $partial_escrow_amount == 0 || $charge == 0){
			$paid_charge = 0;
		} else {
			$paid_charge = $partial_escrow_amount / $charge;
		}

		$remaining_amount = $total_escrow_amount - $partial_escrow_amount;
		$remaining_charge = $buisness_fee_charge - $paid_charge;

		return [
			'partial_escrow_amount' => $partial_escrow_amount,
			'partial_buisness_fee' => $paid_charge,
			'remaining_escrow_amount' => $remaining_amount,
			'remaining_buisness_fee' => $remaining_charge 
		];

	}

	// This method is used to store data into charged_service_fees table
	function insert_data_into_charged_service_fees_tracking($charged_service_fees_data, $released_escrow_data) {

		if($charged_service_fees_data['project_type'] != 'fulltime') {
			$po = $released_escrow_data['project_owner_id'];
			$sp = $released_escrow_data['winner_id'];
		} else {
			$po = $released_escrow_data['employer_id'];
			$sp = $released_escrow_data['employee_id'];
		}

		$service_fee_value_excl_vat = $charged_service_fees_data['charged_service_fee_value_excl_vat'];
		
		$po_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($po);
		$po_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($po);
		$sp_lvl1_referal_data = $this->get_referral_user_details_from_lvl1_user_id($sp);
		$sp_lvl2_referal_data = $this->get_referral_user_details_from_lvl2_user_id($sp);
		$po_lv1_percentage_value = 0;
		$po_lv2_percentage_value = 0;
		$sp_lv1_percentage_value = 0;
		$sp_lv2_percentage_value = 0;
		
		$ts = strtotime(date('Y-m-d'));
		$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
		$week_start_date = date('Y-m-d', $start);

		if(!empty($po_lvl1_referal_data)) {
			$charged_service_fees_data['project_owner_lvl1_referrer_id'] = $po_lvl1_referal_data['user_id'];
			$charged_service_fees_data['project_owner_lvl1_referrer_membership_id'] = $po_lvl1_referal_data['id'];
			$charged_service_fees_data['project_owner_lvl1_referrer_considered_percentage'] = $po_lvl1_referal_data['lvl1_percentage'];
			$po_lv1_percentage_value = ($service_fee_value_excl_vat * $po_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
			$charged_service_fees_data['project_owner_lvl1_referrer_affiliate_generated_income'] = $po_lv1_percentage_value;

			$referral_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $po_lv1_percentage_value,
				'referral_earning_lvl_source' => 'lvl1',
				'referee_user_id' => $po
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];
			
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];

			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

			$total_earnings = [
				'user_id' => $po_lvl1_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl1' => $po_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}
		
		if(!empty($po_lvl2_referal_data)) {
			$charged_service_fees_data['project_owner_lvl2_referrer_id'] = $po_lvl2_referal_data['user_id'];
			$charged_service_fees_data['project_owner_lvl2_referrer_membership_id'] = $po_lvl2_referal_data['id'];
			$charged_service_fees_data['project_owner_lvl2_referrer_considered_percentage'] = $po_lvl2_referal_data['lvl2_percentage'];
			$po_lv2_percentage_value = ($service_fee_value_excl_vat * $po_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
			$charged_service_fees_data['project_owner_lvl2_referrer_affiliate_generated_income'] = $po_lv2_percentage_value;

			$referral_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $po_lv2_percentage_value,
				'referral_earning_lvl_source' => 'lvl2',
				'referee_user_id' => $po
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			pre($daily_earnings);
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);
			
			$total_earnings = [
				'user_id' => $po_lvl2_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl2' => $po_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}

		if(!empty($sp_lvl1_referal_data)) {
			$charged_service_fees_data['winner_lvl1_referrer_id'] = $sp_lvl1_referal_data['user_id'];
			$charged_service_fees_data['winner_lvl1_referrer_membership_id'] = $sp_lvl1_referal_data['id'];
			$charged_service_fees_data['winner_lvl1_referrer_considered_percentage'] = $sp_lvl1_referal_data['lvl1_percentage'];
			$sp_lv1_percentage_value = ($service_fee_value_excl_vat * $sp_lvl1_referal_data['lvl1_percentage'] ) / 100 ;
			$charged_service_fees_data['winner_lvl1_referrer_affiliate_generated_income'] = $sp_lv1_percentage_value;

			$referral_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $sp_lv1_percentage_value,
				'referral_earning_lvl_source' => 'lvl1',
				'referee_user_id' => $sp
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);

			$monthly_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			
			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

			$total_earnings = [
				'user_id' => $sp_lvl1_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl1' => $sp_lv1_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}

		if(!empty($sp_lvl2_referal_data)) {
			$charged_service_fees_data['winner_lvl2_referrer_id'] = $sp_lvl2_referal_data['user_id'];
			$charged_service_fees_data['winner_lvl2_referrer_membership_id'] = $sp_lvl2_referal_data['id'];
			$charged_service_fees_data['winner_lvl2_referrer_considered_percentage'] = $sp_lvl2_referal_data['lvl2_percentage'];
			$sp_lv2_percentage_value = ($service_fee_value_excl_vat * $sp_lvl2_referal_data['lvl2_percentage'] ) / 100 ;
			$charged_service_fees_data['winner_lvl2_referrer_affiliate_generated_income'] = $sp_lv2_percentage_value;

			$referral_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earning_date' => date('Y-m-d H:i:s'),
				'referral_earning_source_reference_id' => $charged_service_fees_data['charged_service_fee_reference_id'],
				'referral_earnig_value' => $sp_lv2_percentage_value,
				'referral_earning_lvl_source' => 'lvl2',
				'referee_user_id' => $sp
			];
			$this->db->insert('users_referrals_earnings_history_tracking', $referral_earnings);

			$daily_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earnings_date' => date('Y-m-d H:i:s'),
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($daily_earnings);

			$weekly_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earnings_week_start_date' => $week_start_date,
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($weekly_earnings);
                                                                
			$monthly_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'referral_earnings_month' => date('Y-m-d'),
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($monthly_earnings);

			$total_earnings = [
				'user_id' => $sp_lvl2_referal_data['user_id'],
				'aggregated_referral_earnings_value_lvl2' => $sp_lv2_percentage_value
			];
			$this->save_data_into_users_referrals_lifetime_total_earnings_tracking($total_earnings);
		}

		$charged_service_fees_data['charged_service_fee_net_value'] = $service_fee_value_excl_vat - ($po_lv1_percentage_value + $po_lv2_percentage_value + $sp_lv1_percentage_value + $sp_lv2_percentage_value);

		if(!empty($charged_service_fees_data)) {
			$this->db->insert('projects_charged_service_fees_tracking', $charged_service_fees_data);
		}
	}
	// This method is used to get referral user information like his current membership information and based on membership which how much percentage he will get from lvl1 referral  
	function get_referral_user_details_from_lvl1_user_id($user_id) {
		$this->db->select('ud.user_id, mp.id, mp.lvl1_percentage');
		$this->db->from('users_referrals_tracking lvl1');
		$this->db->join('users_details ud', 'ud.user_id = lvl1.lvl1_referrer_id');
		$this->db->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id');
		$this->db->where('lvl1.user_id', $user_id);
		return $this->db->get()->row_array();
	}
	
	// This method is used to get referral user information like his current membership information and based on membership which how much percentage he will get from lvl2 referal 
	function get_referral_user_details_from_lvl2_user_id($user_id) {
		$this->db->select('ud.user_id, mp.id, mp.lvl2_percentage');
		$this->db->from('users_referrals_tracking lvl2');
		$this->db->join('users_details ud', 'ud.user_id = lvl2.lvl2_referrer_id');
		$this->db->join('membership_plans mp', 'mp.id = ud.current_membership_plan_id');
		$this->db->where('lvl2.user_id', $user_id);
		return $this->db->get()->row_array();
	}

	// This method is used to save data ino users referral aggregated daily earning history table
	function save_data_into_users_referrals_aggregated_daily_earnings_history_tracking($data) {
		$daily_earnings = $this->db->select('id, DATE_FORMAT(referral_earnings_date, "%Y-%m-%d")')->from('users_referrals_daily_earnings_history_tracking')->where('user_id', $data['user_id'])->where('DATE(referral_earnings_date) = CURDATE()')->get()->row_array();
	
		if(empty($daily_earnings)) {
			$this->db->insert('users_referrals_daily_earnings_history_tracking', $data);
		} else {
			$this->db->where('id', $daily_earnings['id']);
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->update('users_referrals_daily_earnings_history_tracking');
		}		
	}

	// This method is used to save data ino users referral aggregated weekly earning history table
	function save_data_into_users_referrals_aggregated_weekly_earnings_history_tracking($data) {
		$weekly_earnings = $this->db->select('id')->from('users_referrals_weekly_earnings_history_tracking')->where('user_id', $data['user_id'])->where('referral_earnings_week_start_date', $data['referral_earnings_week_start_date'])->get()->row_array();
		if(empty($weekly_earnings)) {
			$this->db->insert('users_referrals_weekly_earnings_history_tracking', $data);
		} else {
			$this->db->where('id', $weekly_earnings['id']);
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->update('users_referrals_weekly_earnings_history_tracking');
		}		
	}

	// This method is used to save data ino users referral aggregated monthly earning history table
	function save_data_into_users_referrals_aggregated_monthly_earnings_history_tracking($data) {
		$monthly_earnings = $this->db->select('id')->from('users_referrals_monthly_earnings_history_tracking')->where('user_id', $data['user_id'])->where('YEAR(referral_earnings_month) = YEAR(NOW()) AND MONTH(referral_earnings_month)=MONTH(NOW())')->get()->row_array();
		if(empty($monthly_earnings)) {
			$this->db->insert('users_referrals_monthly_earnings_history_tracking', $data);
		} else {
			$this->db->where('id', $monthly_earnings['id']);
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->update('users_referrals_monthly_earnings_history_tracking');
		}		
	}

	// This method is used to save data ino users referral lifetime total earnings tracking table
	function save_data_into_users_referrals_lifetime_total_earnings_tracking($data) {
		$total_earnings = $this->db->select('id')->from('users_referrals_lifetime_total_earnings_tracking')->where('user_id', $data['user_id'])->get()->row_array();
		if(empty($total_earnings)) {
			$this->db->insert('users_referrals_lifetime_total_earnings_tracking', $data);
		} else {
			if(isset($data['aggregated_referral_earnings_value_lvl1'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl1', 'aggregated_referral_earnings_value_lvl1 + '.$data['aggregated_referral_earnings_value_lvl1'], false);
			}
			if(isset($data['aggregated_referral_earnings_value_lvl2'])) {
				$this->db->set('aggregated_referral_earnings_value_lvl2', 'aggregated_referral_earnings_value_lvl2 + '.$data['aggregated_referral_earnings_value_lvl2'], false);
			}
			$this->db->where('id', $total_earnings['id']);
			$this->db->update('users_referrals_lifetime_total_earnings_tracking');
		}		
	}
	
	// this function is used to check and insert the data into table "projects_candidates_for_users_ratings_feedbacks_exchange" when po/sp can give the feedback for fixed/hourly/fulltime project
	function insert_data_for_projects_candidates_for_users_ratings_feedbacks_exchange($data = array()){
		$project_id = $data['project_id'];
		$po_id = $data['po_id'];
		$sp_id = $data['sp_id'];
		$project_type = $data['project_type'];
		$project_title = $data['project_title'];
		$project_completion_date = $data['project_completion_date'];
		
		// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" start
		$count_ratings_feedbacks_exchange = $this->db->where(['project_id' => $project_id,'po_id'=>$po_id,'sp_id'=>$sp_id])->from('projects_candidates_for_users_ratings_feedbacks_exchange')->count_all_results();
		if($count_ratings_feedbacks_exchange == 0){
			$ratings_feedbacks_exchange['project_id'] = $project_id;                
			$ratings_feedbacks_exchange['project_title'] = $project_title;
			if($project_type == 'fixed'){
				$ratings_feedbacks_exchange['project_type'] = 'fixed_budget';
			}if($project_type == 'hourly'){
				$ratings_feedbacks_exchange['project_type'] = 'hourly_rate';
			}if($project_type == 'fulltime'){
				$ratings_feedbacks_exchange['project_type'] = 'fulltime';
			}
			$ratings_feedbacks_exchange['project_completion_date'] = $project_completion_date;
			$ratings_feedbacks_exchange['po_id'] = $po_id;
			$ratings_feedbacks_exchange['sp_id'] = $sp_id;
			$this->db->insert ('projects_candidates_for_users_ratings_feedbacks_exchange', $ratings_feedbacks_exchange);
		}
		// insert entry into the table "projects_candidates_for_users_ratings_feedbacks_exchange" end
		
	
	}
	
}
?>