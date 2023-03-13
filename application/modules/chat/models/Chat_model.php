<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH.'third_party/users_chat/connect_cassandra_db.php';
class Chat_model extends BaseModel {
    private $conn;
    public function __construct() {
        parent::__construct();
        $this->load->model('projects/Projects_model');
        $this->conn = new connect_cassandra_db();
    }

    /**
     * This method is used to get all pendeing request for logged in user to display receive contact list in header as well as on contact management page
    */
    public function get_user_all_pending_get_in_contact_requests($start = '', $limit = '') {
        $user = $this->session->userdata('user');
        $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS uvrt.id, uvrt.get_in_contact_request_send_date, u.user_id,ud.user_avatar,u.first_name, u.last_name, u.profile_name, u.account_type,u.is_authorized_physical_person,u.company_name, u.gender ,ubi.headline ', false);
        $this->db->from('users_valid_get_in_contact_requests_tracking uvrt');
        $this->db->join('users u', 'uvrt.get_in_contact_request_sender = u.user_id');
        $this->db->join('users_details ud', 'uvrt.get_in_contact_request_sender = ud.user_id', 'left');
        $this->db->join('users_profile_base_information ubi', 'uvrt.get_in_contact_request_sender = ubi.user_id', 'left');
        $this->db->where('uvrt.get_in_contact_request_receiver', $user[0]->user_id);
        $this->db->where('uvrt.get_in_contact_validity_expiration_date > NOW()');
        // $this->db->group_by('u.user_id');
        $this->db->order_by('uvrt.id', 'desc');
        if($start != '' && $limit != '') {
			    $this->db->limit($limit, $start);
        } else if(isset($start)) {
			    $this->db->limit($limit);
        }
        $users =  $this->db->get()->result_array();
		    $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $total_rec = $query->row()->Count;
        foreach($users as $key => &$user_detail) {
            if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
              $users[$key]['display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
            }else {
              if($user_detail['is_authorized_physical_person'] == 'Y') {
                $users[$key]['display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
              }else{
                $users[$key]['display_name'] = $user_detail['company_name'];
              }
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
                if($user_detail['account_type'] == 1){
                    if($user_detail['gender'] == 'M'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }if($user_detail['gender'] == 'F'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                } else {
                  if($user_detail['is_authorized_physical_person'] == 'Y'){
                    if($user_detail['gender'] == 'M'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }if($user_detail['gender'] == 'F'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                  }else{
                    $user_profile_picture = URL . 'assets/images/avatar_default_company.png';
                  }
                }
            }
            //end check avatar
            $this->ftp->close();

            $users[$key]['display_date'] = date(DATE_TIME_FORMAT, strtotime($user_detail['get_in_contact_request_send_date']));
            $user_detail['user_avatar'] = $user_profile_picture;

        }
        return ['data' => $users, 'total' => $total_rec];
    }
    /**
     * This method is used to get all rejected request for logged in user to display receive contact list in header as well as on contact management page
    */
    public function get_user_all_rejected_get_in_contact_requests($start = '', $limit = '') {
        $user = $this->session->userdata('user');
        $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS urrt.id, urrt.get_in_contact_request_send_date, u.user_id,ud.user_avatar,u.first_name, u.last_name, u.profile_name, u.account_type,u.is_authorized_physical_person,u.company_name, u.gender ,ubi.headline ', false);
        $this->db->from('users_get_in_contact_rejected_requests_tracking urrt');
        $this->db->join('users u', 'urrt.get_in_contact_request_sender = u.user_id');
        $this->db->join('users_details ud', 'urrt.get_in_contact_request_sender = ud.user_id', 'left');
        $this->db->join('users_profile_base_information ubi', 'urrt.get_in_contact_request_sender = ubi.user_id', 'left');
        $this->db->where('urrt.get_in_contact_request_receiver', $user[0]->user_id);
        $this->db->order_by('urrt.id', 'desc');
        if($start != '' && $limit != '') {
			    $this->db->limit($limit, $start);
        } else if(isset($start)) {
			    $this->db->limit($limit);
        }
        $users =  $this->db->get()->result_array();
		    $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $total_rec = $query->row()->Count;
        foreach($users as $key => &$user_detail) {
            if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
              $users[$key]['display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
            } else {
              if($user_detail['is_authorized_physical_person'] == 'Y') {
                $users[$key]['display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
              }else{
                $users[$key]['display_name'] = $user_detail['company_name'];
              }
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
                if($user_detail['account_type'] == 1){
                    if($user_detail['gender'] == 'M'){
                            $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }else{
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                } else {
                  if($user_detail['is_authorized_physical_person'] == 'Y'){
                    if($user_detail['gender'] == 'M'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }else{
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                  }else{
                    $user_profile_picture = URL . 'assets/images/avatar_default_company.png';
                  }
                }
            }
            //end check avatar
            $this->ftp->close();

            $users[$key]['display_date'] = date(DATE_TIME_FORMAT, strtotime($user_detail['get_in_contact_request_send_date']));
            $user_detail['user_avatar'] = $user_profile_picture;

        }
        return ['data' => $users, 'total' => $total_rec];
    }
    /*
     * This method is used to get all blocked request for logged in user to display on contact management page
    */
    public function get_user_all_blocked_get_in_contact_requests($start = '', $limit = '') {
        $user = $this->session->userdata('user');
        $this->db->select('DISTINCT SQL_CALC_FOUND_ROWS ubct.id, ubct.contact_block_date, u.user_id,ud.user_avatar,u.first_name, u.last_name, u.profile_name, u.account_type,u.is_authorized_physical_person,u.company_name, u.gender ,ubi.headline ', false);
        $this->db->from('users_blocked_contacts_tracking ubct');
        $this->db->join('users u', 'ubct.blocked_contact_id = u.user_id');
        $this->db->join('users_details ud', 'ubct.blocked_contact_id = ud.user_id', 'left');
        $this->db->join('users_profile_base_information ubi', 'ubct.blocked_contact_id = ubi.user_id', 'left');
        $this->db->where('ubct.contact_blocked_by', $user[0]->user_id);
        $this->db->order_by('ubct.id', 'desc');
        if($start != '' && $limit != '') {
			    $this->db->limit($limit, $start);
        } else if(isset($start)) {
			    $this->db->limit($limit);
        }
        $users =  $this->db->get()->result_array();
		    $query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $total_rec = $query->row()->Count;
        foreach($users as $key => &$user_detail) {
            if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
              $users[$key]['display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
            } else {
              if($user_detail['is_authorized_physical_person'] == 'Y') {
                $users[$key]['display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
              }else{
                $users[$key]['display_name'] = $user_detail['company_name'];
              }
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
                if($user_detail['account_type'] == 1){
                    if($user_detail['gender'] == 'M'){
                            $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }else{
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                } else {
                  if($user_detail['is_authorized_physical_person'] == 'Y'){
                    if($user_detail['gender'] == 'M'){
                        $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }else{
                        $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                  }else{
                    $user_profile_picture = URL . 'assets/images/avatar_default_company.png';
                  }          
                }
            }
             //end check avatar
             $this->ftp->close();
            $users[$key]['display_date'] = date(DATE_TIME_FORMAT, strtotime($user_detail['contact_block_date']));
            $user_detail['user_avatar'] = $user_profile_picture;

        }
        return ['data' => $users, 'total' => $total_rec];
    }
    /**
     * This method is used to get user unseen pending get in contact request count for logged in user to display on header menu when any request receive
    */
    public function get_user_unseen_pending_get_in_contact_requests_count() {
      $user = $this->session->userdata('user');
      return $this->db->from('users_valid_get_in_contact_requests_tracking')
              ->where('get_in_contact_validity_expiration_date > NOW()')
              ->where('is_seen_by_receiver', 'no')
              ->where('get_in_contact_request_receiver', $user[0]->user_id)
              ->count_all_results();
    }
    /**
     * This method is used on dashboard to display users contact list
     */
    public function get_user_contacts_list($limit = '',  $start = '') {
      $user = $this->session->userdata('user');
      // set user timezone to get date in his localtime
      date_default_timezone_set($this->session->userdata('user_timezone'));

      $this->db->select('u.user_id, u.first_name, u.last_name, u.company_name, u.profile_name, u.gender, u.account_type,u.is_authorized_physical_person, ud.user_avatar, uct.project_id, uct.project_channel_name, uct.channel_unread_messages_count as unread_msg_count');
      $this->db->select('lmt.id, lmt.project_owner_id, lmt.service_provider_id, lmt.latest_message_sender_id, lmt.latest_message, lmt.latest_message_sent_time, lmt.has_attachments,lmt.latest_message_sent_timestamp');
      $this->db->from('users_contacts_tracking uct');
      $this->db->join('users_latest_messages_tracking lmt', 'lmt.id = uct.latest_message_id', 'left');
      $this->db->join('users u', 'u.user_id = uct.contact_requested_to', 'left');
      $this->db->join('users_details ud', 'ud.user_id = uct.contact_requested_to', 'left');
      $this->db->where('uct.contact_initiated_by', $user[0]->user_id);
      $this->db->where('uct.is_blocked = "no"');
      $this->db->order_by('lmt.latest_message_sent_time', 'desc');
      $user_contacts = $this->db->get()->result_array();
      if(!empty($user_contacts)) {
        $final_result = [];
        foreach($user_contacts as $value) {
          $final_result["'".$value['user_id']."'"][] = $value;
        }
        if(!empty($limit)) {
          $final_result = array_slice($final_result, $start, $limit, true);
        }
        $chat_list = [];
        

        foreach($final_result as $key => $array) {
          if(!empty($array)) {
            $first = $array[0];
               $common_source_path = USERS_FTP_DIR . $first['profile_name'];
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
               $avatar_pic = $source_path_avatar . $first['user_avatar'];
                                   
               if (!empty($first['user_avatar'])) {
                   $file_size = $this->ftp->get_filesize($avatar_pic);
                   if ($file_size != '-1') {
                    $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$first['profile_name'].USER_AVATAR.$first['user_avatar'];
                  }
               }else {
                if($first['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($first['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $first['is_authorized_physical_person'] == 'Y')){
                  if($first['gender'] == 'M'){
                    $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                  } else if($first['gender'] == 'F'){
                    $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                  }
                } else {
                  $user_profile_picture = URL . 'assets/images/avatar_default_company.png';
                }
              }
               //end check avatar
               $this->ftp->close();
          }
          foreach($array as $val) {
              if($val['project_id'] != 0 && $val['project_channel_name'] == '') {
                continue;
              }
              $tmp = $val;
              
              $tmp['profile_pic_url'] = $user_profile_picture;
              if($val['latest_message_sent_timestamp'] != '') {
                $tmp['display_latest_message_sent_time'] = date(DATE_TIME_FORMAT_EXCLUDE_SECOND, $val['latest_message_sent_timestamp']);
              } else {
                $tmp['display_latest_message_sent_time'] = '';
              }

              if($val['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($val['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $val['is_authorized_physical_person'] == 'Y')) {
                $tmp['user_name'] = $val['first_name'].' '.$val['last_name'];
              } else {
                $tmp['user_name'] = $val['company_name'];
              }
              if($val['project_id'] != '' && $val['project_id'] != 0) {
                $tmp['project_detail'] = [
                    'project_id' => $val['project_id'],
                    'project_title' => htmlspecialchars($val['project_channel_name'], ENT_QUOTES),
                    'project_owner_id' => $val['project_owner_id']
                ];
              } else {
                $tmp['project_detail'] = [];
              }

              $val['project_id'] = $val['project_id'] != '' ? $val['project_id'] : 0;
              $chat_list[$key][] = $tmp;
          }
        }
      }
      
      return $chat_list;
    }
}
?>