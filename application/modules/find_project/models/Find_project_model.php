<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Find_project_model extends BaseModel
{

    public function __construct ()
    {
        return parent::__construct ();
    }
    // get parent child category array for find project page
    public function get_category_tree() {
        $this->db->where(['parent_id' => 0, 'status' => 'Y']);
        $this->db->order_by('name', 'ASC');
       $parent_categories = $this->db->get('categories_projects')->result_array();
       $categories = $this->db->get_where('categories_projects', ['parent_id != ' => 0, 'status' => 'Y'])->result_array();
       $category_tree = [];
       if(!empty($parent_categories)) {
            foreach($parent_categories as $pkey => $pcategory) {
                array_push($category_tree, $pcategory);
                $category_tree[$pkey]['child'] = [];
                foreach($categories as $key => $category) {
                    if($category['parent_id'] == $pcategory['id']) {
                        array_push($category_tree[$pkey]['child'], $category);
                    }
                }
            }
       }
       return $category_tree;
    }

    // get all open project to display
    public function get_all_open_projects($start = '', $limit = '') {
        $open_bidding_project_data = array();
			$this->db->select('op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,op.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date');
			$this->db->select('plrst.project_next_refresh_time');
			$this->db->from('projects_open_bidding op');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->join('projects_latest_refresh_sequence_tracking plrst', 'plrst.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->where('op.hidden = "N"');
			$this->db->order_by('plrst.project_last_refresh_time DESC, op.project_title ASC');
					if($start != '' && $limit != '') {
							$this->db->limit($limit, $start);
			} else if(isset($start)) {
				$this->db->limit($limit);
			}
			$open_bidding_project_result = $this->db->get();
			$open_bidding_project_data = $open_bidding_project_result->result_array();		
			if(!empty($open_bidding_project_data)){
				foreach($open_bidding_project_data as $project_key=>$project_value){
					$open_bidding_project_data[$project_key]['categories'] = $this->get_project_categories($project_value['project_id'],'open_for_bidding');

					if($this->session->userdata('user')) {
						$user = $this->session->userdata('user');
						$open_bidding_project_data[$project_key]['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_fb']));
						$open_bidding_project_data[$project_key]['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_ln']));
						$open_bidding_project_data[$project_key]['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_twitter']));
						$open_bidding_project_data[$project_key]['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_email']));
					} else {
						$open_bidding_project_data[$project_key]['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
						$open_bidding_project_data[$project_key]['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
						$open_bidding_project_data[$project_key]['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
						$open_bidding_project_data[$project_key]['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
					}
				}	
			}
			
			return $open_bidding_project_data;
    }
    /**
	*	This function is used to return the list of categories of projects from tables .
	*/
	public function get_project_categories($project_id,$project_status){
		
		if(!empty($project_id) && !empty($project_status)){
			
			########## fetch the project categories(awaiting moderation,open for bidding etc) ###
			if($project_status == 'draft')
			{
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
				$this->db->from('draft_projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.draft_project_category_id AND category_project.status = "Y"', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.draft_project_parent_category_id AND parent_category_project.status = "Y"', 'left');
				$this->db->where('category_tracking.project_id',$project_id);
				$this->db->where('category_project.status', 'Y');
				$this->db->order_by('category_tracking.id',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
			
			}
			if($project_status == 'awaiting_moderation')
			{
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
				$this->db->from('awaiting_moderation_projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.awaiting_moderation_project_category_id AND category_project.status = "Y"', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.awaiting_moderation_project_parent_category_id AND parent_category_project.status = "Y"', 'left');
				$this->db->where('category_tracking.project_id',$project_id);
				$this->db->order_by('category_tracking.id',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
			
			} elseif($project_status == 'open_for_bidding' || $project_status == 'expired' || $project_status == 'cancelled'){
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name');
				$this->db->from('projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.project_category_id AND category_project.status = "Y"', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.project_parent_category_id AND parent_category_project.status = "Y"', 'left');
				$this->db->where('category_tracking.project_id',$project_id);
				$this->db->order_by('category_tracking.id',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
			}
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
			return $category_data;
		} else {
			show_404();
		}
	}
	/*
	This function fetch the budget range of fixed type project
	*/
	function get_fixed_budget_projects_budget_range()
	{
        $count_budget_range_fixed_project = $this->db
		->select ('id')
		->from ('fixed_budget_projects_budgets_range')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_budget_range_fixed_project > 0)
		{
			$fixed_budget_projects_budget_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'fixed_budget_projects_budgets_range ORDER BY `min_budget` *1 ');
			foreach ($fixed_budget_projects_budget_range_query->result () as $row)
			{	
				if($row->max_budget != "All"){
				
					if($this->config->item('post_project_budget_range_between')){
						$fixed_budget_range_value = $this->config->item('post_project_budget_range_between').'&nbsp;'.number_format($row->min_budget, 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_budget, 0, '', ' ').'&nbsp'.CURRENCY;
					}else{
						$fixed_budget_range_value = number_format($row->min_budget, 0, '', ' '). '&nbsp;'.CURRENCY .'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_budget, 0, '', ' ').'&nbsp'.CURRENCY;
					}
					$data[] = [
						'fixed_budget_min_key' => $row->min_budget,
						'fixed_budget_max_key' => $row->max_budget,
						'fixed_budget_range_value' =>$fixed_budget_range_value ,
					];
				} else {
				
					$data[] = [
						'fixed_budget_min_key' => $row->min_budget,
						'fixed_budget_max_key' => $row->max_budget,
						'fixed_budget_range_value' => $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($row->min_budget, 0, '', ' ') ."&nbsp;".CURRENCY ,
					];
				
				}
			}
			if($this->config->item('fixed_budget_projects_confidential_dropdown_option')){
				$fixed_budget_projects_confidential_dropdown_option =  $this->config->item('fixed_budget_projects_confidential_dropdown_option');
				
				$data[] = [
						'fixed_budget_min_key' => key($fixed_budget_projects_confidential_dropdown_option),
						'fixed_budget_max_key' => '',
						'fixed_budget_range_value' =>$fixed_budget_projects_confidential_dropdown_option['confidential_dropdown_option_selected'] ,
					];
			}
			if($this->config->item('fixed_budget_projects_not_sure_dropdown_option')){
				$fixed_budget_projects_not_sure_dropdown_option =  $this->config->item('fixed_budget_projects_not_sure_dropdown_option');
				
				$data[] = [
						'fixed_budget_min_key' => key($fixed_budget_projects_not_sure_dropdown_option),
						'fixed_budget_max_key' => '',
						'fixed_budget_range_value' =>$fixed_budget_projects_not_sure_dropdown_option['not_sure_dropdown_option_selected'] ,
					];
			}
			
		}
        return $data;
	}
	/*
	 This function fetch the budget range of hourly type project
	*/
	function get_hourly_budget_projects_budget_range() {
		$count_budget_range_hourly_project = $this->db
		->select ('id')
		->from ('hourly_rate_based_projects_budgets_range')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_budget_range_hourly_project > 0)
		{
			$houlry_rate_projects_budget_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'hourly_rate_based_projects_budgets_range ORDER BY `min_hourly_rate` *1 ');
			foreach ($houlry_rate_projects_budget_range_query->result () as $row)
			{	
				if($row->max_hourly_rate != "All"){
				
					if($this->config->item('post_project_budget_range_between')){
						$hourly_rate_range_value = $this->config->item('post_project_budget_range_between').'&nbsp;'.number_format($row->min_hourly_rate, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_hourly_rate, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour');
					}else{
					
						$hourly_rate_range_value = number_format($row->min_hourly_rate, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_hour').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_hourly_rate, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_hour');
					}
					$data[] = [
						'hourly_rate_min_key' => $row->min_hourly_rate,
						'hourly_rate_max_key' => $row->max_hourly_rate,
						'hourly_rate_range_value' => $hourly_rate_range_value,
					];
				} else {
				
					$data[] = [
						'hourly_rate_min_key' => $row->min_hourly_rate,
						'hourly_rate_max_key' => $row->max_hourly_rate,
						'hourly_rate_range_value' => $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($row->min_hourly_rate, 0, '', ' ') ."&nbsp;".CURRENCY.$this->config->item('post_project_budget_per_hour') ,
					];
				
				}
			}
			if($this->config->item('hourly_rate_based_budget_projects_confidential_dropdown_option')){
				$hourly_rate_based_budget_projects_confidential_dropdown_option =  $this->config->item('hourly_rate_based_budget_projects_confidential_dropdown_option');
				
				$data[] = [
						'hourly_rate_min_key' => key($hourly_rate_based_budget_projects_confidential_dropdown_option),
						'hourly_rate_max_key' => '',
						'hourly_rate_range_value' =>$hourly_rate_based_budget_projects_confidential_dropdown_option['confidential_dropdown_option_selected'] ,
					];
			}
			if($this->config->item('hourly_rate_based_budget_projects_not_sure_dropdown_option')){
				$hourly_rate_based_budget_projects_not_sure_dropdown_option =  $this->config->item('hourly_rate_based_budget_projects_not_sure_dropdown_option');
				
				$data[] = [
						'hourly_rate_min_key' => key($hourly_rate_based_budget_projects_not_sure_dropdown_option),
						'hourly_rate_max_key' => '',
						'hourly_rate_range_value' =>$hourly_rate_based_budget_projects_not_sure_dropdown_option['not_sure_dropdown_option_selected'] ,
					];
			}
			
		}
        return $data;
	}
	/*
	This function fetch the budget range of full time salary based project
	*/
	
	function get_fulltime_projects_salaries_range() {
		
        $count_salary_range_fulltime_project = $this->db
		->select ('id')
		->from ('fulltime_projects_salaries_range')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_salary_range_fulltime_project > 0)
		{
			/* $this->db->order_by('min_budget', 'ASC');
			$res = $this->db->get ('hourly_rate_based_projects_budgets_range');  */
			
			$fulltime_projects_salary_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'fulltime_projects_salaries_range ORDER BY `min_salary` *1 ');
			
			foreach ($fulltime_projects_salary_range_query->result () as $row)
			{
				
				if($row->max_salary != "All"){
				
					if($this->config->item('post_project_budget_range_between')){
						$fulltime_salary_range_value = $this->config->item('post_project_budget_range_between').'&nbsp;'.number_format($row->min_salary, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_salary 	, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month');
					}else{
						$fulltime_salary_range_value = number_format($row->min_salary, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('post_project_budget_per_month').'&nbsp;'. $this->config->item('post_project_budget_range_and').'&nbsp;'.number_format($row->max_salary 	, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('post_project_budget_per_month');
					}
					$data[] = [
						'fulltime_salary_min_range_key' => $row->min_salary,
						'fulltime_salary_max_range_key' => $row->max_salary,
						'fulltime_salary_range_value' => $fulltime_salary_range_value
					];
				}else{
					$data[] = [
						'fulltime_salary_min_range_key' => $row->min_salary,
						'fulltime_salary_max_range_key' => $row->max_salary,
						'fulltime_salary_range_value' => $this->config->item('post_project_budget_range_more_then').'&nbsp;'. number_format($row->min_salary, 0, '', ' ') ."&nbsp;".CURRENCY . $this->config->item('post_project_budget_per_month') ,
					];
				}
			}
			if($this->config->item('fulltime_projects_salary_confidential_dropdown_option')){
				$fulltime_projects_salary_confidential_dropdown_option =  $this->config->item('fulltime_projects_salary_confidential_dropdown_option');
				$data[] = [
						'fulltime_salary_min_range_key' => key($fulltime_projects_salary_confidential_dropdown_option),
						'fulltime_salary_max_range_key' => '',
						'fulltime_salary_range_value' =>$fulltime_projects_salary_confidential_dropdown_option['confidential_dropdown_option_selected'] ,
					];
			}
			if($this->config->item('fulltime_projects_salary_not_sure_dropdown_option')){
				$fulltime_projects_salary_not_sure_dropdown_option =  $this->config->item('fulltime_projects_salary_not_sure_dropdown_option');
				$data[] = [
						'fulltime_salary_min_range_key' => key($fulltime_projects_salary_not_sure_dropdown_option),
						'fulltime_salary_max_range_key' => '',
						'fulltime_salary_range_value' =>$fulltime_projects_salary_not_sure_dropdown_option['not_sure_dropdown_option_selected'] ,
				];
			}
		}
        return $data;
	}
	
	// This function is used to filter open projects listing baed on given criteria
	public function get_open_projects_based_on_filter($filter_arr, $start = '', $limit = '') {
		$open_bidding_project_data = array();
		if(!empty($filter_arr)) {
			$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS op.id, op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.offline_payment_method,op.escrow_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date', false);
			$this->db->select('plrst.project_next_refresh_time');
			$this->db->from('projects_open_bidding op');
			$this->db->where('op.project_expiration_date >= NOW()');
			$this->db->where('op.hidden = "N"');
			$this->db->join('counties', 'counties.id = op.county_id', 'left');
			$this->db->join('localities', 'localities.id = op.locality_id', 'left');
			$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
			$this->db->join('projects_latest_refresh_sequence_tracking plrst', 'plrst.project_id = op.project_id', 'left');
			if($start != '' && $limit != '') {
				$this->db->limit($limit, $start);
			} else if(isset($start)) {
				$this->db->limit($limit);
			}
			if(is_array($filter_arr['categories']) || is_array($filter_arr['parent_categories'])) {
				$this->db->group_start();
			}
			if(is_array($filter_arr['categories']) && !empty($filter_arr['categories'])) {
				$this->db->join('projects_categories_listing_tracking clt', 'clt.project_id = op.project_id', 'left');
				$this->db->where_in('clt.project_category_id', $filter_arr['categories']);
			}
			if(is_array($filter_arr['parent_categories']) && !empty($filter_arr['parent_categories'])) {
				$this->db->or_where_in('clt.project_parent_category_id', $filter_arr['parent_categories']);
			}
			if(is_array($filter_arr['categories']) || is_array($filter_arr['parent_categories'])) {
				$this->db->group_end();
			}
			if(!empty($filter_arr['real_time_search_txt'])) {				
				if (empty($filter_arr['searchtxt_arr'])) {
					$filter_arr['searchtxt_arr'] = [];
				}
				array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
			}
			if($filter_arr['search_title_flag'] == "false" && !empty($filter_arr['searchtxt_arr']) && $filter_arr['search_type'] == 'include') {
				$this->db->join('projects_tags pt', 'pt.project_id = op.project_id', 'left');
				$this->db->group_start();
					$srch = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = $val.'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$srch = implode(' ',$srch);
					$this->db->where("MATCH(op.project_title, op.project_description) AGAINST('".$srch."' IN BOOLEAN MODE)");
					$this->db->or_where_in('pt.project_tag_name', $filter_arr['searchtxt_arr']);

				$this->db->group_end();
			} else if($filter_arr['search_title_flag'] == "false" && !empty($filter_arr['searchtxt_arr']) && $filter_arr['search_type'] == 'exclude') {
				$this->db->join('projects_tags pt', 'pt.project_id = op.project_id', 'left');
				$this->db->group_start();
				
					$srch = array_map(function($val){
						$val = trim(htmlspecialchars($val, ENT_QUOTES));
						$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
						if(!empty($val)) {
							$val = '+'.trim($val).'*';
						}
						return $val;
					}, $filter_arr['searchtxt_arr']);
					$srch = implode(' ',$srch);					
					$this->db->where("MATCH(op.project_title, op.project_description) AGAINST('".$srch."' IN BOOLEAN MODE)");
					$this->db->or_where_in('pt.project_tag_name', $filter_arr['searchtxt_arr']);
				$this->db->group_end();
			}
			// search text in project title only
			if($filter_arr['search_title_flag'] == "true" && is_array($filter_arr['searchtxt_arr']) && !empty($filter_arr['searchtxt_arr'])) {
				$this->db->group_start();
				$srch = array_map(function($val){
					$val = trim(htmlspecialchars($val, ENT_QUOTES));
					$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
					if(!empty($val)) {
						$val = trim($val).'*';
					}
					return $val;
				}, $filter_arr['searchtxt_arr']);
				$srch = implode(' ',$srch);
				$this->db->where("MATCH(op.project_title) AGAINST('".$srch."' IN BOOLEAN MODE)");
				$this->db->group_end();
			}
			// filter project listing based on location search
			if(!empty($filter_arr['location'])) {
				$county = [];
				$locality = [];
				foreach($filter_arr['location'] as $val) {
					if($val['continent'] == 'county') {
						array_push($county, explode('-', $val['value'])[0]);
					} else {
						array_push($locality, explode('-', $val['value'])[0]);
					}
				}
				
				if(!empty($county)) {
					$this->db->where_in('op.county_id', $county);
				}
				if(empty($county) && !empty($locality)) {
					$this->db->where_in('op.locality_id', $locality);
				} else if(!empty($locality)) {
					$this->db->or_where_in('op.locality_id', $locality);
				}
				
			}
			// filter project based on project upgrades
			if(!empty($filter_arr['upgrades'])) {
				$this->db->group_start();
				foreach($filter_arr['upgrades'] as $value) {
					if($value == 'ST') {
						$this->db->or_where('op.featured = "N" AND op.urgent = "N" AND op.sealed = "N"');
						$this->db->or_where('featured = "Y" AND ((featured_upgrade_end_date IS NULL OR featured_upgrade_end_date < NOW()) AND (membership_include_featured_upgrade_end_date IS NULL OR membership_include_featured_upgrade_end_date < NOW()) AND (bonus_featured_upgrade_end_date IS NULL OR bonus_featured_upgrade_end_date < NOW() ))');
						$this->db->or_where('urgent = "Y" AND ((urgent_upgrade_end_date IS NULL OR urgent_upgrade_end_date < NOW()) AND (membership_include_urgent_upgrade_end_date IS NULL OR membership_include_urgent_upgrade_end_date < NOW()) AND (bonus_urgent_upgrade_end_date IS NULL OR bonus_urgent_upgrade_end_date < NOW() ))');
					}
					if($value == 'F') {
						$this->db->or_where('op.featured', 'Y');
						$this->db->group_start();
							$this->db->where('membership_include_featured_upgrade_end_date IS NOT NULL AND membership_include_featured_upgrade_end_date >= NOW()');
							$this->db->or_where('bonus_featured_upgrade_end_date IS NOT NULL AND bonus_featured_upgrade_end_date >= NOW()');
							$this->db->or_where('featured_upgrade_end_date IS NOT NULL AND featured_upgrade_end_date >= NOW()');
						$this->db->group_end();
					}
					if($value == 'U') {
						$this->db->or_where('op.urgent', 'Y');
						$this->db->group_start();
							$this->db->where('membership_include_urgent_upgrade_end_date IS NOT NULL AND membership_include_urgent_upgrade_end_date >= NOW()');
							$this->db->or_where('bonus_urgent_upgrade_end_date IS NOT NULL AND bonus_urgent_upgrade_end_date >= NOW()');
							$this->db->or_where('urgent_upgrade_end_date IS NOT NULL AND urgent_upgrade_end_date >= NOW()');
						$this->db->group_end();
					}
					if($value == 'S') {
						$this->db->or_where('op.sealed', 'Y');
					}
				}
				$this->db->group_end();
			}
			// if(!empty($filter_arr['project_types']) || is_array($filter_arr['categories'])) {
			if(!empty($filter_arr['hourly_rate_range']) || !empty($filter_arr['fixed_budget_range']) || !empty($filter_arr['fulltime_salary_range'])) {
				$this->db->group_start();
			}
			// filter project based on project type
			if(!empty($filter_arr['project_types'])) {
				$this->db->where_in('op.project_type', $filter_arr['project_types']);
			}
			// filter project based on agreement
			if(!empty($filter_arr['agreement'])) {
				if($filter_arr['agreement'] == 'contract_fulltime') {
					$this->db->where('op.project_type', 'fulltime');
				} else if($filter_arr['agreement'] == 'project_based') {
					if(empty($filter_arr['project_types'])) {
						$this->db->group_start();
							$this->db->or_where('op.project_type', 'fixed');
							$this->db->or_where('op.project_type', 'hourly');
						$this->db->group_end();
					}
				} 
				
			}
			// filter project based on project publication date
			if(!empty($filter_arr['publication_dates'])) {
				$this->db->group_start();
					foreach($filter_arr['publication_dates'] as $key => $value) {
						$split = explode('_', $value);
						if(end($split) == 'days') {
							$this->db->where('op.project_posting_date > DATE_SUB(CURDATE(), INTERVAL '.$split[0].' DAY)');
						} else if(end($split) == 'hours') {
							$this->db->where('op.project_posting_date > DATE_SUB(CURDATE(), INTERVAL '.$split[0].' HOUR)');
						} else {
							$this->db->where('DATE(op.project_expiration_date) = DATE(NOW())');
						}
						
					}
				$this->db->group_end();
			}
			// filter project based on min and max budget range selection from fixed budge
			if(!empty($filter_arr['fixed_budget_range'])) {
				if(empty($filter_arr['project_types']) || (!empty($filter_arr['project_types']) && $filter_arr['project_types'][0] == 'fixed' )) {
					$this->db->where('op.project_type', 'fixed');
					$this->db->group_start();
						foreach($filter_arr['fixed_budget_range'] as $key => $value) {
							if($key == 0) {
								$this->db->group_start();
								if(is_numeric($value['min']) && is_numeric($value['max'])) {
									$this->db->where('CAST(min_budget AS SIGNED) >=',$value['min']);
									$this->db->where('CAST(min_budget AS SIGNED) <=',$value['max']);
									$this->db->where('CAST( op.max_budget AS SIGNED ) <=', $value['max']);
								} else if($value['min'] == 'confidential_dropdown_option_selected') {
									$this->db->where('confidential_dropdown_option_selected', 'Y');
								} else if($value['min'] == 'not_sure_dropdown_option_selected') {
									$this->db->where('not_sure_dropdown_option_selected', 'Y');
								} else {
									$this->db->where('CAST( op.min_budget AS SIGNED  ) >=', $value['min']);
								}
								$this->db->group_end();
							} else {					
								$this->db->or_group_start();
								if(is_numeric($value['min']) && is_numeric($value['max'])) {
									$this->db->or_where('CAST(min_budget AS SIGNED) >=',$value['min']);
									$this->db->where('CAST(min_budget AS SIGNED) <=',$value['max']);
									$this->db->where('CAST(op.max_budget AS SIGNED ) <=', $value['max']);
								} else if($value['min'] == 'confidential_dropdown_option_selected') {
									$this->db->or_where('confidential_dropdown_option_selected', 'Y');
								} else if($value['min'] == 'not_sure_dropdown_option_selected') {
									$this->db->or_where('not_sure_dropdown_option_selected', 'Y');
								} else {
									$this->db->where('CAST(op.min_budget AS SIGNED ) >=', $value['min']);
								}
								$this->db->group_end();		
							}
						}
					$this->db->group_end();
				}
				
			} else {
				if((!empty($filter_arr['hourly_rate_range']) || !empty($filter_arr['fulltime_salary_range'])) && empty($filter_arr['project_types']) && !empty($filter_arr['agreement']) && $filter_arr['agreement'] != 'contract_fulltime') {
					$this->db->or_where('op.project_type', 'fixed');
				}
			}
			// filter project based on min and max budget range selection from hourly rate
			if(!empty($filter_arr['hourly_rate_range'])) {
				if(empty($filter_arr['project_types']) || (!empty($filter_arr['project_types']) && $filter_arr['project_types'][0] == 'hourly' )) {
					if(empty($filter_arr['project_types']) || !empty($filter_arr['fixed_budget_range']) || !empty($filter_arr['fulltime_salary_range'])) {
						$this->db->or_where('op.project_type', 'hourly');
					} else {
						$this->db->where('op.project_type', 'hourly');
					}
					$this->db->group_start();
					foreach($filter_arr['hourly_rate_range'] as $key => $value) {
						if($key == 0) {
							$this->db->group_start();
							if(is_numeric($value['min']) && is_numeric($value['max'])) {
								$this->db->where('CAST(min_budget AS SIGNED) >=',$value['min']);
								$this->db->where('CAST(min_budget AS SIGNED) <=',$value['max']);
								$this->db->where('CAST( op.max_budget AS SIGNED ) <=', $value['max']);
							} else if($value['min'] == 'confidential_dropdown_option_selected') {
								$this->db->where('confidential_dropdown_option_selected', 'Y');
							} else if($value['min'] == 'not_sure_dropdown_option_selected') {
								$this->db->where('not_sure_dropdown_option_selected', 'Y');
							} else {
								$this->db->where('CAST( op.min_budget AS SIGNED  ) >=', $value['min']);
							}
							$this->db->group_end();
						} else {					
							$this->db->or_group_start();
							if(is_numeric($value['min']) && is_numeric($value['max'])) {
								$this->db->or_where('CAST(min_budget AS SIGNED) >=',$value['min']);
								$this->db->where('CAST(min_budget AS SIGNED) <=',$value['max']);
								$this->db->where('CAST(op.max_budget AS SIGNED ) <=', $value['max']);
							} else if($value['min'] == 'confidential_dropdown_option_selected') {
								$this->db->or_where('confidential_dropdown_option_selected', 'Y');
							} else if($value['min'] == 'not_sure_dropdown_option_selected') {
								$this->db->or_where('not_sure_dropdown_option_selected', 'Y');
							} else {
								$this->db->where('CAST(op.min_budget AS SIGNED ) >=', $value['min']);
							}
							$this->db->group_end();		
						}
					}
					$this->db->group_end();
				}
				
			} else {
				if((!empty($filter_arr['fixed_budget_range']) || !empty($filter_arr['fulltime_salary_range'])) && empty($filter_arr['project_types']) && !empty($filter_arr['agreement']) && $filter_arr['agreement'] != 'contract_fulltime' ) {
					$this->db->or_where('op.project_type', 'hourly');
				}
			}
			// filter project based on min and max budget range selection from fulltime salary
			if(!empty($filter_arr['fulltime_salary_range'])) {
				if(empty($filter_arr['project_types']) || !empty($filter_arr['project_types'])) {
						if(!empty($filter_arr['fixed_budget_range']) || !empty($filter_arr['hourly_rate_range']) || !empty($filter_arr['project_types'])) {
							$this->db->or_where('op.project_type', 'fulltime');
						} else {
							if(!empty($filter_arr['agreement']) && $filter_arr['agreement'] != 'contract_fulltime')  {
							// if(is_array($filter_arr['categories']) && !empty($filter_arr['categories']))  {
								$this->db->or_where('op.project_type', 'fulltime');
							} 
							// else {
							// 	$this->db->where('op.project_type', 'fulltime');
							// }
						}
						$this->db->group_start();
						foreach($filter_arr['fulltime_salary_range'] as $key => $value) {
							if($key == 0) {
								$this->db->group_start();
								if(is_numeric($value['min']) && is_numeric($value['max'])) {
									$this->db->where('CAST(min_budget AS SIGNED) >=',$value['min']);
									$this->db->where('CAST(min_budget AS SIGNED) <=',$value['max']);
									$this->db->where('CAST( op.max_budget AS SIGNED ) <=', $value['max']);
								} else if($value['min'] == 'confidential_dropdown_option_selected') {
									$this->db->where('confidential_dropdown_option_selected', 'Y');
								} else if($value['min'] == 'not_sure_dropdown_option_selected') {
									$this->db->where('not_sure_dropdown_option_selected', 'Y');
								} else {
									$this->db->where('CAST( op.min_budget AS SIGNED  ) >=', $value['min']);
								}
								$this->db->group_end();
							} else {					
								$this->db->or_group_start();
								if(is_numeric($value['min']) && is_numeric($value['max'])) {
									$this->db->or_where('CAST(min_budget AS SIGNED) >=',$value['min']);
									$this->db->where('CAST(min_budget AS SIGNED) <=',$value['max']);
									$this->db->where('CAST(op.max_budget AS SIGNED ) <=', $value['max']);
								} else if($value['min'] == 'confidential_dropdown_option_selected') {
									$this->db->or_where('confidential_dropdown_option_selected', 'Y');
								} else if($value['min'] == 'not_sure_dropdown_option_selected') {
									$this->db->or_where('not_sure_dropdown_option_selected', 'Y');
								} else {
									$this->db->where('CAST(op.min_budget AS SIGNED ) >=', $value['min']);
								}
								$this->db->group_end();		
							}
						}
					$this->db->group_end();
				}
				
			} else {
				if((!empty($filter_arr['hourly_rate_range']) || !empty($filter_arr['fixed_budget_range'])) && ( $filter_arr['agreement'] == 'agreement_all' || $filter_arr['agreement'] != 'project_based' )) {
					$this->db->or_where('op.project_type', 'fulltime');
				}
			}
			// if(!empty($filter_arr['project_types']) || is_array($filter_arr['categories'])) {
			if(!empty($filter_arr['hourly_rate_range']) || !empty($filter_arr['fixed_budget_range']) || !empty($filter_arr['fulltime_salary_range'])) {
				$this->db->group_end();
			}
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = op.project_id', 'left');
			$this->db->order_by('plrst.project_last_refresh_time DESC, op.project_title ASC');
		}
		// $query =  $this->db->get_compiled_select();
		// pre($query);

		$open_bidding_project_result = $this->db->get();
		$open_bidding_project_data = $open_bidding_project_result->result_array();		

		// pre($open_bidding_project_data);

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;

		if(!empty($open_bidding_project_data)){
			foreach($open_bidding_project_data as $project_key=>$project_value){
				$open_bidding_project_data[$project_key]['categories'] = $this->get_project_categories($project_value['project_id'],'open_for_bidding');

				if($this->session->userdata('user')) {
					$user = $this->session->userdata('user');
					$open_bidding_project_data[$project_key]['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_fb']));
					$open_bidding_project_data[$project_key]['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_ln']));
					$open_bidding_project_data[$project_key]['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_twitter']));
					$open_bidding_project_data[$project_key]['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_email']));
				} else {
					$open_bidding_project_data[$project_key]['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
					$open_bidding_project_data[$project_key]['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
					$open_bidding_project_data[$project_key]['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
					$open_bidding_project_data[$project_key]['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'];
				}
			}
		}
        return ['data' => $open_bidding_project_data, 'total' => $total_rec];
	}
	// This function is used to get array of county and locality to provide suggetion on find project page when user search for county or locality
	public function get_all_county_locality_to_display($str) {
		$display = [];
		$this->db->like('LOWER(name)', strtolower($str));
		$county = $this->db->get('counties')->result_array();
		$this->db->like('LOWER(name)', strtolower($str));
		$locality = $this->db->get('localities')->result_array();
		if(!empty($county)) {
			foreach($county as $key => $val) {
				$tmp = [
					'value' => $val['id'].'-c',
					'text' => $val['name'].' (county)',
					'continent' => 'county'
				];
				array_push($display, $tmp);
			}
		}
		if(!empty($locality)) {
			foreach($locality as $val) {
				$tmp = [
					'value' => $val['id'].'-l',
					'text' => $val['name'].' ('.$val['county_code'].')',
					'continent' => 'locality'
				];
				array_push($display, $tmp);
			}
		}
		return $display;
	}
	
}
?>