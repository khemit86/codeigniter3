<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH.'third_party/users_chat/connect_cassandra_db.php';
class Chat extends MX_Controller {

    /**
     * Description: this used for check the user is exsts or not if exists then it redirect to this site
     * Paremete: username and password
     */
    private $conn;
    public function __construct() {
        parent::__construct();
        $this->load->helper('download');
        $this->load->library('ftp');
        $this->load->model('chat_model');
        $this->load->model('projects/Projects_model');
		 $this->load->model('user/User_model'); //used index
        $this->conn = new connect_cassandra_db();
        // pre(date_default_timezone_get());
    }

    /**
     * This method is used to load chat room view
    */
    public function chat_room() 
    { 
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        $data['current_page'] = 'chat_room';
        ########## set the profile title meta tag and meta description  start here #########
        $user = $this->session->userdata('user');
				$user_id = $user[0]->user_id;
				$account_type = $this->auto_model->getFeild('account_type', 'users', 'user_id', $user_id);
        $name =  $account_type == USER_PERSONAL_ACCOUNT_TYPE || ($account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') ? $this->auto_model->getFeild('first_name', 'users', 'user_id', $user_id) . ' ' . $this->auto_model->getFeild('last_name', 'users', 'user_id', $user_id) : $this->auto_model->getFeild('company_name', 'users', 'user_id', $user_id);
        $chat_room_page_title_meta_tag = $this->config->item('chat_room_page_title_meta_tag');
        $chat_room_page_title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $chat_room_page_title_meta_tag);
        $chat_room_page_description_meta_tag = $this->config->item('chat_room_page_description_meta_tag');
        $chat_room_page_description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $chat_room_page_description_meta_tag);
        $data['user_contacts_list'] = $this->chat_model->get_user_contacts_list($this->config->item('chat_room_contacts_listing_limit'), 0);
        $data['meta_tag'] = '<title>' . $chat_room_page_title_meta_tag . '</title><meta name="description" content="' . $chat_room_page_description_meta_tag . '"/>';
        ########## set the profile title tag start end #########
        $this->layout->view ('chat_room', '', $data, 'include');
    }
    /**
     * This method is used to load previous contacts from list
    */
    public function ajax_load_user_contacts_by_page_no() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = ['status' => 200];
        if(check_session_validity()) { 
            $page = $this->uri->segment(3);
            $limit = $this->config->item('chat_room_contacts_listing_limit');
            $start = ((($page + 1) * $limit) - $limit);
            $result = $this->chat_model->get_user_contacts_list($limit, $start);
            if(!empty($result)) {
                $res['data'] = $result;
                $res['page'] = ++$page;
            }
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to filter user contact listing based on search filter -> /assets/js/modules/chat_room_search_filter.js
     */
    public function ajax_update_user_contacts_listing_based_on_search_filter() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) {
            $filter_arr = $this->input->post();
            $result = $this->chat_model->get_user_contacts_list();
            if(!empty($filter_arr['real_time_search_txt'])) {				
                if (empty($filter_arr['searchtxt_arr'])) {
                    $filter_arr['searchtxt_arr'] = [];
                }
                array_push($filter_arr['searchtxt_arr'], $filter_arr['real_time_search_txt']);
            }
            $final_result = [];
            if(!empty($filter_arr['searchtxt_arr'])) {
                foreach($result as $key => $contacts) {
                    foreach($contacts as $contact) {
                        foreach($filter_arr['searchtxt_arr'] as $filter) {
                            if(strpos(strtolower($contact['user_name']), strtolower($filter)) !== false) {
                                $final_result[$key] = $contacts;
                                break;
                            } else if(count($contact['project_detail']) > 0 && strpos(strtolower($contact['project_detail']['project_title']), strtolower($filter)) !== false) {
                                $final_result[$key] = $contacts;
                                break;
                            }
                        }
                    }
                }
            }
            
            $res['status'] = 200;
            $res['data'] = $final_result;
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }

    /**
     * This event trigger from project detail page message section when user scroll on top of chat window to load previous chat data
    */
    public function ajax_load_previous_conversations() {
			if(!$this->input->is_ajax_request ()){
				show_custom_404_page(); //show custom 404 page
				return;
			}
        if(check_session_validity()) {
            $row = $this->input->post();
            $limit = $this->config->item('project_details_page_messages_tab_users_conversation_listing_limit');
            if(!empty($row['window_type']) && $row['window_type'] == 'small') {
                $limit = $this->config->item('small_window_chat_users_conversation_listing_limit');
            }
            if(!empty($row['page']) && $row['page'] == 'chat_room') {
                $limit = $this->config->item('chat_room_page_users_conversation_listing_limit');
            }
            
            if(!empty($row['socket_connection_flag']) && $row['socket_connection_flag']) {
                $result = $this->conn->get_users_conversations_on_project($row['sender_id'],$row['receiver_id'],$row['project_id'], '', $row['timestamp'], 'DESC', $row['socket_connection_flag']);
            } else {
                $result = $this->conn->get_users_conversations_on_project($row['sender_id'],$row['receiver_id'],$row['project_id'], $limit, $row['timestamp']);
            }
            $first_conversation_row = $this->conn->get_users_conversations_on_project($row['sender_id'],$row['receiver_id'],$row['project_id'], 1, '', 'ASC');
            $first_conversation_uuid = '';
            foreach($first_conversation_row as $val) {
                $id = (array)$val['id'];
                $first_conversation_uuid = $id['uuid'];
            }
            $sender = $this->db->get_where('users', ['user_id' => $row['sender_id']])->row_array();
            $sender_profile = $this->db->get_where('users_details', ['user_id' => $row['sender_id']])->row_array();
            $receiver = $this->db->get_where('users', ['user_id' => $row['receiver_id']])->row_array();
            $receiver_profile = $this->db->get_where('users_details', ['user_id' => $row['receiver_id']])->row_array();
            $profile_name = [];
            
            if(!empty($sender)) {
               $common_source_path = USERS_FTP_DIR . $sender['profile_name'];
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
               $avatar_pic = $source_path_avatar . $sender_profile['user_avatar'];
                                   
               if (!empty($sender_profile['user_avatar'])) {
                   $file_size = $this->ftp->get_filesize($avatar_pic);
                   if ($file_size != '-1') {
                    $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$sender['profile_name'].USER_AVATAR.$sender_profile['user_avatar'];
                    }
               }else { 
                    if($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')){
                            if($sender['gender'] == 'M'){
                                    $sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
                            }if($sender['gender'] == 'F'){
                                    $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
                            }
                    } else {
                            $sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
                    }
                }

                //end check avatar
                $this->ftp->close();

                if($sender['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sender['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender['is_authorized_physical_person'] == 'Y')) {
                    $profile_name[$sender['user_id']] = ['profile' => $sender['first_name'].' '.$sender['last_name']];
                } else {
                    $profile_name[$sender['user_id']] = ['profile' => $sender['company_name']];
                }	
                $profile_name[$sender['user_id']]['avatar'] = $sender_profile_picture;
            }
           
            if(!empty($receiver)) {
               $common_source_path_receiver = USERS_FTP_DIR . $receiver['profile_name'];
               $user_avatar_receiver = USER_AVATAR;
               $source_path_avatar_receiver = $common_source_path_receiver . $user_avatar_receiver;
               ######## connectivity of remote server start #######
               $this->load->library('ftp');
               $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
               $config['ftp_username'] = FTP_USERNAME;
               $config['ftp_password'] = FTP_PASSWORD;
               $config['ftp_port'] = FTP_PORT;
               $config['debug'] = TRUE;
               $this->ftp->connect($config);
                         
               $avatarlist_receiver = $this->ftp->list_files($source_path_avatar_receiver);
               $avatar_pic_receiver = $source_path_avatar_receiver . $receiver_profile['user_avatar'];
                                   
               if (!empty($receiver_profile['user_avatar'])) {
                   $file_size = $this->ftp->get_filesize($avatar_pic_receiver);
                   if ($file_size != '-1') {
                    $receiver_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$receiver['profile_name'].USER_AVATAR.$receiver_profile['user_avatar'];
                }
               }else { 
                    if($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')){
                            if($receiver['gender'] == 'M'){
                                    $receiver_profile_picture = URL . 'assets/images/avatar_default_male.png';
                            }if($receiver['gender'] == 'F'){
                                    $receiver_profile_picture = URL . 'assets/images/avatar_default_female.png';
                            }
                    } else {
                            $receiver_profile_picture = URL . 'assets/images/avatar_default_company.png';
                    }
                }
                 //end check avatar
                 $this->ftp->close();

                if($receiver['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($receiver['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $receiver['is_authorized_physical_person'] == 'Y')) {
                    $profile_name[$receiver['user_id']] = ['profile' => $receiver['first_name'].' '.$receiver['last_name']];
                } else {
                    $profile_name[$receiver['user_id']] = ['profile' => $receiver['company_name']];
                }
                $profile_name[$receiver['user_id']]['avatar'] = $receiver_profile_picture;
            } 
            $chat_msg = [];
                        
            // set user timezone to get date in his localtime
            date_default_timezone_set($this->session->userdata('user_timezone'));
            
            foreach($result as $val) {
                $id = (array)$val['id'];
                $timepstamp = (array)$val['message_sent_time'];
                $msg = nl2br($val['chat_message_text']);                
                
                if(!empty($val['chat_attachments'])) {
                    $val['chat_attachments'] = (array)$val['chat_attachments'];
                    $val['chat_attachments'] = $val['chat_attachments']['values'];
                } 
                if(!empty($msg) || !empty($val['chat_attachments'])) {
                    $tmp = [
                        'id' => $id['uuid'],
                        'sender_id' => $val['sender_id'],
                        'receiver_id' => $val['receiver_id'],
                        'chat_message_text' => $msg,
                        'attachments' => $val['chat_attachments'],
                        'is_general_chat' => $val['is_general_chat'],
                        'message_sent_time' => date(TIME_FORMAT, $timepstamp['seconds']),
                        'sender' => $profile_name[$val['sender_id']]['profile'],
                        'sender_profile_pic' => $profile_name[$val['sender_id']]['avatar'],
                        'timestamp' => "'".date('Y-m-d H:i:s', $timepstamp['seconds'])."'",
                        'seconds' => $timepstamp['seconds'],
                        'is_read' => $val['is_read'] ? 1 : 0,
                        'project_id' => $row['project_id'],
                        'message_grouping' => []
                    ] ;
                    array_push($chat_msg, $tmp);
                }
                
            }
            $chat_msg = array_reverse($chat_msg);
            $first = current($chat_msg);
            $datewise_group_chat_data = [];
            foreach($chat_msg as $val) {
                $datewise_group_chat_data[date(DATE_FORMAT, $val['seconds'])][] = $val;
            }
            $res['status'] = 200;
            $res['data'] = $datewise_group_chat_data;
            $res['first'] = $first;
            $res['first_conversation_uuid'] = $first_conversation_uuid;
        } else {
            $res['status'] = 400;
        }
        echo json_encode($res);
        return;
    }
    
    // this method is used to get and set logged in user timezone [header.php]
    public function get_user_timezone() {
        if($this->input->get('time')) {
            $timezone_offset_minutes = $this->input->get('time');
            $dst = $this->input->get('dst');
            // Convert minutes to seconds
            $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, !empty($dst) ? $dst : false);
            $this->session->set_userdata('user_timezone', $timezone_name);
            
        }
        echo json_encode(['status' => 200]);
        return;
    }
    /**
     * This method is triggered when sp / po sends or receive message
    */
    public function ajax_update_user_contacts_list() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) { 
            $user_log_id = $this->input->post('user_log_id');
            if(check_session_validity() && $user_log_id == $this->session->userdata('user_log_id')) {
                $res['status'] = 200;
                $res['data'] = $this->chat_model->get_user_contacts_list();
            } else {
                $res['status'] = 400;
            }
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
		/**
     * This method is used to load contact request view means any request made to add user to his contact list
    */
    public function contacts_management() { 
        if(!$this->session->userdata('user')) {
            redirect(base_url());
        }
        $data['current_page'] = 'contacts-management';
		
			$user = $this->session->userdata('user');
					$user_id = $user[0]->user_id;
			
			$user_detail = $this->db // get the user detail
			->select('u.user_id,u.account_type, u.first_name,u.is_authorized_physical_person, u.last_name, u.company_name')
			->from('users u')
			->where('u.user_id', $user_id)
			->get()->row_array();
			
			$name = (($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) ? $user_detail['first_name'] . ' ' . $user_detail['last_name'] : $user_detail['company_name'];
		

        $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests(0, $this->config->item('get_in_contact_pending_requests_listing_limit_per_page'));
        $data['pending_contacts'] = $pending_contacts['data'];
        $data['pending_contacts_count'] = $pending_contacts['total'];
        $pagination = generate_pagination_links($pending_contacts['total'], $this->config->item('contacts_management_page_url'), $this->config->item('get_in_contact_pending_requests_listing_limit_per_page'), $this->config->item('get_in_contact_pending_requests_number_of_pagination_links'));
        $data['pending_contacts_pagination_links'] = $pagination['links'];

        $rejected_contacts = $this->chat_model->get_user_all_rejected_get_in_contact_requests(0, $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page'));
        $data['rejected_contacts'] = $rejected_contacts['data'];
        $data['rejected_contacts_count'] = $rejected_contacts['total'];
        $pagination = generate_pagination_links($rejected_contacts['total'], $this->config->item('contacts_management_page_url'), $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page'), $this->config->item('get_in_contact_rejected_requests_number_of_pagination_links'));
        $data['rejected_contacts_pagination_links'] = $pagination['links'];

        $blocked_contacts = $this->chat_model->get_user_all_blocked_get_in_contact_requests(0, $this->config->item('get_in_contact_blocked_requests_listing_limit_per_page'));
        $data['blocked_contacts'] = $blocked_contacts['data'];
        $data['blocked_contacts_count'] = $blocked_contacts['total'];
        $pagination = generate_pagination_links($blocked_contacts['total'], $this->config->item('contacts_management_page_url'), $this->config->item('get_in_contact_blocked_requests_listing_limit_per_page'), $this->config->item('get_in_contact_blocked_requests_number_of_pagination_links'));
        $data['blocked_contacts_pagination_links'] = $pagination['links'];

        ########## set the profile title meta tag and meta description  start here #########
        $title_meta_tag = $this->config->item('contacts_management_page_title_meta_tag');
        $description_meta_tag = $this->config->item('contacts_management_page_description_meta_tag');
		
		
			$title_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $title_meta_tag);
			$description_meta_tag = str_replace('{user_first_name_last_name_or_company_name}', $name, $description_meta_tag);
		

        $data['meta_tag'] = '<title>' . $title_meta_tag . '</title><meta name="description" content="' . $description_meta_tag . '"/>';
        
        ########## set the profile title tag start end #########
        $this->layout->view ('contacts_management', '', $data, 'include');

    }
    /**
     * This method is used to check user is in contact or not -- /assets/js/modules/user_profile_page.js
     */
    public function ajax_check_users_already_in_contact() {
        if(!$this->input->is_ajax_request ()){
            show_custom_404_page(); //show custom 404 page
			return;
        }
        
        if(check_session_validity()) { 
            $row = $this->input->post();
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $row['user_id']) {
                    $res['popup_heading'] = $this->config->item('popup_alert_heading');
                    $res['warning'] = $this->config->item('different_users_session_conflict_message');
                    $res['status'] = 440;
                    echo json_encode($res);
                    return;
            }
            $this->db->select('*');
            $this->db->from('users_contacts_tracking');
            $this->db->where_in('contact_initiated_by',[$user[0]->user_id, $row['id']]);
            $this->db->where_in('contact_requested_to',[$row['id'], $user[0]->user_id]);
            $already_in_contact = $this->db->get()->result_array();

            if(!empty($already_in_contact)) {
                $res['status'] = 200;
                $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y')) {
                    $res['po_name'] = $user['first_name'].' '.$user['last_name'];
                } else {
                    $res['po_name'] = $user['company_name'];
                }

                $user_detail = $this->db->get_where('users_details', ['user_id' => $row['id']])->row_array();
                $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                
                    $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                    $common_source_path = USERS_FTP_DIR . $user['profile_name'];
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
                     $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                    }
                    }else {   
                    if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || (($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y'))){
                        if($user['gender'] == 'M'){
                            $sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
                        }
                        if($user['gender'] == 'F'){
                            $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
                        }
                    } else {
                        $sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
                    }
                }
                 //end check avatar
                 $this->ftp->close();
                $res['po_profile_pic'] = $sender_profile_picture;

            } else {
                $res['status'] = 201;
                $rejected_contact = $this->db->get_where('users_get_in_contact_rejected_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id])->row_array();
                if(!empty($rejected_contact)) {
                    $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                    if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y')) {
                        $res['po_name'] = $user['first_name'].' '.$user['last_name'];
                    } else {
                        $res['po_name'] = $user['company_name'];
                    }

                    $user_detail = $this->db->get_where('users_details', ['user_id' => $row['id']])->row_array();
                    $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                    
                        $common_source_path = USERS_FTP_DIR . $user['profile_name'];
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
                                $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                            }
                        }else {   
                        if($user['account_type'] == 1 || (($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y'))){
                            if($user['gender'] == 'M'){
                                $sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
                            }
                            if($user['gender'] == 'F'){
                                $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
                            }
                        } else {
                            $sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
                        }
                    }
                     //end check avatar
                    $this->ftp->close();
                    $res['po_profile_pic'] = $sender_profile_picture;
                } else {
                    $res['status'] = 202;
                }
            }

        } else {
            $row = $this->input->post();
            $this->session->set_userdata('hire_me_user_id', $row['user_id']);
            $this->session->set_userdata('page', $row['page_no']);
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }

    /**
     * This method is used to identify send contact request is valid or not
     */
    public function ajax_check_send_get_in_contact_request_validity() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = ['status' => 200];
        if(check_session_validity()) { 
            $row = $this->input->post();
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $this->db->select('*');
            $this->db->from('users_contacts_tracking');
            $this->db->where_in('contact_initiated_by',[$user[0]->user_id, $row['id']]);
            $this->db->where_in('contact_requested_to',[$row['id'], $user[0]->user_id]);
            $already_in_contact = $this->db->get()->result_array();
            if(!empty($already_in_contact)) {
                $res['status'] = 201;
                $logged_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $user[0]->user_id, 'blocked_contact_id' => $row['id']])->row_array();
                $requested_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $row['id'], 'blocked_contact_id' => $user[0]->user_id])->row_array();
                if(!empty($requested_user_blocker)) {
                    $res['warnning'] = $this->config->item('user_blocked_contact_get_in_contact_popup_message');
                } else {
                    if($row['project_id'] != 0) {
                        $already_in_project_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $user[0]->user_id, 'contact_requested_to' => $row['id'], 'project_id' => $row['project_id']])->row_array();
                    }
                    $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                    if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y')) {
                        $res['po_name'] = $user['first_name'].' '.$user['last_name'];
                    } else {
                        $res['po_name'] = $user['company_name'];
                    }

                    $user_detail = $this->db->get_where('users_details', ['user_id' => $row['id']])->row_array();
                    $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                    
                        $common_source_path = USERS_FTP_DIR . $user['profile_name'];
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
                                $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                            }
                        }else {
                        if($user['account_type'] == 1 || (($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y'))){
                            if($user['gender'] == 'M'){
                                $sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
                            }
                            if($user['gender'] == 'F'){
                                $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
                            }
                        } else {
                            $sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
                        }
                    }
                     //end check avatar
                     $this->ftp->close();
                    $res['po_profile_pic'] = $sender_profile_picture;
                    if(!empty($already_in_project_contact)) {
                        $res['status'] = 203; // this code is used to identify user is already in contact and also in contact with particular project
                    } else {
                        $res['status'] = 202; // this code is used to identify user is already in contact 
                    }
                }
                echo json_encode($res);
                return;
            }


            $logged_user_requester = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $user[0]->user_id, 'get_in_contact_request_receiver' => $row['id'] ])->row_array();
            $logged_user_receiver = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' =>  $user[0]->user_id  ])->row_array();

            if(!empty($logged_user_requester)) {
                // Remove obsolete entry from valid_get_in_contact_tracking table
                if(strtotime($logged_user_requester['get_in_contact_validity_expiration_date']) <= strtotime(date('Y-m-d H:i:s'))) {
                    $this->db->delete('users_valid_get_in_contact_requests_tracking', ['id' => $logged_user_requester['id']]);
                    if(!empty($logged_user_requester)) {
                        $udpate_data = [
                            'status' => 'expired'
                        ];
                        $update_cond = [
                            'get_in_contact_request_sender' => $logged_user_requester['get_in_contact_request_sender'], 
                            'get_in_contact_request_receiver' => $logged_user_requester['get_in_contact_request_receiver'],
                            'get_in_contact_request_send_date' => $logged_user_requester['get_in_contact_request_send_date']
                        ];
                        $this->db->update('users_get_in_contact_requests_tracking', $udpate_data, $update_cond);
                    }
                    $logged_user_requester = [];
                }
            }
            if(!empty($logged_user_receiver)) {
                // Remove obsolete entry from valid_get_in_contact_tracking table
                if(strtotime($logged_user_receiver['get_in_contact_validity_expiration_date']) <= strtotime(date('Y-m-d H:i:s'))) {
                    $this->db->delete('users_valid_get_in_contact_requests_tracking', ['id' => $logged_user_receiver['id']]);
                    if(!empty($logged_user_receiver)) {
                        $udpate_data = [
                            'status' => 'expired'
                        ];
                        $update_cond = [
                            'get_in_contact_request_sender' => $logged_user_receiver['get_in_contact_request_sender'], 
                            'get_in_contact_request_receiver' => $logged_user_receiver['get_in_contact_request_receiver'],
                            'get_in_contact_request_send_date' => $logged_user_receiver['get_in_contact_request_send_date']
                        ];
                        $this->db->update('users_get_in_contact_requests_tracking', $udpate_data, $update_cond);
                    }
                    $logged_user_receiver = [];
                }
            }

            $logged_user_rejects = $this->db->get_where('users_get_in_contact_rejected_requests_tracking',['get_in_contact_request_sender' => $user[0]->user_id, 'get_in_contact_request_receiver' => $row['id'] ])->row_array();
            $receiver_rejects = $this->db->get_where('users_get_in_contact_rejected_requests_tracking',['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' =>  $user[0]->user_id])->row_array();

            
            $msg_str = '';
            if(empty($logged_user_requester) && empty($logged_user_receiver) && empty($logged_user_rejects) && empty($receiver_rejects)) {
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE ) {
                    if($user_detail['gender'] == 'M') {
                        $msg_str = $this->config->item('get_in_contact_popup_send_contact_request_info_male');
                        $msg_str = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg_str);
                    } else {
                        $msg_str = $this->config->item('get_in_contact_popup_send_contact_request_info_female');
                        $msg_str = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg_str);
                    }
                } else {
                    if($user_detail['is_authorized_physical_person'] == 'Y') {
                        if($user_detail['gender'] == 'M') {
                        $msg_str = $this->config->item('get_in_contact_popup_send_contact_request_info_company_app_male');
                            $msg_str = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg_str);
                        } else {
                            $msg_str = $this->config->item('get_in_contact_popup_send_contact_request_info_company_app_female');
                            $msg_str = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg_str);
                        }
                    } else {
                        $msg_str = $this->config->item('get_in_contact_popup_send_contact_request_info_company');
                        $msg_str = str_replace('{user_company_name}', $user_detail['company_name'], $msg_str);
                    }
                    
                }
                
                $res['data'] = $msg_str;
            } else if(!empty($logged_user_requester) || !empty($logged_user_rejects)) {
                //$res['warnning'] = str_replace('{user_first_name_last_name_or_company_name}', limitString($row['name'], 25), $this->config->item('get_in_contact_popup_sender_already_send_request_txt'));
				$res['warnning'] = str_replace('{user_first_name_last_name_or_company_name}', $row['name'], $this->config->item('get_in_contact_popup_sender_already_send_request_txt'));
            } else if(!empty($receiver_rejects)) {
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                
                $msg = '';
                if($user_detail['account_type'] == 1) {
                  if($user_detail['gender'] == 'M') {
                    $msg = $this->config->item('get_in_contact_popup_receiver_male_already_in_rejected_contacts_list_txt');
                  } else {
                    $msg = $this->config->item('get_in_contact_popup_receiver_female_already_in_rejected_contacts_list_txt');
                  }
                  $msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
                } else {
									if($user_detail['is_authorized_physical_person'] == 'Y') {
										if($user_detail['gender'] == 'M') {
											$msg = $this->config->item('get_in_contact_popup_receiver_company_app_male_already_in_rejected_contacts_list_txt');
										} else {
											$msg = $this->config->item('get_in_contact_popup_receiver_company_app_female_already_in_rejected_contacts_list_txt');
										}
										$msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
									} else {
										$msg = $this->config->item('get_in_contact_popup_receiver_company_already_in_rejected_contacts_list_txt');
                  	$msg = str_replace(['{user_company_name}'],[$user_detail['company_name']],$msg);
									}
                  
                }
                $res['warnning'] = $msg;
                $res['receiver_in_rejected_list'] = 1;
            } else {
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                
                $msg = '';
                if($user_detail['account_type'] == 1) {
                  if($user_detail['gender'] == 'M') {
                    $msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_male_txt');
                  } else {
                    $msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_female_txt');
                  }
                  $msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
                } else {
                  if($user_detail['is_authorized_physical_person'] == 'Y') {
										if($user_detail['gender'] == 'M') {
											$msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_male_txt');
										} else {
											$msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_female_txt');
										}
										$msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
									} else {
										$msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_txt');
                  	$msg = str_replace(['{user_company_name}'],[$user_detail['company_name']],$msg);
									} 
								}
								$msg = str_replace(['{contacts_management_page_url}'],[base_url($this->config->item('contacts_management_page_url'))],$msg);
                $res['warnning'] = $msg;
                $res['sender_in_pending_list'] = 1;

                $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y')) {
                    $res['po_name'] = $user['first_name'].' '.$user['last_name'];
                } else {
                    $res['po_name'] = $user['company_name'];
                }

                $user_detail = $this->db->get_where('users_details', ['user_id' => $row['id']])->row_array();
                $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
        
                    $common_source_path = USERS_FTP_DIR . $user['profile_name'];
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
                            $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                        }
                    }else {
                    if($user['account_type'] == 1 || (($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y'))){
                        if($user['gender'] == 'M'){
                            $sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
                        }
                        if($user['gender'] == 'F'){
                            $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
                        }
                    } else {
                        $sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
                    }
                }
                 //end check avatar
                 $this->ftp->close();
                $res['po_profile_pic'] = $sender_profile_picture;
            }
        } else {
            $row = $this->input->post();
            $this->session->set_userdata('hire_me_user_id', $row['user_id']);
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to store request into get in contact tracking and valid get in contact tracking table 
     * Used in [/assets/js/modules/project_details.js, /assets/js/modules/find_professionals.js, /assets/js/modules/user_profile.js]
    */
    public function ajax_save_send_get_in_contact_request() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = ['status' => 200];
        if(check_session_validity()) { 
            $row = $this->input->post();
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $logged_user_requester = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $user[0]->user_id, 'get_in_contact_request_receiver' => $row['id'] ])->row_array();
            $logged_user_receiver = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' =>  $user[0]->user_id  ])->row_array();

            $logged_user_rejects = $this->db->get_where('users_get_in_contact_rejected_requests_tracking',['get_in_contact_request_sender' => $user[0]->user_id, 'get_in_contact_request_receiver' => $row['id'] ])->row_array();
            $receiver_rejects = $this->db->get_where('users_get_in_contact_rejected_requests_tracking',['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' =>  $user[0]->user_id])->row_array();
            if(empty($logged_user_requester) && empty($logged_user_receiver) && empty($logged_user_rejects) && empty($receiver_rejects)) { 
                $time_arr = explode(':', $this->config->item('get_in_contact_request_availability'));
                $expiration_date = date('Y-m-d H:i:s', strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds'));
                $data = [
                    'get_in_contact_request_sender' => $user[0]->user_id,
                    'get_in_contact_request_receiver' => $row['id'],
                    'get_in_contact_request_send_date' => date('Y-m-d H:i:s'),
                    'get_in_contact_request_sent_from' => str_replace('_page', '', $row['current_page']).'_page',
                    'get_in_contact_validity_expiration_date' => $expiration_date
                ];
                $this->db->insert('users_get_in_contact_requests_tracking', $data);
                $this->db->insert('users_valid_get_in_contact_requests_tracking', $data);
                $sender = $this->config->item('get_in_contact_sender_realtime_notification_confirmation_message');
                $sender = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($row['profile']), $row['name']], $sender);
                $receiver = $this->config->item('get_in_contact_receiver_realtime_notification_received_message');
                if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) {
                    $receiver =  str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->first_name.' '.$user[0]->last_name], $receiver);
                } else {
                    $receiver =  str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->company_name], $receiver);
                }
                $res['data'] = [
                    'sender' => $sender,
                    'receiver' => $receiver
                ];
                // Save activity log message for sender
                $sender_activity_log_msg = $this->config->item('get_in_contact_sender_sent_request_display_activity_log_message');
                $sender_activity_log_msg = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($row['profile']), $row['name']], $sender_activity_log_msg);
                user_display_log($sender_activity_log_msg);
                // Save activity log message for receiver
                $receiver_activity_log_msg = $this->config->item('get_in_contact_receiver_received_request_display_activity_log_message');
                if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) { 
                    $receiver_activity_log_msg = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->first_name.' '.$user[0]->last_name], $receiver_activity_log_msg);
                } else {
                    $receiver_activity_log_msg = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->company_name], $receiver_activity_log_msg);
                }
                user_display_log($receiver_activity_log_msg, $row['id']);
            } else if(!empty($logged_user_requester) || !empty($logged_user_requester)) {
                //$res['warnning'] = str_replace('{user_first_name_last_name_or_company_name}', limitString($row['name'], 25), $this->config->item('get_in_contact_popup_sender_already_send_request_txt'));
				$res['warnning'] = str_replace('{user_first_name_last_name_or_company_name}', $row['name'], $this->config->item('get_in_contact_popup_sender_already_send_request_txt'));
            } else if(!empty($receiver_rejects)) {
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                
                $msg = '';
                if($user_detail['account_type'] == 1) {
                  if($user_detail['gender'] == 'M') {
                    $msg = $this->config->item('get_in_contact_popup_receiver_male_already_in_rejected_contacts_list_txt');
                  } else {
                    $msg = $this->config->item('get_in_contact_popup_receiver_female_already_in_rejected_contacts_list_txt');
                  }
                  $msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
                } else {
									if($user_detail['is_authorized_physical_person'] == 'Y') {
										if($user_detail['gender'] == 'M') {
											$msg = $this->config->item('get_in_contact_popup_receiver_company_app_male_already_in_rejected_contacts_list_txt');
										} else {
											$msg = $this->config->item('get_in_contact_popup_receiver_company_app_female_already_in_rejected_contacts_list_txt');
										}
										$msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
									} else {
										$msg = $this->config->item('get_in_contact_popup_receiver_company_already_in_rejected_contacts_list_txt');
                  	$msg = str_replace(['{user_company_name}'],[$user_detail['company_name']],$msg);
									}
                  
                }
                $res['warnning'] = $msg;
                $res['receiver_in_rejected_list'] = 1;
            } else {
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                
                $msg = '';
                if($user_detail['account_type'] == 1) {
                  if($user_detail['gender'] == 'M') {
                    $msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_male_txt');
                  } else {
                    $msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_female_txt');
                  }
                  $msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
                } else {
									if($user_detail['is_authorized_physical_person'] == 'Y') {
										if($user_detail['gender'] == 'M') {
											$msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_male_txt');
										} else {
											$msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_app_female_txt');
										}
										$msg = str_replace(['{user_first_name_last_name}'],[$user_detail['first_name'].' '.$user_detail['last_name']],$msg);
									} else {
										$msg = $this->config->item('get_in_contact_popup_receiver_already_has_pending_request_from_sender_company_txt');
                  	$msg = str_replace(['{user_company_name}'],[$user_detail['company_name']],$msg);
									}
                  
								}
								$msg = str_replace(['{contacts_management_page_url}'],[base_url($this->config->item('contacts_management_page_url'))],$msg);
                $res['warnning'] = $msg;
                $res['sender_in_pending_list'] = 1;
            }
            
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to update header menu get in contact requests list whenever any request receive from other user
    */
    public function ajax_update_user_get_in_contact_requests_list() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = ['status' => 200];
        if(check_session_validity()) { 
            $result = $this->chat_model->get_user_all_pending_get_in_contact_requests(0, $this->config->item('get_in_contact_requests_notification_limit'));
            $res['data'] = $result['data'];
            $res['get_in_contact_unseen_pending_request_count'] = $this->chat_model->get_user_unseen_pending_get_in_contact_requests_count();
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to update seen by receiver status of receive request for logged in user when user click on header menu of get in contact request 
    */
    public function ajax_update_user_get_in_contact_seen_by_receiver_status() {
			if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $user = $this->session->userdata('user');
        $this->db->update('users_get_in_contact_requests_tracking', ['is_seen_by_receiver' => 'yes'], ['get_in_contact_request_receiver' => $user[0]->user_id]);
        $this->db->update('users_valid_get_in_contact_requests_tracking', ['is_seen_by_receiver' => 'yes'], ['get_in_contact_request_receiver' => $user[0]->user_id]);
        $res = [
            'status' => 200
        ];
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to store record in to serv_users_get_in_contact_rejected_requests_tracking  and serv_users_get_in_contact_requests_tracking when user click on get in contact header menu reject button and contact management page reject button
    */
    public function ajax_user_rejects_valid_get_in_contact_request() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = [];
        if(check_session_validity()) { 
            $row = $this->input->post();
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $pending_request = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->row_array();
            if(!empty($pending_request)) {
                $res['status'] = 200;
                $rejected_request_data = [
                    'get_in_contact_request_sender' => $pending_request['get_in_contact_request_sender'],
                    'get_in_contact_request_receiver' => $pending_request['get_in_contact_request_receiver'],
                    'get_in_contact_request_send_date' => $pending_request['get_in_contact_request_send_date'],
                    'get_in_contact_request_rejection_date' => date('Y-m-d H:i:s'),
                    'get_in_contact_request_sent_from' => $pending_request['get_in_contact_request_sent_from']
                ];
                $this->db->insert('users_get_in_contact_rejected_requests_tracking', $rejected_request_data);
                
                $udpate_data = [
                    'status' => 'rejected',
                    'rejected_on' => date('Y-m-d H:i:s')
                ];
                $update_cond = [
                    'get_in_contact_request_sender' => $pending_request['get_in_contact_request_sender'], 
                    'get_in_contact_request_receiver' => $pending_request['get_in_contact_request_receiver'],
                    'get_in_contact_request_send_date' => $pending_request['get_in_contact_request_send_date']
                ];
                $this->db->update('users_get_in_contact_requests_tracking', $udpate_data, $update_cond);
                // Remove entry from serv_users_valid_get_in_contact_requests_tracking table
                $this->db->delete('users_valid_get_in_contact_requests_tracking', ['id' => $pending_request['id']]);
                
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                
				$receiver_rejected_request_activity_display_log = $this->config->item('get_in_contact_receiver_rejected_request_display_activity_log_message');
				if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                  $receiver_rejected_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['first_name'].' '.$user_detail['last_name']], $receiver_rejected_request_activity_display_log);
                } else {
                  $receiver_rejected_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['company_name']], $receiver_rejected_request_activity_display_log);
                }                
               

                user_display_log($receiver_rejected_request_activity_display_log);
                $receiver_rejected_request_notification = $this->config->item('get_in_contact_receiver_rejected_request_realtime_notification_confirmation_message');
                if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                    $receiver_rejected_request_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['first_name'].' '.$user_detail['last_name']], $receiver_rejected_request_notification);
                } else {
                    $receiver_rejected_request_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['company_name']], $receiver_rejected_request_notification);
                }
                $res['receiver_notification'] = $receiver_rejected_request_notification;

                $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
                $page_links = $this->config->item('get_in_contact_pending_request_number_of_pagination_links');
                $page_url = $this->config->item('contacts_management_page_url');
                $pending_request_count = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver'=> $pending_request['get_in_contact_request_receiver'] , 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
                $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
                $res['data'] = $pending_contacts['data'];
                $filtered_rec_cnt = $pending_contacts['total'];
                $res['pagination'] = $pagination['links'];
                $page = $pagination['current_page_no'];
                $multiplication = $limit * $page;
                $subtraction = ($multiplication - ($limit - count($res['data'])));
                $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                $res['total_records'] = $filtered_rec_cnt;
                $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                $res['page_no'] = $page;
            } else {
                $accepted_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $row['id'], 'contact_requested_to' => $user[0]->user_id])->row_array();
                if(!empty($accepted_contact)) {
                    $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                    $res['popup_heading'] = $this->config->item('popup_alert_heading');
                    $res['status'] = 440;
                    if(!empty($row['source'])) {
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                            if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_accepted_error_message_male_receiver');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_accepted_error_message_female_receiver');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
                        } else {
													if($user_detail['is_authorized_physical_person'] == 'Y') {
														if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_app_male_receiver');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_app_female_receiver');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
													} else {
														$msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_accepted_error_message_company_receiver');
                            $msg = str_replace('{user_company_name}', $user_detail['company_name'], $msg);
													}
                        }
                        $res['source'] = $row['source'];
                    } 
                
                    if(!empty($row['current_page'])) {
                        $res['status'] = 201;
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                            if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_reject_already_accepted_male_user_request_error_message');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_reject_already_accepted_female_user_request_error_message');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
                        } else {
													if($user_detail['is_authorized_physical_person'] == 'Y') {
														if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_reject_already_accepted_company_app_male_user_request_error_message');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_reject_already_accepted_company_app_female_user_request_error_message');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
													} else {
														$msg = $this->config->item('get_in_contact_request_user_try_to_reject_already_accepted_company_user_request_error_message');
                            $msg = str_replace('{user_company_name}', $user_detail['company_name'], $msg);
													}
                            
                        }
                    }

                    $res['warning'] = $msg;

                    if(!empty($row['current_page']) && $row['current_page'] == 'contacts-management') {
                        $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
                        $page_links = $this->config->item('get_in_contact_pending_request_number_of_pagination_links');
                        $page_url = $this->config->item('contacts_management_page_url');
                        $pending_request_count = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
                        $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                        $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
                        $res['data'] = $pending_contacts['data'];
                        $filtered_rec_cnt = $pending_contacts['total'];
                        $res['pagination'] = $pagination['links'];
                        $page = $pagination['current_page_no'];
                        $multiplication = $limit * $page;
                        $subtraction = ($multiplication - ($limit - count($res['data'])));
                        $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                        $res['total_records'] = $filtered_rec_cnt;
                        $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                        $res['page_no'] = $page;
                    }

                    echo json_encode($res);
                    return;
                } else {

                    $rejected_contact = $this->db->get_where('users_get_in_contact_rejected_requests_tracking', ['get_in_contact_request_sender' => $row['id']])->row_array();
                    $res['status'] = 200;
                    if(!empty($rejected_contact)) {
                        $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                        $receiver_rejected_request_notification = $this->config->item('get_in_contact_receiver_rejected_request_realtime_notification_confirmation_message');
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                          $receiver_rejected_request_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($row['profile_url']), $user_detail['first_name'].' '.$user_detail['last_name']], $receiver_rejected_request_notification);
                        } else {
                          $receiver_rejected_request_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($row['profile_url']), $user_detail['company_name']], $receiver_rejected_request_notification);
                        }
                        $res['receiver_notification'] = $receiver_rejected_request_notification;
                    } else {
                        $res['status'] = 201;
                        $pending_expired_request = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date <' => date('Y-m-d H:i:s')])->row_array();
                        if(!empty($pending_expired_request)) {
                            $this->db->update('users_get_in_contact_requests_tracking', ['status' => 'expired'], ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_request_send_date' => $pending_expired_request['get_in_contact_request_send_date'] ]);
                        }
                        $this->db->delete('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id']]);
                        $res['popup_heading'] = $this->config->item('popup_alert_heading');
                        if(!empty($row['source'])) {
                            $res['status'] = 440;
                            $res['source'] = $row['source'];
                        }
                        $res['warning'] = $this->config->item('get_in_contact_request_user_try_to_reject_already_expired_request_error_message');
                        $res['expired_request'] = 1;
                    }

                    if(!empty($row['current_page']) && $row['current_page'] == 'contacts-management') {
                        $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
                        $page_links = $this->config->item('get_in_contact_pending_request_number_of_pagination_links');
                        $page_url = $this->config->item('contacts_management_page_url');
                        $pending_request_count = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
                        $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                        $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
                        $res['data'] = $pending_contacts['data'];
                        $filtered_rec_cnt = $pending_contacts['total'];
                        $res['pagination'] = $pagination['links'];
                        $page = $pagination['current_page_no'];
                        $multiplication = $limit * $page;
                        $subtraction = ($multiplication - ($limit - count($res['data'])));
                        $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                        $res['total_records'] = $filtered_rec_cnt;
                        $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                        $res['page_no'] = $page;
                    }

                    
                }
            }
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to store record in to serv_users_get_in_contact_requests_tracking and serv_users_contact_tracking when user click on get in contact header menu accept button and contact management page accept button
    */
    public function ajax_user_accepts_valid_get_in_contact_request() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = [];
        if(check_session_validity()) {
            $row = $this->input->post();
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $pending_request = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id , 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->row_array();
            if(!empty($pending_request)) {
                $res['status'] = 200;
                $contact_tracking_data = [
                    ['contact_initiated_by' => $pending_request['get_in_contact_request_sender'], 'contact_requested_to' => $pending_request['get_in_contact_request_receiver'], 'contact_initiated_from' => $pending_request['get_in_contact_request_sent_from'], 'project_id' => 0],
                    ['contact_initiated_by' => $pending_request['get_in_contact_request_receiver'], 'contact_requested_to' => $pending_request['get_in_contact_request_sender'], 'contact_initiated_from' => 'pair_entry_of_'.$pending_request['get_in_contact_request_sent_from'], 'project_id' => 0]
                ];
                $this->db->insert_batch('users_contacts_tracking', $contact_tracking_data);
                // update status and reject on date into users_get_in_contact_requests_tracking table
               
                $udpate_data = [
                    'status' => 'accepted',
                    'accepted_on' => date('Y-m-d H:i:s')
                ];
                $update_cond = [
                    'get_in_contact_request_sender' => $pending_request['get_in_contact_request_sender'], 
                    'get_in_contact_request_receiver' => $pending_request['get_in_contact_request_receiver'],
                    'get_in_contact_request_send_date' => $pending_request['get_in_contact_request_send_date']
                ];
                $this->db->update('users_get_in_contact_requests_tracking', $udpate_data, $update_cond);
                // Remove entry from serv_users_valid_get_in_contact_requests_tracking table
                $this->db->delete('users_valid_get_in_contact_requests_tracking', ['id' => $pending_request['id']]);
                
                $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                // Receiver accepted get in contact request activity log
                $receiver_accepted_request_activity_display_log = $this->config->item('get_in_contact_receiver_accepted_request_display_activity_log_message');
                if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                    $receiver_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['first_name'].' '.$user_detail['last_name']], $receiver_accepted_request_activity_display_log);
                } else {
                    $receiver_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['company_name']], $receiver_accepted_request_activity_display_log);
                }
                user_display_log($receiver_accepted_request_activity_display_log);         
                
                // Sender request accepted activity log 
                $sender_accepted_request_activity_display_log = $this->config->item('get_in_contact_sender_request_accepted_confirmation_display_activity_log_message');
                if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) { 
                    $sender_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->first_name.' '.$user[0]->last_name], $sender_accepted_request_activity_display_log);
                } else {
                    $sender_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->company_name], $sender_accepted_request_activity_display_log);
                }
                user_display_log($sender_accepted_request_activity_display_log, $row['id']);

                $sender_accepted_request_realtime_notification = $this->config->item('get_in_contact_sender_request_accepted_confirmation_realtime_notification_message');
                if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) { 
                    $sender_accepted_request_realtime_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->first_name.' '.$user[0]->last_name], $sender_accepted_request_realtime_notification);
                } else {
                    $sender_accepted_request_realtime_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->company_name], $sender_accepted_request_realtime_notification);
                }
                $res['sender_notification'] = $sender_accepted_request_realtime_notification;

               
                $receiver_accepted_request_realtime_notification = $this->config->item('get_in_contact_request_popup_user_accepts_contact_request_from_pending_contact_realtime_notification_confirmation_message');
                if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                    $receiver_accepted_request_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'],[base_url($row['profile_url']), $user_detail['first_name'].' '.$user_detail['last_name']], $receiver_accepted_request_realtime_notification);
                } else {
                    $receiver_accepted_request_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'],[base_url($row['profile_url']), $user_detail['company_name']], $receiver_accepted_request_realtime_notification);
                }

                $res['receiver_notification'] = $receiver_accepted_request_realtime_notification;

                $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
                $page_links = $this->config->item('get_in_contact_pending_requests_number_of_pagination_links');
                $page_url = $this->config->item('contacts_management_page_url');
                $pending_request_count = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver'=> $pending_request['get_in_contact_request_receiver'], 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
                $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
                $res['data'] = $pending_contacts['data'];
                $filtered_rec_cnt = $pending_contacts['total'];
                $res['pagination'] = $pagination['links'];
                $page = $pagination['current_page_no'];
                $multiplication = $limit * $page;
                $subtraction = ($multiplication - ($limit - count($res['data'])));
                $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                $res['total_records'] = $filtered_rec_cnt;
                $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                $res['page_no'] = $page;
            } else {
                $rejected_contact = $this->db->get_where('users_get_in_contact_rejected_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id])->row_array();
                if(!empty($rejected_contact)) {
                    $user_detail = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                    $res['popup_heading'] = $this->config->item('popup_alert_heading');
                    $res['status'] = 440;
                    if(!empty($row['source'])) {
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                            if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_rejected_error_message_male_receiver');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_rejected_error_message_female_receiver');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
                        } else {
													if($user_detail['is_authorized_physical_person'] == 'Y') {
														if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_app_male_receiver');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_app_female_receiver');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
													} else {
														$msg = $this->config->item('get_in_contact_request_top_navigation_window_user_already_rejected_error_message_company_receiver');
                            $msg = str_replace('{user_company_name}', $user_detail['company_name'], $msg);
													}
                            
                        }
                        $res['source'] = $row['source'];
                    }
                    

                    if(!empty($row['current_page'])) {
                        $res['status'] = 201;
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                            if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_accept_already_rejected_male_user_request_error_message');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_accept_already_rejected_female_user_request_error_message');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
                        } else {
													if($user_detail['is_authorized_physical_person'] == 'Y') {
														if($user_detail['gender'] == 'M') {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_accept_already_rejected_company_app_male_user_request_error_message');
                            } else {
                                $msg = $this->config->item('get_in_contact_request_user_try_to_accept_already_rejected_company_app_female_user_request_error_message');
                            }
                            $msg = str_replace('{user_first_name_last_name}', $user_detail['first_name'].' '.$user_detail['last_name'], $msg);
													} else {
														$msg = $this->config->item('get_in_contact_request_user_try_to_accept_already_rejected_company_user_request_error_message');
                            $msg = str_replace('{user_company_name}', $user_detail['company_name'], $msg);
													}
                        }
                    }

                    $res['warning'] = $msg;

                    if(!empty($row['current_page']) && $row['current_page'] == 'contacts-management') {
                        $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
                        $page_links = $this->config->item('get_in_contact_pending_requests_number_of_pagination_links');
                        $page_url = $this->config->item('contacts_management_page_url');
                        $pending_request_count = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
                        $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                        $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
                        $res['data'] = $pending_contacts['data'];
                        $filtered_rec_cnt = $pending_contacts['total'];
                        $res['pagination'] = $pagination['links'];
                        $page = $pagination['current_page_no'];
                        $multiplication = $limit * $page;
                        $subtraction = ($multiplication - ($limit - count($res['data'])));
                        $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                        $res['total_records'] = $filtered_rec_cnt;
                        $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                        $res['page_no'] = $page;
                    }

                    echo json_encode($res);
                    return;
                } else {
                    $res['status'] = 200;

                    $accepted_contact = $this->db->get_where('users_contacts_tracking', ['contact_initiated_by' => $row['id'], 'contact_requested_to' => $user[0]->user_id])->row_array();
                    if(!empty($accepted_contact)) {
                        $user_detail = $this->db->get_where('users', ['user_id' => $row['user_id']])->row_array();
                        $receiver_accepted_request_realtime_notification = $this->config->item('get_in_contact_request_popup_user_accepts_contact_request_from_pending_contact_realtime_notification_confirmation_message');
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                            $receiver_accepted_request_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'],[base_url($row['profile_url']), $user_detail['first_name'].' '.$user_detail['last_name']], $receiver_accepted_request_realtime_notification);
                        } else {
                            $receiver_accepted_request_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'],[base_url($row['profile_url']), $user_detail['company_name']], $receiver_accepted_request_realtime_notification);
                        }
                        $res['receiver_notification'] = $receiver_accepted_request_realtime_notification;
                    } else {
                        $res['status'] = 201;
                        $pending_expired_request = $this->db->get_where('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date <' => date('Y-m-d H:i:s')])->row_array();
                        if(!empty($pending_expired_request)) {
                            $this->db->update('users_get_in_contact_requests_tracking', ['status' => 'expired'], ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_request_send_date' => $pending_expired_request['get_in_contact_request_send_date'] ]);
                        }
                        $this->db->delete('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id']]);
                        $this->db->delete('users_valid_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $row['id']]);
                        $res['popup_heading'] = $this->config->item('popup_alert_heading');
                        if(!empty($row['source'])) {
                            $res['status'] = 440;
                            $res['source'] = $row['source'];
                        }
                        $res['warning'] = $this->config->item('get_in_contact_request_user_try_to_accept_already_expired_request_error_message');
                        $res['expired_request'] = 1;
                    }

                    if(!empty($row['current_page']) && $row['current_page'] == 'contacts-management') {
                        $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
                        $page_links = $this->config->item('get_in_contact_pending_requests_number_of_pagination_links');
                        $page_url = $this->config->item('contacts_management_page_url');
                        $pending_request_count = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
                        $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                        $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
                        $res['data'] = $pending_contacts['data'];
                        $filtered_rec_cnt = $pending_contacts['total'];
                        $res['pagination'] = $pagination['links'];
                        $page = $pagination['current_page_no'];
                        $multiplication = $limit * $page;
                        $subtraction = ($multiplication - ($limit - count($res['data'])));
                        $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                        $res['total_records'] = $filtered_rec_cnt;
                        $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                        $res['page_no'] = $page;
                    }
                    
                }
            }
            
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to paginate pending requests when user click on page link from contact management page pending request list 
    */
    public function ajax_get_in_contact_pending_requests_based_on_page_no() {
      if(!$this->input->is_ajax_request ()){
        show_custom_404_page(); //show custom 404 page
			return;
		
      }
      if(check_session_validity()) {
        $page = $this->uri->segment(3);
        $user = $this->session->userdata('user');
        $limit = $this->config->item('get_in_contact_pending_requests_listing_limit_per_page');
        $page_links = $this->config->item('get_in_contact_pending_requests_number_of_pagination_links');
        $page_url = $this->config->item('contacts_management_page_url');
        if($page != null) {
          if($page == 1) {
            $start = 0;
          } else {
            $start = ($page - 1) * $limit;
          }
        } else {
          $start = 0;
        }
        $total_pending_contacts = $this->db->from('users_valid_get_in_contact_requests_tracking')->where(['get_in_contact_request_receiver' => $user[0]->user_id, 'get_in_contact_validity_expiration_date >' => date('Y-m-d H:i:s')])->count_all_results();
        $pagination = generate_pagination_links($total_pending_contacts, $page_url,$limit, $page_links);
        $pending_contacts = $this->chat_model->get_user_all_pending_get_in_contact_requests($pagination['offset'], $limit);
        $res['status'] = 200;
        $res['data'] = $pending_contacts['data'];
        $filtered_rec_cnt = $pending_contacts['total'];
        $res['pagination'] = $pagination['links'];
        $page = $pagination['current_page_no'];
        $multiplication = $limit * $page;
        $subtraction = ($multiplication - ($limit - count($res['data'])));
        $res['total_records'] = $filtered_rec_cnt;
        $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
        $res['page_no'] = ($limit * ($page - 1)) + 1;
        echo json_encode($res);
      } else {
        echo json_encode(['status' => 404]);
      }
      return;
    }
    /**
     * This method is used to paginate rejected requests when user click on page link from contact management page rejected request list
    */
    public function ajax_get_in_contact_rejected_requests_based_on_page_no() {
      if(!$this->input->is_ajax_request ()){
			  show_custom_404_page(); //show custom 404 page
			  return;
      }
      if(check_session_validity()) {
        $page = $this->uri->segment(3);
        $user = $this->session->userdata('user');
        $limit = $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page');
        $page_links = $this->config->item('get_in_contact_rejected_requests_number_of_pagination_links');
        $page_url = $this->config->item('contacts_management_page_url');
        if($page != null) {
          if($page == 1) {
            $start = 0;
          } else {
            $start = ($page - 1) * $limit;
          }
        } else {
          $start = 0;
        }
        $total_rejected_contacts = $this->db->from('users_get_in_contact_rejected_requests_tracking')->where(['get_in_contact_request_receiver' => $user[0]->user_id])->count_all_results();
        $pagination = generate_pagination_links($total_rejected_contacts, $page_url,$limit, $page_links);
        $pending_contacts = $this->chat_model->get_user_all_rejected_get_in_contact_requests($pagination['offset'], $limit);
        $res['status'] = 200;
        $res['data'] = $pending_contacts['data'];
        $filtered_rec_cnt = $pending_contacts['total'];
        $res['pagination'] = $pagination['links'];
        $page = $pagination['current_page_no'];
        $multiplication = $limit * $page;
        $subtraction = ($multiplication - ($limit - count($res['data'])));
        $res['total_records'] = $filtered_rec_cnt;
        $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
        $res['page_no'] = ($limit * ($page - 1)) + 1;
        echo json_encode($res);
      } else {
        echo json_encode(['status' => 404]);
      }
      return;
    }
    /**
     * This method is used to store record in to serv_users_get_in_contact_requests_tracking and serv_users_contact_tracking also remove entry from serv_users_get_in_contact_rejected_requests_tracking when user click on contact management page accept button from rejected contact request
    */
    public function ajax_user_accepts_rejected_get_in_contact_request() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = [];
        if(check_session_validity()) {
            $row = $this->input->post();
            $user = $this->session->userdata('user');
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $pending_request = $this->db->get_where('users_get_in_contact_rejected_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id])->row_array();
            if(!empty($pending_request)) {
                $res['status'] = 200;
                $contact_tracking_data = [
                    ['contact_initiated_by' => $pending_request['get_in_contact_request_sender'], 'contact_requested_to' => $pending_request['get_in_contact_request_receiver'], 'contact_initiated_from' => $pending_request['get_in_contact_request_sent_from'], 'project_id' => 0],
                    ['contact_initiated_by' => $pending_request['get_in_contact_request_receiver'], 'contact_requested_to' => $pending_request['get_in_contact_request_sender'], 'contact_initiated_from' => 'pair_entry_of_'.$pending_request['get_in_contact_request_sent_from'], 'project_id' => 0]
                ];
                $this->db->insert_batch('users_contacts_tracking', $contact_tracking_data);
                // update status and accepted on date into users_get_in_contact_requests_tracking table
                $contact_request_tracking_data = $this->db->get_where('users_get_in_contact_requests_tracking', ['get_in_contact_request_sender' => $pending_request['get_in_contact_request_sender'], 'get_in_contact_request_receiver' => $pending_request['get_in_contact_request_receiver'],'get_in_contact_request_send_date' => $pending_request['get_in_contact_request_send_date']])->row_array();
                $udpate_data = [
                    'status' => 'accepted',
                    'accepted_on' => date('Y-m-d H:i:s')
                ];
                $update_cond = [
                    'get_in_contact_request_sender' => $pending_request['get_in_contact_request_sender'], 
                    'get_in_contact_request_receiver' => $pending_request['get_in_contact_request_receiver'],
                    'get_in_contact_request_send_date' => $pending_request['get_in_contact_request_send_date']
                ];
                $this->db->update('users_get_in_contact_requests_tracking', $udpate_data, $update_cond);
                
                $this->db->delete('users_get_in_contact_rejected_requests_tracking', ['id' => $pending_request['id']]);

                $sender_data = $this->db->get_where('users', ['user_id' => $row['id']])->row_array(); 
                // Receiver accepted get in contact request activity log
                $receiver_accepted_request_activity_display_log = $this->config->item('get_in_contact_receiver_accepted_request_display_activity_log_message');
                if($sender_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sender_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender_data['is_authorized_physical_person'] == 'Y')) {
                    $receiver_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($sender_data['profile_name']), $sender_data['first_name'].' '.$sender_data['last_name']], $receiver_accepted_request_activity_display_log);
                } else {
                    $receiver_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($sender_data['profile_name']), $sender_data['company_name']], $receiver_accepted_request_activity_display_log);
                }
                user_display_log($receiver_accepted_request_activity_display_log);         
                
                // Sender request accepted activity log 
                $sender_accepted_request_activity_display_log = $this->config->item('get_in_contact_sender_request_accepted_confirmation_display_activity_log_message');
                if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) { 
                    $sender_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->first_name.' '.$user[0]->last_name], $sender_accepted_request_activity_display_log);
                } else {
                    $sender_accepted_request_activity_display_log = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->company_name], $sender_accepted_request_activity_display_log);
                }
                user_display_log($sender_accepted_request_activity_display_log, $row['id']);

                $sender_accepted_request_realtime_notification = $this->config->item('get_in_contact_sender_request_accepted_confirmation_realtime_notification_message');
                if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE || ($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y')) { 
                    $sender_accepted_request_realtime_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->first_name.' '.$user[0]->last_name], $sender_accepted_request_realtime_notification);
                } else {
                    $sender_accepted_request_realtime_notification = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($user[0]->profile_name), $user[0]->company_name], $sender_accepted_request_realtime_notification);
                }
                $res['sender_notification'] = $sender_accepted_request_realtime_notification;

                $receiver_accepted_realtime_notification = $this->config->item('get_in_contact_request_popup_user_accepts_contact_request_from_already_rejected_contact_realtime_notification_confirmation_message');
                
                if(!empty($sender_data)) {
                  if($sender_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sender_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender_data['is_authorized_physical_person'] == 'Y')) { 
                    $receiver_accepted_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'], [base_url($sender_data['profile_name']), $sender_data['first_name'].' '.$sender_data['last_name']], $receiver_accepted_realtime_notification);
                  } else {
                    $receiver_accepted_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'], [base_url($sender_data['profile_name']), $sender_data['company_name']], $receiver_accepted_realtime_notification);
                  }
                  $res['receiver_notification'] = $receiver_accepted_realtime_notification;
                }

                $limit = $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page');
                $page_links = $this->config->item('get_in_contact_rejected_requests_number_of_pagination_links');
                $page_url = $this->config->item('contacts_management_page_url');
                $pending_request_count = $this->db->from('users_get_in_contact_rejected_requests_tracking')->where('get_in_contact_request_receiver', $pending_request['get_in_contact_request_receiver'])->count_all_results();
                $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                $pending_contacts = $this->chat_model->get_user_all_rejected_get_in_contact_requests($pagination['offset'], $limit);
                $res['data'] = $pending_contacts['data'];
                $filtered_rec_cnt = $pending_contacts['total'];
                $res['pagination'] = $pagination['links'];
                $page = $pagination['current_page_no'];
                $multiplication = $limit * $page;
                $subtraction = ($multiplication - ($limit - count($res['data'])));
                $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                $res['total_records'] = $filtered_rec_cnt;
                $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                $res['page_no'] = $page;
            } else {
                $receiver_accepted_realtime_notification = $this->config->item('get_in_contact_request_popup_user_accepts_contact_request_from_already_rejected_contact_realtime_notification_confirmation_message');
                $sender_data = $this->db->get_where('users', ['user_id' => $row['id']])->row_array(); 
                if(!empty($sender_data)) {
                  if($sender_data['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($sender_data['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $sender_data['is_authorized_physical_person'] == 'Y')) { 
                    $receiver_accepted_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'], [base_url($sender_data['profile_name']), $sender_data['first_name'].' '.$sender_data['last_name']], $receiver_accepted_realtime_notification);
                  } else {
                    $receiver_accepted_realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'], [base_url($sender_data['profile_name']), $sender_data['company_name']], $receiver_accepted_realtime_notification);
                  }
                  $res['receiver_notification'] = $receiver_accepted_realtime_notification;
                }
                $res['status'] = 201;

                $limit = $this->config->item('get_in_contact_rejected_requests_listing_limit_per_page');
                $page_links = $this->config->item('get_in_contact_rejected_requests_number_of_pagination_links');
                $page_url = $this->config->item('contacts_management_page_url');
                $pending_request_count = $this->db->from('users_get_in_contact_rejected_requests_tracking')->where('get_in_contact_request_receiver', $user[0]->user_id)->count_all_results();
                $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
                $pending_contacts = $this->chat_model->get_user_all_rejected_get_in_contact_requests($pagination['offset'], $limit);
                $res['data'] = $pending_contacts['data'];
                $filtered_rec_cnt = $pending_contacts['total'];
                $res['pagination'] = $pagination['links'];
                $page = $pagination['current_page_no'];
                $multiplication = $limit * $page;
                $subtraction = ($multiplication - ($limit - count($res['data'])));
                $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
                $res['total_records'] = $filtered_rec_cnt;
                $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
                $res['page_no'] = $page;
            }
            
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to check block request is valid or not, This is trigger when user clicks on block button from chat room page -> click event managed in /assets/js/modules/users_chat.js
     */
    public function ajax_check_contact_status_at_user_block_request() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $row = $this->input->post();
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $logged_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $user[0]->user_id, 'blocked_contact_id' => $row['id']])->row_array();
            $requested_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $row['id'], 'blocked_contact_id' => $user[0]->user_id])->row_array();
            if(empty($logged_user_blocker) && empty($requested_user_blocker)) {
                $res['status'] = 200;
                $user_block_contact_modal_body_title = $this->config->item('user_block_contact_modal_body_title');
                $blocked_user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                if($blocked_user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($blocked_user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $blocked_user['is_authorized_physical_person'] == 'Y')) { 
                    $user_block_contact_modal_body_title = str_replace(['{user_first_name_last_name_or_company_name}'], [$blocked_user['first_name'].' '.$blocked_user['last_name']], $user_block_contact_modal_body_title);
                } else {
                    $user_block_contact_modal_body_title = str_replace(['{user_first_name_last_name_or_company_name}'], [$blocked_user['company_name']], $user_block_contact_modal_body_title);
                }
                $res['modal'] = [
                    'user_block_contact_modal_title' => $this->config->item('user_block_contact_modal_title'),
                    'user_block_contact_modal_body_title' => $user_block_contact_modal_body_title,
                    'user_block_contact_modal_body_confirmation_txt' => $this->config->item('user_confirmation_check_box_txt'),
                    'user_block_contact_modal_block_btn_txt' => $this->config->item('user_block_contact_modal_block_btn_txt')
                ];
            } else if(!empty($logged_user_blocker)) {
                $res['status'] = 201;
                $res['modal'] = [
                    'user_block_contact_modal_title' => $this->config->item('user_block_contact_modal_title')
                ];
                $res['warnning'] = $this->config->item('chat_room_page_warning_displayed_to_blocker_txt');
            } else {
                $res['status'] = 201;
                $res['modal'] = [
                    'user_block_contact_modal_title' => $this->config->item('user_block_contact_modal_title')
                ];
                $res['warnning'] = $this->config->item('chat_room_page_warning_displayed_to_already_blocked_contact_txt');
            }
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to save block user id into users_blocked_contacts_tracking when user click on block button from chat room page modal popup -> click event managed in /assets/js/modules/users_chat.js
    */
    public function ajax_block_contact() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $row = $this->input->post();
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $logged_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $user[0]->user_id, 'blocked_contact_id' => $row['id']])->row_array();
            $requested_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $row['id'], 'blocked_contact_id' => $user[0]->user_id])->row_array();
            if(empty($logged_user_blocker) && empty($requested_user_blocker)) { 
                $block_userdata = [
                    'contact_blocked_by' => $user[0]->user_id,
                    'blocked_contact_id' => $row['id']
                ];
                $this->db->insert('users_blocked_contacts_tracking', $block_userdata);
                
                $this->db->where_in('contact_initiated_by', [$user[0]->user_id, $row['id']]);
                $this->db->where_in('contact_requested_to', [$user[0]->user_id, $row['id']]);
                $this->db->update('users_contacts_tracking', ['is_blocked' => 'yes']);


                $user_block_display_activity_message = $this->config->item('user_block_contact_display_activity_message');
                $block_user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
                
                if($block_user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($block_user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $block_user['is_authorized_physical_person'] == 'Y')) {
                    $user_block_display_activity_message = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($block_user['profile_name']), $block_user['first_name'].' '.$block_user['last_name']], $user_block_display_activity_message);
                } else {
                    $user_block_display_activity_message = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($block_user['profile_name']), $block_user['company_name']], $user_block_display_activity_message);
                }
                user_display_log($user_block_display_activity_message);
                $res['status'] = 200;
            } else if(!empty($logged_user_blocker)) {
                $res['status'] = 201;
                $res['modal'] = [
                    'user_block_contact_modal_title' => $this->config->item('chat_room_page_warning_displayed_to_blocker_txt')
                ];
                $res['warnning'] = $this->config->item('chat_room_page_warning_displayed_to_already_blocked_contact_txt');
            } else {
                $res['status'] = 201;
                $res['modal'] = [
                    'user_block_contact_modal_title' => $this->config->item('user_block_contact_modal_title')
                ];
                $res['warnning'] = $this->config->item('chat_room_page_warning_displayed_to_already_blocked_contact_txt');
            }
        } else {
            $res['status'] = 400;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to un-block user from blocked contact list -> click event managed in /assets/js/modules/contacts_management.js 
    */
    public function ajax_user_unblock_contact() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) {
            $res['status'] = 200;
            $user = $this->session->userdata('user');
            $row = $this->input->post();
            if($user[0]->user_id != $row['user_id']) {
                $res['popup_heading'] = $this->config->item('popup_alert_heading');
                $res['warning'] = $this->config->item('different_users_session_conflict_message');
                $res['status'] = 440;
                echo json_encode($res);
                return;
            }
            $this->db->delete('users_blocked_contacts_tracking', ['contact_blocked_by' => $user[0]->user_id, 'blocked_contact_id' => $row['id']]);


            $this->db->where_in('contact_initiated_by', [$user[0]->user_id, $row['id']]);
            $this->db->where_in('contact_requested_to', [$user[0]->user_id, $row['id']]);
            $this->db->update('users_contacts_tracking', ['is_blocked' => 'no']);

            $user_block_display_activity_message = $this->config->item('user_unblock_contact_display_activity_message');
            $block_user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
            
            if($block_user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($block_user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $block_user['is_authorized_physical_person'] == 'Y')) {
                $user_block_display_activity_message = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($block_user['profile_name']), $block_user['first_name'].' '.$block_user['last_name']], $user_block_display_activity_message);
            } else {
                $user_block_display_activity_message = str_replace(['{user_profile_url_link}', '{user_first_name_last_name_or_company_name}'], [base_url($block_user['profile_name']), $block_user['company_name']], $user_block_display_activity_message);
            }
            user_display_log($user_block_display_activity_message);


            $limit = $this->config->item('get_in_contact_blocked_requests_listing_limit_per_page');
            $page_links = $this->config->item('get_in_contact_blocked_requests_number_of_pagination_links');
            $page_url = $this->config->item('contacts_management_page_url');
            $pending_request_count = $this->db->from('users_blocked_contacts_tracking')->where('contact_blocked_by', $user[0]->user_id)->count_all_results();
            $pagination = generate_pagination_links($pending_request_count, $page_url,$limit, $page_links);
            $pending_contacts = $this->chat_model->get_user_all_blocked_get_in_contact_requests($pagination['offset'], $limit);
            $res['data'] = $pending_contacts['data'];
            $filtered_rec_cnt = $pending_contacts['total'];
            $res['pagination'] = $pagination['links'];
            $page = $pagination['current_page_no'];
            $multiplication = $limit * $page;
            $subtraction = ($multiplication - ($limit - count($res['data'])));
            $page = $page == 1 ? $page : ($limit * ($page - 1)) + 1;
            $res['total_records'] = $filtered_rec_cnt;
            $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
            $res['page_no'] = $page;
        } else {
            $res['status'] = 404;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to check user from contact is blocked or not, -> Event trigger from /assets/js/modules/users_chat.js when user click on "contact me" button of project detail page and "chat with contact" button of [find professionals / user profile]
     */
    public function ajax_check_user_contact_block_status() {
      if(!$this->input->is_ajax_request ()){
        show_custom_404_page(); //show custom 404 page
		  return;
      }
      if(check_session_validity()) {
        $user = $this->session->userdata('user');
        $row = $this->input->post();
        if($user[0]->user_id != $row['user_id']) {
          $res['popup_heading'] = $this->config->item('popup_alert_heading');
          $res['warning'] = $this->config->item('different_users_session_conflict_message');
          $res['status'] = 440;
          echo json_encode($res);
          return;
        }

        if(!empty($row['project_id']) && $row['project_id'] != 0) {
          $project_status_table_array = $this->Projects_model->get_project_status_type($row['project_id']);
          if(empty($project_status_table_array['project_status']) && empty($project_status_table_array['table_name'])){
            echo json_encode(['status' => 440,'location'=>'','popup_heading'=>$this->config->item('popup_alert_heading'),'warning'=>$this->config->item('project_details_page_sp_view_contact_po_deleted_project')]);
            die;
          }
        }
        $project_status_table_array = $this->Projects_model->get_project_status_type($project_id);

        $logged_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $user[0]->user_id, 'blocked_contact_id' => $row['id']])->row_array();
        $requested_user_blocker = $this->db->get_where('users_blocked_contacts_tracking', ['contact_blocked_by' => $row['id'], 'blocked_contact_id' => $user[0]->user_id])->row_array();
        $requested_user_rejected = $this->db->get_where('users_get_in_contact_rejected_requests_tracking', ['get_in_contact_request_sender' => $row['id'], 'get_in_contact_request_receiver' => $user[0]->user_id])->row_array();
        if(!empty($logged_user_blocker)) {
          $res['status'] = 201;
          if($row['current_page'] == 'project_detail') {
              $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
              if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                if($user['gender'] == 'M') {
                    $msg = $this->config->item('project_details_page_user_male_already_in_blocked_contacts_list_txt');
                } else {
                    $msg = $this->config->item('project_details_page_user_female_already_in_blocked_contacts_list_txt');
                }
                $msg = str_replace('{user_first_name_last_name}', $user['first_name'].' '.$user['last_name'], $msg);
              } else { 
								if($user['is_authorized_physical_person'] == 'Y') {
									if($user['gender'] == 'M') {
                    $msg = $this->config->item('project_details_page_user_company_app_male_already_in_blocked_contacts_list_txt');
									} else {
											$msg = $this->config->item('project_details_page_user_company_app_female_already_in_blocked_contacts_list_txt');
									}
									$msg = str_replace('{user_first_name_last_name}', $user['first_name'].' '.$user['last_name'], $msg);
								} else {
									$msg = $this->config->item('project_details_page_user_company_already_in_blocked_contacts_list_txt');
                	$msg = str_replace('{user_company_name}', $user['company_name'], $msg);
								}
                
              }
              $res['warning'] = $msg;
          } else {
              $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
              if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                if($user['gender'] == 'M') {
                    $msg = $this->config->item('find_professionals_page_user_male_already_in_blocked_contacts_list_txt');
                } else {
                    $msg = $this->config->item('find_professionals_page_user_female_already_in_blocked_contacts_list_txt');
                }
                $msg = str_replace('{user_first_name_last_name}', $user['first_name'].' '.$user['last_name'], $msg);
              } else { 
								if($user['is_authorized_physical_person'] == 'Y') { 
									if($user['gender'] == 'M') {
                    $msg = $this->config->item('find_professionals_page_user_company_app_male_already_in_blocked_contacts_list_txt');
									} else {
											$msg = $this->config->item('find_professionals_page_user_company_app_female_already_in_blocked_contacts_list_txt');
									}
									$msg = str_replace('{user_first_name_last_name}', $user['first_name'].' '.$user['last_name'], $msg);
								} else {	
									$msg = $this->config->item('find_professionals_page_user_company_already_in_blocked_contacts_list_txt');
                	$msg = str_replace('{user_company_name}', $user['company_name'], $msg);
								}
                
              }
              $res['warning'] = $msg;
          }
          $res['blocker'] = true;
        } else if(!empty($requested_user_blocker)) {
          $res['status'] = 201;
          $res['warning'] = $this->config->item('user_blocked_contact_get_in_contact_popup_message');
          $res['blocker'] = false;
        } else if(!empty($requested_user_rejected)) {
            $res['status'] = 202;
            $user = $this->db->get_where('users', ['user_id' => $row['id']])->row_array();
            if($user['account_type'] == USER_PERSONAL_ACCOUNT_TYPE) {
                $res['po_name'] = $user['first_name'].' '.$user['last_name'];
                if($user['gender'] == 'M') {
                    $msg = $this->config->item('get_in_contact_popup_receiver_male_already_in_rejected_contacts_list_txt');
                } else {
                    $msg = $this->config->item('get_in_contact_popup_receiver_female_already_in_rejected_contacts_list_txt');
                }
                $msg = str_replace('{user_first_name_last_name}', $user['first_name'].' '.$user['last_name'], $msg);
            } else { 
							if($user['is_authorized_physical_person'] == 'Y') {
								$res['po_name'] = $user['first_name'].' '.$user['last_name'];
                if($user['gender'] == 'M') {
                    $msg = $this->config->item('get_in_contact_popup_receiver_company_app_male_already_in_rejected_contacts_list_txt');
                } else {
                    $msg = $this->config->item('get_in_contact_popup_receiver_company_app_female_already_in_rejected_contacts_list_txt');
                }
                $msg = str_replace('{user_first_name_last_name}', $user['first_name'].' '.$user['last_name'], $msg);
							} else {
								$res['po_name'] = $user['company_name'];
                $msg = $this->config->item('get_in_contact_popup_receiver_company_already_in_rejected_contacts_list_txt');
                $msg = str_replace('{user_company_name}', $user['company_name'], $msg);
							}
            }
            $user_detail = $this->db->get_where('users_details', ['user_id' => $row['id']])->row_array();
            $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
            
                $common_source_path = USERS_FTP_DIR . $user['profile_name'];
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
                         $sender_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                    }
                }else {
                if($user['account_type'] == 1 || (($user['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user['is_authorized_physical_person'] == 'Y'))){
                    if($user['gender'] == 'M'){
                        $sender_profile_picture = URL . 'assets/images/avatar_default_male.png';
                    }
                    if($user['gender'] == 'F'){
                        $sender_profile_picture = URL . 'assets/images/avatar_default_female.png';
                    }
                } else {
                    $sender_profile_picture = URL . 'assets/images/avatar_default_company.png';
                }
            }
             //end check avatar
             $this->ftp->close();

            $res['po_profile_pic'] = $sender_profile_picture;
            $res['warning'] = $msg;
            
        } else {
          $res['status'] = 200;
        }
      } else {
        $this->session->set_userdata('hire_me_user_id', $this->input->post('id'));
        $res['status'] = 404;
      }
      echo json_encode($res);
      return;
    }
    /**
     * This method is used to unblock contact from block contact list by blocker when he click on yes button from get in contact popup from [find professionals / project detail / user profile page] -> Event managed -> /assets/js/modules/users_chat.js
     */
    public function ajax_allow_user_to_unblock_contact_get_in_contact_popup() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $row = $this->input->post();
            $this->db->delete('users_blocked_contacts_tracking', ['contact_blocked_by' => $user[0]->user_id, 'blocked_contact_id' => $row['id']]);
            $this->db->where_in('contact_initiated_by', [$user[0]->user_id, $row['id']]);
            $this->db->where_in('contact_requested_to', [$user[0]->user_id, $row['id']]);
            $this->db->update('users_contacts_tracking', ['is_blocked' => 'no']);
            $realtime_notification = $this->config->item('get_in_contact_request_popup_user_unblock_blocked_contact_realtime_notification_confirmation_message');
            $user_detail = $this->db->get_where('users', ['user_id'=> $row['id'] ])->row_array();
            if(!empty($user_detail)) {
                if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')) {
                    $realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['first_name'].' '.$user_detail['last_name']], $realtime_notification);
                } else {
                    $realtime_notification = str_replace(['{user_profile_url}', '{user_first_name_last_name_or_company_name}'], [base_url($user_detail['profile_name']), $user_detail['company_name']], $realtime_notification);
                }
                $res['confirmation_msg'] = $realtime_notification;
            }
            $res['status'] = 200;
        }
        echo json_encode($res);
        return;
    }
    /**
     * This method is used to paginate blocked requests when user click on page link from contact management page blocked request list
    */
    public function ajax_get_in_contact_blocked_requests_based_on_page_no() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        if(check_session_validity()) {
            $user = $this->session->userdata('user');
            $page = $this->uri->segment(3);
            $limit = $this->config->item('get_in_contact_blocked_requests_listing_limit_per_page');
            $page_links = $this->config->item('get_in_contact_blocked_requests_number_of_pagination_links');
            $page_url = $this->config->item('contacts_management_page_url');
            if($page != null) {
                if($page == 1) {
                    $start = 0;
                } else {
                    $start = ($page - 1) * $limit;
                }
            } else {
                $start = 0;
            }
            $total_blocked_contacts = $this->db->from('users_blocked_contacts_tracking')->where(['contact_blocked_by' => $user[0]->user_id])->count_all_results();
            $pagination = generate_pagination_links($total_blocked_contacts, $page_url,$limit, $page_links);
            $blocked_contacts = $this->chat_model->get_user_all_blocked_get_in_contact_requests($pagination['offset'], $limit);
            $res['status'] = 200;
            $res['data'] = $blocked_contacts['data'];
            $filtered_rec_cnt = $blocked_contacts['total'];
            
            $res['pagination'] = $pagination['links'];
            $page = $pagination['current_page_no'];
            $multiplication = $limit * $page;
            $subtraction = ($multiplication - ($limit - count($res['data'])));
            $res['total_records'] = $filtered_rec_cnt;
            $res['record_per_page'] = count($res['data']) < $limit ? $subtraction : $multiplication;
            $res['page_no'] = ($limit * ($page - 1)) + 1;
            echo json_encode($res);
        } else {
            echo json_encode(['status' => 404]);
        }
        return;
    }
    /**
     * This method is used to upload user attachment selected by user either from chat room /  project details / small chat window -> trigger from /assets/js/modules/users_chat.js
     */
    public function ajax_upload_chat_attachments() {
			if(!$this->input->is_ajax_request ()){
				show_custom_404_page(); //show custom 404 page
				return;
			}
			$uploaded_file_arr = [];
			$upload_fail_file_arr = [];
			$res = [];
			if(check_session_validity()) { 
					$user = $this->session->userdata ('user');
          $row = $this->input->post();
          
          if($user[0]->user_id != $row['sender_id']){
            echo json_encode(['status' => 440,'error'=>$this->config->item('different_users_session_conflict_message')]);
            die;
          }

					$row['message_sent_time'] = date(DATE_TIME_FORMAT);
					$row['seconds'] = time();
					$res['status'] = 200;
					######## connectivity of remote server start#########
					$this->load->library('ftp');
					$config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
					$config['ftp_username'] = FTP_USERNAME;
					$config['ftp_password'] = FTP_PASSWORD;
					$config['ftp_port'] 	= FTP_PORT;
					$config['debug']    = TRUE;
					$this->ftp->connect($config); 
					######## connectivity of remote server end #######
					$users_ftp_dir 	= USERS_FTP_DIR; 
					$chat_attachments_dir = CHAT_ATTACHMENTS_DIR;
					$profile_folder     = $user[0]->profile_name;
					
          if(empty($this->ftp->check_ftp_directory_exist($users_ftp_dir))){
            echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
          }
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.DIRECTORY_SEPARATOR)) {
            echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
          }
					
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$chat_attachments_dir)) {
            echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
          }
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$chat_attachments_dir.$row['receiver_id'].DIRECTORY_SEPARATOR)) {
            echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
          }
					if(!$this->User_model->check_and_create_user_subfolders_on_disk_as_per_need($users_ftp_dir.$profile_folder.$chat_attachments_dir.$row['receiver_id']. DIRECTORY_SEPARATOR .$row['project_id'].DIRECTORY_SEPARATOR)) {
            echo json_encode(['status' => 440,'error'=>$this->config->item('users_folder_not_exist_error_message')]);die;
          }
					
					foreach($_FILES['files']['name'] as $key => $val) {
							$temp 		= 	explode(".", $val);
							$extension 	= 	end($temp);
							if(!empty($this->config->item('attachment_prefix_text'))) {
									$temp_attachment_name = $this->config->item('attachment_prefix_text').round(microtime(true) * 1000).'.'.$extension;// name of attachment 
							} else {
									$temp_attachment_name = round(microtime(true) * 1000).'.'.$extension;
							}
							if(move_uploaded_file($_FILES['files']['tmp_name'][$key], TEMP_DIR.$temp_attachment_name)){ 
									
									$source_path = FCPATH .TEMP_DIR. $temp_attachment_name;		
									$destination_path = $users_ftp_dir.$profile_folder.$chat_attachments_dir.$row['receiver_id'].DIRECTORY_SEPARATOR.$row['project_id'].DIRECTORY_SEPARATOR .$temp_attachment_name;
									if(!$this->ftp->upload($source_path,$destination_path , 'auto', 0777)) { // upload attachment on remote server
											array_push($upload_fail_file_arr, $row['file_name'][$key]);
									} else {
											array_push($uploaded_file_arr, $temp_attachment_name);
									}
									unlink(FCPATH .TEMP_DIR. $temp_attachment_name);
							} else {
									
									array_push($upload_fail_file_arr, $row['file_name'][$key]);
							}
					}
					$this->ftp->close();
					if(!empty($upload_fail_file_arr)) {
							$res['status'] = 201;
							$res['attachments'] = $upload_fail_file_arr;
							echo json_encode($res);
							return;
					}
					if(!empty($uploaded_file_arr)) {
							$row['attachments'] = $uploaded_file_arr;
					}
					$res['data'] = $row;
			} else {
				$res['status'] = 400;
			}
			echo json_encode($res);
			return;
			}
    /**
     * This method is used to download chat attachment when user click on attachment form chat -> /assets/js/modules/users_chat.js
     */
    public function ajax_check_chat_attachments_status() {
        if(!$this->input->is_ajax_request ()){
			show_custom_404_page(); //show custom 404 page
			return;
        }
        $res = [];
        if(check_session_validity()) { 
            $row = $this->input->post();
            $result = $this->conn->get_chat_attachments_from_name("'".$row['name']."'");
            $is_valid_to_db = true;
            if(empty($result)) {
                $is_valid_to_db = false;
            } 
            $user = $this->db->get_where('users', ['user_id' => $row['sender_id']])->row_array();
            ######## connectivity of remote server start#########
            
            $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
            $config['ftp_username'] = FTP_USERNAME;
            $config['ftp_password'] = FTP_PASSWORD;
            $config['ftp_port'] 	= FTP_PORT;
            $config['debug']    = TRUE;
            
            $this->ftp->connect($config); 
            ######## connectivity of remote server end #######
            $users_ftp_dir 	= USERS_FTP_DIR; 
            $chat_attachments_dir = CHAT_ATTACHMENTS_DIR;
            $protocol = CDN_SERVER_LOAD_FILES_PROTOCOL;
            $domain = CDN_SERVER_DOMAIN_NAME;

            $profile_folder = $user['profile_name'];
            $destination_path = $users_ftp_dir.$profile_folder.$chat_attachments_dir.$row['receiver_id'].DIRECTORY_SEPARATOR.$row['project_id'].DIRECTORY_SEPARATOR .$row['name'];
            $file_size = $this->ftp->get_filesize($destination_path);
            
            if($file_size != '-1') {
                if(!$is_valid_to_db) { // File doesn't exist on db but exist on disk
                    $this->ftp->delete_file($destination_path);
                    $res['status'] = 201;
                    $res['warning'] = $this->config->item('chat_attachments_download_failed_error_message');
                } else {
                    $res['status'] = 200;
                    $location = $protocol.$domain.$destination_path;
                    $this->session->set_userdata('file_url', $location);
                    $this->session->set_userdata('file_name', $row['name']);
                }
            } else {
                $files = '';
                if(!empty($result['chat_attachments'])) {
                    $chat_attachments = (array)$result['chat_attachments'];
                    $chat_attachments = $chat_attachments['values'];
                    if (($key = array_search($row['name'], $chat_attachments)) !== false) {
                        unset($chat_attachments[$key]);
                    }
                    if(!empty($chat_attachments)) {
                        if(count($chat_attachments) == 1) {
                            $files = "['" . implode ( "', '", $chat_attachments ) . "']";
                        } else {
                            $files = "['" . implode ( "', '", $chat_attachments ) . "']";
                        }
                    } else {
                        $files = 'null';
                    }
                }
                $this->conn->delete_chat_attachments_by_name($files, $result);
                $res['status'] = 201;
                $res['warning'] = $this->config->item('chat_attachments_download_failed_error_message');
            }
            $this->ftp->close();
            
        } 
        echo json_encode($res);
        die;
    }
    // This method is used to allow download file from ftp to user local reference with ajax_check_chat_attachments_status method
    public function download_chat_attachments() {
        /* Do session stuff here; security; logging; etc. */
        session_start();
        $file_url = $this->session->userdata('file_url');
        $file_name = $this->session->userdata('file_name');
        
        /* Make sure data is actually flushed out to the browser */
        // ob_end_flush();
        ob_end_clean();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Expires: 0');
        header("Content-disposition: attachment; filename=\"".$file_name."\""); 
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        // ob_end_clean();
        session_write_close();
        readfile($file_url);
        exit;
    }
}
?>