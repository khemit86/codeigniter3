<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Favorite_employers_model extends BaseModel {

    public function __construct() {
        parent::__construct();
        $this->load->model('user/User_model');
    }
    /** 
     * This method is used to insert or remove new employer detail when user subscribed or unscribed employer
    */    
    public function add_or_remove_employer_detail($data, $user, $action = '') {
        if(!empty($action) && $action == 'un-favorite') {
            $this->db->delete('users_favorite_employer_tracking', $data);
            $res['state'] = 2;
            return $res;
        }
        $row = $this->db->get_where('users_favorite_employer_tracking', $data)->row_array();
        $membership_plan = $this->db->get_where('users_details', ['user_id' => $user[0]->user_id])->row_array();
        $res = [];
        if(empty($row)) {
            $subscription_limit = 0;
            if($membership_plan['current_membership_plan_id'] == 1) {
                $subscription_limit = $this->config->item('free_subscribers_max_number_of_favorite_employers_subscriptions');
            } else if($membership_plan['current_membership_plan_id'] == 4) {
                $subscription_limit = $this->config->item('gold_subscribers_max_number_of_favorite_employers_subscriptions');
            }
            $favourite_employer_list_count = $this->db->get_where('users_favorite_employer_tracking', ['user_id' => $user[0]->user_id ])->result_array();
            if(count($favourite_employer_list_count) >= $subscription_limit ) {
                $res['error'] = true;
            } else {
                $this->db->insert('users_favorite_employer_tracking', $data);
                $res['state'] = 1;
            }
            
        } else {
            $this->db->delete('users_favorite_employer_tracking', $data);
            $res['state'] = 2;
        }
        return $res;
    }
    /**
     * This method is used to get all favorite employers data
    */
    public function get_all_favorite_employers($start = '', $limit = '') {
      $user = $this->session->userdata('user');
        $this->db->from('users_favorite_employer_tracking ufet');
        $this->db->where('ufet.user_id', $user[0]->user_id);
        if($start != '' && $limit != '') {
            $this->db->limit($limit, $start);
        } else if(isset($start)) {
            $this->db->limit($limit);
        }
        $favorite_employers =  $this->db->get()->result_array();
        foreach($favorite_employers as $key => $user_detail) {
			$this->User_model->remove_scrambled_user_address_entries($user_detail['user_id']);
		}

      
      $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS u.user_id, u.account_type,u.is_authorized_physical_person, u.profile_name, u.gender, u.first_name, u.last_name, u.company_name, ud.user_avatar,ud.project_user_total_avg_rating_as_po,ud.fulltime_project_user_total_avg_rating_as_employer, upbi.description, upbi.headline, c.name county,l.name locality,pc.postal_code postal_code,uadd.street_address,con.country_name,con.country_code', false);
      $this->db->select('(SELECT project_posting_date FROM '.$this->db->dbprefix.'projects_open_bidding where project_owner_id = u.user_id order by id desc limit 1) as last_project_posted_on');
	  $this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employer where feedback_recived_by_employer_id = u.user_id AND employer_already_placed_feedback= "Y") as fulltime_project_user_total_reviews');
	  $this->db->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_po where feedback_recived_by_po_id = u.user_id AND po_already_placed_feedback= "Y") as project_user_total_reviews');
      $this->db->from('users_favorite_employer_tracking ufet');
      $this->db->join('users u', 'u.user_id = ufet.favorite_employer_id', 'left');
      $this->db->join('users_details ud', 'ud.user_id = u.user_id', 'left');
      $this->db->join('users_profile_base_information upbi', 'upbi.user_id = u.user_id', 'left');
      $this->db->join('users_address_details uadd', 'uadd.user_id = u.user_id', 'left');
      $this->db->join('countries con', 'uadd.country_id = con.id', 'left');
      $this->db->join('counties c', 'uadd.county_id = c.id', 'left');
	    $this->db->join('localities l', 'uadd.locality_id = l.id', 'left');
      $this->db->join('postal_codes pc', 'uadd.postal_code_id = pc.id', 'left');
       
      $this->db->where('ufet.user_id', $user[0]->user_id);
      $this->db->order_by('ufet.id', 'desc');
		
      if($start != '' && $limit != '') {
        $this->db->limit($limit, $start);
      } else if(isset($start)) {
        $this->db->limit($limit);
      }
      $favorite_employers =  $this->db->get()->result_array();
    //   pre($this->db->get_compiled_select());

		
      $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
      $total_rec = $query->row()->Count;
      if(!empty($favorite_employers)) {
            $user_ids = array_column($favorite_employers, 'user_id');
            $this->db->select('*');
          $this->db->from('users_contacts_tracking');
          $this->db->where('contact_initiated_by', $user[0]->user_id);
          $this->db->where_in('contact_requested_to', $user_ids);
          $result = $this->db->get()->result_array();
          if(!empty($result)) {
            $already_in_contact = array_unique(array_column($result, 'contact_requested_to'));
          }
      }
        foreach($favorite_employers as $key => &$value) {
            $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$value['profile_name'].USER_AVATAR.$value['user_avatar'];
            
            if(!empty($value['user_avatar']) && is_url_exist($user_profile_picture)) {
                $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$value['profile_name'].USER_AVATAR.$value['user_avatar'];
            } else {
                if(($value['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($value['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $value['is_authorized_physical_person'] == 'Y')) {
                    if($value['gender'] == 'M'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }
                    if($value['gender'] == 'F'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                } else {
                        $user_profile_picture = URL . 'assets/images/avatar_default_company.png';
                }
            }
            $value['user_avatar'] = $user_profile_picture;
            if(!empty($already_in_contact) && in_array($value['user_id'], $already_in_contact)) {
				$value['is_in_contact'] = true;
			}
        }
        
		
        return ['data' => $favorite_employers, 'total' => $total_rec];
    }
}
?>