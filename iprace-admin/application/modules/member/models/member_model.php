<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class  Member_model extends BaseModel {

	public function __construct() {
			return parent::__construct();
	}
	public function getAllMemberList($lim_to,$lim_from,$s_key="") { 
			// 	user_id	username	password	fname	lname	gender	status	email	mobile	address	country	zip	image	reg_date	edit_date	ldate	v_stat	ip	acc_balance	slogan	logo	overview	work_experience	hourly_rate	qualification	certification	education	asclient_aboutus	membership_plan	membership_start	membership_end
      $this->db->select('u.*, ud.current_membership_plan_id, ud.user_avatar, c.country_name as country, ud.user_account_balance,mp.membership_plan_name,uas.additional_dropdpwn_on_find_professionals_page');
			$this->db->select('uas.user_profile_not_displayed_on_find_professionals_page, uas.user_profile_page_not_accessible, ufpt.user_id as featured_user_profile, ufpt.featured_profile_enabled_date');
			$this->db->from('users u');
      $this->db->order_by('u.user_id',"desc");
			$this->db->group_by('u.user_id');
			$this->db->limit($lim_to,$lim_from);
			
			$this->db->join('users_details ud', 'ud.user_id=u.user_id', 'left');
			$this->db->join('users_address_details uad', 'uad.user_id=u.user_id', 'left');
			$this->db->join('countries c', 'c.id=uad.country_id', 'left');
			$this->db->join('membership_plans mp', 'mp.id=ud.current_membership_plan_id', 'left');
			$this->db->join('users_additional_accesses_settings uas', 'uas.user_id=u.user_id', 'left');
			$this->db->join('users_featured_profiles_tracking_on_find_professionals_page ufpt', 'ufpt.user_id=u.user_id', 'left');
			
			$rs = $this->db->get();
			$data = array();
			
			foreach($rs->result() as $row) {
				
				$data[] = array(
					'user_id'=>$row->user_id,
					'username' => $row->profile_name,
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'company_name' => $row->company_name,
					'email' => $row->email,
					'reg_date' => $row->account_validation_date,
					'logo' => $row->user_avatar,
					'country' => $row->country,
					'city' => '',
					'acc_balance' => $row->user_account_balance,
					'latest_login_date' => $row->latest_login_date,
					'status' => '',
					'membership_plan' => $row->current_membership_plan_id,
					'account_type' => $row->account_type,
					'gender' => $row->gender,
					'plan' => $row->membership_plan_name,
					'additional_dropdpwn_on_find_professionals_page' => $row->additional_dropdpwn_on_find_professionals_page,
					'user_profile_not_displayed_on_find_professionals_page' => $row->user_profile_not_displayed_on_find_professionals_page,
					'user_profile_page_not_accessible' => $row->user_profile_page_not_accessible,
					'featured_user_profile' => $row->featured_user_profile,
					'featured_profile_enabled_date' => $row->featured_profile_enabled_date,
					'is_authorized_physical_person' => $row->is_authorized_physical_person
				);

			}
			return $data;
	}
	
	public function getFilterMemberList($filter_arr, $lim_to = '',$lim_from = '') { 

		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS u.user_id', false);
		$this->db->select('u.*, ud.current_membership_plan_id, ud.user_avatar, c.country_name as country, ud.user_account_balance, mp.membership_plan_name,uas.additional_dropdpwn_on_find_professionals_page');
		$this->db->select('uas.user_profile_not_displayed_on_find_professionals_page, uas.user_profile_page_not_accessible,  ufpt.user_id as featured_user_profile,ufpt.featured_profile_enabled_date');
		$this->db->from('users u');
		$this->db->limit($lim_to, $lim_from);		
		$this->db->join('users_details ud', 'ud.user_id=u.user_id', 'left');
		$this->db->join('users_address_details uad', 'uad.user_id=u.user_id', 'left');
		$this->db->join('countries c', 'c.id=uad.country_id', 'left');  
		$this->db->join('membership_plans mp', 'mp.id=ud.current_membership_plan_id', 'left');
		$this->db->join('users_additional_accesses_settings uas', 'uas.user_id=u.user_id', 'left');
		$this->db->join('users_featured_profiles_tracking_on_find_professionals_page ufpt', 'ufpt.user_id=u.user_id', 'left');
		$this->db->join('users_address_details uadd', 'uadd.user_id = u.user_id', 'left');
		$this->db->join('users_company_accounts_additional_branches_addresses ubael', 'ubael.user_id = u.user_id', 'left');


		if(!empty($filter_arr)) {
			if(!empty($filter_arr['plan']) && strpos($filter_arr['plan'], 'all') == false) {
				$this->db->where('mp.id', $filter_arr['plan']);
			}

			if(!empty($filter_arr['additional_settings']) && strpos($filter_arr['additional_settings'], 'all') == false) {
				if($filter_arr['additional_settings'] == 'additional_dropdpwn_find_professional_page') {
					$this->db->where('uas.additional_dropdpwn_on_find_professionals_page', 'Y');
				} else if($filter_arr['additional_settings'] == 'featured_profile_on_find_professionals_page') {
					$this->db->where('(ufpt.user_id IS NOT NULL)');
				}
			}

			if(!empty($filter_arr['banned_profile'])) {
				if(count($filter_arr['banned_profile']) == 1 && in_array('banned_profile_on_find_professional_page', $filter_arr['banned_profile'])) {
					$this->db->where('(uas.user_profile_not_displayed_on_find_professionals_page = "Y")');
				} else if(count($filter_arr['banned_profile']) == 1 && in_array('banned_profile_on_user_profile_page', $filter_arr['banned_profile'])) {
					$this->db->where('(uas.user_profile_page_not_accessible = "Y")');
				} else {
					$this->db->where('(uas.user_profile_not_displayed_on_find_professionals_page = "Y" OR uas.user_profile_page_not_accessible = "Y")');
				}
			}

			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
		
			if($filter_arr['search_title_flag'] == "false" && !empty($filter_arr['searchtxt_arr']) && $filter_arr['search_type'] == 'include') {
				$schr = array_map(function($val){
					$val = trim(htmlspecialchars($val, ENT_QUOTES));
					$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
					if(!empty($val)) {
						$val = $val.'*';
					}
					return $val;
				}, $filter_arr['searchtxt_arr']);
				$schr = implode(' ', $schr);
				
					$this->db->where("MATCH(u.first_name, u.last_name, u.company_name, u.profile_name) AGAINST('".$schr."' IN BOOLEAN MODE)");
					// $this->db->where("MATCH(fpui.user_first_name, fpui.user_last_name, fpui.company_name, fpui.description) AGAINST('".$schr."' IN BOOLEAN MODE)");
				
			} else if($filter_arr['search_title_flag'] == "false" && !empty($filter_arr['searchtxt_arr']) && $filter_arr['search_type'] == 'exclude') {
				$schr = array_map(function($val){
					$val = trim(htmlspecialchars($val, ENT_QUOTES));
					$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
					if(!empty($val)) {
						$val = '+'.$val.'*';
					}
					return $val;
				}, $filter_arr['searchtxt_arr']);
				$schr = implode(' ', $schr);
				
					$this->db->where("MATCH(u.first_name, u.last_name, u.company_name, u.profile_name) AGAINST('".$schr."' IN BOOLEAN MODE)");
				
			}
			// search text in project title only
			if($filter_arr['search_title_flag'] == "true" && !empty($filter_arr['searchtxt_arr']) && is_array($filter_arr['searchtxt_arr'])) {
				
					$schr = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					},  $filter_arr['searchtxt_arr']);
					$schr = implode(' ', $schr);
					$this->db->where("(MATCH(u.first_name, u.last_name, u.company_name, u.profile_name) AGAINST('".$schr."' IN BOOLEAN MODE))");
				
			}


			// filter professional listing based on location search
			if(!empty($filter_arr['location'])) {
				$county = [];
				$locality = [];
				$country = [];
				foreach($filter_arr['location'] as $val) {
					if($val['continent'] == 'county') {
						array_push($county, explode('-', $val['value'])[0]);
					} else if($val['continent'] == 'locality') {
						array_push($locality, explode('-', $val['value'])[0]);
					} else {
						array_push($country, explode('-', $val['value'])[0]);
					}
				}

				
					if(!empty($country)) {
						$this->db->where_in('uadd.country_id', $country);
						$this->db->or_where_in('ubael.country_id', $country);
					} else if(empty($country) && !empty($county)) {
						$this->db->where_in('uadd.county_id', $county);
						$this->db->or_where_in('ubael.county_id', $county);
					} 
					
					if(!empty($country) && !empty($county)) {
						$this->db->or_where_in('uadd.county_id', $county);
						$this->db->or_where_in('ubael.county_id', $county);
					} 
					if(empty($county) && empty($country) && !empty($locality)) {
						
						$this->db->where_in('uadd.locality_id', $locality);
						$this->db->or_where_in('ubael.locality_id', $locality);
					} else if((empty($county) && !empty($locality)) || (empty($country) && !empty($locality))) {
						
						$this->db->or_where_in('uadd.locality_id', $locality);
						$this->db->or_where_in('ubael.locality_id', $locality);
					}
					// $this->db->group_end();
			}
		}
		
		$this->db->order_by('u.user_id', 'desc');
		$rs = $this->db->get();
		// pre($this->db->last_query());
		// pre($rs->result());
		$data = array();
		foreach($rs->result() as $row) {
				$data[] = array(
					'user_id'=>$row->user_id,
					'username' => $row->profile_name,
					'first_name' => $row->first_name,
					'last_name' => $row->last_name,
					'company_name' => $row->company_name,
					'email' => $row->email,
					'reg_date' => $row->account_validation_date,
					'logo' => $row->user_avatar,
					'country' => $row->country,
					'city' => '',
					'acc_balance' => $row->user_account_balance,
					'latest_login_date' => $row->latest_login_date,
					'status' => '',
					'membership_plan' => $row->current_membership_plan_id,
					'account_type' => $row->account_type,
					'gender' => $row->gender,
					'plan' => $row->membership_plan_name,
					'additional_dropdpwn_on_find_professionals_page' => $row->additional_dropdpwn_on_find_professionals_page,
					'user_profile_not_displayed_on_find_professionals_page' => $row->user_profile_not_displayed_on_find_professionals_page,
					'user_profile_page_not_accessible' => $row->user_profile_page_not_accessible,
					'featured_user_profile' => $row->featured_user_profile,
					'featured_profile_enabled_date' => $row->featured_profile_enabled_date,
					'is_authorized_physical_person' => $row->is_authorized_physical_person
				);

		}

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;

		return ['data' => $data, 'total' => $total_rec];
	}
	
	public function deleteMember($id, $config = []) {
		########################################### user as po has awarded/inprogress/completed project open ##################################################
		// Fixed
		$fixed_awarded = $this->db->get_where('fixed_budget_projects_awarded', ['project_owner_id' => $id])->result_array();
		if(!empty($fixed_awarded)) { return false;}
		$fixed_progress = $this->db->get_where('fixed_budget_projects_progress', ['project_owner_id' => $id])->result_array();
		if(!empty($fixed_progress)) {return false;}
		$fixed_completed = $this->db->get_where('fixed_budget_projects_completed', ['project_owner_id' => $id])->result_array();
		if(!empty($fixed_completed)) {return false;}

		// Hourly
		$hourly_awarded = $this->db->get_where('hourly_rate_based_projects_awarded', ['project_owner_id' => $id])->result_array();
		if(!empty($hourly_awarded)) { return false;}
		$hourly_progress = $this->db->get_where('hourly_rate_based_projects_progress', ['project_owner_id' => $id])->result_array();
		if(!empty($hourly_progress)) {return false;}
		$hourly_completed = $this->db->get_where('hourly_rate_based_projects_completed', ['project_owner_id' => $id])->result_array();
		if(!empty($hourly_completed)) {return false;}

		// Fulltime
		$this->db->select('fpat.*');
		$this->db->from('fulltime_projects_awarded_tracking fpat');
		$this->db->join('projects_open_bidding op', 'op.project_id = fpat.fulltime_project_id');
		$this->db->where('op.project_owner_id', $id);
		$this->db->where('op.project_type', 'fulltime');
		$fulltime_open_awarded = $this->db->get()->result_array();
		if(!empty($fulltime_open_awarded)) { return false; }
		$this->db->select('fpat.*');
		$this->db->from('fulltime_projects_hired_employees_tracking fpat');
		$this->db->join('projects_open_bidding op', 'op.project_id = fpat.fulltime_project_id');
		$this->db->where('op.project_owner_id', $id);
		$this->db->where('op.project_type', 'fulltime');
		$fulltime_open_hired = $this->db->get()->result_array();
		if(!empty($fulltime_open_hired)) { return false; }
		$this->db->select('fpat.*');
		$this->db->from('fulltime_projects_awarded_tracking fpat');
		$this->db->join('fulltime_projects_expired op', 'op.fulltime_project_id = fpat.fulltime_project_id');
		$this->db->where('op.employer_id', $id);
		$fulltime_expired_awarded = $this->db->get()->result_array();
		if(!empty($fulltime_expired_awarded)) { return false; }
		$this->db->select('fpat.*');
		$this->db->from('fulltime_projects_hired_employees_tracking fpat');
		$this->db->join('fulltime_projects_expired op', 'op.fulltime_project_id = fpat.fulltime_project_id');
		$this->db->where('op.employer_id', $id);
		$fulltime_expired_hired = $this->db->get()->result_array();
		if(!empty($fulltime_expired_hired)) { return false; }

		#####################################################################################################################################################

		##################################################### user as sp in awarded/inprogress/completed bid status ########################################

		// Fixed
		$fixed_sp_awarded = $this->db->get_where('fixed_budget_projects_awarded_tracking', ['winner_id' => $id])->result_array();
		if(!empty($fixed_sp_awarded)) { return false;}
		$fixed_sp_progress = $this->db->get_where('fixed_budget_projects_progress_sp_bid_reference', ['winner_id' => $id])->result_array();
		if(!empty($fixed_sp_progress)) { return false;}
		$fixed_sp_completed = $this->db->get_where('fixed_budget_projects_completed_tracking', ['winner_id' => $id])->result_array();
		if(!empty($fixed_sp_completed)) { return false;}

		// Hourly
		$hourly_sp_awarded = $this->db->get_where('hourly_rate_based_projects_awarded_tracking', ['winner_id' => $id])->result_array();
		if(!empty($hourly_sp_awarded)) { return false;}
		$hourly_sp_progress = $this->db->get_where('hourly_rate_based_projects_progress_sp_bid_reference', ['winner_id' => $id])->result_array();
		if(!empty($hourly_sp_progress)) { return false;}
		$hourly_sp_completed = $this->db->get_where('hourly_rate_based_projects_completed_tracking', ['winner_id' => $id])->result_array();
		if(!empty($hourly_sp_completed)) { return false;}

		// Fulltime
		$fulltime_sp_awarded = $this->db->get_where('fulltime_projects_awarded_tracking', ['employee_id' => $id])->result_array();
		if(!empty($fulltime_sp_awarded)) { return false;}
		$fulltime_sp_progress = $this->db->get_where('fulltime_projects_hired_employees_tracking', ['employee_id' => $id])->result_array();
		if(!empty($fulltime_sp_progress)) { return false;}
		####################################################################################################################################################
		
		$this->db->where(array('user_id' => $id));
		$row = $this->db->get('users')->row_array();
		
		$delete_flag = $this->db->delete('users', array('user_id' => $id));
		if($delete_flag) {
			$this->db->where('lvl1_referrer_id', $id);
			$data =[
				'lvl1_referrer_code' => null,
				'referee_source' => null,
				'lvl1_referrer_id' => null
			];
			$this->db->update('users', $data);
		}
		$this->db->delete('users_details', array('user_id' => $id));
		$this->db->delete('users_membership_plans_downgrade_tracking', array('user_id' => $id));
		$this->db->delete('users_membership_plans_upgrade_tracking', array('user_id' => $id));
		$this->db->update('users_referrals_tracking', ['lvl2_referrer_id' => null, 'lvl2_referrer_account_validation_date' => null], ['lvl2_referrer_id' => $id]);
		$this->db->delete('users_referrals_tracking', array('user_id' => $id));
		$this->db->delete('users_referrals_tracking', array('lvl1_referrer_id' => $id));

		######################################### user as po #####################################################################
		$open_bidding_project = $this->db->get_where('projects_open_bidding', ['project_owner_id' => $id])->result_array();
		$open_project_ids = array_column($open_bidding_project, 'project_id');
		if(!empty($open_project_ids)) {
			$this->db->where_in('project_id', $open_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $open_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $open_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('projects_open_bidding', ['project_owner_id' => $id]);
		
		$draft_project = $this->db->get_where('projects_draft', ['project_owner_id' => $id])->result_array();
		$draft_project_ids = array_column($draft_project, 'project_id');

		if(!empty($draft_project_ids)) {
			$this->db->where_in('project_id', $draft_project_ids);
			$this->db->delete('draft_projects_attachments');

			$this->db->where_in('project_id', $draft_project_ids);
			$this->db->delete('draft_projects_categories_listing_tracking');

			$this->db->where_in('project_id', $draft_project_ids);
			$this->db->delete('draft_projects_tags');
		}
		$this->db->delete('projects_draft', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('fixed_budget_projects_expired', ['project_owner_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('fixed_budget_projects_expired', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('fixed_budget_projects_cancelled', ['project_owner_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('fixed_budget_projects_cancelled', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('fixed_budget_projects_cancelled_by_admin', ['project_owner_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('fixed_budget_projects_cancelled_by_admin', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('hourly_rate_based_projects_expired', ['project_owner_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('hourly_rate_based_projects_expired', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('hourly_rate_based_projects_cancelled', ['project_owner_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('hourly_rate_based_projects_cancelled', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('hourly_rate_based_projects_cancelled_by_admin', ['project_owner_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('hourly_rate_based_projects_cancelled_by_admin', ['project_owner_id' => $id]);

		$fixed_project = $this->db->get_where('fulltime_projects_expired', ['employer_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'fulltime_project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('fulltime_projects_expired', ['employer_id' => $id]);

		$fixed_project = $this->db->get_where('fulltime_projects_cancelled', ['employer_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'fulltime_project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('fulltime_projects_cancelled', ['employer_id' => $id]);

		$fixed_project = $this->db->get_where('fulltime_projects_cancelled_by_admin', ['employer_id' => $id])->result_array();
		$fixed_project_ids = array_column($fixed_project, 'fulltime_project_id');
		if(!empty($fixed_project_ids)) {
			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_attachments');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_categories_listing_tracking');

			$this->db->where_in('project_id', $fixed_project_ids);
			$this->db->delete('projects_tags');
		}
		$this->db->delete('fulltime_projects_cancelled_by_admin', ['employer_id' => $id]);
		##############################################################################################################################

		$bidder_attachments = $this->db->get_where('projects_active_bids_users_attachments_tracking', ['user_id' => $id])->result_array();

		############################################################ user as sp ######################################################
		$this->db->delete('projects_active_bids_users_attachments_tracking', ['user_id' => $id]);
		$this->db->delete('fixed_budget_projects_open_bidding_active_bids', ['bidder_id' => $id]);
		$this->db->delete('hourly_rate_based_projects_open_bidding_active_bids', ['bidder_id' => $id]);
		$this->db->delete('fulltime_projects_open_bidding_active_applications', ['employee_id' => $id]);
		#############################################################################################################################
		$this->db->delete('user_log', array('user_id' => $id));
		$this->load->library('ftp');
		$cnf['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
		$cnf['ftp_username'] = FTP_USERNAME;
		$cnf['ftp_password'] = FTP_PASSWORD;
		$cnf['ftp_port'] = FTP_PORT;
		$cnf['debug'] = TRUE;
		try {
			$this->ftp->connect($cnf);
		} catch(Exception $e) {

		}
		if(!empty($bidder_attachments)) {
			$project_ids = array_column($bidder_attachments, 'project_id');
			try {
				$users_ftp_dir 	= USERS_FTP_DIR; 
				$projects_ftp_dir = PROJECTS_FTP_DIR;
				$project_open_for_bidding_dir = PROJECT_OPEN_FOR_BIDDING_DIR;
				$project_expired_dir = PROJECT_EXPIRED_DIR;
				$project_cancelled_dir = PROJECT_CANCELLED_DIR;
				$users_bid_attachments_dir = USERS_BID_ATTACHMENTS_DIR;
				$user_folder_list = $this->ftp->list_files($users_ftp_dir);
				foreach($user_folder_list as $user) { 
					$user_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_open_for_bidding_dir);
					$user_expired_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_expired_dir);
					$user_cancelled_projects = $this->ftp->list_files($user.$projects_ftp_dir.$project_cancelled_dir);
					foreach($user_projects as $project) { 
						if(!empty($project)) { 
							if(!empty($this->ftp->check_ftp_directory_exist($project))) {
								$arr = explode('/', $project);
								if(ctype_digit($arr[5]) && in_array($arr[5], $project_ids)) { 
									$project .= $users_bid_attachments_dir.$id;
									$project .= DIRECTORY_SEPARATOR;
									if(!empty($this->ftp->check_ftp_directory_exist($project))) {
										$this->ftp->delete_dir($project);
									}
								}
							}
						}
					}
					foreach($user_expired_projects as $project) { 
						if(!empty($project)) { 
							if(!empty($this->ftp->check_ftp_directory_exist($project))) {
								$arr = explode('/', $project);
								if(ctype_digit($arr[5]) && in_array($arr[5], $project_ids)) { 
									$project .= $users_bid_attachments_dir.$id;
									$project .= DIRECTORY_SEPARATOR;
									if(!empty($this->ftp->check_ftp_directory_exist($project))) {
										$this->ftp->delete_dir($project);
									}
								}
							}
						}
					}
					foreach($user_cancelled_projects as $project) { 
						if(!empty($project)) { 
							if(!empty($this->ftp->check_ftp_directory_exist($project))) {
								$arr = explode('/', $project);
								if(ctype_digit($arr[5]) && in_array($arr[5], $project_ids)) { 
									$project .= $users_bid_attachments_dir.$id;
									$project .= DIRECTORY_SEPARATOR;
									if(!empty($this->ftp->check_ftp_directory_exist($project))) {
										$this->ftp->delete_dir($project);
									}
								}
							}
						}
					}
				}
				
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			
		}
		// remove user directory and it's sub-directory from disk
		if(!empty($row) && !empty($row['profile_name'])) {
			$users_ftp_dir 	= USERS_FTP_DIR;
			$this->ftp->delete_dir($users_ftp_dir.$row['profile_name'].DIRECTORY_SEPARATOR);
		}
		$this->ftp->close();
		return $delete_flag; 
	}
	        
	
	public function record_count_member() 
	{
    return $this->db->count_all('users');
	}
        
	public function getMembership()
	{
		$this->db->select('id, membership_plan_name');
                $this->db->order_by("id","desc");
		$res=$this->db->get('membership_plans');	
		$data=array();
		foreach($res->result() as $row)
		{
			$data[]=array(
			'id'=>$row->id,
			'name'=>$row->membership_plan_name
			);	
		}
		return $data;
	}
  
	//This function is used to update the profile completion data of user
	public function update_user_profile_completion_data($user_profile_completion_data,$user_data=array()){
		include '../application/config/'.SITE_LANGUAGE.'_dashboard_custom_config.php';
		include '../application/config/'.SITE_LANGUAGE.'_server_custom_config.php';
		
		$user_id = $user_data['user_id'];
		$user_account_type = $user_data['account_type'];
		
	
		$check_users_accounts_profile_completion_tracking = $this->db->where(['user_id' => $user_id])->from('users_accounts_profile_completion_tracking')->count_all_results();
		if($check_users_accounts_profile_completion_tracking == 0){
			
			$user_profile_completion_data['user_id'] = $user_id;
			if($user_account_type  == USER_PERSONAL_ACCOUNT_TYPE){
				$user_profile_completion_data['user_account_type'] = USER_PERSONAL_ACCOUNT_TYPE;
				
			}elseif($user_account_type  == USER_COMPANY_ACCOUNT_TYPE){
				$user_profile_completion_data['user_account_type'] = USER_COMPANY_ACCOUNT_TYPE;
			}
			
			$this->db->insert ('users_accounts_profile_completion_tracking', $user_profile_completion_data);
		}else{
			$this->db->update('users_accounts_profile_completion_tracking', $user_profile_completion_data, ['user_id'=> $user_id]);
		}
		$user_profile_completion = 0;
		if($user_account_type  == USER_PERSONAL_ACCOUNT_TYPE){ 
			$user_profile_completion_parameters_tracking_options_value =  $config['user_personal_account_type_profile_completion_parameters_tracking_options_value'];
		}if($user_account_type  == USER_COMPANY_ACCOUNT_TYPE){
			$user_profile_completion_parameters_tracking_options_value =  $config['user_company_account_type_profile_completion_parameters_tracking_options_value'];
		}
		$get_user_profile_completion_data = $this->db->get_where('users_accounts_profile_completion_tracking', ['user_id' => $user_id])->row_array();
		if(!empty($get_user_profile_completion_data)){
			
			if($get_user_profile_completion_data['has_avatar'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['avatar_strength_value'];
			}
			$users_address_details_data = $this->db // get the user detail
			->select('*')
			->from('users_address_details')
			->where('user_id', $user_id)
			->get()->row_array();
			if(!empty($users_address_details_data) && !empty($users_address_details_data['country_id']) && $users_address_details_data['country_id'] != 0 ){
			
				if($users_address_details_data['country_id'] == $config['reference_country_id']){
				
					if($get_user_profile_completion_data['has_country_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
					}if($get_user_profile_completion_data['has_county_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['county_address_strength_value'];
					}if($get_user_profile_completion_data['has_locality_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['locality_address_strength_value'];
					}if($get_user_profile_completion_data['has_street_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['street_address_strength_value'];
					}
				}else{
					if($get_user_profile_completion_data['has_country_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['country_address_strength_value'];
					}
					if($get_user_profile_completion_data['has_street_address_indicated'] == 'Y'){
						$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['street_address_when_country_not_cz_strength_value'];
					}
				}
			}
			if($get_user_profile_completion_data['has_phone_or_mobile_number_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['phone_or_mobile_number_strength_value'];
			}
			if($get_user_profile_completion_data['has_contact_email_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['contact_email_strength_value'];
			}if($get_user_profile_completion_data['has_headline_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['headline_strength_value'];
			}if($get_user_profile_completion_data['has_description_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['description_strength_value'];
			}if($get_user_profile_completion_data['has_areas_of_expertise_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['areas_of_expertise_strength_value'];
			}if($get_user_profile_completion_data['has_skills_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['skills_strength_value'];
			}if($get_user_profile_completion_data['has_services_provided_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['services_provided_strength_value'];
			}
			if($user_account_type  == USER_PERSONAL_ACCOUNT_TYPE){ 
				if($get_user_profile_completion_data['has_mother_tongue_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['mother_tongue_strength_value'];
				}if($get_user_profile_completion_data['has_spoken_foreign_languages_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['spoken_foreign_languages_strength_value'];
				}if($get_user_profile_completion_data['has_work_experience_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['work_experience_strength_value'];
				}if($get_user_profile_completion_data['has_education_training_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['education_training_strength_value'];
				}
			}
			if($get_user_profile_completion_data['has_certifications_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['certifications_strength_value'];
			}if($get_user_profile_completion_data['has_portfolio_indicated'] == 'Y'){
				$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['portfolio_strength_value'];
			}
			if($user_account_type  == USER_COMPANY_ACCOUNT_TYPE){ 
				if($get_user_profile_completion_data['has_company_founded_year_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_founded_year_strength_value'];
				}if($get_user_profile_completion_data['has_company_size_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_size_strength_value'];
				}if($get_user_profile_completion_data['has_company_opening_hours_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_opening_hours_strength_value'];
				}if($get_user_profile_completion_data['has_company_vision_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_values_strength_value'];
				}if($get_user_profile_completion_data['has_company_mission_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_mission_strength_value'];
				}if($get_user_profile_completion_data['has_company_core_values_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_core_values_strength_value'];
				}if($get_user_profile_completion_data['has_company_strategy_goals_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_strategy_goals_strength_value'];
				}if($get_user_profile_completion_data['has_company_invoicing_details_indicated'] == 'Y'){
					$user_profile_completion += $user_profile_completion_parameters_tracking_options_value['company_invoicing_details_strength_value'];
				}
			}
			
		}
		
		
		$this->db->update('users_accounts_profile_completion_tracking', ['user_profile_completion_percentage'=>$user_profile_completion], ['user_id'=> $user_id]);
		
		$get_user_accounts_profile_completion_data = $this->db // get the user detail
		->select('user_profile_completion_percentage')
		->from('users_accounts_profile_completion_tracking')
		->where('user_id', $user_id)
		->get()->row_array();
		if(floatval($get_user_accounts_profile_completion_data['user_profile_completion_percentage']) == 0){
			$this->db->delete('users_accounts_profile_completion_tracking', array("user_id" => $user_id));
		}	
	}
	
	
	// This function is using to copy the users/index.php(404 content file) file into every folder/sub folder exists into users/{user_profile_name folder}
	public function check_and_create_user_subfolders_on_disk_as_per_need($destination_path){
		$this->ftp->mkdir($destination_path, 0777);
	}


	// This method is used to get count locality and country details to display on member list page
	public function get_all_county_locality_to_display($str) {
		$display = [];
		$this->db->like('LOWER(name)', strtolower($str));
		$county = $this->db->get('counties')->result_array();

		$this->db->like('LOWER(country_name)', strtolower($str));
		$countries = $this->db->get('countries')->result_array();

		$this->db->like('LOWER(name)', strtolower($str));
		$locality = $this->db->get('localities')->result_array();

		if(!empty($countries)) {
			foreach($countries as $key => $val) {
				$tmp = [
					'value' => $val['id'].'-con',
					'text' => htmlspecialchars($val['country_name'], ENT_QUOTES).' (country)',
					'continent' => 'country'
				];
				array_push($display, $tmp);
			}
		}
		if(!empty($county)) {
			foreach($county as $key => $val) {
				$tmp = [
					'value' => $val['id'].'-c',
					'text' => htmlspecialchars($val['name'], ENT_QUOTES).' (county)',
					'continent' => 'county'
				];
				array_push($display, $tmp);
			}
		}
		if(!empty($locality)) {
			foreach($locality as $val) {
				$tmp = [
					'value' => $val['id'].'-l',
					'text' => htmlspecialchars($val['name'], ENT_QUOTES).' ('.$val['county_code'].')',
					'continent' => 'locality'
				];
				array_push($display, $tmp);
			}
		}
		return $display;
	}
    
}