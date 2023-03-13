<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Find_professionals_model extends BaseModel
{

    public function __construct ()
    {
		parent::__construct ();
		$this->load->model('user/User_model');
		$this->load->model('dashboard/Dashboard_model');
    }
    // get parent child category array for find project page
    public function get_category_tree() {
			$this->db->where(['parent_id' => 0, 'status' => 'Y']);
       $this->db->order_by('name', 'ASC');
			 $parent_categories = $this->db->get('categories_professionals')->result_array();
       $categories = $this->db->get_where('categories_professionals', ['parent_id != ' => 0, 'status' => 'Y'])->result_array();
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
   
    // This function is used to get array of county and locality to provide suggetion on find professional page when user search for county or locality
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
	/*
	 This function is used to get hourly rate ranges which will be display under dropdown on find professional page
	*/
	function get_find_professionals_hourly_rate_ranges() {
		$count_budget_range_hourly_project = $this->db
		->select ('id')
		->from ('find_professionals_user_hourly_rate_range_selection')
		->get ()->num_rows (); 
		$data = array(); 
		if($count_budget_range_hourly_project > 0)
		{
			$houlry_rate_projects_budget_range_query = $this->db->query('SELECT * FROM '.$this->db->dbprefix .'find_professionals_user_hourly_rate_range_selection ORDER BY `min_hourly_rate` *1 ');
			foreach ($houlry_rate_projects_budget_range_query->result () as $row)
			{	
				if($row->max_hourly_rate != "All"){
				
					if($this->config->item('find_professionals_rate_range_between')){
						$hourly_rate_range_value  = $this->config->item('find_professionals_rate_range_between').'&nbsp;'.number_format($row->min_hourly_rate, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('find_professionals_rate_per_hour').'&nbsp;'. $this->config->item('find_professionals_rate_range_and').'&nbsp;'.number_format($row->max_hourly_rate, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('find_professionals_rate_per_hour');
					}else{
						$hourly_rate_range_value  = number_format($row->min_hourly_rate, 0, '', ' '). '&nbsp;'.CURRENCY .$this->config->item('find_professionals_rate_per_hour').'&nbsp;'. $this->config->item('find_professionals_rate_range_and').'&nbsp;'.number_format($row->max_hourly_rate, 0, '', ' ').'&nbsp'.CURRENCY.$this->config->item('find_professionals_rate_per_hour');
					
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
						'hourly_rate_range_value' => $this->config->item('find_professionals_rate_range_more_then').'&nbsp;'. number_format($row->min_hourly_rate, 0, '', ' ') ."&nbsp;".CURRENCY.$this->config->item('find_professionals_rate_per_hour') ,
					];
				
				}
			}
			
		}
        return $data;
	}
	/*
	* This method is used to get all professional data which are registered on site
	*/
	public function get_all_professionals($start = '', $limit = '') {
		$userArr = array();
		 /*------------user details -------------------*/
		 // get the user detail
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS u.user_id,ud.user_avatar,ud.user_total_avg_rating_as_sp,u.first_name, u.last_name, u.profile_name, u.account_type,u.is_authorized_physical_person, u.company_name, u.gender ,ubi.headline , ubi.description, ubi.hourly_rate ,uadd.street_address, c.name county,l.name locality,pc.postal_code postal_code,countries.*,(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews,(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews,(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects,(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects,(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects', false);
		$this->db->select('ufpt.user_id as featured_user_profile');
		$this->db->from('users u');
		$this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
		$this->db->join('users_profile_base_information ubi', 'ubi.user_id = u.user_id', 'left');
		$this->db->join('users_address_details uadd', 'uadd.user_id = u.user_id', 'left');
		$this->db->join('users_accounts_profile_completion_tracking upct', 'upct.user_id = u.user_id', 'left');
		$this->db->join('users_additional_accesses_settings uas', 'uas.user_id = u.user_id', 'left');
		$this->db->join('users_featured_profiles_tracking_on_find_professionals_page ufpt', 'ufpt.user_id=u.user_id', 'left');
		$this->db->join('counties c', 'uadd.county_id = c.id', 'left');
		$this->db->join('localities l', 'uadd.locality_id = l.id', 'left');
		$this->db->join('postal_codes pc', 'uadd.postal_code_id = pc.id', 'left');
		$this->db->join('countries', 'uadd.country_id = countries.id', 'left');
		$this->db->where("IFNULL(ubi.description, '') != ''");
		$this->db->where("IFNULL(ubi.headline, '') != ''"); 
		$this->db->group_start();
			$this->db->where('IFNULL(uas.user_profile_not_displayed_on_find_professionals_page, "") = "" OR uas.user_profile_not_displayed_on_find_professionals_page = "N"');
		$this->db->group_end();
		 
		$order_by = "
				ud.current_membership_plan_id DESC,
				upct.user_profile_strength_value DESC,
				upct.number_of_portfolios_entries DESC,
				upct.number_of_certifications_entries DESC,
				upct.number_of_areas_of_expertise_entries DESC,
				upct.number_of_skills_entries DESC,
				upct.number_of_services_provided_entries DESC,
				upct.country_address_strength_value DESC,
				upct.phone_or_mobile_number_strength_value DESC,
				upct.contact_email_strength_value DESC,
				u.account_validation_date DESC
		 ";

		 $this->db->order_by($order_by);
			if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
			} else if(isset($start)) {
				$this->db->limit($limit);
			}
		 $users =  $this->db->get()->result_array();
				
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;

		$user_ids = array_column($users, 'user_id');
		if($this->session->userdata('user') && !empty($user_ids)) {
			$user = $this->session->userdata('user');
			$this->db->select('*');
			$this->db->from('users_contacts_tracking');
			$this->db->where('contact_initiated_by', $user[0]->user_id);
			$this->db->where_in('contact_requested_to', $user_ids);
			$result = $this->db->get()->result_array();
			if(!empty($result)) {
				$already_in_contact = array_unique(array_column($result, 'contact_requested_to'));
			}
		}
		
		foreach($users as $key => $user_detail) {
			// $this->User_model->remove_scrambled_user_address_entries($user_detail['user_id']);
			$userArr[$user_detail['user_id']]['featured_user_profile']= $user_detail['featured_user_profile'];
			$userArr[$user_detail['user_id']]['user_id']=$user_detail['user_id'];
			$userArr[$user_detail['user_id']]['profile_name']=$user_detail['profile_name'];
			$userArr[$user_detail['user_id']]['company_name']=$user_detail['company_name'];
			$userArr[$user_detail['user_id']]['name']=$user_detail['first_name'].' '.$user_detail['last_name'];
			$userArr[$user_detail['user_id']]['headline']=$user_detail['headline'];
			$userArr[$user_detail['user_id']]['description']=$user_detail['description'];
			$userArr[$user_detail['user_id']]['gender']=$user_detail['gender'];
			$userArr[$user_detail['user_id']]['account_type']=$user_detail['account_type'];
			$userArr[$user_detail['user_id']]['is_authorized_physical_person']=$user_detail['is_authorized_physical_person'];
			$userArr[$user_detail['user_id']]['hourly_rate']=$user_detail['hourly_rate'];
			$userArr[$user_detail['user_id']]['street_address']=$user_detail['street_address'];
			$userArr[$user_detail['user_id']]['county']=$user_detail['county'];
			$userArr[$user_detail['user_id']]['locality']=$user_detail['locality'];
			$userArr[$user_detail['user_id']]['postal_code']=$user_detail['postal_code'];
			$userArr[$user_detail['user_id']]['total_reviews']=$user_detail['fulltime_project_user_total_reviews']+$user_detail['project_user_total_reviews'];
			$userArr[$user_detail['user_id']]['user_total_avg_rating_as_sp']=$user_detail['user_total_avg_rating_as_sp'];
			//$userArr[$user_detail['user_id']]['reviews']='0.0';
			$userArr[$user_detail['user_id']]['country_code']=$user_detail['country_code'];
			$userArr[$user_detail['user_id']]['country_name']=$user_detail['country_name'];
			$userArr[$user_detail['user_id']]['completed_projects_as_sp']=$user_detail['sp_total_completed_fixed_budget_projects']+$user_detail['sp_total_completed_hourly_based_projects'];
			$userArr[$user_detail['user_id']]['completed_projects_as_employee']= $user_detail['employee_total_completed_fulltime_projects'];
			
			
			
			if(!empty($already_in_contact) && in_array($user_detail['user_id'], $already_in_contact)) {
				$userArr[$user_detail['user_id']]['is_in_contact'] = true;
			}
			$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];

				$common_source_path = USERS_FTP_DIR . $user_detail['profile_name'];
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
				$avatar_pic = $source_path_avatar . $user_detail['user_avatar'];
									
				if (!empty($user_detail['user_avatar'])) {
					$file_size = $this->ftp->get_filesize($avatar_pic);
					if ($file_size != '-1') {
					  $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
					}
				}else {			
				if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
					if($user_detail['gender'] == 'M'){
							$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
					}if($user_detail['gender'] == 'F'){
					   $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
					}
				} else {
						$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
				}
				// profile completeion script start //
				$user_profile_completion_data = array();
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] =0;
				// if($user_profile_completion_data){
					// $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
				
				// }
				// profile completeion script end //
				
			}
			 //end check avatar
			 $this->ftp->close();
			
			$userArr[$user_detail['user_id']]['user_profile_picture'] = $user_profile_picture;
			$userArr[$user_detail['user_id']]['professional_category'] = $this->get_user_areas_of_expertise($user_detail['user_id']);


			if($this->session->userdata('user')) {
				$user = $this->session->userdata('user');
				$userArr[$user_detail['user_id']]['fb_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_fb']));
				$userArr[$user_detail['user_id']]['ln_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_ln']));
				$userArr[$user_detail['user_id']]['twitter_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_twitter']));
				$userArr[$user_detail['user_id']]['email_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_email']));
			} else {
				$userArr[$user_detail['user_id']]['fb_share_url'] = base_url($user_detail['profile_name']);
				$userArr[$user_detail['user_id']]['ln_share_url'] = base_url($user_detail['profile_name']);
				$userArr[$user_detail['user_id']]['twitter_share_url'] = base_url($user_detail['profile_name']);
				$userArr[$user_detail['user_id']]['email_share_url'] = base_url($user_detail['profile_name']);
			}

		}
		return ['data' => $userArr, 'total' => $total_rec];
	}
	/**
	*	This function is used to return the list of areas of experties of user from tables .
	*/
	public function get_user_areas_of_expertise($user_id){
		
		if(!empty($user_id)){
			$this->db->select('category_professional.name as category_name,parent_category_professional.name as parent_category_name, category_professional.id as cid, parent_category_professional.id as pcid');
			$this->db->from('professionals_areas_of_expertise_listings_tracking as category_tracking');
			$this->db->join('categories_professionals as category_professional', 'category_professional.id = category_tracking.professional_category_id AND category_professional.status = "Y"', 'left');
			$this->db->join('categories_professionals as parent_category_professional', 'parent_category_professional.id = category_tracking.professional_parent_category_id AND parent_category_professional.status = "Y"', 'left');
			$this->db->where('category_tracking.user_id',$user_id);
			$this->db->order_by('category_tracking.id',"asc");
			$category_result = $this->db->get();
			$category_data = $category_result->result_array();
			
			$mainArr = array();

            foreach ($category_data as $cat) {
                if (empty($cat['pcid'])) {
                    $mainArr[$cat['category_name']][] = [$cat['pcid'], $cat['category_name']];
                } else {
                    $mainArr[$cat['parent_category_name']][] = [$cat['cid'], $cat['category_name']];
                }
			}
			// pre($mainArr);
			$recordArr = array();
			foreach ($mainArr as $key => $arr) {
                if(!empty($key))
                	$recordArr[$key] = $key;
                
                if(!empty($arr[0][0])) {
                    $recordSArr = array();
                    foreach ($arr as $scat) {
						if(!empty($scat[1]))
                        	$recordSArr[$scat[1]] = $scat[1];
                    }
					asort($recordSArr);
                    $recordArr[$key] = $recordSArr;
                }
            }
			ksort($recordArr);
			return $recordArr;
		} else {
			show_404();
		}
	}
	// This function is used to filter professionals listing baed on given criteria from find professional page
	public function get_all_professionals_based_on_filter($filter_arr, $start = '', $limit = '') {
		$userArr = array();
		/*------------user details -------------------*/
		// get the user detail
		$this->db->select('DISTINCT SQL_CALC_FOUND_ROWS u.user_id,ud.user_avatar,ud.user_total_avg_rating_as_sp,u.first_name, u.last_name, u.profile_name, u.account_type,u.is_authorized_physical_person, u.company_name, u.gender ,ubi.headline , ubi.description, ubi.hourly_rate ,uadd.street_address,c.name county,l.name locality,pc.postal_code postal_code,countries.*,(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews,(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews,(SELECT count(*)  FROM '.$this->db->dbprefix.'fixed_budget_projects_completed_tracking where winner_id = u.user_id  and project_completion_method = "via_portal" ) as sp_total_completed_fixed_budget_projects,(SELECT count(*)  FROM '.$this->db->dbprefix.'hourly_rate_based_projects_completed_tracking where winner_id = u.user_id and project_completion_method = "via_portal" ) as sp_total_completed_hourly_based_projects,(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_projects_hired_employees_tracking where employee_id = u.user_id ) as employee_total_completed_fulltime_projects', false);
		$this->db->select('ufpt.user_id as featured_user_profile');
		$this->db->from('users u');
		$this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
		$this->db->join('users_profile_base_information ubi', 'ubi.user_id = u.user_id', 'left');
		$this->db->join('users_address_details uadd', 'uadd.user_id = u.user_id', 'left');
		$this->db->join('users_company_accounts_additional_branches_addresses ubael', 'ubael.user_id = u.user_id', 'left');
		$this->db->join('users_accounts_profile_completion_tracking upct', 'upct.user_id = u.user_id', 'left');
		$this->db->join('users_additional_accesses_settings uas', 'uas.user_id = u.user_id', 'left');
		$this->db->join('users_featured_profiles_tracking_on_find_professionals_page ufpt', 'ufpt.user_id=u.user_id', 'left');
		$this->db->join('counties c', 'uadd.county_id = c.id', 'left');
		$this->db->join('localities l', 'uadd.locality_id = l.id', 'left');
		$this->db->join('postal_codes pc', 'uadd.postal_code_id = pc.id', 'left');
		$this->db->join('countries', 'uadd.country_id = countries.id', 'left');
		$this->db->where("IFNULL(ubi.description, '') != ''");
		$this->db->where("IFNULL(ubi.headline, '') != ''");
		$this->db->group_start();
			$this->db->where('uas.user_profile_not_displayed_on_find_professionals_page IS NULL OR uas.user_profile_not_displayed_on_find_professionals_page = "N"');
		$this->db->group_end();

		$order_by = "
				ud.current_membership_plan_id DESC,
				upct.user_profile_strength_value DESC,
				upct.number_of_portfolios_entries DESC,
				upct.number_of_certifications_entries DESC,
				upct.number_of_areas_of_expertise_entries DESC,
				upct.number_of_skills_entries DESC,
				upct.number_of_services_provided_entries DESC,
				upct.country_address_strength_value DESC,
				upct.phone_or_mobile_number_strength_value DESC,
				upct.contact_email_strength_value DESC,
				u.account_validation_date DESC
		 ";

		$this->db->order_by($order_by);
		
		if($start != '' && $limit != '') {
			$this->db->limit($limit, $start);
		} else if(isset($start)) {
			$this->db->limit($limit);
		}
		if(is_array($filter_arr['categories']) || is_array($filter_arr['parent_categories'])) {
			$this->db->group_start();
		}
		if(is_array($filter_arr['categories']) && !empty($filter_arr['categories'])) {
			$this->db->join('professionals_areas_of_expertise_listings_tracking clt', 'clt.user_id = u.user_id', 'left');
			$this->db->where_in('clt.professional_category_id', $filter_arr['categories']);
		}
		if(is_array($filter_arr['parent_categories']) && !empty($filter_arr['parent_categories'])) {
			$this->db->or_where_in('clt.professional_parent_category_id', $filter_arr['parent_categories']);
		}
		if(is_array($filter_arr['categories']) || is_array($filter_arr['parent_categories'])) {
			$this->db->group_end();
		}
		if(!empty($filter_arr['account_type']) && strpos($filter_arr['account_type'], 'all') == false) {
			$this->db->where('account_type', $filter_arr['account_type']);
		}
		// filter professionals based on min and max hourly rate selection done
		if(!empty($filter_arr['hourly_rate_range'])) {
			$this->db->group_start();
				foreach($filter_arr['hourly_rate_range'] as $key => $value) {
					if($key == 0) {
						$this->db->group_start();
						if(is_numeric($value['min']) && is_numeric($value['max'])) {
							$this->db->where('CAST(ubi.hourly_rate AS SIGNED) >=',$value['min']);							
							$this->db->where('CAST(ubi.hourly_rate AS SIGNED ) <=', $value['max']);
						} else {
							$this->db->where('CAST(ubi.hourly_rate AS SIGNED  ) >=', $value['min']);
						}
						$this->db->group_end();
					} else {					
						$this->db->or_group_start();
						if(is_numeric($value['min']) && is_numeric($value['max'])) {
							$this->db->or_where('CAST(ubi.hourly_rate AS SIGNED) >=',$value['min']);
							$this->db->where('CAST(ubi.hourly_rate AS SIGNED ) <=', $value['max']);
						} else {
							$this->db->where('CAST(ubi.hourly_rate AS SIGNED ) >=', $value['min']);
						}
						$this->db->group_end();		
					}
				}
			$this->db->group_end();
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

			$this->db->group_start();
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
			$this->db->group_end();
		}
		
		if(!empty($filter_arr['real_time_search_txt'])) {				
			if (empty($filter_arr['searchtxt_arr'])) {
				$filter_arr['searchtxt_arr'] = [];
			}
			array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
		}
		
		if($filter_arr['search_title_flag'] == "false" && !empty($filter_arr['searchtxt_arr']) && $filter_arr['search_type'] == 'include') {

			$this->db->join('find_professionals_users_information fpui', 'fpui.user_id = u.user_id');
			$schr = array_map(function($val){

				$val = trim(htmlspecialchars($val, ENT_QUOTES));
				$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
				if(!empty($val)) {
					$val = $val.'*';
				}
				return $val;				
			}, $filter_arr['searchtxt_arr']);
			$schr = implode(' ', $schr);
			$this->db->group_start();
				$this->db->where("MATCH(fpui.user_first_name, fpui.user_last_name, fpui.company_name, fpui.description) AGAINST('".$schr."' IN BOOLEAN MODE)");
			$this->db->group_end();
		} else if($filter_arr['search_title_flag'] == "false" && !empty($filter_arr['searchtxt_arr']) && $filter_arr['search_type'] == 'exclude') {
			$this->db->join('find_professionals_users_information fpui', 'fpui.user_id = u.user_id');
			$schr = array_map(function($val){
				$val = trim(htmlspecialchars($val, ENT_QUOTES));
				$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
				if(!empty($val)) {
					$val = '+'.$val.'*';
				}
				return $val;
			}, $filter_arr['searchtxt_arr']);
			$schr = implode(' ', $schr);
			$this->db->group_start();
				$this->db->where("MATCH(fpui.user_first_name, fpui.user_last_name, fpui.company_name, fpui.description) AGAINST('".$schr."' IN BOOLEAN MODE)");
			$this->db->group_end();
		}
		// search text in project title only
		if($filter_arr['search_title_flag'] == "true" && is_array($filter_arr['searchtxt_arr']) && !empty($filter_arr['searchtxt_arr'])) {
			$this->db->group_start();
				$schr = array_map(function($val){
					$val = trim(htmlspecialchars($val, ENT_QUOTES));
					$val = trim(preg_replace('/[+\-><\(\)~*\"@\%\\\\]+/', ' ', $val));
					if(!empty($val)) {
						$val = $val.'*';
					}
					return $val;
				},  $filter_arr['searchtxt_arr']);
				$schr = implode(' ', $schr);
				$this->db->where("MATCH(u.first_name, u.last_name, u.company_name, u.profile_name) AGAINST('".$schr."' IN BOOLEAN MODE)");
			$this->db->group_end();
		}

		// filter user profile based on profile_last_update_time
		if(!empty($filter_arr['profile_last_update_time'])) {
			$filter_arr['profile_last_update_time'] = str_replace('during_last_', '', $filter_arr['profile_last_update_time']);
			$split = explode('_', $filter_arr['profile_last_update_time']);
			if(end($split) == 'days') {
				$this->db->where('upct.profile_last_update_time >= (CURDATE() - INTERVAL '.$split[0].' DAY)');
			} else if(end($split) == 'hours') {
				$this->db->where('upct.profile_last_update_time >= (CURDATE() - INTERVAL '.$split[0].' HOUR)');
			} 
		}
		// filter user profile based on user_registration_time
		if(!empty($filter_arr['user_registration_time'])) {
			$filter_arr['user_registration_time'] = str_replace('during_last_', '', $filter_arr['user_registration_time']);
			$split = explode('_', $filter_arr['user_registration_time']);
			if(end($split) == 'days') {
				$this->db->where('u.account_validation_date >= (CURDATE() - INTERVAL '.$split[0].' DAY)');
			} else if(end($split) == 'hours') {
				$this->db->where('u.account_validation_date >= (CURDATE() - INTERVAL '.$split[0].' HOUR)');
			} 
		}
			
		$query =  $this->db->get_compiled_select();
		// pre($query);
		
		$result_query = $this->db->query($query);
		$users = $result_query->result_array();
		// pre($users);
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$total_rec = $query->row()->Count;

		$user_ids = array_column($users, 'user_id');
		if($this->session->userdata('user') && !empty($user_ids)) {
			$user = $this->session->userdata('user');
			$this->db->select('*');
			$this->db->from('users_contacts_tracking');
			$this->db->where('contact_initiated_by', $user[0]->user_id);
			$this->db->where_in('contact_requested_to', $user_ids);
			$result = $this->db->get()->result_array();
			if(!empty($result)) {
				$already_in_contact = array_unique(array_column($result, 'contact_requested_to'));
			}
		}

		foreach($users as $key => $user_detail) {
			$userArr[$user_detail['user_id']]['featured_user_profile']= $user_detail['featured_user_profile'];
			$userArr[$user_detail['user_id']]['user_id']=$user_detail['user_id'];
			$userArr[$user_detail['user_id']]['profile_name']=$user_detail['profile_name'];
			$userArr[$user_detail['user_id']]['company_name']=$user_detail['company_name'];
			$userArr[$user_detail['user_id']]['name']=$user_detail['first_name'].' '.$user_detail['last_name'];
			$userArr[$user_detail['user_id']]['headline']=$user_detail['headline'];
			$userArr[$user_detail['user_id']]['description']=$user_detail['description'];
			$userArr[$user_detail['user_id']]['gender']=$user_detail['gender'];
			$userArr[$user_detail['user_id']]['account_type']=$user_detail['account_type'];
			$userArr[$user_detail['user_id']]['is_authorized_physical_person']=$user_detail['is_authorized_physical_person'];
			$userArr[$user_detail['user_id']]['hourly_rate']=$user_detail['hourly_rate'];
			$userArr[$user_detail['user_id']]['street_address']=$user_detail['street_address'];
			$userArr[$user_detail['user_id']]['county']=$user_detail['county'];
			$userArr[$user_detail['user_id']]['locality']=$user_detail['locality'];
			$userArr[$user_detail['user_id']]['postal_code']=$user_detail['postal_code'];
			$userArr[$user_detail['user_id']]['total_reviews']=$user_detail['fulltime_project_user_total_reviews']+$user_detail['project_user_total_reviews'];
			//$userArr[$user_detail['user_id']]['reviews']='0.0';
			$userArr[$user_detail['user_id']]['user_total_avg_rating_as_sp']=$user_detail['user_total_avg_rating_as_sp'];
			$userArr[$user_detail['user_id']]['country_name']=$user_detail['country_name'];
			$userArr[$user_detail['user_id']]['country_code']=$user_detail['country_code'];
			$userArr[$user_detail['user_id']]['completed_projects_as_sp']=$user_detail['sp_total_completed_fixed_budget_projects']+$user_detail['sp_total_completed_hourly_based_projects'];
			$userArr[$user_detail['user_id']]['completed_projects_as_employee']=$user_detail['employee_total_completed_fulltime_projects'];

			if(!empty($already_in_contact) && in_array($user_detail['user_id'], $already_in_contact)) {
				$userArr[$user_detail['user_id']]['is_in_contact'] = true;
			}

			$user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
			$common_source_path = USERS_FTP_DIR . $user_detail['profile_name'];
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
				 $avatar_pic = $source_path_avatar . $user_detail['user_avatar'];
									 
				 if (!empty($user_detail['user_avatar'])) {
					 $file_size = $this->ftp->get_filesize($avatar_pic);
					 if ($file_size != '-1') {
					   $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
					 }
				 }else { 
					if(($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
							if($user_detail['gender'] == 'M'){
									$user_profile_picture = URL . 'assets/images/avatar_default_male.png';
							}if($user_detail['gender'] == 'F'){
								$user_profile_picture = URL . 'assets/images/avatar_default_female.png';
							}
					} else {
							$user_profile_picture = URL . 'assets/images/avatar_default_company.png';
					}
					
					// profile completeion script start //
					$user_profile_completion_data = array();
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] =0;
					// if($user_profile_completion_data){
						// $this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data,array('user_id'=>$user_detail['user_id'],'account_type'=>$user_detail['account_type']));
					
					// }
					// profile completeion script end //
			}
			//end check avatar
			$this->ftp->close();			

			$userArr[$user_detail['user_id']]['user_profile_picture'] = $user_profile_picture;
			$userArr[$user_detail['user_id']]['professional_category'] = $this->get_user_areas_of_expertise($user_detail['user_id']);

			if($this->session->userdata('user')) {
				$user = $this->session->userdata('user');
				$userArr[$user_detail['user_id']]['fb_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_fb']));
				$userArr[$user_detail['user_id']]['ln_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_ln']));
				$userArr[$user_detail['user_id']]['twitter_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_twitter']));
				$userArr[$user_detail['user_id']]['email_share_url'] = base_url($user_detail['profile_name']).'?rfrd='.base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_profile_share_email']));
			} else {
				$userArr[$user_detail['user_id']]['fb_share_url'] = base_url($user_detail['profile_name']);
				$userArr[$user_detail['user_id']]['ln_share_url'] = base_url($user_detail['profile_name']);
				$userArr[$user_detail['user_id']]['twitter_share_url'] = base_url($user_detail['profile_name']);
				$userArr[$user_detail['user_id']]['email_share_url'] = base_url($user_detail['profile_name']);
			}
		}
		return ['data' => $userArr, 'total' => $total_rec];
	}
}
?>