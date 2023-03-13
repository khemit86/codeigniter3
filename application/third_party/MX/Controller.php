<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller 
{
	public $autoload = array();
	public function __construct() 
	{
            $class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
            log_message('debug', $class." MX_Controller Initialized");
            Modules::$registry[strtolower($class)] = $this;	

            /* copy a loader instance and initialize */
            $this->load = clone load_class('Loader');
            $this->load->initialize($this);	

            /* autoload module items */
            $this->load->_autoloader($this->autoload);
		
			/* if(!$this->session->userdata ('user') && !$this->input->is_ajax_request() && $this->router->fetch_method() != 'signin' && $this->router->fetch_method() != 'register' && $this->router->fetch_method() != 'recover_password' && $this->router->fetch_method() != 'logout' && ($this->router->fetch_class() != 'user' && $this->router->fetch_method() != 'index')){
				
				echo $last_redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				die;
				$this->session->set_userdata ('last_redirect_url', $last_redirect_url);
			} */
			
            if ($this->session->userdata ('user') != null)
            {
                if(!check_session_validity() && !$this->input->is_ajax_request()) {
                    $user = $this->session->userdata ('user');
                    $this->db->where('user_id', $user[0]->user_id);
                    $row = $this->db->get('users')->row_array();
                    $online = 1;
                    $current_online = $row['currently_online'] - 1;
                    if($current_online == 0) {
                            $online = 0;
                    } 
                    if($current_online < 0) {
                            $current_online = 0;
                            $online = 0;
                    }	
                    $user_id = $user[0]->user_id;
                    $current_date = date('Y-m-d H:i:s');
                    $this->db->where ('user_id',$user_id);
                    $this->db->update ('users', array ('latest_logout_date' =>$current_date,'online'=>$online,'currently_online'=>$current_online));
                    //session_destroy();
					$this->session->unset_userdata('user');
					$this->session->unset_userdata('is_logged');
					$this->session->unset_userdata('is_authorized');
					$this->session->sess_destroy();
					$controller_name = $this->router->fetch_class(); // class = controller
					$action_name =  $this->router->fetch_method();
					
					$logout_version_page_array = array(array('controller'=>'user','action'=>'user_profile'),array('controller'=>'Find_project','action'=>'index'),array('controller'=>'Find_professionals','action'=>'index'),array('controller'=>'Projects','action'=>'project_detail'),array('controller'=>'user','action'=>'portfolio_standalone_page'));
					//$this->input->get('id');die;
					$check_redirection = false;
					foreach($logout_version_page_array as $page_key=>$page_value){
						if($page_value['controller'] == $controller_name && $page_value['action'] == $action_name){
							$check_redirection = true;
							break;
						}
					}
					if(!$check_redirection){
						redirect(base_url());
					}
                } 
                if($this->session->userdata ('is_authorized') != null && $this->session->userdata ('is_authorized')) {
                    // load chat model to get user list and there unread message counter contact wise
                    $this->load->model('chat/Chat_model');
                    $get_in_contact_requests = $this->Chat_model->get_user_all_pending_get_in_contact_requests(0, $this->config->item('get_in_contact_requests_notification_limit'));
                    $user = $this->session->userdata ('user');
                    // pre($get_in_contact_requests);
                    $user_detail = $this->db // get the user detail
                    ->select('u.user_id, u.account_type,u.is_authorized_physical_person ,u.first_name, u.last_name, u.company_name, u.gender, u.profile_name, ud.user_avatar, ud.user_total_avg_rating_as_sp')
                    ->select('u.sync_linkedin, u.user_linkedin_associated_email, u.sync_facebook, u.user_facebook_id, u.user_facebook_associated_email,ud.user_bank_deposit_variable_symbol,ud.user_bank_withdrawal_variable_symbol')
					->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'fulltime_prj_users_received_ratings_feedbacks_as_employee where feedback_recived_by_employee_id = u.user_id AND employee_already_placed_feedback= "Y") as fulltime_project_user_total_reviews')
                    ->select('(SELECT count(*)  FROM '.$this->db->dbprefix.'projects_users_received_ratings_feedbacks_as_sp where feedback_recived_by_sp_id = u.user_id AND sp_already_placed_feedback= "Y") as project_user_total_reviews')
                    ->from('users u')
                    ->join('users_details ud', 'ud.user_id = u.user_id', 'left')
                    ->where('u.user_id', $user[0]->user_id)
                    ->get()->row_array();
					$common_source_path = USERS_FTP_DIR . $user_detail['profile_name'];
					$user_avatar = USER_AVATAR;
					$source_path_avatar = $common_source_path . $user_avatar;
                    $user_detail['user_avatar_exist_status'] = false;
					
                    $user_profile_picture = CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_AVATAR.$user_detail['user_avatar'];
                    
                   ######## connectivity of remote server start #######
                    $this->load->library('ftp');
                    $config['ftp_hostname'] = CDN_FTP_SERVER_HOST_IP;
                    $config['ftp_username'] = FTP_USERNAME;
                    $config['ftp_password'] = FTP_PASSWORD;
                    $config['ftp_port'] = FTP_PORT;
                    $config['debug'] = TRUE;
                    $this->ftp->connect($config);
                    $users_ftp_dir = USERS_FTP_DIR;
                    $profile_folder = $user_detail['profile_name'];
                  
                    $avatarlist = $this->ftp->list_files($source_path_avatar);
                    $avatar_pic = $source_path_avatar . $user_detail['user_avatar'];
                                        
                    if (!empty($user_detail['user_avatar'])) {
                        $file_size = $this->ftp->get_filesize($avatar_pic);
                        if ($file_size != '-1') {
                            $user_detail['user_avatar_exist_status'] = true;
                        }
                    }else {                
                        if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
                            if($user_detail['gender'] == 'M'){
                                $user_profile_picture = URL . 'assets/images/avatar_default_male.png';
                            }if($user_detail['gender'] == 'F'){
                                $user_profile_picture = URL . 'assets/images/avatar_default_female.png';
                            }
                           
                        } else {
                            $user_profile_picture = URL . 'assets/images/avatar_default_company.png';                            
                        }
                    }
                    $user_detail['user_profile_picture'] = $user_profile_picture;

                    //end check avatar
                    $this->ftp->close();

                    if($user_detail['account_type'] == USER_PERSONAL_ACCOUNT_TYPE || ($user_detail['account_type'] == USER_COMPANY_ACCOUNT_TYPE && $user_detail['is_authorized_physical_person'] == 'Y')){
                        $user_detail['user_display_name'] = $user_detail['first_name'].' '.$user_detail['last_name'];
                    } else {
                        $user_detail['user_display_name'] = $user_detail['company_name'];
                    }
                    $projects_notifications = [];
                    $this->load->model('newly_posted_projects_realtime_notifications/Newly_posted_projects_realtime_notifications_model');
                    $project_notification = $this->Newly_posted_projects_realtime_notifications_model->get_projects_notification_feed_detail_based_on_filter(0, $this->config->item('newly_posted_projects_realtime_notification_limit'), $user);
                    $user_activity_display_notification = $this->db->from('users_activity_log_tracking')->where('user_id', $user[0]->user_id)->order_by('activity_log_record_time', 'desc')->limit($this->config->item('activity_notification_limit'))->get()->result_array();

                    $channel_unread_messages_count = $this->db->select('id')->from('users_contacts_tracking')->where(['contact_initiated_by'=> $user_detail['user_id'], 'channel_unread_messages_count !=' => 0, 'is_blocked' => 'no'])->group_by('contact_requested_to')->count_all_results();
                    //pre($user_detail);
                    
                    $this->load->vars([
                                        'user_data'=>$user_detail, 
                                        'project_notifications' => $projects_notifications,
                                        'project_notification' => $project_notification['data'],
                                        'user_activity_display_notification' => $user_activity_display_notification, 
                                        'user_log_id' => $this->session->userdata('user_log_id'),
                                        'get_in_contact_requests' => $get_in_contact_requests['data'],
                                        'get_in_contact_unseen_pending_request_count' => $this->Chat_model->get_user_unseen_pending_get_in_contact_requests_count(),
                                        'channel_unread_messages_count' => $channel_unread_messages_count
                                    ]);
                }

                
                
                $this->load->model ('user/user_model', '_api_user_model');
                $this->_api_user_model->db->update ('users', ['online' => 1], ['user_id' => $this->session->userdata ('user')[0]->user_id]);

            } 
	}
	
	public function __get($class) 
	{
		return CI::$APP->$class;
	}
	
	public function checkCurrentUser ()
    {
        if (validate_session())
        {
            redirect (VPATH . $this->config->item('dashboard_page_url'));
        }
    }
}