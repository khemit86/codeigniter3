<?php
if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Newly_posted_projects_realtime_notifications_model extends BaseModel
{

    public function __construct ()
    { 
        return parent::__construct ();
    }
    /**
     * This method is used to get newly posted project notification whenever any project posted which is belongs to users areas of expertise
    */
    public function get_newly_posted_project_notification_by_user_and_project($condition) {
        $this->db->select('unpsn.*, u.account_type, u.first_name, u.last_name, u.company_name');
        $this->db->from('users_newly_posted_projects_sent_notifications_tracking unpsn');
        $this->db->join('users u', 'u.user_id = unpsn.project_owner_id', 'left');
        $this->db->where($condition);
				$result = $this->db->get()->row_array();

				if(!empty($result)) {
					$notification_str = explode('<br/>', $result['notification_description']);
					unset($notification_str[2]);
					$result['notification_description'] = implode('<br/>', $notification_str);
				}
				
				
        return $result;
    }
    /**
     * This method is used to get projects notification feed detail based on filter passed from fornt-end
    */
    public function get_projects_notification_feed_detail_based_on_filter($start, $limit, $user, $filter_arr = []) {
        $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS npsn.id,npsn.project_id ,npsn.notification_description, npsn.notification_send_date, npsn.sent_notification_type, u.account_type, u.first_name, u.last_name, u.company_name, npsn.project_type,categories_tracking.total_category', false);
        $this->db->from('users_newly_posted_projects_sent_notifications_tracking npsn');
        $this->db->join('projects_open_bidding op', 'op.project_id = npsn.project_id');
        $this->db->join('users u', 'u.user_id = npsn.project_owner_id', 'left');
				$this->db->join('(select COUNT(id)  as total_category,project_id from '.$this->db->dbprefix .'projects_categories_listing_tracking  group by project_id ) as categories_tracking', 'categories_tracking.project_id = npsn.project_id', 'left');
        $this->db->where('op.hidden = "N"');
        $this->db->where('op.project_expiration_date >= NOW()');
        $this->db->where('npsn.user_id', $user[0]->user_id);
        $this->db->order_by('npsn.notification_send_date', 'desc');
        if($start != '' && $limit != '') {
            $this->db->limit($limit, $start);
        } else if(isset($start)) {
            $this->db->limit($limit);
        }
        if(!empty($filter_arr) && !empty($filter_arr['search_str'])) {
            $this->db->like('strip_html_tags(npsn.notification_description)', $filter_arr['search_str']);
        }
        // echo $this->db->get_compiled_select();
        $notificaiton_data = $this->db->get()->result_array();
		
        foreach($notificaiton_data as &$value) {
            $notification_str = explode('<br/>', $value['notification_description']);
            // project title
            $project_title = !empty($notification_str[0]) ? $notification_str[0] : '';
            $value['project_title'] = trim($project_title);
            $value['project_categories_count'] = 1;
            // budget range
            if(!empty($notification_str[1])) {
                $budget_str = $notification_str[1];
                $budget_arr = explode(':', $budget_str);
                $value['budget'] = !empty($budget_arr[1]) ? trim($budget_arr[1]) : '';
            }
            // posting date
            if(!empty($notification_str[2])) {
                $posting_date_str = $notification_str[2];
                $posting_date_arr = explode(':', $posting_date_str);
			
                $value['posting_date'] = !empty($posting_date_arr[1]) ? trim($posting_date_arr[1].':'.$posting_date_arr[2].":".$posting_date_arr[3]) : '';
            }
            // Categories
            if(!empty($notification_str[3])) {
                $category_str = $notification_str[3];
                $category_arr = explode(':', $category_str);
                $value['category'] = !empty($category_arr[1]) ? trim($category_arr[1]) : '';
            }
            // profile name
            if($value['account_type'] == 1) {
                $value['project_owner'] = $value['first_name'].' '.$value['last_name'];
            } else {
                $value['project_owner'] = $value['company_name'];
            }
            
				}
				// pre($notificaiton_data);
        $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $total_rec = $query->row()->Count;
        
        return ['data' => $notificaiton_data, 'total' => $total_rec];
    }
    /**
     * This method is used to get all projects notification feed based on filter passed from fornt-end
    */
    public function get_all_projects_notification_feeds_based_on_filter($start, $limit, $user, $filter_arr = []) {
        $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS op.project_id,op.project_owner_id,op.project_title,op.project_description,op.project_type,op.min_budget,op.max_budget,op.confidential_dropdown_option_selected,op.not_sure_dropdown_option_selected,op.featured,op.urgent,op.sealed,op.hidden,op.project_posting_date,op.project_expiration_date,op.escrow_payment_method,op.offline_payment_method,counties.name as county_name,localities.name as locality_name,postal_codes.postal_code,featured_purchasing_tracking.featured_upgrade_end_date,bonus_featured_purchasing_tracking.bonus_featured_upgrade_end_date,urgent_purchasing_tracking.urgent_upgrade_end_date,bonus_urgent_purchasing_tracking.bonus_urgent_upgrade_end_date,membership_include_featured_purchasing_tracking.membership_include_featured_upgrade_end_date,membership_include_urgent_purchasing_tracking.membership_include_urgent_upgrade_end_date', false);
        $this->db->select('plrst.project_next_refresh_time, u.first_name, u.last_name, u.account_type, u.gender, u.company_name,u.is_authorized_physical_person');
        $this->db->from('users_newly_posted_projects_sent_notifications_tracking npsn');
        $this->db->join('projects_open_bidding op', 'op.project_id = npsn.project_id');
				$this->db->join('counties', 'counties.id = op.county_id', 'left');
				$this->db->join('localities', 'localities.id = op.locality_id', 'left');
				$this->db->join('postal_codes', 'postal_codes.id = op.postal_code_id', 'left');
				$this->db->join('users u', 'u.user_id = op.project_owner_id', 'left');
				$this->db->join('projects_latest_refresh_sequence_tracking plrst', 'plrst.project_id = op.project_id', 'left');
				$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as membership_include_featured_purchasing_tracking', 'membership_include_featured_purchasing_tracking.project_id = op.project_id', 'left');
				$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_featured_upgrade_end_date from '.$this->db->dbprefix .'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as bonus_featured_purchasing_tracking', 'bonus_featured_purchasing_tracking.project_id = op.project_id', 'left');
				$this->db->join('(select project_id, max(project_upgrade_end_date) as featured_upgrade_end_date from '.$this->db->dbprefix .'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "featured"  group by project_id ) as featured_purchasing_tracking', 'featured_purchasing_tracking.project_id = op.project_id', 'left');
				$this->db->join('(select project_id, max(project_upgrade_end_date) as membership_include_urgent_upgrade_end_date from '.$this->db->dbprefix .'proj_membership_included_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as membership_include_urgent_purchasing_tracking', 'membership_include_urgent_purchasing_tracking.project_id = op.project_id', 'left');
				$this->db->join('(select project_id, max(project_upgrade_end_date) as bonus_urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_bonus_based_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as bonus_urgent_purchasing_tracking', 'bonus_urgent_purchasing_tracking.project_id = op.project_id', 'left');
        $this->db->join('(select project_id, max(project_upgrade_end_date) as urgent_upgrade_end_date from '.$this->db->dbprefix.'proj_real_money_upgrades_purchase_tracking where project_upgrade_type = "urgent"  group by project_id ) as urgent_purchasing_tracking', 'urgent_purchasing_tracking.project_id = op.project_id', 'left');

        if(!empty($filter_arr)) {
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
										$val = '+'.$val.'*';
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
									$val = $val.'*';
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
										if(is_array($filter_arr['categories']) && !empty($filter_arr['categories']))  {
											$this->db->or_where('op.project_type', 'fulltime');
										} else {
											$this->db->where('op.project_type', 'fulltime');
										}
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
				}

				$this->db->where('op.hidden = "N"');
        $this->db->where('op.project_expiration_date >= NOW()');
        $this->db->where('npsn.user_id', $user[0]->user_id);
        $this->db->order_by('plrst.project_last_refresh_time DESC, op.project_title ASC');
        
        if($start != '' && $limit != '') {
            $this->db->limit($limit, $start);
        } else if(isset($start)) {
            $this->db->limit($limit);
        }
        $notificaiton_data = $this->db->get()->result_array();
        $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $total_rec = $query->row()->Count;
        if(!empty($notificaiton_data)){
					foreach($notificaiton_data as $project_key=>$project_value){
						$notificaiton_data[$project_key]['categories'] = $this->get_project_categories($project_value['project_id'],'open_for_bidding');

						$user = $this->session->userdata('user');
						$notificaiton_data[$project_key]['fb_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_fb']));
						$notificaiton_data[$project_key]['ln_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_ln']));
						$notificaiton_data[$project_key]['twitter_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_twitter']));
						$notificaiton_data[$project_key]['email_share_url'] = base_url($this->config->item('project_detail_page_url')).'?id='.$project_value['project_id'].'&rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'project_url_share_email']));
					}
			}
			return ['data' => $notificaiton_data, 'total' => $total_rec];
    }
	
	/* This function is used to count the unread of message of user when related with newly posted project*/	
	public function get_user_unread_newly_posted_project_messages_count($user_id)
	{
		$this->db->select('npsn.*');
		$this->db->from('users_newly_posted_projects_sent_notifications_tracking npsn');
		$this->db->join('projects_open_bidding op', 'op.project_id = npsn.project_id');
		$this->db->where('op.project_expiration_date >= NOW()');
		$this->db->where(['npsn.user_id' => $user_id,'npsn.is_message_read'=>'N']);
		$count_unread_message = count($this->db->get()->result_array());
		return $count_unread_message;
	}
	
	 /**
	*	This function is used to return the list of categories of projects from tables .
	*/
	public function get_project_categories($project_id,$project_status){
		
		if(!empty($project_id) && !empty($project_status)){
			
			########## fetch the project categories(awaiting moderation,open for bidding etc) ###
			if($project_status == 'draft')
			{
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name,category_project.id as c_id,parent_category_project.id as p_id');
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
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name,category_project.id as c_id,parent_category_project.id as p_id');
				$this->db->from('awaiting_moderation_projects_categories_listing_tracking as category_tracking');
				$this->db->join('categories_projects as category_project', 'category_project.id = category_tracking.awaiting_moderation_project_category_id AND category_project.status = "Y"', 'left');
				$this->db->join('categories_projects as parent_category_project', 'parent_category_project.id = category_tracking.awaiting_moderation_project_parent_category_id AND parent_category_project.status = "Y"', 'left');
				$this->db->where('category_tracking.project_id',$project_id);
				$this->db->order_by('category_tracking.id',"asc");
				$category_result = $this->db->get();
				$category_data = $category_result->result_array();
			
			} elseif($project_status == 'open_for_bidding' || $project_status == 'expired' || $project_status == 'cancelled'){
				$this->db->select('category_project.name as category_name,parent_category_project.name as parent_category_name,category_project.id as c_id,parent_category_project.id as p_id');
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

	/** 
     * This method is used to insert or remove new notification details whenever uesr click on checkboxe from frontend
    */ 
    public function add_or_remove_consent_receive_detail($data) {
        $row = $this->db->get_where('users_consent_receive_notifications_tracking', $data)->row_array();
        if(empty($row)) {
            $this->db->insert('users_consent_receive_notifications_tracking', $data);
            $res['state'] = 1;
        } else {
            $this->db->delete('users_consent_receive_notifications_tracking', $data);
            $res['state'] = 2;
        }
        return $res;
    }
}
?>