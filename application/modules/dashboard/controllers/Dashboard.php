<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('Dashboard_model'); //important
        $this->load->model('post_project/Post_project_model'); //used repost, edit_job, edit_job_extend, edit_job_upgrade
        $this->load->model('projects/Projects_model'); //used index, edit_job
        $this->load->model('bidding/Bidding_model'); //used index, edit_job
        $this->load->model('user/User_model'); //used index
        $this->load->model('chat/Chat_model'); //used index
        $this->load->library('form_validation');

        $this->load->helper('url');
        if ($this->session->userdata('user') == null) {
            redirect(site_url());
        }
    }

   
    public function user_dashboard() {
		
        if (check_session_validity()) {
            $this->session->unset_userdata('edit_page');
            $user = $this->session->userdata('user');
            ############# get the user detail start here########
            $user_detail = $this->db // get the user detail
            ->select('u.user_id,u.account_type, u.first_name, u.last_name, u.company_name,u.is_authorized_physical_person, u.gender, u.profile_name, ud.user_avatar, ud.user_account_balance, ud.bonus_balance, u.referral_code, ud.signup_bonus_balance,ud.current_membership_plan_id,ud.user_total_avg_rating_as_sp, mp.membership_plan_name, mp.id AS plan_id, mp.signup_bonus_value, upbi.headline,upbi.hourly_rate, uad.street_address, uad.locality_id, uad.county_id,uad.country_id, uad.postal_code_id,countries.country_name,countries.country_code')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
			->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
            ->from('users u')
            ->join('users_details ud', 'ud.user_id = u.user_id', 'left')
            ->join('users_profile_base_information upbi', 'upbi.user_id = u.user_id', 'left')
            ->join('membership_plans mp', 'mp.id=ud.current_membership_plan_id', 'left')
			->join('users_address_details uad', 'uad.user_id = u.user_id', 'left')
			->join('countries', 'countries.id = uad.country_id', 'left')
            ->where('u.user_id', $user[0]->user_id)
            ->get()->row_array();
			$address_details = '';
			if(!empty($user_detail['country_id'])){
			
				$address_details = '<div class="default_user_location"><span><i class="fas fa-map-marker-alt"></i></span>';
			
				// For counties
				$this->db->select('name');
				$this->db->where('id', $user_detail['county_id']);
				$qryC = $this->db->get('counties');
				$countyArr = $qryC->row_array();
				// For localities
				$this->db->select('name');
				$this->db->where('county_id', $user_detail['county_id']);
				$this->db->where('id', $user_detail['locality_id']);
				$qryL = $this->db->get('localities');
				$localityArr = $qryL->row_array();
				// For postal code
				$this->db->select('postal_code');
				$this->db->where('locality_id', $user_detail['locality_id']);
				$this->db->where('id', $user_detail['postal_code_id']);
				$qryP = $this->db->get('postal_codes');
				$postalCodeArr = $qryP->row_array();
				
				if(!empty($user_detail['street_address'])){
					if(!preg_match('/\s/',$user_detail['street_address'])) { 
						$address_details .= '<small class="street_address_nospace" style="font-size: 15px;font-weight:normal; !important;">'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
					} else {
						$address_details .= '<small>'.htmlspecialchars($user_detail['street_address'], ENT_QUOTES).',</small>';
					}
				}
				
				if(!empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
					$address_details .= '<small>'.$localityArr['name'].' '.$postalCodeArr['postal_code'].',</small>';
				}
				if(empty($localityArr['name']) && !empty($postalCodeArr['postal_code'])){
					$address_details .= '<small> '.$postalCodeArr['postal_code'].',</small>';
				}
				if(!empty($localityArr['name']) && empty($postalCodeArr['postal_code'])){
					$address_details .= '<small>'.$localityArr['name'].',</small>';
				}
				if(!empty($countyArr['name'])){
					$address_details .= '<small>'.$countyArr['name'].',</small>';
				}
				$country_flag = ASSETS .'images/countries_flags/'.strtolower($user_detail['country_code']).'.png';
				$address_details .= '<small>'.$user_detail['country_name'].'<div class="default_user_location_flag" style="background-image: url('.$country_flag.');"></div></small>';
				$address_details .= '</div>';
			}
			$data['address_details'] = $address_details;
			
			
			
			
			
			$check_user_upgrade_gold_membership = $this->db->where(['user_id' => $user[0]->user_id,'upgrade_to_membership_plan'=>4])->from('users_membership_plans_upgrade_tracking')->count_all_results();
			$data['check_user_upgrade_gold_membership'] = $check_user_upgrade_gold_membership;
			
			/* $this->load->model ('users_ratings_feedbacks/Users_ratings_feedbacks_model');
			echo $this->Users_ratings_feedbacks_model->get_sp_total_completed_projects_count($user[0]->user_id);
			die; */
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
			######## connectivity of remote server start #######
            $this->load->library('ftp');
            $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
            $config['ftp_username'] = FTP_USERNAME;
            $config['ftp_password'] = FTP_PASSWORD;
            $config['ftp_port'] = FTP_PORT;
            $config['debug'] = TRUE;
            $this->ftp->connect($config);
            $common_source_path = USERS_FTP_DIR . $user_detail['profile_name'];
            ;
            //avatar picture
            //start check avatar from ftp server
            $user_avatar = USER_AVATAR;
            $source_path_avatar = $common_source_path . $user_avatar;

            $avatarlist = $this->ftp->list_files($source_path_avatar);
            $avatar_pic = $source_path_avatar . $user_detail['user_avatar'];

            $exap = explode('.', $user_detail['user_avatar']);
            $original_user_avatar = $source_path_avatar . $exap[0] . '_original.png';
            // var_dump($avatarlist);
            if (count($avatarlist) > 0) {
                $acheck = true;
                if (!in_array($avatar_pic, $avatarlist) && $acheck) {
                    $this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $user[0]->user_id));
                    $this->ftp->delete_dir($source_path_avatar);
                    $user_detail['user_avatar'] = '';
                    $acheck = false;
					// profile completion script start 
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] = 0;
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					// profile completion script end 
                } if (!in_array($original_user_avatar, $avatarlist) && $acheck) {
                    $this->db->update('users_details', array('user_avatar' => ''), array("user_id" =>$user[0]->user_id));
                    $this->ftp->delete_dir($source_path_avatar);
                    $user_detail['user_avatar'] = '';
                    $acheck = false;
					// profile completion script start 
					$user_profile_completion_data['has_avatar'] = 'N';
					$user_profile_completion_data['avatar_strength_value'] = 0;
					$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
					// profile completion script end 
                }
            } if (count($avatarlist) == 0 && $user_detail['user_avatar'] != '') {
                $this->db->update('users_details', array('user_avatar' => ''), array("user_id" => $user[0]->user_id));
                $user_detail['user_avatar'] = '';
				
				// profile completion script start 
				$user_profile_completion_data['has_avatar'] = 'N';
				$user_profile_completion_data['avatar_strength_value'] = 0;
				$this->Dashboard_model->update_user_profile_completion_data($user_profile_completion_data);
				// profile completion script end 
				
            }

            $this->ftp->close();
            ######## connectivity of remote server end #######

            $data['user_detail'] = $user_detail;
           
            $user_id = $user[0]->user_id;
            $name = (($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_PERSONAL_ACCOUNT_TYPE) || ($this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id) == USER_COMPANY_ACCOUNT_TYPE && $this->auto_model->getFeild('is_authorized_physical_person', 'users', 'user_id', $user_id) == 'Y')) ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);

            $usr = $this->auto_model->get_user_data_new($user[0]->user_id);
            foreach ($usr as $key => $value) {
                $data[$key] = $value;
            }
            $data["profile_name"] = $user[0]->profile_name;
            $scd = [];

            $data["profile_name"] = $user[0]->profile_name;
            $scd = [];

           
            //start for invite friends
           
            $data["share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" . base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_referral_url_direct_usage']));
            //end for invite friends
            /* $data['first_name'] = $this->user_first_name;
            $data['last_name'] = $this->user_last_name;
            $data['user_associated'] = $this->user_associated; */
			
			
			
            $data['balance'] = floatval($user_detail['user_account_balance']);
            $data['ip'] = $this->autoload_model->getFeild('latest_source_ip', 'users', 'user_id', $user[0]->user_id);
            $sql = "select * from `user_log` where (`user_id`='" . $user[0]->user_id . "' and `id`<>'" . $this->session->userdata('user_log_id') . "') order by `timestamp` desc limit 1";
            $data['sql'] = $sql;
            $r = $this->db->query($sql)->result();
            if (count($r) > 0) {
                $data['ld'] = $r;
            } else {
                $sql = "select * from `user_log` where (`user_id`='" . $user[0]->user_id . "' and `id`='" . $this->session->userdata('user_log_id') . "') order by `timestamp` desc limit 1";
                $data['sql'] = $sql;
                $r = $this->db->query($sql)->result();
                if (count($r) > 0) {
                    $data['ld'] = $r;
                }
            }
            $status = 'O';

            $data['completeness'] = $completeness = $this->auto_model->getCompleteness($user[0]->user_id);
            $data['completeness'] = $completeness;
            ///////////////////////////Leftpanel Section end//////////////////

            $data['users'] = $this->auto_model->count_results('', 'users');

            $data["user_record"] = $this->db->get_where('users', array('user_id' => $user[0]->user_id))->result()[0];
            foreach ($data["user_record"] as $key => $val) {
                $data[$key] = $val;
            }
            unset($data["user_record"]);
			
			$data['user_profile_completion'] = $this->Dashboard_model->get_user_profile_completion_data();

			//$user_profile_completion = $this->Dashboard_model->get_user_profile_completion_result($user[0]->user_id);
            //$data['user_profile_completion'] = $user_profile_completion;
			//$data['user_profile_completion'] = 5;
            $data['current_page'] = 'dashboard';
            ########## set the dashboard title meta tag and meta description  start here #########
            $dashboard_title_meta_tag = $this->config->item('dashboard_page_title_meta_tag');
            $dashboard_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $dashboard_title_meta_tag);

            $dashboard_description_meta_tag = $this->config->item('dashboard_page_description_meta_tag');
            $dashboard_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $dashboard_description_meta_tag);
            $data['meta_tag'] = '<title>' . $dashboard_title_meta_tag . '</title><meta name="description" content="' . $dashboard_description_meta_tag . '"/>';
            ########## set the dashboard title tag start end #########

            $data["locality_name"] = '';

            $redr = '';
            if (isset($_SESSION["redirect"])) {
                $redr = $_SESSION["redirect"];
            }
            $_SESSION["redirect"] = "";
            if ($redr != "") {
                redirect($redr);
            }
            $ar = $data;
            $data["dt"] = $ar;
            $logstr = 'user ' . get_client_ip() . '/' . $this->session->userdata('user_log_id') . ' visited dashboard page';
			$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
			$data["draft_project_data"] = $draft_project_data['data'];
			$data["draft_project_count"] = $draft_project_data['total'];
			
			$awaiting_moderation_project_data = $this->Projects_model->get_po_awaiting_moderation_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awaiting_moderation_projects_listing_limit'));
            $data['awaiting_moderation_project_count'] = $awaiting_moderation_project_data['total'];
            $data['fulltime_awaiting_moderation_project_count'] = $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_type' => 'fulltime' ])->from('projects_awaiting_moderation')->count_all_results();
			$open_bidding_project_data = $this->Projects_model->get_po_open_for_bidding_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_open_bidding_projects_listing_limit'));
			 $data['open_bidding_project_count'] = $open_bidding_project_data['total'];
			##################### fetch the awarded projects from database and show on dashboard############
            $awarded_project_data = $this->Projects_model->get_po_awarded_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_awarded_projects_listing_limit'));
			//$data["awarded_project_data"] = $awarded_project_data['data'];
			$data['awarded_project_count'] = $awarded_project_data['total'];
			
			##################### fetch the in progress projects from database and show on dashboard############
			$in_progress_project_data = $this->Projects_model->get_po_in_progress_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_in_progress_projects_listing_limit'));
			$data['in_progress_project_count'] = $in_progress_project_data['total'];
			
			 ##################### fetch the fixed budget completed projects from database and show on dashboard############
			$completed_project_data = $this->Projects_model->get_po_completed_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_completed_projects_listing_limit'));
			$data['completed_project_count'] = $completed_project_data['total'];
			
			##################### fetch the in complete projects from database and show on dashboard############
			$in_complete_project_data = $this->Projects_model->get_po_incomplete_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_incomplete_projects_listing_limit'));
			$data['in_complete_project_count'] = $in_complete_project_data['total'];
			
			
			
			
            ##################### fetch the expired projects from database and show on dashboard############
			$expired_project_data = $this->Projects_model->get_po_expired_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_expired_projects_listing_limit'));
            $data['expired_project_count'] = $expired_project_data['total'];
            $fulltime_expired_project_cnt = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_expired')->count_all_results(); 
            $fulltime_expired_project_cnt += $this->db->where(['project_owner_id' => $user[0]->user_id, 'project_expiration_date <'=>date('Y-m-d H:i:s'), 'project_type' => 'fulltime'])->from('projects_open_bidding')->count_all_results();
            $data['fulltime_expired_project_count'] = $fulltime_expired_project_cnt;
            ##################### fetch the fixed budget cancelled projects from database and show on dashboard############
            $cancelled_project_data = $this->Projects_model->get_po_cancelled_project_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_cancelled_projects_listing_limit'));
			$data['cancelled_project_count'] = $cancelled_project_data['total'];
			$fulltime_cancelled_project_count = $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled')->count_all_results(); 
            $fulltime_cancelled_project_count += $this->db->where(['employer_id' => $user[0]->user_id])->from('fulltime_projects_cancelled_by_admin')->count_all_results(); 
            $data['fulltime_cancelled_project_count'] = $fulltime_cancelled_project_count;
			

            ##################### fetch the open bidding projects data from database to display under latest project section ##########
            $data['latest_projects_section_data'] = $this->Projects_model->get_all_latest_projects();
			
			
			
            ##################### end ###########################################
			
			######## fetch the active bids from database and show on myproject 
			$draft_project_data = $this->Projects_model->get_po_draft_projects_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_po_view_draft_projects_listing_limit'));
			$data["draft_project_data"] = $draft_project_data['data'];
			$data["draft_project_count"] = $draft_project_data['total'];
			
			
			
			$active_bids_project_data = $this->Bidding_model->get_sp_active_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_active_bids_listing_limit'));
			$data['active_bids_project_count'] = $active_bids_project_data['total'];
            $data['active_bids_project_data'] = $active_bids_project_data['data'];
            $data['fulltime_active_bids_project_count'] = $this->db->where(['employee_id' => $user[0]->user_id])->from('fulltime_projects_open_bidding_active_applications')->count_all_results();
            $data['fulltime_active_bids_project_count'] += $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date <=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
			
			######## fetch the awarded bids from database and show on myproject 		
			$awarded_bids_project_data = $this->Bidding_model->get_sp_awarded_bids_listing_my_projects($user[0]->user_id,0,$this->config->item('user_dashboard_sp_view_awarded_bids_listing_limit'));
            $data['awarded_bids_project_count'] = $awarded_bids_project_data['total'];
            $data['fulltime_awarded_bids_project_count'] = $this->db->where(['employee_id' => $user[0]->user_id, 'application_award_expiration_date >=' => date('Y-m-d H:i:s')])->from('fulltime_projects_awarded_tracking')->count_all_results();
			######## fetch the in progress bids from database and show on myproject 		
			$in_progress_bids_project_data = $this->Bidding_model->get_sp_in_progress_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_in_progress_bids_listing_limit'));
			$data['in_progress_bids_project_count'] = $in_progress_bids_project_data['total'];
			
			######## fetch the in complete bids from database and show on myproject 		
			$in_complete_bids_project_data = $this->Bidding_model->get_sp_incomplete_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_incomplete_bids_listing_limit'));
			$data['in_complete_bids_project_count'] = $in_complete_bids_project_data['total'];
			
			######## fetch the hired projects from database and show on myproject 		
			$hired_project_data = $this->Bidding_model->get_employee_hired_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_fulltime_projects_employee_view_hired_listing_limit'));
			$data['hired_project_count'] = $hired_project_data['total'];
			
			######## fetch the completed bids from database and show on myproject 		
			$completed_bids_project_data = $this->Bidding_model->get_sp_completed_bids_listing_my_projects($user[0]->user_id,0, $this->config->item('user_dashboard_sp_view_completed_bids_listing_limit'));
            $data['completed_bids_project_count'] = $completed_bids_project_data['total'];
            
            ######## fetch data for users chat list ###################################################
            $data['users_chat_list'] = $this->Chat_model->get_user_contacts_list();
            // pre($data['users_chat_list']);

            ############################ invite friends statistics data ######################################################
            $data["fb_share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_self_url_share_fb']));
		    $data["twitter_share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_self_url_share_twitter']));
            $data["linkedin_share_link"] = site_url() . $this->config->item('referrer_page_url')."?rfrd=" .base64_encode(json_encode(['code' => $user[0]->referral_code, 'source' => 'user_self_url_share_ln']));
            
			$data['invited_friends_registered_via_fb_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_fb', 'project_url_share_fb', 'user_profile_share_fb', 'user_portfolio_sap_share_fb'])->count_all_results();
			$data['invited_friends_registered_via_twitter_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_twitter', 'project_url_share_twitter', 'user_profile_share_twitter', 'user_portfolio_sap_share_twitter'])->count_all_results();
			$data['invited_friends_registered_via_ln_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_ln', 'project_url_share_ln', 'user_profile_share_ln', 'user_portfolio_sap_share_ln'])->count_all_results();
            
            $data['invited_friends_registered_via_code_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id, 'referee_source' => 'user_referral_code_direct_usage'])->count_all_results();
            $data['invited_friends_registered_via_url_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id, 'referee_source' => 'user_referral_url_direct_usage'])->count_all_results();
            $data['invited_friends_registered_via_email_count'] = $this->db->from('users')->where(['lvl1_referrer_id' => $user[0]->user_id])->where_in('referee_source', ['user_self_url_share_email', 'project_url_share_email', 'user_profile_share_email', 'user_portfolio_sap_share_email'])->count_all_results();
            $twitter_share_message = $this->config->item('user_referral_url_twitter_share_message');
            $twitter_share_message = str_replace('{referral_url}',$data["twitter_share_link"], $twitter_share_message);
            $data['twitter_share_message'] = $twitter_share_message;
            
            $this->layout->view('user_dashboard', '', $data, 'include');
        } else {
            redirect(site_url());
        }
    }

    /**
	 * @sid This method is used to sent user feedback -> assets/js/modules/user_dashboard.js
	*/
    public function ajax_user_send_feedback() {
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
		$res['status'] = 200;
		$i = 0;
		if($row['description'] == '') {
			$res['error'][$i]['id'] = 'description_error'; 
			$res['error'][$i]['msg'] = $this->config->item('send_feedback_popup_description_required_error_message'); 
			$i++;
        } else if($row['description'] != '') {
            $user_description_charcaters_length = mb_strlen(preg_replace('/\s+/', '', trim($row['description'])));
			if($this->config->item('send_feedback_popup_minimum_length_word_limit') == 0) {
				if($user_description_charcaters_length < $this->config->item('send_feedback_popup_minimum_length_character_limit')) {
					$res['error'][$i]['id'] = 'description_error';
					$res['error'][$i]['msg'] = $this->config->item('send_feedback_popup_minimum_length_error_message');
					$i++;
				}
			} else {
				$string_only_single_space = preg_replace("/\s+/", " ", trim($row['description']));
				$user_description_word_count = count(explode(' ', trim($string_only_single_space)));
				if($user_description_charcaters_length < $this->config->item('send_feedback_popup_minimum_length_character_limit') ||$user_description_word_count < $this->config->item('send_feedback_popup_minimum_length_word_limit') ){
					$res['error'][$i]['id'] = 'description_error';
					$res['error'][$i]['msg'] = $this->config->item('send_feedback_popup_word_minimum_length_error_message');
					$i++;
				}
			}
        }
        if($i == 0) {
            $attachment_name_arr = [];
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
			
						if(($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) {
							if($user[0]->gender == 'M') {
								if($user[0]->is_authorized_physical_person == 'Y'){
									$subject = $this->config->item('email_subject_send_feedback_company_app_male_sender');
									$msg = $this->config->item('email_message_send_feedback_company_app_male_sender');
								} else {
									$subject = $this->config->item('email_subject_send_feedback_male_sender');
									$msg = $this->config->item('email_message_send_feedback_male_sender');
								}
							} else {
								if($user[0]->is_authorized_physical_person == 'Y'){
									$subject = $this->config->item('email_subject_send_feedback_company_app_female_sender');
									$msg = $this->config->item('email_message_send_feedback_company_app_female_sender');
								} else {
									$subject = $this->config->item('email_subject_send_feedback_female_sender');
									$msg = $this->config->item('email_message_send_feedback_female_sender');
								}
							}
							$subject = str_replace('{user_first_name_last_name}', $user[0]->first_name.' '.$user[0]->last_name, $subject);
							$msg = str_replace(['{user_first_name_last_name}'],[ $user[0]->first_name.' '.$user[0]->last_name], $msg);
						} else {
							$subject = $this->config->item('email_subject_send_feedback_company_sender');
							$msg = $this->config->item('email_message_send_feedback_company_sender');
											
							$subject = str_replace('{user_company_name}', $user[0]->company_name, $subject);
							$msg = str_replace(['{user_company_name}'], [$user[0]->company_name], $msg);
							
						}
						$profile_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$user[0]->profile_name;
						
						
						$msg = str_replace(['{user_profile_page_url}', '{feedback_message}'], [$profile_url,$row['description']], $msg);

						$this->email->initialize($config);
						if($this->config->item('email_from_send_feedback') || $this->config->item('email_from_send_feedback')) {
								$this->email->from($this->config->item('email_from_send_feedback'), $this->config->item('email_from_name_send_feedback'));
						}
						if($this->config->item('email_reply_to_send_feedback')) {
								$this->email->reply_to($this->config->item('email_reply_to_send_feedback'));
						}
						if($this->config->item('email_cc_send_feedback')) {
								$this->email->cc($this->config->item('email_cc_send_feedback'));
						}
						if($this->config->item('email_bcc_send_feedback')) {
								$this->email->bcc($this->config->item('email_bcc_send_feedback'));
						}
						$this->email->subject($subject);
						$this->email->to($user[0]->email);
						$this->email->message($msg);
									
						if(!empty($_FILES)) {
							foreach($_FILES['files']['name'] as $key => $file) {
								$temp 		= 	explode(".", $file);
								$extension 	= 	end($temp);
								$attachment_name = round(microtime(true) * 1000);
								$temp_attachment_name = $attachment_name.'.'.$extension;
								if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$temp_attachment_name)){ 
									$this->email->attach(TEMP_DIR. $temp_attachment_name);
									array_push($attachment_name_arr, $temp_attachment_name);
								}
							}
						}
						if($this->email->send()) {
							if(!empty($attachment_name_arr)) {
								foreach($attachment_name_arr as $file) {
									unlink(FCPATH .TEMP_DIR. $file); 
								}
							}
						}
						
						// Activity log
						$activity_log = $this->config->item('send_feedback_popup_user_activity_log_mesage');
						user_display_log($activity_log);
						// Confirmation message
						$confirm_msg = $this->config->item('send_feedback_popup_submit_confirmation_mesage');			
						$res['confirm_msg'] = $confirm_msg;
        }
        echo json_encode($res);
		return;
    }
    
}
?>