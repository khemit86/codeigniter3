<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class User_projects_payments_overview_model extends BaseModel
{

    public function __construct ()
    { 
        return parent::__construct ();
    }
    
	/**
	* This function is used to count the total published project of po .
	*/
	public function get_po_published_projects_count($user_id){
	
		$published_projects_tables_array['projects_open_bidding'] = array('conditions'=>array('project_owner_id'=>$user_id));
		// For fixed
		$published_projects_tables_array['fixed_budget_projects_awarded']= array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['fixed_budget_projects_progress'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['fixed_budget_projects_incomplete'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['fixed_budget_projects_completed'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['fixed_budget_projects_expired'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['fixed_budget_projects_cancelled'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['fixed_budget_projects_cancelled_by_admin'] = array('conditions'=>array('project_owner_id'=>$user_id));
		
		// For hourly
		$published_projects_tables_array['hourly_rate_based_projects_awarded']= array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['hourly_rate_based_projects_progress'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['hourly_rate_based_projects_incomplete'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['hourly_rate_based_projects_completed'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['hourly_rate_based_projects_expired'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['hourly_rate_based_projects_cancelled']= array('conditions'=>array('project_owner_id'=>$user_id));
		$published_projects_tables_array['hourly_rate_based_projects_cancelled_by_admin'] = array('conditions'=>array('project_owner_id'=>$user_id));
		
		// For fulltime
		$published_projects_tables_array['fulltime_projects_expired']= array('conditions'=>array('employer_id'=>$user_id));
		$published_projects_tables_array['fulltime_projects_cancelled'] = array('conditions'=>array('employer_id'=>$user_id));
		$published_projects_tables_array['fulltime_projects_cancelled_by_admin'] = array('conditions'=>array('employer_id'=>$user_id));
		
		$po_published_projects_count = 0;
		foreach($published_projects_tables_array as $table_name=>$conditions){
			$po_published_projects_count += $this->db->where($conditions['conditions'])->from($table_name)->count_all_results();
		}
		return $po_published_projects_count;
	}
	
	/**
	* This function is used to count the total escrows count of project regarding po .
	*/
	public function get_po_all_projects_created_escrows_count($user_id){
	
		// For fixed
		$projects_escrows_tables_array['fixed_budget_projects_requested_escrows'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_active_escrows']= array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_cancelled_escrows_tracking'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_released_escrows'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_rejected_requested_escrows'] = array('conditions'=>array('project_owner_id'=>$user_id));
		
		
		$projects_escrows_tables_array['fixed_budget_projects_closed_disputes_po_reverted_amounts'] = array('conditions'=>array('po_id'=>$user_id));
	
		
		// For hourly
		$projects_escrows_tables_array['hourly_rate_based_projects_requested_escrows'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_active_escrows']= array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_cancelled_escrows_tracking'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_released_escrows'] = array('conditions'=>array('project_owner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_rejected_requested_escrows'] = array('conditions'=>array('project_owner_id'=>$user_id));
		
		$projects_escrows_tables_array['hourly_rate_projects_closed_disputes_po_reverted_amounts'] = array('conditions'=>array('po_id'=>$user_id));
		
		// For fulltime
		$projects_escrows_tables_array['fulltime_projects_requested_escrows'] = array('conditions'=>array('employer_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_active_escrows']= array('conditions'=>array('employer_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_cancelled_escrows_tracking'] = array('conditions'=>array('employer_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_released_escrows'] = array('conditions'=>array('employer_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_rejected_requested_escrows'] = array('conditions'=>array('employer_id'=>$user_id));
		
		$projects_escrows_tables_array['fulltime_projects_closed_disputes_employer_reverted_amounts'] = array('conditions'=>array('employer_id'=>$user_id));
		
		$po_projects_escrows_count = 0;
		foreach($projects_escrows_tables_array as $table_name=>$conditions){
			$po_projects_escrows_count += $this->db->where($conditions['conditions'])->from($table_name)->count_all_results();
		}
		return $po_projects_escrows_count;
	}
	
	/**
	* This function is used to fetch the project name for dropdown regarding escrows for po
	*/
	public function get_po_all_projects_dropdown_list($user_id){
	
		$project_fields = "ed.project_id,pd.project_title,pd.project_type";
		$fulltime_project_fields = "ed.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type";
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$project_fields,false);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		#############################################
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		###############################################
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		#########################################
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		###############################################
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		#########################################################
		// For reverted amount regarding dispute project for fixed budget 
		
		
		$this->db->select('ed.disputed_project_id,pd.project_title,pd.project_type');
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.disputed_project_id');
		$fixed_budget_inprogress_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.disputed_project_id,pd.project_title,pd.project_type');
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('ed.disputed_project_id,pd.project_title,pd.project_type');
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$fixed_budget_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		###############
		
		
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		#########################################################
		// For reverted amount regarding dispute project for hourl budget project
		
		
		$this->db->select('ed.disputed_project_id,pd.project_title,pd.project_type');
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts` as ed');
		$this->db->where('ed.po_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.disputed_project_id');
		$hourly_rate_based_inprogress_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.disputed_project_id,pd.project_title,pd.project_type');
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts` as ed');
		$this->db->where('ed.po_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('ed.disputed_project_id,pd.project_title,pd.project_type');
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts` as ed');
		$this->db->where('ed.po_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		
		#######################
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		// for reverted amount of disputed project
		
		$this->db->select('ed.disputed_fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.disputed_fulltime_project_id');
		$fulltime_open_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('ed.disputed_fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$fulltime_expired_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.disputed_fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$fulltime_cancelled_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.disputed_fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_requested_escrows_query,
			$fixed_budget_incomplete_projects_requested_escrows_query,
			$fixed_budget_completed_projects_requested_escrows_query,
			$fixed_budget_inprogress_projects_active_escrows_query,
			$fixed_budget_incomplete_projects_active_escrows_query,
			$fixed_budget_completed_projects_active_escrows_query,
			$fixed_budget_inprogress_projects_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_cancelled_escrows_query,
			$fixed_budget_completed_projects_cancelled_escrows_query,
			$fixed_budget_inprogress_projects_released_escrows_query,
			$fixed_budget_incomplete_projects_released_escrows_query,
			$fixed_budget_completed_projects_released_escrows_query,
			$fixed_budget_inprogress_projects_rejected_requested_escrows_query,
			$fixed_budget_incomplete_projects_rejected_requested_escrows_query,
			$fixed_budget_completed_projects_rejected_requested_escrows_query,
			$fixed_budget_inprogress_projects_reverted_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query,
			$fixed_budget_completed_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_inprogress_projects_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_requested_escrows_query,
			$hourly_rate_based_completed_projects_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_active_escrows_query,
			$hourly_rate_based_incomplete_projects_active_escrows_query,
			$hourly_rate_based_completed_projects_active_escrows_query,
			$hourly_rate_based_inprogress_projects_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_cancelled_escrows_query,
			$hourly_rate_based_inprogress_projects_released_escrows_query,
			$hourly_rate_based_incomplete_projects_released_escrows_query,
			$hourly_rate_based_completed_projects_released_escrows_query,
			$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query,
			$hourly_rate_based_completed_projects_rejected_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query,
			$fulltime_open_projects_requested_escrows_query,
			$fulltime_expired_projects_requested_escrows_query,
			$fulltime_cancelled_projects_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_requested_escrows_query,
			$fulltime_open_projects_active_escrows_query,
			$fulltime_expired_projects_active_escrows_query,
			$fulltime_cancelled_projects_active_escrows_query,
			$fulltime_cancelled_by_admin_projects_active_escrows_query,
			$fulltime_open_projects_cancelled_escrows_query,
			$fulltime_expired_projects_cancelled_escrows_query,
			$fulltime_cancelled_projects_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_cancelled_escrows_query,
			$fulltime_open_projects_released_escrows_query,
			$fulltime_expired_projects_released_escrows_query,
			$fulltime_cancelled_projects_released_escrows_query,
			$fulltime_cancelled_by_admin_projects_released_escrows_query,
			$fulltime_open_projects_rejected_requested_escrows_query,
			$fulltime_expired_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query,
			$fulltime_open_projects_reverted_cancelled_escrows_query,
			$fulltime_expired_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query
			
		];
		
		$escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY project_title ASC ');
		$result  = $escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	
	/**
	* This function is used to count the project on which sp is hired.
	*/
	public function get_sp_all_projects_count($user_id){
	
		$bid_tables_array['fixed_budget_projects_progress_sp_bid_reference'] = array('conditions'=>array('winner_id'=>$user_id));
		$bid_tables_array['fixed_budget_projects_incomplete_tracking'] = array('conditions'=>array('winner_id'=>$user_id));
		$bid_tables_array['fixed_budget_projects_completed_tracking'] = array('conditions'=>array('winner_id'=>$user_id));
		
		$bid_tables_array['hourly_rate_based_projects_progress_sp_bid_reference'] = array('conditions'=>array('winner_id'=>$user_id));
		
		$bid_tables_array['hourly_rate_based_projects_incomplete_tracking'] = array('conditions'=>array('winner_id'=>$user_id));
		
		$bid_tables_array['hourly_rate_based_projects_completed_tracking'] = array('conditions'=>array('winner_id'=>$user_id));
		$bid_tables_array['fulltime_projects_hired_employees_tracking'] = array('conditions'=>array('employee_id'=>$user_id));
		$sp_projects_count = 0;
		foreach($bid_tables_array as $table_name=>$conditions){
			$sp_projects_count += $this->db->where($conditions['conditions'])->from($table_name)->count_all_results();
		}
		return $sp_projects_count;
	}
	
	/**
	* This function is used to count the total escrows count of project regarding sp .
	*/
	public function get_sp_all_projects_created_escrows_count($user_id){
	
		// For fixed
		$projects_escrows_tables_array['fixed_budget_projects_requested_escrows'] = array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_active_escrows']= array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_cancelled_escrows_tracking'] = array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_released_escrows'] = array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['fixed_budget_projects_rejected_requested_escrows'] = array('conditions'=>array('winner_id'=>$user_id));
		
	
	
		
		// For hourly
		$projects_escrows_tables_array['hourly_rate_based_projects_requested_escrows'] = array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_active_escrows']= array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_cancelled_escrows_tracking'] = array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_released_escrows'] = array('conditions'=>array('winner_id'=>$user_id));
		$projects_escrows_tables_array['hourly_rate_based_projects_rejected_requested_escrows'] = array('conditions'=>array('winner_id'=>$user_id));
		
		// For fulltime
		$projects_escrows_tables_array['fulltime_projects_requested_escrows'] = array('conditions'=>array('employee_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_active_escrows']= array('conditions'=>array('employee_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_cancelled_escrows_tracking'] = array('conditions'=>array('employee_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_released_escrows'] = array('conditions'=>array('employee_id'=>$user_id));
		$projects_escrows_tables_array['fulltime_projects_rejected_requested_escrows'] = array('conditions'=>array('employee_id'=>$user_id));
		
		$sp_projects_escrows_count = 0;
		foreach($projects_escrows_tables_array as $table_name=>$conditions){
			$sp_projects_escrows_count += $this->db->where($conditions['conditions'])->from($table_name)->count_all_results();
		}
		return $sp_projects_escrows_count;
	}
	
	/**
	* This function is used to fetch the project name for dropdown regarding escrows for sp.
	*/
	public function get_sp_all_projects_dropdown_list($user_id){
		
		$project_fields = "ed.project_id,pd.project_title,pd.project_type";
		$fulltime_project_fields = "ed.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type";
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$project_fields,false);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$fixed_budget_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		// reverted cancelled escrow
		
	
		$this->db->select("ed.disputed_project_id as project_id,pd.project_title,pd.project_type");
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.disputed_project_id');
		$fixed_budget_inprogress_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select("ed.disputed_project_id as project_id,pd.project_title,pd.project_type");
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select("ed.disputed_project_id as project_id,pd.project_title,pd.project_type");
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$fixed_budget_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		
		
		#########################################################
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($project_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$hourly_rate_based_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		// Reverted cancelled escrow
		
		$this->db->select("ed.disputed_project_id as project_id,pd.project_title,pd.project_type");
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.disputed_project_id');
		$hourly_rate_based_inprogress_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select("ed.disputed_project_id as project_id,pd.project_title,pd.project_type");
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select("ed.disputed_project_id as project_id,pd.project_title,pd.project_type");
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		#######################
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select('ed.fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_expired_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_project_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		### for reverted amount regarding disputed project
		
		$this->db->select('ed.disputed_fulltime_project_id as project_id,pd.project_title,pd.project_type');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.disputed_fulltime_project_id');
		$fulltime_open_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select("ed.disputed_fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type");
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$fulltime_expired_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select("ed.disputed_fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type");
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$fulltime_cancelled_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select("ed.disputed_fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type");
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		
		
		
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_requested_escrows_query,
			$fixed_budget_incomplete_projects_requested_escrows_query,
			 $fixed_budget_completed_projects_requested_escrows_query,
			$fixed_budget_inprogress_projects_active_escrows_query,
			$fixed_budget_incomplete_projects_active_escrows_query,
			$fixed_budget_completed_projects_active_escrows_query,
			$fixed_budget_inprogress_projects_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_cancelled_escrows_query,
			$fixed_budget_completed_projects_cancelled_escrows_query,
			$fixed_budget_inprogress_projects_released_escrows_query,
			$fixed_budget_incomplete_projects_released_escrows_query,
			$fixed_budget_completed_projects_released_escrows_query,
			$fixed_budget_inprogress_projects_rejected_requested_escrows_query,
			$fixed_budget_incomplete_projects_rejected_requested_escrows_query,
			$fixed_budget_completed_projects_rejected_requested_escrows_query,
			$fixed_budget_inprogress_projects_reverted_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query,
			$fixed_budget_completed_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_inprogress_projects_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_requested_escrows_query,
			$hourly_rate_based_completed_projects_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_active_escrows_query,
			$hourly_rate_based_incomplete_projects_active_escrows_query,
			$hourly_rate_based_completed_projects_active_escrows_query,
			$hourly_rate_based_inprogress_projects_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_cancelled_escrows_query,
			$hourly_rate_based_inprogress_projects_released_escrows_query,
			$hourly_rate_based_incomplete_projects_released_escrows_query,
			$hourly_rate_based_completed_projects_released_escrows_query,
			$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query,
			$hourly_rate_based_completed_projects_rejected_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query,
			$fulltime_open_projects_requested_escrows_query,
			$fulltime_expired_projects_requested_escrows_query,
			$fulltime_cancelled_projects_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_requested_escrows_query,
			$fulltime_open_projects_active_escrows_query,
			$fulltime_expired_projects_active_escrows_query,
			$fulltime_cancelled_projects_active_escrows_query,
			$fulltime_cancelled_by_admin_projects_active_escrows_query,
			$fulltime_open_projects_cancelled_escrows_query,
			$fulltime_expired_projects_cancelled_escrows_query,
			$fulltime_cancelled_projects_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_cancelled_escrows_query,
			$fulltime_open_projects_released_escrows_query,
			$fulltime_expired_projects_released_escrows_query,
			$fulltime_cancelled_projects_released_escrows_query,
			$fulltime_cancelled_by_admin_projects_released_escrows_query,
			$fulltime_open_projects_rejected_requested_escrows_query,
			$fulltime_expired_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query,
			$fulltime_open_projects_reverted_cancelled_escrows_query,
			$fulltime_expired_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query
		];
		
		$escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY project_title ASC ');
		$result  = $escrows_projects_query->result_array();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to fetch the all requested escrows listing of all projects for po.
	*/
	public function get_all_requested_escrows_listing_all_projects_po($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date');
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_expired_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_by_admin_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_requested_escrows_query,
			$fixed_budget_incomplete_projects_requested_escrows_query,
			$fixed_budget_completed_projects_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_requested_escrows_query,
			$hourly_rate_based_completed_projects_requested_escrows_query,
			$fulltime_open_projects_requested_escrows_query,
			$fulltime_expired_projects_requested_escrows_query,
			$fulltime_cancelled_projects_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_requested_escrows_query,
		];
		
		$requested_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_requested_by_sp_date DESC LIMIT '.$limit_range);
		$result  = $requested_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of requested escrows of all projects for po.
	*/
	public function get_sum_all_requested_escrows_amount_all_projects_po($user_id,$params){
		
		$requested_escrow_table_array = array('fixed_budget_projects_requested_escrows','hourly_rate_based_projects_requested_escrows','fulltime_projects_requested_escrows');
		$sum_requested_escrow_amount_value = 0;
		foreach($requested_escrow_table_array as $requested_escrow_table_name){
			$this->db->select('SUM(requested_escrow_amount) as sum_requested_escrow_amount_value');
			$this->db->from($requested_escrow_table_name);
			
			if($requested_escrow_table_name == 'fulltime_projects_requested_escrows'){
				$this->db->where('employer_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('project_owner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_requested_escrow_result = $this->db->get();
			$sum_requested_escrow = $sum_requested_escrow_result->row_array();
			
			$sum_requested_escrow_amount_value += $sum_requested_escrow['sum_requested_escrow_amount_value'];
			
		}
		return $sum_requested_escrow_amount_value ;
	}
	
	/**
	* This function is used to fetch the all requested escrows listing of all projects for sp.
	*/
	public function get_all_requested_escrows_listing_all_projects_sp($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = pd.project_owner_id');
		$fixed_budget_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = pd.project_owner_id');
		$fixed_budget_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = pd.project_owner_id');
		$hourly_rate_based_inprogress_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = pd.project_owner_id');
		$hourly_rate_based_incomplete_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = pd.project_owner_id');
		$hourly_rate_based_completed_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date');
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_expired_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_by_admin_projects_requested_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_requested_escrows_query,
			$fixed_budget_incomplete_projects_requested_escrows_query,
			$fixed_budget_completed_projects_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_requested_escrows_query,
			$hourly_rate_based_completed_projects_requested_escrows_query,
			$fulltime_open_projects_requested_escrows_query,
			$fulltime_expired_projects_requested_escrows_query,
			$fulltime_cancelled_projects_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_requested_escrows_query
		];
		
		$requested_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_requested_by_sp_date DESC LIMIT '.$limit_range);
		$result  = $requested_escrows_projects_query->result_array();
		
		//pre($result);
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of requested escrows of all projects for sp.
	*/
	public function get_sum_all_requested_escrows_amount_all_projects_sp($user_id,$params){
		
		$requested_escrow_table_array = array('fixed_budget_projects_requested_escrows','hourly_rate_based_projects_requested_escrows','fulltime_projects_requested_escrows');
		$sum_requested_escrow_amount_value = 0;
		foreach($requested_escrow_table_array as $requested_escrow_table_name){
			$this->db->select('SUM(requested_escrow_amount) as sum_requested_escrow_amount_value');
			$this->db->from($requested_escrow_table_name);
			
			if($requested_escrow_table_name == 'fulltime_projects_requested_escrows'){
				$this->db->where('employee_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('winner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_requested_escrow_result = $this->db->get();
			$sum_requested_escrow = $sum_requested_escrow_result->row_array();
			
			$sum_requested_escrow_amount_value += $sum_requested_escrow['sum_requested_escrow_amount_value'];
			
		}
		return $sum_requested_escrow_amount_value ;
	}
	
	/**
	* This function is used to fetch the all active escrows listing of all projects for po.
	*/
	public function get_all_active_escrows_listing_all_projects_po($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date');
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_expired_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_by_admin_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_active_escrows_query,
			$fixed_budget_incomplete_projects_active_escrows_query,
			$fixed_budget_completed_projects_active_escrows_query,
			$hourly_rate_based_inprogress_projects_active_escrows_query,
			$hourly_rate_based_incomplete_projects_active_escrows_query,
			$hourly_rate_based_completed_projects_active_escrows_query,
			$fulltime_open_projects_active_escrows_query,
			$fulltime_expired_projects_active_escrows_query,
			$fulltime_cancelled_projects_active_escrows_query,
			$fulltime_cancelled_by_admin_projects_active_escrows_query
		];
		
		$active_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_creation_date DESC LIMIT '.$limit_range);
		$result  = $active_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of active escrows of all projects for po.
	*/
	public function get_sum_all_active_escrows_amount_all_projects_po($user_id,$params){
		
		$active_escrow_table_array = array('fixed_budget_projects_active_escrows','hourly_rate_based_projects_active_escrows','fulltime_projects_active_escrows');
		$sum_active_escrow_amount_value = 0;
		foreach($active_escrow_table_array as $active_escrow_table_name){
			$this->db->select('SUM(created_escrow_amount) as sum_active_escrow_amount_value');
			$this->db->from($active_escrow_table_name);
			
			if($active_escrow_table_name == 'fulltime_projects_active_escrows'){
				$this->db->where('employer_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('project_owner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_active_escrow_result = $this->db->get();
			$sum_active_escrow = $sum_active_escrow_result->row_array();
			
			$sum_active_escrow_amount_value += $sum_active_escrow['sum_active_escrow_amount_value'];
			
		}
		return $sum_active_escrow_amount_value ;
	}
	
	/**
	* This function is used to fetch the all active escrows listing of all projects for sp.
	*/
	public function get_all_active_escrows_listing_all_projects_sp($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete` as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_inprogress_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_incomplete_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_active_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_completed_projects_active_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.escrow_description,ed.created_escrow_amount,ed.escrow_creation_date');
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_expired_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_active_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_by_admin_projects_active_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_active_escrows_query,
			$fixed_budget_incomplete_projects_active_escrows_query,
			$fixed_budget_completed_projects_active_escrows_query,
			$hourly_rate_based_inprogress_projects_active_escrows_query,
			$hourly_rate_based_incomplete_projects_active_escrows_query,
			$hourly_rate_based_completed_projects_active_escrows_query,
			$fulltime_open_projects_active_escrows_query,
			$fulltime_expired_projects_active_escrows_query,
			$fulltime_cancelled_projects_active_escrows_query,
			$fulltime_cancelled_by_admin_projects_active_escrows_query
		];
		
		$active_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_creation_date DESC LIMIT '.$limit_range);
		$result  = $active_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of active escrows of all projects for sp.
	*/
	public function get_sum_all_active_escrows_amount_all_projects_sp($user_id,$params){
		
		$active_escrow_table_array = array('fixed_budget_projects_active_escrows','hourly_rate_based_projects_active_escrows','fulltime_projects_active_escrows');
		$sum_active_escrow_amount_value = 0;
		foreach($active_escrow_table_array as $active_escrow_table_name){
			$this->db->select('SUM(created_escrow_amount) as sum_active_escrow_amount_value');
			$this->db->from($active_escrow_table_name);
			
			if($active_escrow_table_name == 'fulltime_projects_active_escrows'){
				$this->db->where('employee_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('winner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_active_escrow_result = $this->db->get();
			$sum_active_escrow = $sum_active_escrow_result->row_array();
			
			$sum_active_escrow_amount_value += $sum_active_escrow['sum_active_escrow_amount_value'];
			
		}
		return $sum_active_escrow_amount_value ;
	}
	
	/**
	* This function is used to fetch the all cancelled escrows listing of all projects for po.
	*/
	public function get_all_cancelled_escrows_listing_all_projects_po($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id';
		
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id';
		
		$cancelled_disputed_escrow_reverted_amount_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.sp_id as winner_id,"" as cancelled_escrow_description,ed.reverted_amount as reverted_escrowed_amount,"" as initial_escrow_creation_date,ed.dispute_close_date as escrow_cancellation_date,ed.dispute_reference_id';
		
		$fulltime_cancelled_disputed_escrow_reverted_amount_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,"" as cancelled_escrow_description,ed.reverted_amount as reverted_escrowed_amount,"" as initial_escrow_creation_date,ed.dispute_close_date as escrow_cancellation_date,ed.dispute_reference_id';
		
		
		
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// PO reverted amount incomplete fixed project
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.sp_id');
		$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// PO reverted amount completed fixed project
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.sp_id');
		$fixed_budget_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		// PO reverted amount incomplete hourly project
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.sp_id');
		$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// PO reverted amount incomplete hourly project
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.po_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.sp_id');
		$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id');
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_expired_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_by_admin_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		// PO reverted amount fulltime
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,"" as cancelled_escrow_description,ed.reverted_amount as reverted_escrowed_amount,"" as initial_escrow_creation_date,ed.dispute_close_date as escrow_cancellation_date,ed.dispute_reference_id');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.disputed_fulltime_project_id');
		$fulltime_open_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fulltime_cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_expired_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query,
			$fixed_budget_completed_projects_reverted_cancelled_escrows_query,
			$fixed_budget_completed_projects_cancelled_escrows_query,
			$hourly_rate_based_inprogress_projects_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query,
			$fulltime_open_projects_cancelled_escrows_query,
			$fulltime_expired_projects_cancelled_escrows_query,
			$fulltime_cancelled_projects_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_cancelled_escrows_query,
			$fulltime_open_projects_reverted_cancelled_escrows_query,
			$fulltime_expired_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query
		];
		
		$cancelled_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_cancellation_date DESC LIMIT '.$limit_range);
		$result  = $cancelled_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of cancelled escrows of all projects for po.
	*/
	public function get_sum_all_cancelled_escrows_amount_all_projects_po($user_id,$params){
		
		$cancelled_escrow_table_array = array('fixed_budget_projects_cancelled_escrows_tracking','hourly_rate_based_projects_cancelled_escrows_tracking','fulltime_projects_cancelled_escrows_tracking');
		$sum_cancelled_escrow_amount_value = 0;
		foreach($cancelled_escrow_table_array as $cancelled_escrow_table_name){
			$this->db->select('SUM(reverted_escrowed_amount) as sum_cancelled_escrow_amount_value');
			$this->db->from($cancelled_escrow_table_name);
			
			if($cancelled_escrow_table_name == 'fulltime_projects_cancelled_escrows_tracking'){
				$this->db->where('employer_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('project_owner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_cancelled_escrow_result = $this->db->get();
			$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
			
			$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_escrow_amount_value'];
			
		}
		// reverted amount of dispute fixed project
		$this->db->select('SUM(reverted_amount) as sum_cancelled_reverted_amount_value');
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts');
		$this->db->where('po_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('disputed_project_id',$params['project_id']);
		}
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
		$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_reverted_amount_value'];
		
		// reverted amount of dispute hourly project
		$this->db->select('SUM(reverted_amount) as sum_cancelled_reverted_amount_value');
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts');
		$this->db->where('po_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('disputed_project_id',$params['project_id']);
		}
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
		$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_reverted_amount_value'];
		
		// reverted amount of dispute fulltime project
		$this->db->select('SUM(reverted_amount) as sum_cancelled_reverted_amount_value');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts');
		$this->db->where('employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('disputed_fulltime_project_id',$params['project_id']);
		}
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
		$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_reverted_amount_value'];
		
		
		
		return $sum_cancelled_escrow_amount_value ;
	}
	
	/**
	* This function is used to fetch the all cancelled escrows listing of all projects for sp.
	*/
	public function get_all_cancelled_escrows_listing_all_projects_sp($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id';
		
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date,ed.dispute_reference_id';
		
		$cancelled_disputed_escrow_reverted_amount_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.sp_id as winner_id,"" as cancelled_escrow_description,ed.reverted_amount as reverted_escrowed_amount,"" as initial_escrow_creation_date,ed.dispute_close_date as escrow_cancellation_date,ed.dispute_reference_id';
		
		$fulltime_cancelled_disputed_escrow_reverted_amount_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,"" as cancelled_escrow_description,ed.reverted_amount as reverted_escrowed_amount,"" as initial_escrow_creation_date,ed.dispute_close_date as escrow_cancellation_date,ed.dispute_reference_id';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// cancelled revreted escrow amount for incomplete fixed project 
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.po_id');
		$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		// cancelled revreted escrow amount for completed fixed project 
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.po_id');
		$fixed_budget_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_inprogress_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_incomplete_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_completed_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// cancelled revreted escrow amount for incomplete hourly project 
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.po_id');
		$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// cancelled revreted escrow amount for completed hourly project 
		$this->db->select($cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts as ed');
		$this->db->where('ed.sp_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.disputed_project_id');
		$this->db->join('users as u', 'u.user_id = ed.po_id');
		$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.cancelled_escrow_description,ed.reverted_escrowed_amount,ed.initial_escrow_creation_date,ed.escrow_cancellation_date," " as dispute_reference_id');
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_expired_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_cancelled_escrows_tracking as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_by_admin_projects_cancelled_escrows_query = $this->db->get_compiled_select();
		
		// for fulltime project reverted amount
		
	
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,"" as cancelled_escrow_description,ed.reverted_amount as reverted_escrowed_amount,"" as initial_escrow_creation_date,ed.dispute_close_date as escrow_cancellation_date,ed.dispute_reference_id');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.disputed_fulltime_project_id');
		$fulltime_open_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_expired_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_cancelled_disputed_escrow_reverted_amount_fields);
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.disputed_fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query = $this->db->get_compiled_select();
		
		
		
		
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_cancelled_escrows_query,
			$fixed_budget_incomplete_projects_reverted_cancelled_escrows_query,
			$fixed_budget_completed_projects_reverted_cancelled_escrows_query,
			$fixed_budget_completed_projects_cancelled_escrows_query,
			$hourly_rate_based_inprogress_projects_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_cancelled_escrows_query,
			$hourly_rate_based_incomplete_projects_reverted_cancelled_escrows_query,
			$hourly_rate_based_completed_projects_reverted_cancelled_escrows_query, 
			$fulltime_open_projects_cancelled_escrows_query,
			$fulltime_expired_projects_cancelled_escrows_query, 
			$fulltime_cancelled_projects_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_cancelled_escrows_query,
			$fulltime_open_projects_reverted_cancelled_escrows_query,
			$fulltime_expired_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_projects_reverted_cancelled_escrows_query,
			$fulltime_cancelled_by_admin_projects_reverted_cancelled_escrows_query
		];
		
		$cancelled_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_cancellation_date DESC LIMIT '.$limit_range);
		$result  = $cancelled_escrows_projects_query->result_array();
		
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of cancelled escrows of all projects for sp.
	*/
	public function get_sum_all_cancelled_escrows_amount_all_projects_sp($user_id,$params){
		
		$cancelled_escrow_table_array = array('fixed_budget_projects_cancelled_escrows_tracking','hourly_rate_based_projects_cancelled_escrows_tracking','fulltime_projects_cancelled_escrows_tracking');
		$sum_cancelled_escrow_amount_value = 0;
		foreach($cancelled_escrow_table_array as $cancelled_escrow_table_name){
			$this->db->select('SUM(reverted_escrowed_amount) as sum_cancelled_escrow_amount_value');
			$this->db->from($cancelled_escrow_table_name);
			
			if($cancelled_escrow_table_name == 'fulltime_projects_cancelled_escrows_tracking'){
				$this->db->where('employee_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('winner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_cancelled_escrow_result = $this->db->get();
			$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
			
			$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_escrow_amount_value'];
			
		}
		
		// reverted amount of dispute fixed project
		$this->db->select('SUM(reverted_amount) as sum_cancelled_reverted_amount_value');
		$this->db->from('fixed_budget_projects_closed_disputes_po_reverted_amounts');
		$this->db->where('sp_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('disputed_project_id',$params['project_id']);
		}
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
		$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_reverted_amount_value'];
		
		// reverted amount of dispute hourly project
		$this->db->select('SUM(reverted_amount) as sum_cancelled_reverted_amount_value');
		$this->db->from('hourly_rate_projects_closed_disputes_po_reverted_amounts');
		$this->db->where('sp_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('disputed_project_id',$params['project_id']);
		}
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
		$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_reverted_amount_value'];
		
		// reverted amount of dispute hourly project
		$this->db->select('SUM(reverted_amount) as sum_cancelled_reverted_amount_value');
		$this->db->from('fulltime_projects_closed_disputes_employer_reverted_amounts');
		$this->db->where('employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('disputed_fulltime_project_id',$params['project_id']);
		}
		$sum_cancelled_escrow_result = $this->db->get();
		$sum_cancelled_escrow = $sum_cancelled_escrow_result->row_array();
		$sum_cancelled_escrow_amount_value += $sum_cancelled_escrow['sum_cancelled_reverted_amount_value'];
		
		return $sum_cancelled_escrow_amount_value ;
	}
	
	/**
	* This function is used to fetch the all released escrows listing of all projects for po.
	*/
	public function get_all_released_escrows_listing_all_projects_po($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id');
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_expired_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_by_admin_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_released_escrows_query,
			$fixed_budget_incomplete_projects_released_escrows_query,
			$fixed_budget_completed_projects_released_escrows_query,
			$hourly_rate_based_inprogress_projects_released_escrows_query,
			$hourly_rate_based_incomplete_projects_released_escrows_query,
			$hourly_rate_based_completed_projects_released_escrows_query,
			$fulltime_open_projects_released_escrows_query,
			$fulltime_expired_projects_released_escrows_query,
			$fulltime_cancelled_projects_released_escrows_query,
			$fulltime_cancelled_by_admin_projects_released_escrows_query
		];
		
		$released_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_payment_release_date DESC LIMIT '.$limit_range);
		$result  = $released_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of released escrows of all projects for po.
	*/
	public function get_sum_all_released_escrows_amount_all_projects_po($user_id,$params){
		
		$released_escrow_table_array = array('fixed_budget_projects_released_escrows','hourly_rate_based_projects_released_escrows','fulltime_projects_released_escrows');
		$sum_released_escrow_amount_value = 0;
		foreach($released_escrow_table_array as $released_escrow_table_name){
			$this->db->select('SUM(released_escrow_payment_amount) as sum_released_escrow_amount_value');
			$this->db->from($released_escrow_table_name);
			
			if($released_escrow_table_name == 'fulltime_projects_released_escrows'){
				$this->db->where('employer_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('project_owner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_released_escrow_result = $this->db->get();
			$sum_released_escrow = $sum_released_escrow_result->row_array();
			
			$sum_released_escrow_amount_value += $sum_released_escrow['sum_released_escrow_amount_value'];
			
		}
		return $sum_released_escrow_amount_value ;
	}
	
	/**
		* This function is used to fetch the all released escrows listing of all projects for sp.
	*/
	public function get_all_released_escrows_listing_all_projects_sp($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_inprogress_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_incomplete_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_released_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_completed_projects_released_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.released_escrow_payment_description,ed.released_escrow_payment_amount,ed.escrow_payment_release_date,ed.dispute_reference_id');
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_expired_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_released_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_by_admin_projects_released_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_released_escrows_query,
			$fixed_budget_incomplete_projects_released_escrows_query,
			$fixed_budget_completed_projects_released_escrows_query,
			$hourly_rate_based_inprogress_projects_released_escrows_query,
			$hourly_rate_based_incomplete_projects_released_escrows_query,
			$hourly_rate_based_completed_projects_released_escrows_query,
			$fulltime_open_projects_released_escrows_query,
			$fulltime_expired_projects_released_escrows_query,
			$fulltime_cancelled_projects_released_escrows_query,
			$fulltime_cancelled_by_admin_projects_released_escrows_query
		];
		
		$released_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY escrow_payment_release_date DESC LIMIT '.$limit_range);
		$result  = $released_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of released escrows of all projects for sp.
	*/
	public function get_sum_all_released_escrows_amount_all_projects_sp($user_id,$params){
		
		$released_escrow_table_array = array('fixed_budget_projects_released_escrows','hourly_rate_based_projects_released_escrows','fulltime_projects_released_escrows');
		$sum_released_escrow_amount_value = 0;
		foreach($released_escrow_table_array as $released_escrow_table_name){
			$this->db->select('SUM(released_escrow_payment_amount) as sum_released_escrow_amount_value');
			$this->db->from($released_escrow_table_name);
			
			if($released_escrow_table_name == 'fulltime_projects_released_escrows'){
				$this->db->where('employee_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('winner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_released_escrow_result = $this->db->get();
			$sum_released_escrow = $sum_released_escrow_result->row_array();
			
			$sum_released_escrow_amount_value += $sum_released_escrow['sum_released_escrow_amount_value'];
			
		}
		return $sum_released_escrow_amount_value ;
	}
	
	
	/**
	* This function is used to fetch the all rejected requested escrows listing of all projects for po.
	*/
	public function get_all_rejected_requested_escrows_listing_all_projects_po($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date,ed.requested_escrow_rejection_date';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date,ed.requested_escrow_rejection_date';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date,ed.requested_escrow_rejection_date';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$fixed_budget_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.project_owner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.winner_id');
		$hourly_rate_based_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date,ed.requested_escrow_rejection_date');
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_expired_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employer_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employee_id');
		$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_rejected_requested_escrows_query,
			$fixed_budget_incomplete_projects_rejected_requested_escrows_query,
			$fixed_budget_completed_projects_rejected_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query,
			$hourly_rate_based_completed_projects_rejected_requested_escrows_query,
			$fulltime_open_projects_rejected_requested_escrows_query,
			$fulltime_expired_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query
		];
		
		$rejected_requested_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY requested_escrow_rejection_date DESC LIMIT '.$limit_range);
		$result  = $rejected_requested_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of rejected requested escrows of all projects for po.
	*/
	public function get_sum_all_rejected_requested_escrows_amount_all_projects_po($user_id,$params){
		
		$rejected_requested_escrow_table_array = array('fixed_budget_projects_rejected_requested_escrows','hourly_rate_based_projects_rejected_requested_escrows','fulltime_projects_rejected_requested_escrows');
		$sum_rejected_requested_escrow_amount_value = 0;
		foreach($rejected_requested_escrow_table_array as $rejected_requested_escrow_table_name){
			$this->db->select('SUM(requested_escrow_amount) as sum_rejected_requested_escrow_amount_value');
			$this->db->from($rejected_requested_escrow_table_name);
			
			if($rejected_requested_escrow_table_name == 'fulltime_projects_rejected_requested_escrows'){
				$this->db->where('employer_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('project_owner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_rejected_requested_escrow_result = $this->db->get();
			$sum_rejected_requested_escrow = $sum_rejected_requested_escrow_result->row_array();
			
			$sum_rejected_requested_escrow_amount_value += $sum_rejected_requested_escrow['sum_rejected_requested_escrow_amount_value'];
			
		}
		return $sum_rejected_requested_escrow_amount_value ;
	}
	
	
	/**
		* This function is used to fetch the all rejected requested escrows listing of all projects for sp.
	*/
	public function get_all_rejected_requested_escrows_listing_all_projects_sp($user_id,$start,$limit,$params){
		
		$limit_range = '';
		if($start != '' && $limit != '') {
			$limit_range = $start.','. $limit;
		} else if(isset($start)) {
			$limit_range = $limit;
		}
		
		$fixed_budget_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date,ed.requested_escrow_rejection_date';
		
		$hourly_rate_based_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_sp_date,ed.requested_escrow_rejection_date';
		$fulltime_projects_escrows_fields = 'u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.fulltime_project_id as project_id,pd.fulltime_project_title as project_title,pd.project_type,pd.employer_id as project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date,ed.requested_escrow_rejection_date';
		
		
		$this->db->select('SQL_CALC_FOUND_ROWS '.$fixed_budget_projects_escrows_fields,false);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		
		$this->db->join('fixed_budget_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fixed_budget_projects_escrows_fields);
		$this->db->from('fixed_budget_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('fixed_budget_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$fixed_budget_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_progress as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_incomplete as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($hourly_rate_based_projects_escrows_fields);
		$this->db->from('hourly_rate_based_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.winner_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('hourly_rate_based_projects_completed as pd', 'pd.project_id = ed.project_id');
		$this->db->join('users as u', 'u.user_id = ed.project_owner_id');
		$hourly_rate_based_completed_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		
		$this->db->select('u.first_name,u.last_name,u.company_name,u.account_type,u.is_authorized_physical_person,u.profile_name,pd.project_id,pd.project_title,pd.project_type,pd.project_owner_id,ed.employee_id as winner_id,ed.requested_escrow_description,ed.requested_escrow_amount,ed.escrow_requested_by_employee_date as escrow_requested_by_sp_date,ed.requested_escrow_rejection_date');
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.project_id',$params['project_id']);
		}
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$this->db->join('projects_open_bidding as pd', 'pd.project_id = ed.fulltime_project_id');
		$fulltime_open_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_expired as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_expired_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$this->db->select($fulltime_projects_escrows_fields);
		$this->db->from('fulltime_projects_rejected_requested_escrows as ed');
		$this->db->where('ed.employee_id',$user_id);
		if($params['project_id'] != 0){
			$this->db->where('pd.fulltime_project_id',$params['project_id']);
		}
		$this->db->join('fulltime_projects_cancelled_by_admin as pd', 'pd.fulltime_project_id = ed.fulltime_project_id');
		$this->db->join('users as u', 'u.user_id = ed.employer_id');
		$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query = $this->db->get_compiled_select();
		
		$union_table_name = [
			$fixed_budget_inprogress_projects_rejected_requested_escrows_query,
			$fixed_budget_incomplete_projects_rejected_requested_escrows_query,
			$fixed_budget_completed_projects_rejected_requested_escrows_query,
			$hourly_rate_based_inprogress_projects_rejected_requested_escrows_query,
			$hourly_rate_based_incomplete_projects_rejected_requested_escrows_query,
			$hourly_rate_based_completed_projects_rejected_requested_escrows_query,
			$fulltime_open_projects_rejected_requested_escrows_query,
			$fulltime_expired_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_projects_rejected_requested_escrows_query,
			$fulltime_cancelled_by_admin_projects_rejected_requested_escrows_query
		];
		
		$rejected_requested_escrows_projects_query = $this->db->query(implode(' UNION ', $union_table_name).' ORDER BY requested_escrow_rejection_date DESC LIMIT '.$limit_range);
		$result  = $rejected_requested_escrows_projects_query->result_array();
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;
		return ['data' => $result, 'total' => $total_rec];
	}
	
	/**
	* This function is used to sum of rejected requested escrows of all projects for sp.
	*/
	public function get_sum_all_rejected_requested_escrows_amount_all_projects_sp($user_id,$params){
		
		$rejected_requested_escrow_table_array = array('fixed_budget_projects_rejected_requested_escrows','hourly_rate_based_projects_rejected_requested_escrows','fulltime_projects_rejected_requested_escrows');
		$sum_rejected_requested_escrow_amount_value = 0;
		foreach($rejected_requested_escrow_table_array as $rejected_requested_escrow_table_name){
			$this->db->select('SUM(requested_escrow_amount) as sum_rejected_requested_escrow_amount_value');
			$this->db->from($rejected_requested_escrow_table_name);
			
			if($rejected_requested_escrow_table_name == 'fulltime_projects_rejected_requested_escrows'){
				$this->db->where('employee_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('fulltime_project_id',$params['project_id']);
				}
			}else{
				$this->db->where('winner_id',$user_id);
				if($params['project_id'] != 0){
					$this->db->where('project_id',$params['project_id']);
				}
			}
			$sum_rejected_requested_escrow_result = $this->db->get();
			$sum_rejected_requested_escrow = $sum_rejected_requested_escrow_result->row_array();
			
			$sum_rejected_requested_escrow_amount_value += $sum_rejected_requested_escrow['sum_rejected_requested_escrow_amount_value'];
			
		}
		return $sum_rejected_requested_escrow_amount_value ;
	}
	
	// Generate pagination links user projects payment overview page
	/* public function generate_pagination_links_user_projects_payments_overview($total, $url,$record_per_page) {
		
		$this->load->library ('pagination');
		$project_id = $params['project_id'];
		$po_id = Cryptor::doEncrypt($params['po_id']);
		$sp_id = Cryptor::doEncrypt($params['sp_id']);
		$bid_id = $params['bid_id'];
		$p_type = $params['project_type'];
		$tab_type = $params['tab_type'];
		$section_name = $params['section_name'];
        $config = array();
        $config["base_url"] = base_url($url);
        $config["total_rows"] = $total;
        $config["per_page"] = $record_per_page;
        $config["uri_segment"] = 3;
        $config["use_page_numbers"] = TRUE;
        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';
        $config['first_link'] = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
        $config["first_tag_open"] = '<li class="page-item ">';
        $config["first_tag_close"] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
        $config["last_tag_open"] = '<li class="page-item">';
        $config["last_tag_close"] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
        $config["next_tag_open"] = '<li class="page-item">';
        $config["next_tag_close"] = '</li>';
        $config["prev_link"] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
        $config["prev_tag_open"] = "<li class='page-item'>";
        $config["prev_tag_close"] = "</li>";
		
        $config["cur_tag_open"] = "<li class='active'><a class='page-link '  href='".base_url($url)."'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li class='page-item'>";
        $config["num_tag_close"] = "</li>";
        $config['attributes'] = array('class' => 'page-link');
        $config["num_links"] = $this->config->item('user_projects_payments_overview_page_number_of_pagination_links');
		$page = $this->uri->segment(3);
	
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    } */
}
?>